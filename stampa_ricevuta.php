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
	require('./classe_ordine.php');

	class PDF_AutoPrint extends PDF_JavaScript {
		function AutoPrint($dialog=false) {
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
			}	else {
				$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
				$script .= "pp.printerName = '\\\\\\\\" . $server . "\\\\" . $printer . "';";
				$script .= "print(pp);";
				$this->IncludeJS($script);
			}
		}
	}
	define('EURO',chr(128));

	// recupero l'utente corrente per sapere la cartella dove creare il pdf
	$utente = $_SESSION['user'];
	$query = sprintf("SELECT * FROM utenti WHERE ut_nome = '%s'", $utente);
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$cartella = $row['ut_cartella'];
	$result->close();

	$query = "SELECT * FROM ordini WHERE or_numero = " .$ordine;

	$result = $mysqli->query($query);
	//$row = $result->fetch_assoc();

	if ($result->num_rows == 0) {
		echo("<br />Ordine non trovato!!");
		die();
	} else{
		$row = $result->fetch_assoc();
		//Informazioni sull'utente
		$nome = $row['or_cliente'];
		$totale_ordine = $row['or_totale'];
		$tipo = $row['or_tipo'];
		$stato = $row['or_stato'];
		$coperti = $row['or_coperti'];
	}
	$result->close();

	// verifico se devo aggiungere in automatico i coperti sulle bibite
	if ($coperti_automatico_attivo) {
		if ($tipo == 'T' && $coperti != 0) {
			$query = sprintf(
			"SELECT COUNT(*) as totrighe FROM ordinirighe WHERE ri_ordine = '%s' and ri_codice IN
			(SELECT ar_codice FROM articoli WHERE ar_gruppo = '%s')"
			,$ordine, $coperti_automatico_gruppo);
			$result = $mysqli->query($query);
			$row = $result->fetch_assoc();
			$totrighe = $row['totrighe'];
			$result->close();
			if ($totrighe == 0) {
				$ordine1 = new Ordine($ordine);
				$ordine1->addRiga($coperti_automatico_articolo);
			}
		}
	}
	// recupero il totale dell'ordine facendo la somma delle righe
	$query = "SELECT SUM(ri_quantita * ri_prezzo) AS totaleordine FROM ordinirighe WHERE ri_ordine = " .$ordine;
	$result = $mysqli->query($query);
	$row = $result->fetch_assoc();
	$totale_ordine = $row['totaleordine'];
	$result->close();

	// aggiorno lo stato
		/**
		 * se sono di tipo bar o spunceti la stampa della ricevuta
		 * salda automaticamente tutte le righe e la testata
		 */
	switch ($tipo) {
		case "B":
			$stato = 5;
			break;
		case "T":
			$stato = 1;
			break;
		case "A":
			$stato = 2;
			break;
	}

	// aggiorno le righe con il nuovo stato
	$mysqli->query(sprintf("UPDATE ordinirighe
													SET ri_stato = '%s'
													WHERE ri_ordine = '%s'
													AND ri_stato < '%s'",
													$stato, $ordine, $stato));

	// cambio lo stato dell'ordine e il totale
	$mysqli->query(sprintf("UPDATE ordini
													SET or_stato = (
														SELECT MIN(ri_stato)
														FROM ordinirighe
														WHERE ri_ordine = or_numero
													), or_totale = '%s'
													WHERE or_numero = '%s'",
													$totale_ordine, $ordine));

	// se ho messo stato 2 allora metto anche or_data_abbina
	if ($stato == 2) {
		$mysqli->query(sprintf("UPDATE ordini
														SET or_data_abbina = now()
														WHERE or_numero = '%s'
														AND or_data_abbina IS NULL",
														$ordine));
	}

	// se ho messo stato 5 allora metto anche or_data_fine
	if ($stato == 5) {
		$mysqli->query(sprintf("UPDATE ordini
														SET or_data_fine = now()
														WHERE or_numero = '%s'
														AND or_data_fine IS NULL",
														$ordine));
	}

	// creo nuova pagina da 8 cm per 40 cm
	$pdf=new PDF_AutoPrint('P','mm',array(80,400));
	$pdf->SetMargins(5, 0, 0);
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true , 0);
	$pdf->SetFont('Arial','',8);

	//lista articoli
	//reperisco dati da anagrafica
	$totale_ordine = number_format($totale_ordine, 2, ",", ".");
	$altriga = 6;
	stampa_testa();
	$query = "SELECT * FROM ordinirighe
						JOIN articoli ON ri_codice = ar_codice
						JOIN gruppi ON ar_gruppo = gr_codice
						WHERE ri_ordine = $ordine
						ORDER BY gr_ordinamento, ri_riga";
	$result = $mysqli->query($query, MYSQLI_USE_RESULT);
	while($row = $result->fetch_assoc()) {
		// stampa riga pdf
		stampa_riga();
	}
	// stampo totale
	stampa_totale();
	$result->close();

	stampa_coda();

	$pdf->Output("./stampe/" . $cartella . "/" . $ordine . ".pdf", "F");
	$pdf->Output("./stampe/" . $cartella . "/" . $ordine . "_2.pdf", "F");
	$pdf->Output();

	//----------------------------------------------------------------
	// stampo scontrino solo per righe Dolci (gr_buono = 'S')
	if ($tipo == 'T') {
		$stampa_buono = false;
		$query = "SELECT * FROM ordinirighe
							JOIN articoli ON ri_codice = ar_codice
							JOIN gruppi ON ar_gruppo = gr_codice
							WHERE ri_ordine = $ordine and gr_buono = 'S'
							ORDER BY gr_ordinamento, ri_riga";
		$result = $mysqli->query($query, MYSQLI_USE_RESULT);
		while($row = $result->fetch_assoc()) {
			// stampo la testata alla prima riga
			if (!$stampa_buono) {
				// creo nuova pagina da 8 cm per 40 cm
				stampa_buono_testa();
				$stampa_buono = true;
			}
			// stampa riga pdf
			stampa_buono_riga();
		}
		if ($stampa_buono) {
			stampa_buono_fine();
			// salvo
			$pdf->Output("./stampe/" . $cartella . "/" . $ordine . "_buono.pdf", "F");
			//$pdf->Output();
		}

		stampa_coda();

		$result->close();
	}
	//----------------------------------------------------------------


	// chiudo connessione
	$mysqli->close();

	function stampa_testa() {
		global $pdf, $totaleordine, $row, $altriga, $nome, $ordine;

		$pdf->setxy(5,5);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(70,$altriga,'Sagra Mandria 2017',0,1,"C");
		$pdf->Cell(70,$altriga, iconv('UTF-8', 'windows-1252', "Ordine n° " . $ordine), 0, 1, "R");
		$pdf->Cell(70,$altriga, iconv('UTF-8', 'windows-1252', $nome), "B", 1, "L");
		$pdf->Cell(70,2,"",0,1);
	}

	function stampa_riga () {
		global $pdf, $row, $altriga;
		$altriga = 3;

		$totale_riga = number_format(($row['ri_quantita'] * $row['ri_prezzo']), 2);
		$pdf->SetFont('Arial','B',8);

		// stampo riga con descrizione
		if ($row['ri_mod'] == "") {
			$my_string = iconv('UTF-8', 'windows-1252', strtoupper($row['ri_descrizione']));
		} else {
			$my_string = iconv('UTF-8', 'windows-1252', strtoupper($row['ri_descrizione']));
			$my_string = "* " . $my_string;
		}

		$font_size = 8;
		$decrement_step = 0.1;
		$line_width = 70; // Line width (approx) in mm
		$pdf->SetFont('Arial', 'B', $font_size);
		while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
			$pdf->SetFontSize($font_size -= $decrement_step);
		}
		$pdf->Cell($line_width, $altriga, $my_string, 0, 1);

		// stampo nota in corsivo
		if ($row['ri_nota'] <> "") {
			$my_string = iconv('UTF-8', 'windows-1252', "* " .strtoupper($row['ri_nota']) . " *");
			$font_size = 8;
			$decrement_step = 0.1;
			$line_width = 70; // Line width (approx) in mm
			$pdf->SetFont('Arial', 'BI', $font_size);
			while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
				$pdf->SetFontSize($font_size -= $decrement_step);
			}
			$pdf->Cell($line_width, $altriga, $my_string, 0, 1, "L");
		}

		// stampo riga con quantità e prezzo
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(10,$altriga,$row['ri_quantita'],0,0, "R");
		$pdf->Cell(5,$altriga," x ",0,0, "C");
		$pdf->Cell(15,$altriga,$row['ri_prezzo'],0,0, "R");
		$pdf->Cell(40,$altriga,$totale_riga,0,1, "R");
		// alla fine della riga faccio una riga vuota di separazione
		$pdf->Cell(70,$altriga,"",0,1);
	}

	function stampa_totale () {
		global $pdf, $row, $totale_ordine, $altriga, $tipo;

		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(10,10, "","TB",0, "C");
		$pdf->Cell(40, 10, "TOTALE", "TB", 0);
		$pdf->Cell(20,10, EURO . " " . $totale_ordine, "TB", 1, "R");

	}

