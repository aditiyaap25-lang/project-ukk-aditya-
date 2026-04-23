<?php
session_start();
include "../config/koneksi.php"; 


if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}


if ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'petugas') {
    header("Location: ../user/dashboard.php");
    exit;
}
$total_buku = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM buku")
)['total'];

$total_peminjaman = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman")
)['total'];

$peminjaman_aktif = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM peminjaman WHERE status='dipinjam'")
)['total'];

$total_user = mysqli_fetch_assoc(
    mysqli_query($conn, "SELECT COUNT(*) AS total FROM users")
)['total'];
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Admin Dashboard - Library</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#3B82F6",
                        "bg-soft": "#F1F5F9",
                        "card-border": "rgba(226, 232, 240, 0.8)",
                    },
                    fontFamily: {
                        "sans": ["Plus Jakarta Sans", "Inter", "system-ui", "sans-serif"]
                    }
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        body {
            @apply bg-[#F8FAFC];
        }
        .stat-card {
            @apply bg-white border border-card-border rounded-3xl p-6 shadow-sm hover:shadow-md transition-all duration-300;
        }
        .menu-card {
            @apply bg-white border border-card-border rounded-2xl p-4 flex items-center gap-4 hover:border-primary/30 hover:bg-blue-50/30 transition-all active:scale-[0.98];
        }
    </style>
</head>
<body class="min-h-screen text-slate-900">

    <div class="max-w-7xl mx-auto p-4 md:p-8 space-y-8">
        
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">Library Admin</h1>
                <p class="text-slate-500 font-medium">Selamat datang kembali di Staff Portal.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-bold text-slate-900"><?php echo $_SESSION['nama'] ?? 'Administrator'; ?></p>
                    <p class="text-xs text-emerald-500 font-medium flex items-center justify-end gap-1">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Online
                    </p>
                </div>
                <a href="../auth/logout.php" class="flex items-center gap-2 bg-white border border-red-100 text-red-500 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-50 hover:border-red-200 transition-all active:scale-95 shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Logout
                </a>
            </div>
        </header>

        <section class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="stat-card group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total Books</p>
                        <h3 class="text-4xl font-black text-slate-900 mt-1"><?php echo $total_buku; ?></h3>
                    </div>
                    <div class="h-12 w-12 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs font-bold text-blue-500 bg-blue-50 px-2 py-1 rounded-md">Koleksi Aktif</span>
                </div>
            </div>

            <div class="stat-card group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Active Loans</p>
                        <h3 class="text-4xl font-black text-slate-900 mt-1"><?php echo $peminjaman_aktif; ?></h3>
                    </div>
                    <div class="h-12 w-12 bg-orange-50 rounded-2xl flex items-center justify-center text-orange-500 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs font-bold text-orange-500 bg-orange-50 px-2 py-1 rounded-md">Sedang Dipinjam</span>
                </div>
            </div>

            <div class="stat-card group">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">Total Users</p>
                        <h3 class="text-4xl font-black text-slate-900 mt-1"><?php echo $total_user; ?></h3>
                    </div>
                    <div class="h-12 w-12 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-xs font-bold text-emerald-500 bg-emerald-50 px-2 py-1 rounded-md">Anggota Terdaftar</span>
                </div>
            </div>
        </section>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <div class="lg:col-span-1 space-y-4">
                <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] px-2">Main Menu</h4>
                <nav class="space-y-2">
                    <a href="buku.php" class="menu-card">
                        <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1m-6 10a2 2 0 002 2h5a2 2 0 002-2v-5a2 2 0 00-2-2h-5a2 2 0 00-2 2v5z" /></svg>
                        </div>
                        <span class="font-bold text-slate-700">Kelola Buku</span>
                    </a>
                    <a href="peminjaman.php" class="menu-card">
                        <div class="h-10 w-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" /></svg>
                        </div>
                        <span class="font-bold text-slate-700">Peminjaman</span>
                    </a>
                    <a href="user.php" class="menu-card">
                        <div class="h-10 w-10 bg-violet-50 text-violet-600 rounded-xl flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                        </div>
                        <span class="font-bold text-slate-700">Data User</span>
                    </a>
                </nav>
            </div>

            <div class="lg:col-span-3">
                <div class="bg-gradient-to-br from-primary to-blue-700 rounded-3xl p-8 text-white relative overflow-hidden shadow-xl shadow-blue-500/20">
                    <div class="relative z-10 space-y-4 max-w-lg">
                        <h2 class="text-3xl font-black italic tracking-tight uppercase">Ready to Manage?</h2>
                        <p class="text-blue-100 font-medium">Sistem Perpustakaan Bintang Pengetahuan siap membantu Anda mengelola data buku dan transaksi peminjaman dengan lebih efisien.</p>
                        <div class="pt-2">
                            <a href="buku_tambah.php" class="inline-flex items-center gap-2 bg-white text-primary px-6 py-3 rounded-2xl font-bold hover:bg-blue-50 transition-all active:scale-95 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                                Tambah Koleksi Baru
                            </a>
                        </div>
                    </div>
                    <div class="absolute -right-20 -bottom-20 h-64 w-64 bg-white/10 rounded-full blur-3xl"></div>
                    <div class="absolute right-10 top-10 h-32 w-32 bg-blue-400/20 rounded-full blur-2xl"></div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>