<?php

//Script by Horus
//Script in viele Dateien aufgeteilt für besseren
//Überblick. Dieser Text MUSS stehen bleiben, der Rest
//darf umgeändert werden.

require_once "common.php";
addcommentary();
page_header("Der Turm der Elemente");
output("`$`c`bTurm der Elemente`c`bDu betrittst einen großen Turm. Das ist der Turm der Elemente. Hier treffen sich die mächtigsten Krieger aus Barquox um gegen die 4 Meister der Elemente zu bestehen. An der Wand gegenüber von dir sind 4 Türen. Auf jeder der 4 Türen ist ein Zeichen eingeritzt. Ein Stückchen vor den Türen steht ein Mann in einem Gewand der dich die ganze Zeit beobachtet. In der Mitte des Raumes gibt es ein kleines rundes Feld das leuchtet. Damit weißt du leider noch nichts anzufangen. Was tust du?`n`n`9In der Nähe unterhalten sich ein paar Krieger:`n");
viewcommentary("turm","Hinzufügen",15);
addnav("Turm");
addnav("Zu dem Mann gehen","man.php");
if($session[user][orden]==4){
addnav("`^Zum Turm der Götter teleportieren","goetter.php");
}
addnav("Wege");
addnav("Zurück zum Dorf","zylyma.php");

page_footer();
?> 