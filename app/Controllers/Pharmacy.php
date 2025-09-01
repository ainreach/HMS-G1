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
}
