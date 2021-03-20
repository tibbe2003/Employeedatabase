<?php

$conn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 or die('Could not connect: ' . pg_last_error());
 session_start();

 $query = 'SELECT fistname, lastname, employeeid FROM employees';
 //showing the units
 $resultunits = pg_query($conn,$query) or die ('Query failed' . pg_last_error());
 //showing in table
 echo "<table>\n";
 echo "<tr><td class=\"unitname\">Unit name</td></tr>";
 echo "\t<tr>\t";
     while ($lineunits = pg_fetch_array($resultunits,NULL, PGSQL_ASSOC)) {
       echo "\t<tr>\n";
       foreach ($lineunits as $col_value) {
           echo "\t\t<td>$col_value</td>\n";
       }
     echo "<td><a href='unitincludes/delete.inc.php?unitid=".$lineunits['unitid']."'>Delete</a></td>";
     echo "\t</tr>\n";

     }
   echo "</table>\n";