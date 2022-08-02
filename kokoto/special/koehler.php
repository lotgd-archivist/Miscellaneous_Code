<?php
//*-------------------------*
//| koehler.php |
//| Scriptet by |
//| °*Amerilion*° |
//| steffenmischnick@gmx.de |
//*-------------------------*
//Version 1.1
//Überarbeitet by Tidus V1.2 ^^ www.kokoto.de

if (!isset($session)) exit();
output('`n`c`b`#Der Köhler`b`c`n`n');
if($_GET['op']=='huette'){
$session['user']['specialinc']='';
$ming=mt_rand(200,750);
output('`6Du gehst zu der kleinen, unter einer Tanne gelegenen Hütte und klopfst zaghaft an die kleine Tür.');
switch(mt_rand(1,4)){
case 1:
case 2:
case 3:
output('`n`n`6Hm, es dürfte niemand zu Hause sein. Du öffnest die Tür und gehst in die Hütte.');
switch(e_rand(1,5)){
case 1:
case 2:
output("`6Nach einiger Zeit findest du eine kleine Truhe.`n`nDu öffnest sie und findest `^".$ming." Gold`6, `6außerdem noch einen `^Edelstein`6.`n`n`\$Du verlierst einen Waldkampf.`0");
$session['user']['turns']-=1;
$session['user']['gold']+=$ming;
$session['user']['gems']+=1;
break;
case 3:
case 4:
output('`6Du durchsuchst gerade eine stinkende Bettstadt, als du ein Grunzen an der Tür hörst. Du drehst dich um und siehst eine schwarze Gestalt, augenscheinlich der Köhler, welcher dich schlecht gelaunt ansieht. Er wirft seine Axt nach dir, welche dich aber zum Glück nicht trifft. Du fliehst so schnell du kannst und bleibst nach einiger Zeit im Wald stehen. Vor Schreck bist du noch ganz zittrig und machst erst mal eine längere Pause. `n`n`$Du hast dadurch einige Waldkämpfe vergeudet!`0');

if($session['user']['turns']>4){
$session['user']['turns']-=4;
}else{
$session['user']['turns']=0;
}
break;
case 5:
output('`6Nach einiger Zeit gibst du entäuscht auf, anscheinend gibt es hier nichts Wertvolles.');
if($session['user']['turns']>4){
$session['user']['turns']-=4;
}else{
$session['user']['turns']=0;
}
break;
}
break;
case 4:
output('`6Dir öffnet ein alter, schmutzverschmierter Mann. Er scheint von deinem Besuch erfreut zu sein und drückt dir eine Kippe voll Kohle in die Hand.`n`n`7"Endlich bist du da! Diese Kohle muss noch heute auf den Markt, das Gold bring mir das nächste Mal mit." `n`n`6Du schaust noch verwirrt auf die geschlossene Tür, als dir die Kippe wieder einfällt, du nimmst sie seufzend, gehst zum Markt und behälst den Erlös, da du die Lichtung nicht mehr findest. `n`n`$Du hast dadurch einige Waldkämpfe verloren!`0 `n`n`^Dafür bist du um 500 Gold reicher!`0');
if($session['user']['turns']>3){
$session['user']['turns']-=3;
}else{
$session['user']['turns']=0;
}
$session['user']['gold']+=500;
break;
}
}else if($_GET['op']=='haufen'){
$session['user']['specialinc']='';
output('`6Du trittst näher an den glühenden Haufen und...`n`n');
switch(mt_rand(1,4)){
case 1:
output('`$verbrennst wegen deinem Übermut.');
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session['user']['gold']=0;
$session['user']['experience']0.97;
addnews($session['user']['name']." wurde verkohlt im Wald gefunden.`0");
addnav('Tägliche News','news.php');
break;
case 2:
case 3:
output('`qerholst dich in der Wärme ein wenig. `^`n`nDu hast 3 Waldkämpfe mehr für heute.`0');
$session['user']['turns']+=3;
break;
case 4:
output('`5findest einen Edelstein am Rande des Feuers. Du nimmst ihn und holst dir dabei eine Brandwunde.');
$session['user']['gems']++;
$session['bufflist']['feuer'] = array("name"=>"`4Brandwunde",
"rounds"=>25,
"wearoff"=>"`2Deine Hand ist wieder verheilt",
"defmod"=>0.80,
"atkmod"=>0.80,
"roundmsg"=>"`4Deine Hand brennt wie Feuer!",
"activate"=>"defense");
break;
}
}else if($_GET['op']=="zurueck"){
output('`6Du verlässt die Lichtung, da du lieber nicht dem Köhler begegnen willst. Denn in der Kneipe erzählen sie sich üble Geschichten über ihn...');
$session['user']['specialinc']='';
}else{
output('`6Du streifst wie immer durch den Wald als dir der Geruch von brennendem Holz in die Nase steigt. Du kommst kurze Zeit später auf eine Lichtung, von der Rauch zu kommen scheint. Du sieht dich um und erblickst eine kleine Holzhütte und einen großen Haufen glühendes Holzes, das bald zu Holzkohle werden wird.');
addnav('Die Lichtung');
$session['user']['specialinc']='koehler.php';
addnav('Zur Hütte','forest.php?op=huette');
addnav('Zum Kohlehaufen','forest.php?op=haufen');
addnav('Zurück in den Wald','forest.php?op=zurueck');
}
?>