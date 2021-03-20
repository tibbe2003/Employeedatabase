<?php

include_once("dbh.inc.php");

function emptyinputsignup($email,$pwd,$pwdrepeat) {
	$result;
	if (empty($email) || empty($pwd) || empty($pwdrepeat)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function invalidemail($email) {
	$result;
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function pwdmatch($pwd, $pwdrepeat) {
	$result;
	if ($pwd !== $pwdrepeat) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

//haalt user op als die bestaat
function emailexists($conn, $email) {
	$qry = pg_query_params($conn, "SELECT * FROM users WHERE usersemail = $1",array($email));

	if ($row = pg_fetch_assoc($qry)) {
		return $row; //kan ook true terug geveb
	}
	else {
		$result = false;
		return $result;
	}

	pg_close($conn);
}

function createuser($conn,$id,$email,$pwd,$role) {
	$hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);

	$qry = pg_query_params($conn, "INSERT INTO users (usersid, usersemail, userspwd, accesslevel) VALUES ($1, $2, $3, $4)",array(intval($id),$email,$hashedpwd,$role));
	pg_close($conn);
	header("location:/employees.php?error=none");
	exit();

}

function emptyinputlogin($email,$pwd) {
	$result;
	if (empty($email) || empty($pwd)) {
		$result = true;
	}
	else {
		$result = false;
	}
	return $result;
}

function loginuser($conn,$email,$pwd) {
	$emailexists = emailexists($conn, $email);

	if ($emailexists === false) {
		header("location:/login.php?error=error1");
		exit();
	}

	$pwdhashed = $emailexists["userspwd"];
	$checkpwd = password_verify($pwd, $pwdhashed);

	if ($checkpwd === false) {
		header("location:/login.php?error=error2");
		exit();
	}
	else if ($checkpwd === true) {
		session_start();
		$_SESSION["useremail"] = $emailexists["usersemail"];
		$_SESSION["userid"] = $emailexists["usersid"];
		$_SESSION["role"] = $emailexists["accesslevel"];

		$useridtologindetails = pg_query_params($conn, "UPDATE users SET lastactivity = CURRENT_TIMESTAMP WHERE usersid = $1",array($emailexists["usersid"]));
		header("location:/home.php");
		exit();
	}
}

function checkpwd($conn,$pwd) {
	$qry = pg_query_params($conn, "SELECT * FROM users WHERE userspwd = $1",array($pwd));

	if ($row = pg_fetch_assoc($qry)) {
		return $row; //kan ook true terug geveb
	}
	else {
		$result = false;
		return $result;
	}

	pg_close($qry);

	$pwdhashed = $row["userspwd"];
	$checkpwd = password_verify($pwd, $pwdhashed);

	if ($checkpwd === false) {
		header("location:/settings.php?error=wrongpassword");
		exit();
	}
	else if ($checkpwd === true) {
		header("location:/settings.php?error=none");
		exit();
	}
}

function pwdreset($conn,$pwd) {
	$hashedpwd = password_hash($pwd, PASSWORD_DEFAULT);

	session_start();
	$email = $_SESSION['useremail'];

	$qry = pg_query_params($conn, "UPDATE users SET userspwd=$1 where usersemail = $2",array($hashedpwd,$email));

}

function pwdresetconfirm() {
		session_start();
		$to = $_SESSION['useremail'];
    $subject = "Oranet account";
    $message = "Hi,
Your password is succesfully changed.
If you do not recognise this action, please reset you password immediatly!
www.thijmenbrand.nl/login.php";

    $headers = "From: oranet@thijmenbrand.nl";

            // Send the email
    mail($to,$subject,$message);
		header("location:/settings.php?error=none");
		exit();
}

function fetch_user_last_activity($userid,$conn) {
	date_default_timezone_set('Europe/Amsterdam');
	$query = pg_query_params($conn, "SELECT lastactivity FROM users WHERE usersid = $1 ORDER BY lastactivity DESC LIMIT 1",array(intval($userid)));
	$result = pg_fetch_array($query);

	 $stringdate = $result['lastactivity'];
	 
	 $date = strtotime($stringdate);
	 $newdate = date('Y-m-d H:i:s', $date);
	 return $newdate;
}

function fetch_user_chat_history($from_user_id, $to_user_id, $conn)
{
	$query = pg_query_params($conn, "SELECT * FROM chat_message
							WHERE (from_user_id = $1 AND to_user_id = $2)
							OR (from_user_id = $2 AND to_user_id = $1) ORDER BY timestamp DESC",array(intval($from_user_id), intval($to_user_id)));

	$output = '<ul class="list-unstyled">';
	while($row = pg_fetch_array($query,NULL, PGSQL_ASSOC))
	{
	 $user_name = '';
	 $dynamic_background = '';
	 $chat_message = '';
	 if($row["from_user_id"] == $from_user_id)
	 {
		$dynamic_background = 'background-color:#FFDDC1;';
	  if($row["status"] > 1) {
		$chat_message = '<em>This message has been removed</em>';
		$user_name = '<b class="text-success">You</b>';
	  } else {
		$chat_message = $row['chat_message'];
		$user_name = '<button type="button" class="remove_chat" id="'.$row['chat_message_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
	}


	 } else {
		$dynamic_background = 'background-color:#BADDFF;';
		 if($row["status"] > 1) 
		 {
			$chat_message = '<em>This message has been removed</em>';
		 } else {
			 $chat_message = $row["chat_message"];
		 }
		 $user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $conn).'</b>';
	 }
	 $output .= '
	 <li style="border-bottom:1px dotted; list-style-type: none; #ccc;padding-top:8px; padding-left:8px; padding-right:8px;'.$dynamic_background.'">
	  <p>'.$user_name.' - '.$chat_message.'
	   <div align="right">
		- <small><em>'.$row['timestamp'].'</em></small>
	   </div>
	  </p>
	 </li>
	 ';
	}
	$output .= '</ul>';
	return $output;
}

function get_user_name($user_id, $conn)
{
	$query = pg_query_params($conn, "SELECT firstname FROM employees WHERE employeeid = $1",array(intval($user_id)));

	while($row = pg_fetch_array($query,NULL, PGSQL_ASSOC))
	{
		return $row['firstname'];
	}
}

function count_unseen_message($from_user_id, $to_user_id, $conn)
{
	$query = pg_query_params($conn, "SELECT COUNT(*) AS amount FROM chat_message
							WHERE from_user_id = $1
							AND to_user_id = $2
							AND status = 1",array($from_user_id,$to_user_id));
	
	$count = pg_fetch_array($query);
	if($count > 0)
	{
		return $output = '<span class="messageamount">'.$count['amount'].'</span>';
	}
	return $output = "";
}


