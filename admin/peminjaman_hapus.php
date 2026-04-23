<?php
include "../config/koneksi.php";
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM peminjaman WHERE id_peminjaman='$id'");
header("Location: peminjaman.php");
