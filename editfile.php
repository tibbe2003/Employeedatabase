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

//bytes to whatever is needed
function formatBytes($bytes, $precision = 2) { 
  $units = array('B', 'KB', 'MB', 'GB', 'TB'); 

  $bytes = max($bytes, 0); 
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
  $pow = min($pow, count($units) - 1); 

  // Uncomment one of the following alternatives
  // $bytes /= pow(1024, $pow);
  // $bytes /= (1 << (10 * $pow)); 

  return round($bytes, $precision) . ' ' . $units[$pow]; 
} 

$qry = pg_query_params($conn, "SELECT * FROM files where fileid = $1",array(intval($_GET['id'])));
$fetch = pg_fetch_array($qry);
$filename = $fetch['name'];
$fileid = $fetch['fileid'];
$path = "/data/sites/thijmenbrand.nl/files/".$filename;
$tag = "Dit komt nog";
$size = formatBytes(filesize($path));
$type = pathinfo('/data/sites/thijmenbrand.nl/files/'.$filename, PATHINFO_EXTENSION);
$url = $_SERVER['HTTP_REFERER'];
if($url != "https://thijmenbrand.nl/cloud.php?order_by=name&sort=desc" || $url != "https://thijmenbrand.nl/cloud.php?order_by=name&sort=asc") {
  $url = "cloud.php";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat</title>
  <link href='css/editfile.css?<?php echo time(); ?>' rel='stylesheet'></link>
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
      
      <!--File info-->
      <div>
        <p>File "<?php echo $filename ?>"
        <br>
        <br>
        File size: <?php echo $size ?>
        <br>
        MIME-type: <?php echo $type ?>
        <br>
        Tag: <?php echo $tag ?></p>
      </div>
      <!--operations-->
      <div class="operations">
      <a href='includes/filedownload.inc.php?fileid=<?php echo $fileid?>'><img src="img/icons8-download-from-cloud-40.png" alt="Download"><p>Download</p></a>
      <a href='editdocument.php?fileid=<?php echo $fileid?>' style="margin-left:15px;"><img src="img/icons8-edit-file-40.png" alt=""><p>Edit</p></a>
      <a href="<?php echo $url ?>" style="margin-left:15px;"><img src="img/icons8-back-40.png" alt=""><p>Back</p></a>
      </div>
      <!--Inhoud-->
      <div class="inhoud">
        <?php
            $file = fopen("/data/sites/thijmenbrand.nl/files/".$filename, "r") or die("Unable to open file!");
            while(!feof($file)) {
                echo clean_input(fgets($file)) . "<br>";
              }
              fclose($file);
        ?>
      </div>

    </div>
</body>
</html>

