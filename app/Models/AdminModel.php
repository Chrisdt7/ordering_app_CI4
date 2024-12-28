<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Services;

class AdminModel extends Model
{
    protected $table = 'user';
	protected $primaryKey = 'id_user';
	protected $allowedFields = ['nama_lengkap', 'username', 'email', 'alamat', 'no_telp', 'password', 'image', 'id_role', 'created_at', 'updated_at'];
	protected $useTimestamps = true;
	protected $db;
	
	public function __construct()
    {
        parent::__construct();
        $this->db = \Config\Database::connect();
    }

    public function get($table, $data = null, $where = null)
    {
        if ($data != null) {
            return $this->where($data)->get()->getRowArray();
        } else {
            return $this->where($where)->get()->getResultArray();
        }
    }

    public function update($id = null, $row = null): bool
	{
		if ($id === null) {
			$id = $this->tempId;
		} elseif ($this->tempId !== $id) {
			$this->tempId = $id;
		}

		return parent::update($id, $row);
	}

    public function insertUser($data)
    {
        return $this->db->table($this->table)->insert($data);
    }

    public function delete($id = null, bool $purge = false): bool
	{
		return parent::delete($id, $purge);
	}

    public function update_stok($id_barang, $data)
    {
        return $this->set($data)->where('id_barang', $id_barang)->update('barang');
    }

    public function getUsers($id)
    {
        $this->where('id_user !=', $id);
        return $this->get()->getResultArray();
    }

    public function getBarang()
    {
        $this->join('jenis j', 'b.jenis_id = j.id_jenis');
        $this->orderBy('id_barang');
        return $this->get('barang b')->getResultArray();
    }

    public function get_barang($id_barang)
	{
		return $this->db->query("SELECT * FROM barang WHERE id_barang='$id_barang'");
	}

	public function getBarangMasuk($limit = null, $id_barang = null, $range = null)
	{
		$builder = $this->db->table('barang_masuk bm');
		$builder->select('*');
		$builder->join('supplier sp', 'bm.id_supplier = sp.id_supplier');
		$builder->join('barang b', 'bm.id_barang = b.id_barang');
		
		if ($limit != null) {
			$builder->limit($limit);
		}

		if ($id_barang != null) {
			$builder->where('id_barang', $id_barang);
		}

		if ($range != null) {
			$builder->where('tanggal_masuk >=', $range['mulai']);
			$builder->where('tanggal_masuk <=', $range['akhir']);
		}

		$builder->orderBy('id_barang_masuk', 'DESC');
		return $builder->get()->getResultArray();
	}

	public function getBarangKeluarDashboard($limit = null, $range = null)
	{
		$builder = $this->db->table('barang_keluar bkl');
		$builder->select('*');
		$builder->join('pelanggan p', 'bkl.id_pelanggan = p.id_pelanggan');
		$builder->join('barang b', 'bkl.id_barang = b.id_barang');

		if ($limit != null) {
			$builder->limit($limit);
		}

		if ($range != null) {
			$builder->where('tanggal_keluar >=', $range['mulai']);
			$builder->where('tanggal_keluar <=', $range['akhir']);
		}

		$builder->orderBy('bkl.tanggal_keluar', 'DESC');
		return $builder->get()->getResultArray();
	}

	public function getBarangKeluar($limit = null, $range = null)
	{
		$builder = $this->db->table('barang_keluar_dtl bkd');
		$builder->select('*');
		$builder->join('barang_keluar bk', 'bk.id_barang_keluar = bkd.id_barang_keluar');
		$builder->join('barang b', 'bkd.barang_id = b.id_barang');
		$builder->join('satuan s', 'b.satuan_id = s.id_satuan');

		if ($limit != null) {
			$builder->limit($limit);
		}

		if ($range != null) {
			$builder->where('tanggal_keluar >=', $range['mulai']);
			$builder->where('tanggal_keluar <=', $range['akhir']);
		}

		$builder->orderBy('bkl.id_barang_keluar', 'DESC');
		return $builder->get()->getResultArray();
	}

