<?php
	include_once('dbh.inc.php');
	//clean input data function
	require_once('includes/datavalidation.inc.php');
	//empting variables
	$job = $joberr = "";


    //if form is submited
    if (isset($_POST['submit'])) {
    	$job = clean_input($_POST['jobtitle']);
    }

    $query = pg_query_params($conn, "INSERT INTO Jobtitles(Jobtitles) VALUES ($1)",array($job));

    header('location:/units.php');
?>