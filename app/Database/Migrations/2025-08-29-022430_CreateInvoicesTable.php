<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvoicesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true,
            ],
            'invoice_no' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'null' => false ],
            'patient_name' => [ 'type' => 'VARCHAR', 'constraint' => 120, 'null' => false ],
            'amount' => [ 'type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00' ],
            'status' => [ 'type' => 'VARCHAR', 'constraint' => 20, 'null' => false, 'default' => 'unpaid' ],
            'issued_at' => [ 'type' => 'DATETIME', 'null' => false ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('invoice_no');
        $this->forge->createTable('invoices', true);
    }

    public function down()
    {
        $this->forge->dropTable('invoices', true);
    }
}
