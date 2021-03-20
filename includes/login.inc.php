<?php

if (isset($_POST['submit'])) {
	
	$email = strtolower($_POST['email']);
	$pwd = $_POST['pwd'];

	require_once('dbh.inc.php');
	require_once('functions.inc.php');

	if (emptyinputlogin($email,$pwd) !== false) {
		header("location:/login.php?error=emptyinput");
		exit();
	}

	loginuser($conn, $email, $pwd);
}
	else {
		header("location:/login.php");
		exit();
	}