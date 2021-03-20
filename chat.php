<?php
include_once("includes/dbh.inc.php");
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
  <link href='css/navbar.css?<?php echo time(); ?>' rel='stylesheet'></link>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="preconnect" href="https://fonts.gstatic.com">
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
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

      <div class="users" id="user_details"></div>
      <div id="user_model_details"></div>
 
    </div>
</body>
</html>

<script>  
$(document).ready(function(){

fetch_user();

setInterval(function(){
  update_chat_history_data();
},1000);

setInterval(function(){
 update_last_activity();
 fetch_user();
}, 5000);


function fetch_user()
{
 $.ajax({
  url:"includes/fetch_user.inc.php",
  method:"POST",
  success:function(data){
   $('#user_details').html(data);
  }
 })
}

function update_last_activity()
 {
  $.ajax({
   url:"includes/update_last_activity.inc.php",
   success:function()
   {

   }
  })
 }

function make_chat_dialog_box(to_user_id, to_user_name)
{
  var box = '<div id="user_dialog_'+to_user_id+'" class="user_dialog">';
  box += '<h3 class="chatmate">You are chatting with '+to_user_name+'</h3>';
  box += '<div style="height:400px; border:1px solid #ccc; overflow-y: scroll; margin-bottom:24px; padding:16px;" class="chat_history" data-touserid="'+to_user_id+'" id="chat_history_'+to_user_id+'">';
  box += '</div>';
  box += '<div class="form-group">';
  box += '<textarea name="chat_message_'+to_user_id+'" id="chat_message_'+to_user_id+'" class="form-control" placeholder="Type message"></textarea>';
  box += '<button type="button" name="send_chat" id="'+to_user_id+'" class="send_chat">Send</button>';
  box += '</div></div>';
  $('#user_model_details').html(box);
}

$(document).on('click', '.start_chat', function(){
  var to_user_id = $(this).data('touserid');
  var to_user_name = $(this).data('tousername');
  make_chat_dialog_box(to_user_id, to_user_name);
  fetch_user_chat_history(to_user_id);
  update_count(to_user_id);
 });

 $(document).on('click', '.send_chat', function(){
  var to_user_id = $(this).attr('id');
  var chat_message = $('#chat_message_'+to_user_id).val();
  $.ajax({
   url:"includes/insert_chat.inc.php",
   method:"POST",
   data:{to_user_id:to_user_id, chat_message:chat_message},
   success:function(data)
   {
    $('#chat_message_'+to_user_id).val('');
    $('#chat_history_'+to_user_id).html(data);
   }
  })
 });

 function fetch_user_chat_history(to_user_id)
 {
  $.ajax({
   url:"includes/fetch_user_chat_history.inc.php",
   method:"POST",
   data:{to_user_id:to_user_id},
   success:function(data){
    $('#chat_history_'+to_user_id).html(data);
   }
  })
 }

 function update_chat_history_data()
 {
  $('.chat_history').each(function(){
   var to_user_id = $(this).data('touserid');
   fetch_user_chat_history(to_user_id);
  });
 }

 function update_count(to_user_id)
 {
   $.ajax({
     url:"includes/update_count.inc.php",
     method:"POST",
     data:{to_user_id:to_user_id}
   })
 }

 $(document).on('click', '.remove_chat', function(){
  var chat_message_id = $(this).attr('id');
  if(confirm("Are you sure you want to remove this chat?"))
  {
   $.ajax({
    url:"includes/remove_chat.inc.php",
    method:"POST",
    data:{chat_message_id:chat_message_id},
    success:function(data)
    {
     update_chat_history_data();
    }
   })
  }
 });

}); 
</script>