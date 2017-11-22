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
		<title>Modifica Gruppi</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link rel="icon" href="./img/icon.ico" />

		<!-- jQuery e plugin JavaScript  -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
  <body>

	<?php
	/** includi la barra dei pulsanti */
	include("includes/testata_admin.php");

	/** aggiornamento valori tabella gruppi */
	if(isset($_POST['update'])) {
		$update_codice=$_POST['update'];
		$update_descrizione=$_POST['descrizione'];
		$update_attivo=$_POST['attivo'];
		$update_ordinamento=$_POST['ordinamento'];
		$update_stile=$_POST['stile'];
		$update_stato=$_POST['stato'];
		$update_tipo_stampa=$_POST['tipostampa'];
		$update_cartella=$_POST['cartella'];
		$update_barcode=$_POST['barcode'];
		$update_ritardo=$_POST['ritardo_stampa'];
		$update_griglia=$_POST['griglia'];

		$update="UPDATE gruppi SET
    					gr_codice='$update_codice',
    					gr_descrizione='$update_descrizione',
    					gr_attivo='$update_attivo',
    					gr_ordinamento='$update_ordinamento',
    					gr_stile='$update_stile',
    					gr_stato='$update_stato',
    					gr_tipostampa='$update_tipo_stampa',
    					gr_cartella='$update_cartella',
    					gr_barcode='$update_barcode',
    					gr_ritardo_stampa='$update_ritardo',
    					gr_griglia='$update_griglia'
    					WHERE gr_codice='$update_codice'";
		$query = $mysqli->query($update);

		/** rimando alla pagina principale */
		header("Location: gruppi.php");
	}
	unset($_POST['update']);

	/** inserimento nuovo gruppo */
	if(isset($_POST['insert'])) {

		$insert_codice=$_POST['insert'];
		$insert_descrizione=$_POST['descrizione'];
		$insert_attivo=$_POST['attivo'];
		$insert_ordinamento=$_POST['ordinamento'];
		$insert_stile=$_POST['stile'];
		$insert_stato=$_POST['stato'];
		$insert_tipo_stampa=$_POST['tipostampa'];
		$insert_cartella=$_POST['cartella'];
		$insert_barcode=$_POST['barcode'];
		$insert_ritardo=$_POST['ritardo_stampa'];
		$insert_griglia=$_POST['griglia'];

		$insert="INSERT INTO gruppi (
    					gr_codice,
    					gr_descrizione,
    					gr_attivo,
    					gr_ordinamento,
    					gr_stile,
    					gr_stato,
    					gr_tipostampa,
    					gr_cartella,
    					gr_barcode,
    					gr_ritardo_stampa,
    					gr_griglia
							) VALUES (
							'$insert_codice',
							'$insert_descrizione',
							'$insert_attivo',
							'$insert_ordinamento',
							'$insert_stile',
							'$insert_stato',
							'$insert_tipo_stampa',
							'$insert_cartella',
							'$insert_barcode',
							'$insert_ritardo',
							'$insert_griglia')";
		$query = $mysqli->query($insert);

		/** rimando alla pagina principale */
		header("Location: gruppi.php");
	}
	unset($_POST['insert']);
	?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-12 ">
          <!-- tabella gruppi -->
          <div class="row" id="elenco_gruppi">
              <div class="table-responsive">
                <table class="table table-condensed table-striped" id="tabella_elenco_gruppi">
                  <thead>
                    <tr>
											<th>
												<p style="text-align: center">Codice</p>
											</th>
											<th>
												<p style="text-align: center">Descrizione</p>
											</th>
											<th>
												<p style="text-align: center">Attivo</p>
											</th>
											<th>
												<p style="text-align: center">Ordinamento</p>
											</th>
											<th>
												<p style="text-align: center">Stile</p>
											</th>
											<th>
												<p style="text-align: center">Stato</p>
											</th>
											<th>
												<p style="text-align: center">Tipo stampa</p>
											</th>
											<th><p style="text-align: center">Cartella</p>
											</th>
											<th>
												<p style="text-align: center">Barcode</p>
											</th>
											<th>
												<p style="text-align: center">Ritardo</p>
											</th>
											<th>
												<p style="text-align: center">Griglia</p>
											</th>
											<th>
												<p style="text-align: center">Aggiorna</p>
											</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    /** cerco i dati del gruppo selezionato */
                    if(isset($_GET['gruppo'])) {

                      $operation=explode("-",$_GET['gruppo']);
                  		$gruppo=$operation[1];
                      $query = "SELECT * FROM gruppi WHERE gr_codice='$gruppo'";
                      $result = $mysqli->query($query);
                      $row = $result->fetch_assoc();

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
                    ?>
										<form action="modifica_gruppi.php" method="POST">
											<tr>
											<?php
											if($gruppo=="nuovo") {
											?>
												<td>
													<input title="codice" class="form-control" type="text" name="insert" value="">
												</td>
												<td>
													<input title="descrizione" class="form-control" type="text" name="descrizione" value="">
												</td>
												<td>
													<input title="attivo" class="form-control" type="text" name="attivo" value="">
												</td>
												<td>
													<input title="ordinamento" class="form-control" type="text" name="ordinamento" value="">
												</td>
												<td>
													<input title="stile" class="form-control" type="text" name="stile" value="">
												</td>
												<td>
													<input title="stato" class="form-control" type="text" name="stato" value="">
												</td>
												<td>
													<input title="tipostampa" class="form-control" type="text" name="tipostampa" value="">
												</td>
												<td>
													<input title="cartella" class="form-control" type="text" name="cartella" value="">
												</td>
												<td>
													<input title="barcode" class="form-control" type="text" name="barcode" value="">
												</td>
												<td>
													<input title="ritardo_stampa" class="form-control" type="text" name="ritardo_stampa" value="">
												</td>
												<td>
													<input title="griglia" class="form-control" type="text" name="griglia" value="">
												</td>
												<td>
													<input class="btn btn-danger btn-large btn-block" type="submit" value="Crea">
												</td>
											<?php
											} else {
											?>
												<td>
													<input title="codice" class="form-control" type="text" name="update" readonly value="<?php echo $codice; ?>">
												</td>
												<td>
													<input title="descrizione" class="form-control" type="text" name="descrizione" value="<?php echo $descrizione; ?>">
												</td>
												<td>
													<input title="attivo" class="form-control" type="text" name="attivo" value="<?php echo $attivo; ?>">
												</td>
												<td>
													<input title="ordinamento" class="form-control" type="text" name="ordinamento" value="<?php echo $ordinamento; ?>">
												</td>
												<td>
													<input title="stile" class="form-control" type="text" name="stile" value="<?php echo $stile; ?>">
												</td>
												<td>
													<input title="stato" class="form-control" type="text" name="stato" value="<?php echo $stato; ?>">
												</td>
												<td>
													<input title="tipostampa" class="form-control" type="text" name="tipostampa" value="<?php echo $tipo_stampa; ?>">
												</td>
												<td>
													<input title="cartella" class="form-control" type="text" name="cartella" value="<?php echo $cartella; ?>">
												</td>
												<td>
													<input title="barcode" class="form-control" type="text" name="barcode" value="<?php echo $barcode; ?>">
												</td>
												<td>
													<input title="ritardo_stampa" class="form-control" type="text" name="ritardo_stampa" value="<?php echo $ritardo; ?>">
												</td>
												<td>
													<input title="griglia" class="form-control" type="text" name="griglia" value="<?php echo $griglia; ?>">
												</td>
												<td>
													<input class="btn btn-danger btn-large btn-block" type="submit" value="Aggiorna">
												</td>
											<?php
											}
										?>
											</tr>
                    </form>
                    <?php
										}
                    ?>
                  </tbody>
                </table>
              </div>
          </div> <!-- tabella gruppi -->
        </div>
      </div>
    </div>
  </body>
</html>
