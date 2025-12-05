<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixPrescriptionStatus extends Migration
{
    public function up()
    {
        $db = $this->db;
        
        if ($db->tableExists('prescriptions')) {
            // Update NULL status to 'pending' for existing prescriptions
            $db->query("UPDATE `prescriptions` SET `status` = 'pending' WHERE `status` IS NULL");
        }
    }

    public function down()
    {
        // No need to revert
    }
}

