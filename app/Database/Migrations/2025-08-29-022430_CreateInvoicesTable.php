<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAdmissionFieldsToPatientsTable extends Migration
{
    public function up()
    {
        // Check if columns already exist
        $existingColumns = $this->db->getFieldNames('patients');
        
        if (!in_array('admission_type', $existingColumns)) {
            $fields = [
                'admission_type' => [
                    'type'       => 'ENUM',
                    'constraint' => ['checkup', 'admission'],
                    'default'    => 'checkup',
                    'after'      => 'branch_id',
                ],
                'assigned_room_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'admission_type',
                ],
                'admission_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'assigned_room_id',
                ],
                'discharge_date' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'admission_date',
                ],
            ];
            
            $this->forge->addColumn('patients', $fields);
        }
    }

    public function down()
    {
        // Drop foreign key first
        $this->db->query("ALTER TABLE `patients` DROP FOREIGN KEY IF EXISTS `fk_patients_room`");
        
        // Drop columns
        $this->forge->dropColumn('patients', 'admission_type');
        $this->forge->dropColumn('patients', 'assigned_room_id');
        $this->forge->dropColumn('patients', 'admission_date');
        $this->forge->dropColumn('patients', 'discharge_date');
    }
}
