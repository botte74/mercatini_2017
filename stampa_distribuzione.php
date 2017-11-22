<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");
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

require('./fpdf181/code39.php');

define('EURO',chr(128));

	// variabili globali
	$pdf = "";
	$ordine = "";
	$gruppo = "";
	$cartella = "";
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
		$query = $query . "WHERE gr_tipostampa = 'F' and ri_stato = 2 and ((TIMESTAMPDIFF(SECOND, or_data_abbina, now()) > gr_ritardo_stampa) or (or_tipo = 'A')) ";
	} else {
		$query = $query . "WHERE ri_ordine = $get_ordine AND gr_tipostampa = 'F' ";
	}
	if ($get_gruppo != "") {
		$query = $query . "and gr_codice = '$get_gruppo' ";
	}
	$query = $query . "ORDER BY ri_ordine, gr_cartella, gr_ordinamento, ri_riga";

	//echo $query . "</br>";
	$result = $mysqli->query($query, MYSQLI_USE_RESULT);
	while($row = $result->fetch_assoc()) {

		// Quando cambia l'ordine o il gruppo aggiorno lo stato
		//if (($ordine != "" and $ordine != $row['ri_ordine']) || ($gruppo != "" and $gruppo != $row['ar_gruppo'])) {
		//	aggiorna_stato();
		//}

		// Quando cambia l'ordine reperisco alcune informazioni di testata
		if ($ordine != $row['ri_ordine']) {

			// se è cambiato l'ordine salvo il vecchio ordine
			if ($ordine != "") {
				salva_file();
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
		}

		// se cambia gruppo o ho stampato 10 righe devo creare una nuova sezione
		if (($row['ar_gruppo'] != $gruppo) || ($righestampate >= 8) || ($row['ri_nota'] != "" && $righestampate >= 7)) {
			switch ($sezione) {
				case 0:
				$sezione = 1;
				stampa_testa();
				break;

				case 1:
				// stampo righe vuote per compleare la sezione
				while ($righestampate < 8) {
					stampa_riga_vuota();
				}

				$sezione = 2;
				stampa_testa();
				break;

				case 2:
				// stampo righe vuote per compleare la sezione
				while ($righestampate < 8) {
					stampa_riga_vuota();
				}

				$sezione = 3;
				stampa_testa();
				break;

				case 3:
				// stampo righe vuote per compleare la sezione
				while ($righestampate < 8) {
					stampa_riga_vuota();
				}

				$pdf->AddPage();
				$sezione = 1;
				stampa_testa();
				break;
			}
			$gruppo = $row['ar_gruppo'];
		}

		// stampa riga pdf
		stampa_riga();

		// salvo su array la coppia ordine e gruppo da aggiornare
		$ordinegruppo = $row['ri_ordine'] . '#' . $row['ar_gruppo'];
		if (! array_search($ordinegruppo, $array_ordinegruppo)) {
			array_push($array_ordinegruppo, $ordinegruppo);
		}

	}

	// alla fine salvo l'ultimo file aperto
	if ($creato) {
		salva_file();
	}

	// chiudo SQL
	$result->close();

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
		$query = $query . "WHERE ri_ordine = $get_ordine AND gr_tipostampa = 'S' ";
	}
	if ($get_gruppo != "") {
		$query = $query . "and gr_codice = '$get_gruppo' ";
	}
	$query = $query . "ORDER BY ri_ordine, gr_cartella, gr_ordinamento, ri_riga";

	$result = $mysqli->query($query, MYSQLI_USE_RESULT);
	while($row = $result->fetch_assoc()) {

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

		// salvo su array la coppia ordine e gruppo da aggiornare
		$ordinegruppo = $row['ri_ordine'] . '#' . $row['ar_gruppo'];
		if (! array_search($ordinegruppo, $array_ordinegruppo)) {
			array_push($array_ordinegruppo, $ordinegruppo);
		}
	}

	// alla fine salvo l'ultimo file aperto
	if ($creato) {
		salva_file_s();
	}

	// chiudo SQl
	$result->close();

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

	// stampo righe vuote per completare la sezione
	while ($righestampate < 8) {
		stampa_riga_vuota();
	}

	// salvo il file sulla cartella relativa al gruppo
	$pdf->Output("./stampe/" .$cartella . "/" . $ordine . ".pdf", "F");
	//$pdf->Output();
	echo "Stampato ordine " . $ordine . " nella cartella " . $cartella . "</br>";
}
function stampa_testa() {
	global $pdf, $sezione, $righestampate, $row, $altriga, $nome, $ordine, $tipo, $tavolo;

	// in base alla sezione calcolo la posizione di Y
	if ($sezione == 1) $y = 5;
	if ($sezione == 2) $y = 100;
	if ($sezione == 3) $y = 200;

	// posizione cursore
	$pdf->setxy(10,$y);

	// Imposto spessore cella
	$pdf->SetLineWidth(1);

	// stampo il gruppo
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(130,10, iconv('UTF-8', 'windows-1252', $row['gr_descrizione']),"LTR",0, "C");
	$pdf->Cell(60,10, "","TR",1, "C");

	// Stampo intestazioni piccoline
	$pdf->SetFont('Arial','',8);
	$pdf->Cell(30,5, iconv('UTF-8', 'windows-1252', "Ordine N°"),"LTR",0, "C");
	$pdf->Cell(70,5, iconv('UTF-8', 'windows-1252', "Cliente"),"LTR",0, "C");
	$pdf->Cell(30,5, iconv('UTF-8', 'windows-1252', "Tavolo"),"LTR",0, "C");
	$pdf->Cell(60,5, "","R",1, "C");

	// stampo dati ordine
	$pdf->SetFont('Arial','B',22);
	$pdf->Cell(30,10, $ordine,"LBR",0, "C");
	// scrivo il nome stretto
	$my_string = iconv('UTF-8', 'windows-1252', $nome);
	$font_size = 22;
	$decrement_step = 0.1;
	$line_width = 70; // Line width (approx) in mm
	$pdf->SetFont('Arial', 'B', $font_size);
	while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
		$pdf->SetFontSize($font_size -= $decrement_step);
	}
	$pdf->Cell($line_width, 10, $my_string, "LBR", 0, "C");
	// tavolo
	$pdf->Cell(30,10, $tavolo,"LBR",0,"C");
	$pdf->Cell(60,10, "","RB",1, "C");

	// Barcode
	$barcode = $ordine . "." . $row['gr_barcode'];
	if ($sezione == 1) $pdf->Code39(150,10,$barcode,1,15);
	if ($sezione == 2) $pdf->Code39(150,105,$barcode,1,15);
	if ($sezione == 3) $pdf->Code39(150,205,$barcode,1,15);

	// azzero numero righe stampate
	$righestampate = 0;

	$pdf->SetLineWidth(0.2);

	// mi riposiziono il cursore per la stampa delle righe
	$y += 25;
	//if ($sezione == 1) $y = 20;
	//if ($sezione == 2) $y = 115;
	//if ($sezione == 3) $y = 215;

	// stampo instestazione di tabella
	// $pdf->SetLineWidth(0.2);
	// $pdf->setxy(10,$y);
	// $pdf->SetFont('Arial','',14);
	// $pdf->Cell(20,$altriga, iconv('UTF-8', 'windows-1252', "Q.tà"),1,0, "C");
	// $pdf->Cell(40,$altriga,"Codice",1,0);
	// $pdf->Cell(170,$altriga,"Descrizione",1,1, "C");

}

