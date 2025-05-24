<?php

namespace App\Database\Seeds; 

use CodeIgniter\Database\Seeder;
use CodeIgniter\I18n\Time;

class TbKaryawanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            'IdKaryawan'   => '00001',
            'NamaKaryawan' => 'Admin IT',
            'IdDepartemen' => 1,
            'IdJabatan'    => 1,
            'NoHp'         => '081213141516',
            'Role'         => 1,
            'Password'     => '12345',
            'UserInput'    => '00001',
            'TglInput'     => Time::now()
        ];

        // Simple Queries
        // $this->db->query('INSERT INTO users (username, email) VALUES(:username:, :email:)', $data);

        // Using Query Builder
        $this->db->table('tb_karyawan')->insert($data);
    }
}
