<?php
include "../config/koneksi.php";

$error = '';

function ensureColumn($conn, $column, $definition)
{
    $result = mysqli_query($conn, "SHOW COLUMNS FROM buku LIKE '$column'");
    if (!$result || mysqli_num_rows($result) === 0) {
        mysqli_query($conn, "ALTER TABLE buku ADD COLUMN $column $definition");
    }
}

$coverDir = "../cover/";
$ebookDir = "../ebook/";
if (!is_dir($coverDir)) {
    mkdir($coverDir, 0755, true);
}
if (!is_dir($ebookDir)) {
    mkdir($ebookDir, 0755, true);
}

ensureColumn($conn, 'deskripsi', 'TEXT NULL');
ensureColumn($conn, 'genre', 'VARCHAR(100) NULL');
ensureColumn($conn, 'cover', 'VARCHAR(255) NULL');
ensureColumn($conn, 'file_buku', 'VARCHAR(255) NULL');

if (isset($_POST['simpan'])) {
    $judul     = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $penulis   = mysqli_real_escape_string($conn, trim($_POST['penulis']));
    $genre     = mysqli_real_escape_string($conn, trim($_POST['genre'] ?? ''));
    $tahun     = mysqli_real_escape_string($conn, trim($_POST['tahun']));
    $stok      = mysqli_real_escape_string($conn, trim($_POST['stok']));
    $deskripsi = mysqli_real_escape_string($conn, trim($_POST['deskripsi'] ?? ''));

    $coverName = null;
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
        $coverFile = $_FILES['cover'];
        $coverExt = strtolower(pathinfo($coverFile['name'], PATHINFO_EXTENSION));
        if (!in_array($coverExt, ['jpg', 'jpeg', 'png'])) {
            $error = 'Format cover tidak valid. Gunakan JPG, JPEG, atau PNG.';
        } else {
            $coverName = time() . '_cover_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($coverFile['name']));
            move_uploaded_file($coverFile['tmp_name'], $coverDir . $coverName);
        }
    }

    $fileBukuName = null;
    if (empty($error) && isset($_FILES['file_buku']) && $_FILES['file_buku']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fileBuku = $_FILES['file_buku'];
        $bukuExt = strtolower(pathinfo($fileBuku['name'], PATHINFO_EXTENSION));
        if ($bukuExt !== 'pdf') {
            $error = 'Format file buku harus PDF.';
        } else {
            $fileBukuName = time() . '_ebook_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($fileBuku['name']));
            move_uploaded_file($fileBuku['tmp_name'], $ebookDir . $fileBukuName);
        }
    }

    if (empty($error)) {
        $coverSql    = $coverName ? "'$coverName'" : 'NULL';
        $fileBukuSql = $fileBukuName ? "'$fileBukuName'" : 'NULL';

        mysqli_query($conn, "INSERT INTO buku (judul, penulis, genre, tahun, stok, deskripsi, cover, file_buku)
                             VALUES ('$judul','$penulis','$genre','$tahun','$stok','$deskripsi',$coverSql,$fileBukuSql)");
        header("Location: buku.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Tambah Buku - Library Admin</title>
    
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                      
                        "primary": "#3B82F6", 
                        "primary-hover": "#2563EB",
                        "bg-main": "#E5E7EB", 
                    },
                    fontFamily: {
                        
                        "sans": ["Inter", "ui-sans-serif", "system-ui", "-apple-system", "sans-serif"]
                    }
                },
            },
        }
    </script>

    <style type="text/tailwindcss">
        
        body {
            background-color: #E5E7EB;
        }
    </style>
