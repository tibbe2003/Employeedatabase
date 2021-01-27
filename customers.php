<?php 
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
  <link href="customers.css?<?php echo time(); ?>" rel="stylesheet">
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
  	<!--datainput popup-->
  	<button data-modal-target="#modal" class="addbutton">Add customer</button>
  		<div class="modal" id="modal">
    		<div class="modal-header">
      			<div class="title">Add new customer</div>
     				<button data-close-button class="close-button">&times;</button>
    		</div>
    	<div class="modal-body">
      		<form method="POST" action="customerinsert.php">
            <label>Business name</label> 
            <input type="text" name="customername" placeholder="businessname" class="datainput" required> 
            <label>Contact name</label>
            <input type="text" name="contactname" placeholder="contact name" class="datainput">
            <label>Contact email</label>
            <input type="text" name="email" placeholder="contact email" class="datainput" required>
            <label>Business website</label>
            <input type="text" name="website" placeholder="Business website" class="datainput">
            <label>Business adress</label>
            <input type="text" name="adress" placeholder="Business adress" class="datainput">
            <input type="submit" name="submit">
          </form>
    	</div>
  	</div>
  	<div id="overlay"></div>

  	<!--showing all the customers-->
  	<div style="overflow-x:auto;width: 100%;">
    <h1>All customers</h1>
    	<?php
        //connect to database
          $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
            or die('Could not connect: ' . pg_last_error());
          //constructing query to select all employee data
          $query = 'SELECT customerid,customername,contactname,email,website,adress 
            FROM customers 
            ORDER BY customerid';
          //preparing to show the result
          $result = pg_query($query) or die('Query failed: ' . pg_last_error()); //alles ophalen uit database
          //showing result in table
            echo "<table>\n";
            echo
            "<tr>
            <td>ID</td>
            <td>Business name</td>
            <td>Contact name</td>
            <td>Email</td>
            <td>Website</td>
            <td>Adress</td>
            </tr>";
            echo "\t<tr>\t";
              while ($line = pg_fetch_array($result,NULL, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                foreach ($line as $col_value) {
                    echo "\t\t<td><a href='customerview.php?customerid=".$line['customerid']."' class=\"queryresultaten\">$col_value</a></td>\n";
                }
              echo "<td><a href='deletecustomer.php?customerid=".$line['customerid']."'>Delete</a></td>";
              echo "\t</tr>\n";

              }
            echo "</table>\n";

      pg_free_result($result);

      pg_close($dbconn);
    ?>
  </div>

</div>
</body>
</html>