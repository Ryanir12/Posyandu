<?php
session_start();
include '../../koneksi.php';

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_anak = $_POST['id_anak'];
    $bidan_id = $_POST['bidan'];
    $petugas = $_POST['petugas'];
    $tanggal_imunisasi = $_POST['tgl_imunisasi'];
    $usia_anak = $_POST['usia'];
    $jenis_imunisasi = $_POST['jenis_imunisasi'];
    $vitamin = $_POST['vitamin'];
    $keterangan = $_POST['keterangan'];

    // Ambil nomor orang tua berdasarkan id anak
    $query_orang_tua_no = "SELECT id_ibu FROM anak WHERE id = ?";
    if ($stmt = $koneksi->prepare($query_orang_tua_no)) {
        $stmt->bind_param("i", $id_anak);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $orang_tua_no = $row['id_ibu'];
        } else {
            $_SESSION['error'] = "Nomor orang tua tidak ditemukan.";
            header("Location: tambah-imunisasi.php");
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Gagal menyiapkan statement: " . $koneksi->error;
        header("Location: tambah-imunisasi.php");
        exit;
    }

    // Query untuk menyimpan data imunisasi ke tabel kelola_imunisasi
    $query = "INSERT INTO kelola_imunisasi (anak_id, orang_tua_no, bidan_id, petugas, tanggal_imunisasi, usia_anak, jenis_imunisasi, vitamin, keterangan) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $koneksi->prepare($query)) {
        $stmt->bind_param("iisssisss", $id_anak, $orang_tua_no, $bidan_id, $petugas, $tanggal_imunisasi, $usia_anak, $jenis_imunisasi, $vitamin, $keterangan);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Data berhasil disimpan.";
            header("Location: kelola-imunisasi.php?pesan=berhasil");
        } else {
            $_SESSION['error'] = "Gagal menyimpan data: " . $stmt->error;
            header("Location: tambah-imunisasi.php");
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Prepare statement gagal: " . $koneksi->error;
        header("Location: tambah-imunisasi.php");
    }
}

$koneksi->close();
