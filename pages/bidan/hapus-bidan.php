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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data bidan
    $query = "DELETE FROM bidan WHERE id_bidan = '$id'";

    if ($koneksi->query($query) === TRUE) {
        echo "<script>alert('Data bidan berhasil dihapus.'); window.location.href='kelola-bidan.php';</script>";
    } else {
        echo "Error: " . $query . "<br>" . $koneksi->error;
    }
} else {
    echo "<script>alert('ID bidan tidak ditemukan.'); window.location.href='kelola-bidan.php';</script>";
}
