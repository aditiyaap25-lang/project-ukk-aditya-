<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Lupa Password - Perpustakaan Bintang Pengetahuan</title>
    
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
            <h1 class="text-3xl font-black text-slate-900 tracking-tight">Lupa Password?</h1>
            <p class="text-slate-500 font-medium text-sm">Masukkan email Anda untuk mereset password.</p>
        </div>

        <div class="bg-white p-8 md:p-10 rounded-[32px] shadow-sm border border-slate-100">
            
            <form action="proses_forgot_password.php" method="POST" class="space-y-5">
                
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
                               placeholder="email@example.com"/>
                    </div>
                    <p class="text-xs text-slate-500 ml-1">Kami akan mengirimkan link untuk mereset password ke email Anda.</p>
                </div>

                <button type="submit" name="reset_request" 
                        class="w-full bg-primary text-white py-4 rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-500/30 active:scale-[0.98] transition-all mt-4">
                    Kirim Link Reset
                </button>
            </form>

            <div class="border-t border-slate-200 mt-6 pt-6">
                <p class="text-center text-sm font-medium text-slate-500">
                    Ingat passwordnya? <a href="login.php" class="text-primary font-bold hover:underline">Masuk di sini</a>
                </p>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5">
            <div class="flex gap-3">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-xs font-medium text-blue-900">
                        <strong>Catatan:</strong> Link untuk reset password akan berlaku selama 1 jam. Jika link sudah expired, Anda dapat meminta link baru.
                    </p>
                </div>
            </div>
        </div>

    </div>

</body>
</html>
