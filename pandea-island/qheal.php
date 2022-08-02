<?php
require_once "common.php";
page_header("Das Lazarett");
output("`#`b`cDas Lazarett`c`b`n");

$loglev = log($session[user][level]);
$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
if ($golinda) $cost *= .5;
$cost = round($cost,0);

if ($HTTP_GET_VARS[op]==""){
      checkday();
    output("`3Auf Grund der Belagerung ist es nicht möglich den Wald aufzusuchen da er selbst für die erfahrensten Spieler einfach zu gefährlich wäre. Deshalb hat der Heiler einfach kurzer Hand seine 7 Sachen gepackt und ist über einen Abwassertunnel in die Stadt und letztendlich auch unweit in die Nähe des Flüchtlingslagers vorgedrungen, wo er nun sein Notfallzelt aufgebaut hat. Die nötigsten Sachen bei sich um zumindest normale Verletzungen zu heilen`n`n");
    if ($session[user][hitpoints] < $session[user][maxhitpoints]){
       output("\"`6Sehen kann ich dich. Bevor du sehen konntest mich, hmm?`3\" bemerkt das alte Wesen. \"`6Ich kenne dich, ja; Heilung du suchst. Bereit zu heilen dich ich bin, wenn bereit zu bezahlen du bist.`3\"`n`n\"`5Oh-oh. Wieviel?`3\" fragst du, bereit dich von diesem stinkenden alten Dings ausnehmen zu lassen.`n`nDas alte Wesen pocht dir mit einem knorrigen Stab auf die Rippen: \"`6Für dich... `$`b$cost`b`6 Goldstücke für eine komplette Heilung!!`3\". Dabei krümmt es sich und zieht ein Tonfläschchen hinter einem Haufen Schädel hervor. Der Anblick dieses Dings, das sich über den Schädelhaufen krümmt, um das Fläschchen zu holen, verursacht wohl genug geistigen Schaden, um eine größere Flasche zu verlangen.  \"`6Ich auch habe einige - ähm... 'günstigere' Tränke im Angebot.`3\" sagt das Wesen, während es auf  einen verstaubten Haufen zerbrochener Tonkrüge deutet. \"`6Sie werden heilen einen bestimmten Prozentsatz deiner `iBeschädigung`i.`3\"");
        addnav("Heiltränke");
        addnav("`^Komplette Heilung`0","qheal.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","qheal.php?op=buy&pct=$i");
        }
        addnav("`bZurück`b");
        addnav("Zurück ins Dorf","village.php");
    }else if($session[user][hitpoints] == $session[user][maxhitpoints]){
        output("`3Die alte Kreatur schaut in deine Richtung und grunzt: \"`6Einen Heiltrank du nicht brauchst. Warum du mich störst, ich mich frage.`3\" Der Geruch seines Atems lässt dich wünschen, du wärst gar nicht erst gekommen. Du denkst, es ist das Beste, einfach wieder zu gehen.");
        addnav("`bZurück`b");
        addnav("Zurück ins Dorf","village.php");
    }else{
        output("`3Die alte Kreatur blickt dich an und mit einem `^Wirbelwind einer Bewegung`3, die dich völlig unvorbereitet erwischt, bringt sie ihren knorrigen Stab in direkten Kontakt mit deinem Hinterkopf. Du stöhnst und brichst zusammen.`n`nLangsam öffnest du die Augen und bemerkst, dass dieses Biest gerade die letzten Tropfen aus einem Tonkrug in deinen Rachen schüttet.`n`n\"`6Dieser Trank kostenlos ist.`3\" ist alles, was es zu sagen hat. Du hast das dringende Bedürfnis, das Zelt so schnell wie möglich zu verlassen.");
        $session[user][hitpoints] = $session[user][maxhitpoints];
        addnav("`bZurück`b");
        addnav("Zurück ins Dorf","village.php");
    }
}else{
    $newcost=round($HTTP_GET_VARS[pct]*$cost/100,0);
    if ($session[user][gold]>=$newcost){
        $session[user][gold]-=$newcost;
        //debuglog("spent $newcost gold on healing");
        $diff = round(($session[user][maxhitpoints]-$session[user][hitpoints])*$HTTP_GET_VARS[pct]/100,0);
        $session[user][hitpoints] += $diff;
        output("`3Mit verzerrtem Gesicht kippst du den Trank, den dir die Kreatur gegeben hat, runter. Trotz des fauligen Geschmacks fühlst du, wie sich Wärme in deinen Adern ausbreitet und deine Muskeln heilen. Leicht taumelnd gibst du der Kreatur ihr Geld und verlässt das Zelt um die Stadt weiter zu befreien.");
        output("`n`n`#Du wurdest um $diff Punkte geheilt!");
        addnav("`bZurück`b");
        addnav("Zurück ins Dorf","village.php");
    }else{
        output("`3Die alte Kreatur durchbohrt dich mit einem harten, grausamen Blick. Deine blitzschnellen Reflexe ermöglichen dir, dem Schlag mit seinem knorrigen Stab auszuweichen. Vielleicht solltest du erst etwas Gold besorgen, bevor du versuchst, in den lokalen Handel einzusteigen. `n`nDir fällt ein, dass die Kreatur `b`\$$newcost`3`b Goldmünzen verlangt hat.");
        addnav("Heiltränke");
        addnav("`^Komplette Heilung`0","qheal.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","qheal.php?op=buy&pct=$i");
        }
        addnav("`bZurück`b");
        addnav("Zurück ins Dorf","village.php");
    }
}
page_footer();
?> 