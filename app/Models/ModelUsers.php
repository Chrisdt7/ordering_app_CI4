<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelUsers extends Model
{
    protected $table         = 'users';
    protected $primaryKey    = 'id_users';
    protected $allowedFields = ['username', 'password', 'name', 'email', 'contact', 'role', 'image'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function saveData(array $data)
    {
        return $this->save($data);
    }

    public function checkData($username)
    {
        return $this->where('username', $username)->first();
    }

    // Method to get users based on a condition
    public function getUsersWhere($userId)
    {
        return $this->where('id_users', $userId)->first();
    }

    // Method to get limited users
    public function getUsersWithLimit(int $limit = 10, int $offset = 0)
    {
        return $this->findAll($limit, $offset);
    }

    // Method to update user data
    public function updateUser(int $id, array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        return $this->update($id, $data);
    }

    // Method to delete a user
    public function deleteUser(int $id)
    {
        return $this->delete($id);
    }

    // Method to verify password
    public function verifyPassword($userId, $password)
    {
        $user = $this->find($userId);
        if ($user && password_verify($password, $user['password'])) {
            return true;
        }
        return false;
    }

    public function updatePassword($userId, $newPassword)
    {
        $data = ['password' => password_hash($newPassword, PASSWORD_DEFAULT)];
        return $this->update($userId, $data);
    }
}