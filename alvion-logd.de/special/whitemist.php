
<?php

/*By Alradur
Ein leises Knacksen im Unterholz des Waldes, ein leises, unverständliches Wispern.. Und ganz plötzlich wabert dichter, weisser Nebel um dich herum auf und hüllt dich gänzlich ein.. Wo bist du hingeraten? Willst du versuchen, aus diesem scheinbar magischen Nebel zu entkommen oder wartest du, bis der Nebel verschwindet?

*/

require_once "common.php";

if (!isset($session)) exit();

if ($_GET[op]==""){

output("Bei deinen tagtäglichen Streifzügen durch den Wald hörst du ein leises Knacksen, welches wie aus weiter Ferne zu erklingen scheint.. Du störst dich allerdings nicht daran, sondern wanderst weiter.. Du zuckst erst zusammen, als auf ein kaum verständliches Murmeln dichter, weißer Nebel aufzieht und dich einhüllt..
Dir wird kalt und du setzt dich automatisch hin, du kannst dich nicht dagegen wehren.. Langsam zitterst du vor dich hin, während du überlegst, was du machen könntest.. In der Ferne steht ein Baum, den du neugierig anschaust..
Zweifelst du an deinem Verstand und stehst auf, um zu dem Baum zu gehen? Oder entscheidest du dich dafür, sitzen zu bleiben und zu warten, bis der Nebel verschwindet? Schreist du vor lauter Angst nach deinen Eltern?
Du hast nicht viel Zeit, um dich zu entscheiden, denn die Luft ist furchtbar kalt..");
addnav("Möglichkeiten");
addnav("Zum Baum gehen","forest.php?op=zumbaumgehen");
addnav("Warten","forest.php?op=warten");
addnav("E?Nach Eltern rufen","forest.php?op=nachelternrufen");

$session[user][specialinc] = "whitemist.php";

}

if ($_GET[op]=="zumbaumgehen"){

switch(e_rand(1,3)){

case 1:
output("Du stehst langsam auf und gehst in Richtung des Baumes.. Du gehst stundenlang weiter.. Der Baum will allerdings nicht einen Meter näher kommen..
Dir wird immer kälter und dein Körper wird immer schwächer.. Irgend wann schließt du die Augen, kippst um und bleibst liegen, für immer.. Du bist erfroren.
Gerade, als du stirbst, verschwindet der Nebel und deine Leiche scheint vor den Toren der Stadt zu liegen..");
$session[user][alive]=false;
$session[user][hitpoints]=0;

addnews("{$session['user']['name']} 's Leiche wurde vor den Stadttoren gefunden. Sie fühlt sich eiskalt an.");

addnav("News","news.php");

break;

case 2:
output("Du stehst langsam auf und gehst in Richtung des Baumes.. du brauchst nur wenige Minuten zu gehen, bevor du schon vor dem Baum stehst. Als du dich am Baum abstützt, um kurz durchzuatmen, verschwindet der Nebel ganz plötzlich und du siehst vor deinen Füßen ein helles Glitzern. Du hebst 2 Edelsteine und 1000 Gold auf!");
$session[user][gems]+=2;
$session[user][gold]+=1000;
$session[user][turns]-=1;

addnav("Zurück zum Wald","forest.php");

break;

case 3:
output("Du stehst langsam auf und gehst in Richtung des Baumes.. Schnell kommst du diesem näher, doch ganz plötzlich und überraschend wirst du am Kragen gepackt und fliegst nach oben.. Der Nebel verschwindet, und als du nach oben siehst, erblickst du eine Hexe, welche dich auf ihrem Besen sitzend gepackt hat und dich nun verschleppt! Als ihr in der Nähe des Dorfes seid, lässt sie dich aus vier Metern Höhe fallen und verschwindet. Ihr irres Kichern dröhnt in deinen Ohren, als du hart auf dem Boden aufschlägst. Du verlierst die meisten deiner Lebenspunkte und hast nun solche Rückenschmerzen, dass du kaum mehr kämpfen kannst.");
$session['user']['hitpoints']=ceil($session['user']['hitpoints']*0.2);

addnav("Zurück zum Wald","forest.php");

break;

}

}

if ($_GET[op]=="warten"){

output("Du bleibst einfach still sitzen und wartest ein wenig... Nach einer Weile kippst du zur Seite hin weg und schnappst nach Luft.. Dir ist furchtbar kalt.. Doch plötzlich löst sich der Nebel auf und eine kleine Fee schwebt vor deinen Augen.. Du starrst sie verwirrt an, als sie leise flüstert: ' Es tut mir Leid, dass du wegen mir hier so gefroren hast.. Lass mich dir als Entschädigung etwas geben!'
Sie verstreut ein goldenes Pulver über deinem Kopf und verschwindet dann. Du merkst, dass du plötzlich auf etwas Hartem sitzt und stehst auf. Unter dir liegt ein Edelstein, welchen du sofort einsteckst. Auf deinem Wege zurück in den Wald bemerkst du, wie ein Kribbeln dich erfüllt, du fühlst dich gesünder als je zuvor! Du erhältst einen permanenten Lebenspunkt!");

$session[user][gems]+=1;
$session[user][maxhitpoints]+=1;

addnav("Zurück zum Wald","forest.php");

}

if ($_GET[op]=="nachelternrufen"){

output("Du bekommst furchtbare Angst und beginnst, nach deinen Eltern zu schreien. Plötzlich hörst du ein lautes, anhaltendes Kichern.. Der Nebel verschwindet und ein paar Meter von dir entfernt stehen zwei Jugendliche, welche mit dem Finger auf dich zeigen und dich auslachen. Du wirst rot und verschwindest. Verdammt, war das peinlich! Vor lauter Scham verlierst du 3 Charmepunkte!");
$session[user][charm]-=3;

addnav("Zurück zum Wald","forest.php");

addnews("{$session['user']['name']} rief im Wald laut nach ".($session[user][sex]?"ihren":"seinen")." Eltern und wurde von Jugendlichen ausgelacht.");

}

?>

