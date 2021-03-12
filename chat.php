<?php
include_once("includes/dbh.inc.php");
include_once("includes/fetch_user.inc.php");
session_start();
if(empty($_SESSION['useremail'])) {
   header("Location: login.php");
   die("Redirecting to login.php");
}
$username = $_SESSION['useremail'];
$role = $_SESSION["role"];

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat</title>
  <link href='css/chat.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <script defer src="datainsert.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
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
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="units.php"><img src="img/unit.png" alt="Unit"></a></li> <?php } ?>
      <li class="navitem"><a href="chat.php"><img src="img/icons8-chat-100.png" alt="chat"></a></li>
      <li class="navitem"><a href="settings.php"><img src="img/settings.png" alt="Settings"></a></li>
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
    </div>

    <div class="users">
        <?php fetch_users($conn); ?>
    </div>
</body>
</html>

<script>
$(document).ready(function(){
    fetch_user();

    function fetch_user()
    {
        $.ajax({
            url:"includes/fetch_user.inc.php",
            method:"POST",
            succes:function(data){
                $('#user_details').html(data);
            }
        })
    }
});
</script>