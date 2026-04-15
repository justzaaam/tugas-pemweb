<?php
include "koneksi.php";

// 1. Ambil ID dari URL (link yang diklik tadi)
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn = connect();

    // 2. Perintah SQL untuk menghapus berdasarkan ID
    $query = "DELETE FROM peserta WHERE id = $id";
    $result = pg_query($conn, $query);

    if ($result) {
        // 3. Jika berhasil, kembali ke halaman utama
        header("Location: form.php");
    } else {
        echo "Gagal menghapus data: " . pg_last_error($conn);
    }

    pg_close($conn);
} else {
    // Jika mencoba akses langsung tanpa ID, balikkan ke form
    header("Location: form.php");
}
?>