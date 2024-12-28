<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ModelUsers;
use App\Models\ModelMenu;
use App\Models\ModelCategory;
use CodeIgniter\HTTP\ResponseInterface;

class Menu extends BaseController
{
    public function __construct()
    {
        $this->modelUser     = new ModelUsers();
        $this->modelMenu     = new ModelMenu();
        $this->modelCategory = new ModelCategory();
        helper(['form', 'url']);
    }

    public function index($category = null)
    {
        $session = \Config\Services::session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
            if ($session->get('role') === 'Admin') {
                $data['title'] = "Menu";
                $data['subtitle'] = "";

                $data['menu'] = $this->modelMenu->getMenu(); 
                $data['categories'] = $this->modelCategory->getAllCategories();

                $categoryMap = [];
                foreach ($data['categories'] as $cat) {
                    $categoryMap[strtolower($cat['category_name'])] = $cat['id_category'];
                }

                if ($category !== null && array_key_exists($category, $categoryMap)) {
                    $categoryId = $categoryMap[$category];
                    $data['items'] = $this->modelMenu->menuWhere(['id_category' => $categoryId]);
                    $data['subtitle'] = ucfirst($category);

                    if (empty($data['items'])) {
                        $data['message'] = "No data available for " . $data['subtitle'];
                    }
                } else {
                    $data['items'] = [];
                    if ($category !== null) {
                        $data['message'] = "Invalid menu category.";
                    }
                }

                return view('templates/admin/header', $data)
                    . view('templates/admin/sidebar', $data)
                    . view('templates/admin/topbar', $data)
                    . view('admin/menu', $data)
                    . view('templates/admin/footer');
            } else {
                return redirect()->to('/');
            }
        }
    }

    public function add() 
    {
        $session = \Config\Services::session();
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
            if ($session->get('role') === 'Admin') {
                $data['title'] = "Add Menu";

                $rules = [
                    'menu_name' => [
                        'rules'  => 'required|min_length[5]|max_length[100]|is_unique[menu.menu_name]',
                        'errors' => [
                            'required'    => '{field} is required',
                            'min_length'  => '{field} must be at least {param} characters',
                            'max_length'  => '{field} cannot exceed {param} characters',
                            'is_unique'   => '{field} is already taken. Please choose another one'
                        ]
                    ],
                    'description' => [
                        'rules'  => 'required|max_length[255]',
                        'errors' => [
                            'required'    => '{field} is required',
                            'max_length'  => '{field} cannot exceed {param} characters'
                        ]
                    ],
                    'category' => [
                        'rules'  => 'required|integer',
                        'errors' => [
                            'required'    => '{field} is required',
                            'integer'     => '{field} must be a valid category ID',
                        ]
                    ],
                    'price' => [
                        'rules'  => 'required|numeric',
                        'errors' => [
                            'required'    => '{field} is required',
                            'numeric'     => '{field} can only contain numbers',
                        ]
                    ],
                    'image' => [
                        'rules' => 'uploaded[image]|max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
                        'errors' => [
                            'uploaded' => 'Please upload an image',
                            'max_size' => 'Image size cannot exceed 1MB',
                            'is_image' => 'Please upload a valid image file',
                            'mime_in' => 'Please upload an image file (jpg, jpeg, png)'
                        ]
                    ],
                ];

                if ($this->request->getPost()) {
                    if (!$this->validate($rules)) {
                        $data['validation'] = $this->validator;
                        $session->setFlashdata('validation', $this->validator->getErrors());
                        return redirect()->back()->withInput();
                    }

                    // Check if the category exists
                    $category = $this->modelCategory->find($request->getPost('category'));

                    if (!$category) {
                        // Category does not exist, handle this case (optional)
                        $session->setFlashdata('add_modal_open', true);
                        return redirect()->back()->with('error', 'Selected category does not exist.');
                    }

                    $file = $request->getFile('image');
                    if ($file->isValid() && !$file->hasMoved()) {
                        $menuName = $request->getPost('menu_name');
                        $sanitizedMenuName = preg_replace('/[^A-Za-z0-9\-]/', '_', $menuName);
                        $currentDateTime = date('Ymd_His');
                        $extension = $file->getExtension();
                        $fileName = $sanitizedMenuName . '_' . $currentDateTime . '.' . $extension;

                        $file->move('assets/img/menu', $fileName);

                        $this->modelMenu->save([
                            'menu_name' => $request->getPost('menu_name'),
                            'description' => $request->getPost('description'),
                            'id_category' => $request->getPost('category'),
                            'price' => $request->getPost('price'),
                            'image' => $fileName
                        ]);

                        $session->setFlashdata('success', 'Menu item added successfully.');
                        return redirect()->back();
                    } else {
                        $session->setFlashdata('add_modal_open', true);
                        $session->setFlashdata('message', 'File upload failed.');
                        return redirect()->back();
                    }
                } else {
                    $session->setFlashdata('add_modal_open', true);
                    return redirect()->back();
                }
            } else {
                return redirect()->to('/');
            }
        }
    }

    public function delete($id_menu)
    {
        $session = \Config\Services::session();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
            if ($session->get('role') === 'Admin') {
                // Check if the menu item exists
                $menu = $this->modelMenu->find($id_menu);
                if (!$menu) {
                    $session->setFlashdata('error', 'Menu item not found.');
                    return redirect()->back();
                }

                // Perform the deletion
                $this->modelMenu->deleteMenu((string) $id_menu); // Ensure $id_menu is cast to string

                // Optionally, you can also delete the image file associated with the menu item
                $imagePath = 'assets/img/menu/' . $menu['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }

                $session->setFlashdata('success', 'Menu item deleted successfully.');
                return redirect()->back();
            } else {
                return redirect()->to('/');
            }
        }
    }

    public function update($id_menu = null)
    {
        $session = \Config\Services::session();
        $validation = \Config\Services::validation();
        $request = \Config\Services::request();

        if (!$session->get('isLoggedIn')) {
            return redirect()->to('login');
        } else {
            if ($session->get('role') === 'Admin') {
                $data['title'] = "Update Menu";
                
                if ($id_menu === null) {
                    return redirect()->back()->with('error', 'Invalid menu ID.');
                }

                $menu = $this->modelMenu->find($id_menu);
                if (!$menu) {
                    return redirect()->back()->with('error', 'Menu item not found.');
                }

                $data['menu'] = $menu;
                $data['categories'] = $this->modelCategory->getAllCategories();

                $rules = [
                    'menu_name' => [
                        'rules'  => 'required|min_length[5]|max_length[100]',
                        'errors' => [
                            'required'    => '{field} is required',
                            'min_length'  => '{field} must be at least {param} characters',
                            'max_length'  => '{field} cannot exceed {param} characters',
                        ]
                    ],
                    'description' => [
                        'rules'  => 'required|max_length[255]',
                        'errors' => [
                            'required'    => '{field} is required',
                            'max_length'  => '{field} cannot exceed {param} characters'
                        ]
                    ],
                    'category' => [
                        'rules'  => 'required|integer',
                        'errors' => [
                            'required'    => '{field} is required',
                            'integer'     => '{field} must be a valid category ID',
                        ]
                    ],
                    'price' => [
                        'rules'  => 'required|numeric',
                        'errors' => [
                            'required'    => '{field} is required',
                            'numeric'     => '{field} can only contain numbers',
                        ]
                    ],
                    'image' => [
                        'rules' => 'max_size[image,1024]|is_image[image]|mime_in[image,image/jpg,image/jpeg,image/png]',
                        'errors' => [
                            'max_size' => 'Image size cannot exceed 1MB',
                            'is_image' => 'Please upload a valid image file',
                            'mime_in' => 'Please upload an image file (jpg, jpeg, png)'
                        ]
                    ],
                ];

                if ($this->request->getPost()) {
                    if (!$this->validate($rules)) {
                        $data['validation'] = $this->validator;
                        $session->setFlashdata('validation', $this->validator->getErrors());
                        return redirect()->back()->withInput();
                    }

                    $file = $request->getFile('image');
                    $fileName = $menu['image'];

                    if ($file && $file->isValid() && !$file->hasMoved()) {
                        $menuName = $request->getPost('menu_name');
                        $sanitizedMenuName = preg_replace('/[^A-Za-z0-9\-]/', '_', $menuName);
                        $currentDateTime = date('Ymd_His');
                        $extension = $file->getExtension();
                        $fileName = $sanitizedMenuName . '_' . $currentDateTime . '.' . $extension;

                        $file->move('assets/img/menu', $fileName);

                        // Optionally, you can delete the old image file
                        $oldImagePath = 'assets/img/menu/' . $menu['image'];
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath);
                        }
                    }

                    $this->modelMenu->update($id_menu, [
                        'menu_name' => $request->getPost('menu_name'),
                        'description' => $request->getPost('description'),
                        'id_category' => $request->getPost('category'),
                        'price' => $request->getPost('price'),
                        'image' => $fileName
                    ]);

                    $session->setFlashdata('success', 'Menu item updated successfully.');
                    return redirect()->back();
                }

                return view('templates/admin/header', $data)
                    . view('templates/admin/sidebar', $data)
                    . view('templates/admin/topbar', $data)
                    . view('admin/edit_menu', $data)
                    . view('templates/admin/footer');
            } else {
                return redirect()->to('/');
            }
        }
    }
}