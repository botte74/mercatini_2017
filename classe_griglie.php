<?php
	include_once("includes/config.php");

	class Griglie {
		
		//costruttore
        public function __construct() {}
        
		public static function AggiungiArticoloInGriglia($articolo, $quantita) {
			global $mysqli;
			
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
			
			
			$query = sprintf(
				"SELECT * FROM articoli join distinta ON ar_codice = di_codicearticolo JOIN prodotti on di_codiceprodotto = pr_codice
				WHERE ar_codice = '%s' and pr_autogriglia = 'S'"
				, $articolo);
			$result = $mysqli->query($query, MYSQLI_STORE_RESULT);
			while($row = $result->fetch_assoc()) {
				$prodotto = $row['pr_codice'];
				$quant = $quantita * $row['di_coefficente'];
				$query=sprintf("INSERT INTO griglie SET gri_serata = %s, gri_prodotto = '%s', gri_quantita = %d, gri_richiesta = false ON DUPLICATE KEY UPDATE gri_quantita = gri_quantita + %d", $serata, $prodotto, $quant, $quant);
				if (!$mysqli->query($query)) {
					echo $mysqli->error;
				}
			}

			$result->close();
		}
	}