<?php
include_once('includes/calender.inc.php');
include_once("includes/dbh.inc.php");

if(empty($_SESSION['useremail'])) {
   header("Location: login.php");
   die("Redirecting to login.php");
}
$username = $_SESSION['useremail'];
$role = $_SESSION["role"];
$userid = $_SESSION['userid'];
$unitamount = $_SESSION['unitid'];
$id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat</title>
  <link href='css/calender.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <link href='css/navbar.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <script defer src="includes/datainsert.inc.js"></script>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>

<body>
  <!--navbar-->
  <ul class="nav">
      <li class="navitem"><a href="home.php"><img src="img/logo.png" alt="Logo"></a></li>
      <li class="navitem"><a href="home.php"><img src="img/home.png" alt="home"></a></li>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="employees.php"><img src="img/employee.png"></a></li> <?php } ?>
  		<?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="customers.php"><img src="img/customer.png" alt="Customers"></a></li> <?php } ?>
      <?php if($role == "admin" || $role == "manager") {?> <li class="navitem"><a href="units.php"><img src="img/unit.png" alt="Unit"></a></li> <?php } ?>
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

      <button type="button" class="newbutton" data-toggle="modal" data-target="#myModal">Open Small Modal</button>
      
      <div id="calendar_div">
        <?php echo getCalender(); ?>
      </div>

    </div>

<!--add item modal-->
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Add new calender item</h4>
        </div>
        <div class="modal-body">
        <form action="includes/additem.inc.php" method="POST">
        <input type="text" name="title" class="addform">
        <input type="date" name="date" class="addform">
        <?php if($role == "manager" || $role == "admin") { ?>
          <select name="employee" class="addform">
          	<option value="">Select...</option>
            	<?php
              		//get jobtitle and jobid form database
                  if($role == "manager") {
              		$resultaat = pg_query_params($conn, "SELECT employeeid, firstname, lastname FROM employees WHERE UnitID = $1",array($unitamount));
                  } else {$resultaat = pg_query($conn, "SELECT employeeid, firstname, lastname FROM employees");}
                  if (!$resultaat) {
                		// error message
                		echo "An error occurred.\n";
                			exit;
              		}
                	// dispaly result in dropdown
              		while ($row = pg_fetch_row($resultaat)) {
                		echo '<option value="'.$row[0].'">'.$row[1]." ".$row[2].'</option>';
              		}
            	?>
        	</select>
        <?php } ?>
        <input type="submit" name="submit" value="add" class="newbutton">
      </form>
        </div>
      </div>
    </div>
  </div>
    
<!--edit modal-->
<div id="editmodal" class="editmodal">
      <div class="edit-modal-content">
      <span class="closeedit">&times;</span>
          <?php 
            $qry = pg_query_params($conn, "SELECT * FROM calender WHERE id = $1",array($id));
            $result = pg_fetch_array($qry);
          ?>
          <form action="includes/calenderhelper.inc.php" method="POST">
                <input type="text" name="id" value="<?php echo $id ?>" hidden>
                <input type="text" name="title" value="<?php echo $result['title']; ?>" class="addform">
                <input type="date" name="date" value="<?php echo $result['date']; ?>" class="addform"> 
                <input type="submit" name="submit" value="Save" class="newbutton">
                <input type="submit" name="delete" value="delete" class="newbutton" style="backgroundcolor: red;">
          </form>
      </div>
    </div>
</body>
</html>

<script>
var modal = document.getElementById("assignemployee");
var btn = document.getElementById("additem");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

var modal = document.getElementById("assignemployee");
var span2 = document.getElementsByClassName("closeedit")[0];
var id = <?php echo $id ?>;

if(id == 'undefined'){
  //Noting here
} else {
  editmodal.style.display = "block";
}

span2.onclick = function() {
  editmodal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    editmodal.style.display = "none";
  }
}
</script>