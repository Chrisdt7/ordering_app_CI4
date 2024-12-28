<?php

namespace App\Controllers;

use App\Models\ModelUsers;
use App\Models\ModelOrderItems;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $modelUser;

    public function __construct()
    {
        $this->modelUser = new ModelUsers();
        $this->modelOrderItems = new ModelOrderItems();
        helper(['form', 'url']);
    }

    public function register()
    {
        $validation = \Config\Services::validation();
        $session = \Config\Services::session();
        $validation->reset();

        $data['title'] = 'Register';

        $rules = [
            'reg-username' => [
                'rules'     => 'required|min_length[5]|max_length[20]|is_unique[users.username]',
                'errors'    => [
                    'required'    => '{field} is required',
                    'min_length'  => '{field} must be at least {param} characters',
                    'max_length'  => '{field} cannot exceed {param} characters',
                    'is_unique'   => '{field} is already taken. Please choose another one'
                ]
            ],
            'reg-password' => [
                'rules'     => 'required|min_length[8]|max_length[25]',
                'errors'    => [
                    'required'    => '{field} is required',
                    'min_length'  => '{field} must be at least {param} characters',
                    'max_length'  => '{field} cannot exceed {param} characters'
                ]
            ],
            'name' => [
                'rules'     => 'required|alpha_space|min_length[5]|max_length[30]',
                'errors'    => [
                    'required'    => '{field} is required',
                    'alpha_space' => '{field} can only contain alphabetic characters and spaces',
                    'min_length'  => '{field} must be at least {param} characters',
                    'max_length'  => '{field} must not exceed {param} characters'
                ]
            ],
            'email' => [
                'rules'     => 'required|valid_email',
                'errors'    => [
                    'required'    => '{field} is required.',
                    'valid_email' => '{field} must contain a valid email address'
                ]
            ],
            'contact' => [
                'rules'     => 'required|numeric|min_length[10]|max_length[15]',
                'errors'    => [
                    'required'    => '{field} is required.',
                    'numeric'     => '{field} can only contain numbers',
                    'min_length'  => '{field} must be at least {param} digits',
                    'max_length'  => '{field} must not exceed {param} digits'
                ]
            ]
        ];

        if (!$this->validate($rules)) {
            $data['validation'] = $this->validator;
            $session->setFlashdata('registerDrawer', true);
            $session->setFlashdata('validation', $this->validator->getErrors());
            
            return redirect()->back()->withInput();
        } else {
            $username = htmlspecialchars($this->request->getPost('reg-username'));
            $password = password_hash($this->request->getPost('reg-password'), PASSWORD_DEFAULT);
            $name = htmlspecialchars($this->request->getPost('name'));
            $email = htmlspecialchars($this->request->getPost('email'));
            $contact = htmlspecialchars($this->request->getPost('contact'));

            $this->modelUser->saveData([
                'username' => $username,
                'password' => $password,
                'name' => $name,
                'email' => $email,
                'contact' => $contact
            ]);

            $session->setFlashdata('loginDrawer', true);
            $session->setFlashdata('success', 'Registration successful !');
            return redirect()->to('login');
        }
    }

    public function login()
    {
        $validation = \Config\Services::validation();
        $session = \Config\Services::session();
        $validation->reset();
        
        $data['title'] = 'Login';
        $data['numRows'] = $this->modelOrderItems->getDataWhere('orders', ['id_users' => session('id_users')])->getNumRows();

        if ($data['numRows'] == null) {
            $data['numRows'] = 0;
        } else {
            $data['numRows'] = $data['numRows']->getNumRows();
        }

        $rules = [
            'username' => [
                'rules' => 'required|min_length[5]|max_length[20]',
                'errors' => [
                    'required' => '{field} is required',
                    'min_length' => '{field} must be at least {param} characters',
                    'max_length' => '{field} cannot exceed {param} characters'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[8]|max_length[25]',
                'errors' => [
                    'required' => '{field} is required',
                    'min_length' => '{field} must be at least {param} characters',
                    'max_length' => '{field} cannot exceed {param} characters'
                ]
            ]
        ];

        if ($this->request->getMethod() === 'POST') {
            if (!$this->validate($rules)) {

                $data['validation'] = $this->validator;
                $session->setFlashdata('loginDrawer', true);
                $session->setFlashdata('validation', $this->validator->getErrors());
    
                return redirect()->back()->withInput();
            } else {
                $username = htmlspecialchars($this->request->getPost('username'));
                $password = $this->request->getPost('password');
                $user = $this->modelUser->checkData($username);

                if ($user && password_verify($password, $user['password'])) {
                    $data = [
                        'id_users' => $user['id_users'],
                        'username' => $user['username'],
                        'name' => $user['name'],
                        'email' => $user['email'],
                        'contact' => $user['contact'],
                        'role' => $user['role'],
                        'image' => $user['image'],
                        'isLoggedIn' => true,
                    ];

                    $session->set($data);
                    
                    if ($user['role'] === 'Admin') {
                        return redirect()->to('dashboard');
                    } else {
                        $session->setFlashdata('loginDrawer', false);
                        return redirect()->to('/');
                    }
                } else {
                    $session->setFlashdata('message', 'Wrong Username or Password!');
                    $session->setFlashdata('loginDrawer', true);
                    return redirect()->to('login')->withInput();
                }
            }
        } else {
            $session->setFlashdata('loginDrawer', true);

            return view('templates/header', $data)
            . view('templates/topbar')
            . view('index')
            . view('auth/login')
            . view('auth/register')
            . view('templates/footer');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
}
