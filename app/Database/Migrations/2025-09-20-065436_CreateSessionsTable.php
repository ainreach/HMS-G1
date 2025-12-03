<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRoomsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'room_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
            ],
            'room_type' => [
                'type'       => 'ENUM',
                'constraint' => ['general', 'private', 'icu', 'pediatrics', 'maternity', 'emergency'],
                'default'    => 'general',
            ],
            'floor' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'capacity' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
            ],
            'current_occupancy' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['available', 'occupied', 'maintenance', 'reserved'],
                'default'    => 'available',
            ],
            'rate_per_day' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'null'       => true,
            ],
            'features' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['room_number', 'branch_id']);
        $this->forge->addKey('room_type');
        $this->forge->addKey('status');
        $this->forge->addKey('branch_id');
        $this->forge->createTable('rooms');

        // Insert initial data
        $this->db->table('rooms')->insertBatch([
            [
                'room_number' => '101',
                'room_type' => 'general',
                'floor' => 1,
                'capacity' => 2,
                'rate_per_day' => 1500.00,
                'features' => 'Basic amenities, shared bathroom',
                'branch_id' => 1,
            ],
            [
                'room_number' => '102',
                'room_type' => 'general',
                'floor' => 1,
                'capacity' => 2,
                'rate_per_day' => 1500.00,
                'features' => 'Basic amenities, shared bathroom',
                'branch_id' => 1,
            ],
            [
                'room_number' => '103',
                'room_type' => 'general',
                'floor' => 1,
                'capacity' => 2,
                'rate_per_day' => 1500.00,
                'features' => 'Basic amenities, shared bathroom',
                'branch_id' => 1,
            ],
            [
                'room_number' => '201',
                'room_type' => 'private',
                'floor' => 2,
                'capacity' => 1,
                'rate_per_day' => 3000.00,
                'features' => 'Private room, attached bathroom, TV',
                'branch_id' => 1,
            ],
            [
                'room_number' => '202',
                'room_type' => 'private',
                'floor' => 2,
                'capacity' => 1,
                'rate_per_day' => 3000.00,
                'features' => 'Private room, attached bathroom, TV',
                'branch_id' => 1,
            ],
            [
                'room_number' => '301',
                'room_type' => 'pediatrics',
                'floor' => 3,
                'capacity' => 2,
                'rate_per_day' => 2000.00,
                'features' => 'Child-friendly, play area',
                'branch_id' => 1,
            ],
            [
                'room_number' => '302',
                'room_type' => 'pediatrics',
                'floor' => 3,
                'capacity' => 2,
                'rate_per_day' => 2000.00,
                'features' => 'Child-friendly, play area',
                'branch_id' => 1,
            ],
            [
                'room_number' => '401',
                'room_type' => 'icu',
                'floor' => 4,
                'capacity' => 1,
                'rate_per_day' => 8000.00,
                'features' => 'Critical care equipment, 24/7 monitoring',
                'branch_id' => 1,
            ],
            [
                'room_number' => '402',
                'room_type' => 'icu',
                'floor' => 4,
                'capacity' => 1,
                'rate_per_day' => 8000.00,
                'features' => 'Critical care equipment, 24/7 monitoring',
                'branch_id' => 1,
            ],
            [
                'room_number' => '501',
                'room_type' => 'maternity',
                'floor' => 5,
                'capacity' => 2,
                'rate_per_day' => 3500.00,
                'features' => 'Maternity care, baby facilities',
                'branch_id' => 1,
            ],
            [
                'room_number' => '502',
                'room_type' => 'maternity',
                'floor' => 5,
                'capacity' => 2,
                'rate_per_day' => 3500.00,
                'features' => 'Maternity care, baby facilities',
                'branch_id' => 1,
            ],
            [
                'room_number' => '601',
                'room_type' => 'emergency',
                'floor' => 1,
                'capacity' => 1,
                'rate_per_day' => 5000.00,
                'features' => 'Emergency care, immediate attention',
                'branch_id' => 1,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('rooms');
    }
}
