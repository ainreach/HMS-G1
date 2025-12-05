<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNurseIdToLabTests extends Migration
{
    public function up()
    {
        // Check if column already exists
        $existingColumns = $this->db->getFieldNames('lab_tests');
        
        if (!in_array('nurse_id', $existingColumns)) {
            $this->forge->addColumn('lab_tests', [
                'nurse_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'comment'    => 'Nurse assigned to collect specimen',
                    'after'      => 'doctor_id',
                ],
            ]);
        }
    }

    public function down()
    {
        // Check if column exists before dropping
        $existingColumns = $this->db->getFieldNames('lab_tests');
        
        if (in_array('nurse_id', $existingColumns)) {
            $this->forge->dropColumn('lab_tests', 'nurse_id');
        }
    }
}

