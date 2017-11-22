<?php
/** Error reporting */
error_reporting(E_ALL);

/** PHPExcel */
include_once("includes/config.php");

include_once("includes/check_utente.php");

/** controllo di che tipo � l'utente*/
if ($_SESSION['tipo'] != "admin") {
	header("location: index.php");
	exit;
}

include 'Classes/PHPExcel.php';


//QUERY GIORNI E DATE
$data=array();
$fogli=array();
$i=0;
$sql="SELECT * FROM serate";
$query = $mysqli->query($sql);
while($result=mysqli_fetch_array($query)){
	$data[$i]=$result['se_data'];
	$fogli[$i]=$result['se_descrizione'];
	$i++;
}//while
$fogli[$i]='GENERALE';
$fogli[$i+1]='Giacenze';
$fogli[$i+2]='Ricavo Casse';
$numGiorni=count($data)-1;

//QUERY NUMERO ORDINI
$numOr=array();
$numOrTot=0;
for($i=0;$i<count($data);$i++ ){
		$h=$i+1;
		$sql="SELECT COUNT(or_numero) FROM ordini,serate WHERE or_stato>0 AND or_serata=se_numero AND se_numero=$h";
		$query = $mysqli->query($sql);
		$result=mysqli_fetch_array($query);
		$numOr[$i] = $result[0];
		$numOrTot+=$numOr[$i];
}

//QUERY Coperti
$copDay=array();
$copTot=0;
for($i=0;$i<count($data);$i++){
	$h=$i+1;
	$sql="SELECT sum(or_coperti) AS coperto FROM ordini,serate WHERE or_stato>0 AND or_serata=se_numero AND se_numero=$h";
	$query = $mysqli->query($sql);
	$result=mysqli_fetch_array($query);
	if($result['coperto']!==NULL)
		$copDay[$i] = $result['coperto'];
	else
		$copDay[$i] = 0;
	$copTot+=$copDay[$i];
}

//QUERY RICAVO GIORNALIERO E TOTALE
$prezzoOr=array();
$prezzoOrTot=0;
//query prezzo tot
for($i=0;$i<count($data);$i++ ){
		$h=$i+1;
		$sql="SELECT sum(or_totale) AS prezzo FROM ordini,serate WHERE or_stato>0 AND or_serata=se_numero AND se_numero=$h";
		$query = $mysqli->query($sql);
		$result=mysqli_fetch_array($query);
		if($result['prezzo']!==NULL)
			$prezzoOr[$i] = $result['prezzo'];
		else
			$prezzoOr[$i] = 0;
		$prezzoOrTot+=$prezzoOr[$i];
}


//ARRAY NOME ALIMENTI E PREZZO
$nomiAlim=array();
$prezzoAlim=array();
$sql="SELECT ar_codice, ar_prezzo FROM articoli WHERE ar_attivo='S' AND ar_gruppo<>'Aggiunte'";
$query = $mysqli->query($sql);
//echo $sql;
$i=0;
while($result=mysqli_fetch_array($query)){
	$nomiAlim[$i]=$result['ar_codice'];
	$prezzoAlim[$i]=(double)$result['ar_prezzo'];
	//echo 'Nome'.$nomiAlim[$i].' Prezzo'.$prezzoAlim[$i];
	$i++;
}


//Giacenze
$prodotto=array();
$giacenza=array();
$i=0;
$sql="SELECT * FROM magazzino ORDER BY ma_gruppi, ma_codiceprodotto ASC";
$query = $mysqli->query($sql);
while($result=mysqli_fetch_array($query)){
	$prodotto[$i]=$result['ma_codiceprodotto'];
	$giacenza[$i]=(double)$result['ma_giacenza'];
	$i++;
}//while

