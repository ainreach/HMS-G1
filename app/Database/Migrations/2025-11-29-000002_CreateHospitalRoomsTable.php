<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateHospitalRoomsTable extends Migration
{
    public function up()
    {
        // Check if table already exists
        if (!$this->db->tableExists('rooms')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'branch_id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'default' => 1,
                ],
                'room_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => false,
                ],
                'room_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['private', 'ward', 'icu', 'emergency', 'consultation', 'operating'],
                    'default' => 'private',
                ],
                'floor' => [
                    'type' => 'INT',
                    'constraint' => 3,
                    'default' => 1,
                ],
                'capacity' => [
                    'type' => 'INT',
                    'constraint' => 3,
                    'default' => 1,
                ],
                'current_occupancy' => [
                    'type' => 'INT',
                    'constraint' => 3,
                    'default' => 0,
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['available', 'occupied', 'maintenance', 'reserved'],
                    'default' => 'available',
                ],
                'rate_per_day' => [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => '0.00',
                ],
                'is_active' => [
                    'type' => 'BOOLEAN',
                    'default' => true,
                ],
                'created_at' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
                'updated_at' => [
                    'type' => 'DATETIME',
                    'null' => false,
                ],
            ]);
            
            $this->forge->addKey('id', true);
            $this->forge->addKey(['branch_id', 'room_number'], false, true);
            $this->forge->addKey(['branch_id', 'status'], false);
            $this->forge->createTable('rooms');
        }
    }

    public function down()
    {
        $this->forge->dropTable('rooms');
    }
}
