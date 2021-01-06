<?php 
$referer = $_SERVER['HTTP_REFERER'];

$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 or die('Could not connect: ' . pg_last_error());
 
$id = $_GET['employeeid'];

$del = pg_query_params($dbconn, 'DELETE FROM employees WHERE employeeid = $1',array($id)) or die('Query failed: ' . pg_last_error());

if($del)
{
    pg_close($dbconn); // Close connection
    header("Location: $referer?delete=1"); // redirects to all records page
    exit;	
}
	?>