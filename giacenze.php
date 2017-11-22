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
		<title>Giacenze</title>
	
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

			/** controllo se è attiva una serata */
			$query = "SELECT * FROM serate WHERE se_attiva = 'S'";
			$result = $mysqli->query($query);
			if ($result->num_rows == 0) {
				echo "Nessuna serata attiva";
			} else {
				$row = $result->fetch_assoc();
				$serata = $row['se_numero'];
				$result->close();
			}

			/** ricarico i valori dopo un aggiornamento delle quantità */
			if (isset($_POST['update'])) {
				$codice = $_POST['codiceprodotto'];
				$giacenza = $_POST['giacenza'];
				$aggiunta = $_POST['aggiunta'];
				$totale = $_POST['totale'];
				$variazione = 0;
				if ($aggiunta <> 0) {
					$variazione = $aggiunta;
				} else {
					$variazione = $totale - $giacenza;
				}

				$UpdateQuery = sprintf("
					UPDATE magazzino
					SET ma_giacenza = ma_giacenza + '%s'
					WHERE ma_codiceprodotto='%s'",
					$variazione, $codice);

				// controllo errore
				if (!$mysqli->query($UpdateQuery)) {
					echo "</br>";
					echo mysqli_error($mysqli);
				}
			}

			/** estraggo tutti i prodotti a magazzino dal database */
			$sql = "SELECT * FROM magazzino
				JOIN gruppi_magazzino
				ON ma_gruppi = gr_ma_descrizione
				ORDER BY gr_ma_ordinamento ASC,
				ma_giacenza ASC";
			$myData = $mysqli->query($sql);
		?>

		<!-- tabella prodotti -->

		<?php
			$gruppo = "";

			// generazione tabella prodotti magazzino
			while ($record = $myData->fetch_array(MYSQLI_ASSOC)) {
				if ($record['ma_gruppi'] != $gruppo) {
		?>
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<div class="h2">
							<p class="text-center">
								<?php
									echo $record['gr_ma_descrizione'];
									$gruppo = $record['ma_gruppi'];
								?>
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-8 col-lg-offset-2">
						<div class="container-fluid">
							<div class="row">
								<div class="col-lg-10">
									<div class="col-lg-6">
										<div class="h3">
											<p class="text-left">Descrizione</p>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="h3">
											<p class="text-center">Giacenza</p>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="h3">
											<p class="text-center">Aggiunta</p>
										</div>
									</div>
									<div class="col-lg-2">
										<div class="h3">
											<p class="text-center">Totale</p>
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
					<div class="col-lg-8 col-lg-offset-2">
						<form action="giacenze.php" method="post">
							<div class="container-fluid">
								<div class="row">
									<div class="col-lg-10">
										<div class="row">
											<div class="col-lg-6">
												<?php
													echo sprintf("<input type=text class=form-control name=descrizione value=\"%s\" readonly>", $record['ma_descrizione']);
												?>
											</div>
											<div class="col-lg-2">
												<?php
													echo sprintf("<input type=text class=form-control name=giacenza value=\"%s\" readonly>", $record['ma_giacenza']);
												?>
											</div>
											<div class="col-lg-2">
												<?php
													echo sprintf("<input type=text class=\"form-control text-center\" name=aggiunta value=\"\">");
												?>
											</div>
											<div class="col-lg-2">
												<?php
													echo sprintf("<input type=text class=\"form-control text-center\" name=totale value=\"\">");
												?>
											</div>
											<div>
												<?php
													echo sprintf("<input type=hidden name=codiceprodotto value=\"%s\" readonly>", $record['ma_codiceprodotto']);
												?>
											</div>
										</div>
									</div>
									<div class="col-lg-2">
										<button type=submit class="btn btn-success btn-block" name=update value=Aggiorna>Aggiorna</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			<?php
			}
			$myData->close();
			?>
			<!-- tabella prodotti -->
	</body>
</html>