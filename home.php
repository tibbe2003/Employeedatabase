<?php 
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

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <link href='insight.css?<?php echo time(); ?>' rel='stylesheet'></link>
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
      <h1>test</h1>
      <?php
        $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
            or die('Could not connect: ' . pg_last_error()); 

        $result_1 = pg_query($dbconn,'SELECT COUNT(employeeid) FROM employees')
      ?>
      <h1><?php echo $result"['employeeid']"; ?></h1>
    </div>

    <!--number of employees per business unit (graph)-->
    <div class="insight right">
      <h1>test</h1>
    </div>

    <!--number of customers-->
    <div class="insight left">
      <h1>test</h1>
    </div>

    <!--Number of jobs done-->
    <div class="insight right">
      <h1>test</h1>
    </div>

    </div>
</body>
</html>