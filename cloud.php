<?php
include_once("includes/dbh.inc.php");
session_start();
if(empty($_SESSION['useremail'])) {
   header("Location: login.php");
   die("Redirecting to login.php");
}
$username = $_SESSION['useremail'];
$role = $_SESSION["role"];

    // generating orderby and sort url for table header
    function sortorder($fieldname){
      $sorturl = "?order_by=".$fieldname."&sort=";
      $sorttype = "asc";
      if(isset($_GET['order_by']) && $_GET['order_by'] == $fieldname){
          if(isset($_GET['sort']) && $_GET['sort'] == "asc"){
              $sorttype = "desc";
          }
      }
      $sorturl .= $sorttype;
      return $sorturl;
  }


  ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Chat</title>
  <link href='css/cloud.css?<?php echo time(); ?>' rel='stylesheet'></link>
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
      <div>
      <input type='hidden' id='sort' value='asc'>
      <?php if(isset($_SESSION['result']) != "") {
        echo "<div class=\"alert\">".$_SESSION['result']."
    </div>";
      } ?>
        
      <button id="uploadfile">Upload file</button>

      <table width="100%" id="emp_table" border="0">
        <tr class="tr_header">
            <th style="width:0px;"></td>
            <th></th>
            <th onclick="location.href='<? echo sortorder('name') ?>';" class="filter">Name<img src="img/icons8-sort-26.png" alt=""></th>
            <th onclick="location.href='<? echo sortorder('name') ?>';" class="filter">Date<img src="img/icons8-sort-26.png" alt=""></th>
            <th></th>
        </tr>
        <?php
        $orderby = " ORDER BY fileid desc ";
        if(isset($_GET['order_by']) && isset($_GET['sort'])){
            $orderby = ' order by '.$_GET['order_by'].' '.$_GET['sort'];
        }

        // fetch rows
        $result = pg_query_params($conn, "SELECT * FROM files WHERE userid = $1".$orderby,array($_SESSION['userid']));
        while($fetch = pg_fetch_array($result)){
            $name = $fetch['name'];
            $date = $fetch['date'];
            $fileid = $fetch['fileid'];
            ?>
            <tr>
              <?php if($fetch['favorite'] == 0) {?>
                <td><a href='includes/filefavorite.inc.php?fileid=<?php echo $fileid?>'><img src="img/icons8-star-26.png" alt="delete" class="favorite"></a></td>
              <?php }
              else {?>
                <td><a href='includes/filefavorite.inc.php?fileid=<?php echo $fileid?>'><img src="img/icons8-star-26-2.png" alt="delete" class="favorite"></a></td>
              <?php } 
              $path = pathinfo('../../files/'.$name);
              $extension = $path['extension'];

              switch ($extension) {
                case "php":
                  $output = "img/php.png";
                  break;
                case "docx":
                  $output = "img/docx.png";
                  break;
                case "zip":
                  $output = "img/zip.png";
                  break;
                case "jsx":
                case "js":
                case "json":
                  $output = "img/js.png";
                  break;
                case "html":
                case "htm":
                case "xhtml":
                case "xhtml":
                  $output = "img/html.png";
                  break;
                case "css":
                  $output = "img/css.png";
                  break;
                case "pdf":
                  $output = "img/pdf.png";
                  break;
                case "txt":
                  $output = "img/txt.png";
                  break;
                case "xls":
                  $output = "img/xls.png";
                  break;
                case "ppt":
                case "pptx":
                  $output = "img/ppt.png";
                  break;
                case "png":
                case "jpg":
                case "jpeg":
                case "svg":
                case "jfif":
                case "gif":
                case "webp":
                  $output = "img/img.png";
                  break;               
                case "avi":
                case "webm":
                case "vob":
                case "mov":
                case "mp4":
                case "m4p":
                case "m4v":
                  $output = "img/video.png";
                  break;
                case "jsp":
                case "jspx":
                case "wss":
                case "do":
                case "action":
                  $output = "img/java.png";
                  break;
                case "pl":
                  $output = "img/perl.png";
                  break;
                case "rb":
                case "rhtml":
                  $output = "img/ruby.png";
                  break;
                case "cs":
                  $output = "img/csharp.png";
                  break;
                case "cpp":
                  $output = "img/cplus.png";
                  break;
                case "c":
                  $output = "img/c.png";
                  break;
                case "p":
                  $output = "img/py.png";
                  break;
                default:
                  $output = "img/default.png";
              }
              ?>
              <td class="extension een"><img src="<?php echo $output ?>"></td>
              <td class="extension classtwo"><a href="editfile.php?id=<?php echo $fileid?>" style="text-decoraction:none;color:black;"><?php echo $name; ?></a></td>
              <td><?php echo $date; ?></td>
              <td><img src="img/icons8-eye-26.png" alt="edit file" onclick="openmodal('<?php echo $fileid ?>')" style="cursor=pointer;" class="modify"><img src="img/icons8-edit-file-26.png" alt="edit file" onclick="openmodaltwo('<?php echo $fileid ?>')" style="cursor=pointer;margin-left:10px;" class="modify"><a href='includes/filedownload.inc.php?fileid=<?php echo $fileid?>' style="margin-left:10px;"><img src="img/icons8-download-26.png" alt="Download" class="modify"></a><a href='includes/deletefile.inc.php?fileid=<?php echo $fileid?>' style="margin-left: 10px;"><img src="img/icons8-delete-bin-26.png" alt="delete" class="modify"></a></td>
            </tr>
            <?php
        }
        ?>
    </table>

  </div>

      <!--file upload form-->
      <div id="upload" class="upload">
        <div class="upload-modal-content">
          <span class="closeupload">&times;</span>
          <form action="includes/cloud.inc.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" id="file" class="file">
                <input type="submit" name="submit" value="Upload" class="uploadfile">
          </form>
        </div>
      </div>

      <!--reaf file-->
      <div id="read" class="upload">
        <div class="read-modal-content">
          <span class="readclose">&times;</span>
            <div id="filecontent"></div>
        </div>
      </div>

      <div id="edit" class="edit">
        <div class="edit-modal-content">
          <span class="editclose">&times;</span>
            <div id="form"></div>
        </div>
      </div>
 
    </div>
