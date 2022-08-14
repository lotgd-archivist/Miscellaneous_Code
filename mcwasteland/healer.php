
<?php

require_once("common.php");

$config = unserialize($session['user']['donationconfig']);

if ($config['healer'] || $session['user']['acctid']==getsetting("hasegg",0)) $golinda = 1;



if ($golinda) {

    page_header("Golindas HÃ¼tte");

    output("`#`b`cGolindas HÃ¼tte`c`b`n");

} else {

    page_header("HÃ¼tte des Heilers");

    output("`#`b`cHÃ¼tte des Heilers`c`b`n");

}

$loglev = log($session[user][level]);

$cost = ($loglev * ($session[user][maxhitpoints]-$session[user][hitpoints])) + ($loglev*10);

if ($golinda) $cost *= .5;

$cost = round($cost,0);



if ($HTTP_GET_VARS[op]==""){

      checkday();

    if ($golinda) {

        output("`3Eine sehr zierliche und wunderhÃ¼bsche BrÃ¼nette schaut auf, als du eintrittst. \"`6Ah, Du musst {$session['user']['name']}.`6  sein. Mir wurde gesagt, dass du kommen wÃ¼rdest. Komm rein... komm rein!`3\", ruft sie.`n`nDu gehst tiefer in die HÃ¼tte.`n`n");

    } else {

        output("`3Du gehst gebÃ¼ckt in die rauchgefÃ¼llte GrashÃ¼tte.  Das stechende Aroma lÃ¤sst dich husten und zieht die Aufmerksamkeit einer uralten grauhaarigen Person auf dich, die den Job, dich an einen Felsen zu erinnern, bemerkenswert gut ausfÃ¼hrt. Das erklÃ¤rt, dass du den kleinen Kerl bis jetzt nicht bemerkt hast. Kann ja nicht dein Fehler sein - als Krieger... Nop, definitiv nicht.`n`n");

    }

    if ($session[user][hitpoints] < $session[user][maxhitpoints]){

        if ($golinda) {

            output("`3\"`6Nun... lass uns mal sehen. Hmmm. Hmmm. Du siehst ein bisschen angeschlagen aus.`3\"`n`n\"`5Ã„h... ja. Ich schÃ¤tze schon. Was wird mich das kosten?`3\", fragst du betreten, \"`5WeiÃŸt du, normalerweise werde ich nicht so leicht verletzt.`3\"`n`n\"`6Ich weiÃŸ, ich weiÃŸ. Niemand von euch wird `^jemals`6 verletzt. Aber egal. FÃ¼r `$`b$cost`b`6 GoldstÃ¼cke mache ich dich wieder frisch wie einen Sommerregen. Ich kann dich auch zu einem niedrigeren Preis teilweise heilen, wenn du dir die volle Heilung nicht leisten kannst.`3\", sagt Golinda mit einem sÃ¼ÃŸen LÃ¤cheln.");

        } else {

            output("\"`6Sehen kann ich dich. Bevor du sehen konntest mich, hmm?`3\" bemerkt das alte Wesen. \"`6Ich kenne dich, ja; Heilung du suchst. Bereit zu heilen dich ich bin, wenn bereit zu bezahlen du bist.`3\"`n`n\"`5Oh-oh. Wieviel?`3\" fragst du, bereit dich von diesem stinkenden alten Dings ausnehmen zu lassen.`n`nDas alte Wesen pocht dir mit einem knorrigen Stab auf die Rippen: \"`6FÃ¼r dich... `$`b$cost`b`6 GoldstÃ¼cke fÃ¼r eine komplette Heilung!!`3\". Dabei krÃ¼mmt es sich und zieht ein TonflÃ¤schchen hinter einem Haufen SchÃ¤del hervor. Der Anblick dieses Dings, das sich Ã¼ber den SchÃ¤delhaufen krÃ¼mmt, um das FlÃ¤schchen zu holen, verursacht wohl genug geistigen Schaden, um eine grÃ¶ÃŸere Flasche zu verlangen.  \"`6Ich auch habe einige - Ã¤hm... 'gÃ¼nstigere' TrÃ¤nke im Angebot.`3\" sagt das Wesen, wÃ¤hrend es auf  einen verstaubten Haufen zerbrochener TonkrÃ¼ge deutet. \"`6Sie werden heilen einen bestimmten Prozentsatz deiner `iBeschÃ¤digung`i.`3\"");

        }

        addnav("HeiltrÃ¤nke");

        addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");

        for ($i=90;$i>0;$i-=10){

            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");

        }

        addnav("`bZurÃ¼ck`b");

        addnav("ZurÃ¼ck in den Wald", "forest.php");

        addnav("ZurÃ¼ck ins Dorf","village.php");

    }else if($session[user][hitpoints] == $session[user][maxhitpoints]){

        if ($golinda) {

            output("`3Golinda untersucht dich sehr sorgfÃ¤ltig. \"`6Nun, du hast diesen leicht eingewachsenen Zehennagel hier, aber ansonsten bist du vollkommen gesund. `^Ich`6 glaube, du bist nur hier her gekommen, weil du einsam warst.`3\", kichert sie.`n`nDu erkennst, dass sie Recht hat und dass du sie von ihren anderen Patienten abhÃ¤ltst. Deswegen gehst du zurÃ¼ck in den Wald.");

        } else {

            output("`3Die alte Kreatur schaut in deine Richtung und grunzt: \"`6Einen Heiltrank du nicht brauchst. Warum du mich stÃ¶rst, ich mich frage.`3\" Der Geruch seines Atems lÃ¤sst dich wÃ¼nschen, du wÃ¤rst gar nicht erst gekommen. Du denkst, es ist das Beste, einfach wieder zu gehen.");

        }

        forest(true);

    }else{

        if ($golinda) {

            output("`3Golinda untersucht dich sehr sorgfÃ¤ltig. \"`6Ohje! Du hast nicht einmal einen eingewachsenen Zehennagel, den ich heilen kÃ¶nnte! Du bist ein Prachtexemplar der " . ($session['user']['sex'] == 1 ? "Frauenschaft" : "MÃ¤nnerschaft") . "!  Komm bitte wieder, wenn du verletzt wurdest`3\". Damit wendet sie sich wieder ihrer TrÃ¤nkemischerei zu.`n`n\"`6Das werde ich`3\", stammelst du unglaublich verlegen und gehst zurÃ¼ck in den Wald.");

        } else {

            output("`3Die alte Kreatur blickt dich an und mit einem `^Wirbelwind einer Bewegung`3, die dich vÃ¶llig unvorbereitet erwischt, bringt sie ihren knorrigen Stab in direkten Kontakt mit deinem Hinterkopf. Du stÃ¶hnst und brichst zusammen.`n`nLangsam Ã¶ffnest du die Augen und bemerkst, dass dieses Biest gerade die letzten Tropfen aus einem Tonkrug in deinen Rachen schÃ¼ttet.`n`n\"`6Dieser Trank kostenlos ist.`3\" ist alles, was es zu sagen hat. Du hast das dringende BedÃ¼rfnis, die HÃ¼tte so schnell wie mÃ¶glich zu verlassen.");

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

            output("`3Du erwartest ein fauliges GesÃ¶ff und kippst den Trank herunter, aber als die FlÃ¼ssigkeit dir den Rachen hinunter lÃ¤uft, schmeckst du Zimt, Honig und irgendetwas fruchtiges. Du fÃ¼hlst WÃ¤rme durch deinen KÃ¶rper strÃ¶men und deine Muskeln fangen an, sich von selbst zusammenzufÃ¼gen. Mit klarem Kopf und wieder bei bester Gesundheit gibst du Golinda ihr Gold und verlÃ¤sst die HÃ¼tte in Richtung Wald.");

        } else {

            output("`3Mit verzerrtem Gesicht kippst du den Trank, den dir die Kreatur gegeben hat, runter. Trotz des fauligen Geschmacks fÃ¼hlst du, wie sich WÃ¤rme in deinen Adern ausbreitet und deine Muskeln heilen. Leicht taumelnd gibst du der Kreatur ihr Geld und verlÃ¤sst die HÃ¼tte.");

        }

        output("`n`n`#Du wurdest um $diff Punkte geheilt!");

        if ($HTTP_GET_VARS[pct]==100 && $session[user][dragonkills]>3 && e_rand(1,2)==2 && $session[user][reputation]>0) $session[user][reputation]--;

        forest(true);

    }else{

        if ($golinda) {

            output("`3\"`6Tss, tss!`3\", murmelt Golinda. \"`6Vielleicht solltest du erstmal zur Bank gehen und wiederkommen, sobald du `b`\$$newcost`6`b Gold hast?`3\"`n`nDu fÃ¼hlst dich ziemlich blÃ¶de, weil du ihre kostbare Zeit vergeudet hast.`n`n\"Oder vielleicht wÃ¤re ein billigerer Trank besser fÃ¼r dich?`3\", schlÃ¤gt sie freundlich vor.");

        } else {

            output("`3Die alte Kreatur durchbohrt dich mit einem harten, grausamen Blick. Deine blitzschnellen Reflexe ermÃ¶glichen dir, dem Schlag mit seinem knorrigen Stab auszuweichen. Vielleicht solltest du erst etwas Gold besorgen, bevor du versuchst, in den lokalen Handel einzusteigen. `n`nDir fÃ¤llt ein, dass die Kreatur `b`\$$newcost`3`b GoldmÃ¼nzen verlangt hat.");

        }

        addnav("HeiltrÃ¤nke");

        addnav("`^Komplette Heilung`0","healer.php?op=buy&pct=100");

        for ($i=90;$i>0;$i-=10){

            addnav("$i% - ".round($cost*$i/100,0)." Gold","healer.php?op=buy&pct=$i");

        }

        addnav("`bZurÃ¼ck`b");

        addnav("ZurÃ¼ck in den Wald","forest.php");

        addnav("ZurÃ¼ck ins Dorf","village.php");

    }

}

page_footer();

?>

