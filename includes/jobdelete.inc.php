<?php
	include_once('dbh.inc.php');
	session_start();
	if(empty($_SESSION['useremail'])) {
		header("Location: login.php");
		die("Redirecting to login.php");
	 }

	//refering back to previus page
	$referer = $_SERVER['HTTP_REFERER'];
	//get id from clicked record
	$id = $_GET['jobid'];
	//query to delete record
	$del = pg_query_params($conn, 'DELETE FROM jobtitles WHERE jobid = $1',array($id)) or die('Query failed: ' . pg_last_error());
	//if query is true
	if($del)
		{
    		pg_close($conn); // Close connection
    		header("Location: $referer?delete=1"); // redirects to all records page
    		exit;	
		}
?>