<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBillingItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'billing_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'item_type' => [
                'type'       => 'ENUM',
                'constraint' => ['consultation', 'procedure', 'medication', 'lab_test', 'room_charge', 'other'],
            ],
            'item_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '200',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'constraint' => 5,
                'default'    => 1,
            ],
            'unit_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'total_price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('billing_id');
        $this->forge->createTable('billing_items');
    }

    public function down()
    {
        $this->forge->dropTable('billing_items');
    }
}
