<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include "../config/koneksi.php";

if (!isset($_SESSION['id_user'])) {
    header("Location: ../auth/login.php");
    exit;
}

$cari = isset($_GET['cari']) ? trim($_GET['cari']) : '';
$safeCari = mysqli_real_escape_string($conn, $cari);
$hasGenre = false;
$columnCheck = mysqli_query($conn, "SHOW COLUMNS FROM buku LIKE 'genre'");
if ($columnCheck && mysqli_num_rows($columnCheck) > 0) {
    $hasGenre = true;
}
$query = "SELECT * FROM buku WHERE judul LIKE '%$safeCari%' OR penulis LIKE '%$safeCari%'";
if ($hasGenre) {
    $query .= " OR genre LIKE '%$safeCari%'";
}
$data = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Buku - Digital Library</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#3B82F6",
                        "bg-main": "#F8FAFC",
                    },
                    fontFamily: { "sans": ["Plus Jakarta Sans", "sans-serif"] }
                },
            },
        }
    </script>
    <style>
        .buku-card { transition: transform 0.3s ease, box-shadow 0.3s ease; }
        .buku-card:hover { transform: translateY(-6px); }
        .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
        .line-clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    </style>
</head>
<body class="bg-bg-main min-h-screen font-sans text-slate-900">

    <nav class="bg-white/80 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex items-center gap-4">
                    <a href="dashboard.php" class="p-2 hover:bg-slate-100 rounded-full transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <h1 class="text-xl font-black tracking-tight text-slate-800">Cari Koleksi Buku</h1>
                </div>

                <form method="GET" class="relative hidden md:block w-96">
                    <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari judul atau penulis..."
                           class="w-full h-11 pl-12 pr-4 rounded-2xl border border-slate-200 bg-slate-50 focus:ring-4 focus:ring-primary/10 focus:border-primary outline-none transition-all text-sm">
                    <svg class="h-5 w-5 absolute left-4 top-3 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" />
                    </svg>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Alert Messages -->
        <?php if (isset($_SESSION['error'])) { ?>
        <div class="mb-8 bg-red-50 border border-red-200 rounded-2xl p-4 flex gap-3 items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-red-900"><?= htmlspecialchars($_SESSION['error'], ENT_QUOTES) ?></p>
            </div>
        </div>
        <?php unset($_SESSION['error']); } ?>

        <?php if (isset($_SESSION['success'])) { ?>
        <div class="mb-8 bg-green-50 border border-green-200 rounded-2xl p-4 flex gap-3 items-start">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div>
                <p class="text-sm font-semibold text-green-900"><?= htmlspecialchars($_SESSION['success'], ENT_QUOTES) ?></p>
            </div>
        </div>
        <?php unset($_SESSION['success']); } ?>

        <form method="GET" class="md:hidden mb-8 relative">
            <input type="text" name="cari" value="<?= htmlspecialchars($cari) ?>" placeholder="Cari buku..."
                   class="w-full h-14 pl-12 pr-4 rounded-2xl border border-slate-200 bg-white shadow-sm outline-none text-sm">
            <svg class="h-5 w-5 absolute left-4 top-4.5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="2.5" />
            </svg>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php
            $found = false;
            while ($b = mysqli_fetch_assoc($data)) {
                $found = true;
            ?>
                <div class="buku-card group bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-blue-500/5 overflow-hidden">
                    <div class="relative aspect-[3/4] overflow-hidden bg-slate-100">
                        <?php if (!empty($b['cover'])) { ?>
                            <img src="../cover/<?= htmlspecialchars($b['cover'], ENT_QUOTES) ?>" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" alt="Cover <?= htmlspecialchars($b['judul'], ENT_QUOTES) ?>">
                        <?php } else { ?>
                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                </svg>
                            </div>
                        <?php } ?>
                        <div class="absolute top-4 right-4">
                            <span class="bg-white/90 backdrop-blur px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest text-primary shadow-sm">Stok: <?= htmlspecialchars($b['stok'], ENT_QUOTES) ?></span>
                        </div>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="space-y-1">
                            <p class="text-[10px] font-bold text-primary uppercase tracking-widest"><?= htmlspecialchars($b['penulis'], ENT_QUOTES) ?></p>
                            <h3 class="font-bold text-slate-800 line-clamp-1 group-hover:text-primary transition-colors"><?= htmlspecialchars($b['judul'], ENT_QUOTES) ?></h3>
                            <?php if (!empty($b['genre'])) : ?>
                                <p class="text-[11px] text-slate-500 uppercase tracking-wide mt-1"><?= htmlspecialchars($b['genre'], ENT_QUOTES) ?></p>
                            <?php endif; ?>
                        </div>

                        <button type="button"
                            onclick="openModal(this)"
                            class="w-full py-3 rounded-xl bg-slate-50 text-slate-600 font-bold text-sm hover:bg-primary hover:text-white transition-all shadow-sm flex items-center justify-center gap-2"
                            data-id="<?= htmlspecialchars($b['id_buku'], ENT_QUOTES) ?>"
                            data-judul="<?= htmlspecialchars($b['judul'], ENT_QUOTES) ?>"
                            data-penulis="<?= htmlspecialchars($b['penulis'], ENT_QUOTES) ?>"
                            data-deskripsi="<?= htmlspecialchars($b['deskripsi'], ENT_QUOTES) ?>"
                            data-cover="<?= htmlspecialchars($b['cover'], ENT_QUOTES) ?>"
                        >
                            Detail Buku
                        </button>
                    </div>
                </div>
            <?php } ?>
        </div>

        <?php if (!$found) { ?>
            <div class="mt-12 rounded-[32px] bg-white border border-slate-200 shadow-sm p-10 text-center">
                <p class="text-slate-500 text-lg mb-3">Tidak ditemukan buku dengan kata kunci tersebut.</p>
                <p class="text-sm text-slate-400">Silakan coba kata kunci lain atau kosongkan pencarian.</p>
            </div>
        <?php } ?>
    </main>

    <div id="modalPinjam" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
        <div class="bg-white w-full max-w-2xl rounded-[40px] shadow-2xl border border-slate-100 overflow-hidden relative">
            <button onclick="closeModal()" class="absolute top-6 right-6 h-10 w-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 transition-all z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <div class="flex flex-col md:flex-row h-full">
                <div class="w-full md:w-5/12 bg-slate-50 p-8 flex items-center justify-center">
                    <img id="modalCover" src="" class="w-full aspect-[3/4] object-cover rounded-2xl shadow-2xl shadow-slate-400/20" alt="Cover Buku">
                </div>
                <div class="w-full md:w-7/12 p-8 md:p-12 flex flex-col justify-between">
                    <div class="space-y-6">
                        <div>
                            <span class="inline-block px-3 py-1 rounded-full bg-blue-50 text-primary text-[10px] font-black uppercase tracking-widest mb-2">Informasi Buku</span>
                            <h2 id="modalJudul" class="text-3xl font-black text-slate-900"></h2>
                            <p id="modalPenulis" class="text-sm text-slate-500 mt-2"></p>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold uppercase tracking-wider text-slate-400 mb-3">Deskripsi</h3>
                            <p id="modalDeskripsi" class="text-slate-600 text-sm leading-relaxed"></p>
                        </div>
                    </div>
                    <div class="mt-8">
                        <a id="modalAction" href="#" class="inline-flex items-center justify-center w-full rounded-2xl bg-primary text-white py-3 font-semibold hover:bg-blue-600 transition-all">Pinjam Buku</a>
                        <p class="text-xs text-slate-400 mt-3">Silakan lanjutkan ke halaman peminjaman jika buku tersedia.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(button) {
            var modal = document.getElementById('modalPinjam');
            document.getElementById('modalJudul').textContent = button.dataset.judul || '-';
            document.getElementById('modalPenulis').textContent = button.dataset.penulis ? 'Oleh ' + button.dataset.penulis : '';
            document.getElementById('modalDeskripsi').textContent = button.dataset.deskripsi || 'Deskripsi tidak tersedia.';
            var cover = button.dataset.cover ? '../cover/' + button.dataset.cover : 'https://via.placeholder.com/400x550?text=No+Cover';
            document.getElementById('modalCover').src = cover;
            document.getElementById('modalAction').href = 'pinjam.php?id_buku=' + encodeURIComponent(button.dataset.id);
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeModal() {
            var modal = document.getElementById('modalPinjam');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        window.addEventListener('click', function(event) {
            var modal = document.getElementById('modalPinjam');
            if (!modal.classList.contains('hidden') && event.target === modal) {
                closeModal();
            }
        });
    </script>
</body>
</html>
