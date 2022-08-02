<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Nuvola – Das Nobelviertel"); 
output("`)`c`bDas Schloss - Außengelände`b`c`6"); 


addnav("Das Schloss betreten","nuvola_schloss_vestibuel.php");
addnav("Besuch den Schlossgarten","nuvola_schloss_schlossgarten.php");
addnav("Zurück");
addnav("Zurück zum Regierungsviertel","nuvola_regierungsviertel.php");
addnav("Zurück zum Brunnenplatz","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`n`cHalb Märchenschloss, halb Festung, besteht die Anlage vordergründig aus einem äußeren Verteidungswall, in dessen Mauern zur Not die gesamte Stadtbevölkerung Platz findet. 
`nDas eigentliche Schloss, ein Prunkbau der nichts so sehr ähnelt wie einer Zuckerguss-Kreation, ist noch von einer eigenen Wehrmauer umgeben und steht inmitten manikürter Parkanlagen und Gärten.
 `0`c");                   
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_schloss");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 