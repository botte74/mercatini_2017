<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");
	
	require('./classe_griglie.php');
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Stampa distribuzione</title>
		<link rel="icon" href="./img/icon.ico" />
	</head>
	<body>

<?php

	// creo variabili
	$get_ordine = "";
	$get_gruppo = "";
	$get_speciale = "";
	$loop = false;

	// ricevo parametri da url
	// se ricevo un ordine stampo quello altrimenti stampo tutti quelli con stato = 2
	if (isset($_GET["ordine"])) {
		$get_ordine = $_GET["ordine"];
	}
	else {
		$loop = true;
	}

	// se ricevo gruppo stampo solo quel gruppo
	if (isset($_GET["gruppo"])) {
		$get_gruppo = $_GET["gruppo"];
	}
	
		// se ricevo gruppo stampo solo quel gruppo
	if (isset($_GET["speciale"])) {
		$get_speciale = $_GET["speciale"];
	}

require('./fpdf181/code39.php');

define('EURO',chr(128));

	// variabili globali
	$pdf = "";
	$ordine = "";
	$gruppo = "";
	$gruppobarcode = "";
	$cartella = "";
	$cartella2 = "";
	$sezione = 0;
	$righestampate = 0;
	$altriga = 8;
	$creato = false;
	//Informazioni sull'utente
	$nome = "";
	$tipo = "";
	$tavolo = "";

	// array con la coppia ordine, gruppo da aggiornare
	$array_ordinegruppo = [];

	// --------------------------- FOGLIO A4 ---------------------------------------------------------------
	// Stampo il foglio A4 diviso in 3 sezioni per certe tipologie di cibo come panini e piatti
	$query = "SELECT * FROM ordini JOIN ordinirighe ON or_numero = ri_ordine JOIN articoli ON ri_codice = ar_codice JOIN gruppi ON ar_gruppo = gr_codice ";
	// in base al loop faccio query
	if ($loop) {
		$query = $query . "WHERE gr_tipostampa in ('F','T') and ri_stato = 2 and ((TIMESTAMPDIFF(SECOND, or_data_abbina, now()) > gr_ritardo_stampa) or (or_tipo = 'A')) ";
	} else {
		$query = $query . "WHERE ri_ordine = $get_ordine AND gr_tipostampa in ('F','T') ";
	}
	if ($get_gruppo != "") {
		$query = $query . "and gr_barcode = '$get_gruppo' ";
	}
	
	// se ho una chiamata speciale invalido la query per il foglio A4 perchè devo fare uno scontrino
	if ($get_speciale == "S") {
		$query = $query . "and or_numero = -1 ";
	}
	
	$query = $query . "ORDER BY ri_ordine, gr_cartella, gr_barcode, gr_ordinamento, ri_riga";

	//echo $query . "</br>";
	$result = $mysqli->query($query, MYSQLI_STORE_RESULT);
	while($row = $result->fetch_assoc()) {

		// Quando cambia l'ordine reperisco alcune informazioni di testata
		if ($ordine != $row['ri_ordine']) {

			// se è cambiato l'ordine salvo il vecchio ordine
			if ($ordine != "") {
				salva_file();
				$cartella = "";
				$gruppo = "";
				$gruppobarcode = "";
				$sezione = 0;
				$righestampate = 0;
			}

			// imposto nuove informazioni di testata
			$ordine = $row['ri_ordine'];
			$nome = $row['or_cliente'];
			$tipo = $row['or_tipo'];
			$tavolo = $row['or_tavolo'];
			if ($tipo == "A") $tavolo = "ASP.";
		}

		$creato = true;

		// se cambia la cartella dove salvare il file devo fare un nuovo file
		if ($row['gr_cartella'] != $cartella) {
			if ($cartella != "") {
				salva_file();
			}
			$cartella = $row['gr_cartella'];
			crea_file();
			stampa_testa();
		}

		// se cambia gruppo o ho stampato 10 righe devo creare una nuova sezione
		if (($row['ar_gruppo'] != $gruppo)) {
			stampa_gruppo();
			$gruppo = $row['ar_gruppo'];
			$gruppobarcode = $row['gr_barcode'];
		}

		// stampa riga pdf
		stampa_riga();

		// salvo su array la coppia ordine e gruppo da aggiornare
		$ordinegruppo = $row['ri_ordine'] . '#' . $row['ar_gruppo'];
		if (array_search($ordinegruppo, $array_ordinegruppo) === FALSE) {
			array_push($array_ordinegruppo, $ordinegruppo);
		}

	}

	// alla fine salvo l'ultimo file aperto
	if ($creato) {
		salva_file();
	}

	// chiudo SQL
	//$result->close();

	// aggiorno stato degli ordini appena stampati
	aggiorna_stato();

	// --------------------------- SCONTRINO ---------------------------------------------------------------
	// Stampo lo scontrino per certe tipologie di cibo come bere e bar

	// variabili globali
	$pdf = "";
	$ordine = "";
	$gruppo = "";
	$cartella = "";
	$sezione = 0;
	$righestampate = 0;
	$altriga = 6;
	$creato = false;
	//Informazioni sull'utente
	$nome = "";
	$tipo = "";
	$tavolo = "";
	$gr_barcode = "";

	// array con la coppia ordine, gruppo da aggiornare
	$array_ordinegruppo = [];

	// Query per stampa scontrino
	$query = "SELECT * FROM ordini JOIN ordinirighe ON or_numero = ri_ordine JOIN articoli ON ri_codice = ar_codice JOIN gruppi ON ar_gruppo = gr_codice ";
	// in base al loop faccio query
	if ($loop) {
		$query = $query . "WHERE gr_tipostampa = 'S' and ri_stato = 2 and (TIMESTAMPDIFF(SECOND, or_data_abbina, now()) > gr_ritardo_stampa) ";
	} else {
		if ($get_speciale == 'S') {
			$query = $query . "WHERE ri_ordine = $get_ordine AND gr_tipostampa = 'T' ";
		} else {
			$query = $query . "WHERE ri_ordine = $get_ordine AND gr_tipostampa = 'S' ";
		}
	}
	if ($get_gruppo != "") {
		$query = $query . "and gr_barcode = '$get_gruppo' ";
	}

	$query = $query . "ORDER BY ri_ordine, gr_cartella, gr_ordinamento, ri_riga";
	$result = $mysqli->query($query, MYSQLI_STORE_RESULT);
	while($row = $result->fetch_assoc()) {
		if ($get_speciale == 'S') {
			$row['gr_cartella'] = $row['gr_cartella2'];
		}

		// Quando cambia l'ordine o il gruppo aggiorno lo stato
		//if (($ordine != "" and $ordine != $row['ri_ordine']) || ($gruppo != "" and $gruppo != $row['ar_gruppo'])) {
		//	aggiorna_stato();
		//}

		// Quando cambia l'ordine reperisco alcune informazioni di testata
		if ($ordine != $row['ri_ordine']) {

			// se è cambiato l'ordine salvo il vecchio ordine
			if ($ordine != "") {
				salva_file_s();
				$cartella = "";
				$gruppo = "";
				$sezione = 0;
				$righestampate = 0;
			}

			// imposto nuove informazioni di testata
			$ordine = $row['ri_ordine'];
			$nome = $row['or_cliente'];
			$tipo = $row['or_tipo'];
			$tavolo = $row['or_tavolo'];
			$gruppo = $row['ar_gruppo'];
			$gr_barcode = $row['gr_barcode'];
			if ($tipo == "A") $tavolo = "ASP.";
		}

		$creato = true;

		// se cambia la cartella dove salvare il file devo fare un nuovo file
		if ($row['gr_cartella'] != $cartella) {
			if ($cartella != "") {
				salva_file_s();
			}
			$cartella = $row['gr_cartella'];
			$gruppo = $row['ar_gruppo'];
			$gr_barcode = $row['gr_barcode'];
			crea_file_s();
			stampa_testa_s();
		}

		// stampa riga pdf
		stampa_riga_s();
		
		// in caso di stampa speciale alcuni articoli devono essere aggiunti alle griglie
		if ($get_speciale == 'S' and $row['ri_stato'] == 3) {
			Griglie::AggiungiArticoloInGriglia($row['ri_codice'], $row['ri_quantita']);
		}

		// salvo su array la coppia ordine e gruppo da aggiornare
		$ordinegruppo = $row['ri_ordine'] . '#' . $row['ar_gruppo'];
		if (array_search($ordinegruppo, $array_ordinegruppo) === FALSE) {
			array_push($array_ordinegruppo, $ordinegruppo);
		}
	}

	// alla fine salvo l'ultimo file aperto
	if ($creato) {
		salva_file_s();
	}

	// chiudo SQl
	//$result->close();

	//------------------------------------------------------------------------------------------------
	// Cerco le righe degli articoli di gruppi a saldo automatico
	if ($loop) {
		$query = "SELECT * FROM ordini JOIN ordinirighe ON or_numero = ri_ordine JOIN articoli ON ri_codice = ar_codice JOIN gruppi ON ar_gruppo = gr_codice ";
		$query = $query . "WHERE gr_tipostampa = 'N' and ri_stato = 2 ";
		$query = $query . "ORDER BY ri_ordine, gr_cartella, gr_ordinamento, ri_riga";
		$result = $mysqli->query($query, MYSQLI_STORE_RESULT);
		
		// salvo su array la coppia ordine e gruppo da aggiornare
		while($row = $result->fetch_assoc()) {
		
		$ordinegruppo = $row['ri_ordine'] . '#' . $row['ar_gruppo'];
		if (! array_search($ordinegruppo, $array_ordinegruppo)) {
			array_push($array_ordinegruppo, $ordinegruppo);
		}
		// chiudo SQl
		//$result->close();
		}
	}
	//------------------------------------------------------------------------------------------------
	
	// aggiorno stato degli ordini appena stampati
	aggiorna_stato();

	// chiudo connessione
	$mysqli->close();

	if ($loop) {
		header( "refresh:10;" );
 	}
