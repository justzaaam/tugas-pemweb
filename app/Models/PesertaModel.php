<?php

namespace App\Models;

use CodeIgniter\Model;

class PesertaModel extends Model
{
    protected $table      = 'peserta';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nama', 'cita_cita', 'tempatlahir', 'tanggallahir', 'agama', 
        'alamat', 'telepon', 'jk', 'hobi', 'provinsi_id', 'kabkot_id'
    ];

    public function getPesertaLengkap()
    {
        return $this->select('peserta.*, provinsi.nama_provinsi, kabkot.nama_kabkot')
                    ->join('provinsi', 'provinsi.id = peserta.provinsi_id', 'left')
                    ->join('kabkot', 'kabkot.id = peserta.kabkot_id', 'left')
                    ->orderBy('peserta.id', 'DESC')
                    ->findAll();
    }
}