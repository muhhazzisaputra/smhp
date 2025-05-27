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

    public function getPivotData($date1, $date2, $produk_src="", $dateList) {
        $builder = $this->db->table('tb_hasil_produksi a');
        $builder->select("a.IdProduk, b.NamaProduk, a.TglProduksi, SUM(a.QtyHasil) as total_qty");
        $builder->join('tb_produk b', 'b.IdProduk = a.IdProduk', 'left');
        $builder->where('a.TglProduksi >=', $date1);
        $builder->where('a.TglProduksi <=', $date2);
        if($produk_src) {
            $builder->where('a.IdProduk', $produk_src);
        }
        $builder->groupBy("a.IdProduk, a.TglProduksi, b.NamaProduk");
        $builder->orderBy("b.NamaProduk");
        $query = $builder->get()->getResultArray();

        // Initialize all rows for each product with 0s for all dates
        $pivot = [];

        foreach ($query as $row) {
            $id_produk = $row['IdProduk'];
            $produk    = $row['NamaProduk'];
            $tanggal   = $row['TglProduksi'];
            $qty       = $row['total_qty'];

            if (!isset($pivot[$produk])) {
                $pivot[$produk]['Produk'] = $produk;
                foreach ($dateList as $date) {
                    $pivot[$produk][$date] = 0.00;
                }
            }

            $pivot[$produk][$tanggal]   = $qty;
            $pivot[$produk]['IdProduk'] = $id_produk;
        }

        // Ensure all products and dates are initialized even if no data
        foreach ($pivot as &$row) {
            foreach ($dateList as $date) {
                if (!isset($row[$date])) {
                    $row[$date] = 0.00;
                }
            }
        }

        return $pivot;
    }

}
