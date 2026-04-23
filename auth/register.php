<?php
include "../config/koneksi.php";

if (isset($_POST['register'])) {

    $nama     = trim($_POST['nama']);
    $email    = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Validasi nama (harus nama asli, minimal 3 kata atau 8 karakter)
    $nama_parts = explode(" ", $nama);
    $nama_length = strlen($nama);

    if ($nama_length < 8) {
        echo "<script>alert('Nama lengkap harus minimal 8 karakter. Gunakan nama asli Anda!');</script>";
    } else if (preg_match('/[0-9]/', $nama)) {
        echo "<script>alert('Nama tidak boleh mengandung angka!');</script>";
    } else if (!preg_match('/^[a-zA-Z\s]+$/', $nama)) {
        echo "<script>alert('Nama hanya boleh mengandung huruf dan spasi!');</script>";
    } else if (count($nama_parts) < 2) {
        echo "<script>alert('Mohon masukkan nama lengkap (minimal 2 kata) asli Anda!');</script>";
    } else {
        // Validasi email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>alert('Silakan masukkan email yang valid!');</script>";
        } else {
            // Cek email sudah terdaftar
            $cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

            if (mysqli_num_rows($cek) > 0) {
                echo "<script>alert('Email sudah terdaftar di sistem!');</script>";
            } else {

                mysqli_query($conn, "
                    INSERT INTO users (nama, email, password, role)
                    VALUES ('$nama','$email','$password','peminjam')
                ");

                echo "<script>
                        alert('Registrasi berhasil! Silakan login dengan akun Anda.');
                        window.location='login.php';
                      </script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Daftar Anggota - Bintang Pengetahuan</title>
    
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
<body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-4 font-sans">

    <div class="w-full max-w-[480px] space-y-8">
        
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center h-16 w-16 bg-white rounded-2xl shadow-sm border border-slate-100 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Akun Baru</h1>
            <p class="text-slate-500 font-medium text-sm">Bergabung dengan komunitas perpustakaan kami.</p>
        </div>

        <div class="bg-white p-8 md:p-10 rounded-[32px] shadow-sm border border-slate-100">
            
            <form action="" method="POST" class="space-y-5">
                
                <div class="space-y-2">
                    <label for="nama" class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <input type="text" id="nama" name="nama" required 
                               class="w-full h-13 pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/30 placeholder:text-slate-400 text-slate-900 focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all outline-none"
                               placeholder="Nama lengkap Anda"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input type="email" id="email" name="email" required 
                               class="w-full h-13 pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/30 placeholder:text-slate-400 text-slate-900 focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all outline-none"
                               placeholder="nama@email.com"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password" class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required 
                               class="w-full h-13 pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/30 placeholder:text-slate-400 text-slate-900 focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all outline-none"
                               placeholder="Buat kata sandi aman"/>
                    </div>
                </div>

                <div class="flex items-start gap-2 px-1 pt-1">
                    <input type="checkbox" id="terms" required class="mt-1 w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer">
                    <label for="terms" class="text-[13px] font-medium text-slate-500 leading-relaxed cursor-pointer select-none">
                        Saya setuju dengan <a href="ketentuan.php" target="_blank" class="text-primary font-bold hover:underline">Ketentuan Layanan</a> dan kebijakan privasi perpustakaan.
                    </label>
                </div>

                <button type="submit" name="register" 
                        class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 active:scale-[0.98] transition-all mt-2">
                    Daftar Sekarang
                </button>
            </form>
        </div>

        <p class="text-center text-sm font-medium text-slate-500">
            Sudah punya akun? <a href="login.php" class="text-primary font-bold hover:underline">Masuk di sini</a>
        </p>

    </div>

</body>
</html>