<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Nuvola – Das Himmelsviertel"); 
output("`)`c`bDas Innere der Sternenwarte`b`c`6"); 

addnav("Vor die Sternwarte","nuvola_sternenwarte.php");


if ($_GET[op]==""){
output("`n`n");
output ("`(`n
Ein langer Korridor führt die Besucher in den größten Raum, den die Sternwarte zu bieten hat, den Kuppelraum. 
`n„Komischer Name, wo es doch kein Dach gibt…“, kommt es dir, aber du willst eigentlich nicht nachfragen, wie es dazu gekommen ist.
`nHier befinden sich die Teleskope. Fasziniert siehst du dich um und erkennst an den Wänden einige Sternkarten, die wohl die Sternbilder zeigen, die von hier aus zu erkennen sind.
`nAbgesehen davon gibt es auch noch einige Türen und Flure, die wohl dem Personal vorbehalten sind. Was es wohl dahinter zu entdecken gibt?

");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("sternenwarte2");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 