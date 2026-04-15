<?php 
include "koneksi.php"; 
$conn = connect();

// 1. Ambil data Provinsi untuk dropdown awal
$query_prov = "SELECT * FROM provinsi ORDER BY nama_provinsi ASC";
$res_prov = pg_query($conn, $query_prov);

// 2. Logika Simpan Data
if(isset($_POST["submit"])){
    $table_name = 'peserta';
    if(empty($_POST["nama"])) {
        echo "<script>alert('Nama tidak boleh kosong!'); window.history.back();</script>";
        exit;
    }
    
    // Sesuaikan kunci array dengan nama kolom di tabel 'peserta' pgAdmin kamu
    $user_data = array(
    'nama'         => $_POST["nama"],
    'cita_cita' => $_POST["cita_cita"],
    'tempatlahir'  => $_POST["tempat"],
    'tanggallahir' => $_POST["tanggal"],
    'agama'        => $_POST["agama"],
    'alamat'       => $_POST["alamat"],
    'telepon'      => $_POST["telp"],
    'jk'           => ($_POST["kelamin"] == 'laki-laki') ? 1 : 0, 
    'hobi'         => isset($_POST["hobi"]) ? implode(", ", $_POST["hobi"]) : "",
    
    // Nama kolom di sini harus PERSIS dengan yang ada di pgAdmin
    'provinsi_id'  => $_POST["provinsi"], 
    'kabkot_id'    => $_POST["kabkota"]   
);
    
    // Menjalankan insert
    $result = pg_insert($conn, $table_name, $user_data);
    
    if ($result) {
        echo "<script>alert('Data berhasil disimpan!'); window.location='form.php';</script>";
    } else {
        echo "Error: " . pg_last_error($conn);
    }
}
?>
<html>
<head>
    <title>Tugas Kuliah</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    
    <form method="POST">
    <fieldset>
    <legend><h1>formulir pendaftaran siswa</h1></legend>
        
    
    <?php
    echo "<label> nama calon siswa </label>";
    echo "<input type='text' name='nama'>";
    echo "<br>";

    echo "<label> cita-cita </label>";
    echo "<input type='text' name='cita_cita'>";
    echo "<br>";

    echo "<label> Tempat/Tanggal Lahir </label>";
    echo "<input type='text' name='tempat'>"; 
    echo "<input type='date' name='tanggal'>";
    echo "<br>";

    echo "<label> Agama </label>";
    echo "<select name='agama' id='agam'>";
     echo "<option value=''> Pilih agama </option>";
    echo "<option value='islam'>Islam</option>";
    echo "<option value='kristen'>Kristen</option>";
    echo "<option value='katolik'>Katolik</option>";
    echo "<option value='hindu'>Hindu</option>";
    echo "</select>";
    echo "<br>";

    echo "<label> Alamat </label>";
    echo "<input type='text' name='alamat' placeholder='Contoh: JL. Merdeka No. 123' style= 'width: 300px;'>";
    echo "<p style='font-size: 11px; color: gray; margin: 0;'>*Cukup isi nama jalan, nomor rumah, atau dusun. Provinsi dan Kota dipilih di bawah.</p>";
    echo "<br>";

    echo "<label> No Telp/HP </label>";
    echo "<input type='number' name='telp'>";
    echo "<br>";

    echo "<label> Jenis Kelamin </label>";
    echo "<input type='radio' name='kelamin' value='laki-laki'> laki-laki";
    echo "<input type='radio' name='kelamin' value='perempuan'> perempuan";
    echo "<br>";

    echo "<label> hobi </label>";
    echo "<input type='checkbox' name='hobi[]' value='Membaca'> Membaca ";
    echo "<input type='checkbox' name='hobi[]' value='Menulis'> Menulis ";
    echo "<input type='checkbox' name='hobi[]' value='Olahraga'> Olahraga ";
    echo "<br>";

    echo "<label> pas foto </label>";
    echo "<input type='file' name='foto'>";
    echo "<br>";

    echo "<label> Provinsi </label>";
    echo "<select name='provinsi' id='pilihProvinsi' required>";
    echo "<option value=''>-- Pilih Provinsi --</option>";
    while($p = pg_fetch_assoc($res_prov)) {
        echo "<option value='".$p['id']."'>".$p['nama_provinsi']."</option>";
    }
    echo "</select><br>";

// Wadah kosong yang akan diisi otomatis oleh AJAX
    echo "<div id='tampil_kabko'></div>";

    echo "<br>";
    echo "<input type='submit' name='submit' value='Submit'>";
    ?>


    </form>
    </fieldset>

   <br>
<h2>Data Pendaftaran Siswa</h2>

