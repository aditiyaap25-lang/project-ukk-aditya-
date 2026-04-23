<?php
session_start();
include "../config/koneksi.php";

if (!isset($_SESSION['role'])) {
    header("Location: ../auth/login.php");
    exit;
}

$data = mysqli_query($conn, "
    SELECT p.*, u.nama, b.judul 
    FROM peminjaman p
    JOIN users u ON p.id_user = u.id_user
    JOIN buku b ON p.id_buku = b.id_buku
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>Data Peminjaman - Library Admin</title>
    
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
        
        <header class="flex flex-col md:flex-row md:items-center justify-between gap-4 p-6 bg-white rounded-3xl shadow-sm border border-slate-100">
            <div class="flex items-center gap-4">
                <a href="dashboard.php" class="h-11 w-11 border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 hover:text-primary transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-900 tracking-tight">Data Peminjaman</h1>
                    <p class="text-sm text-slate-500 font-medium">Kelola riwayat peminjaman buku perpustakaan.</p>
                </div>
            </div>
            
            <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-2xl border border-slate-100">
                <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">Admin Portal</span>
            </div>
        </header>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">No</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Peminjam</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest">Judul Buku</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Tgl Pinjam</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Tgl Kembali</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Status</th>
                            <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        <?php 
                        $no = 1;
                        while($row = mysqli_fetch_assoc($data)) { 
                            // Logika Warna Status
                            $is_returned = ($row['status'] == 'dikembalikan');
                            $status_class = $is_returned 
                                ? "bg-emerald-50 text-emerald-600 ring-emerald-600/20" 
                                : "bg-amber-50 text-amber-600 ring-amber-600/20";
                        ?>
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-400"><?php echo $no++; ?></td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-slate-100 flex items-center justify-center text-xs font-bold text-slate-500">
                                        <?php echo substr($row['nama'], 0, 1); ?>
                                    </div>
                                    <span class="font-bold text-slate-700"><?php echo $row['nama']; ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-slate-600 italic">"<?php echo $row['judul']; ?>"</td>
                            <td class="px-6 py-4 text-center text-slate-500 font-medium"><?php echo date('d M Y', strtotime($row['tgl_pinjam'])); ?></td>
                            <td class="px-6 py-4 text-center text-slate-500 font-medium">
                                <?php echo $row['tgl_kembali'] ? date('d M Y', strtotime($row['tgl_kembali'])) : '-'; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider ring-1 ring-inset <?php echo $status_class; ?>">
                                    <?php echo $row['status']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <?php if ($row['status'] == 'dipinjam') { ?>
                                    <a href="peminjaman_kembali.php?id=<?= $row['id_peminjaman'] ?>&buku=<?= $row['id_buku'] ?>" class="p-2 text-slate-400 hover:text-primary transition-colors" title="Kembalikan">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </a>
                                    <?php } ?>
                                    <a href="peminjaman_hapus.php?id=<?= $row['id_peminjaman'] ?>" onclick="return confirm('Hapus peminjaman ini?')" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus">
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
                    Total: <?php echo mysqli_num_rows($data); ?> Transaksi
                </p>
                <div class="flex gap-1">
                    <div class="h-8 w-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                    </div>
                    <div class="h-8 w-8 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                    </div>
                </div>
            </div>
        </div>

    </div>

</body>
</html>