<?php
require_once('dbh.inc.php');
require_once('../datavalidation.php');
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }

if (isset($_POST['submit'])) {
$companyname = clean_input($_POST['companyname']);
$street = clean_input($_POST['street']);
$postcode = clean_input($_POST['postcode']);
$city = clean_input($_POST['city']);

$qry = pg_query_params($conn,"UPDATE companyinfo SET companyname = $1, street = $2, postalcode = $3, city = $4",array($companyname,$street,$postcode,$city));

if ($qry) {
    header("location: /settings.php");
}
} else {die;}

