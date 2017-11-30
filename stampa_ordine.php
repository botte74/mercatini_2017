<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	// ricevo parametri da url
	if (isset($_GET["ordine"])) {
		$ordine = $_GET["ordine"];
	} else {
		die();
	}

	require('./fpdf181/pdf_js.php');

	class PDF_AutoPrint extends PDF_JavaScript {
		function AutoPrint($dialog=false)	{
			//Open the print dialog or start printing immediately on the standard printer
			$param=($dialog ? 'true' : 'false');
			$script="print($param);";
			$this->IncludeJS($script);
		}

		function AutoPrintToPrinter($server, $printer, $dialog=false) {
			//Print on a shared printer (requires at least Acrobat 6)
			$script = "var pp = getPrintParams();";
			if($dialog) {
				$script .= "pp.interactive = pp.constants.interactionLevel.full;";
			} else {
				$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
				$script .= "pp.printerName = '\\\\\\\\" . $server . "\\\\" . $printer . "';";
				$script .= "print(pp);";
				$this->IncludeJS($script);
			}
		}
	}
	define('EURO',chr(128));

	$query = "SELECT * FROM ordini WHERE or_numero = " .$ordine;

	$result = $mysqli->query($query);
	//$row = $result->fetch_assoc();

	if ($result->num_rows == 0) {
		echo("<br />Ordine non trovato!!");
		die();
	} else {
		$row = $result->fetch_assoc();
		//Informazioni sull'utente
		$nome = $row['or_cliente'];
		$prezzo = $row['or_totale'];
		$tipo = $row['or_tipo'];
	}
	$result->close();

	// recupero il totale dell'ordine facendo la somma delle righe
	$query = "SELECT SUM(ri_quantita * ri_prezzo) AS totaleordine
						FROM ordinirighe
						WHERE ri_ordine = " .$ordine;
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$totaleordine = $row['totaleordine'];
	$result->close();

	$pdf=new PDF_AutoPrint();
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true , 5);
	$pdf->SetFont('Arial','',12);

	//lista articoli
	//reperisco dati da anagrafica
	$totaleordine = number_format($totaleordine, 2, ",", ".");
	$gruppo = "";
	$sezione = 0;
	$righestampate = 0;
	$altriga = 7;
	$query = "SELECT * FROM ordinirighe
						JOIN articoli ON ri_codice = ar_codice
						JOIN gruppi ON ar_gruppo = gr_codice
						WHERE ri_ordine = $ordine
						ORDER BY gr_ordinamento, ri_riga";
	$result = $mysqli->query($query, MYSQLI_USE_RESULT);
	while($row = $result->fetch_assoc()) {
		// Non stampo le righe del gruppo speciali
		if ($row['ar_gruppo'] == 'Speciali') {
			continue;
		}
		// se cambia gruppo o ho stampato 10 righe devo creare una nuova sezione
		if (($row['ar_gruppo'] != $gruppo) || ($righestampate >= 10)) {
			switch ($sezione) {
				case 0:
					$sezione = 1;
					stampa_testa();
					break;
				case 1:
					// stampo righe vuote per compleare la sezione
					while ($righestampate < 10) {
						stampa_riga_vuota();
					}
					$sezione = 2;
					stampa_testa();
					break;
				case 2:
					// stampo righe vuote per compleare la sezione
					while ($righestampate < 10) {
						stampa_riga_vuota();
					}
					$sezione = 3;
					stampa_testa();
					break;
				case 3:
					// stampo righe vuote per compleare la sezione
					while ($righestampate < 10) {
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
	}
	if ($sezione > 0) {
		while ($righestampate < 10) {
			stampa_riga_vuota();
		}
	}
	$result->close();

	$pdf->Output("./stampe/stampa_ordine/" . $ordine . ".pdf", "F");
	$pdf->Output();

	// chiudo connessione
	$mysqli->close();

	function stampa_testa() {
		global $pdf, $totaleordine, $sezione, $righestampate, $row, $altriga, $nome, $ordine, $tipo;

		// in base alla sezione calcolo la posizione di Y
		if ($sezione == 1) {
			$y = 5;
		}
		if ($sezione == 2) {
			$y = 100;
		}
		if ($sezione == 3) {
			$y = 200;
		}

		$pdf->SetLineWidth(1);
		$pdf->setxy(10,$y);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(190,10,'Mercatini Natale 2017',0,1,"C");
		$y += 10;

		// stampo testatina
		$pdf->SetLineWidth(0.2);
		$pdf->setxy(10,$y);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(18,$altriga, iconv('UTF-8', 'windows-1252', "Q.tà"),1,0, "C");
		//$pdf->Cell(40,$altriga,"Codice",1,0);
		$pdf->Cell(122,$altriga,"Descrizione",1,0);
		$pdf->Cell(20,$altriga,"Totale", 1,1);

		// imposto le celle a destra della stampa
		$pdf->SetLineWidth(1);
		// Tavolo
		$pdf->SetFont('Arial','',12);
		$pdf->setxy(170,$y);
		$pdf->Cell(30,$altriga, iconv('UTF-8', 'windows-1252', "Tavolo N°"),"LTR",1, "C");
		$y += $altriga;
		if ($tipo == 'A') {
			$tavolo = "ASPORTO";
		} else {
			$tavolo = "";
		}
		$pdf->setxy(170,$y);
		$pdf->Cell(30,$altriga * 2, $tavolo,"LBR",1, "C");
		$y += $altriga * 2;

		// preparo 2 righe per il nome
		$nome1 = "";
		$nome2 = "";
		$array_nomi = explode(" ", $nome);
		if (count($array_nomi) == 1) {
			$nome1 = $array_nomi[0];
		}
		if (count($array_nomi) == 2) {
			$nome1 = $array_nomi[0];
			$nome2 = $array_nomi[1];
		}
		if (count($array_nomi) >= 3) {
			if ((strlen($array_nomi[0] . $array_nomi[1])) < (strlen($array_nomi[1] . $array_nomi[2]))) {
				$nome1 = $array_nomi[0] . " " . $array_nomi[1];
				$nome2 = $array_nomi[2];
			} else {
				$nome1 = $array_nomi[0];
				$nome2 = $array_nomi[1] . " " . $array_nomi[2];
			}
		}
		if ($nome2 == "") {
			$pdf->setxy(170,$y);
			$my_string = iconv('UTF-8', 'windows-1252', $nome1);
			$font_size = 14;
			$decrement_step = 0.1;
			$line_width = 30; // Line width (approx) in mm
			$pdf->SetFont('Arial', '', $font_size);
			while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
				$pdf->SetFontSize($font_size -= $decrement_step);
			}
			$pdf->Cell($line_width, $altriga * 2, $my_string, 1, 0, "C");
			$y += $altriga * 2;
		} else {
			$pdf->setxy(170,$y);
			// prima parte del nome
			$my_string = iconv('UTF-8', 'windows-1252', $nome1);
			$font_size = 14;
			$decrement_step = 0.1;
			$line_width = 30; // Line width (approx) in mm
			$pdf->SetFont('Arial', '', $font_size);
			while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
				$pdf->SetFontSize($font_size -= $decrement_step);
			}
			$pdf->Cell($line_width, $altriga, $my_string, "LTR", 0, "C");
			$y += $altriga;

			$pdf->setxy(170,$y);
			// seconda parte del nome
			$my_string = iconv('UTF-8', 'windows-1252', $nome2);
			$font_size = 14;
			$decrement_step = 0.1;
			$line_width = 30; // Line width (approx) in mm
			$pdf->SetFont('Arial', '', $font_size);
			while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
				$pdf->SetFontSize($font_size -= $decrement_step);
			}
			$pdf->Cell($line_width, $altriga , $my_string, "LBR", 0, "C");
			$y += $altriga;
		}

		// Ordine
		$pdf->SetFont('Arial','',12);
		$pdf->setxy(170,$y);
		$pdf->Cell(30,$altriga, iconv('UTF-8', 'windows-1252', "Ordine N°"),"LTR",1, "C");
		$y += $altriga;
		$pdf->SetFont('Arial','B',22);
		$pdf->setxy(170,$y);
		$pdf->Cell(30,($altriga * 2), $ordine,"LBR",1, "C");
		$y += $altriga * 2;

		// Totale
		$pdf->SetFont('Arial','',12);
		$pdf->setxy(170,$y);
		$pdf->Cell(30,$altriga, "Totale","LTR",1, "C");
		$y += $altriga;
		$pdf->SetFont('Arial','B',20);
		$pdf->setxy(170,$y);
		$pdf->Cell(30,($altriga * 2), EURO . " " . $totaleordine,"LBR",1, "C");

		// azzero numero righe stampate
		$righestampate = 0;

		$pdf->SetLineWidth(0.2);

		// mi riposizione il cursore per la stampa delle righe
		if ($sezione == 1) {
			$pdf->setxy(10, (5 + 10 + $altriga));
		}
		if ($sezione == 2) {
			$pdf->setxy(10, (100 + 10 + $altriga));
		}
		if ($sezione == 3) {
			$pdf->setxy(10, (200 + 10 + $altriga));
		}

	}

	function stampa_riga () {
		global $pdf, $row, $righestampate, $altriga;

		$totale_riga = number_format(($row['ri_quantita'] * $row['ri_prezzo']), 2);
		$pdf->SetFont('Arial','B',14);
		$pdf->Cell(18,$altriga,$row['ri_quantita'],1,0, "C");
		$pdf->SetFont('Arial','',12);

		// se la riga è stata modificata lo segnalo con *** all'inizio
		if ($row['ri_mod'] == '*' ) {
			$descrizione = '*** ' . $row['ri_descrizione'];
		} else {
			$descrizione = $row['ri_descrizione'];
		}
		$my_string = iconv('UTF-8', 'windows-1252', $descrizione);
		$font_size = 12;
		$decrement_step = 0.1;
		$line_width = 122; // Line width (approx) in mm
		$pdf->SetFont('Arial', '', $font_size);
		while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
			$pdf->SetFontSize($font_size -= $decrement_step);
		}
		$pdf->Cell($line_width, $altriga, $my_string, 1, 0);
		//$pdf->Cell(80,10,$row['ri_descrizione'],1,0);

		$pdf->SetFont('Arial','',12);
		$pdf->Cell(20,$altriga, EURO . " " . $totale_riga,1,1, "R");
		$righestampate += 1;
	}

	function stampa_riga_vuota() {
		global $pdf, $righestampate, $altriga;
		$pdf->SetFont('Arial','',12);
		$pdf->Cell(18,$altriga, "",1,0);
		//$pdf->Cell(40,$altriga, "",1,0);
		$pdf->Cell(122,$altriga, "", 1, 0);
		$pdf->Cell(20,$altriga, "",1,1);
		$righestampate += 1;
	}
