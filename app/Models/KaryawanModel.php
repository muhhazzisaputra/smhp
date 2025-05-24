<?php

namespace App\Models;

use CodeIgniter\Model;

class KaryawanModel extends Model
{

	protected $table         = 'tb_karyawan';
	protected $primaryKey    = 'IdKaryawan';
	protected $allowedFields = ['IdKaryawan','NamaKaryawan','IdDepartemen','IdJabatan','NoHp','Alamat','Foto','Role','Password','UserInput','TglInput','UserEdit','TglEdit'];

}