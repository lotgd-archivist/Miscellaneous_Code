
<?
// games taken from: The Romar Casino
// source available at: www.dragonprime.net
//
// mods made by gargamel @ www.rabenthal.de
// - integration into race specific enhancements
// - make one game available for each race
//
require_once("common.php");

// Gargamel inventory system
$session['user']['blockinventory']=1; // don't use the inventory

switch ( $session['user']['race'] ) {
    case 1:    //troll    //würfel
    page_header("Akwark");
    output("`c`b`&~~~ Stollen des Glückes ~~~`b`c`n");
    break;

    case 2:    //elf      //glücksrad
    page_header("Quintarra");
    output("`c`b`&~~~ Ast des Glückes ~~~`b`c`n");
    break;

    case 3:    //human    //hütchen
    page_header("Waldpark");
    output("`c`b`&~~~ Garten des Glückes ~~~`b`c`n");
    break;

    case 4:    //dwarf    //höchste karte
    page_header("Thais");
    output("`c`b`&~~~ Stollen des Glückes ~~~`b`c`n");
    break;
}


if ($_GET[op]=="") {
    switch ( $session['user']['race'] ) {
        case 1:    //troll    //würfel
        addnav("Zurück","race_troll.php?op=enter2");
        addnav("Spiel wagen","race_game.php?op=dice");
        output("`7Du gehst den Pfad des Glücks und bist kurz darauf bei einer kleinen
        Lichtung. Mächtige Baumstämme sind hier rund um Stümpfe gelegt. Diese rustikale
        Atmosphäre scheint beliebt, kaum ein Platz ist frei. Überall erkennst Du den
        Klang der Würfel.`0");
        break;

        case 2:    //elf      //glücksrad
        addnav("Zurück","race_elf.php?op=enter2");
        addnav("Spiel wagen","race_game.php?op=wheel");
        output("`7Du flatterst am Ast des Glücks entlang, bis Du sehr schnell an
        eine Verdickung kommst. Rundherum haben sich viele Elfen niedergelassen und
        schauen gebannt auf das Glücksrad, daß dort befestigt wurde.`n`0");
        break;

        case 3:    //human    //hütchen
        addnav("Zurück","race_human.php?op=enter2");
        addnav("Spiel wagen","race_game.php?op=hut");
        output("`7Du schlenderst einen kleinen Weg entlang und kommst sehr schnell
        auf einen kleinen schattigen Platz. An einer Ecke stehen mehrere Menschen
        beisammen und spielen das \"Hütchenspiel\".`n`0");
        break;

        case 4:    //dwarf    //höchste karte
        addnav("Zurück","race_dwarf.php?op=enter2");
        addnav("Spiel wagen","race_game.php?op=card");
        output("`7Zügig gehst Du den kurzen Stollen entlang und erreichst am Ende
        eine kleine Höhle. An Holztischen sitzen mehrere Zwerge, die ein einfaches
        Kartenspiel Namens \"Höchste Karte\" spielen.`n`0");
        break;
    }
}
else if ($_GET[op]=="dice") {
    if ($_GET[action]=="") {
        output("`3Du suchst Dir einen freien Platz und setzt Dich einfach dazu. Dir
        gegenüber sitzt wohl "
        .($session[user][sex]?"der Spielleiter":"die Spielleiterin").
        ", denn unermüdlich erklärt "
        .($session[user][sex]?"er":"sie").
        " die Regeln:`n
        \"Du musste drei Würfel schmeissen. Haben alle gleiche Augen, kannst Du
        viel gewinnen, bei zwei gleichen Augen noch ein wenig.\"`n`n
        Du hast diese einfache Regel verstanden und überlegst, ob Du wirklich spielen
        sollst.`0");
        addnav("Spiele um 100 Gold", "race_game.php?op=dice&action=play&bet=100");
        addnav("Spiele um 250 Gold", "race_game.php?op=dice&action=play&bet=250");
        addnav("Spiele um 500 Gold", "race_game.php?op=dice&action=play&bet=500");
        addnav("Spiele um 1000 Gold", "race_game.php?op=dice&action=play&bet=1000");
        addnav("Spiele um 2500 Gold", "race_game.php?op=dice&action=play&bet=2500");
        addnav("Spiele um 5000 Gold", "race_game.php?op=dice&action=play&bet=5000");
        addnav("Spiele um 10000 Gold", "race_game.php?op=dice&action=play&bet=10000");
        addnav("Zurück","race_troll.php?op=enter2");
    }
    else if ($_GET[action]=="play") {
        $bet = $_GET[bet];
        if ( $bet > $session[user][gold] ) {
            output("`n`7"
            .($session[user][sex]?"Der Spielleiter":"Die Spielleiterin").
            " schaut Dich an und macht Dich darauf aufmerksam, dass Du nicht genug
            Gold zum Spielen hast und Kredit gibts hier wohl nicht!`0");
            addnav("Zurück","race_game.php?op=dice");
        }
        else {
            $first = e_rand(1,6);
            $second = e_rand(1,6);
            $third = e_rand(1,6);
            output ("`n`7Mutig greifst Du die Würfel, packst den Ersten in den Becher.
            Du schüttelst kräftig und knallst den Becher auf den Tisch. Als Du ihn
            hochhebst, siehst Du:`n`n`0");
            output("`%| " . $first . " |  |  |`n`n");
            output ("`n`7Nun nimmst Du den zweiten Würfel, packst ihn wie den Ersten
            in den Becher. Du gibst Dir Mühe beim schütteln und knallst den Becher
            erneut auf den Tisch. Gespannt nimmst Du ihn noch und siehst:`n`n`0");
            output("`%| " . $first . " | " . $second . " |  |`n`n");
            output ("`n`7Jetzt ist es Zeit für den finalen Wurf! Hochkonzentriert
            gehst Du die Sache an. Du schüttelst diesmal den Becher besonders gut
            und lässt ihn direkt neben den ersten beiden Würfeln auf den Tisch krachen.`n
            Einige andere Spieler schauen Dir über die Schulter, als Du nun das letzte
            Mal den Becher hebst. Alle sehen Dein Ergebnis:`n`n`0");
            output("`%| " . $first . " | " . $second . " | " . $third . " |`n`n");
            $one = $two = $three = $four = $five = $six = 0;
            if($first == "1"){$one++;}
            if($second == "1"){$one++;}
            if($third == "1"){$one++;}
            if($first == "2"){$two++;}
            if($second == "2"){$two++;}
            if($third == "2"){$two++;}
            if($first == "3"){$three++;}
            if($second == "3"){$three++;}
            if($third == "3"){$three++;}
            if($first == "4"){$four++;}
            if($second == "4"){$four++;}
            if($third == "4"){$four++;}
            if($first == "5"){$five++;}
            if($second == "5"){$five++;}
            if($third == "5"){$five++;}
            if($first == "6"){$six++;}
            if($second == "6"){$six++;}
            if($third == "6"){$six++;}

            if(($two==3) || ($three==3) || ($four==3) || ($five==3)) {
                 output("`3 3 Gleiche!  Du `2verdreifachst`3 deinen Einsatz!!");
                 output("`n`n`@".($session[user][sex]?"Der Spielleiter":"Die Spielleiterin").
                 " schiebt Dir Deine `^" . $bet * 3 . "`@ Gold über den Tisch!!");
                 $session[user][gold] += $bet * 3;
            }
            else if($one == 3) {
                 output("`3 3 Einsen!  Du erhälst den `2vierfachen`3 Einsatz!!");
                 output("`n`n`@".($session[user][sex]?"Der Spielleiter":"Die Spielleiterin").
                 " schiebt Dir Deine `^" . $bet * 4 . "`@ Gold über den Tisch!!");
                 $session[user][gold] += $bet * 4;
            }
            else if($six == 3) {
                 output("`3 3 Sechsen!  Du erhälst den `2sechsfachen`3 Einsatz!!");
                 output("`n`n`@".($session[user][sex]?"Der Spielleiter":"Die Spielleiterin").
                 " schiebt Dir Deine `^" . $bet * 6 . "`@ Gold über den Tisch!!");
                 $session[user][gold] += $bet * 6;
            }
            else if(($one==2) || ($two==2) || ($three==2) || ($four==2) || ($five==2) || ($six==2)) {
                 output("`3 2 Gleiche!  Du `2bekommst`3 die Hälfte Deines Einsatzes als Gewinn!!");
                 output("`n`n`@".($session[user][sex]?"Der Spielleiter":"Die Spielleiterin").
                 " schiebt Dir Deine `^" . $bet / 2 . "`@ Gold über den Tisch!!");
                 $session[user][gold] += $bet / 2;
            }
            else {
                 output("`^Du verlierst, sorry");
                 $session[user][gold] -= $bet;
            }
            addnav("Neues Spiel","race_game.php?op=dice");
            addnav("Zurück","race_troll.php?op=enter2");
        }
    }
}
else if ($_GET[op]=="wheel") {
    if ($_GET[action]=="") {
        output("`3Du lässt Dich bei den anderen Elfen nieder und siehst Dir das
        Glückrad, dass sich gerade dreht, genauer an. Es besteht aus lauter `@grünen`3
        und gelben Feldern. Vor dem Rad flattert "
        .($session[user][sex]?"ein Elfe":"eine Elfin").
        " und erklärt nochmal die Spielregeln:`n
        \"Du setzt immer auf die `@grünen`3 Felder! Bleibt das Rad auf grün stehen,
        erhälst Du Deinen Einsatz als Gewinn. Anderenfalls ist er verloren.\"`n`n
        Du hast diese einfache Regel verstanden und überlegst, ob Du wirklich spielen
        sollst.`0");
        addnav("Spiele um 100 Gold", "race_game.php?op=wheel&action=play&bet=100");
        addnav("Spiele um 250 Gold", "race_game.php?op=wheel&action=play&bet=250");
        addnav("Spiele um 500 Gold", "race_game.php?op=wheel&action=play&bet=500");
        addnav("Spiele um 1000 Gold", "race_game.php?op=wheel&action=play&bet=1000");
        addnav("Spiele um 2500 Gold", "race_game.php?op=wheel&action=play&bet=2500");
        addnav("Spiele um 5000 Gold", "race_game.php?op=wheel&action=play&bet=5000");
        addnav("Spiele um 10000 Gold", "race_game.php?op=wheel&action=play&bet=10000");
        addnav("Zurück","race_elf.php?op=enter2");
    }
    else if ($_GET[action]=="play") {
        $bet = $_GET[bet];
        if ( $bet > $session[user][gold] ) {
            output("`n`7"
            .($session[user][sex]?"Der Elfe":"Die Elfin").
            " schaut Dich an und macht Dich darauf aufmerksam, dass Du nicht genug
            Gold zum Spielen hast und Kredit gibts hier wohl nicht!`0");
            addnav("Zurück","race_game.php?op=wheel");
        }
        else {
            $chance = e_rand(1,100);
            output ("`n`7"
            .($session[user][sex]?"Der Elfe":"Die Elfin").
            " holt kräftig aus und dreht das Rad!`n`n
            Gebannt starren alle auf die kleine Nadel, die das Ergebnisfeld anzeigt.
            Als die Farben immer langsamer unter der Nadel durchziehen, verstummt auch
            das letzte Geflatter. Dann bleibt das Rad stehen...`n`n");
            if ($chance >= 50) {
                $session[user][gold]+=$bet;
                output( ($session[user][sex]?"Der Elf ":"Die Elfin ")." verkündet:
                `@Grün!`7 Du hast gewonnen!");
            }
            else {
                $session[user][gold]-=$bet;
                output( ($session[user][sex]?"Der Elf ":"Die Elfin ")." verkündet:
                `^Gelb!`7 Du hast verloren!");
            }
            addnav("Neues Spiel","race_game.php?op=wheel");
            addnav("Zurück","race_elf.php?op=enter2");
        }
    }
}
else if ($_GET[op]=="hut") {
    if ($_GET[action]=="") {
        output("`3Du gesellst Dich zu den anderen Menschen und guckst erstmal zu.`n
        Auf einer grob gewebten Decke sitzt "
        .($session[user][sex]?"ein Mann":"eine Frau").
        ", die mit schnellen Bewegungen 3 Nußschalen rotieren lässt. Um Dich herum
        siehst Du glückliche und traurige Gesichter. Aber alle sind von dem Spiel
        fasziniert.`n`n
        Dann spricht Dich "
        .($session[user][sex]?"ein Mann":"eine Frau").
        " an und erklärt kurz die Regeln:`n
        \"Ich lege eine Perle unter eine Nußschale und mische diese dann. Wenn Du
        mir sagen kannst, unter welcher Schale die Perle am Ende liegt, gewinnst Du
        doppelt - sonst bekomme ich Deinen Einsatz.\"`n`n
        Du hast diese einfache Regel verstanden und überlegst, ob Du wirklich spielen
        sollst.`0");
        addnav("Spiele um 100 Gold", "race_game.php?op=hut&action=play&bet=100");
        addnav("Spiele um 250 Gold", "race_game.php?op=hut&action=play&bet=250");
        addnav("Spiele um 500 Gold", "race_game.php?op=hut&action=play&bet=500");
        addnav("Spiele um 1000 Gold", "race_game.php?op=hut&action=play&bet=1000");
        addnav("Spiele um 2500 Gold", "race_game.php?op=hut&action=play&bet=2500");
        addnav("Spiele um 5000 Gold", "race_game.php?op=hut&action=play&bet=5000");
        addnav("Spiele um 10000 Gold", "race_game.php?op=hut&action=play&bet=10000");
        addnav("Zurück","race_human.php?op=enter2");
    }
    else if ($_GET[action]=="play") {
        $bet = $_GET[bet];
        if ( $bet > $session[user][gold] ) {
            output("`n`7"
            .($session[user][sex]?"Der Mann":"Die Frau").
            " schaut Dich an und macht Dich darauf aufmerksam, dass Du nicht genug
            Gold zum Spielen hast und Kredit gibts hier wohl nicht!`0");
            addnav("Zurück","race_game.php?op=hut");
        }
        else {
            $chance = e_rand(1,100);
            output ("`n`7Das Spiel beginnt!`n`n"
            .($session[user][sex]?"Der Mann ":"Die Frau ").
            "stülpt eine der kleinen Nußschalen über die Perle und beginnt diese
            wie wild über den Tisch zu schieben.`n`n
            Ziemlich schnell hast Du die richtige Nußschale aus den Augen verloren,
            so dass Du nicht mehr weisst, wo nun die Perle ist. Am Ende deutest Du
            einfach auf eine der Nußschalen.`n`n`0");
            //if ($chance >= 33 && $chance <= 66) {
            if ($chance <= 33 ) {
                $session[user][gold]+=$bet*2;
                output( ($session[user][sex]?"Er ":"Sie")." hebt die Nußschale an
                und die Perle liegt drunter. `\$Du hast gewonnen!");
            }
            else {
                $session[user][gold]-=$bet;
                output( ($session[user][sex]?"Er ":"Sie")." hebt die Nußschale an
                und zeigt Dir, dass die Perle nicht drunter liegt.
                `\$Du hast verloren!");
            }
            addnav("Neues Spiel","race_game.php?op=hut");
            addnav("Zurück","race_human.php?op=enter2");
        }
    }
}
else if ($_GET[op]=="card") {
    if ($_GET[action]=="") {
        output("`3Du schlenderst rüber zu einem der schmutzigen hölzernen Tische.
        Du kannst sehen, daß Bier über den Tisch verschüttet wurde und zerbrochenes
        Glas sich davor verteilt. Also die besten Vorraussetzungen für ein dreck....,
        aehm sauberes Spiel.`n`nEs gibt aber eine gute Nachricht, denn "
        .($session[user][sex]?"der Zwerg":"die Zwergin").
        " vor Dir mischt zwei Decks Karten und hält ein Stapel Gold bereit, daß bald
        das Deine sein könnte.`n`n
        Du hörst kurz der Regelerklärung zu:`n
        \"Jeder zieht eine Karte von seinem Deck. Die höchste Karte gewinnt.\"`n`n
        Du hast diese einfache Regel verstanden und überlegst, ob Du wirklich spielen
        sollst.`0");
        addnav("Spiele um 100 Gold", "race_game.php?op=card&action=play&bet=100");
        addnav("Spiele um 250 Gold", "race_game.php?op=card&action=play&bet=250");
        addnav("Spiele um 500 Gold", "race_game.php?op=card&action=play&bet=500");
        addnav("Spiele um 1000 Gold", "race_game.php?op=card&action=play&bet=1000");
        addnav("Spiele um 2500 Gold", "race_game.php?op=card&action=play&bet=2500");
        addnav("Spiele um 5000 Gold", "race_game.php?op=card&action=play&bet=5000");
        addnav("Spiele um 10000 Gold", "race_game.php?op=card&action=play&bet=10000");
        addnav("Zurück","race_dwarf.php?op=enter2");
    }
    else if ($_GET[action]=="play") {
        $bet = $_GET[bet];
        if ( $bet > $session[user][gold] ) {
            output("`n`7"
            .($session[user][sex]?"Der Zwerg ":"Die Zwergin").
            " schaut Dich an und macht Dich darauf aufmerksam, dass Du nicht genug
            Gold zum Spielen hast und Kredit gibts hier wohl nicht!`0");
            addnav("Zurück","race_game.php?op=card");
        }
        else {
            $yourcard = e_rand(2, 14);
            $yoursuit = e_rand(0, 3);
            $mycard = e_rand(2, 14);
            $mysuit = e_rand(0, 3);
            $suitName = array("Karo", "Herz", "Pik", "Kreuz");
            $specialCard = array("Bauer", "Dame", "König", "As");
            if(($yourcard == $mycard) && ($mysuit == $yoursuit)) $result = 0;
            if(($yourcard == $mycard) && ($mysuit < $yoursuit)) $result = 1;
            if(($yourcard == $mycard) && ($mysuit > $yoursuit)) $result = -1;
            if($yourcard > $mycard) $result = 1;
            if($yourcard < $mycard) $result = -1;
            if($yourcard > 10) {
                $yourcard = $specialCard[$yourcard - 11];
            }
            if($mycard > 10) {
                $mycard = $specialCard[$mycard - 11];
            }
            //$session[romar][jackpot]=$jack+$bet*0.1;
            //savesetting("jackpot",addslashes($session[romar][jackpot]));

            output("`2Du wettest: `^$bet Gold`2`n");
            output("`n`n".($session[user][sex]?"Der Zwerg ":"Die Zwergin")." sagt:
            \"Meine Karte ist ".$suitName[$mysuit]." $mycard\"`n" );
            output("`n`nDeine Karte ist: ".$suitName[$yoursuit]." $yourcard`n");
            switch($result) {
                case 1:
                output("`n`n`n`7"
                .($session[user][sex]?"Der Zwerg ":"Die Zwergin").
                " guckt etwas mürrisch. `^Du gewinnst!!!`0");
                $session[user][gold] += $bet;
                break;
                case 0:
                output("`n`n`^Gleichstand. Kein Gold wechselt den Besitzer.`0");
                break;
                case -1:
                output("`n`n`n`7"
                .($session[user][sex]?"Der Zwerg ":"Die Zwergin").
                " lacht dich an. `^\"Ich gewinne, her mit dem Geld!\"`0");
                $session[user][gold] -= $bet;
                break;
            }
            addnav("Neues Spiel","race_game.php?op=card");
            addnav("Zurück","race_dwarf.php?op=enter2");
        }
    }
}

page_footer();
?>


