<?php
include "koneksi.php";
$conn = connect();

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); 

    $query = "DELETE FROM peserta WHERE id = $id";
    $result = pg_query($conn, $query);

    if ($result) {
        // Output ini akan dibaca oleh success di AJAX
        echo "berhasil";
    } else {
        echo pg_last_error($conn);
    }
}
?>