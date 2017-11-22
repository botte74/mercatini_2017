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
				if ((basename($_SERVER["PHP_SELF"]) != "crea_ordine.php")) {
					/** Nuovo ordine se admin, bar, bibite o cassa */
					if ($_SESSION['tipo'] == 'admin' || $_SESSION['tipo'] == 'bar' || $_SESSION['tipo'] == 'bibite' || $_SESSION['tipo'] == 'cassa') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="crea_ordine.php">
								<span class="glyphicon glyphicon-cutlery" aria-hidden="true"></span>
								Nuovo ordine
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "elenco_ordini.php")) {
					/** Elenco ordini solo se admin, bar, bibite, cassa o distribuzione */
					if ($_SESSION['tipo'] == 'admin' || $_SESSION['tipo'] == 'bar' || $_SESSION['tipo'] == 'bibite' || $_SESSION['tipo'] == 'cassa' || $_SESSION['tipo'] == 'distribuzione') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="elenco_ordini.php">
								<span class="glyphicon glyphicon-duplicate" aria-hidden="true"></span>
								Elenco ordini
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "abbina.php")) {
					/** Visualizza abbinamenti tavoli solo se admin, abbina o distribuzione */
					if ($_SESSION['tipo'] == 'admin' || $_SESSION['tipo'] == 'abbina' || $_SESSION['tipo'] == 'distribuzione') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="abbina.php">
								<span class="glyphicon glyphicon-phone" aria-hidden="true"></span>
								Abbinamenti
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "distribuzione.php")) {
					/** Visualizza distribuzione solo se admin o distribuzione */
					if ($_SESSION['tipo'] == 'admin' || $_SESSION['tipo'] == 'distribuzione') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="distribuzione.php">
								<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>
								Distribuzione
							</a>
						</li>
						<?php
					}
				}

				/** Se sono sulla stessa pagina non vedo il pulsante */
				if ((basename($_SERVER["PHP_SELF"]) != "griglie.php")) {
					/** Visualizza grigle solo se admin, distribuzione o griglie */
					if ($_SESSION['tipo'] == 'admin' || $_SESSION['tipo'] == 'distribuzione' || $_SESSION['tipo'] == 'griglie') {
						?>
						<li>
							<a class="btn btn-success btn-lg" href="griglie.php">
								<span class="glyphicon glyphicon-calendar" aria-hidden="true"></span>
								Griglie
							</a>
						</li>
						<?php
					}
				}


				/** Pulsante Home solo se su pagina diversa da index.php */
				if ((basename($_SERVER["PHP_SELF"]) != "index.php")) {
					?>
					<li>
						<a class="btn btn-success btn-lg" href = "<?php echo $_SESSION['homepage']; ?>" >
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
