
<?php

require_once('common.php');
require_once(LIB_PATH.'board.lib.php');

define('LISTCOLORPINBOARDTEXT','`u');
define('LISTCOLORPINBOARDBOLD','`g');
define('LISTCOLORPINBOARDSTICKER','`G');

checkday();

page_header('Pinnwand');

if ($_GET['op']=='add') {
    if ($_GET['board_action'] == '') {
        output(LISTCOLORPINBOARDTEXT.'Du entscheidest dich dafür, eine Nachricht zu verfassen. Also trittst du an den Stehtisch, nimmst dir
                einen der Zettel und schreibst in Schönschrift deine Nachricht darauf.`n');

        board_view_form('Nachricht verfassen',
        'Gib deine Nachricht ein:');

        addnav('Zurück');
        addnav('Zurück','rp_request.php');
    } else {
        $requestprice = 10;

        if ($session['user']['gold'] < $requestprice && $session['user']['goldinbank'] < $requestprice) {
            output('`^Leider hast du nicht genug Gold dabei und auch nicht genug Gold auf der Bank.`n');
            addnav('Zurück');
            addnav('Zurück','rp_request.php');
        } else {
            if (board_add('list',(int)$_GET['amt'],1) == -1) {
                output(LISTCOLORPINBOARDTEXT.'Es hängt bereit eine Nachricht von dir an der Pinnwand. Bitte entferne diese erst, bevor du eine neue verfasst.`n');
                addnav('Zurück');
                addnav('Zurück','rp_request.php');
            } else {
                output(LISTCOLORPINBOARDTEXT.'Du heftest deine Nachricht an die Pinnwand. Nun kann sie von anderen gelesen werden.`n');
                if($session['user']['gold'] < $requestprice) {
                    output(LISTCOLORPINBOARDTEXT.'`nDie Gebühr für deinen Aushang wird von deinem Bankkonto eingezogen.`n');
                    $session['user']['goldinbank'] -= $requestprice;
                } else {
                    $session['user']['gold'] -= $requestprice;
                }
                addnav('Zurück');
                addnav('Zurück','rp_request.php');
            }
        }
    }
} else {
    $requestdays = (int)getsetting('daysperday',4);
    output(LISTCOLORPINBOARDBOLD.'`c`bDie Pinnwand`b`c`n`n
            '.LISTCOLORPINBOARDTEXT.'Dein Blick fällt auf eine weiß umrahmte Pinnwand und einen schmalen Stehtisch,
            der daneben gestellt wurde und auf dem einige Zettel, ein Tintenfass und eine Feder liegen. Bei genauerem
            Hinsehen entdeckst du zudem noch eine Plakette, die unter der Pinnwand angebracht worden ist und auf der
            geschrieben steht:`n`n
            '.LISTCOLORPINBOARDSTICKER.'"An dieser Pinnwand können RP-Gesuche und -Angebote gegen eine Gebühr
            von `^10 Gold '.LISTCOLORPINBOARDSTICKER.'hinterlassen werden.`n
            Es ist jedoch verboten, sie als schwarzes Brett zu missbrauchen und Nachrichten zu anderen Themen
            (Schlüsselanfragen, aufs Klickspiel bezogene Gildenwerbung o.ä.) dort anzubringen."`n`n
            '.LISTCOLORPINBOARDTEXT.'Dir steht es frei zu entscheiden, ob deine Nachricht für zwei Wochen oder alternativ
            einen, zwei oder drei Monate aufgehängt werden soll.');
    addnav('Zeitraum für Nachricht');
    addnav('2 RL-Wochen','rp_request.php?op=add&amt=14');
    addnav('1 RL-Monat','rp_request.php?op=add&amt=30');
    addnav('2 RL-Monate','rp_request.php?op=add&amt=60');
    addnav('3 RL-Monate','rp_request.php?op=add&amt=90');
    if ($session['user']['message'] > '') {
        output(LISTCOLORPINBOARDTEXT.'`nBedenke, dass deine alte Nachricht entfernt wird, wenn du nun eine neue aufgibst.`n');
    }

    output(LISTCOLORPINBOARDTEXT.'`n`n`n');
    board_view('list',(su_check(SU_RIGHT_COMMENT))?2:1,
               'An der Pinnwand hängen bereits einige Nachrichten:',
               'Bisher wurden noch keine Nachrichten an die Pinnwand geheftet.',true,false,false,true,true);

    addnav('Zurück');
    addnav('Zurück zur Einwohnerliste','list.php');
}

page_footer();

?>

