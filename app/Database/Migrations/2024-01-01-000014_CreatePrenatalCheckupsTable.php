<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrenatalCheckupsTable extends Migration
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
            'checkup_number' => [
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
            'checkup_date' => [
                'type' => 'DATE',
            ],
            'gestational_age' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,1',
                'null'       => true,
                'comment'    => 'Gestational age in weeks',
            ],
            'blood_pressure' => [
                'type'       => 'VARCHAR',
                'constraint' => '20',
                'null'       => true,
            ],
            'weight' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'null'       => true,
                'comment'    => 'Weight in kg',
            ],
            'fetal_heart_rate' => [
                'type'       => 'INT',
                'constraint' => 3,
                'null'       => true,
                'comment'    => 'Fetal heart rate in bpm',
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
        $this->forge->addKey('checkup_date');
        $this->forge->createTable('prenatal_checkups');
    }

    public function down()
    {
        $this->forge->dropTable('prenatal_checkups');
    }
}