function stampa_riga () {
	global $pdf, $row, $righestampate, $altriga;

	$totale_riga = number_format(($row['ri_quantita'] * $row['ri_prezzo']), 2);
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(20,$altriga,$row['ri_quantita'],1,0, "C");

	// se la riga è stata modificata lo segnalo con *** all'inizio
	if ($row['ri_mod'] == '*' ) {
		$descrizione = '*** ' . $row['ri_descrizione'];
	}
	else {
		$descrizione = $row['ri_descrizione'];
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

function stampa_riga_vuota() {
	global $pdf, $righestampate, $altriga;
	$pdf->SetFont('Arial','',12);
	$pdf->Cell(20,$altriga, "",1,0);
	//$pdf->Cell(40,$altriga, "",1,0);
	$pdf->Cell(170,$altriga, "", 1, 1);
	$righestampate += 1;
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
	$pdf->Cell(70,$altriga,'MandriaFest 2017',0,1,"C");
	$pdf->Cell(35,$altriga, iconv('UTF-8', 'windows-1252', "Ordine n° " . $ordine), 0, 0, "L");
	$pdf->Cell(35,$altriga, iconv('UTF-8', 'windows-1252', "Tavolo n° " . $tavolo), 0, 1, "R");
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
	global $array_ordinegruppo, $mysqli;

	foreach ($array_ordinegruppo as $ordinegruppo) {
		$a = explode("#", $ordinegruppo);
		$ordine = $a[0];
		$gruppo = $a[1];

		// Aggiorno stato righe in base allo stato imposto sulla tabella Gruppi
		$query2 = "UPDATE ordinirighe SET ri_stato =
		(select gr_stato from gruppi join articoli ON gr_codice = ar_gruppo where ri_codice = ar_codice)
		WHERE ri_ordine = $ordine and ri_codice in (select ar_codice from articoli where ar_gruppo = '$gruppo' )
		and ri_stato < 	(select gr_stato from gruppi join articoli ON gr_codice = ar_gruppo where ri_codice = ar_codice)";
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
