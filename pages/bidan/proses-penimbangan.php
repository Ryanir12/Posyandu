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
    $tanggal_timbangan = $_POST['tgl_timbangan'];
    $usia = $_POST['usia'];
    $berat_badan = $_POST['bb'];
    $tinggi_badan = $_POST['tb'];
    $deteksi = $_POST['deteksi'];
    $keterangan = $_POST['ket'];

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
            header("Location: tambah-penimbangan.php");
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Gagal menyiapkan statement: " . $koneksi->error;
        header("Location: tambah-penimbangan.php");
        exit;
    }

    // Query untuk menyimpan data penimbangan ke tabel penimbangan
    $query = "INSERT INTO penimbangan (tgl_timbangan, usia, bb, tb, deteksi, ket, id_anak, id_bidan, petugas) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $koneksi->prepare($query)) {
        // Bind parameter dengan tipe data yang sesuai
        $stmt->bind_param("siidsssis", $tanggal_timbangan, $usia, $berat_badan, $tinggi_badan, $deteksi, $keterangan, $id_anak, $bidan_id, $petugas);
        if ($stmt->execute()) {
            $_SESSION['success'] = "Data berhasil disimpan.";
            header("Location: kelola-penimbangan.php?pesan=berhasil");
            exit;
        } else {
            $_SESSION['error'] = "Gagal menyimpan data: " . $stmt->error;
            header("Location: tambah-penimbangan.php");
            exit;
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = "Prepare statement gagal: " . $koneksi->error;
        header("Location: tambah-penimbangan.php");
        exit;
    }
}

$koneksi->close();
