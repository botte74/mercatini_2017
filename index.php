<?php
	include_once("includes/config.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Pagina iniziale</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link rel="icon" href="./img/icon.ico" />

		<!-- jQuery e plugin JavaScript  -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/autofocus.js"></script>
	</head>

	<body>
		<?php
		/** includi la barra dei pulsanti */
		include("includes/testata.php");
		?>

		<!-- Schermata Login -->
		<div class="container-fluid">
			<div class="row">
				<div class="col-sm-8 col-sm-offset-2">
				<?php
					if (!isset($_SESSION['user'])) {
				?>
					<p class="h2 text-center">Inserire nome utente e password</p>
					<?php
					} else {
					?>
						<p class="h2 text-center"><?php echo "Benvenuto " . $_SESSION['user'];?></p>
				<?php
					}
				?>
				</div>
			</div>

			<!-- Form Login -->
			<?php
				if (basename($_SERVER["PHP_SELF"]) == "index.php") {
					if (!isset($_SESSION['user'])) {
			?>
			<div class="container">
				<div class="col-sm-8 col-sm-offset-2">
					<form action="includes/login.php" method="POST">
						<fieldset>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" class="form-control" id="nome_utente" placeholder="Nome utente" name="user">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<div class="form-group input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
										<input type="password" class="form-control" id="password" placeholder="Password" name="password">
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<button type="submit" class="btn btn-success btn-large btn-block" value="LOGIN">
										<span class="glyphicon glyphicon-thumbs-up" aria-hidden="true"></span>
										 LOGIN
									</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div> <!-- Form Login -->
			<?php
				} else {
			?>
			<!-- Form Logout -->
			<div class="container">
				<div class="col-sm-8 col-sm-offset-2">
					<form action="includes/logout.php" method="POST">
						<fieldset>
							<div class="row">
								<div class="col-sm-8 col-sm-offset-2">
									<input type="hidden" name="logout" value="true">
									<button type="submit" class="btn btn-success btn-large btn-block" value="LOGOUT">
										<span class="glyphicon glyphicon-off" aria-hidden="true"></span>
										 LOGOUT
									</button>
								</div>
							</div>
						</fieldset>
					</form>
				</div>
			</div> <!-- Form Logout -->
			<?php
				}
			}
			?>
		</div> <!-- Schermata Login -->
  </body>
</html>
