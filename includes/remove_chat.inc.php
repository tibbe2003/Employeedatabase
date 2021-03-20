<?php

//remove_chat.php

include_once('dbh.inc.php');

if(isset($_POST["chat_message_id"]))
{
 $query = pg_query_params($conn, " UPDATE chat_message SET status = 2 WHERE chat_message_id = $1",array($_POST['chat_message_id']));
}