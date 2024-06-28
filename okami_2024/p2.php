
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Frühlingspavillon";
page_header("Frühlingspavillon");

output("`&Du betritst einen Pavillon in verschiedenen gelb Tönen.`n
Eine Steintreppe führt in die Mitte des Pavillon zu den Bänken.`n
Welche im Halbkreis zur geschloßenen Seite des Gebilde stehen.`n
Der Pavillon ist umrunden von verschienden farbenen Ranukeln.`n
Die Innenseite ist geschmückt mit Hyazinthen, Primeln und Tulpen.`n
Die Bänke sind verziert mit Krokuse, Narzissen und Netzblatt-Irise.`n
Man sieht auf sofort das der Pavillon den Frühling darstellen soll.");
output("`n`n`gMit anderen flüstern:`n");
viewcommentary("p2","flüstern",15);

addnav("Pavillon");
addnav("`rSommerpavillon`0","p3.php");
addnav("`gHerbstpavillon`0","p4.php");
addnav("`vWinterpavillon`0","p5.php");
addnav("`tTeich`0","p6.php");
addnav("`8Kirche`0","kir.php");
addnav("Zurück");
addnav("zurück","p1.php");
page_footer();
?>

