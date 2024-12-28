<?php

namespace App\Models;

use CodeIgniter\Model;

class ModelCategory extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id_category';
    protected $allowedFields = ['category_name'];

    /* Get all categories */
    public function getAllCategories()
    {
        return $this->findAll();
    }

    /* Get category by ID */
    public function getCategoryById($id)
    {
        return $this->find($id);
    }

    /* Insert a new category */
    public function saveCategory($data)
    {
        if (isset($data['category_name'])) {
            $data['category_name'] = ucwords(strtolower($data['category_name']));
        }
        return $this->save($data);
    }

    /* Update category by ID */
    public function updateCategory($id, $data)
    {
        return $this->update($id, $data);
    }

    /* Delete category by ID */
    public function deleteCategory($id)
    {
        return $this->delete($id);
    }
}
