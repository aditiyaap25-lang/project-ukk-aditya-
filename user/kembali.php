<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];

$query = mysqli_query($conn,"
    SELECT p.id_peminjaman,
           p.tgl_pinjam,
           b.judul
    FROM peminjaman p
    JOIN buku b ON p.id_buku=b.id_buku
    WHERE p.id_user='$id_user'
    AND p.status='dipinjam'
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Pengembalian Buku - Digital Library</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet"/>
<script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#3b82f6",
                        "background-light": "#f8fafc",
                        "background-dark": "#0f172a",
                        "surface-dark": "#1e293b",
                        "accent-green": "#10b981",
                    },
                    fontFamily: {
                        display: ["Inter", "sans-serif"],
                    },
                    borderRadius: {
                        DEFAULT: "0.5rem",
                    },
                },
            },
        };
    </script>
<style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }.zebra-row:nth-child(even) {
            background-color: rgba(30, 41, 59, 0.5);
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>

<body class="bg-white text-slate-900 min-h-screen flex flex-col">
<main class="flex-grow container mx-auto px-4 py-8 max-w-5xl">
<div class="mb-8">
<h1 class="text-3xl font-bold tracking-tight mb-2">Pengembalian Buku</h1>
<div class="h-1 w-20 bg-primary rounded-full"></div>
</div>
<div class="overflow-hidden bg-white shadow-xl border border-slate-200 rounded-xl">
<div class="overflow-x-auto">
<table class="w-full text-left border-collapse">
<thead>
<tr class="bg-slate-50 border-b border-slate-200">
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 w-16">No</th>
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Judul Buku</th>
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500">Tgl Pinjam</th>
<th class="px-6 py-4 text-xs font-semibold uppercase tracking-wider text-slate-500 text-center">Aksi</th>
</tr>
</thead>
<tbody>
<?php
$no=1;
while($d=mysqli_fetch_assoc($query)){
?>
<tr>
    <td class="px-6 py-4 text-sm font-medium"><?= $no++ ?></td>
    <td class="px-6 py-4 text-sm text-slate-500"><?= $d['judul'] ?></td>
    <td class="px-6 py-4 text-sm text-slate-500"><?= $d['tgl_pinjam'] ?></td>
    <td class="px-6 py-4 text-center">
        <a href="kembali_proses.php?id=<?= $d['id_peminjaman'] ?>" class="inline-flex items-center px-4 py-1.5 bg-accent-green hover:bg-emerald-600 text-white text-xs font-semibold rounded-md transition-all active:scale-95 shadow-sm">
            <span class="material-icons-round text-sm mr-1.5">keyboard_return</span>
            Kembalikan
        </a>
    </td>
</tr>
<?php }
if (mysqli_num_rows($query) === 0) { ?>
<tr>
    <td class="px-6 py-6 text-center text-slate-500" colspan="4">Belum ada buku yang sedang dipinjam.</td>
</tr>
<?php } ?>
</tbody>
</table>
</div>
</div>

<div class="mt-6">
    <a class="inline-flex items-center px-6 py-2.5 bg-slate-700 hover:bg-slate-800 text-white font-medium rounded-lg transition-colors group" href="dashboard.php">
        <span class="material-icons-round mr-2 transition-transform group-hover:-translate-x-1">arrow_back</span>
        Kembali ke Dashboard
    </a>
</div>
</main>
</body>
</html>
