
<?php
#####################################
#                                   #
#            Osterspezial           #
#         für die Osterwiese        #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#       von Laserian und mfs        #
#       mit Unterstützung von       #
#            Amon Chan              #
#         Texte von calamus         #
#           Ostern 2008             #
#           Frohe Ostern            #
#                                   #
#####################################
require_once "common.php";
page_header("Osterhase");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
bild("osterhase.jpg");
switch($_GET['op']){
default:
$session['user']['specialinc'] = "osterhase.php";
output("`@Vor Dir hörst Du ein Geräusch und willst schon Deine Waffe ziehen, als ein Hase mit
einem Körbchen voll buntbemalter Eier über den Pfad hüpft.
Er guckt Dich kurz an und zwinkert mit dem Auge, dann hüpft er weiter seines Weges,
immer wieder kurz anhaltend und ein Ei aus dem Körbchen auf ein Stückchen Wiese legend.
`n`n
Hase? bunte Eier? Ostereier! - Du hast den Osterhasen gesehen.
Fröhlich hüpfend setzt Du Deinen Weg fort. Du erhälst `^3 Charmepunkte`@.");
$session['user']['charm']+=3;
addnav("Zurück zum Wald","forest.php?op=weiter");
break;
case "weiter":
output("`@Du hoppelst weiter zur wilden Osterwiese.");
addnav("Weiter","forest.php");
break;
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

