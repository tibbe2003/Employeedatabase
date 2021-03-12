<?php
session_start();
include_once('dbh.inc.php');
require_once('functions.inc.php');

if (empty($_SESSION["userid"])){
    header("location: /home.php");
}

$id = $_GET['employeeid'];
$email = $_SESSION['employeeemail'];
$pwd = "Welkom01";

//jobid ophalen voor volgende query
$jobidqry = pg_query_params($conn,"SELECT jobid FROM employees WHERE employeeid = $1",array(intval($id)));
$jobiddata = pg_fetch_array($jobidqry);
$jobid = $jobiddata['jobid'];

 $roleqry = pg_query_params($conn,"SELECT employees.employeeid, jobxrole.accesslevel
                        FROM employees, jobxrole, jobtitles
                        WHERE employeeid = $1 AND jobxrole.jobid = $2 AND jobtitles.jobid = $2",array(intval($id),intval($jobid)));
$roledata = pg_fetch_array($roleqry);
$role = $roledata['accesslevel'];

createuser($conn,$id,$email,$pwd,$role);


