<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/**
	* codice php per estrarre dalle tabelle i vari ordini con gli stati
	* che ci interessano e presentarli in tabella
	*/
  $serata=0;
	/** trovo la serata */
	$query = "SELECT * FROM serate WHERE se_attiva = 'S'";
	$result = $mysqli->query($query);
	if ($result->num_rows == 0) {
		echo "Nessuna serata attiva";
	} else {
		$row = $result->fetch_assoc();
		$serata = $row['se_numero'];
	}
	$result->close();

	//AGGIUNTA CARNE IN GRIGLIA
	if(isset($_GET['prodAdd']) && isset($_GET['quantAdd'])){
		$prodotto=$_GET['prodAdd'];
		$quant=(integer)$_GET['quantAdd'];
		$query=sprintf("INSERT INTO griglie SET gri_serata = %s, gri_prodotto = '%s', gri_quantita = %d, gri_richiesta = false ON DUPLICATE KEY UPDATE gri_quantita = gri_quantita + %d", $serata, $prodotto, $quant, $quant);
		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		}
		// aggiorno prodotto collegato
		$query = sprintf("SELECT * FROM griglie_legami WHERE leg_prodotto1 = '%s'",$prodotto);
		$result = $mysqli->query($query);
		while($row = $result->fetch_assoc()) {
			$prodotto2 = $row['leg_prodotto2'];
			$quant2 = $quant * $row['leg_coefficente'] * - 1;
			$query=sprintf("INSERT INTO griglie SET gri_serata = %s, gri_prodotto = '%s', gri_quantita = %d, gri_richiesta = false ON DUPLICATE KEY UPDATE gri_quantita = gri_quantita + %d", $serata, $prodotto2, $quant2, $quant2);
			if (!$mysqli->query($query)) {
				echo $mysqli->error;
			}
		}
	}

	//ELIMINA CARNE GRIGLIA
	if(isset($_GET['prodDel'])){
		$prodotto=$_GET['prodDel'];
		$query=sprintf("INSERT INTO griglie SET gri_serata = %s, gri_prodotto = '%s', gri_quantita = 0, gri_richiesta = false ON DUPLICATE KEY UPDATE gri_quantita = 0", $serata, $prodotto);
		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		}
	}

	//togliere colore rosso dallo sfondo
	if(isset($_GET['noProblem'])){
		$prodotto=$_GET['noProblem'];
		$query=sprintf("INSERT INTO griglie SET gri_serata = %s, gri_prodotto = '%s', gri_quantita = 0, gri_richiesta = false ON DUPLICATE KEY UPDATE gri_richiesta= false", $serata, $prodotto);
		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		}
	}
	
		//togliere colore rosso dallo sfondo
	if(isset($_GET['richiesta'])){
		$prodotto=$_GET['richiesta'];
		$query=sprintf("INSERT INTO griglie SET gri_serata = %s, gri_prodotto = '%s', gri_quantita = 0, gri_richiesta = true ON DUPLICATE KEY UPDATE gri_richiesta= true", $serata, $prodotto);
		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		} else {
			$query = "SELECT * FROM prodotti WHERE pr_codice = '" . $prodotto . "'";
			$result = $mysqli->query($query);
			if ($result->num_rows == 0) {
				echo "Prodotto non valido";
			} else {
				$row = $result->fetch_assoc();
				$descrizione = $row['pr_descrizione'];
			}
			$result->close();
			echo "Richiesta di " . $descrizione . " inviata correttamente!";
		}
	}
?>
