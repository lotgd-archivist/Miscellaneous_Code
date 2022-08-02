<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Ordamant – Das Wasserviertel"); 
output("`)`c`bDie Felsenbucht`b`c`6"); 

addnav("Zum Strand","ordamant_strand.php");
addnav("Zum Sternenherz","ordamant_village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`n`cEin kleiner, etwas versteckter Teil des Strands. Hier hat das Meer den Felsen unterhöhlt und eine Art Höhle geschaffen, von oben geschützt und an beiden Seiten von Felsausläufern 
`nbegrenzt. An einer Stelle gibt es sogar einen Wasserfall, der zehn Meter vom Felsen ins Meer stürzt. Das Wasser in der Bucht ist besonders warm, und der Schwarm winziger schillernder Fische ist ein hübscher Anblick.  
  `c
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("ordamant_felsenbucht");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 