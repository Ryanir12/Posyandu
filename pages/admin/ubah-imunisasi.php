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
        $anak_id = $_POST['anak_id'];
        $orang_tua_no = $_POST['orang_tua_no'];
        $bidan_id = $_POST['bidan_id'];
        $petugas = $_POST['petugas'];
        $tanggal_imunisasi = $_POST['tanggal_imunisasi'];
        $usia_anak = $_POST['usia_anak'];
        $jenis_imunisasi = $_POST['jenis_imunisasi'];
        $vitamin = $_POST['vitamin'];
        $keterangan = $_POST['keterangan'];

        // Update data ke database
        $query = "UPDATE kelola_imunisasi SET
                  anak_id = ?, orang_tua_no = ?, bidan_id = ?, petugas = ?, tanggal_imunisasi = ?,
                  usia_anak = ?, jenis_imunisasi = ?, vitamin = ?, keterangan = ?
                  WHERE id = ?";
        $stmt = $koneksi->prepare($query);

        if ($stmt === false) {
            die("Gagal mempersiapkan query: " . $koneksi->error);
        }

        $stmt->bind_param('iisssssssi', $anak_id, $orang_tua_no, $bidan_id, $petugas, $tanggal_imunisasi, $usia_anak, $jenis_imunisasi, $vitamin, $keterangan, $id);

        if ($stmt->execute()) {
            header("Location: kelola-imunisasi.php?pesan=update");
            exit;
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        // Ambil data yang akan diubah
        $query = "SELECT k.id, k.anak_id, k.orang_tua_no, k.bidan_id, k.petugas, k.tanggal_imunisasi, k.usia_anak, k.jenis_imunisasi, k.vitamin, k.keterangan
                  FROM kelola_imunisasi k
                  WHERE k.id = ?";
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
                        <h1 class="h3 mb-0 text-gray-800">Ubah Data Imunisasi</h1>
                    </div>

                    <div class="card mb-5">
                        <div class="card-body">
                            <form method="post" action="">
                                <div class="form-group">
                                    <label for="anak_id">ID Anak</label>
                                    <input type="number" class="form-control" id="anak_id" name="anak_id" value="<?php echo htmlspecialchars($data['anak_id']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="orang_tua_no">Nomor Orang Tua</label>
                                    <input type="number" class="form-control" id="orang_tua_no" name="orang_tua_no" value="<?php echo htmlspecialchars($data['orang_tua_no']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="bidan_id">ID Bidan</label>
                                    <input type="number" class="form-control" id="bidan_id" name="bidan_id" value="<?php echo htmlspecialchars($data['bidan_id']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="petugas">Nama Petugas</label>
                                    <input type="text" class="form-control" id="petugas" name="petugas" value="<?php echo htmlspecialchars($data['petugas']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="tanggal_imunisasi">Tanggal Imunisasi</label>
                                    <input type="date" class="form-control" id="tanggal_imunisasi" name="tanggal_imunisasi" value="<?php echo htmlspecialchars($data['tanggal_imunisasi']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="usia_anak">Usia Anak</label>
                                    <input type="number" class="form-control" id="usia_anak" name="usia_anak" value="<?php echo htmlspecialchars($data['usia_anak']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="jenis_imunisasi">Jenis Imunisasi</label>
                                    <input type="text" class="form-control" id="jenis_imunisasi" name="jenis_imunisasi" value="<?php echo htmlspecialchars($data['jenis_imunisasi']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="vitamin">Vitamin</label>
                                    <input type="text" class="form-control" id="vitamin" name="vitamin" value="<?php echo htmlspecialchars($data['vitamin']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?php echo htmlspecialchars($data['keterangan']); ?></textarea>
                                </div>
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="kelola-imunisasi.php" class="btn btn-secondary">Kembali</a>
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