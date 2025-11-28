<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabTestsTable extends Migration
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
            'test_number' => [
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
            'test_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'test_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'test_category' => [
                'type'       => 'ENUM',
                'constraint' => ['blood', 'urine', 'imaging', 'pathology', 'microbiology', 'other'],
            ],
            'requested_date' => [
                'type' => 'DATETIME',
            ],
            'sample_collected_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'result_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['requested', 'sample_collected', 'in_progress', 'completed', 'cancelled'],
                'default'    => 'requested',
            ],
            'results' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'normal_range' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'interpretation' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'lab_technician_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'priority' => [
                'type'       => 'ENUM',
                'constraint' => ['routine', 'urgent', 'stat'],
                'default'    => 'routine',
            ],
            'cost' => [
                'type'       => 'DECIMAL',
                'constraint' => '8,2',
                'default'    => 0.00,
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
        $this->forge->addKey(['patient_id', 'requested_date']);
        $this->forge->createTable('lab_tests');
    }

    public function down()
    {
        $this->forge->dropTable('lab_tests');
    }
}
