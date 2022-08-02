<?php
//scripted by
//Vladir
//installation: addnav("RP Übungsplatz",learnrp.php); an beliebiger stelle einfügen und bei $wach und $dorf den dementsprechenden wert einsetzen.

require_once "common.php";
checkday();
$wach = "Arwen Anouk"; //hier den Namen des Wächter eintragen
$dorf = "Seaquinn"; //hier den Namen des Dorfes eintragen
page_header("RP Übungsplatz");
addnav("Zurück ins Dorf","village.php");
addnav("$wach um Hilfe bitten","/mail.php?op=write&to=$wach",false,true);
output("`&Du betrittst den RP Übungsplatz von $dorf . Hier kannst du RP üben bis zum abwinken, ohne das es jemanden stört wenn du Fehler machst und die Funktionen und Farben testen. Solltest du Fragen haben Wende dich an $wach ");
output("$w `n");
output("Sie/Er wird dir gerne behilflich sein und dir deine Fragen beantworten.`n");
addcommentary();
viewcommentary("testcomment","Hier Üben:`n",20,"sagt");

    output("`n`n  `11 , `22 , `33 , `44 , `55 , `66 , `77 , `88 , `99 `n,
    `!! , `@@ , `## , `$$ , `%% , `^^ , `qq , `QQ , `&& `n,
    `TT , `tt , `RR , `rr , `VV , `vv , `gg");
page_footer();
?> 