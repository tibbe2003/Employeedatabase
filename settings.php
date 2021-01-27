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
  <link href='settings2.css?<?php echo time(); ?>' rel='stylesheet'></link>
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

    <div class="innernav"style="overflow-x:auto;width: 100%;">
      <h1 style="margin-right: 10%;">My settings</h1>
      <a href="#companyinfo">Company information</a>
      <a href="#accountsettings">Account settings</a>
      <a href="#security">Security</a>

      <hr>
      <div>
        <h3 id="companyinfo">Company information</h3>
        <form method="POST" class="form">
        	<label for="companyname">Company name</label>
        	<input type="text" name="companyname" value="<?php echo $data['companyname'] ?>" class="settings compname" placeholder="Company name" disabled><br>
        	<label for="street">Street</label>
        	<input type="text" name="street" value="<?php echo $data['street'] ?>"class="settings" placeholder="Street" disabled>
        	<label for="postcode">Postal code</label>
        	<input type="text" name="postcode" value="<?php echo $data['postalcode'] ?>" class="settings" placeholder="Postal code" disabled><br>
        	<label for="city">City</label>
        	<input type="text" name="city" value="<?php echo $data['city'] ?>" class="settings" placeholder="City" disabled>
        	<label for="ceo">CEO name</label>
        	<input type="text" name="ceo" value="<?php echo $ceoresult['firstname']." ".$ceoresult['lastname'] ?>" class="settings" placeholder="CEO name" disabled>
        </form>
      </div>
        <hr>
      <div>
        <h3 id="accountsettings">Account settings</h3>
        <form method="POST" class="form">
        	<label for="firstname">First name</label>
        	<input type="text" name="firstname" class="settings" placeholder="First name">
        	<label for="lastname">Last name</label>
        	<input type="text" name="lastname" class="settings" placeholder="Last name"><br>
        	<label for="email">Email</label>
        	<input type="text" name="email" class="settings" placeholder="Email">
        	<label for="phone">Phone</label>
        	<input type="text" name="phone" class="settings" placeholder="Phone"><br>
        	<label for="street">Street</label>
        	<input type="text" name="street" class="settings" placeholder="Street">
        	<label for="city">City</label>
        	<input type="text" name="city" class="settings" placeholder="City">
        </form>
      </div>
      <hr>
      <div>
        <h3 id="security">Security</h3>
        <form method="POST" class="form">
        <label for="oldpassword">Old password</label>
        <input type="password" name="oldpassword" class="settings" placeholder="Old password"><br>
        <label for="newpassword">New password</label>
        <input type="password" name="newpassword" class="settings" placeholder="New password"><br>
        <label for="repeatnewpassword">Repeat new password</label>
        <input type="password" name="repeatnewpassword" class="settings" placeholder="Repeat new password"><br>
    	</form>
      </div>
    </div>

</div>
</body>
</html>