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

<!-- tabella elenco ordini -->
<div class="row" id="elencoordini">
	<div class="table-responsive">
		<div class="dt-example dt-example-bootstrap">
			<div class="container-fluid">
				<section>
					<table  id="tabellaelencoordini" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Numero</th>
								<th>Cliente</th>
								<th>Modifica</th>
								<th>Ricevuta</th>
								<th>Distribuzione</th>
								<th>Venduto</th>
								<th>Abbinato</th>
								<th>Preparazione</th>
								<th>Evaso</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$utente=$_SESSION['user'];

							/**
							 * cerco tutti gli ordini della serata attiva;
							 * admin vede tutti gli ordini,
							 * gli altri utenti solo quelli che hanno fatto loro
							 */
							if(($_SESSION['tipo'] == "admin") || ($_SESSION['tipo'] == "distribuzione")) {
								$query = sprintf("SELECT * FROM ordini WHERE or_serata = '%s' ORDER BY or_numero DESC", $serata);
							} else {
								$query = sprintf("SELECT * FROM ordini WHERE or_serata = '%s' AND or_cassa='$utente' ORDER BY or_numero DESC", $serata);
							}
							$result = $mysqli->query($query);

							/** ciclo per mettere in tabella tutti gli ordini */
							while($row = $result->fetch_assoc()) {
								$ordine = $row['or_numero'];
								$cliente = $row['or_cliente'];
								$stato = $row['or_stato'];
								?>
								<tr>
									<td style="text-align:center"><?php echo $ordine; ?></td>
									<td style="text-align:center"><?php echo $cliente; ?></td>
									<td style="text-align:center"><a href="gestione_ordini.php?ordine=<?php echo $ordine; ?>"><img src="img/edit.png"></a></td>
									<td style="text-align:center"><a href="stampa_ricevuta.php?ordine=<?php echo $ordine; ?>" target="newtab"><img src="img/scontrino.png"></a></td>
									<td><div style="cursor:pointer;text-align:center" <?php echo "onClick=\"ristampa('" . $row['or_numero'] . "');\""?>>
									<img src="img/printer.png"></div></td>
								<?php
								// Venduti
								if ($stato >= 1) {
									?>
									<td style="text-align:center"><img src="img/green.png"></td>
									<?php
								} else {
									?>
									<td style="text-align:center"><img src="img/red.png"></td>
									<?php
								}
								// Abbinati
								if ($stato >= 2) {
									?>
									<td style="text-align:center"><img src="img/green.png"></td>
									<?php
								} else {
									?>
									<td style="text-align:center"><img src="img/red.png"></td>
									<?php
								}
								// Preparazione
								if ($stato >= 3) {
									?>
									<td style="text-align:center"><img src="img/green.png"></td>
									<?php
								} else {
									?>
									<td style="text-align:center"><img src="img/red.png"></td>
									<?php
								}
								// Evaso
								if ($stato >= 5) {
									?>
									<td style="text-align:center"><img src="img/green.png"></td>
									<?php
								} else {
									?>
									<td style="text-align:center"><img src="img/red.png"></td>
									<?php
								}
								?>
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
</div> <!-- tabella elenco ordini -->

<!-- script per stile e funzioni tabella -->
<!-- non spostare da questa posizione -->
<script type="text/javascript" language="javascript" class="init">
	$(document).ready(function() {
		$('#tabellaelencoordini').DataTable( {
			"scrollY":        "600px",
			"scrollCollapse": true,
			"paging":         false,
			"order": [[ 0, "desc" ]],
			"autoWidth": false, //step 1
			"columnDefs": [
              { width: '10%', targets: 0 }, //step 2, column 1 out of 9
              { width: '10%', targets: 2 }, //step 2, column 3 out of 9
			  { width: '10%', targets: 3 }, //step 2, column 4 out of 9
			  { width: '10%', targets: 4 }, //step 2, column 5 out of 9
			  { width: '10%', targets: 5 }, //step 2, column 6 out of 9
			  { width: '10%', targets: 6 }, //step 2, column 7 out of 9
			  { width: '10%', targets: 7 }, //step 2, column 8 out of 9
              { width: '10%', targets: 8 }  //step 2, column 9 out of 9
           ]
		});
	});
</script> <!-- script per stile e funzioni tabella -->
