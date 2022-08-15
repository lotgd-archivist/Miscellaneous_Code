
<?php
/****************************************/
/* Schlammloch
/* filename: mud.php
/* Version 0.1
/* Type: Forest Event
/* Web: http://www.pqcomp.com/logd
/* E-mail: logd@pqcomp.com
/* Author: Lonny Luberts
/*
/* Modification by SkyPhy, July 04
/* Translation by SkyPhy, July 04
/**************************************/

$session['user']['specialinc'] = 'mud.php';

switch($_GET['op']) {
    case 'bucht':
        output("`TWährend deiner Reise durch den Wald gerätst du zufällig immer näher an die Küste heran. Irgendwann teilt sich der Wald, und vor dir liegt eine kleine
                Bucht, an deren Strand - du traust deinen Augen kaum! - eine junge Meerjungfrau halb aus dem Wasser ragt. Oberkörper und Arme sind umschlungen von den
                dicken Seilen eines Fischernetzes. Als sie dich bemerkt, reißt sie die bunt schillernden Augen auf und rüttelt an ihren Fesseln, kann sich aber nicht
                befreien.`n
                Willst du dem armen Ding nicht helfen?");
        addnav('Bucht');
        addnav('Helfen','forest.php?op=helfen');
        addnav('Zurück');
        addnav('Lieber nicht','forest.php?op=gehen');
    break;
    case 'helfen':
        output("`TDu überlegst nicht lang, zückst deine Waffe und bewegst dich langsam auf die Meerjungfrau zu. Diese scheint deine Beweggründe falsch zu
                verstehen, denn sie beginnt heftiger an ihren Fesseln zu rütteln. Du versuchst sie, mit Worten zu beruhigen...`n`n");
        $int_rand = e_rand(1,3);
        switch($int_rand) {
            // Meerjungfrau bedankt sich
            case 1:
            case 2:
                output("`T... Und tatsächlich, es funktioniert! Das Wesen lässt ab von ihren Fesseln und starrt dir stattdessen abwartend entgegen. Langsam
                        gehst du vor ihr in die Knie und schneidest nach und nach die Fesseln durch. Kaum ist das letzte Seil durchtrennt, reißt die
                        Meerjungfrau die Arme auseinander, befreit sich so von den Seilfetzen und wirft sich ins Wasser. Einmal noch siehst du sie kurz
                        auftauchen, dann ist sie verschwunden.`n
                        Als du dich abwenden willst, siehst du es zwischen den zerschnittenen Seilstücken verdächtig glänzen. Ein juwelenbesetzter Ohrring!
                        Den muss das Wesen wohl verloren haben. Du löst die beiden Edelsteine heraus und steckst sie ein, ehe du von dannen ziehst.");
                        $session['user']['gems'] += 2;
                        $session['user']['specialinc'] = '';
            break;
            // Meerjungfrau tötet Char
            case 3:
                output("`T... Doch stattdessen ruckt das Wesen nur noch heftiger an dem Netz. Und gerade, als du nur noch einen Schritt vom Wasser entfernt bist,
                        sprengt die Meerjungfrau die Fesseln und stürzt sich auf dich! Ihre eben noch unschuldigen Züge sind verzerrt zu einer schrecklichen
                        Fratze, und mit zu Klauen gekrümmten Händen packt sie dich an beiden Armen und zieht dich mit einem kräftigen Ruck ins Wasser!");
                        addnav('Bucht');
                        addnav('Kämpfe um dein Leben!','forest.php?op=kaempfen&enemy=mermaid');
            break;
        }
    break;
    case 'gehen':
        output("`TDu lässt die Meerjungfrau links liegen und gehst weiter.");
        $session['user']['specialinc'] = '';
    break;
    case 'kaempfen':
        $badguy = array(
        "creaturename" => 'Meerjungfrau',
        "creatureweapon" => 'scharfe Klauen',
        "creaturelevel" => $session['user']['level'],
        "creatureattack" => $session['user']['attack']+1,
        "creaturedefense" => $session['user']['defence'],
        "creaturehealth" => $session['user']['maxhitpoints']
        );
        $gegner['enemy_mermaid'] = createstring($badguy);

        $session['user']['badguy'] = $gegner['enemy_mermaid'];
        $_GET['op']="fight";
        $battle = true;
    break;
    case 'fight':
        $battle = true;
    break;
    case 'run':    // Fliehen
        if(e_rand(1,5) == 1) {
            output('`@Endlich schaffst du es, dich aus dem Griff des Wesens zu befreien und zurück an Land zu gelangen. Du nimmst die Beine in die Hand und fliehst
                    von diesem Ort.`0');
            $session['user']['specialinc'] = '';
            addnav('Flucht in die Stadt!','village.php');
        }
        else {
            output('Du kannst dich nicht aus dem Griff der Meerjungfrau befreien!`n`n');
            $battle=true;
        }
    break;
    default:
        $int_rand = e_rand(1,2);
        if($int_rand == 2) {
            redirect('forest.php?op=bucht');
        } else {
            output("`TWährend deiner Reise durch den Wald kommst du an den Klippen vorbei und genießt, wo du schon einmal hier bist, ein wenig die Aussicht...`n
                    Moment! War das gerade ein Segelschiff dort hinten, in der unbewohnten Bucht? Nein, bestimmt hast du dich getäuscht... Und das war bestimmt auch
                    keine Piratenflagge!");
            $session['user']['specialinc'] = '';
        }
    break;
}

if($battle == true)
{
    include_once('battle.php');
    if ($victory)
    {
        //addnav('Zurück in den Wald','forest.php');
        $session['user']['specialinc'] = '';
        $exp_plus = round($session['user']['experience'] * 0.05);
        $session['user']['experience'] += $exp_plus;
        output('`n`GDu bekommst ' . $exp_plus . ' Erfahrungspunkte dafür, dass du die Meerjungfrau in die Flucht geschlagen hast!');
    }
    else if ($defeat)
    {
        addnews('' . $session['user']['name'] . '`T wurde von einer Meerjungfrau ertränkt!');
        killplayer(100,5,0,'',0);
        output('`n`4Du hast keine Chance gegen die Meerjungfrau und wirst von ihr hinab in die Tiefen des Meeres gezogen.`n
                Du verlierst 5% deiner Erfahrung und all dein Gold!');
        addnav('Verdammt...','news.php');
        $session['user']['specialinc'] = '';
    }
    else
    {
        fightnav(true,true);
    }
}
?>

