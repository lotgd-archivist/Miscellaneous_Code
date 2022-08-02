<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Büro des Hauptmanns"); 
output("`)`c`bDas Büro des Hauptmanns`b`c`k"); 


addnav("Zurück zur Stadt","village.php");
addnav("Zum Hauptraum","nuvola_stadtwache.php");
addnav("Zur Kaserne","nuvola_stadtwache_kaserne.php");
addnav("Zum Verlies","nuvola_stadtwache_verlies.php");

if ($_GET[op]==""){
output("`n`n"); 
output("`c`nGanz am Ende des Raumes findest du das Büro des Hauptmannes der Wache.`n
Direkt in der Mitte steht ein großer Schreibtisch mit einem gepolsterten Stuhl mit hoher Lehne.`n`c"); 
output("`c`nAn einer Wand steht ein deckenhohes Regal, welches mit Papieren, Akten und Büchern derart vollgestopft ist, dass sich alles, was dort keinen Platz mehr finden konnten auf dem Boden und dem Schreibtisch verteilt.`c`n"); 
output("`n`cEin gemütliches Feuer prasselt in dem kleinen Kamin und füllt den Raum mit einer angenehmen Wärme. Auf einem Holztischchen stehen stets eine gefüllte Wasserkaraffe und frische Gläser. Drei Sessel laden zu einem kurzen Verweilen und dem ein oder anderen Geständnis ein.`c`n"); 
output("`n`n"); 
output("`n`n"); 
output("`n`n");
output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_stadtwache_buero");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 