//ricavo casse giornaliero
$cassa=array();
$ricavo=array();
$serata=array();
$i=0;
$sql="SELECT or_cassa, SUM(or_totale) as ricavo, or_serata FROM ordini WHERE or_stato>0 GROUP BY or_cassa, or_serata ORDER BY or_serata, or_cassa ASC";
$query = $mysqli->query($sql);
while($result=mysqli_fetch_array($query)){
	$cassa[$i]=$result['or_cassa'];
	$ricavo[$i]=(double)$result['ricavo'];
	$serata[$i]=$result['or_serata'];
	$i++;
}//while

//ricavo casse intera festa
$cassaAll=array();
$ricavoAll=array();
$i=0;
$sql="SELECT or_cassa, SUM(or_totale) as ricavo FROM ordini WHERE or_stato>0 GROUP BY or_cassa ORDER BY or_serata, or_cassa ASC";
$query = $mysqli->query($sql);
while($result=mysqli_fetch_array($query)){
	$cassaAll[$i]=$result['or_cassa'];
	$ricavoAll[$i]=$result['ricavo'];
	$i++;
}//while

// Create new PHPExcel object
$objPHPExcel = new PHPExcel();
// Set document properties
$objPHPExcel->getProperties()->setCreator("MandriaFest 2017")
							 ->setLastModifiedBy("MandriaFest 2017")
							 ->setTitle("Rendiconto MandriaFest 2017")
							 ->setSubject("Rendiconto MandriaFest 2017")
							 ->setDescription("Rendiconto MandriaFest 2017")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");

