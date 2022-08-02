<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Der Wasserspeicher"); 
output("`)`c`bDer Wasserspeicher`b`c`6"); 
addnav("Zurück");
addnav("Zurück zu den Tunneln","essos_tunnel.php");
addnav("Zurück zum Vergnügungsviertel","essos_vergnuegungsviertel.php");
addnav("Zurück zum Marktplatz","essos_village.php");



if ($_GET[op]==""){
output("`n`n");
output ("`k`c`nNach einem Marsch von mehreren Meilen durch unmarkierte, verschlungene Tunnel öffnet sich vor dir unerwartet eine weitere gewaltige Höhle. Von der niedrigen Decke tropft beständig Wasser in ein Becken, das sich von einer bläulich schimmernden Wand zur anderen erstreckt. Niemals hättest du erwartet, einen solchen Ort im Herzen der Wüste zu finden. Aber nun da du hergefunden hast, möchtest du sicher einen Augenblick verweilen bevor du dich auf den langen Rückweg machst. Wieso versuchst du nicht einen Schluck des Wassers, oder gönnst dir das Vergnügen eine Runde zu schwimmen?`c");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("essos_wasserspeicher");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");





}

page_footer(); 
?> 