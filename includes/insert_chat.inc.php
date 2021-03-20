<?php

include_once('dbh.inc.php');
include_once('functions.inc.php');
session_start();

$touserid = $_POST['to_user_id'];
$fromuserid = $_SESSION['userid'];
$chatmessage = $_POST['chat_message'];
$status = 1;

$query = pg_query_params($conn, "INSERT INTO chat_message (to_user_id, from_user_id, chat_message, status) 
                        VALUES ($1, $2, $3, $4)",array(intval($touserid),intval($fromuserid), $chatmessage, $status));
if ($query) {
    echo fetch_user_chat_history($_SESSION['userid'], $_POST['to_user_id'], $conn);
}