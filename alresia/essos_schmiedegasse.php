<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Essos – Die Unterstadt"); 
output("`)`c`bDie Schmiedegasse`b`c`6"); 


addnav("Dagnas Rüstungen","armor.php");
addnav("Duncans Waffenladen","weapons.php");
addnav("Die Schmiede","essos_schmiede.php");
addnav("Zurück zum Marktplatz","essos_village.php");
if ($_GET[op]==""){
output("`n`n");
output ("`(`n`cKaum befindet man sich in Essos, betritt man schon gleich die Schmiedegasse, nur wenige Gebäude, teils rußgeschwärzt, finden sich dort, doch sie ist gut ausgeleuchtet und breiter als ihr Name annehmen lässt. 
`n`nMehrmals am Tag ruckeln Gefährte hindurch, die Ware anliefern oder bringen, für die Läden dort, in denen man Rüstungen wie Waffen erstehen kann, aber auch für den Schmied, dem man dort bei der Arbeit zusehen oder seine Ware kaufen kann.`c`0");
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_schmiedegasse");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 