<?php 


$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
    or die('Could not connect: ' . pg_last_error());

if(isset($_POST['employeeid'])) {$id = $_POST['employeeid'];}

if(isset($_POST['assign'])) {
	$customer = $_POST['customerid'];

	$qry = pg_query_params($dbconn,'INSERT INTO deployment(customerid, employeeid) VALUES ($1,$2)',array(intval($customer),intval($id))) or die;

	header("location:https://thijmenbrand.nl/edit.php?employeeid=$id");
}
else {
	header("location:https://thijmenbrand.nl/edit.php?employeeid=$id");	
}
?>