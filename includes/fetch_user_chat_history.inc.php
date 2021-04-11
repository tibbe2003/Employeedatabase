<?php
//This file will only get the data from the POST and pass it thrue to functions.php (becouse you cant deferenciate between clicked buttons in one file)
//including database handler and functions.php to perform function
include_once('dbh.inc.php');
include_once('functions.inc.php');
//starting session to check if users is logged in
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }

 //echo will print functions output so we can use it in chat.php
echo fetch_user_chat_history($_SESSION['userid'], $_POST['to_user_id'], $conn);