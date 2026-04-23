<?php
session_start();
include "../config/koneksi.php";

$query = "SELECT * FROM users ORDER BY id_user DESC";
$result = mysqli_query($conn, $query);

if (isset($_POST['simpan'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash password
    $role = $_POST['role'];

   
    $cek_email = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    if (mysqli_num_rows($cek_email) > 0) {
        echo "<script>alert('Email sudah terdaftar!'); window.location='user.php';</script>";
    } else {
        mysqli_query($conn, "INSERT INTO users (nama, email, password, role) VALUES ('$nama', '$email', '$password', '$role')");
        header("Location: user.php");
        exit;
    }
}

if (isset($_POST['update']) && !empty($_POST['id_user'])) {
    $id_user = (int) $_POST['id_user'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $password = $_POST['password'];

    $password_sql = '';
    if (!empty($password)) {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $password_sql = ", password='$password_hash'";
    }

    mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email', role='$role'$password_sql WHERE id_user='$id_user'");
    header("Location: user.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Kelola Data User - Library Admin</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    
    <script id="tailwind-config">
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        "primary": "#3B82F6",
                        "bg-main": "#F8FAFC",
                    },
                    fontFamily: {
                        "sans": ["Plus Jakarta Sans", "Inter", "sans-serif"]
                    }
                },
            },
        }
    </script>
</head>
<body class="bg-[#F8FAFC] min-h-screen font-sans p-4 md:p-8">

    <div class="max-w-7xl mx-auto space-y-6">
        
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 bg-white rounded-3xl shadow-sm border border-slate-100 relative overflow-hidden">
            <div class="flex items-center gap-4 z-10">
                <a href="dashboard.php" class="h-11 w-11 border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-primary transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Data Pengguna</h1>
                    <p class="text-sm text-slate-500 font-medium">Kelola akses staf dan anggota perpustakaan.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3 z-10">
                <div class="group relative">
                    <button class="flex items-center gap-2.5 bg-slate-100 text-slate-600 px-6 py-3 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                        Ekspor Data
                    </button>
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-lg border border-slate-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50">
                        <a href="export_users.php?format=csv" class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-slate-50 first:rounded-t-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span class="font-semibold text-sm">CSV File</span>
                        </a>
                        <a href="export_users.php?format=excel" class="flex items-center gap-3 px-4 py-3 text-slate-700 hover:bg-slate-50 last:rounded-b-2xl">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <span class="font-semibold text-sm">Excel File</span>
                        </a>
                    </div>
                </div>
                
                <button id="openModal" class="flex items-center gap-2.5 bg-primary text-white px-6 py-3 rounded-2xl font-bold text-sm hover:bg-blue-600 transition-all active:scale-95 shadow-lg shadow-primary/20">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" /></svg>
                    Tambah User Baru
                </button>
            </div>
        </header>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest w-16 text-center">No</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Lengkap</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Alamat Email</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Role</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($result)) { 
                            // Warna Badge Role
                            $is_admin = ($row['role'] == 'admin');
                            $is_petugas = ($row['role'] == 'petugas');
                            $role_class = $is_admin 
                                ? "bg-red-50 text-red-600 ring-red-600/20" 
                                : ($is_petugas ? "bg-blue-50 text-blue-600 ring-blue-600/20" : "bg-emerald-50 text-emerald-600 ring-emerald-600/20");
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-400 text-center"><?php echo $no++; ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-9 w-9 rounded-full bg-slate-100 flex items-center justify-center text-xs font-extrabold text-slate-500">
                                        <?php echo strtoupper(substr($row['nama'], 0, 1)); ?>
                                    </div>
                                    <span class="font-bold text-slate-700"><?php echo $row['nama']; ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600 italic"><?php echo $row['email']; ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider ring-1 ring-inset <?php echo $role_class; ?>">
                                    <?php echo $row['role']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button type="button" class="editUserBtn p-2 text-slate-400 hover:text-primary transition-colors" title="Edit"
                                            data-id="<?= $row['id_user']; ?>"
                                            data-nama="<?= htmlspecialchars($row['nama'], ENT_QUOTES); ?>"
                                            data-email="<?= htmlspecialchars($row['email'], ENT_QUOTES); ?>"
                                            data-role="<?= $row['role']; ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <a href="user_proses.php?hapus=<?php echo $row['id_user']; ?>" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus User" onclick="return confirm('Hapus user ini?')">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            
            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex items-center justify-between">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                    Total: <?php echo mysqli_num_rows($result); ?> Pengguna Terdaftar
                </p>
            </div>
        </div>

    </div>

    <div id="modalAddUser" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity duration-300 opacity-0 pointer-events-none">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-lg overflow-hidden border border-slate-100 scale-95 transition-transform duration-300">
            
            <div class="flex items-center justify-between p-6 pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <div class="h-10 w-10 bg-primary rounded-xl flex items-center justify-center text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                    </div>
                    <h2 id="modalTitle" class="text-xl font-extrabold text-slate-950 tracking-tight">Daftarkan Staf / Anggota</h2>
                </div>
                <button id="closeModal" class="text-slate-400 hover:text-red-500 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <form action="" method="POST" class="p-6 md:p-8 space-y-6">
                <input type="hidden" id="formUserId" name="id_user" value="">
                
                <div class="space-y-2">
                    <label for="nama" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Nama Lengkap
                    </label>
                    <input type="text" id="nama" name="nama" required placeholder="Contoh: Ahmad Sofyan..."
                           class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>

                <div class="space-y-2">
                    <label for="email" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" /></svg>
                        Alamat Email
                    </label>
                    <input type="email" id="email" name="email" required placeholder="ahmad@gmail.com"
                           class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-2">
                        <label for="password" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                            Password
                        </label>
                        <input type="password" id="password" name="password" placeholder="Kosongkan jika tidak ingin mengubah password"
                               class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white placeholder:text-slate-400 text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"/>
                        <p class="text-xs text-slate-400">Biarkan kosong untuk mempertahankan password lama saat mengedit.</p>
                    </div>

                    <div class="space-y-2">
                        <label for="role" class="flex items-center gap-2 text-sm font-semibold text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                            Role / Hak Akses
                        </label>
                        <select id="role" name="role" required class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-white text-slate-900 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih Role</option>
                            <option value="admin">Admin (Staf)</option>
                            <option value="petugas">Petugas (Staf)</option>
                            <option value="peminjam">Peminjam (Anggota)</option>
                        </select>
                    </div>
                </div>

                <div class="pt-6 mt-8 border-t border-slate-100 flex flex-col-reverse sm:flex-row sm:items-center sm:justify-end gap-3">
                    <button type="button" id="cancelModal" class="h-12 px-6 rounded-xl flex items-center justify-center font-semibold text-slate-600 border border-slate-200 hover:bg-slate-50 transition-colors">
                        Batal
                    </button>
                    <button id="formSubmitButton" type="submit" name="simpan" class="h-12 px-8 rounded-xl flex items-center justify-center gap-2 font-semibold text-white bg-primary hover:bg-blue-600 transition-colors shadow-sm shadow-blue-500/20 active:scale-[0.98]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                        <span id="formSubmitText">Simpan & Daftar User</span>
                    </button>
                </div>

            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('modalAddUser');
        const modalContent = modal.querySelector('.scale-95');
        const openBtn = document.getElementById('openModal');
        const closeBtn = document.getElementById('closeModal');
        const cancelBtn = document.getElementById('cancelModal');
        const formUserId = document.getElementById('formUserId');
        const inputNama = document.getElementById('nama');
        const inputEmail = document.getElementById('email');
        const inputPassword = document.getElementById('password');
        const inputRole = document.getElementById('role');
        const modalTitle = document.getElementById('modalTitle');
        const formSubmitButton = document.getElementById('formSubmitButton');
        const formSubmitText = document.getElementById('formSubmitText');

        function openModal(isEdit = false) {
            modal.classList.remove('opacity-0');
            modal.classList.remove('pointer-events-none');
            modalContent.classList.remove('scale-95');
            modalContent.classList.add('scale-100');

            if (isEdit) {
                modalTitle.textContent = 'Edit User';
                formSubmitButton.name = 'update';
                formSubmitText.textContent = 'Perbarui User';
            } else {
                modalTitle.textContent = 'Daftarkan Staf / Anggota';
                formSubmitButton.name = 'simpan';
                formSubmitText.textContent = 'Simpan & Daftar User';
                formUserId.value = '';
                inputNama.value = '';
                inputEmail.value = '';
                inputPassword.value = '';
                inputRole.value = '';
            }
        }

        function closeModal() {
            modal.classList.add('opacity-0');
            modal.classList.add('pointer-events-none');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
        }

        function initEditButtons() {
            const editButtons = document.querySelectorAll('.editUserBtn');
            editButtons.forEach(button => {
                button.addEventListener('click', function () {
                    formUserId.value = this.dataset.id;
                    inputNama.value = this.dataset.nama;
                    inputEmail.value = this.dataset.email;
                    inputPassword.value = '';
                    inputRole.value = this.dataset.role;
                    openModal(true);
                });
            });
        }

        openBtn.addEventListener('click', function () {
            openModal(false);
        });
        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        // Tutup jika klik di area blur background
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        initEditButtons();
    </script>

</body>
</html>