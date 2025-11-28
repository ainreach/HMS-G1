<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateStaffSchedulesTable extends Migration
{
    public function up()
    {
        $fields = [
            'start_date' => [
                'type' => 'DATE',
                'null' => false,
                'after' => 'branch_id'
            ],
            'end_date' => [
                'type' => 'DATE',
                'null' => false,
                'after' => 'start_date'
            ],
            'rest_day' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => true,
                'after' => 'end_time'
            ],
            'is_recurring' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
                'after' => 'rest_day'
            ]
        ];

        $this->forge->addColumn('staff_schedules', $fields);
        
        // Add index for better performance on date range queries
        $this->db->query('ALTER TABLE staff_schedules ADD INDEX idx_date_range (start_date, end_date)');
        $this->db->query('ALTER TABLE staff_schedules ADD INDEX idx_user_rest_day (user_id, rest_day)');
    }

    public function down()
    {
        $fields = ['start_date', 'end_date', 'rest_day', 'is_recurring'];
        $this->forge->dropColumn('staff_schedules', $fields);
    }
}
