<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Wohnviertel"); 
output("`)`c`bDas Wohnviertel`b`c`6"); 

if (@file_exists("houses.php")) addnav("Zu den Häusern","houses.php?location=2");

addnav("Zurück");
addnav("Zurück zum Marktplatz","essos_village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`& Dies ist ein Platzhalter.`n
");  
                  
output("`n`n");



output("`n`n`%`mIn der Nähe reden einige Anwohner:`n");
viewcommentary("wohnviertelessos","Hinzufügen",25);





output("`n`n`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");



}

page_footer(); 
?> 