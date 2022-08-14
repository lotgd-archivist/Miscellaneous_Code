
<?php
#####################################
#                                   #
#            Osterspezial           #
#         für die Osterwiese        #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#       von Laserian und mfs        #
#     mit der Unterstützung von     #
#             Amon Chan             #
#         Texte von calamus         #
#           Ostern 2008             #
#           Frohe Ostern            #
#                                   #
#####################################
require_once "common.php";
page_header("Ostersegen");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
bild("ostersegen.jpg");
switch($_GET['op']){
default:
$session['user']['specialinc'] = "ostersegen.php";
output("`@Vor Dir zweigt ein Pfad ab, an dessen Rändern Birkenreisig mit bunten Bändern geschmückt ist.
Aus der Richtung, in die der Pfad führt, kannst Du Gesang hören.
Wirst Du dem Pfad folgen und nachsehen, was dort los ist?");
addnav("Zurück zum Wald","forest.php?op=zurueck");
addnav("Pfad folgen","forest.php?op=weiter");
break;
case "zurueck":
output("`@Du denkst du solltest dein Glück lieber nicht herausfordern und gehst zurück.");
addnav("Wald","forest.php");
break;
case "weiter":
output("`@Du folgst dem geschmückten Pfad und kommst auf eine grosse Wiese, auf der ein Brunnen steht.
Viele Menschen stehen um den Brunnen und singen festliche Lieder und ein Priester geht von
Mensch zu Mensch und besprengt sie mit Wasser und spricht seinen Segen aus.
Er kommt auch zu Dir und Du erhälst den Ostersegen.
`n`n
Deine Lebenspunkte steigen permanent um 2");
$session['user']['maxhitpoints']+=2;
addnav("Weiter","forest.php");
break;
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

