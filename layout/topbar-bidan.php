<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Topbar</title>

    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    <!-- FontAwesome CSS -->
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        body {
            margin: 0;
        }

        .topbar {
            background-color: transparent;
            border-bottom: 1px solid #e3e6f0;
            /* Optional: border bawah untuk memisahkan topbar */
        }

        .topbar-header {
            text-align: center;
            margin-bottom: 10px;
            /* Jarak antara header dan menu navigasi */
            color: #333333;
            /* Warna tulisan untuk header */
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
            /* Warna tulisan hitam */
            font-size: 16px;
            padding: 10px 15px;
            margin: 0 5px;
        }

        .navbar-nav .nav-link:hover {
            color: #0056b3 !important;
            /* Warna tulisan saat hover (misalnya biru) */
            background-color: transparent;
        }

        .user-info {
            color: #000000 !important;
            /* Warna tulisan hitam untuk info pengguna */
        }

        .logout-btn {
            color: #ffffff;
            background-color: #dc3545;
            /* Warna merah untuk tombol logout */
            border: none;
        }

        .logout-btn:hover {
            background-color: #c82333;
            /* Warna merah gelap untuk hover */
        }
    </style>
</head>

<body>
    <!-- Topbar Header -->
    <div class="topbar-header">
        <h1>Posyandu LPK Subarang</h1>
    </div>

    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav">
            <!-- Menu Items -->
            <li class="nav-item">
                <a class="nav-link" href="../bidan/index-bidan.php">Home</a>
            </li>


            <li class="nav-item">
                <a class="nav-link" href="kelola-imunisasi.php">Imunisasi</a>
            </li>

            <!-- User Information -->


            <!-- Logout Button -->
            <li class="nav-item">
                <a class="nav-link" href="../../logout.php" data-toggle="modal" data-target="#logoutModal">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                    <span>Logout</span></a>
            </li>
        </ul>
    </nav>
    <!-- End of Topbar -->

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>