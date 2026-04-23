<?php
session_start();
include "../config/koneksi.php";

$id = $_GET['id'];


mysqli_query($conn,"
    UPDATE peminjaman 
    SET 
        status='dikembalikan',
        tgl_kembali=NOW()
    WHERE id_peminjaman='$id'
");


$data = mysqli_fetch_assoc(mysqli_query($conn,"
    SELECT id_buku 
    FROM peminjaman 
    WHERE id_peminjaman='$id'
"));

$id_buku = $data['id_buku'];


mysqli_query($conn,"
    UPDATE buku 
    SET stok = stok + 1
    WHERE id_buku='$id_buku'
");

header("Location: kembali.php");
