<?php
include "koneksi.php";
$conn = connect();

// 1. Ambil data berdasarkan ID dari URL
$id = $_GET['id'];
$query_data = pg_query($conn, "SELECT * FROM peserta WHERE id=$id");
$data = pg_fetch_assoc($query_data);
// Memecah string hobi (misal: "Membaca, Musik") menjadi array agar bisa dicek
$hobi_user = explode(", ", $data['hobi']);

// 2. Logika Update Data
if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $cita_cita = $_POST['cita_cita'];
    $tempat = $_POST['tempat'];
    $tanggal = $_POST['tanggal'];
    $agama = $_POST['agama'];
    $alamat = $_POST['alamat'];
    $telp = $_POST['telp'];
    $jk = ($_POST['kelamin'] == 'laki-laki') ? 1 : 0;
    $hobi = isset($_POST['hobi']) ? implode(", ", $_POST['hobi']) : "";
    $provinsi = $_POST['provinsi'];
    $kabkota = $_POST['kabkota'];

    $query_update = "UPDATE peserta SET 
        nama='$nama', 
        cita_cita= '$cita_cita',
        tempatlahir='$tempat', 
        tanggallahir='$tanggal',
        agama='$agama',
        alamat='$alamat',
        telepon='$telp',
        jk=$jk,
        hobi='$hobi',
        provinsi_id=$provinsi,
        kabkot_id=$kabkota
        WHERE id=$id";

    $result = pg_query($conn, $query_update);
    if ($result) {
        echo "<script>alert('Data berhasil diupdate!'); window.location='form.php';</script>";
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}

// Ambil semua provinsi untuk dropdown
$res_prov = pg_query($conn, "SELECT * FROM provinsi ORDER BY nama_provinsi ASC");
?>

<html>
<head>
    <title>Edit Data Siswa</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h1>Edit Data Pendaftaran</h1>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo $data['id']; ?>">

        <label>Nama:</label>
        <input type="text" name="nama" value="<?php echo $data['nama']; ?>"><br><br>

        <label>cita-cita:</label>
        <input type="text" name="cita_cita" value="<?php echo $data['cita_cita']; ?>"><br><br>

        <label>Tempat/Tanggal Lahir:</label>
        <input type="text" name="tempat" value="<?php echo $data['tempatlahir']; ?>">
        <input type="date" name="tanggal" value="<?php echo $data['tanggallahir']; ?>"><br><br>

        <label>Agama:</label>
        <select name="agama">
            <option value="">Pilih agama</option>
            <option value="islam" <?php if($data['agama'] == 'islam') echo 'selected'; ?>>Islam</option>
            <option value="kristen" <?php if($data['agama'] == 'kristen') echo 'selected'; ?>>Kristen</option>
            <option value="katolik" <?php if($data['agama'] == 'katolik') echo 'selected'; ?>>Katolik</option>
            <option value="hindu" <?php if($data['agama'] == 'hindu') echo 'selected'; ?>>Hindu</option>
        </select><br><br>

        <label>Alamat Jalan:</label>
        <input type="text" name="alamat" value="<?php echo $data['alamat']; ?>" placeholder="Contoh: Jl. Merdeka No. 123">
        <p style="font-size: 11px; color: gray; margin: 0;">*Cukup isi nama jalan/nomor rumah.</p><br>

        <label>No Telp/HP:</label>
        <input type="number" name="telp" value="<?php echo $data['telepon']; ?>"><br><br>

        <label>Jenis Kelamin:</label>
        <input type="radio" name="kelamin" value="laki-laki" <?php if($data['jk'] == 1) echo 'checked'; ?>> Laki-laki
        <input type="radio" name="kelamin" value="perempuan" <?php if($data['jk'] == 0) echo 'checked'; ?>> Perempuan<br><br>

        <label>Hobi:</label><br>
        <input type="checkbox" name="hobi[]" value="Membaca" <?php echo (in_array("Membaca", $hobi_user)) ? "checked" : ""; ?>> Membaca<br>
        <input type="checkbox" name="hobi[]" value="Olahraga" <?php echo (in_array("Olahraga", $hobi_user)) ? "checked" : ""; ?>> Olahraga<br>
        <input type="checkbox" name="hobi[]" value="Musik" <?php echo (in_array("Musik", $hobi_user)) ? "checked" : ""; ?>> Musik<br>
        <input type="checkbox" name="hobi[]" value="Traveling" <?php echo (in_array("Traveling", $hobi_user)) ? "checked" : ""; ?>> Traveling<br>
            <br>

        <label>Provinsi:</label>
        <select name="provinsi" id="pilihProvinsi" required>
            <option value="">-- Pilih Provinsi --</option>
            <?php while($p = pg_fetch_assoc($res_prov)) { ?>
                <option value="<?php echo $p['id']; ?>" <?php if($p['id'] == $data['provinsi_id']) echo 'selected'; ?>>
                    <?php echo $p['nama_provinsi']; ?>
                </option>
            <?php } ?>
        </select><br><br>

        <div id="tampil_kabko">
            <label>Kabupaten/Kota:</label>
            <select name="kabkota" id="kabkota" required>
                <option value="">-- Pilih Kabupaten/Kota --</option>
            </select>
        </div><br>

        <button type="submit" name="update">Update Data</button>
        <a href="form.php">Batal</a>
    </form>

    <script>
    $(document).ready(function(){
        // 1. Fungsi untuk memuat Kabupaten berdasarkan Provinsi
        function loadKabupaten(provID, selectedKab = "") {
            $.ajax({
                type:'POST',
                url:'kabko.php',
                data: {id_prov: provID},
                success:function(html){
                    $('#tampil_kabko').html(html);
                    // Jika ada data kabkot yang harus terpilih (saat baru buka halaman edit)
                    if(selectedKab != "") {
                        $('#kabkota').val(selectedKab);
                    }
                }
            });
        }

        // 2. Saat halaman dibuka, langsung muat kabupaten sesuai data lama
        var provAwal = "<?php echo $data['provinsi_id']; ?>";
        var kabAwal = "<?php echo $data['kabkot_id']; ?>";
        if(provAwal){
            loadKabupaten(provAwal, kabAwal);
        }

        // 3. Saat dropdown provinsi diubah secara manual
        $(document).on('change', '#pilihProvinsi', function(){
            var provID = $(this).val();
            if(provID){
                loadKabupaten(provID);
            } else {
                $('#tampil_kabko').html('<label>Kabupaten/Kota:</label><select name="kabkota"><option value="">-- Pilih Kabupaten/Kota --</option></select>');
            }
        });
    });
    </script>
</body>
</html>