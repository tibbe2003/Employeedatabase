<?php
  include_once('includes/datavalidation.inc.php');
  include_once('includes/dbh.inc.php');
  session_start();
  if(empty($_SESSION['useremail'])) {
    header("Location: login.php");
    die("Redirecting to login.php");
  }
  $username = $_SESSION['useremail'];
  $role = $_SESSION["role"];
  $qry = $data = "";

  //getting userid form logged in user
  if (isset($_SESSION['userid'])){$id = $_SESSION['userid'];}

  if (isset($_POST['save'])) {
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    $adress = clean_input($_POST['adress']);
    $city = clean_input($_POST['city']);

    //nieuwe gegevens in db zetten
    $edit = pg_query_params($conn, "UPDATE employees SET email=$1, phone=$2, adress=$3, city=$4 WHERE employeeid = $5",array($email,$phone,$adress,$city,intval($id)));
    $editmail = pg_query_params($conn, "UPDATE users SET usersemail=$1 WHERE usersid=$2", array($email,intval($id)));

    if ($edit) {
      pg_close($conn);
      header("location:settings.php?succes=1");
      exit;
    }
    else {
      pg_close($conn);
      header("location:settings.php?succes=0");
      exit;
    }
  } //else for if the button is not clicked
  else {
    $userdata = pg_query_params($conn,"SELECT firstname, lastname, email, phone, adress, city FROM employees WHERE employeeid = $1",array(intval($id)));
    $userresult = pg_fetch_array($userdata);
  }

  //preparing query
  $qry = pg_query("SELECT * FROM companyinfo");
  $data = pg_fetch_assoc($qry);

  //preparing ceo query
  $ceo = pg_query("SELECT firstname, lastname FROM employees WHERE jobid=1");
  $ceoresult = pg_fetch_assoc($ceo);

  $pf = pg_query_params($conn, "SELECT pf FROM users WHERE usersid = $1",array($_SESSION['userid']));
  $fetch = pg_fetch_array($pf);
  $pfname = $fetch['pf'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Employees</title>
  <link href='css/settings.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <link href='css/navbar.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

      <?php
        if (isset($_GET["error"])) {
          if ($_GET["error"] == "2") {
            echo "<div class=\"alert\">
                      <strong>Error!</strong> File can't be larger than 5MB!
                  </div>";
          }
          else if ($_GET["error"] == "3") {
            echo "<div class=\"altert\">
                      <strong>Error!</strong> Unknown error!
                  </div>";
          }
          else if ($_GET["error"] == "4") {
            echo "<div class=\"alert\">
                      <strong>Error</strong> File must be image (png, jpg or gif)!
                  </div>";
          }
        }
      ?>
    <div class="innernav"style="overflow-x:auto;width: 100%;">
      <h1 style="margin-right: 10%;">My settings</h1>
      <a href="#companyinfo">Company information</a>
      <a href="#accountsettings">Account settings</a>
      <p id="pfmodal">Profile picture</p>
      <a href="#security">Security</a>
      <?php
      if (isset($_SESSION["useremail"])) {
      	echo "<a href='includes/logout.inc.php' class=\"logout\" style=\"color: red;\">Logout</a>";
      }
      ?>

      <hr>
      <!--als de role admin is kan je gegevens bewerken-->
      <?php if($role == "admin") { ?>
      <div class="section">
        <h3 id="companyinfo">Company information</h3>
        <form method="POST" class="form" action="includes/editcompanyinfo.inc.php">
        	<label for="companyname">Company name</label>
        	<input type="text" name="companyname" value="<?php echo $data['companyname'] ?>" class="settings compname" placeholder="Company name"><br>
        	<label for="street">Street</label>
        	<input type="text" name="street" value="<?php echo $data['street'] ?>"class="settings" placeholder="Street">
        	<label for="postcode">Postal code</label>
        	<input type="text" name="postcode" value="<?php echo $data['postalcode'] ?>" class="settings" placeholder="Postal code"><br>
        	<label for="city">City</label>
        	<input type="text" name="city" value="<?php echo $data['city'] ?>" class="settings" placeholder="City">
        	<label for="ceo">CEO name</label>
        	<input type="text" name="ceo" value="<?php echo $ceoresult['firstname']." ".$ceoresult['lastname'] ?>" class="settings" placeholder="CEO name" disabled>
          <input type="submit" name="submit" value="Save">
        </form>
      </div><?php } ?>
      
      <!--als de role iets anders is dan admin-->
      <?php if($role == "manager" || $role == "employee") { ?>
      <div class="section">
        <h3 id="companyinfo">Company information</h3>
        <form method="POST" class="form">
        	<label for="companyname">Company name</label>
        	<input type="text" name="companyname" value="<?php echo $data['companyname'] ?>" class="settings compname" placeholder="Company name" disabled><br>
        	<label for="street">Street</label>
        	<input type="text" name="street" value="<?php echo $data['street'] ?>"class="settings" placeholder="Street" disabled>
        	<label for="postcode">Postal code</label>
        	<input type="text" name="postcode" value="<?php echo $data['postalcode'] ?>" class="settings" placeholder="Postal code" disabled><br>
        	<label for="city">City</label>
        	<input type="text" name="city" value="<?php echo $data['city'] ?>" class="settings" placeholder="City" disabled>
        	<label for="ceo">CEO name</label>
        	<input type="text" name="ceo" value="<?php echo $ceoresult['firstname']." ".$ceoresult['lastname'] ?>" class="settings" placeholder="CEO name" disabled>
        </form>
      </div><?php } ?>
        <hr>
      <div class="section">
        <h3 id="accountsettings">Account settings</h3>
        <form method="POST" class="form" action="settings.php">
        	<label for="lastname">full name</label>
        	<input type="text" name="name" class="settings" placeholder="full name" value="<?php echo $userresult['firstname']. " " . $userresult['lastname'] ?>" disabled><br>
        	<label for="email">Email</label>
        	<input type="text" name="email" class="settings" placeholder="Email" value="<?php echo $userresult['email'] ?>">
        	<label for="phone">Phone</label>
        	<input type="text" name="phone" class="settings" placeholder="Phone" value="<?php echo $userresult['phone'] ?>"><br>
        	<label for="adress">Adress</label>
        	<input type="text" name="adress" class="settings" placeholder="Adress" value="<?php echo $userresult['adress'] ?>">
        	<label for="city">City</label>
        	<input type="text" name="city" class="settings" placeholder="City" value="<?php echo $userresult['city'] ?>">
          <input type="submit" name="save" value="Save" class="btn">
        </form>
      </div>
      <hr>
      <div class="section">
        <?php
          if (isset($_GET["error"])) {
            if ($_GET["error"] == "error") {
              echo "<div class=\"alert\">
                        <strong>Error!</strong> Password reset failed!.
                    </div>";
            }
            else if ($_GET["error"] == "none") {
              echo "<div class=\"succes\">
                        <strong>Succes</strong> Your password has been reset!.
                    </div>";
            }
          }
        ?>
        <h3 id="security">Security</h3>

        <form action="includes/pwdreset.inc.php" method="POST" class="form">
        <label for="oldpassword">Old password</label>
        <input type="password" name="oldpwd" class="settings" placeholder="Old password"><br>
        <label for="newpassword">New password</label>
        <input type="password" name="pwd" class="settings" placeholder="New password"><br>
        <label for="repeatnewpassword">Repeat new password</label>
        <input type="password" name="pwdrepeat" class="settings" placeholder="Repeat new password"><br>
        <input type="submit" name="submit" value="submit" class="btn">
    	</form>
      </div>
    </div>

</div>

<div id="modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <div style="height:80px;width:80px;margin-left:auto;margin-right:auto;">
      <img src="profilepictures/<?php echo $pfname ?>" alt="Profile picture" class="pf" style="height:100%;width:100%;border-radius:50%;">
    </div>
    <form action="includes/profilepicture.inc.php" method="POST" enctype="multipart/form-data">
      <input type="file" name="file" class="file">
      <input type="submit" name="submit" value="Edit" class="uploadfile">
    </form>
  </div>

</div>
</body>
</html>

<script>
  var modal = document.getElementById("modal");
var btn = document.getElementById("pfmodal");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

$(document).ready(function(){
  
  setInterval(function(){
   update_last_activity();
  }, 5000);
  
  function update_last_activity()
   {
    $.ajax({
     url:"includes/update_last_activity.inc.php",
     success:function()
     {
  
     }
    })
   }
  
  }); 
</script>
