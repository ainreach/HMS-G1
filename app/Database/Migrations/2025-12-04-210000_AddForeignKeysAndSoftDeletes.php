<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Adds soft delete support and core foreign key constraints.
 *
 * Assumptions are documented inline where behavior could vary by installation.
 */
class AddForeignKeysAndSoftDeletes extends Migration
{
    public function up()
    {
        $db = $this->db;

        // -----------------------------
        // 1) Add deleted_at columns
        // -----------------------------
        $tablesWithSoftDeletes = [
            'patients',
            'appointments',
            'billing',
            'billing_items',
            'lab_tests',
            'medical_records',
            'medicines',
            'inventory',
            'rooms',
            'users',
            'staff_schedules',
            'prescriptions',
        ];

        foreach ($tablesWithSoftDeletes as $table) {
            if ($db->tableExists($table)) {
                $fields = $db->getFieldNames($table);
                if (!in_array('deleted_at', $fields, true)) {
                    $this->forge->addColumn($table, [
                        'deleted_at' => [
                            'type' => 'DATETIME',
                            'null' => true,
                            'after' => 'updated_at',
                        ],
                    ]);
                }
            }
        }

        // ---------------------------------
        // 2) Data cleanup for FK integrity
        // ---------------------------------
        // Example: lab_tests.sample row uses patient_id = 0 and doctor_id = 3; 0 is invalid.
        if ($db->tableExists('lab_tests') && $db->tableExists('patients')) {
            $db->query('DELETE lt FROM lab_tests lt LEFT JOIN patients p ON lt.patient_id = p.id WHERE p.id IS NULL');
        }

        // ---------------------------------
        // 3) Add foreign key constraints
        // ---------------------------------
        // Note: MySQL/MariaDB require explicit constraint names to be able to drop them later.

        // users.branch_id -> branches.id (staff belongs to a branch)
        if ($db->tableExists('users') && $db->tableExists('branches')) {
            $this->addForeignKeyIfNotExists(
                'users',
                'fk_users_branch',
                'branch_id',
                'branches',
                'id',
                'SET NULL',
                'CASCADE'
            );
        }

        // patients.branch_id -> branches.id
        if ($db->tableExists('patients') && $db->tableExists('branches')) {
            $this->addForeignKeyIfNotExists(
                'patients',
                'fk_patients_branch',
                'branch_id',
                'branches',
                'id',
                'RESTRICT',
                'CASCADE'
            );
        }

        // patients.assigned_room_id -> rooms.id (added by AddAdmissionFieldsToPatientsTable)
        if ($db->tableExists('patients') && $db->tableExists('rooms')) {
            $fields = $db->getFieldNames('patients');
            if (in_array('assigned_room_id', $fields, true)) {
                $this->addForeignKeyIfNotExists(
                    'patients',
                    'fk_patients_room',
                    'assigned_room_id',
                    'rooms',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
        }

        // rooms.branch_id -> branches.id
        if ($db->tableExists('rooms') && $db->tableExists('branches')) {
            $this->addForeignKeyIfNotExists(
                'rooms',
                'fk_rooms_branch',
                'branch_id',
                'branches',
                'id',
                'RESTRICT',
                'CASCADE'
            );
        }

        // appointments relations
        if ($db->tableExists('appointments')) {
            if ($db->tableExists('patients')) {
                $this->addForeignKeyIfNotExists(
                    'appointments',
                    'fk_appointments_patient',
                    'patient_id',
                    'patients',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'appointments',
                    'fk_appointments_doctor',
                    'doctor_id',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );

                $this->addForeignKeyIfNotExists(
                    'appointments',
                    'fk_appointments_created_by',
                    'created_by',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('branches')) {
                $this->addForeignKeyIfNotExists(
                    'appointments',
                    'fk_appointments_branch',
                    'branch_id',
                    'branches',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }

        // billing relations
        if ($db->tableExists('billing')) {
            if ($db->tableExists('patients')) {
                $this->addForeignKeyIfNotExists(
                    'billing',
                    'fk_billing_patient',
                    'patient_id',
                    'patients',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('appointments')) {
                $this->addForeignKeyIfNotExists(
                    'billing',
                    'fk_billing_appointment',
                    'appointment_id',
                    'appointments',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
            if ($db->tableExists('branches')) {
                $this->addForeignKeyIfNotExists(
                    'billing',
                    'fk_billing_branch',
                    'branch_id',
                    'branches',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'billing',
                    'fk_billing_created_by',
                    'created_by',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }

        // billing_items.billing_id -> billing.id (child rows cascade when header removed)
        if ($db->tableExists('billing_items') && $db->tableExists('billing')) {
            $this->addForeignKeyIfNotExists(
                'billing_items',
                'fk_billing_items_billing',
                'billing_id',
                'billing',
                'id',
                'CASCADE',
                'CASCADE'
            );
        }

        // inventory relations
        if ($db->tableExists('inventory')) {
            if ($db->tableExists('medicines')) {
                $this->addForeignKeyIfNotExists(
                    'inventory',
                    'fk_inventory_medicine',
                    'medicine_id',
                    'medicines',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('branches')) {
                $this->addForeignKeyIfNotExists(
                    'inventory',
                    'fk_inventory_branch',
                    'branch_id',
                    'branches',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'inventory',
                    'fk_inventory_last_updated_by',
                    'last_updated_by',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }

        // lab_tests relations
        if ($db->tableExists('lab_tests')) {
            if ($db->tableExists('patients')) {
                $this->addForeignKeyIfNotExists(
                    'lab_tests',
                    'fk_lab_tests_patient',
                    'patient_id',
                    'patients',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'lab_tests',
                    'fk_lab_tests_doctor',
                    'doctor_id',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );

                $this->addForeignKeyIfNotExists(
                    'lab_tests',
                    'fk_lab_tests_technician',
                    'lab_technician_id',
                    'users',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
            if ($db->tableExists('branches')) {
                $this->addForeignKeyIfNotExists(
                    'lab_tests',
                    'fk_lab_tests_branch',
                    'branch_id',
                    'branches',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }

        // medical_records relations
        if ($db->tableExists('medical_records')) {
            if ($db->tableExists('patients')) {
                $this->addForeignKeyIfNotExists(
                    'medical_records',
                    'fk_medical_records_patient',
                    'patient_id',
                    'patients',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('appointments')) {
                $this->addForeignKeyIfNotExists(
                    'medical_records',
                    'fk_medical_records_appointment',
                    'appointment_id',
                    'appointments',
                    'id',
                    'SET NULL',
                    'CASCADE'
                );
            }
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'medical_records',
                    'fk_medical_records_doctor',
                    'doctor_id',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('branches')) {
                $this->addForeignKeyIfNotExists(
                    'medical_records',
                    'fk_medical_records_branch',
                    'branch_id',
                    'branches',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }

        // prescriptions relations
        if ($db->tableExists('prescriptions')) {
            if ($db->tableExists('patients')) {
                $this->addForeignKeyIfNotExists(
                    'prescriptions',
                    'fk_prescriptions_patient',
                    'patient_id',
                    'patients',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'prescriptions',
                    'fk_prescriptions_doctor',
                    'doctor_id',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }

        // staff_schedules relations
        if ($db->tableExists('staff_schedules')) {
            if ($db->tableExists('users')) {
                $this->addForeignKeyIfNotExists(
                    'staff_schedules',
                    'fk_staff_schedules_user',
                    'user_id',
                    'users',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
            if ($db->tableExists('branches')) {
                $this->addForeignKeyIfNotExists(
                    'staff_schedules',
                    'fk_staff_schedules_branch',
                    'branch_id',
                    'branches',
                    'id',
                    'RESTRICT',
                    'CASCADE'
                );
            }
        }
    }

    public function down()
    {
        $db = $this->db;

        // Drop foreign keys (reverse order of creation to avoid dependency issues)
        $fkDrops = [
            // staff_schedules
            ['staff_schedules', 'fk_staff_schedules_branch'],
            ['staff_schedules', 'fk_staff_schedules_user'],
            // prescriptions
            ['prescriptions', 'fk_prescriptions_doctor'],
            ['prescriptions', 'fk_prescriptions_patient'],
            // medical_records
            ['medical_records', 'fk_medical_records_branch'],
            ['medical_records', 'fk_medical_records_doctor'],
            ['medical_records', 'fk_medical_records_appointment'],
            ['medical_records', 'fk_medical_records_patient'],
            // lab_tests
            ['lab_tests', 'fk_lab_tests_branch'],
            ['lab_tests', 'fk_lab_tests_technician'],
            ['lab_tests', 'fk_lab_tests_doctor'],
            ['lab_tests', 'fk_lab_tests_patient'],
            // inventory
            ['inventory', 'fk_inventory_last_updated_by'],
            ['inventory', 'fk_inventory_branch'],
            ['inventory', 'fk_inventory_medicine'],
            // billing_items
            ['billing_items', 'fk_billing_items_billing'],
            // billing
            ['billing', 'fk_billing_created_by'],
            ['billing', 'fk_billing_branch'],
            ['billing', 'fk_billing_appointment'],
            ['billing', 'fk_billing_patient'],
            // appointments
            ['appointments', 'fk_appointments_branch'],
            ['appointments', 'fk_appointments_created_by'],
            ['appointments', 'fk_appointments_doctor'],
            ['appointments', 'fk_appointments_patient'],
            // rooms
            ['rooms', 'fk_rooms_branch'],
            // patients
            ['patients', 'fk_patients_room'],
            ['patients', 'fk_patients_branch'],
            // users
            ['users', 'fk_users_branch'],
        ];

        foreach ($fkDrops as [$table, $constraint]) {
            if ($db->tableExists($table)) {
                // Use plain SQL; some MariaDB versions don\'t support "DROP FOREIGN KEY IF EXISTS".
                try {
                    $db->query("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$constraint}`");
                } catch (\Throwable $e) {
                    // Ignore if constraint does not exist.
                }
            }
        }

        // Drop deleted_at columns
        $tablesWithSoftDeletes = [
            'patients',
            'appointments',
            'billing',
            'billing_items',
            'lab_tests',
            'medical_records',
            'medicines',
            'inventory',
            'rooms',
            'users',
            'staff_schedules',
            'prescriptions',
        ];

        foreach ($tablesWithSoftDeletes as $table) {
            if ($db->tableExists($table)) {
                $fields = $db->getFieldNames($table);
                if (in_array('deleted_at', $fields, true)) {
                    $this->forge->dropColumn($table, 'deleted_at');
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
