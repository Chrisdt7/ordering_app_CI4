<?php namespace App\Models;

use CodeIgniter\Model;

class ModelTables extends Model
{
    protected $table = 'tables';
    protected $primaryKey = 'id_tables';

    protected $allowedFields = ['table_number', 'qr_code', 'created_at', 'updated_at'];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';

	public function getTable()
    {
        return $this->findAll();
    }

	public function insertTable($data)
    {
        return $this->insert($data);
    }

    public function deleteTable($table_number)
    {
        return $this->where('table_number', $table_number)->delete();
    }

}
