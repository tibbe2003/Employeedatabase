<?php
	session_start();
	if(empty($_SESSION['useremail'])) {
		header("Location: login.php");
		die("Redirecting to login.php");
	 }
	//clean input data function
	require_once('includes/datavalidation.inc.php');
	include_once('dbh.inc.php');
	//empting variables
	$unit = $uniterr = "";

    //if form is submited
    if (isset($_POST['submit'])) {
    	$unit = clean_input($_POST['unit']);
    }

    $query = pg_query_params($conn, "INSERT INTO BusinessUnits(BusinessUnit) VALUES ($1)",array($unit));

    header('location:/units.php');
?>