
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Gruft";
page_header("Die Gruft des Schreckens");
output("`c<img src='images/gru.jpg' border='0' align=center alt='Gruft'>`c",true);
output("`n`cVom Friedhof hast du dich aufgemacht,`n
bist hinunter gestiegen in die finstere Nacht.`n
Fackeln hüllen alles in schattenhaftes Licht,`n
da ein Schrei der die Stille durchbricht.`n
Auf der steilen Treppe achte auf deinen Tritt,`n
sie führt dich weiter hinab mit jedem Schritt.`n
Unter deinen Füßen ein Boden mit tiefer Rille,`n
um dich herum nur eines... unendliche Stille.`n
Die Gräber reihen sich an Boden und Wänden,`n
ach wenn vor dir nicht auch noch Särge ständen,`n
der Gedanke an sie lässt dich zusammenfahren,`n
jene die starben vor gar vielen Jahren.`n
Rote und braune Flecken zieren die Gruft,`n
es ist stickig, du kriegst fast keine Luft,`n
modrig faule der Geruch der in der Nase sticht,`n
da ein Zittern, als ob die Gruft gleich bricht,`n
die Decke ist niedrig, du hoffst das sie hält,`n
wer klug ist der klettert zurück auf die Welt.`n`c");
output("`n`nMit anderen flüstern:`n");
viewcommentary("gruft","flüstern",5);

addnav("Friedhof","friedhof.php");

page_footer();
?>

