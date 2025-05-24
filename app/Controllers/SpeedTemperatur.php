<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\MesinModel;
use App\Models\PegawaiModel;
use App\Models\ProdukModel;
use App\Models\KategoriNgModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Reader\Html;

class SpeedTemperatur extends BaseController
{

    public function __construct() {
        $this->db              = \Config\Database::connect();
        $this->MesinModel      = new MesinModel();
        $this->PegawaiModel    = new PegawaiModel();
        $this->ProdukModel     = new ProdukModel();
        $this->KategoriNgModel = new KategoriNgModel();

        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index() {
        $data['judul']       = 'Data Hasil Produksi';
        $data['mesin']       = $this->MesinModel->getResult();
        $data['operator']    = $this->PegawaiModel->getResult(2);
        $data['inspector']   = $this->PegawaiModel->getResult(3);
        $data['produk']      = $this->ProdukModel->getResult();
        $data['kategori_ng'] = $this->KategoriNgModel->getResult();

        $data['judul'] = 'List Speed & Temperatur';

        return view('speed_temperatur/v_speed_temperatur_list', $data);
    }

    public function datatables() {
        $request = service('request');

        $builder = $this->sql_list();

        // Total records
        $totalRecords = $builder->countAllResults(false); // keep query

        // Ordering
        // $order = $request->getPost('order');
        // if ($order) {
        //     $builder->orderBy($columns[$order[0]['column']], $order[0]['dir']);
        // }
        $builder->orderBy('c.TglProduksi', 'DESC');
        $builder->orderBy('c.Shift', 'ASC');
        $builder->orderBy('d.NoMesin', 'ASC');

        // Limit
        $length = $request->getPost('length');
        $start  = $request->getPost('start');
        $builder->limit($length, $start);

        $data = $builder->get()->getResult();

        $json = [
            "draw"            => intval($request->getPost('draw')),
            "recordsTotal"    => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data"            => $data,
        ];

        return $this->response->setJSON($json);
    }

    public function export_xls() {
        ob_start();

        $data = $this->sql_list()->get()->getResult();

        $html     = view('speed_temperatur/v_speed_temperatur_list_xls', ['list' => $data]);
        // die;
        $tempFile = tempnam(sys_get_temp_dir(), 'html');
        file_put_contents($tempFile, $html);
        $numberFormat = '#,##0.00';
        $reader       = new Html();
        $spreadsheet  = $reader->load($tempFile);

        unlink($tempFile);

        $sheet       = $spreadsheet->getActiveSheet();
        $lastColumn  = $sheet->getHighestColumn();
        $lastRow     = $sheet->getHighestRow();
        $array_alpha = ["H","I"];
        for ($i=1; $i <= $lastRow ; $i++) {
            foreach(range('A', $lastColumn) as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
                $cek_alpha = in_array($columnID,$array_alpha);
                if($cek_alpha){
                    $sheet->getStyle($columnID.$i)->getNumberFormat()->setFormatCode($numberFormat);
                }
            }
        }

        $cellRange = 'A1:' . $lastColumn . $lastRow;
        $style     = $spreadsheet->getActiveSheet()->getStyle($cellRange);
        $borders   = $style->getBorders();
        $borders->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);

        // Clean output buffer to avoid corruption
        ob_end_clean();

        // Headers to force download
        return $this->response
            ->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
            ->setHeader('Content-Disposition', 'attachment;filename="List Speed dan Temperatur.xlsx"')
            ->setHeader('Cache-Control', 'max-age=0')
            ->setBody($this->writeToOutput($writer));
    }

    private function writeToOutput($writer) {
        ob_start();
        $writer->save('php://output');
        return ob_get_clean();
    }

    public function sql_list() {
        $request = service('request');

        $db = \Config\Database::connect();
        $builder = $db->table('tb_hasil_produksi_detail a');
        $builder->select('a.IdProduksi, c.Shift, d.NoMesin, LEFT(a.Jam,5) as Jam, LEFT(a.Jam2,5) as Jam2, a.Speed, a.Temperatur, a.QtyNG, a.Keterangan, b.NamaKategori, e.NamaProduk, f.NamaKaryawan as NamaOperator');
        $builder->select("DATE_FORMAT(c.TglProduksi, '%d %b %Y') as TglProduksi");
        $builder->join('tb_kategori_ng b', 'b.IdKategori=a.IdKategoriNG', 'left');
        $builder->join('tb_hasil_produksi c', 'c.IdProduksi=a.IdProduksi', 'left');
        $builder->join('tb_mesin d', 'd.Idmesin=c.IdMesin', 'left');
        $builder->join('tb_produk e', 'e.IdProduk = c.IdProduk', 'left');
        $builder->join('tb_karyawan f', 'f.IdKaryawan = c.IdKaryawan', 'left');

        $id_produksi_src = $request->getPost('id_produksi_src');
        if (!empty($id_produksi_src)) {
            $builder->like('a.IdProduksi', $id_produksi_src);
        }

        $no_mesin_src = $request->getPost('no_mesin_src');
        if (!empty($no_mesin_src)) {
            $builder->where('c.IdMesin', $no_mesin_src);
        }

        $shift_src = $request->getPost('shift_src');
        if (!empty($shift_src)) {
            $builder->where('c.Shift', $shift_src);
        }

        $operator_src = $request->getPost('operator_src');
        if (!empty($operator_src)) {
            $builder->where('c.IdKaryawan', $operator_src);
        }

        $produk_src = strtolower($request->getPost('produk_src'));
        if (!empty($produk_src)) {
            $builder->like('a.IdProduk', $produk_src)->orLike('e.NamaProduk', $produk_src);
        }

        $id_kategori_ng = $request->getPost('id_kategori_ng');
        if (!empty($id_kategori_ng)) {
            $builder->where('a.IdKategoriNG', $id_kategori_ng);
        }

        $lebih_atau_sama = $request->getPost('lebih_atau_sama');
        $qty_ng_src      = $request->getPost('qty_ng_src');
        if (!empty($qty_ng_src)) {
            if($lebih_atau_sama=="kurang_sama") {
                $builder->where('a.QtyNG <=', $qty_ng_src);
            } else {
                $builder->where('a.QtyNG >=', $qty_ng_src);
            }
        }

        $tgl_src  = $request->getPost('tgl_src');
        $tgl2_src = $request->getPost('tgl2_src');

        $tgl_src  = (!empty($tgl_src)) ? $tgl_src : date('Y-m-01');
        $tgl2_src = (!empty($tg2l_src)) ? $tgl2_src : date('Y-m-d');

        $builder->where("c.TglProduksi BETWEEN '$tgl_src' AND  '$tgl2_src'");

        return $builder;
    }

}
