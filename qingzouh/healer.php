
<?php

// 24062004

require_once "common.php";
$config = unserialize($session['user']['donationconfig']);
if ($config['healer'] || $session[user][acctid]==getsetting("hasegg",0)) $golinda = 1;

if ($golinda) {
    page_header("Golindas Hütte");
        output("`n`n`n`n`c<font face='Baskerville Old Face' size='5'>
        `YGo`Ïli`Cnd`has `ëHütte
        </font>`c`n`n`n`n",true);
} else {
    page_header("Hütte des Heilers");
    output("`n`n`n`n`c<font face='Baskerville Old Face' size='5'>
        `THüt`âte d`Êes H`êeil`~ers
        </font>`c`n`n`n`n",true);
}
$loglev = log($session[user][level]);
$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
if ($golinda) $cost *= .5;
$cost = round($cost,0);

if ($_GET[op]==""){
      checkday();
    if ($golinda) {
output("`c`CEine sehr zierliche und wunderhübsche Brünette schaut auf,`n
 als du eintrittst. \"`YAh, Du musst `h{$session['user']['name']}.`Y  sein.`n
 Mir wurde gesagt, dass du kommen würdest. Komm rein... komm rein!`C\", ruft sie.`n`n
Du gehst tiefer in die Hütte.`c`0`n`n");
    } else {
output("`c`âDu gehst gebückt in die rauchgefüllte Grashütte.  Das stechende Aroma lässt dich husten `n
und zieht die Aufmerksamkeit einer uralten grauhaarigen Person auf dich, die den Job,`n
 dich an einen Felsen zu erinnern, bemerkenswert gut ausführt.`n
 Das erklärt, dass du den kleinen Kerl bis jetzt nicht bemerkt hast. Kann ja nicht dein Fehler sein - als Krieger... Nope, definitiv nicht.`c`0`n`n");
    }
    if ($session[user][hitpoints] < $session[user][maxhitpoints]){
        if ($golinda) {
output("`c`C\"`YNun... lass uns mal sehen. Hmmm. Hmmm. Du siehst ein bisschen angeschlagen aus.`n
`C\"`n`n\"`ÏÄh... ja. Ich schätze schon. Was wird mich das kosten?`C\", fragst du betreten,`n
 \"`ÏWeißt du, normalerweise werde ich nicht so leicht verletzt.`C\"`n`n
\"`YIch weiß, ich weiß. Niemand von euch wird `hjemals`Y verletzt. Aber egal.`n
 Für `h`b$cost`b`Y Goldstücke mache ich dich wieder frisch wie einen Sommerregen.`n
 Ich kann dich auch zu einem niedrigeren Preis teilweise heilen, wenn du dir die volle Heilung nicht leisten kannst.`C\", sagt `ëGolinda`C mit einem süßen Lächeln.`c`0");
        } else {
output("`c\"`TSehen kann ich dich. Bevor du sehen konntest mich, hmm?`â\" bemerkt das alte Wesen.`n
 \"`TIch kenne dich, ja; Heilung du suchst. Bereit zu heilen dich ich bin, wenn bereit zu bezahlen du bist.`n
`â\"`n`n\"`xOh-oh. Wieviel?`â\" fragst du, bereit dich von diesem stinkenden alten Dings ausnehmen zu lassen.`n`n
Das alte Wesen pocht dir mit einem knorrigen Stab auf die Rippen: \"`TFür dich... `~`b$cost`b Goldstücke`T für eine komplette Heilung!!`â\".`n
 Dabei krümmt es sich und zieht ein Tonfläschchen hinter einem Haufen Schädel hervor.`n
 Der Anblick dieses Dings, das sich über den Schädelhaufen krümmt, um das Fläschchen zu holen, verursacht wohl genug geistigen Schaden,`n
 um eine größere Flasche zu verlangen.  \"`TIch auch habe einige - ähm... 'günstigere' Tränke im Angebot.`â\" sagt das Wesen`â, `n
während es auf  einen verstaubten Haufen zerbrochener Tonkrüge deutet.`n
 \"`TSie werden heilen einen bestimmten Prozentsatz deiner `iBeschädigung`i.`â\"`c`0");
        }
        addnav("Heiltränke");
        addnav("`&Komplette Heilung`0","healer.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        addnav("Zurück");
        addnav("In den Wald", "forest.php");
                addnav("Die Stadtumgebung","umgebung.php");
        addnav("Nach Astaros","village.php");
    }else if($session[user][hitpoints] == $session[user][maxhitpoints]){
        if ($golinda) {
output("`c`ëGolinda `Cuntersucht dich sehr sorgfältig. \"`YNun, du hast diesen leicht eingewachsenen Zehennagel hier,`n
 aber ansonsten bist du vollkommen gesund. `hIch`Y glaube, du bist nur hier her gekommen, weil du einsam warst.`C\", kichert sie.`n`n
Du erkennst, dass sie Recht hat und dass du sie von ihren anderen Patienten abhältst. Deswegen gehst du zurück in den Wald.`c`0");
        } else {
output("`c`âDie alte Kreatur schaut in deine Richtung und grunzt: \"`TEinen Heiltrank du nicht brauchst. Warum du mich störst, ich mich frage.`â\" `n
Der Geruch seines Atems lässt dich wünschen, du wärst gar nicht erst gekommen.`n
 Du denkst, es ist das Beste, einfach wieder zu gehen.`c`0");
        }
        forest(true);
    }else{
        if ($golinda) {
output("`c`ëGolinda `Cuntersucht dich sehr sorgfältig. \"`YOhje! Du hast nicht einmal einen eingewachsenen Zehennagel,`n
 den ich heilen könnte! Du bist ein Prachtexemplar der `h" . ($session['user']['sex'] == 1 ? "Frauenschaft" : "Männerschaft") . "`Y!  Komm bitte wieder,`n
 wenn du verletzt wurdest`C\". Damit wendet sie sich wieder ihrer Tränkemischerei zu.`n`n\"`ÏDas werde ich`C\", `n
stammelst du unglaublich verlegen und gehst zurück in den Wald.`c`0");
        } else {
output("`c`âDie alte Kreatur blickt dich an und mit einem Wirbelwind einer Bewegung, die dich völlig unvorbereitet erwischt,`n
 bringt sie ihren knorrigen Stab in direkten Kontakt mit deinem Hinterkopf. Du stöhnst und brichst zusammen.`n`n
Langsam öffnest du die Augen und bemerkst, dass dieses Biest gerade die letzten Tropfen aus einem Tonkrug in deinen Rachen schüttet.`n`n
\"`TDieser Trank kostenlos ist.`â\" ist alles, was es zu sagen hat.`n
 Du hast das dringende Bedürfnis, die Hütte so schnell wie möglich zu verlassen.`c`0");
            $session[user][hitpoints] = $session[user][maxhitpoints];
        }
        forest(true);
    }
}else{
    $newcost=round($_GET[pct]*$cost/100,0);
    if ($session[user][gold]>=$newcost){
        $session[user][gold]-=$newcost;
        //debuglog("spent $newcost gold on healing");
        $diff = round(($session[user][maxhitpoints]-$session[user][hitpoints])*$_GET[pct]/100,0);
        $session[user][hitpoints] += $diff;
        if ($golinda) {
output("`c`CDu erwartest ein fauliges Gesöff und kippst den Trank herunter, aber als die Flüssigkeit dir den Rachen hinunter läuft,`n
 schmeckst du Zimt, Honig und irgendetwas fruchtiges. Du fühlst Wärme durch deinen Körper strömen und deine Muskeln fangen an,`n
 sich von selbst zusammenzufügen. Mit klarem Kopf und wieder bei bester Gesundheit gibst du `ëGolinda`C ihr Gold und verlässt die Hütte in Richtung Wald.`c`0");
        } else {
output("`c`âMit verzerrtem Gesicht kippst du den Trank, den dir die Kreatur gegeben hat, runter.`n
 Trotz des fauligen Geschmacks fühlst du, wie sich Wärme in deinen Adern ausbreitet und deine Muskeln heilen.`n
 Leicht taumelnd gibst du der Kreatur ihr Geld und verlässt die Hütte.`c`0");
        }
        output("`n`n`c`&Du wurdest um $diff Punkte geheilt!`c`0");
        if ($_GET[pct]==100 && $session[user][dragonkills]>3 && e_rand(1,2)==2 && $session[user][reputation]>0) $session[user][reputation]--;
        forest(true);
    }else{
        if ($golinda) {
output("`c`C\"`YTss, tss!`C\", murmelt `ëGolinda`C. \"`YVielleicht solltest du erstmal zur Bank gehen und wiederkommen,`n
 sobald du `b`h`\$$newcost`b Gold `Y hast?`C\"`n`n
Du fühlst dich ziemlich blöde, weil du ihre kostbare Zeit vergeudet hast.`n`n
\"`YOder vielleicht wäre ein billigerer Trank besser für dich?`C\", schlägt sie freundlich vor.`c`0");
        } else {
output("`c`âDie alte Kreatur durchbohrt dich mit einem harten, grausamen Blick.`n
 Deine blitzschnellen Reflexe ermöglichen dir, dem Schlag mit seinem knorrigen Stab auszuweichen.`n
 Vielleicht solltest du erst etwas Gold besorgen, bevor du versuchst, in den lokalen Handel einzusteigen. `n`n
Dir fällt ein, dass die Kreatur `~`b`\$$newcost`â`b Goldmünzen verlangt hat.`c`0");
        }

        addnav("Heiltränke");
        addnav("`&Komplette Heilung`0","healer.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        addnav("`bZurück`b");
        addnav("Zurück in den Wald","forest.php");
        addnav("Zurück nach Astaros","village.php");
    }
}
page_footer();
?>


