<?php 
//clean input data function
require_once ('datavalidation.php');
//empting variables
$job = $unit =""; $nameErr = $emailErr ="";
//validate input
if (isset($_GET['nameErr'])){ $nameErr = clean_input($_GET['nameErr']); }
if (isset($_GET['emailErr'])){$emailErr = clean_input($_GET['emailErr']); }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <link href="home.css" rel="stylesheet">
  <script defer src="datainsert.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
  <!--navbar-->
  <ul class="nav">
      <li class="navitem"><a href="#employees"><img src="img/logo.png" alt="Logo"></a></li>
      <li class="navitem"><a class="active" href="#employees"><img src="img/employee.png"></a></li>
      <li class="navitem"><a href="customers.php"><img src="img/customer.png" alt="Customers"></a></li>
      <li class="navitem"><a href="units.php"><img src="img/unit.png" alt="Unit"></a></li>
      <li class="navitem"><a href="settings.php"><img src="img/settings.png" alt="Settings"></a></li>
  </ul>
    <!--mainpage-->
    <div style="margin-left:100px;padding:1px 16px;height:100%;">
      <!--search employee-->
      <div class="searchform">
         <form method="post" action="searchresult.php" class="search">
              <input type="text" name="search" placeholder="Search employee" required class="S">
          </form>
        <hr>
        </div>
    <!--datainput popup-->
    <button data-modal-target="#modal" class="addbutton">Add employee</button>
      <div class="modal" id="modal">
        <div class="modal-header">
            <div class="title">Add new employee</div>
            <button data-close-button class="close-button">&times;</button>
        </div>
      <div class="modal-body">
          <form action="datainsert.php" method="post">
            <label for="Fname">First name:</label>
          <input type="text" name="Fname" placeholder="First name" required class="datainput">
            <label for="Lname">Last name:</label>
          <input type="text" name="Lname" placeholder="Last name" required class="datainput">
            <label for="Email">Email:</label>
          <input type="email" name="Email" placeholder="Email" required class="datainput">
            <label for="Phone">Phone:</label>
          <input type="tel" name="Phone" placeholder="Phone" class="datainput">
          <label for="Birthdate">Date of birth:</label>
          <input type="date" name="Birthdate" placeholder="Birthdate" class="datainput">
            <label for="Adress">Adress:</label>
          <input type="text" name="Adress" placeholder="Adress" class="datainput">
            <label for="City">City:</label>
          <input type="text" name="City" placeholder="City" class="datainput">
            <label for="Jobtitle">Job title:</label>
          <select name="Jobtitle" class="datainput">
            <option value="">Select...</option>
              <?php
                  // connect to database
                  $conn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
                      or die('Could not connect: ' . pg_last_error());
                  //get jobtitle and jobid form database
                  $resultaat = pg_query($conn, "SELECT * FROM Jobtitles");
                  if (!$resultaat) {
                    // error message  
                    echo "An error occurred.\n";
                      exit;
                  }
                  // dispaly result in dropdown
                  while ($row = pg_fetch_row($resultaat)) {
                    echo '<option value="'.$row[0].'">'.$row[1].'</option>';
                  }
              ?>
          </select>
            <label for="Businessunit">Businessunit:</label>
          <select name="Businessunit" class="datainput">
            <option value="">Select...</option>
              <?php
                  // connect to database
                  $conn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
                      or die('Could not connect: ' . pg_last_error());
                  // get unit and unitid from database
                  $resultaat2 = pg_query($conn, "SELECT * FROM BusinessUnits");
                  if (!$resultaat2) {
                    // error message  
                    echo "An error occurred.\n";
                      exit;
                  }
                  // dispaly result in dropdown
                  while ($row2 = pg_fetch_row($resultaat2)) {
                    echo '<option value="'.$row2[0].'">'.$row2[1].'</option>';
                  }
              ?>
          </select>
            <label for="Joindate">Joindate:</label>
          <input type="date" name="Joindate" placeholder="Joindate" class="datainput">
            <label for="Salary">Salary:</label>
          <input type="text" name="Salary" placeholder="salary" class="datainput">
          <input type="submit" name="submit">
          </form>
      </div>
    </div>
    <div id="overlay"></div>

    <!--showing all the employees-->
    <div style="overflow-x:auto;width: 100%;">
    <h1>My employees</h1>
      <?php
        //connect to database
          $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
            or die('Could not connect: ' . pg_last_error());
          //constructing query to select all employee data
          $query = 'SELECT Employees.EmployeeID, Employees.FirstName, Employees.LastName, Employees.Email, Employees.Phone, Employees.BirthDate, Employees.Adress, Employees.City, Jobtitles.Jobtitles, BusinessUnits.BusinessUnit, Employees.Joindate, Employees.Salary 
            FROM employees 
            JOIN Jobtitles ON Employees.JobID=Jobtitles.JobID
            JOIN BusinessUnits ON Employees.UnitID=BusinessUnits.UnitID 
            ORDER BY employeeid';
          //preparing to show the result
          $result = pg_query($query) or die('Query failed: ' . pg_last_error()); //alles ophalen uit database
          //showing result in table
            echo "<table>\n";
            echo
            "<tr>
            <td>ID</td>
            <td>First Name</td>
            <td>Last Name</td>
            <td>Email</td>
            <td>Phone</td>
            <td>Birthdate</td>
            <td>Adress</td>
            <td>City</td>
            <td>Jobtitle</td>
            <td>BusinessUnit</td>
            <td>joindate</td>
            <td>Salary</td>
            </tr>";
            echo "\t<tr>\t";
              while ($line = pg_fetch_array($result,NULL, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                foreach ($line as $col_value) {
                    echo "\t\t<td><a href='edit.php?employeeid=".$line['employeeid']."' class=\"queryresultaten\">$col_value</a></td>\n";
                }
              echo "<td><a href='delete.php?employeeid=".$line['employeeid']."'>Delete</a></td>";
              echo "\t</tr>\n";

              }
            echo "</table>\n";

      pg_free_result($result);

      pg_close($dbconn);
    ?>
  </div>
</div>
</body>
</html>