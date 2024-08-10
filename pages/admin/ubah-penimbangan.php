<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        .form-group {
            margin-bottom: 15px;
        }

        .btn-group {
            display: flex;
            gap: 5px;
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

    // Ambil ID dari query parameter
    $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Ambil data dari form
        $tgl_timbangan = $_POST['tgl_timbangan'];
        $usia = $_POST['usia'];
        $bb = $_POST['bb'];
        $tb = $_POST['tb'];
        $deteksi = $_POST['deteksi'];
        $ket = $_POST['ket'];
        $id_anak = $_POST['id_anak'];
        $id_bidan = $_POST['id_bidan'];

        // Update data ke database
        $query = "UPDATE penimbangan SET
                  tgl_timbangan = ?, usia = ?, bb = ?, tb = ?, deteksi = ?, ket = ?, id_anak = ?, id_bidan = ?
                  WHERE id_penimbangan = ?";
        $stmt = $koneksi->prepare($query);

        if ($stmt === false) {
            die("Gagal mempersiapkan query: " . $koneksi->error);
        }

        $stmt->bind_param('siidssiii', $tgl_timbangan, $usia, $bb, $tb, $deteksi, $ket, $id_anak, $id_bidan, $id);

        if ($stmt->execute()) {
            header("Location: kelola-penimbangan.php?pesan=update");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // Ambil data yang akan diubah
        $query = "SELECT id_penimbangan, tgl_timbangan, usia, bb, tb, deteksi, ket, id_anak, id_bidan
                  FROM penimbangan
                  WHERE id_penimbangan = ?";
        $stmt = $koneksi->prepare($query);

        if ($stmt === false) {
            die("Gagal mempersiapkan query: " . $koneksi->error);
        }

        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if (!$data) {
            echo "Data tidak ditemukan!";
            exit;
        }
    }
    ?>

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar-admin.php'; ?>

                <div class="container-fluid">

                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Ubah Data Penimbangan</h1>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="tgl_timbangan">Tanggal Penimbangan</label>
                                    <input type="date" class="form-control" id="tgl_timbangan" name="tgl_timbangan" value="<?php echo htmlspecialchars($data['tgl_timbangan']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="usia">Usia (dalam bulan)</label>
                                    <input type="number" class="form-control" id="usia" name="usia" value="<?php echo htmlspecialchars($data['usia']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="bb">Berat Badan (kg)</label>
                                    <input type="number" step="0.01" class="form-control" id="bb" name="bb" value="<?php echo htmlspecialchars($data['bb']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tb">Tinggi Badan (cm)</label>
                                    <input type="number" step="0.01" class="form-control" id="tb" name="tb" value="<?php echo htmlspecialchars($data['tb']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="deteksi">Deteksi</label>
                                    <select class="form-control" id="deteksi" name="deteksi" required>
                                        <option value="Ideal" <?php echo ($data['deteksi'] == 'Ideal') ? 'selected' : ''; ?>>Ideal</option>
                                        <option value="Tidak Ideal" <?php echo ($data['deteksi'] == 'Tidak Ideal') ? 'selected' : ''; ?>>Tidak Ideal</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="ket">Keterangan</label>
                                    <textarea class="form-control" id="ket" name="ket" rows="3"><?php echo htmlspecialchars($data['ket']); ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="id_anak">ID Anak</label>
                                    <input type="number" class="form-control" id="id_anak" name="id_anak" value="<?php echo htmlspecialchars($data['id_anak']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="id_bidan">ID Bidan</label>
                                    <input type="number" class="form-control" id="id_bidan" name="id_bidan" value="<?php echo htmlspecialchars($data['id_bidan']); ?>" required>
                                </div>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="kelola-penimbangan.php" class="btn btn-secondary">Kembali</a>
                            </form>
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