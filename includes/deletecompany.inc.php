<?php 
	include_once('dbh.inc.php');
	session_start();
	//get id from clicked record
 	$customerid = $_GET['customerid'];
	$id = $_POST['employeeid'];
	//query to delete record
	$del = pg_query_params($conn, 'DELETE FROM deployment WHERE customerid = $1',array($customerid)) or die('Query failed: ' . pg_last_error());
	//if query is true
	if($del)
		{
    		pg_close($conn); // Close connection
    		    		header("location:/edit.php?employeeid=".$_SESSION["employeeid"]);
			//header("location:https://thijmenbrand.nl/customerview.php?customerid=$customerid"); // redirects to all records page
    		exit;	
		}

?>