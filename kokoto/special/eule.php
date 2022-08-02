<?php
//*-------------------------*
//|          eule.php        |
//|        Scriptet by      |
//|       °*Amerilion*°     |
//| steffenmischnick@gmx.de |
//*-------------------------*


$gold=($session['user']['level']200);
$gems=round($session['user']['gems']0.05);
$gemmax=5;

if (!isset($session)) exit();

if ($_GET['op']=='' || $_GET['op']=='search'){
output('`n`c`b`8Die Eule`b`c`n`n `tAuf dem Ast einer Tanne siehst du eine kleine braune Eule sitzen, welche dich zu beobachten scheint. Nachdenklich siehst du ihr in die Augen und überlegst, ob sie das wohl wirklich tut. Du lachst leise, schließlich ist sie ja doch nur ein Tier, welches sich wohl überhaupt nicht für dich intressiert. Du hast dennoch das Gefühl, dass sie dich beobachtet und jeder deiner Bewegungen folgt.');
addnav('Die Eule');
$session['user']['specialinc']='eule.php';
addnav('Beobachte sie weiter','forest.php?op=beob');
addnav('Kümmer dich nicht um sie','forest.php?op=back');
}
if($_GET['op']=='beob'){
output('`tDu gehst einige Schritte weiter, und siehst über die Schulter, die Eule erhebt sich und fliegt');
switch(mt_rand(1,3)){
case '1':
       output('in eine unbestimmte Richtung weiter. Du schaust ihr noch nach bis sie verschwundern ist und gehst dann vergnügt über deine Phantasien weiter.');
       break;
case '2':
case '3':
       $session['user']['specialinc']='eule.php';
       output('dir nach. Sie setzt sich auf einen Ast eines nahen Baums und schaut dich weiter durchdringend an. Schließlich fliegt sie ein Stück weiter, und setzt sich bald wieder als scheine sie auf dich zu warten.');
       addnav('Gehe ihr nach','forest.php?op=w');
       addnav('Kümmer dich nicht um sie','forest.php?op=back');
break;
}
}
if($_GET['op']=='w'){
output('`tDu gehst ihr nach und sie führt dich immer weiter in den Wald hinein, bis sie schließlich an einem kleinen See haltmacht. Hier setzt sie sich auf einen kleinen Felsen und vollzieht eine wundersame Verwandlung. Es gibt einen kleinen Lichtblitz und anstelle der Eule sitzt'.($session['user']['sex']?"ein wunderschöner Elf":"eine wunderschöne Elfe").' auf der kleinen Insel. '.($session['user']['sex']?"Er":"Sie").' lacht dich an und erklärt dir vergnügt das  '.($session['user']['sex']?"er":"sie").' solche Spiele öfter spielt, und jeden Abenteuer der  '.($session['user']['sex']?"ihm":"ihr").' Folgt dafür im See der Edelsteine baden liese.');
$session['user']['specialinc']='eule.php';
addnav('Der Edelsteinsee');
addnav('Tauche','forest.php?op=ta');
addnav('In den Wald','forest.php?op=back');
}
if($_GET['op']=='ta'){
output('`tDu springst in den See und');
switch(mt_rand(1,5)){
case '1':
case '2':
       output(' ertrinkst in deiner Gier!`n`n`^Du bist tot.`nDu verlierst 3% deiner Erfahrung.');
       output("`nDu verlierst ".$gems." Edelsteine.");
       $session['user']['alive']=false;
       $session['user']['hitpoints']=0;
       $session['user']['gems']-=$gems;
       $session['user']['experience']0.97;
       addnews($session['user']['name'].'  `tertrank im See der Edelsteine');
       addnav('Tägliche News','news.php');
       break;
case '2':
case '3':
case '4':
case '5':
     if($gems<$gemmax){
     output("Du kannst mit ".$gems." Edelsteinen wieder aufauchen. Von  ".($session['user']['sex']?"dem Elf":"der Elfe")."");
     output('siehst du zu deinem Bedauern nichts mehr, doch du hoffst bald wieder mal den Weg hierher zu finden.');
     $session['user']['gems']+=$gems;
     }else{
     output("Du kannst mit ".$gemmax." Edelsteinen wieder aufauchen. Von  ".($session['user']['sex']?"dem Elf":"der Elfe")."");
     output('siehst du zu deinem Bedauern nichts mehr, doch du hoffst bald wieder mal den Weg hierher zu finden.');
     $session['user']['gems']+=$gemmax;
     }
break;
}
}
if($_GET['op']=='back'){
output('`tDir ist das alles ein wenig seltsam und du gehst wieder in den Wald zurück.');
}
?>