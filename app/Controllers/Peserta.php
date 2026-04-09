<?php

namespace App\Controllers;

use App\Models\PesertaModel;

class Peserta extends BaseController
{
    protected $pesertaModel;

    public function __construct() {
        $this->pesertaModel = new PesertaModel();
    }

    public function index() {
        $db = \Config\Database::connect();
        $data = [
            'peserta'  => $this->pesertaModel->getPesertaLengkap(),
            'provinsi' => $db->table('provinsi')->orderBy('nama_provinsi', 'ASC')->get()->getResultArray()
        ];
        return view('peserta_view', $data);
    }

    public function simpan() {
    $model = new \App\Models\PesertaModel();
    $hobi = $this->request->getPost('hobi');
    
    // Konversi teks radio button ke angka integer untuk database
    $jk_input = $this->request->getPost('kelamin');
    $jk_db = ($jk_input == "Laki-Laki") ? 1 : 0;

    $data = [
        'nama'         => $this->request->getPost('nama'),
        'cita_cita'    => $this->request->getPost('cita_cita'),
        'tempatlahir'  => $this->request->getPost('tempat'),
        'tanggallahir' => $this->request->getPost('tanggal'),
        'agama'        => $this->request->getPost('agama'),
        'alamat'       => $this->request->getPost('alamat'),
        'telepon'      => $this->request->getPost('telp'),
        'jk'           => $jk_db, // Masukkan angka 1 atau 0
        'hobi'         => $hobi ? implode(", ", $hobi) : "",
        'provinsi_id'  => $this->request->getPost('provinsi'),
        'kabkot_id'    => $this->request->getPost('kabkota'),
    ];
    
    $model->insert($data);
    return redirect()->to(base_url('peserta'));
}
    public function edit($id) {
        $db = \Config\Database::connect();
        $peserta = $this->pesertaModel->find($id);
        $data = [
            'data'      => $peserta,
            'provinsi'  => $db->table('provinsi')->orderBy('nama_provinsi', 'ASC')->get()->getResultArray(),
            'hobi_user' => explode(", ", $peserta['hobi'] ?? '')
        ];
        return view('edit_view', $data);
    }

    public function update()
{
    $model = new \App\Models\PesertaModel();
    $id = $this->request->getPost('id');
    $hobi = $this->request->getPost('hobi');

    // SINKRONISASI DATA: Ubah teks radio button menjadi angka untuk database
    $jk_input = $this->request->getPost('kelamin');
    $jk_db = ($jk_input == "Laki-Laki") ? 1 : 0;

    $data = [
        'nama'         => $this->request->getPost('nama'),
        'cita_cita'    => $this->request->getPost('cita_cita'),
        'tempatlahir'  => $this->request->getPost('tempat'),
        'tanggallahir' => $this->request->getPost('tanggal'),
        'agama'        => $this->request->getPost('agama'),
        'alamat'       => $this->request->getPost('alamat'),
        'telepon'      => $this->request->getPost('telp'),
        'jk'           => $jk_db, // Kirim angka 1 atau 0 ke PostgreSQL
        'hobi'         => $hobi ? implode(", ", $hobi) : "",
        'provinsi_id'  => $this->request->getPost('provinsi'),
        'kabkot_id'    => $this->request->getPost('kabkota'),
    ];

    $model->update($id, $data);
    return redirect()->to(base_url('peserta'));
}

    public function hapus($id) {
        $this->pesertaModel->delete($id);
        return redirect()->to(base_url('peserta'));
    }

    public function hapus_js() {
        $id = $this->request->getPost('id');
        if ($this->pesertaModel->delete($id)) {
            return "berhasil";
        }
        return "gagal";
    }

    public function get_kabkota() {
        $id_prov = $this->request->getPost('id_prov');
        $db = \Config\Database::connect();
        $results = $db->table('kabkot')->where('provinsi_id', $id_prov)->orderBy('nama_kabkot', 'ASC')->get()->getResultArray();

        echo "<label>Kabupaten/Kota</label><br>";
        echo "<select id='kabkota' name='kabkota' required style='width:250px;'>";
        echo "<option value=''>-- Pilih Kabupaten --</option>";
        foreach($results as $row) {
            echo "<option value='".$row['id']."'>".$row['nama_kabkot']."</option>";
        }
        echo "</select>";
    }
}