<?php

// 24062004

require_once "common.php";
$config = unserialize($session['user']['donationconfig']);
if ($config['healer'] || $session[user][acctid]==getsetting("hasegg",0)) $golinda = 1;

if ($golinda) {
    page_header("Golindas Hütte");
    output("`#`b`cGolindas Hütte`c`b`n");
} else {
    page_header("Hütte der Heilerin");
    output("`#`b`c`fH`Fü`jt`&t`me `Ld`le`Kr `yH`Ye`9i`xl`Xe`mri`&n`c`b`n");
}
$loglev = log($session[user][level]);
$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
if ($golinda) $cost *= .5;
$cost = round($cost,0);

if ($HTTP_GET_VARS[op]==""){
      checkday();
    if ($golinda) {
        output("`3Eine sehr zierliche und wunderhübsche Brünette schaut auf, als du eintrittst. \"`6Ah, Du musst {$session['user']['name']}.`6  sein. Mir wurde gesagt, dass du kommen würdest. Komm rein... komm rein!`3\", ruft sie.`n`nDu gehst tiefer in die Hütte.`n`n");
    } else {
//        output("`3Du gehst gebückt in die rauchgefüllte Grashütte.  Das stechende Aroma lässt dich husten und zieht die Aufmerksamkeit einer uralten grauhaarigen Person auf dich, die den Job, dich an einen Felsen zu erinnern, bemerkenswert gut ausführt. Das erklärt, dass du den kleinen Kerl bis jetzt nicht bemerkt hast. Kann ja nicht dein Fehler sein - als Krieger... Nop, definitiv nicht.`n`n");
        output("`9Du gehst zum Rande des Waldes auf die Heilerhütte zu. Als du auf der Lichtung vor der Hütte ankommst, hörst du eine helle Stimme die aus der Hütte zu dir dringt. `#\"Ich komme gleich.\"`9 Das ist die `yH`Ye`9i`xl`Xe`mri`&n `9des Dorfes `ÀV`êi`re`&su`òl`7a`&s `ÀL`êi`rl`&l`òy`9.");
    }
    if ($session[user][hitpoints] < $session[user][maxhitpoints]){
        if ($golinda) {
            output("`3\"`6Nun... lass uns mal sehen. Hmmm. Hmmm. Du siehst ein bisschen angeschlagen aus.`3\"`n`n\"`5Äh... ja. Ich schätze schon. Was wird mich das kosten?`3\", fragst du betreten, \"`5Weißt du, normalerweise werde ich nicht so leicht verletzt.`3\"`n`n\"`6Ich weiß, ich weiß. Niemand von euch wird `^jemals`6 verletzt. Aber egal. Für `$`b$cost`b`6 Goldstücke mache ich dich wieder frisch wie einen Sommerregen. Ich kann dich auch zu einem niedrigeren Preis teilweise heilen, wenn du dir die volle Heilung nicht leisten kannst.`3\", sagt Golinda mit einem süßen Lächeln.");
        } else {
//            output("\"`6Sehen kann ich dich. Bevor du sehen konntest mich, hmm?`3\" bemerkt das alte Wesen. \"`6Ich kenne dich, ja; Heilung du suchst. Bereit zu heilen dich ich bin, wenn bereit zu bezahlen du bist.`3\"`n`n\"`5Oh-oh. Wieviel?`3\" fragst du, bereit dich von diesem stinkenden alten Dings ausnehmen zu lassen.`n`nDas alte Wesen pocht dir mit einem knorrigen Stab auf die Rippen: \"`6Für dich... `$`b$cost`b`6 Goldstücke für eine komplette Heilung!!`3\". Dabei krümmt es sich und zieht ein Tonfläschchen hinter einem Haufen Schädel hervor. Der Anblick dieses Dings, das sich über den Schädelhaufen krümmt, um das Fläschchen zu holen, verursacht wohl genug geistigen Schaden, um eine größere Flasche zu verlangen.  \"`6Ich auch habe einige - ähm... 'günstigere' Tränke im Angebot.`3\" sagt das Wesen, während es auf  einen verstaubten Haufen zerbrochener Tonkrüge deutet. \"`6Sie werden heilen einen bestimmten Prozentsatz deiner `iBeschädigung`i.`3\"");
            output("`9Sie wirbelt aus ihrer Hütte. Ihre Augen huschten über deinen Körper `#\"Ohje, was hast du denn angestellt? Da müssen wir was tun. Aber ich muss meine Unkosten Decken.\"`n`n`&\"Ähm. W-wie viel...?\"`n`n`#\"Keine Sorge. Für dich ist es nur `m`b$cost`b Gold`#. Dann bist du wieder fitt. Versprochen. Wenn du so viel nicht hast, dann kann ich dich auch teilweise Heilen. Wie du es willst.\"`9 Sie huscht rasch zu der Hüttenwand und greift durch eines der geöffneten Fenster in ein Regal um einen Heiltrank deiner Wahl zu holen.");
        }
        addnav("Heiltränke");
        addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        addnav("`bZurück`b");
        addnav("Zurück in den Wald", "forest.php");
        addnav("in die Heilerhütte","heilerhuette.php");        
        addnav("Zurück ins Dorf","sanela.php");
        addnav("zur Waldlichtung","waldlichtung.php");
    }else if($session[user][hitpoints] == $session[user][maxhitpoints]){
        if ($golinda) {
            output("`3Golinda untersucht dich sehr sorgfältig. \"`6Nun, du hast diesen leicht eingewachsenen Zehennagel hier, aber ansonsten bist du vollkommen gesund. `^Ich`6 glaube, du bist nur hier her gekommen, weil du einsam warst.`3\", kichert sie.`n`nDu erkennst, dass sie Recht hat und dass du sie von ihren anderen Patienten abhältst. Deswegen gehst du zurück in den Wald.");
        } else {
//            output("`3Die alte Kreatur schaut in deine Richtung und grunzt: \"`6Einen Heiltrank du nicht brauchst. Warum du mich störst, ich mich frage.`3\" Der Geruch seines Atems lässt dich wünschen, du wärst gar nicht erst gekommen. Du denkst, es ist das Beste, einfach wieder zu gehen.");
            output("`9`ÀV`êi`re`&su`òl`7a`&s `ÀL`êi`rl`&l`òy`9 kommt aus der Hütte gelaufen. Vor dir stoppt und schaut dich von oben bis unten an. `#\"Dir geht es gut. Warum bist du hier. Brauchst du Gesellschaft?\"`9 Ihr fröhliches Lachen steckt dich an, und so lächelst du sie etwas beschämt an, bevor du dich umdrehst und zurück in den Wald gehst, um noch mehr Monster zu bekämpfen.");
        }
        addnav("`bZurück`b");
        addnav("Zurück in den Wald", "forest.php");
        addnav("in die Heilerhütte","heilerhuette.php");        
        addnav("Zurück ins Dorf","sanela.php");        
        addnav("Waldlichtung","waldlichtung.php");
//        forest(true);
    }else{
        if ($golinda) {
            output("`3Golinda untersucht dich sehr sorgfältig. \"`6Ohje! Du hast nicht einmal einen eingewachsenen Zehennagel, den ich heilen könnte! Du bist ein Prachtexemplar der " . ($session['user']['sex'] == 1 ? "Frauenschaft" : "Männerschaft") . "!  Komm bitte wieder, wenn du verletzt wurdest`3\". Damit wendet sie sich wieder ihrer Tränkemischerei zu.`n`n\"`6Das werde ich`3\", stammelst du unglaublich verlegen und gehst zurück in den Wald.");
        } else {
//            output("`3Die alte Kreatur blickt dich an und mit einem `^Wirbelwind einer Bewegung`3, die dich völlig unvorbereitet erwischt, bringt sie ihren knorrigen Stab in direkten Kontakt mit deinem Hinterkopf. Du stöhnst und brichst zusammen.`n`nLangsam öffnest du die Augen und bemerkst, dass dieses Biest gerade die letzten Tropfen aus einem Tonkrug in deinen Rachen schüttet.`n`n\"`6Dieser Trank kostenlos ist.`3\" ist alles, was es zu sagen hat. Du hast das dringende Bedürfnis, die Hütte so schnell wie möglich zu verlassen.");
            output("`9Doch aus der Hütte tritt nicht `ÀV`êi`re`&su`òl`7a`&s `ÀL`êi`rl`&l`òy`9. Stattdessen steht nun `Jdunkler Druide`9 vor dir und mustert dich durchdringen, mit einem spättischen Lächeln auf dem Gesicht. Offensichtlich hat er Lillys Stimme kopiert. Jetzt klingt seine Stimme dunkeln und weich. `J\"Du hast mehr bekommen, als dir zusteht, dass muss ich doch direkt wieder rückgängig machen.\"`9 Er hebt die Hände und bevor du reagieren kannst trifft dich sein Zauber. Während du keuchend auf dem Boden liegst, geht der `JDruide`9 leise lachend an dir vorbei und verschwindet. Schließlich rappelst du dich wieder auf und verlässt die Hütte so schnell wie möglich.");
    $session[user][hitpoints] = $session[user][maxhitpoints];
        }
//        forest(true);
        addnav("`bZurück`b");
        addnav("Zurück in den Wald", "forest.php");
        addnav("in die Heilerhütte","heilerhuette.php");        
        addnav("Zurück ins Dorf","sanela.php");
        addnav("Waldlichtung","Waldlichtung.php");
    }
}else{
    $newcost=round($HTTP_GET_VARS[pct]*$cost/100,0);
    if ($session[user][gold]>=$newcost){
        $session[user][gold]-=$newcost;
        //debuglog("spent $newcost gold on healing");
        $diff = round(($session[user][maxhitpoints]-$session[user][hitpoints])*$HTTP_GET_VARS[pct]/100,0);
        $session[user][hitpoints] += $diff;
        if ($golinda) {
            output("`3Du erwartest ein fauliges Gesöff und kippst den Trank herunter, aber als die Flüssigkeit dir den Rachen hinunter läuft, schmeckst du Zimt, Honig und irgendetwas fruchtiges. Du fühlst Wärme durch deinen Körper strömen und deine Muskeln fangen an, sich von selbst zusammenzufügen. Mit klarem Kopf und wieder bei bester Gesundheit gibst du Golinda ihr Gold und verlässt die Hütte in Richtung Wald.");
        } else {
//            output("`3Mit verzerrtem Gesicht kippst du den Trank, den dir die Kreatur gegeben hat, runter. Trotz des fauligen Geschmacks fühlst du, wie sich Wärme in deinen Adern ausbreitet und deine Muskeln heilen. Leicht taumelnd gibst du der Kreatur ihr Geld und verlässt die Hütte.");
            output("`9Du nimmst den Trank an, den `ÀV`êi`re`&su`òl`7a`&s `ÀL`êi`rl`&l`òy`9 dir reicht und trinkst ihn aus. Sofort breitet sich eine wohlige Wärme in deinem Bauch aus und du spührst, wie sich deine Wunden schließen. Neue Kraft strömt durch deine Muskeln. `ÀL`êi`rl`&l`òy`9 lächelt dich Scheu an. `#\"Ich hoffe jetzt geht es dir wieder gut.\"`9 erklingt ihre Stimme. Du bedankst dich und verlässt die Lichtung kraftstrotzdend und wild auf einen weiteren Kampf.");
        }
        output("`n`n`9Du wurdest um $diff Punkte geheilt!");
        if ($HTTP_GET_VARS[pct]==100 && $session[user][dragonkills]>3 && e_rand(1,2)==2 && $session[user][reputation]>0) $session[user][reputation]--;
        forest(true);
    }else{
        if ($golinda) {
            output("`3\"`6Tss, tss!`3\", murmelt Golinda. \"`6Vielleicht solltest du erstmal zur Bank gehen und wiederkommen, sobald du `b`\$$newcost`6`b Gold hast?`3\"`n`nDu fühlst dich ziemlich blöde, weil du ihre kostbare Zeit vergeudet hast.`n`n\"Oder vielleicht wäre ein billigerer Trank besser für dich?`3\", schlägt sie freundlich vor.");
        } else {
//            output("`3Die alte Kreatur durchbohrt dich mit einem harten, grausamen Blick. Deine blitzschnellen Reflexe ermöglichen dir, dem Schlag mit seinem knorrigen Stab auszuweichen. Vielleicht solltest du erst etwas Gold besorgen, bevor du versuchst, in den lokalen Handel einzusteigen. `n`nDir fällt ein, dass die Kreatur `b`\$$newcost`3`b Goldmünzen verlangt hat.");
            output("`ÀV`êi`re`&su`òl`7a`&s `ÀL`êi`rl`&l`òy`9 blickt dich traurig an. `#\"Tut mir leid, ich würde dir gerne auch so helfen. Aber dann kann ich bald gar keinen mehr heilen. Bitte besorg dir erst Gold bei der Bank oder nimm einen der billigeren Heiltränke.\"`9 Peinlich berührt siehst du sie an und beschließt sofort, die mehr Geld zu holen.");
        }
        addnav("Heiltränke");
        addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        addnav("`bZurück`b");
        addnav("Zurück in den Wald","forest.php");
        addnav("in die Heilerhütte","heilerhuette.php");
        addnav("Zurück ins Dorf","Sanela.php");
        addnav("Waldlichtung","waldlichtung.php");
    }
}
page_footer();
?>