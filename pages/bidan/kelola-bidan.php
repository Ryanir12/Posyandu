<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        .btn-tambah {
            max-width: 150px;
            /* Atur lebar maksimum tombol */
            text-align: center;
            /* Pusatkan teks */
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .table {
            table-layout: auto;
        }

        .table td,
        .table th {
            white-space: nowrap;
        }

        .btn-group {
            display: flex;
            gap: 5px;
        }

        /* Styling tambahan untuk DataTables */
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.5em 1em;
        }

        .dataTables_wrapper .dataTables_info {
            margin-top: 10px;
        }

        .dataTables_wrapper .dataTables_filter input {
            margin-bottom: 10px;
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
    ?>

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar-admin.php'; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Bidan</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="container row-12">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-header">
                                    <div class="nav-item">
                                        <a href="tambah-bidan.php" class="btn btn-sm btn-primary">Tambah Data</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive-lg" style="overflow-x: auto;">
                                        <table class="table table-responsive-lg table-hover table-bordered" id="Table">
                                            <thead>
                                                <tr>
                                                    <th>ID Bidan</th>
                                                    <th>Nama Bidan</th>
                                                    <th>Tempat Lahir</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>No HP</th>
                                                    <th>Pendidikan Terakhir</th>
                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "SELECT * FROM bidan";
                                                $result = $koneksi->query($query);

                                                if (!$result) {
                                                    die("Query gagal: " . $koneksi->error);
                                                }

                                                while ($row = $result->fetch_assoc()) {
                                                    $id = isset($row['id_bidan']) ? $row['id_bidan'] : '';

                                                    echo "<tr>
                                                        <td>{$id}</td>
                                                        <td>{$row['nama_bidan']}</td>
                                                        <td>{$row['tempat_lahir']}</td>
                                                        <td>{$row['tgl_lahir']}</td>
                                                        <td>{$row['no_hp']}</td>
                                                        <td>{$row['pendidikan_terakhir']}</td>
                                                        <td>
                                                            <div class='btn-group'>
                                                                <a href='ubah-bidan.php?id={$id}' class='btn btn-success btn-sm'>Edit</a>
                                                                <a href='hapus-bidan.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>
                                                            </div>
                                                        </td>
                                                    </tr>";
                                                }
                                                ?>
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
    <script src="../../path/to/datatables.min.js"></script>
    <?php include '../../layout/js.php' ?>
    <script>
        $(document).ready(function() {
            $('#Table').DataTable();
        });
    </script>
</body>

</html>