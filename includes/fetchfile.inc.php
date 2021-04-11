<?php

include "dbh.inc.php";
include_once("datavalidation.inc.php");
session_start();
if(empty($_SESSION['useremail'])) {
  header("Location: login.php");
  die("Redirecting to login.php");
}

$extensions = array("png", "jpg", "jpeg", "svg", "jfif", "gif", "webp");
$id = $_POST['id'];
$qry = pg_query_params($conn, "SELECT name FROM files WHERE fileid = $1",array($id));
$result = pg_fetch_array($qry);
$name = $result['name'];
$path = "../../files/".$name;
$info = pathinfo('../../files/'.$name);
$extension = $info['extension'];
$url = $_SERVER['HTTP_REFERER'];

    $file = fopen("../../files/".$result['name'], "r") or die("Unable to open file!");
  while(!feof($file)) {
      echo clean_input(fgets($file)) . "<br>";
    }
    fclose($file);
