<?php
session_start();

include "koneksi.php";

$username = $_POST['username'];
$password = $_POST['password'];

$login = mysqli_query($koneksi, "SELECT * FROM user WHERE username='$username' and password='$password'");

$cek = mysqli_num_rows($login);

if ($cek > 0) {
    $data = mysqli_fetch_assoc($login);


    if ($data['hak_akses'] == "bidan") {
        //buat session
        $_SESSION['username'] = $username;
        $_SESSION['hak_akses'] = "bidan";
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['jabatan'] = $data['jabatan'];
        $_SESSION['id_user'] = $data['id_user'];

        //alihkan
        header("location:pages/bidan/index-bidan.php");
    } else if ($data['hak_akses'] == "admin") {
        //buat session
        $_SESSION['username'] = $username;
        $_SESSION['hak_akses'] = "admin";
        $_SESSION['nama'] = $data['nama'];
        $_SESSION['jabatan'] = $data['jabatan'];
        $_SESSION['id_user'] = $data['id_user'];

        //alihkan
        header("location:pages/admin/index-admin.php");
    } else {

        // alihkan ke halaman login kembali
        header("location:./index.php?pesan=gagal");
    }
} else {
    header("location:./index.php?pesan=gagal");
}
