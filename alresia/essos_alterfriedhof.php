<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Essos – Die Unterstadt"); 
output("`)`c`bAlter Friedhof`b`c`6"); 
addnav("Zurück zum Marktplatz","essos_village.php");


if ($_GET[op]==""){
output("`n`n");
output ("`k`c`nHier, auf einem ummauerten Bereich der früher einmal vor den Toren der Stadt gelegen hat, liegen all diejenigen begraben, die zu Lebzeiten nicht den Namen oder das nötige Kleingeld hatten, sich einen Platz auf dem prächtigen Hauptfriedhof zu sichern. Heute liegt der alte Friedhof mitten im Stadtviertel Essos, und ist dort einer der wenigen Plätze, wo üppiges Grün offen unter freiem Himmel wächst, weswegen viele Bürger das Gelände für Spaziergänge oder sogar Picknicks nutzen.`c
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_alterfriedhof");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");





}

page_footer(); 
?> 