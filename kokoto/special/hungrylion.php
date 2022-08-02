<?php

//Hungriger Löwe
//Idee: Des
//umgesetzt von Des
//überarbeitet von Tweety
//12.06.2005
// überarbeitet von Tidus www.kokoto.de
if (!isset($session)) exit();

if ($_GET['op']=='fuettern'){
output('Du sammelst all Deinen Mut zusammen und näherst dich dem Löwen mit einem Stück Fleisch in der Hand.');
switch (mt_rand(1,4)){
case '1':
case '2':
case '3':
output('Der Löwe zögert einen Augenblick und frisst dann das Fleisch aus deiner Hand. `n`qDer Adrenalinstoss fliesst noch durch deinen Körper und du fühlst dich bereit für ein paar Kämpfe mehr`0');
addnav('Zurück in den Wald','forest.php');
$session['user']['turns']+=2;
break;
case '4':
output('Der Löwe scheint wirklich hungrig gewesen zu sein, denn als Du Dich ihm mit dem Stück Fleisch näherst, springt er wild auf Dich und zerfleischt Dich. `n`qDein letzter Gedanke ist: `$"Verdammt, jetzt bin ich seine Hauptspeise"`0`nDu verlierst all dein Gold.`nWenigstens hast du etwas daraus gelernt und erhältst Erfahrungspunkte.');
addnav('Tägliche News','news.php');
$session['user']['experience']=$session['user']['experience']1.10;
addnews($session['user']['name'].' `4 wurde von einem hungrigen Löwen zerfleischt`0 ');
$session['user']['gold']=0;
$session['user']['hitpoints']=0;
$session['user']['alive']=false;
break;

}
}else if ($_GET['op']=='nichtfuettern'){
output('`qDu traust dich nicht zu nah an das Tier und wendest dich ab.');
addnav('Schnell zurück','forest.php');
}else{
output('Bei einem deiner Streifzüge durch den Wald siehst Du einen knurrenden Löwen. Du denkst Dir das er Hunger haben muss.');
output('`nTraust du dich dem Löwen näher zu kommen und ihn zu füttern?');
addnav('Füttern?');
addnav('Ja','forest.php?op=fuettern');
addnav('Nein','forest.php?op=nichtfuettern');
$session['user']['specialinc'] = 'hungrylion.php';

}
page_footer();
?>