<?php
include "../config/koneksi.php";

$id = $_GET['id'];
$id_buku = $_GET['buku'];
$tgl_kembali = date('Y-m-d');

mysqli_query($conn, "
    UPDATE peminjaman 
    SET status='dikembalikan', tgl_kembali='$tgl_kembali'
    WHERE id_peminjaman='$id'
");

mysqli_query($conn, "
    UPDATE buku SET stok = stok + 1 WHERE id_buku='$id_buku'
");

header("Location: peminjaman.php");
