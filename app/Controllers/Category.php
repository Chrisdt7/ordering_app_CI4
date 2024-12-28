<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\ModelUsers;
use App\Models\ModelCategory;

class Category extends BaseController
{
    public function __construct()
    {
        $this->modelUser = new ModelUsers();
        $this->modelCategory = new ModelCategory();
    }

    public function index()
    {
        
    }

    public function add()
    {
        $validation = \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
        $validation->reset();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
            if ($session->get('role') === 'Admin') {
                $data['title'] = "Add Category";
                
                $rules = [
					'category_name' => [
						'rules' => 'required|alpha_space|max_length[50]|is_unique[category.category_name]',
						'errors'    => [
							'required'    => '{field} is required',
                            'alpha_space' => '{field} can only contain alphabetic characters and spaces',
							'max_length'  => '{field} cannot exceed {param} characters',
                            'is_unique'   => 'The Category {field} already exist'
						]
					],
                ];

                if ($request->getPost()) {
                    if (!$this->validate($rules)) {
						$data['validation'] = $validation;
						$session->setFlashdata('validation', $validation->getErrors());
						return redirect()->back()->withInput();
					} else {
                        $category = htmlspecialchars($this->request->getPost('category_name'));
                        
                        $this->modelCategory->saveCategory(['category_name' => $category]);

                        $session->setFlashdata('success', 'Category Added Successfully !');
                        return redirect()->back()->with('success', 'Category Added Successfully');
                    }
                }
                
                return redirect()->back();
            } else {
                return redirect()->to('/');
            }
        }
    }
}