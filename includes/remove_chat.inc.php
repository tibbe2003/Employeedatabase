<?php
//including dbh
include_once('dbh.inc.php');
//checking if user is logged in
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }

//if the delete is conformed we delete the row with id that we get from chat.php
if(isset($_POST["chat_message_id"]))
{
 $query = pg_query_params($conn, " UPDATE chat_message SET status = 2 WHERE chat_message_id = $1",array($_POST['chat_message_id']));
}