<?php
// Ursprung des Specials ist "golden egg"
// Ich machte mir daraus etwas ähnliches und schrieb es mir um. Bei Problemen bei
// http://www.anpera.net/forum/index.php?c=12 oder Garlant-T@web.de melden.
// überarbeitet von Tidus www.kokoto.de

$session['user']['specialinc'] = 'amulett.php';
if ($_GET['op']=='takeit') {
    output('`3Du bemerkst vor dir etwas pulsieren. Vorsichtig schleichst du dich heran um heraus zu finden was es ist. Als du nahe genug dran bist, erkennt du das es ein Amulett mit göttlicher Macht sein.`n`n');
    if (mt_rand(1,5)==4){
        output('Die Götter meinen es nicht gut mit dir. Sie verwehren dir dieses Amulett auf zu heben und schicken einen Blitz auf dich hinab...');
        $lvl = $session['user']['level'];
        $hurt = mt_rand(5$lvl,10$lvl);
        $session['user']['hitpoints']-=$hurt;
        output("`n`n`^Du verlierst $hurt Lebenspunkte!`n");
        if ($session['user']['hitpoints']<=0) {
                output('`4Du bist `bTOT`b!!!`nDu verlierst glücklicherweise weder Gold noch Erfahrungspunkte.`nDu kannst morgen wieder kämpfen.');
                addnav('Zu den Schatten','shades.php');
                addnav('Zu den News','news.php');
            addnews($session['user']['name']." `0starb mit dem Amulett der Macht in der Hand.");
            }
    } else if (getsetting("hasamulett",0)!=0){
        output('Gerade als du das Amulett aufheben möchtest, kommt ein Fremder angerannt und schnappt sich vor deinen Augen `bdein`b Amulett...');
    } else {
        output('`3Nach kurzem Zögern, läufst du vorsichtig auf das Amulett zu und hebst es geschwind auf Du spürst ein pulsieren, das nun deinen Körper erfasst. Dir ist nun bewusst, weche macht das Amulett besitzt. Als du dir das Amulett genauer anschaust, bemerkst du einige Einkerbungen, die du für Zeichen halst. Als du dir das Amulett umgelegt hast, hört das pulsieren ganz plötzlich auf und wird golden.');
        addnews("`V".$session['user']['name']."`V hat das Amulett der Macht im Wald gefunden!`0");
        $session['user']['reputation']++;
        savesetting("hasamulett",$session['user']['acctid']);
    }
    $session['user']['specialinc']='';
} elseif ($_GET['op']=='abhaun') {
    output('`3Du hast zu viel Ehrfurcht vor dem Amulett. Du glaubst, das es sicherer ist zu warten, bis eine andere Person das Amulett hat und es ihr zu stehlen.');
    $session['user']['specialinc']='';
} else {
if (getsetting("hasamulett",0)==0){
          output('`3Mitten im dichten Wald bemerkst du eine Ruine eines längst vergessenen Altar. Auf dem etwas pulsiert! In deiner Neugier kommst du näher.  Du siehst auf einer großen lederten steinernen Platte ein Amulett, welches pulsiert!`n Da du nicht an Götter glaubst, aber das Amulett pulsiert und der Altar sicherlich einem Gott geweiht war bist du unschlüssig, ob du den Altar betreten kannst und das Amulett nehmen solltest,  oder es doch lieber auf der Altarruine lassen solltest. Einerseits könnte das Amulett einen hohen preis für dich haben, oder sogar ein Geheimnis Preis geben, welches die Götter hier vor langer zeit versteckt haben - andererseits aber könntest du für die Entweihung und Entdeckung des Geheimnisses gestraft werden...');
        addnav('Nimm das Amulett mit','forest.php?op=takeit');
        addnav('Lieber nicht','forest.php?op=abhaun');
    } else {
        $sql = "SELECT acctid,name,sex FROM accounts WHERE acctid = '".getsetting("hasamulett",0)."'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $owner = $session['user']['acctid'];
        if ($owner == $row['acctid']) {
            output('`3Du kommst an eine Stelle im Wald, die dir mehr als bekannt vorkommt. Als du dich genauer umsiehst, siehst du die Alte Ruine, aus der das Amulett stammt.');
            output(' Noch bevor du einen Gedanken hegen kannst, wirst du von hinten Niedergeschlagen.');
            $lvl = $session['user']['level'];
            $hurt = mt_rand(4$lvl,9$lvl);
            $session['user']['hitpoints']-=$hurt;
            output("`n`n`^Du verlierst $hurt Lebenspunkte!`n");
            $text ="`V".$session['user']['name']."`V hat das Amulett der Macht im Wald verloren`0";
            if ($session['user']['hitpoints']<=0) {
                    output('`4Du bist `bTOT`b!!!`nDu verlierst glücklicherweise weder Gold noch Erfahrungspunkte.`nDu kannst morgen wieder kämpfen.');
                    addnav('Zu den Schatten','shades.php');
                    addnav('Zu den News','news.php');
                $text = $text." und starb dabei";
                }
            addnews($text.".");
           savesetting("hasamulett","0");
        } else {
            output("`3Mitten im dichten Wald entdeckst du eine Ruine eines längst vergessenen Altars.`n Du siehst schon vom weiten, das der Altar leer ist und wendest dich wieder ab.`n`n`VDas Amulett der Macht befindet sich zur Zeit im Besitz von {$row['name']}`^!`n`3Willst du es ".($row['sex']?"ihr":"ihm")." nicht mal abnehmen?`n");
        }
        $session['user']['specialinc']='';
    }
}
?>

Datei auswählen
4,98 KiB, Zuletzt geändert am 29.07.2013 11:20
