<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Nachtlokal"); 
output("`)`c`bNachtlokal `iMarmelädchen`i`b`c`6"); 
addnav("Zurück zum Marktplatz","essos_village.php");


if ($_GET[op]==""){
output("`n`n");
output ("`k`cZusehen, nicht anfassen - an diese Regel solltest du dich hier besser halten, wenn du nicht willst dass der Rausschmeißer dir einen Finger bricht und dich dann um deine Barschaft erleichtert. Die Tänzerinnen hier verstehen was von ihrem Job, aber keinen Spaß was wandernde Hände betrifft. Halte dich an die Regel, und du wirst eine Show erleben, wie du sie sonst nirgendwo in der Stadt findest. Nicht umsonst erzählt man sich dass dieser fensterlose Club den besten Ausblick im Land hat.`c
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_nachtlokal");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");



}

page_footer(); 
?> 