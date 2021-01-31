<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
	<link rel="stylesheet" type="text/css" href="css/login.css?<?php echo time(); ?>">
	<meta charset="UTF-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1.0">
  	<meta http-equiv="X-UA-Compatible" content="ie=edge">
  	<link rel="preconnect" href="https://fonts.gstatic.com">
  	<link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>

	<?php
		if (isset($_GET["error"])) {
			if ($_GET["error"] == "emptyinput") {
				echo "<div class=\"alert\">
          				<strong>Error!</strong> NOT all forms are filled in.
      				</div>";
			}
			else if ($_GET["error"] == "wronglogin") {
				echo "<div class=\"alert\">
          				<strong>Error!</strong>email and password don't match.
      				</div>";
			}
		}
	?>

  <form action="includes/login.inc.php" method="POST" class="container">
    <h1 style="color: #FEAD68;">Login</h1><br>

    <label for="email" class="email"><b>Email</b></label>
    <input type="text" placeholder="Enter Email" name="email">

    <label for="psw"><b>Password</b></label>
    <input type="password" placeholder="Enter Password" name="pwd">

    <button type="submit" name="submit" class="btn">Login</button>
  </form>

</body>
</html>
