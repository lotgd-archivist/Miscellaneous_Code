<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Korallenriff"); 
output("`)`c`bDas Korallenriff`b`c`6"); 

addnav("Zum Sternenherz","ordamant_village.php");
if ($_GET[op]==""){
output("`n`n");
output ("`(`n`cEine kurze Bootstour von der Stadt entfernt liegt mit dem Korallenriff eines der beliebtesten Ausflugsziele der Umgebung. An der Oberfläche bildet das Riff einige flache Inselchen, auf 
`ndenen Wasservögel nisten, aber sobald man taucht findet man sich in einer anderen Welt wieder. Dank des kristallklaren Wassers kannst du beinahe das ganze Riff überblicken, und die Fische 
`ndie dieses bevölkern funkeln wie kostbare Juwelen. Und beim Stichwort Juwelen kommst du vielleicht auf die Idee tiefer zu tauchen, dorthin wo die traurigen Wracks zahlreicher Segelschiffe 
`nverstreut liegen. In irgendeinem davon liegt noch eine Kiste mit Piratengold versteckt, das weißt du ganz sicher...  `c
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("ordamant_korallen");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 