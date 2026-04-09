<!DOCTYPE html>
<html>
<head>
    <title>Edit Data Siswa</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2>Edit Data Siswa</h2>
    <form action="<?= base_url('peserta/update') ?>" method="POST">
        <input type="hidden" name="id" value="<?= $data['id'] ?>">

        <div class="form-group">
            <label>Nama</label>
            <input type="text" name="nama" value="<?= $data['nama'] ?>" style="width: 300px;" required>
        </div>

        <div class="form-group">
            <label>Cita-Cita</label>
            <input type="text" name="cita_cita" value="<?= $data['cita_cita'] ?>"> 
        </div>

        <div class="form-group">
            <label>Tempat/Tanggal Lahir</label>
            <input type="text" name="tempat" value="<?= $data['tempatlahir'] ?>"> 
            <input type="date" name="tanggal" value="<?= $data['tanggallahir'] ?>">
        </div>

        <div class="form-group">
            <label>Agama</label>
            <select name="agama">
                <?php $list_agama = ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Budha']; ?>
                <?php foreach($list_agama as $ag): ?>
                    <option value="<?= $ag ?>" <?= ($data['agama'] == $ag) ? 'selected' : '' ?>><?= $ag ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Alamat</label>
            <textarea name="alamat" rows="2" cols="40"><?= $data['alamat'] ?></textarea>
        </div>

        <div class="form-group">
            <label>No Telp/HP</label>
            <input type="text" name="telp" value="<?= $data['telepon'] ?>">
        </div>

        <div class="form-group">
            <label>Jenis Kelamin</label>
            <input type="radio" name="kelamin" value="Laki-Laki" <?= ($data['jk'] == "1" || $data['jk'] == "Laki-Laki") ? 'checked' : '' ?>> Laki-laki
            <input type="radio" name="kelamin" value="Perempuan" <?= ($data['jk'] == "0" || $data['jk'] == "Perempuan") ? 'checked' : '' ?>> Perempuan
        </div>

        <div class="form-group">
            <label>Hobi</label>
            <?php 
                $hobi_list = ['Membaca', 'Menulis', 'Olahraga']; 
                // $hobi_user sudah dikirim dari controller dalam bentuk array
            ?>
            <?php foreach($hobi_list as $h): ?>
                <input type="checkbox" name="hobi[]" value="<?= $h ?>" <?= (in_array($h, $hobi_user)) ? 'checked' : '' ?>> <?= $h ?>
            <?php endforeach; ?>
        </div>

        <div class="form-group">
            <label>Provinsi</label>
            <select name="provinsi" id="pilihProvinsi" style="width:250px;">
                <?php foreach($provinsi as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($p['id'] == $data['provinsi_id']) ? 'selected' : '' ?>>
                        <?= $p['nama_provinsi'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div id="tampil_kabko" class="form-group">
            </div>

        <button type="submit" style="padding: 10px 20px; background: blue; color: white; border: none; cursor: pointer;">Update Data</button>
        <a href="<?= base_url('peserta') ?>" style="margin-left: 10px;">Batal</a>
    </form>

    <script>
    $(document).ready(function() {
        $('#pilihProvinsi').select2();

        // Fungsi Load Kabupaten
        function loadKabupaten(provID, selectedKabID = "") {
            $.ajax({
                type: 'POST',
                url: '<?= base_url('peserta/get_kabkota') ?>',
                data: {id_prov: provID},
                success: function(html) {
                    $('#tampil_kabko').html(html);
                    $('#kabkota').select2();
                    if(selectedKabID) {
                        $('#kabkota').val(selectedKabID).trigger('change');
                    }
                }
            });
        }

        // Jalankan load pertama kali saat halaman dibuka
        loadKabupaten("<?= $data['provinsi_id'] ?>", "<?= $data['kabkot_id'] ?>");

        // Ganti kabupaten jika provinsi diubah
        $('#pilihProvinsi').change(function() {
            loadKabupaten($(this).val());
        });
    });
    </script>
</body>
</html>