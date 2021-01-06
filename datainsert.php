<?php 
require_once ('datavalidation.php'); //data schonen
$fname = $lname = $email = $phone = $birthdate = $adress = $city = $jobtitle = $businessunit = $joindate = $salary=NULL; //variabelen leegmaken
$nameErr = $emailErr = $phoneErr = $adressErr = $cityErr ="";


?>

<?php 
$dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
    or die('Could not connect: ' . pg_last_error());

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
    } //gegevens uit form halen

    // input valideren.
    if (empty($fname) or empty ($lname)) {
        $nameErr = "First and Last name are both required";
    } else if (!preg_match("/^[a-zA-Z-' ]*$/",$fname) or !preg_match("/^[a-zA-Z-' ]*$/",$lname)
        )  {  
      // Check if firstname and lastname are well formed.
      $nameErr = "Only letters and white space allowed";
    }

    //input valideren
    if (empty($email)) {
      $emailErr = "Email is required";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $emailErr = "Invalid email format";
    }


    if (!$nameErr and !$emailErr) {
        // als we geen erros hebben, data in database toevoegen.
        if($phone=="") {$phone=NULL;}
        if($adress=="") {$adress=NULL;}
        if($birthdate=="") {$birthdate=NULL;}
        if($city=="") {$city=NULL;}
        if($jobtitle=="") {$jobtitle=NULL;}
        if($businessunit=="") {$businessunit=NULL;}
        if($joindate=="") {$joindate=NULL;}
        if($salary=="") {$salary=NULL;}

        $query = pg_query_params(
                    $dbconn,
                    "INSERT  INTO Employees(FirstName, LastName, Email, Phone, BirthDate, Adress, City, JobID, UnitID, Joindate, Salary) VALUES ($1,$2,$3,$4,$5,$6,$7,$8,$9,$10,$11)",
                    array($fname,$lname,$email,$phone,$birthdate,$adress,$city,$jobtitle,$businessunit,$joindate,$salary)
                );
            header("Location: employees.php");
    } else {
        // als we errors hebben fout melding geven.
            header("Location: employees.php?nameErr=".$nameErr."&emailErr=".$emailErr);
    }
    
    exit();
?>