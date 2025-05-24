<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbDepartemen extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdDepartemen' => [
                'type'       => 'INT',
                'constraint' => 1
            ],
            'NamaDepartemen' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ]
        ]);

        $this->forge->addKey('IdDepartemen', TRUE);
        $this->forge->createTable('Tb_Departemen');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Departemen');
    }
}
