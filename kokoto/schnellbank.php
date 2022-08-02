<?php
/*
filename: Schnellbank.php
by SkyPhy, July 2004
Idee von ??
Transferiert alles Gold, das man mithat, auf die Bank
Installation:
search in common.php
addnav("Sonstiges");
add after
addnav("Adlerfelsen","schnellbank.php");


modyficated 20072004 by Hadriel
*/

require_once "common.php";
page_header("Adlerfelsen");
output("`^`c`bAdlerfelsen`b`c`6`n`n Vor dir liegt der Adlerfelsen. Eine grosse Tasche liegt vor dir und ein Warnzettel wurde an einem Felsen angebracht...`n`n `4...Sei gewarnt Krieger. Wenn du hier pfeifst  wird dein ganzes Gold von einem Adler in die Bank gebracht!...`0");
if ($_GET['op']==''){
  output("`0Du nimmst eine leere Tasche aus deinem Rucksack und füllst dein Gold hinein. Dann pfeifst Du laut und schon stößt, nach wenigen Sekunden, ein großer Adler vom Himmel herab und nimmt deine Tasche mit dem Gold. Er fliegt zurück ins Dorf und liefert es dort bei der Bank ab. Du wirst traurig denn du hast kein Gold mehr bei dir. Nur eine leere Tasche hast Du noch. Aber Du weißt das Dein Gold jetzt sicher auf der Bank liegt und es dir keiner mehr wegnehmen kann.`n`nDu deponierst `0`6".$session['user']['gold']." `6`0auf Deinem Konto`n");
  $session['user']['goldinbank']+=$session['user']['gold'];
  $session['user']['gold']=0;
  
  output("Du hast damit `0`6".$session['user']['goldinbank']." `6`0auf Deinem Konto");
}
addnav("Zurück in den Wald","forest.php");

page_footer();

?>