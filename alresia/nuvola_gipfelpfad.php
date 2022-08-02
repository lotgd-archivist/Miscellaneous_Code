<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Der Gipfelpfad"); 
output("`)`c`bDer Gipfelpfad`b`c`6"); 



addnav("Zur Sternenwarte","nuvola_sternenwarte.php");
addnav("Zum Gipfel","");
addnav("Zum alten Wachturm","nuvola_wachturm.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`nDer schmale Wanderpfad führt am Schloss vorbei und ermöglicht den Zugang zur Sternenwarte, sowie zur Ruine des alten Wachturms. Besonders Hartnäckige gelangen außerdem über den holprigen Weg zum Gipfel hinauf, von wo aus man die beste Aussicht der ganzen Stadt genießen kann.

");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("gipfelpfad");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 