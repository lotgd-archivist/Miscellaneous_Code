
<?php

// 16_02_2007 by Linus

require_once "common.php";
addcommentary();
checkday();

page_header("Eingang zur Dorfsiedlung");
$session['user']['standort']="Wohnviertel";

addnav("W?Weiter zu den Häusern","houses.php?location=1");
addnav("S?Spielplatz","spielplatz.php");
addnav("D?Dorfbrunnen","well.php");

addnav("Z?Zurück zum Dorf","village.php");
output("`C`c`bDie Dorfsiedlung`b`n`n");
output("<img src='./images/dorfsiedlung.gif'>`n`n`c",true);
output("`C Ohne große Eile zu haben, schlenderst du gemütlich durch die kleinen Gassen bis du endlich die Siedlung erreichst.  Mit Zufriedenheit im Gesicht erkennst du, dass es hier ständig neue Häuser und Baustellen gibt. Ein Zeichen, dass sich die Bewohner hier wohl fühlen und niederlassen wollen. Das bedeutete für dich, dass du bald neue Bekanntschaften machen könntest, wenn du nur ein wenig über deinen Schatten springen und die anderen ansprechen würdest. Gedankenversunken setzt du deinen Weg fort um zu deinem eigenen Häuschen zu gelangen.`n");
output("`n`n`%`@In deiner Nähe reden einige Dorfbewohner:`n");
output("`n`n");

viewcommentary("Dorfsiedlung","Hinzufügen",25,"sagt",1,1);

page_footer();


