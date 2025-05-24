<?php

namespace App\Controllers;

class Dashboard extends BaseController
{

    public function __construct()
    {
        if (!session()->get('logged_in')) {
            redirect()->to('/')->send();
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Dashboard';

        return view('v_home', $data);
    }

}
