<?php
// avvio sessione
ini_set('session.gc_maxlifetime', 60*60*5); // 60*60*5 = 5 ore
session_start();

//settare php per lavorare con dati Unicode
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');
mb_http_input('UTF-8');
mb_language('uni');
mb_regex_encoding('UTF-8');
ob_start('mb_output_handler');

// apro connessione al DB
$db_host = 'localhost';
$db_user = 'root';
$db_pass = 'admin';
$db_name = 'parrocchia_mercatini';
$mysqli = new mysqli($db_host, $db_user, $db_pass, $db_name);

//controllo errori connessione al db
if (mysqli_connect_errno()){
	echo "Errore di connessione al DataBase:" . mysqli_connect_error();
}

$mysqli->query("SET NAMES utf8");
$mysqli->query("SET CHARACTER SET utf8");

// Mettere a false per gestione stampe versione 2016
$stampe_new = true;

// Per aggiungere una riga di coperti sulle bibite agli ordini senza bibite ma con coperti
$coperti_automatico_attivo = true;
$coperti_automatico_gruppo = "Bibite";
$coperti_automatico_articolo = "Coperti";
