<?php
	//refering back to previus page
	$referer = $_SERVER['HTTP_REFERER'];
	//connecting to database
	$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 		or die('Could not connect: ' . pg_last_error());
	//get id from clicked record
	$id = $_GET['unitid'];

	//query to delete record
	$del = pg_query_params($dbconn, 'DELETE FROM businessunits WHERE unitid = $1',array($id)) or die('There are employees assigned to this unit. You cant delete it');
	//if query is true
	if($del)
		{
    		pg_close($dbconn); // Close connection
    		header("Location: $referer?delete=1"); // redirects to all records page
    		exit;	
		}
	else {
		pg_close($dbconn);
		header("Location: referer?delete=failed");
		exit;
	}
?>