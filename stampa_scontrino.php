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
			} else {
				$script .= "pp.interactive = pp.constants.interactionLevel.automatic;";
				$script .= "pp.printerName = '\\\\\\\\".$server."\\\\".$printer."';";
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
	} else{
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
	$totale_ordine = $row['totaleordine'];
	$result->close();

	$pdf=new PDF_AutoPrint('P','mm',array(80,400));
	$pdf->SetMargins(4, 0, 0);
	$pdf->AddPage();
	$pdf->SetAutoPageBreak(true , 0);
	$pdf->SetFont('Arial','',8);

	/**
	 * lista articoli
	 * reperisco dati da anagrafica
	 */
	$totale_ordine = number_format($totale_ordine, 2, ",", ".");
	$altriga = 8;
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

	$pdf->Output("./stampe/stampa_scontrino/" . $ordine . ".pdf", "F");
	$pdf->Output();

	// chiudo connessione
	$mysqli->close();


	function stampa_testa() {
		global $pdf, $totaleordine, $row, $altriga, $nome, $ordine;
		$pdf->setxy(4,5);
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(68,$altriga,'Mercatini Natale 2017',0,1,"C");
		$pdf->Cell(68,$altriga, iconv('UTF-8', 'windows-1252', "Ordine n° " . $ordine), "B", 1, "R");
	}

	function stampa_riga () {
		global $pdf, $row, $altriga;
		$totale_riga = number_format(($row['ri_quantita'] * $row['ri_prezzo']), 2);
		$pdf->SetFont('Arial','B',10);
		$pdf->Cell(8,$altriga,$row['ri_quantita'] . " -",0,0, "R");
		$my_string = iconv('UTF-8', 'windows-1252', strtoupper($row['ri_descrizione']));
		$font_size = 10;
		$decrement_step = 0.1;
		$line_width = 60; // Line width (approx) in mm
		$pdf->SetFont('Arial', 'B', $font_size);
		while($pdf->GetStringWidth($my_string) > ($line_width - 2)) {
			$pdf->SetFontSize($font_size -= $decrement_step);
		}
		$pdf->Cell($line_width, $altriga, $my_string, 0, 1);
	}

	function stampa_totale () {
		global $pdf, $row, $totale_ordine, $altriga;
		$pdf->SetFont('Arial','B',12);
		$pdf->Cell(8,10, "","TB",0, "C");
		$pdf->Cell(40, 10, "TOTALE", "TB", 0);
		$pdf->Cell(20,10, EURO . " " . $totale_ordine, "TB", 1, "R");
	}
