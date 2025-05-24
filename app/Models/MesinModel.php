<?php 

namespace App\Models;

use CodeIgniter\Model;

class MesinModel extends Model
{

    protected $table         = 'tb_mesin';
    protected $primaryKey    = 'IdMesin';
    protected $allowedFields = ['IdMesin', 'NoMesin', 'UserInput'];

    public function getMaxCode () {
        $result = $this->db->table($this->table)
                  ->select("MAX(RIGHT(IdMesin, 3)) as max_code")
                  ->like('IdMesin', 'M', 'after')
                  ->get()->getRow();

        $maxCode = $result->max_code ?? '000';

        return 'M'.str_pad(((int)$maxCode) + 1, 3, '0', STR_PAD_LEFT);
    }

    public function getRow($id) {
        $result = $this->db->table($this->table)->where('IdMesin', $id)->get()->getRow();

        return $result;
    }

    public function getResult() {
        $result = $this->db->table($this->table)->select('IdMesin, NoMesin')->orderBy('NoMesin')->get()->getResult();

        return $result;
    }

}
