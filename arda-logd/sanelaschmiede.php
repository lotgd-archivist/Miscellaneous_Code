<?php
//*-------------------------*
//|      Haganirs Schmiede  |
//|        by Amerilion     |
//|        comments to      |
//|     greenmano@gmx.de    |
//*-------------------------*

/*
Sehr große Teile aus der castel.php entnommen und umgeschriebenn
Nya okay eigentlich alles daraus genommen und umgeschrieben *zwinker*
Hier mal der Copyright auszug aus der castel.php
------------------------------------------------------
originally found at www.lotgd.com
changes & translation by anpera
additional changes by nTE
------------------------------------------------------

//Sanela-Pack Version 1.1
*/

require_once "common.php";

page_header("Haganirs Schmiede");
$session['user']['sanela']=unserialize($session['user']['sanela']);
output("`c`b`QHaganirs Schmiede`c`b`n`n");
addnav("Zurück nach Symia","sanela.php");

if($_GET['op']==""){
    output("`gDu gehst zu der kleinen Schmiede, die hier in Sanela eigentlich fehl am Platz zu sein scheint.");
    output("Sie hat auch nicht an jeden Tag offen sondern nur wenn ihr Besitzer `QHaganir`g sich erbarmt, seine");
    output("Handwerkskunst auch anderen Abenteurern anzubieten. Du öffnest die kleine Tür und eine Rauchwolke kommt");
    output("dir entgegen. Haganir, ein kräftig gebauter, doch unscheinbarer Mann mittleren Alters sieht dich an.");
    addnav("Waffe verbessern","sanelaschmiede.php?op=we");
    addnav("Rüstung verbessern","sanelaschmiede.php?op=am");
}
if($_GET['op']=="am"){
    output("`gNoch bevor er den Mund aufmacht ahnst du, dass er eine Stimme haben muss, die von vielen schon erlebten");
    output("Abenteuern erfahren und bestimmt klingen wird.`n");
    if (strchr($session['user']['armor'],"Haganirs")){
        output("`#\"Hallo. Ich sehe, du trägst mein(e/n) `^".$session['user']['armor']." .`#Wie schön, doch ich");
        output("wüsste nicht warum ihr mich nun schon wieder von meiner Arbeit abhaltet. Wenn ihr von meinen Abenteuern");
        output("hören wollt, kommt wieder wenn fertig gearbeitet habe.\"");
    }else{
        $newdefence = $session['user']['armordef'] + 1;
        $cost = $session['user']['armordef'] * 660;
        output("`#\"Hallo, kommt doch bitte näher damit ich euch ansehen kann.\"`n`gEr sieht sich deine `^".$session['user']['armor']."`g an.`n");
        if ($cost == 0){
            output("`#\"Sieht nicht so aus, als ob ich daraus irgendetwas machen könnte.\"`g`n Murmelt er, bevor er weiterarbeitet.");
            output("`n`n`gNiedergeschlagen machst du dich daran die Schmiede zu verlassen...");
        }else if ($cost > $session['user']['gold']){
            output("`#\"Ich könnte das zu eine(r/m) `^Haganirs ".$session['user']['armor']."`#  mit `^$newdefence`# Rüstungsschutz machen, wenn du willst. Und das kostet dich nur `^$cost`# Gold!\"`g, murmelt er, bevor er weiterarbeitet.");
            output("`n`n`gDa du aber nicht so viel Gold dabei hast, beschließt du den Laden wieder zu verlassen...");
        }else{
            output("`#\"Ich könnte das zu eine(r/m) `^Haganirs ".$session['user']['armor']."`# mit `^$newdefence`# Rüstungsschutz machen, wenn du willst. Und das kostet dich nur `^$cost`# Gold!'`g, murmelt er, bevor er sich wieder zum Feuer dreht.");
            addnav("Ok","sanelaschmiede.php?op=amplus");
        }
    }
}
if ($_GET['op']=="amplus"){
    output("`QHaganir`g nimmt dein(e/n) `^".$session['user']['armor']."`g und arbeitet eine Weile daran. Bald, passt er dir die Rüstung an und macht noch ein paar abschließende Änderungen. Die Rüstung fühlt sich jetzt besser an, einfach von Unnötigem befreit und doch stabiler. Zufrieden verlässt du die Schmiede.");
    $newarmor = "Haganirs ".$session['user']['armor'];
    $cost = $session['user']['armordef'] * 660;
    $session['user']['gold']-=$cost;
    $session['user']['armor']= $newarmor;
    $session['user']['armordef']++;
    $session['user']['armorvalue']+=$cost;
    $session['user']['defence']++;
}
if ($_GET['op']=="we"){
    output("`gNoch bevor er den Mund aufmacht ahnst du, dass er eine Stimme haben muss, die von vielen schon erlebten");
    output("Abenteuern erfahren und bestimmt klingen wird.`n");
    if (strchr($session['user']['weapon'],"Haganirs")){
        output("`#\"Hallo. Ich sehe du trägst mein(e/n) `^".$session['user']['weapon']." .`#Wie schön, doch ich");
        output("wüsste nicht, warum ihr mich nun schon wieder von meiner Arbeit abhaltet. Wenn ihr von meinen Abenteuern");
        output("hören wollt, kommt wieder wenn fertig gearbeitet habe.\"");
    }else{
        $newattack = $session['user']['weapondmg'] + 1;
        $cost = $session['user']['weapondmg'] * 660;
        output("`#\"Hallo, kommt doch bitte näher, damit ich euch ansehen kann.\"`n`gEr sieht sich deine `^".$session['user']['weapon']."`g an.`n");
        if ($cost == 0){
            output("`#\"Du erwartest doch nicht, dass ich sowas bearbeite? Komm wieder, wenn du eine ordentliche Waffe hast.\"");
            output("`n`n`gNiedergeschlagen machst du dich daran die Schmiede zu verlassen...");
        }else if ($cost > $session['user']['gold']){
            output("`#\"Daraus kann ich ein `^Haganirs ".$session['user']['weapon']."`# mit `^$newattack`# Schaden machen! Aber das wird dich `^$cost`# Gold kosten...\"");
            output("`n`n`gDa du nicht genug Gold hast, beschließt du die Schmiede zu verlassen...");
        }else{
            output("`#\"Daraus kann ich ein `^Haganirs ".$session['user']['weapon']."`# mit `^$newattack`# Schaden machen! Aber das wird dich `^$cost`# Gold kosten...\"");
            addnav("Ok","sanelaschmiede.php?op=weplus");
        }
    }
}
if ($_GET['op']=="weplus"){
    output("`QHaganir`g nimmt dein(e/n) `^".$session['user']['weapon']."`g und arbeitet eine Weile daran. Bald testet er die Waffe an einem Holzblock und macht noch ein paar abschließende Änderungen. Deine Waffe ist sichtbar schärfer als vorher. Zufrieden verlässt du die Schmiede.");
    $newweapon = "Haganirs ".$session['user']['weapon'];
    $cost = $session['user']['weapondmg'] * 660;
    $session['user']['gold']-=$cost;
    $session['user']['weapon']= $newweapon;
    $session['user']['weapondmg']++;
    $session['user']['weaponvalue']+=$cost;
    $session['user']['attack']++;
}
$session['user']['sanela']=serialize($session['user']['sanela']);
page_footer();
?>