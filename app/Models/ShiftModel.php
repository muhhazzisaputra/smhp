<?php

namespace App\Models;

use CodeIgniter\Model;

class ShiftModel extends Model
{

    protected $table      = 'tb_shift';
    protected $primaryKey = 'IdShift';

    public function getRow($id) {
        $result = $this->db->table($this->table)->where('IdShift', $id)->get()->getRow();

        return $result;
    }

    public function getResult() {
        $result = $this->db->table($this->table)->select('IdShift, JamMulai, JamSelesai')->orderBy('IdShift')->get()->getResult();

        return $result;
    }

}
