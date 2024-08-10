<?php
session_start();

if (!isset($_SESSION['hak_akses']) || empty($_SESSION['hak_akses'])) {
    header("Location: ../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_penimbangan = $_POST['id_penimbangan'];
    $id_anak = $_POST['id_anak'];
    $id_bidan = $_POST['id_bidan'];
    $tgl_timbangan = $_POST['tgl_timbangan'];
    $usia = $_POST['usia'];
    $bb = $_POST['bb'];
    $tb = $_POST['tb'];
    $deteksi = $_POST['deteksi'];
    $ket = $_POST['ket'];

    if (empty($id_penimbangan) || empty($id_anak) || empty($id_bidan) || empty($tgl_timbangan) || empty($bb) || empty($tb)) {
        die("Semua field wajib diisi");
    }

    // Query untuk mengupdate data penimbangan
    $query = "UPDATE penimbangan SET id_anak = ?, id_bidan = ?, tgl_timbangan = ?, usia = ?, bb = ?, tb = ?, deteksi = ?, ket = ? WHERE id_penimbangan = ?";
    $stmt = $koneksi->prepare($query);
    $stmt->bind_param("iissddssi", $id_anak, $id_bidan, $tgl_timbangan, $usia, $bb, $tb, $deteksi, $ket, $id_penimbangan);

    if ($stmt->execute()) {
        header("Location: kelola-penimbangan.php?pesan=sukses");
    } else {
        die("Update gagal: " . $koneksi->error);
    }
}
