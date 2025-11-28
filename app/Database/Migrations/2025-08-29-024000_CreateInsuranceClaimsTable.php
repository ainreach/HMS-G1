<?php
namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInsuranceClaimsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [ 'type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true ],
            'claim_no' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'null' => false ],
            'invoice_no' => [ 'type' => 'VARCHAR', 'constraint' => 50, 'null' => true ],
            'patient_name' => [ 'type' => 'VARCHAR', 'constraint' => 120, 'null' => false ],
            'provider' => [ 'type' => 'VARCHAR', 'constraint' => 120, 'null' => true ],
            'policy_no' => [ 'type' => 'VARCHAR', 'constraint' => 80, 'null' => true ],
            'amount_claimed' => [ 'type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00' ],
            'amount_approved' => [ 'type' => 'DECIMAL', 'constraint' => '10,2', 'null' => false, 'default' => '0.00' ],
            'status' => [ 'type' => 'VARCHAR', 'constraint' => 20, 'null' => false, 'default' => 'submitted' ],
            'submitted_at' => [ 'type' => 'DATETIME', 'null' => false ],
            'created_at' => [ 'type' => 'DATETIME', 'null' => true ],
            'updated_at' => [ 'type' => 'DATETIME', 'null' => true ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('claim_no');
        $this->forge->createTable('insurance_claims', true);
    }

    public function down()
    {
        $this->forge->dropTable('insurance_claims', true);
    }
}
