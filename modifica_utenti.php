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
		<title>Modifica Utenti</title>

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

	/** aggiornamento valori tabella utenti */
	if(isset($_POST['update'])) {
		$u=$_POST['update'];
		$p=$_POST['password'];
		$password=md5($p);
		$t=$_POST['tipo'];
		$h=$_POST['homepage'];
		$c=$_POST['cartella'];

		$update="UPDATE utenti SET
    					ut_nome='$u',
    					ut_password='$password',
    					ut_tipo='$t',
    					ut_homepage='$h',
    					ut_cartella='$c'
    					WHERE ut_nome='$u'";
		$query = $mysqli->query($update);

		/** rimando alla pagina principale */
		header("Location: utenti.php");
	}
	unset($_POST['update']);

	/** inserimento nuovo utente */
	if(isset($_POST['insert'])) {
		$u=$_POST['insert'];
		$p=$_POST['password'];
		$password=md5($p);
		$t=$_POST['tipo'];
		$h=$_POST['homepage'];
		$c=$_POST['cartella'];

		$insert="INSERT INTO utenti (
							ut_nome,
							ut_password,
							ut_tipo,
							ut_homepage,
							ut_cartella
							) VALUES (
							'$u',
							'$password',
							'$t',
							'$h',
							'$c')";
		$query = $mysqli->query($insert);

		/** rimando alla pagina principale */
		header("Location: utenti.php");
	}
	unset($_POST['insert']);
	?>

    <div class="container-fluid">
      <div class="row">
        <div class="col-lg-8 col-lg-offset-2">
          <!-- tabella utenti -->
          <div class="row" id="elenco_utenti">
              <div class="table-responsive">
                <table class="table table-condensed table-striped" id="tabella_elenco_utenti">
                  <thead>
                    <tr>
                      <th>
												<p style="text-align: center">Username</p>
											</th>
                      <th>
												<p style="text-align: center">Password</p>
											</th>
                      <th>
												<p style="text-align: center">Tipo</p>
											</th>
                      <th>
												<p style="text-align: center">Homepage</p>
											</th>
                      <th>
												<p style="text-align: center">Cartella</p>
											</th>
                      <th>
												<p style="text-align: center">Modifica</p>
											</th>
                    </tr>
                  </thead>
                  <tbody>

                    <?php
                    /** cerco i dati dell'utente selezionato */
                    if(isset($_GET['utente'])) {

                      $operation=explode("-",$_GET['utente']);
                  		$utente=$operation[1];
                      $query = "SELECT * FROM utenti WHERE ut_nome='$utente'";
                      $result = $mysqli->query($query);
                      $row = $result->fetch_assoc();

                      $username=$row['ut_nome'];
                      $password=$row['ut_password'];
                      $tipo=$row['ut_tipo'];
                      $homepage=$row['ut_homepage'];
                      $cartella=$row['ut_cartella'];
                    ?>
										<form action="modifica_utenti.php" method="POST">
                    	<tr>
											<?php
											if($utente=="nuovo") {
											?>
                      	<td>
													<input title="insert" class="form-control" type="text" name="insert" value="<?php echo $username; ?>">
												</td>
												<td>
													<input title="password" class="form-control" type="text" name="password" value="">
												</td>
											<?php
											}
											if($utente!=="nuovo") {
											?>
												<td>
													<input title="update" class="form-control" type="text" name="update" readonly value="<?php echo $username; ?>">
												</td>
												<td>
													<input title="password" class="form-control" type="text" name="password" value="**************">
												</td>
											<?php
											}
											?>
												<td>
													<input title="tipo" class="form-control" type="text" name="tipo" value="<?php echo $tipo; ?>">
												</td>
												<td>
													<input title="homapage" class="form-control" type="text" name="homepage" value="<?php echo $homepage; ?>">
												</td>
												<td>
													<input title="cartella" class="form-control" type="text" name="cartella" value="<?php echo $cartella; ?>">
												</td>
												<td>
													<input class="btn btn-danger btn-large btn-block" type="submit" value="Aggiorna">
												</td>
											</tr>
                    </form>
                    <?php
										}
                    ?>
                  </tbody>
                </table>
              </div>
          </div> <!-- tabella utenti -->
        </div>
      </div>
    </div>
  </body>
</html>
