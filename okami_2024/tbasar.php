
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Schankstand";
page_header("Schankstand");
switch($_GET['op']){
case '':
output("`c`b`rD`Ru kommst zum `rS`Rchankstand und siehst dich dort um. `rE`Rine junge `rF`Rrau zeigt dir die `rP`Rreisliste.`b`c`n
`tFrühlingszauber `&kostet 5 Gold.`n
`tGrog `&kostet 10 Gold.`n
`tTiger `&kostet 15 Gold.`n
`tSex on the Beach `&kostet 20 Gold.`n
`tSummer Kiss `&kostet 25 Gold.`n
`tGrüne Witwe `&kostet 30 Gold.`n
`tCaribean Sunrise `&kostet 35 Gold.`n
`tKatergift `&kostet 40 Gold.`n
`tRed Rose `&kostet 45 Gold.`n
`tHeißer Caipirinha `&kostet 5 Gold.`n
`tAlmkaffee `&kostet 10 Gold.`n
`tVulcano `&kostet 15 Gold.`n
`tAbendsonne `&kostet 20 Gold.`n
`tFlip Flap `&kostet 25 Gold.`n
`tHighlife `&kostet 30 Gold.`n
`tOcean's 12 `&kostet 35 Gold.`n 
`tSanfter Engel `&kostet 40 Gold.`n
`tEl Diabolo `&kostet 45 Gold.`n`n");
if ($session['user']['drunkenness']<99){
addnav("Getränke");
if ($session['user']['gold']>4){
addnav("Frühlingszauber","tbasar.php?op=tr1");
}
if ($session['user']['gold']>9){
addnav("Grog","tbasar.php?op=tr2");
}
if ($session['user']['gold']>14){
addnav("Tiger","tbasar.php?op=tr3");
}
if ($session['user']['gold']>19){
addnav("Sex on the Beach","tbasar.php?op=tr4");
}
if ($session['user']['gold']>24){
addnav("Summer Kiss","tbasar.php?op=tr5");
}
if ($session['user']['gold']>29){
addnav("Grüne Witwe","tbasar.php?op=tr6");
}
if ($session['user']['gold']>34){
addnav("Caribean Sunrise","tbasar.php?op=tr7");
}
if ($session['user']['gold']>39){
addnav("Katergift","tbasar.php?op=tr8");
}
if ($session['user']['gold']>44){
addnav("Red Rose","tbasar.php?op=tr9");
}
if ($session['user']['gold']>4){
addnav("Heißer Caipirinha","tbasar.php?op=ti1");
}
if ($session['user']['gold']>9){
addnav("Almkaffee ","tbasar.php?op=ti2");
}
if ($session['user']['gold']>14){
addnav("Vulcano ","tbasar.php?op=ti3");
}
if ($session['user']['gold']>19){
addnav("Abendsonne","tbasar.php?op=ti4");
}
if ($session['user']['gold']>24){
addnav("Flip Flap","tbasar.php?op=ti5");
}
if ($session['user']['gold']>29){
addnav("Highlife","tbasar.php?op=ti6");
}
if ($session['user']['gold']>34){
addnav("Ocean's 12","tbasar.php?op=ti7");
}
if ($session['user']['gold']>39){
addnav("Sanfter Engel","tbasar.php?op=ti8");
}
if ($session['user']['gold']>44){
addnav("El Diabolo","tbasar.php?op=ti9");
}
}
break;

case 'tr1':
output("Du trinkst genüßlich deinen Frühlingszauber.");
$session[user][drunkenness]+=2;
//$session['user']['trink']+=1;
$session['user']['gold']-=5;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr2':
output("Du trinkst genüßlich deinen Grog.");
$session[user][drunkenness]+=3;
//$session['user']['trink']+=1;
$session['user']['gold']-=10;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr3':
output("Du trinkst genüßlich deinen Tiger.");
$session[user][drunkenness]+=5;
//$session['user']['trink']+=1;
$session['user']['gold']-=15;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr4':
output("Du trinkst genüßlich deinen Sex on the Beach .");
$session[user][drunkenness]+=7;
//$session['user']['trink']+=1;
$session['user']['gold']-=20;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr5':
output("Du trinkst genüßlich deinen Summer Kiss.");
$session[user][drunkenness]+=10;
//$session['user']['trink']+=1;
$session['user']['gold']-=25;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr6':
output("Du trinkst genüßlich deine Grüne Witwe.");
$session[user][drunkenness]+=13;
//$session['user']['trink']+=1;
$session['user']['gold']-=30;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr7':
output("Du trinkst genüßlich deinen Caribean Sunrise.");
$session[user][drunkenness]+=15;
//$session['user']['trink']+=1;
$session['user']['gold']-=35;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr8':
output("Du trinkst genüßlich dein Katergift.");
$session[user][drunkenness]+=18;
//$session['user']['trink']+=1;
$session['user']['gold']-=40;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'tr9':
output("Du trinkst genüßlich deinen Red Rose.");
$session[user][drunkenness]+=20;
//$session['user']['trink']+=1;
$session['user']['gold']-=45;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti1':
output("Du trinkst genüßlich deinen Heißen Caipirinha.");
$session[user][drunkenness]+=2;
//$session['user']['trink']+=1;
$session['user']['gold']-=5;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti2':
output("Du trinkst genüßlich deinen Almkaffee.");
$session[user][drunkenness]+=4;
//$session['user']['trink']+=1;
$session['user']['gold']-=10;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti3':
output("Du trinkst genüßlich deinen Vulcano.");
$session[user][drunkenness]+=6;
//$session['user']['trink']+=1;
$session['user']['gold']-=15;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti4':
output("Du trinkst genüßlich deine Abendsonne.");
$session[user][drunkenness]+=8;
//$session['user']['trink']+=1;
$session['user']['gold']-=20;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti5':
output("Du trinkst genüßlich deinen Flip Flap.");
$session[user][drunkenness]+=10;
//$session['user']['trink']+=1;
$session['user']['gold']-=25;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti6':
output("Du trinkst genüßlich deinen Highlife.");
$session[user][drunkenness]+=12;
//$session['user']['trink']+=1;
$session['user']['gold']-=30;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti7':
output("Du trinkst genüßlich deinen Ocean's 12.");
$session[user][drunkenness]+=14;
//$session['user']['trink']+=1;
$session['user']['gold']-=35;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti8':
output("Du trinkst genüßlich deinen Sanften Engel.");
$session[user][drunkenness]+=17;
//$session['user']['trink']+=1;
$session['user']['gold']-=40;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;
case 'ti9':
output("Du trinkst genüßlich deinen El Diabolo.");
$session[user][drunkenness]+=20;
//$session['user']['trink']+=1;
$session['user']['gold']-=45;
addnav("Genung getrunken.","mg.php");
addnav("Hab durst.","tbasar.php");
break;

}

output("`n`n`tM`Tit `ta`Tnderen `tr`Teden:`n");
viewcommentary("tbasar","reden",5);
addnav("weiter");
addnav("Zurück","mg.php");
page_footer();
?>

