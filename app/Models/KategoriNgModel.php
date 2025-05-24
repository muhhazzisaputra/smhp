<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriNgModel extends Model
{

    protected $table         = 'tb_kategori_ng';
    protected $primaryKey    = 'IdKategori';
    protected $allowedFields = ['IdKategori', 'NamaKategori', 'UserInput'];

    public function getResult() {
        $result = $this->db->table($this->table)->select('IdKategori, NamaKategori')->orderBy('IdKategori')->get()->getResult();

        return $result;
    }

}
