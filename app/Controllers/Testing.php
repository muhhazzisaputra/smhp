<?php


namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\HasilProduksiModel;
use App\Models\MesinModel;
use App\Models\PegawaiModel;
use App\Models\ProdukModel;
use App\Models\ShiftModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Reader\Html;

class Testing extends BaseController
{

	public function __construct() {
        $this->db                 = \Config\Database::connect();
        $this->HasilProduksiModel = new HasilProduksiModel();
        $this->MesinModel         = new MesinModel();
        $this->PegawaiModel       = new PegawaiModel();
        $this->ProdukModel        = new ProdukModel();
        $this->ShiftModel         = new ShiftModel();

        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index() {
        $bulan = 5;
        $this->ShiftModel = new ShiftModel();

        $builder = $this->db->table('tb_hasil_produksi a');
        $builder->select("a.IdProduk, b.NamaProduk, a.Shift, SUM(a.QtyHasil) as total_qty");
        $builder->join('tb_produk b', 'b.IdProduk = a.IdProduk', 'left');
        $builder->where('MONTH(a.TglProduksi)', $bulan);
        // if($produk_src) {
        //     $builder->where('a.IdProduk', $produk_src);
        // }
        // if($shift_src) {
        //     $builder->where('a.IdShift', $shift_src);
        // }
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

        echo '<pre>';
        print_r($pivot);
        die;
    }

}