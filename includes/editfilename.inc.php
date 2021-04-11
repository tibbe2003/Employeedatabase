<?php

    include "dbh.inc.php";
    include_once("datavalidation.inc.php");
    session_start();
    if(empty($_SESSION['useremail'])) {
      header("Location: login.php");
      die("Redirecting to login.php");
   }

    $id = $_POST['id'];
    $qry = pg_query_params($conn, "SELECT name FROM files WHERE fileid = $1",array($id));
    $result = pg_fetch_array($qry);
    $name = $result['name'];
    $path = "/data/sites/thijmenbrand.nl/files/";
    $file = "/data/sites/thijmenbrand.nl/files/".$name;
    $url = $_SERVER['HTTP_REFERER'];

    if(isset($_POST['submit'])) {
        $name = $_POST['newname'];
        $newname = clean_input($name);
        $id = $_POST['id'];
        $rename = rename($file, $path.$newname);

          if($rename == true) {
            $qry = pg_query_params($conn, "UPDATE files SET name = $1 WHERE fileid = $2",array($newname,$id));
            header("location:$url");
          } else header("location:$url?error=3");
      }

    $output = "<h3>New name</h3>
              <form action='includes/editfilename.inc.php' method='POST'>
                <input type='text' name='id' value='".$id."' hidden>
                <input type='text' name='newname' value='".$name."' value='".$name."' required>
                <input type='submit' name='submit' value='submit'>
             </form>";
  
      echo $output;