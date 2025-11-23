<?php
namespace App\Controllers;

class Pharmacy extends BaseController
{
    public function dashboard()
    {
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'pharmacist') {
            return redirect()->to(site_url('auth/login'))->with('error', 'You must be logged in as a pharmacist to access this page.');
        }
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
            $mrows = $meds->select('id, medicine_code, name')->whereIn('id', $medicineIds)->findAll();
            foreach ($mrows as $m) {
                $meta[(int)$m['id']] = [
                    'name' => $m['name'] ?: ('#'.$m['id']),
                    'sku'  => $m['medicine_code'] ?: '',
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
            $mrows = $meds->select('id, medicine_code, name')->whereIn('id', $medicineIds)->findAll();
            foreach ($mrows as $m) {
                $medMeta[(int)$m['id']] = [
                    'name' => $m['name'] ?: ('#'.$m['id']),
                    'sku'  => $m['medicine_code'] ?: '',
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
        $medicalRecordModel = model('App\\Models\\MedicalRecordModel');
        
        $prescriptions = $medicalRecordModel
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.first_name as doctor_first, users.last_name as doctor_last')
            ->join('patients', 'patients.id = medical_records.patient_id')
            ->join('users', 'users.id = medical_records.doctor_id')
            ->where('medical_records.medications_prescribed IS NOT NULL')
            ->where('medical_records.medications_prescribed !=', '')
            ->orderBy('medical_records.visit_date', 'DESC')
            ->findAll(50);

        return view('pharmacy/prescriptions', ['prescriptions' => $prescriptions]);
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
                             ->orLike('generic_name', $search)
                             ->orLike('medicine_code', $search)
                             ->orderBy('name', 'ASC')
                             ->findAll($perPage, $offset);
            $total = $model->like('name', $search)
                         ->orLike('generic_name', $search)
                         ->orLike('medicine_code', $search)
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
            'name' => 'required|min_length[3]|max_length[255]',
            'medicine_code' => 'required|is_unique[medicines.medicine_code,id,'.($id ?? '').']',
            'purchase_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'image' => [
                'rules' => 'max_size[image,2048]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]',
                'errors' => [
                    'max_size' => 'Image size should be less than 2MB',
                    'is_image' => 'Please upload a valid image file',
                    'mime_in' => 'Please upload a valid image (JPG, JPEG, PNG, or WebP)'
                ]
            ]
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        $data = [
            'medicine_code' => $this->request->getPost('medicine_code'),
            'name' => $this->request->getPost('name'),
            'generic_name' => $this->request->getPost('generic_name'),
            'brand_name' => $this->request->getPost('brand_name'),
            'category' => $this->request->getPost('category'),
            'dosage_form' => $this->request->getPost('dosage_form'),
            'strength' => $this->request->getPost('strength'),
            'unit' => $this->request->getPost('unit'),
            'manufacturer' => $this->request->getPost('manufacturer'),
            'supplier' => $this->request->getPost('supplier'),
            'purchase_price' => $this->request->getPost('purchase_price'),
            'selling_price' => $this->request->getPost('selling_price'),
            'requires_prescription' => $this->request->getPost('requires_prescription') ? 1 : 0,
            'description' => $this->request->getPost('description'),
            'side_effects' => $this->request->getPost('side_effects'),
            'contraindications' => $this->request->getPost('contraindications'),
            'storage_instructions' => $this->request->getPost('storage_instructions'),
            'is_active' => $this->request->getPost('is_active') ? 1 : 0,
        ];
        
        // Handle image upload
        $imageFile = $this->request->getFile('image');
        if ($imageFile && $imageFile->isValid() && !$imageFile->hasMoved()) {
            $newName = $imageFile->getRandomName();
            $imageFile->move(ROOTPATH . 'public/uploads/medicines', $newName);
            $data['image'] = $newName;
            
            // Delete old image if exists
            if ($id) {
                $oldMedicine = $model->find($id);
                if ($oldMedicine && !empty($oldMedicine['image'])) {
                    $oldImagePath = ROOTPATH . 'public/uploads/medicines/' . $oldMedicine['image'];
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }
            }
        } elseif ($this->request->getPost('remove_image') && $id) {
            // Remove existing image if requested
            $oldMedicine = $model->find($id);
            if ($oldMedicine && !empty($oldMedicine['image'])) {
                $oldImagePath = ROOTPATH . 'public/uploads/medicines/' . $oldMedicine['image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
                $data['image'] = null;
            }
        }
        
        // Save to database
        if ($id) {
            $model->update($id, $data);
            $message = 'Medicine updated successfully.';
        } else {
            $model->insert($data);
            $message = 'Medicine added successfully.';
        }
        
        return redirect()->to(site_url('pharmacy/medicines'))->with('success', $message);
    }
    
    public function deleteMedicine($id)
    {
        $model = model('App\\Models\\MedicineModel');
        $medicine = $model->find($id);
        
        if (!$medicine) {
            return redirect()->back()->with('error', 'Medicine not found.');
        }
        
        // Delete image if exists
        if (!empty($medicine['image'])) {
            $imagePath = ROOTPATH . 'public/uploads/medicines/' . $medicine['image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $model->delete($id);
        return redirect()->to(site_url('pharmacy/medicines'))->with('success', 'Medicine deleted successfully.');
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
                ->orLike('generic_name', $searchTerm)
                ->orLike('medicine_code', $searchTerm)
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
            ->select('medical_records.*, patients.first_name, patients.last_name, patients.patient_id as patient_code, users.first_name as doctor_first, users.last_name as doctor_last')
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
