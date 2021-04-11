<?php
	//connecting to database
	include_once('dbh.inc.php');
	session_start();
	if(empty($_SESSION['useremail'])) {
		header("Location: login.php");
		die("Redirecting to login.php");
	 }
	//get id from clicked record

	$id = $_GET['employeeid'];
	//query to delete record
	$del = pg_query_params($conn, 'DELETE FROM employees WHERE employeeid = $1',array($id)) or die('Query failed: ' . pg_last_error());
	$deluser = pg_query_params($conn,'DELETE FROM users WHERE usersid = $1',array($id));
	//if query is true
	if($del && $deluser)
	{
    		pg_close($conn); // Close connection
    		header("Location: /employees.php?delete=1"); // redirects to all records page
    		exit;	
	}
?>