</body>
</html>

<script>

var modal = document.getElementById("upload");
var btn = document.getElementById("uploadfile");
var span = document.getElementsByClassName("closeupload")[0];
var readmodal = document.getElementById("read");
var readspan = document.getElementsByClassName("readclose")[0];
var editmodal = document.getElementById("edit");
var editspan = document.getElementsByClassName("editclose")[0];

btn.onclick = function() {
  modal.style.display = "block";
}

span.onclick = function() {
  modal.style.display = "none";
}

window.onclick = function(event) {
  if (event.target == modal || event.target == readmodal || event.target == editmodal) {
    modal.style.display = "none";
    readmodal.style.display = "none";
    editmodal.style.display = "none";
  }
}

function openmodal(fileid) {
  readmodal.style.display = "block";
  var id = fileid;
  fetchfile(id);
}

function openmodaltwo(fileid) {
  editmodal.style.display = "block";
  var id = fileid;
  fetchfiletwo(id);
}

readspan.onclick = function() {
  readmodal.style.display = "none";
}

function fetchfiletwo(id)
 {
  $.ajax({
   url:"includes/editfilename.inc.php",
   method:"POST",
   data:{id:id},
   success:function(data){
    $('#form').html(data);
   }
  })
 }

 function fetchfile(id)
 {
  $.ajax({
   url:"includes/fetchfile.inc.php",
   method:"POST",
   data:{id:id},
   success:function(data){
    $('#filecontent').html(data);
   }
  })
 }

</script>