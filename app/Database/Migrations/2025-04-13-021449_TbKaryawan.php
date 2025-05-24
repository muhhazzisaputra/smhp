<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TbKaryawan extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IdKaryawan' => [
                'type'       => 'VARCHAR',
                'constraint' => '5'
            ],
            'NamaKaryawan' => [
                'type'       => 'VARCHAR',
                'constraint' => '50'
            ],
            'IdDepartemen' => [
                'type'       => 'INT',
                'constraint' => 1
            ],
            'IdJabatan' => [
                'type'       => 'INT',
                'constraint' => 1
            ],
            'NoHp' => [
                'type'       => 'VARCHAR',
                'constraint' => '15',
                'null'       => true
            ],
            'Alamat' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
                'null'       => true
            ],
            'Foto' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true
            ],
            'Role' => [
                'type'       => 'INT',
                'constraint' => 1
            ],
            'Password' => [
                'type'       => 'VARCHAR',
                'constraint' => '60'
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
            ]
        ]);

        $this->forge->addKey('IdKaryawan', TRUE);
        $this->forge->createTable('Tb_Karyawan');
    }

    public function down()
    {
        $this->forge->dropTable('Tb_Karyawan');
    }
}
