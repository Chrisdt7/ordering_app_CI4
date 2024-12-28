<?php

namespace App\Controllers;

use App\Models\ModelUsers;
use App\Models\ModelCategory;
use CodeIgniter\Controller;

class Users extends Controller {
	protected $modelUser;
    protected $session;
	
	public function __construct()
    {
        $this->modelUser 	 = new ModelUsers();
		$this->modelCategory = new ModelCategory();
        helper(['form', 'url']);
    }

	public function changePassword()
    {
		$validation = \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$validation->reset();

		$data['title'] = "Change Password";

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
			if ($session->get('role') !== 'Admin') {
				return redirect()->to('/');
			} else {
				$data['categories'] = $this->modelCategory->getAllCategories();

				$rules = [
					'current-password' => [
						'rules' => 'required',
						'errors'    => [
							'required'    => '{field} is required',
						]
					],
					'new-password' => [
						'rules'     => 'required|min_length[8]|max_length[25]',
						'errors'    => [
							'required'    => '{field} is required',
							'min_length'  => '{field} must be at least {param} characters',
							'max_length'  => '{field} cannot exceed {param} characters'
						]
					],
					'confirm-password' => [
						'rules'     => 'required|matches[new-password]',
						'errors'    => [
							'required'    => '{field} is required',
							'matches'     => '{field} does not match with New Password'
						]
					],
				];

				if ($request->getPost()) {
					if (!$this->validate($rules)) {
						$data['validation'] = $this->validator;
						$session->setFlashdata('validation', $this->validator->getErrors());

						return redirect()->back();
					} else {
						$currentPassword = $request->getPost('current-password');
						$newPassword = $request->getPost('new-password');
			
						$userId = $session->get('id_users');
						$user = $this->modelUser->getUsersWhere($userId);
			
						if (!password_verify($currentPassword, $user['password'])) {
							$session->setFlashdata('message', 'Wrong Current Password!');
							
							return redirect()->back();
						}
			
						if ($this->modelUser->updatePassword($userId, $newPassword)) {
							session()->setFlashdata('success', 'Password changed successfully.');

							return redirect()->back();
						} else {
							session()->setFlashdata('message', 'Failed to change password. Please try again.');
							
							return redirect()->back();
						}
					}
				} else {

					echo view('templates/admin/header', $data)
					. view('templates/admin/sidebar', $data)
					. view('templates/admin/topbar', $data)
					. view('admin/profile/change-password', $data)
					. view('templates/admin/footer');
				}
			}
		}	
    }

	public function profile() {
		$session = \Config\Services::session();

		if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
			if ($session->get('role') !== 'Admin') {
				echo "not admin";
			} else {
				$data['categories'] = $this->modelCategory->getAllCategories();

				$userId = $session->get('id_users');
				$user = $this->modelUser->getUsersWhere($userId);
				
				$data = [
					'title' => 'Profile',
					'user' => $user
				];

				echo view('templates/admin/header', $data)
				. view('templates/admin/sidebar', $data)
				. view('templates/admin/topbar', $data)
				. view('admin/profile/profile', $data)
				. view('templates/admin/footer');
			}
		}
	}

	public function updateProfile()
	{
		$validation = \Config\Services::validation();
		$session = \Config\Services::session();
		$request = \Config\Services::request();
		$validation->reset();
		
		if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
			if ($session->get('role') !== 'Admin') {
				return redirect()->to('/');
			} else {
				$data['title'] = 'Update Profile';

				$data['categories'] = $this->modelCategory->getAllCategories();

				$rules = [
					'updt-username' => [
						'rules'  => 'required|min_length[5]|max_length[20]|is_unique[users.username]',
						'errors' => [
							'required'    => '{field} is required',
							'min_length'  => '{field} must be at least {param} characters',
							'max_length'  => '{field} cannot exceed {param} characters',
							'is_unique'   => '{field} is already taken. Please choose another one'
						]
					],
					'updt-name' => [
						'rules'  => 'required|alpha_space|min_length[5]|max_length[30]',
						'errors' => [
							'required' 	  => '{field} is required',
							'alpha_space' => '{field} can only contain alphabetic characters and spaces',
							'min_length'  => '{field} must be at least {param} characters',
							'max_length'  => '{field} cannot exceed {param} characters'
						]
					],
					'updt-email' => [
						'rules'  => 'required|valid_email',
						'errors' => [
							'required'    => '{field} is required',
							'valid_email' => '{field} must contain a valid email address'
						]
					],
					'updt-contact' => [
						'rules'  => 'required|numeric|min_length[10]|max_length[15]',
						'errors' => [
							'required'    => '{field} is required',
							'numeric'     => '{field} can only contain numbers',
							'min_length'  => '{field} must be at least {param} characters',
							'max_length'  => '{field} cannot exceed {param} characters'
						]
					],
					'updt-image' => [
						'rules' => 'uploaded[image]|max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
						'errors' => [
							'uploaded' => 'Please upload an image',
							'max_size' => 'Image size cannot exceed 1MB',
							'is_image' => 'Please upload a valid image file',
							'mime_in' => 'Please upload an image file (jpg, jpeg, png)'
						]
					],
				];

				if ($request->getPost()) {
					if (!$this->validate($rules)) {
						$data['validation'] = $validation;
						$session->setFlashdata('validation', $validation->getErrors());
						return redirect()->back()->withInput();
					} else {
						$username = htmlspecialchars($request->getPost('updt-username'));
						$name = htmlspecialchars($request->getPost('updt-name'));
						$email = htmlspecialchars($request->getPost('updt-email'));
						$contact = htmlspecialchars($request->getPost('updt-contact'));
						$upload_image = $_FILES['image']['name'];
						$userId = $session->get('id_users');

						if ($upload_image) {
							$config['upload_path'] = './assets/img/profile/';
							$config['allowed_types'] = 'gif|jpg|png';
							$config['max_size'] = '3000';
							$config['max_width'] = '1024';
							$config['max_height'] = '1000';
							$config['file_name'] = 'pro' . time();
					
							$file = $this->request->getFile('image');
					
							if ($file->isValid() && !$file->hasMoved()) {
								if ($file->move($config['upload_path'], $config['file_name'])) {
									$gambar_lama = $data['user']['image'];
									if ($gambar_lama != 'default.jpg') {
										unlink(FCPATH . 'assets/img/avatar/' . $gambar_lama);
									}
									$gambar_baru = $file->getName();
									$modelUser->set('image', $gambar_baru);
								} else {
									// Handle the upload error here if needed
									$errors = $file->getErrorString();
									// Flash message or handle errors accordingly
								}
							}
						}
					}
				} else {
					$userId = $session->get('id_users');
					$user = $this->modelUser->getUsersWhere($userId);
					$data['user'] = $user;
		
					echo view('templates/admin/header', $data)
					. view('templates/admin/sidebar', $data)
					. view('templates/admin/topbar', $data)
					. view('admin/profile/update-profile', $data)
					. view('templates/admin/footer');
				}
			}
		}
	}
}