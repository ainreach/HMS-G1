<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToPayments extends Migration
{
    public function up()
    {
        // Check if table exists
        if (!$this->db->tableExists('payments')) {
            return; // Table doesn't exist, skip
        }

        $existingColumns = $this->db->getFieldNames('payments');
        $fieldsToAdd = [];

        // Add payment_method column
        if (!in_array('payment_method', $existingColumns)) {
            $fieldsToAdd['payment_method'] = [
                'type'       => 'ENUM',
                'constraint' => ['cash', 'card', 'cheque', 'bank_transfer', 'insurance', 'other'],
                'null'       => true,
                'after'      => 'amount',
            ];
        }

        // Add patient_id column
        if (!in_array('patient_id', $existingColumns)) {
            $fieldsToAdd['patient_id'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'billing_id',
            ];
        }

        // Add transaction_id column
        if (!in_array('transaction_id', $existingColumns)) {
            $fieldsToAdd['transaction_id'] = [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'payment_method',
            ];
        }

        // Add notes column
        if (!in_array('notes', $existingColumns)) {
            $fieldsToAdd['notes'] = [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'transaction_id',
            ];
        }

        // Add processed_by column
        if (!in_array('processed_by', $existingColumns)) {
            $fieldsToAdd['processed_by'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'notes',
            ];
        }

        // Add created_by column (if not exists, check if it's in allowedFields)
        if (!in_array('created_by', $existingColumns)) {
            $fieldsToAdd['created_by'] = [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'default'    => 0,
                'after'      => 'processed_by',
            ];
        }

        // Add all missing columns at once
        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('payments', $fieldsToAdd);
        }
    }

    public function down()
    {
        // Check if table exists
        if (!$this->db->tableExists('payments')) {
            return;
        }

        $existingColumns = $this->db->getFieldNames('payments');
        $columnsToDrop = [];

        // Drop columns if they exist
        $columnsToCheck = ['payment_method', 'patient_id', 'transaction_id', 'notes', 'processed_by', 'created_by'];
        
        foreach ($columnsToCheck as $column) {
            if (in_array($column, $existingColumns)) {
                $columnsToDrop[] = $column;
            }
        }

        if (!empty($columnsToDrop)) {
            $this->forge->dropColumn('payments', $columnsToDrop);
        }
    }
}

