<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Verifies and fixes the lab_test_catalog connection to lab_tests.
 * Ensures the foreign key constraint is properly created.
 */
class VerifyAndFixLabTestCatalogConnection extends Migration
{
    public function up()
    {
        $db = $this->db;

        // -----------------------------
        // Verify and fix lab_tests.catalog_id -> lab_test_catalog.id
        // -----------------------------
        if ($db->tableExists('lab_tests') && $db->tableExists('lab_test_catalog')) {
            $fields = $db->getFieldNames('lab_tests');
            
            // Step 1: Ensure catalog_id column exists
            if (!in_array('catalog_id', $fields, true)) {
                $this->forge->addColumn('lab_tests', [
                    'catalog_id' => [
                        'type' => 'INT',
                        'constraint' => 11,
                        'unsigned' => true,
                        'null' => true,
                        'after' => 'test_category',
                        'comment' => 'Foreign key to lab_test_catalog.id',
                    ],
                ]);
                log_message('info', 'Added catalog_id column to lab_tests table');
            }

            // Step 2: Verify and add foreign key constraint
            $fields = $db->getFieldNames('lab_tests');
            if (in_array('catalog_id', $fields, true)) {
                // Check if foreign key already exists
                $constraintExists = $this->checkForeignKeyExists('lab_tests', 'fk_lab_tests_catalog');
                
                if (!$constraintExists) {
                    // Add the foreign key constraint
                    try {
                        $db->query("
                            ALTER TABLE `lab_tests`
                            ADD CONSTRAINT `fk_lab_tests_catalog`
                            FOREIGN KEY (`catalog_id`) REFERENCES `lab_test_catalog`(`id`)
                            ON DELETE SET NULL ON UPDATE CASCADE
                        ");
                        log_message('info', 'Successfully added foreign key fk_lab_tests_catalog');
                    } catch (\Throwable $e) {
                        log_message('error', 'Failed to add foreign key fk_lab_tests_catalog: ' . $e->getMessage());
                        
                        // Try to fix any data issues first
                        try {
                            // Remove invalid catalog_id references
                            $db->query("
                                UPDATE `lab_tests` 
                                SET `catalog_id` = NULL 
                                WHERE `catalog_id` IS NOT NULL 
                                AND `catalog_id` NOT IN (SELECT `id` FROM `lab_test_catalog`)
                            ");
                            log_message('info', 'Cleaned up invalid catalog_id references');
                            
                            // Try adding FK again
                            $db->query("
                                ALTER TABLE `lab_tests`
                                ADD CONSTRAINT `fk_lab_tests_catalog`
                                FOREIGN KEY (`catalog_id`) REFERENCES `lab_test_catalog`(`id`)
                                ON DELETE SET NULL ON UPDATE CASCADE
                            ");
                            log_message('info', 'Successfully added foreign key after cleanup');
                        } catch (\Throwable $e2) {
                            log_message('error', 'Failed to add foreign key after cleanup: ' . $e2->getMessage());
                        }
                    }
                } else {
                    log_message('info', 'Foreign key fk_lab_tests_catalog already exists');
                }
            }
        }
    }

    public function down()
    {
        $db = $this->db;

        // Drop foreign key
        if ($db->tableExists('lab_tests')) {
            try {
                $db->query('ALTER TABLE `lab_tests` DROP FOREIGN KEY `fk_lab_tests_catalog`');
            } catch (\Throwable $e) {
                // Ignore if constraint does not exist
            }
        }
    }

    /**
     * Check if a foreign key constraint exists
     */
    private function checkForeignKeyExists(string $table, string $constraintName): bool
    {
        $db = $this->db;
        
        try {
            $result = $db->query("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND CONSTRAINT_NAME = ?
            ", [$table, $constraintName])->getResultArray();
            
            return !empty($result);
        } catch (\Throwable $e) {
            log_message('error', 'Error checking foreign key: ' . $e->getMessage());
            return false;
        }
    }
}

