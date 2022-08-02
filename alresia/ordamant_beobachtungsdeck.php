<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Beobachtungsdeck"); 
output("`)`c`bDas Beobachtungsdeck`b`c`6"); 

addnav("Zur Aussichtsplattform","ordamant_aussichtsplattform.php");

addnav("Zum Sternenherz","ordamant_village.php");
if ($_GET[op]==""){
output("`n`n");
output ("`(`n`cÜber eine lange gewundene Treppe im Inneren des zentralen Turms gelangst du nach unzähligen Stufen schließlich ganz bis an den untersten Punkt der Stadt, hunderte Meter unter der 
`nMeeresoberfläche. Hier unten herrscht nur ein trübes grünliches Licht, aber hinter dem Glas, das den Ozean aussperrt, ist es stockdunkel, und du willst dich schon abwenden, um den langen Weg 
`nzurück nach oben zu gehen. Aber dann bemerkst du im pechschwarzen Wasser plötzlich unzählige schwach leuchtende Punkte, wie Sterne am Nachthimmel, und nun kannst du dich von dem Schauspiel 
`nerstmal nicht losreißen     `c
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("ordamant_beobachtung");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 