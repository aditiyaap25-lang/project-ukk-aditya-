<?php
date_default_timezone_set('Asia/Jakarta');
include "../config/koneksi.php";

if (isset($_POST['reset_request'])) {
    $email = trim($_POST['email']);
    
    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Email tidak valid!'); window.location='forgot_password.php';</script>";
        exit;
    }
    
    // Cek email di database
    $result = mysqli_query($conn, "SELECT id_user FROM users WHERE email='$email'");
    
    if (mysqli_num_rows($result) == 0) {
        // Jangan memberitahu bahwa email tidak terdaftar (keamanan)
        echo "<script>
            alert('Jika email Anda terdaftar, Anda akan menerima link untuk reset password. Silakan cek email Anda dalam beberapa menit.');
            window.location='login.php';
        </script>";
        exit;
    }
    
    $user = mysqli_fetch_assoc($result);
    $id_user = $user['id_user'];
    
    // Generate random token
    $token = bin2hex(random_bytes(32));
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
    
    // Simpan token ke database
    $update_query = "UPDATE users SET reset_token='$token', reset_expiry='$expiry' WHERE id_user=$id_user";
    
    if (mysqli_query($conn, $update_query)) {
        
        // ============================================
        // UNTUK LOCAL DEVELOPMENT (tampilkan link)
        // ============================================
        // Di production, ganti dengan pengiriman email
        
        $reset_link = "http://localhost/projeck_ukk/auth/reset_password.php?token=" . $token;
        
        // Tampilkan halaman konfirmasi dengan opsi untuk copy link (untuk local dev)
        ?>
        <!DOCTYPE html>
        <html lang="id">
        <head>
            <meta charset="utf-8"/>
            <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
            <title>Reset Password Dikirim</title>
            <script src="https://cdn.tailwindcss.com"></script>
            <script id="tailwind-config">
                tailwind.config = {
                    theme: {
                        extend: {
                            colors: {
                                "primary": "#3B82F6",
                            },
                        },
                    },
                }
            </script>
        </head>
        <body class="bg-[#F8FAFC] min-h-screen flex items-center justify-center p-4 font-sans">
            <div class="w-full max-w-[480px]">
                <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 p-8 md:p-10 text-center space-y-6">
                    
                    <div class="inline-flex items-center justify-center h-16 w-16 bg-green-100 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 mb-2">Cek Email Anda!</h1>
                        <p class="text-slate-600">Kami telah mengirimkan link untuk reset password ke email Anda. Link berlaku selama 1 jam.</p>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-left space-y-3">
                        <p class="text-sm font-medium text-slate-700">
                            <strong>Untuk Development/Testing:</strong> Anda bisa menggunakan link di bawah ini untuk mereset password:
                        </p>
                        <div class="bg-white rounded-lg p-3 break-all text-xs font-mono text-slate-700 border border-slate-200">
                            <?php echo htmlspecialchars($reset_link); ?>
                        </div>
                        <button type="button" onclick="copyToClipboard()" class="w-full py-2 px-3 bg-blue-600 text-white text-sm font-bold rounded-lg hover:bg-blue-700 transition-colors">
                            Salin Link
                        </button>
                    </div>

                    <div class="space-y-3">
                        <p class="text-xs text-slate-500">
                            Atau gunakan tombol di bawah untuk langsung reset password:
                        </p>
                        <a href="reset_password.php?token=<?php echo $token; ?>" class="block w-full py-3 px-4 bg-primary text-white font-bold rounded-xl hover:bg-blue-600 transition-colors">
                            Reset Password
                        </a>
                    </div>

                    <p class="text-xs text-slate-500">
                        Tidak mendapat email? <a href="forgot_password.php" class="text-primary font-bold hover:underline">Coba lagi</a>
                    </p>
                </div>
            </div>

            <script>
                function copyToClipboard() {
                    const link = "<?php echo htmlspecialchars($reset_link); ?>";
                    navigator.clipboard.writeText(link).then(() => {
                        alert('Link sudah disalin ke clipboard!');
                    });
                }
            </script>
        </body>
        </html>
        <?php
        
    } else {
        echo "<script>alert('Terjadi kesalahan! Silakan coba lagi.'); window.location='forgot_password.php';</script>";
    }
}
?>
