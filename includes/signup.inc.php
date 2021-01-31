<?php 

if(isset($_POST['submit'])) {

	$name = $_POST['name'];
	$email = $_POST['email'];
	$pwd = $_POST['pwd'];
	$pwdrepeat = $_POST['pwdrepeat'];

	require_once 'dbh.inc.php';
	require_once 'functions.inc.php';

	if (emptyinputsignup($name,$email,$pwd,$pwdrepeat) !== false) {
		header("location:/signup.php?error=emptyinput");
		exit();
	}
	if (invalidemail($email) !== false) {
		header("location:/signup.php?error=invalidemail");
		exit();
	}
	if (pwdmatch($pwd,$pwdrepeat) !== false) {
		header("location:/signup.php?error=passwordsdontmatch");
		exit();
	}
	if (emailexists($conn, $email) !== false) {
		header("location:/signup.php?error=emailtaken");
		exit();
	}

	createuser($conn, $name,$email,$pwd);

}
else {
	header("location:signup.php");
}