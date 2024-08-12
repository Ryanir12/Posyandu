<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: bold;
        }

        .form-control {
            display: block;
            width: 100%;
            height: calc(1.5em + .75rem + 2px);
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
        }

        .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .form-row label {
            flex: 0 0 200px;
            max-width: 200px;
        }

        .form-row input {
            flex: 1;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-group .btn {
            max-width: 150px;
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

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Query untuk mengambil data bidan
        $query = "SELECT * FROM bidan WHERE id_bidan = '$id'";
        $result = $koneksi->query($query);

        if (!$result) {
            die("Query gagal: " . $koneksi->error);
        }

        $bidan = $result->fetch_assoc();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $nama_bidan = $_POST['nama_bidan'];
        $tempat_lahir = $_POST['tempat_lahir'];
        $tgl_lahir = $_POST['tgl_lahir']; // Diubah dari tgl_lahir_anak
        $no_hp = $_POST['no_hp'];
        $pendidikan_terakhir = $_POST['pendidikan_terakhir'];

        // Query untuk memperbarui data
        $query = "UPDATE bidan SET 
                  nama_bidan = '$nama_bidan', 
                  tempat_lahir = '$tempat_lahir',
                  tgl_lahir = '$tgl_lahir', 
                  no_hp = '$no_hp',
                  pendidikan_terakhir = '$pendidikan_terakhir'
                  WHERE id_bidan = '$id'";

        if ($koneksi->query($query) === TRUE) {
            echo "<script>alert('Data bidan berhasil diperbarui.'); window.location.href='kelola-bidan.php';</script>";
        } else {
            echo "Error: " . $query . "<br>" . $koneksi->error;
        }
    }
    ?>

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar-admin.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ubah Bidan</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="container row-12">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-header">
                                    <div class="nav-item">
                                        <a href="kelola-bidan.php" class="btn btn-sm btn-secondary">Kembali</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form method="POST" action="">
                                        <div class="form-row">
                                            <label for="nama_bidan" class="form-label">Nama Bidan:</label>
                                            <input type="text" id="nama_bidan" name="nama_bidan" class="form-control" value="<?php echo htmlspecialchars($bidan['nama_bidan']); ?>" required>
                                        </div>
                                        <div class="form-row">
                                            <label for="tempat_lahir" class="form-label">Tempat Lahir:</label>
                                            <input type="text" id="tempat_lahir" name="tempat_lahir" class="form-control" value="<?php echo htmlspecialchars($bidan['tempat_lahir']); ?>" required>
                                        </div>
                                        <div class="form-row">
                                            <label for="tgl_lahir" class="form-label">Tanggal Lahir:</label>
                                            <input type="date" id="tgl_lahir" name="tgl_lahir" class="form-control" value="<?php echo htmlspecialchars($bidan['tgl_lahir']); ?>" required>
                                        </div>
                                        <div class="form-row">
                                            <label for="no_hp" class="form-label">No HP:</label>
                                            <input type="text" id="no_hp" name="no_hp" class="form-control" value="<?php echo htmlspecialchars($bidan['no_hp']); ?>" required>
                                        </div>
                                        <div class="form-row">
                                            <label for="pendidikan_terakhir" class="form-label">Pendidikan Terakhir:</label>
                                            <input type="text" id="pendidikan_terakhir" name="pendidikan_terakhir" class="form-control" value="<?php echo htmlspecialchars($bidan['pendidikan_terakhir']); ?>" required>
                                        </div>
                                        <div class="btn-group">
                                            <button type="submit" class="btn btn-primary">Simpan</button>
                                            <a href="kelola-bidan.php" class="btn btn-secondary">Kembali</a>
                                        </div>
                                    </form>
                                </div>
                            </div>
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