?>

	</body>
</html>

<?php


// crea un nuovo file
function crea_file() {
	global $pdf, $sezione, $righestampate;
	$pdf = new PDF_Code39();
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true , 5);
	$pdf->SetFont('Arial','',12);
	$sezione = 0;
	$righestampate = 0;
}

function salva_file() {
	global $pdf, $cartella, $ordine, $righestampate;

	// salvo il file sulla cartella relativa al gruppo
	$pdf->Output("./stampe/" .$cartella . "/" . $ordine . ".pdf", "F");
	//$pdf->Output();
	echo "Stampato ordine " . $ordine . " nella cartella " . $cartella . "</br>";
}
function stampa_testa() {
	global $pdf, $sezione, $righestampate, $row, $altriga, $nome, $ordine, $tipo, $tavolo;

	// posizione cursore
	$pdf->setxy(10,5);

	// Imposto spessore cella
	$pdf->SetLineWidth(1);

	// Stampo intestazioni piccoline
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(40,5, iconv('UTF-8', 'windows-1252', "Ordine N°"),"LTR",0, "C");
	$pdf->Cell(110,5, iconv('UTF-8', 'windows-1252', "Cliente"),"LTR",0, "C");
	$pdf->Cell(40,5, iconv('UTF-8', 'windows-1252', "Tavolo"),"LTR",1, "C");
	

	// stampo dati ordine
	$pdf->SetFont('Arial','B',22);
	$pdf->Cell(40,10, $ordine,"LBR",0, "C");
	// scrivo il nome stretto
	$my_string = iconv('UTF-8', 'windows-1252', $nome);
	$font_size = 22;
	$decrement_step = 0.1;
	$line_width = 110; // Line width (approx) in mm
	$pdf->SetFont('Arial', 'B', $font_size);
	while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
		$pdf->SetFontSize($font_size -= $decrement_step);
	}
	$pdf->Cell($line_width, 10, $my_string, "LBR", 0, "C");
	// tavolo
	$pdf->Cell(40,10, $tavolo,"LBR",1,"C");


	// azzero numero righe stampate
	$righestampate = 0;

	$pdf->SetLineWidth(0.2);

}

