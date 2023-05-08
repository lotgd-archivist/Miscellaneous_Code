
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

    output("`nPlötzlich hörst Du ein eindringliches Pfeiffen, sofort abgelöst von
    einem Donnern. Die Erde bebt etwas. Neben Dir schiesst ein `8Geysir`0 in die Höhe!`n`n`0");
    $von = 3;
    if ( $session[user][hashorse] > 0 && $session[bufflist][mount][rounds] > 2 ) $von = 1;
    switch ( e_rand($von,6) ) {
        case 1:
        output("Du springst zur Seite und betrachtest das beeindruckende Schauspiel.
        Reichlich Wasser stürzt neben Dir zu Boden. Dein Tier trinkt davon, nur leider
        scheint es nicht sehr bekömmlich zu sein. `2So kann es Dich nicht unterstützen.`0");
        $session[bufflist][mount][rounds]=1;
        break;
        case 2:
        output("Du springst zur Seite und betrachtest das beeindruckende Schauspiel.
        Reichlich Wasser stürzt neben Dir zu Boden. Dein Tier trinkt davon, und scheint
        neue Kraft zu schöpfen. `^Du erhälst 50 Runden zusätzliche Unterstützung!`0");
        $session[bufflist][mount][rounds]+=50;
        break;
        case 3:
        output("Ein ordentlicher Schwall des kochend heissen Wassers trifft Dich. Du
        ziehst Dir ernsthafte Verbrühungen zu und `8verlierst einige Lebenspunkte.`0");
        $session[user][hitpoints]=round( $session[user][hitpoints]*0.80 );
        break;
        case 4:
        output("An der Spitze der Fontäne löst sich das Wasser in feine Tropfen auf.
        Du siehst einen wunderschönen Regenbogen, an dessen Ende ja ein Topf Gold
        stehen soll. Aber das ist nur ein Märchen...`n`n
        Fröhlich gehst Du weiter.`0");
        break;
        case 5:
        output("Du bekommst von dem kochend heissen Wasser einige Spritzer ab und
        verbrühst ein wenig im Gesicht.`n
        `3Du verlierst ein Charmepunkt.`0");
        $session[user][charm]-=1;
        break;
        case 6:
        output("Im herausschiessenden Wasserstrahl bemerkst Du funkeln und glitzern.
        Tief aus der Erde hat der Geysir `Qeinen Edelstein`0 zu Tage gefördert, der Dir
        nun vor die Füsse fällt.`0");
        $session[user][gems]++;
        break;
    }

?>


