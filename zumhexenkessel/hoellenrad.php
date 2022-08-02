<?php
/*
/*
 * Project: Das Höllenrad (The Wheel of Hell)
 * Ideen und Umsetzung (Hilfe): Cassandra, Lori
 * Copyright (C) 2006 Katrin Graumann [Leen/Cassandra]
 * Email: cassandra@leensworld.de
 * Adresse: http://www.leensworld.de/Merydia
 *
 * Beschreibung: Das Höllenrad soll im Schattenreich eine Möglichkeit sein, seine Edelsteine zu verringern. Es gibt einiges zu gewinnen - der Hauptpreis ist da ja wohl klar - aber man kann auch einiges verlieren, es ist halt ein Glücksrad.
 Ich habe die Benutzung auf 5 Runden pro Tag beschränkt, jeder kann sich das aber auch anders einstellen!

 * Einbauanleitung:

SQL-Befehle:

    ALTER TABLE `accounts` ADD `hellwheel` TINYINT( 4 ) NOT NULL ;

öffne newday.php und suche:

    $session['user']['gotfreeale'] = 0;

füge danach ein:

    $session['user']['hellwheel'] = 0;

speichern und schließen!

öffne shades.php und suche:

    addnav("In Ruhmeshalle spuken","hof.php");

füge danach ein:

    addnav("Zum Höllenrad","hoellenrad.php");




*/
require_once 'common.php';
if ($session['user']['alive'])
    {
    redirect("village.php");
    }
else
    {
    }


page_header('Das Höllenrad');

addnav('Möglichkeiten');
addnav('Nicht drehen','shades.php');
//Einstellungen wer drehen darf
if ($session['user']['hellwheel'] < 5 && $session['user']['gems'] >= 1 && $session['user']['gravefights'] >= 4)
    {
    addnav('Am Rad drehen (1 Edelstein)','hoellenrad.php?op=turn');
    }
output('`c`b`QDas Höllenrad`b`c`n`n');
output('`QMitten im Schattenreich steht ein riesiges weißes Rad. Als du näher trittst erkennst du, dass das Rad gänzlich aus riesigen blankgeputzten Knochen besteht. In der Mitte des Rades grinst dich ein riesiger Schädel an.`n');
output('Was wirst du tun?`&');
//Einstellung wer genug gedreht hat
if ($session['user']['hellwheel'] >= 5)
    {
    output('`n`nDu hast heute schon genug dein Glück herausgefordert. Komme ein andermal wieder!');
    }


