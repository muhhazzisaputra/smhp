<?php

namespace App\Controllers;

use App\Models\MesinModel;

class Mesin extends BaseController
{

    public function __construct() {
        $this->db         = \Config\Database::connect();
        $this->MesinModel = new MesinModel();

        if (!session()->get('logged_in')) { redirect()->to('/')->send(); exit; }
    }

    public function index() {
        $data['judul'] = 'Data Mesin';

        return view('mesin/v_mesin_list', $data);
    }

    public function datatables() {
        $request = service('request');
        $db = \Config\Database::connect();
        $builder = $db->table('tb_mesin a');
        $builder->select('a.IdMesin, a.NoMesin, b.NamaKaryawan as UserInput, c.NamaKaryawan as UserEdit');
        $builder->select("DATE_FORMAT(a.TglInput, '%d %b %Y %H:%i:%s') as TglInput");
        $builder->select("DATE_FORMAT(a.TglEdit, '%d %b %Y %H:%i:%s') as TglEdit");
        $builder->join('tb_karyawan b', 'b.IdKaryawan=a.UserInput', 'left');
        $builder->join('tb_karyawan c', 'c.IdKaryawan=a.UserEdit', 'left');

        // $username = $request->getPost('username');
        // $status   = $request->getPost('status');

        // if (!empty($username)) {
        //     $builder->like('IdMesin', $username);
        // }

        // if (!empty($status)) {
        //     $builder->where('NoMesin', $status);
        // }

        $columns = ['IdMesin', 'NoMesin'];
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

    function form_data() {
        $jenis = $this->request->getVar('jenis');

        if($jenis=="input") {

            $data['maxCode'] = $this->MesinModel->getMaxCode();
            echo view('mesin/v_mesin_input', $data);
        } else {
            $id_mesin = $this->request->getPost('id_mesin');

            $data['mesin'] = $this->MesinModel->getRow($id_mesin);
            
            echo view('mesin/v_mesin_edit', $data);
        }
    }

    public function simpan_data() {
        $id_mesin = strip_tags($this->request->getPost('id_mesin'));
        $no_mesin = strip_tags($this->request->getPost('no_mesin'));

        try {
            $this->db->transException(true)->transStart();

            $this->db->table('tb_mesin')->insert([
                'IdMesin'   => $id_mesin,
                'NoMesin'   => $no_mesin,
                'UserInput' => session('id_user'),
                'TglInput'  => date('Y:m:d H:i:s')
            ]);

            $this->db->transComplete();

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

    public function update_data() {
        $id_mesin = strip_tags($this->request->getPost('id_mesin'));
        $no_mesin = strip_tags($this->request->getPost('no_mesin'));

        try {
            $this->db->transException(true)->transStart();

            $this->db->table('tb_mesin')
            ->where('IdMesin', $id_mesin)
            ->update([
                'NoMesin'  => $no_mesin,
                'UserEdit' => session('id_user'),
                'TglEdit'  => date('Y:m:d H:i:s')
            ]);

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

            $this->db->table('tb_mesin')->where('IdMesin', $id)->delete();

            $this->db->transComplete();

            return $this->response->setJSON(['status'  => 'success', 'message' => 'Success']);
        } catch (\Throwable $e) {
            return $this->response->setJSON(['status'  => 'error', 'message' => 'Transaction failed: ' . $e->getMessage()])->setStatusCode(500);
        }
    }

}
