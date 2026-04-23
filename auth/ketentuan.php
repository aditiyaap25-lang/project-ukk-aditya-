<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Ketentuan Layanan - Perpustakaan Bintang Pengetahuan</title>
    
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
<body class="bg-[#F8FAFC] min-h-screen p-4 font-sans">

    <div class="max-w-4xl mx-auto">
        
        <div class="mb-8">
            <a href="register.php" class="inline-flex items-center gap-2 text-primary font-bold hover:underline mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                </svg>
                Kembali
            </a>

            <h1 class="text-4xl font-black text-slate-900 mb-2">Ketentuan Layanan</h1>
            <p class="text-slate-500">Perpustakaan Digital Bintang Pengetahuan</p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-8 md:p-12 space-y-8">
            
            <div class="prose prose-sm max-w-none text-slate-600 space-y-6">
                
                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">1. Pendaftaran Akun</h2>
                    <div class="space-y-3 text-slate-600">
                        <p>Dengan mendaftar untuk menggunakan layanan perpustakaan digital kami, Anda setuju untuk mematuhi ketentuan-ketentuan berikut:</p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 space-y-3">
                            <div class="flex gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h3 class="font-bold text-slate-900">Nama Asli</h3>
                                    <p class="text-sm">Anda harus mendaftarkan nama lengkap asli Anda. Penggunaan nama samaran, nama palsu, atau nama yang tidak sesuai dengan identitas pribadi Anda tidak diperbolehkan. Kami berhak untuk memverifikasi identitas Anda kapan saja.</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h3 class="font-bold text-slate-900">Email Asli dan Aktif</h3>
                                    <p class="text-sm">Alamat email yang Anda gunakan harus merupakan email asli dan aktif yang Anda miliki. Email harus dapat diakses dan diterima oleh Anda secara langsung. Kami akan mengirimkan pemberitahuan penting, notifikasi peminjaman, dan komunikasi lainnya ke email tersebut.</p>
                                </div>
                            </div>

                            <div class="flex gap-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <h3 class="font-bold text-slate-900">Satu Akun Per Orang</h3>
                                    <p class="text-sm">Setiap orang hanya diizinkan memiliki satu akun aktif. Pembuatan akun ganda atau penggunaan email orang lain tidak diperbolehkan dan dapat mengakibatkan pemblokiran akun Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">2. Tanggung Jawab Pengguna</h2>
                    <div class="space-y-3">
                        <ul class="space-y-2 list-disc list-inside text-slate-600">
                            <li>Anda bertanggung jawab untuk menjaga kerahasiaan password Anda</li>
                            <li>Anda setuju untuk tidak memberikan akses ke akun Anda kepada orang lain</li>
                            <li>Segala aktivitas yang dilakukan dengan akun Anda adalah tanggung jawab Anda</li>
                            <li>Anda wajib memberitahu kami jika akun Anda diakses tanpa izin</li>
                        </ul>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">3. Penggunaan Layanan</h2>
                    <div class="space-y-3">
                        <p>Dalam menggunakan layanan perpustakaan digital kami, Anda setuju untuk:</p>
                        <ul class="space-y-2 list-disc list-inside text-slate-600">
                            <li>Tidak melakukan aktivitas yang ilegal atau melanggar hukum</li>
                            <li>Tidak menggunakan layanan untuk mengganggu atau membahayakan pengguna lain</li>
                            <li>Menghormati hak cipta dan kekayaan intelektual pihak lain</li>
                            <li>Hanya menggunakan konten yang diunduh untuk keperluan pribadi dan non-komersial</li>
                        </ul>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold text-slate-900 mb-4">4. Pelanggaran Ketentuan</h2>
                    <div class="space-y-3">
                        <p>Jika Anda melanggar ketentuan-ketentuan ini, terutama dalam hal memberikan informasi palsu atau tidak akurat, kami berhak untuk:</p>
                        <ul class="space-y-2 list-disc list-inside text-slate-600">
                            <li>Menangguhkan akun Anda</li>
                            <li>Memblokir akun Anda secara permanen</li>
                            <li>Menghapus data terkait akun Anda</li>
                            <li>Mengambil tindakan hukum jika diperlukan</li>
                        </ul>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4">
                    <div class="flex gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4v2m0 4v2M12 3v2m0 4v2m0 4v2" />
                        </svg>
                        <div>
                            <h3 class="font-bold text-yellow-900 mb-2">Perhatian Penting</h3>
                            <p class="text-sm text-yellow-800">Dengan mengklik tombol "Setuju" pada formulir pendaftaran, Anda menyatakan bahwa semua informasi yang Anda berikan adalah benar, akurat, dan lengkap sesuai dengan identitas asli Anda. Anda juga setuju untuk bertanggung jawab atas segala akibat dari pemberian informasi palsu.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="border-t border-slate-200 pt-6 flex gap-4">
                <a href="register.php" class="flex-1 py-3 px-4 text-center rounded-xl border border-slate-200 text-slate-900 font-bold hover:bg-slate-50 transition-colors">
                    Tidak Setuju
                </a>
                <a href="register.php" class="flex-1 py-3 px-4 text-center rounded-xl bg-primary text-white font-bold hover:bg-blue-600 transition-colors">
                    Kembali ke Pendaftaran
                </a>
            </div>

        </div>
    </div>

</body>
</html>
