<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">

	<title>Sistem Informasi Posyandu | Login</title>

	<!-- Custom fonts for this template -->
	<link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

	<!-- Custom styles for this template -->
	<link href="css/sb-admin-2.min.css" rel="stylesheet">

	<style>
		/* CSS untuk gradasi warna latar belakang */
		.bg-gradient {
			background: linear-gradient(to right, #D0F0C0, #FFFFFF);
			/* Gradasi dari hijau muda ke putih */
			position: fixed;
			right: 0;
			bottom: 0;
			width: 100%;
			height: 100%;
			z-index: -1;
		}

		.container {
			position: relative;
			z-index: 1;
		}

		.alert {
			position: relative;
			z-index: 2;
		}

		.logo-container {
			text-align: center;
			margin-bottom: 20px;
		}

		.logo-container img {
			max-width: 100%;
			height: auto;
			max-width: 200px;
		}

		.card {
			background-color: rgba(255, 255, 255, 0.9);
		}

		@media (max-width: 576px) {
			.container {
				padding: 20px;
			}
		}
	</style>

</head>

<body>

	<!-- Elemen latar belakang gradasi -->
	<div class="bg-gradient"></div>

	<!-- Pengecekan User 'Pesan' -->
	<?php
	if (isset($_GET['pesan'])) {
		if ($_GET['pesan'] == 'error') {
			echo "<div class='alert alert-danger text-center' role='alert'>Username dan Password tidak sesuai !</div>";
		}
	}
	?>

	<div class="container">

		<!-- Outer Row -->
		<div class="row justify-content-center">

			<div class="col-xl-4 col-lg-4 col-md-9">

				<div class="card o-hidden border-0 shadow-lg my-5">
					<div class="card-body p-0">
						<div class="logo-container">
							<img src="img/Posyandu.png" alt="Logo Posyandu">
						</div>
						<!-- Nested Row within Card Body -->
						<div class="row">
							<div class="col">
								<div class="p-5">
									<form class="user" action="cek_login.php" method="post">
										<div class="form-group">
											<label for="username">Username</label>
											<input type="text" class="form-control form-control-user" name="username" id="username" placeholder="Masukkan username" required>
										</div>
										<div class="form-group">
											<label for="password">Password</label>
											<input type="password" class="form-control form-control-user" name="password" id="password" placeholder="Masukkan Password" required>
										</div>
										<input type="submit" class="btn btn-info btn-user btn-block" value="LOGIN" />
										<hr>
									</form>
									<div class="text-center">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

			</div>

		</div>

	</div>

	<!-- Bootstrap core JavaScript -->
	<script src="vendor/jquery/jquery.min.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

	<!-- Core plugin JavaScript -->
	<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

	<!-- Custom scripts for all pages -->
	<script src="js/sb-admin-2.min.js"></script>

</body>

</html>