<?php 
//clean input data function
require_once ('datavalidation.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <link href="settings.css" rel="stylesheet">
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
      <li class="navitem"><a href="employees.php"><img src="img/logo.png" alt="Logo"></a></li>
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
    <!--datainput popup-->
    <!--<button data-modal-target="#modal" class="addbutton">Add customer</button>
      <div class="modal" id="modal">
        <div class="modal-header">
            <div class="title">Add new customer</div>
            <button data-close-button class="close-button">&times;</button>
        </div>
      <div class="modal-body">
          <h1>Add Customer</h1>
      </div>
    </div>
    <div id="overlay"></div>-->

    <!--showing all the employees-->
    <div class="innernav"style="overflow-x:auto;width: 100%;">
      <h1 style="margin-right: 10%;">My settings</h1>
      <a href="#companyinfo">Company information</a>
      <a href="#accountsettings">Account settings</a>
      <a href="#security">Security</a>

      <hr>
      <div>
        <h3 id="companyinfo">Company information</h3>
      </div>
        <hr>
      <div>
        <h3 id="accountsettings">Account settings</h3>
      </div>
      <hr>
      <div>
        <h3 id="security">Security</h3>
      </div>
    </div>

</div>
</body>
</html>