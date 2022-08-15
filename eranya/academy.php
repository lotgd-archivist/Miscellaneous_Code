
<?php
// Ursprüngliches Skript gecodet von Warchild (Feb/März 2004, warchild@gmx.org)
// Zauberladen eingebaut (25.07.2004, durch anpera)
// Talion: Anpassung ans Gildensystem (Rabatte)
// Skript verkleinert und neu aufgesetzt von Silva für eranya.de
require_once("common.php");
addcommentary();
// Farben festlegen
define('ACADEMYCOLORTEXT','`|');
define('ACADEMYCOLORHEAD','`ã');
define('ACADEMYCOLORTALK','`ý');
define('ACADEMYCOLORPRICE','`v');
define('ACADEMYCOLORPRICEGOLD','`&');
define('ACADEMYCOLORPRICEGEMS','`&');
// * Skriptstart & Vorbereitungen
page_header("Akademie für Künste");
// Modul laden:
$sql = "SELECT * FROM specialty WHERE specid='".$session['user']['specialty']."'";
$row = db_fetch_assoc(db_query($sql));
if(file_exists("module/".$row['filename'].".php")) {
  require_once "module/".$row['filename'].".php";
  $f1 = $row['filename']."_info";
  $f1();
  $f2 = $row['filename']."_run";
} else {
  function blank(){ return false;}
  $f2 = "blank";
}
// Kosten: gestaffelt nach Skillevel
$skills = array($row['specid']=>$row['usename']);
$akt_magiclevel = (int)($session['user']['specialtyuses'][$skills[$session['user']['specialty']]] + 1); // man faengt bei 0 an ;o)
// Preise festlegen:
$cost_low = (($akt_magiclevel + 1) * 75)-$session['user']['reputation'];
$cost_medium =(($akt_magiclevel + 1)* 100)-$session['user']['reputation']; //plus ein Edelstein
$cost_high = (($akt_magiclevel + 1) * 125)-$session['user']['reputation']; //plus 2 Edelsteine
// tcb: Gesamtanzahl an Anwendungen darf 20 nicht überschreiten
$uses_ges = $session['user']['specialtyuses']['darkartuses'] + $session['user']['specialtyuses']['thieveryuses'] + $session['user']['specialtyuses']['magicuses'];
// Schon mal studiert an diesem newday?
$rowe = user_get_aei('seenacademy');
// end Skriptstart & Vorbereitungen *
$str_op = (isset($_GET['op']) ? $_GET['op'] : '');
switch($str_op) {
    // Startseite
    case '':
        output(ACADEMYCOLORHEAD.'`c`bIn der Akademie für Künste`b`c`n'.ACADEMYCOLORTEXT.'
                Schon das Gebäude verrät, welch außergewöhnliches Innenleben sich darin befindet: Als wohl ältestes Bauwerk '.getsetting('townname','Eranya').'s
                sticht es mit seinen zahlreichen Erkern und den zwei gen Himmel ragenden Türmen zwischen all den umliegenden Gebäuden der Stadtmitte hervor. Fast
                könnte die Konstruktion als Schloss durchgehen, wäre sie nur etwas größer. So allerdings reicht der Platz gerade aus, um die von Zerindo geleitete
                Akademie für Künste zu beherbergen.`n
                Im Inneren erwartet dich eine weite Eingangshalle mit hoher Decke. Gemälde von dir fremden Personen hängen an den Wänden, und auch die eine und
                andere Skulptur ist hier ausgestellt, auch wenn sich dir der Sinn der meisten auf den ersten Blick nicht erschließt. In der Mitte des
                Raumes schwebt eine leuchtend blaue Kugel von gut einer Armlänge Durchmesser, die mit drei schweren Eisenketten am Boden festgekettet ist. Du
                kannst die geballte Magie förmlich spüren, die in dieser Kugel eingeschlossen sein muss.`n
                `n
                Zu deiner Linken findest du ein Informationsbrett mit einem Aushang zu den gängigen Preisen:`n'.ACADEMYCOLORPRICE.'"');
        // Preisliste
        $f2("academy_desc");
        // end
        output('"`n'.ACADEMYCOLORTEXT.'An den unteren Rand des Aushangs hat zudem jemand Folgendes gekritzelt:`n
               '.ACADEMYCOLORPRICE.'"`iSelbstst. eher semi -- Prakt. Ü. lustig, aber gelegentl. überfl. -- Std. bei Z. super!`i"`n`n');
        // Chars mit schlechtem Ruf dürfen nicht trainieren oder Zauber kaufen:
        if($session['user']['reputation'] < -5) {
            output(ACADEMYCOLORHEAD.'Leider ist dir dein schlechter Ruf vorausgeeilt, weswegen dir sämtliche Dienste in der Akademie nicht zur Verfügung stehen.`n`n');
        }
        // end
        viewcommentary('academy','Sagen',15,'sagt');
        // Gesamte Nav nur anzeigen, wenn Char angemessenen bis guten Ruf besitzt
        if($session['user']['reputation'] >= -5) {
            // Künste-Nav nur anzeigen, wenn...
            if($uses_ges <= 20 && $session['user']['turns'] > 0 && $rowe['seenacademy'] == 0) {
                addnav('Akademie');
                addnav("Selbststudium","academy.php?op=study");
                addnav("Praktische Übung","academy.php?op=practice");
                addnav("Stunde bei Zerindo","academy.php?op=warchild");
            }
            addnav('Zauberladen');
            addnav("Zauberladen betreten","academy.php?op=shop");
        }
        knappentraining_link('academy');
    break;
    // Selbststudium
    case 'study':
        if($session['user']['gold'] < $cost_low) {
            output('`^Du kannst dir das Selbststudium leider nicht leisten.');
            addnav('Zurück');
            addnav('Zum Eingang','academy.php');
        } else {
            // subtract costs
            $session['user']['gold'] -= $cost_low;
            debuglog("paid {$cost_low} to the academy for studying");
            // war heute schonmal hier...
            user_set_aei(array('seenacademy'=>1));

            $session['user']['turns']--;

            output("`\$`b`c Bibliothek der Akademie`c`b`n`n");
            $rand = e_rand(1,3);
            switch($rand) {
                case 1:
                    output("`^Du sitzt in der Bibliothek mit dem Buch in der Hand, als es plötzlich nach dir schnappt und dir in die Hand `4beisst! `6Der
                            Schmerz ist furchtbar!`^`n
                            Du versuchst verzweifelt, das Buch wieder abzuschütteln, während einige andere Studenten einen kleinen Kreis um dich bilden und sich
                            schlapplachen.`n
                            Frustiert und fluchend verlässt du die Akademie.`n
                            `n
                            `5Du verlierst einige Lebenspunkte!");
                    $session['user']['hitpoints'] *= 0.8;
                break;
                case 2:
                    output("`^Du verbringst einige Zeit in der Akademie und liest intensiv, doch schon bald ergeben die Wörter irgendwie keinen Sinn mehr.
                            Schließlich gibst du auf.`n
                            Frustiert verlässt du die Akademie.");
                break;
                case 3:
                    output("`7Du nimmst dir einen grossen, ledergebundenen Folianten und öffnest ihn. Zunächst geschieht nichts, doch plötzlich `2redet das
                            Buch mit dir!`7`n
                            Fasziniert lauschst du den geheimen Worten und lernst wirklich etwas über `i".$info['specname']."`i. Breit grinsend und stolz auf
                            dein neues Wissen verlässt du die Akademie.`n`n");
                    increment_specialty();
                break;
            }
        }
    break;
    // Praktische Übung
    case 'practice':
        if($session['user']['gold'] < $cost_medium) {
            output('`^Du kannst dir die Teilnahme an der praktischen Übung leider nicht leisten, dir fehlt das nötige Gold.');
            addnav('Zurück');
            addnav('Zum Eingang','academy.php');
        } elseif($session['user']['gems'] < 1) {
            output('`^Du kannst dir die Teilnahme an der praktischen Übung leider nicht leisten, dir fehlt der nötige Edelstein.');
            addnav('Zurück');
            addnav('Zum Eingang','academy.php');
        } else {
            // subtract costs
            $session['user']['gold'] -= $cost_medium;
            $session['user']['gems']--;
            debuglog("paid {$cost_medium} and 1 gem to the academy for practicing");
            // war heute schonmal hier...
            user_set_aei(array('seenacademy'=>1));

            $session['user']['turns']--;

            output("`\$`b`c Bibliothek der Akademie`c`b`n`n");
            $rand = e_rand(1,3);
            switch ($rand)
            {
                case 1:
                    output("`^Du verlässt den Trainingsbereich geschlagen und mit einigen blutenden Wunden.`n
                            Gesenkten Hauptes gehst du in die Stadt zurück.`n
                            `n
                            `5Du verlierst ein paar Lebenspunkte!");
                    $session['user']['hitpoints'] *= 0.9;
                break;
                case 2:
                case 3:
                    output("`7Nach einer forderndern Trainingsstunde, die du souverän meisterst, machst du dich auf den Heimweg.`n
                            Bevor du gehst, gratuliert dir Zerindo zu dem erfolgreichen Training.`n`n");
                    increment_specialty();
                break;
            }
        }
    break;
    // Stunde bei Zerindo
    case 'warchild':
        if($session['user']['gold'] < $cost_high) {
            output('`^Du kannst dir die Teilnahme an der praktischen Übung leider nicht leisten, dir fehlt das nötige Gold.');
            addnav('Zurück');
            addnav('Zum Eingang','academy.php');
        } elseif($session['user']['gems'] < 2) {
            output('`^Du kannst dir die Teilnahme an der praktischen Übung leider nicht leisten, dir fehlt der nötige Edelstein.');
            addnav('Zurück');
            addnav('Zum Eingang','academy.php');
        } else {
            // subtract costs
            $session['user']['gold'] -= $cost_high;
            $session['user']['gems'] -= 2;
            debuglog("paid {$cost_high} and 2 gems to the academy for working with Zerindo");
            // war heute schonmal hier...
            user_set_aei(array('seenacademy'=>1));

            $session['user']['turns']--;

            output("`\$`b`c Das Innere der Akademie`c`b`n
                    `n
                    `7Du verbringst einige Zeit im schwarzen Turm der Akademie in der höchsten Kammer und `4Zerindo`7 eröffnet dir eine neue Dimension deiner
                    Fähigkeiten.`n
                    Du verlässt den Ort zufrieden und wissender als zuvor!`n`n");
            increment_specialty();
        }
    break;
    // * Zauberladen
    case 'shop':
        output("`b`c".ACADEMYCOLORHEAD."Instant-Zauber aller Art`c`b`0`n");

        $show_invent = true;

        require_once(LIB_PATH.'dg_funcs.lib.php');
        if($session['user']['guildid'] && $session['user']['guildfunc'] != DG_FUNC_APPLICANT) {
            $rebate = dg_calc_boni($session['user']['guildid'],'rebates_spells',0);
        }

        if($_GET['action']=="sell") {
            output(ACADEMYCOLORTEXT."Du zeigst dem alten Zauberer alle deine Zauber und er sagt dir, was er dafür bezahlen würde.`n`n");

            item_show_invent(' (spellshop = 2 OR spellshop = 3) AND owner='.$session['user']['acctid'], false, 2, 1, 1, ACADEMYCOLORTEXT.'Du hast keine Zauber, die du dem Alten anbieten könntest.');

            addnav('Zurück');
            addnav("Zum Laden","academy.php?op=shop");
        } elseif($_GET['action']=="buy") {
            output(ACADEMYCOLORTALK."\"Du willst Magie nutzen, ohne sie mühsam studieren zu müssen? Dann bist du hier genau richtig.\"".ACADEMYCOLORTEXT." Mit diesen Worten überreicht dir der Alte eine Liste aller Zauber, die er dir anbieten kann. ".ACADEMYCOLORTALK."\"Bitte sehr. Wähle dir etwas aus.".($rebate?" Achja, dank deiner Gildenmitgliedschaft gewähre ich dir `^".$rebate." %".ACADEMYCOLORTALK." Rabatt!":"")."\"`n`n");

            $rebate = (100 - $rebate) * 0.01;

            item_show_invent(' (spellshop = 1 OR spellshop = 3) ', true, 1, $rebate, $rebate, ACADEMYCOLORTALK.'"Tut mir Leid, mein Freund. Wir haben keine Zauber für dich."');

            addnav('Zurück');
            addnav("Zum Laden","academy.php?op=shop");
        } else {
            $arr_race = race_get($session['user']['race']);
            output(ACADEMYCOLORTEXT."Durch schwere und reich verzierte Holztüren betrittst du den Zauberladen der Akademie. Hier bietet ein älterer Zauberer die Werke verschiedenster Akademie-Magier an, denen es gelungen ist, selbst magisch unbegabten ".($arr_race['name_plur'])." wie dir die Anwendung ihrer Zauber zu ermöglichen. ");
            output(" Natürlich geht bei Magiern nichts ohne entsprechende Bezahlung, so rechnest du auch hier mit saftigen Preisen, um die hohen Entwicklungskosten, die wohl durch zahlreiche Fehlschläge und unzählige zu Bruch gegangene Zauberutensilien zu erklären sind, auszugleichen.");
            addnav('Zauberladen');
            addnav("Zauber verkaufen","academy.php?op=shop&action=sell");
            addnav("Zauber kaufen","academy.php?op=shop&action=buy");
            addnav('Zurück');
        }
        addnav("Zur Akademie","academy.php");
    break;
    // Zauber-Kauf durchführen
    case 'buy_do':
        $item = item_get_tpl(' tpl_id="'.$_GET['tpl_id'].'" ');

        $name = $item['tpl_name'];

        $goldprice = round($item['tpl_gold'] * $_GET['gold_r']);
        $gemsprice = round($item['tpl_gems'] * $_GET['gems_r']);

        $item['tpl_gold'] = round($goldprice * 0.8);
        $item['tpl_gems'] = 0;

        if (item_count(' tpl_id="'.$_GET['tpl_id'].'" AND owner='.$session['user']['acctid']) > 0 && $item['tpl_class'] == 14) { # zweimal denselben Zauber zu kaufen, ist verboten
            output(ACADEMYCOLORTEXT."Diesen Zauber hast du schon. Du musst ihn entweder aufbrauchen, oder verkaufen, bevor du ihn neu kaufen kannst.");
            addnav("Etwas anderes kaufen","academy.php?op1=bringmetolife&action=buy");
        } else {
            output(ACADEMYCOLORTEXT."Du deutest auf den Namen in der Liste. Bis auf den Namen des Zaubers \"".ACADEMYCOLORTEXT.$name.ACADEMYCOLORTEXT."\" verschwinden alle anderen Worte von der Liste und der Zauberer gibt dir, was du verlangst. Gerade, als du bezahlen willst, schweben ".($goldprice?"`^".$goldprice." ".ACADEMYCOLORTEXT."Gold":"")." ".($gemsprice?"`#".$gemsprice."".ACADEMYCOLORTEXT." Edelsteine":"")." aus deinen Vorräten in die Hand des Zauberers. ");

            $session['user']['gold'] -= $goldprice;
            $session['user']['gems'] -= $gemsprice;

            item_add($session['user']['acctid'],0,$item);

            addnav("Mehr kaufen","academy.php?op=shop&action=buy");
        }
    break;
    // Zauber-Verkauf durchführen
    case 'sell_do':
        $item = item_get(' id="'.$_GET['id'].'" ', false);

        $name = $item['name'];

        $goldprice = round($item['gold'] * $_GET['gold_r']);
        $gemsprice = round($item['gems'] * $_GET['gems_r']);

        output(ACADEMYCOLORTEXT."Der alte Zauberer begutachtet ".$name.ACADEMYCOLORTEXT.". Dann überreicht er dir sorgfältig abgezählt ".($goldprice?"`^".$goldprice." ".ACADEMYCOLORTEXT."Gold":"")." ".($gemsprice?"`#".$gemsprice."".ACADEMYCOLORTEXT." Edelsteine":"")." und lässt den Zauber verschwinden. Wörtlich. ");
        addnav("Mehr verkaufen","academy.php?op=shop&action=sell");

        item_delete(' id='.(int)$_GET['id'] );

        $session['user']['gold'] += $goldprice;
        $session['user']['gems'] += $gemsprice;
    break;
    // end Zauberladen *
    // Debug
    default:
        output('`&Nanu, was machst du denn hier? Sende bitte die folgende Meldung via Anfrage an das Adminteam, zusammen mit einer kurzen Beschreibung dessen, was
                du direkt vor dem Auftauchen dieser Seite getan hast:`n
                `n
                `^fehlende op: '.$str_op.' in academy.php');
        addnav('Zurück');
        addnav('Zur Akademie','academy.php');
    break;
}
addnav('Verlassen');
addnav('Zum Stadtplatz','village.php');

page_footer();
?>

