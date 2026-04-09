<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Siswa CI4</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: yellow; padding: 10px; border: 1px solid black; }
        td { padding: 8px; border: 1px solid black; text-align: center; }
        .form-group { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h2>formulir pendaftaran siswa</h2>
    <form action="<?= base_url('peserta/simpan') ?>" method="POST">
        <div class="form-group">
            <label>nama</label><br>
            <input type="text" name="nama" style="width: 300px;" required>
        </div>
        <div class="form-group">
            <label>Cita-Cita</label><br>
            <input type="text" name="cita_cita"> 
        </div>
        <div class="form-group">
            <label>Tempat/Tanggal Lahir</label><br>
            <input type="text" name="tempat" placeholder="Tempat"> 
            <input type="date" name="tanggal">
        </div>
        <div class="form-group">
            <label>Agama</label><br>
            <select name="agama">
                <option value="Islam">Islam</option>
                <option value="Kristen">Kristen</option>
                <option value="Katolik">Katolik</option>
                <option value="Hindu">Hindu</option>
                <option value="Budha">Budha</option>
            </select>
        </div>
        <div class="form-group">
            <label>Alamat</label><br>
            <textarea name="alamat" rows="2" cols="40"></textarea>
        </div>
        <div class="form-group">
            <label>No Telp/HP</label><br>
            <input type="text" name="telp">
        </div>
        <div class="form-group">
            <label>Jenis Kelamin</label>
            <input type="radio" name="kelamin" value="Laki-Laki"> Laki-laki
            <input type="radio" name="kelamin" value="Perempuan"> Perempuan
        </div>
        <div class="form-group">
            <label>hobi</label>
            <input type="checkbox" name="hobi[]" value="Membaca"> Membaca
            <input type="checkbox" name="hobi[]" value="Menulis"> Menulis
            <input type="checkbox" name="hobi[]" value="Olahraga"> Olahraga
        </div>
        <div class="form-group">
            <label>Provinsi</label><br>
            <select name="provinsi" id="pilihProvinsi" style="width:250px;">
                <option value="">-- Cari Provinsi --</option>
                <?php foreach($provinsi as $p): ?>
                    <option value="<?= $p['id'] ?>"><?= $p['nama_provinsi'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div id="tampil_kabko" class="form-group"></div>
        <button type="submit">Submit</button>
    </form>

    <hr>
    <h3>Data Pendaftaran Siswa</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2">Nama</th>
                <th rowspan="2">Cita-Cita</th>
                <th colspan="2">Lahir</th>
                <th rowspan="2">No Tlp</th>
                <th rowspan="2">Agama</th>
                <th rowspan="2">Jenis Kelamin</th>
                <th rowspan="2">Hobi</th>
                <th rowspan="2">Alamat</th>
                <th rowspan="2">Lokasi</th>
                <th rowspan="2">Aksi</th>
            </tr>
            <tr>
                <th>Tempat</th>
                <th>Tanggal</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($peserta as $row): ?>
            <tr>
    <td><?= $row['nama'] ?></td>
    <td><?= $row['cita_cita'] ?></td>
    <td><?= $row['tempatlahir'] ?></td>
    <td><?= $row['tanggallahir'] ?></td>
    <td><?= $row['telepon'] ?></td>
    <td><?= $row['agama'] ?></td>
    <td>
    <?php 
        if ($row['jk'] == 1) {
            echo "Laki-Laki";
        } else {
            echo "Perempuan";
        }
    ?>
</td>
    <td><?= $row['hobi'] ?></td>
    <td><?= $row['alamat'] ?></td>
    <td><?= ($row['nama_kabkot'] ?? '-') . ", " . ($row['nama_provinsi'] ?? '-') ?></td>
    <td>
        <a href="<?= base_url('peserta/edit/'.$row['id']) ?>">Edit</a> | 
        <a href="<?= base_url('peserta/hapus/'.$row['id']) ?>">Hapus</a> | 
        <button class="btn-hapus-js" data-id="<?= $row['id'] ?>" style="background:red; color:white; border:none; cursor:pointer;">Hapus JS</button>
    </td>
</tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <script>
    $(document).ready(function() {
        $('#pilihProvinsi').select2();
        $('#pilihProvinsi').change(function() {
            var provID = $(this).val();
            if(provID) {
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('peserta/get_kabkota') ?>',
                    data: {id_prov: provID},
                    success: function(html) {
                        $('#tampil_kabko').html(html);
                        $('#kabkota').select2();
                    }
                });
            }
        });

        $('.btn-hapus-js').click(function() {
            var id = $(this).data('id');
            var row = $(this).closest('tr');
            if(confirm('Hapus data ini via AJAX?')) {
                $.ajax({
                    type: 'POST',
                    url: '<?= base_url('peserta/hapus_js') ?>',
                    data: {id: id},
                    success: function(res) {
                        if(res.trim() == "berhasil") {
                            row.fadeOut(500);
                        } else {
                            alert("Gagal menghapus data.");
                        }
                    }
                });
            }
        });
    });
    </script>
</body>
</html>