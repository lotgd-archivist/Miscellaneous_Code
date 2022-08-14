
<?php
#####################################
#                                   #
#            Osterspezial           #
#         für die Osterwiese        #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#       von Laserian und mfs        #
#     mit der Unterstützung von     #
#           Amon Chan               #
#         Texte von calamus         #
#           Ostern 2008             #
#           Frohe Ostern            #
#                                   #
#####################################
require_once "common.php";
page_header("Osterei");
function bild($dn){
    global $session;
    $pic = "images/$dn";
    output("`n`c<img src='$pic'>`c`n",true);
}
bild("osterei.jpg");
switch($_GET['op']){
default:
$session['user']['specialinc'] = "osterei.php";
output("`@Am Rande Deines Weges siehst Du aus dem Gras etwas buntes leuchten.
Du gehst etwas näher und findest ein Nest mit bunt bemalten Eiern und einem kleinen Päckchen.");
addnav("Zurück zum Wald","forest.php=op=zurueck");
addnav("Päckchen öffnen","forest.php?op=weiter");
break;
case "zurueck":
    output("`@Du denkst du solltest lieber zurück gehen.");
    addnav("Zum Wald","forest.php");
break;
case "weiter":
switch(e_rand(1,10)){
case 1:
case 2:
case 3:
case 4:
case 5:
output("`@Du öffnest das Päckchen und findest ein Beutelchen. Als Du das Beutelchen öffnest,
findest Du `^1000 Gold`@.");
$session['user']['gold']+=1000;
break;
case 6:
case 7:
case 8:
case 9:
case 10:
output("
`@Du öffnest das Päckchen und findest ein Beutelchen. Als Du das Beutelchen öffnest,
findest Du `^3 Edelsteine`@.");
$session['user']['gems']+=3;
break;
}
addnav("Weiter","forest.php");
break;
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

