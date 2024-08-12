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

// Inisialisasi variabel
$filter = isset($_GET['filter']) ? $_GET['filter'] : '';
$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// Menyiapkan query berdasarkan filter
$query = "SELECT k.id, a.nama_anak, a.nik, a.jenis_kelamin, o.nama_ibu, b.nama_bidan, k.petugas as nama_petugas, k.tanggal_imunisasi, k.usia_anak, k.jenis_imunisasi, k.vitamin, k.keterangan
          FROM kelola_imunisasi k
          JOIN anak a ON k.anak_id = a.id
          LEFT JOIN orang_tua o ON a.id_ibu = o.no
          JOIN bidan b ON k.bidan_id = b.id_bidan
          WHERE 1=1"; // Base query

// Inisialisasi array untuk parameter
$params = [];

// Menambahkan filter tanggal
if ($filter == 'harian') {
    $query .= " AND k.tanggal_imunisasi = ?";
    $params[] = $tanggal;
} elseif ($filter == 'bulanan') {
    $query .= " AND MONTH(k.tanggal_imunisasi) = ? AND YEAR(k.tanggal_imunisasi) = ?";
    $params[] = $bulan;
    $params[] = $tahun;
} elseif ($filter == 'tahunan') {
    $query .= " AND YEAR(k.tanggal_imunisasi) = ?";
    $params[] = $tahun;
}

// Mempersiapkan statement dan mengeksekusi query
$stmt = $koneksi->prepare($query);

if ($stmt === false) {
    die("Error preparing statement: " . $koneksi->error);
}

if (!empty($params)) {
    $types = str_repeat('s', count($params)); // Mengatur tipe parameter sebagai string
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        /* Styling untuk container informasi anak */
        .info-container {
            display: flex;
            justify-content: space-between;
        }

        .info-left {
            flex: 3;
            padding-right: 20px;
        }

        .info-right {
            flex: 1;
            padding-left: 20px;
        }

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
                            $selected = ($row['id'] == intval($_GET['anak_id'])) ? 'selected' : '';
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
        <br><br>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Laporan Imunisasi Anak</h1>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <!-- Container khusus untuk pencetakan -->
                <div class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Paninggahan, Kec. Junjung Sirih</h2>
                    <hr>
                    <h5 class="text-center ">Laporan Imunisasi Anak</h5>

                    <br>
                    <br>
                    <!-- Informasi Anak -->
                    <?php
                    $anak_id = isset($_GET['anak_id']) ? intval($_GET['anak_id']) : 0;
                    $query_anak_detail = "SELECT a.nama_anak, a.tanggal_lahir, a.jenis_kelamin, a.nik, o.nama_ibu,
       k.petugas, b.nama_bidan
FROM anak a
LEFT JOIN orang_tua o ON a.id_ibu = o.no
LEFT JOIN kelola_imunisasi k ON a.id = k.anak_id
LEFT JOIN bidan b ON k.bidan_id = b.id_bidan
WHERE a.id = ?
LIMIT 1"; // Tambahkan LIMIT 1 untuk memastikan hanya satu baris yang diambil
                    $stmt_anak_detail = $koneksi->prepare($query_anak_detail);

                    if ($stmt_anak_detail === false) {
                        die("Error preparing statement: " . $koneksi->error);
                    }

                    $stmt_anak_detail->bind_param("i", $anak_id);
                    $stmt_anak_detail->execute();
                    $result_anak_detail = $stmt_anak_detail->get_result();

                    if ($result_anak_detail === false) {
                        die("Query gagal: " . $koneksi->error);
                    }

                    $anak_detail = $result_anak_detail->fetch_assoc();

                    if ($anak_detail):
                    ?>
                        <div class="info-container">
                            <div class="info-left">
                                <p>NIK: <?php echo htmlspecialchars($anak_detail['nik']); ?></p>
                                <p>Nama Anak: <?php echo htmlspecialchars($anak_detail['nama_anak']); ?></p>
                                <p>Nama Ibu: <?php echo htmlspecialchars($anak_detail['nama_ibu']); ?></p>
                            </div>
                            <div class="info-right">
                                <p>Tanggal Lahir: <?php echo htmlspecialchars($anak_detail['tanggal_lahir']); ?></p>
                                <p>Jenis Kelamin: <?php echo htmlspecialchars($anak_detail['jenis_kelamin']); ?></p>
                                <p>Petugas: <?php echo htmlspecialchars($anak_detail['petugas']); ?></p>
                                <p>Nama Bidan: <?php echo htmlspecialchars($anak_detail['nama_bidan']); ?></p>
                            </div>
                        </div>

                    <?php else: ?>
                        <p>Data anak tidak ditemukan.</p>
                    <?php endif; ?>



                    <div class="table-responsive-lg" style="overflow-x: auto;">
                        <table class="table table-hover table-bordered" id="Table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Tanggal Periksa</th>
                                    <th>Umur(Bulan)</th>
                                    <th>Berat Badan (kg)</th>
                                    <th>Tinggi Badan (cm)</th>
                                    <th>Imunisasi</th>
                                    <th>Vitamin</th>

                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $no = 1; // Inisialisasi nomor urut
                                // Mengambil data imunisasi anak berdasarkan ID anak yang dipilih
                                $query_imunisasi = "
                SELECT k.tanggal_imunisasi, k.usia_anak, k.jenis_imunisasi, k.vitamin, p.bb as berat_badan, p.tb as tinggi_badan
                FROM kelola_imunisasi k
                LEFT JOIN penimbangan p ON k.anak_id = p.id_anak
                WHERE k.anak_id = ?
            ";
                                $stmt_imunisasi = $koneksi->prepare($query_imunisasi);

                                if ($stmt_imunisasi === false) {
                                    die("Error preparing statement: " . $koneksi->error);
                                }

                                $stmt_imunisasi->bind_param("i", $anak_id);
                                $stmt_imunisasi->execute();
                                $result_imunisasi = $stmt_imunisasi->get_result();

                                if ($result_imunisasi && $result_imunisasi->num_rows > 0) {
                                    while ($row = $result_imunisasi->fetch_assoc()) {
                                        echo "<tr>
                        <td>" . htmlspecialchars($no++) . "</td> <!-- Menambahkan nomor urut -->
                        <td>" . htmlspecialchars($row['tanggal_imunisasi']) . "</td>
                        <td>" . htmlspecialchars($row['usia_anak']) . "</td>
                        <td>" . htmlspecialchars($row['berat_badan']) . "</td>
                        <td>" . htmlspecialchars($row['tinggi_badan']) . "</td>
                        <td>" . htmlspecialchars($row['jenis_imunisasi']) . "</td>
                        <td>" . htmlspecialchars($row['vitamin']) . "</td>
                        
                      </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7'>Tidak ada data imunisasi untuk anak ini.</td></tr>";
                                }

                                $stmt_imunisasi->close();
                                ?>
                            </tbody>
                        </table>
                    </div>



                </div>
            </div>
        </div>
    </div>
    </div>

    <?php include '../../layout/footer.php'; ?>

    <script src="../../path/to/your/js/script.js"></script>
</body>

</html>

<?php
$stmt->close();
$koneksi->close();
?>