<?php
	session_start();
	$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 		or die('Could not connect: ' . pg_last_error());
	//get id from clicked record
 	$customerid = $_POST['customerid'];
	$id = $_GET['employeeid'];
	//query to delete record
	$del = pg_query_params($dbconn, 'DELETE FROM deployment WHERE employeeid = $1',array($id)) or die('Query failed: ' . pg_last_error());
	//if query is true
	if($del)
		{
    		pg_close($dbconn); // Close connection
    		header("location:customerview.php?customerid=".$_SESSION["customerid"]);
			//header("location:https://thijmenbrand.nl/customerview.php?customerid=$customerid"); // redirects to all records page
    		exit;	
		}

?>