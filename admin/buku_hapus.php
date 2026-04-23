<?php
session_start();
include "../config/koneksi.php";

// Cek login
if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}


if (isset($_POST['id_buku'])) {

    $id = $_POST['id_buku'];

    mysqli_query($conn, "
        DELETE FROM peminjaman 
        WHERE id_buku='$id'
    ");

    $book = mysqli_query($conn, "SELECT cover, file_buku FROM buku WHERE id_buku='$id'");
    $row = mysqli_fetch_assoc($book);
    if ($row) {
        if (!empty($row['cover']) && file_exists(__DIR__ . '/../cover/' . $row['cover'])) {
            @unlink(__DIR__ . '/../cover/' . $row['cover']);
        }
        if (!empty($row['file_buku']) && file_exists(__DIR__ . '/../ebook/' . $row['file_buku'])) {
            @unlink(__DIR__ . '/../ebook/' . $row['file_buku']);
        }
    }

    // Hapus buku
    mysqli_query($conn, "
        DELETE FROM buku 
        WHERE id_buku='$id'
    ") or die(mysqli_error($conn));

}

// Kembali ke halaman buku
header("Location: buku.php");
exit;
