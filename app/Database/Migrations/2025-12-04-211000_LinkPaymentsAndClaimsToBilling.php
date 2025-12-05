<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Link payments and insurance_claims to billing header records.
 *
 * - Adds payments.billing_id -> billing.id (optional, nullable).
 * - Adds insurance_claims.billing_id -> billing.id (optional, nullable).
 * - Attempts a simple backfill based on matching invoice numbers.
 */
class LinkPaymentsAndClaimsToBilling extends Migration
{
    public function up()
    {
        $db = $this->db;

        // payments.billing_id
        if ($db->tableExists('payments')) {
            $fields = $db->getFieldNames('payments');
            if (!in_array('billing_id', $fields, true)) {
                $this->forge->addColumn('payments', [
                    'billing_id' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'unsigned'   => true,
                        'null'       => true,
                        'after'      => 'id',
                    ],
                ]);
            }

            if ($db->tableExists('billing')) {
                // Backfill: match by invoice number where possible
                try {
                    $db->query('UPDATE `payments` p
                        JOIN `billing` b ON p.invoice_no = b.invoice_number
                        SET p.billing_id = b.id
                        WHERE p.invoice_no IS NOT NULL AND p.invoice_no <> ""');
                } catch (\Throwable $e) {
                    // Ignore if something goes wrong; FK will still be added.
                }

                // Add FK constraint (check if exists first)
                $this->addForeignKeyIfNotExists(
                    'payments',
                    'fk_payments_billing',
                    'billing_id',
                    'billing',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
        }

        // insurance_claims.billing_id
        if ($db->tableExists('insurance_claims')) {
            $fields = $db->getFieldNames('insurance_claims');
            if (!in_array('billing_id', $fields, true)) {
                $this->forge->addColumn('insurance_claims', [
                    'billing_id' => [
                        'type'       => 'INT',
                        'constraint' => 11,
                        'unsigned'   => true,
                        'null'       => true,
                        'after'      => 'invoice_no',
                    ],
                ]);
            }

            if ($db->tableExists('billing')) {
                // Backfill: match by invoice number
                try {
                    $db->query('UPDATE `insurance_claims` ic
                        JOIN `billing` b ON ic.invoice_no = b.invoice_number
                        SET ic.billing_id = b.id
                        WHERE ic.invoice_no IS NOT NULL AND ic.invoice_no <> ""');
                } catch (\Throwable $e) {
                    // Ignore matching errors
                }

                $this->addForeignKeyIfNotExists(
                    'insurance_claims',
                    'fk_insurance_claims_billing',
                    'billing_id',
                    'billing',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
        }
    }

    public function down()
    {
        $db = $this->db;

        // Drop FKs then columns
        if ($db->tableExists('payments')) {
            try {
                $db->query('ALTER TABLE `payments` DROP FOREIGN KEY `fk_payments_billing`');
            } catch (\Throwable $e) {
                // ignore
            }
            $fields = $db->getFieldNames('payments');
            if (in_array('billing_id', $fields, true)) {
                $this->forge->dropColumn('payments', 'billing_id');
            }
        }

        if ($db->tableExists('insurance_claims')) {
            try {
                $db->query('ALTER TABLE `insurance_claims` DROP FOREIGN KEY `fk_insurance_claims_billing`');
            } catch (\Throwable $e) {
                // ignore
            }
            $fields = $db->getFieldNames('insurance_claims');
            if (in_array('billing_id', $fields, true)) {
                $this->forge->dropColumn('insurance_claims', 'billing_id');
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
