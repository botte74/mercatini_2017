<?php
/** controllo se è settato l'utente */
if (!isset($_SESSION['user'])) {
	header("location: index.php");
	exit;
}