<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");




	// recupero il barcode ricevuto
	if (isset($_GET["barcode"]))

	{

		$barcode = $_GET["barcode"];
	} else {
		echo "Barcode non ricevuto";
		die();
	}

	// divido il barcode in base al puntino
	$ordine = "";
	$gruppo = "";
	$a = explode(".", $barcode);
	if (isset ($a[0]))
	{
		$ordine = $a[0];
	}
	if (isset ($a[1])) {
		$gruppo = $a[1];
	}

	// se non ho ordine o gruppo segnalo errore
	if ($ordine == "" || $gruppo == "") {
		echo "Errore formato barcode";
		die();
	}

	//echo "barcode *" . $barcode . "*</br>";
	//echo "ordine *" . $ordine . "*</br>";
	//echo "gruppo *" . $gruppo . "*</br>";

	// Recupero la descrizione del gruppo per usarlo nello stato di ritorno
	$query = sprintf("SELECT * FROM gruppi WHERE gr_barcode = '%s' ", $gruppo);
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$descr_gruppo = $row['gr_descrizioneunificata'];
	$result->close();

	// Devo segnare lo stato 5 a tutte le righe dell'ordine il cui gruppo ha quel valore riportato nel barcode
	$query = sprintf("
		UPDATE ordinirighe
		SET ri_stato = 5
		WHERE ri_ordine = '%s'
		AND ri_stato < 5
		AND ri_codice IN (
			SELECT ar_codice
			FROM articoli
			JOIN gruppi ON ar_gruppo = gr_codice
			WHERE gr_barcode = '%s'
			)", $ordine, $gruppo);

	//echo $query . "</br>";

	// aggiorno stato righe
	if (!$mysqli->query($query)) {
		echo $mysqli->error;
	}

	// Guardo il minor stato delle righe
	$query = sprintf("SELECT MIN(ri_stato) AS min_stato FROM ordinirighe WHERE ri_ordine = '%s' ", $ordine);
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$stato = $row['min_stato'];
	$result->close();

	// se lo stato è a 5 allora chiudo anche la testata dell'ordine
	if ($stato == 5) {
		$query = sprintf("
			UPDATE ordini
			SET or_stato = '%s', or_data_fine = now()
			WHERE or_numero = '%s'
			AND or_data_fine IS NULL ",
			$stato, $ordine);

		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		}
		echo "Chiusura completa dell'ordine " . $ordine . " con il gruppo " . $descr_gruppo;
	} else {
		echo "Chiusura parziale dell'ordine " . $ordine . " con il gruppo " . $descr_gruppo;
	}
	$mysqli->close();
