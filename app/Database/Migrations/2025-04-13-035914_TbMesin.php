<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbMesin extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdMesin' => [
                'type'       => 'VARCHAR',
                'constraint' => '4'
            ],
            'NoMesin' => [
                'type'       => 'INT',
                'constraint' => 2
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

        $this->forge->addKey('IdMesin', TRUE);
        $this->forge->createTable('Tb_Mesin');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Mesin');
    }
}