function stampa_gruppo() {
	global $pdf, $sezione, $righestampate, $row, $altriga, $nome, $ordine, $tipo, $tavolo, $gruppobarcode;

	// testo se è ora di fare una nuova pagina
	$y = $pdf->getY();
	if ($y > 240) {
		$pdf->AddPage();
		stampa_testa();
	}

	// se il barcode è diverso faccio la riga del gruppo più alta per far stare anche il barcode
	// altrimenti stampo solo il nome del gruppo
	if (($row['gr_barcode'] != $gruppobarcode) && ($row['gr_tipostampa'] == 'F')) {
		// stampo il gruppo
		$pdf->SetFont('Arial','BI',22);
		$pdf->Cell(130,25, iconv('UTF-8', 'windows-1252', $row['gr_descrizione']),"LT",0, "C");
		$pdf->Cell(60,25, "","TR",1, "C");
		$y = $pdf->getY();
		$y = $y - 20;
		// Barcode
		$barcode = $ordine . "." . $row['gr_barcode'];
		$pdf->Code39(150,$y,$barcode,1,15);
	} else {
		// stampo il gruppo
		$pdf->SetFont('Arial','BI',22);
		$pdf->Cell(130,15, iconv('UTF-8', 'windows-1252', $row['gr_descrizione']),"LT",0, "C");
		$pdf->Cell(60,15, "","TR",1, "C");
	}
	
}

