<?php
//clean input data function
require_once('datavalidation.php');
//connecting to database
$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
 or die('Could not connect: ' . pg_last_error());
//empting varialbes
 $fristname = $lastname = $email = $phone = $id = $birthdate = $joindate ="";

//get the employeeid to make changes
if(isset($_GET['employeeid'])) { $id = $_GET['employeeid'];}

// when click on Update button
if(isset($_POST['update']))
{
	//getting input out of the form
    $firstname = clean_input($_POST['firstname']);
    $lastname = clean_input($_POST['lastname']);
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    $birthdate = clean_input($_POST['birthdate']);
    $adress = clean_input($_POST['adress']);
    $city = clean_input($_POST['city']);
    $joindate = clean_input($_POST['joindate']);
    $salary = clean_input($_POST['salary']);
    $employeeid = clean_input($_POST['employeeid']);

    //preparing an update query for given input
    $edit = pg_query_params($dbconn,"UPDATE employees SET firstname=$1, lastname=$2, email=$3, phone=$4, adress=$5, city=$6, salary=$7  where employeeid = $8",array($firstname,$lastname,$email,$phone,$adress,$city,$salary,intval($employeeid))) or die ('Query failed: ' . pg_last_error()); //nieuwe gegevens in database zetten
	
	//if edit is true
    if($edit)
    {
        pg_close($dbconn); // Close connection
        header("location:employees.php"); // redirects to all records page
        exit;
    }
    //if edit is false
    else
    {
        echo "Could not edit this employee";
    } 

} 
//if not clicked on update
	else {
	//getting data from database
    	$qry = pg_query_params($dbconn,'SELECT Employees.EmployeeID, Employees.FirstName, Employees.LastName, Employees.Email, Employees.Phone, Employees.BirthDate, Employees.Adress, Employees.City, Jobtitles.Jobtitles, BusinessUnits.BusinessUnit, Employees.Joindate, Employees.Salary 
        	FROM employees 
        	JOIN Jobtitles ON Employees.JobID=Jobtitles.JobID
        	JOIN BusinessUnits ON Employees.UnitID=BusinessUnits.UnitID
        	WHERE employeeid = $1',array(intval($id)))
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
  <title>Employees</title>
  	<link href="edit.css" rel="stylesheet">
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
  		<li class="navitem"><a class="active" href="employees.php"><img src="img/home.png"></a></li>
  		<li class="navitem"><a href="business.php"><img src="img/office.png" alt="Office"></a></li>
  		<li class="navitem"><a href="customer.php"><img src="img/customer.png" alt="Customers"></a></li>
  		<li class="navitem"><a href="unit.php"><img src="img/unit.png" alt="Unit"></a></li>
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

		<h3>Update Data</h3>
	<!--showing data in form to edit-->
		<form action="edit.php" method="POST">
    		<input type="hidden" name="employeeid" class="editform" value="<?php echo $data['employeeid']?>">
     		<input type="text" name="firstname" class="editform" value="<?php echo $data['firstname'] ?>" placeholder="Enter Firstname" Required>
    		<input type="text" name="lastname" class="editform" value="<?php echo $data['lastname'] ?>" placeholder="Enter Lastname" Required>
    		<input type="text" name="email" class="editform" value="<?php echo $data['email'] ?>" placeholder="Enter email" Required>
    		<input type="text" name="phone" class="editform" value="<?php echo $data['phone'] ?>" placeholder="Enter phone" Required>
    		<input type="date" name="birthday" class="editform" value="<?php echo $data['birthdate'] ?>" placeholder="Enter Firstname" disabled>
    		<input type="text" name="adress" class="editform" value="<?php echo $data['adress'] ?>" placeholder="Enter Lastname" Required>
    		<input type="text" name="city" class="editform" value="<?php echo $data['city'] ?>" placeholder="Enter email" Required>
    		<input type="text" name="jobtitle" class="editform" value="<?php echo $data['jobtitles']?>" placeholer="jobtitle" disabled>
    		<input type="text" name="businessunit" class="editform" value="<?php echo $data['businessunit']?>" placeholer="businessunit" disabled>
    		<input type="date" name="joindate" class="editform" value="<?php echo $data['joindate'] ?>" placeholder="joindate" Required disabled>
    		<input type="text" name="salary" class="editform" value="<?php echo $data['salary'] ?>" placeholder="Enter phone" Required>
  			<input type="submit" name="update" value="update">
  			<button><a href="employees.php">cancel</a></button>
		</form>
  	</div>
</body>
</html>