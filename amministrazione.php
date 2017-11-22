<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/** controllo di che tipo è l'utente */
	if ($_SESSION['tipo'] != "admin") {
		header("location: index.php");
		exit;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Amministrazione</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link rel="icon" href="./img/icon.ico" />

		<!-- jQuery e plugin JavaScript  -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
	<body>

		<?php
		/** includi la barra dei pulsanti */
		include("includes/testata_admin.php");
		?>

		<div class="container-fluid">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2">
					<div class="h3">
						<a href="utenti.php" class="btn btn-default btn-large btn-block">
							<h4>
								GESTIONE UTENTI
							</h4>
						</a>
					</div>
					<div class="h3">
						<a href="serate.php" class="btn btn-success btn-large btn-block">
							<h4>
								GESTIONE SERATE
							</h4>
						</a>
					</div>
					<div class="h3">
						<a href="rendiconto.php" class="btn btn-primary btn-large btn-block">
							<h4>
								RIEPILOGO
							</h4>
						</a>
					</div>
					<div class="h3">
						<a href="gestione_prodotti.php" class="btn btn-warning btn-large btn-block">
							<h4>
								ATTIVAZIONE PRODOTTI
							</h4>
						</a>
					</div>
					<div class="h3">
						<a href="giacenze.php" class="btn btn-danger btn-large btn-block">
							<h4>
								GESTIONE GIACENZE
							</h4>
						</a>
					</div>
					<div class="h3">
						<a href="stampa_distribuzione_sagra.php" class="btn btn-info btn-large btn-block" target="_blank">
							<h4>
								AVVIA STAMPE SERVER
							</h4>
						</a>
					</div>
					<div class="h3">
						<a href="tempi.php" class="btn btn-default btn-large btn-block">
							<h4>
								GESTIONE TEMPI
							</h4>
						</a>
					</div>
					<div class="h3">
						<a href="gruppi.php" class="btn btn-success btn-large btn-block">
							<h4>
								GESTIONE GRUPPI
							</h4>
						</a>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
