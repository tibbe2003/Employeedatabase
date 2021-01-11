<?php
//input schonen en testen
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
    //clean input data function
    require_once ('datavalidation.php');
    //empting varialbes
    $businessname = $contactname = $email = $website = $adress = NULL;
    $websiteErr ="";
?>

<?php

    if (isset($_POST['submit'])) {
        echo "succes";}
    die;
    //connect to database
    $dbconn = pg_connect("host=localhost dbname=thijmen user=thijmen password=Oliebol2003")
        or die('Could not connect: ' . pg_last_error());

    //if submit button is pushed, collect data and clean input
    if (isset($_POST['submit'])) {
        $businessname = clean_input($_POST['businessname']); 
        $contactname = clean_input($_POST['contactname']); 
        $email = clean_input($_POST['email']); 
        $website = clean_input($_POST['website']); 
        $adress = clean_input($_POST['adress']); 
    }


    //make shure that website is well formed
  if (empty($_POST["website"])) {
    $website = "";
  } else {
    $website = test_input($_POST["website"]);
    // check if URL address syntax is valid
    if (!preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i",$website)) {
      $websiteErr = "Invalid URL";
    }    
  }

    if (!$websiteErr) {
        //if name and email err are false import data
        if($businessname=="") {$businessname=NULL;} //if anything is empty give NULL
        if($contactname=="") {$contactname=NULL;}
        if($email=="") {$email=NULL;}
        if($adress=="") {$adress=NULL;}
        //input data into database
        $query = pg_query_params(
            $dbconn,"INSERT  INTO customers(customername,contactname,email,website,adress) VALUES ($1,$2,$3,$4,$5)",array($businessname,$contactname,$email,$website,$adress));
            header("Location: customers.php");
    }
    //if there are name or mail errors
    else {
            header("Location: customers.php?Error=".$websiteErr);
    }
    
    exit();
?>