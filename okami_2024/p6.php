
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Teich";
page_header("Teich");

output("`c`tMitten in den Pavillons findet sich ein mit Steinen umrandeter Gartenteich.`n
Von Richtung Norden her wird der Teich von einem kleinen Fluss gespeisst.`n
So ist immer genügend frisches Wasser vorhanden so das man hier Fische findet.`n
Richtung Süden ist ein kleiner Holzsteg welcher nur so zun Picknicken einläd.`n
Jetzt fehlt nur noch ein gefüllter Korb und das romantische Picknick wäre perfekt.`n
Geht man Richtung Westen sieht man die kleine Kirche die wohl für Hochzeiten ist.`n
In Richtung Osten kommt man wieder ins Paradies von wo aus man zum Platz findet.`n`c");
output("`n`n`gMit anderen flüstern:`n");
viewcommentary("p6","flüstern",15);

addnav("Pavillon");
addnav("`&Frühlingspavillon`0","p2.php");
addnav("`rSommerpavillon`0","p3.php");
addnav("`gHerbstpavillon`0","p4.php");
addnav("`vWinterpavillon`0","p5.php");
addnav("`8Kirche`0","kir.php");
addnav("Zurück");
addnav("zurück","p1.php");
page_footer();
?>

