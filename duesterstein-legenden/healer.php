
<?
require_once "common.php";
$config = unserialize($session['user']['donationconfig']);
if ($config['healer'] || $session[user][acctid]==getsetting("hasegg",0)) $golinda = 1;
if ($golinda) {
    page_header("Golinda's Hütte");
    output("`#`b`cGolinda's Hütte`c`b`n");
} else {
    page_header("Hütte des Heilers");
    output("`#`b`cHütte des Heilers`c`b`n");
}
$loglev = log($session[user][level]);
$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);
// Guilds/Clans Code
$guilddisc = 1;
if ($session['user']['guildID']!=0) {
    $MyGuild=&$session['guilds'][$session['user']['guildID']];
    if (isset($MyGuild)) {
        $guilddiscount = $MyGuild['PotionDiscount'];
        if ( $guilddiscount > 0 ) $guilddisc = ( 1 - ($guilddiscount/100) );
    } else {
        // Error
        // Their guildID is set but the information cannot be retrieved
        $debug=print_r($session['user']['guildID'],true);
        debuglog("MyGuild isn't set: ".$debug);
    }
} elseif ($session['user']['clanID']!=0) {
    $MyClan=&$session['guilds'][$session['user']['clanID']];
    if (isset($MyClan)) {
        $guilddiscount = $MyClan['PotionDiscount'];
        if ( $guilddiscount > 0 ) $guilddisc = ( 1 - ($guilddiscount/100) );
    } else {
        // Error
        // Their clanID is set but the information cannot be retrieved
        $debug=print_r($session['user']['clanID'],true);
        debuglog("MyClan isn't set: ".$debug);
    }
} else {
      // They don't belong to a guild or clan
}
//
$potioncost = round( (($loglev * ($session[user][maxhitpoints])) + ($loglev*10))*$guilddisc ,0);
if ($potioncost == 0) $potioncost=round(($session['user']['dragonkills']*5)*$guilddisc,0);
if ($golinda) $cost *= .5;
if ( $session['user']['stone'] == 24 ) $cost *= .8;
$cost = round($cost,0);

