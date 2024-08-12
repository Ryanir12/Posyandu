<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        /* Styling untuk container form */
        .container-fluid {
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
        }

        .form-group {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
        }

        .form-group label {
            flex: 0 0 200px;
            margin-bottom: 0;
            font-weight: bold;
        }

        .form-group input,
        .form-group select {
            flex: 1;
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            color: #fff;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            text-align: center;
        }

        .btn-primary {
            background-color: #007bff;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }

        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }

        .alert-danger {
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
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

    $error_message = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Ambil data dari form
        $nik = $_POST['nik'];
        $nama_anak = $_POST['nama_anak'];
        $tempat_lahir = $_POST['tempat_lahir'];
        $tanggal_lahir = $_POST['tanggal_lahir'];
        $jenis_kelamin = $_POST['jenis_kelamin'];
        $golongan_darah = $_POST['golongan_darah'];
        $orang_tua_id = $_POST['orang_tua_id'];

        // Ambil id_ibu berdasarkan orang_tua_id yang dipilih
        $query_id_ibu = "SELECT no FROM orang_tua WHERE no = ?";
        $stmt_id_ibu = $koneksi->prepare($query_id_ibu);
        $stmt_id_ibu->bind_param("i", $orang_tua_id);
        $stmt_id_ibu->execute();
        $stmt_id_ibu->bind_result($id_ibu);
        $stmt_id_ibu->fetch();
        $stmt_id_ibu->close();

        // Validasi data
        if (empty($nik)) {
            $error_message = 'NIK tidak boleh kosong.';
        } elseif ($jenis_kelamin !== 'Laki-Laki' && $jenis_kelamin !== 'Perempuan') {
            $error_message = 'Pilih jenis kelamin yang valid.';
        } elseif ($golongan_darah === 'Pilih') {
            $golongan_darah = ''; // Kosongkan jika tidak dipilih
        } elseif ($orang_tua_id === 'Pilih') {
            $error_message = 'Pilih orang tua yang valid.';
        } else {
            // Query untuk menambahkan data anak
            $query = "INSERT INTO anak (nik, nama_anak, tempat_lahir, tanggal_lahir, jenis_kelamin, golongan_darah, orang_tua_id, id_ibu) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

            $stmt = $koneksi->prepare($query);
            $stmt->bind_param("ssssssii", $nik, $nama_anak, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $golongan_darah, $orang_tua_id, $id_ibu);

            if ($stmt->execute()) {
                header("Location: kelola-anak.php?pesan=berhasil");
                exit;
            } else {
                $error_message = "Error: " . $stmt->error;
            }
        }
    }
    ?>

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar-admin.php'; ?>

                <div class="container-fluid">
                    <h2>Tambah Data Anak</h2>
                    <?php if ($error_message) : ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" class="form-control" id="nik" name="nik" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_anak">Nama Anak</label>
                            <input type="text" class="form-control" id="nama_anak" name="nama_anak" required>
                        </div>
                        <div class="form-group">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="Laki-Laki">Laki-laki</option>
                                <option value="Perempuan">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="golongan_darah">Golongan Darah</label>
                            <select class="form-control" id="golongan_darah" name="golongan_darah">
                                <option value="">Pilih Golongan Darah</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="AB">AB</option>
                                <option value="O">O</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="orang_tua_id">Nama Orang Tua</label>
                            <select class="form-control" id="orang_tua_id" name="orang_tua_id" required>
                                <option value="">Pilih Nama Orang Tua</option>
                                <?php
                                $query_orang_tua = "SELECT no, nama_ibu, nama_suami FROM orang_tua";
                                $result_orang_tua = $koneksi->query($query_orang_tua);

                                while ($row_orang_tua = $result_orang_tua->fetch_assoc()) {
                                    echo "<option value='{$row_orang_tua['no']}'>{$row_orang_tua['nama_ibu']} & {$row_orang_tua['nama_suami']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="kelola-anak.php" class="btn btn-secondary">Kembali</a>
                        </div>
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