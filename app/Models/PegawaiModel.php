<?php

namespace App\Models;

use CodeIgniter\Model;

class PegawaiModel extends Model
{

    protected $table         = 'tb_karyawan';
    protected $primaryKey    = 'IdKaryawan';
    // protected $allowedFields = ['IdMesin', 'NoMesin', 'UserInput'];

    public function getMaxCode () {
        $result = $this->db->table($this->table)
                  ->select("MAX(RIGHT(IdKaryawan, 5)) as max_code")
                  ->get()->getRow();

        $maxCode = $result->max_code ?? '000';

        return str_pad(((int)$maxCode) + 1, 5, '0', STR_PAD_LEFT);
    }

    public function getRow($id) {
        $result = $this->db->table($this->table)->where('IdKaryawan', $id)->get()->getRow();

        return $result;
    }

    public function getResult($params="") {
        $builder = $this->db->table($this->table);
        $builder->select('IdKaryawan, NamaKaryawan');

        if(!empty($params)) {
            $builder->where('IdJabatan', 3);
            $builder->where('IdDepartemen', $params);
        }

        $result = $builder->orderBy('NamaKaryawan')->get()->getResult();
        // $result = $this->db->table($this->table)
        //           ->select('IdKaryawan, NamaKaryawan')
        //           ->orderBy('NamaKaryawan')->get()->getResult();

        return $result;
    }

    public function getNama($id_karyawan) {
        $sql = "SELECT NamaKaryawan FROM tb_karyawan WHERE IdKaryawan IN($id_karyawan)
            ORDER BY FIELD(IdKaryawan,$id_karyawan)";

        return $this->db->query($sql);
    }

}
