
<?php
/* * * *
 * Caligos Halloween-Wunsch: Caligo's Castle
 * Texte (c) Zephyr
 * programmiert für eranya.de                                          kleiner Mann fertig --- Schrein begonnen (Spendenlimit fehlt) --- Warteschlange
 * * * */
require_once('common.php');
page_header();
// Konstanten etc.
$str_filename = basename(__FILE__);
$str_tout = '`c`bCaligos Castle`b`c`n';
// Los geht's
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
switch($str_op) {
    // Intro
    case '':
        $str_tout .= 'Das wuchtige Schloss, das in kürzester Zeit nahe des Stadtamtes gebaut wurde, überragt alle übrigen Gebäude der Stadt. Ohne großen Zierrat an den Fassaden wirkt das
                      dunkle Gebäude sehr kalt und unpersönlich. Obgleich du es nicht lassen kannst und die breite Eingangstüre durchschreitest, um dir das Innere anzusehen.`n
                      `n
                      Du betrittst das Schloss und findest dich in einer großen Eingangshalle wieder. Zu deiner Linken zweigt ein endlos scheinender Flur ab, der mit einer langen Reihe
                      Wartender gefüllt ist. Die Menschenschlange ragt bereits in die Eingangshalle hinein und scheint nur unmerklich kürzer zu werden.`n
                      In der Mitte des Raumes an der hinteren Wand der Halle ist ein kleiner Schreibtisch aufgebaut, hinter dem ein alter, in sich zusammen gesunkener Mann sitzt und
                      Pergamente zu bearbeiten scheint. Er scheint zum Personal zu gehören.`n
                      Zu deiner Rechten ist ein protziger Schrein aus purem Gold aufgebaut, der von zwei breitschultrigen, großen Männern bewacht wird.`n`n';
        // Nav
        addnav('Das Schloss');
        addnav('W?Stelle dich in die Warteschlange',$str_filename.'?op=line');
        addnav('M?Gehe zu dem kleinen Mann',$str_filename.'?op=littleman');
        addnav('c?Gehe zu dem Schrein',$str_filename.'?op=shrine');
        addnav('Weg hier');
        addnav('S?Zum Stadtplatz','village.php');
    break;
    // Warteschlange  clyrax
    case 'line':
        $str_tout .= '';
        // Nav
        addnav('Warteschlange');
        addnav('Lieber wieder gehen',$str_filename);
    break;
    // Kleiner Mann
    case 'littleman':
        $str_tout .= 'Du trittst an den mickrigen Schreibtisch heran, der von Pergamenten beinahe überquillt. Als der Mann seinen kahlen, schrundigen Schädel hebt, rückt er sein
                      Spekuliereisen zurecht und sieht dich mit strengem, abschätzigen Blick an.';
        // Nav
        addnav('Frage nach der Warteschlange',$str_filename.'?op=ask&what=line');
        addnav('Frage nach dem Schrein',$str_filename.'?op=ask&what=shrine');
    break;
    // Kleiner Mann - Erklärungen
    case 'ask':
        if($_GET['what'] == 'line') {
            $str_tout .= 'Der kleine Mann seufzt schwerfällig und deutet auf die lange Menschenschlange, an die sich immer neue Bittsteller einreihen. "Dieser Gang führt zum Bittschalter.
                          Dort wirst du für deine Taten belohnt, oder für deine Missetaten bestraft werden. Aber das Warten wird dich einiges an Zeit kosten, die du auch mit Waldkämpfen
                          verbringen könntest."';
        } else {
            $str_tout .= 'Der kleine Mann wirft einen kurzen Blick zum Schrein und zuckt dann mit den Schultern. "Der Schrein steht dort, um den großen Leviathan zu ehren. An seinem Deckel
                          befindet sich eine Öffnung, dort kannst du Gold spenden. So kannst du dir das Wohlwollen des großen Herren erkaufen. Zumindest kannst du es versuchen. Das Gold
                          geht in die Stadtkasse über. Davon werden zum Beispiel Feste bezahlt und ausgerichtet. Das Gold ist also nicht verloren."';
        }
        // Nav
        addnav('Zurück');
        addnav('Danke für die Info!',$str_filename.'?op=littleman');
    break;
    // Schrein          clyrax: Spendenlimit muss noch eingebaut werden
    case 'shrine':
        $int_amtskasse = (int)getsetting("amtskasse",0);
        if($_GET['act'] == 'donateconfirm') {
            $int_donategold = (int)$_POST['donategold'];
            // Spenden sind erst ab 1.000 Gold akzeptabel
            if($int_donategold <= 1000) {
                $str_tout .= 'Du wirfst deine Goldmünzen in den Schrein und fühlst dich ob deiner großzügigen Spende gleich viel besser. Allerdings scheint der große Leviathan das etwas
                              anders zu sehen. Das realisierst du, als der Schrein plötzlich alle Münzen wieder ausspuckt, die du gerade gespendet hast. Ein paar Münzen treffen dich am
                              Kopf - autsch! Du sammelst dein Gold wieder ein und entfernst dich erst einmal wieder.';
                $session['user']['hitpoints'] *= 0.8;
            // Hat Char überhaupt genug Gold dabei?
            } elseif($session['user']['gold'] <= $int_donategold) {
                $str_tout .= '`^So viel Gold hast du nicht dabei!';
                // Nav
                addnav('Schrein');
                addnav('Nochmal versuchen',$str_filename.'?op=shrine&act=donate');
            } else {
                // Amtskasse füllen - aber nur bis Max.
                $int_amtskasse_max = (int)getsetting("maxbudget","2000000");
                if($int_amtskasse + $int_donategold > $int_amtskasse_max) {
                    $int_donategold = $int_amtskasse_max - $int_amtskasse;
                }
                savesetting("amtskasse",($int_amtskasse+$int_donategold));
                $session['user']['gold'] -= $int_donategold;
                // User informieren:
                $str_tout .= 'Du spendest '.$int_donategold.' Goldstücke. Nun bleibt nur noch zu hoffen, dass der große Leviathan von deiner großzügigen Spende erfährt und sich wohlwollend
                              zeigt. Irgendwann.';
                if(e_rand(1,8) == 2) {
                    $str_tout .= '`n`n..und tatsächlich! Auf einmal leuchtet der Schrein auf und überträgt seinen goldenen Glanz auf dich. Die Kraft des Leviathan durchflutet dich.`n
                                  `@Du erhältst drei Waldkämpfe!';
                    $session['user']['turns'] += 3;
                }
            }
        } elseif($_GET['act'] == 'donate') {
            $str_tout .= 'Du entscheidest dich dafür, mit einer großzügigen Spende den großen Leviathan milde zu stimmen. Du nimmst deinen Goldbeutel heraus und überlegst, wie viel Gold
                          du am besten spenden solltest.`n
                          Ein kleines Schild, das du jetzt erst siehst, weist dich darauf hin, dass du '.(
                          $session['user']['marks'] > 31 ?
                            'maximal den doppelten Steuersatz, also '.(getsetting("taxrate",750)*2).' Goldstücke, pro Tagesabschnitt spenden kannst.'
                            : 'maximal den einfachen Steuersatz, also '.getsetting("taxrate",750).' Goldstücke, pro Tagesabschnitt spenden kannst.'
                          ).'`n
                          `n
                          <form action="'.$str_filename.'?op=shrine&act=donateconfirm" method="post">
                          <input name="donategold" value="0" size="5"> <input type="submit" class="button" value="Spenden" onClick="return confirm(\'Möchtest du dieses Gold wirklich spenden?\');">
                          </form>';
        } else {
            $str_tout .= 'Du näherst dich dem glänzenden Schrein und begutachtest ihn. Dir fällt sofort die Öffnung ins Auge, die gerade groß genug ist, um Goldstücke nacheinander einzuwerfen.
                          Die beiden Wachen stehen derweil scheinbar regungslos neben dem Schrein, doch ein Blick in ihre Gesichter genügt dir, um zu wissen: Solltest du versuchen, das goldene
                          Artefakt zu stehen, wirst du hier nicht lebend heraus kommen.`n
                          `n
                          Derzeit ist die Stadtkasse mit `^'.$int_amtskasse.' Gold gefüllt.';
            // Nav
            addnav('Schrein');
            addnav('Gold spenden',$str_filename.'?op=shrine&act=donate');
        }
        addnav('Zurück');
        addnav('Wieder gehen',$str_filename);
    break;
    // Debug
    default:
        $str_tout .= '`&Huch, wie hast du dich denn hierher verirrt? Sende bitte folgende Meldung via Anfrage an das Adminteam:`n
                      `n
                      `^fehlende op: '.$str_op.' in '.$str_filename;
        addnav('Zurück',$str_filename);
    break;
}
output($str_tout);
page_footer();
?>

