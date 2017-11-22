<!-- Menu di navigazione -->
<nav class="navbar navbar-default navbar-fixed-top">

	<!-- Pulsante di navigazione -->
	<div class="navbar-header">
		<!-- Pulsante nascosto -->
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<!-- Pulsante nascosto -->
	</div>
	<!-- Pulsante di navigazione -->

	<!-- Barra dei pulsanti -->
	<div class="collapse navbar-collapse">
		<ul class="nav navbar-nav">
			<?php
			/** se è settata la sessione vedi i pulsanti altrimenti salti */
			if (isset($_SESSION['user'])) {
				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "amministrazione.php")) {
					/** Pagina di amministrazione solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="amministrazione.php">
								<span class="glyphicon glyphicon-user" aria-hidden="true"></span>
								Amministrazione
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "utenti.php")) {
					/** Visualizza pagina utenti solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="utenti.php">
								<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
								Utenti
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "serate.php")) {
					/** Visualizza pagina serate solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="serate.php">
								<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
								Serate
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "rendiconto.php")) {
					/** Visualizza riepiloghi solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="rendiconto.php">
								<span class="glyphicon glyphicon-piggy-bank" aria-hidden="true"></span>
								Riepilogo
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "gestione_prodotti.php")) {
					/** Visualizza gestione prodotti solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="gestione_prodotti.php">
								<span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
								Attivazioni
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "giacenze.php")) {
					/** Visualizza giacenze solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="giacenze.php">
								<span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>
								Giacenze
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "stampa_server.php")) {
					/** Pagina per ciclo stampa solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="stampa_distribuzione_sagra.php" target="_blank">
								<span class="glyphicon glyphicon-print" aria-hidden="true"></span>
								Stampe
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "tempi.php")) {
					/** Visualizza gestione tempi solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="tempi.php">
								<span class="glyphicon glyphicon-time" aria-hidden="true"></span>
								Tempi
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "gruppi.php")) {
					/** Visualizza gestione tempi solo se admin */
					if ($_SESSION['tipo'] == 'admin') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="gruppi.php">
								<span class="glyphicon glyphicon-folder-open" aria-hidden="true"></span>
								Gruppi
							</a>
						</li>
						<?php
					}
				}

				/** Pulsante Home solo se su pagina diversa da index.php */
				if ((basename($_SERVER["PHP_SELF"]) != "index.php")) {
					?>
					<li>
						<a class="btn btn-success btn-lg" href = "index.php" >
							<span class="glyphicon glyphicon-home" aria-hidden = "true" ></span >
							Home
						</a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</div>
	<!-- Barra dei pulsanti -->
</nav>
<!-- Menu di navigazione -->
