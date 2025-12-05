<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds missing columns and foreign keys for invoices and lab_tests connections.
 * Based on ERD analysis showing missing relationships.
 */
class AddMissingInvoiceAndLabTestConnections extends Migration
{
    public function up()
    {
        $db = $this->db;

        // -----------------------------
        // Invoices table - Add missing columns
        // -----------------------------
        if ($db->tableExists('invoices')) {
            $fields = $db->getFieldNames('invoices');
            $columnsToAdd = [];

            // Add patient_id if missing
            if (!in_array('patient_id', $fields, true)) {
                $columnsToAdd['patient_id'] = [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'patient_name',
                ];
            }

            // Add billing_id if missing
            if (!in_array('billing_id', $fields, true)) {
                $columnsToAdd['billing_id'] = [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'patient_id',
                ];
            }

            // Add due_date if missing
            if (!in_array('due_date', $fields, true)) {
                $columnsToAdd['due_date'] = [
                    'type' => 'DATE',
                    'null' => true,
                    'after' => 'issued_at',
                ];
            }

            // Add paid_amount if missing
            if (!in_array('paid_amount', $fields, true)) {
                $columnsToAdd['paid_amount'] = [
                    'type' => 'DECIMAL',
                    'constraint' => '10,2',
                    'default' => 0.00,
                    'after' => 'amount',
                ];
            }

            if (!empty($columnsToAdd)) {
                $this->forge->addColumn('invoices', $columnsToAdd);
            }

            // Add foreign keys for invoices
            if ($db->tableExists('patients')) {
                $fields = $db->getFieldNames('invoices');
                if (in_array('patient_id', $fields, true)) {
                    $this->addForeignKeyIfNotExists(
                        'invoices',
                        'fk_invoices_patient',
                        'patient_id',
                        'patients',
                        'id',
                        'SET NULL',
                        'CASCADE'
                    );
                }
            }

            if ($db->tableExists('billing')) {
                $fields = $db->getFieldNames('invoices');
                if (in_array('billing_id', $fields, true)) {
                    $this->addForeignKeyIfNotExists(
                        'invoices',
                        'fk_invoices_billing',
                        'billing_id',
                        'billing',
                        'id',
                        'SET NULL',
                        'CASCADE'
                    );
                }
            }
        }

        // -----------------------------
        // Lab Tests - Add catalog_id connection
        // -----------------------------
        if ($db->tableExists('lab_tests') && $db->tableExists('lab_test_catalog')) {
            $fields = $db->getFieldNames('lab_tests');
            
            // Add catalog_id if missing
            if (!in_array('catalog_id', $fields, true)) {
                $this->forge->addColumn('lab_tests', [
                    'catalog_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true,
                        'after' => 'test_category',
                        'comment' => 'Reference to lab_test_catalog.id',
                    ],
                ]);
            }

            // Add foreign key for catalog_id
            $fields = $db->getFieldNames('lab_tests');
            if (in_array('catalog_id', $fields, true)) {
                $this->addForeignKeyIfNotExists(
                    'lab_tests',
                    'fk_lab_tests_catalog',
                    'catalog_id',
                    'lab_test_catalog',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
        }

        // -----------------------------
        // Prescriptions - Add is_active if missing
        // -----------------------------
        if ($db->tableExists('prescriptions')) {
            $fields = $db->getFieldNames('prescriptions');
            
            if (!in_array('is_active', $fields, true)) {
                $this->forge->addColumn('prescriptions', [
                    'is_active' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 1,
                        'after' => 'end_date',
                    ],
                ]);
            }

            // Add status field if missing (for better prescription management)
            if (!in_array('status', $fields, true)) {
                $this->forge->addColumn('prescriptions', [
                    'status' => [
                        'type' => 'ENUM',
                        'constraint' => ['active', 'completed', 'cancelled'],
                        'default' => 'active',
                        'after' => 'is_active',
                    ],
                ]);
            }

            // Add instructions field if missing
            if (!in_array('instructions', $fields, true)) {
                $this->forge->addColumn('prescriptions', [
                    'instructions' => [
                        'type' => 'TEXT',
                        'null' => true,
                        'after' => 'frequency',
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        $db = $this->db;

        // Drop foreign keys first
        $fkDrops = [
            ['lab_tests', 'fk_lab_tests_catalog'],
            ['invoices', 'fk_invoices_billing'],
            ['invoices', 'fk_invoices_patient'],
        ];

        foreach ($fkDrops as [$table, $constraint]) {
            if ($db->tableExists($table)) {
                try {
                    $db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
                } catch (\Throwable $e) {
                    // Ignore if constraint does not exist
                }
            }
        }

        // Drop columns
        if ($db->tableExists('lab_tests')) {
            $fields = $db->getFieldNames('lab_tests');
            if (in_array('catalog_id', $fields, true)) {
                try {
                    $this->forge->dropColumn('lab_tests', 'catalog_id');
                } catch (\Throwable $e) {
                    // Ignore if column does not exist
                }
            }
        }

        if ($db->tableExists('prescriptions')) {
            $fields = $db->getFieldNames('prescriptions');
            $columnsToDrop = [];
            
            if (in_array('instructions', $fields, true)) {
                $columnsToDrop[] = 'instructions';
            }
            if (in_array('status', $fields, true)) {
                $columnsToDrop[] = 'status';
            }
            if (in_array('is_active', $fields, true)) {
                $columnsToDrop[] = 'is_active';
            }

            if (!empty($columnsToDrop)) {
                try {
                    $this->forge->dropColumn('prescriptions', $columnsToDrop);
                } catch (\Throwable $e) {
                    // Ignore if columns do not exist
                }
            }
        }

        if ($db->tableExists('invoices')) {
            $fields = $db->getFieldNames('invoices');
            $columnsToDrop = [];
            
            if (in_array('paid_amount', $fields, true)) {
                $columnsToDrop[] = 'paid_amount';
            }
            if (in_array('due_date', $fields, true)) {
                $columnsToDrop[] = 'due_date';
            }
            if (in_array('billing_id', $fields, true)) {
                $columnsToDrop[] = 'billing_id';
            }
            if (in_array('patient_id', $fields, true)) {
                $columnsToDrop[] = 'patient_id';
            }

            if (!empty($columnsToDrop)) {
                try {
                    $this->forge->dropColumn('invoices', $columnsToDrop);
                } catch (\Throwable $e) {
                    // Ignore if columns do not exist
                }
            }
        }
    }

    /**
     * Helper method to add foreign key constraint only if it doesn't exist
     */
    private function addForeignKeyIfNotExists(
        string $table,
        string $constraintName,
        string $column,
        string $referencedTable,
        string $referencedColumn,
        string $onDelete = 'RESTRICT',
        string $onUpdate = 'CASCADE'
    ): void {
        $db = $this->db;

        // Check if constraint already exists
        $constraints = $db->query("
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = '{$table}'
            AND CONSTRAINT_NAME = '{$constraintName}'
        ")->getResultArray();

        if (empty($constraints)) {
            try {
                $db->query("
                    ALTER TABLE `{$table}`
                    ADD CONSTRAINT `{$constraintName}`
                    FOREIGN KEY (`{$column}`) REFERENCES `{$referencedTable}`(`{$referencedColumn}`)
                    ON DELETE {$onDelete} ON UPDATE {$onUpdate}
                ");
            } catch (\Throwable $e) {
                log_message('error', "Failed to add foreign key {$constraintName}: " . $e->getMessage());
            }
        }
    }
}

