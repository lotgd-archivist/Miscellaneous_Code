<?php
#############
# idee by Nyx von http://logd.gloth.org
#############
# script by Azrael von http://logd.gloth.org
#############
# v1.0
#############
# Überarbeitet von Tidus für www.kokoto.de
#############
# v.2.0
#############
if (!isset($session)) exit();

page_header("Die Schlange");
output('`b`c`n`dD`^i`de `^S`dc`^h`dl`^a`dn`^g`de`c`b`n`n');
if ($_GET['op']=="leave"){
output("`^Du entfernst dich so schnell es geht in irgendeine Richtung, da du nachdem du aufgehört hast zu rennen dich erstmal neu Orientieren musst verlierst du 1 Waldkampf.");
$session['user']['specialinc']='';
$session['user']['turns']--;
}else if ($_GET['op']=='right'){
switch (mt_rand(1,4))
{
case 1:
output('Du hast Glück und die Schlange huscht an dir vorbei.`n Du gewinnst an Erfahrung.');
addnav('Zurück in den Wald','forest.php?op=leave');
$session['user']['specialinc']='schlange.php';
if ($session['user']['experience']==0){
   $session['user']['experience']+=500;
}else{
$exp=$session['user']['experience']0.02;
$session['user']['experience']+=$exp;
}
break;
case 2:
output("`4Leider warst Du zu langsam und die Schlange beisst Dich. Du wurdest nicht vergiftet, aber verlierst ein paar Lebenspunkte und aus Ärger über deinen Fehler verlierst Du einen Charmepunkt.");
addnav("Zurück in den Wald","forest.php?op=leave");
$hits=$session['user']['hitpoints']0.2;
$session['user']['hitpoints']-=$hits;
$session['user']['charm']-=1;
$session['user']['specialinc']='schlange.php';
break;
case 3:
output('Du bist der Schlange entkommen und siehst vor dir etwas auf dem Boden glitzern.`n Du findest ein wenig Gold.');
addnav('Zurück in den Wald','forest.php?op=leave');
$session['user']['gold']+=100;
$session['user']['specialinc']='schlange.php';
break;
case 4:
output('Die Schlange hat dich gebissen und ihr Gift ist in deinem Blut.`n Du fühlst Dich geschwächt!');
$session['bufflist']['poison2']=array(
						"name"=>"`@Schlangengift",
						"roundmsg" => "Das Gift macht es Dir schwer zu kämpfen",
						"rounds"=>50,
						"wearoff"=>"Die Wirkung des Gifts lässt nach!",
                        "atkmod" => "0.85",
						"defmod" => "0.85",
						"minioncount"=>1,
                        "maxgoodguydamage"=>$session['user']['level']2,
                        "effectmsg"=>"`7Das Gift verursacht `^{damage}`7 Schadenspunkte!",
						"activate"=>"roundstart");
addnav('Zurück in den Wald','forest.php?op=leave');
$session['user']['specialinc']='schlange.php';
break;
}
}else if ($_GET['op']=="left"){
$session['user']['specialinc']='schlange.php';
switch(mt_rand(1,3))
{
case 1:
output('Die Schlange hat dich gebissen und ihr Gift ist in deinem Blut.`n Du fühlst Dich geschwächt!');
$session['bufflist']['poison2']=array(
						"name"=>"`@Schlangengift",
						"roundmsg" => "Das Gift macht es Dir schwer zu kämpfen",
						"rounds"=>50,
						"wearoff"=>"Die Wirkung des Gifts lässt nach!",
                        "atkmod" => "0.85",
						"defmod" => "0.85",
						"minioncount"=>1,
                        "maxgoodguydamage"=>$session['user']['level']2,
                        "effectmsg"=>"`7Das Gift verursacht `^{damage}`7 Schadenspunkte!",
						"activate"=>"roundstart");
addnav("Zurück in den Wald","forest.php?op=leave");

break;
case 2:
output('Du bist der Schlange entkommen und siehst vor dir etwas auf dem Boden glitzern.`n Du findest einen Edelstein.');
addnav("Zurück in den Wald","forest.php?op=leave");
$session['user']['gems']+=1;
break;
case 3:
output('Du hast Glück und die Schlange huscht an dir vorbei.`n Du gewinnst an Erfahrung.');
addnav("Zurück in den Wald","forest.php?op=leave");
if ($session['user']['experience']==0){
   $session['user']['experience']+=500;
}else{
$exp=$session['user']['experience']0.02;
$session['user']['experience']+=$exp;
}
break;
}
}else if ($_GET['op']=='front'){
switch(mt_rand(1,3))
{
case 1:
output('Du bist zwar der Schlange entwischt, jedoch bist Du bei deinem Sprung ausgerutscht und hast Dich mit Erde beschmutzt. Vor lauter Scham verlierst Du einen Charmepunkt.');
addnav("Zurück in den Wald","forest.php?op=leave");
$session['user']['charm']-=1;
$session['user']['specialinc']='schlange.php';
break;
case 2:
output('Du hast Glück und die Schlange huscht an dir vorbei.`n Du gewinnst an Erfahrung.');
addnav("Zurück in den Wald","forest.php?op=leave");
if ($session['user']['experience']==0){
   $session['user']['experience']+=500;
}else{
$exp=$session['user']['experience']0.02;
$session['user']['experience']+=$exp;
}
$session['user']['specialinc']="schlange.php";

break;
case 3:
output('Die Schlange hat dich gebissen und ihr Gift ist in deinem Blut.`n Du fühlst Dich geschwächt!');
$session['bufflist']['poison2']=array(
						"name"=>"`@Schlangengift",
						"roundmsg" => "Das Gift macht es Dir schwer zu kämpfen",
						"rounds"=>50,
						"wearoff"=>"Die Wirkung des Gifts lässt nach!",
                        "atkmod" => "0.85",
						"defmod" => "0.85",
						"minioncount"=>1,
                        "maxgoodguydamage"=>$session['user']['level']2,
                        "effectmsg"=>"`7Das Gift verursacht `^{damage}`7 Schadenspunkte!",
						"activate"=>"roundstart");
addnav("Zurück in den Wald","forest.php?op=leave");
$session['user']['specialinc']='schlange.php';
break;
}
}else{
output('Auf deinem Weg durch den Wald schlängelt plötzlich eine giftig scheinende Schlange aus dem Gebüsch. Überrascht bleibst du stehen und beobachtest sie angespannt. Es scheint, als wolle sie Dich angreifen. Nun liegt es an dir, ihr auszuweichen. In welche Richtung wirst Du springen?');

    addnav('R?Rechts','forest.php?op=right');
    addnav('L?Links','forest.php?op=left');
    addnav('G?Geradeaus','forest.php?op=front');
    $session['user']['specialinc']='schlange.php';
}
?>
