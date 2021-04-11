<?php
include_once('dbh.inc.php');
include_once('functions.inc.php');
session_start();
if(empty($_SESSION['useremail'])) {
  header("Location: login.php");
  die("Redirecting to login.php");
}

date_default_timezone_set('Europe/Amsterdam');

$query = pg_query($conn, "
SELECT firstname, lastname, employeeid FROM employees 
WHERE employeeid != '".$_SESSION['userid']."' ORDER BY employeeid");


echo "<table>\n";

while($row = pg_fetch_array($query,NULL, PGSQL_ASSOC))
{
echo "\t<tr>\t";
      echo "\t<tr>\n";
          echo "\t\t<td>". $row['firstname']. " " . $row['lastname']."</td>\n";
          echo "\t\t<td><button type='button' id='startchat' onclick='window.location.href='/page2'' class='button btn start_chat' data-touserid=".$row['employeeid'].">Share</button></td>";
    echo "\t</tr>\n";
}
  echo "</table>\n";