<?php
	//session_start();
	include_once("config.php");

	$username = $_POST["user"];
	$password = md5($_POST["password"]);
	$query = "SELECT * FROM utenti WHERE ut_nome = '$username' AND ut_password = '$password'";

	$result = $mysqli->query($query);

	if ($result->num_rows == 0)
		{
			header('Location: ../index.php');
		}

	else
		{
			$row = $result->fetch_assoc();

			//Informazioni sull'utente
			$homepage = $row['ut_homepage'];

			//Scrittura variabili di sessione
			$_SESSION['user'] = $row['ut_nome'];
			$_SESSION['tipo'] = $row['ut_tipo'];
			$_SESSION['homepage'] = $row['ut_homepage'];

			//Redirezione
			header('Location: ../' . $homepage);
		}
	$result->close();
