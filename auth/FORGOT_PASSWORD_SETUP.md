# Fitur Lupa Password - Setup & Panduan Penggunaan

## 🔧 Setup Awal

Sebelum fitur lupa password bisa digunakan, Anda perlu menjalankan script untuk menambahkan kolom ke database:

### Langkah 1: Jalankan Script Setup
1. Buka browser Anda
2. Akses URL: `http://localhost/projeck_ukk/auth/add_reset_columns.php`
3. Tunggu hingga muncul pesan "✓ Kolom berhasil ditambahkan!" atau "Kolom sudah ada"
4. Setelah selesai, **hapus file `add_reset_columns.php`** dari server untuk keamanan

Kolom yang ditambahkan:
- `reset_token` (VARCHAR 100) - menyimpan token reset
- `reset_expiry` (DATETIME) - menyimpan waktu expired token

---

## 📋 Alur Penggunaan Fitur Lupa Password

### Untuk User (Pengguna):

**1. Di Halaman Login:**
   - Klik link "Lupa?" di samping field "Kata Sandi"
   - Atau akses langsung: `/auth/forgot_password.php`

**2. Di Halaman Lupa Password:**
   - Masukkan email terdaftar
   - Klik tombol "Kirim Link Reset"

**3. Di Halaman Konfirmasi:**
   - Untuk **development/testing**: Link reset ditampilkan langsung
   - Salin link atau klik tombol "Reset Password"
   - Di production: Pengguna akan menerima email dengan link reset

**4. Di Halaman Reset Password:**
   - Masukkan password baru (minimal 6 karakter)
   - Konfirmasi password
   - Klik "Reset Password"
   - Jika berhasil, login dengan password baru

---

## 🔒 Fitur Keamanan

✓ **Token Verification**: Setiap link reset memiliki token unik 32-byte
✓ **Token Expiry**: Token berlaku hanya 1 jam
✓ **One-time Use**: Token dihapus setelah password berhasil direset
✓ **Password Hashing**: Password di-hash dengan `PASSWORD_DEFAULT` (bcrypt)
✓ **Email Validation**: Email divalidasi sebelum pemrosesan
✓ **No Email Enumeration**: Pesan respons sama untuk email terdaftar/tidak

---

## 📧 Integrasi Email (Production)

Untuk production, Anda perlu mengintegrasikan email service. Langkah-langkahnya:

### Option 1: Menggunakan PHP Mail (Built-in)
```php
$reset_link = "https://yourdomain.com/auth/reset_password.php?token=" . $token;
$subject = "Reset Password - Perpustakaan Bintang Pengetahuan";
$message = "Klik link berikut untuk reset password: " . $reset_link;
$headers = "From: noreply@perpustakaan.com";

mail($email, $subject, $message, $headers);
```

### Option 2: Menggunakan PHPMailer Library
```php
$mail = new PHPMailer(true);
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'your-email@gmail.com';
$mail->Password = 'your-app-password';
$mail->SMTPSecure = 'tls';
$mail->Port = 587;

$mail->setFrom('noreply@perpustakaan.com', 'Perpustakaan');
$mail->addAddress($email);
$mail->Subject = 'Reset Password';
$mail->Body = 'Klik link: ' . $reset_link;
$mail->send();
```

Modifikasi di file `proses_forgot_password.php` setelah bagian komentar `// UNTUK LOCAL DEVELOPMENT`.

---

## 🧪 Testing Checklist

- [ ] Buka halaman login dan klik "Lupa?"
- [ ] Masukkan email yang terdaftar
- [ ] Verifikasi link reset ditampilkan dengan benar
- [ ] Klik link reset dan input password baru
- [ ] Coba login dengan password baru
- [ ] Klik link yang sudah digunakan (harus error)
- [ ] Tunggu 1 jam atau ubah database waktu expiry (harus error)
- [ ] Coba email yang tidak terdaftar (harus aman)

---

## 📁 File-File yang Ditambahkan

| File | Deskripsi |
|------|-----------|
| `forgot_password.php` | Halaman form input email |
| `proses_forgot_password.php` | Proses generate token dan kirim email |
| `reset_password.php` | Halaman form reset password |
| `add_reset_columns.php` | Script setup database (hapus setelah dijalankan) |

---

## 🆘 Troubleshooting

**Q: Token tidak valid atau sudah expired**
- A: Token berlaku hanya 1 jam. Minta link reset baru.

**Q: Email tidak terkirim**
- A: Ini adalah mode development. Gunakan link yang ditampilkan di halaman.
- Untuk production, konfigurasi SMTP email service.

**Q: Password tidak bisa direset**
- A: Pastikan password baru:
  - Minimal 6 karakter
  - Cocok dengan konfirmasi password
  - Token masih valid

**Q: Kolom sudah ada (saat jalankan add_reset_columns.php)**
- A: Normal! Artinya kolom sudah ditambahkan sebelumnya.
- Anda tetap bisa menghapus file `add_reset_columns.php`.

---

## 🔐 Best Practices

1. **Selalu gunakan HTTPS di production** untuk melindungi token
2. **Jangan tampilkan link reset di halaman** untuk production
3. **Gunakan email service yang reliable** untuk pengiriman
4. **Log setiap aktivitas reset password** untuk audit trail
5. **Cleanup token expired secara berkala** di database

---

Selamat! Fitur lupa password sudah siap digunakan! 🎉
