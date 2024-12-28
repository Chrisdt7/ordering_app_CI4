<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelMenu extends Model
{
    protected $table            = 'menu';
    protected $primaryKey       = 'id_menu';
    protected $useAutoIncrement = true;
    protected $allowedFields    = ['menu_name', 'description', 'price', 'id_category', 'image'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    public function getMenu()
    {
        return $this->findAll();
    }

    public function menuWhere($where)
    {
        return $this->where($where)->findAll();
    }

    public function saveMenu($data = null)
    {
        return $this->insert($data);
    }

    public function updateMenu($data = null, $where = null)
    {
        return $this->update($where, $data);
    }

    public function deleteMenu($id_menu)
    {
        return $this->where('id_menu', $id_menu)->delete();
    }

    public function total($field, $where)
    {
        $this->selectSum($field);
        if (!empty($where) && count($where) > 0) {
            $this->where($where);
        }
        return $this->get()->getRow()->$field;
    }

    // Manajemen Kategori
    public function getKategori()
    {
        $kategoriModel = new \App\Models\ModelKategori(); // Assuming you have another model for kategori
        return $kategoriModel->findAll();
    }

    public function kategoriWhere($where)
    {
        $kategoriModel = new \App\Models\ModelKategori();
        return $kategoriModel->where($where)->findAll();
    }
}
