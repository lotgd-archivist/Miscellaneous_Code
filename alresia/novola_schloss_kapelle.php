<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Kapelle"); 
output("`)`c`bDie Kapelle`b`c`6"); 




addnav("Zurück");
addnav("Zurück zum Eingangsbereich","nuvola_schloss_vestibuel.php");
addnav("Zurück zum Regierungsviertel","nuvola_regierungsviertel.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`c`nDie Kapelle ist ein eher kleiner, nüchterner Bau direkt an der äußeren Verteidigungsmauer, und enthält wenig mehr als einige Bänke und einen Altar. Früher einmal befand sie sich im Schloss selbst und war geschmückt mit goldenen Statuen sämtlicher Götter, aber diese wurden schon vor langer Zeit verkauft um mit dem Geld eine Stadtsanierung für Essos zu finanzieren - zu der es dann aber nie gekommen ist. `c
");  
                  
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_kapelle");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");



}

page_footer(); 
?> 