function stampa_buono_testa() {

	global $pdf, $totaleordine, $row, $nome, $ordine;

	$pdf=new PDF_AutoPrint('P','mm',array(80,400));
	$pdf->SetMargins(5, 0, 0);
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true , 0);
	$pdf->SetFont('Arial','',8);

	$pdf->setxy(5,5);
	$pdf->SetFont('Arial','B',12);
	$pdf->Cell(70,6,'Sagra Mandria 2017',0,1,"C");
	$pdf->Cell(70,6, iconv('UTF-8', 'windows-1252', "Ordine n° " . $ordine), 0, 1, "R");
	$pdf->Cell(70,6, iconv('UTF-8', 'windows-1252', $nome), 0, 1, "L");
	$pdf->Cell(70,2,"",0,1);
	$pdf->SetFont('Arial','B',16);
	$pdf->Cell(70,6, iconv('UTF-8', 'windows-1252', "BUONO PER RITIRO"), "B", 1, "C");
	$pdf->Cell(70,2,"",0,1);
}

function stampa_buono_riga() {

	global $pdf, $row;

	// stampo quantità
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(10,6,$row['ri_quantita']." x",0,0, "R");

	// stampo riga con descrizione
	if ($row['ri_mod'] == "") {
		$my_string = iconv('UTF-8', 'windows-1252', strtoupper($row['ar_descbreve']));
	} else {
		$my_string = iconv('UTF-8', 'windows-1252', strtoupper($row['ri_descrizione']));
		$my_string = "* " . $my_string;
	}

	$font_size = 14;
	$decrement_step = 0.1;
	$line_width = 60; // Line width (approx) in mm
	$pdf->SetFont('Arial', 'B', $font_size);
	while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
		$pdf->SetFontSize($font_size -= $decrement_step);
	}
	$pdf->Cell($line_width, 6, $my_string, 0, 1);

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
		$pdf->Cell(10,6,"",0,0, "R");
		$pdf->Cell($line_width, 6, $my_string, 0, 1, "L");
	}
	// alla fine della riga faccio una riga vuota di separazione
	$pdf->Cell(70,6,"",0,1);

}
function stampa_buono_fine() {
	global $pdf;
	$pdf->SetFont('Arial','B',10);
	$pdf->Cell(70, 1, " ", "B", 1, "C");
	$pdf->Cell(70, 2, " ", 0, 1, "C");
	$pdf->Cell(70, 5, "", 0, 1, "C");
	$pdf->Cell(70, 5, "Usate questo buono per ritirare", 0, 1, "C");
	$pdf->Cell(70, 5, "", 0, 1, "C");
	$pdf->Cell(70, 5, "", 0, 1, "C");
	$pdf->Cell(70, 5, "i prodotti indicati alla", 0, 1, "C");
	$pdf->Cell(70, 5, "", 0, 1, "C");
	$pdf->Cell(70, 5, "", 0, 1, "C");
	$pdf->Cell(70, 5, "distribuzione bibite", 0, 1, "C");
}

function stampa_coda() {

}
