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
      <div id="chart" style="width:100%;height:74%;"></div>
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

    <!--favorite files-->
    <div class="insight left">
      <h1 class="title">Favorite files</h1>
      <?php
      $files = pg_query_params($conn,"SELECT * FROM files
                  WHERE userid = $1 AND favorite = 1",array($_SESSION['userid']));
      $dataresult = pg_fetch_array($files);
      ?>
      <?php if (empty($dataresult)) {
        echo "<p class='tekst'>You have no favorite file yet! go make some</p><br><a href='cloud.php'>in your personal cloud</a>";
      } else {?>
        <table id='filetable'>
        <tr>
              <td>File name</td>
              <td>Date</td>
              <td></td>
              <td></td>
              <td></td>
        </tr>
        <?php
        $qry = pg_query_params($conn, "SELECT * FROM files WHERE userid = $1 AND favorite = 1",array($_SESSION['userid']));
        while($row = pg_fetch_array($qry,NULL, PGSQL_ASSOC)){
          $name = $row['name'];
          $date = $row['date'];
          $fileid = $row['fileid'];
        ?>
          <tr>
            <td><img src="img/icons8-star-24-2.png" alt="delete" class="star"></td>
            <td><?php echo $name; ?></td>
            <td><?php echo $date; ?></td>
            <td><a href='includes/filedownload.inc.php?fileid=<?php echo $fileid?>'><img src="img/icons8-download-24.png" alt="Download"></a></td>
            <td><a href='includes/deletefile.inc.php?fileid=<?php echo $fileid?>'><img src="img/icons8-delete-bin-24.png" alt="delete"></a></td>
          </tr>
  <?php
  }
  ?>
</table>
        <?php } ?>
    </div>

    <!--calender items-->
    <div class="insight right">
      <?php
        $day = date('l');
        $month = date('F');
        $daynum = date('d');
      ?>
      <h3 class="agenda" style="color:red;"><?php echo $day ?></h3>
      <h1 class="agenda" style="margin-top:-30px;display:inline-block;"><?php echo $daynum ?></h1>
      <h3 class="agenda" style="display:inline-block;margin-left:-5x;"><?php echo $month ?></h3>
      <?php
      $appointments = pg_query_params($conn, "SELECT * FROM calender WHERE userid = $1 AND date = CURRENT_DATE",array($_SESSION['userid']));
      $ifappointments = pg_fetch_array($appointments);
      if(empty($ifappointments)) {
      echo "<h2>There are no Calender items today!</h2>";
      } else {  
        // Fetch events based on the specific date 
        $userid = $_SESSION['userid'];
        $result = pg_query_params($conn, "SELECT id, title FROM calender WHERE date = CURRENT_DATE AND status = 1 AND userid = $1",array($userid)); 
        $qry = pg_query_params($conn, "SELECT COUNT(title) AS amount FROM calender WHERE date = CURRENT_DATE AND status = 1 AND userid = $1",array($userid));
        $rowcount = pg_fetch_array($qry);
        if($rowcount['amount'] > 0){ 
            $eventListHTML .= '<ul class="sidebar__list">'; 
            $eventListHTML .= '<li class="sidebar__list-item sidebar__list-item--complete">Events</li>'; 
            while($row = pg_fetch_array($result,NULL, PGSQL_ASSOC)){
                $num = rand(1, 6);
                switch ($num)
                {
                  case 1:
                    $color = "green";
                    break;
                  case 2:
                    $color = "red";
                    break;
                  case 3:
                    $color = "orange";
                    break;
                  case 4:
                    $color = "blue";
                    break;
                  case 5:
                    $color = "yellow";
                    break;
                  case 6:
                    $color = "pink";
                    break;
                }
                $eventListHTML .= "<li id='editrow' class='sidebar__list-item listitem'><div class='colortje' style='background-color:".$color.";'></div> ".$row['title']."</li>"; 
            } 
            $eventListHTML .= '</ul>'; 
        } 
        echo $eventListHTML;
      }
      ?>
      
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
