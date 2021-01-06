<?php 
  require_once ('datavalidation.php'); //data schonen
  $job = $unit =""; $nameErr = $emailErr ="";
  if (isset($_GET['nameErr'])){ $nameErr = clean_input($_GET['nameErr']); }//input valideren
  if (isset($_GET['emailErr'])){$emailErr = clean_input($_GET['emailErr']); } //input valideren
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="searchresult.css" rel="stylesheet">
  <script defer src="datainsert.js"></script>
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>


<body>

<ul class="nav">
  <li class="navitem"><a href="employees.php"><img src="img/logo.png" alt="Logo"></a></li>
  <li class="navitem"><a class="active" href="employees.php"><img src="img/home.png"></a></li>
  <li class="navitem"><a href="business.php"><img src="img/office.png" alt="Office"></a></li>
  <li class="navitem"><a href="customer.php"><img src="img/customer.png" alt="Customers"></a></li>
  <li class="navitem"><a href="unit.php"><img src="img/unit.png" alt="Unit"></a></li>
  <li class="navitem"><a href="settings.php"><img src="img/settings.png" alt="Settings"></a></li>
</ul>

<div style="margin-left:100px;padding:1px 16px;height:100%;">

  <div class="searchform">
      <form method="post" action="searchresult.php" class="search">
          <input type="text" name="search" placeholder="Search employee" required class="S">
      </form>
    <hr>
  </div>

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
        <?php
      //$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
        //  or die('Could not connect: ' . pg_last_error());

      //$slcqry = 'SELECT * FROM Jobtitles';
      //$resultaat = pg_query($dbconn, $slcqry);
      //while($rijen = pg_fetch_array($resultaat))
        {
          echo "<option value='1'>sql professional</option>";
          echo "<option value='2'>Business unit manager</option>";
          echo "<option value='3'>Operations manager</option>";
          echo "<option value='4'>CEO</option>";
        }
        ?>
        </select>
          <label for="Businessunit">Business unit:</label>
        <select name="Businessunit" class="datainput">
        <?php
      //$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
        //  or die('Could not connect: ' . pg_last_error());

      //$slcqry = 'SELECT * FROM Jobtitles';
      //$resultaat = pg_query($dbconn, $slcqry);
      //while($rijen = pg_fetch_array($resultaat))
        {
          echo "<option value='1'>Machine&systems</option>";
          echo "<option value='2'>Automotive</option>";
          echo "<option value='3'>Databases</option>";
          echo "<option value='4'>OrangeNXT</option>";
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

  <div style="overflow-x:auto;width: 100%;">
    <h1><?php if(isset($_POST['search'])) {
          $searchname = $_POST['search'];
          echo "Search result '$searchname'";}?></h1>
    <?php
      $searchq = $query = $result = $line = $col_value="";
      //Collect
        if(isset ($_POST['search'])) {
          $searchq = $_POST['search'];
          //Connect database
          $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
          or die('Could not connect: ' . pg_last_error());

          //Query
          $query = 'SELECT Employees.EmployeeID, Employees.FirstName, Employees.LastName, Employees.Email, Employees.Phone, Employees.BirthDate, Employees.Adress, Employees.City
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
                echo "\t\t<td><a href='edit.php?employeeid=".$line['employeeid']."' class=\"queryresultaten\">$col_value</a></td>\n";
              }
                echo "<td><a href='delete.php?employeeid=".$line['employeeid']."'>Delete</a></td>";
                echo "\t</tr>\n";

              }
            echo "</table>\n";

            pg_close($dbconn);
        }
    ?>
  </div>

</div>
</body>
</html>