
ï»¿<?php



//20160418



/*

* author: bibir (logd_bibir@email.de)

*      and Chaosmaker (webmaster@chaosonline.de)

*      for http://logd.chaosonline.de

*

* version: 1.2

*

*     a library with text from users to help other

*        a bit like faq

*

* details:

*  (15.11.04) start of idea

*  (15.01.05) project finished

*  (16.01.05) version 1.2: several minor bugfixes

*/



require_once("common.php");

checkday();

addcommentary();

if(!isset($_GET['op'])) $_GET['op']="";



// multivillages mod

if (isset($_GET['mv']) && $_GET['mv']!=$session['user']['specialmisc']) $session['user']['specialmisc']=$_GET['mv'];

$village=($session['user']['specialmisc']>0)?"tradervillages.php?village={$session['user']['specialmisc']}":"academy.php";



addnav('Verlassen',$village);

if (!isset($_GET['mv'])) addnav("Arator betreten","tradervillages.php?village=5");

addnav('Bibliothek');



$sql = "SELECT count(bookid) AS anz FROM lib_books WHERE activated='1'";

$result = db_query($sql) or die(db_error(LINK));

$books = db_fetch_assoc($result);

page_header("Bibirs Bibliothek");

output("`c`b`9Bibirs Bibliothek des gesammelten Wissens in ".($books['anz']==1?'einem Band':$books['anz'].' BÃ¤nden')."`0`b`c`n");



