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

if(isset($_POST['cancel'])) {header("location:employees.php");}
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
  	<link href="employeesview.css" rel="stylesheet">
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
  		<li class="navitem"><a class="active" href="employees.php"><img src="img/employee.png"></a></li>
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

		<h2>Employee: <?php echo $data['firstname'] . $data['lastname']; ?></h2>
    <div class="employeedata">
	<!--showing data in form to edit-->
		<form action="edit.php" method="POST">
        <h3>General information</h3>
        <label>Employee id</label>
    		<input type="text" name="employeeid" class="editform" value="<?php echo $data['employeeid']?>" disabled>
        <label>First name</label>
     		<input type="text" name="firstname" class="editform" value="<?php echo $data['firstname'] ?>" placeholder="Enter Firstname" Required>
        <label>Last name</label>
    		<input type="text" name="lastname" class="editform" value="<?php echo $data['lastname'] ?>" placeholder="Enter Lastname" Required>
        <label>Email</label>
    		<input type="text" name="email" class="editform" value="<?php echo $data['email'] ?>" placeholder="Enter email" Required>
        <h3>Work information</h3>
        <label>Jobtitle</label>
        <input type="text" name="jobtitle" class="editform" value="<?php echo $data['jobtitles']?>" placeholer="jobtitle" disabled>
        <label>Business unit</label>
        <input type="text" name="businessunit" class="editform" value="<?php echo $data['businessunit']?>" placeholer="businessunit" disabled>
        <label>Salary</label>
        <input type="text" name="salary" class="editform" value="<?php echo $data['salary'] ?>" placeholder="Enter phone" Required>
        <label>Join date</label>
        <input type="date" name="joindate" class="editform" value="<?php echo $data['joindate'] ?>" placeholder="joindate" Required disabled>
        <h3>Personal information</h3>
        <label>Phone</label>
    		<input type="text" name="phone" class="editform" value="<?php echo $data['phone'] ?>" placeholder="Enter phone" Required>
        <label>Adress</label>
        <input type="text" name="adress" class="editform" value="<?php echo $data['adress'] ?>" placeholder="Enter Lastname" Required>
        <label>City</label>
        <input type="text" name="city" class="editform" value="<?php echo $data['city'] ?>" placeholder="Enter email" Required>
        <label>Birthdate</label>
    		<input type="date" name="birthday" class="editform" value="<?php echo $data['birthdate'] ?>" placeholder="Enter Firstname" disabled>
  			<input type="submit" name="update" value="Save" class="button">
  			<input type="submit" name="cancel" value="Cancel" class="button">
		</form>
    </div>

    <div class="leftdiv">
      <h3>Assigned to:</h3>
      <div class="assignedto">
        <!-- assigned to customers-->
      </div>
      <!--collapsinble buttons for attachments and notes-->
      <button class="collapsible">Attachments</button>
      <div class="content">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
      </div>
      <button class="collapsible">Notes</button>
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
