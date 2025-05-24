<?php

namespace App\Controllers;
use App\Models\UserModel;
use CodeIgniter\HTTP\ResponseInterface;

class Users extends BaseController
{
    public function index()
    {
        return view('users_list'); // Make sure this matches your view file
    }

    public function datatables()
    {
        $request = service('request');
        $db = \Config\Database::connect();
        $builder = $db->table('users');

        $username = $request->getPost('username');
        $status   = $request->getPost('status');

        if (!empty($username)) {
            $builder->like('name', $username);
        }

        if (!empty($status)) {
            $builder->where('status', $status);
        }

        $columns = ['id', 'name', 'email', 'status'];
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
        $start = $request->getPost('start');
        $builder->limit($length, $start);

        $data = $builder->get()->getResult();

        $json = [
            "draw" => intval($request->getPost('draw')),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecords,
            "data" => $data,
        ];

        return $this->response->setJSON($json);
    }

    public function update()
    {
        $id = $this->request->getPost('id');
        $data = [
            'name'   => $this->request->getPost('name'),
            'email'  => $this->request->getPost('email'),
            'status' => $this->request->getPost('status')
        ];

        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->update($data);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete()
    {
        $id = $this->request->getPost('id');
        $db = \Config\Database::connect();
        $db->table('users')->where('id', $id)->delete();

        return $this->response->setJSON(['status' => 'deleted']);
    }

}
