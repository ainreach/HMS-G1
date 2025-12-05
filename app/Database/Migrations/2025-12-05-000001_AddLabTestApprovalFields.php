<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLabTestApprovalFields extends Migration
{
    public function up()
    {
        // Check if columns already exist
        $existingColumns = $this->db->getFieldNames('lab_tests');
        
        $fields = [];
        
        if (!in_array('requires_specimen', $existingColumns)) {
            $fields['requires_specimen'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
                'comment'    => '1 = requires specimen, 0 = no specimen needed',
                'after'      => 'test_category',
            ];
        }
        
        if (!in_array('accountant_approved', $existingColumns)) {
            $fields['accountant_approved'] = [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '1 = approved by accountant, 0 = pending approval',
                'after'      => 'requires_specimen',
            ];
        }
        
        if (!in_array('accountant_approved_by', $existingColumns)) {
            $fields['accountant_approved_by'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'accountant_approved',
            ];
        }
        
        if (!in_array('accountant_approved_at', $existingColumns)) {
            $fields['accountant_approved_at'] = [
                'type' => 'DATETIME',
                'null' => true,
                'after' => 'accountant_approved_by',
            ];
        }
        
        if (!empty($fields)) {
            $this->forge->addColumn('lab_tests', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('lab_tests', [
            'requires_specimen',
            'accountant_approved',
            'accountant_approved_by',
            'accountant_approved_at',
        ]);
    }
}

