<?php

namespace App\Controllers;
use App\Models\ModelOrderItems;

class Home extends BaseController
{
    protected $modelOrderItems;

    public function __construct()
    {
        $this->modelOrderItems = new ModelOrderItems();
        helper(['form', 'url']);
    }

    public function index()
    {
        $session = \Config\Services::session();
        if (!$session->get('isLoggedIn')) {
            $data['title'] = 'Restaurant';
            $data['numRows'] = $this->modelOrderItems->getDataWhere('orders', ['id_users' => session('id_users')])->getNumRows();

            if ($data['numRows'] == null) {
                $data['numRows'] = 0;
            } else {
                $data['numRows'] = $data['numRows']->getNumRows();
            }

            return view('templates/header', $data)
                . view('templates/topbar')
                . view('index')
                . view('auth/login')
                . view('auth/register')
                . view('templates/footer');
        } else {
            if ($session->get('role') === 'Admin') {
                return redirect()->to('dashboard');
            } else {
                $data['title'] = 'Restaurant';

                $data['numRows'] = $this->modelOrderItems->getDataWhere('orders', ['id_users' => session('id_users')])->getNumRows();
            }
        }
    }
}
