<?php

namespace App\Controllers;

use App\Models\KaryawanModel;

class Auth extends BaseController
{

    public function index()
    {
        if (session()->get('logged_in')) {
            redirect()->to('/home')->send();
            exit;
        }
        
        return view('v_login');
    }

    public function process() {
        $nip      = $this->request->getPost('nip');
        $password = $this->request->getPost('password');

        $userModel = new KaryawanModel();
        $user     = $userModel->where('IdKaryawan', $nip)->first();

        if ($user && password_verify($password, $user['Password'])) {
            session()->set(['id_user' => $user['IdKaryawan'], 'logged_in' => true, 'group_id' => $user['Role']]);
            return $this->response->setJSON(['status' => 'success']);
        } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid credentials']);
        }
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/');
    }

}
