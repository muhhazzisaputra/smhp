<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ProdukModel;

class Produk extends BaseController
{

    public function __construct() {
        $this->db          = \Config\Database::connect();
        $this->ProdukModel = new ProdukModel();

        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index()
    {
        $data['judul'] = 'Data Produk';

        return view('produk/v_produk_list', $data);
    }

    public function datatables() {
        $request = service('request');
        $db = \Config\Database::connect();
        $builder = $db->table('tb_produk a');
        $builder->select('a.IdProduk, a.NamaProduk, a.Aktif, a.Gambar, b.NamaKaryawan as UserInput, c.NamaKaryawan as UserEdit');
        $builder->select("DATE_FORMAT(a.TglInput, '%d %b %Y %H:%i') as TglInput");
        $builder->select("DATE_FORMAT(a.TglEdit, '%d %b %Y %H:%i') as TglEdit");
        $builder->join('tb_karyawan b', 'b.IdKaryawan=a.UserInput', 'left');
        $builder->join('tb_karyawan c', 'c.IdKaryawan=a.UserEdit', 'left');

        $columns = ['IdProduk', 'NamaProduk'];
        $searchValue = $request->getPost('search')['value'] ?? '';

        if (!empty($searchValue)) {
            $builder->groupStart();
            foreach ($columns as $col) {
                $builder->orLike($col, $searchValue);
            }
            $builder->groupEnd();
        }

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

    public function form_data() {
        $jenis = $this->request->getPost('jenis');

        if($jenis=="input") {

            $data['maxCode'] = $this->ProdukModel->getMaxCode();
            echo view('produk/v_produk_input', $data);
        } else {
            $id_produk = $this->request->getPost('id_produk');

            $data['produk'] = $this->ProdukModel->getRow($id_produk);
            
            echo view('produk/v_produk_edit', $data);
        }
    }

    public function simpan_data() {
        $id_produk   = strip_tags($this->request->getPost('id_produk'));
        $nama_produk = strip_tags($this->request->getPost('nama_produk'));
        $aktif       = strip_tags($this->request->getPost('aktif'));

        $statusUpload = $this->ajaxUpload();
        if($statusUpload['status']=='success') {
            try {
                $this->db->transException(true)->transStart();

                $this->db->table('tb_produk')->insert([
                    'IdProduk'   => $id_produk,
                    'NamaProduk' => $nama_produk,
                    'Aktif'      => ($aktif=="2") ? 0 : $aktif,
                    'Gambar'     => $statusUpload['filename'] ?? null,
                    'UserInput'  => session('id_user'),
                    'TglInput'   => date('Y:m:d H:i:s')
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
        $id_produk   = strip_tags($this->request->getPost('id_produk'));
        $nama_produk = strip_tags($this->request->getPost('nama_produk'));
        $aktif       = strip_tags($this->request->getPost('aktif'));

        $statusUpload = $this->ajaxUpload();
        if($statusUpload['status']=='success') {
            try {
                $this->db->transException(true)->transStart();

                $this->db->table('tb_produk')
                ->where('IdProduk', $id_produk)
                ->update([
                    'NamaProduk' => $nama_produk,
                    'Aktif'      => ($aktif=="2") ? 0 : $aktif,
                    'Gambar'     => $statusUpload['filename'] ?? null,
                    'UserEdit'   => session('id_user'),
                    'TglEdit'    => date('Y:m:d H:i:s')
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
            $image->move(FCPATH . 'uploads/produk', $newName);

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

            // hapus gambar (jika ada)
            $produk = $this->db->table('tb_produk')->where('IdProduk', $id)->get()->getRow();

            if ($produk->Gambar) {
                $filePath = FCPATH . 'uploads/produk' . $produk->Gambar;

                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $this->db->table('tb_produk')->where('IdProduk', $id)->delete();            

            $this->db->transComplete();

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

}
