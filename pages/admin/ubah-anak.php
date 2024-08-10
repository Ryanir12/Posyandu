<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        /* Tambahkan styling tambahan jika diperlukan */
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

    if (!isset($_GET['id'])) {
        header("Location: kelola-anak.php?pesan=gagal");
        exit;
    }

    $id = $_GET['id'];

    // Ambil data anak berdasarkan id
    $query = "SELECT * FROM anak WHERE id = '$id'";
    $result = $koneksi->query($query);

    if (!$result) {
        echo "<p>Error Query: " . $koneksi->error . "</p>";
        exit;
    }

    if ($result->num_rows == 0) {
        header("Location: kelola-anak.php?pesan=gagal");
        exit;
    }

    $data_anak = $result->fetch_assoc();

    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari form
        $nama_anak = $_POST['nama_anak'];
        $tempat_lahir = $_POST['tempat_lahir'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $golongan_darah = $_POST['golongan_darah'];
        $orang_tua_id = $_POST['orang_tua_id'];

        // Validasi data
        if ($jenis_kelamin !== 'Laki-Laki' && $jenis_kelamin !== 'Perempuan') {
            $error_message = 'Pilih jenis kelamin yang valid.';
        } elseif ($golongan_darah === 'Pilih') {
            $golongan_darah = ''; // Kosongkan jika tidak dipilih
        } elseif ($orang_tua_id === 'Pilih') {
            $error_message = 'Pilih orang tua yang valid.';
        } else {
            // Query untuk mengubah data anak
            $query_update = "UPDATE anak SET 
                            nama_anak='$nama_anak', 
                            tempat_lahir='$tempat_lahir', 
                            tanggal_lahir='$tanggal_lahir', 
                            jenis_kelamin='$jenis_kelamin', 
                            golongan_darah='$golongan_darah', 
                            orang_tua_id='$orang_tua_id'
                            WHERE id='$id'";

            if ($koneksi->query($query_update) === TRUE) {
                header("Location: kelola-anak.php?pesan=berhasil");
                exit;
            } else {
                $error_message = "Error: " . $query_update . "<br>" . $koneksi->error;
            }
        }
    }
    ?>

    <div id="wrapper">
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include '../../layout/topbar-admin.php'; ?>

                <div class="container-fluid">
                    <h2>Ubah Data Anak</h2>
                    <?php if ($error_message) : ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="nama_anak">Nama Anak</label>
                            <input type="text" class="form-control" id="nama_anak" name="nama_anak" value="<?php echo htmlspecialchars($data_anak['nama_anak']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo htmlspecialchars($data_anak['tempat_lahir']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo htmlspecialchars($data_anak['tanggal_lahir']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="Pilih">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki" <?php echo ($data_anak['jenis_kelamin'] == 'Laki-Laki') ? 'selected' : ''; ?>>Laki-laki</option>
                                <option value="Perempuan" <?php echo ($data_anak['jenis_kelamin'] == 'Perempuan') ? 'selected' : ''; ?>>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="golongan_darah">Golongan Darah</label>
                            <select class="form-control" id="golongan_darah" name="golongan_darah">
                                <option value="Pilih">Pilih Golongan Darah</option>
                                <option value="A" <?php echo ($data_anak['golongan_darah'] == 'A') ? 'selected' : ''; ?>>A</option>
                                <option value="B" <?php echo ($data_anak['golongan_darah'] == 'B') ? 'selected' : ''; ?>>B</option>
                                <option value="AB" <?php echo ($data_anak['golongan_darah'] == 'AB') ? 'selected' : ''; ?>>AB</option>
                                <option value="O" <?php echo ($data_anak['golongan_darah'] == 'O') ? 'selected' : ''; ?>>O</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="orang_tua_id">Nama Orang Tua</label>
                            <select class="form-control" id="orang_tua_id" name="orang_tua_id" required>
                                <option value="Pilih">Pilih Nama Orang Tua</option>
                                <?php
                                $query_orang_tua = "SELECT no, nama_ibu, nama_suami FROM orang_tua";
                                $result_orang_tua = $koneksi->query($query_orang_tua);

                                while ($row_orang_tua = $result_orang_tua->fetch_assoc()) {
                                    $selected = ($row_orang_tua['no'] == $data_anak['orang_tua_id']) ? 'selected' : '';
                                    echo "<option value='{$row_orang_tua['no']}' $selected>{$row_orang_tua['nama_ibu']} & {$row_orang_tua['nama_suami']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="kelola-anak.php" class="btn btn-secondary">Kembali</a>
                    </form>
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
</body>

</html>