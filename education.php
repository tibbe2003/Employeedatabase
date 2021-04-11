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
  <link href='css/education.css?<?php echo time(); ?>' rel='stylesheet'></link>
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
        <div class="tekst">
        <h1>Informatiemodellering</h1>
        <img class="image" src="img/education.jpeg" alt="informatiemodelering">

        <h1>Database paradigma’s</h1>
        <p><b>De kandidaat kan naast het relationele paradigma ten minste 
        één ander databaseparadigma beschrijven en kan voor 
        een concrete toepassing de geschiktheid van de betreffende paradigma's afwegen.</b>
        <br>
        Ik gebruik een relationele database.
        <br>
        Daarnaast zijn er nog een paar andere zoals graph-orientated databases, 
        document-orientated databases en search-engine databases. 
        Allemaal hebben ze zo hun voor-/ en nadelen. 
        Een veel gebruikt paradigma is de document-georiënteerde database. 
        Dit houdt in dat data wordt opgeslagen in documenten, die worden onderverdeeld door de KEYS binnen het document. 
        Je kan er van alles in opslaan en het is makkelijk op te schalen. 
        Het is vooral goed wanneer je veel data wil opslaan waar je later dingen aan toe voegt. 
        Bijvoorbeeld gescande documenten waar later metadata aan toegevoegd kan worden. 
        Ook is het te gebruiken voor een grote chat applicatie. 
        Je kan er namelijk makkelijk miljoenen chats in opslaan. 
        Een relationele database zou hier minder geschikt voor zijn omdat het minder makkelijk op te schalen is.</p>
 
        <h1>Linked data</h1>
        <p><b>De kandidaat kan in een toepassing data uit verschillende databases 
        (databronnen) met elkaar in verband brengen.</b>
        <br>
        Op mijn home pagina heb ik een aandelen grafiek staan. Deze grafiek is via een API van Stockdio gegenereed en komt uit een database van hun. Dit is dus een externe database ik deze heb ik gekoppeld aan mijn eigen informatie.</P>

        <h1>Analyse/ Ontwerp</h1>
        <p><b>De kandidaat kan de relatie tussen ontwerpkeuzes van een interactief digitaal 
        artefact en de verwachte cognitieve, gedragsmatige en affectieve veranderingen
        of ervaringen verklaren.</b>
        <br>
        De kandidaat kan voor een digitaal artefact de gebruikersinteractie 
        vormgeven, de ontwerpbeslissingen verantwoorden en voor een eenvoudige toepassing implementeren. 
        <br>
        In mijn ontwerp heb ik veel gebruik gemaakt van de kleur blauw. 
        Dit zorgt voor een rustgevend effect en zal een prettige omgeving 
        scheppen voor de gebruiker. Daarnaast heb ik op het home menu een aantal 
        verschillende blokken toegevoegd waar de gebruiker in een oogopslag relevante informatie kan opdoen. 
        De navigatiebalk staat aan de linker kant van het scherm om zo veel mogelijk informatie te 
        kunnen weergeven in het main gedeelte van de pagina. Verticale ruimte is in mijn geval van 
        groter belang dan horizontale ruimte. Daarnaast heb ik voor alle tekst of iconen wat op een
        blauwe ondergrond staat gekozen voor een witte kleur. Dit zorgt voor leesbaarheid en contrast. 
        </p>
        </div>
    </div>
</body>
</html>