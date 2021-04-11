<?php
//uncluding neccesery files
include_once('dbh.inc.php');
include_once('functions.inc.php');
//checking if user is logged in
session_start();
if(empty($_SESSION['useremail'])) {
  header("Location: /login.php");
  die("Redirecting to login.php");
}

//set timezone to get activity status right
date_default_timezone_set('Europe/Amsterdam');

//selecting every user besides of yourself
$query = pg_query($conn, "
SELECT firstname, lastname, employeeid FROM employees 
WHERE employeeid != '".$_SESSION['userid']."' ORDER BY employeeid");

echo "<table>\n";
//while there are items in the array it wil do this
while($row = pg_fetch_array($query,NULL, PGSQL_ASSOC))
{
  //getting online status of user
 $status = '';
 $current_timestamp = strtotime(date("Y-m-d H:i:s") . '- 10 second');
 $current_timestamp = date('Y-m-d H:i:s', $current_timestamp);
 $user_last_activity = fetch_user_last_activity($row['employeeid'], $conn);
 
 if($user_last_activity > $current_timestamp)
 {
  $status = '<span class="label label-success"></span>';
 }
 else
 {
  $status = '<span class="label label-danger"></span>';
 }

//fetching all aquired data in table to view users
echo "\t<tr>\t";
      echo "\t<tr>\n";
          echo "\t\t<td>". count_unseen_message($row['employeeid'], $_SESSION['userid'], $conn) . "</td>";
          echo "\t\t<td>". $row['firstname']. " " . $row['lastname'] . "</td>\n";
          echo "\t\t<td>".$status."</td>";
          echo "\t\t<td><button type='button' id='startchat' class='button btn start_chat' data-touserid=".$row['employeeid']. " data-tousername=" .$row['firstname']. " " . $row['lastname'].">Chat</button></td>";
    echo "\t</tr>\n";
}
  echo "</table>\n";