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
		<title>Serate</title>

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
		/** recupero e aggiorno dati dalla tabella serate */
			if (isset($_POST['update'])){
				$se_numero = $_POST['se_numero'];
			
				// Azzero le eventuali serate attive
				$UpdateQuery = "UPDATE serate SET se_attiva = ' ' WHERE se_attiva = 'S'";
				if (!$mysqli->query($UpdateQuery)) {
					echo "</br>";
					echo mysqli_error($mysqli);
				}

				$UpdateQuery = sprintf("UPDATE serate SET se_attiva = 'S' WHERE se_numero = '%s'", $se_numero);

				/** controllo errore */
				if (!$mysqli->query($UpdateQuery)) {
				echo "</br>";
				echo mysqli_error($mysqli);
			}
		  }
		?>

      <!-- intestazione tabella serate -->
      <div class="row">
        <div class="col-lg-10 col-lg-offset-1">
          <div class="container-fluid">
            <div class="row">
							<div class="h2">
              <p class="text-center">Scelta serate</p>
            </div>
          </div>
					<div class="row">
						<div class="h3">
							<p class="text-center">solo una deve essere attiva!!!</p>
						</div>
					</div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-10 col-lg-offset-2">
          <div class="container-fluid">
            <div class="row">
              <div class="col-lg-10">
                <div class="col-lg-6">
                  <div class="h3">
                    <p class="text-center">Serata</p>
                  </div>
                </div>
                <div class="col-lg-3">
                  <div class="h3">
                    <p class="text-center">Stato</p>
                  </div>
                </div>
              <div class="col-lg-3">
                <div class="h3">
                  <p class="text-center">Attiva</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> <!-- intestazione tabella serate -->

      <?php
      /** codice per recuperare tutte le serate dal database */
      $sql = "SELECT * FROM serate ORDER BY se_numero ASC";
      $myData = $mysqli->query($sql);

      /** generazione tabella serate */
      while ($record = $myData->fetch_array(MYSQLI_ASSOC)) {
      ?>
        <div class="row">
          <div class="col-lg-10 col-lg-offset-2">
            <form action="serate.php" method="post">
              <div class="container-fluid">
                <div class="row">
                  <div class="col-lg-10">
                    <div class="row">
                      <div class="col-lg-6">
                        <?php
                          echo sprintf("<input type=text class=form-control name=se_descrizione value=\"%s\" readonly>", $record['se_descrizione']);
                        ?>
                      </div>
                      <div class="col-lg-3">
                        <?php
                        echo sprintf("<input type=text class=\"form-control text-center\" name=se_attiva value=\"%s\" readonly>", $record['se_attiva']);
                        ?>
                      </div>
					  <div class="col-lg-3">
						<?php
						  echo sprintf("<input type=hidden name=se_numero value=\"%s\">", $record['se_numero']);
						?>
						<button type=submit class="btn btn-success btn-block" name=update value=Aggiorna>Attiva</button>
					  </div>
                    </div>
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
		</div>
	</body>
</html>