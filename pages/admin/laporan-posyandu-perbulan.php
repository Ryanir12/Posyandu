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

        .info-container {
            display: flex;
            justify-content: flex-start;
            gap: 10px;
            /* Kurangi jarak antar kolom */
            margin-bottom: 20px;
        }

        .info-group {
            flex-grow: 1;
            flex-basis: 10%;
            /* Atur lebar setiap kolom */
            text-align: left;
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

            .info-container {
                display: flex;
                justify-content: flex-start;
                gap: 10px;
                /* Kurangi jarak antar kolom */
                margin-bottom: 20px;
            }

            .info-group {
                flex-grow: 1;
                flex-basis: 10%;
                /* Atur lebar setiap kolom */
                text-align: left;
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

    // Mendapatkan bulan dan tahun dari parameter GET atau default ke bulan dan tahun saat ini
    $bulan = isset($_GET['bulan']) ? intval($_GET['bulan']) : date('m');
    $tahun = isset($_GET['tahun']) ? intval($_GET['tahun']) : date('Y');

    // Query untuk mengambil data berdasarkan bulan dan tahun
    $query_imunisasi = "
    SELECT a.nama_anak, o.nama_ibu, a.tanggal_lahir, a.jenis_kelamin, k.tanggal_imunisasi, k.usia_anak, k.jenis_imunisasi, k.vitamin, p.bb AS berat_badan, p.tb AS tinggi_badan
    FROM kelola_imunisasi k
    LEFT JOIN anak a ON k.anak_id = a.id
    LEFT JOIN orang_tua o ON a.id_ibu = o.no
    LEFT JOIN penimbangan p ON k.anak_id = p.id_anak
    WHERE MONTH(k.tanggal_imunisasi) = ? AND YEAR(k.tanggal_imunisasi) = ?
    ORDER BY k.tanggal_imunisasi
";

    $stmt_imunisasi = $koneksi->prepare($query_imunisasi);

    if (!$stmt_imunisasi) {
        die("Error preparing statement: " . $koneksi->error);
    }

    $stmt_imunisasi->bind_param("ii", $bulan, $tahun);
    $stmt_imunisasi->execute();
    $result_imunisasi = $stmt_imunisasi->get_result();

    if (!$result_imunisasi) {
        die("Query gagal: " . $koneksi->error);
    }
    ?>


    <?php include '../../layout/topbar-admin.php'; ?>

    <div class="container-fluid">
        <!-- Form filter bulan dan tahun -->
        <form method="get" class="mb-4">
            <div class="form-group">
                <label for="bulan">Bulan:</label>
                <select id="bulan" name="bulan" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?php echo $i; ?>" <?php echo $i == $bulan ? 'selected' : ''; ?>>
                            <?php echo date('F', mktime(0, 0, 0, $i, 1)); ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
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
            <h1 class="h3 mb-0 text-gray-800">Laporan Imunisasi Anak Bulan <?php echo date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)); ?></h1>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <!-- Container khusus untuk pencetakan -->
                <div class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Panginggahan, Kec. Junjung SIrih</h2>
                    <hr>
                    <h5 class="text-center">Laporan Imunisasi Anak Bulan <?php echo date('F Y', mktime(0, 0, 0, $bulan, 1, $tahun)); ?></h5>
                    <br>
                    <br>





                    <div class="table-responsive-lg" style="overflow-x: auto;">
                        <table class="table table-hover table-bordered" id="Table">
                            <thead>
                                <tr>
                                    <th rowspan="2">No</th>
                                    <th rowspan="2">Nama Anak</th>
                                    <th rowspan="2">Nama Ibu</th>

                                    <th rowspan="2">Jenis Kelamin</th>
                                    <th rowspan="2">Tanggal Lahir</th>
                                    <th rowspan="2">Umur (Bulan)</th>
                                    <th colspan="4" class="text-center">Hasil Periksa</th>
                                </tr>
                                <tr>
                                    <th>Hasil Posyandu</th>
                                    <th>Vitamin</th>
                                    <th>Berat Badan (kg)</th>
                                    <th>Tinggi Badan (cm)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1; // Untuk nomor urut
                                if ($result_imunisasi->num_rows > 0) {
                                    while ($row = $result_imunisasi->fetch_assoc()) {
                                        echo "<tr>
                        <td>" . $no++ . "</td>
                        <td>" . htmlspecialchars($row['nama_anak']) . "</td>
                        <td>" . htmlspecialchars($row['nama_ibu']) . "</td>
                        
                        <td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>
                        <td>" . htmlspecialchars($row['tanggal_lahir']) . "</td>
                        <td>" . htmlspecialchars($row['usia_anak']) . "</td>
                        <td>" . htmlspecialchars($row['jenis_imunisasi']) . "</td>
                        <td>" . htmlspecialchars($row['vitamin']) . "</td>
                        <td>" . htmlspecialchars($row['berat_badan']) . "</td>
                        <td>" . htmlspecialchars($row['tinggi_badan']) . "</td>
                    </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>Tidak ada data imunisasi tersedia untuk bulan ini</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                        <?php
                        $query_count = "SELECT
    SUM(CASE WHEN a.jenis_kelamin = 'Laki-laki' THEN 1 ELSE 0 END) AS jumlah_laki,
    SUM(CASE WHEN a.jenis_kelamin = 'Perempuan' THEN 1 ELSE 0 END) AS jumlah_perempuan,
    SUM(CASE WHEN k.vitamin = 'Merah' THEN 1 ELSE 0 END) AS jumlah_merah,
    SUM(CASE WHEN k.vitamin = 'Biru' THEN 1 ELSE 0 END) AS jumlah_biru,
    SUM(CASE WHEN k.jenis_imunisasi = 'HB' THEN 1 ELSE 0 END) AS jumlah_hb,
    SUM(CASE WHEN k.jenis_imunisasi = 'DPT' THEN 1 ELSE 0 END) AS jumlah_dpt,
    SUM(CASE WHEN k.jenis_imunisasi = 'Campak' THEN 1 ELSE 0 END) AS jumlah_campak,
    SUM(CASE WHEN k.jenis_imunisasi = 'Polio' THEN 1 ELSE 0 END) AS jumlah_polio,
    SUM(CASE WHEN k.jenis_imunisasi = 'BCG' THEN 1 ELSE 0 END) AS jumlah_bcg
    FROM kelola_imunisasi k
    LEFT JOIN anak a ON k.anak_id = a.id
    WHERE MONTH(k.tanggal_imunisasi) = ? AND YEAR(k.tanggal_imunisasi) = ?";

                        $stmt_count = $koneksi->prepare($query_count);
                        if (!$stmt_count) {
                            die("Error preparing statement: " . $koneksi->error);
                        }

                        $stmt_count->bind_param("ii", $bulan, $tahun);
                        $stmt_count->execute();
                        $result_count = $stmt_count->get_result();
                        $data_count = $result_count->fetch_assoc();

                        // Menampilkan informasi di atas tabel
                        echo "<div class='info-container'>";
                        echo "<div class='info-group'>";
                        echo "<p><strong>Jenis Kelamin:</strong></p>";
                        echo "<p>Laki-laki: " . htmlspecialchars($data_count['jumlah_laki']) . "</p>";
                        echo "<p>Perempuan: " . htmlspecialchars($data_count['jumlah_perempuan']) . "</p>";
                        echo "</div>";
                        echo "<div class='info-group'>";
                        echo "<p><strong>Vitamin:</strong></p>";
                        echo "<p>Merah: " . htmlspecialchars($data_count['jumlah_merah']) . "</p>";
                        echo "<p>Biru: " . htmlspecialchars($data_count['jumlah_biru']) . "</p>";
                        echo "</div>";
                        echo "<div class='info-group'>";
                        echo "<p><strong>Jenis Imunisasi:</strong></p>";
                        echo "<p>HB: " . htmlspecialchars($data_count['jumlah_hb']) . "</p>";
                        echo "<p>DPT: " . htmlspecialchars($data_count['jumlah_dpt']) . "</p>";
                        echo "<p>Campak: " . htmlspecialchars($data_count['jumlah_campak']) . "</p>";
                        echo "<p>Polio: " . htmlspecialchars($data_count['jumlah_polio']) . "</p>";
                        echo "<p>BCG: " . htmlspecialchars($data_count['jumlah_bcg']) . "</p>";
                        echo "</div>";
                        echo "</div>";
                        ?>
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