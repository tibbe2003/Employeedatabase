<?php 

include_once("dbh.inc.php");

function emptyinputsignup($name,$email,$pwd,$pwdrepeat) {
	$result;
	if (empty($name) || empty($email) || empty($pwd) || empty($pwdrepeat)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function invalidemail($email) {
	$result;
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function pwdmatch($pwd, $pwdrepeat) {
	$result;
	if ($pwd !== $pwdrepeat) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function emailexists($conn, $email) {
	$qry = pg_query_params($conn, "SELECT * FROM users WHERE usersemail = $1",array($email));

	if ($row = pg_fetch_assoc($qry)) {
		return $row;
	}
	else {
		$result = false;
		return $result;
	}

	pg_close($qry);
}

function createuser($conn,$name,$email,$pwd) {
	$hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);

	$qry = pg_query_params($conn, "INSERT INTO users (usersname, usersemail, userspwd) VALUES ($1, $2, $3)",array($name,$email,$hashedpwd));
	pg_close($qry);
	header("location:../login.php");
	exit();

}

function emptyinputlogin($email,$pwd) {
	$result;
	if (empty($email) || empty($pwd)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function loginuser($conn,$email,$pwd) {
	$emailexists = emailexists($conn, $email);

	if ($emailexists === false) {
		header("location:/login.php?error=wronglogin");
		exit();
	}

	$pwdhashed = $emailexists["userspwd"];
	$checkpwd = password_verify($pwd, $pwdhashed);

	if ($checkpwd === false) {
		header("location:/login.php?error=wronglogin");
		exit();
	}
	else if ($checkpwd === true) {
		session_start();
		$_SESSION["useremail"] = $emailexists["usersemail"];
		header("location:../home.php");
		exit();
	}
}