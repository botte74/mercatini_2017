<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/** controllo di che tipo è l'utente*/
	if ($_SESSION['tipo'] != "admin" && $_SESSION['tipo'] != "abbina" && $_SESSION['tipo'] != "distribuzione") {
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
		<title>Abbinamento</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/select2.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link href="css/style3.css" rel="stylesheet" media="screen">
		<link rel="icon" href="./img/icon.ico" />

		<!-- jQuery e plugin JavaScript  -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/select2.min.js"></script>
		<script src="js/abbina.js"></script>
	</head>

	<body>

		<?php
		/**
		 * includi la barra dei pulsanti
		 */
		include("includes/testata.php");
		?>

		<!-- Contenitore principale pagina -->
		<div class="container-fluid">
			<form id="ricerca" name="ordini">
				<div class="row">
					<div class="col-xs-12 col-sm-6">
						<!-- Sezione selezione gruppo tavoli -->
						<div class="col-xs-12 col-sm-12">
							<select name="gruppi" class="js-placeholder-gruppo-hide-search" style="width: 100%">
								<option value=""></option>
							</select>
						</div>
						<!-- Sezione selezione gruppo tavoli -->
						<!-- Sezione selezione tavolo -->
						<div class="col-xs-12 col-sm-12">
							<select name="sel2" onchange="aggiornaHidden(this)" class="js-placeholder-tavolo-hide-search" style="width: 100%">
								<option value=""></option>
							</select>
							<input type="hidden" id="tavolo" name="sel2_value">
							<input type="hidden" name="sel2_text">
						</div>
						<!-- Sezione selezione tavolo -->
					</div>
					<!-- Sezione selezione ordine -->
					<div class="col-xs-12 col-sm-6">
						<div class="col-xs-12 col-sm-12">
						<select name="sel1" onchange="aggiornaHidden(this)" class="js-placeholder-ordine-hide-search" style="width: 100%">
							<option value="" selected></option>
						</select>
						<input type="hidden" id = "ordine" name="sel1_value">
						<input type="hidden" name="sel1_text">
						</div>
					</div> <!-- Sezione selezione ordine -->
				</div>
				<!-- Pulsante abbinamento e ritorno stato -->
				<div id="lancia" class="col-sm-8 col-sm-offset-2">
					<input type="button" onclick="conferma();" class="btn btn-success btn-large btn-block" value="Abbina ordine - tavolo"/>
				</div> <!-- Pulsante abbinamento e ritorno stato -->
			</form>
			<div id="divrisposta" class="col-sm-8 col-sm-offset-2 h4 text-center">
					STATO
			</div>
		</div> <!-- Contenitore principale pagina -->
	</body>
</html>
