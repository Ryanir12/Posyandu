<?php
session_start();
include '../../koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $id_anak = $_POST['nama_anak'];
    $id_ibu = $_POST['nama_ibu']; // Mengambil ID ibu dari form
    $bidan_id = $_POST['bidan'];
    $petugas = $_POST['petugas'];
    $tanggal_imunisasi = $_POST['tanggal_imunisasi'];
    $usia_anak = $_POST['usia_anak'];
    $jenis_imunisasi = $_POST['jenis_imunisasi'];
    $vitamin = $_POST['vitamin'];
    $keterangan = $_POST['keterangan'];

    // Query untuk menyimpan data imunisasi ke tabel kelola_imunisasi
    $query = "INSERT INTO kelola_imunisasi (id_anak, id_ibu, bidan_id, petugas, tanggal_imunisasi, usia_anak, jenis_imunisasi, vitamin, keterangan) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    if ($stmt = $koneksi->prepare($query)) {
        $stmt->bind_param("iiissssss", $id_anak, $id_ibu, $bidan_id, $petugas, $tanggal_imunisasi, $usia_anak, $jenis_imunisasi, $vitamin, $keterangan);
        if ($stmt->execute()) {
            header("Location: kelola-imunisasi.php?pesan=berhasil");
        } else {
            echo "Gagal menyimpan data: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Prepare statement gagal: " . $koneksi->error;
    }
}
