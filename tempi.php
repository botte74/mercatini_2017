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
		<title>Tempi</title>

		<!-- Fogli di stile -->
		<link href="css/bootstrap.min.css" rel="stylesheet" media="screen">
		<link href="css/style.css" rel="stylesheet" media="screen">
		<link href="css/style3.css" rel="stylesheet" media="screen">
		<link rel="icon" href="./img/icon.ico" />

		<!-- jQuery e plugin JavaScript  -->
		<script src="js/jquery.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</head>
  <body>
    <?php
    /** includi la barra dei pulsanti */
    include("includes/testata_admin.php");
    ?>

    <!-- contenitore principale pagina -->
    <div class="container-fluid">

      <?php
      /** recupero e aggiornamento dati dalla tabella tempi */
      if (isset($_POST['update'])){
        $time_tipo = $_POST['time_tipo'];
        $time_modifica = $_POST['time_modifica'];

        if ($time_modifica <> '') {
          $UpdateQuery = "UPDATE tempi SET numero = $time_modifica WHERE tipo = '$time_tipo'";

          /** controllo errore */
          if (!$mysqli->query($UpdateQuery)) {
            echo "</br>";
            echo mysqli_error($mysqli);
          }
        }
      }
      ?>

      <!-- intestazione tabella tempi -->
      <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
          <div class="container-fluid">
            <div class="h2">
              <p class="text-center">Definizione Tempi</p>
            </div>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-10">
                <div class="col-lg-6">
                  <div class="h3">
                    <p class="text-center">Descrizione</p>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="h3">
                    <p class="text-center">Valore (sec.)</p>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="h3">
                    <p class="text-center">Nuovo valore (sec.)</p>
                  </div>
                </div>
              </div>
              <div class="col-lg-2">
                <div class="h3">
                  <p class="text-center">Aggiorna</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- intestazione tabella tempi -->

      <?php
      /** codice per recuperare i campi dal database */
      $sql = "SELECT * FROM tempi ORDER BY codice ASC";
      $myData = $mysqli->query($sql);

      /** generazione tabella tempi */
      while ($record = $myData->fetch_array(MYSQLI_ASSOC)) {
      ?>
        <div class="row">
          <div class="col-lg-10 col-lg-offset-1">
            <form action="tempi.php" method="post">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-lg-10">
                    <div class="row">
                      <div class="col-lg-6">
                        <?php
                          echo sprintf("<input type=text class=form-control name=time_descrizione value=\"%s\" readonly>", $record['descrizione']);
													echo sprintf("<input type=hidden class=form-control name=time_tipo value=\"%s\">", $record['tipo']);
												?>
                      </div>
                      <div class="col-lg-3">
                        <?php
	                        echo sprintf("<input type=text class=\"form-control text-center\" name=time_valore value=\"%s\" readonly>", $record['numero']);
                        ?>
                      </div>
                      <div class="col-lg-3">
                        <?php
                          echo sprintf("<input type=text class=\"form-control text-center\" name=time_modifica value='' placeholder='nuovo valore'>");
                        ?>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <button type=submit class="btn btn-success btn-block" name=update value=Aggiorna>Aggiorna</button>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      <?php
      }
      $myData->close();
      ?>
		</div>
	</body>
</html>
