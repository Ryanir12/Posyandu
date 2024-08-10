<?php
session_start();
if (!isset($_SESSION['hak_akses']) || empty($_SESSION['hak_akses'])) {
    header("Location: ../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Query untuk mendapatkan data anak dan orang tua
$query_anak = "SELECT id, nama_anak, YEAR(tanggal_lahir) as tahun_lahir FROM anak";
$query_orang_tua = "SELECT no, nama_ibu FROM orang_tua";
$query_bidan = "SELECT id_bidan, nama_bidan FROM bidan";

// Eksekusi query
$result_anak = $koneksi->query($query_anak);
if (!$result_anak) {
    die("Query anak gagal: " . $koneksi->error);
}

$result_orang_tua = $koneksi->query($query_orang_tua);
if (!$result_orang_tua) {
    die("Query orang tua gagal: " . $koneksi->error);
}

$result_bidan = $koneksi->query($query_bidan);
if (!$result_bidan) {
    die("Query bidan gagal: " . $koneksi->error);
}

// Proses form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anak_id = $_POST['anak_id'];
    $orang_tua_no = $_POST['orang_tua_id'];
    $bidan_id = $_POST['bidan_id'];
    $petugas = htmlspecialchars($_POST['petugas']);
    $tanggal_imunisasi = htmlspecialchars($_POST['tanggal_imunisasi']);
    $usia_anak = (int) $_POST['usia_anak'];
    $jenis_imunisasi = htmlspecialchars($_POST['jenis_imunisasi']);
    $vitamin = isset($_POST['vitamin']) ? htmlspecialchars($_POST['vitamin']) : null;
    $keterangan = isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : null;

    // Persiapan dan eksekusi query untuk memasukkan data
    $stmt = $koneksi->prepare("INSERT INTO kelola_imunisasi (anak_id, orang_tua_no, bidan_id, petugas, tanggal_imunisasi, usia_anak, jenis_imunisasi, vitamin, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare statement failed: " . $koneksi->error);
    }

    if (!$stmt->bind_param("iiissssss", $anak_id, $orang_tua_no, $bidan_id, $petugas, $tanggal_imunisasi, $usia_anak, $jenis_imunisasi, $vitamin, $keterangan)) {
        die("Bind parameters failed: " . $stmt->error);
    }

    if ($stmt->execute()) {
        // Setelah berhasil menyimpan data, alihkan ke halaman kelola-imunisasi.php
        header("Location: kelola-imunisasi.php");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }

    $stmt->close();
}

$koneksi->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        .form-group {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .form-group label {
            flex: 1;
            margin-right: 1rem;
            text-align: right;
        }

        .form-group .form-control {
            flex: 2;
        }

        .btn-group {
            display: flex;
            gap: 5px;
        }

        .indicator {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background-color: grey;
            margin-right: 5px;
        }

        .indicator.active {
            background-color: green;
        }

        .indicator.not-active {
            background-color: red;
        }
    </style>
</head>

<body id="page-top">

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include '../../layout/topbar-bidan.php'; ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Imunisasi</h1>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-body">
                                    <form method="post" action="tambah-imunisasi.php">
                                        <div class="form-group">
                                            <label for="anak_id">Nama Anak:</label>
                                            <select id="anak_id" name="anak_id" class="form-control" required onchange="updateUsia()">
                                                <option value="">Pilih Nama Anak</option>
                                                <?php while ($row_anak = $result_anak->fetch_assoc()) { ?>
                                                    <option value="<?php echo htmlspecialchars($row_anak['id']); ?>" data-tahun-lahir="<?php echo htmlspecialchars($row_anak['tahun_lahir']); ?>">
                                                        <?php echo htmlspecialchars($row_anak['nama_anak']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="orang_tua_id">Nama Ibu:</label>
                                            <select id="orang_tua_id" name="orang_tua_id" class="form-control" required>
                                                <option value="">Pilih Nama Ibu</option>
                                                <?php while ($row_orang_tua = $result_orang_tua->fetch_assoc()) { ?>
                                                    <option value="<?php echo htmlspecialchars($row_orang_tua['no']); ?>">
                                                        <?php echo htmlspecialchars($row_orang_tua['nama_ibu']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="bidan_id">Nama Bidan:</label>
                                            <select id="bidan_id" name="bidan_id" class="form-control" required>
                                                <option value="">Pilih Nama Bidan</option>
                                                <?php while ($row_bidan = $result_bidan->fetch_assoc()) { ?>
                                                    <option value="<?php echo htmlspecialchars($row_bidan['id_bidan']); ?>">
                                                        <?php echo htmlspecialchars($row_bidan['nama_bidan']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="petugas">Nama Petugas:</label>
                                            <input type="text" id="petugas" name="petugas" class="form-control" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="tanggal_imunisasi">Tanggal Imunisasi:</label>
                                            <input type="date" id="tanggal_imunisasi" name="tanggal_imunisasi" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="usia_anak">Usia Anak:</label>
                                            <input type="number" id="usia_anak" name="usia_anak" class="form-control" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="jenis_imunisasi">Jenis Imunisasi:</label>
                                            <input type="text" id="jenis_imunisasi" name="jenis_imunisasi" class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="vitamin">Vitamin:</label>
                                            <input type="text" id="vitamin" name="vitamin" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label for="keterangan">Keterangan:</label>
                                            <textarea id="keterangan" name="keterangan" class="form-control"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../../path/to/your/js/scripts.js"></script>
    <script>
        function updateUsia() {
            var selectElement = document.getElementById("anak_id");
            var selectedOption = selectElement.options[selectElement.selectedIndex];
            var tahunLahir = selectedOption.getAttribute("data-tahun-lahir");
            var tahunSekarang = new Date().getFullYear();

            if (tahunLahir) {
                var usia = tahunSekarang - parseInt(tahunLahir, 10);
                document.getElementById("usia_anak").value = usia;
            }
        }
    </script>
</body>

</html>