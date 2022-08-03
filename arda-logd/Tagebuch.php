<?php
$allowedTags = '<br><b><h1><h2><h3><h4><i><hr>' . '<img><li><ol><p><strong><table>' . '<tr><td><th><u><ul><div><span><center><p><img>';

$stripAttrib = 'javascript&#058;|onclick|ondblclick|onmousedown|onmouseup|onmouseover|' . 'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|onabort|' . 'onfocus|onload|onblur|onchange|onerror|onreset|onselect|obsubmit|onunload|style';
removeEvilAttributes();

function removeEvilTags($source, $iframe_allowed) {
    global $allowedTags;
    if ($iframe_allowed == 1)
        $allowedTags .= "<iframe>";
    $source = strip_tags($source, $allowedTags);

    return preg_replace('/<(.*?)>/ie', "'<'.removeEvilAttributes('\\1').'>'", $source, $allowedTags);
}

function removeEvilAttributes($tagSource) {
    global $stripAttrib;
    return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden', $tagSource));
}

require_once "common.php";
checkday();

page_header("Das Tagebuch");
output("`^`c`bDein Tagebuch`b`c`6");

if ($_GET['op'] == 'editChapter') {
    // edit a chapter

    output("<form action='Tagebuch.php?char=$char&op=progress&act=editChapter&ID=$_GET[no]&ret=$_GET[ret]' method='POST'>", true);

    $form = array("Neues Kapitel,title", "diaryID" => "ID ,veryhidden", "title" => "Titel", "body" => "Inhalt,textarea,70,30");

    $bio_res = db_query("SELECT * FROM `diary` WHERE `diaryID`='" . $_GET['no'] . "'");

    $bio_row = db_fetch_assoc($bio_res);

    $prefs['title'] = $bio_row['title'];

    $prefs['body'] = $bio_row['body'];

    $prefs['diaryID'] = $bio_row['diaryID'];

    showform($form, $prefs);

    output("</form>", true);

    addnav("", "Tagebuch.php?char=$char&op=progress&act=editChapter&ID=$_GET[no]&ret=$_GET[ret]");
}
elseif ($_GET['op'] == 'delChapter') {
    // delete a chapter

    $sql = "DELETE FROM `diary` WHERE `diaryID`='" . $_GET['no'] . "'";

    db_query($sql);

    redirect("Tagebuch.php");
}
else if ($_GET['op'] == "create") {
    addnav("Zurück", "Tagebuch.php");
    // create a new chapter

    output("<form action='Tagebuch.php?op=progress&act=newChapter&char=$char&ret=$_GET[ret]' method='POST'>", true);

    $form = array("Neues Kapitel,title", "title" => "Titel", "body" => "Inhalt,textarea,70,30");

    $prefs['title'] = "";

    $prefs['body'] = "";

    showform($form, $prefs);

    output("</form>", true);

    addnav("", "Tagebuch.php?op=progress&act=newChapter&char=" . $char . "&ret=" . $_GET[ret]);
}
elseif ($_GET['op'] == 'progress') {
    // saving all the changes
    // most of them and centralizing them by :kelko:

    if ($_GET['act'] == 'editChapter') {
        // editing a chapter

        /*
         * $body = str_replace("\'","\\'", $_POST[body]); $body = str_replace("'","\\'", $_POST[body]); $title = str_replace("'","\\'", $_POST[title]); $title = str_replace("\'","\\'", $_POST[title]);
         */
        $body = mysql_real_escape_string(stripslashes($_POST['body']));
        $title = mysql_real_escape_string(stripslashes($_POST['title']));

        db_query("UPDATE diary SET title='$title', body='$body' WHERE diaryID='" . $_GET[ID] . "'");

        output("Kapitel geändert");
        redirect("Tagebuch.php");
    }
    else if ($_GET['act'] == 'newChapter') {
        // creating a chapter

        /*
         * $body = str_replace("\'","\\'", $_POST[body]); $body = str_replace("'","\\'", $_POST[body]); $title = str_replace("'","\\'", $_POST[title]); $title = str_replace("\'","\\'", $_POST[title]);
         */
        $body = mysql_real_escape_string(stripslashes($_POST['body']));
        $title = mysql_real_escape_string(stripslashes($_POST['title']));

        db_query("INSERT INTO `diary`(`acctid`,`title`,`body`,`date`) VALUES('" . $session[user][acctid] . "','$title','$body',now())");

        output("Kapitel gespeichert");
        redirect("Tagebuch.php");
    }
}
else {
    addnav("zurück", "prefs.php");
    addnav("Neuer Eintrag", "Tagebuch.php?op=create");

    output("Hier hast du die Möglichkeit ein Tagebuch für dich zu schreiben und weiter zu führen.`n");
    output("Ebenso sollst du auch die möglichkeit bekommen deine Einträge hier zu bearbeiten und zu löschen`n");
    output("Derzeitige Einträg: `n`n");

    $sql = "SELECT * FROM `diary` WHERE `acctid`='" . $session[user][acctid] . "' ORDER BY `diaryID` ASC";

    $bio_res = db_query($sql);

    // showing each chapter
    output("`n`n");
    if (db_num_rows < 1) {
        for ($i = 0; $i < db_num_rows($bio_res); $i++) {

            $bio_row = db_fetch_assoc($bio_res);

            // the table is used for better centralized texts
            // no 'reorganizing' (shifting right) of the text when the paypal-icons end
            output("<table width='100%'><tr><td width='5%'></td><td width='90%'>", true);

            output("`c`!$bio_row[title]`0");
           // if ($session['user']['superuser'] >= 3) {
                // edit this particular chapter
                output("<a href='Tagebuch.php?op=editChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET[ret] . "'>[Bearbeiten]</a>", true);

                addnav("", "Tagebuch.php?op=editChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET[ret]);

                // delete this particular chapter
                output("<a href='Tagebuch.php?op=delChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET[ret] . "'>`$[Löschen]</a>", true);

                addnav("", "Tagebuch.php?op=delChapter&no=$bio_row[diaryID]&char=" . $char . "&ret=" . $_GET[ret]);
           // }
            output("`c`n");
            //output($bio_row ['body']);

            $body = str_replace ( "/me", $session[user][name]."`0", $bio_row ['body'] );

            output ( $body );

            // show this chapter
            //output ( removeEvilTags ( soap ( nl2br ( $body ) ), "`c`b" ), true );

            output("</td><td width='5%'></td></tr></table>", true);

            output("`n`n");
        }
    }else{
        output("Leider sind noch keine Einträge vorhanden");
    }
}
page_footer();
?> 