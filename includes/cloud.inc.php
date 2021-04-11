<?php
include_once("dbh.inc.php");
include_once("datavalidation.inc.php");
session_start(); 
if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
 }
$_SESSION['result'] = "";

if(isset($_POST['submit'])){
    $name= $_FILES['file']['name'];    
   
   $size=$_FILES['file']['size'];  
   $type=$_FILES['file']['type'];  
   $temp=$_FILES['file']['tmp_name'];  
   $error = $_FILES['file']['error'];  
   $date = date('Y-m-d H:i:s');  

 if ($error > 0) //Check file upload has error  
     {  
     $_SESSION['result'] = "<strong>Error:</strong> " . $error . "<br>"; //Sesssio to carry error  
     header("location:/cloud.php");
     die; 
     }  
   else{  
         
       if ($size > 500000) { //Check File Size   
            $_SESSION['result'] = "<strong>Error:</strong> Maximum file size 5MB!!!<br>"; 
            header("location:/cloud.php");
            die;
       }else{  
           $targetPath = "/data/sites/thijmenbrand.nl/files/".clean_input($name);  
             
           if(file_exists($targetPath)){  // Check whether the file name already exist in the server.  
                    $_SESSION['result'] = "<strong>Error:</strong> File already exists!<br>";
                    header("location:/cloud.php");
                    die;
                 }
   $uploadstatus = move_uploaded_file($temp,$targetPath);   
   
  if($uploadstatus == true){$query= pg_query_params($conn, "INSERT INTO files (name,date,userid) VALUES ($1, $2, $3)",array($name,$date,$_SESSION['userid']));}  
 if($query || $uploadstatus == true){    
     header("location:/cloud.php");
     die;
 }  
 else{   
    $_SESSION['result'] = "<strong>On no!</strong>Something went horrably wrong"; //Session to carry success message  
    header("location:/cloud.php");
    die;
 }  
       }  
   }  
 }  