
<?php
require_once "common.php";
addcommentary();
//$session[user][standort]="Wolfsreal,";
page_header("Wolfsrealm");
output("`cDu kommst nach langer reise an einen kleinen Ort an und siehst dich um.`c`n`n");
output("`n`n`_Mit anderen reden:`n");
viewcommentary("WR","reden",5);
addnav("zurück",".php");
page_footer();
?>

