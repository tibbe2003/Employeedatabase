<?php

include_once('dbh.inc.php');
include_once('functions.inc.php');
session_start();

echo fetch_user_chat_history($_SESSION['userid'], $_POST['to_user_id'], $conn);