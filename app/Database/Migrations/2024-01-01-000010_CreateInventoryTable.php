<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInventoryTable extends Migration
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
            'medicine_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'branch_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'batch_number' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'expiry_date' => [
                'type' => 'DATE',
            ],
            'quantity_in_stock' => [
                'type'       => 'INT',
                'constraint' => 8,
                'default'    => 0,
            ],
            'minimum_stock_level' => [
                'type'       => 'INT',
                'constraint' => 8,
                'default'    => 10,
            ],
            'maximum_stock_level' => [
                'type'       => 'INT',
                'constraint' => 8,
                'default'    => 1000,
            ],
            'reorder_level' => [
                'type'       => 'INT',
                'constraint' => 8,
                'default'    => 20,
            ],
            'location' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
                'comment'    => 'Storage location like shelf, room, etc.',
            ],
            'last_updated_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
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
        $this->forge->addKey(['medicine_id', 'branch_id']);
        $this->forge->addKey('expiry_date');
        $this->forge->createTable('inventory');
    }

    public function down()
    {
        $this->forge->dropTable('inventory');
    }
}