function stampa_riga () {
	global $pdf, $row, $righestampate, $altriga;

		// testo se è ora di fare una nuova pagina
	$y = $pdf->getY();
	if ($y > 260) {
		$pdf->AddPage();
		stampa_testa();
	}
	
	
	$totale_riga = number_format(($row['ri_quantita'] * $row['ri_prezzo']), 2);
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(20,$altriga,$row['ri_quantita'],1,0, "C");

	// se la riga è stata modificata lo segnalo con *** all'inizio
	if ($row['ri_mod'] == '*' ) {
		$descrizione = '*** ' . $row['ri_descrizione'];
	}
	else {
		$descrizione = $row['ar_descbreve'];
	}
	
	$my_string = iconv('UTF-8', 'windows-1252', $descrizione);
	$font_size = 16;
	$decrement_step = 0.1;
	$line_width = 170; // Line width (approx) in mm
	$pdf->SetFont('Arial', 'B', $font_size);
	while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
		$pdf->SetFontSize($font_size -= $decrement_step);
	}
	$pdf->Cell($line_width, $altriga, $my_string, 1, 1);
	//$pdf->Cell(80,10,$row['ri_descrizione'],1,0);

	$righestampate += 1;

	// se presente nota la stampo
	if ($row['ri_nota'] != "") {
		$pdf->SetFont('Arial','B',16);
		$pdf->Cell(20,$altriga,"",1,0, "C");
		$my_string = iconv('UTF-8', 'windows-1252', "** " . $row['ri_nota'] . " **");
		$font_size = 16;
		$decrement_step = 0.1;
		$line_width = 170; // Line width (approx) in mm
		$pdf->SetFont('Arial', 'BI', $font_size);
		while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
			$pdf->SetFontSize($font_size -= $decrement_step);
		}
		$pdf->Cell($line_width, $altriga, $my_string, 1, 1, "C");
		$righestampate += 1;
	}
}

// crea un nuovo file
function crea_file_s() {
	global $pdf;
	$pdf = new PDF_Code39('P','mm',array(80,400));
	$pdf->SetMargins(5, 0, 0);
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true , 5);
	$pdf->SetFont('Arial','',8);
}

