<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Der Hinterhof`"); 
output("`)`c`bDer Hinterhof`b`c`6"); 
addnav("Zugang zu den Tunneln","essos_tunnel.php");
addnav("Zurück zum Marktplatz","essos_village.php");


if ($_GET[op]==""){
output("`n`n");
output ("`k`c`nEin staubiger, hitzeflirrender Hinterhof, in dem gestapelte Kisten ein regelrechtes Labyrinth bilden. Ob es sich wohl lohnt, eine der Kisten zu öffnen und hinein zu sehen? Gerüchteweise soll hier einer der erfolgreichsten Schmuggler der Stadt seine Waren zwischenlagern. Aber das sind wohl wirklich nur Gerüchte, denn ganz sicher wird man kein erfolgreicher Schmuggler indem man seine Ware unbewacht irgendwo herumstehen lässt.
`c");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_hinterhof");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");





}

page_footer(); 
?> 