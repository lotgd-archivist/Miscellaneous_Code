<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Gemäldesaal"); 
output("`)`c`bDer Gemäldesaal `b`c`6"); 


addnav("Zurück");
addnav("Zurück zum Eingangsbereich","nuvola_schloss_vestibuel.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`c`nIn dieser langen Halle ist beinahe jedes freie Fleckchen Wand mit Gemälden und Wandteppichen bedeckt, sogar die gewölbte Decke ziert ein gewaltiges Fresko der Götter. 
`nAnsonsten findet sich hier kein Mobiliar, und es ist klar, dass dies wenig mehr als ein glorreicher Korridor zwischen zwei wichtigeren Räumen dient   `c  ");  
                  
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_schloss_gemälde");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 