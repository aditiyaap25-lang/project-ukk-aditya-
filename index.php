<?php
session_start();

if (isset($_SESSION['role'])) {
    header("Location: admin/dashboard.php");
} else {
    header("Location: auth/login.php");
}
exit;
