<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Lasterhöhle"); 
output("`)`c`bLasterhöhle`b`c`6"); 
addnav("Zurück");
addnav("Zurück zu den Tunneln","essos_tunnel.php");
addnav("Zurück zum Vergnügungsviertel","essos_vergnuegungsviertel.php");
addnav("Zurück zum Marktplatz","essos_village.php");


if ($_GET[op]==""){
output("`n`n");
output ("`k`c`nEgal was du als dein bevorzugtes Laster ansiehst - Alkohol, Drogen oder was auch immer - hier wirst du fündig werden. Oder vielleicht willst du für wenig Geld jemanden auf dem Weg räumen lassen? Sieh dich einfach hier um. Aber sei vorsichtig - jeder den du hier siehst könnte bereits deinen Namen kennen.`c");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_lasterhoehle");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");





}

page_footer(); 
?> 