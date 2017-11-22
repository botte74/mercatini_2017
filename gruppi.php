<?php
	include_once("includes/config.php");

	include_once("includes/check_utente.php");

	/** controllo di che tipo è l'utente */
	if ($_SESSION['tipo'] != "admin") {
		header("location: index.php");
		exit;
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Gruppi</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/dataTables.bootstrap.min.css" rel="stylesheet" type="text/css">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link rel="icon" href="./img/icon.ico" />

		<!-- jQuery e plugin JavaScript  -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/jquery.dataTables.min.js"></script>
		<script src="js/dataTables.bootstrap.min.js"></script>
	</head>

  <body>
		<?php
		/** includi la barra dei pulsanti */
		include("includes/testata_admin.php");

		if(isset($_GET['gruppo'])) {
			$operation=explode("-",$_GET['gruppo']);
			$gruppo=$operation[1];
			$delete="DELETE FROM gruppi WHERE gr_codice='$gruppo'";
			$query = $mysqli->query($delete);
		}
		?>
  	<div class="container-fluid">
			<div class="row">
				<div class="col-lg-12">
					<!-- tabella gruppi -->
					<div class="row" id="gruppi">
							<div class="table-responsive">
								<div class="dt-example dt-example-bootstrap">
									<div class="container-fluid">
										<section>
											<table  id="tabellagruppi" class="table table-striped table-bordered" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>Codice</th>
														<th>Descrizione</th>
														<th>Attivo</th>
														<th>Ordinamento</th>
														<th>Stile</th>
														<th>Stato</th>
														<th>Tipo stampa</th>
														<th>Cartella</th>
														<th>Barcode</th>
														<th>Ritardo</th>
														<th>Griglia</th>
														<th>Modifica</th>
														<th>Elimina</th>
													</tr>
												</thead>
												<tbody>
												<?php
												/** estraggo tutti i gruppi dalla tabella */
												$query = "SELECT * FROM gruppi";
												$result = $mysqli->query($query);

												/** ciclo per mettere in tabella tutti gli i gruppi */
												while($row = $result->fetch_assoc()) {
													$codice=$row['gr_codice'];
													$descrizione=$row['gr_descrizione'];
													$attivo=$row['gr_attivo'];
													$ordinamento=$row['gr_ordinamento'];
													$stile=$row['gr_stile'];
													$stato=$row['gr_stato'];
													$tipo_stampa=$row['gr_tipostampa'];
													$cartella=$row['gr_cartella'];
													$barcode=$row['gr_barcode'];
													$ritardo=$row['gr_ritardo_stampa'];
													$griglia=$row['gr_griglia'];
													$mod="edit-".$codice;
													$del="delete-".$codice;
												?>

													<tr>
														<td style="text-align:center"><?php echo $codice; ?></td>
														<td style="text-align:center"><?php echo $descrizione; ?></td>
														<td style="text-align:center"><?php echo $attivo; ?></td>
														<td style="text-align:center"><?php echo $ordinamento; ?></td>
														<td style="text-align:center"><?php echo $stile; ?></td>
														<td style="text-align:center"><?php echo $stato; ?></td>
														<td style="text-align:center"><?php echo $tipo_stampa; ?></td>
														<td style="text-align:center"><?php echo $cartella; ?></td>
														<td style="text-align:center"><?php echo $barcode; ?></td>
														<td style="text-align:center"><?php echo $ritardo; ?></td>
														<td style="text-align:center"><?php echo $griglia; ?></td>
														<td style="text-align:center"><a href="modifica_gruppi.php?gruppo=<?php echo $mod; ?>"><img src="img/edit.png"></a></td>
														<td style="text-align:center"><a href="gruppi.php?gruppo=<?php echo $del; ?>"><img src="img/cancel.png"></a></td>
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
						</div> <!-- tabella gruppi -->
					<!-- script per stile e funzioni tabella -->
					<!-- non spostare da questa posizione -->
					<script type="text/javascript" language="javascript" class="init">
						$(document).ready(function() {
							$('#tabellagruppi').DataTable( {
								"scrollY":        "600px",
								"scrollCollapse": true,
								"paging":         false,
								"searching": false,
								"order": [[ 3, "asc" ]]
							});
						});
					</script> <!-- script per stile e funzioni tabella -->
					<a href="modifica_gruppi.php?gruppo=edit-nuovo" class="btn btn-danger btn-large btn-block ">NUOVO GRUPPO</a>
				</div>
			</div>
		</div>
	</body>
</html>
