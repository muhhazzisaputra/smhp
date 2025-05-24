<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbJabatan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdJabatan' => [
                'type'       => 'INT',
                'constraint' => 1
            ],
            'NamaJabatan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ]
        ]);

        $this->forge->addKey('IdJabatan', TRUE);
        $this->forge->createTable('Tb_Jabatan');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Jabatan');
    }
}
