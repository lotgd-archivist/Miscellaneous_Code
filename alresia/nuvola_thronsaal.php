<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Thronsaal"); 
output("`)`c`bDer Thronsaal`b`c`6"); 


addnav("Der Ballsaal","nuvola_schloss_Ballsaal.php");
addnav("Zurück zum Eingangsbereich","nuvola_schloss.php");
addnav("Zurück zum Regierungsviertel","nuvola_regierungsviertel.php");
addnav("Zurück zur Stadt","village.php");

if ($_GET[op]==""){
output("`n`n");
output ("`(`nHier kommt ein toller Text hin!

");     
 output("`c`n`n`n<table><tr><td><img src='./images/pollyatwork.gif'></td></tr></table>`c`n", true);
 output("`c`rBei Risiken und Nebenwirkungen wenden Sie sich an Ihren Arzt oder Apotheker.`0`c");                   
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("nuvola_thronsaal");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 