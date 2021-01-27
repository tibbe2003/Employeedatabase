<?php 


$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
    or die('Could not connect: ' . pg_last_error());

if(isset($_POST['customerid'])) {$id = $_POST['customerid'];}

if(isset($_POST['assign'])) {
	$employee = $_POST['employee'];

	$qry = pg_query_params($dbconn,'INSERT INTO deployment(customerid, employeeid) VALUES ($1,$2)',array(intval($id),intval($employee))) or die;

	header("location:https://thijmenbrand.nl/customerview.php?customerid=$id");
}
else {
	header("location:https://thijmenbrand.nl/customerview.php?customerid=$id");	
}
?>