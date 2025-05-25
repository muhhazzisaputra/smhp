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

class HasilProduksi extends BaseController
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
        $data['judul']     = 'Data Hasil Produksi';
        $data['mesin']     = $this->MesinModel->getResult();
        $data['operator']  = $this->PegawaiModel->getResult(2);
        $data['inspector'] = $this->PegawaiModel->getResult(3);
        $data['shift']     = $this->ShiftModel->getResult();

        return view('hasil_produksi/v_hasil_produksi_list', $data);
    }

    public function datatables() {
        $request = service('request');

        $builder = $this->sql_list();

        // Total records
        $totalRecords = $builder->countAllResults(false); // keep query

        // Ordering
        // $order = $request->getPost('order');
        // if ($order) {
        $builder->orderBy('a.TglProduksi', 'DESC');
        $builder->orderBy('a.Shift', 'ASC');
        $builder->orderBy('b.NoMesin', 'ASC');
        // }

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

        $html     = view('hasil_produksi/v_hasil_produksi_list_xls', ['list' => $data]);
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
            ->setHeader('Content-Disposition', 'attachment;filename="List Hasil Produksi.xlsx"')
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
        
        $builder = $this->db->table('tb_hasil_produksi a');
        $builder->select('a.IdProduksi, a.Shift, b.NoMesin, c.NamaKaryawan as NamaOperator, a.QtyHasil, a.QtyWaste, a.KdProduksi_RefBahan, a.Qty_RefBahan, a.QtySisa_RefBahan, d.NamaKaryawan as NamaQc, e.NamaKaryawan as UserInput, f.NamaKaryawan as UserEdit, g.NamaProduk');
        $builder->select("DATE_FORMAT(a.TglInput, '%d %b %Y %H:%i') as TglInput");
        $builder->select("DATE_FORMAT(a.TglEdit, '%d %b %Y %H:%i') as TglEdit");
        $builder->select("DATE_FORMAT(a.TglProduksi, '%d %b %Y') as TglProduksi");
        $builder->join('tb_mesin b', 'b.IdMesin = a.IdMesin', 'left');
        $builder->join('tb_produk g', 'g.IdProduk = a.IdProduk', 'left');
        $builder->join('tb_karyawan c', 'c.IdKaryawan = a.IdKaryawan', 'left');
        $builder->join('tb_karyawan d', 'd.IdKaryawan = a.IdQc', 'left');
        $builder->join('tb_karyawan e', 'e.IdKaryawan=a.UserInput', 'left');
        $builder->join('tb_karyawan f', 'f.IdKaryawan=a.UserEdit', 'left');
        // $builder->orderBy('a.TglProduksi desc', 'a.Shift asc', 'a.NoMesin asc');

        $id_produksi_src = $request->getPost('id_produksi_src');
        if (!empty($id_produksi_src)) {
            $builder->like('a.IdProduksi', $id_produksi_src);
        }

        $no_mesin_src = $request->getPost('no_mesin_src');
        if (!empty($no_mesin_src)) {
            $builder->where('a.IdMesin', $no_mesin_src);
        }

        $shift_src = $request->getPost('shift_src');
        if (!empty($shift_src)) {
            $builder->where('a.Shift', $shift_src);
        }

        $operator_src = $request->getPost('operator_src');
        if (!empty($operator_src)) {
            $builder->where('a.IdKaryawan', $operator_src);
        }

        $produk_src = strtolower($request->getPost('produk_src'));
        if (!empty($produk_src)) {
            $builder->like('a.IdProduk', $produk_src)->orLike('g.NamaProduk', $produk_src);
        }

        $qty_hasil_src = $request->getPost('qty_hasil_src');
        if (!empty($qty_hasil_src)) {
            if($qty_hasil_src=="lebih") {
                $builder->where('a.QtyHasil >=', 30);
            } else {
                $builder->where('a.QtyHasil <', 30);
            }
        }

        $tgl_src  = $request->getPost('tgl_src');
        $tgl2_src = $request->getPost('tgl2_src');

        $tgl_src  = (!empty($tgl_src)) ? $tgl_src : date('Y-m-01');
        $tgl2_src = (!empty($tg2l_src)) ? $tgl2_src : date('Y-m-d');

        $builder->where("a.TglProduksi BETWEEN '$tgl_src' AND  '$tgl2_src'");

        return $builder;
    }

    public function form_data() {
        $jenis = $this->request->getPost('jenis');
        $date  = date('Y-m-d');

        $data['mesin']     = $this->MesinModel->getResult();
        $data['operator']  = $this->PegawaiModel->getResult(2);
        $data['inspector'] = $this->PegawaiModel->getResult(3);
        $data['produk']    = $this->ProdukModel->getResult();
        $data['shift']     = $this->ShiftModel->getResult();

        $kategoriNg = $result = $this->db->table('tb_kategori_ng')->select('IdKategori, NamaKategori')->orderBy('IdKategori')->get()->getResult();

        $opt_ng = '<option value="">-Pilih-</option>';
        foreach($kategoriNg as $kng) {
            $opt_ng .= '<option value="'.$kng->IdKategori.'">'.$kng->NamaKategori.'</option>';
        }

        $data['kategori_ng'] = $opt_ng;

        $builder   = $this->db->table('tb_hasil_produksi');
        $shiftRows = $builder->select('Shift')
                     ->where('TglProduksi', $date)
                     ->get()
                     ->getResultArray();

        // Extract shift values into a flat array
        $existingShifts = array_column($shiftRows, 'Shift');

        // Filter out used shifts
        // $allShifts               = ['1', '2', '3'];
        // $data['availableShifts'] = array_diff($allShifts, $existingShifts);
        $data['availableShifts'] = ['1', '2', '3'];

        if($jenis=="input") {
            echo view('hasil_produksi/v_hasil_produksi_input', $data);
        } else {
            $id_produksi = $this->request->getPost('id_produksi');

            $data['hasil']          = $this->HasilProduksiModel->getRow($id_produksi);
            $data['detail']         = $this->HasilProduksiModel->getResultDetail($id_produksi);
            $data['ms_kategori_ng'] = $kategoriNg;
            
            echo view('hasil_produksi/v_hasil_produksi_edit', $data);
        }
    }

    public function cek_tgl_shift() {
        $tgl_produksi = $this->request->getPost('tgl_produksi');
        $shift        = $this->request->getPost('shift');
        $id_mesin     = $this->request->getPost('id_mesin');

        $produksi = $this->db->table('tb_hasil_produksi')
                    ->where(['TglProduksi' => $tgl_produksi, 'Shift' => $shift, 'IdMesin' => $id_mesin])
                    ->get()->getNumRows();

        $result['status'] = ($produksi > 0) ? "double" : "ok";

        return $this->response->setJSON($result);
    }

    public function jam_shift() {
        $id_shift = $this->request->getPost('id_shift');

        $JamMulai   = "";
        $JamSelesai = "";
        if($id_shift) {
            $shift = $this->ShiftModel->getRow($id_shift);
            $JamMulai   = substr($shift->JamMulai, 0, 5);
            $JamSelesai = substr($shift->JamSelesai, 0, 5);
        }

        $result = ['JamMulai' => $JamMulai, 'JamSelesai' => $JamSelesai];

        return $this->response->setJSON($result);
    }

    public function simpan_data() {
        $tgl_produksi       = $this->request->getPost('tgl_produksi');
        $shift              = $this->request->getPost('shift');
        $id_mesin           = $this->request->getPost('id_mesin');
        $id_pegawai         = $this->request->getPost('id_pegawai');
        $id_produk          = $this->request->getPost('id_produk');
        $qty_hasil          = $this->request->getPost('qty_hasil');
        $qty_waste          = $this->request->getPost('qty_waste');
        $kd_produksi_bahan  = $this->request->getPost('kd_produksi_bahan');
        $qty_produksi_bahan = $this->request->getPost('qty_produksi_bahan');
        $qty_sisa_bahan     = $this->request->getPost('qty_sisa_bahan');
        $id_qc              = $this->request->getPost('id_qc');

        $jam            = $this->request->getPost('jam');
        $jam2           = $this->request->getPost('jam2');
        $speed          = $this->request->getPost('speed');
        $temperatur     = $this->request->getPost('temperatur');
        $id_kategori_ng = $this->request->getPost('id_kategori_ng');
        $qty_ng         = $this->request->getPost('qty_ng');
        $keterangan     = $this->request->getPost('keterangan');

        try {
            $this->db->transException(true)->transStart();

            $idProduksi = $this->HasilProduksiModel->getMaxCode();

            $this->db->table('tb_hasil_produksi')->insert([
                'IdProduksi'          => $idProduksi,
                'TglProduksi'         => $tgl_produksi,
                'IdKaryawan'          => $id_pegawai,
                'Shift'               => $shift, 
                'IdMesin'             => $id_mesin,
                'IdProduk'            => $id_produk,
                'QtyHasil'            => $qty_hasil,
                'QtyWaste'            => $qty_waste,
                'KdProduksi_RefBahan' => $kd_produksi_bahan,
                'Qty_RefBahan'        => $qty_produksi_bahan,
                'QtySisa_RefBahan'    => $qty_sisa_bahan,
                'IdQc'                => $id_qc,
                'UserInput'           => session('id_user'),
                'TglInput'            => date('Y:m:d H:i:s')
            ]);

            $num = 0;
            foreach($jam as $key => $value) {
                $num++;
                
                $this->db->table('tb_hasil_produksi_detail')->insert([
                    'IdProduksi'   => $idProduksi,
                    'NoUrut'       => $num,
                    'Jam'          => $jam[$key],
                    'Jam2'         => $jam2[$key], 
                    'Speed'        => $speed[$key],
                    'Temperatur'   => $temperatur[$key],
                    'IdKategoriNG' => $id_kategori_ng[$key],
                    'QtyNG'        => empty($qty_ng[$key]) ? 0 : $qty_ng[$key],
                    'Keterangan'   => empty($keterangan[$key]) ? "" : $keterangan[$key]
                ]);
            }

            $this->db->transComplete();

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function update_data() {
        $id_produksi        = $this->request->getPost('id_produksi');
        $tgl_produksi       = $this->request->getPost('tgl_produksi');
        $shift              = $this->request->getPost('shift');
        $id_mesin           = $this->request->getPost('id_mesin');
        $id_pegawai         = $this->request->getPost('id_pegawai');
        $id_produk          = $this->request->getPost('id_produk');
        $qty_hasil          = $this->request->getPost('qty_hasil');
        $qty_waste          = $this->request->getPost('qty_waste');
        $kd_produksi_bahan  = $this->request->getPost('kd_produksi_bahan');
        $qty_produksi_bahan = $this->request->getPost('qty_produksi_bahan');
        $qty_sisa_bahan     = $this->request->getPost('qty_sisa_bahan');
        $id_qc              = $this->request->getPost('id_qc');

        $jam            = $this->request->getPost('jam');
        $jam2           = $this->request->getPost('jam2');
        $speed          = $this->request->getPost('speed');
        $temperatur     = $this->request->getPost('temperatur');
        $id_kategori_ng = $this->request->getPost('id_kategori_ng');
        $qty_ng         = $this->request->getPost('qty_ng');
        $keterangan     = $this->request->getPost('keterangan');

        try {
            $this->db->transException(true)->transStart();

            $dataUpdate = [
                'TglProduksi'         => $tgl_produksi,
                'IdKaryawan'          => $id_pegawai,
                'Shift'               => $shift, 
                'IdMesin'             => $id_mesin,
                'IdProduk'            => $id_produk,
                'QtyHasil'            => $qty_hasil,
                'QtyWaste'            => $qty_waste,
                'KdProduksi_RefBahan' => $kd_produksi_bahan,
                'Qty_RefBahan'        => $qty_produksi_bahan,
                'QtySisa_RefBahan'    => $qty_sisa_bahan,
                'IdQc'                => $id_qc,
                'UserEdit'            => session('id_user'),
                'TglEdit'             => date('Y:m:d H:i:s')
            ];

            $this->db->table('tb_hasil_produksi')->where('IdProduksi', $id_produksi)->update($dataUpdate);

            // $num = 0;
            // foreach($jam as $key => $value) {
            //     $num++;
                
            //     $this->db->table('tb_hasil_produksi_detail')->insert([
            //         'IdProduksi' => $idProduksi,
            //         'NoUrut'     => $num,
            //         'Jam'        => $jam[$key],
            //         'Jam2'       => $jam2[$key], 
            //         'Speed'      => $speed[$key],
            //         'Temperatur' => $temperatur[$key],
            //         'Keterangan' => $keterangan[$key]
            //     ]);
            // }

            $num = 0;
            foreach ($jam as $index => $val) {
                $num++;

                $this->db->table('tb_hasil_produksi_detail')->replace([
                    'IdProduksi'   => $id_produksi,
                    'NoUrut'       => $num,
                    'Jam'          => $jam[$index],
                    'Jam2'         => $jam2[$index],
                    'Speed'        => $speed[$index],
                    'Temperatur'   => $temperatur[$index],
                    'IdKategoriNG' => $id_kategori_ng[$index],
                    'QtyNG'        => $qty_ng[$index],
                    'Keterangan'   => $keterangan[$index]
                ]);

                // $setJam          = $jam[$index];
                // $setJam2         = $jam2[$index];
                // $setSpeed        = $speed[$index];
                // $setTemperatur   = $temperatur[$index];
                // $setIdKategoriNG = $id_kategori_ng[$index];
                // $setQtyNG        = $qty_ng[$index];
                // $setKeterangan   = $keterangan[$index];

                // $sql = "INSERT INTO tb_hasil_produksi_detail (IdProduksi, NoUrut, Jam, Jam2, Speed, Temperatur, IdKategoriNG, QtyNG, Keterangan)
                //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                //         ON DUPLICATE KEY UPDATE Jam = ?, Jam2 = ?, Speed = ?, Temperatur = ?, IdKategoriNG = ?, QtyNG = ?, Keterangan = ?";

                // $this->db->query($sql, [$id_produksi, $num, $setJam, $setJam2, $setSpeed, $setTemperatur, $setIdKategoriNG, $setQtyNG, $setKeterangan]);
            }

            $this->db->transComplete();

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function hapus_data() {
        $id = $this->request->getPost('id');

        try {
            $this->db->transException(true)->transStart();

            // delete detail
            $this->db->table('tb_hasil_produksi_detail')->where('IdProduksi', $id)->delete(); 

            // delete header           
            $this->db->table('tb_hasil_produksi')->where('IdProduksi', $id)->delete();            

            $this->db->transComplete();

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

}