<table border="1" cellpadding="10" cellspacing="0">
    <table border="1" cellpadding="10" cellspacing="0">
    <tr bgcolor="yellow">
    <th rowspan="2">Nama</th>
    <th rowspan="2">cita cita</th>
    <th colspan="2">Lahir</th>
    <th rowspan="2">No Tlp</th>
    <th rowspan="2">Agama</th>
    <th rowspan="2">Jenis Kelamin</th>
    <th rowspan="2">Hobi</th>
    <th rowspan="2">Alamat</th>  
    <th rowspan="2">Lokasi</th>
    <th rowspan="2">Aksi</th>
</tr>
    <tr bgcolor="yellow">
        <th>Tempat</th>
        <th>Tanggal</th>
    </tr>

  
<?php
/// QUERY YANG SUDAH DISESUAIKAN DENGAN NAMA KOLOM DI DATABASE KAMU
$query_tampil = "SELECT p.*, prov.nama_provinsi, kab.nama_kabkot 
                 FROM peserta p
                 LEFT JOIN provinsi prov ON p.provinsi_id = prov.id
                 LEFT JOIN kabkot kab ON p.kabkot_id = kab.id
                 ORDER BY p.id DESC";

$result_tampil = pg_query($conn, $query_tampil);

// Cek jika query gagal untuk debugging
if (!$result_tampil) {
    echo "Query Error: " . pg_last_error($conn);
}

while ($row = pg_fetch_assoc($result_tampil)) {
    $warna = ($row["jk"] == 1) ? "lightblue" : "pink";
?>
<tr style="background-color: <?php echo $warna; ?>;">
    <td><?php echo $row["nama"]; ?></td>
    <td><?php echo $row["cita_cita"]; ?></td>
    <td><?php echo $row["tempatlahir"]; ?></td>
    <td><?php echo $row["tanggallahir"]; ?></td>
    <td><?php echo $row["telepon"]; ?></td>
    <td><?php echo $row["agama"]; ?></td>
    <td><?php echo ($row["jk"] == 1) ? "Laki-Laki" : "Wanita"; ?></td>
    <td><?php echo $row["hobi"]; ?></td>
    <td><?php echo $row["alamat"]; ?></td> <td>
        <?php 
            // Menampilkan Kota dan Provinsi saja
            $lokasi = [];
            if (!empty($row["nama_kabkot"])) $lokasi[] = $row["nama_kabkot"];
            if (!empty($row["nama_provinsi"])) $lokasi[] = $row["nama_provinsi"];
            echo implode(", ", $lokasi);
        ?>
    </td>
    <td align="center">
            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
            <a href="hapus.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Yakin?')">Hapus</a> | 
            <a href="javascript:void(0)" class="btn-hapus-js" data-id="<?php echo $row['id']; ?>" style="color:red; font-weight:bold;">Hapus (JS)</a>
        </td>
</tr>
<?php
}
?>
</table>

   <script>
$(document).ready(function(){
    $('#pilihProvinsi').change(function(){
        var provID = $(this).val();
        if(provID){
            $.ajax({
                type:'POST',
                url:'kabko.php',
                data: {id_prov: provID},
                success:function(html){
                    // Ini akan mengisi DIV tampil_kabko dengan label + select dari kabko.php
                    $('#tampil_kabko').html(html);
                }
            }); 
        } else {
            $('#tampil_kabko').html(""); // Kosongkan jika tidak ada provinsi terpilih
        }
    });
});

$(document).ready(function(){
    // Fitur Hapus via JavaScript (AJAX)
    $('.btn-hapus-js').click(function(){
        var id_peserta = $(this).data('id'); 
        var baris = $(this).closest('tr'); // Mengambil baris tabel agar bisa dihapus otomatis

        if(confirm('Hapus data ini menggunakan JavaScript (Tanpa Reload)?')){
            $.ajax({
                type: 'POST',
                url: 'hapusjs.php', 
                data: {id: id_peserta},
                success: function(response){
                    if(response.trim() == "berhasil"){
                        // Menghapus baris dari tabel dengan efek fade out
                        baris.css("background-color", "#ffcccc");
                        baris.fadeOut(600, function(){
                            $(this).remove();
                        });
                    } else {
                        alert('Gagal menghapus: ' + response);
                    }
                }
            });
        }
    });
});

$(document).ready(function() {
    // Aktifkan pencarian untuk Dropdown Provinsi
    $('#pilihProvinsi').select2({
        placeholder: "Cari Provinsi...",
        allowClear: true
    });

    // Saat Provinsi diubah
    $('#pilihProvinsi').on('change', function() {
        var provID = $(this).val();
        $.ajax({
            type: 'POST',
            url: 'kabko.php',
            data: {id_prov: provID},
            success: function(html) {
                $('#tampil_kabko').html(html);
                // AKTIFKAN pencarian untuk Dropdown Kabupaten yang baru muncul
                $('#kabkota').select2({
                    placeholder: "Cari Kabupaten/Kota...",
                    allowClear: true
                });
            }
        });
    });
});
</script>
    
</body>
</html>