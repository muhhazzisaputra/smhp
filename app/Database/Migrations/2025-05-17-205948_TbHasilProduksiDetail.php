<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbHasilProduksiDetail extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdProduksi'   => ['type' => 'VARCHAR', 'constraint' => '13'],
            'NoUrut'       => ['type' => 'INT', 'constraint' => 2],
            'Jam'          => ['type' => 'TIME'],
            'Jam2'         => ['type' => 'TIME'],
            'Speed'        => ['type' => 'INT', 'constraint' => 11],
            'Temperatur'   => ['type' => 'DECIMAL', 'constraint' => 10,2],
            'IdKategoriNG' => ['type' => 'INT', 'constraint' => 2],
            'QtyNG'        => ['type' => 'DECIMAL', 'constraint' => '10,2'],
            'Keterangan'   => ['type' => 'VARCHAR', 'constraint' => '150']
        ]);

        $this->forge->addKey(['IdProduksi', 'NoUrut'], TRUE);
        $this->forge->createTable('tb_hasil_produksi_detail');
    }

    public function down()
    {
        $this->forge->dropTable('tb_hasil_produksi_detail');
    }
}
