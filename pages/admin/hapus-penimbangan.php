<?php
session_start();

if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
    header("location:../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil ID dari query parameter
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    // Hapus data dari database
    $query = "DELETE FROM penimbangan WHERE id_penimbangan = ?";
    $stmt = $koneksi->prepare($query);

    if ($stmt === false) {
        die("Gagal mempersiapkan query: " . $koneksi->error);
    }

    $stmt->bind_param('i', $id);

    if ($stmt->execute()) {
        header("Location: kelola-penimbangan.php?pesan=hapus");
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
} else {
    echo "ID Penimbangan tidak valid!";
}
