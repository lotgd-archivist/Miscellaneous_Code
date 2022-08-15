
<?php
/**
Waldspecial erstellt von und für den Gewinner des Halloween-Gewinnspiels 2015: Reven
**/

// array( Monat => array( Munitionsname , Geschlecht , Mehrzahl , Hauptgewinn-Nachricht [Du hast die Zielscheibe getroffen,...] ) )
$arr_munition = array(1=>array('Schneeball',0,'Schneebälle','die nun unter einer dicken Schneeschicht versteckt ist')
                     ,2=>array('Schneeball',0,'Schneebälle','die nun unter einer dicken Schneeschicht versteckt ist')
                     ,3=>array('Osterei',2,'Ostereier','von der nun eine weißgelbe Masse herabtropft')
                     ,4=>array('Osterei',2,'Ostereier','von der nun eine weißgelbe Masse herabtropft')
                     ,5=>array('Blumentopf',0,'Blumentöpfe','an der nun ein dicker Brocken Erde klebt')
                     ,6=>array('Blumentopf',0,'Blumentöpfe','an der nun ein dicker Brocken Erde klebt')
                     ,7=>array('Wasserbombe',1,'Wasserbomben','die nun klitschnass ist')
                     ,8=>array('Bienennest',2,'Bienennester','die nun vor Honig trieft')
                     ,9=>array('Maiskolben',0,'Maiskolben','die nun gelb gesprenkelt ist')
                     ,10=>array('Kürbis',0,'Kürbisse','die nun in einem matschigen Orange erstrahlt')
                     ,11=>array('Kürbis',0,'Kürbisse','die nun in einem matschigen Orange erstrahlt')
                     ,12=>array('Schneeball',0,'Schneebälle','die nun unter einer dicken Schneeschicht versteckt ist')
                     );
$int_month = date('n');

