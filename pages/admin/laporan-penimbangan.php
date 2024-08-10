<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        .btn-tambah,
        .btn-cetak {
            max-width: 150px;
            text-align: center;
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }

        .table {
            table-layout: auto;
            width: 100%;
            border-collapse: collapse;
        }

        .table td,
        .table th {
            border: 1px solid #000;
            padding: 5px;
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

        .print-btn {
            margin-bottom: 20px;
        }

        .print-container {
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .print-container,
            .print-container * {
                visibility: visible;
            }

            .btn-cetak {
                display: none;
            }
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

    // Handle year filter if form is submitted
    $year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

    // Query to fetch penimbangan data for the selected year
    $query_penimbangan = "SELECT p.id_penimbangan, p.tgl_timbangan, p.usia, p.bb, p.tb, p.deteksi, p.ket, 
        a.nama_anak, o.nama_ibu, b.nama_bidan 
        FROM penimbangan p 
        JOIN anak a ON p.id_anak = a.id 
        JOIN orang_tua o ON a.orang_tua_id = o.no 
        JOIN bidan b ON p.id_bidan = b.id_bidan
        WHERE YEAR(p.tgl_timbangan) = ?";

    $stmt = $koneksi->prepare($query_penimbangan);

    if (!$stmt) {
        die("Error preparing statement: " . $koneksi->error);
    }

    $stmt->bind_param("i", $year);
    $stmt->execute();
    $result_penimbangan = $stmt->get_result();

    if (!$result_penimbangan) {
        die("Query gagal: " . $koneksi->error);
    }
    ?>

    <?php include '../../layout/topbar-admin.php'; ?>

    <div class="container-fluid">
        <!-- Form filter tahun -->
        <form method="get" class="mb-4">
            <div class="form-group">
                <label for="year">Pilih Tahun:</label>
                <select id="year" name="year" class="form-control" onchange="this.form.submit()">
                    <?php
                    // Generate year options dynamically
                    $currentYear = date('Y');
                    for ($i = $currentYear; $i >= 2000; $i--) {
                        $selected = ($i == $year) ? 'selected' : '';
                        echo "<option value=\"$i\" $selected>$i</option>";
                    }
                    ?>
                </select>
            </div>
        </form>

        <button class="btn btn-success btn-cetak" onclick="window.print()">Cetak Laporan</button>
        <br>
        <br>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Penimbangan</h1>
        </div>

        <div class="card mb-5">

            <div class="card-body">

                <!-- Container khusus untuk pencetakan -->
                <div class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Panginggahan, Kec. Junjung SIrih</h2>
                    <hr>
                    <h5 class="text-center">Laporan Penimbangan Tahun <?php echo htmlspecialchars($year); ?></h5>

                    <div class="table-responsive-lg" style="overflow-x: auto;">

                        <table class="table table-hover table-bordered" id="Table">
                            <thead>
                                <tr>
                                    <th>ID Penimbangan</th>
                                    <th>Tanggal Penimbangan</th>
                                    <th>Nama Anak</th>
                                    <th>Nama Ibu</th>
                                    <th>Nama Bidan</th>
                                    <th>Usia Anak</th>
                                    <th>Berat Badan (Kg)</th>
                                    <th>Tinggi Badan (Cm)</th>
                                    <th>Deteksi Pertumbuhan</th>
                                    <th>Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_penimbangan->num_rows > 0) {
                                    while ($row = $result_penimbangan->fetch_assoc()) {
                                        echo "<tr>
                                                        <td>" . htmlspecialchars($row['id_penimbangan']) . "</td>
                                                        <td>" . htmlspecialchars($row['tgl_timbangan']) . "</td>
                                                        <td>" . htmlspecialchars($row['nama_anak']) . "</td>
                                                        <td>" . htmlspecialchars($row['nama_ibu']) . "</td>
                                                        <td>" . htmlspecialchars($row['nama_bidan']) . "</td>
                                                        <td>" . htmlspecialchars($row['usia']) . "</td>
                                                        <td>" . htmlspecialchars($row['bb']) . "</td>
                                                        <td>" . htmlspecialchars($row['tb']) . "</td>
                                                        <td>" . htmlspecialchars($row['deteksi']) . "</td>
                                                        <td>" . htmlspecialchars($row['ket']) . "</td>
                                                    </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>Tidak ada data tersedia</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Akhir dari container khusus untuk pencetakan -->
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