<?php
/*
filename: Schnellbank.php
Orginal by SkyPhy, July 2004

modyficated 20072004 by Hadriel
modyficated 01112005 by Nightknight for Rha.ch.vu
modificates 16042009 by Sirenja für pandea-island.de
*/

require_once "common.php";
page_header("Spatzenfelsen");
output("`^`c`bSpatzenfelsen`b`c`6`n`n");
output("Vor dir liegt der Spatzenfelsen. Eine grosse Tasche liegt vor dir und ein Warnzettel wurde an einem Felsen angebracht...`n`n");
output("`4...Sei gewarnt Krieger. Wenn du hier pfeiffst wird die Hälfte deines Goldes von einem Spatz in die Bank gebracht!...`0`n`n`n");

if ($_GET[op]==""){
  checkday();
  $gold = round($session[user][gold]*0.5);
  output("`0Du nimmst eine leere Tasche aus deinem Rucksack und füllst die Hälfte deines Goldes hinein.");
  output(" Dann pfeifst Du laut und schon stößt, nach wenigen Sekunden, ein kleiner Spatz vom Himmel herab");
  output(" und nimmt deine Tasche mit dem Gold. Er fliegt zurück ins Dorf und liefert es dort bei der Bank ab.");
  output(" Du wirst traurig denn du hast nun nicht mehr so viel Gold bei dir.");
  output(" Aber Du weißt dass dein Gold jetzt sicher auf der Bank liegt und es dir keiner mehr weg nehmen kann.`n");
  output("`nDu deponierst `0`6".$gold." `6Gold `0auf Deinem Konto`n");
  $session[user][goldinbank]+=$gold;
  $session[user][gold]-=$gold;
  output("Du hast damit `0`6".$session[user][goldinbank]." `6Gold `0auf Deinem Konto");
  forest(true);
}
if($_GET[op]=="abh"){
output("`0Du pfeiffst wieder dem Adler und er holt dir das ganze Gold von der Bank `nund lässt es vor deine Füsse fallen...`nDu hebst es auf und hattest vorher `6".$session[user][gold]." `0in der Hand...");
output("`n`n`n`%der Adler brachte dir `6".$session[user][goldinbank]." `%Gold von der Bank");
$session[user][gold]+=$session[user][goldinbank];
$session[user][goldinbank] = 0;
output("`n`%Nun hast du `6".$session[user][gold]."`% Gold auf der Hand!");
forest(true);
}else{

}
page_footer();

?> 