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
          JOIN orang_tua o ON k.orang_tua_no = o.no
          JOIN bidan b ON k.bidan_id = b.id_bidan
          WHERE 1=1"; // Base query

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

$stmt = $koneksi->prepare($query);

if ($filter) {
    // Bind parameters
    if ($filter == 'harian') {
        $stmt->bind_param('s', $tanggal);
    } elseif ($filter == 'bulanan') {
        $stmt->bind_param('ii', $bulan, $tahun);
    } elseif ($filter == 'tahunan') {
        $stmt->bind_param('i', $tahun);
    }
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
        @media print {

            /* Mengatur margin halaman untuk mencegah potongan */
            @page {
                margin: 20mm;
                /* Sesuaikan margin sesuai kebutuhan */
            }

            /* Mengatur ukuran font dan padding untuk tabel */
            table {
                width: 100%;
                border-collapse: collapse;
                font-size: 10pt;
                /* Mengurangi ukuran font jika diperlukan */
                margin-left: -10mm;
                /* Geser tabel ke kiri */
            }

            th,
            td {
                border: 1px solid #000;
                padding: 4px;
                /* Mengurangi padding untuk menghemat ruang */
                text-align: left;
            }

            /* Mengatur lebar kolom agar tabel tidak terpotong */
            th {
                width: auto;
                max-width: 150px;
                /* Atur lebar kolom sesuai kebutuhan */
            }

            td {
                width: auto;
                max-width: 150px;
                /* Atur lebar kolom sesuai kebutuhan */
                word-wrap: break-word;
            }

            /* Mencegah tabel terputus saat mencetak */
            table {
                page-break-inside: auto;
            }

            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            /* Mengatur tampilan elemen saat cetak */
            .no-print,
            .btn-cetak {
                display: none;
            }

            /* Mengatur jarak konten agar tidak terlalu dekat dengan tepi halaman */
            .print-container {
                margin: 0 auto;
                width: auto;
                /* Agar konten berada di tengah halaman */
            }

            /* Pastikan elemen lain di halaman cetak tidak mengganggu */
            body {
                margin: 0;
                padding: 0;
            }

            /* Hapus background dan warna yang tidak diperlukan */
            body {
                background: #fff;
                color: #000;
                margin: 0;
                padding: 0;
                font-family: Arial, sans-serif;
            }

            /* Sembunyikan elemen yang tidak perlu saat cetak */
            .no-print {
                display: none;
            }

            /* Atur ukuran dan jarak tabel */
            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #000;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f2f2f2;
            }

            /* Atur margin dan padding untuk tampilan cetak */
            .container-fluid {
                margin: 0;
                padding: 0;
            }

            .card {
                border: none;
                box-shadow: none;
            }

            .print-container {
                padding: 0;
                margin: 0;
                position: absolute;
                top: 0;
                width: 100%;
            }

            @page {
                margin: 0;
            }

            h1,
            h2,
            h5,
            p {
                margin: 0;
                padding: 0;
            }

            .print-container {
                margin: 10px;
            }

            .text-center {
                text-align: center;
            }
        }
    </style>
</head>

