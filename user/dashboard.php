<?php
session_start();
include "../config/koneksi.php";


if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}


if ($_SESSION['role'] != 'peminjam') {
    header("Location: ../admin/dashboard.php");
    exit;
}

$nama = $_SESSION['nama'];

$total_buku = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total FROM buku")
)['total'];

$peminjaman_saya = mysqli_fetch_assoc(
    mysqli_query($conn,"SELECT COUNT(*) as total 
                        FROM peminjaman 
                        WHERE id_user='$_SESSION[id_user]'")
)['total'];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Dashboard - Bintang Pengetahuan</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#3B82F6",
                        "secondary": "#10B981",
                        "accent": "#6366F1",
                        "bg-light": "#F8FAFC",
                    },
                    fontFamily: {
                        "sans": ["Plus Jakarta Sans", "Inter", "sans-serif"]
                    }
                },
            },
        }
    </script>
</head>
<body class="bg-[#F8FAFC] min-h-screen font-sans text-slate-900">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-3">
                    <div class="bg-primary/10 p-2 rounded-xl">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <span class="text-xl font-bold tracking-tight">Digital Library</span>
                </div>
                
                <div class="flex items-center gap-4">
                    <div class="hidden md:block text-right">
                        <p class="text-sm font-bold text-slate-900"><?= $nama ?></p>
                        <p class="text-[11px] font-medium text-slate-400 uppercase tracking-wider">Anggota Peminjam</p>
                    </div>
                    <div class="h-10 w-10 bg-slate-100 rounded-full border-2 border-white shadow-sm flex items-center justify-center text-slate-500 font-bold">
                        <?= substr($nama, 0, 1) ?>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">
        
        <div class="relative overflow-hidden bg-primary rounded-[32px] p-8 md:p-12 text-white shadow-xl shadow-blue-500/20">
            <div class="relative z-10 space-y-2">
                <h2 class="text-3xl md:text-4xl font-black tracking-tight">Halo, <?= $nama ?> 👋</h2>
                <p class="text-blue-100 text-lg font-medium opacity-90">Selamat datang kembali! Temukan buku favoritmu hari ini.</p>
            </div>
            <div class="absolute -right-20 -top-20 h-64 w-64 bg-white/10 rounded-full blur-3xl"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm flex items-center gap-5">
                <div class="h-16 w-16 bg-blue-50 rounded-2xl flex items-center justify-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">Total Koleksi Buku</p>
                    <p class="text-3xl font-black text-slate-900"><?= $total_buku ?></p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-[28px] border border-slate-100 shadow-sm flex items-center gap-5">
                <div class="h-16 w-16 bg-emerald-50 rounded-2xl flex items-center justify-center text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                    </svg>
                </div>
                <div>
                    <p class="text-slate-400 text-sm font-bold uppercase tracking-wider">Pinjaman Aktif</p>
                    <p class="text-3xl font-black text-slate-900"><?= $peminjaman_saya ?></p>
                </div>
            </div>
        </div>

        <div class="space-y-4">
            <h3 class="text-xl font-bold text-slate-900 flex items-center gap-2 px-2">
                <span class="h-1.5 w-6 bg-primary rounded-full"></span>
                Akses Cepat
            </h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <a href="buku.php" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-primary/50 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 bg-blue-50 rounded-xl flex items-center justify-center text-primary group-hover:bg-primary group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <span class="font-bold text-slate-700">Cari Koleksi</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 group-hover:text-primary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <a href="baca_buku.php" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-accent/50 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 bg-indigo-50 rounded-xl flex items-center justify-center text-accent group-hover:bg-accent group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                            <span class="font-bold text-slate-700">Baca Buku</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 group-hover:text-accent transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <a href="kembali.php" class="group bg-white p-4 rounded-2xl border border-slate-100 shadow-sm hover:border-emerald-500/50 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 bg-emerald-50 rounded-xl flex items-center justify-center text-secondary group-hover:bg-secondary group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12H9m3 3v-6m5-2a9 9 0 11-10 0 9 9 0 0110 0z" />
                                </svg>
                            </div>
                            <span class="font-bold text-slate-700">Pengembalian</span>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-300 group-hover:text-secondary transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </div>
                </a>

                <a href="../auth/logout.php" class="group bg-rose-50 p-4 rounded-2xl border border-rose-100 shadow-sm hover:bg-rose-500 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="h-12 w-12 bg-white rounded-xl flex items-center justify-center text-rose-500 group-hover:bg-white/20 group-hover:text-white transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </div>
                            <span class="font-bold text-rose-600 group-hover:text-white">Keluar Sesi</span>
                        </div>
                    </div>
                </a>
            </div>
        </div>

    </main>

</body>
</html>