<?php
include_once('dbh.inc.php');

session_start();

$update = pg_query_params($conn, "UPDATE users SET lastactivity = now() WHERE usersid = $1",array($_SESSION['userid']));