<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TbJabatanSeeder extends Seeder
{
    public function run() 
    {
        $data = [
            [
            'IdJabatan'   => '1',
            'NamaJabatan' => 'Administrator'
            ],
            [
            'IdJabatan'   => '2',
            'NamaJabatan' => 'Kashift'
            ],
            [
            'IdJabatan'   => '3',
            'NamaJabatan' => 'Operator'
            ]
        ];

        $this->db->table('tb_jabatan')->insertBatch($data);
    }
}
