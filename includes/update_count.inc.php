<?php 
//connecting with dbh
include_once('dbh.inc.php');
//checking if user is logged in
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }

//putting passed variable in to new variable
$to = $_POST['to_user_id'];

//update every 1 to 0 if chat is opend
$query = pg_query_params($conn, "UPDATE chat_message SET status = 0 WHERE to_user_id = $1 AND from_user_id = $2 AND status = 1",array($_SESSION['userid'],$to));