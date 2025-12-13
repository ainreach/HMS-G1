<?php
namespace App\Controllers;

class Pharmacy extends BaseController
{
    public function dashboard()
    {
        helper('url');
        $inventory = model('App\\Models\\InventoryModel');
        $meds      = model('App\\Models\\MedicineModel');

        // Aggregate inventory per medicine (on hand stock)
        $db = \Config\Database::connect();
        $builder = $db->table('inventory i')
            ->select('i.medicine_id, SUM(i.quantity_in_stock) AS on_hand, MIN(i.reorder_level) AS reorder_level')
            ->groupBy('i.medicine_id');
        $rows = $builder->get()->getResultArray();

        // Build map of medicine meta
        $medicineIds = array_map(fn($r)=>(int)$r['medicine_id'], $rows);
        $meta = [];
        if ($medicineIds) {
            $mrows = $meds->select('id, name')->whereIn('id', $medicineIds)->findAll();
            foreach ($mrows as $m) {
                $meta[(int)$m['id']] = [
                    'name' => $m['name'] ?: ('#'.$m['id']),
                    'sku'  => 'MED-' . str_pad($m['id'], 6, '0', STR_PAD_LEFT), // Generate SKU from ID
                ];
            }
        }

        // Compute low stock count and table data
        $lowStock = 0;
        $snapshot = [];
        foreach ($rows as $r) {
            $onHand = (int)$r['on_hand'];
            $reorder = max(0, (int)$r['reorder_level']);
            if ($reorder > 0 && $onHand <= $reorder) { $lowStock++; }
            $m = $meta[(int)$r['medicine_id']] ?? ['name'=>'#'.$r['medicine_id'], 'sku'=>''];
            $snapshot[] = [
                'item' => $m['name'],
                'sku'  => $m['sku'],
                'on_hand' => $onHand,
                'reorder' => $reorder,
            ];
        }

        // Sort snapshot by lowest stock first
        usort($snapshot, function($a,$b){ return $a['on_hand'] <=> $b['on_hand']; });
        $snapshot = array_slice($snapshot, 0, 15);

        // Prescriptions KPI placeholder (requires dispense log model)
        $prescriptionsToday = 0;

        return view('pharmacy/dashboard', [
            'lowStock' => $lowStock,
            'prescriptionsToday' => $prescriptionsToday,
            'snapshot' => $snapshot,
        ]);
    }

    public function inventory()
    {
        helper('url');
        $db = \Config\Database::connect();
        // Aggregate per medicine
        $summary = $db->table('inventory i')
            ->select('i.medicine_id, SUM(i.quantity_in_stock) AS on_hand, MIN(i.reorder_level) AS reorder_level')
            ->groupBy('i.medicine_id')
            ->get()->getResultArray();

        $medicineIds = array_map(fn($r)=>(int)$r['medicine_id'], $summary);
        $medMeta = [];
        if ($medicineIds) {
            $meds = model('App\\Models\\MedicineModel');
            $mrows = $meds->select('id, name')->whereIn('id', $medicineIds)->findAll();
            foreach ($mrows as $m) {
                $medMeta[(int)$m['id']] = [
                    'name' => $m['name'] ?: ('#'.$m['id']),
                    'sku'  => 'MED-' . str_pad($m['id'], 6, '0', STR_PAD_LEFT), // Generate SKU from ID
                ];
            }
        }

        $rows = [];
        foreach ($summary as $r) {
            $m = $medMeta[(int)$r['medicine_id']] ?? ['name'=>'#'.$r['medicine_id'], 'sku'=>''];
            $rows[] = [
                'medicine_id' => (int)$r['medicine_id'],
                'item'   => $m['name'],
                'sku'    => $m['sku'],
                'on_hand'=> (int)$r['on_hand'],
                'reorder'=> max(0, (int)$r['reorder_level']),
            ];
        }
        usort($rows, fn($a,$b)=> strcmp($a['item'],$b['item']));

        return view('pharmacy/inventory', [ 'rows' => $rows ]);
    }

