<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MedicineSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // Common Pain Relievers
            [
                'medicine_code' => 'MED-001',
                'name' => 'Paracetamol 500mg',
                'generic_name' => 'Acetaminophen',
                'brand_name' => 'Tylenol',
                'category' => 'Analgesic',
                'dosage_form' => 'tablet',
                'strength' => '500',
                'unit' => 'mg',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 2.50,
                'selling_price' => 5.00,
                'requires_prescription' => 0,
                'description' => 'Pain reliever and fever reducer',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'medicine_code' => 'MED-002',
                'name' => 'Ibuprofen 400mg',
                'generic_name' => 'Ibuprofen',
                'brand_name' => 'Advil',
                'category' => 'NSAID',
                'dosage_form' => 'tablet',
                'strength' => '400',
                'unit' => 'mg',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 3.00,
                'selling_price' => 6.00,
                'requires_prescription' => 0,
                'description' => 'Anti-inflammatory pain reliever',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Antibiotics
            [
                'medicine_code' => 'MED-003',
                'name' => 'Amoxicillin 500mg',
                'generic_name' => 'Amoxicillin',
                'brand_name' => 'Amoxil',
                'category' => 'Antibiotic',
                'dosage_form' => 'capsule',
                'strength' => '500',
                'unit' => 'mg',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 8.00,
                'selling_price' => 15.00,
                'requires_prescription' => 1,
                'description' => 'Broad-spectrum antibiotic for bacterial infections',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'medicine_code' => 'MED-004',
                'name' => 'Azithromycin 500mg',
                'generic_name' => 'Azithromycin',
                'brand_name' => 'Zithromax',
                'category' => 'Antibiotic',
                'dosage_form' => 'tablet',
                'strength' => '500',
                'unit' => 'mg',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 12.00,
                'selling_price' => 25.00,
                'requires_prescription' => 1,
                'description' => 'Macrolide antibiotic for respiratory and skin infections',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Antacids
            [
                'medicine_code' => 'MED-005',
                'name' => 'Omeprazole 20mg',
                'generic_name' => 'Omeprazole',
                'brand_name' => 'Prilosec',
                'category' => 'Antacid',
                'dosage_form' => 'capsule',
                'strength' => '20',
                'unit' => 'mg',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 5.00,
                'selling_price' => 10.00,
                'requires_prescription' => 0,
                'description' => 'Proton pump inhibitor for acid reflux',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Cough & Cold
            [
                'medicine_code' => 'MED-006',
                'name' => 'Dextromethorphan Syrup',
                'generic_name' => 'Dextromethorphan',
                'brand_name' => 'Robitussin',
                'category' => 'Cough Suppressant',
                'dosage_form' => 'syrup',
                'strength' => '15',
                'unit' => 'mg/5ml',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 45.00,
                'selling_price' => 80.00,
                'requires_prescription' => 0,
                'description' => 'Cough suppressant syrup',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Vitamins
            [
                'medicine_code' => 'MED-007',
                'name' => 'Multivitamin Tablet',
                'generic_name' => 'Multivitamin',
                'brand_name' => 'Centrum',
                'category' => 'Vitamin',
                'dosage_form' => 'tablet',
                'strength' => '1',
                'unit' => 'tablet',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 3.50,
                'selling_price' => 7.00,
                'requires_prescription' => 0,
                'description' => 'Daily multivitamin supplement',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'medicine_code' => 'MED-008',
                'name' => 'Vitamin C 1000mg',
                'generic_name' => 'Ascorbic Acid',
                'brand_name' => 'Cebion',
                'category' => 'Vitamin',
                'dosage_form' => 'tablet',
                'strength' => '1000',
                'unit' => 'mg',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 2.00,
                'selling_price' => 4.00,
                'requires_prescription' => 0,
                'description' => 'Vitamin C supplement for immune support',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Antihistamines
            [
                'medicine_code' => 'MED-009',
                'name' => 'Loratadine 10mg',
                'generic_name' => 'Loratadine',
                'brand_name' => 'Claritin',
                'category' => 'Antihistamine',
                'dosage_form' => 'tablet',
                'strength' => '10',
                'unit' => 'mg',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 4.00,
                'selling_price' => 8.00,
                'requires_prescription' => 0,
                'description' => 'Non-drowsy antihistamine for allergies',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            
            // Topical
            [
                'medicine_code' => 'MED-010',
                'name' => 'Hydrocortisone Cream 1%',
                'generic_name' => 'Hydrocortisone',
                'brand_name' => 'Cortizone',
                'category' => 'Topical',
                'dosage_form' => 'cream',
                'strength' => '1',
                'unit' => '%',
                'manufacturer' => 'Generic Pharma',
                'supplier' => 'Medical Supplies Co.',
                'purchase_price' => 6.00,
                'selling_price' => 12.00,
                'requires_prescription' => 0,
                'description' => 'Topical corticosteroid for skin inflammation',
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $builder = $this->db->table('medicines');
        foreach ($data as $row) {
            // Check by unique medicine_code to avoid duplicates
            $exists = $builder->where('medicine_code', $row['medicine_code'])->get()->getFirstRow('array');
            if ($exists) {
                // Update if already present
                $builder->where('id', $exists['id'])->update([
                    'name' => $row['name'],
                    'generic_name' => $row['generic_name'],
                    'brand_name' => $row['brand_name'],
                    'category' => $row['category'],
                    'dosage_form' => $row['dosage_form'],
                    'strength' => $row['strength'],
                    'unit' => $row['unit'],
                    'purchase_price' => $row['purchase_price'],
                    'selling_price' => $row['selling_price'],
                    'description' => $row['description'],
                    'is_active' => $row['is_active'],
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $builder->insert($row);
            }
        }
    }
}

