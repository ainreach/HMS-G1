<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLabTestCatalogTable extends Migration
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
            'test_name' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'test_type' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'null'       => true,
            ],
            'specimen_category' => [
                'type'       => 'ENUM',
                'constraint' => ['with_specimen', 'without_specimen'],
                'default'    => 'with_specimen',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'normal_range' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'price' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
                'default'    => 0.00,
            ],
            'is_active' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 1,
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
        $this->forge->addKey('test_name');
        $this->forge->addKey('test_type');
        $this->forge->addKey('specimen_category');
        $this->forge->addKey('is_active');
        $this->forge->createTable('lab_test_catalog');
    }

    public function down()
    {
        $this->forge->dropTable('lab_test_catalog');
    }
}

