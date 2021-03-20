<?php
	include_once('dbh.inc.php');
	session_start();
	//get id from clicked record
 	$customerid = $_POST['customerid'];
	$id = $_GET['employeeid'];
	//query to delete record
	$del = pg_query_params($conn, 'DELETE FROM deployment WHERE employeeid = $1',array($id)) or die('Query failed: ' . pg_last_error());
	//if query is true
	if($del)
		{
    		pg_close($conn); // Close connection
    		header("location:/customerview.php?customerid=".$_SESSION["customerid"]);
			//header("location:https://thijmenbrand.nl/customerview.php?customerid=$customerid"); // redirects to all records page
    		exit;	
		}

?>