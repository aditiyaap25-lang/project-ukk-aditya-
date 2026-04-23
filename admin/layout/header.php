<?php
session_start();
include "../config/koneksi.php";

// Proteksi login
if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Panel</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background: #f4f6f8; }
        .sidebar {
            width: 250px;
            min-height: 100vh;
            background: #0d6efd;
        }
        .sidebar a {
            color: #fff;
            padding: 12px 15px;
            display: block;
            text-decoration: none;
        }
        .sidebar a.active,
        .sidebar a:hover {
            background: rgba(255,255,255,0.2);
            border-left: 4px solid #fff;
        }
    </style>
</head>
<body>
