<?php namespace App\Models;
use CodeIgniter\Model;

class WilayahModel extends Model {
    public function getProvinsi() {
        return $this->db->table('provinsi')->orderBy('nama_provinsi', 'ASC')->get()->getResultArray();
    }

    public function getKabkot($id_prov) {
        return $this->db->table('kabkot')->where('provinsi_id', $id_prov)->orderBy('nama_kabkot', 'ASC')->get()->getResultArray();
    }
}