<?php

namespace App\Controllers\Admin;


use App\Controllers\BaseController;
use App\Models\ModelUsers;
use App\Models\ModelCategory;

class Dashboard extends BaseController
{
    public function __construct()
    {
        $this->modelUser = new ModelUsers();
        $this->modelCategory = new ModelCategory();
    }

    public function index()
    {
        $session = \Config\Services::session();

        $data['categories'] = $this->modelCategory->getAllCategories();
        $categories = $data['categories'];

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
            if ($session->get('role') === 'Admin') {
                $data['title'] = "Dashboard";
                
                return view('templates/admin/header', $data)
                . view('templates/admin/sidebar', $data)
                . view('templates/admin/topbar', $data)
                . view('admin/dashboard', $data)
                . view('templates/admin/footer');
            }
            else {
                return redirect()->to('/');
            }
        }
    }
}