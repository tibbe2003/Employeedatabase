<?php
session_start();
include_once('dbh.inc.php');
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }
$userid = $fileid = "";
$userid = $_SESSION['userid'];
$fileid = $_GET['fileid'];
$url = $_SERVER['HTTP_REFERER'];

//query voor het ophalen van status.
$qry1 = pg_query_params($conn, "SELECT favorite FROM files WHERE userid = $1 AND fileid = $2",array($userid,$fileid));
$status = pg_fetch_array($qry1,NULL, PGSQL_ASSOC);

//als status == 1 moet hij naar 0  en visa versa
if($status['favorite'] >= 1) {
    $tozero = pg_query_params($conn, "UPDATE files SET favorite = 0 WHERE userid = $1 AND fileid = $2",array($userid,$fileid));
    if($tozero) {
        header("location:$url");
    }
} else {
    $toone = pg_query_params($conn, "UPDATE files SET favorite = 1 WHERE userid = $1 AND fileid = $2",array($userid,$fileid));
    if($toone) {
        header("location:$url");
    }
}