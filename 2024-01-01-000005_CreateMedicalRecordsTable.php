<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMedicalRecordsTable extends Migration
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
            'record_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'unique'     => true,
            ],
            'patient_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'appointment_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'doctor_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'visit_date' => [
                'type' => 'DATETIME',
            ],
            'chief_complaint' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'history_present_illness' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'physical_examination' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'vital_signs' => [
                'type' => 'JSON',
                'null' => true,
                'comment' => 'Blood pressure, temperature, pulse, etc.',
            ],
            'diagnosis' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'treatment_plan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'medications_prescribed' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'follow_up_instructions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'next_visit_date' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'branch_id' => [
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
        $this->forge->addKey(['patient_id', 'visit_date']);
        $this->forge->createTable('medical_records');
    }

    public function down()
    {
        $this->forge->dropTable('medical_records');
    }
}
