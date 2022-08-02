<?php
//°-------------------------°
//| daemon.php |
//| Script by |
//| xitachix |
//| mcitachi@web.de |
//°-------------------------°
//http://logd.macjan.de/
//Überarbeitet von Tidus für www.kokoto.de
if (!isset($session)) exit();
output("`n`c`tDer finstere Waldpfad`c`n`n");
if ($_GET['op']=='go'){
output('`n`tDu gehst durch den dunklen Pfad den du gewählt hast und siehst dich um. `n`tAlles um dich drum herum scheint finster zu sein.`nDu kannst dich entscheiden: du kannst gehen..`nOder du kannst den Pfad weitergehen.');
$session['user']['specialinc']='daemon.php';
addnav('Weiter','forest.php?op=w');
addnav('Zurück','forest.php?op=z');

}else if ($_GET['op']=='w'){
output('`n`tDu gehst den Weg weiter und siehst plötzlich einen Dämon hinter einem Baum stehen `n`tEr sieht dich an und geht auf dich zu `n`#Gib mir einen Edelstein und ich werde dir einen gefallen gewähren `n`tDu stehst nun vor der Entscheidung: Entweder du gibst ihm einen Edelstein oder du verpasst ihm einen Tritt.');
$session['user']['specialinc']='daemon.php';
if ($session['user']['gems']>1) addnav('Gib ihm einen Edelstein','forest.php?op=gib'); else addnav('Gib ihm einen Edelstein (Du hast keinen)');
addnav('Verpass ihm einen Tritt','forest.php?op=tritt');
}else if ($_GET['op']=='gib'){
output('`n`tDu holst einen Edelstein aus deinem Beutel und reichst ihn dem Dämon. `n`tDer Dämon steigt auf und fliegt davon, hat dich jedoch nicht entlohnt.. `n`tVoller Wut trittst du gegen einen auf dem Boden liegenden Stein.');
$session['user']['gems']-=1;
switch(mt_rand(1,10)){
case 1:
case 2:
output('`n`3Du hast dir den Fuß gestoßen und dich verletzt. Dadurch verlierst du einige Lebenspunkte!');
$hurt = mt_rand($lvl,3$lvl);
$session['user']['hitpoints']-=$hurt;
break;
case 3:
case 4:
output('`n`3Du siehst unter dem Stein eine Pergamentrolle. Durch das Lesen der Rolle erhälst du Erfahrung.');
$exp=$session['user']['experience']0.25;
$session['user']['experience']+=$exp;
break;
case 5:
case 6:
output('`n`3Du findest unter dem Stein ein paar Edelsteine');
$gem=mt_rand(3,6);
$session['user']['gems']+=$gem;
break;
case 7:
case 8:
output('`n`3Der Stein rollt weg und du zeigst deine Enttäuschung offen kund. Jeder sieht es dir an und so verlierst du ein wenig Charme');
$charm=mt_rand(2,5);
$session['user']['charm']-=$charm;
break;
case 9:
case 10:
output('`n`$Du findest eine Schatztruhe unter dem Stein. Du bist heute wohl der Glücklichste Mann der Welt. `n`QDu findest einige Edelsteine:`n`QAusserdem eine Menge Gold,`n`3Und ein paar Schriftrollen!`n`3Du vollführst einen Freudentanz und verlierst einen Waldkampf!');
$session['user']['gems']+=3;

$gold = $session['user']['gold']($session['user']['level']30);
$session['user']['gold']+=$gold;

$exp2=$session['user']['experience']0.20;
$session['user']['experience']+=$exp2;

$session['user']['turns']-=1;
addnews("`#".$session['user']['name']." `0 hat einen riesen Schatz unter einem Stein gefunden.");
break;
$session['user']['specialinc']='';
}
}else if ($_GET['op']=='tritt'){
output('`tDu meckerst den Dämon an und holst zum Tritt aus Warum verlangt er auch einen Edelstein von dir?');
switch(mt_rand(1,4)){
case 1:
case 2:
output('`n`$Der Dämon schreit vor Schmerzen auf und du fühlst dich richtig gut! `n`3Du erhälst Charmepunkte');
$charm=mt_rand(2,3);
$session['user']['charm']+=$charm;
break;
case 3:
case 4:
output('`n`$Der Dämon sieht deinen Tritt, weicht aus und ersticht dich mit seinem Schwert!`n`$ Du bist tot!');
addnews('`Q'.$session['user']['name'].' `0 wurde in einer finsteren Ecke von einem `tDämon`0 erstochen.');
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
addnav("Tägliche News","news.php");
break;
$session['user']['specialinc']='daemon.php';
}
}else if ($_GET['op']=='z'){
$session['user']['specialinc']='';
output('Du gehst zurück in den Wald, wobei du keine Zeit verlierst.');
}else{
output('`n`tDu schlenderst durch den Wald, auf der Suche nach Monstern `n`tDoch statt den Monstern findest du einen finsteren Waldpfad `n`tGehst du den Pfad, oder verschwindest du lieber um Monster zu töten?');
$session['user']['specialinc']='daemon.php';
addnav('Den Pfad gehen','forest.php?op=go');
addnav('Zurück','forest.php?op=z');
}
?>