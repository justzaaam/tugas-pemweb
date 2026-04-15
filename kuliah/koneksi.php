<?php

function connect(){
// Detail koneksi
$host = "localhost";
$port = "5432";
$dbname = "kuliah";
$user = "postgres";
$password = "112233";

// Membuat string koneksi
$connection_string = "host={$host} port={$port} dbname={$dbname} user={$user} password={$password}";

// Menghubungkan ke database
$dbconn = pg_connect($connection_string);

// Memeriksa koneksi
if (!$dbconn) {
    die("Koneksi gagal: " . pg_last_error());
} else {
    // Baris "echo" sudah dihapus agar tidak merusak layout dan redirect
    return $dbconn;
}
}
function getKabupatenKota($id_prov) {
    $dbconn = connect(); 
    // Sesuaikan nama tabel 'kabkot' dan kolom 'provinsi_id'
    $query = "SELECT * FROM kabkot WHERE provinsi_id = '$id_prov' ORDER BY nama_kabkot ASC";
    $result = pg_query($dbconn, $query);
    return $result;
}

?>