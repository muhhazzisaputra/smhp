<?php

namespace App\Controllers;

class Dashboard extends BaseController
{

    public function __construct()
    {
        $this->db = \Config\Database::connect();

        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index()
    {
        $data['judul']          = 'Dashboard';

        $data['total_hasil']    = $this->db->query("SELECT SUM(a.QtyHasil) as TotalHasil FROM tb_hasil_produksi a WHERE LEFT(a.TglProduksi,7)='2025-06'")->getRow()->TotalHasil;
        $data['total_waste']    = $this->db->query("SELECT SUM(a.QtyWaste) as TotalWaste FROM tb_hasil_produksi a WHERE LEFT(a.TglProduksi,7)='2025-06'")->getRow()->TotalWaste;
        $data['total_produk']   = $this->db->query("SELECT COUNT(DISTINCT a.IdProduk) as TotalProduk FROM tb_hasil_produksi a WHERE LEFT(a.TglProduksi,7)='2025-06'")->getRow()->TotalProduk;
        $data['total_operator'] = $this->db->query("SELECT COUNT(DISTINCT a.IdKaryawan) as TotalOperator FROM tb_hasil_produksi a WHERE LEFT(a.TglProduksi,7)='2025-06'")->getRow()->TotalOperator;

        return view('v_dashboard', $data);
    }

}