</head>
<body class="min-h-screen text-slate-900 font-sans p-3 md:p-6">

    <main class="max-w-7xl mx-auto space-y-6">
        
        <header class="flex items-center justify-between gap-4 p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-3">
                <a href="buku.php" class="h-10 w-10 border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-primary transition-colors" title="Kembali ke Kelola Buku">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-slate-950">Tambah Koleksi Buku</h1>
                    <p class="text-sm text-slate-500 mt-0.5">Lengkapi formulir di bawah untuk menambahkan buku baru.</p>
                </div>
            </div>
            <div class="flex items-center gap-1.5 text-sm font-medium text-slate-500">
                <span>Admin</span>
                <span class="text-slate-300">/</span>
                <span class="text-primary font-semibold">Tambah Buku</span>
            </div>
        </header>

        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-100">
            
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-7">
                <?php if (!empty($error)) : ?>
                    <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-4 py-3">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-7">
                    
                    <div class="space-y-2">
                        <label for="judul" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                            Judul Buku
                        </label>
                        <input type="text" id="judul" name="judul" required placeholder="Masukkan judul lengkap buku..."
                               value="<?= htmlspecialchars($_POST['judul'] ?? '') ?>"
                               class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>

                    <div class="space-y-2">
                        <label for="penulis" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Nama Penulis / Pengarang
                        </label>
                        <input type="text" id="penulis" name="penulis" required placeholder="Contoh: Andrea Hirata..."
                               value="<?= htmlspecialchars($_POST['penulis'] ?? '') ?>"
                               class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>

                    <div class="space-y-2">
                        <label for="genre" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Genre Buku
                        </label>
                        <input type="text" id="genre" name="genre" placeholder="Contoh: Fiksi, Pendidikan, Biografi..."
                               list="genre-list"
                               value="<?= htmlspecialchars($_POST['genre'] ?? '') ?>"
                               class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                        <datalist id="genre-list">
                            <option value="Fiksi">
                            <option value="Non-Fiksi">
                            <option value="Pendidikan">
                            <option value="Sejarah">
                            <option value="Biografi">
                            <option value="Fantasi">
                            <option value="Sains">
                            <option value="Teknologi">
                            <option value="Anak-anak">
                            <option value="Agama">
                            <option value="Motivasi">
                        </datalist>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-7">
                    
                    <div class="space-y-2">
                        <label for="tahun" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            Tahun Terbit
                        </label>
                        <input type="number" id="tahun" name="tahun" required placeholder="YYYY" min="1900" max="2099"
                               value="<?= htmlspecialchars($_POST['tahun'] ?? '') ?>"
                               class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>

                    <div class="space-y-2">
                        <label for="stok" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                            </svg>
                            Jumlah Stok Buku
                        </label>
                        <input type="number" id="stok" name="stok" required placeholder="0" min="0"
                               value="<?= htmlspecialchars($_POST['stok'] ?? '') ?>"
                               class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                    </div>

                </div>

                <div class="grid grid-cols-1 gap-y-7">
                    <div class="space-y-2">
                        <label for="deskripsi" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            Deskripsi Singkat Buku
                        </label>
                        <textarea id="deskripsi" name="deskripsi" rows="4" placeholder="Tulis ringkasan singkat buku..." class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="cover" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                Upload Cover (JPG/PNG)
                            </label>
                            <input type="file" id="cover" name="cover" accept="image/png, image/jpeg" class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-slate-100 file:text-slate-700" onchange="previewCover(this)" />
                            <div id="coverPreview" class="mt-3 hidden">
                                <img id="coverImg" src="" alt="Cover Preview" class="w-32 h-48 object-cover rounded-lg shadow border border-slate-200">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label for="file_buku" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                                Upload PDF Buku
                            </label>
                            <input type="file" id="file_buku" name="file_buku" accept="application/pdf" class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-slate-100 file:text-slate-700" />
                        </div>
                    </div>
                </div>

                <div class="pt-6 mt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    <a href="buku.php" class="h-12 px-6 rounded-xl flex items-center justify-center font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
                        Batal
                    </a>
                    
                    <button type="submit" name="simpan" class="h-12 px-8 rounded-xl flex items-center justify-center gap-2 font-semibold text-white bg-primary hover:bg-primary-hover transition-colors shadow-sm shadow-blue-500/20 active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        Simpan Buku Baru
                    </button>
                </div>

            </form>
        </div>

    </main>

    <script>
        function previewCover(input) {
            const file = input.files[0];
            const preview = document.getElementById('coverPreview');
            const img = document.getElementById('coverImg');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                preview.classList.add('hidden');
            }
        }
    </script>

</body>
</html>