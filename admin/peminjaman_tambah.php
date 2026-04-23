<?php
include "../config/koneksi.php";

if (isset($_POST['simpan'])) {
    $id_user = $_POST['id_user'];
    $id_buku = $_POST['id_buku'];
    $tgl_pinjam = date('Y-m-d');

    mysqli_query($conn, "
        INSERT INTO peminjaman (id_user, id_buku, tgl_pinjam, status)
        VALUES ('$id_user', '$id_buku', '$tgl_pinjam', 'dipinjam')
    ");

    mysqli_query($conn, "
        UPDATE buku SET stok = stok - 1 WHERE id_buku='$id_buku'
    ");

    header("Location: peminjaman.php");
}
?>

<h2>Tambah Peminjaman</h2>
<form method="post">
    Peminjam <br>
    <select name="id_user" required>
        <option value="">-- Pilih --</option>
        <?php
        $u = mysqli_query($conn, "SELECT * FROM users WHERE role='peminjam'");
        while($row = mysqli_fetch_assoc($u)){
            echo "<option value='$row[id_user]'>$row[nama]</option>";
        }
        ?>
    </select><br><br>

    Buku <br>
    <select name="id_buku" required>
        <option value="">-- Pilih --</option>
        <?php
        $b = mysqli_query($conn, "SELECT * FROM buku WHERE stok > 0");
        while($row = mysqli_fetch_assoc($b)){
            echo "<option value='$row[id_buku]'>$row[judul]</option>";
        }
        ?>
    </select><br><br>

    <button type="submit" name="simpan">Simpan</button>
</form>
