<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDepartmentIdToUsers extends Migration
{
    public function up()
    {
        $fields = [
            'department_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'specialization',
            ],
        ];

        $this->forge->addColumn('users', $fields);
        
        // Add foreign key constraint
        $this->forge->addForeignKey('department_id', 'departments', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        // Drop foreign key first
        $this->forge->dropForeignKey('users', 'users_department_id_foreign');
        
        // Drop column
        $this->forge->dropColumn('users', 'department_id');
    }
}

