<?php
	include_once('dbh.inc.php');
	//refering back to previus page
	$referer = $_SERVER['HTTP_REFERER'];
	//connecting to database
	//get id from clicked record
	$id = $_GET['customerid'];
	//query to delete record
	$del = pg_query_params($conn, 'DELETE FROM customers WHERE customerid = $1',array($id)) or die('Query failed: ' . pg_last_error());
	//if query is true
	if($del)
		{
    		pg_close($conn); // Close connection
    		header("Location: $referer?delete=1"); // redirects to all records page
    		exit;	
		}
?>