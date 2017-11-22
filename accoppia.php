<?php
	// recupero vaolori delle select
	$gruppi = $_REQUEST['gruppi'];
	$tavoli = $_REQUEST['sel2'];
	$ordini = $_REQUEST['sel1'];

	//includo connessione e impostazione database
	include_once("includes/config.php");

	// estraggo tutti i gruppi di tavoli
	$query = "SELECT * FROM gruppi_tavoli";
	$result = $mysqli->query($query);

	// svuoto la select gruppi tavoli
	echo "$('select[name=\"gruppi\"]').empty();";

	// aggiungo una option vuota
	echo "$('select[name=\"gruppi\"]').append('<option value=\"\" selected></option>');";

	// ciclo i risultati della query
	while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

		// imposto il selected sull'eventuale gruppo di tavoli scelto
		$selected = "";
		if ($row['gr_ta_id'] == $gruppi) {
			$selected = "selected=\"selected\"";
		}

		// popolo la select
		echo "$('select[name=\"gruppi\"]').append('<option $selected value=\"" . $row['gr_ta_id'] . "\">" . addslashes($row['gr_ta_descrizione']) . "</option>');";
	}

	// se è stata scelto un gruppo di tavoli
	if (!empty($gruppi)) {

		// estraggo tutti i tavoli dal gruppo di tavoli scelto
		$query = "SELECT * FROM tavoli WHERE ta_id_gruppo ='$gruppi'";
		$result = $mysqli->query($query);

		// svuoto la select tavoli
		echo "$('select[name=\"sel2\"]').empty();";

		// aggiungo una option vuota
		echo "$('select[name=\"sel2\"]').append('<option value=\"\" selected></option>');";

		// ciclo i risultati della query
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

			// imposto il selected sull'eventuale tavolo scelto
			$selected = "";
			if ($row['ta_id'] == $tavoli) {
				$selected = "selected=\"selected\"";
			}

			// popolo la select
			echo "$('select[name=\"sel2\"]').append('<option $selected value=\"" . $row['ta_id'] . "\">" . addslashes($row['ta_descrizione']) . "</option>');";
		}
	}

	// se è stato scelto un tavolo
	if (!empty($tavoli)) {

		/** trovo la serata */
		$sere = "SELECT * FROM serate WHERE se_attiva = 'S'";
		$Data = $mysqli->query($sere);
		if ($Data->num_rows == 0) {
			echo "Nessuna serata attiva";
		} else {
			$row = $Data->fetch_assoc();
			$serata = $row['se_numero'];
			$Data->close();
		}

		// estraggo gli ordini con or_stato uguale a 1 e serata attiva
		$query = "SELECT * FROM ordini 
			WHERE or_stato = 1
			AND ordini.or_serata = '$serata'";
		$result = $mysqli->query($query);

		// svuoto la select ordini
		echo "$('select[name=\"sel1\"]').empty();";

		// aggiungo una option vuota
		echo "$('select[name=\"sel1\"]').append('<option value=\"\" selected></option>');";

		// ciclo i risultati della query
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {

			// imposto il selected sull'eventuale ordine scelto
			$selected = "";
			if ($row['or_numero'] == $ordini) {
				$selected = "selected=\"selected\"";
			}

			// popolo la select
			echo "$('select[name=\"sel1\"]').append('<option $selected value=\"" . $row['or_numero'] . "\">" . utf8_encode(addslashes("#" . $row['or_numero'] . " - " . $row['or_cliente'] . " - " . $row['or_totale'])) . "</option>');";
		}
	}
