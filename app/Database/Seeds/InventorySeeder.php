<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InventorySeeder extends Seeder
{
    public function run()
    {
        // Check if tables exist
        if (!$this->db->tableExists('inventory')) {
            log_message('warning', 'inventory table does not exist. Please run the migration first.');
            return;
        }

        if (!$this->db->tableExists('medicines')) {
            log_message('warning', 'medicines table does not exist. Please run the migration first.');
            return;
        }

        // Get all active medicines
        $medicines = $this->db->table('medicines')
            ->where('is_active', 1)
            ->get()
            ->getResultArray();

        if (empty($medicines)) {
            log_message('info', 'No medicines found. Please seed medicines first.');
            return;
        }

        // Get default branch ID (first branch or create default)
        $branch = $this->db->table('branches')->get()->getFirstRow('array');
        $branchId = $branch['id'] ?? 1;

        // Default stock levels
        $stockLevels = [
            'Paracetamol 500mg' => ['quantity' => 500, 'min' => 50, 'max' => 1000, 'reorder' => 100],
            'Ibuprofen 400mg' => ['quantity' => 400, 'min' => 40, 'max' => 800, 'reorder' => 80],
            'Amoxicillin 500mg' => ['quantity' => 300, 'min' => 30, 'max' => 600, 'reorder' => 60],
            'Azithromycin 500mg' => ['quantity' => 250, 'min' => 25, 'max' => 500, 'reorder' => 50],
            'Omeprazole 20mg' => ['quantity' => 350, 'min' => 35, 'max' => 700, 'reorder' => 70],
            'Dextromethorphan Syrup' => ['quantity' => 100, 'min' => 10, 'max' => 200, 'reorder' => 20],
            'Multivitamin Tablet' => ['quantity' => 600, 'min' => 60, 'max' => 1200, 'reorder' => 120],
            'Vitamin C 1000mg' => ['quantity' => 450, 'min' => 45, 'max' => 900, 'reorder' => 90],
            'Loratadine 10mg' => ['quantity' => 300, 'min' => 30, 'max' => 600, 'reorder' => 60],
            'Hydrocortisone Cream 1%' => ['quantity' => 150, 'min' => 15, 'max' => 300, 'reorder' => 30],
        ];

        $builder = $this->db->table('inventory');
        $now = date('Y-m-d H:i:s');
        $futureDate = date('Y-m-d', strtotime('+2 years')); // Expiry date 2 years from now

        foreach ($medicines as $medicine) {
            $medicineName = $medicine['name'];
            
            // Check if inventory already exists for this medicine
            $existing = $builder
                ->where('medicine_id', $medicine['id'])
                ->where('branch_id', $branchId)
                ->get()
                ->getFirstRow('array');

            if ($existing) {
                // Update existing inventory if stock is 0
                if (($existing['quantity_in_stock'] ?? 0) == 0) {
                    $stockData = $stockLevels[$medicineName] ?? [
                        'quantity' => 200,
                        'min' => 20,
                        'max' => 500,
                        'reorder' => 40
                    ];

                    $builder->where('id', $existing['id'])->update([
                        'quantity_in_stock' => $stockData['quantity'],
                        'minimum_stock_level' => $stockData['min'],
                        'maximum_stock_level' => $stockData['max'],
                        'reorder_level' => $stockData['reorder'],
                        'updated_at' => $now,
                    ]);
                    log_message('info', "Updated stock for {$medicineName}");
                }
            } else {
                // Create new inventory entry
                $stockData = $stockLevels[$medicineName] ?? [
                    'quantity' => 200,
                    'min' => 20,
                    'max' => 500,
                    'reorder' => 40
                ];

                $inventoryData = [
                    'medicine_id' => $medicine['id'],
                    'branch_id' => $branchId,
                    'batch_number' => 'BATCH-' . strtoupper(substr($medicineName, 0, 3)) . '-' . date('Ymd'),
                    'expiry_date' => $futureDate,
                    'quantity_in_stock' => $stockData['quantity'],
                    'minimum_stock_level' => $stockData['min'],
                    'maximum_stock_level' => $stockData['max'],
                    'reorder_level' => $stockData['reorder'],
                    'location' => 'Pharmacy Storage',
                    'last_updated_by' => 1, // Default admin user
                    'created_at' => $now,
                    'updated_at' => $now,
                ];

                $builder->insert($inventoryData);
                log_message('info', "Added stock for {$medicineName}: {$stockData['quantity']} units");
            }
        }

        log_message('info', 'Inventory seeding completed.');
    }
}

