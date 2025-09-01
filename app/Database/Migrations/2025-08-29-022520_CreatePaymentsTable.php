<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true,
            ],
            'patient_name' => [ 'type' => 'VARCHAR', 'constraint' => 120, 'null' => false ],
            'invoice_no' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'null' => true ],
            'amount' => [ 'type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00' ],
            'paid_at' => [ 'type' => 'DATETIME', 'null' => false ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('payments', true);
    }

    public function down()
    {
        $this->forge->dropTable('payments', true);
    }
}
