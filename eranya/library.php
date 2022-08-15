
<?php
/*
* author: bibir (logd_bibir@email.de)
*      and Chaosmaker (webmaster@chaosonline.de)
*      for http://logd.chaosonline.de
*
* version: 1.2
*
*     a library with text from users to help other
*            a bit like faq
*
* details:
*  (15.11.04) start of idea
*  (15.01.05) project finished
*  (16.01.05) version 1.2: several minor bugfixes
*  (11.11.05) Talion: added feature for recommending books that are well done.
*  (10.07.06) Talion: newest contributions are shown in an extra section.
*/

require_once "common.php";

function lib_show_rules () {
        
        output('
        Jeder Spieler kann eigene Werke einreichen, die dann von den Bibliothekaren (Auf diesem Server: Die Moderatoren) begutachtet und entweder zugelassen oder abgelehnt werden können. `n
        Damit ein Buch durchgelassen werden kann, sollte es folgende Regeln und Richtlinien einhalten:`n
        `n- Angemessene Textlänge: Je nach Textart (Gedicht, Geschichte, Einführung in diese Welt etc.) sind unterschiedliche Textlängen erforderlich. Gedichte und Lieder sollten dabei eine Länge von 16 Zeilen nicht unterschreiten, wobei eine Zeile nicht nur aus 5 Wörtern bestehen sollte. Alle anderen Texte können von der Länge her variieren. Bei Einführungen in diese Welt sind auch kürzere Texte in Ordnung, wenn das Thema nicht mehr hergibt.  Allgemein sollte jedoch darauf geachtet werden, die Texte möglichst weit auszuführen. Geschichten der Art: Ich ging in den Wald, schlachtete viele Monster ab, verlor einen Freund und ging nach Hause werden nicht durchgelassen. Ist der Verlauf der Geschichte jedoch einigermaßen ausführlich geschildert, wird es durchgelassen, sofern die anderen Regeln eingehalten werden.
        `n`n- Rechtschreibung: Bitte prüft eure Werke hierauf, ehe ihr sie einreicht! Fehler kommen vor und werden gerne von den Bibliothekaren behoben, allerdings nur in Maßen. Sie sind nicht dazu da, eure kompletten Texte Korrektur zu lesen. Texte, die vor Fehlern sprießen (Buchstabierung, Zeichensetzung), werden, ohne weiter gelesen zu werden, gelöscht. 
        `n`n- Themen: Ihr könnt natürlich über alles schreiben, was euch einfällt. Vorzugsweise sollte es jedoch mit diesem Spiel oder zumindest dieser Welt zu tun haben. Das heißt: Wenn ihr ein Buch über euren Kampf mit dem grünen Drachen schreiben möchtet, oder andere Fantasiewesen, tut das. Bücher hingegen über die 50 edelsten Kaffeesorten von heute etc. behaltet lieber für euch. Des Weiteren reicht bitte keine Werke über die Rassen ein, in denen ihr schreibt, dass sie sich nur auf die eine Weise verhalten oder es nur einen wahren Ursprung gäbe. Die Rassen auf diesem Server werden von jedem anders gespielt, da jeder ein anderes Hintergrundwissen zu ihnen hat. Wollt ihr also, über Ursprung etc. einer oder mehrerer Rassen schreiben, beginnt euer Buch z.B. mit Eine Theorie besagt, dass. So tretet ihr niemandem auf die Füße und alle können sich an eurem Buch erfreuen. 
        `n`n- Qualität: Hier gibt es keine Richtlinien. Wenn die Bibliothekare ein Buch erhalten, von dem sie denken, dass es keiner lesen und es nur auf dem Server vermodern wird, werden sie es nicht durchlassen. Bemüht euch, gerade bei Erzählungen, spannend und gut leserlich zu schreiben. 
        `n`n- Zuletzt noch die wichtigste Regel: Sendet keine Bücher ein, die nicht von euch selbst sind! Texte, die ihr kopiert habt (Jeder Text wird überprüft!), werden sofort gelöscht, da diese urheberrechtlich geschützt sind und die Betreiber sich strafbar machen würden, wenn sie diese veröffentlichen würden. Achtet also hierauf besonders!        
        ');
        
}


checkday();
addcommentary();

// Themen-ID für Fragen und Anworten-Bereich
define('THEMEID_FUNDA',10);
// Dauer der Editiersperre bei FundA-Beiträgen
define('FUNDA_EDITIERSPERRE_DAUER',getsetting('funda_editiersperre_dauer',5));   # Angabe in Minuten
// end

if(!isset($_GET['op'])) $_GET['op']="";

addnav('Bibliothek');


$sql = "SELECT count(bookid) AS anz FROM lib_books WHERE activated='1'";
$result = db_query($sql) or die(db_error(LINK));
$books = db_fetch_assoc($result);
page_header("Drachenbibliothek");
output("`c`b`9Drachenbibliothek des gesammelten Wissens in ".($books['anz']==1?'einem Band':$books['anz'].' Bänden')."`0`b`c`n");

switch($_GET['op']){
case "browse":
        addnav("H?Zurück in die Halle","library.php");
        if(!$session['user']['imprisoned']) {
                addnav("Buch einreichen","library.php?op=offer");
        }
        output("`tDu ".(!$session['user']['alive'] ? 'schwebst' : 'gehst')." durch die Regalreihen und siehst, dass alle Bücher ordentlich nach Themen einsortiert sind.`n
        Folgende Themen stehen derzeit zur Auswahl:`n`n");
        $sql = "SELECT t.*, COUNT(b.bookid) as anz FROM lib_themes t
                        LEFT JOIN lib_books b ON b.themeid=t.themeid AND b.activated='1'
                        GROUP BY themeid
                        ORDER BY listorder ASC";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td style='padding: 6px;'>Thema</td><td style='padding: 6px;'>Bücher</td></tr>",true);
        $bgclass = '';
        addnav("Themen");
        while ($row = db_fetch_assoc($result)) {
                // F&A-Kategorie läuft extra:
                if($row['themeid'] == THEMEID_FUNDA) {
                    $str_funda = "<tr class='trdark'><td style='padding: 6px;'><a href=\"library.php?op=funda\">".$row['theme']."`0</a></td><td style='text-align: center; padding: 6px;'>".$row['anz']."</td></tr>";
                    $str_funda_theme = $row['theme'];
                    addnav('','library.php?op=funda');
                // end
                } else {
                    $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                    if ($row['anz']>0) {
                            output("<tr class='{$bgclass}'><td style='padding: 6px;'><a href=\"library.php?op=theme&id=".$row['themeid']."\">".$row['theme']."`0</a></td><td style='text-align: center; padding: 6px;'>".$row['anz']."</td></tr>");
                    }
                    else {
                            output("<tr class='{$bgclass}'><td style='padding: 6px;'>".$row['theme']."`0</td><td style='text-align: center; padding: 6px;'>kein Buch</td></tr>",true);
                    }
                    addnav($row['theme'],"library.php?op=theme&id=".$row['themeid']);
                }
                addnav("","library.php?op=theme&id=".$row['themeid']);
        }
        output("</table>",true);
        if(isset($str_funda)) {
                output("`n`n<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td style='padding: 6px;'>Thema</td><td style='padding: 6px;'>Bücher</td></tr>".$str_funda."</table>");
                addnav('Wiki');
                addnav($str_funda_theme,"library.php?op=funda");
        }
        break;

case "theme":
        addnav("H?Zurück in die Halle","library.php");
        addnav("Buch einreichen","library.php?op=offer");

        addnav("Themen");
        $sql = "SELECT themeid, theme, description FROM lib_themes ORDER BY listorder ASC";
        $result = db_query($sql) or die(db_error(LINK));
        while ($row = db_fetch_assoc($result)) {
                if($row['themeid'] == THEMEID_FUNDA) {
                    $str_funda_theme = $row['theme'];
                    continue;
                }
                if ($row['themeid']!=$_GET['id']) {
                        addnav($row['theme'],"library.php?op=theme&id=".$row['themeid']);
                }
                else {
                        addnav($row['theme'],'');
                        $thistheme = $row['theme'];
                        $thisdescription=$row['description'];
                }
        }
        if(isset($str_funda_theme)) {
            addnav('Wiki');
            addnav($str_funda_theme,'library.php?op=funda');
            $thistheme = $row['theme'];
            $thisdescription=$row['description'];
        }

        output("`c`b".$thistheme."`0`b`c");
        output("`n`c".$thisdescription."`0`c");
        output("`n`6Zu diesem Thema stehen dir folgende Bücher zur Verfügung:`n`n");

        $sql = "SELECT title, bookid, author, recommended FROM lib_books
                        WHERE themeid=".$_GET['id']." AND activated='1' ORDER BY listorder ASC";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td style='padding: 6px;'>Titel</td><td style='padding: 6px;'>Autor</td><td style='padding: 6px;'>Empfehlenswert?</td></tr>",true);
        if (db_num_rows($result)==0) {
                output("<tr class='trdark'><td colspan='3' style='padding: 6px;'>Es gibt leider bisher noch keine Bücher zu diesem Thema.</td></tr>",true);
        }
        else {
                addnav('Bücher');
                $bgclass = '';
                while ($row = db_fetch_assoc($result)) {
                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                        output("<tr class='$bgclass'><td style='padding: 6px;'><a href=\"library.php?op=book&bookid=".$row['bookid']."\">",true);
                        output($row['title']);
                        output("`0</a></td><td style='padding: 6px;'>",true);
                        output($row['author']);
                        output("`0</a></td><td  style='text-align: center; padding: 6px;'>",true);
                        output( ($row['recommended'] ? 'Ja' : ' - ') );
                        output("`0</td></tr>",true);
                        addnav("","library.php?op=book&bookid=".$row['bookid']);
                        addnav($row['title'],'library.php?op=book&bookid='.$row['bookid'],false,false,false,false);
                }
        }
        output("</table>",true);
        break;

case "funda":
        addnav("H?Zurück in die Halle","library.php");
        addnav("B?Anderen Bereich aufsuchen","library.php?op=browse");
        addnav("Eintrag verfassen","library.php?op=offer&what=funda");

        $sql = db_query("SELECT theme, description FROM lib_themes WHERE themeid = ".THEMEID_FUNDA." ORDER BY listorder ASC");
        $row = db_fetch_assoc($sql);
        addnav('Wiki');
        addnav($row['theme'].'`0 - Startseite','library.php?op=funda');

        output("`c`b".$row['theme']."`0`b`c
                `n".$row['description']."`0`n
                `n
                <a href='library.php?op=offer&what=funda' class='button' style='background-color: #002f00; border-top: 1px solid #004f00; border-left: 1px solid #004f00; border-right: 1px solid #001f00; border-bottom: 1px solid #001f00;'>Eintrag verfassen</a>`n
                `n
                `n
                <form action='library.php?op=funda&act=search' method='post'>
                <table style='border: none;'>
                <tr><td>`sStichwortsuche (mind. 3 Zeichen): </td><td style='max-width: 50px;'><input name='funda_search' class='input'></td><td> <input type='submit' class='button' value='Suchen'></td></tr>
                <tr><td style='vertical-align: top;'>`sWas soll durchsucht werden?</td><td colspan='2'><input type='radio' name='funda_field' value='both'".(!isset($_POST['funda_field']) || $_POST['funda_field'] == 'both' ? " checked": "").">Fragen und Antworten <input type='radio' name='funda_field' value='title'".($_POST['funda_field'] == 'title' ? " checked": "").">Nur Fragen <input type='radio' name='funda_field' value='book'".($_POST['funda_field'] == 'book' ? " checked": "").">Nur Antworten</td></tr>
                </table>
                </form>
                `n
                `FZu diesem Thema/Stichwort stehen dir folgende Bücher zur Verfügung:`n
                `n");
        addnav('','library.php?op=funda&act=search');
        addnav('','library.php?op=offer&what=funda');
        // Wurde ein Suchbegriff eingegeben? Begriff zählt erst ab 3 Zeichen
        if($_GET['act'] == 'search' && strlen($_POST['funda_search']) > 2) {
                if($_POST['funda_field'] == 'title' || $_POST['funda_field'] == 'book') {
                    $str_where = " AND ".$_POST['funda_field']." LIKE '".str_create_search_string($_POST['funda_search'])."'";
                } else {
                    $str_where = " AND (title LIKE '".str_create_search_string($_POST['funda_search'])."' OR book LIKE '".str_create_search_string($_POST['funda_search'])."')";
                }
        } else {
                $str_where = "";
        }
        // end
        $sql = "SELECT title, bookid, author, recommended, listorder FROM lib_books
                        WHERE themeid=".THEMEID_FUNDA." AND activated='1'".$str_where." ORDER BY listorder ASC";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td style='padding: 6px;'>Frage</td><td style='padding: 6px;'>Autor</td><td style='padding: 6px;'>Empfehlenswert?</td>".(su_check(SU_RIGHT_EDITORLIBRARY) ? "<td style='padding: 6px;'>SU: Aktion</td>" : "")."</tr>",true);
        if (db_num_rows($result)==0) {
                output("<tr class='trdark'><td colspan='4' style='padding: 6px;'>Es gibt leider bisher noch keine Bücher zu diesem Thema.</td></tr>",true);
        }
        else {
                addnav('Einträge');
                $bgclass = '';
                while ($row = db_fetch_assoc($result)) {
                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                        output("<tr class='$bgclass'><td style='padding: 6px;'><a href=\"library.php?op=book&bookid=".$row['bookid']."\">".($row['listorder'] == 0 ? "<img src='images/star.png' alt='star'> " : "").$row['title']."`0</a></td>
                                 <td style='padding: 6px;'>".$row['author']."`0</a></td>
                                 <td  style='text-align: center; padding: 6px;'>".($row['recommended'] ? 'Ja' : ' - ')."`0</td>
                                 ".(su_check(SU_RIGHT_EDITORLIBRARY) ? ($row['listorder'] > 0 ? "<td style='padding: 6px;'><a href='library.php?op=funda_markas&what=important&bookid=".$row['bookid']."'>`&[`FAls Wichtig markieren`&]</a></td>" : "<td style='padding: 6px;'><a href='library.php?op=funda_markas&what=regular&bookid=".$row['bookid']."'>`&[`CMarkierung aufheben`&]</a></td>") : "")."
                                </tr>",true);
                        addnav("","library.php?op=book&bookid=".$row['bookid']);
                        addnav("","library.php?op=funda_markas&what=important&bookid=".$row['bookid']);
                        addnav("","library.php?op=funda_markas&what=regular&bookid=".$row['bookid']);
                        addnav($row['title'],'library.php?op=book&bookid='.$row['bookid'],false,false,false,false);
                }
        }
        output("</table>",true);
        if(su_check(SU_RIGHT_EDITORLIBRARY)) {
            addnav('SU');
            addnav('F&A-Log','su_logs.php?type=fundalog');
        }
        break;

case 'funda_edit':
        // Ohne bookid geht nichts:
        if(!isset($_GET['bookid']) || empty($_GET['bookid'])) {
            redirect('library.php?op=funda');
        }
        // end
        // Bei Editier-Abbruch: Editiersperre aufheben
        if(isset($_GET['out'])) {
            db_query("UPDATE lib_books SET lastclick = '0000-00-00 00:00:00' WHERE bookid = ".$_GET['bookid']." AND themeid = ".THEMEID_FUNDA);
            if($_GET['out'] == 'showbook') {
                redirect('library.php?op=book&bookid='.$_GET['bookid']);
            } else {
                redirect('library.php');
            }
        }
        // end
        $sql = db_query("SELECT title,book,edited,theme,description,lastclick FROM lib_books LEFT JOIN lib_themes USING(themeid) WHERE bookid = ".$_GET['bookid']." AND themeid = ".THEMEID_FUNDA." LIMIT 1");
        $rowb = db_fetch_assoc($sql);
        $date_lastclick = $rowb['lastclick'];
        // * WICHTIG! Editierung darf nur vorgenommen werden, wenn der Eintrag freigegeben ist!
        if($_GET['act'] != 'save' && strtotime($date_lastclick) > strtotime("-".FUNDA_EDITIERSPERRE_DAUER." minutes")) {   # wenn letzte Änderung weniger als X min her ist -> Abbruch
            exit;
        } else {    # sonst derzeitigen Zeitstempel ins DB-Feld eintragen
            db_query("UPDATE lib_books SET lastclick = NOW() WHERE bookid = ".$_GET['bookid']." AND themeid = ".THEMEID_FUNDA);
            $date_lastclick = date('Y-m-d H:i:s',strtotime('now'));
        }
        addnav('B?Zurück zur Buchanzeige','library.php?op=funda_edit&out=showbook&bookid='.$_GET['bookid']);
        addnav("H?Zurück in die Halle",'library.php?op=funda_edit&out=showhall&bookid='.$_GET['bookid']);
        // end Freigabe Editierung *
        if($_GET['act'] == 'save') {
            // Buchtext filtern & vorbereiten:
            $str_book = htmlspecialchars(encode_specialchars(strip_tags(closetags($_POST['book'],'`b`c`i'),'<table><tr><td><p><hr><ol><ul><li>')));
            // Buchtitel filtern & vorbereiten:
            $str_title = encode_specialchars(strip_tags(closetags($_POST['title'],'`i`c`b')));
            // Char mit ID, Login & Datum ergänzen:
            $arr_edited = unserialize($rowb['edited']);
            if(!$arr_edited || !is_array($arr_edited)) {   // falls noch niemand vorher editiert hat: neues Array anlegen
                $arr_edited = array(0=>array('id'=>(int)$session['user']['acctid'],'login'=>$session['user']['login'],'timestamp'=>time()));
            } else {
                $int_c = count($arr_edited);
                $arr_edited = array_merge($arr_edited,array($int_c=>array('id'=>(int)$session['user']['acctid'],'login'=>$session['user']['login'],'timestamp'=>time())));
            }
            $str_edited = serialize($arr_edited);
            db_query("UPDATE lib_books SET title = '".$str_title."', book = '".$str_book."', edited = '".$str_edited."', lastclick = '0000-00-00 00:00:00' WHERE bookid = ".$_GET['bookid']." AND themeid = ".THEMEID_FUNDA." LIMIT 1");
            // Änderungen mitloggen:                                                                                     # ^-- Editiersperre zurücksetzen #
            db_query("INSERT INTO lib_funda_log (date,actor,bookid,message) VALUES (NOW(),".$session['user']['acctid'].",".$_GET['bookid'].",'`b`3Änderung`b`n`n`KTitel: `&".$str_title."`n`n`KInhalt:`n".addslashes(compare_strings($rowb['book'],$str_book))."')");
            redirect('library.php?op=book&bookid='.$_GET['bookid']);
        } else {
            output('`c<div id="timer" style="display: inline; color: #F8DB83;">`tDieser Beitrag ist noch '.FUNDA_EDITIERSPERRE_DAUER.' Minuten für Editierungen durch andere gesperrt.</div>`c`n`n');
            // ~ Für Editiersperre - JS-countdown einfügen
            output('<script type="text/javascript" language="javascript">
                    function countdown(minutes) {
                        var seconds = 60;
                        var mins = minutes;
                        function tick() {
                            var counter = document.getElementById("timer");
                            var current_minutes = mins-1
                            seconds--;
                            counter.innerHTML = "Dieser Beitrag ist noch " + current_minutes.toString() + ":" + (seconds < 10 ? "0" : "") + String(seconds) + " Minuten für Editierungen durch andere gesperrt.";
                            if( seconds > 0 ) {
                                setTimeout(tick, 1000);
                            } else {
                                if(mins > 1){
                                   // countdown(mins-1);   never reach 00? issue solved:Contributed by Victor Streithorst
                                   setTimeout(function () { countdown(mins - 1); }, 1000);
                                } else {
                                   counter.innerHTML = "<b style=\'color: red;\'>Achtung!</b> Dieser Beitrag kann wieder von anderen editiert werden.";
                                }
                            }
                        }
                        tick();
                    }
                    countdown('.FUNDA_EDITIERSPERRE_DAUER.');
                    </script>',true);
            // end JS-countdown ~
            output('<form action="library.php?op=funda_edit&act=save&bookid='.$_GET['bookid'].'" method="post">');
            $form = array('F&A-Buch editieren,divider'
                         ,'title'=>'Frage:'
                         ,'book'=>'Antwort:,textarea,60,20');
            $prefs = array('title'=>$rowb['title'],'book'=>$rowb['book']);
            showform($form,$prefs,false,'Abschicken');
            output('</form>`n
                    `b`tBuchvorschau:`b`n
                    `n
                    `tFrage: `&'.js_preview('title').'`n
                    `n
                    `tAntwort:`n
                    `&'.js_preview('book'));
            addnav('','library.php?op=funda_edit&act=save&bookid='.$_GET['bookid']);
        }
        break;
        
case 'funda_markas':
        $int_what = ($_GET['what'] == 'important' ? 0 : 1);
        db_query("UPDATE lib_books SET listorder = ".$int_what." WHERE bookid = ".$_GET['bookid']);
        redirect('library.php?op=funda');
        break;
        
case 'recommended':
        addnav("H?Zurück in die Halle","library.php");
        
        output('`n`6Die folgenden Bücher werden von den Göttern als besonders empfehlenswert angesehen. Es lohnt sich also bestimmt, hier einen Blick hineinzuwerfen:`n`n');
        
        $sql = 'SELECT b.*,t.theme FROM lib_books b
                        LEFT JOIN lib_themes t ON t.themeid=b.themeid 
                        WHERE recommended = 1 AND activated = "1"
                        ORDER BY t.themeid ASC, t.listorder DESC, b.listorder DESC';
        
        $result = db_query($sql) or die(db_error(LINK));
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td style='padding: 6px;'>Titel</td><td style='padding: 6px;'>Autor</td></tr>",true);
        if (db_num_rows($result)==0) {
                output("<tr class='trdark'><td colspan='2' style='padding: 6px;'>Es gibt leider bisher noch keine empfohlenen Bücher.</td></tr>",true);
        }
        else {
                addnav('Bücher');
                $bgclass = '';
                $last_theme = 0;
                while ($row = db_fetch_assoc($result)) {
                        
                        if($last_theme != $row['themeid']) {
                                output('<tr class="trhead"><td colspan="2" style="padding: 6px;">`b'.$row['theme'].'`b</td></tr>',true);
                                $last_theme = $row['themeid'];
                        }        
                        
                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                        output("<tr class='$bgclass'><td style='padding: 6px;'><a href=\"library.php?op=book&bookid=".$row['bookid']."\">",true);
                        output($row['title']);
                        output("`0</a></td><td style='padding: 6px;'>",true);
                        output($row['author']);
                        output("`0</td></tr>",true);
                        addnav("","library.php?op=book&bookid=".$row['bookid']);
                        addnav($row['title'],'library.php?op=book&bookid='.$row['bookid'],false,false,false,false);
                }
        }
        output("</table>",true);
        
        break;
        
case 'new':
        
        addnav("H?Zurück in die Halle","library.php");
        
        output('`n`6Diese Bücher wurden erst vor kurzem eingereicht, wie du an den fast unverbrauchten Einbänden erkennen kannst:`n`n');
        
        $sql = 'SELECT b.*,t.theme FROM lib_books b
                        LEFT JOIN lib_themes t ON t.themeid=b.themeid 
                        WHERE activated = "1" AND b.themeid != 999
                        ORDER BY b.bookid DESC, t.themeid ASC, t.listorder DESC, b.listorder DESC
                        LIMIT 10';
        
        $result = db_query($sql) or die(db_error(LINK));
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td style='padding: 6px;'>Titel</td><td style='padding: 6px;'>Autor</td><td style='padding: 6px;'>Thema</td></tr>",true);
        if (db_num_rows($result)==0) {
                output("<tr class='trdark'><td colspan='3' style='padding: 6px;'>Es gibt leider bisher noch keine Bücher.</td></tr>",true);
        }
        else {
                addnav('Bücher');
                $bgclass = '';
                while ($row = db_fetch_assoc($result)) {
                                                                                                
                        $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
                        output("<tr class='$bgclass'><td style='padding: 6px;'><a href=\"library.php?op=book&bookid=".$row['bookid']."\">",true);
                        output($row['title']);
                        output("`0</a></td><td style='padding: 6px;'>",true);
                        output($row['author']);
                        output('`0</td><td style="padding: 6px;">'.$row['theme'],true);
                        output("`0</td></tr>",true);
                        addnav("","library.php?op=book&bookid=".$row['bookid']);
                        addnav($row['title'],'library.php?op=book&bookid='.$row['bookid'],false,false,false,false);
                }
        }
        output("</table>",true);
        
        break;

case "book":
        addnav("H?Zurück in die Halle","library.php");
        //addnav("Ein anderes Thema","library.php?op=browse");

        $sql = "SELECT t.theme, b.themeid, b.title, b.book, b.author, b.bookid, b.edited FROM lib_books b
                        LEFT JOIN lib_themes t USING(themeid)
                        WHERE bookid=".$_GET['bookid'];
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);

        //addnav("R?Zurück ans Regal","library.php?op=theme&id=".$row['themeid']);
        if($row['themeid'] == THEMEID_FUNDA) {
            addnav("Eintrag verfassen","library.php?op=offer&what=funda");
        } else {
            addnav("Buch einreichen","library.php?op=offer");
        }
        addnav("Empfehlenswerte Lektüre","library.php?op=recommended");
        addnav("Neueste Werke","library.php?op=new");

        $sql = "SELECT themeid, theme FROM lib_themes ORDER BY listorder ASC";
        $result = db_query($sql) or die(db_error(LINK));
        addnav("Themen");
        while ($row2 = db_fetch_assoc($result)) {
                // F&A-Kategorie läuft extra:
                if($row2['themeid'] == THEMEID_FUNDA) {
                    $str_funda_theme = $row2['theme'];
                // end
                } else {
                    addnav($row2['theme'],"library.php?op=theme&id=".$row2['themeid']);
                }
        }
        if(isset($str_funda_theme)) {
                addnav('Wiki');
                addnav($str_funda_theme,"library.php?op=funda");
        }
        addnav('Bücher');
        $sql = 'SELECT title, bookid FROM lib_books WHERE themeid='.$row['themeid'].' AND activated="1" ORDER BY listorder ASC';
        $result = db_query($sql) or die(db_error(LINK));
        while ($row2 = db_fetch_assoc($result)) {
                if ($row2['bookid']==$_GET['bookid']) {
                    addnav($row2['title'],'');
                } else {
                    addnav($row2['title'],'library.php?op=book&bookid='.$row2['bookid'],false,false,false,false);
                }
        }

        //nichts editierbar
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'>
                <tr class='trlight'><td style='padding: 6px;'>`YThema:</td><td style='padding: 6px;'>`&".$row['theme']."`0</td></tr>
                <tr class='trdark'><td style='padding: 6px;'>`YTitel:</td><td style='padding: 6px;'>`&".str_replace("'","&#39;",$row['title'])."`0</td></tr>
                <tr class='trlight'><td style='padding: 6px;'>`YAutor:</td><td style='padding: 6px;'>`&".$row['author']."`0</td></tr>
                <tr class='trdark'><td colspan='2' style='text-align: justify; padding: 6px;'>`&".str_replace(array("&#39;","&amp;","\n"),array("'","&","`n"),$row['book'])."</td></tr>");
        // Bei F&A: anzeigen, ob Buch editiert wurde
        if($row['themeid'] == THEMEID_FUNDA) {
            $arr_edited = unserialize($row['edited']);
            // Wenn das Buch bereits editiert wurde:
            if(is_array($arr_edited)) {
                // Ermitteln, wer zuletzt editiert hat:
                $int_c = count($arr_edited);
                output("<tr class='trdark'><td colspan='2'>`YZuletzt editiert von: ".$arr_edited[($int_c-1)]['login']." (".date('d.m.Y, H:i',$arr_edited[($int_c-1)]['timestamp']).")");
                // Für SU: Weitere Editierungen anzeigen (Reihenfolge: timestamp DESC)
                if(su_check(SU_RIGHT_EDITORLIBRARY) && $int_c > 1) {
                    $str_edited = "";
                    for($i=0;$i<$int_c-1;$i++) {
                        $str_edited = "&times; ".date('d.m.Y, H:i',$arr_edited[$i]['timestamp'])." - ".$arr_edited[$i]['login']."`n".$str_edited;
                    }
                    output("<script type='text/javascript' language='javascript' src='templates/plumi.js'></script>
                            `n`n<a href='#'".set_plumi_onclick('more_edited').">".set_plumi_img('more_edited')." `XWeitere Editierungen anzeigen (SU):</a>`n
                            <div id='more_edited' style='display: none; line-height: 250%;'>`f".$str_edited."</div>");
                }
                // end
                output("`n</td></tr>");
            }
        }
        // end
        output("</table>");
        if($row['themeid'] == THEMEID_FUNDA) {
            $sql = db_query("SELECT title,book,edited,theme,description,lastclick FROM lib_books LEFT JOIN lib_themes USING(themeid) WHERE bookid = ".$row['bookid']." AND themeid = ".THEMEID_FUNDA." LIMIT 1");
            $rowb = db_fetch_assoc($sql);
            $date_lastclick = $rowb['lastclick'];
            // * WICHTIG! Editierung darf nur vorgenommen werden, wenn der Eintrag freigegeben ist!
            if($_GET['act'] != 'save' && strtotime($date_lastclick) > strtotime("-".FUNDA_EDITIERSPERRE_DAUER." minutes"))   # wenn letzte Änderung weniger als X min her ist -> Abbruch
            {
                $int_min_till_edit = ((FUNDA_EDITIERSPERRE_DAUER+1) - ceil((strtotime('now')-strtotime($date_lastclick))/60));
                output('<br />`b`CAchtung!`b `^Dieser Eintrag wird gerade von einem anderen User editiert. Bitte warte noch
                   `Q'.($int_min_till_edit == 1 ? 'eine Minute' : $int_min_till_edit.' Minuten').'`^, ehe du erneut versuchst zu editieren.');
            }
            else
                output("`n<a href='library.php?op=funda_edit&bookid=".$row['bookid']."' class='button'>Buch editieren</a>`n`n");
            addnav('','library.php?op=funda_edit&bookid='.$row['bookid']);
            if(su_check(SU_RIGHT_EDITORLIBRARY)) {
                addnav('SU');
                addnav('F&A-Log zu diesem Buch','su_logs.php?op=search&type=fundalog&target_id='.$row['bookid']);
            }
        }
        break;

case "offer":
        addnav("H?Zurück in die Halle","library.php");
        if ($_GET['subop']=="save" && strlen(trim($_POST['title'])) >= 5 && strlen(trim($_POST['book'])) >= 15) {
                $title = encode_specialchars(strip_tags(closetags($_POST['title'],'`b`c`i')));
                $book = htmlspecialchars(encode_specialchars(strip_tags(closetags($_POST['book'],'`b`c`i'),'<table><tr><td><p><hr><ol><ul><li>')));
                addnav("Weiteres Buch schreiben","library.php?op=offer");
                output("`tDein Buch wurde zum Druck eingereicht.`0");
                // maximale sortiernummer holen
                $sql = 'SELECT MAX(listorder) AS maxorder FROM lib_books';
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                // Buch-Daten als array - Vorbereitung für Speicherung in DB
                $arr_book = array('themeid'=>$_POST['themeid']
                                 ,'acctid'=>$session['user']['acctid']
                                 ,'author'=>$session['user']['name']
                                 ,'title'=>$title
                                 ,'book'=>$book
                                 ,'listorder'=>max($row['maxorder'],1)
                                 );
                // Ist das Buch für den F&A-Bereich? Dann vorneweg freischalten:
                if($_POST['themeid'] == THEMEID_FUNDA) {
                    $arr_book['activated'] = 1;
                }
                
                db_insert('lib_books',
                                $arr_book);

                // Außerdem: Originaltext mitloggen
                if($_POST['themeid'] == THEMEID_FUNDA) {
                    $int_bookid = db_insert_id();
                    db_query("INSERT INTO lib_funda_log (date,actor,bookid,message)
                                          VALUES (NOW(),".$session['user']['acctid'].",".$int_bookid.",'`b`GNeuer F&A-Eintrag`b`n`n`2Titel: `&".$title."`n`n`2Inhalt:`n`&".addslashes($book)."')");
                }
                
        }
        else {
                if ($_GET['subop']=='save') {
                        output('`c`$Fehler! `tEntweder beträgt die Länge deines Titels unter 5 Zeichen oder die Länge deines Buchs unter 15 Zeichen. Das reicht nicht
                                für eine Veröffentlichung, es wäre schade ums Papier, hm?`0`c`n`n');
                        $_POST['title'] = str_replace('`','``',$_POST['title']);
                        $_POST['book'] = str_replace('`','``',$_POST['book']);
                }
                else {
                        $_POST['title'] = '';
                        $_POST['book'] = '';
                        $_POST['themeid'] = ($_GET['what'] == 'funda' ? THEMEID_FUNDA : '');
                        
                }
                output("`tHier hast du die Möglichkeit, eigenes Wissen niederzuschreiben und anderen damit zur Verfügung zu stellen. Nun liegt es an dir, die Zeilen auf das 
                                        Pergament zu bringen, die du dein Wissen nennst.`n
                                                `n
                                                `7`i(Info: HTML wird größtenteils herausgefiltert; lediglich &#060;table>, &#060;tr>, &#060;td>, &#060;hr> und &#060;p> sind erlaubt.)`i`0`n`n");
                output("<script type='text/javascript' language='javascript'>
                        function change_td(e) {
                            var s = e.options[e.selectedIndex].value;
                            if(s == '".THEMEID_FUNDA."') {
                                document.getElementById('t').innerHTML = '`^Frage:';
                                document.getElementById('c').innerHTML = '`^Antwort:';
                                document.getElementById('t2').innerHTML = '`tFrage:';
                                document.getElementById('c2').innerHTML = '<br>`tAntwort:';
                            } else {
                                document.getElementById('t').innerHTML = '`YTitel:';
                                document.getElementById('c').innerHTML = '`YMein Wissen über dieses Thema:';
                                document.getElementById('t2').innerHTML = '`tTitel:';
                                document.getElementById('c2').innerHTML = '<br>`tInhalt:';
                            }
                        }
                        </script>
                        <form action=\"library.php?op=offer&subop=save\" method='POST' id='formselect'>
                        <table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trdark'><td>`YThema:</td><td><select id='themeid' name='themeid' onChange='change_td(this);'>",true);
                $sql2 = "SELECT * FROM lib_themes ORDER BY listorder ASC";
                $result2 = db_query($sql2) or die(db_error(LINK));
                while ($row2 = db_fetch_assoc($result2)) {
                        output("<option value='".$row2['themeid']."' ".($row2['themeid']==$_POST['themeid']?" selected='selected'":"").">".preg_replace('/`./','',$row2['theme'])."</option>",true);
                }
                output("</select></td></tr>",true);
                output("<tr class='trlight'><td id='t'>`YTitel:</td><td><input class='input' type='text' name='title' value='{$_POST['title']}' maxlength='255' size='50'></td></tr>",true);
                output("<tr class='trdark'><td id='c' colspan='2'>`YMein Wissen über dieses Thema:</td></tr>",true);
                output("<tr class='trdark'><td colspan='2'><textarea name='book' class='input' cols='60' rows='10'>{$_POST['book']}</textarea></td></tr>",true);
                output("<tr class='trlight'><td colspan='2'><input type='submit' class='button' value='Einreichen'></td></tr></table></form>`n`n",true);
                addnav("","library.php?op=offer&subop=save");
                output('`t`bBuchvorschau:`b`n`n
                        <table style="border: none;">
                        <tr><td id="t2" style="width: 20px;">`tTitel:</td><td style="min-width: 400px;">`&'.js_preview('title').'</td></tr>
                        <tr><td id="c2" colspan="2">`n`tInhalt:</td></tr>
                        <tr><td colspan="2">`&'.js_preview('book',true).'</td></tr>
                        </table>');
                if($_POST['themeid'] == THEMEID_FUNDA && !isset($bool_done)) {
                    output("<script language='javascript' type='text/javascript'>
                                document.getElementById('t').innerHTML = '`^Frage:';
                                document.getElementById('c').innerHTML = '`^Antwort:';
                                document.getElementById('t2').innerHTML = '`tFrage:';
                                document.getElementById('c2').innerHTML = '<br>`tAntwort:';
                            </script>");
                    $bool_done = true;
                }
        }
        break;

case 'rules':
        
        output('`tAm Schalter der Bibliothekare hängt ein Pergament. Bei näherer Betrachtung kannst du die (dir aufgrund der manchmal etwas seltsamen Sprache leicht unverständlichen) Regeln der Götter lesen, welche für das Einreichen neuer Bücher gelten:`n`n`q');
        
        lib_show_rules();
        
        addnav('Zurück zur Halle','library.php');
        
        break;
        
case 'rp_train':
        
        output('`tAm Rande des großen Lesesaals verbirgt eine schmale Tür dieses Gemach, welches dir nun offensteht. 
                        Es ähnelt im Aufbau der Halle, obgleich es natürlich wesentlich kleiner und schmäler ist. Ein großes, in Stein
                        gemeißeltes Schild über der Eingangstür verkündet:`n
                        `c`TRaum des Lernens.`c`n`tHier kannst du nach Herzenslust das Sprechen in der Öffentlichkeit üben, Gelerntes anwenden und
                        Tricks ausprobieren:`n`n');
        
        viewcommentary('rp_train','Etwas sagen:',30);
        
        addnav('Zurück zur Halle','library.php');
        
        break;
        
default:
        output('`tAm Eingang zur Bibliothek hängt ein Plakat. Du liest:`n`n');
        output('`qDie Bibliothek ist ein Ort des Wissens.`n
           Dieses Wissen kann aber nur gehalten werden, wenn jemand es niedergeschrieben hat.`n
           Dazu steht in dieser Bibliothek die Möglichkeit bereit, Texte zu verfassen und diese einzureichen.`n
           Nach Genehmigung durch Regenten oder Bevollmächtigte wird das Buch gedruckt und in die Regale der Bücherei gestellt.`n
           Von nun an hat jeder die Möglichkeit, einen Blick in dieses Buch zu werfen und sowohl interessante als auch nützliche Informationen zu bekommen.`n
           Sollte das geschriebene Buch gedruckt werden, erhält der Autor ein Dankeschön in Form von `bbis zu`b '.getsetting("libdp","5").' Punkten in J.C. Petersens Jägerhütte, je nach Qualität.`n`n');
        
        addnav("Stöbern","library.php?op=browse");
        addnav("`GEmpfohlene Lektüre`0","library.php?op=recommended");
        addnav("Neueste Werke","library.php?op=new");
        addnav('Raum des Lernens','library.php?op=rp_train');
                   
        if($session['user']['alive']) {
                                
                if(!$session['user']['imprisoned']) {
                        
                        output("`tDu betrittst den großen Raum mit den unzähligen Regalen, welche - wie passend! - allesamt mit Symbolen von aufgeschlagenen Büchern verziert worden sind.`n
                        Hier sind gedämpfte Unterhaltungen zu hören und in den vielen bequemen
                        Drachenleder-Sesseln sitzen eifrige Kämpfer, um zu lesen.`n
                        An ein paar Tischen kannst du hin und wieder auch sehr erfahrene Drachentöter finden,
                        die ihr Wissen in Büchern niederschreiben.");
                        
                        output("`n`nEin paar Leute unterhalten sich leise:`n`0");
                                        
                        viewcommentary("library","Leise flüstern:",25);
                }
                else {
                        output("`tDu betrittst die vom Kerker aus erreichbare Abteilung der Drachenbücherei und siehst dich um.");
                }
        }
        else {
                output('`tDein Geist schwebt zwischen den Regalen der Bibliothek. Nur undeutlich und schemenhaft kannst du die
                                Umrisse der Bücherborde ausmachen, die - wie passend! - mit Symbolen von aufgeschlagenen Büchern verziert sind.
                                Dennoch sollte es kein Problem für dich darstellen, das Wissen aufzunehmen.');
        }
        if(!$session['user']['imprisoned'])
        {
                addnav('Eigene Bücher');
                addnav("Buch einreichen","library.php?op=offer");
                addnav("Regeln","library.php?op=rules");
        }
        // Wiki bereits hier verlinken:
        $row = db_fetch_assoc(db_query("SELECT theme FROM lib_themes WHERE themeid = ".THEMEID_FUNDA." LIMIT 1"));
        addnav('Wiki');
        addnav($row['theme'],'library.php?op=funda');
        // end
        knappentraining_link('lib');
}

// Wegen der Editiersperre dürfen diese Links nicht angezeigt werden, wenn ein FundA-Eintrag editiert wird !
if($_GET['op'] != 'funda_edit') {
    addnav('Zurück');
    if($session['user']['alive']) {
            if($session['user']['imprisoned']) {
                    addnav('Zum Kerker','prison.php');
            }
            else {
                    addnav('Zum Stadtplatz','village.php');
                    addnav('Zum Marktplatz','market.php');
            }
    }
    else {
            addnav('Zu den Schatten','shades.php');
    }
}

page_footer();
?>

