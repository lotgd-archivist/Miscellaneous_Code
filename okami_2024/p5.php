
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Winterpavillon";
page_header("Winterpavillon");

output("`vDu betritst einen Pavillon in verschiedenen blau Tönen.`n
Eine Steintreppe führt in die Mitte des Pavillon zu den Bänken.`n
Welche im Halbkreis zur geschloßenen Seite des Gebilde stehen.`n
Der Pavillon ist umrunden von weißen und gefärbten Christrosen.`n
Die Innenseite ist geschmückt mit Stiefmütterchen und Hornveilchen.`n
Die Bänke sind alle verziert mit dem immer grüne Spindelstrauch.`n
Man sieht auf sofort das der Pavillon den Winter darstellen soll.");
output("`n`n`gMit anderen flüstern:`n");
viewcommentary("p5","flüstern",15);

addnav("Pavillon");
addnav("`&Frühlingspavillon`0","p2.php");
addnav("`rSommerpavillon`0","p3.php");
addnav("`gHerbstpavillon`0","p4.php");
addnav("`tTeich`0","p6.php");
addnav("`8Tempe`0","tempel.php");
addnav("Zurück");
addnav("zurück","p1.php");
page_footer();
?>

