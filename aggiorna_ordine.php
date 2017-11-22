<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");


	if (isset($_POST["ordine"])) {
		$ordine = $_POST["ordine"];
	} else {
		echo("<br />Codice non inserito");
	}

	if (isset($_POST["nome"])) {
		$nome = $_POST["nome"];
	} else {
		//echo("<br />Nome non inserito");
	}
	
	if (isset($_POST["coperti"])) {
		$coperti = (int)$_POST["coperti"];
	} else {
		//echo("<br />Coperto non inserito");
	}
	
	//print_r($_POST);
	if (isset($_POST["tipo"])) {
		$tipo = "";
		switch ($_POST["tipo"]) {
			case "tavolo":
				$tipo = 'T';
				break;
			case "asporto":
				$tipo = 'A';
				break;
			case "bar":
				$tipo = 'B';
				break;
		}
	}
	
	if (isset($_POST["tavolo"])) {
		$tavolo = $_POST["tavolo"];
	} else {
		//echo("<br />Tavolo non inserito");
	}
	
	/*
	 * se ho numero ordine, nome cliente e tipo ordine
	 * aggiorno l'ordine inserendo questi valori
	 */
	if (isset($ordine) && isset($nome) && isset($tipo) && isset($coperti) ) {
		/** Aggiorno l'ordine con il nome */
		$query = sprintf("
			UPDATE ordini
			SET or_cliente = '%s' , or_tipo = '%s', or_coperti = %s
			WHERE or_numero = '%s' ",
			$nome, $tipo, $coperti, $ordine);
			
		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		}
	}

	/*
	 * se ho numero ordine e il tavolo in cui sono seduti
	 * aggiorno l'ordine inserendo lo stato a 2
	 */

	if (isset($ordine) && isset($tavolo)) {
		
		// mi viene passato l'id del tavolo e da quello recupero il valore del tavolo 24--> B04
		$query = sprintf("SELECT * FROM tavoli where ta_id = '%s' ",$tavolo);
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		$tavolo_valore = $row['ta_valore'];
		$result->close();

		/** Per prima cosa imposto il tavolo sull'ordine */
		$query = sprintf("
			UPDATE ordini
			SET or_tavolo = '%s'
			WHERE or_numero = '%s' ",
			$tavolo_valore, $ordine);

		if ($mysqli->query($query)) {
			echo "Ordine " . $ordine . " abbinato al tavolo " . $tavolo_valore;
		} else {
			echo $mysqli->error;
		}
		
		/** Poi aggiorno le righe impostando lo stato a 2 se sono inferiori a 2 */
		$query = sprintf("
			UPDATE ordinirighe
			SET ri_stato = 2
			WHERE ri_ordine = '%s'
			AND ri_stato < 2 ",
			$ordine);

		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		}
		
		/** Poi aggiorno le righe impostando lo stato a 2 se sono inferiori a 2 */
		$query = sprintf("
			SELECT MIN(ri_stato) AS min_stato
			FROM ordinirighe
			WHERE ri_ordine = '%s' ",
			$ordine);
		
		$result = $mysqli->query($query);
		$row = $result->fetch_assoc();
		$stato = $row['min_stato'];
		$result->close();
		
		/** Poi l'ordine impostando lo stato e la data di abbinamento */
		$query = sprintf("
			UPDATE ordini
			SET or_stato = '%s', or_data_abbina = now()
			WHERE or_numero = '%s'",
			$stato, $ordine);

		if (!$mysqli->query($query)) {
			echo $mysqli->error;
		}		
	}

