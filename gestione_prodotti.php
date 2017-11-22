<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/** controllo di che tipo è l'utente*/
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
		<title>Gestione attivazioni</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link href="css/style3.css" rel="stylesheet" media="screen">
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

		<!-- contenitore principale pagina -->
		<div class="container-fluid">

			<?php
				/** recupero dati dalle tabelle */
				if (isset($_POST['update1'])) {
					$se_numero = $_POST['se_numero'];
					$se_attiva = $_POST['se_attiva'];
					$se_modifica = $_POST['se_modifica'];

					if ($se_modifica <> '') {
						$se_attiva = $se_modifica;
						$UpdateQuery1 = sprintf("
							UPDATE serate
							SET se_attiva = '%s'
							WHERE se_numero = '%s'",
							$se_attiva, $se_numero);

						/** controllo errore */
						if (!$mysqli->query($UpdateQuery1)) {
							echo "</br>";
							echo mysqli_error($mysqli);
						}
					}
				}

				/** recupero dati dalle tabelle */
				if (isset($_POST['update2'])) {
					$pr_codice = $_POST['pr_codice'];
					$pr_attivo = $_POST['pr_attivo'];
					$pr_modifica = $_POST['pr_modifica'];

					if ($pr_modifica <> '') {
						$pr_attivo = $pr_modifica;
						$UpdateQuery2 = sprintf("
							UPDATE articoli
							SET ar_attivo = '%s'
							WHERE ar_codice = '%s'",
							$pr_attivo, $pr_codice);

						/** controllo errore */
						if (!$mysqli->query($UpdateQuery2)) {
							echo "</br>";
							echo mysqli_error($mysqli);
						}
					}
				}
			?>

			<!-- tabella prodotti -->

			<?php
				/** codice per recuperare tutti i prodotti dal database */
				$sql2 = "SELECT * FROM articoli
					JOIN gruppi ON ar_gruppo = gr_codice
					ORDER BY gr_ordinamento, ar_ordinamento";
				$myData2 = $mysqli->query($sql2);
				$gruppo = "";

				/** generazione tabella prodotti */
				while ($record2 = $myData2->fetch_array(MYSQLI_ASSOC)) {
					if ($record2['ar_gruppo'] != $gruppo) {
			?>
					<div class="row">
						<div class="col-lg-10 col-lg-offset-1">
							<div class="h2">
								<p class="text-center">
									<?php
										echo $record2['ar_gruppo'];
										$gruppo = $record2['ar_gruppo'];
									?>
								</p>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-lg-10 col-lg-offset-1">
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-10">
										<div class="col-lg-2">
											<div class="h3">
												<p class="text-center">Codice</p>
											</div>
										</div>
										<div class="col-lg-6">
											<div class="h3">
												<p class="text-center">Descrizione</p>
											</div>
										</div>
										<div class="col-lg-1">
											<div class="h3">
												<p class="text-center">Stato</p>
											</div>
										</div>
										<div class="col-lg-3">
											<div class="h3">
												<p class="text-center">Modifica</p>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="h3">
											<p class="text-center">Aggiorna</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<?php
					}
					?>
					<div class="row">
						<div class="col-lg-10 col-lg-offset-1">
							<form action="gestione_prodotti.php" method="post">
								<div class="container-fluid">
									<div class="row">
										<div class="col-lg-10">
											<div class="row">
												<div class="col-lg-2">
													<?php
											  		echo sprintf("<input type=text class=form-control name=pr_codice value=\"%s\" readonly>", $record2['ar_codice']);
													?>
												</div>
												<div class="col-lg-6">
													<?php
											  		echo sprintf("<input type=text class=form-control name=pr_descrizione value=\"%s\" readonly>", $record2['ar_descrizione']);
													?>
												</div>
												<div class="col-lg-1">
													<?php
														echo sprintf("<input type=text class=\"form-control text-center\" name=pr_attivo value=\"%s\" readonly>", $record2['ar_attivo']);
													?>
												</div>
												<div class="col-lg-3">
													<?php
														echo sprintf("<input type=text class=\"form-control text-center\" name=pr_modifica value='' placeholder='S->ON spazio->OFF'>");
													?>
												</div>
											</div>
										</div>
										<div class="col-lg-2">
											<button type=submit class="btn btn-success btn-block" name=update2 value=Aggiorna>Aggiorna</button>
										</div>
									</div>
								</div>
							</form>
						</div>
					</div>
				<?php
				}
				$myData2->close();
				?>
			<!-- tabella prodotti -->

		</div> <!-- contenitore principale pagina -->
	</body>
</html>
