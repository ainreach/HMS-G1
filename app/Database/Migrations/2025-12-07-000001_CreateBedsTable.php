<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBedsTable extends Migration
{
    public function up()
    {
        // Check if table already exists
        if (!$this->db->tableExists('beds')) {
            $this->forge->addField([
                'id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'room_id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => false,
                ],
                'bed_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => '20',
                    'null' => false,
                ],
                'bed_type' => [
                    'type' => 'ENUM',
                    'constraint' => ['standard', 'electric', 'icu', 'pediatric'],
                    'default' => 'standard',
                ],
                'status' => [
                    'type' => 'ENUM',
                    'constraint' => ['available', 'occupied', 'maintenance', 'reserved'],
                    'default' => 'available',
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
            $this->forge->addKey(['room_id', 'bed_number'], false, true);
            $this->forge->addKey(['room_id', 'status'], false);
            
            // Add foreign key constraint
            $this->forge->addForeignKey('room_id', 'rooms', 'id', 'CASCADE', 'CASCADE');
            
            $this->forge->createTable('beds');
        }
    }

    public function down()
    {
        if ($this->db->tableExists('beds')) {
            $this->forge->dropTable('beds');
        }
    }
}

