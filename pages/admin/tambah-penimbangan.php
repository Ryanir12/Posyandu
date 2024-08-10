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

    // Get data for select fields
    $query_anak = "SELECT id, nama_anak, tanggal_lahir FROM anak";
    $query_orang_tua = "SELECT nama_ibu FROM orang_tua";
    $query_bidan = "SELECT id_bidan, nama_bidan FROM bidan";

    $result_anak = $koneksi->query($query_anak);
    $result_orang_tua = $koneksi->query($query_orang_tua);
    $result_bidan = $koneksi->query($query_bidan);

    if (!$result_anak || !$result_orang_tua || !$result_bidan) {
        die("Query gagal: " . $koneksi->error);
    }
    ?>

    <div id="wrapper">

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <?php include '../../layout/topbar-admin.php'; ?>

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Penimbangan</h1>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-body">
                                    <form method="post" action="proses-penimbangan.php">
                                        <div class="form-group">
                                            <label for="nama_anak">Nama Anak:</label>
                                            <select id="nama_anak" name="id_anak" class="form-control" required onchange="updateUsia()">
                                                <option value="">Pilih Nama Anak</option>
                                                <?php while ($row_anak = $result_anak->fetch_assoc()) { ?>
                                                    <option value="<?php echo htmlspecialchars($row_anak['id']); ?>" data-tanggal-lahir="<?php echo htmlspecialchars($row_anak['tanggal_lahir']); ?>">
                                                        <?php echo htmlspecialchars($row_anak['nama_anak']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="nama_ibu">Nama Ibu:</label>
                                            <select id="nama_ibu" name="nama_ibu" class="form-control" required>
                                                <option value="">Pilih Nama Ibu</option>
                                                <?php while ($row_orang_tua = $result_orang_tua->fetch_assoc()) { ?>
                                                    <option value="<?php echo htmlspecialchars($row_orang_tua['nama_ibu']); ?>">
                                                        <?php echo htmlspecialchars($row_orang_tua['nama_ibu']); ?>
                                                    </option>
                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="bidan">Nama Bidan:</label>
                                            <select id="bidan" name="bidan" class="form-control" required>
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
                                            <label for="tgl_timbangan">Tanggal Penimbangan:</label>
                                            <input type="date" id="tgl_timbangan" name="tgl_timbangan" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="usia">Usia Anak:</label>
                                            <input type="number" id="usia" name="usia" class="form-control" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="bb">Berat Badan (Kg):</label>
                                            <input type="number" step="0.01" id="bb" name="bb" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="tb">Tinggi Badan (Cm):</label>
                                            <input type="number" step="0.01" id="tb" name="tb" class="form-control" required>
                                        </div>

                                        <div class="form-group">
                                            <label for="deteksi">Deteksi Pertumbuhan:</label>
                                            <div id="deteksi">
                                                <span id="ideal" class="indicator"></span> Ideal
                                                <span id="not_ideal" class="indicator"></span> Tidak Ideal
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="ket">Keterangan:</label>
                                            <textarea id="ket" name="ket" class="form-control" rows="3"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        <a href="kelola-penimbangan.php" class="btn btn-secondary">Kembali</a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logout Modal -->
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
    </div>

    <?php include '../../layout/footer.php'; ?>

    <!-- Script to handle detection based on input -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaAnakSelect = document.getElementById('nama_anak');
            const usiaInput = document.getElementById('usia');
            const bbInput = document.getElementById('bb');
            const tbInput = document.getElementById('tb');
            const idealIndicator = document.getElementById('ideal');
            const notIdealIndicator = document.getElementById('not_ideal');

            namaAnakSelect.addEventListener('change', function() {
                const selectedOption = namaAnakSelect.options[namaAnakSelect.selectedIndex];
                const tanggalLahir = selectedOption.getAttribute('data-tanggal-lahir');
                if (tanggalLahir) {
                    const usia = hitungUsia(tanggalLahir);
                    usiaInput.value = usia;
                } else {
                    usiaInput.value = '';
                }
            });

            function hitungUsia(tanggalLahir) {
                const today = new Date();
                const birthDate = new Date(tanggalLahir);
                let age = today.getFullYear() - birthDate.getFullYear();
                const m = today.getMonth() - birthDate.getMonth();
                if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                return age;
            }

            function updateDeteksi() {
                const bb = parseFloat(bbInput.value);
                const tb = parseFloat(tbInput.value);
                let deteksi = '';

                if (bb > 0 && tb > 0) {
                    const imt = bb / ((tb / 100) ** 2); // Convert cm to meters
                    if (imt >= 18.5 && imt <= 24.9) {
                        deteksi = 'Ideal';
                        idealIndicator.classList.add('active');
                        notIdealIndicator.classList.remove('active');
                    } else {
                        deteksi = 'Tidak Ideal';
                        notIdealIndicator.classList.add('active');
                        idealIndicator.classList.remove('active');
                    }
                } else {
                    idealIndicator.classList.remove('active');
                    notIdealIndicator.classList.remove('active');
                }
            }

            bbInput.addEventListener('input', updateDeteksi);
            tbInput.addEventListener('input', updateDeteksi);
        });
    </script>
</body>

</html>