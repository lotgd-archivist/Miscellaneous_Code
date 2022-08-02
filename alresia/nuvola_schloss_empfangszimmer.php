<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Nuvola – Das Nobelviertel"); 
output("`)`c`bDas Empfangszimmer `b`c`6"); 


addnav("");
addnav("Zurück zum Eingangsbereich","nuvola_schloss_vestibuel.php");
addnav("Zurück zum Regierungsviertel","nuvola_regierungsviertel.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`c`nDer erste Saal, den geladene Gäste bei ihrer Ankunft im Schloss betreten. `nEin paar gemütliche Ohrensessel laden zum Hinsetzen ein, bis man in den Thronsaal gebeten wird. `c  ");  
                  
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_schloss_empfang");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 