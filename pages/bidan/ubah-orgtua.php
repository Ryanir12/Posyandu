<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: -0.5rem;
        }

        .form-col {
            flex: 1;
            padding: 0.5rem;
            min-width: 300px;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-group {
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>
</head>

<body id="page-top">

    <?php
    session_start();

    // Cek apakah yang mengakses halaman ini sudah login
    if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
        header("location:../../index.php?pesan=gagal");
        exit;
    }

    // Koneksi database
    include '../../koneksi.php';

    // Ambil no dari URL
    $no = mysqli_real_escape_string($koneksi, $_GET['no']);

    // Ambil data dari database
    $query = "SELECT * FROM orang_tua WHERE no = '$no'";
    $result = mysqli_query($koneksi, $query);
    $data = mysqli_fetch_assoc($result);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $nama_ibu = mysqli_real_escape_string($koneksi, $_POST['nama_ibu']);
        $tempat_lahir_ibu = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir_ibu']);
        $tanggal_lahir_ibu = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_ibu']);
        $golongan_darah_ibu = mysqli_real_escape_string($koneksi, $_POST['golongan_darah_ibu']);
        $pendidikan_ibu = mysqli_real_escape_string($koneksi, $_POST['pendidikan_ibu']);
        $pekerjaan_ibu = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_ibu']);
        $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);
        $kota = mysqli_real_escape_string($koneksi, $_POST['kota']);
        $kecamatan = mysqli_real_escape_string($koneksi, $_POST['kecamatan']);
        $nama_suami = mysqli_real_escape_string($koneksi, $_POST['nama_suami']);
        $tempat_lahir_suami = mysqli_real_escape_string($koneksi, $_POST['tempat_lahir_suami']);
        $tanggal_lahir_suami = mysqli_real_escape_string($koneksi, $_POST['tanggal_lahir_suami']);
        $pendidikan_suami = mysqli_real_escape_string($koneksi, $_POST['pendidikan_suami']);
        $pekerjaan_suami = mysqli_real_escape_string($koneksi, $_POST['pekerjaan_suami']);
        $no_telpon = mysqli_real_escape_string($koneksi, $_POST['no_telpon']);

        // Update data ke database
        $updateQuery = "UPDATE orang_tua SET 
            nama_ibu = '$nama_ibu', tempat_lahir_ibu = '$tempat_lahir_ibu', tanggal_lahir_ibu = '$tanggal_lahir_ibu', 
            golongan_darah_ibu = '$golongan_darah_ibu', pendidikan_ibu = '$pendidikan_ibu', pekerjaan_ibu = '$pekerjaan_ibu', 
            alamat = '$alamat', kota = '$kota', kecamatan = '$kecamatan', nama_suami = '$nama_suami', 
            tempat_lahir_suami = '$tempat_lahir_suami', tanggal_lahir_suami = '$tanggal_lahir_suami', 
            pendidikan_suami = '$pendidikan_suami', pekerjaan_suami = '$pekerjaan_suami', no_telpon = '$no_telpon' 
            WHERE no = '$no'";

        if (mysqli_query($koneksi, $updateQuery)) {
            // Redirect setelah update
            header("Location: kelola-orgtua.php?pesan=update");
            exit;
        } else {
            echo "Error updating record: " . mysqli_error($koneksi);
        }
    }
    ?>

    <!-- Page Wrapper -->
    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include '../../layout/topbar-admin.php'; ?>

                <div class="container-fluid">

                    <h1 class="h3 mb-4 text-gray-800">Ubah Data Orang Tua</h1>

                    <form method="post" action="">
                        <div class="form-row">
                            <!-- Data Ibu -->
                            <div class="form-col">
                                <h4>Data Ibu</h4>
                                <div class="form-group">
                                    <label for="nama_ibu">Nama Ibu:</label>
                                    <input type="text" id="nama_ibu" name="nama_ibu" value="<?php echo htmlspecialchars($data['nama_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir_ibu">Tempat Lahir Ibu:</label>
                                    <input type="text" id="tempat_lahir_ibu" name="tempat_lahir_ibu" value="<?php echo htmlspecialchars($data['tempat_lahir_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir_ibu">Tanggal Lahir Ibu:</label>
                                    <input type="date" id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" value="<?php echo htmlspecialchars($data['tanggal_lahir_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="golongan_darah_ibu">Golongan Darah Ibu:</label>
                                    <input type="text" id="golongan_darah_ibu" name="golongan_darah_ibu" value="<?php echo htmlspecialchars($data['golongan_darah_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_ibu">Pendidikan Ibu:</label>
                                    <input type="text" id="pendidikan_ibu" name="pendidikan_ibu" value="<?php echo htmlspecialchars($data['pendidikan_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_ibu">Pekerjaan Ibu:</label>
                                    <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" value="<?php echo htmlspecialchars($data['pekerjaan_ibu']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="alamat">Alamat:</label>
                                    <input type="text" id="alamat" name="alamat" value="<?php echo htmlspecialchars($data['alamat']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kota">Kota:</label>
                                    <input type="text" id="kota" name="kota" value="<?php echo htmlspecialchars($data['kota']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="kecamatan">Kecamatan:</label>
                                    <input type="text" id="kecamatan" name="kecamatan" value="<?php echo htmlspecialchars($data['kecamatan']); ?>" required>
                                </div>
                            </div>
                            <!-- Data Suami -->
                            <div class="form-col">
                                <h4>Data Suami</h4>
                                <div class="form-group">
                                    <label for="nama_suami">Nama Suami:</label>
                                    <input type="text" id="nama_suami" name="nama_suami" value="<?php echo htmlspecialchars($data['nama_suami']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tempat_lahir_suami">Tempat Lahir Suami:</label>
                                    <input type="text" id="tempat_lahir_suami" name="tempat_lahir_suami" value="<?php echo htmlspecialchars($data['tempat_lahir_suami']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_lahir_suami">Tanggal Lahir Suami:</label>
                                    <input type="date" id="tanggal_lahir_suami" name="tanggal_lahir_suami" value="<?php echo htmlspecialchars($data['tanggal_lahir_suami']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pendidikan_suami">Pendidikan Suami:</label>
                                    <input type="text" id="pendidikan_suami" name="pendidikan_suami" value="<?php echo htmlspecialchars($data['pendidikan_suami']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="pekerjaan_suami">Pekerjaan Suami:</label>
                                    <input type="text" id="pekerjaan_suami" name="pekerjaan_suami" value="<?php echo htmlspecialchars($data['pekerjaan_suami']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="no_telpon">No Telpon:</label>
                                    <input type="text" id="no_telpon" name="no_telpon" value="<?php echo htmlspecialchars($data['no_telpon']); ?>" required>
                                </div>
                            </div>
                        </div>

                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="kelola-orgtua.php" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <?php include '../../layout/footer.php'; ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->


</body>

</html>