
<?php
#####################################
#                                   #
#            Osterspezial           #
#         für die Osterwiese        #
#            von Midgar             #
#  http://www.logd-midgar.de/logd/  #
#       von Laserian und mfs        #
#       mit der Unterstützung von   #
#             Amon Chan             #
#         Texte von calamus         #
#           Ostern 2008             #
#           Frohe Ostern            #
#                                   #
#####################################
require_once "common.php";
page_header("Osterfeier");
switch($_GET['op']){
default:
$session['user']['specialinc'] = "osterfeier.php";
output("`@Du kommst an einen Pfad, aus dessen Richtung Du fröhliche Musik hören kannst.
Tiefe Spuren von Wagenrädern haben sich in den Boden gegraben.
Wirst Du dem Pfad folgen und nachsehen, was dort los ist?");
addnav("Zurück zum Wald","forest.php?op=zurueck");
addnav("Pfad folgen","forest.php?op=weiter");
break;
case "zurueck":
    output("`@Du denkst du solltest lieber zurück gehen.");
    addnav("Wald","forest.php");
break;
case "weiter":
output("`@Du folgst den tiefen Räderspuren, die Musik wird immer lauter.
Inzwischen kannst Du auch verführerischen Geruch nach Braten riechen.
Du kommst auf eine Lichtung, auf der mehrere Wagen stehen.
Eine Gruppe von Menschen macht fröhliche Musik. Über einem Feuer wird ein Lamm gegrillt.
Ein kleines Mädchen kommt, sagt `&\"wir feiern Ostern, Du feierst doch mit?\" `@, nimmt Dich
bei der Hand und zieht Dich zu der Gruppe. Eine junge Frau reicht Dir einen Teller mit
Lammfleisch, das Du genüsslich isst. Du feierst ein wenig mit und gehst dann gut gesättigt und
erholt in den Wald zurück. Du spürst, dass Du Kraft für `^3 zusätzliche Waldkämpfe`@ hast.");
$session['user']['turns']+=3;
addnav("Weiter","forest.php");
break;
}
$copyright ="<div align='center'><a href=http://www.logd-midgar.de/logd/ target='_blank'>&copy;`^Mi`&dg`@ar`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
page_footer();
?>

