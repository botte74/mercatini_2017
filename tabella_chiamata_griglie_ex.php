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
<div class="row" id="chiamatagriglie">
	<div class="table-responsive">
		<div class="dt-example dt-example-bootstrap">
			<div class="container-fluid">
				<section>
					<table  id="tabellachiamatagriglie" class="table table-striped table-bordered" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th>Descrizione</th>
								<th>Chiamata</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$query = sprintf(
							"SELECT prodotti.*, COALESCE(gri_richiesta, 0) AS gri_richiesta 
							FROM prodotti 
							LEFT JOIN griglie ON pr_codice = gri_prodotto AND gri_serata = '%s'
							WHERE pr_chiamata = 'S' order by pr_codice", $serata);
							
							$result = $mysqli->query($query);

							/** ciclo per mettere in tabella tutti gli ordini */
							while($row = $result->fetch_assoc()) {
								$prodotto = $row['pr_codice'];
								$descrizione = $row['pr_descrizione'];
								if ($row['gri_richiesta'] == 1) {
									$colore = 'background:red';
								} else {
									$colore= 'background:null';
								}
								?>
								<tr style="<?php echo $colore; ?>">
									<td style="text-align:center"><?php echo $descrizione; ?></td>
									<td><div style="cursor:pointer;text-align:center" <?php echo "onClick=\"richiesta('" . $prodotto . "');\""?>>
									<img src="img/megafono.png"></div></td>
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
		$('#tabellachiamatagriglie').DataTable( {
			"scrollY":        "300px",
			"scrollCollapse": true,
			"paging":         false,
			"order": [[ 0, "asc" ]],
			"searching": false,
			"autoWidth": false, //step 1
			"columnDefs": [
              { width: '70%', targets: 0 }, //step 2, column 1 out of 9
              { width: '80%', targets: 1 } //step 2, column 3 out of 9
           ]
		});
	});
</script> <!-- script per stile e funzioni tabella -->
