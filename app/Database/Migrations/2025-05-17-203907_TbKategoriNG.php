<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbKategoriNG extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdKategori'   => ['type' => 'INT', 'constraint' => 2],
            'NamaKategori' => ['type' => 'VARCHAR', 'constraint' => '30'],
            'UserInput'    => ['type' => 'VARCHAR','constraint' => '5', 'null' => true],
            'TglInput'     => ['type' => 'DATETIME','null' => true],
            'UserEdit'     => ['type' => 'VARCHAR','constraint' => '5','null' => true],
            'TglEdit'      => ['type' => 'DATETIME','null' => true]
        ]);

        $this->forge->addKey('IdKategori', TRUE);
        $this->forge->createTable('tb_kategori_ng');
    }

    public function down()
    {
         $this->forge->dropTable('tb_kategori_ng');
    }
}
