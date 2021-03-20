<?php

include_once('datavalidation.inc.php');
include_once('dbh.inc.php');


$id = $_POST['id'];

if(isset($_POST['submit'])){
    $title = clean_input($_POST['title']);
    $date = clean_input($_POST['date']);

    $qry = pg_query_params($conn, "UPDATE calender SET title = $1, date = $2 WHERE id = $3",array($title, $date, $id));

    if($qry) {
        header("location:/calender.php");
    }
} else if(isset($_POST['delete'])) {
    $qry = pg_query_params($conn, "DELETE FROM calender WHERE id = $1",array($id));

    if($qry) {
        header("location:/calender.php");
    }
}




<button data-modal-target="#modal" class="addbutton">Add employee</button>
  	<div class="modal" id="modal">
    	<div class="modal-header">
      		<div class="title">Add new employee</div>
     				<button data-close-button class="close-button">&times;</button>
    		</div>
    	<div class="modal-body">
    </div>
  	<div id="overlay"></div>