    public function newDispense()
    {
        helper('url');
        $patients = model('App\\Models\\PatientModel')->orderBy('last_name','ASC')->findAll(1000);
        $medicines = model('App\\Models\\MedicineModel')->where('is_active', 1)->orderBy('name','ASC')->findAll(1000);
        return view('pharmacy/dispense_new', [
            'patients' => $patients,
            'medicines' => $medicines,
        ]);
    }

    public function storeDispense()
    {
        helper(['url','form']);
        $validation = \Config\Services::validation();
        $rules = [
            'medicine_id' => 'required|is_natural_no_zero',
            'patient_id'  => 'required|is_natural_no_zero',
            'quantity'    => 'required|is_natural_no_zero',
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->with('error', implode(' ', $validation->getErrors()))->withInput();
        }

        $medicineId = (int) $this->request->getPost('medicine_id');
        $quantity   = (int) $this->request->getPost('quantity');
        $patientId  = (int) $this->request->getPost('patient_id');

        $patient = model('App\\Models\\PatientModel')->find($patientId);
        $medicine = model('App\\Models\\MedicineModel')->find($medicineId);
        if (!$patient || !$medicine) {
            return redirect()->back()->with('error', 'Invalid patient or medicine.')->withInput();
        }

        $db = \Config\Database::connect();
        $db->transBegin();
        try {
            $inventory = new \App\Models\InventoryModel();
            $batches = $inventory->where('medicine_id', $medicineId)
                ->where('quantity_in_stock >', 0)
                ->orderBy('expiry_date', 'ASC')
                ->findAll();
            $remaining = $quantity;
            foreach ($batches as $batch) {
                if ($remaining <= 0) break;
                $take = min($remaining, (int) $batch['quantity_in_stock']);
                if ($take <= 0) continue;
                $inventory->update($batch['id'], [
                    'quantity_in_stock' => (int) $batch['quantity_in_stock'] - $take,
                    'last_updated_by' => (int) (session('user_id') ?: 0),
                ]);
                $remaining -= $take;
            }
            if ($remaining > 0) {
                // Not enough stock, rollback
                $db->transRollback();
                return redirect()->back()->with('error', 'Insufficient stock across batches.')->withInput();
            }
            $db->transCommit();
        } catch (\Throwable $e) {
            if ($db->transStatus()) { $db->transRollback(); }
            return redirect()->back()->with('error', 'Error dispensing: ' . $e->getMessage())->withInput();
        }

        return redirect()->to(site_url('dashboard/pharmacist'))->with('success', 'Medicine dispensed successfully.');
    }

