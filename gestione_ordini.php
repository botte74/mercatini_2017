<?php
	include_once("includes/config.php");
	include_once("includes/check_utente.php");

	/** controllo di che tipo è l'utente */
	if ($_SESSION['tipo'] != "admin" && $_SESSION['tipo'] != "bar" && $_SESSION['tipo'] != "bibite" && $_SESSION['tipo'] != "cassa") {
		header("location: index.php");
		exit;
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Gestione Ordini</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<script type="text/javascript" src="js/jquery.js" ></script>
		<script type="text/javascript" src="js/gestione_ordini.js" ></script>
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<link type="text/css" rel="stylesheet" href="css/style2.css">
		<link rel="icon" href="./img/icon.ico" />
	</head>
	<body>
		<div id="wrapper">
			<?php
				/** ricevo parametri da url */
				if (isset($_GET["ordine"])) {
				$ordine = $_GET["ordine"];
				} else {
					echo("<br />Ordine non inserito");
					die();
				}

				$query = "SELECT * FROM ordini WHERE or_numero = " .$ordine;
				$result = $mysqli->query($query);

				if ($result->num_rows == 0)	{
					echo("<br />Ordine non trovato!!");
				} else {
					$row = $result->fetch_assoc();

					/** informazioni sull'utente */
					$nome = $row['or_cliente'];
					$coperto = 0;
					$prezzo = $row['or_totale'];
					$tipo = $row['or_tipo'];
					$coperti = $row['or_coperti'];
			?>
			<div class="ordini">
				<form id='form-ordine' name='form-ordine'>
					<div id="header-ordini">
						<div class="header-ordini stretto centrato">
							<label>Ordine Nr.:</label>
							<strong id="ordine" class="big"><?php echo $ordine; ?></strong>
						</div>
						<div id="divOrdine">
							<input id="nrordine" type="hidden" name="ordine" value="<?php echo $ordine; ?>"/>
						</div>

						<?php
							/** visualizza nome e tipo ordine se admin o cassa */
							if ($_SESSION['tipo'] == 'admin' || $_SESSION['tipo'] == 'cassa') {
						?>

						<div id="divNome" class="header-ordini largo">
							<label>Nome: </label>
							<input class="text_ordine" type="text" id="nome" name="nome" value="<?php echo $nome; ?>"/>
						</div>
						<div id="divCoperti" class="header-ordini stretto">
							<label>Coperti: </label>
							<input class="text_coperti" type="number" id="coperti" name="coperti" value="<?php echo $coperti; ?>"/>
						</div>
						<div class="header-ordini medio">
							<label>Tipologia Ordine</label>
							<select class="tipologia" name="tipo" id="tipo">
								<option value="tavolo" <?php if ($tipo == "T") { echo "selected"; } ?> >Tavolo</option>
								<option value="asporto" <?php if ($tipo == "A") { echo "selected"; } ?> >Asporto</option>
								<option value="bar" <?php if ($tipo == "B") { echo "selected"; } ?> >Bar</option>
							</select>
						</div>

						<?php
							}
						?>

						<div class="header-ordini medio centrato">
							<label>Prezzo Totale:</label>
							<strong class = "big">&euro; </strong>
							<strong id="prezzo" class="big"><?php echo $prezzo; ?> </strong>
						</div>

						<?php
							// nuova stampa ricevuta
							if (($_SESSION['tipo'] == 'admin' || $_SESSION['tipo'] == 'bar' || $_SESSION['tipo'] == 'bibite' || $_SESSION['tipo'] == 'cassa') && $stampe_new) {
							echo "<a class=\"pulsante\" href=\"stampa_ricevuta.php?ordine=$ordine\" target=\"newtab\">Stampa Ricevuta</a>";
							}

							// per tutti nuovo ordine
							echo "<a class=\"pulsante\" href=\"crea_ordine.php\">Nuovo Ordine</a>";

							// admin può fare ordine speciale
							if ($_SESSION['tipo'] == 'admin') {
								echo "<input class=\"articoli\" type=\"button\" value=\"Gratis\" onclick=\"OrdineGratis('$ordine');\" />";
							}

						?>
						<a class="pulsante" href="<?php echo $_SESSION['homepage']; ?>">Home</a>
					</div>
				</form>
			</div>
			<!--<div id = "divritorno">Ritorno </div>-->
			<div id = "divbarra">
				<div id = "divnota">
					Nota
					<input class="text_nota" type="text" id="nota" name="nota" value=""/>
				</div>
				<div id = "divstato">
					STATO
				</div>
			</div>
		
			<?php
				$result->close();
				}
			?>

			<div id="container-center">
			<!-- visualizzo tutti i bottoni -->
				<div id="divarticoli">
				<?php
					$tipoutente = $_SESSION['tipo'];
					$sql = "SELECT * FROM articoli
						JOIN gruppi ON ar_gruppo = gr_codice
						WHERE gr_attivo = 'S'
						AND ar_attivo = 'S'
						AND ar_gruppo IN (
							SELECT ab_gruppo FROM abilitazione
							WHERE ab_tipoutente = '$tipoutente'
							AND ab_attivo = 'S'
						)
						ORDER BY gr_ordinamento, ar_ordinamento";
					$result = $mysqli->query($sql, MYSQLI_USE_RESULT);
					$gruppo = "";
				?>
				<!-- inizio form -->
					<form name="articoli" id="articoli">
					<?php
						while($row = $result->fetch_assoc()) {
							if ($row['gr_codice'] != $gruppo) {
								if ($gruppo != "") {
									echo "<br />";
								}
								echo "<b>" . $row['gr_codice'] . "</b>";
								echo "<br />";
								$gruppo = $row['gr_codice'];
							}
							$codice = $row['ar_codice'];
							$stile = $row['ar_stile'];

							if ($row['ar_gruppo'] == "Aggiunte") {
								echo "<input class=\"$stile articoli\" type=\"button\" value=\"$codice\" onclick=\"aggiunta('$ordine','$codice');\" />";
							} else {
								echo "<input class=\"$stile articoli\" type=\"button\" value=\"$codice\" onclick=\"faiOrdine('$ordine','$codice','1');\" />";
							}
						}
					?>
					</form>
					<?php
						$result->close();
					?>
				</div>
				<div id="divrigheordine">
					<table id="tabellarighe">
						<script>aggiornaRighe(<?php echo $ordine?>)</script>
					</table>
				</div>
			</div>
		</div><!-- Wrapper -->
	</body>
</html>
