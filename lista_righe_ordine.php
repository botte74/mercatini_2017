<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	// ricevo parametri da url
	if (isset($_GET["ordine"])) {
		$ordine = $_GET["ordine"];
	}
	else {
		die("<br />Ordine non inserito");
	}


	//reperisco dati da anagrafica
	$totaleordine = 0.00;
	$query = "SELECT * FROM ordinirighe WHERE ri_ordine = $ordine ORDER BY ri_riga";
	$result = $mysqli->query($query, MYSQLI_USE_RESULT);

	echo "<tbody>
		<tr>
		<td class=\"col_descrizione\">Descrizione</td>
		<td class=\"col_quantita\" style=\"text-align:center;\">Q.</td>
		<td class=\"col_prezzo\" style=\"text-align:center;\">P.</td>
		<td style=\"text-align:center;\">M.</td>
		<td style=\"text-align:center;\">+1</td>
		<td style=\"text-align:center;\">-1</td>
		<td style=\"text-align:center;\">C.</td>
		<td style=\"text-align:center;\">S.</td>
		</tr>";

	while($row = $result->fetch_assoc()) {
		$output = sprintf("<tr id='rigatab%s'>


		<td class=\"col_descrizione\">%s</td>
		<td class=\"col_quantita\" style=\"text-align:right;\">%s</td>
		<td class=\"col_prezzo\" style=\"text-align:right;\">%s</td>
		<td style=\"text-align:center;\">%s</td>
		<td><div style=\"cursor:pointer;\" onClick=\"piuUno('%s','%s');\"><img src = \"img//plus.png\"></div></td>
		<td><div style=\"cursor:pointer;\" onClick=\"menoUno('%s','%s');\"><img src = \"img//minus.png\"></div></td>
		<td><div style=\"cursor:pointer;\" onClick=\"cancella('%s','%s');\"><img src = \"img//cancel.png\"></div></td>
		<td><div style=\"cursor:pointer;\" onClick=\"selezione('%s','%s');\"><img src = \"img//edit.png\"></div></td>
		</tr>",
		$row['ri_riga'],
		$row['ri_descrizione'],
		$row['ri_quantita'],
		$row['ri_prezzo'],
		$row['ri_mod'],
		$row['ri_ordine'], $row['ri_riga'],
		$row['ri_ordine'], $row['ri_riga'],
		$row['ri_ordine'], $row['ri_riga'],
		$row['ri_ordine'], $row['ri_riga']);
		echo $output;
		$totaleordine += $row['ri_quantita'] * $row['ri_prezzo'];
		
		// stampo riga con note se presente
		if ($row['ri_nota'] <> "") {
			$output = sprintf("<tr id='rigatabnota%s'>

			<td id='testonota%s' class=\"col_nota\">%s</td>
			<td class=\"col_quantita\" style=\"text-align:right;\"></td>
			<td class=\"col_prezzo\" style=\"text-align:right;\"></td>
			<td style=\"text-align:center;\"></td>
			<td></td>
			<td></td>
			<td><div style=\"cursor:pointer;\" onClick=\"eliminanota('%s','%s');\"><img src = \"img//cancel.png\"></div></td>
			<td><div style=\"cursor:pointer;\" onClick=\"selezionanota('%s','%s');\"><img src = \"img//edit.png\"></div></td>
			</tr>",
			$row['ri_riga'],
			$row['ri_riga'],
			$row['ri_nota'],
			$row['ri_ordine'], $row['ri_riga'],
			$row['ri_ordine'], $row['ri_riga']);
			echo $output;
		}
	}
	echo "</tbody>";
	$result->close();
	$totaleordine = number_format($totaleordine, 2, ",", ".");
	echo "|$totaleordine";

	// chiudo connessione
	$mysqli->close();