function tryit() {

    global $session,$arr_munition,$int_month;

    output('`tDu greifst zu eine'.($arr_munition[$int_month][1] == 1 ? 'r' : 'm').' großen '.$arr_munition[$int_month][0].', packst
           '.($arr_munition[$int_month][1] == 1 ? 'sie' : ($arr_munition[$int_month][1] == 1 ? 'es' : 'ihn')).' sorgfältig in die Schale des Katapults, feuchtest deinen Finger an, streckst
            ihn in die Luft, betätigst nach deinen peniblen Berechnungen die Drehkurbel des Gerätes, lässt los und...`n`n');
    $int_rand = e_rand(1,10);
    switch($int_rand) {
        // Niete, weil Ziel verfehlt
        case 1:
        case 2:
        case 3:
        case 4:
            output('`t..bist voller Erwartung, triffst aber nur den Baumstamm neben der Zielscheibe. Das war leider nichts.');
        break;
        // Trostpreis, weil knapp daneben -> 500 Gold
        case 5:
        case 6:
        case 7:
            output('`t..fluchst laut! Fast hättest du die Zielscheibe getroffen, dein'.($arr_munition[$int_month][1] == 1 ? 'e' : '').' '.$arr_munition[$int_month][0].' hat sogar die Borke
                    des Baums gestriffen. Doch jetzt liegt '.($arr_munition[$int_month][1] == 1 ? 'sie' : ($arr_munition[$int_month][1] == 2 ? 'es' : 'er')).' irgendwo dahinter im Gestrüpp.
                    Immerhin, der Goblin ist beeindruckt von deinem Schuss und gibt dir einen Trostpreis in Form von 500 Gold.');
            $session['user']['gold'] += 500;
        break;
        // einen Char, der online ist, mit der Munition abwerfen
        case 8:
            output('`t..'.($arr_munition[$int_month][1] == 1 ? 'die' : ($arr_munition[$int_month][1] == 2 ? 'das' : 'der')).' '.$arr_munition[$int_month][0].' fliegt und fliegt und fliegt mit
                    einem sausenden Geräusch volle Kanne in Richtung Stadt. Oh-Oh! Du hörst lautstark jemanden fluchen und meinst dabei das Wort "'.$arr_munition[$int_month][0].'"
                    herausgehört zu haben. Die Stimme klingt verdächtig nach ');
            // Ist ein Char gerade online?
            $sql = 'SELECT acctid,name FROM accounts WHERE '.user_get_online().' AND acctid != '.$session['user']['acctid'].' ORDER BY RAND() LIMIT 1';
            $result = db_query($sql) or die(db_error(LINK));
            $int_amount = db_num_rows($result);
            // Falls ja: abwerfen!
            if($int_amount > 0) {
                $row = db_fetch_assoc($result);
                output($row['name'].'`t. Ups.');
                systemmail($row['acctid'],'`^Fliegende '.$arr_munition[$int_month][2],$session['user']['name'].' `Fhat dich mit eine'.($arr_munition[$int_month][1] == 1 ? 'r' : 'm').' '.$arr_munition[$int_month][0].' abgeworfen! Frechheit!`nAls du dir murrend den Kopf reibst, fällt dein Blick auf einen Edelstein, der in den Resten zu deinen Füßen steckt. Also ist wohl weniger das Wurfgeschoss daran Schuld, wenn du fortan mit einer Beule herumläufst.');
                db_query("UPDATE accounts SET gems = gems+1 WHERE acctid = ".$row['acctid']);
            // Falls nein: Riyad abwerfen
            } else {
                output('`tRiyad. Ups. Vielleicht sollest du den nächsten Besuch bei ihm auf morgen verschieben. Oder übermorgen.');
            }
        break;
        // Hauptgewinn -> 3 Edelsteine    !! Ausnahme Dezember:
        case 9:
        case 10:
            output('`t..kannst es nicht fassen! Du hast die Zielscheibe getroffen, '.$arr_munition[$int_month][3].', und erhältst vom Goblin drei Edelsteine als Preis.
                    Glückwunsch!');
            $session['user']['gems'] += 3;
        break;
    }
}

$session['user']['specialinc'] = 'reven.php';
if($_GET['op'] == '') {
    output('`n`tGut gelaunt und voller Tatendrang durchstreifst du die Wälder, ehe ein Flüstern an dein Ohr dringt. `2"Pscht! Hee, du!"`t Du hältst in deinem Schritt inne, guckst dich um und
            entdeckst einen kleinen, grünen Goblin, an einen Baumstamm gelehnt. Mit dem nackten Zeigefinger deutest du auf dich selbst und fragst dich: Meint der etwa mich? `2"Ja, du!"`t,
            spricht der Goblin in einem rauen Flüsterton weiter. `2"Ich komm leider viel zu spät zum Fescht im Städtle, hätt längscht dort sein sollen, aber mir isch was dazwischen
            gekommen."`t Der Goblin erklärt dir in einem merkwürdigen Dialekt, dass er seine Erfindung dort vorstellen wollte. `^KAWUMM-KATAPULT 3000!`t Ein mechanischer Mini-Katapult, der
            mit kleinen '.$arr_munition[$int_month][2].(substr($arr_munition[$int_month][2],-1) == 'n' ? '' : 'n').' geladen wird. Damit gilt es dann eine Zielscheibe in ein paar Metern
            Entfernung zu treffen. Nimmst du die Einladung des Goblins an, hast du drei Versuche und erhältst einen Preis, wenn du die Zielscheibe triffst!`n');
    addnav('Katapult');
    addnav('Starte den ersten Versuch','forest.php?op=try1');
    addnav('Pff!');
    addnav('Das ist mir zu blöd, ich gehe!','forest.php?op=leave');
} else {
    if($_GET['op'] == 'try1') {
        tryit();
        addnav('Katapult');
        addnav('Starte den zweiten Versuch','forest.php?op=try2');
        addnav('Reicht!');
        addnav('Lieber aufhören','forest.php?op=stop');
    } elseif($_GET['op'] == 'try2') {
        tryit();
        addnav('Katapult');
        addnav('Starte den dritten Versuch','forest.php?op=try3');
        addnav('Reicht!');
        addnav('Lieber aufhören','forest.php?op=stop');
    } elseif($_GET['op'] == 'try3') {
        tryit();
        output('`n`n`tDas war dein letzter Versuch. Der Goblin wünscht dir grinsend noch einen schönen Tag und hält dann nach weiteren Passanten Ausschau.');
        $session['user']['specialinc'] = '';
    } elseif($_GET['op'] == 'stop') {
        output('`tDu dankst dem Goblin, doch nun willst du weiter.');
        $session['user']['specialinc'] = '';
    } elseif($_GET['op'] == 'leave') {
        output('`tDer Goblin guckt dir verdutzt nach und zuckt mit den Schultern. `2"Heiligsblechle.."');
        addnews($session['user']['name'].'`t hat einen Goblin stehen lassen, der '.($session['user']['sex']?'sie':'ihn').' mit einem Katapult schießen lassen wollte. Mit einem Katapult!');
        $session['user']['specialinc'] = '';
    }
}
?>

