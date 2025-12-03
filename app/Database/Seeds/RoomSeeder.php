<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        $now = date('Y-m-d H:i:s');
        
        $rooms = [
            [
                'branch_id' => 1,
                'room_number' => '101',
                'room_type' => 'private',
                'floor' => 1,
                'capacity' => 1,
                'current_occupancy' => 0,
                'status' => 'available',
                'rate_per_day' => 1500.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_id' => 1,
                'room_number' => '102',
                'room_type' => 'private',
                'floor' => 1,
                'capacity' => 1,
                'current_occupancy' => 1,
                'status' => 'occupied',
                'rate_per_day' => 1500.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_id' => 1,
                'room_number' => '201',
                'room_type' => 'ward',
                'floor' => 2,
                'capacity' => 4,
                'current_occupancy' => 2,
                'status' => 'occupied',
                'rate_per_day' => 800.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_id' => 1,
                'room_number' => 'ICU-01',
                'room_type' => 'icu',
                'floor' => 3,
                'capacity' => 1,
                'current_occupancy' => 0,
                'status' => 'available',
                'rate_per_day' => 5000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_id' => 1,
                'room_number' => 'ER-01',
                'room_type' => 'emergency',
                'floor' => 1,
                'capacity' => 1,
                'current_occupancy' => 0,
                'status' => 'available',
                'rate_per_day' => 2000.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'branch_id' => 1,
                'room_number' => 'CONS-01',
                'room_type' => 'consultation',
                'floor' => 2,
                'capacity' => 1,
                'current_occupancy' => 0,
                'status' => 'available',
                'rate_per_day' => 500.00,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        $this->db->table('rooms')->insertBatch($rooms);
    }
}
