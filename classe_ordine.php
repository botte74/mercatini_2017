<?php
	include_once("includes/config.php");

	class Ordine {
		private $numero = "";
		
		//costruttore
        public function __construct($numero)
        {
            //inizializzazione della proprietà $name    
            $this->numero = $numero;
        }   
		
		public function addRiga($codice) {
			global $mysqli;
			$ordine =$this->numero;
			$esito = false;
			$query = sprintf("SELECT * FROM articoli WHERE ar_codice = '%s'", $codice);
			$result = $mysqli->query($query, MYSQLI_USE_RESULT);
			$row = $result->fetch_assoc();
			$descrizione = $row['ar_descrizione'];
			$prezzo = $row['ar_prezzo'];
			$result->close();
			// verifico disponibilità prima di aggiungere la riga
			$ok = true;
			$mysqli->query("LOCK TABLES magazzino SELECT UPDATE");
			$query = sprintf("SELECT * FROM distinta JOIN magazzino ON di_codiceprodotto = ma_codiceprodotto WHERE di_codicearticolo = '%s' ", $codice);
			$result = $mysqli->query($query, MYSQLI_USE_RESULT);
			while($row = $result->fetch_assoc()) {
				if ($row['di_coefficente'] > $row['ma_giacenza']) {
					//echo "FINITO " . $row['ma_codiceprodotto'] . "! <br />";
					$ok = false;
				}
			}

			$result->close();

			// se ok inserisco riga
			if ($ok) {
				// eseguo insert
				$mysqli->query("LOCK TABLES ordinirighe SELECT WRITE");
				$query = sprintf("SELECT MAX(ri_riga) AS MaxRiga FROM ordinirighe WHERE ri_ordine = '%s' ", $ordine);
				$result = $mysqli->query($query, MYSQLI_USE_RESULT);
				$row = $result->fetch_assoc();
				$riga = $row['MaxRiga'];
				$result->close();
				$riga += 1;
				$quantita = 1;
				$query = sprintf("INSERT INTO ordinirighe (ri_ordine, ri_riga, ri_codice, ri_descrizione, ri_quantita, ri_prezzo) VALUES ('%s', '%s', '%s', '%s', %s, %s)", $ordine, $riga, $codice, $descrizione, $quantita, $prezzo);

				// eseguo inserimento nuova riga
				if ($mysqli->query($query)) {
					//echo "$codice OK <br /> |" . $riga;
					$esito = true;
				}
				else {
					//echo("<br />SQL eseguito: ". $query . " Errore rilevato: " . mysqli_error($mysqli) . "|0");
				}
			}

			// rimuovo lock table
			$mysqli->query("UNLOCK TABLES");
			return $esito;
		}
	}