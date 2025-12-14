<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCancellationReasonToAppointments extends Migration
{
    public function up()
    {
        $fields = [
            'cancellation_reason' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status',
            ],
        ];
        $this->forge->addColumn('appointments', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('appointments', 'cancellation_reason');
    }
}
