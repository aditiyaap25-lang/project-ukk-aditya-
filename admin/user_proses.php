<?php
session_start();
include "../config/koneksi.php";

if ($_SESSION['role'] != 'admin') {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['simpan'])) {

    $nama     = $_POST['nama'];
    $email    = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role     = $_POST['role'];

   
    $query = "INSERT INTO users 
              (nama, email, password, role)
              VALUES 
              ('$nama', '$email', '$password', '$role')";

    mysqli_query($conn, $query);

    header("Location: user.php");
    exit;
}

if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($conn, 
        "DELETE FROM users WHERE id_user='$id'"
    );

    header("Location: user.php");
    exit;
}
?>
