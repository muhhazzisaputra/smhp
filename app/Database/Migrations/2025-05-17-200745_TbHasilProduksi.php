<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbHasilProduksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdProduksi'          => ['type' => 'VARCHAR','constraint' => '13'],
            'TglProduksi'         => ['type' => 'DATE'],
            'IdKaryawan'          => ['type' => 'VARCHAR','constraint' => '5'],
            'Shift'               => ['type' => 'CHAR','constraint' => '1'],
            'IdMesin'             => ['type' => 'VARCHAR','constraint' => '4'],
            'IdProduk'            => ['type' => 'VARCHAR','constraint' => '4'],
            'QtyHasil'            => ['type' => 'DECIMAL','constraint' => 10,2],
            'QtyWaste'            => ['type' => 'DECIMAL','constraint' => 10,2],
            'KdProduksi_RefBahan' => ['type' => 'VARCHAR','constraint' => '11','null' => true],
            'Qty_RefBahan'        => ['type' => 'DECIMAL','constraint' => 10,2],
            'QtySisa_RefBahan'    => ['type' => 'DECIMAL','constraint' => 10,2],
            'IdQc'                => ['type' => 'VARCHAR','constraint' => '5'],
            'UserInput'           => ['type' => 'VARCHAR','constraint' => '5', 'null' => true],
            'TglInput'            => ['type' => 'DATETIME','null' => true],
            'UserEdit'            => ['type' => 'VARCHAR','constraint' => '5','null' => true],
            'TglEdit'             => ['type' => 'DATETIME','null' => true]
        ]);

        $this->forge->addKey('IdProduksi', TRUE);
        $this->forge->createTable('tb_hasil_produksi');
    }

    public function down()
    {
        $this->forge->dropTable('tb_hasil_produksi');
    }
}
