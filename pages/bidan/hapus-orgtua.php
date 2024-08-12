<?php
include "../../koneksi.php"; // Pastikan path ke koneksi database benar

// Cek apakah parameter 'no' ada dan bukan kosong
if (isset($_GET['no']) && !empty($_GET['no'])) {
    // Amankan input
    $no = mysqli_real_escape_string($koneksi, $_GET['no']);

    // Periksa apakah no valid sebelum menghapus
    if ($no) {
        // Hapus data dari database
        $query = "DELETE FROM orang_tua WHERE no = ?";
        $stmt = $koneksi->prepare($query);
        $stmt->bind_param("s", $no); // 's' untuk string
        $stmt->execute();

        // Redirect setelah berhasil menghapus
        header("Location: kelola-orgtua.php?pesan=hapus_sukses");
        exit(); // Hentikan eksekusi script setelah redirect
    } else {
        header("Location: kelola-orgtua.php?pesan=no_tidak_valid");
        exit();
    }
} else {
    header("Location: kelola-orgtua.php?pesan=no_tidak_ditemukan");
    exit();
}

$koneksi->close();
