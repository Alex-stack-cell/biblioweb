<?php
//Sécurisation d'accès
if(!isset($_SESSION['login'])) {
	//redirection
	header('Location: index.php');
	header('Status: 302');
	exit;
}
?>