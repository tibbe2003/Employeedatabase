<?php

session_start();

function fetch_users($conn) {
$query = pg_query_params($conn, "SELECT firstname,lastname,employeeid FROM employees WHERE employeeid != $1", array($_SESSION['userid']));
          //showing the units
          echo "<table>\n";
          echo "<tr><td class=\"unitname\">Contact name</td></tr>";
          echo "\t<tr>\t";
              while ($line = pg_fetch_array($query,NULL, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                    echo "\t\t<td>". $line['firstname']. " " . $line['lastname'] . "</td>\n";
                    echo "\t\t<td><button type='button' class='button' data-touserid=".$line['employeeid']. " data-tousername=" .$line['firstname']. " " . $line['lastname'].">Start Chat</button></td>";
                    if($_SESSION["userid"] == $line)
              echo "\t</tr>\n";

              }
            echo "</table>\n";

      pg_free_result($query);

      pg_close($dbconn);
}