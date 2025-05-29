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

class LaporanProduksi extends BaseController
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
        $data['judul']     = 'Laporan Hasil Produksi';
        $data['mesin']     = $this->MesinModel->getResult();
        $data['operator']  = $this->PegawaiModel->getResult(2);
        $data['inspector'] = $this->PegawaiModel->getResult(3);

        return view('laporan_produksi/v_laporan_produksi', $data);
    }

    public function pilih_format() {
        $format         = $this->request->getPost('format');
        $data['format'] = $format;
        $data['shift']  = $this->ShiftModel->getResult();
        $data['mesin']  = $this->MesinModel->getResult();
        $data['produk'] = $this->ProdukModel->getResult();

        $data['opt_format'] = '
            <td style="width: 125px;">Format Laporan</td>
            <td style="width: 13px;">:</td>
            <td style="width: 100px;">
                <select class="form-control" name="format" id="format" style="width: 200px;" onchange="pilih_format(this)">
                    <option value="">-Pilih-</option>
                    <option value="per_tgl"'.(($format=='per_tgl') ? ' selected' : '').'>1. Produksi Per Tanggal</option>
                    <option value="per_shift"'.(($format=='per_shift') ? ' selected' : '').'>2. Produksi Per Shift</option>
                    <option value="per_mesin"'.(($format=='per_mesin') ? ' selected' : '').'>3. Produksi Per Mesin</option>
                    <option value="per_operator"'.(($format=='per_operator') ? ' selected' : '').'>4. Produksi Per Operator</option>
                    <option value="tidak_mencapai_target"'.(($format=='tidak_mencapai_target') ? ' selected' : '').'>5. Produksi Tidak Mencapai Target</option>
                </select>
            </td>';

        return view('laporan_produksi/v_laporan_produksi_format', $data);
    }

    private function generateDateRange($start, $end) {
        $dates = [];
        $current = strtotime($start);
        $end = strtotime($end);

        while ($current <= $end) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime("+1 day", $current);
        }

        return $dates;
    }

    public function view_data($xls="") {
        $format = $this->request->getPost('format');

        if($format=="per_tgl") {
            return $this->produksi_per_tgl($xls);
        } else if($format=="per_shift") {
            return $this->produksi_per_shift($xls);
        } else if($format=="per_mesin") {
            return $this->produksi_per_mesin($xls);
        }
    }

    public function produksi_per_tgl($xls="") {
        $format     = $this->request->getPost('format');
        $tgl_src    = $this->request->getPost('tgl_src');
        $tgl2_src   = $this->request->getPost('tgl2_src');
        $produk_src = $this->request->getPost('produk_src');
    
        $dateList = $this->generateDateRange($tgl_src, $tgl2_src);

        $pivotData = $this->HasilProduksiModel->getPivotData($tgl_src, $tgl2_src, $produk_src, $dateList);
        
        $data['dates'] = $dateList;
        $data['pivot'] = $pivotData;
        $data['date1'] = $tgl_src;
        $data['date2'] = $tgl2_src;
        // echo '<pre>';
        // print_r($data['dates']);
        // die;
        if($xls) {
            $html     = view('laporan_produksi/v_laporan_produksi_pertgl_xls', $data);
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
                    // $cek_alpha = in_array($columnID,$array_alpha);
                    // if($cek_alpha){
                    //     $sheet->getStyle($columnID.$i)->getNumberFormat()->setFormatCode($numberFormat);
                    // }
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
                ->setHeader('Content-Disposition', 'attachment;filename="Laporan Hasil Produksi Per Tgl.xlsx"')
                ->setHeader('Cache-Control', 'max-age=0')
                ->setBody(write_excel_to_output($writer));
        } else {
            return view('laporan_produksi/v_laporan_produksi_pertgl', $data);
        }
    }

    public function detail_hasil_pertgl($xls="") {
        $tgl_produksi = $this->request->getPost('tgl_produksi');
        $id_produk    = $this->request->getPost('id_produk');

        $data['row']    = $this->HasilProduksiModel->getPerTgl($tgl_produksi,$id_produk)->getRow();
        $data['detail'] = $this->HasilProduksiModel->getPerTgl($tgl_produksi,$id_produk)->getResult();

        if($xls) {
            $html     = view('laporan_produksi/v_laporan_produksi_pertgl_detail_xls', $data);
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
                    // $cek_alpha = in_array($columnID,$array_alpha);
                    // if($cek_alpha){
                    //     $sheet->getStyle($columnID.$i)->getNumberFormat()->setFormatCode($numberFormat);
                    // }
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
                ->setHeader('Content-Disposition', 'attachment;filename="Detail Hasil Produksi Per Tanggal.xlsx"')
                ->setHeader('Cache-Control', 'max-age=0')
                ->setBody(write_excel_to_output($writer));
        } else {
            return view('laporan_produksi/v_laporan_produksi_pertgl_detail', $data);
        }
    }

    public function produksi_per_shift() {
        // echo '<pre>';
        // print_r($_POST);
        // die;
        
    }

    public function produksi_per_mesin($xls="") {
        $format     = $this->request->getPost('format');
        $tgl_src    = $this->request->getPost('tgl_src');
        $mesin_src  = $this->request->getPost('mesin_src');
        $produk_src = $this->request->getPost('produk_src');

        $bulan = date('n', strtotime($tgl_src));

        $pivotData = $this->HasilProduksiModel->getPerMesin($bulan,$mesin_src,$produk_src);

        $data['pivot'] = $pivotData;
        $data['date1'] = $tgl_src;
        $data['mesin'] = $this->MesinModel->getResult();

        if($xls) {
            $html = view('laporan_produksi/v_laporan_produksi_permesin_xls', $data);
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
                    // $cek_alpha = in_array($columnID,$array_alpha);
                    // if($cek_alpha){
                    //     $sheet->getStyle($columnID.$i)->getNumberFormat()->setFormatCode($numberFormat);
                    // }
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
                ->setHeader('Content-Disposition', 'attachment;filename="Laporan Hasil Produksi Per Mesin.xlsx"')
                ->setHeader('Cache-Control', 'max-age=0')
                ->setBody(write_excel_to_output($writer));
        } else {
            return view('laporan_produksi/v_laporan_produksi_permesin', $data);
        }

    }

    public function detail_hasil_permesin($xls="") {
        $id_mesin  = $this->request->getPost('id_mesin');
        $id_produk = $this->request->getPost('id_produk');
        $bulan     = $this->request->getPost('bulan');
        $bulan     = date('n', strtotime($bulan));

        $data['row']    = $this->HasilProduksiModel->detailPerMesin($bulan,$id_mesin,$id_produk)->getRow();
        $data['detail'] = $this->HasilProduksiModel->detailPerMesin($bulan,$id_mesin,$id_produk)->getResult();

        if($xls) {
            $html = view('laporan_produksi/v_laporan_produksi_permesin_detail_xls', $data);
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
                    // $cek_alpha = in_array($columnID,$array_alpha);
                    // if($cek_alpha){
                    //     $sheet->getStyle($columnID.$i)->getNumberFormat()->setFormatCode($numberFormat);
                    // }
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
                ->setHeader('Content-Disposition', 'attachment;filename="Detail Hasil Produksi Per Mesin.xlsx"')
                ->setHeader('Cache-Control', 'max-age=0')
                ->setBody(write_excel_to_output($writer));
        } else {
            return view('laporan_produksi/v_laporan_produksi_permesin_detail', $data);
        }
    }

}