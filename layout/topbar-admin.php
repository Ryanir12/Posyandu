<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topbar</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            margin: 0;
        }

        .topbar {
            background-color: transparent;
            border-bottom: 1px solid #e3e6f0;
        }

        .topbar-header {
            text-align: center;
            margin-bottom: 10px;
            color: #333333;
        }

        .topbar-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .navbar-nav {
            margin: 0 auto;
        }

        .navbar-nav .nav-link {
            color: #000000 !important;
            font-size: 16px;
            padding: 10px 15px;
            margin: 0 5px;
        }

        .navbar-nav .nav-link:hover {
            color: #0056b3 !important;
            background-color: transparent;
        }

        .dropdown-menu {
            width: auto;
            min-width: 250px;
            /* atau atur sesuai kebutuhan */
        }

        .logout-btn {
            color: #ffffff;
            background-color: #ffffff;
            border: none;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>

<body>
    <!-- Topbar Header -->
    <div class="topbar-header">
        <h1 class="text-capitalize">Posyandu LPK Subarang</h1>
    </div>

    <!-- Topbar -->
    <nav class="navbar navbar-expand-lg navbar-light topbar mb-4 static-top shadow" aria-label="Main Navigation">
        <div class="container-fluid">
            <!-- Topbar Navbar -->
            <ul class="navbar-nav mx-auto">
                <!-- Menu Items -->
                <li class="nav-item">
                    <a class="nav-link" href="../admin/index-admin.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/kelola-orgtua.php">Orang Tua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../admin/kelola-anak.php">Anak</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola-penimbangan.php">Penimbangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola-imunisasi.php">Imunisasi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola-petugas.php">Petugas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="kelola-bidan.php">Bidan</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Laporan
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="laporan-posyandu-peranak.php">Laporan Posyandu/anak</a></li>
                        <li><a class="dropdown-item" href="laporan-posyandu-perbulan.php">Laporan Hasil Posyandu</a></li>
                        <li><a class="dropdown-item" href="laporan-posyandu-pertahun.php">Laporan Rekapitulasi Posyandu</a></li>
                    </ul>
                </li>

                <!-- Logout Button -->
                <li class="nav-item">
                    <a class="nav-link logout-btn" href="../../logout.php" data-bs-toggle="modal" data-bs-target="#logoutModal">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw me-2"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- End of Topbar -->

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>