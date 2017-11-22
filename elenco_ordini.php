<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/** controllo di che tipo è l'utente */
	if ($_SESSION['tipo'] != "admin" && $_SESSION['tipo'] != "bar" && $_SESSION['tipo'] != "bibite" && $_SESSION['tipo'] != "cassa" && $_SESSION['tipo'] != "distribuzione") {
		header("location: index.php");
		exit;
	}

	/** variabile che definisce tempo di ricarica della barra */
	$sql_tempo="SELECT * FROM tempi WHERE tipo='elenco_ordini'";
	$query_tempo=$mysqli->query($sql_tempo);
	$result=mysqli_fetch_array($query_tempo);
	$tempo=intval($result['numero'])*10;
?>

<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Elenco Ordini</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" media="screen">
		<link href="css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link href="css/style4.css" rel="stylesheet" media="screen">
		<link rel="icon" href="./img/icon.ico" />

		<!-- jQuery e plugin JavaScript  -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>
		<script src="js/elenco_ordini.js"></script>
	</head>

	<body>

		<?php
		/** includi la barra dei pulsanti */
		include("includes/testata.php");
		?>

			<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<!-- barra di avanzamento -->
					<div class="progress progress-striped active" style="width:auto">
						<div class="progress-bar progress-bar-success" role="progressbar" data-transitiongoal="100" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
							<span>0%<span>
						</div>
					</div>
					<script type="text/javascript" language="javascript">
						$(function() {
							var $bar = $('.progress-bar');
							var larghezza = $('.progress').width();
							var percentuale = 0;
							var progress = setInterval(function() {
								percentuale = percentuale + 1;
								if (percentuale > 100) {
									percentuale = 0;
									aggiornaTabellaOrdini()
								}
								$bar.width(Math.floor(percentuale * larghezza / 100));
								$bar.html("<span>" + Math.floor(percentuale) + "%" + "</span>");
							}, <?php echo $tempo; ?>);
						});
					</script>
					<!-- barra di avanzamento -->
				</div>
			</div>
			<div class="row">
				<h4>
					<div id="stato" class="col-lg-12">
						Stato
					</div>
				</h4>
			</div>
			<div class="row">
				<div id="tabella" class="col-lg-12">
					<script>aggiornaTabellaOrdini()</script>
				</div>
			</div>
		</div>
	</body>
</html>
