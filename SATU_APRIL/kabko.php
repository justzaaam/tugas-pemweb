<?php
include 'koneksi.php';
$conn = connect(); 
// Pastikan index 'id_prov' ini sama dengan yang dikirim di AJAX form.php
$id_prov = isset($_POST['id_prov']) ? $_POST['id_prov'] : '';

echo "<label> Kabupaten/Kota </label>"; // Tambahkan label jika perlu
?>

<select id="kabkota" name="kabkota" required>
    <option value="">-- Pilih Kabupaten/Kota --</option>
    <?php
    if($id_prov != ''){
        $result = getKabupatenKota($id_prov);
        while($row = pg_fetch_assoc($result)){
            // Di tabel 'kabkot' kolomnya adalah 'nama_kabkot'
            echo "<option value='".$row['id']."'>".$row['nama_kabkot']."</option>";
        }
    }
    ?>
</select>