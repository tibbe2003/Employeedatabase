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
	//getting last activity
	$query = pg_query_params($conn, "SELECT lastactivity FROM users WHERE usersid = $1 ORDER BY lastactivity DESC LIMIT 1",array(intval($userid)));
	$result = pg_fetch_array($query);
	$stringdate = $result['lastactivity'];
	
	//parsing date format into right date format
	$date = strtotime($stringdate);
	$newdate = date('Y-m-d H:i:s', $date);
	return $newdate;
}

function fetch_user_chat_history($from_user_id, $to_user_id, $conn)
{
	//getting data from chatmessages that where sent by logged in users and for other userid
	$query = pg_query_params($conn, "SELECT * FROM chat_message
							WHERE (from_user_id = $1 AND to_user_id = $2)
							OR (from_user_id = $2 AND to_user_id = $1) ORDER BY timestamp DESC",array(intval($from_user_id), intval($to_user_id)));
	$output = '<ul class="list-unstyled" style="margin-left:-40px;">';
	while($row = pg_fetch_array($query,NULL, PGSQL_ASSOC))
	{
		//defining bacis variable
		$user_name = '';
	 	$dynamic_background = '';
	 	$chat_message = '';
	 	$float = 'float:left;';
	 	$margin='margin-left:30px;';
		//if from user id and logged in user is equal
	 	if($row["from_user_id"] == $from_user_id)
	 	{
		 	$userid = $from_user_id;
		 	$margin='margin-right:30px;';
			$float = 'float:right;';
			$dynamic_background = 'background-color:#FFDDC1;';
			//if status is 1 (this will be updated from 0 to 1 if message is deleted)
	  		if($row["status"] > 1) {
				$chat_message = '<em>This message has been removed</em>';
				$user_name = '<b class="text-success">You</b>';
	  		} else {
				$chat_message = $row['chat_message'];
				$user_name = '<button type="button" class="remove_chat" id="'.$row['chat_message_id'].'">x</button>&nbsp;<b class="text-success">You</b>';
			}
		//if userid and logged in user do not match, it must be the other user who sent it
		} else {
			$userid = $to_user_id;
			$dynamic_background = 'background-color:#BADDFF;';
			//if status is deleted
		 	if($row["status"] > 1) 
		 	{
				$chat_message = '<em>This message has been removed</em>';
		 	} else {
			 	$chat_message = $row["chat_message"];
		 	}
		 	$user_name = '<b class="text-danger">'.get_user_name($row['from_user_id'], $conn).'</b>';
	 	}
		//selecting path to profile picture	
		$pf = pg_query_params($conn, "SELECT pf FROM users WHERE usersid = $1",array($userid));
		$fetch = pg_fetch_array($pf);
		$pfname = $fetch['pf'];
		//placing names and messages in listitems. $float is defined right when from userid = logged in user els it will be left
	 	$output .= '
	 	<div style="display:table;width:100%;margin-top:10px;"><li style="min-width: 5%;max-width: 80%; list-style-type: none; #ccc; '. $float.'">
	 		<div style="height:40px;width:40px;'.$float.'">
	 			<img src="../profilepictures/'.$pfname.'" alt="PF" style="height:100%;width:100%;border-radius:50%;">
	 		</div>
	 		<p style="width:100%;'.$margin.'border-radius:10px;padding: 5px 5px 5px 5px;'.$dynamic_background.'">'.$chat_message.'</p>
			<p style="margin-right:20px;margin-top:-15px;'.$float.'"><small><em>'.$row['timestamp'].'</em></small></p>
	 	</li></div>
	 	';
	}
	$output .= '</ul>';

	//after chats have loaded we update open message count to 0
	$query = pg_query_params($conn, "UPDATE chat_message SET status = 0 WHERE to_user_id = $1 AND from_user_id = $2 AND status = 1",array($_SESSION['userid'],$to_user_id));

	return $output;
}

function count_unseen_message($from_user_id, $to_user_id, $conn)
{
	//count every row with status 1 and other matching criteria
	$query = pg_query_params($conn, "SELECT COUNT(*) AS amount FROM chat_message
							WHERE from_user_id = $1
							AND to_user_id = $2
							AND status = 1",array($from_user_id,$to_user_id));
	
	$count = pg_fetch_array($query);
	//if count > 0 it will display the amount
	if($count > 0)
	{
		return $output = '<span class="messageamount">'.$count['amount'].'</span>';
	}
	return $output = "";
}


