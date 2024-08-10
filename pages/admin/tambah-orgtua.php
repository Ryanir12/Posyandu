<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css"> <!-- Pastikan link CSS benar -->
    <style>
        /* Styling untuk form */
        .form-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f9f9f9;
        }

        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .form-group label {
            flex: 0 0 200px;
            margin-bottom: 5px;
            text-align: right;
            margin-right: 10px;
        }

        .form-group input,
        .form-group select {
            flex: 1;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-group button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        .form-group button:hover {
            background-color: #0056b3;
        }

        .form-row {
            display: flex;
            justify-content: space-between;
            gap: 20px;
        }

        .form-col {
            flex: 1;
        }

        .form-actions {
            margin-top: 20px;
            display: flex;
            gap: 10px;
        }

        .btn {
            padding: .375rem .75rem;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            border-radius: .25rem;
            display: inline-block;
            text-align: center;
            text-decoration: none;
            vertical-align: middle;
        }

        .btn-primary {
            color: #fff;
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border-color: #6c757d;
        }
    </style>
</head>

<body id="page-top">

    <?php
    session_start();

    if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
        header("location:../../index.php?pesan=gagal");
        exit;
    }

    include '../../koneksi.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $nama_ibu = $_POST['nama_ibu'];
        $tempat_lahir_ibu = $_POST['tempat_lahir_ibu'];
        $tanggal_lahir_ibu = $_POST['tanggal_lahir_ibu'];
        $golongan_darah_ibu = $_POST['golongan_darah_ibu'];
        $pendidikan_ibu = $_POST['pendidikan_ibu'];
        $pekerjaan_ibu = $_POST['pekerjaan_ibu'];
        $alamat = $_POST['alamat'];
        $kota = $_POST['kota'];
        $kecamatan = $_POST['kecamatan'];
        $nama_suami = $_POST['nama_suami'];
        $tempat_lahir_suami = $_POST['tempat_lahir_suami'];
        $tanggal_lahir_suami = $_POST['tanggal_lahir_suami'];
        $pendidikan_suami = $_POST['pendidikan_suami'];
        $pekerjaan_suami = $_POST['pekerjaan_suami'];
        $no_telpon = $_POST['no_telpon'];

        // Query untuk menambahkan data orang tua
        $query = "INSERT INTO orang_tua (nama_ibu, tempat_lahir_ibu, tanggal_lahir_ibu, golongan_darah_ibu, pendidikan_ibu, pekerjaan_ibu, alamat, kota, kecamatan, nama_suami, tempat_lahir_suami, tanggal_lahir_suami, pendidikan_suami, pekerjaan_suami, no_telpon) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $koneksi->prepare($query);
        if ($stmt === false) {
            die("Error preparing statement: " . $koneksi->error);
        }

        $stmt->bind_param('sssssssssssssss', $nama_ibu, $tempat_lahir_ibu, $tanggal_lahir_ibu, $golongan_darah_ibu, $pendidikan_ibu, $pekerjaan_ibu, $alamat, $kota, $kecamatan, $nama_suami, $tempat_lahir_suami, $tanggal_lahir_suami, $pendidikan_suami, $pekerjaan_suami, $no_telpon);

        if ($stmt->execute()) {
            header("Location: kelola-orgtua.php"); // Redirect ke kelola-orgtua.php setelah berhasil
            exit;
        } else {
            echo "<div class='alert alert-danger'>Gagal menambahkan data: " . $stmt->error . "</div>";
        }
    }
    ?>

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar-admin.php'; ?>

                <div class="container-fluid">
                    <div class="form-container">
                        <h2>Tambah Data Orang Tua</h2>
                        <form id="dataForm" method="post" action="">
                            <div class="form-row">
                                <div class="form-col">
                                    <h4>Data Ibu</h4>
                                    <!-- Form Group Ibu -->
                                    <div class="form-group">
                                        <label for="nama_ibu">Nama Ibu:</label>
                                        <input type="text" id="nama_ibu" name="nama_ibu" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tempat_lahir_ibu">Tempat Lahir Ibu:</label>
                                        <input type="text" id="tempat_lahir_ibu" name="tempat_lahir_ibu" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_lahir_ibu">Tanggal Lahir Ibu:</label>
                                        <input type="date" id="tanggal_lahir_ibu" name="tanggal_lahir_ibu" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="golongan_darah_ibu">Golongan Darah Ibu:</label>
                                        <input type="text" id="golongan_darah_ibu" name="golongan_darah_ibu" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pendidikan_ibu">Pendidikan Ibu:</label>
                                        <input type="text" id="pendidikan_ibu" name="pendidikan_ibu" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pekerjaan_ibu">Pekerjaan Ibu:</label>
                                        <input type="text" id="pekerjaan_ibu" name="pekerjaan_ibu" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Alamat:</label>
                                        <input type="text" id="alamat" name="alamat" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kota">Kota:</label>
                                        <input type="text" id="kota" name="kota" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kecamatan">Kecamatan:</label>
                                        <input type="text" id="kecamatan" name="kecamatan" required>
                                    </div>
                                </div>
                                <div class="form-col">
                                    <h4>Data Suami</h4>
                                    <!-- Form Group Suami -->
                                    <div class="form-group">
                                        <label for="nama_suami">Nama Suami:</label>
                                        <input type="text" id="nama_suami" name="nama_suami" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tempat_lahir_suami">Tempat Lahir Suami:</label>
                                        <input type="text" id="tempat_lahir_suami" name="tempat_lahir_suami" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_lahir_suami">Tanggal Lahir Suami:</label>
                                        <input type="date" id="tanggal_lahir_suami" name="tanggal_lahir_suami" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pendidikan_suami">Pendidikan Suami:</label>
                                        <input type="text" id="pendidikan_suami" name="pendidikan_suami" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="pekerjaan_suami">Pekerjaan Suami:</label>
                                        <input type="text" id="pekerjaan_suami" name="pekerjaan_suami" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="no_telpon">No Telpon:</label>
                                        <input type="text" id="no_telpon" name="no_telpon" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="kelola-orgtua.php" class="btn btn-secondary">Kembali</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="../../logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../layout/footer.php'; ?>
</body>

</html>