<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAppointmentsTable extends Migration
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
            'appointment_number' => [
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
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'appointment_date' => [
                'type' => 'DATE',
            ],
            'appointment_time' => [
                'type' => 'TIME',
            ],
            'duration' => [
                'type'       => 'INT',
                'constraint' => 3,
                'default'    => 30,
                'comment'    => 'Duration in minutes',
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['consultation', 'follow_up', 'emergency', 'surgery', 'checkup'],
                'default'    => 'consultation',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['scheduled', 'confirmed', 'in_progress', 'completed', 'cancelled', 'no_show'],
                'default'    => 'scheduled',
            ],
            'reason' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey(['patient_id', 'doctor_id', 'appointment_date']);
        $this->forge->createTable('appointments');
    }

    public function down()
    {
        $this->forge->dropTable('appointments');
    }
}
