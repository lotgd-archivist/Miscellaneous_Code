<?php

/* ********************
Codierung von Ray
Ideen von Ray
ICQ: 230406044
******************** */

/* ************************ Datenbank Anweisung **********************************
ALTER TABLE `accounts` ADD `nagel` INT (12) DEFAULT '0' NOT NULL;
ALTER TABLE `accounts` ADD `nagelco` INT (12) DEFAULT '0' NOT NULL;



Nagel: 0=>Kurze Nägel, 10=>Normale Nägel, 20=>Gepflägte Nägel 40=>Ungeplfegt



nagelco: 0=>Normale Nägel,1=>Schwarze Nagellack,,2=>Gelbes Nagellack,3=>Lila Nagellack,4=>Rotes Nagellack,5=>Blaues Nagellack,6=>Pinkses Nagellack, 7=>Klarlack


*********************************************************************************/



require_once "common.php";
page_header ("Nagelstudio");


if ($_GET['op']==""){
output("`#Du betritts das Nagelstudio geschäfft und guckst dich gleich um.`n");
output("`nWas willst du tun?`n");

if ($session['user']['nagel']>1) addnav("Nägel");
if ($session['user']['nagel']>1) addnav("Nägel kurz Schneiden - 200 Gold","nagel.php?op=nks");
if ($session['user']['nagel']>10)addnav("Nägel normal Schneiden - 200 Gold","nagel.php?op=nns");
if ($session['user']['nagel']>20)addnav("Nägel Pflegen lassen - 200 Gold","nagel.php?op=nls");
addnav("Nägel Lackieren");
addnav("Schwarz Lackieren - 400 Gold","nagel.php?op=ls");
addnav("Gelb Lackieren - 400 Gold","nagel.php?op=lg");
addnav("Lila Lackieren - 400 Gold","nagel.php?op=ll");
addnav("Rot Lackieren - 400 Gold","nagel.php?op=lr");
addnav("Blau Lackieren - 400 Gold","nagel.php?op=lb");
addnav("Pink Lackieren - 400 Gold","nagel.php?op=lp");
addnav("Klarlack Lackieren - 600 Gold","nagel.php?op=lk");
addnav("Nagellack entfernen - 200 Gold","nagel.php?op=le");
addnav("Sonstiges");
addnav("Zurück","center.php");


}else if ($_GET['op']=="nks"){
if ($session['user']['gold']>199){
output("Du lässt dir deine Nägel auf kurze länge schneiden");
$session['user']['nagel']=0;
$session['user']['gold']-=200;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="nns"){
if ($session['user']['gold']>199){
output("Du lässt dir deine Nägel auf normale länge schneiden");
$session['user']['nagel']=10;
$session['user']['gold']-=200;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="nls"){
if ($session['user']['gold']>199){
output("Du lässt dir deine Nägel pflegen");
$session['user']['nagel']=20;
$session['user']['gold']-=200;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="ls"){
if ($session['user']['gold']>399){
output("Du lässt dir deine Nägel Schwarz Lackieren");
$session['user']['nagelco']=1;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="lg"){
if ($session['user']['gold']>399){
output("Du lässt dir deine Nägel Gelb Lackieren");
$session['user']['nagelco']=2;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="ll"){
if ($session['user']['gold']>399){
output("Du lässt dir deine Nägel Lila Lackieren");
$session['user']['nagelco']=3;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="lr"){
if ($session['user']['gold']>399){
output("Du lässt dir deine Nägel Rot Lackieren");
$session['user']['nagelco']=4;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="lb"){
if ($session['user']['gold']>399){
output("Du lässt dir deine Nägel Blau Lackieren");
$session['user']['nagelco']=5;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="lp"){
if ($session['user']['gold']>399){
output("Du lässt dir deine Nägel Pink Lackieren");
$session['user']['nagelco']=6;
$session['user']['gold']-=400;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="lk"){
if ($session['user']['gold']>599){
output("Du lässt dir deine Nägel Klarlack Lackieren");
$session['user']['nagelco']=7;
$session['user']['gold']-=600;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}else if ($_GET['op']=="le"){
if ($session['user']['gold']>199){
output("Du lässt dir dein Nagellack entfernen");
$session['user']['nagelco']=0;
$session['user']['gold']-=200;

addnav("Weiter","center.php");
}else{
output("Du hast nicht genügend Gold.");

addnav("Zurück","nagel.php");
    }
}

page_footer();
?> 