<body id="page-top">

    <div class="no-print">
        <?php include '../../layout/topbar-admin.php'; ?>
    </div>

    <div class="container-fluid">
        <!-- Form filter tanggal -->
        <form method="GET" class="mb-4 no-print">
            <div class="form-group">
                <label for="filter">Filter Berdasarkan:</label>
                <select name="filter" id="filter" class="form-control" onchange="filterChanged()">
                    <option value="">Pilih Filter</option>
                    <option value="harian" <?php echo ($filter == 'harian') ? 'selected' : ''; ?>>Harian</option>
                    <option value="bulanan" <?php echo ($filter == 'bulanan') ? 'selected' : ''; ?>>Bulanan</option>
                    <option value="tahunan" <?php echo ($filter == 'tahunan') ? 'selected' : ''; ?>>Tahunan</option>
                </select>
            </div>
            <div id="filter-options">
                <?php if ($filter == 'harian') : ?>
                    <div class="form-group">
                        <label for="tanggal">Tanggal</label>
                        <input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo htmlspecialchars($tanggal); ?>">
                    </div>
                <?php elseif ($filter == 'bulanan') : ?>
                    <div class="form-group">
                        <label for="bulan">Bulan</label>
                        <input type="number" name="bulan" id="bulan" class="form-control" min="1" max="12" value="<?php echo htmlspecialchars($bulan); ?>">
                    </div>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo htmlspecialchars($tahun); ?>">
                    </div>
                <?php elseif ($filter == 'tahunan') : ?>
                    <div class="form-group">
                        <label for="tahun">Tahun</label>
                        <input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo htmlspecialchars($tahun); ?>">
                    </div>
                <?php endif; ?>
            </div>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>

        <button class="btn btn-success btn-cetak no-print" onclick="window.print()">Cetak Laporan</button>
        <br><br>
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800 no-print">Laporan Imunisasi</h1>
        </div>

        <div class="card mb-5">
            <div class="card-body">
                <div id="reportContent" class="print-container">
                    <h2 class="text-center">Posyandu Lapau Kasik Subarang</h2>
                    <h2 class="text-center">Puskesmas Paninggahan</h2>
                    <h2 class="text-center">Jl. Tabing Biduk, Nagari Panginggahan, Kec. Junjung SIrih</h2>
                    <hr>
                    <h5 class="text-center">
                        Laporan Imunisasi
                        <?php
                        if ($filter == 'harian') {
                            echo "Tanggal: " . date('d/m/Y', strtotime($tanggal));
                        } elseif ($filter == 'bulanan') {
                            echo "Bulan: " . date('m/Y', strtotime("$tahun-$bulan-01"));
                        } elseif ($filter == 'tahunan') {
                            echo "Tahun: " . htmlspecialchars($tahun);
                        } else {
                            echo "Seluruh Data";
                        }
                        ?>
                    </h5>

                    <div class="table-responsive-lg" style="overflow-x: auto;">
                        <table class="table table-hover table-bordered" id="Table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Anak</th>
                                    <th>Nama Ibu</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Usia Anak</th>
                                    <th>Jenis Imunisasi</th>
                                    <th>Vitamin</th>
                                    <th>Nama Bidan</th>
                                    <th>Nama Petugas</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result && $result->num_rows > 0) {
                                    $no = 1;
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<tr>
                                                        <td>" . $no++ . "</td>
                                                        <td>" . htmlspecialchars($row['nik']) . "</td>
                                                        <td>" . htmlspecialchars($row['nama_anak']) . "</td>     
                                                        <td>" . htmlspecialchars($row['nama_ibu']) . "</td>                                                   
                                                        <td>" . htmlspecialchars($row['jenis_kelamin']) . "</td>
                                                        <td>" . htmlspecialchars($row['usia_anak']) . "</td>
                                                        <td>" . htmlspecialchars($row['jenis_imunisasi']) . "</td>
                                                        <td>" . htmlspecialchars($row['vitamin']) . "</td>
                                                         <td>" . htmlspecialchars($row['nama_bidan']) . "</td>
                                                        <td>" . htmlspecialchars($row['nama_petugas']) . "</td>
                                                    </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='10' class='text-center'>Data tidak ditemukan</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterChanged() {
            var filter = document.getElementById('filter').value;
            var filterOptions = document.getElementById('filter-options');
            if (filter === 'harian') {
                filterOptions.innerHTML = '<div class="form-group"><label for="tanggal">Tanggal</label><input type="date" name="tanggal" id="tanggal" class="form-control" value="<?php echo htmlspecialchars($tanggal); ?>"></div>';
            } else if (filter === 'bulanan') {
                filterOptions.innerHTML = '<div class="form-group"><label for="bulan">Bulan</label><input type="number" name="bulan" id="bulan" class="form-control" min="1" max="12" value="<?php echo htmlspecialchars($bulan); ?>"></div><div class="form-group"><label for="tahun">Tahun</label><input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo htmlspecialchars($tahun); ?>"></div>';
            } else if (filter === 'tahunan') {
                filterOptions.innerHTML = '<div class="form-group"><label for="tahun">Tahun</label><input type="number" name="tahun" id="tahun" class="form-control" value="<?php echo htmlspecialchars($tahun); ?>"></div>';
            } else {
                filterOptions.innerHTML = '';
            }
        }

        // Initialize filter options on page load
        document.addEventListener('DOMContentLoaded', function() {
            filterChanged();
        });
    </script>

</body>

</html>

<?php
$stmt->close();
$koneksi->close();
?>