for($h=0;$h<count($fogli);$h++){

		$objPHPExcel->createSheet();
		$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A1','MandriaFest 2017 - Rendiconto '.$fogli[$h].'');

		if($h<(count($fogli)-2)){
			//ORDINI
			$j=0;
			for($i=0;$i<count($data);$i++){
				$j=$i+3;
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A'.$j.'','NUM ORDINI'." ".$data[$i].'')->setCellValue('B'.$j.'',$numOr[$i]);
			}
			$a=$j+1;
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A'.$a.'','NUM ORDINI TOTALI')->setCellValue('B'.$a.'',$numOrTot);

			//RICAVO
			for($i=0;$i<count($data);$i++){
				$j=$i+3;
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('C'.$j.'','RICAVO'." ".$data[$i].'')->setCellValue('D'.$j.'',$prezzoOr[$i]);
				$objPHPExcel->setActiveSheetIndex($h)->getStyle('D'.$j.'')->getNumberFormat()->setFormatCode('#,##0.00');
			}
			$a=$j+1;
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('C'.$a.'','RICAVO TOTALE')->setCellValue('D'.$a.'',$prezzoOrTot);
			$objPHPExcel->setActiveSheetIndex($h)->getStyle('D'.$a.'')->getNumberFormat()->setFormatCode('#,##0.00');

			//COPERTI
			for($i=0;$i<count($data);$i++){
				$j=$i+3;
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('E'.$j.'','COPERTI'." ".$data[$i].'')->setCellValue('F'.$j.'',$copDay[$i]);
			}
			$a=$j+1;
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('E'.$a.'','COPERTI TOTALI')->setCellValue('F'.$a.'',$copTot);

			$b=$a+2;
			//TABELLA ORDINATA PER NOME DEL PIATTO
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A'.$b.'','Nome Piatto');
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('D'.$b.'','Prezzo Unit.');
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('E'.$b.'','Quant.');
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('F'.$b.'','Prezzo Tot.');

			$i=0;
			$qTot=array();
			$pTot=array();
			while($i<count($nomiAlim)){
				$j=$i+($b+1);
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A'.$j.'',$nomiAlim[$i]);
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('D'.$j.'',$prezzoAlim[$i]);
				$objPHPExcel->setActiveSheetIndex($h)->getStyle('D'.$j.'')->getNumberFormat()->setFormatCode('#,##0.00');
				if($h<count($data)){
					$sera=$h+1;
					$sql="SELECT ri_codice, sum(ri_quantita) as quantita, sum(ri_prezzo*ri_quantita) as prezzoTot FROM ordinirighe,ordini,articoli,serate
					where ri_codice=ar_codice and ri_ordine=or_numero and ri_codice='$nomiAlim[$i]' AND or_serata=se_numero AND or_serata=$sera and or_stato>0
					group by ri_codice
					order by ri_codice";
					$query = $mysqli->query($sql);
					while($result=mysqli_fetch_array($query)){
						//$prezzo= (double)$result['prezzoTot'];
						$objPHPExcel->setActiveSheetIndex($h)->setCellValue('E'.$j.'',$result['quantita']);
						$objPHPExcel->setActiveSheetIndex($h)->setCellValue('F'.$j.'',(double)$result['prezzoTot']);
						$objPHPExcel->setActiveSheetIndex($h)->getStyle('F'.$j.'')->getNumberFormat()->setFormatCode('#,##0.00');
						//$qTot[$i]+=$result['quantita'];
						//$pTot[$i]+=$result['prezzoTot'];
					}//while
				}//if
				else {
					$sql="SELECT ri_codice, sum(ri_quantita) as quantita, sum(ri_prezzo*ri_quantita) as prezzoTot FROM ordinirighe,ordini,articoli
					where ri_codice=ar_codice and ri_ordine=or_numero and ri_codice='$nomiAlim[$i]' and or_stato>0
					group by ri_codice
					order by ri_codice";
					$query = $mysqli->query($sql);
					while($result=mysqli_fetch_array($query)){
						$objPHPExcel->setActiveSheetIndex($h)->setCellValue('E'.$j.'',$result['quantita']);
						$objPHPExcel->setActiveSheetIndex($h)->setCellValue('F'.$j.'',(double)$result['prezzoTot']);
						$objPHPExcel->setActiveSheetIndex($h)->getStyle('F'.$j.'')->getNumberFormat()->setFormatCode('#,##0.00');
					}//while
				}//else
				$i++;
			}//while

			//NOME FOGLI
			//for($i=0;$i<count($fogli);$i++){
					$objPHPExcel->getActiveSheet()->setTitle($fogli[$h]);
			//}

			//STILI DI FORMATTAZIONE
			$c=3+count($data);
			$e=$c+2;
			$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A3:A'.$c.'')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('C3:C'.$c.'')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('E3:E'.$c.'')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$e.':J'.$e.'')->getFont()->setBold(true);

			$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);
			$objPHPExcel->getActiveSheet()->getStyle('A'.$e.':J'.$e.'')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			//UNISCI CELLE
			$objPHPExcel->setActiveSheetIndex($h)->mergeCells('A1:F1');

			for($i=0;$i<=count($nomiAlim);$i++){
					$j=$i+($b);
					$objPHPExcel->setActiveSheetIndex($h)->mergeCells('A'.$j.':C'.$j.'');
			}

			//BORDI CELLE
			$styleArray = array(
			  'borders' => array(
			    'allborders' => array(
			      'style' => PHPExcel_Style_Border::BORDER_THIN
			    )
			  )
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);

			$c=3+count($data);
			$objPHPExcel->getActiveSheet()->getStyle('A3:F'.$c.'')->applyFromArray($styleArray);

			for($i=0;$i<=count($nomiAlim);$i++){
					$j=$i+15;
					$objPHPExcel->setActiveSheetIndex($h)->getStyle('A'.$j.':F'.$j.'')->applyFromArray($styleArray);;
			}

		}//if
		if($h==(count($fogli)-2)){
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A3','Nome Prodotto');
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('B3','Giacenza');
			for($z=0;$z<count($prodotto);$z++){
				$j=$h+$z-1;
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A'.$j.'',$prodotto[$z]);
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('B'.$j.'',$giacenza[$z]);

				$objPHPExcel->setActiveSheetIndex($h)->mergeCells('B'.$j.':C'.$j.'');
				$objPHPExcel->setActiveSheetIndex($h)->getStyle('B'.$j.'')->getNumberFormat()->setFormatCode('#,##0.00');
			}

			$objPHPExcel->setActiveSheetIndex($h)->mergeCells('B3:C3');
			$objPHPExcel->setActiveSheetIndex($h)->mergeCells('A1:C1');
			$objPHPExcel->getActiveSheet()->getStyle('A1:B1')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('A3')->getFont()->setBold(true);
			$objPHPExcel->getActiveSheet()->getStyle('B3')->getFont()->setBold(true);

			$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle('A1:C1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->applyFromArray($styleArray);
			for($j=4;$j<(count($prodotto)+4);$j++){
				$objPHPExcel->setActiveSheetIndex($h)->getStyle('A'.$j.':C'.$j.'')->applyFromArray($styleArray);
			}

			$objPHPExcel->getActiveSheet()->setTitle($fogli[$h]);
		}

    if($h==(count($fogli)-1)){
      $objPHPExcel->getActiveSheet()->setTitle($fogli[$h]);

			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('A3','RICAVO DAY');
      $objPHPExcel->setActiveSheetIndex($h)->setCellValue('A4','NOME CASSA');
      $objPHPExcel->setActiveSheetIndex($h)->setCellValue('B4','RICAVO');
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('C4','SERATA');
      for($i=0;$i<count($cassa);$i++){
        $j=$i+5;
        $objPHPExcel->setActiveSheetIndex($h)->setCellValue('A'.$j.'',$cassa[$i]);
        $objPHPExcel->setActiveSheetIndex($h)->setCellValue('B'.$j.'',$ricavo[$i]);
				$objPHPExcel->setActiveSheetIndex($h)->setCellValue('C'.$j.'',$serata[$i]);
      }

			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('E3','RICAVO ALL');
			$objPHPExcel->setActiveSheetIndex($h)->setCellValue('E4','NOME CASSA');
      $objPHPExcel->setActiveSheetIndex($h)->setCellValue('F4','RICAVO');
      for($i=0;$i<count($cassaAll);$i++){
        $j=$i+5;
        $objPHPExcel->setActiveSheetIndex($h)->setCellValue('E'.$j.'',$cassaAll[$i]);
        $objPHPExcel->setActiveSheetIndex($h)->setCellValue('F'.$j.'',$ricavoAll[$i]);
      }
			//STILI DI FORMATTAZIONE
			$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex($h)->mergeCells('A1:F1');

			$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex($h)->mergeCells('A3:C3');

			$objPHPExcel->getActiveSheet()->getStyle('E3:F3')->applyFromArray($styleArray);
			$objPHPExcel->getActiveSheet()->getStyle('E3:F3')->getFont()->setBold(true);
			$objPHPExcel->setActiveSheetIndex($h)->mergeCells('E3:F3');

			for($i=0;$i<=count($cassa);$i++){
					$j=$i+4;
					$objPHPExcel->setActiveSheetIndex($h)->getStyle('A'.$j.':C'.$j.'')->applyFromArray($styleArray);;
			}
			for($i=0;$i<=count($cassaAll);$i++){
					$j=$i+4;
					$objPHPExcel->setActiveSheetIndex($h)->getStyle('E'.$j.':F'.$j.'')->applyFromArray($styleArray);;
			}

			$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle('A3:C3')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle('E3:F3')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle('A4:C4')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

			$objPHPExcel->getActiveSheet()->getStyle('E4:F4')->getAlignment()->applyFromArray(
			    array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,)
			);

    }//if

		//AUTOSIZE COLONNE
		for($col = 'A'; $col !== 'Z'; $col++) {
		    $objPHPExcel->getActiveSheet()
		        ->getColumnDimension($col)
		        ->setAutoSize(true);
		}


}//for
// Redirect output to a client�s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="rendiconto.xlsx"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>
