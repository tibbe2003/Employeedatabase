<?php
	include_once('dbh.inc.php');
  session_start();
  if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }
    //clean input data function
    require_once ('includes/datavalidation.inc.php');
    //empting varialbes
    $fname = $lname = $email = $phone = $birthdate = $adress = $city = $jobtitle = $businessunit = $joindate = $salary=NULL;
    $nameErr = $emailErr = $phoneErr = $adressErr = $cityErr ="";
?>

<?php
    //if submit button is pushed, collect data and clean input
    if (isset($_POST['submit'])) {
        $fname = clean_input($_POST['Fname']); 
        $lname = clean_input($_POST['Lname']); 
        $email = clean_input($_POST['Email']); 
        $phone = clean_input($_POST['Phone']); 
        $birthdate = clean_input($_POST['Birthdate']); 
        $adress = clean_input($_POST['Adress']); 
        $city = clean_input($_POST['City']); 
        $jobtitle = clean_input($_POST['Jobtitle']); 
        $businessunit = clean_input($_POST['Businessunit']); 
        $joindate = clean_input($_POST['Joindate']); 
        $salary = clean_input($_POST['Salary']); 
    }

    //validate input
    if (empty($fname) or empty ($lname)) {
        $nameErr = "First and Last name are both required";
    } else if (!preg_match("/^[a-zA-Z-' ]*$/",$fname) or !preg_match("/^[a-zA-Z-' ]*$/",$lname)
        )  {  
      // Check if firstname and lastname are well formed.
      $nameErr = "Only letters and white space allowed";
    }

    //validate input
    if (empty($email)) {
      $emailErr = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }


    if (!$nameErr and !$emailErr) {
        //if name and email err are false import data
        if($phone=="") {$phone=NULL;} //if anything is empty give NULL
        if($adress=="") {$adress=NULL;}
        if($birthdate=="") {$birthdate=NULL;}
        if($city=="") {$city=NULL;}
        if($jobtitle=="") {$jobtitle=NULL;}
        if($businessunit=="") {$businessunit=NULL;}
        if($joindate=="") {$joindate=NULL;}
        if($salary=="") {$salary=NULL;}
        //input data into database
        $query = pg_query_params(
            $conn,"INSERT  INTO Employees(FirstName, LastName, Email, Phone, BirthDate, Adress, City, JobID, UnitID, Joindate, Salary) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)",array($fname,$lname,$email,$phone,$birthdate,$adress,$city,$jobtitle,$businessunit,$joindate,$salary));
            header("Location: /employees.php");
    }
    //if there are name or mail errors
    else {
            header("Location: /employees.php?nameErr=".$nameErr."&emailErr=".$emailErr);
    }
    
    exit();
?>