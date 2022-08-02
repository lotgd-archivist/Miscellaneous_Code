<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Spiegelsalon"); 
output("`)`c`bDer Spiegelsalon `b`c`6"); 



addnav("Zurück");
addnav("Zurück zum Eingangsbereich","nuvola_schloss_vestibuel.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`c`nDer letzte Prunkraum vor dem Thronsaal, hat dieses Zimmer keine Fenster, dafür aber hunderte Spiegel an den Wänden und zahllose Leuchter, deren Licht von den Spiegeln zurückgeworfen wird    `c  ");  
                  
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_schloss_spiegel");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 