<?php
// 06.10.2005
// fertiggestellt
/*
    Idee und Konzept von Portas und Tari
    Umsetzung durch Portas und aragon
*/

require_once "common.php";
addcommentary();

if (!isset($session)) exit();

Page_Header("Die Nervenheilanstalt");

output("`7`c`bUntersuchungszimmer`b`c`n");
if($session[user][superuser]>=3){//3 ist der übliche Wert für Admin; sollte dem Server entsprechend angepasst werden

    output("`7Du bist entweder ein Admin oder ein zuständiger Arzt.`nDie anwesenden Patienten wurden hier nicht ohne Grund reingeschickt. Da man von Geisteskrankheiten sowie Unzurechnungsfähigkeit ausgehen kann, ist absolute Vorsicht beim Umgang mit den Patienten geboten.`n`n`n");
    viewcommentary("sanctum_room","Hinzufügen",25);
//    addnav("W?Wartezimmer?","sanctum.php");
    addnav("P?Zum Pranger","jail.php");
    addnav("A?Zur Pranger Administration","admin_jail.php");
    addnav("D?Zurück zum Dorf","village.php");

}else{
    addnav("Z?Zurück zum Dorfplatz?","village.php");
    output("`$Du hast nicht die Berechtigung, dich in der Nervenheilanstalt aufzuhalten!`0`n");
}


page_footer();
?>