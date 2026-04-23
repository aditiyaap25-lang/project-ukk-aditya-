<?php
$halaman = basename($_SERVER['PHP_SELF']);
?>

<div class="d-flex">
    <div class="sidebar">
        <h5 class="text-center py-3 text-white">📚 Perpustakaan</h5>

        <a href="dashboard.php" class="<?= $halaman=='dashboard.php'?'active':'' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>

        <a href="buku.php" class="<?= $halaman=='buku.php'?'active':'' ?>">
            <i class="bi bi-book"></i> Buku
        </a>

        <a href="peminjaman.php" class="<?= $halaman=='peminjaman.php'?'active':'' ?>">
            <i class="bi bi-arrow-repeat"></i> Peminjaman
        </a>

        <?php if ($_SESSION['role']=='admin') { ?>
        <a href="user.php" class="<?= $halaman=='user.php'?'active':'' ?>">
            <i class="bi bi-people"></i> User
        </a>
        <?php } ?>

        <a href="../auth/logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
        </a>
    </div>

    <div class="flex-fill p-4">
