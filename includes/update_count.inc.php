<?php 

include_once('dbh.inc.php');
session_start();
$to = $_POST['to_user_id'];

$query = pg_query_params($conn, "UPDATE chat_message SET status = 0 WHERE to_user_id = $1 AND from_user_id = $2 AND status = 1",array($_SESSION['userid'],$to));