<?php
 session_start();
if(empty($_SESSION['useremail'])) {
   header("Location: login.php");
   die("Redirecting to login.php");
}
$username = $_SESSION['useremail'];
$role = $_SESSION['role'];

if($_SESSION['role'] == "employee") {header("location: home.php?error=91");}
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
  <link href="css/units.css?<?php echo time(); ?>" rel="stylesheet">
  <script defer src="datainsert.js"></script>
  <script defer src="jobtitleinsert.js"></script>
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
      <li class="navitem"><a href="home.php"><img src="img/logo.png" alt="Logo"></a></li>
      <li class="navitem"><a href="home.php"><img src="img/home.png" alt="home"></a></li>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="employees.php"><img src="img/employee.png"></a></li> <?php } ?>
  		<?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="customers.php"><img src="img/customer.png" alt="Customers"></a></li> <?php } ?>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="units.php"><img src="img/unit.png" alt="Unit"></a></li> <?php } ?>
      <li class="navitem"><a href="chat.php"><img src="img/icons8-chat-100.png" alt="chat"></a></li>
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
    <!--datainput popup 1-->
    <?php if($role == "admin") { ?>
    <button data-modal-target="#modal" class="addbutton">Add businessunit</button> <?php } ?>
      <div class="modal" id="modal">
        <div class="modal-header">
            <div class="title">Add new businessunit</div>
            <button data-close-button class="close">&times;</button>
        </div>
      <div class="modal-body">
          <form action="unitinsert.php" method="post">
          <input type="text" name="unit" placeholder="Add Businessunit" required class="datainput">
          <input type="submit" name="submit">
          </form>
      </div>
    </div>
    <div id="overlay"></div>

    <!--showing all the employees-->
    <div style="overflow-x:auto;width: 100%;">
      <div class="units">
        <h2>Businessunits</h2>
        <?php
          //connecting database
          $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
            or die('Could not connect: ' . pg_last_error());

          //constructing query to select all employee data
          $query = 'SELECT * FROM BusinessUnits ORDER BY UnitID';
          //showing the units
          $resultunits = pg_query($dbconn,$query) or die ('Query failed' . pg_last_error());
          //showing in table
          echo "<table>\n";
          echo "<tr><td class=\"unitname\">Unit name</td></tr>";
          echo "\t<tr>\t";
              while ($lineunits = pg_fetch_array($resultunits,NULL, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                foreach ($lineunits as $col_value) {
                    echo "\t\t<td>$col_value</td>\n";
                }
              echo "<td><a href='unitdelete.php?unitid=".$lineunits['unitid']."'>Delete</a></td>";
              echo "\t</tr>\n";

              }
            echo "</table>\n";

      pg_free_result($resultunits);

      pg_close($dbconn);
        ?>
        <?php
        if (isset($_GET["error"])) {
    			if ($_GET["error"] == "error") {
    				echo "<div class=\"alert\">
              				<strong>Error!</strong>There are employees assigned to this unit!
          				</div>";
          }
        }
        ?>
      </div>

      <div class="titles">
        <h2>Jobtitles</h2>
        <?php
          //connecting database
          $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
            or die('Could not connect: ' . pg_last_error());

          //constructing query to select all employee data
          $queryjob = 'SELECT * FROM Jobtitles ORDER BY JobID';
          //showing the units
          $resultjob = pg_query($dbconn,$queryjob) or die ('Query failed' . pg_last_error());
          //showing in table
          echo "<table>\n";
          echo "<tr><td class=\"unitname\">Jobtitles</td></tr>";
          echo "\t<tr>\t";
              while ($linejob = pg_fetch_array($resultjob,NULL, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                foreach ($linejob as $col_valuejob) {
                    echo "\t\t<td>$col_valuejob</td>\n";
                }
              echo "\t</tr>\n";

              }
            echo "</table>\n";

      pg_free_result($resultjob);

      pg_close($dbconn);
        ?>
      </div>
    </div>

</div>
</body>
</html>
