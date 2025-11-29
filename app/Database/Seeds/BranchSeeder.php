<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BranchSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        $branches = [
            [
                'name' => 'Main Hospital',
                'code' => 'MAIN',
                'address' => '123 Medical Center Drive, Manila',
                'city' => 'Manila',
                'phone' => '+63-2-1234-5678',
                'email' => 'info@mainhospital.com',
                'is_main' => 1,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'North Branch',
                'code' => 'NORTH',
                'address' => '456 North Avenue, Quezon City',
                'city' => 'Quezon City',
                'phone' => '+63-2-8765-4321',
                'email' => 'north@mainhospital.com',
                'is_main' => 0,
                'is_active' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('branches')->insertBatch($branches);
    }
}