if ($HTTP_GET_VARS[op] == "heal"){
    output("Etwas unfreundlich wird Dir eine kleine Ampulle mit einer übel aussehenden Flüssigkeit
    zugeworfen. Du legst das Geld auf den Tisch und verstaust die Ampulle sehr sorgfältig
    in Deiner Tasche.`n");
    $session['user']['gold']-=round(100*$potioncost/100,0);
    $session['user']['potion']+=1;
    addnav("Weiter","healer.php");
}else{
if ($HTTP_GET_VARS[op]==""){
      checkday();
        if ($session[user][gold] >= round(100*$potioncost/100,0) and $session['user']['potion']<5){
        output("`2Spezieller Heiltrank!");
        output("`3Ein Heiltrank`7 kostet `6".round(100*$potioncost/100,0)." Gold`7.
                Du kannst maximal 5 Heiltränke als Medizin mit Dir führen.`n`n");
        addnav("Extra");
        addnav("Heiltrank","healer.php?op=heal");
    }
    if ($golinda) {
        output("`3Eine sehr zierliche und wunderhübsche Brünette schaut auf, als du eintrittst. \"`6Ah, Du musst {$session['user']['name']}.`6  sein. Mir wurde gesagt, dass du kommen würdest. Komm rein... komm rein!`3\", ruft sie.`n`nDu gehst tiefer in die Hütte.`n`n");
    } else {
        output("`3Du gehst gebückt in die rauchgefüllte Grashütte.  Das stechende Aroma lässt dich husten und zieht die Aufmerksamkeit einer uralten grauhaarigen Person auf dich, die den Job, dich an einen Felsen zu erinnern, bemerkenswert gut ausführt. Das erklärt, dass du den kleinen Kerl bis jetzt nicht bemerkt hast. Kann ja nicht dein Fehler sein - als Krieger... Nop, definitiv nicht.`n`n");
    }
    if ($session[user][hitpoints] < $session[user][maxhitpoints]){
        if ($golinda) {
            output("`3\"`6Nun... lass uns mal sehen. Hmmm. Hmmm. Du siehst ein bisschen angeschlagen aus.`3\"`n`n\"`5Äh... yeah. Ich schätze schon. Was wird mich das kosten?`3\", fragst du betreten, \"`5Weisst du, normalerweise werde ich nicht so leicht verletzt.`3\"`n`n\"`6Ich weiss, ich weiss. Niemand von euch wird `^jemals`6 verletzt. Aber egal. Für `$`b$cost`b`6 Goldstücke mache ich dich wieder frisch wie einen Sommerregen. Ich kann dich auch zu einem niedrigeren Preis teilweise heilen, wenn du dir die volle Heilung nicht leisten kannst.`3\", sagt Golinda mit einem süssen Lächeln.");
        } else {
            output("\"`6Sehen kann ich dich. Bevor du sehen konntest mich, hmm?`3\" bemerkt das alte Wesen. \"`6Ich kenne dich, ja; Heilung du suchst. Bereit zu heilen dich ich bin, wenn bereit zu bezahlen du bist.`3\"`n`n\"`5Oh-oh. Wieviel?`3\" fragst du, bereit dich von diesem stingenden alten Dings ausnehmen zu lassen.`n`nDas alte Wesen pocht dir mit einem knorrigen Stab auf die Rippen: \"`6Für dich... `$`b$cost`b`6 Goldstücke für eine komplette Heilung!!`3\". Dabei krümmt es sich und zieht ein Tonfläschen hinter einem Haufen Schädel hervor. Der Anblick dieses Dings, das sich über den Schädelhaufen krümmt, um das Fläschchen zu holen, verursacht wohl genug geistigen Schaden, um eine grössere Flasche zu verlangen.  \"`6Ich auch habe einige - ähm... 'günstigere' Tränke im Angebot.`3\" sagt das Wesen, während es auf  einen verstaubten Haufen zerbrochener Tonkrüge deutet. \"`6Sie werden heilen einen bestimmten Prozentsatz deiner `iBeschädigung`i.`3\"");
        }
        addnav("Heilung");
        addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        addnav("`bZurück`b");
        addnav("Zurück in den Wald", "forest.php");
        addnav("Zurück ins Dorf","village.php");
    }else if($session[user][hitpoints] == $session[user][maxhitpoints]){
        if ($golinda) {
            output("`3Golinda untersucht dich sehr sorgfältig. \"`6Nun, du hast diesen leicht eingewachsenen Zehennagel hier, aber ansonsten bist du vollkommen gesund. `^Ich`6 glaube, du bist nur hier her gekommen, weil du einsam warst.`3\", kichert sie.`n`nDu erkennst, dass sie Recht hat und dass du sie von ihren anderen Patienten abhältst. Deswegen gehst du zurück in den Wald.");
        } else {
            output("`3Die alte Kreatur schaut in deine Richtung und grunzt: \"`6Einen Heiltrank du nicht brauchst. Warum du mich störst, ich mich frage.`3\" Der Geruch seines Atems lässt dich wünschen, du wärst gar nicht erst gekommen. Du denkst, es ist das beste einfach wieder zu gehen.");
        }
        forest(true);
    }else{
        if ($golinda) {
            output("`3Golinda untersucht dich sehr sorgfältig. \"`6Ohje! Du hast nicht einmal einen eingewachsenen Zehennagel, den ich heilen könnte! Du bist ein Prachtexemplar der " . ($session['user']['sex'] == 1 ? "Frauenschaft" : "Männerschaft") . "!  Komm bitte wieder, wenn du verletzt wurdest`3\". Damit wendet sie sich wieder ihrer Tränkemischerei zu.`n`n\"`6Das werde ich`3\", stammelst du unglaublich verlegen und gehst zurück in den Wald.");
        } else {
            output("`3Die alte Kreatur blickt dich an und mit einem `^Wirbelwind einer Bewegung`3, die dich völlig unvorbereitet erwischt, bringt sie ihren knorrigen Stab in direkten Kontakt mit deinem Hinterkopf. Du stöhnst und brichst zusammen.`n`nLangsam öffnest du die Augen und bemerkst, dass dieses Biest gerade die letzten Tropfen aus einem Tonkrug in deinen Rachen schüttet.`n`n\"`6Dieser Trank kostenlos ist.`3\" ist alles, was es zu sagen hat. Du hast das dringende Bedürfnis die Hütte so schnell wie möglich zu verlassen.");
            $session[user][hitpoints] = $session[user][maxhitpoints];
        }
        forest(true);
    }
}else{
    $newcost=round($HTTP_GET_VARS[pct]*$cost/100,0);
    if ($session[user][gold]>=$newcost){
        $session[user][gold]-=$newcost;
        //debuglog("spent $newcost gold on healing");
        $diff = round(($session[user][maxhitpoints]-$session[user][hitpoints])*$HTTP_GET_VARS[pct]/100,0);
        $session[user][hitpoints] += $diff;
        if ($golinda) {
            output("`3Du erwartest ein fauliges Gesöff und kippst den Trank herunter, aber als die Flüssigkeit dir den Rachen hinunter läuft, schmeckst du Zimt, Honig und irgendetwas fruchtiges. Du fühlst Wärme durch deinen Körper strömen und deine Muskeln fangen an, sich von selbst zusammenzufügen. Mit klarem Kopf und wieder bei bester Gesundheit gibst du Golinda ihr Gold und verlässt die Hütte in Richtung Wald.");
            //$config['healer']--;
        } else {
            output("`3Mit verzerrtem Gesicht kippst du den Trank, den dir die Kreatur gegeben hat, runter. Trotz des fauligen Geschmacks fühlst du, wie sich Wärme in deinen Adern ausbreitet und deine Muskeln heilen. Leicht taumelnd gibst du der Kreatur ihr Geld und verlässt die Hütte.");
        }
        output("`n`n`#Du wurdest um $diff Punkte geheilt!");
        forest(true);
    }else{
        if ($golinda) {
            output("`3\"`6Tss, tss!`3\", murmelt Golinda. \"`6Vielleicht solltest du erstmal zur Bank gehen und wiederkommen, sobald du `b`\$$newcost`6`b Gold hast?`3\"`n`nDu fühlst dich ziemlich blöde, weil du ihre kostbare Zeit vergeudet hast.`n`n\"Oder vielleicht wäre ein billigerer Trank besser für dich?`3\", schlägt sie freundilich vor.");
        } else {
            output("`3Die alte Kreatur durchbohrt dich mit einem harten, grausamen Blick. Deine Blitzschnellen Reflexe ermöglichen dir, dem Schlag mit seinem knorrigen Stab auszuweichen. Vielleicht solltest du erst etwas Gold besorgen, bevor du versuchst, in den lokalen Handel einzusteigen. `n`nDir fällt ein, dass die Kreatur `b`\$$newcost`3`b Goldmünzen verlangt hat.");
        }
        addnav("Heilung");
        addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");
        for ($i=90;$i>0;$i-=10){
            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");
        }
        addnav("`bZurück`b");
        addnav("Zurück in den Wald","forest.php");
        addnav("Zurück ins Dorf","village.php");
    }
}
$session['user']['donationconfig'] = serialize($config);
}
page_footer();
?>


