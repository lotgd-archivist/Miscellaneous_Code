<?php

require_once "common.php";

page_header("Der Mann");
addnav("Dein Meister");
if($session[user][orden]==0){
addnav("`9Zulan`0","zulan.php");
}
if($session[user][orden]==1){
addnav("`$ Elwus`0","elwus.php");
}
if($session[user][orden]==2){
addnav("`7Reanda`0","reanda.php");
}
if($session[user][orden]==3){
addnav("`qGaya`0","gaya.php");
}
addnav("Wege");
addnav("Zurück zum Eingang","turm.php");
addnav("Zurück zum Dorf","village.php");

output("`$`c`bDer Mann`c`bDu gehst auf den Mann im Gewand zu. Er schaut dich an und sagt dann:`n`^Ahh...{$session[user][name]} `^ich habe dich schon erwartet. Ich bin Zunog, derjenige der die Krieger zu ihren richtigen Meistern führt. ");
output("`n`$ Er schaut auf ein Pergament und sagt dann:`n`n");
if($session[user][orden]==0){
output("`^Dein nächster Meister ist `9Zulan, Meister des Wassers`0`^.`n");
}
if($session[user][orden]==1){
output("`^Dein nächster Meister ist `$ Elwus, Meister des Feuers`0`^.`n");
}
if($session[user][orden]==2){
output("`^Dein nächster Meister ist `7Reanda, Meisterin des Windes`0`^.`n");
}
if($session[user][orden]==3){
output("`^Dein nächster Meister ist `qGaya, Meister der Erde`0`^.`n");
}
if($session[user][orden]==4){
output("`^Wie es aussieht hast du all deine Meister besiegt! Glückwunsch. Du wirst nun über das Portalfeld in der Eingangshalle zum Turm der Götter gelangen!`n");
}
if($session[user][orden]>4){
output("`^`nWenn du alle 4 Meister besiegt hast, wird dir der Zugang zum Turm der Götter zustehen.
Aber das ist nicht so einfach. Die Meister hier sin extrem stark. Nur wirklich mächtige Krieger können sie besiegen. Und auf eins muss ich dich hinweisen. Solltest du gegen einen deiner Meister verlieren gibt es kein zurück. Sie haben keine Gnade. Du wirst also um Leben und Tod kämpfen!");
output("`$`nDu schaust ihn noch einmal an und schaust dann auf die Tür deines nächsten Meisters.");
}
output("`n`n`5Du besitzt momentan `^{$session[user][orden]} `5Orden!");

page_footer();
?> 