<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/** controllo se è attiva una serata */
	$query = "SELECT * FROM serate WHERE se_attiva = 'S'";
	$result = $mysqli->query($query);
	if ($result->num_rows == 0) {
		echo "Nessuna serata attiva";
	} else {
		$row = $result->fetch_assoc();
		$serata = $row['se_numero'];
		$result->close();
	}
?>

<!-- tabella chiamata griglie -->
<?php
	$query = sprintf(
	"SELECT prodotti.*, COALESCE(gri_richiesta, 0) AS gri_richiesta 
	FROM prodotti 
	LEFT JOIN griglie ON pr_codice = gri_prodotto AND gri_serata = '%s'
	WHERE pr_chiamata = 'S' order by pr_codice", $serata);
	
	$result = $mysqli->query($query);

	/** ciclo per mettere in tabella tutti gli ordini */
	while($row = $result->fetch_assoc()) {
		$prodotto = $row['pr_codice'];
		$descrizione = $row['pr_descrizione'];
		if ($row['gri_richiesta'] == 1) {
			$stile = 'rosso';
		} else {
			$stile= 'bianco';
		}
		echo "<input class=\"$stile articoli\" type=\"button\" value=\"$descrizione\" onClick=\"richiesta('" . $prodotto . "');\" />";
	}
	$result->close();
?>