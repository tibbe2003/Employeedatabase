<?php
include_once("includes/dbh.inc.php");
include_once("includes/datavalidation.inc.php");
session_start();
if(empty($_SESSION['useremail'])) {
   header("Location: login.php");
   die("Redirecting to login.php");
}
$username = $_SESSION['useremail'];
$role = $_SESSION["role"];

$qry = pg_query_params($conn, "SELECT * FROM files where fileid = $1",array(intval($_GET['fileid'])));
$fetch = pg_fetch_array($qry);
$filename = $fetch['name'];
$fileid = $fetch['fileid'];
$path = "/data/sites/thijmenbrand.nl/files/".$filename;
$url = $_SERVER['HTTP_REFERER'];
$output = file_get_contents($path, FILE_USE_INCLUDE_PATH);
  

  if(isset($_POST['save'])) {
    $editfile = fopen($path, 'w');
    $edit = $_POST['file'];

    fwrite($editfile, $edit);
    fclose($editfile);
    header("location:editfile.php?id=".$fileid);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat</title>
  <link href='css/editdocument.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <link href='css/navbar.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>

<body>
  <!--navbar-->
  <ul class="nav">
      <li class="navitem"><a href="home.php"><img src="img/logo.png" alt="Logo"></a></li>
      <li class="navitem"><a href="home.php"><img src="img/home.png" alt="home"></a></li>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="employees.php"><img src="img/employee.png"></a></li> <?php } ?>
  		<?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="customers.php"><img src="img/customer.png" alt="Customers"></a></li> <?php } ?>
      
      <li class="navitem"><a href="cloud.php"><img src="img/icons8-upload-to-cloud-100.png" alt="cloud"></a></li>
      <li class="navitem"><a href="calender.php"><img src="img/icons8-thursday-100.png" alt="calender"></a></li>
      <li class="navitem"><a href="chat.php"><img src="img/icons8-chat-100.png" alt="chat"></a></li>
      <li class="navitem"><a href="settings.php"><img src="img/settings.png" alt="Settings"></a></li>
      <li class="navitem bottom"><a href="education.php"><img src="img/icons8-education-100.png" alt="Education"></a></li>

  </ul>
    <!--mainpage-->
    <div style="margin-left:100px;padding:1px 16px;height:100%;">
      <!--search employee-->
      <?php if($role != "employee") { ?>
      <div class="searchform">
         <form method="post" action="searchresult.php" class="search">
              <input type="text" name="search" placeholder="Search employee" required class="S">
          </form>
        <hr>
      </div> <?php } ?>
      
      <div class="operators">
        <a href="editfile.php?id=<?php echo $fileid ?>"><img src="img/icons8-double-left-20.png" alt=""><p> Back</p></a>
        <form action="editdocument.php?fileid=<?php echo $fileid ?>" method="POST" onsubmit='redirect();return false;'>
          <input type="submit" name="save" value="Save" class="btn">
          <textarea name="file" id="file" rows="31" cols="170">
            <?php echo $output ?>
          </textarea>
        </form>
     </div>

    </div>
</body>
</html>