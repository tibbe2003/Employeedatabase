<?php 
	session_start();
	$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 		or die('Could not connect: ' . pg_last_error());
	//get id from clicked record
 	$customerid = $_GET['customerid'];
	$id = $_POST['employeeid'];
	//query to delete record
	$del = pg_query_params($dbconn, 'DELETE FROM deployment WHERE customerid = $1',array($customerid)) or die('Query failed: ' . pg_last_error());
	//if query is true
	if($del)
		{
    		pg_close($dbconn); // Close connection
    		    		header("location:edit.php?employeeid=".$_SESSION["employeeid"]);
			//header("location:https://thijmenbrand.nl/customerview.php?customerid=$customerid"); // redirects to all records page
    		exit;	
		}

?>