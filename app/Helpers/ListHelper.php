<?php

namespace App\Helpers;

use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\Session\Session;
use App\Models\AdminModel;

class ListHelper {
    public static function cek_login()
    {
        $session = session();
        if (!$session->has('isLoggedIn')) {
            set_pesan('You need to login first.');
            return redirect()->to('login');
        } else {
            
        }
    }

    public static function is_admin()
    {
        $session = session();
        $role = $session->get('login_session')['id_role'];

        return $role === 'admin';
    }

    public static function set_pesan($pesan, $tipe = true)
    {
        $session = session();
        $flashData = ($tipe) ? "alert alert-success" : "alert alert-danger";
        $pesan = "<div class='{$flashData}'><strong>" . (($tipe) ? "SUCCESS!" : "ERROR!") . "</strong> {$pesan} <button type='button' class='close' data-dismiss='alert' aria-label='Close'><span aria-hidden='true'>&times;</span></button></div>";
        $session->setFlashdata('pesan', $pesan);
    }

    public static function userdata($field)
    {
        $session = session();
        $adminModel = new AdminModel();

        $userId = $session->get('login_session')['user'];
        return $adminModel->get('user', ['id_user' => $userId]);
    }

    public static function output_json($data)
    {
        $response = service('response');
        return $response->setJSON($data);
    }
}