<?php 
//clean input data function
require_once ('datavalidation.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <link href="settings2.css" rel="stylesheet">
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

    <div class="innernav"style="overflow-x:auto;width: 100%;">
      <h1 style="margin-right: 10%;">My settings</h1>
      <a href="#companyinfo">Company information</a>
      <a href="#accountsettings">Account settings</a>
      <a href="#security">Security</a>

      <hr>
      <div>
        <h3 id="companyinfo">Company information</h3>
        <form>
        	<label for="companyname">Company name</label>
        	<input type="text" name="companyname" class="settings">
        	<label for="street">Street</label>
        	<input type="text" name="street" class="settings">
        	<label for="postcode">Postal code</label>
        	<input type="text" name="postcode" class="settings">
        	<label for="city">City</label>
        	<input type="text" name="city" class="settings">
        	<label for="ceo">CEO name</label>
        	<input type="text" name="ceo" class="settings">
        </form>
      </div>
        <hr>
      <div>
        <h3 id="accountsettings">Account settings</h3>
        <form>
        	<label for="firstname">First name</label>
        	<input type="text" name="firstname" class="settings">
        	<label for="lastname">Last name</label>
        	<input type="text" name="lastname" class="settings">
        	<label for="email">Email</label>
        	<input type="text" name="email" class="settings">
        	<label for="phone">Phone</label>
        	<input type="text" name="phone" class="settings">
        	<label for="street">Street</label>
        	<input type="text" name="street" class="settings">
        	<label for="city">City</label>
        	<input type="text" name="city" class="settings">
        </form>
      </div>
      <hr>
      <div>
        <h3 id="security">Security</h3>
        <label for="oldpassword">Old password</label>
        <input type="password" name="oldpassword" class="settings">
        <label for="newpassword">New password</label>
        <input type="password" name="newpassword" class="settings">
        <label for="repeatnewpassword">Repeat new password</label>
        <input type="password" name="repeatnewpassword" class="settings">
      </div>
    </div>

</div>
</body>
</html>