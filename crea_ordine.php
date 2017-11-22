<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	// recupero serata attiva
	$query = "SELECT * FROM serate WHERE se_attiva = 'S'";
	$result = $mysqli->query($query);
	if ($result->num_rows == 0) {
		echo "Nessuna serata attiva";
	} else {
		$row = $result->fetch_assoc();
		$serata = $row['se_numero'];
		$result->close();
	}

	// se ho recuperato la serata
	if (isset($serata)) {

		// blocco tabella ordini
		$mysqli->query("LOCK TABLES ordini SELECT");

		// trovo l'ultimo ordine fatto per aggiungerne uno nuovo
		$query = "SELECT max(or_numero) AS max_ordine FROM ordini";
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		$ordine = $row['max_ordine'];
		$ordine += 1;

		// se utente bar precompilo già nome cliente
		$nome = "";
		// Preassegno il tipo: B se bar, S se spunceti, T servito al tavolo
		$tipo = "T";
		if ($_SESSION['tipo'] == 'bar') {
			$nome = "BAR";
			$tipo = "B";
		}
		if ($_SESSION['tipo'] == 'bibite') {
			$nome = "BIBITE";
			$tipo = "B";
		}
		$result->close();

		//Aggiunto nuovo ordine e poi vado in manutenzione dell'ordine
		$query = sprintf("
			INSERT INTO ordini (
			or_numero, or_serata, or_cassa, or_data_inizio, or_cliente, or_tipo
			)
			VALUES (
			'%s', '%s', '%s', now(), '%s', '%s'
			) ",
			$ordine, $serata, $_SESSION['user'], $nome, $tipo);
		//echo $query;
		if ($mysqli->query($query)) {
			header('Location: gestione_ordini.php?ordine=' . $ordine);
		} else {
			echo $mysqli->error;
		}
		$mysqli->query("UNLOCK TABLES");
	}
	$mysqli->close();
