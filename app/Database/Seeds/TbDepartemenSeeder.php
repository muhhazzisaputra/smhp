<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TbDepartemenSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
            'IdDepartemen'   => 1,
            'NamaDepartemen' => 'Information & Technology'
            ],
            [
            'IdDepartemen'   => 2,
            'NamaDepartemen' => 'Produksi'
            ],
            [
            'IdDepartemen'   => 3,
            'NamaDepartemen' => 'Quality Control'
            ]
        ];

        $this->db->table('tb_departemen')->insertBatch($data);
    }
}