	public function getIDBarangKeluar($id_barang_keluar)
	{
		$builder = $this->db->table('barang_keluar bk');
		$builder->select('*');
		$builder->join('user u', 'bk.user_id = u.id_user');
		$builder->join('barang b', 'bk.barang_id = b.id_barang');
		$builder->join('jenis j', 'b.jenis_id = j.id_jenis');
		$builder->join('satuan s', 'b.satuan_id = s.id_satuan');
		$builder->where('bk.id_barang_keluar', $id_barang_keluar);
		$builder->orderBy('id_barang_keluar', 'DESC');
		return $builder->get()->getRowArray();
	}

	public function getIDBarangKeluar2($id_barang_keluar)
	{
		$builder = $this->db->table('barang_keluar_dtl bkd');
		$builder->select('*');  
		$builder->join('barang_keluar bk', 'bk.id_barang_keluar = bkd.id_barang_keluar');
		$builder->join('user u', 'bk.user_id = u.id_user');
		$builder->join('barang b', 'bkd.barang_id = b.id_barang');
		$builder->join('jenis j', 'b.jenis_id = j.id_jenis');
		$builder->join('satuan s', 'b.satuan_id = s.id_satuan');
		$builder->where('bk.id_barang_keluar', $id_barang_keluar);
		return $builder->get()->getRowArray();
	}

	public function findIDBarangKeluar($id)
	{
		$builder = $this->db->table('barang_keluar');
		$query = $builder->where('id_barang_keluar', $id)->get();
		if ($query->numRows() > 0) {
			return $query->getRowArray();
		} else {
			return array();
		}
	}

	public function simpan_cart($id_barang_keluar)
	{
		foreach ($this->cart->contents() as $item) {
			$data = array(
				'id_barang_keluar' => $id_barang_keluar,
				'barang_id' => $item['id'],
				'harga' => $item['amount'],
				'jumlah_keluar' => $item['qty'],
				'total_nominal_dtl' => $item['subtotal']
			);
			$this->db->table('barang_keluar_dtl')->insert($data);
			// $this->db->query("update tbl_barang set barang_stok=barang_stok-'$item[qty]' where barang_id='$item[id]'");
		}
		return true;
	}

	public function getMax($table, $field, $kode = null)
	{
		$this->db->selectMax($field);
		if ($kode != null) {
			$this->db->like($field, $kode, 'after');
		}
		$result = $this->db->get($table)->getRowArray();
		return $result[$field];
	}

	public function count($table)
	{
		return $this->db->table($table)->countAllResults();
	}

	public function sum($table, $field)
	{
		$query = $this->db->query("SELECT SUM($field) AS total FROM $table");
		$result = $query->getRowArray();
		return $result ? $result['total'] : 0; // Return 0 if no result or field not found
	}

	public function sumEarnings()
	{
		$builder = $this->db->table('barang_keluar bk')
							->selectSum('b.harga_barang', 'total_earnings')
							->join('barang b', 'bk.id_barang = b.id_barang');
		$result = $builder->get()->getRowArray();
		return $result['total_earnings'];
	}

	public function min($table, $field, $min)
	{
		$sql = "SELECT MIN($field) AS min_value FROM $table WHERE $field < ?";
		$query = $this->db->query($sql, [$min]);
		$result = $query->getRowArray();
		return $result['min_value'];
	}

	public function chartBarangMasuk($bulan)
	{
		$like = 'I' . date('y') . $bulan;
		$sql = "SELECT COUNT(*) AS count FROM barang_masuk WHERE id_barang_masuk LIKE ?";
		$query = $this->db->query($sql, ["%$like"]);
		$result = $query->getRowArray();
		return $result['count'];
	}

	public function chartBarangKeluar($bulan)
	{
		$like = 'S' . date('y') . $bulan;
		$sql = "SELECT COUNT(*) AS count FROM barang_keluar WHERE id_barang_keluar LIKE ?";
		$query = $this->db->query($sql, ["%$like"]);
		$result = $query->getRowArray();
		return $result['count'];
	}

	public function laporan($table, $mulai, $akhir)
	{
		$tgl = ($table == 'barang_masuk') ? 'tanggal_masuk' : 'tanggal_keluar';
		$this->db->where("$tgl >=", $mulai);
		$this->db->where("$tgl <=", $akhir);
		return $this->db->get($table)->getResultArray();
	}

	public function cekStok($id)
	{
		$this->db->join('satuan s', 'b.satuan_id = s.id_satuan');
		return $this->db->getWhere('barang b', ['id_barang' => $id])->getRowArray();
	}
}
