<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSurgeriesTable extends Migration
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
            'surgery_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'surgery_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'surgery_date' => [
                'type' => 'DATE',
            ],
            'surgery_time' => [
                'type' => 'TIME',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'in_progress', 'completed', 'cancelled', 'postponed'],
                'default'    => 'scheduled',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->addKey('patient_id');
        $this->forge->addKey('doctor_id');
        $this->forge->addKey('surgery_date');
        $this->forge->addKey('status');
        $this->forge->createTable('surgeries');
    }

    public function down()
    {
        $this->forge->dropTable('surgeries');
    }
}

