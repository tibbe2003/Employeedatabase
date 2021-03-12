<?php
 session_start();
if(empty($_SESSION['useremail'])) { 
   header("Location: login.php");  
   die("Redirecting to login.php"); 
} 
$username = $_SESSION['useremail'];
$role = $_SESSION['role'];

if($_SESSION['role'] == "employee") {header("location: home.php?error=91");}

  require_once ('datavalidation.php'); //data schonen
  $job = $unit =""; $nameErr = $emailErr ="";
  if (isset($_GET['nameErr'])){ $nameErr = clean_input($_GET['nameErr']); }//input valideren
  if (isset($_GET['emailErr'])){$emailErr = clean_input($_GET['emailErr']); } //input valideren
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <script defer src="datainsert.js"></script>
  <link href="css/searchresult.css?<?php echo time(); ?>" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<body>
  <!--Navbar-->
<ul class="nav">
  <li class="navitem"><a href="home.php"><img src="img/logo.png" alt="Logo"></a></li>
  <li class="navitem"><a href="home.php"><img src="img/home.png" alt="home"></a></li>
  <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="employees.php"><img src="img/employee.png"></a></li> <?php } ?>
  		<?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="customers.php"><img src="img/customer.png" alt="Customers"></a></li> <?php } ?>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="units.php"><img src="img/unit.png" alt="Unit"></a></li> <?php } ?>
      <li class="navitem"><a href="chat.php"><img src="img/icons8-chat-100.png" alt="chat"></a></li>
  <li class="navitem"><a href="settings.php"><img src="img/settings.png" alt="Settings"></a></li>
</ul>

<!--main page-->
<div style="margin-left:100px;padding:1px 16px;height:100%;">
  <!--search employees-->
  <div class="searchform">
      <form method="post" action="searchresult.php" class="search">
          <input type="text" name="search" placeholder="Search employee" required class="S">
      </form>
    <hr>
  </div>

  <!--Add employee popup-->
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
                  // get jobtitle en jobid form database
                 $resultaat = pg_query($conn, "SELECT * FROM Jobtitles");
                    if (!$resultaat) {
                      // error message  
                      echo "An error occurred.\n";
                        exit;
                    }
                  // display results in dropdown
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
                  // get unit and unitid form database
                  $resultaat2 = pg_query($conn, "SELECT * FROM BusinessUnits");
                    if (!$resultaat2) {
                      // error message  
                      echo "An error occurred.\n";
                        exit;
                    }
                  // display results in dropdown
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

    <!--show search results-->
    <div style="overflow-x:auto;width: 100%;">
      <!--echo input in title-->
      <h1><?php if(isset($_POST['search'])) {
          $searchname = $_POST['search'];
          echo "Search result '$searchname'";}?></h1>
      <?php
      //empting variables
      $searchq = $query = $result = $line = $col_value="";
        //Collect input from search
        if(isset ($_POST['search'])) {
          $searchq = $_POST['search'];
          //Connect database
          $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
          or die('Could not connect: ' . pg_last_error());

          //query to select employee data
          $query = 'SELECT Employees.EmployeeID, Employees.FirstName, Employees.LastName, Employees.Email, Employees.Phone, Employees.BirthDate, Employees.Adress, Employees.City, Jobtitles.Jobtitles, BusinessUnits.BusinessUnit, Employees.Joindate, Employees.Salary 
              FROM Employees
              JOIN Jobtitles ON Employees.JobID=Jobtitles.JobID
              JOIN BusinessUnits ON Employees.UnitID=BusinessUnits.UnitID 
              WHERE LOWER(FirstName) LIKE LOWER($1) OR LOWER(LastName) LIKE LOWER($1)
              ORDER BY employeeid';

          //result
          $result = pg_query_params($dbconn,$query,array("%$searchq%"));
        }

        //if search is submited show results
        if (isset($_POST['search'])) {
          //show results in table
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

            pg_close($dbconn);
        }
    ?>
  </div>
</div>
</body>
</html>