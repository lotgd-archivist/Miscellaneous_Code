
<?php
require_once "common.php";
addcommentary();
checkday();

page_header("Der Spielplatz");
$session['user']['standort']="Spielplatz";

output("`b`c`tDer Spielplatz`c`b`n");

output("`n`tDer Ort, den du jetzt betrittst, überrascht dich doch: du befindest dich tatsächlich auf einem Spielplatz!
Viele Spielgeräte ziehen deinen Blick an, eine Rutsche, ein Sandkasten, ein Klettergrüst aus sorgsam zusammengefügten
Holzstangen und etliches mehr.  Am Rand stehen einige Bänke für die Eltern, die nicht mehr so viel Energie haben, wie
die Kinder.`nMit einem anerkennenden Lächeln für die Erbauer Alvions betrittst du diesen Platz und schaust den
spielenden Kindern zu.`0`n`n");

viewcommentary("spielplatz","Hinzufügen",25,"sagt",1,1);

addnav("Z?Zurück","wohnviertel.php");
addnav("D?Zum Dorfplatz","village.php");
page_footer();


