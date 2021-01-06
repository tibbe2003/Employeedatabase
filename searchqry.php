<?php
  $searchq = $query = $result = $line = $col_value="";
    //Collect
    if(isset ($_POST['search'])) {
      $searchq = $_POST['search'];
    //Connect database
      $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
          or die('Could not connect: ' . pg_last_error());

      //Query
      $query = 'SELECT Employees.EmployeeID, Employees.FirstName, Employees.LastName, Employees.Email
              FROM Employees WHERE LOWER(FirstName) LIKE LOWER($1) OR LOWER(LastName) LIKE LOWER($1)
              ORDER BY employeeid';

      //result
      $result = pg_query_params($dbconn,$query,array("%$searchq%"));
    }

if (isset($_POST['search'])) {

  echo "<table>\n"; //data in tabel zetten
    echo
    "<tr>
    <td>ID</td>
    <td>First Name</td>
    <td>Last Name</td>
    <td>Email</td>
    </tr>";
  echo "\t<tr>\t";
  while ($line = pg_fetch_array($result,NULL, PGSQL_ASSOC)) {
      echo "\t<tr>\n";
      foreach ($line as $col_value) {
          echo "\t\t<td>$col_value</td>\n";
      }
      echo "\t</tr>\n";

  }
  echo "</table>\n";

  pg_close($dbconn);
}
?>