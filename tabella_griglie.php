<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/**
	* codice php per estrarre dalle tabelle i vari ordini con gli stati
	* che ci interessano e presentarli in tabella
	*/
	$serata=0;
	/** trovo la serata */
	$query = "SELECT * FROM serate WHERE se_attiva = 'S'";
	$result = $mysqli->query($query);
	if ($result->num_rows == 0) {
		echo "Nessuna serata attiva";
	} else {
		$row = $result->fetch_assoc();
		$serata = $row['se_numero'];
	}
	$result->close();

?>
<head>
	 <script type="text/javascript" src="griglie_sara.js"></script>
</head>
	<!-- tabella prodotti per distribuzione -->
	<div class="row">
		<div class="table-responsive">
			<div class="dt-example dt-example-bootstrap">
				<div class="container-fluid">
					<section>
						<table id="prodotti" class="table table-bordered" cellspacing="0" width="100%" style="background:white; font-size:18px;">
							<thead>
								<tr>
									<th>Descrizione</th>
									<th>Aggiungi</th>
									<th>Aggiungi</th>
									<th>Azzera</th>
									<th>In Griglia</th>
									<th>Totali</th>
									<th>Evasi</th>
									<th>Disponibilità</th>
								</tr>
							</thead>
							<tbody>

							<?php
								$query = sprintf(
								"SELECT pr_codice, pr_descrizione, COALESCE(gri_richiesta, 0) AS gri_richiesta, 
								COALESCE(gri_quantita, 0) AS gri_quantita,
								SUM(COALESCE(ri_quantita, 0) * COALESCE(di_coefficente, 0) ) as quantitatotale, 
								SUM(CASE WHEN (COALESCE(ri_stato, 0) = 5) THEN (COALESCE(ri_quantita, 0) * COALESCE(di_coefficente, 0) ) ELSE 0 END) as quantitaevasa,
								(COALESCE(gri_quantita, 0) - SUM(COALESCE(ri_quantita, 0) * COALESCE(di_coefficente, 0))) as disponibilita
								FROM prodotti
								LEFT JOIN griglie ON pr_codice = gri_prodotto AND gri_serata = '%s'
								LEFT JOIN distinta ON pr_codice = di_codiceprodotto
								LEFT JOIN (SELECT * FROM ordinirighe JOIN ordini ON ri_ordine = or_numero WHERE or_serata = '%s') AS ordinierighe ON di_codicearticolo = ri_codice 
								WHERE pr_griglia = 'S'
								GROUP BY pr_codice, pr_descrizione, gri_richiesta
								ORDER BY pr_codice, pr_descrizione, gri_richiesta", $serata, $serata);
								$result = $mysqli->query($query);
								while($row = $result->fetch_assoc()) {
									$prodotto = $row['pr_codice'];
									$descrizione = $row['pr_descrizione'];
									$richiesta = $row['gri_richiesta'];
									$quantita = $row['gri_quantita'];
									$quantitatotale = $row['quantitatotale'];
									$quantitaevasa = $row['quantitaevasa'];
									$disponibilita = $row['disponibilita'];
									if ($richiesta == 1) {
										$colore = 'background:red';
									} else {
										$colore= 'background:null';
									}
							
							?>
								<tr style="<?php echo $colore; ?>">
										<td><button style="font-size:18px;" type="text" class="btn btn-info btn-block" onclick="noProblem(this.value)" value="<?php echo $prodotto; ?>"><?php echo $descrizione; ?></button></td>
										<td><input style="font-size:18px;" type="text" class="form-control" id="aggiungi<?php echo $prodotto; ?>"></input></td>
										<td><button style="font-size:18px;" type="submit" class="btn btn-success btn-block" onclick="incrementa(this.value)" value="<?php echo $prodotto; ?>">Aggiungi</button></td>
										<td><button style="font-size:18px;" type="submit" class="btn btn-warning btn-block" onclick="azzera(this.value)" value="<?php echo $prodotto; ?>">Azzera</button></td>
									<td style="text-align:center; font-weight:bold;"><?php echo $quantita; ?></td>
									<td style="text-align:center; font-weight:bold;"><?php echo $quantitatotale; ?></td>
									<td style="text-align:center; font-weight:bold;"><?php echo $quantitaevasa; ?></td>
									<td style="text-align:center; font-weight:bold;"><?php echo $disponibilita; ?></td>
								</tr>
							<?php
							}
							$result->close();
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
				"scrollY":        "800px",
				"scrollCollapse": true,
				"paging":         false,
				"searching": false,
				"order": [[ 0, "asc" ]],
				"autoWidth": false, //step 1
				"columnDefs": [
              { width: '30%', targets: 0 }, //step 2, column 1 out of 9
              { width: '10%', targets: 1 }, //step 2, column 3 out of 9
			  { width: '10%', targets: 2 }, //step 2, column 4 out of 9
			  { width: '10%', targets: 3 }, //step 2, column 5 out of 9
			  { width: '10%', targets: 4 }, //step 2, column 6 out of 9
			  { width: '10%', targets: 5 }, //step 2, column 6 out of 9
			  { width: '10%', targets: 6 }, //step 2, column 6 out of 9
			  { width: '10%', targets: 7 } //step 2, column 6 out of 9

           ]
			});
		});
	</script> <!-- script per stile e funzioni tabella -->
