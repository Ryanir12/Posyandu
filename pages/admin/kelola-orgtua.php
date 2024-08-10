<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem Informasi Posyandu | Login</title>

    <!-- Custom fonts for this template -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css"> <!-- Pastikan link CSS benar -->
    <style>
        .table-responsive {
            margin: 1rem 0;
        }

        .btn-group {
            display: flex;
            gap: 5px;
        }
    </style>
</head>

<body id="page-top">

    <?php
    session_start();

    // Cek apakah yang mengakses halaman ini sudah login
    if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
        header("location:../../index.php?pesan=gagal");
        exit; // Hentikan eksekusi script setelah redirect
    }

    include '../../koneksi.php';

    // Query untuk mendapatkan data orang tua
    $query = "SELECT * FROM orang_tua";
    $result = $koneksi->query($query);
    ?>

    <!-- Page Wrapper -->
    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar-admin.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Orang Tua</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-header">
                                    <div class="nav-item">
                                        <a href="tambah-orgtua.php" class="btn btn-sm btn-primary">Tambah Data</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Nama Ibu</th>
                                                    <th>Tempat Lahir Ibu</th>
                                                    <th>Tanggal Lahir Ibu</th>
                                                    <th>Golongan Darah Ibu</th>
                                                    <th>Nama Suami</th>
                                                    <th>No. Telpon</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result->fetch_assoc()) {
                                                    $no = $row['no']; // Menggunakan 'no' sebagai identifikasi

                                                    echo "<tr>
                                        <td>{$no}</td>
                                        <td>{$row['nama_ibu']}</td>
                                        <td>{$row['tempat_lahir_ibu']}</td>
                                        <td>{$row['tanggal_lahir_ibu']}</td>
                                        <td>{$row['golongan_darah_ibu']}</td>
                                        <td>{$row['nama_suami']}</td>
                                        <td>{$row['no_telpon']}</td>
                                        <td class='table-action-buttons'>
                                            <a href='ubah-orgtua.php?no={$no}' class='btn btn-success btn-sm'>Edit</a>
                                            <a href='hapus-orgtua.php?no={$no}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>
                                        </td>
                                    </tr>";
                                                } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <?php include '../../layout/footer.php'; ?>
    <!-- End of Footer -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

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

    <?php include '../../layout/js.php'; ?>
    <?php include '../../layout/js.php' ?>
    <script>
        $(document).ready(function() {
            $('#Table').DataTable();
        });
    </script>
</body>

</html>