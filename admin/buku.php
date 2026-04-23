<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

function ensureColumn($conn, $column, $definition)
{
    $result = mysqli_query($conn, "SHOW COLUMNS FROM buku LIKE '$column'");
    if (!$result || mysqli_num_rows($result) === 0) {
        mysqli_query($conn, "ALTER TABLE buku ADD COLUMN $column $definition");
    }
}

ensureColumn($conn, 'deskripsi', 'TEXT NULL');
ensureColumn($conn, 'genre', 'VARCHAR(100) NULL');
ensureColumn($conn, 'cover', 'VARCHAR(255) NULL');
ensureColumn($conn, 'file_buku', 'VARCHAR(255) NULL');

$data = mysqli_query($conn, "SELECT * FROM buku");
$total = mysqli_num_rows($data);

$keyword = isset($_GET['keyword']) ? mysqli_real_escape_string($conn, trim($_GET['keyword'])) : '';

$query = "
    SELECT * FROM buku
    WHERE 
        judul LIKE '%$keyword%' OR
        penulis LIKE '%$keyword%' OR
        genre LIKE '%$keyword%' OR
        stok LIKE '%$keyword%' OR
        deskripsi LIKE '%$keyword%'
";

$data = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Manajemen Buku CRUD Admin</title>


<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>

<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>

<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                        "success": "#10b981",
                        "danger": "#ef4444"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            line-height: 1;
            text-transform: none;
            letter-spacing: normal;
            word-wrap: normal;
            white-space: nowrap;
            direction: ltr;
        }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>

<body class="bg-background-light dark:bg-background-dark font-display text-[#111318] dark:text-gray-100 min-h-screen">
<!-- Top Navigation Bar -->
<div class="sticky top-0 z-40 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">
<div class="flex items-center p-4 justify-between max-w-7xl mx-auto">
<div class="flex items-center gap-3">
  <a href="dashboard.php" class="flex items-center gap-4 p-3 rounded-lg text-gray-700 dark:text-gray-300">
<span class="material-symbols-outlined">keyboard_return</span>
</a>
<h2 class="text-[#111318] dark:text-white text-lg font-bold leading-tight tracking-tight">Manajemen Buku</h2>
</div>
<div class="flex items-center gap-2">
<button class="flex items-center justify-center size-10 rounded-full bg-gray-100 dark:bg-gray-800 text-[#111318] dark:text-white">
<span class="material-symbols-outlined">person</span>
</button>
</div>
</div>
</div>


<div class="p-4 space-y-4 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800">

<a href="buku_tambah.php" class="flex items-center justify-center gap-2 bg-success text-white py-3 px-4 rounded-xl font-bold shadow-sm hover:bg-emerald-600 transition-colors">
<span class="material-symbols-outlined">add</span>
<span>Tambah Buku Baru</span>
</a>
<form method="GET" class="mb-4 flex gap-2">
    <input 
        type="text" 
        name="keyword"
        placeholder="Cari judul / penulis / genre..."
        value="<?= $keyword ?>"
        class="border px-3 py-2 rounded w-full text-black"
    >
    <button class="bg-blue-500 px-4 py-2 rounded">
        Cari
    </button>
</form>
<p class="text-sm text-gray-500"><?= $total ?> total buku</p>


<?php while ($b = mysqli_fetch_assoc($data)) { ?>
<div class="bg-white p-4 rounded-xl shadow flex gap-4 items-center">

  <!-- Cover Preview -->
  <div class="w-24 h-32 flex-shrink-0 bg-slate-100 rounded-lg overflow-hidden border border-slate-200 flex items-center justify-center">
    <?php if (!empty($b['cover']) && file_exists("../cover/" . htmlspecialchars($b['cover']))) { ?>
      <img src="../cover/<?= htmlspecialchars($b['cover'], ENT_QUOTES) ?>" alt="Cover" class="w-full h-full object-cover">
    <?php } else { ?>
      <div class="text-center text-slate-300 p-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="text-xs">No Cover</span>
      </div>
    <?php } ?>
  </div>

  <div class="flex-1">
    <h2 class="font-bold"><?= htmlspecialchars($b['judul']) ?></h2>
    <p class="text-sm text-gray-500"><?= htmlspecialchars($b['penulis']) ?></p>
    <?php if (!empty($b['genre'])) : ?>
        <p class="text-xs text-slate-600 mt-1">Genre: <?= htmlspecialchars($b['genre']) ?></p>
    <?php endif; ?>
    <p class="text-xs mt-1">Stok: <?= htmlspecialchars($b['stok']) ?></p>
    <?php if (!empty($b['file_buku'])) : ?>
        <p class="text-xs mt-1 text-blue-600">
            <a href="../ebook/<?= htmlspecialchars($b['file_buku']) ?>" target="_blank" class="hover:underline">Buka PDF</a>
        </p>
    <?php else : ?>
        <p class="text-xs mt-1 text-slate-500">PDF belum diupload</p>
    <?php endif; ?>
  </div>

  
  <div class="flex gap-2">
    <a href="buku_edit.php?id=<?= $b['id_buku'] ?>" class="flex items-center justify-center p-2 text-primary bg-primary/10 rounded-lg hover:bg-primary/20 transition-colors">
<span class="material-symbols-outlined text-xl">edit</span>
</a>

    <form action="buku_hapus.php" method="POST" onsubmit="return confirm('Hapus buku ini?')" class="inline">
      <input type="hidden" name="id_buku" value="<?= $b['id_buku'] ?>">
      <button type="submit" class="flex items-center justify-center p-2 text-danger bg-danger/10 rounded-lg hover:bg-danger/20 transition-colors">
<span class="material-symbols-outlined text-xl">delete</span>
</button>
    </form>
  </div>

</div>
<?php } ?>

</div>
</body>
</html>
