<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Nuvola – Das Nobelviertel"); 
output("`)`c`bDie Ahnengalerie `b`c`6"); 


addnav("Zurück");
addnav("Zurück zum Eingangsbereich","nuvola_schloss_vestibuel.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`c`nIn der Galerie hängen Generationen der königlichen Familie in Form von Gemälden. In diesem Saal wird auch die Tafel für Festmähler aufgebaut.    `c  ");  
                  
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_schloss_ahnengalerie");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 