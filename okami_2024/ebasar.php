
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Schmankerlstand";
page_header("Der Schmankerlstand");
output("`c`b`9Du kommst zum Schmankerlstand und siehst dich dort um. Eine junger Mann zeigt dir die Preisliste.`b`c`n
`rFrisches Brot `&kostet 5 Gold.`n
`rFisches Brötchen `&kostet 10 Gold.`n
`rBrotsuppe `&kostet 15 Gold.`n
`rGebratener Apfel `&kostet 20 Gold.`n
`rFlambierte Banane `&kostet 25 Gold.`n
`rGlasierte Birne `&kostet 30 Gold.`n
`rGeröstete Pilze `&kostet 35 Gold.`n
`rHeiße Kartoffel `&kostet 40 Gold.`n
`rSchüssel Reis `&kostet 45 Gold.`n
`rForelle `&kostet 50 Gold.`n
`rHummer `&kostet 55 Gold.`n
`rHühnerkeule `&kostet 60 Gold.`n
`rFalscher Hase `&kostet 65 Gold.`n
`rSchlangenragout `&kostet 70 Gold.`n
`rRehbraten `&kostet 75 Gold.`n
`rLammfilet `&kostet 80 Gold.`n
`rRindsteak `&kostet 85 Gold.`n
`rSchweinshaxe `&kostet 90 Gold.`n`n");
switch($_GET['op']){
case '':
addnav("weiter");
addnav("zurück","mg.php");
addnav("Essen");
if ($session['user']['gold']>4){
addnav("Frisches Brötchen","ebasar.php?op=e1");
}
if ($session['user']['gold']>9){
addnav("Fisches Brot","ebasar.php?op=e2");
}
if ($session['user']['gold']>14){
addnav("Brotsuppe","ebasar.php?op=e3");
}
if ($session['user']['gold']>19){
addnav("Gebratenen Apfel","ebasar.php?op=e4");
}
if ($session['user']['gold']>24){
addnav("Flambierte Banane","ebasar.php?op=e5");
}
if ($session['user']['gold']>29){
addnav("Glasierte Birne","ebasar.php?op=e6");
}
if ($session['user']['gold']>34){
addnav("Geröstete Pilze","ebasar.php?op=e7");
}
if ($session['user']['gold']>39){
addnav("Heiße Kartoffel","ebasar.php?op=e8");
}
if ($session['user']['gold']>44){
addnav("Schüssel Reis","ebasar.php?op=e9");
}
if ($session['user']['gold']>49){
addnav("Forelle","ebasar.php?op=f1");
}
if ($session['user']['gold']>54){
addnav("Hummer","ebasar.php?op=f2");
}
if ($session['user']['gold']>59){
addnav("Hühnerkeule","ebasar.php?op=f3");
}
if ($session['user']['gold']>64){
addnav("Falscher Hase","ebasar.php?op=f4");
}
if ($session['user']['gold']>69){
addnav("Schlangenragout","ebasar.php?op=f5");
}
if ($session['user']['gold']>74){
addnav("Rehbraten","ebasar.php?op=f6");
}
if ($session['user']['gold']>79){
addnav("Lammfilet","ebasar.php?op=f7");
}
if ($session['user']['gold']>84){
addnav("Rindsteak","ebasar.php?op=f8");
}
if ($session['user']['gold']>89){
addnav("Schweinshaxe","ebasar.php?op=f9");
}
break;
case 'e1':
output("Du nimmst das frisches Brötchen und beißt hinein. Dabei beißt du auf einen Stein`n");
$session['user']['gold']-=5;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e2':
output("Du nimmst das fische Brot und beißt hinein. Dabei beißt du auf ein Goldstück.`n");
$session['user']['gold']-=9;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e3':
output("Du nimmst die Brotsuppe und beißt in ein Brot hinein. Dabei beißt du auf zwei Goldstücke.`n");
$session['user']['gold']-=13;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e4':
output("Du nimmst einen gebratenen Apfel und beißt hinein. Dabei beist du auf drei Goldstücke.`n");
$session['user']['gold']-=17;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e5':
output("Du nimmst eine flambierte Banane und beißt hinein. Dabei beißt du auf vier Goldstücke.`n");
$session['user']['gold']-=21;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e6':
output("Du nimmst eine mit Zuckerglasur überzogen Birne und beißt hinein. Dabei beißt du auf fünf Goldstücke.`n");
$session['user']['gold']-=25;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e7':
output("Du nimmst eine Schale gerösteter Pilze und beißt in einen hinein. Dabei beißt du auf sechs Goldstücke.`n");
$session['user']['gold']-=29;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e8':
output("Du nimmst eine heiße Kartoffel und beißt hinein. Dabei beißt du auf sieben Goldstücke.`n");
$session['user']['gold']-=33;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'e9':
output("Du nimmst eine kleine Schüssel Reis und isst davon. Dabei beißt du auf acht Goldstücke.`n");
$session['user']['gold']-=37;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f1':
output("Du nimmst dir eine Forelle und beißt hinein. Dabei beißt du auf neun Goldstücke.`n");
$session['user']['gold']-=41;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f2':
output("Du nimmst dir einen Hummer und beißt hinein. Dabei beißt du auf zehn Goldstücke.`n");
$session['user']['gold']-=45;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f3':
output("Du nimmst die Hühnerkeule und beißt hinein. Dabei beißt du auf elf Goldstücke.");
$session['user']['gold']-=49;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f4':
output("Du nimmst einen falschen Hasen und beißt hinein. Dabei beißt du auf zwölf Goldstücke.`n");
$session['user']['gold']-=53;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f5':
output("Du nimmst etwas Schlangenragout und beißt hinein. Dabei beißt du auf dreizehn Goldstücke.`n");
$session['user']['gold']-=57;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f6':
output("Du nimmst etwas Rehbraten und beißt hinein. Dabei beißt du auf vierzehn Goldstücke.`n");
$session['user']['gold']-=61;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f7':
output("Du nimmst ein Lammfilet und beißt hinein. Dabei beißt du auf fünfzehn Goldstücke.`n");
$session['user']['gold']-=65;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f8':
output("Du nimmst dir ein Rindsteak und beißt hinein. Dabei beißt du auf sechzehn Goldstücke.`n");
$session['user']['gold']-=69;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
case 'f9':
output("Du nimmst die Schweinshaxe und beißt hinein. Dabei beißt du auf siebzehn Goldstücke.`n");
$session['user']['gold']-=73;
//$session['user']['ess']+=1;
addnav("Bin satt.","mg.php");
addnav("Hab Hunger.","ebasar.php");
break;
}
output("`n`n`2M`git `2a`gnderen `2r`geden:`n");
viewcommentary("ebasar","reden",5);
page_footer();
?>