if ($_GET['op'] == 'turn')
    {
    $session['user']['hellwheel'] ++;
    $session['user']['gems'] --;
    output('<hr>`n`n',true);
//Hier die Ausgangswahrscheinlichkeit angeben
    $var = e_rand (1,100);
    switch (TRUE)
        {
        case ($var >= 1 && $var <= 10):
        $big = e_rand (1,10);
        switch (TRUE)
            {
            case ($big >= 1 && $big <= 3):
            //großer Gewinn Seelenpunkte
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen großen Geist. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt 20 Seelenpunkte zu deinen bisherigen dazu, dieser Gewinn verfällt mit der Zeit!`c`b');

            $session['user']['soulpoints'] += 20;
            break;

            case ($big >= 4 && $big <= 5):
            //großer Gewinn Gefallen
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf ein Abbild des Ramius. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt 50 Gefallen zu deinen bisherigen dazu!`c`b');
            $session['user']['deathpower'] += 50;
            break;
            case ($big >= 6 && $big <= 8):
            //großer Gewinn Grabkämpfe
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf ein großes Grab. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt einmalig 10 Grabkämpfe zu deinen bisherigen dazu!`c`b');
            $session['user']['gravefights'] += 10;
            break;
            case ($big >= 9 && $big <= 10):
            //großer Gewinn Edelsteine
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen Sack mit Edelsteinen. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt 5 Edelsteine!`c`b');
            $session['user']['gems'] += 5;
            break;
            }
        break;
        case ($var >= 11 && $var <= 20):
        $small = e_rand (1,10);
        switch (TRUE)
            {
            case ($small >= 1 && $small <= 3):
            //kleiner Gewinn Seelenpunkte
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen kleinen Geist. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt 10 Seelenpunkte zu deinen bisherigen dazu, dieser Gewinn verfällt mit der Zeit!`c`b');
            $session['user']['soulpoints'] += 10;
            break;
            case ($small >= 4 && $small <= 5):
            //kleiner Gewinn Gefallen
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf ein kleines Abbild des Ramius. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt 10 Gefallen zu deinen bisherigen dazu!`c`b');
            $session['user']['deathpower'] += 10;
            break;
            case ($small >= 6 && $small <= 8):
            //kleiner Gewinn Grabkämpfe
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf ein kleines Grab. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt einmalig 5 Grabkämpfe zu deinen bisherigen dazu!`c`b');
            $session['user']['gravefights'] += 5;
            break;
            case ($small >= 9 && $small <= 10):
            //kleiner Gewinn Edelsteine
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen Edelstein. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn beträgt 1 Edelstein!`c`b');
            $session['user']['gems'] += 1;
            break;
            }
        break;
        case ($var >= 21 && $var <= 30):
        $lost = e_rand (1,10);
        switch (TRUE)
            {
            case ($lost >= 1 && $lost <= 3):
            //Verlust Seelenpunkte
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen schwarzen Geist. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Verlust beträgt 10 Seelenpunkte!`c`b');
            $session['user']['soulpoints'] -= 10;
            break;
            case ($lost >= 4 && $lost <= 5):
            //Verlust Gefallen
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf eine Peitsche. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Verlust beträgt 10 Gefallen!`c`b');
            $session['user']['deathpower'] -= 10;
            break;
            case ($lost >= 6 && $lost <= 8):
            //Verlust Grabkämpfe
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf ein schwarzes Grab. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Verlust beträgt einmalig 5 Grabkämpfe!`c`b');
            $session['user']['gravefights'] -= 5;
            break;
            case ($lost >= 9 && $lost <= 10):
            //Verlust Edelsteine
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen schwarzen Edelstein. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            if ($session['user']['gems'] <= 3)
                {
                output('`b`cDein Verlust beträgt all deine Edelsteine!`c`b');
                $session['user']['gems'] = 0;
                }
            else
                {
                output('`b`cDein Verlust beträgt 3 Edelsteine!`c`b');
                $session['user']['gems'] -= 3;
                }
            break;
            }
        break;
        case ($var >= 31 && $var <= 40):
        $mini = e_rand(1,5);
        switch (TRUE)
            {
            case ($mini == 1):
            //Nochmal drehen
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen Kreis. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn lautet noch einmal zu drehen!`c`b');
            $session['user']['gems'] ++;
            $session['user']['hellwheel'] --;
            addnav('Am Rad drehen (1 Edelstein)','hoellenrad.php?op=turn');
            break;
            case ($mini == 2):
            //Erniedrigung
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf zwei rote leuchtende Augen. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDu wurdest erniedrigt!`c`b');
            $session['user']['gravefights'] = 0;
            $session['user']['soulpoints'] = 0;
            $session['user']['hellwheel'] = 5;
            break;
            case ($mini == 3):
            //Trampelpfad zum Fluss
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen Fluss. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn lautet gehe den Weg zum Seelenfluss!`c`b');
            addnav('Zum Seelenfluss','styx.php');
            break;
            case ($mini == 4):
            //Wiederbelebung
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf ein Boot. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDu wirst wiedergeboren!`c`b');
            $session['user']['alive'] = 1;
            addnav('Ins Reich der Lebenden','newday.php?resurrection=true');
            addnews(''.($session['user']['name']).' `&hat am Höllenrad den Hauptgewinn gewonnen. '.($session['user']['name']).' `&ist wiederbelebt wurden!`0');
            break;
            case ($mini == 5):
            //Baum des Todes
            output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf einen Baum. Eine monotone Stimme erschallt aus dem Rad:`n`n');
            output('`b`cDein Gewinn lautet noch einmal den Baum des Todes zu besuchen!`c`b');
            $session['user']['deadtreepick'] --;
            break;
            }
        break;
        default:
        output('Das Rad dreht und dreht sich, bis es nach einer halben Ewigkeit stehenbleibt. Eine skelletierte Hand zeigt auf nichts. Eine monotone Stimme erschallt aus dem Rad:`n`n');
        output('`b`cNiete!`c`b');
        break;
        }
    }

page_footer();

?> 