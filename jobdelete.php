<?php
	//refering back to previus page
	$referer = $_SERVER['HTTP_REFERER'];
	//connecting to database
	$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 		or die('Could not connect: ' . pg_last_error());
	//get id from clicked record
	$id = $_GET['jobid'];
	//query to delete record
	$del = pg_query_params($dbconn, 'DELETE FROM jobtitles WHERE jobid = $1',array($id)) or die('Query failed: ' . pg_last_error());
	//if query is true
	if($del)
		{
    		pg_close($dbconn); // Close connection
    		header("Location: $referer?delete=1"); // redirects to all records page
    		exit;	
		}
?>