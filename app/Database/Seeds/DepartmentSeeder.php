<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Cardiology',
                'code' => 'CARD',
                'description' => 'Heart and cardiovascular system care',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Pediatrics',
                'code' => 'PED',
                'description' => 'Medical care for infants, children, and adolescents',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Orthopedics',
                'code' => 'ORTH',
                'description' => 'Bone, joint, and muscle care',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'General Medicine',
                'code' => 'GEN',
                'description' => 'General medical care and primary healthcare services',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Emergency Medicine',
                'code' => 'EMER',
                'description' => 'Emergency and urgent care services',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Surgery',
                'code' => 'SURG',
                'description' => 'Surgical procedures and operations',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Obstetrics and Gynecology',
                'code' => 'OBGYN',
                'description' => 'Women\'s health, pregnancy, and childbirth',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Neurology',
                'code' => 'NEURO',
                'description' => 'Nervous system and brain disorders',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Dermatology',
                'code' => 'DERM',
                'description' => 'Skin, hair, and nail conditions',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'name' => 'Radiology',
                'code' => 'RAD',
                'description' => 'Medical imaging and diagnostic radiology',
                'head_doctor_id' => null,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $builder = $this->db->table('departments');
        foreach ($data as $row) {
            // Check by unique code to avoid duplicates
            $exists = $builder->where('code', $row['code'])->get()->getFirstRow('array');
            if ($exists) {
                // Update name/description/active flags if already present
                $builder->where('id', $exists['id'])->update([
                    'name' => $row['name'],
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

