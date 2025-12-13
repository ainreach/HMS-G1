<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateBedTypeEnum extends Migration
{
    public function up()
    {
        // Check if table exists
        if (!$this->db->tableExists('beds')) {
            return;
        }
        
        $fields = $this->db->getFieldNames('beds');
        
        // Update bed_type ENUM to include new types
        if (in_array('bed_type', $fields)) {
            $this->db->query("
                ALTER TABLE `beds` 
                MODIFY COLUMN `bed_type` ENUM(
                    'standard', 'electric', 'icu', 'pediatric', 
                    'bariatric', 'maternity', 'isolation'
                ) DEFAULT 'standard'
            ");
        }
    }
    
    public function down()
    {
        // Revert to original bed types
        if ($this->db->tableExists('beds')) {
            $fields = $this->db->getFieldNames('beds');
            if (in_array('bed_type', $fields)) {
                $this->db->query("
                    ALTER TABLE `beds` 
                    MODIFY COLUMN `bed_type` ENUM(
                        'standard', 'electric', 'icu', 'pediatric'
                    ) DEFAULT 'standard'
                ");
            }
        }
    }
}

