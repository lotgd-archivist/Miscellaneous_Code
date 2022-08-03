<?php
//Umgeändert in Symias Hafenmole (da Zylyma keine Hafenstadt ist
require_once "common.php";
addcommentary();

page_header("Symias kleine Hafenmole");
output("`^`c`bSymias kleine Hafenmole`b`c`6");
if ($_GET[op]==""){
output("Leise schlagen die Wellen an die weißen, niedrigen Mauern der Hafenmole von Symia. Hier liegen die schlanken, wendigen Elbenboote und einige wenige Schiffe, die eindeutig menschlicher Hand entsprungen sind.
                Nur kleine Schiffe haben hier Platz und auch von denen nicht viele. EIn grau-weißer Turm ragt an der Seite herauf und gibt den Booten über sein helles Licht Zeichen, ansonsten ist der Hafen ziemlich naturbelassen
                und ein schmaler Weg führt in den Sonnenbeschienenen Hain der Elfen hinauf.`n`n");
viewcommentary("necron_hafen","sagt:",15);
/*output ("In Zylyma ist alles Prunkvoll und schön... aber nicht der Hafen hier sieht es aus wie vor der Koniallisierung durch Arda, überall lungern Zombies rum und warten auf`n");
output ("Wesen die Reisen wollen, du siehst allerdings nur einen 3 Master der nach Arda fahren will. Ein anderes Schiff das scheinabr die neuen Kolonnien ansteuert hat leider gerade abgelegt`n
Du beschliesst dich etwas umzugucken und eventuell nach Hause zu fahren.");*/
addnav ("Zum Schiff nach Arda (3 Gold)","necron_hafen.php?op=nightwood");
addnav("Zurück nach Symia","sanela.php");
}
if ($_GET[op]=="nightwood"){
if ($session[user][gold]>2 /*&& $session[user][turns]>0*/){
output ("Du betrittst das Schiff und wirst gleich vom Capitän eingewiesen. Dann bezahlst du die 3 Gold und wartest einige Stunden`n");
addnav ("Nach Arda","hafen.php");
$session[user][gold]-=3;
$session[user][turns]-=0;
}
else{
output ("Du kannst nicht Reisen da du zu Müde bist oder zu Arm!");
addnav ("Zurück zum Hafen","necron_hafen.php");
addnav("Zurück nach Symia","sanela.php");
}
}
page_footer();
?> 