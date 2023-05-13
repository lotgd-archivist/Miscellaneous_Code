
<?php
require_once "common.php";
addcommentary();
checkday();
page_header("Übungsraum");

addnav("Z?Zum Dorf","village.php");

output("`v`c`bÜbungsraum`b`c");
output("`tDu bist im Übungsraum. Dieser Ort ist geschaffen worden um Spielern die Möglichkeit zu geben zu üben und ihre RP-Fähigkeiten zu verbessern. `0`n`n`n");
viewcommentary("uebungsraum","Hinzufügen",25,"sagt",1,1);

/*
output("`n`n`n");
viewcommentary("uebungsraum-off","`4`bÜbungsraum OFF-TOPIC`b `tHier diskutiert ihr über eure Fortschritte",25,"sagt");
*/

if (!$session['user']['prefs']['keinot']){
    if ($session['user']['ot_denied']){
        output("`n`n`n`4Es ist dir nicht erlaubt im Off-Topic zu posten!`n`n");
        viewcommentary("uebungsraum-off","hier labern",15,"sagt",0,0);
    }else{
        output("`n`n`n`4`bÜbungsraum OFF-TOPIC`b`n");
        viewcommentary("uebungsraum-off","`tHier diskutiert ihr über eure Fortschritte",25,"sagt");
    }
}

page_footer();


