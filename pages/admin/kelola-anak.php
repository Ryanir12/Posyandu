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
                        <h1 class="h3 mb-0 text-gray-800">Kelola Anak</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-header">
                                    <div class="nav-item">
                                        <a href="tambah-anak.php" class="btn btn-sm btn-primary">Tambah Data</a>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive-lg" style="overflow-x: auto;">
                                        <table class="table table-responsive-lg table-hover table-bordered" id="Table">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Nama Anak</th>
                                                    <th>Tempat Lahir</th>
                                                    <th>Tanggal Lahir</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Golongan Darah</th>
                                                    <th>Nama Ibu</th>
                                                    <th>Nama Ayah</th>
                                                    <th>Alamat</th>

                                                    <th>Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $query = "
                                    SELECT a.id, a.nama_anak, a.tempat_lahir, a.tanggal_lahir, a.jenis_kelamin, a.golongan_darah, 
                                           o.nama_ibu, o.nama_suami AS nama_ayah, o.alamat, o.no_telpon
                                    FROM anak a
                                    LEFT JOIN orang_tua o ON a.orang_tua_id = o.no
                                ";

                                                $result = $koneksi->query($query);

                                                if (!$result) {
                                                    die("Query gagal: " . $koneksi->error);
                                                }

                                                while ($row = $result->fetch_assoc()) {
                                                    $id = isset($row['id']) ? $row['id'] : '';

                                                    echo "<tr>
                                        <td>{$id}</td>
                                        <td>{$row['nama_anak']}</td>
                                        <td>{$row['tempat_lahir']}</td>
                                        <td>{$row['tanggal_lahir']}</td>
                                        <td>{$row['jenis_kelamin']}</td>
                                        <td>{$row['golongan_darah']}</td>
                                        <td>{$row['nama_ibu']}</td>
                                        <td>{$row['nama_ayah']}</td>
                                        <td>{$row['alamat']}</td>
                                       
                                        <td>
                                            <div class='btn-group'>
                                                <a href='ubah-anak.php?id={$id}' class='btn btn-success btn-sm'>Edit</a>
                                                <a href='hapus-anak.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>
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
            <?php include '../../layout/js.php' ?>
            <script>
                $(document).ready(function() {
                    $('#Table').DataTable();
                });
            </script>
</body>

</html>