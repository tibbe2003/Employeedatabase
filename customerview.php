 <?php
//clean input data function
require_once('datavalidation.php');
//connecting to database
$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 or die('Could not connect: ' . pg_last_error());
//empting varialbes
 $customername = $contactname = $email = $website = $id = $adress = $customerid ="";

//get the customer id to make changes
if(isset($_GET['customerid'])) {$id = $_GET['customerid'];}

if(isset($_POST['cancel'])) {header("location:customers.php");}
// when click on Update button
if(isset($_POST['update']))
{  
	//getting input out of the form
    $customername = clean_input($_POST['customername']);
    $contactname = clean_input($_POST['contactname']);
    $email = clean_input($_POST['email']);
    $website = clean_input($_POST['website']);
    $adress = clean_input($_POST['adress']);
    $customerid = clean_input($_POST['customerid']);

    //preparing an update query for given input
    $edit = pg_query_params($dbconn,"UPDATE customers SET customername=$1, contactname=$2, email=$3, website=$4, adress=$5 where customerid = $6",array($customername,$contactname,$email,$website,$adress,intval($customerid))) or die ('Query failed: ' . pg_last_error()); //nieuwe gegevens in database zetten
	
	//if edit is true
    if($edit)
    {
        pg_close($dbconn); // Close connection
        header("location:customers.php"); // redirects to all records page
        exit;
    }
    //if edit is false
    else
    {
        echo "Could not edit this customer";
    } 
} 
//if not clicked on update
	else {
	//getting data from database
    	$qry = pg_query_params($dbconn,'SELECT customerid, customername, contactname, email, website, adress 
        	FROM customers 
        	WHERE customerid = $1',array(intval($id)))
    			or die ('Query failed: ' . pg_last_error());
    			//getting ready to show data
    			$data = pg_fetch_array($qry); // fetch data  
	}
?>

<?php
	//clean input data function
  	require_once ('datavalidation.php');
  	//empting variables
  	$job = $unit =""; $nameErr = $emailErr ="";
  	//validate input
  	if (isset($_GET['nameErr'])){ $nameErr = clean_input($_GET['nameErr']); }//input valideren
  	if (isset($_GET['emailErr'])){$emailErr = clean_input($_GET['emailErr']); } //input valideren
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title><?php echo $data['customername']; ?></title>
  	<link href="customerview.css" rel="stylesheet">
  	<script defer src="datainsert.js"></script>
  	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
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

	<div style="margin-left:100px;padding:1px 16px;height:100%;">
	<!--search employees-->
  		<div class="searchform">
      		<form method="post" action="searchresult.php" class="search">
          		<input type="text" name="search" placeholder="Search employee" required class="S">
      		</form>
    	<hr>
  		</div>

		<h2>Customer: <?php echo $data['customername']; ?></h2>
    <div class="employeedata">
	<!--showing data in form to edit-->
		<form action="customerview.php" method="POST">
      <input type="text" name="customerid" value="<?php echo $data['customerid'] ?>" hidden>
        <h3>Customer information</h3>
        <label>Customer id</label>
    		<input type="text" name="employeeidshow" class="editform" value="<?php echo $data['customerid']?>" disabled>
        <label>Business name</label>
     		<input type="text" name="customername" class="editform" value="<?php echo $data['customername'] ?>" placeholder="Enter Businessname" Required>
        <label>Contact name</label>
    		<input type="text" name="contactname" class="editform" value="<?php echo $data['contactname'] ?>" placeholder="Enter contact name" Required>
        <label>Email</label>
    		<input type="text" name="email" class="editform" value="<?php echo $data['email'] ?>" placeholder="Enter contact email" Required>
        <label>Adress</label>
        <input type="text" name="adress" class="editform" value="<?php echo $data['adress']?>" placeholer="Enter adress">
        <label>Website</label>
        <input type="text" name="website" class="editform" value="<?php echo $data['website']?>" placeholer="Webadress" >
  			<input type="submit" name="update" value="Save" class="button">
  			<input type="submit" name="cancel" value="Cancel" class="button">
		</form>
    </div>

    <div class="leftdiv">
      <h3>Assigned employees:</h3>
      <div class="assignedto">
        <?php 
          $assigned = pg_query_params($dbconn,'SELECT * FROM deployment
            JOIN FirstName ON Employees.EmployeeID=Deployment.EmployeeID
            JOIN LastName ON Employees.EmployeeID=Deployment.EmployeeID
            WHERE customerid = $1',array(intval($id)))
            or die ('query failed' . pg_last_error());

            $assigneddata = pg_fetch_array($assigned);

            echo "<table>\n";
            echo
            "<tr>
            employeeid
            <td>Name</td>
            </tr>";
            echo "\t<tr>\t";
              while ($line = pg_fetch_array($assigneddata,NULL, PGSQL_ASSOC)) {
                echo "\t<tr>\n";
                foreach ($line as $col_value) {
                    echo "\t\t<td>$col_value</td>\n";
                }
              echo "<td><a href='deassign.php?employeeid=".$line['employeeid']."'>Delete</a></td>";
              echo "\t</tr>\n";

              }
            echo "</table>\n";

      pg_free_result($assigneddata);

      pg_close($dbconn);
    ?>
      </div>
      <!--collapsinble buttons for attachments and notes-->
      <button class="collapsible"><img src="img/attachment.png" class="image"> Contracts</button>
      <div class="content">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      </div>
    </div>

  </div>
</body>
</html>

<script>
var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.maxHeight){
      content.style.maxHeight = null;
    } else {
      content.style.maxHeight = content.scrollHeight + "px";
    } 
  });
}
</script>


