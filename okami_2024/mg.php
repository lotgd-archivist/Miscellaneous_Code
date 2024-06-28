
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Markt";
page_header("Marktgasse");

output("`c`7Hier hat der Krieg einige Schäden hinterlassen und viele Läden haben Geschlossen.`n
Vielleicht wird es eines Tages wieder besser und die Läden kommen zurück.`c`n`n");
output("`n`n`7Mit anderen reden:`n");
viewcommentary("mg","reden",5);

addnav("Händler");
if (getsetting("vendor",0)==1) addnav("Wanderhändler","vendor.php");
addnav("Edelsteinhandel","em.php");
addnav("Geschenkeladen","newgiftshop.php");
addnav("Basar");
addnav("Schmankerlstand","ebasar.php");
addnav("Schankstand","tbasar.php");

addnav("Beauty");

addnav("Weg");
addnav("zurück","village.php");
addnav("Kriegergasse","kg.php");
addnav("Zur Schenke Brennenden Wolf","inn.php");
page_footer();
?>

