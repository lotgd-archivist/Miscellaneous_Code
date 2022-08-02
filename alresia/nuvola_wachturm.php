<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Nuvola – Das Nobelviertel"); 
output("`)`c`bAlter Wachturm`b`c`6"); 



addnav("Zum Gipfelpfad","nuvola_gipfelpfad.php");


if ($_GET[op]==""){
output("`n`n");
output ("`(`nHier kommt noch ein ganz toller Text hin. Wirklich.

");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("Alter_Wachturm");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 