<?php
/*
Squall
21//11//2005
text und boni dürfen nicht verändert,odin an den werten schon ist jeden selbst überlassen
Rechtschreibfehler darf jeder,sollte er einen finden,beheben,ebenso festlegen,wieviel ep man verliert.
www.vendaldrachennest.de
nighti215@hotmail.de
überarbeitet von Tidus www.kokoto.de
*/

require_once"common.php";
page_header("Odin");

if ($_GET['op']=='vers'){
switch(mt_rand(1,7)){
case '1':

$sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tOdins-Ritterschild','Rüstung','".$session['user']['acctid']."','30','10','Rüstung mit 30 Verteidigungswert')";
db_query($sql);
output('`4Was für ein Glück du hast, Odin war so besessen von seinem Ziel, dass er dich völlig übersah `4Neben deinem Versteck sieht du etwas glitzern... WOW es ist Odins Schild!!');
$session['user']['specialinc']='';
addnav('Zurück','forest.php');
break;
case '2':
case '3':
case '4':
output('`4Was für ein Glück du hast, Odin war so besessen von seinem Ziel, dass er dich völlig übersah `4Du hast etwas durch deine Todesangst gelernt und bist somit Stärker geworden');
$session['user']['defence']+=3;
$session['user']['specialinc']='';
addnav('Zurück','forest.php');
break;
case '5':
case '6':
case '7':
output('`4Du merkst langsam,das es keine gute Idee war sich zuverstecken.Du hörst ein leises Zischen und dir kommt das bekannt vor...es ist eine Giftschlange die auf deinen Rücken hochklettert und zu beißt `4Du schreist einen Moment auf und langsam verlierst du dein Bewußtsein');
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session['user']['experience']*=0.95;
addnav('Tägliche News','news.php');
$session['user']['specialinc']='';
addnews($session['user']['name'].' `Qstarb durch den Biss einer Cobra');
break;
}
}else if($_GET['op']=='steh'){
$session['user']['specialinc']='Odin.php';
switch(mt_rand(1,8)){
case '1':
case '2':
output('`4Der mächtige Odin übersieht dich, jedoch ist das auch ein Problem für dich da sein Hammer den er in deine Richtung zufällig warf, dich tötlich traf. Du bist tot');
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session['user']['experience']*=0.95;
addnav('News','news.php');
$session['user']['specialinc']='';
addnews($session['user']['name'].' `Qstarb durch Odins Hammer');
break;
case '3':
case '4':
case '5':
case '6':
output('`4Odin erscheint noch furchteinflößender als er vor dir steht und dich zum Kampf auffordert. Du hast keine andere Wahl du musst kämpfen');
$badguy = array(
                                "creaturename"=>"`^Odin`0",
                                "creaturelevel"=>$session['user']['level']1,
                                "creatureweapon"=>"`^Odins Hammer",
                                "creatureattack"=>$session['user']['attack']1,
                                "creaturedefense"=>$session['user']['defence']1,
                                "creaturehealth"=>round($session['user']['maxhitpoints']1.05,0),
                                "diddamage"=>0);
                        $session['user']['badguy']=createstring($badguy);
                        $session['user']['specialinc']='odin.php';
                        $battle=true;
                        $session['user']['specialinc']='';
                        break;
case '7':
case '8':
output('`4Odin bewundert deinen Mut und schenkt dir als Zeichen seiner Wertschätung seinen legendären Hammer');
$sql="INSERT INTO items (name,class,owner,value1,gold,description) VALUES ('`tOdins Hammer','Waffe','".$session['user']['acctid']."','30','10','Waffe mit 30 Angriffswert')";
db_query($sql);
$session['user']['specialinc']='';
addnav('Zurück','forest.php');
break;
}
}else if ($_GET['op'] == 'fight'){
$session['user']['specialinc']='Odin.php';
$battle=true;
}else{
$session['user']['specialinc']='odin.php';
output('`4Du ziehst durch den Wald und auf deinem Weg kommst du an einer alten Rune vorbei,du bleibts kurz stehen und blickts zur Rune Du fühlst dich überhaupt nicht wohl und merkst ein leichtes pochen deines Herzes `4Es schlägt immer weiter als du eine große Gestalt auf dich zukommen siehts.Doch du kommst nicht weit...Odin erblickt dich...und es scheint so als ob er auf dich zukommt `2Was tust du nun ?');
addnav('`qWeinen und sich verstecken','forest.php?op=vers');
addnav('`qDich mutig stellen','forest.php?op=steh');
}
if ($battle){
include ("battle.php");
if ($victory){
$exp=$session['user']['experience']0.09;
output('Du hast `^Odin geschlagen und erhältst $exp Erfahrung');
$session['user']['experience']+=$exp;
$session['user']['specialinc']='';
$badguy=array();
$session['user']['badguy']='';
addnav('Weiter','forest.php');
}
elseif ($defeat){
output('`4Du liegst erschöpft auf den Boden und Odin schlägt dir mit seinem Hammer auf dein Kopf ein. Du fällst in Unmacht!');
addnews($session['user']['name'].' `!wurde brutal von Odin erschlagen');
output('`n`4Du bist tot.`n Du verlierst 10% deiner Erfahrung und alles Gold.`n Du kannst morgen weiterspielen.');
$session['user']['gold']=0;
$session['user']['experience']=round($session['user']['experience'].9,0); 
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session['user']['specialinc']='';
$session['user']['reputation']--;
addnav('Tägliche News','news.php');
}
else{
fightnav(true,false);
}
}
?>
