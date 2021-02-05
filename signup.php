<!DOCTYPE html>
<html>
<head>
	<title>Signup</title>
	<link rel="stylesheet" type="text/css" href="css/signup.css?<?php echo time(); ?>">
</head>
<body>

	<?php
		if (isset($_GET["error"])) {
			if ($_GET["error"] == "emptyinput") {
				echo "<div class=\"alert\">
          				<strong>Error!</strong>NOT all fields are filled!
      				</div>";
			}
			else if ($_GET["error"] == "invalidemail") {
				echo "<div class=\"alert\">
          				<strong>Error!</strong>Invalid email!
      				</div>";
			}
			else if ($_GET["error"] == "pwdmatch") {
				echo "<div class=\"alert\">
          				<strong>Error!</strong>passwords do not match!
      				</div>";
			}
			else if ($_GET["error"] == "emailtaken") {
				echo "<div class=\"alert\">
          				<strong>Error!</strong>That email already exists
      				</div>";
			}
		}
	?>

		<form action="includes/signup.inc.php" method="POST" class="container">
			<h1 style="color: #FEAD68;">Signup</h1><br>

			<label for="name">Full name</label>
			<input type="text" name="name" placeholder="Full name...">

			<label for="email">Email</label>
			<input type="text" name="email" placeholder="Email...">

			<label for="pwd">Password</label>
			<input type="password" name="pwd" placeholder="Password...">

			<label for="pwdrepeat">Password</label>
			<input type="password" name="pwdrepeat" placeholder="Repeat password">
			<button type="submit" name="submit" class="btn">Sign Up</button>
			<button type="button" name="button" class="btn" onclick="window.location.href='login.php'" style="margin-top: 4%;">Already an acount? login</button>
		</form>

</body>
</html>
