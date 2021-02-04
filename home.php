<?php
session_start();
if(empty($_SESSION['useremail'])) {
   header("Location: login.php");
   die("Redirecting to login.php");
}
$username = $_SESSION['useremail'];

//clean input data function
require_once ('datavalidation.php');

//Connecting to bd
$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 or die('Could not connect: ' . pg_last_error());

 //empting variables
 $qry = $data = "";

 //preparing query
 $qry = pg_query("SELECT * FROM companyinfo");
 $data = pg_fetch_assoc($qry);

 //preparing ceo query
 $ceo = pg_query("SELECT firstname, lastname FROM employees WHERE jobid=1");
 $ceoresult = pg_fetch_assoc($ceo);
?>
<?php
$query = "SELECT businessunits.businessunit, COUNT(*) as number FROM employees
          JOIN BusinessUnits ON Employees.UnitID=BusinessUnits.UnitID
          GROUP BY businessunit";
$result = pg_query($dbconn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <link href='css/home.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <script defer src="datainsert.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
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
      <li class="navitem"><a href="employees.php"><img src="img/employee.png"></a></li>
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

    <h1>Hello <?php echo "User...."; ?></h1>
    <!--number of employees-->
    <div class="insight left">
      <h1 class="title">Number of employees</h1>
      <?php
        $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
            or die('Could not connect: ' . pg_last_error());

        $amount = pg_query($dbconn,'SELECT COUNT(employeeid) AS numberofemployees FROM employees');
        $resultamount = pg_fetch_array($amount);
      ?>
      <h1 class="employeeamount"><?php echo $resultamount['numberofemployees']; ?></h1>
    </div>

    <!--number of employees per business unit (graph)-->
    <div class="insight right">
      <iframe frameBorder='0' class="stock" scrolling='no' src='https://api.stockdio.com/visualization/financial/charts/v1/HistoricalPrices?app-key=2AEA891CB1874DFBB32238D7A954FDD2&indicators=macd(26,12,9);&stockExchange=AEX&symbol=ict&dividends=true&splits=true&palette=Financial-Light&showLogo=No&borderColor=f0f0f0&backgroundColor=f0f0f0&captionColor=fead68&titleColor=ffffff'></iframe>
    </div>

    <!--number of customers-->
    <div class="insight left">
      <h1 class="title">Employees per Businessunit</h1>
      <div id="chart" style="width:100%;height:78%;"></div>
    </div>

    <!--Number of jobs done-->
    <div class="insight right">
      <h1 class="title">Birthdays of today</h1>
      <?php
      $dataquery = pg_query($dbconn,"SELECT firstname, lastname FROM employees
                  WHERE DATE_PART('day', birthdate) = date_part('day', CURRENT_DATE)
                  AND DATE_PART('month', birthdate) = date_part('month', CURRENT_DATE)");
      ?>
      <h2><?php if (empty($dataquery)) {
        echo "<h2 class=\"geenjarigen\">There are no birthdays today! :/</h2>";
      } else {
          echo "<table class=\"jarigentable\">\n";
          echo "\t<tr>\t";
            while ($line = pg_fetch_array($dataquery,NULL, PGSQL_ASSOC)) {
              echo "\t<tr>\n";
              foreach ($line as $col_value) {
                echo "\t\t<td class=\"jarigen\">$col_value</td>\n";
              }
              echo "\t</tr>\n";

            }
          echo "</table>\n";

          pg_free_result($dataquery);

          pg_close($dbconn);}
       ?></h2>
    </div>

    </div>
</body>
</html>
