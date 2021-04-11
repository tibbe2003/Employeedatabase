<?php

include_once("dbh.inc.php");
include_once("datavalidation.inc.php");
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }
$username = $_SESSION['useremail'];
$role = $_SESSION["role"];
$userid = $_SESSION['userid'];


//getting variables
$title = clean_input($_POST['title']);
$date = clean_input($_POST['date']);
if($role == "manager" || $role == "admin") {
    $employeeid = $_POST['employee'];
} else {$employeeid = $userid;}

$qry = pg_query_params($conn, "INSERT INTO calender (userid, title, date) VALUES ($1, $2, $3)",array($employeeid, $title, $date));

if($qry) {
    header("location:/calender.php");
}