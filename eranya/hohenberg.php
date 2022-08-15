
<?php
/* * * * * * * * * *
 * RP-Ort für Halloween-Special - geeignet für 'Schillerstraße'-RP
 * Autor: Silva
 * erstellt für http://eranya.de
 * * * * * * * * * */
require_once('common.php');
page_header('Bei den Zu Hohenbergs');
// URL auslesen
$str_filename = basename(__FILE__);
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
// end
// Variablen festlegen
$str_output = '';
$str_comsection = '';
define('HOHENBERGCOLORTEXT','`d');
define('HOHENBERGCOLORHEAD','`F');
// Aufgaben für Schillerstraße-RP
$arr_aufgaben = array('Plötzliche Müdigkeit überkommt Dich.'
                     ,'Versuche mit jemandem die Maske zu tauschen.'
                     ,'Verkünde die Botschaft, dass sie kommen.'
                     ,'Plötzliche Müdigkeit überkommt Dich.'
                     ,'Versuche mit jemandem die Maske zu tauschen.'
                     ,'Verkünde die Botschaft, dass sie kommen.'
                     );
// end
// Los geht's!
switch($str_op) {
    // Eingang -> RP-Ort
    case '':
        $str_output .= HOHENBERGCOLORHEAD.'`c`bDas Anwesen der Zu Hohenbergs`b`c`n'.HOHENBERGCOLORTEXT.'
                        Eine dünne Mondsichel steht am sternenklaren Himmel und beleuchtet den schmalen Kiesweg, der hinauf zu dem Anwesen führt, in welchem in
                        der heutigen Nacht der große Maskenball von Eranya stattfindet. Immanuel Theodor zu Hohenberg hat geladen und sich nicht lumpen lassen:
                        Zahlreiche Feuerschalen und Laternen säumen den Pfad und Dutzende von Kerzen und Kürbissen, deren ausgeschnitzte Grimassen leuchten und
                        flackern, erhellen das Innere des Ballsaals. Sie sorgen für ein auffälliges Schattenspiel, das es schwer macht zu erkennen, was echt und
                        was nur Einbildung ist. Viele Bürger Eranyas sind dem Aufruf des Edelmannes gefolgt und wer kann, hat sich fein herausgeputzt. Was aber
                        alle  jung und alt, arm und reich  gemeinsam haben, sind die Tiermasken, die Augen und Nasen, teils auch Münder verdecken und dem Ball
                        eine Anonymität verleihen, die der Stimmung jedoch keinen Abbruch tut. Ausgelassen wird getanzt und geschunkelt, getratscht und gelacht
                        und zum Wohl der Gäste wuseln unzählige Bedienstete mit silbernen Tabletts umher, um Häppchen und alkoholische Getränke zu reichen. Eine
                        Spielmannsgruppe hat sich auf einer kleinen Holzbühne eingefunden und präsentiert eine muntere Weise nach der anderen.`n
                        Wer sich von all dem bunten Trubel nicht zu sehr ablenken lässt, der gewahrt vielleicht in einem halb versteckten Winkel des Saals
                        schwere Vorhänge aus rotem Samt. Dahinter, vor neugierigen Blicken gut verborgen, befindet sich eine Flügeltür. Wohin sie führt weiß
                        wohl nur der Hausherr selbst und er scheint gewisse Vorkehrungen getroffen zu haben, um Unbefugten den Zutritt zu verwehren. Zwei
                        vermummte Wächter tummeln sich nicht fern des verbotenen Zugangs und sie haben ein wachsames Auge auf die Gäste.`n
                        `n
                        `i`7(Info zum Ablauf: Die maximale Länge pro Post beträgt 2000 Zeichen. Charaktere, die im eigenen Post angeschrieben werden, sollten
                        `bfett`b markiert werden, das sorgt für eine bessere Übersicht. Wer den Rabengeist bei sich hat, muss ihn mind. 2 Posts lang
                        behalten, selbst wenn die Aufgabe schon nach einem Post erfüllt sein sollte. Erst dann wird der Vogel ingame durch Berührung bzw. über
                        den Link \'Rabengeist weitergeben\' an den nächsten Char weitergegeben.)`i`n`n';
        $sql = db_query('SELECT name FROM accounts WHERE schillerstr = 1 LIMIT 1');
        $row = db_fetch_assoc($sql);
        $str_aufgabe = getsetting('schillerstr_aktuelle_aufgabe','keine');
        if($session['user']['acctid'] == 985 || $session['user']['acctid'] == 979 || su_check(SU_RIGHT_GROTTO)) {
            $str_output .= '`7Derzeit hat '.(db_num_rows($sql) == 0 ? 'niemand' : $row['name']).' `7den Raben mit folgender Aufgabe:`n
                            "'.$str_aufgabe.'"`n`n';
        } elseif($row['name'] == $session['user']['name']) {
            $str_output .= '`&`bDerzeit sitzt der Rabe auf deiner Schulter! Deine Aufgabe lautet:`n
                            "'.$str_aufgabe.'"`b`n`n';
        }
        $str_comsection = 'halloween_hohenberg';
        addnav('Rabengeist');
        addnav('Rabengeist weitergeben',$str_filename.'?op=sendtoken');
        addnav('Anwesen verlassen');
        addnav('S?Zum Stadtplatz','village.php');
    break;
    // token weiterreichen
    case 'sendtoken':
        $str_act = (isset($_GET['act']) ? $_GET['act'] : '');
        if($session['user']['schillerstr'] != 1 && $session['user']['acctid'] != 985 && $session['user']['acctid'] != 979 && !su_check(SU_RIGHT_GROTTO)) {
            output('`qDu hast den Rabengeist doch gerade gar nicht, wie willst du ihn dann weiterschicken? Schnell zurück zum Spiel!');
            addnav('Zurück');
            addnav('Zurück zum Anwesen',$str_filename);
            page_footer();
            break;
        }
        switch($str_act) {
            // Start -> Username eingeben
            case '':
                $str_output .= '`^An wen möchtest du den Rabengeist weitergeben?`n
                                Bedenke, dass zwischen deinem Charakter und dem von dir festgelegten Charakter ingame eine Berührung stattgefunden haben muss.
                                Diese solltest du erst ausspielen, ehe du den Raben weiterschickst.`n
                                `n
                                <form action="'.$str_filename.'?op=sendtoken&act=search" method="post">
                                An: <input name="to" type="text" class="input"> <input type="submit" class="button" value="Suchen">
                                </form>';
                addnav('',$str_filename.'?op=sendtoken&act=search');
            break;
            // Suche eingrenzen / Fund bestätigen
            case 'search':
                $str_to = $_POST['to'];
                $sql = db_query('SELECT login,name FROM accounts WHERE name LIKE "'.str_create_search_string($str_to).'"');
                $int_rowcount = db_num_rows($sql);
                if($int_rowcount == 0) {
                    $str_output .= '`qEs wurde leider kein Charakter mit diesem Namen gefunden. Bitte versuche es noch einmal:`n
                                    `n
                                    <form action="'.$str_filename.'?op=sendtoken&act=search" method="post">
                                    An: <input name="to" value="'.$str_to.'" type="text" class="input"> <input type="submit" class="button" value="Suchen">
                                    </form>';
                    addnav('',$str_filename.'?op=sendtoken&act=search');
                } elseif($int_rowcount > 50) {
                    $str_output .= '`qÜber dieses Suchwort lassen sich zu viele Charaktere finden. Sei bei deiner Eingabe bitte etwas präziser:`n
                                    `n
                                    <form action="'.$str_filename.'?op=sendtoken&act=search" method="post">
                                    An: <input name="to" value="'.$str_to.'" type="text" class="input"> <input type="submit" class="button" value="Suchen">
                                    </form>';
                    addnav('',$str_filename.'?op=sendtoken&act=search');
                } else {
                    $str_output .= '`^Zu wem soll der Rabe geschickt werden?`n
                                    `n
                                    <form action="'.$str_filename.'?op=sendtoken&act=send" method="post">
                                    <select name="sendto">';
                    while($row = db_fetch_assoc($sql)) {
                        $str_output .= '<option value="'.$row['login'].'">'.$row['name'].'</option>';
                    }
                    $str_output .= '</select>
                                    <input type="submit" class="button" value="Senden"></form>';
                    addnav('',$str_filename.'?op=sendtoken&act=send');
                }
            break;
            // Systemmail senden
            case 'send':
                $str_sendto = $_POST['sendto'];
                // erstmal schauen, ob dies ein Eingriff der RP-Leitung ist -> wenn ja, muss Feld 'schillerstr' eines anderen Users von 1 auf 0 gesetzt werden
                if($session['user']['schillerstr'] == 1) {
                    db_query('UPDATE accounts SET schillerstr = 0 WHERE schillerstr = 1');
                }
                // end
                // Aufg zufällig auswählen
                $int_max = (count($arr_aufgaben)-1);
                $str_aufgabe = $arr_aufgaben[e_rand(0,$int_max)];
                $sql = db_query('SELECT acctid,name FROM accounts WHERE login = "'.db_real_escape_string($str_sendto).'"');
                $row = db_fetch_assoc($sql);
                // Mail senden
                systemmail($row['acctid'],'`qDer Geist des Raben ist auf dich übergegangen!`0',$session['user']['name'].'`Q hat dich berührt; dadurch wurde der Geist des Raben auf dich aufmerksam und hat sich kurzerhand auf deine Schulter niedergelassen. Ein leises Krächzen dringt in dein Ohr, und ganz plötzlich spürst du, wie etwas deinen Geist umhüllt und dir die folgende Aufgabe auferlegt:`n`n`F'.$str_aufgabe.'`n`n`QErfülle diese Aufgabe, dann kannst du den Geist durch eine Berührung an einen anderen Gast weiterschicken.');
                $str_output .= '`^Du vernimmst ein leises Krächzen ganz nah an deinem Ohr - und schon im nächsten Moment spürst du, wie sich etwas von dir löst
                                und du dich mit einem Mal wieder gänzlich frei und Herr deiner Sinne fühlst.`n
                                Der Rabengeist hat dich verlassen und sitzt nun auf '.$row['name'].'`^s Schulter.';
                db_query('UPDATE accounts SET schillerstr = 1 WHERE acctid = '.$row['acctid']);
                savesetting('schillerstr_aktuelle_aufgabe',$str_aufgabe);
                $session['user']['schillerstr'] = 0;
            break;
            // Debug
            default:
                $str_output = '`&Nanu, wie kommst du denn hierher? Schicke bitte folgende Meldung via Anfrage ans E-Team, zusammen mit einer kurzen Beschreibung der
                               Aktionen, die du unmittelbar vorher durchgeführt hast:`n
                               `n
                               `^fehlende act: '.$str_act.' in op: '.$str_op.' in '.$str_filename.'`n
                               `n
                               `&Und nun schnell zurück zum Spiel!';
            break;
        }
        addnav('Zurück zum Anwesen',$str_filename);
    break;
    // Debug
    default:
        $str_output = '`&Nanu, wie kommst du denn hierher? Schicke bitte folgende Meldung via Anfrage ans E-Team, zusammen mit einer kurzen Beschreibung der
                       Aktionen, die du unmittelbar vorher durchgeführt hast:`n
                       `n
                       `^fehlende op: '.$str_op.' in '.$str_filename.'`n
                       `n
                       `&Und nun schnell zurück zum Spiel!';
        addnav('Zurück zum Anwesen',$str_filename);
    break;
}
// Ausgabe
output($str_output);
if(strlen($str_comsection) > 2) {
    addcommentary();
    viewcommentary($str_comsection,'Sagen',15,'sagt');
}
page_footer();
?>

