<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\MesinModel;
use App\Models\ShiftModel;

class HasilProduksiModel extends Model
{

    protected $table      = 'tb_hasil_produksi';
    protected $primaryKey = 'IdProduksi';

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
                $pivot[$produk]['Produk']   = $produk;
                $pivot[$produk]['IdProduk'] = $id_produk;
                foreach ($dateList as $date) {
                    $pivot[$produk][$date] = 0.00;
                }
            }

            $pivot[$produk][$tanggal]   = $qty;
        }

        // Ensure all products and dates are initialized even if no data
        foreach ($pivot as $row) {
            foreach ($dateList as $date) {
                if (!isset($row[$date])) {
                    $row[$date] = 0.00;
                }
            }
        }

        return $pivot;
    }

    function getPerTgl($tgl="", $id_produk="") {
        $sql = "SELECT a.IdProduksi,a.TglProduksi,a.Shift,a.IdMesin,a.QtyHasil,a.QtyWaste,b.NamaKaryawan,c.NoMesin,a.IdProduk,d.NamaProduk
            FROM tb_hasil_produksi a
            LEFT JOIN tb_karyawan b ON b.IdKaryawan=a.IdKaryawan
            LEFT JOIN tb_mesin c ON c.IdMesin=a.IdMesin
            LEFT JOIN tb_produk d ON d.IdProduk=a.IdProduk
            WHERE a.TglProduksi='$tgl' AND a.IdProduk='$id_produk'
            ORDER BY a.Shift,c.NoMesin";

        return $this->db->query($sql);
    }

    function getPerMesin($bulan="",$mesin_src="",$produk_src="") {
        $this->MesinModel = new MesinModel();

        $builder = $this->db->table('tb_hasil_produksi a');
        $builder->select("a.IdProduk, b.NamaProduk, a.IdMesin, SUM(a.QtyHasil) as total_qty");
        $builder->join('tb_produk b', 'b.IdProduk = a.IdProduk', 'left');
        $builder->where('MONTH(a.TglProduksi)', $bulan);
        if($produk_src) {
            $builder->where('a.IdProduk', $produk_src);
        }
        if($mesin_src) {
            $builder->where('a.IdMesin', $mesin_src);
        }
        $builder->groupBy("a.IdProduk, a.IdMesin, b.NamaProduk");
        $builder->orderBy("b.NamaProduk");
        $query = $builder->get()->getResultArray();
        // echo '<pre>';
        // print_r($query);
        // die;

        $dataMesin = $this->MesinModel->getResult();

        // Initialize all rows for each product with 0s for all dates
        $pivot = [];

        foreach ($query as $row) {
            $id_produk = $row['IdProduk'];
            $produk    = $row['NamaProduk'];
            $id_mesin  = $row['IdMesin'];
            $qty       = $row['total_qty'];

            if (!isset($pivot[$produk])) {
                $pivot[$produk]['Produk']   = $produk;
                $pivot[$produk]['IdProduk'] = $id_produk;

                foreach ($dataMesin as $mesin) {
                    $pivot[$produk][$mesin->IdMesin] = 0.00;
                }
            }

            $pivot[$produk][$id_mesin] = $qty;
        }

        return $pivot;
    }

    function detailPerMesin($bulan="",$id_mesin="",$id_produk="") {
        $sql = "SELECT a.IdProduksi,a.TglProduksi,a.Shift,a.IdMesin,a.QtyHasil,a.QtyWaste,b.NamaKaryawan,c.NoMesin,a.IdProduk
        ,d.NamaProduk
        FROM tb_hasil_produksi a
        LEFT JOIN tb_karyawan b ON b.IdKaryawan=a.IdKaryawan
        LEFT JOIN tb_mesin c ON c.IdMesin=a.IdMesin
        LEFT JOIN tb_produk d ON d.IdProduk=a.IdProduk
        WHERE MONTH(a.TglProduksi)=$bulan AND a.IdMesin='$id_mesin' AND A.IdProduk='$id_produk'
        ORDER BY a.TglProduksi DESC, a.Shift";

        return $this->db->query($sql);
    }

    function getPerShift($bulan="",$shift_src="",$produk_src="") {
        $this->ShiftModel = new ShiftModel();

        $builder = $this->db->table('tb_hasil_produksi a');
        $builder->select("a.IdProduk, b.NamaProduk, a.Shift, SUM(a.QtyHasil) as total_qty");
        $builder->join('tb_produk b', 'b.IdProduk = a.IdProduk', 'left');
        $builder->where('MONTH(a.TglProduksi)', $bulan);
        if($produk_src) {
            $builder->where('a.IdProduk', $produk_src);
        }
        if($shift_src) {
            $builder->where('a.IdShift', $shift_src);
        }
        $builder->groupBy("a.IdProduk, a.Shift, b.NamaProduk");
        $builder->orderBy("b.NamaProduk");
        $query = $builder->get()->getResultArray();
        // echo '<pre>';
        // print_r($query);
        // die;

        $dataShift = $this->ShiftModel->getResult();

        // Initialize all rows for each product with 0s for all dates
        $pivot = [];

        foreach ($query as $row) {
            $id_produk = $row['IdProduk'];
            $produk    = $row['NamaProduk'];
            $id_shift  = $row['Shift'];
            $qty       = $row['total_qty'];

            if (!isset($pivot[$produk])) {
                $pivot[$produk]['Produk']   = $produk;
                $pivot[$produk]['IdProduk'] = $id_produk;

                foreach ($dataShift as $shift) {
                    $pivot[$produk][$shift->IdShift] = 0.00;
                }
            }

            $pivot[$produk][$id_shift] = $qty;
        }

        return $pivot;
    }

    function detailPerShift($bulan="",$id_shift="",$id_produk="") {
        $sql = "SELECT a.IdProduksi,a.TglProduksi,a.Shift,a.IdMesin,a.QtyHasil,a.QtyWaste,b.NamaKaryawan,c.NoMesin,a.IdProduk
        ,d.NamaProduk
        FROM tb_hasil_produksi a
        LEFT JOIN tb_karyawan b ON b.IdKaryawan=a.IdKaryawan
        LEFT JOIN tb_mesin c ON c.IdMesin=a.IdMesin
        LEFT JOIN tb_produk d ON d.IdProduk=a.IdProduk
        WHERE MONTH(a.TglProduksi)=$bulan AND a.Shift=$id_shift AND A.IdProduk='$id_produk'
        ORDER BY a.TglProduksi DESC,c.NoMesin";

        return $this->db->query($sql);
    }

    function getPerOperator($tgl_src="",$tgl2_src="",$produk_src="",$id_pegawai="") {
        $builder = $this->db->table('tb_hasil_produksi a');
        $builder->select("a.IdProduk,a.IdKaryawan,b.NamaKaryawan,a.TglProduksi,SUM(a.QtyHasil) as total_qty");
        $builder->join('tb_karyawan b', 'b.IdKaryawan = a.IdKaryawan', 'left');
        $builder->where('a.TglProduksi >=', $tgl_src);
        $builder->where('a.TglProduksi <=', $tgl2_src);

        if($produk_src) {
            $builder->where('a.IdProduk', $produk_src);
        }

        if($id_pegawai) {
            $builder->where('a.IdKaryawan', $id_pegawai);
        }

        $builder->groupBy("a.IdProduk,a.IdKaryawan,b.NamaKaryawan,a.TglProduksi");
        $builder->orderBy("a.TglProduksi");
        $query = $builder->get()->getResultArray();

        return $query;
    }

    function detailPerOperator($tgl_produksi="",$id_operator="") {
        $sql = "SELECT a.IdProduksi,a.TglProduksi,a.Shift,b.NoMesin,c.NamaKaryawan,d.NamaProduk,a.QtyHasil,a.QtyWaste
            FROM tb_hasil_produksi a
            LEFT JOIN tb_mesin b ON b.IdMesin=a.IdMesin
            LEFT JOIN tb_karyawan c ON c.IdKaryawan=a.IdKaryawan
            LEFT JOIN tb_produk d ON d.IdProduk=a.IdProduk
            WHERE a.TglProduksi='$tgl_produksi' AND a.IdKaryawan='$id_operator'";

        return $this->db->query($sql);    
    }

    function getPerProduk($periode,$tgl_src,$tgl2_src,$produk_src="") {
        if($periode=="tanggal") {
            $builder = $this->db->table('tb_hasil_produksi a');
            $builder->select("a.IdProduk,a.TglProduksi,SUM(a.QtyHasil) as total_qty");
            $builder->join('tb_karyawan b', 'b.IdKaryawan = a.IdKaryawan', 'left');
            $builder->where('a.TglProduksi >=', $tgl_src);
            $builder->where('a.TglProduksi <=', $tgl2_src);

            if($produk_src) {
                $builder->where('a.IdProduk', $produk_src);
            }

            $builder->groupBy("a.IdProduk,a.TglProduksi");
            $builder->orderBy("a.TglProduksi");
            $query = $builder->get()->getResultArray();
        } else if($periode=="minggu") {
            $query = [];
        } else if($periode=="bulan") {
            $query = [];
        }

        return $query;
    }

}
