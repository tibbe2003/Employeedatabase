<?php

if (isset($_POST['submit'])) {

  require_once('dbh.inc.php');
	require_once('functions.inc.php');

  //ingevulde data ophalen
  $oldpwd = $_POST['oldpwd'];
  $pwd = $_POST['pwd'];
  $pwdrepeat = $_POST['pwdrepeat'];

  //check if the old password is correct
  if (checkpwd($conn,$pwd) !== false) {
    header("location:/settings.php?error=error");
    exit();
  }
  if (pwdmatch($pwd,$pwdrepeat) !== false) {
    header("location:/settings.php?error=error");
    exit();
  }

  //update function aanroepen
  pwdreset($conn,$pwd);
  //mail functie aanroepen
  pwdresetconfirm();
}

else {
  header("location:/settings.php");
  exit();
}
