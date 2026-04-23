<?php
include "../config/koneksi.php";

if (!isset($_GET['id'])) {
    header("Location: buku.php");
    exit;
}

$id = $_GET['id'];

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

$data = mysqli_query($conn, "SELECT * FROM buku WHERE id_buku='$id'");
$row = mysqli_fetch_assoc($data);

$error = '';

if (!$row) {
    header("Location: buku.php");
    exit;
}

if (isset($_POST['update'])) {
    $judul     = mysqli_real_escape_string($conn, trim($_POST['judul']));
    $penulis   = mysqli_real_escape_string($conn, trim($_POST['penulis']));
    $genre     = mysqli_real_escape_string($conn, trim($_POST['genre'] ?? ''));
    $tahun     = mysqli_real_escape_string($conn, trim($_POST['tahun']));
    $stok      = mysqli_real_escape_string($conn, trim($_POST['stok']));
    $deskripsi = mysqli_real_escape_string($conn, trim($_POST['deskripsi'] ?? ''));

    $coverName = !empty($row['cover']) ? $row['cover'] : null;
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] !== UPLOAD_ERR_NO_FILE) {
        $coverFile = $_FILES['cover'];
        $coverExt = strtolower(pathinfo($coverFile['name'], PATHINFO_EXTENSION));
        if (!in_array($coverExt, ['jpg', 'jpeg', 'png'])) {
            $error = 'Format cover tidak valid. Gunakan JPG, JPEG, atau PNG.';
        } else {
            $newCoverName = time() . '_cover_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($coverFile['name']));
            move_uploaded_file($coverFile['tmp_name'], $coverDir . $newCoverName);
            if (!empty($row['cover']) && file_exists($coverDir . $row['cover'])) {
                unlink($coverDir . $row['cover']);
            }
            $coverName = $newCoverName;
        }
    }

    $fileBukuName = !empty($row['file_buku']) ? $row['file_buku'] : null;
    if (empty($error) && isset($_FILES['file_buku']) && $_FILES['file_buku']['error'] !== UPLOAD_ERR_NO_FILE) {
        $fileBuku = $_FILES['file_buku'];
        $bukuExt = strtolower(pathinfo($fileBuku['name'], PATHINFO_EXTENSION));
        if ($bukuExt !== 'pdf') {
            $error = 'Format file buku harus PDF.';
        } else {
            $newFileBukuName = time() . '_ebook_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($fileBuku['name']));
            move_uploaded_file($fileBuku['tmp_name'], $ebookDir . $newFileBukuName);
            if (!empty($row['file_buku']) && file_exists($ebookDir . $row['file_buku'])) {
                unlink($ebookDir . $row['file_buku']);
            }
            $fileBukuName = $newFileBukuName;
        }
    }

    if (empty($error)) {
        mysqli_query($conn, "UPDATE buku SET
            judul='$judul',
            penulis='$penulis',
            genre='$genre',
            tahun='$tahun',
            stok='$stok',
            deskripsi='$deskripsi',
            cover='" . mysqli_real_escape_string($conn, $coverName) . "',
            file_buku='" . mysqli_real_escape_string($conn, $fileBukuName) . "'
            WHERE id_buku='$id'
        ");
        header("Location: buku.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Buku - Library Admin</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
</head>
<body class="min-h-screen text-slate-900 font-sans p-3 md:p-6 bg-slate-100">
    <main class="max-w-4xl mx-auto space-y-6">
        <header class="flex items-center justify-between gap-4 p-4 bg-white rounded-2xl shadow-sm border border-slate-100">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-slate-950">Edit Buku</h1>
                <p class="text-sm text-slate-500 mt-0.5">Perbarui informasi buku dan file PDF jika diperlukan.</p>
            </div>
            <a href="buku.php" class="text-sm font-semibold text-primary hover:text-primary-hover">Kembali</a>
        </header>

        <div class="bg-white p-6 md:p-8 rounded-3xl shadow-sm border border-slate-100">
            <?php if (!empty($error)) : ?>
                <div class="rounded-2xl bg-red-50 border border-red-200 text-red-700 px-4 py-3 mb-6">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data" class="space-y-7">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="judul" class="text-sm font-semibold text-slate-700">Judul Buku</label>
                        <input type="text" id="judul" name="judul" required value="<?= htmlspecialchars($row['judul']) ?>" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary"/>
                    </div>
                    <div class="space-y-2">
                        <label for="penulis" class="text-sm font-semibold text-slate-700">Nama Penulis</label>
                        <input type="text" id="penulis" name="penulis" required value="<?= htmlspecialchars($row['penulis']) ?>" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary"/>
                    </div>
                    <div class="space-y-2">
                        <label for="genre" class="text-sm font-semibold text-slate-700">Genre Buku</label>
                        <input type="text" id="genre" name="genre" value="<?= htmlspecialchars($row['genre']) ?>" placeholder="Contoh: Fiksi, Pendidikan, Biografi"
                               list="genre-list"
                               class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary"/>
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
                    <div class="space-y-2">
                        <label for="tahun" class="text-sm font-semibold text-slate-700">Tahun Terbit</label>
                        <input type="number" id="tahun" name="tahun" required value="<?= htmlspecialchars($row['tahun']) ?>" min="1900" max="2099" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary"/>
                    </div>
                    <div class="space-y-2">
                        <label for="stok" class="text-sm font-semibold text-slate-700">Jumlah Stok</label>
                        <input type="number" id="stok" name="stok" required value="<?= htmlspecialchars($row['stok']) ?>" min="0" class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="deskripsi" class="text-sm font-semibold text-slate-700">Deskripsi Singkat</label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary"><?= htmlspecialchars($row['deskripsi']) ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="cover" class="text-sm font-semibold text-slate-700">Cover Baru (opsional)</label>
                        <input type="file" id="cover" name="cover" accept="image/png, image/jpeg" class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-slate-100 file:text-slate-700" onchange="previewCover(this)" />
                        <?php if (!empty($row['cover']) && file_exists("../cover/" . htmlspecialchars($row['cover']))) : ?>
                            <div class="mt-4 space-y-2">
                                <p class="text-xs text-slate-600 font-semibold">Cover Saat Ini:</p>
                                <div id="currentCoverPreview" class="w-32 h-48 bg-slate-100 rounded-lg overflow-hidden border border-slate-200">
                                    <img src="../cover/<?= htmlspecialchars($row['cover'], ENT_QUOTES) ?>" alt="Cover Buku" class="w-full h-full object-cover">
                                </div>
                            </div>
                        <?php elseif (!empty($row['cover'])) : ?>
                            <p class="text-xs text-slate-500 mt-2">Cover saat ini: <a href="../cover/<?= htmlspecialchars($row['cover']) ?>" target="_blank" class="text-blue-600 hover:underline"><?= htmlspecialchars($row['cover']) ?></a></p>
                        <?php endif; ?>
                        <div id="coverPreviewNew" class="mt-4 hidden space-y-2">
                            <p class="text-xs text-slate-600 font-semibold">Preview Cover Baru:</p>
                            <div class="w-32 h-48 bg-slate-100 rounded-lg overflow-hidden border border-blue-400">
                                <img id="coverImgNew" src="" alt="Cover Preview" class="w-full h-full object-cover">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="file_buku" class="text-sm font-semibold text-slate-700">PDF Buku Baru (opsional)</label>
                        <input type="file" id="file_buku" name="file_buku" accept="application/pdf" class="w-full rounded-xl border border-slate-200 bg-white text-slate-900 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-slate-100 file:text-slate-700" />
                        <?php if (!empty($row['file_buku'])) : ?>
                            <p class="text-xs text-slate-500 mt-2">File saat ini: <a href="../ebook/<?= htmlspecialchars($row['file_buku']) ?>" target="_blank" class="text-blue-600 hover:underline"><?= htmlspecialchars($row['file_buku']) ?></a></p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="pt-6 mt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    <a href="buku.php" class="h-12 px-6 rounded-xl flex items-center justify-center font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">Batal</a>
                    <button type="submit" name="update" class="h-12 px-8 rounded-xl flex items-center justify-center gap-2 font-semibold text-white bg-blue-600 hover:bg-blue-700 transition-colors shadow-sm shadow-blue-500/20 active:scale-[0.98]">Update Buku</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function previewCover(input) {
            const file = input.files[0];
            const preview = document.getElementById('coverPreviewNew');
            const img = document.getElementById('coverImgNew');
            
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
