<?php

namespace App\Models;

use CodeIgniter\Model;

class HasilProduksiModel extends Model
{

    protected $table            = 'tb_hasil_produksi';
    protected $primaryKey       = 'IdProduksi';

    public function getMaxCode() {
        $prefix = 'PR' . date('Ymd'); // e.g., PR20250506

        $result = $this->db->table($this->table)
                          ->select("MAX(RIGHT(IdProduksi, 3)) as max_code")
                          ->like('IdProduksi', $prefix, 'after') // Filter by prefix
                          ->get()
                          ->getRow();

        $maxCode = $result->max_code ?? '000'; // If no existing code, start from 000
        $nextCounter = str_pad(((int)$maxCode) + 1, 3, '0', STR_PAD_LEFT); // e.g., 001

        return $prefix . $nextCounter; // e.g., PR20250506001
    }

    public function getRow($id) {
        $result = $this->db->table($this->table)->where('IdProduksi', $id)->get()->getRow();

        return $result;
    }

    public function getResultDetail($params="") {
        $builder = $this->db->table('tb_hasil_produksi_detail');
        $builder->select('IdProduksi, NoUrut, Jam, Jam2, Speed, Temperatur, IdKategoriNG, QtyNG, Keterangan');
        $result = $builder->where('IdProduksi', $params)->orderBy('NoUrut')->get()->getResult();

        return $result;
    }

}
