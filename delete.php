<?php
	//connecting to database
	$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 		or die('Could not connect: ' . pg_last_error());
	//get id from clicked record
	$id = $_GET['employeeid'];
	//query to delete record
	$del = pg_query_params($dbconn, 'DELETE FROM employees WHERE employeeid = $1',array($id)) or die('Query failed: ' . pg_last_error());
	$deluser = pg_query_params($dbconn,'DELETE FROM users WHERE usersid = $1',array($id));
	//if query is true
	if($del && $deluser)
		{
    		pg_close($dbconn); // Close connection
    		header("Location: employees.php?delete=1"); // redirects to all records page
    		exit;	
		}
?>