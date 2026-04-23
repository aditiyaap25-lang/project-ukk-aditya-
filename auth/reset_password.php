<?php
date_default_timezone_set('Asia/Jakarta');
include "../config/koneksi.php";

$token = isset($_GET['token']) ? trim($_GET['token']) : '';
$error = '';
$success = '';

// Jika POST request (user submit form)
if (isset($_POST['reset_password'])) {
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];
    $token = trim($_POST['token']);
    
    // Validasi token
    $result = mysqli_query($conn, "SELECT id_user FROM users WHERE reset_token='$token' AND reset_expiry > NOW()");
    
    if (mysqli_num_rows($result) == 0) {
        $error = 'Link reset password tidak valid atau sudah expired!';
    } else if (strlen($password) < 6) {
        $error = 'Password harus minimal 6 karakter!';
    } else if ($password !== $password_confirm) {
        $error = 'Password tidak cocok!';
    } else {
        $user = mysqli_fetch_assoc($result);
        $id_user = $user['id_user'];
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Update password dan clear token
        $update = mysqli_query($conn, "UPDATE users SET password='$hashed_password', reset_token=NULL, reset_expiry=NULL WHERE id_user=$id_user");
        
        if ($update) {
            $success = 'Password berhasil direset! Silakan login dengan password baru Anda.';
            $token = ''; // Clear token setelah berhasil
        } else {
            $error = 'Terjadi kesalahan! Silakan coba lagi.';
        }
    }
} else if ($token) {
    // Validasi token saat halaman pertama kali dimuat
   $now = date('Y-m-d H:i:s');
$result = mysqli_query($conn, "SELECT id_user FROM users WHERE reset_token='$token' AND reset_expiry > '$now'");
    
    if (mysqli_num_rows($result) == 0) {
        $error = 'Link reset password tidak valid atau sudah expired! Silakan <a href="forgot_password.php" class="text-primary font-bold">minta link baru</a>.';
        $token = '';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Reset Password - Perpustakaan Bintang Pengetahuan</title>
    
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
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Buat Password Baru</h1>
            <p class="text-slate-500 font-medium text-sm">Masukkan password baru Anda di bawah ini.</p>
        </div>

        <!-- Alert Error -->
        <?php if ($error): ?>
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <div class="text-sm text-red-800"><?php echo $error; ?></div>
        </div>
        <?php endif; ?>

        <!-- Alert Success -->
        <?php if ($success): ?>
        <div class="bg-white rounded-[32px] shadow-sm border border-slate-100 p-8 md:p-10 text-center space-y-6">
            <div class="inline-flex items-center justify-center h-16 w-16 bg-green-100 rounded-full">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-black text-slate-900 mb-2">Password Berhasil Direset!</h2>
                <p class="text-slate-600"><?php echo $success; ?></p>
            </div>
            <a href="login.php" class="block w-full py-3 px-4 bg-primary text-white font-bold rounded-xl hover:bg-blue-600 transition-colors">
                Kembali ke Login
            </a>
        </div>
        <?php elseif ($token): ?>
        <!-- Form Reset Password -->
        <div class="bg-white p-8 md:p-10 rounded-[32px] shadow-sm border border-slate-100">
            
            <form action="" method="POST" class="space-y-5">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                
                <div class="space-y-2">
                    <label for="password" class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required 
                               class="w-full h-13 pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/30 placeholder:text-slate-400 text-slate-900 focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all outline-none"
                               placeholder="Minimal 6 karakter"/>
                    </div>
                </div>

                <div class="space-y-2">
                    <label for="password_confirm" class="text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password_confirm" name="password_confirm" required 
                               class="w-full h-13 pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/30 placeholder:text-slate-400 text-slate-900 focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all outline-none"
                               placeholder="Ketik ulang password Anda"/>
                    </div>
                </div>

                <button type="submit" name="reset_password" 
                        class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 active:scale-[0.98] transition-all mt-2">
                    Reset Password
                </button>
            </form>

            <div class="border-t border-slate-200 mt-6 pt-6">
                <p class="text-center text-sm font-medium text-slate-500">
                    <a href="forgot_password.php" class="text-primary font-bold hover:underline">Minta link reset baru</a>
                </p>
            </div>
        </div>
        <?php endif; ?>

    </div>

</body>
</html>
