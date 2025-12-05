<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePrescriptionStatusQueue extends Migration
{
    public function up()
    {
        $db = $this->db;
        
        if ($db->tableExists('prescriptions')) {
            // Update status enum to include queue statuses
            $db->query("ALTER TABLE `prescriptions` 
                MODIFY COLUMN `status` ENUM('pending', 'approved', 'prepared', 'dispensed', 'administered', 'cancelled') 
                DEFAULT 'pending'");
            
            // Update existing records: 'active' -> 'pending', 'completed' -> 'dispensed'
            $db->query("UPDATE `prescriptions` SET `status` = 'pending' WHERE `status` = 'active' OR `status` IS NULL");
            $db->query("UPDATE `prescriptions` SET `status` = 'dispensed' WHERE `status` = 'completed'");
        }
    }

    public function down()
    {
        $db = $this->db;
        
        if ($db->tableExists('prescriptions')) {
            // Revert to old statuses
            $db->query("UPDATE `prescriptions` SET `status` = 'active' WHERE `status` IN ('pending', 'approved', 'prepared')");
            $db->query("UPDATE `prescriptions` SET `status` = 'completed' WHERE `status` IN ('dispensed', 'administered')");
            
            $db->query("ALTER TABLE `prescriptions` 
                MODIFY COLUMN `status` ENUM('active', 'completed', 'cancelled') 
                DEFAULT 'active'");
        }
    }
}

