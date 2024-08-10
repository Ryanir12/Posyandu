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

    // Handle anak_id filter if form is submitted
    $anak_id = isset($_GET['anak_id']) ? intval($_GET['anak_id']) : 0;

    // Query to fetch data for the selected anak
    $query_anak_detail = "SELECT nama_anak, tanggal_lahir, jenis_kelamin
                          FROM anak
                          WHERE id = ?";
    $stmt_anak_detail = $koneksi->prepare($query_anak_detail);

    if (!$stmt_anak_detail) {
        die("Error preparing statement: " . $koneksi->error);
    }

    $stmt_anak_detail->bind_param("i", $anak_id);
    $stmt_anak_detail->execute();
    $result_anak_detail = $stmt_anak_detail->get_result();

    if (!$result_anak_detail) {
        die("Query gagal: " . $koneksi->error);
    }

    $anak_detail = $result_anak_detail->fetch_assoc();

    // Query to fetch kelola_imunisasi data for the selected anak
    $query_imunisasi = "SELECT k.tanggal_imunisasi, k.usia_anak, k.jenis_imunisasi, k.vitamin, p.bb AS berat_badan, p.tb AS tinggi_badan
                        FROM kelola_imunisasi k
                        LEFT JOIN penimbangan p ON k.anak_id = p.id_anak
                        WHERE k.anak_id = ?";
    $stmt_imunisasi = $koneksi->prepare($query_imunisasi);

    if (!$stmt_imunisasi) {
        die("Error preparing statement: " . $koneksi->error);
    }

    $stmt_imunisasi->bind_param("i", $anak_id);
    $stmt_imunisasi->execute();
    $result_imunisasi = $stmt_imunisasi->get_result();

    if (!$result_imunisasi) {
        die("Query gagal: " . $koneksi->error);
    }
    ?>

    <?php include '../../layout/topbar-admin.php'; ?>

    <div class="container-fluid">
        <!-- Form filter anak -->
        <form method="get" class="mb-4">
            <div class="form-group">
                <label for="anak_id">Pilih Anak:</label>
                <select id="anak_id" name="anak_id" class="form-control" onchange="this.form.submit()">
                    <option value="">Nama Anak</option>
                    <?php
                    // Generate anak options dynamically
                    $query_anak = "SELECT id, nama_anak FROM anak";
                    $result_anak = $koneksi->query($query_anak);

                    if ($result_anak && $result_anak->num_rows > 0) {
                        while ($row = $result_anak->fetch_assoc()) {
                            $selected = ($row['id'] == $anak_id) ? 'selected' : '';
                            echo "<option value=\"" . htmlspecialchars($row['id']) . "\" $selected>" . htmlspecialchars($row['nama_anak']) . "</option>";
                        }
                    } else {
                        echo "<option value=\"\">Tidak ada data anak</option>";
                    }
                    ?>
                </select>
            </div>
        </form>

        <button class="btn btn-success btn-cetak" onclick="window.print()">Cetak Laporan</button>
        <br>
        <br>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Imunisasi Anak</h1>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <!-- Container khusus untuk pencetakan -->
                <div class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Panginggahan, Kec. Junjung SIrih</h2>
                    <hr>
                    <h5 class="text-center">Laporan Imunisasi Anak</h5>

                    <!-- Informasi Anak -->
                    <?php if ($anak_detail): ?>
                        <div>
                            <p>Nama Anak: <?php echo htmlspecialchars($anak_detail['nama_anak']); ?></p>
                            <p>Tanggal Lahir: <?php echo htmlspecialchars($anak_detail['tanggal_lahir']); ?></p>
                            <p>Jenis Kelamin: <?php echo htmlspecialchars($anak_detail['jenis_kelamin']); ?></p>
                        </div>
                        <hr>
                    <?php else: ?>
                        <p>Data anak tidak ditemukan.</p>
                    <?php endif; ?>

                    <div class="table-responsive-lg" style="overflow-x: auto;">
                        <table class="table table-hover table-bordered" id="Table">
                            <thead>
                                <tr>
                                    <th>Tanggal Imunisasi</th>
                                    <th>Usia Anak</th>
                                    <th>Jenis Imunisasi</th>
                                    <th>Vitamin</th>
                                    <th>Berat Badan (kg)</th>
                                    <th>Tinggi Badan (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_imunisasi->num_rows > 0) {
                                    while ($row = $result_imunisasi->fetch_assoc()) {
                                        echo "<tr>
                                                    <td>" . htmlspecialchars($row['tanggal_imunisasi']) . "</td>
                                                    <td>" . htmlspecialchars($row['usia_anak']) . "</td>
                                                    <td>" . htmlspecialchars($row['jenis_imunisasi']) . "</td>
                                                    <td>" . htmlspecialchars($row['vitamin']) . "</td>
                                                    <td>" . htmlspecialchars($row['berat_badan']) . "</td>
                                                    <td>" . htmlspecialchars($row['tinggi_badan']) . "</td>
                                                </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>Tidak ada data imunisasi tersedia</td></tr>";
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