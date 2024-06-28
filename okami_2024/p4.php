
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Herbstpavillon";
page_header("Herbstpavillon");

output("`gDu betritst einen Pavillon in verschiedenen braun Tönen.`n
Eine Steintreppe führt in die Mitte des Pavillon zu den Bänken.`n
Welche im Halbkreis zur geschloßenen Seite des Gebilde stehen.`n
Der Pavillon ist umrunden von verschienden farbenen Portulak-Röschen.`n
Die Innenseite ist geschmückt mit Zinnien, Cosmea und Fuchsschwänzen.`n
Die Bänke sind verziert mit Spinnen-, Stutenden- und Kornblumen.`n
Man sieht auf sofort das der Pavillon den Herbst darstellen soll.");
output("`n`n`gMit anderen flüstern:`n");
viewcommentary("p4","flüstern",15);

addnav("Pavillon");
addnav("`&Frühlingspavillon`0","p2.php");
addnav("`rSommerpavillon`0","p3.php");
addnav("`vWinterpavillon`0","p5.php");
addnav("`tTeich`0","p6.php");
addnav("`8Kirche`0","kir.php");
addnav("Zurück");
addnav("zurück","p1.php");
page_footer();
?>

