<?php
//*-------------------------*
//|        Scriptet by      |
//|       °*Amerilion*°     |
//| steffenmischnick@gmx.de |
//|   Fixed & Modificated   |
//|           by Hadriel    |
//|Angepasst, verschönert   |
//| by Tidus (www.kokoto.de)|
//*-------------------------*

if (!isset($session)) exit();

if ($_GET['op']=='zurueck'){
$session['user']['specialinc']='';
switch(mt_rand(1,8)){
case '1':
case '2':
case '3':
case '4':
case '5':
output('`2Du findest den richtigen weg und außerdem noch `n');
             switch(mt_rand(1,4)){
             case '1':
             output('`9einige Edelsteine!');
             $session['user']['gems']+=2;
             break;
             case '2':
             case '3':
             case '4':
             output('`9ein wenig Gold!');
             $session['user']['gold']+=500;
             break;
             }
break;
case '6':
case '7':
case '8':
output('`2Du findest den weg nicht mehr und kommst jämmerlich im Sumpf um dein Leben');
$session['user']['alive']=false;
$session['user']['hitpoints']=0;
$session['user']['gold']=0;
//lostexp(3);
addnews('`&'.$session['user']['name'].' `2ist jetzt eine tolle Moorleiche!');
addnav('Tägliche News','news.php');
break;
}
}else if($_GET['op']=='sumpf'){
output('`2Du suchst dir einen Weg durch den undurchdringlich scheinenden Sumpf. Nach einiger Zeit bemerkst du`n');
switch(mt_rand(1,5)){
case '1':
case '2':
$session['user']['specialinc']='';
output('`2das du nicht mehr weiterkannst und dich ausruhen musst. Dazu setzt du dich auf einen Stein. Du siehst dich in ruhe um damit du dich wieder orientieren kannst und bemerkst das du im Kreis gelaufen bist. Daher denkst du das es besser wäre den Weg zurück in den Wald zu nehmen, trotzdem verlierst du einen Waldkampf.');
$session['user']['turns']-=1;
addnav('Zurück','forest.php');
break;
case '3':
case '4':
case '5':
output('`2das du eine seltsame Musik hörst. Sie hört sich irgendwie an als wenn sie nicht aus dieser Zeit stammt, und du vernimmst einige Zeilen aus dem Text`c`n`$...`n`n`iDoch nun bin ich bereit zu vergessen`n was mir wichtig war`nEin Schritt, ein Stoss, ein Atemzug`n mein Weg in eine neue Welt`nbereit zum Sieg über dieses Leben `nwenn sich Freud\' zu Schmerz gesellt`n...`n`nCoprytight by L\'ame Immortelle - Gezeiten`i`n`n`c `2Du Verspürst eine dunkle Macht in dir, und die Lust viele Monster damit zu töten und ziehst sogleich los in den Wald. `n`n`n`$Deine dunklen Künste steigen um 3 Level!');
addnews($session['user']['name'].'`$ lauschte einer magischen Melodie und wurde stärker!');
$session['user']['darkarts']+=3;
$session['user']['darkartuses']++;
$session['user']['specialinc']='';
addnav('Wald','forest.php');
break;
}
}else{
output('`n`c`b`1Der Sumpf`b`c`n`n `2Während deiner Wanderung durch den Wald zieht ein dichter Nebel auf und du bemerkst das du auf einen schmalen Pfad gehst der durch ein Sumpfgelände führt... du musst dich entscheiden ob du lieber zurück zum Wald gehst oder weiter in den Sumpf auch wenn dich das viel kosten kann...');
$session['user']['specialinc']='seltsamemusik.php';
addnav('Weiter zum Sumpf','forest.php?op=sumpf');
addnav('Zurück zum Wald','forest.php?op=zurueck');
}
?>