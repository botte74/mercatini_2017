<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/**
	* codice php per estrarre dalle tabelle i vari ordini con gli stati
	* che ci interessano e presentarli in tabella
	*/

	/** trovo la serata */
	$query = "SELECT * FROM serate WHERE se_attiva = 'S'";
	$result = $mysqli->query($query);
	if ($result->num_rows == 0) {
		echo "Nessuna serata attiva";
	} else {
		$row = $result->fetch_assoc();
		$serata = $row['se_numero'];
		$result->close();
	}

	/** estraggo tutti i prodotti dalla tabella articoli con i filtri appropriati */
	$sql = "SELECT * FROM ordinirighe
			JOIN articoli ON ar_codice = ri_codice
			JOIN ordini ON ri_ordine = or_numero
			WHERE or_serata = $serata
			AND ri_stato IN ( 1, 2, 3, 4)
			AND ar_gruppo IN (
				SELECT gr_codice FROM gruppi
				WHERE gr_griglia = 'S'
				)";
	$myData = $mysqli->query($sql);

	$prodotti = [];
	$piatti = [];
	$venduti = [];
	$abbinati = array();
	$prepara = [];
	$evasi = [];

	$sql_piatti = "SELECT * FROM articoli
 									WHERE ar_gruppo IN (
 									SELECT gr_codice FROM gruppi
 									WHERE gr_griglia = 'S') 
									ORDER BY ar_gruppo DESC,
									ar_codice ASC";
	$piatti_result = $mysqli->query($sql_piatti);

	while ($piatto = $piatti_result->fetch_array(MYSQLI_ASSOC)) {
		$venduti[$piatto['ar_codice']] = 0;
		$abbinati[$piatto['ar_codice']] = 0;
		$prepara[$piatto['ar_codice']] = 0;
		$evasi[$piatto['ar_codice']] = 0;
		$piatti[$piatto['ar_codice']] = $piatto['ar_codice'];
	}

	while ($record = $myData->fetch_array(MYSQLI_ASSOC)) {
		if ($record['ri_stato'] == 1) {
		$venduti[$record['ar_codice']] = $venduti[$record['ar_codice']] + $record['ri_quantita'];
		}
		if ($record['ri_stato'] == 2) {
			$abbinati[$record['ar_codice']] = $abbinati[$record['ar_codice']] + $record['ri_quantita'];
		}
		if (($record['ri_stato'] == 3) || ($record['ri_stato'] == 4)) {
			$prepara[$record['ar_codice']] = $prepara[$record['ar_codice']] + $record['ri_quantita'];
		}
		if ($record['ri_stato'] == 5) {
			$evasi[$record['ar_codice']] = $evasi[$record['ar_codice']] + $record['ri_quantita'];
		}
	}
?>
	<!-- tabella prodotti per distribuzione -->
	<div class="row">
		<div class="table-responsive">
			<div class="dt-example dt-example-bootstrap">
				<div class="container-fluid">
					<section>
						<table id="prodotti" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Descrizione</th>
									<th>Venduti</th>
									<th>Abbinati</th>
									<th>Prepara</th>
									<th>Evasi</th>
								</tr>
							</thead>
							<tbody>

							<?php
							/** generazione tabella prodotti venduti */
							foreach ($piatti as $codice => $nome) {
							?>

								<tr>
									<td><?php echo $nome; ?></td>
									<td style="text-align:center"><?php echo $venduti[$codice]; ?></td>
									<td style="text-align:center"><?php echo $abbinati[$codice]; ?></td>
									<td style="text-align:center"><?php echo $prepara[$codice]; ?></td>
									<td style="text-align:center"><?php echo $evasi[$codice]; ?></td>
								</tr>
							<?php
							}
							?>
							</tbody>
						</table>
					</section>
				</div>
			</div>
		</div>
	</div> <!-- tabella prodotti per distribuzione -->

	<!-- script per stile e funzioni tabella -->
	<!-- non spostare da questa posizione -->
	<script type="text/javascript" language="javascript" class="init">
		$(document).ready(function() {
			$('#prodotti').DataTable( {
				"scrollY":        "600px",
				"scrollCollapse": true,
				"paging":         false,
				"searching": false,
				"order": [[ 3, "desc" ]]
			});
		});
	</script> <!-- script per stile e funzioni tabella -->



