<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	$query = "SELECT or_numero, or_cliente, gr_barcode, or_tavolo, gr_descrizioneunificata,
		(TIMESTAMPDIFF(MINUTE, or_data_abbina, now())) AS ritardo, 
		CONCAT(or_numero, '.', gr_barcode) AS barcode 
		FROM ordini
		JOIN ordinirighe ON or_numero = ri_ordine
		JOIN articoli ON ri_codice = ar_codice 
		JOIN gruppi ON ar_gruppo = gr_codice
		JOIN serate ON or_serata = se_numero 
		WHERE ri_stato = 3
		AND se_attiva = 'S' 
		GROUP BY or_numero, or_cliente, gr_barcode, or_tavolo, gr_descrizioneunificata, ritardo, barcode ";
	
	//echo $query;
	$result = $mysqli->query($query, MYSQLI_USE_RESULT);
?>
	<!-- tabella ordini -->

	<div class="row">
		<div class="table-responsive">
			<div class="dt-example dt-example-bootstrap">
				<div class="container-fluid">
					<section>
						<table id="ordini_stampare" class="table table-striped table-bordered" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th>Ordine</th>
									<th>Cliente</th>
									<th>Gruppo</th>
									<th>Attesa Minuti</th>
									<th>Barcode</th>
                                    <th>Tavolo</th>
									<th>Stampa</th>
								</tr>
							</thead>
							<tbody>
							<?php
							/** generazione tabella prodotti venduti */
							 while($row = $result->fetch_assoc()) {
							?>
								<tr>
									<td style="text-align:center"><?php echo $row['or_numero']; ?></td>
									<td style="text-align:center"><?php echo $row['or_cliente']; ?></td>
									<td style="text-align:center"><?php echo $row['gr_descrizioneunificata']; ?></td>
									<td style="text-align:center"><?php echo $row['ritardo']; ?></td>
									<td style="text-align:center"><?php echo $row['barcode']; ?></td>
                                    <td style="text-align:center"><?php echo $row['or_tavolo']; ?></td>
									<td><div style="cursor:pointer;text-align:center" <?php echo "onClick=\"ristampa_speciale('" . $row['or_numero'] . "', '" .$row['gr_barcode'] . "');\""?>>
									<img src="img/printer.png"></div></td>
								</tr>
							<?php 
							} 
							$result->close(); 
							$mysqli->close();
							?>
							</tbody>
						</table>
					</section>
				</div>
			</div>
		</div>
	</div> <!-- tabella ordini -->

	<!-- script per stile e funzioni tabella -->
	<!-- non spostare da questa posizione -->
	<script type="text/javascript" language="javascript" class="init">
		$(document).ready(function() {
			$('#ordini_stampare').DataTable( {
				"scrollY":        "600px",
				"scrollCollapse": true,
				"paging":         false,
				"searching": false,
				"order": [[ 3, "desc" ]]
			});
		});
	</script> <!-- script per stile e funzioni tabella -->



