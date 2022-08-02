<?php
header('Content-Type: text/html; charset=utf-8');
require_once "common.php"; 
addcommentary();
page_header("Ordamant – Das Wasserviertel"); 
output("`)`c`bDas Sternenherz von `!O`9r`od`ma`Âm`ma`9n`!t`b`c`6"); 

addnav("Sternenherz");
if (@file_exists("houses.php")) addnav("Wohnviertel","houses.php?location=4");
addnav("Strand","ordamant_strand.php");
addnav("Delfinschule","ordamant_delfinschule.php");
addnav("Postamt `iDer Götterbote`i", "");
addnav("Hafen","ordamant_hafen.php");
addnav("Korallenriff","ordamant_korallenriff.php");
addnav("Kneipe `iThe Locker`i","ordamant_kneipe.php");
addnav("Beobachtungsdeck","ordamant_beobachtungsdeck.php");



addnav("Zum Brunnenplatz","village.php");

if ($_GET[op]==""){
output("`n`n");
output("`c`n`n`n<table><tr><td><img src='./images/ordamant.png'></td></tr></table>`c`n", true);
output ("`(`nDas Viertel, das man nur über eine steinerne Brücke erreichen kann, liegt wie ein riesiger Seestern auf dem Meer. 
An den fünf vorspringenden Armen kann eine ganze Flotte von Schiffen anlegen, und es herrscht stets ein reges Kommen und Gehen. Je weiter man sich vom Rand entfernt, desto  höher werden 
die perlmuttschimmernden Gebäude, bis hin zum höchsten Turm, der sich wie eine glitzernde Nadel genau im Zentrum der Stadt erhebt. ");
output("`nZwischen den äußersten Spitzen der Anleger und dem Zentrum ziehen sich fünf breite, schnurgrade Straßen, aber wirklich interessant wird es in den unzähligen kleinen Gassen dazwischen. Dort lässt sich angeblich alles finden, was jemals von irgendwem
aus dem Meer gezogen wurde. In einem Ring um den Turm im Zentrum zieht sich eine gepflegte Grünfläche, die zum Verweilen einlädt und an die sich die teureren Geschäfte und Restaurants reihen. 
Das wahre Wunder des Viertels liegt aber unter der Wasseroberfläche, wo sich das Spiegelbild der Oberstadt grünlich schimmernd und still in die Tiefe erstreckt, der zentrale Turm beinahe am 
Meeresgrund. Hier liegen die Wohnungen der Stadtbewohner, die es lieber nass mögen.
");                        
output("`n`n");


output("`n`n`%`mIn der Nähe reden einige Dorfbewohner:`n");
viewcommentary("ordamant_village");


output("`n`n`%`GOOC-Bereich:`n");
viewcommentary("ooc");


output("`n`n`n<form action=\"$REQUEST_URI\" method='POST'>
                                <a href='farben.php' target='_blank' onClick=\"".popup("farben.php").";return false;\">`zFarblegende`&</a>`n</form>",true);

}

page_footer(); 
?> 