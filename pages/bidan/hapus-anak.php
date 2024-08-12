<?php
session_start();

if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
    header("location:../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk menghapus data anak berdasarkan id
    $query_delete = "DELETE FROM anak WHERE id='$id'";

    if ($koneksi->query($query_delete) === TRUE) {
        header("Location: kelola-anak.php?pesan=berhasil");
        exit;
    } else {
        header("Location: kelola-anak.php?pesan=gagal");
        exit;
    }
} else {
    header("Location: kelola-anak.php?pesan=gagal");
    exit;
}
