<?php

namespace App\Controllers;
use CodeIgniter\DateTime;

class Dashboard extends BaseController
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();

        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index()
    {
        $data['judul'] = 'Dashboard';
        $year          = date("Y");
        $data["tahun"] = $year; 

        $data['total_hasil']    = $this->db->query("SELECT SUM(a.QtyHasil) as TotalHasil FROM tb_hasil_produksi a WHERE YEAR(a.TglProduksi)=$year")->getRow()->TotalHasil;
        $data['total_waste']    = $this->db->query("SELECT SUM(a.QtyWaste) as TotalWaste FROM tb_hasil_produksi a WHERE YEAR(a.TglProduksi)=$year")->getRow()->TotalWaste;
        $data['total_produk']   = $this->db->query("SELECT COUNT(DISTINCT a.IdProduk) as TotalProduk FROM tb_hasil_produksi a WHERE YEAR(a.TglProduksi)=$year")->getRow()->TotalProduk;
        $data['total_operator'] = $this->db->query("SELECT COUNT(DISTINCT a.IdKaryawan) as TotalOperator FROM tb_hasil_produksi a WHERE YEAR(a.TglProduksi)=$year")->getRow()->TotalOperator;

        $hasilProduksi = $this->db->query("SELECT MONTH(a.TglProduksi) AS BulanProduksi, SUM(a.QtyHasil) AS TotalHasil, SUM(a.QtyWaste) AS TotalWaste
            FROM `tb_hasil_produksi` a
            WHERE YEAR(a.TglProduksi)=$year
            GROUP BY MONTH(a.TglProduksi)
            ORDER BY MONTH(a.TglProduksi)")->getResult();

        $arrBulanHasil = [];
        $arrQtyHasil   = [];
        $arrBulanWaste = [];
        $arrQtyWaste   = [];
        foreach($hasilProduksi as $key => $val) {
            $bulanProduksi   = $val->BulanProduksi;
            $arrBulanHasil[] = "'".date("F", mktime(0, 0, 0, $bulanProduksi, 1))."'";

            $arrQtyHasil[] = $val->TotalHasil;
            $arrQtyWaste[] = $val->TotalWaste;
        }

        $data["bulan_produksi"] = implode(",", $arrBulanHasil);
        $data["hasil_produksi"] = implode(",", $arrQtyHasil);
        $data["waste_produksi"] = implode(",", $arrQtyWaste);
        // d($arrBulanHasil);

        return view('v_dashboard', $data);
    }

}
