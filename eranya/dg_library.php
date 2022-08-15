
<?php
require_once('common.php');
require_once(LIB_PATH.'dg_funcs.lib.php');
require_once('dg_output.php');

checkday();
page_header('Das Gildenviertel');

if($session['user']['guildid']) {
    $leader = ($session['user']['guildfunc'] == DG_FUNC_LEADER) ? true : false;
    $members = ($session['user']['guildfunc'] == DG_FUNC_LEADER || $session['user']['guildfunc'] == DG_FUNC_MEMBERS) ? true : false;
    $team = ($leader || $members) ? true : false;
    $member = ($session['user']['guildfunc'] != DG_FUNC_APPLICANT && $session['user']['guildfunc']) ? true : false;
    $applicant = ($session['user']['guildfunc'] == DG_FUNC_APPLICANT) ? true : false;
} elseif(!su_check(SU_RIGHT_EDITORGUILDS)) {
    output('`c`b`8Die gildeneigene Bibliothek`b`c`n
            `8Du stehst vor dem Eingang zur gildeneigenen Bibliothek, doch zwei Wachen versperren dir den Weg und geben dir zu verstehen, dass ab hier nur noch Gildenmitglieder Zutritt haben.');
    addnav('Zurück','dg_main.php');
    exit;
}
if(su_check(SU_RIGHT_EDITORGUILDS)) {$leader = true;}

// ID-Check
if($_GET['gid']) {
        $session['gid'] = (int)$_GET['gid'];
}
if(!$session['gid']) {
        $session['gid'] = $session['user']['guildid'];
}
$gid = $session['gid'];
if(!$gid) {redirect('dg_main.php');}
// END ID-Check

$op = ($_GET['op']) ? $_GET['op'] : '';
$out = '`c`b`8Die gildeneigene Bibliothek`b`c`n';
$bool_show_return_link = true;

switch($op) {
    // Übersicht Bücher
    case '':
        $bool_show_return_link = false;
        $out .= '`8Durch eine offen stehende Flügeltür trittst du ein in die hauseigene Bibliothek deiner Gilde. Sie ist untergebracht in einem quadratisch angelegten Raum mit hohen Decken,
                 welcher von bodentiefen Fenstern erhellt wird. Den Großteil des Inventars machen die Bücherregale aus: In festen Reihen aufgestellt, bilden sie breite Gassen, an deren
                 Eingängen jeweils ein Schild verrät, welche Autoren dort zu finden sind. Zu deiner Rechten stehen einige Tischgruppen bereit, damit man sich niederlassen und in Ruhe
                 lesen kann. Insbesondere in diesem Bereich hängen Öllampen in geringen Abständen zueinander an der Wand. Du kannst aber auch einen der Kerzenständer entzünden, sollte dir
                 das Licht zum Lesen nicht ausreichen.`n`n
                 Folgende Werke stehen für dich zum Nachschlagen bereit:`n
                 `n
                 <table cellpadding=2 cellspacing=1 bgcolor="#999999"><tr class="trhead"><td style="padding: 6px;">`bTitel`b</td><td style="padding: 6px;">`bAutor`b</td>'.($leader ? '<td style="padding: 6px;">Verwalten</td>' : '').'</tr>';
        $sql = db_query("SELECT * FROM lib_books WHERE".(!$leader ? " activated != '0' AND" : "")." themeid = 999 AND guildid = ".$gid." ORDER BY activated ASC, bookid DESC");
        $sql_c = db_num_rows($sql);
        if($sql_c == 0) {
            $out .= '<tr class="trdark"><td colspan="3" style="text-align: center;">`i`7Es wurden noch keine Werke veröffentlicht.`i</td></tr>';
        } else {
            for($i=0;$i<$sql_c;$i++) {
                $row_gb = db_fetch_assoc($sql);
                $out .= '<tr class="trdark">
                         <td style="padding: 6px;"><a href="dg_library.php?op=read&bookid='.$row_gb['bookid'].'">`&'.stripslashes($row_gb['title']).'</a></td>
                         <td style="padding: 6px;">`&'.$row_gb['author'].'</td>';
                if($leader) {
                    $out .= '<td style="padding: 6px;">';
                    if(!$row_gb['activated']) {
                        $out .= '`&[<a href="dg_library.php?op=activate&bookid='.$row_gb['bookid'].'">`pFreischalten</a>`&] `X|| ';
                        addnav('','dg_library.php?op=activate&bookid='.$row_gb['bookid']);
                    } else {
                        $out .= '`&[<a href="dg_library.php?op=deactivate&bookid='.$row_gb['bookid'].'">`3Deaktivieren</a>`&] `X|| ';
                        addnav('','dg_library.php?op=deactivate&bookid='.$row_gb['bookid']);
                    }
                    $out .= '`&[<a href="dg_library.php?op=edit&bookid='.$row_gb['bookid'].'">`FBearbeiten</a>`&] `X||
                             `&[<a href="dg_library.php?op=edit&bookid='.$row_gb['bookid'].'">`4Löschen</a>`&]
                             </td>';
                    addnav('','dg_library.php?op=edit&bookid='.$row_gb['bookid']);
                    addnav('','dg_library.php?op=del&bookid='.$row_gb['bookid']);
                }
                $out .= '</tr>';
                addnav('','dg_library.php?op=read&bookid='.$row_gb['bookid']);
            }
        }
        $out .= '</table>';
    break;
    // Buch lesen
    case 'read':
        $row_gb_book = db_fetch_assoc(db_query("SELECT * FROM lib_books WHERE themeid = 999 AND guildid = ".$gid." AND bookid=".$_GET['bookid']));
        $out .= '`8Du schlägst eines der Bücher auf und beginnst zu lesen:`n
                 `n
                 <table cellpadding=2 cellspacing=1 bgcolor="#999999">
                 <tr class="trlight"><td style="padding: 6px; width: 50%;">`b`&Titel:`b '.stripslashes($row_gb_book['title']).'</td><td style="padding: 6px; width: 50%;">`b`&Autor:`b '.$row_gb_book['author'].'</td></tr>
                 <tr class="trdark"><td colspan="2" style="padding: 6px;">`&'.stripslashes($row_gb_book['book']).'</td></tr>
                 </table>';
    break;
    // Buch einreichen
    case 'offer':
        if($_GET['act'] == 'save') {
            $str_title = strip_tags(closetags(preg_replace('/[`](n|c)/','',$_POST['newtitle']),'`i`b'));
            $str_book = htmlspecialchars(strip_tags(closetags($_POST['newbook'],'`c`i`b'),'<table><tr><td><p><hr>'));
            db_query("INSERT INTO lib_books (themeid,guildid,acctid,author,title,book)
                             VALUES (999,".$gid.",".$session['user']['acctid'].",'".$session['user']['name']."','".db_real_escape_string($str_title)."','".db_real_escape_string($str_book)."')");
            // Gildenleiter ermitteln & Info-Hörnchen rausschicken:
            $sql = db_query("SELECT acctid FROM accounts WHERE guildid = ".$gid." AND guildfunc = ".DG_FUNC_LEADER);
            while($row_leader = db_fetch_assoc($sql)) {
                systemmail($row_leader['acctid'],'`hNeues Buch für die Gildenbibliothek','`&'.$session['user']['name'].' `8hat ein neues Buch für eure Gildenbibliothek eingereicht. Du kannst es in der Bibliothek einsehen und ggf. freischalten.');
            }
            // end
            $out .= '`8Dein Buch wurde eingereicht und die Gildenleitung benachrichtigt.`n`n';
        } else {
            output($out.'
                   <style>
                    hr.divider2 {
                      border: none;
                      border-top: 1px dotted #CCCCCC;
                      background-color: #000000;
                      height: 1px;
                    }
                   </style>
                   `8Du nimmst dir einige Blätter Papier und ein Tintenfässchen zur Hand, zückst den Federkiel und machst dich ans Werk:`n`n
                    <form action="dg_library.php?op=offer&act=save" method="post">');
            $arr_form = array("Neues Buch schreiben,title"
                             ,'newtitle'=>'Titel:,text,255'
                             ,'newtitle_priv'=>'Vorschau:,preview,newtitle'
                             ,'Trennstrich,divider2'
                             ,'newbook'=>'Inhalt:,textarea,60,20'
                             ,'newbook_priv'=>'Vorschau:,preview,newbook'
                             );
            showform($arr_form,array());
            output('</form>');
            addnav('','dg_library.php?op=offer&act=save');
            $out = '';
        }
    break;
    // Leader: Buch freischalten
    case 'activate':
        db_query("UPDATE lib_books SET activated = '1' WHERE themeid = 999 AND guildid = ".$gid." AND bookid=".$_GET['bookid']);
        redirect('dg_library.php');
    break;
    // Leader: Buch deaktivieren
    case 'deactivate':
        db_query("UPDATE lib_books SET activated = '0' WHERE themeid = 999 AND guildid = ".$gid." AND bookid=".$_GET['bookid']);
        redirect('dg_library.php');
    break;
    // Leader: Buch editieren
    case 'edit':
        $row_gb_book = db_fetch_assoc(db_query("SELECT * FROM lib_books WHERE themeid = 999 AND guildid = ".$gid." AND bookid=".$_GET['bookid']));
        if($_GET['act'] == 'send') {
            $str_title = strip_tags(closetags(preg_replace('/[`](n|c)/','',$_POST['newtitle']),'`i`b'));
            $str_book = htmlspecialchars(strip_tags(closetags($_POST['newbook'],'`c`i`b'),'<table><tr><td><p><hr>'));
            db_query("UPDATE lib_books SET title='".db_real_escape_string($str_title)."',book='".db_real_escape_string($str_book)."' WHERE themeid = 999 AND guildid = ".$gid." AND bookid=".$row_gb_book['bookid']);
            redirect('dg_library.php');
        } else {
            output($out.'<form action="dg_library.php?op=edit&bookid='.$row_gb_book['bookid'].'&act=send" method="post">');
            $arr_form = array('Buch editieren,title'
                         ,'newtitle'=>'Titel:,text,255'
                         ,'newbook'=>'Inhalt:,textarea,60,20'
                         );
            $arr_data['newtitle'] = $row_gb_book['title'];
            $arr_data['newbook'] = $row_gb_book['book'];
            showform($arr_form,$arr_data);
            output('</form>');
            addnav('','dg_library.php?op=edit&bookid='.$row_gb_book['bookid'].'&act=send');
            $out = '';
        }
    break;
    // Leader: Buch löschen
    case 'del':
        $row_gb_book = db_fetch_assoc(db_query("SELECT * FROM lib_books WHERE themeid = 999 AND guildid = ".$gid." AND bookid=".$_GET['bookid']));
        if($_GET['act'] == 'confirm') {
            db_query("DELETE FROM lib_books WHERE themeid = 999 AND guildid = ".$gid." AND bookid=".$_GET['bookid']);
            // Info-Hörnchen an den Buchautor
            systemmail($row_gb_book['acctid'],'`qBuch gelöscht','`QDein Buch "'.$row_gb_book['title'].'`Q" wurde von '.$session['user']['name'].' `Qaus der Gildenbibliothek gelöscht.');
            // end
            redirect('dg_library.php');
        } else {
            $out .= '`^Möchtest du das Buch "`&'.$row_gb_book['title'].'`^" von `&'.$row_gb_book['author'].' `^wirklich löschen?`n`n
                     <a href="dg_library.php?op=del&bookid='.$row_gb_book['bookid'].'&act=confirm" class="button">Ja, löschen</a>';
            addnav('','dg_library.php?op=del&bookid='.$row_gb_book['bookid'].'&act=confirm');
        }
    break;
}
// Textausgabe:
output($out.'`n`n');
// Allgemeine Seitennav:
addnav('Bibliothek');
if($bool_show_return_link) {
    addnav('Zur Übersicht','dg_library.php');
}
addnav('Buch einreichen','dg_library.php?op=offer');
addnav('Zurück');
addnav('Zur Gildenhalle','dg_main.php?op=in');
// end
page_footer();
?>

