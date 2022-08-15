
<?php
// Editor für RP-News (-> s. village.php)
// RP-News einsehen, erstellen, editieren, löschen
// @ Silva
// erstellt für eranya.de
require_once('common.php');
su_check(SU_RIGHT_NEWS,true);        # SU-Rechte-Überprüfung: Wer nicht besteht, fliegt aus der Grotte
page_header('RP-News-Editor');
output("`c`b`GRP-News-Editor`b`c`n");
switch($_GET['op']) {
    // Übersicht über alle schon erstellten RP-News
    case '':
        output("`2In diesem Editor kannst du dir die aktuellen RP-News anzeigen lassen, sie editieren, löschen sowie neue RP-News erstellen.`n
                `n
                <a href='su_newsrp.php?op=new' class='button'>Neue RP-News erstellen</a>`n
                `n
                `n
                `c<table cellpadding='2' cellspacing='2' bgcolor='#333333'>
                    <tr class='trhead'>
                        <td>Nr</td><td>RP-News</td><td>Aktion</td>
                    </tr>");
        addnav("","su_newsrp.php?op=new");
        // Daten aus der DB laden & anzeigen
        $sql = db_query("SELECT * FROM newsrp WHERE archiv = 0 ORDER BY newsrpid");
        if(db_num_rows($sql) == 0) {
            output("<tr class='trdark'><td colspan='3' style='text-align: center;'>`i`7keine Einträge vorhanden`i</td></tr>");
        } else {
            for($i=0;$i<db_num_rows($sql);$i++) {
                $row = db_fetch_assoc($sql);
                output("<tr class='".($i%2 ? "trlight" : "trdark")."'>
                            <td>".($i+1)."</td><td>".$row['newsrptext']."</td><td><a href='su_newsrp.php?op=edit&id=".$row['newsrpid']."'>`FÄndern</a> || <a href='su_newsrp.php?op=del&id=".$row['newsrpid']."' onClick='return confirm(\"Soll der RP-News-Eintrag wirklich gelöscht werden?\");'>`4Löschen</a> || <a href='su_newsrp.php?op=filenews&id=".$row['newsrpid']."' onClick='return confirm(\"Soll der RP-News-Eintrag wirklich ins Archiv verschoben werden?\");'>`9Archivieren</a></td>
                        </tr>");
                addnav("","su_newsrp.php?op=edit&id=".$row['newsrpid']);
                addnav("","su_newsrp.php?op=del&id=".$row['newsrpid']);
                addnav("","su_newsrp.php?op=filenews&id=".$row['newsrpid']);
            }
        }
        output("</table>`c");
        addnav("Archiv");
        addnav("Archivierte Einträge","su_newsrp.php?op=archiv");
    break;
    // RP-News-Archiv anzeigen
    case 'archiv':
        output("`2Dies ist das RP-News-Archiv. Die aufgelisteten Nachrichten werden nicht auf dem Stadtplatz angezeigt.`n
                Um sie wieder anzeigen zu lassen, hole sie aus dem Archiv.`n
                `n
                `c<table cellpadding='2' cellspacing='2' bgcolor='#333333'>
                    <tr class='trhead'>
                        <td>Nr</td><td>RP-News</td><td>Aktion</td>
                    </tr>");
        // Daten aus der DB laden & anzeigen
        $sql = db_query("SELECT * FROM newsrp WHERE archiv = 1 ORDER BY newsrpid");
        if(db_num_rows($sql) == 0) {
            output("<tr class='trdark'><td colspan='3' style='text-align: center;'>`i`7keine Archiv-Einträge vorhanden`i</td></tr>");
        } else {
            for($i=0;$i<db_num_rows($sql);$i++) {
                $row = db_fetch_assoc($sql);
                output("<tr class='".($i%2 ? "trlight" : "trdark")."'>
                            <td>".($i+1)."</td><td>".$row['newsrptext']."</td><td><a href='su_newsrp.php?op=edit&id=".$row['newsrpid']."'>`FÄndern</a> || <a href='su_newsrp.php?op=del&id=".$row['newsrpid']."' onClick='return confirm(\"Soll der RP-News-Eintrag wirklich gelöscht werden?\");'>`4Löschen</a> || <a href='su_newsrp.php?op=activatenews&id=".$row['newsrpid']."' onClick='return confirm(\"Soll der RP-News-Eintrag wirklich aus dem Archiv geholt und wieder angezeigt werden?\");'>`9Aus Archiv holen</a></td>
                        </tr>");
                addnav("","su_newsrp.php?op=edit&id=".$row['newsrpid']);
                addnav("","su_newsrp.php?op=del&id=".$row['newsrpid']);
                addnav("","su_newsrp.php?op=activatenews&id=".$row['newsrpid']);
            }
        }
        output("</table>`c");
        addnav("Editor");
        addnav("Zur Übersicht","su_newsrp.php");
    break;
    // Neue RP-News erstellen I: Formular für die Dateneingabe anzeigen
    case 'new':
        output("`2Erstelle einen neuen RP-News-Eintrag:`n
                `n
                <form action='su_newsrp.php?op=new2' method='post'>
                    <textarea name='text' class='input' cols='55' rows='5'></textarea>`n
                    `n
                    <input type='submit' class='button' value='Absenden'>
                </form>
                `n
                Vorschau:`n
                `n
                ".js_preview('text'));
        addnav("","su_newsrp.php?op=new2");
        // Link zurück zur Übersicht
        addnav("Abbruch");
        addnav("Zur Übersicht","su_newsrp.php");
    break;
    // Neue RP-News erstellen II: Daten in DB eintragen
    case 'new2':
        $str_newsrptext = closetags(trim(encode_specialchars(strip_tags($_POST['text']))),'`i`c`b');
        db_query("INSERT INTO newsrp(newsrptext) VALUES('".$str_newsrptext."')");
        redirect("su_newsrp.php");
    break;
    // RP-News editieren I: Datensatz abrufen und in textarea anzeigen
    case 'edit':
        $int_newsrpid = (int)$_GET['id'];
        // Datensatz aus DB laden
        $row = db_fetch_assoc(db_query("SELECT * FROM newsrp WHERE newsrpid = ".$int_newsrpid." LIMIT 1"));
        // RP-News-Text in textarea eintragen
        output("`2Editiere den RP-News-Eintrag:`n
                `n
                <form action='su_newsrp.php?op=edit2&id=".$row['newsrpid']."' method='post'>
                    <textarea name='text' class='input' cols='55' rows='5'>");
        rawoutput($row['newsrptext']);
        output("</textarea>`n
                    `n
                    <input type='submit' class='button' value='Absenden'>
                </form>
                `n
                Vorschau:`n
                `n
                ".js_preview('text'));
        addnav("","su_newsrp.php?op=edit2&id=".$row['newsrpid']);
        // Link zurück zur Übersicht
        addnav("Abbruch");
        addnav("Zur Übersicht","su_newsrp.php");
    break;
    // RP-News editieren II: RP-News in DB überschreiben
    case 'edit2':
        $int_newsrpid = (int)$_GET['id'];
        $str_newsrptext = closetags(trim(encode_specialchars(strip_tags($_POST['text']))),'`i`c`b');
        db_query("UPDATE newsrp SET newsrptext = '".$str_newsrptext."' WHERE newsrpid = ".$int_newsrpid);
        redirect("su_newsrp.php");
    break;
    // RP-News löschen
    case 'del':
        $int_newsrpid = (int)$_GET['id'];
        db_query("DELETE FROM newsrp WHERE newsrpid = ".$int_newsrpid);
        redirect("su_newsrp.php");
    break;
    // RP-News-Eintrag ins Archiv verschieben
    case 'filenews':
        $int_newsrpid = (int)$_GET['id'];
        db_query("UPDATE newsrp SET archiv = 1 WHERE newsrpid = ".$int_newsrpid);
        redirect("su_newsrp.php");
    break;
    // RP-News-Eintrag aus dem Archiv holen
    case 'activatenews':
        $int_newsrpid = (int)$_GET['id'];
        db_query("UPDATE newsrp SET archiv = 0 WHERE newsrpid = ".$int_newsrpid);
        redirect("su_newsrp.php?op=archiv");
    break;
    // Debug
    default:
        output("`^fehlende op: ".$_GET['op']);
    break;
}
addnav("Zurück");
addnav("Zur Grotte","superuser.php");
page_footer();
?>

