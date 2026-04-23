<?php
session_start();
include "../config/koneksi.php";

/**
 * Memproses autentikasi user berdasarkan email dan password
 * 
 * Fungsi ini memverifikasi kredensial user dari database dan mengatur session
 * jika login berhasil. Mengarahkan user ke dashboard sesuai role mereka.
 * 
 * @param mysqli $conn Koneksi database MySQLi
 * @param string $email Email user yang akan login
 * @param string $password Password user (belum di-hash)
 * @return void Fungsi ini mengatur session dan melakukan redirect, tidak mengembalikan nilai
 * 
 * @throws Exception Jika query database gagal
 * 
 * @example
 * processLogin($conn, 'user@example.com', 'password123');
 * // Akan mengatur session dan redirect ke dashboard sesuai role
 */
function processLogin($conn, $email, $password) {
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (!$query) {
        die("Query error: " . mysqli_error($conn));
    }
    $user = mysqli_fetch_assoc($query);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['nama']    = $user['nama'];
        $_SESSION['role']    = $user['role'];

        // ARAHKAN SESUAI ROLE
        if ($user['role'] == 'admin' || $user['role'] == 'petugas') {
            header("Location: ../admin/dashboard.php");
        } elseif ($user['role'] == 'peminjam') {
            header("Location: ../user/dashboard.php");
        } else {
            echo "Role tidak dikenali!";
        }
        exit;
    } else {
        echo "Login gagal! <a href='login.php'>Coba lagi</a>";
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    processLogin($conn, $email, $password);
}
