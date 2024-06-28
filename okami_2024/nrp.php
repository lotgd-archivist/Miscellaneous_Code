
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Name für die kämpferliste";
page_header("Name des Projektes");
output("`c<img src='images/'>`c",true); 
output("`c<img src='images/' border='0' align=center alt='Beschreibung'>`c",true);
output("`c`c`n`n");
output("`n`n`_Mit anderen ...:`n");
viewcommentary("name","aktion",5);
addnav("zurück",".php");
page_footer();
?>

