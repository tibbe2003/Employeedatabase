<?php
//including dbh
include_once('dbh.inc.php');
//checking is user is logged in
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }

//if user loads any page lastactivity will be set to 0x
$update = pg_query_params($conn, "UPDATE users SET lastactivity = now() WHERE usersid = $1",array($_SESSION['userid']));