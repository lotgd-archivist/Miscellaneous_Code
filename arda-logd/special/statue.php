<?php 
/* 
Statue 
by Vaan 
12//4//2004 
Erstmal erschienen auf http://cop-logd.de/logd
*/ 

require_once"common.php"; 
if (!isset($session)) exit(); 
page_header("Seltsame Statue"); 
if ($HTTP_GET_VARS['op']==""){ 
$session['user']['specialinc']="statue.php"; 
output("Als du so deinen Weg entlang gehst kommst du an einer riesigen Statue vorbei an der ein großes Schild angelehnt ist. Du versuchst zu entziffern was auf dem alten Schild steht."); 
output("Du liest: \"In mir ist etwas verborgen, in mir ist was versteckt, in mir ist etwas gutes oder etwas böses! Wenn du es herausfinden willst was es ist guck in mich hinein.\""); 
output("Was willst du machen?"); 
addnav("In die Statue kriechen und nach irgend einem Gegenstand suchen","forest.php?op=such"); 
addnav("Einfach weiter gehen","forest.php?op=gehe"); 
} 
else if ($HTTP_GET_VARS['op']=="such"){ 
output("Du fängst an dem Einstieg  der Statue an zu suchen. Nach einiger Zeit findest du ein Loch. Du steckst deinen Arm durch das Loch und bekommst etwas zu fassen."); 
switch(e_rand(1,13)){ 
case 1: 
case 2: 
output("Es scheint so als ob der Gegenstand festgebunden wär. Es dauert eine Ewigkeit bis du den Gegenstand hinaus bekommen hast."); 
output("Da du so lange gebraucht hast verlierst du für heute einen Waldkampf"); 
output("Doch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt."); 
$session['user']['turns']-=1; 
$session['user']['attack']+=3; 
$session['user']['specialinc']=""; 
addnav("Zurück in den Wald","forest.php"); 
break; 
case 3: 
case 4: 
output("Es scheint so als ob der Gegenstand festgebunden wär. Es dauert eine Ewigkeit bis du den Gegenstand hinaus bekommen hast."); 
output("Da du so lange gebraucht hast verlierst du für heute einen Waldkampf"); 
output("Doch jetzt liegt er in deiner Hand. Du schaust dir den kleinen Gegenstand an und fühlst dich gestärkt."); 
$session['user']['turns']-=1; 
$session['user']['defence']+=3; 
$session['user']['specialinc']=""; 
addnav("Zurück in den Wald","forest.php"); 
break; 
case 5: 
case 6: 
output("Du ziehst deinen Arm samt Gegenstand aus dem Loch und schaust ihn dir an."); 
output("Ein stechender Schmerz, der von der Hand kommt in dem der kleine Gegenstand liegt kommt, lässt dich zusammen sacken. Als du wieder aufwachst fühlst du dich geschwächt."); 
$session['user']['attack']-=3; 
$session['user']['specialinc']=""; 
addnav("Zurück in den Wald","forest.php"); 
break; 
case 7: 
case 8: 
output("Du ziehst deinen Arm samt Gegenstand aus dem Loch und schaust ihn dir an."); 
output("Ein stechender Schmerz, der von der Hand kommt in dem der kleine Gegenstand liegt kommt, lässt dich zusammen sacken. Als du wieder aufwachst fühlst du dich geschwächt."); 
$session['user']['defence']-=3; 
$session['user']['specialinc']=""; 
addnav("Zurück in den Wald","forest.php"); 
break; 
case 9: 
case 10: 
output("Als du dir das kleine Ding in deiner Hand anschust bekommst du aus irgendeinem grund Glücksgefühle und willst kämpfen."); 
output("Du erhälst eien zusätzlichen Waldkampf."); 
$session['user']['turns']+=1; 
$session['user']['specialinc']=""; 
addnav("Zurück in den Wald","forest.php"); 
break; 
case 11: 
case 12: 
output("Du ziehst und ziehst und ziehst aber das kleine Ding in der Satur will einfach nicht raus kommen."); 
output("Du verlierst einen Waldkampf."); 
output("Wütend gehst du zurück in den Wald."); 
$session['user']['turns']-=1; 
$session['user']['specialinc']=""; 
addnav("Zurück in den Wald","forest.php"); 
break; 
case 13: 
case 14: 
output("Grade als du den Gegenstand aus der Statue rausziehen willst spürst du, dass du von etwas gebissen worden bist."); 
output("Du bist am Gift einer Giftigenschlange gestorben."); 
$session['user']['alive']=false; 
$session['user']['hitpoints']=0; 
$session['user']['experience']*=0.95; 
addnav("Tägliche News","news.php"); 
$session['user']['specialinc']=""; 
addnews($session['user']['name']." starb durch eine Giftschlange"); 
break; 
} 
} 
else if ($HTTP_GET_VARS['op']=="gehe"){ 
//$session['user']['specialinc']="statue.php"; 
output("Mit schnellen Schritten verlässt du den Ort."); 
addnav("Schnell zurück in den Wald","forest.php"); 
} 
?> 