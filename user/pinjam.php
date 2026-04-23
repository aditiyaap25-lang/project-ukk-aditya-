
<?php
session_start();
include "../config/koneksi.php";

// CEK LOGIN
if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_buku = null;
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_buku = (int) $_GET['id'];
} elseif (isset($_GET['id_buku']) && is_numeric($_GET['id_buku'])) {
    $id_buku = (int) $_GET['id_buku'];
}

if (!$id_buku) {
    header("Location: buku.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];


$cek_sudah_pinjam = mysqli_query($conn, "
    SELECT p.id_peminjaman 
    FROM peminjaman p 
    WHERE p.id_user='$id_user' 
    AND p.id_buku='$id_buku' 
    AND p.status='dipinjam'
");

if (mysqli_num_rows($cek_sudah_pinjam) > 0) {
 
    $_SESSION['error'] = 'Anda sudah meminjam buku ini! Harap mengembalikan buku terlebih dahulu sebelum meminjam lagi.';
    header("Location: buku.php");
    exit;
}

$cek = mysqli_query($conn, "SELECT stok FROM buku WHERE id_buku='$id_buku'");
$buku = mysqli_fetch_assoc($cek);
if (!$buku || $buku['stok'] <= 0) {
    $_SESSION['error'] = 'Stok buku tidak tersedia!';
    header("Location: buku.php");
    exit;
}


$insert = mysqli_query($conn, "INSERT INTO peminjaman (id_user, id_buku, tgl_pinjam, status) VALUES ('$id_user', '$id_buku', NOW(), 'dipinjam')");
if ($insert) {
    mysqli_query($conn, "UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'");
    $_SESSION['success'] = 'Buku berhasil dipinjam!';
}

header("Location: buku.php");
exit;
