<?php
session_start();

// Cek apakah pengguna sudah login
if (!isset($_SESSION['hak_akses']) || empty($_SESSION['hak_akses'])) {
    header("Location: ../../index.php?pesan=gagal");
    exit;
}

include '../../koneksi.php';

// Inisialisasi variabel
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
                <?php include '../../layout/topbar-admin.php'; ?>
                <div class="container-fluid">
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Tambah Imunisasi</h1>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="card mb-5">
                                <div class="card-body">
                                    <form method="post" action="proses-imunisasi.php">
                                        <div class="form-group">
                                            <label for="id_anak">Nama Anak:</label>
                                            <select id="id_anak" name="id_anak" class="form-control" required onchange="updateNamaIbu()">
                                                <option value="">Pilih Nama Anak</option>
                                                <?php echo $anak_options; ?>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="id_ibu">Nama Ibu:</label>
                                            <input type="text" id="id_ibu" name="id_ibu" class="form-control" readonly>
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
                                            <label for="tgl_imunisasi">Tanggal Imunisasi:</label>
                                            <input type="date" id="tgl_imunisasi" name="tgl_imunisasi" class="form-control" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="usia">Usia Anak (Bulan):</label>
                                            <input type="number" id="usia" name="usia" class="form-control" readonly>
                                        </div>

                                        <div class="form-group">
                                            <label for="jenis_imunisasi">Jenis Imunisasi:</label>
                                            <select id="jenis_imunisasi" name="jenis_imunisasi" class="form-control" required>
                                                <option value="">Pilih Jenis Imunisasi</option>
                                                <option value="HB">HB</option>
                                                <option value="Polio">Polio</option>
                                                <option value="Campak">Campak</option>
                                                <option value="DPT">DPT</option>
                                                <option value="BCG">BCG</option>
                                            </select>
                                        </div>

                                        <div class="form-group">
                                            <label for="vitamin">Vitamin:</label>
                                            <select id="vitamin" name="vitamin" class="form-control">
                                                <option value="">Pilih Vitamin</option>
                                                <option value="Merah">Merah</option>
                                                <option value="Biru">Biru</option>
                                            </select>
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
            <?php include '../../layout/footer.php'; ?>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const namaAnakSelect = document.getElementById('id_anak');
            const namaIbuInput = document.getElementById('id_ibu');
            const usiaInput = document.getElementById('usia');
            const tglImunisasiInput = document.getElementById('tgl_imunisasi');

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
                const tglImunisasi = new Date(tglImunisasiInput.value || new Date());
                const usia = Math.floor((tglImunisasi - tanggalLahir) / (1000 * 60 * 60 * 24 * 30.4375));
                usiaInput.value = usia;
            }

            namaAnakSelect.addEventListener('change', updateNamaIbu);
            tglImunisasiInput.addEventListener('change', updateNamaIbu);
        });
    </script>
</body>

</html>