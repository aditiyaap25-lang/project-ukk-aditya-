<?php
session_start();
if (isset($_SESSION['role'])) {
    header("Location: ../admin/dashboard.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Masuk - Perpustakaan Digital</title>
    
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

    <div class="w-full max-w-[440px] space-y-8">
        
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center h-16 w-16 bg-white rounded-2xl shadow-sm border border-slate-100 mb-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            </div>
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Selamat Datang</h1>
            <p class="text-slate-500 font-medium text-sm">ke Perpustakaan Bintang Pengetahuan</p>
        </div>

        <div class="bg-white p-8 md:p-10 rounded-[32px] shadow-sm border border-slate-100">
            
            <form action="login_proses.php" method="POST" class="space-y-6">
                
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
                    <div class="flex items-center justify-between ml-1">
                        <label for="password" class="text-xs font-bold text-slate-400 uppercase tracking-widest">Kata Sandi</label>
                        <a href="forgot_password.php" class="text-xs font-bold text-primary hover:underline">Lupa?</a>
                    </div>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 00-2 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input type="password" id="password" name="password" required 
                               class="w-full h-13 pl-11 pr-4 py-3.5 rounded-2xl border border-slate-200 bg-slate-50/30 placeholder:text-slate-400 text-slate-900 focus:ring-4 focus:ring-primary/10 focus:border-primary focus:bg-white transition-all outline-none"
                               placeholder="••••••••"/>
                    </div>
                </div>

                <div class="flex items-center gap-2 px-1">
                    <input type="checkbox" id="remember" class="w-4 h-4 rounded border-slate-300 text-primary focus:ring-primary/20 cursor-pointer">
                    <label for="remember" class="text-sm font-medium text-slate-600 cursor-pointer select-none">Ingat saya di perangkat ini</label>
                </div>

                <button type="submit" name="login" 
                        class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 active:scale-[0.98] transition-all">
                    Masuk Sekarang
                </button>
            </form>
        </div>

        <p class="text-center text-sm font-medium text-slate-500">
            Belum punya akun? <a href="register.php" class="text-primary font-bold hover:underline">Daftar Anggota</a>
        </p>

    </div>

</body>
</html>