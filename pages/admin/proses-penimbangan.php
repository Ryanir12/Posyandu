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

// Debug: Tampilkan data POST
echo '<pre>';
print_r($_POST);
echo '</pre>';

if (!isset($_POST['id_anak']) || empty($_POST['id_anak'])) {
    die("ID Anak tidak ada atau kosong");
}

$id_anak = $_POST['id_anak'];
$id_bidan = $_POST['bidan'];
$petugas = $_POST['petugas'];
$tanggal_penimbangan = $_POST['tgl_timbangan'];
$usia = $_POST['usia'];
$berat_badan = $_POST['bb'];
$tinggi_badan = $_POST['tb'];
$keterangan = $_POST['ket'];

// Logika deteksi pertumbuhan
$deteksi = '';
if ($berat_badan > 0 && $tinggi_badan > 0) {
    $imt = $berat_badan / (($tinggi_badan / 100) ** 2); // Convert cm to meters
    if ($imt >= 18.5 && $imt <= 24.9) {
        $deteksi = 'Ideal';
    } else {
        $deteksi = 'Tidak Ideal';
    }
}

// Siapkan statement SQL
$sql = "INSERT INTO penimbangan (tgl_timbangan, usia, bb, tb, deteksi, ket, id_anak, id_bidan) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $koneksi->prepare($sql);

if ($stmt === false) {
    die("Prepare statement gagal: " . $koneksi->error);
}

$stmt->bind_param("siidssis", $tanggal_penimbangan, $usia, $berat_badan, $tinggi_badan, $deteksi, $keterangan, $id_anak, $id_bidan);

if ($stmt->execute()) {
    header("Location: kelola-penimbangan.php?pesan=success");
} else {
    die("Execute statement gagal: " . $stmt->error);
}

$stmt->close();
$koneksi->close();
