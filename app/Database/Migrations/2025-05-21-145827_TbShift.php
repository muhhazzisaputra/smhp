<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbShift extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdShift'    => ['type' => 'INT', 'constraint' => 1],
            'JamMulai'   => ['type' => 'TIME'],
            'JamSelesai' => ['type' => 'TIME']
        ]);

        $this->forge->addKey('IdShift', TRUE);
        $this->forge->createTable('tb_shift');
    }

    public function down()
    {
        $this->forge->dropTable('tb_shift');
    }
}
