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
     header("location:/settings.php?error=".$error);
     die; 
     }  
   else{  
        $allowed = array('gif', 'png', 'jpg', 'jpeg');
        $ext = pathinfo($name, PATHINFO_EXTENSION);
        if (!in_array($ext, $allowed)) {
        header("location:/settings.php?error=4");
        die;
    }
         
       if ($size > 500000) { //Check File Size   
            header("location:/settings.php?error=2");
            die;
       }else{  
           $qry = pg_query_params($conn, "SELECT pf FROM users WHERE usersid = $1",array($_SESSION['userid']));
           $result = pg_fetch_array($qry);
           $prefpf = $result['pf'];
            unlink("../profilepictures/".$prefpf);

           $targetPath = "../profilepictures/".clean_input($name);  
             
           if(file_exists($targetPath)){  // Check whether the file name already exist in the server.  
                    header("location:/settings.php?error=3");
                    die;
                 }
  $uploadstatus = move_uploaded_file($temp,$targetPath);   
   
  if($uploadstatus = true){
        $query = pg_query_params($conn, "UPDATE users SET pf = $1 WHERE usersid = $2",array($name,$_SESSION['userid']));
    if($query && $uploadstatus == true){    
     header("location:/settings.php");
     die;
    }  else{   
    $_SESSION['result'] = "<strong>On no!</strong>Something went horrably wrong"; //Session to carry success message  
    header("location:/settings.php?nope");
    die;
 }  
       }  
   } 
} 
 }  