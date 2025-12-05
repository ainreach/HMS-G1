<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds missing foreign key constraints for complete database relationship integrity.
 * This ensures all tables are properly connected with referential integrity.
 */
class AddMissingForeignKeys extends Migration
{
    public function up()
    {
        $db = $this->db;

        // -----------------------------
        // Dispensing table foreign keys
        // -----------------------------
        if ($db->tableExists('dispensing')) {
            // dispensing.patient_id -> patients.id
            if ($db->tableExists('patients')) {
                $this->addForeignKeyIfNotExists(
                    'dispensing',
                    'fk_dispensing_patient',
                    'patient_id',
                    'patients',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }

            // dispensing.medicine_id -> medicines.id
            if ($db->tableExists('medicines')) {
                $this->addForeignKeyIfNotExists(
                    'dispensing',
                    'fk_dispensing_medicine',
                    'medicine_id',
                    'medicines',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }

            // dispensing.prescription_id -> prescriptions.id
            if ($db->tableExists('prescriptions')) {
                $this->addForeignKeyIfNotExists(
                    'dispensing',
                    'fk_dispensing_prescription',
                    'prescription_id',
                    'prescriptions',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }

            // dispensing.dispensed_by -> users.id
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'dispensing',
                    'fk_dispensing_user',
                    'dispensed_by',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }

        // -----------------------------
        // Payments table foreign keys
        // -----------------------------
        if ($db->tableExists('payments')) {
            // payments.billing_id -> billing.id
            if ($db->tableExists('billing')) {
                $this->addForeignKeyIfNotExists(
                    'payments',
                    'fk_payments_billing',
                    'billing_id',
                    'billing',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }

            // payments.processed_by -> users.id (if column exists)
            if ($db->tableExists('users')) {
                $fields = $db->getFieldNames('payments');
                if (in_array('processed_by', $fields, true)) {
                    $this->addForeignKeyIfNotExists(
                        'payments',
                        'fk_payments_processed_by',
                        'processed_by',
                        'users',
                        'id',
                        'SET NULL',
                        'CASCADE'
                    );
                }
            }
        }

        // -----------------------------
        // Invoices table foreign keys
        // -----------------------------
        if ($db->tableExists('invoices')) {
            // invoices.billing_id -> billing.id (if column exists)
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

            // invoices.patient_id -> patients.id (if column exists)
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
        }

        // -----------------------------
        // Insurance Claims table foreign keys
        // -----------------------------
        if ($db->tableExists('insurance_claims')) {
            // insurance_claims.billing_id -> billing.id
            if ($db->tableExists('billing')) {
                $this->addForeignKeyIfNotExists(
                    'insurance_claims',
                    'fk_insurance_claims_billing',
                    'billing_id',
                    'billing',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }

            // insurance_claims.patient_id -> patients.id (if column exists)
            if ($db->tableExists('patients')) {
                $fields = $db->getFieldNames('insurance_claims');
                if (in_array('patient_id', $fields, true)) {
                    $this->addForeignKeyIfNotExists(
                        'insurance_claims',
                        'fk_insurance_claims_patient',
                        'patient_id',
                        'patients',
                        'id',
                        'SET NULL',
                        'CASCADE'
                    );
                }
            }

            // insurance_claims.processed_by -> users.id (if column exists)
            if ($db->tableExists('users')) {
                $fields = $db->getFieldNames('insurance_claims');
                if (in_array('processed_by', $fields, true)) {
                    $this->addForeignKeyIfNotExists(
                        'insurance_claims',
                        'fk_insurance_claims_processed_by',
                        'processed_by',
                        'users',
                        'id',
                        'SET NULL',
                        'CASCADE'
                    );
                }
            }
        }

        // -----------------------------
        // Lab Tests - Add lab_technician_id if missing and foreign keys
        // -----------------------------
        if ($db->tableExists('lab_tests')) {
            $fields = $db->getFieldNames('lab_tests');
            
            // Add lab_technician_id column if missing
            if (!in_array('lab_technician_id', $fields, true)) {
                $this->forge->addColumn('lab_tests', [
                    'lab_technician_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true,
                        'after' => 'doctor_id',
                    ],
                ]);
            }

            // lab_tests.accountant_approved_by -> users.id (if column exists)
            if ($db->tableExists('users')) {
                if (in_array('accountant_approved_by', $fields, true)) {
                    $this->addForeignKeyIfNotExists(
                        'lab_tests',
                        'fk_lab_tests_accountant_approved_by',
                        'accountant_approved_by',
                        'users',
                        'id',
                        'SET NULL',
                        'CASCADE'
                    );
                }
            }
        }
    }

    public function down()
    {
        $db = $this->db;

        // Drop foreign keys in reverse order
        $fkDrops = [
            // Lab tests
            ['lab_tests', 'fk_lab_tests_accountant_approved_by'],
            // Insurance claims
            ['insurance_claims', 'fk_insurance_claims_processed_by'],
            ['insurance_claims', 'fk_insurance_claims_patient'],
            ['insurance_claims', 'fk_insurance_claims_billing'],
            // Invoices
            ['invoices', 'fk_invoices_patient'],
            ['invoices', 'fk_invoices_billing'],
            // Payments
            ['payments', 'fk_payments_processed_by'],
            ['payments', 'fk_payments_billing'],
            // Dispensing
            ['dispensing', 'fk_dispensing_user'],
            ['dispensing', 'fk_dispensing_prescription'],
            ['dispensing', 'fk_dispensing_medicine'],
            ['dispensing', 'fk_dispensing_patient'],
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

        // Remove lab_technician_id column if it was added
        if ($db->tableExists('lab_tests')) {
            $fields = $db->getFieldNames('lab_tests');
            if (in_array('lab_technician_id', $fields, true)) {
                try {
                    $this->forge->dropColumn('lab_tests', 'lab_technician_id');
                } catch (\Throwable $e) {
                    // Ignore if column does not exist
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

