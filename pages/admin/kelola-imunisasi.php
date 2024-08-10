<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        .btn-tambah {
            max-width: 150px;
            text-align: center;
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

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Kelola Imunisasi</h1>
                    </div>

                    <div class="card mb-5">
                        <div class="card-header">
                            <a href="tambah-imunisasi.php" class="btn btn-sm btn-primary btn-tambah">Tambah Data</a>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive-lg" style="overflow-x: auto;">
                                <table class="table table-hover table-bordered" id="Table">
                                    <thead>
                                        <tr>
                                            <th>ID Imunisasi</th>
                                            <th>Nama Anak</th>
                                            <th>Nama Ibu</th>
                                            <th>Nama Bidan</th>
                                            <th>Nama Petugas</th>
                                            <th>Tanggal Imunisasi</th>
                                            <th>Usia Anak</th>
                                            <th>Jenis Imunisasi</th>
                                            <th>Vitamin</th>
                                            <th>Keterangan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Query untuk menggabungkan tabel kelola_imunisasi, anak, orang_tua, dan bidan
                                        $query = "SELECT k.id, a.nama_anak, o.nama_ibu, b.nama_bidan, k.petugas as nama_petugas, k.tanggal_imunisasi, k.usia_anak, k.jenis_imunisasi, k.vitamin, k.keterangan
                                                  FROM kelola_imunisasi k
                                                  JOIN anak a ON k.anak_id = a.id
                                                  JOIN orang_tua o ON k.orang_tua_no = o.no
                                                  JOIN bidan b ON k.bidan_id = b.id_bidan";

                                        if ($result = $koneksi->query($query)) {
                                            while ($row = $result->fetch_assoc()) {
                                                $id = htmlspecialchars($row['id']);
                                                $nama_anak = htmlspecialchars($row['nama_anak']);
                                                $nama_ibu = htmlspecialchars($row['nama_ibu']);
                                                $nama_bidan = htmlspecialchars($row['nama_bidan']);
                                                $nama_petugas = htmlspecialchars($row['nama_petugas']);
                                                $tanggal_imunisasi = htmlspecialchars($row['tanggal_imunisasi']);
                                                $usia_anak = htmlspecialchars($row['usia_anak']);
                                                $jenis_imunisasi = htmlspecialchars($row['jenis_imunisasi']);
                                                $vitamin = htmlspecialchars($row['vitamin']);
                                                $keterangan = htmlspecialchars($row['keterangan']);

                                                echo "<tr>
                                                    <td>{$id}</td>
                                                    <td>{$nama_anak}</td>
                                                    <td>{$nama_ibu}</td>
                                                    <td>{$nama_bidan}</td>
                                                    <td>{$nama_petugas}</td>
                                                    <td>{$tanggal_imunisasi}</td>
                                                    <td>{$usia_anak}</td>
                                                    <td>{$jenis_imunisasi}</td>
                                                    <td>{$vitamin}</td>
                                                    <td>{$keterangan}</td>
                                                    <td>
                                                        <div class='btn-group'>
                                                            <a href='ubah-imunisasi.php?id={$id}' class='btn btn-warning btn-sm'>Edit</a>
                                                            <a href='hapus-imunisasi.php?id={$id}' class='btn btn-danger btn-sm' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Delete</a>
                                                        </div>
                                                    </td>
                                                </tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='11'>Tidak ada data tersedia</td></tr>";
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
    <script src="../../path/to/your/js/script.js"></script>
</body>

</html>