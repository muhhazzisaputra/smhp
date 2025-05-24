<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbProduk extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdProduk' => [
                'type'       => 'VARCHAR',
                'constraint' => '4'
            ],
            'NamaProduk' => [
                'type'       => 'INT',
                'constraint' => 2
            ],
            'Aktif' => [
                'type'       => 'INT',
                'constraint' => 1
            ],
            'UserInput' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true
            ],
            'TglInput' => [
                'type'       => 'DATETIME',
                'null'       => true
            ],
            'UserEdit' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => true
            ],
            'TglEdit' => [
                'type'       => 'DATETIME',
                'null'       => true
            ],
        ]);

        $this->forge->addKey('IdProduk', TRUE);
        $this->forge->createTable('Tb_Produk');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Produk');
    }
}
