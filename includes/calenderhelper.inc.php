<?php

include_once('datavalidation.inc.php');
include_once('dbh.inc.php');
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }


$id = $_POST['id'];

if(isset($_POST['submit'])){
    $title = clean_input($_POST['title']);
    $date = clean_input($_POST['date']);

    $qry = pg_query_params($conn, "UPDATE calender SET title = $1, date = $2 WHERE id = $3",array($title, $date, $id));

    if($qry) {
        header("location:/calender.php");
    }
} else if(isset($_POST['delete'])) {
    $qry = pg_query_params($conn, "DELETE FROM calender WHERE id = $1",array($id));

    if($qry) {
        header("location:/calender.php");
    }
}