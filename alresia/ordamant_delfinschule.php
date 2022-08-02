<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Delfinschule"); 
output("`)`c`bDie Delfinschule`b`c`6"); 

addnav("Zum Sternenherz","ordamant_village.php");
if ($_GET[op]==""){
output("`n`n");
output ("`(`n`cSchwimmen mit Delfinen! Das wolltest du immer schon einmal, und in den Gewässern um die schwimmende, schimmernde Stadt herum hast du die Möglichkeit. Die hier lebenden Tümmler 
`nsind weder scheu noch schreckhaft, und lieben es mit Schwimmern herumzutollen.   `c
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("ordamant_delfin");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 