<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Connects Insurance_claims to Invoices table.
 * Based on ERD showing invoice_no relationship.
 */
class ConnectInsuranceClaimsToInvoices extends Migration
{
    public function up()
    {
        $db = $this->db;

        // -----------------------------
        // Insurance Claims - Add invoice_id to connect to Invoices table
        // -----------------------------
        if ($db->tableExists('insurance_claims') && $db->tableExists('invoices')) {
            $fields = $db->getFieldNames('insurance_claims');
            
            // Add invoice_id if missing (to connect to invoices.id)
            if (!in_array('invoice_id', $fields, true)) {
                $this->forge->addColumn('insurance_claims', [
                    'invoice_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true,
                        'after' => 'invoice_no',
                        'comment' => 'Foreign key to invoices.id',
                    ],
                ]);

                // Backfill: match by invoice_no where possible
                try {
                    $db->query('UPDATE `insurance_claims` ic
                        JOIN `invoices` inv ON ic.invoice_no = inv.invoice_no
                        SET ic.invoice_id = inv.id
                        WHERE ic.invoice_no IS NOT NULL AND ic.invoice_no <> ""');
                } catch (\Throwable $e) {
                    log_message('error', "Failed to backfill invoice_id: " . $e->getMessage());
                }
            }

            // Add foreign key for invoice_id
            $fields = $db->getFieldNames('insurance_claims');
            if (in_array('invoice_id', $fields, true)) {
                $this->addForeignKeyIfNotExists(
                    'insurance_claims',
                    'fk_insurance_claims_invoice',
                    'invoice_id',
                    'invoices',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
        }

        // Note: Invoices table columns (patient_id, billing_id, etc.) are already added
        // by migration 2025-12-06-000002_AddMissingInvoiceAndLabTestConnections.php
        // Foreign keys for invoices are also handled by that migration
    }

    public function down()
    {
        $db = $this->db;

        // Drop foreign keys first
        if ($db->tableExists('insurance_claims')) {
            try {
                $db->query('ALTER TABLE `insurance_claims` DROP FOREIGN KEY `fk_insurance_claims_invoice`');
            } catch (\Throwable $e) {
                // Ignore if constraint does not exist
            }

            $fields = $db->getFieldNames('insurance_claims');
            if (in_array('invoice_id', $fields, true)) {
                try {
                    $this->forge->dropColumn('insurance_claims', 'invoice_id');
                } catch (\Throwable $e) {
                    // Ignore if column does not exist
                }
            }
        }

        // Note: Invoices table columns are handled by migration 2025-12-06-000002
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

