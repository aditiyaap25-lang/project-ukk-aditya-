<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$id_user = (int) $_SESSION['id_user'];
$query = "SELECT DISTINCT b.* FROM buku b
          JOIN peminjaman p ON b.id_buku = p.id_buku
          WHERE p.id_user='$id_user'
            AND p.status='dipinjam'
            AND b.file_buku IS NOT NULL
            AND b.file_buku != ''";
if ($cari !== '') {
    $safeCari = mysqli_real_escape_string($conn, $cari);
    $genreCondition = '';
    $columnCheck = mysqli_query($conn, "SHOW COLUMNS FROM buku LIKE 'genre'");
    if ($columnCheck && mysqli_num_rows($columnCheck) > 0) {
        $genreCondition = " OR b.genre LIKE '%$safeCari%'";
    }
    $query .= " AND (b.judul LIKE '%$safeCari%' OR b.penulis LIKE '%$safeCari%'$genreCondition)";
}
$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baca Buku - Digital Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#3B82F6',
                        'bg-soft': '#F8FAFC'
                    },
                    fontFamily: { 'sans': ['Plus Jakarta Sans', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        .book-card { transition: all 0.28s ease; }
        .book-card:hover { transform: translateY(-6px); }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-bg-soft font-sans text-slate-900 min-h-screen">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="dashboard.php" class="h-10 w-10 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <h1 class="text-xl font-extrabold tracking-tight">Perpustakaan Digital</h1>
            </div>

            <form method="GET" class="relative hidden md:block w-full max-w-md">
                <input type="text" name="cari" placeholder="Cari judul buku atau penulis..." value="<?= htmlspecialchars($cari) ?>"
                    class="w-full h-11 pl-11 pr-4 rounded-2xl border border-slate-200 bg-slate-50/50 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none text-sm transition-all">
                <svg class="h-5 w-5 absolute left-3.5 top-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" />
                </svg>
            </form>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-6 py-10">
        <div class="mb-10">
            <h2 class="text-3xl font-black text-slate-900 mb-2">E-Book yang Bisa Dibaca</h2>
            <p class="text-slate-500">Hanya buku yang sudah kamu pinjam yang bisa dibaca di sini.</p>
        </div>

        <form method="GET" class="mb-8 md:hidden relative">
            <input type="text" name="cari" placeholder="Cari judul atau penulis..." value="<?= htmlspecialchars($cari) ?>"
                class="w-full h-12 pl-11 pr-4 rounded-2xl border border-slate-200 bg-white shadow-sm outline-none text-sm">
            <svg class="h-5 w-5 absolute left-3.5 top-3.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" />
            </svg>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $bookCount = mysqli_num_rows($data);
            if ($bookCount === 0) {
            ?>
                <div class="col-span-full rounded-[32px] bg-white border border-slate-200 shadow-sm p-10 text-center">
                    <p class="text-slate-500 text-lg mb-3">Belum ada buku yang sedang kamu pinjam.</p>
                    <?php if ($cari !== '') { ?>
                        <p class="text-sm text-slate-400 mb-4">Tidak ditemukan buku yang dipinjam dengan kata kunci tersebut.</p>
                        <a href="baca_buku.php" class="inline-flex items-center gap-2 text-primary font-semibold">Tampilkan semua buku yang dipinjam</a>
                    <?php } else { ?>
                        <p class="text-sm text-slate-400 mb-4">Pastikan kamu sudah meminjam buku terlebih dahulu lewat halaman pinjam.</p>
                        <a href="pinjam.php" class="inline-flex items-center justify-center px-5 py-3 rounded-2xl bg-primary text-white font-semibold shadow-sm hover:bg-blue-600 transition-all">
                            Pinjam Buku Sekarang
                        </a>
                    <?php } ?>
                </div>
            <?php
            } else {
                while($b = mysqli_fetch_assoc($data)) {
            ?>
                <div class="book-card group bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-blue-500/10 overflow-hidden">
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                        <?php if (!empty($b['cover'])) { ?>
                            <img src="../cover/<?= htmlspecialchars($b['cover'], ENT_QUOTES) ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" alt="Cover <?= htmlspecialchars($b['judul'], ENT_QUOTES) ?>">
                        <?php } else { ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        <?php } ?>
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur rounded-full text-[10px] font-black uppercase tracking-widest text-primary shadow-sm">PDF E-Book</span>
                        </div>
                    </div>

                    <div class="p-6">
                        <p class="text-[11px] font-bold text-primary uppercase tracking-widest mb-1 italic"><?= htmlspecialchars($b['penulis'], ENT_QUOTES) ?></p>
                        <?php if (!empty($b['genre'])) : ?>
                            <p class="text-[11px] text-slate-500 uppercase tracking-wide mb-1"><?= htmlspecialchars($b['genre'], ENT_QUOTES) ?></p>
                        <?php endif; ?>
                        <h3 class="font-bold text-slate-800 text-lg leading-snug line-clamp-1 mb-2"><?= htmlspecialchars($b['judul'], ENT_QUOTES) ?></h3>
                        <p class="text-xs text-slate-500 mb-6 line-clamp-2 leading-relaxed">
                            <?= !empty($b['deskripsi']) ? htmlspecialchars($b['deskripsi'], ENT_QUOTES) : 'Deskripsi buku tidak tersedia.' ?>
                        </p>
                        <a href="../ebook/<?= htmlspecialchars($b['file_buku'], ENT_QUOTES) ?>" target="_blank" class="block text-center bg-primary hover:bg-blue-600 text-white py-3 rounded-2xl font-semibold transition-all">
                            Baca Sekarang
                        </a>
                    </div>
                </div>
            <?php
                }
            }
            ?>
        </div>
    </main>
</body>
</html>
