<?php

/* ********************
Codierung von Ray
Ideen von Ray
ICQ: 230406044
******************** */

/* ************************ Datenbank Anweisung **********************************
ALTER TABLE `accounts` ADD `frisur` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `hairco` INT (12) DEFAULT '0' NOT NULL;


frisur: 0=>Glatze, 10=>Kurze Haare, 20=>Normale Länge, 40=>Lange Haare,60=>Lang und Ungeflegt

hairco: 0=>Braune Haare,1=>Schwarze Haare,,2=>Blonde Haare,3=>Grüne Haare,4=>Pinke Haare,5=>Rote Haare,6=>Blaue Haare


*********************************************************************************/




require_once "common.php";
page_header ("Friseur");


if ($_GET['op']==""){
output("`#Du betritts das Friseur geschäfft und guckst dich gleich um.`n");
output("Du siehst eine Kleine Frau die dir einen Haarschnitt unter Bezahlung anbietet.");
output("`nWelchen Haarschnitt willst du?`n");

if ($session['user']['frisur']>1)addnav("Haare Schneiden");
if ($session['user']['frisur']>1) addnav("Glatze machen - 200 Gold","fri.php?op=hg");
if ($session['user']['frisur']>11) addnav("Haare Kurz Schneiden - 200 Gold","fri.php?op=hk");
if ($session['user']['frisur']>21) addnav("Haare auf Normale Länge schneiden - 200 Gold","fri.php?op=hh");
if ($session['user']['frisur']>41) addnav("Haare pflegen - 200 Gold","fri.php?op=hl");
if ($session['user']['frisur']>1){
addnav("Haare Färben");
addnav("Haare Braun Färben - 400 Gold","fri.php?op=fb");
addnav("Haare Schwarz Färben - 400 Gold","fri.php?op=fs");
addnav("Haare Blond Färben - 400 Gold","fri.php?op=fb2");
addnav("Haare Grün Färben - 600 Gold","fri.php?op=fg");
addnav("Haare Pink Färben - 600 Gold","fri.php?op=fp");
addnav("Haare Rot Färben - 600 Gold","fri.php?op=fr");
addnav("Haare Blau Färben - 600 Gold","fri.php?op=fb3");
}
addnav("Sonstiges");
addnav("Zurück","center.php");

}else if ($_GET['op']=="hg"){
if ($session['user']['gold']>199){
output("Du bezahlst und Lässt dir danach eine Glatze schneiden.");
$session['user']['gold']-=200;
$session['user']['frisur']=0;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");

    }
}else if ($_GET['op']=="hk"){
if ($session['user']['gold']>199){
output("Du bezahlst und Lässt dir danach deine Haare Kurz schneiden schneiden.");
$session['user']['gold']-=200;
$session['user']['frisur']=10;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");

    }
}else if ($_GET['op']=="hh"){
if ($session['user']['gold']>199){
output("Du bezahlst und Lässt dir danach deine Haare auf Normale Länge schneiden.");
$session['user']['gold']-=200;
$session['user']['frisur']=20;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");

    }
}else if ($_GET['op']=="hl"){
if ($session['user']['gold']>199){
output("Du bezahlst und Lässt dir danach deine Haare pflegen.");
$session['user']['gold']-=200;
$session['user']['frisur']=40;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");

    }
}else if ($_GET['op']=="fb"){
if ($session['user']['gold']>399){
output("Du bezahlst und Lässt dir deine Haare Braun Färben");
$session['user']['hairco']=0;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");
    }
}else if ($_GET['op']=="fs"){
if ($session['user']['gold']>399){
output("Du bezahlst und Lässt dir deine Haare Schwarz Färben");
$session['user']['hairco']=1;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");
    }
}else if ($_GET['op']=="fb2"){
if ($session['user']['gold']>399){
output("Du bezahlst und Lässt dir deine Haare Blond Färben");
$session['user']['hairco']=2;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");
    }
}else if ($_GET['op']=="fg"){
if ($session['user']['gold']>599){
output("Du bezahlst und Lässt dir deine Haare Grün Färben");
$session['user']['hairco']=3;
$session['user']['gold']-=600;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");
    }
}else if ($_GET['op']=="fp"){
if ($session['user']['gold']>599){
output("Du bezahlst und Lässt dir deine Haare Pink Färben");
$session['user']['hairco']=4;
$session['user']['gold']-=600;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");
    }
}else if ($_GET['op']=="fr"){
if ($session['user']['gold']>599){
output("Du bezahlst und Lässt dir deine Haare Rot Färben");
$session['user']['hairco']=5;
$session['user']['gold']-=600;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");
    }
}else if ($_GET['op']=="fb3"){
if ($session['user']['gold']>599){
output("Du bezahlst und Lässt dir deine Haare Blau Färben");
$session['user']['hairco']=6;
$session['user']['gold']-=600;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","fri.php");
    }
}

page_footer();
?> 