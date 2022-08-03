<?php

require_once("common.php");

page_header("Das Universitätsviertel");
addcommentary("");


output("`7In diesem Viertel steht die alt... äh neuerwürdige Universität sowie die anderen Gebäude, die dazu gehören-`n`n
Fühlst du dich erst von der Größe der Universität selbst förmlich erschlagen, so fällt dein Auge dann doch auf ein paar kleinere, nicht weniger Schmucke Gebäude.
Die Bibliiothek sowie die Schule sind Horte des Wissens. Anbei stehen noch die Kunstschule, deren Gelände durch ein niedriges Mäuerchen abgetrennt ist und wo man
schon von außen herrliche Gärten bewunden kann die Minayas Herz erfreuen, sowie der Hohe Astronomieturm, der allein dem Wissen der Sterne und deren Göttin gewittmet ist`n
Etwas abseits steht - etwas kleiner, aber immerhin mit großem Garten - das Waisenhaus Ardas. ");
 
 
addnav("Zur  Universität","uni.php");
addnav("Zur Bibliothek","library.php");
addnav("Astronomieturm","astrom.php");
if ($session['user']['superuser']>1) { addnav("Kunstschule","kunst.php"); 
}//Raus wegen weil keine Beschreibung
addnav("Zurück");
addnav("Zum Dorf","village.php");

viewcommentary("univiertel","Sprechen:",15);

page_footer();
 ?>