function salva_file_s() {
	global $pdf, $cartella, $ordine, $gr_barcode;

	// faccio linea di chiusura
	$pdf->Cell(70,2,"","T",1);

	// Barcode
	$x = $pdf->getX();
	$y = $pdf->getY();
	$barcode = $ordine . "." . $gr_barcode;
	$pdf->Code39(20,$y,$barcode,1,15);

	// faccio linea di chiusura
	$pdf->SetXY($x, $y + 20);
	$pdf->Cell(70,2,"","T",1);

	// salvo il file sulla cartella relativa al gruppo
	$pdf->Output("./stampe/" .$cartella . "/" . $ordine . ".pdf", "F");
	//$pdf->Output();
	echo "Stampato ordine " . $ordine . " nella cartella " . $cartella . "</br>";
}
function stampa_testa_s() {
	global $pdf, $row, $altriga, $nome, $ordine, $tipo, $tavolo;

	$altriga = 6;

	$pdf->setxy(5,5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(35,$altriga, iconv('UTF-8', 'windows-1252', "Ordine n° " . $ordine), 0, 0, "L");
	$pdf->Cell(35,$altriga, iconv('UTF-8', 'windows-1252', "Tavolo n° " . $tavolo), 0, 1, "R");
	
	// stampo i coperti
	if (($row['gr_coperti'] == 'S') && ($row['or_coperti'] != 0)) {
		$pdf->Cell(70,$altriga, iconv('UTF-8', 'windows-1252', "Coperti: " . $row['or_coperti']), 0, 1, "C");
	}
	$pdf->Cell(70,$altriga, iconv('UTF-8', 'windows-1252', $nome), "B", 1, "L");
	$pdf->Cell(70,2,"",0,1);

}

function stampa_riga_s () {
	global $pdf, $row, $altriga;

	$altriga = 3;

	// stampo quantità
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,$altriga,$row['ri_quantita']." x",0,0, "R");

	// stampo riga con descrizione
	if ($row['ri_mod'] == "") {
		$my_string = iconv('UTF-8', 'windows-1252', strtoupper($row['ar_descbreve']));
	} else {
		$my_string = iconv('UTF-8', 'windows-1252', strtoupper($row['ri_descrizione']));
		$my_string = "* " . $my_string;
	}

	$font_size = 10;
	$decrement_step = 0.1;
	$line_width = 60; // Line width (approx) in mm
	$pdf->SetFont('Arial', 'B', $font_size);
	while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
		$pdf->SetFontSize($font_size -= $decrement_step);
	}
	$pdf->Cell($line_width, $altriga, $my_string, 0, 1);

	// stampo nota in corsivo
	if ($row['ri_nota'] <> "") {
		$my_string = iconv('UTF-8', 'windows-1252', "* " .strtoupper($row['ri_nota']) . " *");
		$font_size = 10;
		$decrement_step = 0.1;
		$line_width = 60; // Line width (approx) in mm
		$pdf->SetFont('Arial', 'BI', $font_size);
		while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
			$pdf->SetFontSize($font_size -= $decrement_step);
		}
		$pdf->Cell(10,$altriga,"",0,0, "R");
		$pdf->Cell($line_width, $altriga, $my_string, 0, 1, "L");
	}
	// alla fine della riga faccio una riga vuota di separazione
	$pdf->Cell(70,$altriga,"",0,1);
}

// aggiorna lo stato di tutti i ordini / gruppi processati nella stampa
function aggiorna_stato () {
	global $array_ordinegruppo, $mysqli, $get_speciale;

	foreach ($array_ordinegruppo as $ordinegruppo) {
		$a = explode("#", $ordinegruppo);
		$ordine = $a[0];
		$gruppo = $a[1];

		// Aggiorno stato righe in base allo stato imposto sulla tabella Gruppi
		if ($get_speciale == 'S') {
			$query2 = "UPDATE ordinirighe SET ri_stato = 4
			WHERE ri_ordine = $ordine and ri_codice in (select ar_codice from articoli where ar_gruppo = '$gruppo' )
			and ri_stato < 4";
		} else {
			$query2 = "UPDATE ordinirighe SET ri_stato =
			(select gr_stato from gruppi join articoli ON gr_codice = ar_gruppo where ri_codice = ar_codice)
			WHERE ri_ordine = $ordine and ri_codice in (select ar_codice from articoli where ar_gruppo = '$gruppo' )
			and ri_stato < 	(select gr_stato from gruppi join articoli ON gr_codice = ar_gruppo where ri_codice = ar_codice)";
		}
		
		if (!$mysqli->query($query2)) {
			echo $mysqli->error;
		}

		// Aggiorno stato ordine con il minor stato delle righe
		$query2 = "UPDATE ordini SET or_stato = (select MIN(ri_stato) from ordinirighe where ri_ordine = $ordine) WHERE or_numero = $ordine";
		if (!$mysqli->query($query2)) {
			echo $mysqli->error;
		}
	}
}