    public function prescriptions()
    {
        helper('url');
        $prescriptionModel = model('App\\Models\\PrescriptionModel');
        
        // Get status filter from query string, default to 'pending'
        $status = $this->request->getGet('status') ?? 'pending';
        
        // Get counts for each status (include NULL as pending for backward compatibility)
        $db = \Config\Database::connect();
        
        // Use raw queries for accurate counts
        $pendingCount = $db->query("SELECT COUNT(*) as count FROM prescriptions WHERE COALESCE(status, 'pending') = 'pending' AND deleted_at IS NULL")->getRowArray();
        $approvedCount = $db->query("SELECT COUNT(*) as count FROM prescriptions WHERE status = 'approved' AND deleted_at IS NULL")->getRowArray();
        $preparedCount = $db->query("SELECT COUNT(*) as count FROM prescriptions WHERE status = 'prepared' AND deleted_at IS NULL")->getRowArray();
        $dispensedCount = $db->query("SELECT COUNT(*) as count FROM prescriptions WHERE status = 'dispensed' AND deleted_at IS NULL")->getRowArray();
        $administeredCount = $db->query("SELECT COUNT(*) as count FROM prescriptions WHERE status = 'administered' AND deleted_at IS NULL")->getRowArray();
        
        $counts = [
            'pending' => (int)($pendingCount['count'] ?? 0),
            'approved' => (int)($approvedCount['count'] ?? 0),
            'prepared' => (int)($preparedCount['count'] ?? 0),
            'dispensed' => (int)($dispensedCount['count'] ?? 0),
            'administered' => (int)($administeredCount['count'] ?? 0),
        ];
        
        // Get prescriptions for the selected status
        // Handle NULL status as 'pending' for backward compatibility using raw query
        if ($status === 'pending') {
            $prescriptions = $db->query("
                SELECT 
                    p.*,
                    CONCAT(pat.first_name, ' ', pat.last_name) as patient_name,
                    CONCAT(u.first_name, ' ', u.last_name) as doctor_name,
                    p.medication as medicine_name,
                    p.start_date as prescription_date
                FROM prescriptions p
                JOIN patients pat ON pat.id = p.patient_id
                JOIN users u ON u.id = p.doctor_id
                WHERE COALESCE(p.status, 'pending') = 'pending'
                AND p.deleted_at IS NULL
                ORDER BY p.start_date ASC
                LIMIT 100
            ")->getResultArray();
        } else {
            $prescriptions = $prescriptionModel
                ->select('prescriptions.*, 
                         CONCAT(patients.first_name, " ", patients.last_name) as patient_name,
                         CONCAT(users.first_name, " ", users.last_name) as doctor_name,
                         prescriptions.medication as medicine_name,
                         prescriptions.start_date as prescription_date')
                ->join('patients', 'patients.id = prescriptions.patient_id')
                ->join('users', 'users.id = prescriptions.doctor_id')
                ->where('prescriptions.status', $status)
                ->orderBy('prescriptions.start_date', 'ASC')
                ->findAll(100);
        }

        return view('pharmacy/prescriptions_queue', [
            'prescriptions' => $prescriptions,
            'currentStatus' => $status,
            'counts' => $counts,
        ]);
    }

    public function viewPrescription($prescriptionId)
    {
        helper('url');
        $db = \Config\Database::connect();
        
        // Get prescription with patient and doctor info
        $prescription = $db->query("
            SELECT 
                p.*,
                CONCAT(pat.first_name, ' ', pat.last_name) as patient_name,
                pat.patient_id as patient_code,
                pat.phone as patient_phone,
                pat.email as patient_email,
                CONCAT(u.first_name, ' ', u.last_name) as doctor_name,
                u.username as doctor_username,
                p.medication as medicine_name,
                p.start_date as prescription_date
            FROM prescriptions p
            LEFT JOIN patients pat ON pat.id = p.patient_id
            LEFT JOIN users u ON u.id = p.doctor_id
            WHERE p.id = ?
            AND p.deleted_at IS NULL
        ", [$prescriptionId])->getRowArray();
        
        if (!$prescription) {
            return redirect()->to(site_url('pharmacy/prescriptions'))
                ->with('error', 'Prescription not found.');
        }
        
        // Parse medications_prescribed if it's JSON (from medical records)
        // Check if this prescription is linked to a medical record
        $medications = null;
        $medicalRecord = $db->query("
            SELECT medications_prescribed 
            FROM medical_records 
            WHERE id = (SELECT medical_record_id FROM prescriptions WHERE id = ? LIMIT 1)
            LIMIT 1
        ", [$prescriptionId])->getRowArray();
        
        if (!empty($medicalRecord['medications_prescribed'])) {
            $decoded = json_decode($medicalRecord['medications_prescribed'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $medications = $decoded;
            }
        }
        
        return view('pharmacy/prescription_view', [
            'prescription' => $prescription,
            'medications' => $medications,
        ]);
    }

    public function updatePrescriptionStatus($prescriptionId)
    {
        helper(['url', 'form']);
        $prescriptionModel = model('App\\Models\\PrescriptionModel');
        
        $prescription = $prescriptionModel->find($prescriptionId);
        if (!$prescription) {
            return redirect()->to(site_url('pharmacy/prescriptions'))->with('error', 'Prescription not found.');
        }
        
        $newStatus = $this->request->getPost('status');
        $validStatuses = ['pending', 'approved', 'prepared', 'dispensed', 'administered', 'cancelled'];
        
        if (!in_array($newStatus, $validStatuses)) {
            return redirect()->back()->with('error', 'Invalid status.');
        }
        
        $prescriptionModel->update($prescriptionId, [
            'status' => $newStatus,
        ]);
        
        $statusMessages = [
            'approved' => 'Prescription approved successfully.',
            'prepared' => 'Prescription marked as prepared.',
            'dispensed' => 'Prescription marked as dispensed.',
            'administered' => 'Prescription marked as administered.',
        ];
        
        $message = $statusMessages[$newStatus] ?? 'Prescription status updated successfully.';
        
        return redirect()->to(site_url('pharmacy/prescriptions?status=' . $newStatus))->with('success', $message);
    }

    public function dispenseFromPrescription($prescriptionId)
    {
        helper('url');
        $prescriptionModel = model('App\\Models\\PrescriptionModel');
        $patientModel = model('App\\Models\\PatientModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        $inventoryModel = model('App\\Models\\InventoryModel');
        
        $prescription = $prescriptionModel
            ->select('prescriptions.*, 
                     CONCAT(patients.first_name, " ", patients.last_name) as patient_name,
                     patients.id as patient_id,
                     CONCAT(users.first_name, " ", users.last_name) as doctor_name')
            ->join('patients', 'patients.id = prescriptions.patient_id')
            ->join('users', 'users.id = prescriptions.doctor_id')
            ->where('prescriptions.id', $prescriptionId)
            ->first();
        
        if (!$prescription) {
            return redirect()->to(site_url('pharmacy/prescriptions'))->with('error', 'Prescription not found.');
        }
        
        // Get patient details
        $patient = $patientModel->find($prescription['patient_id']);
        
        // Try to find medicine by name (medication field)
        $medicine = $medicineModel
            ->where('name', $prescription['medication'])
            ->orLike('name', $prescription['medication'])
            ->first();
        
        // Get available stock for the medicine
        $availableStock = 0;
        if ($medicine) {
            $stock = $inventoryModel
                ->where('medicine_id', $medicine['id'])
                ->selectSum('quantity_in_stock')
                ->first();
            $availableStock = (int)($stock['quantity_in_stock'] ?? 0);
        }
        
        // Get all medicines with their actual stock from inventory
        $allMedicines = $medicineModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
        $medicinesWithStock = [];
        foreach ($allMedicines as $med) {
            $stockResult = $inventoryModel
                ->where('medicine_id', $med['id'])
                ->selectSum('quantity_in_stock')
                ->first();
            $totalStock = (int)($stockResult['quantity_in_stock'] ?? 0);
            
            $medicinesWithStock[] = [
                'id' => $med['id'],
                'name' => $med['name'],
                'stock' => $totalStock,
            ];
        }
        
        return view('pharmacy/dispense_from_prescription', [
            'prescription' => $prescription,
            'patient' => $patient,
            'medicine' => $medicine,
            'availableStock' => $availableStock,
            'medicinesWithStock' => $medicinesWithStock,
        ]);
    }

    public function processDispenseFromPrescription($prescriptionId)
    {
        helper(['url', 'form']);
        $prescriptionModel = model('App\\Models\\PrescriptionModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        $inventoryModel = model('App\\Models\\InventoryModel');
        $dispensingModel = model('App\\Models\\DispensingModel');
        
        $prescription = $prescriptionModel->find($prescriptionId);
        if (!$prescription) {
            return redirect()->to(site_url('pharmacy/prescriptions'))->with('error', 'Prescription not found.');
        }
        
        $medicineId = (int) $this->request->getPost('medicine_id');
        $quantity = (int) $this->request->getPost('quantity');
        $notes = $this->request->getPost('notes');
        
        if (!$medicineId || $quantity <= 0) {
            return redirect()->back()->with('error', 'Medicine and quantity are required.')->withInput();
        }
        
        $medicine = $medicineModel->find($medicineId);
        if (!$medicine) {
            return redirect()->back()->with('error', 'Medicine not found.')->withInput();
        }
        
        // Check stock availability
        $db = \Config\Database::connect();
        $db->transBegin();
        try {
            $inventory = new \App\Models\InventoryModel();
            $batches = $inventory->where('medicine_id', $medicineId)
                ->where('quantity_in_stock >', 0)
                ->orderBy('expiry_date', 'ASC')
                ->findAll();
            
            $remaining = $quantity;
            foreach ($batches as $batch) {
                if ($remaining <= 0) break;
                $take = min($remaining, (int) $batch['quantity_in_stock']);
                if ($take <= 0) continue;
                $inventory->update($batch['id'], [
                    'quantity_in_stock' => (int) $batch['quantity_in_stock'] - $take,
                    'last_updated_by' => session('user_id') ?: 0,
                ]);
                $remaining -= $take;
            }
            
            if ($remaining > 0) {
                $db->transRollback();
                return redirect()->back()->with('error', 'Insufficient stock. Available: ' . ($quantity - $remaining) . ' units.')->withInput();
            }
            
            // Create dispensing record
            $dispensingModel->insert([
                'patient_id' => $prescription['patient_id'],
                'medicine_id' => $medicineId,
                'quantity' => $quantity,
                'dispensed_by' => session('user_id') ?: 0,
                'dispensed_at' => date('Y-m-d H:i:s'),
                'prescription_id' => $prescriptionId,
                'notes' => $notes,
            ]);
            
            // Update prescription status to dispensed
            $prescriptionModel->update($prescriptionId, [
                'status' => 'dispensed',
            ]);
            
            // AUTOMATIC BILLING: Add medication charge to patient's consolidated bill
            $billingModel = model('App\\Models\\BillingModel');
            $billingItemModel = model('App\\Models\\BillingItemModel');
            
            $patientId = $prescription['patient_id'];
            $medicinePrice = (float)($medicine['price'] ?? 0);
            $totalPrice = $medicinePrice * $quantity;
            
            if ($totalPrice > 0) {
                // Get or create active bill for this patient
                $activeBill = $billingModel->getOrCreateActiveBill($patientId, 1, session('user_id'));
                $billingId = $activeBill['id'];
                
                // Check if this medication from this prescription is already billed (avoid duplicate charges)
                $existingMedication = $billingItemModel
                    ->where('billing_id', $billingId)
                    ->where('item_type', 'medication')
                    ->where('item_name', $medicine['name'] ?? 'Medicine')
                    ->where('description LIKE', '%Prescription ID: ' . $prescriptionId . '%')
                    ->first();
                
                if (!$existingMedication) {
                    // Add medication item to the bill
                    $billingItemData = [
                        'billing_id' => $billingId,
                        'item_type' => 'medication',
                        'item_name' => $medicine['name'] ?? 'Medicine',
                        'description' => 'Prescription ID: ' . $prescriptionId . ' - Quantity: ' . $quantity . ($notes ? ' - ' . $notes : ''),
                        'quantity' => $quantity,
                        'unit_price' => $medicinePrice,
                        'total_price' => $totalPrice,
                    ];
                    $billingItemModel->insert($billingItemData);
                    
                    // Recalculate bill totals
                    $billingModel->recalculateBill($billingId);
                }
            }
            
            $db->transCommit();
            
            return redirect()->to(site_url('pharmacy/prescriptions'))->with('success', 'Medicine dispensed successfully from prescription.');
        } catch (\Throwable $e) {
            if ($db->transStatus()) { $db->transRollback(); }
            return redirect()->back()->with('error', 'Error dispensing: ' . $e->getMessage())->withInput();
        }
    }

    public function dispensingHistory()
    {
        helper('url');
        // This would require a dispensing_log table to track actual dispensing
        // For now, we'll show a placeholder
        $dispensingHistory = [];
        
        return view('pharmacy/dispensing_history', ['dispensingHistory' => $dispensingHistory]);
    }

    
    public function medicines()
    {
        helper('url');
        $model = model('App\\Models\\MedicineModel');
        
        $perPage = 20;
        $page = (int)($this->request->getGet('page') ?? 1);
        $offset = ($page - 1) * $perPage;
        
        $search = $this->request->getGet('search');
        
        if ($search) {
            $medicines = $model->like('name', $search)
                             ->orLike('id', $search)
                             ->orderBy('name', 'ASC')
                             ->findAll($perPage, $offset);
            $total = $model->like('name', $search)
                         ->orLike('id', $search)
                         ->countAllResults();
        } else {
            $medicines = $model->orderBy('name', 'ASC')
                             ->findAll($perPage, $offset);
            $total = $model->countAllResults();
        }
        
        $data = [
            'medicines' => $medicines,
            'total' => $total,
            'perPage' => $perPage,
            'page' => $page,
            'search' => $search
        ];
        
        return view('pharmacy/medicines_list', $data);
    }
    
    public function medicineForm($id = null)
    {
        helper('form');
        $model = model('App\\Models\\MedicineModel');
        $medicine = null;
        
        if ($id) {
            $medicine = $model->find($id);
            if (!$medicine) {
                return redirect()->to(site_url('pharmacy/medicines'))->with('error', 'Medicine not found.');
            }
        }
        
        return view('pharmacy/medicine_form', [
            'medicine' => $medicine
        ]);
    }
    
    public function saveMedicine($id = null)
    {
        helper(['form', 'url']);
        $model = model('App\\Models\\MedicineModel');
        
        // Validation rules
        $rules = [
            'name'         => 'required|min_length[3]|max_length[255]',
            'purchase_price' => 'required|numeric',
            'selling_price'  => 'required|numeric',
            'stock_quantity' => 'required|integer',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'name'           => $this->request->getPost('name'),
            'unit'           => $this->request->getPost('unit'),
            'price'          => $this->request->getPost('purchase_price'),
            'retail_price'   => $this->request->getPost('selling_price'),
            'stock'          => $this->request->getPost('stock_quantity'),
            'expiration_date'=> $this->request->getPost('expiry_date'),
            'is_active'      => $this->request->getPost('is_active') ? 1 : 0,
        ];
        
        
        // Save to database
        $db = \Config\Database::connect();
        $db->transBegin();
        
        try {
            if ($id) {
                $model->update($id, $data);
                $message = 'Medicine updated successfully.';
            } else {
                $model->insert($data);
                $medicineId = $model->getInsertID();
                $message = 'Medicine added successfully.';
                
                // Automatically create inventory record for new medicine
                if ($medicineId && isset($data['stock']) && $data['stock'] > 0) {
                    $inventoryModel = model('App\\Models\\InventoryModel');
                    $inventoryData = [
                        'medicine_id' => $medicineId,
                        'batch_number' => 'INIT-' . date('YmdHis'),
                        'expiry_date' => $data['expiration_date'] ?? null,
                        'quantity_in_stock' => (int)$data['stock'],
                        'reorder_level' => max(10, (int)($data['stock'] * 0.2)), // 20% of initial stock as reorder level
                        'location' => 'Main Store',
                        'last_updated_by' => session('user_id') ?: 0,
                    ];
                    $inventoryModel->insert($inventoryData);
                }
            }
            
            $db->transCommit();
            return redirect()->to(site_url('pharmacy/medicines'))->with('success', $message);
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Error saving medicine: ' . $e->getMessage());
        }
    }
    
    public function deleteMedicine($id)
    {
        $model = model('App\\Models\\MedicineModel');
        $medicine = $model->find($id);
        
        if (!$medicine) {
            return redirect()->back()->with('error', 'Medicine not found.');
        }
        
        
        $model->delete($id);
        return redirect()->to(site_url('pharmacy/medicines'))->with('success', 'Medicine deleted successfully.');
    }
    
    public function patientSearch()
    {
        helper('url');
        $searchTerm = $this->request->getGet('q');
        $patientModel = model('App\Models\PatientModel');
        
        $patients = $patientModel
            ->groupStart()
                ->like('first_name', $searchTerm)
                ->orLike('last_name', $searchTerm)
                ->orLike('patient_id', $searchTerm)
            ->groupEnd()
            ->orderBy('last_name', 'ASC')
            ->findAll(20);

        return $this->response->setJSON($patients);
    }

    public function medicineSearch()
    {
        helper('url');
        $searchTerm = $this->request->getGet('q');
        $medicineModel = model('App\\Models\\MedicineModel');
        
        $medicines = $medicineModel
            ->where('is_active', 1)
            ->groupStart()
                ->like('name', $searchTerm)
                ->orLike('id', $searchTerm)
            ->groupEnd()
            ->orderBy('name', 'ASC')
            ->findAll(20);

        return $this->response->setJSON($medicines);
    }

    public function lowStockAlerts()
    {
        helper('url');
        $inventoryModel = model('App\\Models\\InventoryModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        
        $db = \Config\Database::connect();
        $builder = $db->table('inventory i')
            ->select('i.medicine_id, SUM(i.quantity_in_stock) AS on_hand, MIN(i.reorder_level) AS reorder_level')
            ->groupBy('i.medicine_id')
            ->having('on_hand <= reorder_level')
            ->having('reorder_level >', 0);
        $rows = $builder->get()->getResultArray();

        $alerts = [];
        foreach ($rows as $row) {
            $medicine = $medicineModel->find($row['medicine_id']);
            if ($medicine) {
                $alerts[] = [
                    'medicine' => $medicine,
                    'on_hand' => $row['on_hand'],
                    'reorder_level' => $row['reorder_level'],
                ];
            }
        }

        return view('pharmacy/low_stock_alerts', ['alerts' => $alerts]);
    }

    public function addStock()
    {
        helper('url');
        $medicineModel = model('App\\Models\\MedicineModel');
        $medicines = $medicineModel->where('is_active', 1)->orderBy('name', 'ASC')->findAll(100);
        
        return view('pharmacy/add_stock', ['medicines' => $medicines]);
    }

    public function storeStock()
    {
        helper(['url', 'form']);
        $inventoryModel = model('App\\Models\\InventoryModel');
        
        $data = [
            'medicine_id' => (int) $this->request->getPost('medicine_id'),
            'batch_number' => $this->request->getPost('batch_number'),
            'expiry_date' => $this->request->getPost('expiry_date'),
            'quantity_in_stock' => (int) $this->request->getPost('quantity'),
            'reorder_level' => (int) $this->request->getPost('reorder_level'),
            'location' => $this->request->getPost('location') ?: 'Main Store',
            'last_updated_by' => session('user_id') ?: 0,
        ];

        $inventoryModel->insert($data);
        return redirect()->to(site_url('pharmacy/inventory'))->with('success', 'Stock added successfully.');
    }

    public function prescriptionFulfillment()
    {
        helper('url');
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        $prescriptions = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.username as doctor_name')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->join('users', 'users.id = medical_records.doctor_id')
            ->where('medical_records.medications_prescribed IS NOT NULL')
            ->where('medical_records.medications_prescribed !=', '')
            ->where('medical_records.visit_date >=', date('Y-m-d', strtotime('-7 days')))
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(20);

        return view('pharmacy/prescription_fulfillment', ['prescriptions' => $prescriptions]);
    }

    public function medicineExpiry()
    {
        helper('url');
        $inventoryModel = model('App\\Models\\InventoryModel');
        $medicineModel = model('App\\Models\\MedicineModel');
        
        $expiringSoon = $inventoryModel
            ->where('expiry_date <=', date('Y-m-d', strtotime('+30 days')))
            ->where('expiry_date >=', date('Y-m-d'))
            ->orderBy('expiry_date', 'ASC')
            ->findAll(20);

        $medicines = [];
        foreach ($expiringSoon as $item) {
            $medicine = $medicineModel->find($item['medicine_id']);
            if ($medicine) {
                $medicines[] = [
                    'inventory' => $item,
                    'medicine' => $medicine,
                ];
            }
        }

        return view('pharmacy/medicine_expiry', ['medicines' => $medicines]);
    }
}
