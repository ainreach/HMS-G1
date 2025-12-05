<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBedIdToPatientsTable extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();
        $columns = $db->getFieldNames('patients');
        
        // Add bed_id column if it doesn't exist
        if (!in_array('assigned_bed_id', $columns)) {
            $this->forge->addColumn('patients', [
                'assigned_bed_id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'assigned_room_id',
                ],
            ]);
        }

        // Add admission_reason column if it doesn't exist
        if (!in_array('admission_reason', $columns)) {
            $this->forge->addColumn('patients', [
                'admission_reason' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'admission_date',
                ],
            ]);
        }

        // Add attending_physician_id column if it doesn't exist
        if (!in_array('attending_physician_id', $columns)) {
            $this->forge->addColumn('patients', [
                'attending_physician_id' => [
                    'type' => 'INT',
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'admission_reason',
                ],
            ]);
        }

        // Add admission_notes column if it doesn't exist
        if (!in_array('admission_notes', $columns)) {
            $this->forge->addColumn('patients', [
                'admission_notes' => [
                    'type' => 'TEXT',
                    'null' => true,
                    'after' => 'attending_physician_id',
                ],
            ]);
        }

        // Add foreign key for bed_id if beds table exists
        if ($db->tableExists('beds')) {
            $this->db->query("
                ALTER TABLE `patients`
                ADD CONSTRAINT `fk_patients_bed`
                FOREIGN KEY (`assigned_bed_id`) REFERENCES `beds`(`id`)
                ON DELETE SET NULL ON UPDATE CASCADE
            ");
        }
    }

    public function down()
    {
        // Remove foreign key first
        $this->db->query("ALTER TABLE `patients` DROP FOREIGN KEY IF EXISTS `fk_patients_bed`");
        
        // Remove columns
        $this->forge->dropColumn('patients', ['assigned_bed_id', 'admission_reason', 'attending_physician_id', 'admission_notes']);
    }
}

