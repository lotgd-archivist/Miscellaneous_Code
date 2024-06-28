
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Sommerpavillon";
page_header("Sommerpavillon");

output("`rDu betritst einen Pavillon in verschiedenen rosa Tönen.`n
Eine Steintreppe führt in die Mitte des Pavillon zu den Bänken.`n
Welche im Halbkreis zur geschloßenen Seite des Gebilde stehen.`n
Der Pavillon ist umrunden von verschienden farbenen Löwenmäulchen.`n
Die Innenseite ist geschmückt mit Sonnenblumen, Stockmalven und Dahlien.`n
Die Bänke sind verziert mit Borretch, Stauden-Phlox und Sonnehut.`n
Man sieht auf sofort das der Pavillon den Sommer darstellen soll.");
output("`n`n`gMit anderen flüstern:`n");
viewcommentary("p3","flüstern",15);

addnav("Pavillon");
addnav("`&Frühlingspavillon`0","p2.php");
addnav("`gHerbstpavillon`0","p4.php");
addnav("`vWinterpavillon`0","p5.php");
addnav("`tTeich`0","p6.php");
addnav("`8Kirche`0","kir.php");
addnav("Zurück");
addnav("zurück","p1.php");
page_footer();
?>

