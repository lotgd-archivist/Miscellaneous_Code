
<?php
#####################################
#                                   #
#            Osterspezial           #
#         für die Osterwiese        #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#       von Laserian und mfs        #
#     mit der Unterstützung von     #
#            Amon Chan              #
#         Texte von calamus         #
#           Ostern 2008             #
#           Frohe Ostern            #
#                                   #
#####################################
require_once "common.php";
page_header("Osterwurf");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
bild("osterwurf.jpg");
switch($_GET['op']){
default:
$session['user']['specialinc'] = "osterwurf.php";
output("`@Du kommst auf eine Wiese auf der viele Menschen mit Ostereiern werfen.
Ein kleiner Junge kommt mit einem Korb voll Eiern auch zu Dir und hält ihn Dir hin,
damit auch du ein Ei werfen kannst. Du nimmst ein Ei und wirfst es.");
addnav("Zurück zum Wald","forest.php");
addnav("Weiter","forest.php?op=weiter");
break;
case "weiter":
switch(e_rand(1,3)){
case 1:
output("`@Guter Wurf! Du hast Dein Ei weit geworfen und ein kleiner Junge auf der anderen Seite
der Wiese hebt das heile Ei auf und zeigt es hoch.
Alles beglückwunscht Dich und du erhälst `^3 Charmepunkte`@.");
$session['user']['charm']+=3;
break;
case 2:
output("`@Du hast weit geworfen, aber ein Junge auf der anderen Seite der Wiese hebt Dein
geworfenes Ei kaputt auf und zeigt es hoch.
Schade, dass Dein Ei nicht heil geblieben ist.");
break;
case 3:
output("`@Oh weh! Also das mit dem Eier werfen solltest Du aber noch üben. Du wirfst viel zu hoch,
Dein Ei fliegt nicht weit und das Knacksen der Schale kannst Du deutlich hören,
als das Ei auf dem Boden aufkommt. Um Dich herum hörst Du schallendes Gelächter
und beschämt ziehst Du von dannen.
Dein Charme hat gelitten, Du hast `^3 Charmepunkte `@verloren.");
$session['user']['charm']-=3;
break;
}
addnav("Weiter","forest.php");
break;
}

$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);

page_footer();
?>

