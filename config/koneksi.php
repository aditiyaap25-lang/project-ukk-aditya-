<?php
$conn = mysqli_connect("localhost", "root", "", "db_perpustakaan");

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>
