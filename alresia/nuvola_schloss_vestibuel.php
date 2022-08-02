<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Vestibül"); 
output("`)`c`bVestibül`b`c`6"); 

addnav("Erdgeschoss");
addnav("Empfangszimmer","nuvola_schloss_empfangszimmer.php");
addnav("Thronsaal","nuvola_schloss_thronsaal.php");
addnav("Spiegelsalon","nuvola_schloss_spiegelsalon.php");
addnav("Schlossgarten","nuvola_schloss_schlossgarten.php");
addnav("Ballsaal","nuvola_schloss_ballsaal.php");
addnav("Erste Etage");



addnav("Öffentliche Bibliothek","nuvola_schloss_oeffentlichebibliothek.php");
addnav("Kapelle","nuvola_schloss_kapelle.php");
addnav("Konservatorium","nuvola_schloss_konservatorium.php");
addnav("Ahnengalerie","nuvola_schloss_ahnengalerie.php");
//addnav("Rittersaal","nuvola_schloss_rittersaal.php");
//addnav("Haushaltszimmer","nuvola_schloss_haushaltszimmer.php");
//addnav("Speisesaal","nuvola_schloss_speisesaal.php");
//addnav("Treppe zum Obergeschoss","nuvola_schloss_obergeschoss.php");
//addnav("Treppe ins Kellergewölbe","nuvola_schloss_keller.php");

addnav("Zurück");
addnav("Zurück zum Regierungsviertel","nuvola_regierungsviertel.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`n`cHalb Märchenschloss, halb Festung, besteht die Anlage vordergründig aus einem äußeren Verteidungswall, in dessen Mauern zur Not die gesamte Stadtbevölkerung Platz findet. 
`nDas eigentliche Schloss, ein Prunkbau der nichts so sehr ähnelt wie einer Zuckerguss-Kreation, ist noch von einer eigenen Wehrmauer umgeben und steht inmitten manikürter Parkanlagen und Gärten.
 `0`c");                   
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_schloss");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 