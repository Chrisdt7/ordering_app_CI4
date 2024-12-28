<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthModel extends Model
{
    protected $table = 'user';
    protected $primaryKey = 'id_user';
	protected $allowedFields = ['nama_lengkap', 'username', 'email', 'alamat', 'no_telp', 'password', 'image', 'id_role', 'created_at', 'updated_at'];
	protected $useTimestamps = true;

    public function cek_username($username)
    {
        return $this->where('username', $username)->countAllResults();
    }

    // Inside get_password method in AuthModel
    public function get_password($username)
    {
        $data = $this->where('username', $username)->get()->getRow();
        return $data->password;
    }

    public function userdata($username)
    {
        return $this->where('username', $username)->first();
    }
}
