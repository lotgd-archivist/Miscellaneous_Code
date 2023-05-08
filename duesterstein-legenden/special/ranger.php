
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`3Auf Deinem Weg durch den Wald triffst Du auf einen Waldhüter.`0 Er trägt an
    seiner Uniform das Wappen von Rabenthal. Scheint amtlich zu sein....`n
    Der Waldhüter weist Dich darauf hin, dass er dafür zuständig ist, im Wald für
    Ordnung zu sorgen. Und da er noch in der Probezeit ist, arbeitet er wirklich sehr
    genau!`n`n");
    
    switch (e_rand(1,4)) {
        case 1: // Weg
        $gold = $session[user][level]*10;
        $goldb = $session[user][level]*12;
        $geb = $goldb - $gold;
        output("Aha, ".$session[user][name]."`0 auf frischer Tat ertappt!`n`nDu weisst
        zunächst gar nicht, was er meint, aber dann wird es Dir schnell klar.
        \"Du bist hier mitten in einer Schonung! Das wurde hier alles extra angepflanzt
        und liebevoll gepflegt, und Du latscht hier durch?\" regt sich der Waldhüter
        auf. `2\"Dafür muss ich eine Verwarnung aussprechen, was ich hiermit gerne tue\" fährt
        der Waldhüter fort und weist auch gleich auf die Kosten hin: $gold Goldstücke.`n`n`0");
        if ( $session[user][gold] >$gold ) {
            output("Der Betrag wird sofort vom Waldhüter kassiert.`0");
            $session[user][gold]-= $gold;
        }
        else if ( $session[user][goldinbank] > $goldb ) {
            output("Da Du nicht genug Gold dabei hast, wird der Waldhüter eine
            Zahlungsforderung an die Bank senden. Weil er nun mehr Arbeit mit Dir hat,
            kommen noch $geb Gold Gebühren hinzu, so dass die Bank Dir insgesamt $goldb
            Gold belastet.`0");
            $session[user][goldinbank]-=$goldb;
        }
        else if ( ($session[user][gold] + $session[user][goldinbank]) > $goldb ) {
            output("Da Du die Strafe nicht gleich komplett zahlen kannst, sendet der
            Waldhüter eine Zahlungsforderung über den Restbetrag an die Bank. Weil er
            nun mehr Arbeit mit Dir hat, kommen noch $geb Gold Gebühren dazu.`0");
            $goldb-= $session[user][gold];
            $session[user][gold]=0;
            $session[user][goldinbank]-= $goldb;
        }
        else {
            output("Auch wenn Du zur Zeit nicht genug Geld hast, um die Strafe zu
            bezahlen, wirst Du nicht darum herumkommen. Der Waldhüter schickt eine
            Zahlungsforderung an die Bank, die Dir automatisch einen Kredit gewährt.
            Weil der nun mehr Arbeit mit Dir hat, kommen noch $geb Gold Gebühren
            dazu.`0");
            $goldb-= $session[user][gold];
            $session[user][gold]=0;
            $session[user][goldinbank]-= $goldb;
        }
        output("`n`n`2Der Waldhüter rät Dir zum Abschied, zukünftig auf den Wegen zu
        bleiben.`0");
        break;
        
        case 2: // Alkoholkontrolle
        output("\"In letzter Zeit haben wir verstärkt Probleme mit Trunkenbolden, die
        nichts als Ärger machen.\" eröffnet der Waldhüter das Gespräch. \"Ich werde
        daher einen amtlichen Alkoholtest mit Dir durchführen\" informiert er Dich.`n
        `8Du must ihn kräftig anhauchen.`n`n`0");
        if ( $session[user][drunkenness] >= 66 ) {
            output("\"Oh mann... Du hast ja eine kräftige Fahne. Kommst Du direkt aus
            der Taverne?\" fragt er Dich. Du weist keine rechte Antwort, denn er hat
            ja recht.`n`n
            `QDamit andere Dorfbewohner nicht von Dir belästigt werden, vertreibt er
            Dich für heute aus dem Wald. Du kannst morgen wieder Kämpfen.`0");
            $session[user][turns]=0;
        }
        else if ( $session[user][drunkenness] >= 25 ) {
            output("\"Erzähl mir bloß nicht, dass Du nichts getrunken hast!\" hält Dir
            der Waldhüter vor. \"Aber Du scheinst nur einen kleinen Glimmer zu haben.
            Ruhe Dich etwas aus, dann kannst Du weiterziehen.\" Der Waldhüter belässt
            es bei dieser Ermahnung und verschwindet.`n`n
            `QDu schläfst 3 Runden lang und ziehst dann weiter.`0");
            $session[user][turns]-=3;
        }
        else {
            output("\"Du gehörst offensichtlich zu den ehrenwerten Dorfbewohnern\" lobt
            Dich der Waldhüter für Deine Nüchternheit.`n`n
            `9Weil Du so positiv aufgefallen bist, bekommst Du einen Charmepunkt.`0");
            $session[user][charm]+=1;
        }
        break;

        case 3: // Reitweg
           if ( $session[user][hashorse]>0 && $playermount[mountcategory]=='Pferde') {
            output("`$\"Halt! Sofort HALT!\"`0 brüllt Dich der Waldhüter an. Fragend deutet
            er neben Dich. \"$playermount[mountname]\" entgegnest Du knapp.`nDer Waldhüter
            weist Dich nun ausführlich darauf hin, dass das Reiten nur auf den dafür
            besonders gekennzeichneten Wegen erlaubt ist. \"Und hier NICHT!\" schliesst
            sein Vortrag.`n`n
            Auf der Suche nach einem Reitweg `Qverlierst Du einen Waldkampf.`0");
            if ( $session[bufflist][mount][rounds] > 1 ) {
                output(" `%Dein $playermount[mountname] verliert viel Kraft.`0");
                $session[bufflist][mount][rounds]=1;
            }
            $session[user][turns]-=1;
        }
        else if (  $session[user][hashorse]>0 ) {
            output("`6\"".($session[user][sex]?"Gute Frau, ":"Guter Mann, ")."ein
            $playermount[mountname] kann hier nicht frei rumrennen!\" belehrt Dich
            der Waldhüter.`0`nDu siehst ein, dass das andere Bewohner erschrecken könnte
            und kommst daher mit einer Ermahnung davon.`n`n
            `7Du verlierst einen Charmepunkt.`0");
            $session[user][charm]-=1;
        }
        else {
            output("`8Der Waldhüter mustert Dich mit prüfenden Blicken. Aber er hat
            offenbar nichts zu beanstanden, denn er geht wortlos weiter.`0");
        }
        break;

        case 4: // Verschmutzung
        output("\"Schön das Du Deine Waffe säuberst. Nur werfe gefälligst das gebrauchte
        Tuch nicht in den Wald!\" herrscht Dich der Waldhüter an.`n
        Weil Du einsichtig bist, lässt er Dich gehen.`n`n
        `2Für die Waldschmutzung verlierst Du einen Charmepunkt.`0");
        $session[user][charm]-=1;
        break;
    }
}
?>


