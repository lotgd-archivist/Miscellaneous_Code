<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Sternenwarte"); 
output("`)`c`bDie Sternenwarte`b`c`6"); 


addnav("Geh hinein","nuvola_sternenwarte2.php");
addnav("Zurück zum Gipfelpfad","nuvola_gipfelpfad.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`nAuf dem Gipfel des Berges befindet sich eines der bekanntesten Gebäude von Alresia: die Sternwarte.
`nVon außen mag sie sich nicht wirklich vom Rest der Stadt abheben, aber als du genauer hinsiehst, erkennst du, dass dort, wo eigentlich ein Dach sein sollte, 
`neinfach nur gaffende Leere ist. Man könnte glauben, ein Unwetter hätte es fortgerissen. Dafür sieht es aber einfach zu gewollt und zu perfekt aus. 
`nUnd tatsächlich: Eine fein säuberlich polierte, goldene Tafel informiert dich darüber, dass es absichtlich kein Dach gibt und dass ein besonderer Zauber das 
`nInnere des Gebäudes vor äußeren Witterungsbedingungen schützt.

");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("sternenwarte");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 