<?php
	include_once('includes/dbh.inc.php');
session_start();
if(empty($_SESSION['useremail'])) {
   header("Location: login.php");
   die("Redirecting to login.php");
}
$username = $_SESSION['useremail'];
$role = $_SESSION["role"];

//clean input data function
require_once ('includes/datavalidation.inc.php');

//gettig userid to receive name from db
$id = $_SESSION['userid'];
//getting name from db
$userdata = pg_query_params($conn,"SELECT firstname, lastname FROM employees WHERE employeeid = $1",array(intval($id)));
$userresult = pg_fetch_array($userdata);

 //empting variables
 $qry = $data = "";

 //preparing query
 $qry = pg_query("SELECT * FROM companyinfo");
 $data = pg_fetch_assoc($qry);

 //preparing ceo query
 $ceo = pg_query("SELECT firstname, lastname FROM employees WHERE jobid=1");
 $ceoresult = pg_fetch_assoc($ceo);

$query = "SELECT businessunits.businessunit, COUNT(*) as number FROM employees
          JOIN BusinessUnits ON Employees.UnitID=BusinessUnits.UnitID
          GROUP BY businessunit";
$result = pg_query($conn, $query);

//businessunit krijgen van ingelogde manager
$managerunit = pg_query_params($conn, "SELECT UnitID AS aantal FROM employees WHERE employeeid = $1",array(intval($id)));
$unitamaount = pg_fetch_array($managerunit);
$unitamount = $unitamaount['aantal'];
$_SESSION['unitid'] = $unitamount;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <link href='css/home.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <link href='css/navbar.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
           <script type="text/javascript">
           google.charts.load('current', {'packages':['corechart', 'bar']});
           google.charts.setOnLoadCallback(drawChart);
           function drawChart()
           {
                var data = google.visualization.arrayToDataTable([
                          ['unitid', 'Number'],
                          <?php
                          while($row = pg_fetch_array($result))
                          {
                               echo "['".$row["businessunit"]."', ".$row["number"]."],";
                          }
                          ?>
                     ]);
                var options = {
                  backgroundColor: '#f0f0f0'
                };
                var chart = new google.visualization.ColumnChart(document.getElementById('chart'));
                chart.draw(data, options);
           }
           $(window).resize(function(){
             drawChart();
           });
           </script>

  </script>
</head>

<body>
  <!--navbar-->
  <ul class="nav">
      <li class="navitem"><a href="home.php"><img src="img/logo.png" alt="Logo"></a></li>
      <li class="navitem"><a href="home.php"><img src="img/home.png" alt="home"></a></li>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="employees.php"><img src="img/employee.png"></a></li> <?php } ?>
  		<?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="customers.php"><img src="img/customer.png" alt="Customers"></a></li> <?php } ?>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="units.php"><img src="img/unit.png" alt="Unit"></a></li> <?php } ?>
      <li class="navitem"><a href="cloud.php"><img src="img/icons8-upload-to-cloud-100.png" alt="cloud"></a></li>
      <li class="navitem"><a href="calender.php"><img src="img/icons8-thursday-100.png" alt="calender"></a></li>
      <li class="navitem"><a href="chat.php"><img src="img/icons8-chat-100.png" alt="chat"></a></li>
      <li class="navitem"><a href="settings.php"><img src="img/settings.png" alt="Settings"></a></li>
      <li class="navitem bottom"><a href="education.php"><img src="img/icons8-education-100.png" alt="Education"></a></li>

  </ul>
    <!--mainpage-->
    <div style="margin-left:100px;padding:1px 16px;height:100%;">
      <!--search employee-->
      <?php if($role != "employee") { ?>
      <div class="searchform">
         <form method="post" action="searchresult.php" class="search">
              <input type="text" name="search" placeholder="Search employee" required class="S">
          </form>
        <hr>
      </div> <?php } ?>

    <h1>Hello <?php echo $userresult['firstname'] . " " . $userresult['lastname']; ?></h1>

    <!--number of employees-->
    <?php if($role == "admin") { ?>
    <div class="insight left">
      <h1 class="title">Number of employees</h1>
      <?php
        $conn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
            or die('Could not connect: ' . pg_last_error());

        $amount = pg_query($conn,'SELECT COUNT(employeeid) AS numberofemployees FROM employees');
        $resultamount = pg_fetch_array($amount);
      ?>
      <h1 class="employeeamount"><?php echo $resultamount['numberofemployees']; ?></h1>
    </div>
    <?php } else if ($role == "manager") {?>
    <div class="insight left">
      <h1 class="title">Number of employees<br>in your unit</h1>
      <?php
        $amount = pg_query_params($conn,'SELECT COUNT(employeeid) AS numberofemployees FROM employees WHERE UnitID = $1',array(intval($unitamount)));
        $resultamount = pg_fetch_array($amount);
      ?>
      <h1 class="employeeamount"><?php echo $resultamount['numberofemployees']; ?></h1>
    </div>
    <?php } ?>

    <!--stock-->
    <div class="insight right">
      <iframe frameBorder='0' class="stock" scrolling='no' src='https://api.stockdio.com/visualization/financial/charts/v1/HistoricalPrices?app-key=2AEA891CB1874DFBB32238D7A954FDD2&indicators=macd(26,12,9);&stockExchange=AEX&symbol=ict&dividends=true&splits=true&palette=Financial-Light&showLogo=No&borderColor=f0f0f0&backgroundColor=f0f0f0&captionColor=fead68&titleColor=ffffff'></iframe>
    </div>

    <!--employees-->
    <?php if($role == "admin" || $role == "manager") {?>
    <div class="insight left">
      <h1 class="title">Employees per Businessunit</h1>
      <div id="chart" style="width:100%;height:78%;"></div>
    </div>
    <?php } ?>


    <!--birthdays-->
    <div class="insight right">
      <h1 class="title">Birthdays of today</h1>
      <?php
      $dataquery = pg_query($conn,"SELECT firstname, lastname FROM employees
                  WHERE DATE_PART('day', birthdate) = date_part('day', CURRENT_DATE)
                  AND DATE_PART('month', birthdate) = date_part('month', CURRENT_DATE)");
      $dataresult = pg_fetch_array($dataquery);
      ?>
      <h2 class="jarigen"><?php if (empty($dataresult)) {
        echo "There is nothing to celebrate today! :/";
      } else {
          echo $dataresult['firstname']." ".$dataresult['lastname'];
        }
       ?></h2>
    </div>

    </div>
</body>
</html>

<script>
$(document).ready(function(){

fetch_user();

setInterval(function(){
 update_last_activity();
 fetch_user();
}, 5000);


function fetch_user()
{
 $.ajax({
  url:"includes/fetch_user.inc.php",
  method:"POST",
  success:function(data){
   $('#user_details').html(data);
  }
 })
}

function update_last_activity()
 {
  $.ajax({
   url:"includes/update_last_activity.inc.php",
   success:function()
   {

   }
  })
 }



});  
</script>
