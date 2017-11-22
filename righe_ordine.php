<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	// Recupero tipo elaborazione
	if (isset($_GET["tipo"])) {
		$tipo = $_GET["tipo"];
	}
	else {
		echo("<br />Tipo non inserito");
		die();
	}


	// Recupero ordine
	if (isset($_GET["ordine"])) {
		$ordine = $_GET["ordine"];
	}
	else {
		echo("<br />Ordine non inserito");
		die();
	}

	// Recupero riga
	if (isset($_GET["riga"])) {
		$riga = $_GET["riga"];
	}

	// Recupero codice
	if (isset($_GET["codice"])) {
		$codice = $_GET["codice"];
	}
	
	// Recupero nota
	if (isset($_GET["nota"])) {
		$nota = $_GET["nota"];
	}

	switch ($tipo) {
		case "nuova":
			nuova();
			break;
		case "piu":
			piu();
			break;
		case "meno":
			meno();
			break;
		case "aggiunta":
			aggiunta();
			break;
		case "cancella":
			cancella();
			break;
		case "gratis":
			gratis();
			break;
		case "nota":
			nota();
			break;
		case "eliminanota":
			eliminanota();
			break;
	}
	$mysqli->close();

	// nuova riga
	function nuova() {
		global $mysqli, $codice, $ordine;
		$query = sprintf("SELECT * FROM articoli WHERE ar_codice = '%s'", $codice);
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		$row = $result->fetch_assoc();
		$descrizione = $row['ar_descrizione'];
		$prezzo = $row['ar_prezzo'];
		$result->close();
		// verifico disponibilità prima di aggiungere la riga
		$ok = true;
		$mysqli->query("LOCK TABLES magazzino SELECT UPDATE");
		$query = sprintf("SELECT * FROM distinta JOIN magazzino ON di_codiceprodotto = ma_codiceprodotto WHERE di_codicearticolo = '%s' ", $codice);
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		while($row = $result->fetch_assoc()) {
			if ($row['di_coefficente'] > $row['ma_giacenza']) {
				echo "FINITO " . $row['ma_codiceprodotto'] . "! <br />";
				$ok = false;
			}
		}

		$result->close();

		// se ok inserisco riga
		if ($ok) {
			// eseguo insert
			$mysqli->query("LOCK TABLES ordinirighe SELECT WRITE");
			$query = sprintf("SELECT MAX(ri_riga) AS MaxRiga FROM ordinirighe WHERE ri_ordine = '%s' ", $ordine);
			$result = $mysqli->query($query, MYSQLI_USE_RESULT);
			$row = $result->fetch_assoc();
			$riga = $row['MaxRiga'];
			$result->close();
			$riga += 1;
			$quantita = 1;
			$query = sprintf("INSERT INTO ordinirighe (ri_ordine, ri_riga, ri_codice, ri_descrizione, ri_quantita, ri_prezzo) VALUES ('%s', '%s', '%s', '%s', %s, %s)", $ordine, $riga, $codice, $descrizione, $quantita, $prezzo);

			// eseguo inserimento nuova riga
			if ($mysqli->query($query)) {
				echo "$codice OK <br /> |" . $riga;
			}
			else {
				echo("<br />SQL eseguito: ". $query . " Errore rilevato: " . mysqli_error($mysqli) . "|0");
			}
		}

		// rimuovo lock table
		$mysqli->query("UNLOCK TABLES");
	}

	function piu() {
		global $mysqli, $ordine, $riga;
		//reperisco dati da anagrafica
		$query = "SELECT * FROM ordinirighe WHERE ri_ordine = $ordine AND ri_riga = $riga";
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		$row = $result->fetch_assoc();
		$quantita = $row['ri_quantita'];
		$codice = $row['ri_codice'];
		$result->close();
		// verifico disponibilità prima di aggiungere la riga
		$ok = true;
		$mysqli->query("LOCK TABLES magazzino SELECT UPDATE");
		$query = sprintf("SELECT * FROM distinta JOIN magazzino ON di_codiceprodotto = ma_codiceprodotto WHERE di_codicearticolo = '%s' ", $codice);
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		while($row = $result->fetch_assoc()) {
			if ($row['di_coefficente'] > $row['ma_giacenza']) {
				echo "FINITO " . $row['ma_codiceprodotto'] . "! <br />";
				$ok = false;
			}
		}

		$result->close();

		// se ok inserisco riga
		if ($ok) {
			// eseguo update
			$newquantita = $quantita + 1;
			$query = "UPDATE ordinirighe SET ri_quantita = $newquantita WHERE ri_ordine = $ordine AND ri_riga = $riga";
			$mysqli->query($query);
			echo "+1 OK <br />";
	}
	// rimuovo lock table
	$mysqli->query("UNLOCK TABLES");
	}

	function meno() {
		global $mysqli, $ordine, $riga;
		//reperisco dati da anagrafica
		$query = "SELECT * FROM ordinirighe WHERE ri_ordine = $ordine AND ri_riga = $riga";
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		$row = $result->fetch_assoc();
		$quantita = $row['ri_quantita'];
		$result->close();
		// eseguo update
		$newquantita = $quantita - 1;
		if ($newquantita <= 0) {
			$query = "DELETE FROM ordinirighe WHERE ri_ordine = $ordine AND ri_riga = $riga";
			echo "RIGA CANCELLATA! <br />";
		}
		else {
			$query = "UPDATE ordinirighe SET ri_quantita = $newquantita WHERE ri_ordine = $ordine AND ri_riga = $riga";
			echo "- 1 OK! <br />";
		}
		$mysqli->query($query);
	}

	function aggiunta() {
		global $mysqli, $ordine, $riga, $codice;
		// reperisco dati dell'aggiunta
		$query = "SELECT * FROM articoli WHERE ar_codice = '$codice'";
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		$row = $result->fetch_assoc();
		$descrizioneAggiunta = $row['ar_descrizione'];
		$prezzoAggiunta = $row['ar_prezzo'];
		$result->close();
		//reperisco dati da anagrafica
		$query = "SELECT * FROM ordinirighe WHERE ri_ordine = $ordine AND ri_riga = $riga";
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		$row = $result->fetch_assoc();
		$descrizione = $row['ri_descrizione'];
		$prezzo = $row['ri_prezzo'];
		$result->close();
		$nuovoPrezzo = $prezzo + $prezzoAggiunta;
		$nuovaDescrizione = $descrizione . " + " . $descrizioneAggiunta;
		// eseguo update
		$query = "UPDATE ordinirighe SET ri_descrizione = '$nuovaDescrizione', ri_prezzo = $nuovoPrezzo, ri_mod = '*' WHERE ri_ordine = $ordine AND ri_riga = $riga";
		if ($mysqli->query($query)) {
			echo "AGGIUNTO " . $descrizioneAggiunta . " <br />";
		}
		else {
			echo mysqli_error($mysqli);
		}
	}

	function cancella() {
		global $mysqli, $ordine, $riga;
		$query = "DELETE FROM ordinirighe WHERE ri_ordine = $ordine AND ri_riga = $riga";
		echo "RIGA CANCELLATA! <br />";
		$mysqli->query($query);
	}

	function gratis() {
		global $mysqli, $ordine;
		// reperisco totale ordine
		$query = sprintf("SELECT SUM(ri_quantita * ri_prezzo) AS totale FROM ordinirighe WHERE ri_ordine = '%s'", $ordine);
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		$row = $result->fetch_assoc();
		$totaleordine = $row['totale'];
		$result->close();


		// eseguo insert
		$mysqli->query("LOCK TABLES ordinirighe SELECT WRITE");
		$query = sprintf("SELECT MAX(ri_riga) AS MaxRiga FROM ordinirighe WHERE ri_ordine = '%s' ", $ordine);
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		$row = $result->fetch_assoc();
		$riga = $row['MaxRiga'];
		$result->close();
		$riga += 1;
		$codice = 'Sconto';
		$descrizione = 'Sconto';
		$quantita = 1;
		$prezzo = $totaleordine * -1;
		$query = sprintf("INSERT INTO ordinirighe (ri_ordine, ri_riga, ri_codice, ri_descrizione, ri_quantita, ri_prezzo) VALUES ('%s', '%s', '%s', '%s', '%s', '%s')", $ordine, $riga, $codice, $descrizione, $quantita, $prezzo);
		// eseguo inserimento nuova riga
		if ($mysqli->query($query)) {
			echo "Sconto Applicato OK <br />";
		}
		else {
			echo("<br />SQL eseguito: ". $query . " Errore rilevato: " . mysqli_error($mysqli) . "|0");
		}
		// rimuovo lock table
		$mysqli->query("UNLOCK TABLES");
	}
	
	function nota() {
		global $mysqli, $ordine, $riga, $nota;
		// eseguo update
		$query = "UPDATE ordinirighe SET ri_nota = '$nota' WHERE ri_ordine = $ordine AND ri_riga = $riga";
		if ($mysqli->query($query)) {
			echo "AGGIUNTA NOTA " . $nota . " <br />";
		}
		else {
			echo mysqli_error($mysqli);
			//echo $query;
		}
	}
	
	function eliminanota() {
		global $mysqli, $ordine, $riga;
		// eseguo update
		$query = "UPDATE ordinirighe SET ri_nota = '' WHERE ri_ordine = $ordine AND ri_riga = $riga";
		if ($mysqli->query($query)) {
			echo "ELIMINATA NOTA " . " <br />";
		}
		else {
			echo mysqli_error($mysqli);
			//echo $query;
		}
	}