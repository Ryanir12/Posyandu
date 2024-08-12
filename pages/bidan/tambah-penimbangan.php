<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['hak_akses']) || empty($_SESSION['hak_akses'])) {
    header("Location: ../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

// Inisialisasi variabel untuk menampung opsi select
$anak_options = '';
$bidan_options = '';
$error_message = '';
$petugas_nama = '';

// Mengambil data anak
$query_anak = "SELECT id, nama_anak, tanggal_lahir, id_ibu FROM anak";
$result_anak = $koneksi->query($query_anak);
if ($result_anak) {
    while ($row = $result_anak->fetch_assoc()) {
        $anak_options .= "<option value='{$row['id']}' data-tanggal-lahir='{$row['tanggal_lahir']}' data-id-ibu='{$row['id_ibu']}'>{$row['nama_anak']}</option>";
    }
} else {
    $error_message = "Gagal mengambil data anak: " . $koneksi->error;
}

// Mengambil data bidan
$query_bidan = "SELECT id_bidan, nama_bidan FROM bidan";
$result_bidan = $koneksi->query($query_bidan);
if ($result_bidan) {
    while ($row = $result_bidan->fetch_assoc()) {
        $bidan_options .= "<option value='{$row['id_bidan']}'>{$row['nama_bidan']}</option>";
    }
} else {
    $error_message .= "<br>Gagal mengambil data bidan: " . $koneksi->error;
}

// Mengambil nama petugas berdasarkan username
$username = $_SESSION['username'];
$query_petugas = "SELECT nama FROM user WHERE username = ?";
$stmt = $koneksi->prepare($query_petugas);

if ($stmt === false) {
    $error_message .= "<br>Gagal menyiapkan statement: " . $koneksi->error;
} else {
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result_petugas = $stmt->get_result();

    if ($result_petugas->num_rows > 0) {
        $row = $result_petugas->fetch_assoc();
        $petugas_nama = htmlspecialchars($row['nama']);
    } else {
        $error_message .= "<br>Nama petugas tidak ditemukan.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php include '../../layout/header.php'; ?>
    <link rel="stylesheet" href="../../path/to/your/css/style.css">
    <style>
        /* Gaya umum untuk form dan elemen */
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }

        .form-group label {
            flex: 0 0 200px;
            margin-bottom: 5px;
            text-align: right;
            margin-right: 10px;
        }

        .form-group .form-control {
            text-align: left;
            /* Rata kiri untuk teks dalam input */
        }

        .btn-group {
            display: flex;
            gap: 5px;
            text-align: left;
            /* Rata kiri untuk tombol */
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

        .alert {
            margin-top: 1rem;
            padding: 1rem;
            border-radius: 0.25rem;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Mengatur teks dan form input agar rata kiri */
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            text-align: left;
            /* Rata kiri untuk teks dalam input dan textarea */
            width: 100%;
            /* Memastikan input mengisi lebar kontainer */
        }
    </style>
</head>

<body id="page-top">

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
                                    <?php if ($error_message): ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php echo htmlspecialchars($error_message); ?>
                                        </div>
                                    <?php endif; ?>

                                    <form method="post" action="proses-penimbangan.php">
                                        <div class="form-group">
                                            <label for="nama_anak">Nama Anak:</label>
                                            <select id="nama_anak" name="id_anak" class="form-control" required onchange="updateNamaIbu()">
                                                <option value="">Pilih Nama Anak</option>
                                                <?php echo $anak_options; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="nama_ibu">Nama Ibu:</label>
                                            <input type="text" id="nama_ibu" name="nama_ibu" class="form-control" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="bidan">Nama Bidan:</label>
                                            <select id="bidan" name="bidan" class="form-control" required>
                                                <option value="">Pilih Nama Bidan</option>
                                                <?php echo $bidan_options; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="petugas">Nama Petugas:</label>
                                            <input type="text" id="petugas" name="petugas" class="form-control" value="<?php echo htmlspecialchars($petugas_nama); ?>" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="tgl_timbangan">Tanggal Penimbangan:</label>
                                            <input type="date" id="tgl_timbangan" name="tgl_timbangan" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="usia">Usia Anak (Bulan):</label>
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
                                            <label>Deteksi Pertumbuhan:</label>
                                            <div>
                                                <label>
                                                    <input type="radio" name="deteksi" value="Ideal" id="radio_ideal" /> Ideal
                                                </label>
                                                <label>
                                                    <input type="radio" name="deteksi" value="Tidak Ideal" id="radio_not_ideal" /> Tidak Ideal
                                                </label>
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
    <!-- End of Content Wrapper -->

    <?php include '../../layout/footer.php'; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaAnakSelect = document.getElementById('nama_anak');
            const namaIbuInput = document.getElementById('nama_ibu');
            const usiaInput = document.getElementById('usia');
            const bbInput = document.getElementById('bb');
            const tbInput = document.getElementById('tb');
            const idealIndicator = document.getElementById('ideal');
            const notIdealIndicator = document.getElementById('not_ideal');
            const tglTimbanganInput = document.getElementById('tgl_timbangan');

            function updateNamaIbu() {
                const selectedOption = namaAnakSelect.options[namaAnakSelect.selectedIndex];
                const idIbu = selectedOption.getAttribute('data-id-ibu');

                // Fetch Nama Ibu based on id_ibu
                if (idIbu) {
                    fetch(`get_nama_ibu.php?id=${idIbu}`)
                        .then(response => response.json())
                        .then(data => {
                            namaIbuInput.value = data.nama_ibu;
                        })
                        .catch(error => {
                            console.error('Error fetching nama ibu:', error);
                        });
                }

                // Calculate usia in months
                const tanggalLahir = new Date(selectedOption.getAttribute('data-tanggal-lahir'));
                const tglTimbangan = new Date(tglTimbanganInput.value || new Date());
                const usia = Math.floor((tglTimbangan - tanggalLahir) / (1000 * 60 * 60 * 24 * 30.4375));
                usiaInput.value = usia;
            }

            function updateDeteksi() {
                const bb = parseFloat(bbInput.value);
                const tb = parseFloat(tbInput.value);
                const ideal = document.getElementById('radio_ideal').checked;
                const notIdeal = document.getElementById('radio_not_ideal').checked;

                if (ideal) {
                    idealIndicator.classList.add('active');
                    notIdealIndicator.classList.remove('active');
                } else if (notIdeal) {
                    idealIndicator.classList.remove('active');
                    notIdealIndicator.classList.add('active');
                } else {
                    idealIndicator.classList.remove('active');
                    notIdealIndicator.classList.remove('active');
                }
            }

            namaAnakSelect.addEventListener('change', updateNamaIbu);
            bbInput.addEventListener('input', updateDeteksi);
            tbInput.addEventListener('input', updateDeteksi);
            tglTimbanganInput.addEventListener('change', updateNamaIbu);
            document.querySelectorAll('input[name="deteksi"]').forEach(radio => {
                radio.addEventListener('change', updateDeteksi);
            });
        });
    </script>

</body>

</html>