<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMaritalStatusToPatients extends Migration
{
    public function up()
    {
        $this->forge->addColumn('patients', [
            'marital_status' => [
                'type' => 'VARCHAR',
                'constraint' => '50',
                'null' => true,
                'after' => 'gender',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('patients', 'marital_status');
    }
}
