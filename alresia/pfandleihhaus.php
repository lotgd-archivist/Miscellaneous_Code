<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Pfandleihe"); 
output("`)`c`bPfandleihe Silber & Gold`b`c`6"); 


addnav("Zurück zur Stadt","village.php");
addnav("Zurück zum Herzen Essos","essos_village.php");
if ($_GET[op]==""){
output("`n`n");
output ("`(`n`c Du betrittst die Pfandleihe durch eine Tür, von der du schwören könntest, dass sie gestern noch auf der anderen Straßenseite gewesen war, und siehst dich um. `nEs handelt sich offenbar um einen von *diesen* Läden, dunkel und vollgestopft mit staubigen Vitrinen und Schränken und einem mottenzerfressenen ausgestopften Alligator unter der Decke. Ein wenig enttäuscht bist du schon, als du Madame Silber hinter der Kasse entdeckst - zum Ambiente würde eine verhutzelte Alte besser passen. Oder ein humpelnder koboldartiger Kerl wie Mister Gold, der halb versteckt hinter einer monströs hässlichen Götterstatue neue Waren auszeichnet. Lacey informiert dich kaugummikauend und mit gelangweiltem Gesichtsausdruck, dass es hier alles gibt was du dir vorstellen kannst und mehr, und das du auch beleihen kannst was du willst - für nur drei Prozent Zinsen im Monat, plus Bearbeitungsgebühr.  `c
");                        

output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_pfandleihe");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 