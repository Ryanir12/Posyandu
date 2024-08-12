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
$query_count = "
SELECT
    MONTH(k.tanggal_imunisasi) AS bulan,
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
WHERE YEAR(k.tanggal_imunisasi) = ?
GROUP BY bulan
";

$stmt_count = $koneksi->prepare($query_count);
$stmt_count->bind_param("i", $tahun);
$stmt_count->execute();
$result_count = $stmt_count->get_result();

$totals = [
    'jumlah_laki' => 0,
    'jumlah_perempuan' => 0,
    'jumlah_merah' => 0,
    'jumlah_biru' => 0,
    'jumlah_hb' => 0,
    'jumlah_polio' => 0,
    'jumlah_bcg' => 0,
    'jumlah_dpt' => 0,
    'jumlah_campak' => 0
];

?>

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
            text-align: center;
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
    <?php include '../../layout/topbar-admin.php'; ?>
    <div class="container-fluid">
        <form method="get" class="mb-4">
            <div class="form-group">
                <label for="tahun">Tahun:</label>
                <input type="number" id="tahun" name="tahun" class="form-control" value="<?php echo htmlspecialchars($tahun); ?>" min="2000" max="<?php echo date('Y'); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
        <button class="btn btn-success btn-cetak" onclick="window.print()">Cetak Laporan</button>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Imunisasi Anak <?php echo htmlspecialchars($tahun); ?></h1>
        </div>
        <div class="card mb-5">
            <div class="card-body">
                <div class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Panginggahan, Kec. Junjung SIrih</h2>
                    <hr>
                    <h5 class="text-center">Rekapitulasi Data Pelaksanaan Posyandu Tahun <?php echo htmlspecialchars($tahun); ?></h5>
                    <div class="table-responsive-lg" style="overflow-x: auto;">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Bulan</th>
                                    <th colspan="2">Jumlah Anak</th>
                                    <th colspan="5">Imunisasi</th>
                                    <th colspan="2">Vitamin</th>
                                </tr>
                                <tr>
                                    <th></th>
                                    <th></th>
                                    <th>Laki-laki</th>
                                    <th>Perempuan</th>
                                    <th>HB</th>
                                    <th>Polio</th>
                                    <th>BCG</th>
                                    <th>DPT</th>
                                    <th>Campak</th>
                                    <th>Merah</th>
                                    <th>Biru</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1;
                                while ($data_count = $result_count->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>' . $no++ . '</td>';
                                    echo '<td>' . date('F', mktime(0, 0, 0, $data_count['bulan'], 10)) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_laki']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_perempuan']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_hb']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_polio']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_bcg']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_dpt']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_campak']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_merah']) . '</td>';
                                    echo '<td>' . htmlspecialchars($data_count['jumlah_biru']) . '</td>';
                                    echo '</tr>';

                                    // Accumulate totals
                                    $totals['jumlah_laki'] += $data_count['jumlah_laki'];
                                    $totals['jumlah_perempuan'] += $data_count['jumlah_perempuan'];
                                    $totals['jumlah_merah'] += $data_count['jumlah_merah'];
                                    $totals['jumlah_biru'] += $data_count['jumlah_biru'];
                                    $totals['jumlah_hb'] += $data_count['jumlah_hb'];
                                    $totals['jumlah_polio'] += $data_count['jumlah_polio'];
                                    $totals['jumlah_bcg'] += $data_count['jumlah_bcg'];
                                    $totals['jumlah_dpt'] += $data_count['jumlah_dpt'];
                                    $totals['jumlah_campak'] += $data_count['jumlah_campak'];
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2">Total</th>
                                    <td><?php echo htmlspecialchars($totals['jumlah_laki']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_perempuan']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_hb']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_polio']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_bcg']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_dpt']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_campak']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_merah']); ?></td>
                                    <td><?php echo htmlspecialchars($totals['jumlah_biru']); ?></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../../layout/footer.php'; ?>
</body>

</html>