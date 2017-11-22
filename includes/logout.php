<?php
	include_once("config.php");
		if (isset($_POST["logout"])) {
			if($_POST["logout"]==true) {
				$_SESSION['user'] = NULL;
				$_SESSION['tipo'] = NULL;
				session_unset();
				session_destroy();
				header("Location: ../index.php");
			}
		}
