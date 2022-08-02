<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Die Stadtwache"); 
output("`)`c`bDie Stadtwache`b`c`k"); 



addnav("Zurück zur Stadt","village.php");
addnav("Büro des Hauptmanns","nuvola_stadtwache_buero.php");
addnav("Zur Kaserne","nuvola_stadtwache_kaserne.php");
addnav("Zum Verlies","nuvola_stadtwache_verlies.php");

if ($_GET[op]==""){
output("`n`n");        
 output("`cDu steht vor einem Gebäudekomplex, das mit Sicherheit schon einmal bessere Jahre gesehen hat. Dennoch kann man heute noch erahnen, wie imposant es einmal ausgesehen haben mag.`c");
 output("`cÜber der hohen Eingangstüren steht in große Messinglettern, einige dumpf von der Witterung der Jahre, andere noch glänzend und vor kurzem erst ausgetauscht, geschrieben:`c");                
output("`n`c`bStadtwache`b`c`n");
output("`n`cDu trittst durch die Türe und befindest dich im großen Hauptraum, in dem bereits die ersten geschäftigen Wachleute an ihren Schreibtischen Berichte schreiben, sich leise mit besorgten, sowie erleichterten Stadtbewohnern unterhalten.`c`n");
output("`n`cHinter einem der hohen Tische blickt dir ein junger Rekrut entgegen.`n
Du könntest dein Anliegen an ihn weitergeben,`n
Oder möchtest du dich doch lieber noch weiter umsehen?`c
`n");
output("`n`n");
output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_stadtwache");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 