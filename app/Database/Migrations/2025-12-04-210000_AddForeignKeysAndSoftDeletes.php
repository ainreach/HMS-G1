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
            $db->query('ALTER TABLE `users`
                ADD CONSTRAINT `fk_users_branch`
                FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                ON DELETE SET NULL ON UPDATE CASCADE');
        }

        // patients.branch_id -> branches.id
        if ($db->tableExists('patients') && $db->tableExists('branches')) {
            $db->query('ALTER TABLE `patients`
                ADD CONSTRAINT `fk_patients_branch`
                FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                ON DELETE RESTRICT ON UPDATE CASCADE');
        }

        // patients.assigned_room_id -> rooms.id (added by AddAdmissionFieldsToPatientsTable)
        if ($db->tableExists('patients') && $db->tableExists('rooms')) {
            $fields = $db->getFieldNames('patients');
            if (in_array('assigned_room_id', $fields, true)) {
                $db->query('ALTER TABLE `patients`
                    ADD CONSTRAINT `fk_patients_room`
                    FOREIGN KEY (`assigned_room_id`) REFERENCES `rooms`(`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE');
            }
        }

        // rooms.branch_id -> branches.id
        if ($db->tableExists('rooms') && $db->tableExists('branches')) {
            $db->query('ALTER TABLE `rooms`
                ADD CONSTRAINT `fk_rooms_branch`
                FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                ON DELETE RESTRICT ON UPDATE CASCADE');
        }

        // appointments relations
        if ($db->tableExists('appointments')) {
            if ($db->tableExists('patients')) {
                $db->query('ALTER TABLE `appointments`
                    ADD CONSTRAINT `fk_appointments_patient`
                    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('users')) {
                $db->query('ALTER TABLE `appointments`
                    ADD CONSTRAINT `fk_appointments_doctor`
                    FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');

                $db->query('ALTER TABLE `appointments`
                    ADD CONSTRAINT `fk_appointments_created_by`
                    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('branches')) {
                $db->query('ALTER TABLE `appointments`
                    ADD CONSTRAINT `fk_appointments_branch`
                    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
        }

        // billing relations
        if ($db->tableExists('billing')) {
            if ($db->tableExists('patients')) {
                $db->query('ALTER TABLE `billing`
                    ADD CONSTRAINT `fk_billing_patient`
                    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('appointments')) {
                $db->query('ALTER TABLE `billing`
                    ADD CONSTRAINT `fk_billing_appointment`
                    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE');
            }
            if ($db->tableExists('branches')) {
                $db->query('ALTER TABLE `billing`
                    ADD CONSTRAINT `fk_billing_branch`
                    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('users')) {
                $db->query('ALTER TABLE `billing`
                    ADD CONSTRAINT `fk_billing_created_by`
                    FOREIGN KEY (`created_by`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
        }

        // billing_items.billing_id -> billing.id (child rows cascade when header removed)
        if ($db->tableExists('billing_items') && $db->tableExists('billing')) {
            $db->query('ALTER TABLE `billing_items`
                ADD CONSTRAINT `fk_billing_items_billing`
                FOREIGN KEY (`billing_id`) REFERENCES `billing`(`id`)
                ON DELETE CASCADE ON UPDATE CASCADE');
        }

        // inventory relations
        if ($db->tableExists('inventory')) {
            if ($db->tableExists('medicines')) {
                $db->query('ALTER TABLE `inventory`
                    ADD CONSTRAINT `fk_inventory_medicine`
                    FOREIGN KEY (`medicine_id`) REFERENCES `medicines`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('branches')) {
                $db->query('ALTER TABLE `inventory`
                    ADD CONSTRAINT `fk_inventory_branch`
                    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('users')) {
                $db->query('ALTER TABLE `inventory`
                    ADD CONSTRAINT `fk_inventory_last_updated_by`
                    FOREIGN KEY (`last_updated_by`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
        }

        // lab_tests relations
        if ($db->tableExists('lab_tests')) {
            if ($db->tableExists('patients')) {
                $db->query('ALTER TABLE `lab_tests`
                    ADD CONSTRAINT `fk_lab_tests_patient`
                    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('users')) {
                $db->query('ALTER TABLE `lab_tests`
                    ADD CONSTRAINT `fk_lab_tests_doctor`
                    FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');

                $db->query('ALTER TABLE `lab_tests`
                    ADD CONSTRAINT `fk_lab_tests_technician`
                    FOREIGN KEY (`lab_technician_id`) REFERENCES `users`(`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE');
            }
            if ($db->tableExists('branches')) {
                $db->query('ALTER TABLE `lab_tests`
                    ADD CONSTRAINT `fk_lab_tests_branch`
                    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
        }

        // medical_records relations
        if ($db->tableExists('medical_records')) {
            if ($db->tableExists('patients')) {
                $db->query('ALTER TABLE `medical_records`
                    ADD CONSTRAINT `fk_medical_records_patient`
                    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('appointments')) {
                $db->query('ALTER TABLE `medical_records`
                    ADD CONSTRAINT `fk_medical_records_appointment`
                    FOREIGN KEY (`appointment_id`) REFERENCES `appointments`(`id`)
                    ON DELETE SET NULL ON UPDATE CASCADE');
            }
            if ($db->tableExists('users')) {
                $db->query('ALTER TABLE `medical_records`
                    ADD CONSTRAINT `fk_medical_records_doctor`
                    FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('branches')) {
                $db->query('ALTER TABLE `medical_records`
                    ADD CONSTRAINT `fk_medical_records_branch`
                    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
        }

        // prescriptions relations
        if ($db->tableExists('prescriptions')) {
            if ($db->tableExists('patients')) {
                $db->query('ALTER TABLE `prescriptions`
                    ADD CONSTRAINT `fk_prescriptions_patient`
                    FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('users')) {
                $db->query('ALTER TABLE `prescriptions`
                    ADD CONSTRAINT `fk_prescriptions_doctor`
                    FOREIGN KEY (`doctor_id`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
        }

        // staff_schedules relations
        if ($db->tableExists('staff_schedules')) {
            if ($db->tableExists('users')) {
                $db->query('ALTER TABLE `staff_schedules`
                    ADD CONSTRAINT `fk_staff_schedules_user`
                    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
            }
            if ($db->tableExists('branches')) {
                $db->query('ALTER TABLE `staff_schedules`
                    ADD CONSTRAINT `fk_staff_schedules_branch`
                    FOREIGN KEY (`branch_id`) REFERENCES `branches`(`id`)
                    ON DELETE RESTRICT ON UPDATE CASCADE');
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
}
