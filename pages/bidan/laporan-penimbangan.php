<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

            .btn-cetak,
            .no-print {
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

    // Handle filter if form is submitted
    $filter = isset($_GET['filter']) ? $_GET['filter'] : 'tahunan';
    $tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
    $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
    $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    $query_penimbangan = "SELECT p.id_penimbangan, p.tgl_timbangan, p.usia, p.bb, p.tb, p.deteksi, p.ket,
       a.nik, a.jenis_kelamin, a.nama_anak, o.nama_ibu, b.nama_bidan, p.petugas
FROM penimbangan p
JOIN anak a ON p.id_anak = a.id
JOIN orang_tua o ON a.orang_tua_id = o.no
JOIN bidan b ON p.id_bidan = b.id_bidan
WHERE 1=1
";


    $params = [];
    $types = "";

    // Filter query based on selected filter
    if ($filter == 'harian') {
        $query_penimbangan .= " AND p.tgl_timbangan = ?";
        $params[] = $tanggal;
        $types .= "s";
    } elseif ($filter == 'bulanan') {
        $query_penimbangan .= " AND MONTH(p.tgl_timbangan) = ? AND YEAR(p.tgl_timbangan) = ?";
        $params[] = $bulan;
        $params[] = $tahun;
        $types .= "ii";
    } elseif ($filter == 'tahunan') {
        $query_penimbangan .= " AND YEAR(p.tgl_timbangan) = ?";
        $params[] = $tahun;
        $types .= "i";
    }

    $stmt = $koneksi->prepare($query_penimbangan);

    if (!$stmt) {
        die("Error preparing statement: " . $koneksi->error);
    }

    if (!empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result_penimbangan = $stmt->get_result();

    if (!$result_penimbangan) {
        die("Query gagal: " . $koneksi->error);
    }
    ?>

    <div class="no-print">
        <?php include '../../layout/topbar-admin.php'; ?>
    </div>

    <div class="container-fluid">
        <!-- Form filter -->
        <form method="GET" class="mb-4 no-print">
            <div class="form-group">
                <label for="filter">Filter Berdasarkan:</label>
                <select id="filter" name="filter" class="form-control" onchange="this.form.submit()">
                    <option value="tahunan" <?php echo ($filter == 'tahunan') ? 'selected' : ''; ?>>Tahunan</option>
                    <option value="bulanan" <?php echo ($filter == 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                    <option value="harian" <?php echo ($filter == 'harian') ? 'selected' : ''; ?>>Harian</option>
                </select>
            </div>

            <?php if ($filter == 'harian'): ?>
                <div class="form-group">
                    <label for="tanggal">Pilih Tanggal:</label>
                    <input type="date" id="tanggal" name="tanggal" class="form-control" value="<?php echo htmlspecialchars($tanggal); ?>" onchange="this.form.submit()">
                </div>
            <?php elseif ($filter == 'bulanan'): ?>
                <div class="form-group">
                    <label for="bulan">Pilih Bulan:</label>
                    <input type="month" id="bulan" name="bulan" class="form-control" value="<?php echo htmlspecialchars($tahun . '-' . $bulan); ?>" onchange="this.form.submit()">
                </div>
            <?php elseif ($filter == 'tahunan'): ?>
                <div class="form-group">
                    <label for="tahun">Pilih Tahun:</label>
                    <select id="tahun" name="tahun" class="form-control" onchange="this.form.submit()">
                        <?php
                        $currentYear = date('Y');
                        for ($i = $currentYear; $i >= 2000; $i--) {
                            $selected = ($i == $tahun) ? 'selected' : '';
                            echo "<option value=\"$i\" $selected>$i</option>";
                        }
                        ?>
                    </select>
                </div>
            <?php endif; ?>
        </form>

        <button class="btn btn-success btn-cetak no-print" onclick="window.print()">Cetak Laporan</button>
        <br>
        <br>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 no-print">Laporan Penimbangan</h1>
        </div>

        <div class="card mb-5">

            <div class="card-body">

                <!-- Container khusus untuk pencetakan -->
                <div class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Panginggahan, Kec. Junjung SIrih</h2>
                    <hr>
                    <h5 class="text-center">Laporan Penimbangan <?php echo ucfirst($filter); ?> <?php echo ($filter == 'harian') ? htmlspecialchars(date('d F Y', strtotime($tanggal))) : (($filter == 'bulanan') ? htmlspecialchars(date('F Y', strtotime($tahun . '-' . $bulan . '-01'))) : htmlspecialchars($tahun)); ?></h5>

                    <div class="table-responsive-lg" style="overflow-x: auto;">

                        <table class="table table-hover table-bordered" id="Table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Anak</th>
                                    <th>Nama Ibu</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Umur(Bulan)</th>
                                    <th>Berat Badan (Kg)</th>
                                    <th>Tinggi Badan (Cm)</th>
                                    <th>Nama Bidan</th>
                                    <th>Nama Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result_penimbangan->num_rows > 0) {
                                    $no = 1;
                                    while ($row = $result_penimbangan->fetch_assoc()) {
                                        echo "<tr>
                                                <td>" . htmlspecialchars($no++) . "</td>
                                                <td>" . htmlspecialchars($row['nik']) . "</td>
                                                <td>" . htmlspecialchars($row['nama_anak']) . "</td>
                                                <td>" . htmlspecialchars($row['nama_ibu']) . "</td>
                                                <td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>
                                                <td>" . htmlspecialchars($row['usia']) . "</td>
                                                <td>" . htmlspecialchars($row['bb']) . "</td>
                                                <td>" . htmlspecialchars($row['tb']) . "</td>
                                                <td>" . htmlspecialchars($row['nama_bidan']) . "</td>
                                                <td>" . htmlspecialchars($row['petugas']) . "</td>
                                            </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10'>Tidak ada data</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include '../../layout/footer.php'; ?>


</body>

</html>