<?php
session_start();
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }
//load.php

include_once('dbh.inc.php');

$data = array();

$query = "SELECT * FROM events ORDER BY id";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["id"],
  'title'   => $row["title"],
  'start'   => $row["start_event"],
  'end'   => $row["end_event"]
 );
}

echo json_encode($data);

?>