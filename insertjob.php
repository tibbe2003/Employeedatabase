<?php 
	//clean input data function
	require_once('datavalidation.php');
	//empting variables
	$job = $joberr = "";

	//connect to database
	$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
    	or die('Could not connect: ' . pg_last_error());

    //if form is submited
    if (isset($_POST['submit'])) {
    	$job = clean_input($_POST['jobtitle']);
    }

    $query = pg_query_params($dbconn, "INSERT INTO Jobtitles(Jobtitles) VALUES ($1)",array($job));

    header('location:units.php');
?>