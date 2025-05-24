<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use App\Models\PegawaiModel;

class Pegawai extends BaseController
{

    public function __construct() {
        $this->db           = \Config\Database::connect();
        $this->PegawaiModel = new PegawaiModel();

        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index()
    {
        $data['judul'] = 'Data Karyawan';

        return view('pegawai/v_pegawai_list', $data);
    }

    public function datatables() {
        $request = service('request');

        $builder = $this->sql_list();

        // Total records
        $totalRecords = $builder->countAllResults(false); // keep query

        // Ordering
        $order = $request->getPost('order');
        if ($order) {
            $builder->orderBy($columns[$order[0]['column']], $order[0]['dir']);
        }

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
        $pegawai_src    = $this->request->getPost('pegawai_src');
        $departemen_src = $this->request->getPost('departemen_src');
        $jabatan_src    = $this->request->getPost('jabatan_src');

        $data = $this->sql_list()->get()->getResult();

        // dd($data);

        $html     = view('pegawai/v_pegawai_list_xls', ['list' => $data]);
        $tempFile = tempnam(sys_get_temp_dir(), 'html');
        file_put_contents($tempFile, $html);
        // $numberFormat = '#,##0';
        $reader       = new Html();
        $spreadsheet  = $reader->load($tempFile);

        unlink($tempFile);

        $sheet       = $spreadsheet->getActiveSheet();
        $lastColumn  = $sheet->getHighestColumn();
        $lastRow     = $sheet->getHighestRow();
        
        for ($i=1; $i <= $lastRow ; $i++) {
            foreach(range('A', $lastColumn) as $columnID) {
                $spreadsheet->getActiveSheet()->getColumnDimension($columnID)->setAutoSize(true);
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
            ->setHeader('Content-Disposition', 'attachment;filename="Data Karyawan.xlsx"')
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
        
        $builder = $this->db->table('tb_karyawan a');
        $builder->select('a.IdKaryawan,a.NamaKaryawan, a.IdDepartemen, a.IdJabatan, a.NoHp, a.Alamat, a.Foto, a.Role, a.Password, d.NamaKaryawan as UserInput, e.NamaKaryawan as UserEdit, b.NamaDepartemen, c.NamaJabatan');
        $builder->select("DATE_FORMAT(a.TglInput, '%d %b %Y %H:%i') as TglInput");
        $builder->select("DATE_FORMAT(a.TglEdit, '%d %b %Y %H:%i') as TglEdit");
        $builder->join('tb_departemen b', 'b.IdDepartemen = a.IdDepartemen', 'left');
        $builder->join('tb_jabatan c', 'c.IdJabatan = a.IdJabatan', 'left');
        $builder->join('tb_karyawan d', 'd.IdKaryawan=a.UserInput', 'left');
        $builder->join('tb_karyawan e', 'e.IdKaryawan=a.UserEdit', 'left');

        $pegawai_src    = $request->getPost('pegawai_src');
        $departemen_src = $request->getPost('departemen_src');
        $jabatan_src    = $request->getPost('jabatan_src');

        $columns = ['a.IdKaryawan', 'a.NamaKaryawan'];

        if (!empty($pegawai_src)) {
            $builder->groupStart();
            foreach ($columns as $col) {
                $builder->orLike($col, $pegawai_src);
            }
            $builder->groupEnd();
        }

        if (!empty($departemen_src)) {
            $builder->where('a.IdDepartemen', $departemen_src);
        }

        if (!empty($jabatan_src)) {
            $builder->where('a.IdJabatan', $jabatan_src);
        }

        return $builder;
    }

    public function form_data() {
        $jenis = $this->request->getPost('jenis');

        if($jenis=="input") {

            $data['maxCode'] = $this->PegawaiModel->getMaxCode();
            echo view('pegawai/v_pegawai_input', $data);
        } else {
            $id_pegawai = $this->request->getPost('id_pegawai');

            $data['pegawai'] = $this->PegawaiModel->getRow($id_pegawai);
            
            echo view('pegawai/v_pegawai_edit', $data);
        }
    }

    public function simpan_data() {
        $id_pegawai    = strip_tags($this->request->getPost('id_pegawai'));
        $nama_pegawai  = strip_tags($this->request->getPost('nama_pegawai'));
        $id_departemen = strip_tags($this->request->getPost('id_departemen'));
        $id_jabatan    = strip_tags($this->request->getPost('id_jabatan'));
        $no_hp         = strip_tags($this->request->getPost('no_hp'));
        $alamat        = strip_tags($this->request->getPost('alamat'));

        $statusUpload = $this->ajaxUpload();
        if($statusUpload['status']=='success') {
            try {
                $this->db->transException(true)->transStart();

                $this->db->table('tb_karyawan')->insert([
                    'IdKaryawan'   => $id_pegawai,
                    'NamaKaryawan' => $nama_pegawai,
                    'IdDepartemen' => $id_departemen,
                    'IdJabatan'    => $id_jabatan,
                    'NoHp'         => $no_hp,
                    'Alamat'       => $alamat,
                    'Foto'         => $statusUpload['filename'] ?? null,
                    'Role'         => 2,
                    'UserInput'    => session('id_user'),
                    'TglInput'     => date('Y:m:d H:i:s')
                ]);

                $this->db->transComplete();

                return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
            } catch (\Throwable $e) {
                return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
            }
        } else {
            return $this->response->setJSON(['status'  => 'error', 'message' => $statusUpload['message']]);
        }
    }

    public function update_data() {
        $id_pegawai    = strip_tags($this->request->getPost('id_pegawai'));
        $nama_pegawai  = strip_tags($this->request->getPost('nama_pegawai'));
        $id_departemen = strip_tags($this->request->getPost('id_departemen'));
        $id_jabatan    = strip_tags($this->request->getPost('id_jabatan'));
        $no_hp         = strip_tags($this->request->getPost('no_hp'));
        $alamat        = strip_tags($this->request->getPost('alamat'));

        $statusUpload = $this->ajaxUpload();
        if($statusUpload['status']=='success') {
            try {
                $this->db->transException(true)->transStart();

                $this->db->table('tb_karyawan')
                ->where('IdKaryawan', $id_pegawai)
                ->update([
                    'NamaKaryawan' => $nama_pegawai,
                    'IdDepartemen' => $id_departemen,
                    'IdJabatan'    => $id_jabatan,
                    'NoHp'         => $no_hp,
                    'Alamat'       => $alamat,
                    'Foto'         => $statusUpload['filename'] ?? null,
                    'UserEdit'     => session('id_user'),
                    'TglEdit'      => date('Y:m:d H:i:s')
                ]);

                $this->db->transComplete();

                return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
            } catch (\Throwable $e) {
                return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
            }
        } else {
            return $this->response->setJSON(['status'  => 'error', 'message' => $statusUpload['message']]);
        }
    }

    public function ajaxUpload() {
        $image = $this->request->getFile('image');

        // If no file uploaded, skip validation and upload
        if (!$image || $image->getError() == 4) {
            return [
                'status'  => 'success',
                'message' => 'No upload.',
                'filename' => null // Important! So you can detect no file in simpan_data
            ];
        }

        // Validate file
        $validationRule = [
            'image' => [
                'label' => 'Image File',
                'rules' => 'is_image[image]'
                        . '|mime_in[image,image/jpg,image/jpeg,image/png,image/webp]'
                        . '|max_size[image,2048]' // 2MB
            ],
        ];

        if (! $this->validate($validationRule)) {
            return [
                'status'  => 'error',
                'message' => $this->validator->getErrors(),
            ];
        }

        if ($image->isValid() && !$image->hasMoved()) {
            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads', $newName);

            return [
                'status'   => 'success',
                'message'  => 'Image uploaded successfully.',
                'filename' => $newName
            ];
        }

        return [
            'status'  => 'error',
            'message' => 'Image upload failed.'
        ];
    }

    public function hapus_data() {
        $id = $this->request->getPost('id');

        try {
            $this->db->transException(true)->transStart();

            // hapus foto (jika ada)
            $karyawan = $this->db->table('tb_karyawan')->where('IdKaryawan', $id)->get()->getRow();

            if ($karyawan->Foto) {
                $filePath = FCPATH . 'uploads/' . $karyawan->Foto;

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->db->table('tb_karyawan')->where('IdKaryawan', $id)->delete();            

            $this->db->transComplete();

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

}
