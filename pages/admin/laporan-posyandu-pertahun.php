<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
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

    // Mendapatkan tahun dari parameter GET atau default ke tahun saat ini
    $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');

    // Query untuk mengambil data berdasarkan tahun
    $query_imunisasi = "SELECT a.nama_anak, k.tanggal_imunisasi, k.usia_anak, k.jenis_imunisasi, k.vitamin, p.bb AS berat_badan, p.tb AS tinggi_badan
                        FROM kelola_imunisasi k
                        LEFT JOIN anak a ON k.anak_id = a.id
                        LEFT JOIN penimbangan p ON k.anak_id = p.id_anak
                        WHERE YEAR(k.tanggal_imunisasi) = ?
                        ORDER BY k.tanggal_imunisasi";
    $stmt_imunisasi = $koneksi->prepare($query_imunisasi);

    if (!$stmt_imunisasi) {
        die("Error preparing statement: " . $koneksi->error);
    }

    $stmt_imunisasi->bind_param("i", $tahun);
    $stmt_imunisasi->execute();
    $result_imunisasi = $stmt_imunisasi->get_result();

    if (!$result_imunisasi) {
        die("Query gagal: " . $koneksi->error);
    }
    ?>

    <?php include '../../layout/topbar-admin.php'; ?>

    <div class="container-fluid">
        <!-- Form filter tahun -->
        <form method="get" class="mb-4">
            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <input type="number" id="tahun" name="tahun" class="form-control" value="<?php echo $tahun; ?>" min="2000" max="<?php echo date('Y'); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>

        <button class="btn btn-success btn-cetak" onclick="window.print()">Cetak Laporan</button>
        <br>
        <br>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Imunisasi Anak Tahun <?php echo htmlspecialchars($tahun); ?></h1>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <!-- Container khusus untuk pencetakan -->
                <div class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Panginggahan, Kec. Junjung SIrih</h2>
                    <hr>
                    <h5 class="text-center">Laporan Imunisasi Anak Tahun <?php echo htmlspecialchars($tahun); ?></h5>

                    <div class="table-responsive-lg" style="overflow-x: auto;">
                        <table class="table table-hover table-bordered" id="Table">
                            <thead>
                                <tr>
                                    <th>Nama Anak</th>
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
                                                    <td>" . htmlspecialchars($row['nama_anak']) . "</td>
                                                    <td>" . htmlspecialchars($row['tanggal_imunisasi']) . "</td>
                                                    <td>" . htmlspecialchars($row['usia_anak']) . "</td>
                                                    <td>" . htmlspecialchars($row['jenis_imunisasi']) . "</td>
                                                    <td>" . htmlspecialchars($row['vitamin']) . "</td>
                                                    <td>" . htmlspecialchars($row['berat_badan']) . "</td>
                                                    <td>" . htmlspecialchars($row['tinggi_badan']) . "</td>
                                                </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Tidak ada data imunisasi tersedia untuk tahun ini</td></tr>";
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
</body>

</html>