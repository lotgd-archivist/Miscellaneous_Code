<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Essos – Die Unterstadt"); 
output("`)`c`bDie Oase`b`c`6"); 

addnav("Zurück zur Stadt","village.php");
if ($_GET[op]==""){
output("`n`n");
output ("`(`nDie meisten Oasen entlang der alten Handelsrouten sind nicht mehr als ein Brunnen und ein paar vertrocknende Dattelpalmen. 
`nNicht so diese Oase. Ein regelrechter Wall aus meterhohen, ineinander verwachsenen Kakteen erhebt sich vor dir aus dem Wüstensand, und nur nach einigem Suchen findest du schließlich einen Weg hindurch, zu dem Palmenwäldchen im Inneren. 
`nHier ist es kühl und schattig, selbst der Sand scheint nicht mehr so kratzig, und es gibt sogar einen kleinen Teich mit klarem Wasser. Instinktiv weißt du das du dir hier wegen der zahlreichen Wüstenräuber keine Gedanken machen musst, 
`nhier kannst du in Ruhe entspannen. Und wer weiß, vielleicht bringst du ja nächstes Mal jemand ganz besonderen mit?
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_oase");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 