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
		<title>Utenti</title>

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

		if(isset($_GET['utente'])) {
			$operation=explode("-",$_GET['utente']);
			$utente=$operation[1];
			$delete="DELETE FROM utenti WHERE ut_nome='$utente'";
			$query = $mysqli->query($delete);
		}
		?>
  	<div class="container-fluid">
			<div class="row">
				<div class="col-lg-10 col-lg-offset-1">
					<!-- tabella utenti -->
					<div class="row" id="utenti">
							<div class="table-responsive">
								<div class="dt-example dt-example-bootstrap">
									<div class="container-fluid">
										<section>
											<table  id="tabellautenti" class="table table-striped table-bordered" cellspacing="0" width="100%">
												<thead>
													<tr>
														<th>Username</th>
														<th>Password</th>
														<th>Tipo</th>
														<th>Homepage</th>
														<th>Cartella</th>
														<th>Modifica</th>
														<th>Elimina</th>
													</tr>
												</thead>
												<tbody>
													<?php
													/** estraggo tutti gli utenti dalla tabella */
													$query = "SELECT * FROM utenti";
													$result = $mysqli->query($query);

													/** ciclo per mettere in tabella tutti gli utenti */
													while($row = $result->fetch_assoc()) {
														$username=$row['ut_nome'];
														$password=$row['ut_password'];
														$tipo=$row['ut_tipo'];
														$homepage=$row['ut_homepage'];
														$cartella=$row['ut_cartella'];

														$mod="edit-".$username;
														$del="delete-".$username;
														?>

														<tr>
															<td style="text-align:center"><?php echo $username; ?></td>
															<td style="text-align:center">*****************</td>
															<td style="text-align:center"><?php echo $tipo; ?></td>
															<td style="text-align:center"><?php echo $homepage; ?></td>
															<td style="text-align:center"><?php echo $cartella; ?></td>
															<td style="text-align:center"><a href="modifica_utenti.php?utente=<?php echo $mod; ?>"><img src="img/edit.png"></a></td>
															<td style="text-align:center"><a href="utenti.php?utente=<?php echo $del; ?>"><img src="img/cancel.png"></a></td>
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
						</div> <!-- tabella utenti -->
					<!-- script per stile e funzioni tabella -->
					<!-- non spostare da questa posizione -->
					<script type="text/javascript" language="javascript" class="init">
						$(document).ready(function() {
							$('#tabellautenti').DataTable( {
								"scrollY":        "600px",
								"scrollCollapse": true,
								"paging":         false,
								"order": [[ 0, "asc" ]]
							});
						});
					</script> <!-- script per stile e funzioni tabella -->
					<a href="modifica_utenti.php?utente=edit-nuovo" class="btn btn-danger btn-large btn-block ">NUOVO UTENTE</a>
				</div>
			</div>
		</div>
	</body>
</html>
