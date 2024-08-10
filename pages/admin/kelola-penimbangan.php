<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
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

    if (!isset($_SESSION['hak_akses']) || $_SESSION['hak_akses'] == "") {
        header("location:../../index.php?pesan=gagal");
        exit;
    }

    include '../../koneksi.php';

    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Query to fetch penimbangan data
    $query_penimbangan = "SELECT p.id_penimbangan, p.tgl_timbangan, p.usia, p.bb, p.tb, p.deteksi, p.ket, 
        a.nama_anak, o.nama_ibu, b.nama_bidan 
        FROM penimbangan p 
        JOIN anak a ON p.id_anak = a.id 
        JOIN orang_tua o ON a.orang_tua_id = o.no 
        JOIN bidan b ON p.id_bidan = b.id_bidan";

    $result_penimbangan = $koneksi->query($query_penimbangan);

    if (!$result_penimbangan) {
        die("Query gagal: " . $koneksi->error);
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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Penimbangan</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-header">
                                    <a href="tambah-penimbangan.php" class="btn btn-sm btn-primary">Tambah Data</a>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                            <thead>
                                                <tr>
                                                    <th>ID Penimbangan</th>
                                                    <th>Nama Anak</th>
                                                    <th>Nama Ibu</th>
                                                    <th>Nama Bidan</th>
                                                    <th>Tanggal Penimbangan</th>
                                                    <th>Usia</th>
                                                    <th>Berat Badan (Kg)</th>
                                                    <th>Tinggi Badan (Cm)</th>
                                                    <th>Deteksi Pertumbuhan</th>
                                                    <th>Keterangan</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($row = $result_penimbangan->fetch_assoc()) { ?>
                                                    <tr>
                                                        <td><?php echo $row['id_penimbangan']; ?></td>
                                                        <td><?php echo $row['nama_anak']; ?></td>
                                                        <td><?php echo $row['nama_ibu']; ?></td>
                                                        <td><?php echo $row['nama_bidan']; ?></td>
                                                        <td><?php echo $row['tgl_timbangan']; ?></td>
                                                        <td><?php echo $row['usia']; ?></td>
                                                        <td><?php echo $row['bb']; ?></td>
                                                        <td><?php echo $row['tb']; ?></td>
                                                        <td><?php echo $row['deteksi']; ?></td>
                                                        <td><?php echo $row['ket']; ?></td>
                                                        <td>
                                                            <div class="btn-group">
                                                                <a href="ubah-penimbangan.php?id=<?php echo $row['id_penimbangan']; ?>" class="btn btn-success btn-sm">Edit</a>
                                                                <a href="hapus-penimbangan.php?id=<?php echo $row['id_penimbangan']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Anda yakin ingin menghapus data ini?');">Hapus</a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php } ?>
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
    <?php include '../../layout/js.php'; ?>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });
    </script>
</body>

</html>