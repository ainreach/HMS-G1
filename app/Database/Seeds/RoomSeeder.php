<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoomSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncation
        $this->db->disableForeignKeyChecks();

        // Truncate tables to clear old data
        $this->db->table('beds')->truncate();
        $this->db->table('rooms')->truncate();

        $now = date('Y-m-d H:i:s');
        $rooms = [];

        $roomTypes = [
            'private' => ['prefix' => 'PR', 'capacity' => 1, 'rate' => 3000.00],
            'semi-private' => ['prefix' => 'SP', 'capacity' => 2, 'rate' => 2000.00],
            'ward' => ['prefix' => 'WD', 'capacity' => 4, 'rate' => 1000.00],
        ];

        for ($floor = 1; $floor <= 3; $floor++) {
            for ($roomNum = 1; $roomNum <= 10; $roomNum++) {
                $roomKey = array_rand($roomTypes);
                $roomType = $roomTypes[$roomKey];
                $roomNumber = sprintf('%s-%d%02d', $roomType['prefix'], $floor, $roomNum);

                $isOccupied = (bool)rand(0, 1);
                $currentOccupancy = $isOccupied ? rand(1, $roomType['capacity']) : 0;
                $status = $currentOccupancy > 0 ? ($currentOccupancy < $roomType['capacity'] ? 'available' : 'occupied') : 'available';
                
                if ($currentOccupancy === $roomType['capacity']) {
                    $status = 'occupied';
                }

                $rooms[] = [
                    'branch_id' => 1,
                    'room_number' => $roomNumber,
                    'room_type' => $roomKey,
                    'floor' => $floor,
                    'capacity' => $roomType['capacity'],
                    'current_occupancy' => $currentOccupancy,
                    'status' => $status,
                    'rate_per_day' => $roomType['rate'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Add a few special rooms
        $rooms[] = [
            'branch_id' => 1, 'room_number' => 'ICU-01', 'room_type' => 'icu', 'floor' => 4, 'capacity' => 1, 'current_occupancy' => 0, 'status' => 'available', 'rate_per_day' => 8000.00, 'created_at' => $now, 'updated_at' => $now
        ];
        $rooms[] = [
            'branch_id' => 1, 'room_number' => 'MT-01', 'room_type' => 'maternity', 'floor' => 5, 'capacity' => 1, 'current_occupancy' => 0, 'status' => 'available', 'rate_per_day' => 4000.00, 'created_at' => $now, 'updated_at' => $now
        ];

        $this->db->table('rooms')->insertBatch($rooms);

        // Re-enable foreign key checks
        $this->db->enableForeignKeyChecks();
    }
}
