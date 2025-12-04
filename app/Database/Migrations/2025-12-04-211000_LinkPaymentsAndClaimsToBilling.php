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

                // Add FK constraint
                $db->query('ALTER TABLE `payments`
                    ADD CONSTRAINT `fk_payments_billing`
                    FOREIGN KEY (`billing_id`) REFERENCES `billing`(`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE');
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

                $db->query('ALTER TABLE `insurance_claims`
                    ADD CONSTRAINT `fk_insurance_claims_billing`
                    FOREIGN KEY (`billing_id`) REFERENCES `billing`(`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE');
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
}
