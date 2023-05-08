
<?
// found in www
// translation and mods by gargamel @ www.rabenthal.de
if (!isset($session)) exit();

    if ( $session[user][bounty] > 0 ) {
        output("`n`!Du triffst auf einen Gesandten der Dorfverwaltung`0, der Dich sehr
        misstrauisch beäugt. \"Bist Du nicht.....\" spricht Dich der Gesandte an.
        `nLeider warst Du kurz abgelenkt, so dass Du nur noch den Schluß hörst.
        `^\"Aber auf Dich ist ja schon, `9übrigens völlig zu Recht, `^ein Kopfgeld ausgesetzt!\"`0
        `n`nDir ist eigentlich egal, was er wollte und Du ziehst weiter.`0");
    }
    else {
        $chance = e_rand(1,100);
        if ( $chance < 33 ) {
            $gold = round($session[user][level] * e_rand(50,400));
            output("`nIrgendwas muss da schief gelaufen sein, den `Qdie Dorfverwaltung
            hat ein Kopfgeld von `^$gold Gold`Q auf Dich ausgesetzt!`0`nDu hast keine
            Ahnung warum, vielleicht eine Verwechselung. Du könntest ins Dorf zurückkehren
            um die Sache zu klären, aber wird man Dir glauben? "
            .($session[user][sex]?"Du bist doch ein braves Mädchen!":"Du bist doch ein anständiger Kerl!").
            "`n`nDu setzt lieber Deinen Weg fort.`0");
            debuglog("$gold Kopfgeld von der Dorfverwaltung ausgesetzt");
            //abschluss
            $session[user][specialinc] = "";
            $session[user][bounty]+=$gold;
            $user = $session[user][name];
            addnews("`4Die Dorfverwaltung hat ein Kopfgeld von $gold Gold auf $user ausgesetzt.`n");
        }
        else {
            output("`n`!Du triffst auf einen Gesandten der Dorfverwaltung`0, der Dich sehr
            misstrauisch beäugt. \"Bist Du nicht.....\" spricht Dich der Gesandte an, den
            Namen verstehst Du leider nicht, weil Du kurz abgelenkt warst. Aber dann hörst
            Du plötzlich hellwach zu: `n`Q\"....wird ein stattliches Kopfgeld auf Dich ausgesetzt.
            Los, unterschreib hier!\"`0 Etwas eingeschüchtert unterschreibst Du.`n`nDer Gesandte
            setzt seine Lesebrille auf, um die Unterlagen gegenzuzeichnen. \"Moment mal\" stutzt
            er, \"das ist ja eine ganz andere Unterschrift. `!Du bist ja garnicht "
            .($session[user][sex]?"die Gesuchte!":"der Gesuchte!")."`0\" `nEinige Entschuldigungen
            murmelned zieht sich der Gesandte zurück. Du weißt nicht so genau, was Du davon
            halten sollst und gehst weiter.`0");
        }
    }
?>


