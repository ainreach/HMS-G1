<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingColumnsToRoomsTable extends Migration
{
    public function up()
    {
        // Check if table exists
        if (!$this->db->tableExists('rooms')) {
            return;
        }
        
        $fields = $this->db->getFieldNames('rooms');
        $columnsToAdd = [];
        
        // Add is_active if it doesn't exist
        if (!in_array('is_active', $fields)) {
            $columnsToAdd['is_active'] = [
                'type' => 'BOOLEAN',
                'default' => true,
                'after' => 'rate_per_day',
            ];
        }
        
        // Add current_occupancy if it doesn't exist
        if (!in_array('current_occupancy', $fields)) {
            $columnsToAdd['current_occupancy'] = [
                'type' => 'INT',
                'constraint' => 3,
                'default' => 0,
                'after' => 'capacity',
            ];
        }
        
        // Update room_type ENUM to include new types
        if (in_array('room_type', $fields)) {
            $this->db->query("
                ALTER TABLE `rooms` 
                MODIFY COLUMN `room_type` ENUM(
                    'normal', 'standard', 'private', 'ward', 'general', 
                    'icu', 'emergency', 'consultation', 'operating', 
                    'maternity', 'pediatrics'
                ) DEFAULT 'private'
            ");
        }
        
        if (!empty($columnsToAdd)) {
            $this->forge->addColumn('rooms', $columnsToAdd);
        }
    }
    
    public function down()
    {
        // Check if table exists
        if (!$this->db->tableExists('rooms')) {
            return;
        }
        
        $fields = $this->db->getFieldNames('rooms');
        $columnsToDrop = [];
        
        // Only drop if they exist
        if (in_array('is_active', $fields)) {
            $columnsToDrop[] = 'is_active';
        }
        if (in_array('current_occupancy', $fields)) {
            $columnsToDrop[] = 'current_occupancy';
        }
        
        if (!empty($columnsToDrop)) {
            $this->forge->dropColumn('rooms', $columnsToDrop);
        }
    }
}

