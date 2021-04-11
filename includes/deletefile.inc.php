<?php
include_once('dbh.inc.php');
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }

$qry = pg_query_params($conn, "SELECT name FROM files WHERE userid = $1 AND fileid = $2",array($_SESSION['userid'],$_GET['fileid']));
$result = pg_fetch_array($qry,NULL, PGSQL_ASSOC);
$dir = "/data/sites/thijmenbrand.nl/files/".$result['name'];

if(unlink($dir)) {
    $deleterecord = pg_query_params($conn, "DELETE FROM files WHERE userid = $1 AND fileid = $2",array($_SESSION['userid'],$_GET['fileid']));
    if($deleterecord) {
        header("location:/cloud.php");
    }
} else {header("location:/cloud.php?error=unknown");}