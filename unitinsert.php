<?php

	//clean input data function
	require_once('datavalidation.php');
	//empting variables
	$unit = $uniterr = "";

	//connect to database
	$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
    	or die('Could not connect: ' . pg_last_error());

    //if form is submited
    if (isset($_POST['submit'])) {
    	$unit = clean_input($_POST['unit']);
    }

    $query = pg_query_params($dbconn, "INSERT INTO BusinessUnits(BusinessUnit) VALUES ($1)",array($unit));

    header('location:units.php');
?>