switch($_GET['op']){

case "browse":

    addnav("H?ZurÃ¼ck in die Halle","library.php");

    addnav("Buch einreichen","library.php?op=offer");

    output("`tDu gehst durch die Regalreihen und siehst, dass alle BÃ¼cher ordentlich nach Themen einsortiert sind.`n

    Folgende Themen stehen derzeit zur Auswahl:`n`n");

    $sql = "SELECT t.*, COUNT(b.bookid) as anz FROM lib_themes t

            LEFT JOIN lib_books b ON b.themeid=t.themeid AND b.activated='1'

            GROUP BY themeid

            ORDER BY listorder ASC";

    $result = db_query($sql) or die(db_error(LINK));

    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td>Thema</td><td>BÃ¼cher</td></tr>",true);

    $bgclass = '';

    addnav("Themen");

    while ($row = db_fetch_assoc($result)) {

        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

        if ($row['anz']>0) {

            output("<tr class='$bgclass'><td><a href=\"library.php?op=theme&id=".$row['themeid']."\">",true);

            output($row['theme']);

            output("`0</a></td><td align='right'>".$row['anz']."</td></tr>",true);

        }

        else {

            output("<tr class='$bgclass'><td>",true);

            output($row['theme']);

            output("`0</td><td>kein Buch</td></tr>",true);

        }

        addnav("","library.php?op=theme&id=".$row['themeid']);

        addnav($row['theme'],"library.php?op=theme&id=".$row['themeid']);

    }

    output("</table>",true);

        addnav("F.A.Q.","petition.php?op=faq",false,true);

    break;



case "theme":

    addnav("H?ZurÃ¼ck in die Halle","library.php");

    //addnav("Andere Regale","library.php?op=browse");

    addnav("Buch einreichen","library.php?op=offer");



    addnav("Themen");

    $sql = "SELECT themeid, theme FROM lib_themes ORDER BY listorder ASC";

    $result = db_query($sql) or die(db_error(LINK));

    while ($row = db_fetch_assoc($result)) {

        if ($row['themeid']!=$_GET['id']) {

            addnav($row['theme'],"library.php?op=theme&id=".$row['themeid']);

         }

        else {

            addnav($row['theme'],'');

            $thistheme = $row['theme'];

        }

    }

    output("`c`b".$thistheme."`0`b`c");

    output("`n`6Zu diesem Thema stehen dir folgende BÃ¼cher zur VerfÃ¼gung:`n`n");



    $sql = "SELECT title, bookid, author, gelesen FROM lib_books

            WHERE themeid=".$_GET['id']." AND activated='1' ORDER BY listorder ASC";

    $result = db_query($sql) or die(db_error(LINK));

    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td>Titel</td><td>Autor</td><td>Gelesen</td></tr>",true);

    if (db_num_rows($result)==0) {

        output("<tr class='trdark'><td colspan='3'>Es gibt leider bisher noch keine BÃ¼cher zu diesem Thema.</td></tr>",true);

    }

    else {

        addnav('BÃ¼cher');

        $bgclass = '';

        while ($row = db_fetch_assoc($result)) {

            $bgclass = ($bgclass=='trdark'?'trlight':'trdark');

            output("<tr class='$bgclass'><td><a href=\"library.php?op=book&bookid=".$row['bookid']."\">",true);

            output($row['title']);

            output("`0</a></td><td>",true);

            output($row['author']);

            output("`0</td><td>",true);

            output($row['gelesen']."`0</td></tr>",true);

            addnav("","library.php?op=book&bookid=".$row['bookid']);

            addnav($row['title'],'library.php?op=book&bookid='.$row['bookid']);

        }

    }

    output("</table>",true);

    break;



case "book":

    addnav("H?ZurÃ¼ck in die Halle","library.php");

    //addnav("Ein anderes Thema","library.php?op=browse");



    $sql = "SELECT t.theme, b.themeid, b.title, b.book, b.acctid, b.author, b.gelesen, b.lastreadby FROM lib_books b

            LEFT JOIN lib_themes t USING(themeid)

            WHERE bookid=".$_GET['bookid'];

    $result = db_query($sql) or die(db_error(LINK));

    $row = db_fetch_assoc($result);



    //addnav("R?ZurÃ¼ck ans Regal","library.php?op=theme&id=".$row['themeid']);

    addnav("Buch einreichen","library.php?op=offer");



    addnav("Themen");

    $sql = "SELECT themeid, theme FROM lib_themes ORDER BY listorder ASC";

    $result = db_query($sql) or die(db_error(LINK));

    while ($row2 = db_fetch_assoc($result)) {

        addnav($row2['theme'],"library.php?op=theme&id=".$row2['themeid']);

    }



    addnav('BÃ¼cher');

    $sql = 'SELECT title, bookid FROM lib_books WHERE themeid='.$row['themeid'].' AND activated="1" ORDER BY listorder ASC';

    $result = db_query($sql) or die(db_error(LINK));

    while ($row2 = db_fetch_assoc($result)) {

        if ($row2['bookid']!=$_GET['bookid']) addnav($row2['title'],'library.php?op=book&bookid='.$row2['bookid']);

        else addnav($row2['title'],'');

    }



    //nichts editierbar

    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trdark'><td>Thema:</td><td>",true);

    output($row['theme']);

    output("`0</td></tr><tr class='trlight'><td>Titel:</td><td>",true);

    output($row['title']);

    output("`0</td></tr><tr class='trdark'><td>Autor:</td><td>",true);

    output($row['author']);

    output("`0</td></tr><tr class='trlight'><td colspan='2'>",true);

    output(str_replace("\n",'`n',$row['book']));

    output('</td></tr></table>',true);

    output("`nDieses Buch wurde bereits ".$row['gelesen']."x gelesen.");

        if ($row['lastreadby'] == $session['user']['acctid']) {

        output("Zuletzt von dir.");

    } elseif ($row['acctid'] == $session['user']['acctid']) {

        output("Du Ã¼berzeugst dich vom einwandfreien Zustand deines Werks und stellst es zurÃ¼ck ins Regal.");

    } else {

        $sql = 'UPDATE lib_books SET gelesen=gelesen+1, lastreadby='.$session['user']['acctid'].' WHERE bookid='.$_GET['bookid'];

        db_query($sql);

    }

    break;



case "offer":

    addnav("H?ZurÃ¼ck in die Halle","library.php");

    if ($_GET['subop']=="save" && !empty($_POST['title']) && !empty($_POST['book'])) {

        addnav("Weiteres Buch schreiben","library.php?op=offer");

        output("`tDein Buch wurde zum Druck eingereicht.`0");

        // maximale sortiernummer holen

        $sql = 'SELECT MAX(listorder) AS maxorder FROM lib_books';

        $result = db_query($sql);

        $row = db_fetch_assoc($result);

        if ($row['maxorder']<=0) $row['maxorder']=0;

        $sql = "INSERT INTO lib_books (themeid, acctid, author, title, book, listorder, gelesen)

            VALUES (".(int)$_POST['themeid'].", ".$session['user']['acctid'].", '".$session['user']['name']."', '".$_POST['title']."', '".$_POST['book']."', ".$row['maxorder'].", 0)";

        db_query($sql);

    }

    else {

        if ($_GET['subop']=='save') {

            output('`c`$Wie soll ein Buch gedruckt werden, wenn nicht Titel und Inhalt existieren?`0`c`n`n');

            $_POST['title'] = str_replace('`','``',$_POST['title']);

            $_POST['book'] = str_replace('`','``',$_POST['book']);

        }

        else $_POST['title'] = $_POST['book'] = $_POST['themeid'] = '';

        output("`tHier hast du die MÃ¶glichkeit, eigenes Wissen niederzuschreiben und anderen damit zur VerfÃ¼gung zu stellen.`n`n

        Nun liegt es an dir, die Zeilen auf das Pergament zu bringen, die du dein Wissen nennst.`0");

        output("<form action=\"library.php?op=offer&subop=save\" method='POST'>",true);

        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trdark'><td>Thema:</td><td><select name='themeid'>",true);

        $sql2 = "SELECT * FROM lib_themes ORDER BY listorder ASC";

        $result2 = db_query($sql2) or die(db_error(LINK));

        while ($row2 = db_fetch_assoc($result2)) {

            output("<option value='".$row2['themeid']."' ".($row2['themeid']==$_POST['themeid']?" selected='selected'":"").">".preg_replace('/`./','',$row2['theme'])."</option>",true);

        }

        output("</select></td></tr>",true);

        output("<tr class='trlight'><td>Titel:</td><td><input class='input' type='text' name='title' value='{$_POST['title']}' maxlength='50' size='50'></td></tr>",true);

        output("<tr class='trdark'><td colspan='2'>Mein Wissen Ã¼ber dieses Thema:</td></tr>",true);

        output("<tr class='trdark'><td colspan='2'><textarea name='book' class='input' cols='60' rows='10'>{$_POST['book']}</textarea></td></tr>",true);

        output("<tr class='trlight'><td colspan='2'><input type='submit' class='button' value='Einreichen'></td></tr></table></form>",true);

        addnav("","library.php?op=offer&subop=save");

    }

    break;



default:

    output('`tAm Eingang zur Bibliothek hÃ¤ngt ein Plakat. Du liest:`n`n');

    output('`qDie Bibliothek ist ein Ort des Wissens.`n

           Dieses Wissen kann aber nur gehalten werden, wenn jemand es niedergeschrieben hat.`n

           Dazu steht in dieser Bibliothek die MÃ¶glichkeit bereit, Texte zu verfassen und diese einzureichen.`n

           Nach Genehmigung durch Regenten oder BevollmÃ¤chtigte wird das Buch gedruckt und in die Regale der BÃ¼cherei gestellt.`n

           Von nun an hat jeder die MÃ¶glichkeit, einen Blick in dieses Buch zu werfen und sowohl interessante als auch nÃ¼tzliche Informationen zu bekommen.`n

           Sollte das geschriebene Buch gedruckt werden, erhÃ¤lt der Autor ein DankeschÃ¶n in Form von '.getsetting("libdp","25").' Punkten in J.C. Petersens JÃ¤gerhÃ¼tte.`n`n');

    output("`tDu betrittst den groÃŸen Raum mit den unzÃ¤hligen Regalen.`n

    Hier sind gedÃ¤mpfte Unterhaltungen zu hÃ¶ren und in den vielen bequemen

    Drachenleder-Sesseln sitzen eifrige KÃ¤mpfer, um zu lesen.`n

    An ein paar Tischen kannst du hin und wieder auch sehr erfahrene DrachentÃ¶ter finden,

    die ihr Wissen in BÃ¼chern niederschreiben.");

    output("`n`nEin paar Leute unterhalten sich leise:`n`0");

    viewcommentary("library","Leise flÃ¼stern:",25);

    addnav("StÃ¶bern","library.php?op=browse");

    addnav("Buch einreichen","library.php?op=offer");

}



page_footer();

?>

