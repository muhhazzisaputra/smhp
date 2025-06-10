<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukModel extends Model
{
    
    protected $table      = 'tb_produk';
    protected $primaryKey = 'IdProduk';

    public function getMaxCode () {
        $result = $this->db->table($this->table)
                  ->select("MAX(RIGHT(IdProduk, 3)) as max_code")
                  ->like('IdProduk', 'P', 'after')
                  ->get()->getRow();

        $maxCode = $result->max_code ?? '000';

        return 'P'.str_pad(((int)$maxCode) + 1, 3, '0', STR_PAD_LEFT);
    }

    public function getRow($id) {
        $result = $this->db->table($this->table)->where('IdProduk', $id)->get()->getRow();

        return $result;
    }

    public function getResult($params="") {
        $builder = $this->db->table($this->table);
        $builder->select('IdProduk, NamaProduk');
        $result = $builder->orderBy('NamaProduk')->get()->getResult();

        return $result;
    }

    public function getNama($id_produk) {
        $sql = "SELECT NamaProduk FROM tb_produk WHERE IdProduk IN($id_produk)
            ORDER BY FIELD(IdProduk,$id_produk)";

        return $this->db->query($sql);
    }

}
