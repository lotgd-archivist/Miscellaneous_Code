
<?php
/*
* @desc Diese Datei ist eine Umstrukturierung der ursprünglichen mail.php
* Sie enthält alle meines Wissens verfügbaren Addons für die ursprüngliche Mail.php, wurde jedoch auf
* Geschwindigkeit getrimmt
* Folgende Features wurden implementiert:
* - Adressbuch: Adressbuch kann in den Settings an und ausgeschaltet werden (deZent und draKarr)
* - Maximale Anzahl an Kontakten festgelegt (Dragonslayer,Talion)
* - Löschen von ungelesenen/gelesenen/Systemnachrichten (Eliwood)
* - Anzeige der noch verfügbaren Zeichen in einer zu schreibenden Mail
* - Rückruf von gesendeten Nachrichten, wenn diese bisher noch nicht vom Empfänger gelesen wurden (Dragonslayer)
* - Versand aller Messages an die Mailadresse des Users
* - Automatische Weiterleitung der Tauben an einen anderen Superuser
* - Es wurde außerdem versucht die Mail.php zu entschlacken und performanter
*   zu realisieren.
* leicht modifiziert von Talion, kleine Tweaks hier und dort.
* @author Kolja Engelmann for lotgd.drachenserver.de
*/

require_once 'common.php';

define('MAIL_DATE_FORMAT','d. M, H:i');

//Speichern der Output Variablen in einer temporären Variable
//Die Funktion output() wird nur einmal am Ende des Skripts aufgerufen, um den Overhead zu sparen
$str_output_backup = $output;
//echo $str_output_backup;
unset($output);

// Enthält Untertitel für aktuelle Aktion
$str_subtitle = '';

//Javascript für die Restzeichenanzeige der Nachrichten
$output = '<script language="JavaScript" type="text/JavaScript">
<!--
function CountMax($var)
{
    var wert,max,show;
    max = $var;
    wert = max-document.mail.body.value.length;
    show = document.getElementById("rv_counter");

    if (wert < 0)
    {
        alert("Es dürfen nicht mehr als " + max + " Zeichen eingegeben werden!");
        document.mail.body.value = document.mail.body.value.substring(0,max);
        wert = max-document.mail.body.value.length;
        show.innerHTML = wert;
    }
    else
    {
        show.innerHTML = wert;
    }
}
//-->
</script>
';

//Bearbeite alle möglichen Optionen die von dieser Datei durchgeführt werden können
switch ($_GET['op'])
{
    //Lösche eine Nachricht mit einer speziellen ID
    case 'del':
        {
            $sql = 'DELETE FROM mail WHERE msgto="'.$session['user']['acctid'].'" AND messageid="'.$_GET['id'].'"';
            db_query($sql);
            $session['message'] = '`@Die Nachricht wurde erfolgreich gelöscht.`0';
            $session['refresh_maillink'] = true;
            $session['refresh_minilink'] = true;
            header('Location: mail.php');
            exit();
        }
        break;
    case 'del_revoked_message':
        {
            $sql = 'DELETE FROM mail WHERE msgfrom="'.$session['user']['acctid'].'" AND messageid="'.$_GET['id'].'"';
            db_query($sql);
            $session['message'] = '`@Die Nachricht wurde erfolgreich gelöscht.`0';
            $session['refresh_maillink'] = true;
            $session['refresh_minilink'] = true;
            header('Location: mail.php');
            exit();
        }

        break;
        //Lösche mehrere Nachrichten
    case 'process':
        {
            switch ($_REQUEST['process_type'])
            {
                //Lösche Systemmails
                case 'sys':
                    {
                        $sql = 'DELETE FROM mail WHERE msgto=\''.$session['user']['acctid'].'\' AND msgfrom=0 AND archived=0';
                        db_query($sql);
                        $int_affected = db_affected_rows();
                        $session['message'] = '`@Alle '.$int_affected.' Systemnachrichten wurden gelöscht.`0';
                        header('Location: mail.php');
                        exit();
                    }
                    break;
                    //Lösche ungelesene nachrichten
                case 'ugdel':
                    {
                        $sql = 'DELETE FROM mail WHERE msgto=\''.$session['user']['acctid'].'\' AND seen=0';
                        db_query($sql);
                        $int_affected = db_affected_rows();
                        $session['message'] = '`@Alle '.$int_affected.' ungelesenen Nachrichten wurden erfolgreich gelöscht.`0';
                        header('Location: mail.php');
                        exit();
                    }
                    break;
                    //Lösche gelesene Nachrichten
                case 'gdel':
                    {
                        $sql = 'DELETE FROM mail WHERE msgto=\''.$session['user']['acctid'].'\' AND seen=1 AND archived=0';
                        db_query($sql);
                        $int_affected = db_affected_rows();
                        $session['message'] = '`@Alle '.$int_affected.' gelesenen Nachrichten wurden erfolgreich gelöscht.`0';
                        header('Location: mail.php');
                        exit();
                    }
                    break;
                    //Lösche alle markierten Nachrichten
                case 'delete_marked':
                    {
                        //Überprüfen, ob überhaupt mehrere Nachrichten zum löschen markiert wurden
                        if (!is_array($_POST['msg']) || count($_POST['msg'])<1)
                        {
                            $session['message'] = '`$`bEs wurden keine Nachrichten ausgewählt, es wurde nichts gelöscht`b`0';
                        }
                        else
                        {
                            //Wenn die gewählten Nachrichten solche sind, die vom Verfasser zurückgerufen werden sollen
                            if($_GET['revoke_messages']==1)
                            {
                                //Lösche alle markierten Nachrichten
                                $sql = 'DELETE FROM mail WHERE msgfrom=\''.$session['user']['acctid'].'\' AND seen=0 AND messageid IN (\''.join('\',\'',$_POST['msg']).'\')';
                            }
                            else
                            {
                                //Lösche alle markierten Nachrichten
                                $sql = 'DELETE FROM mail WHERE msgto=\''.$session['user']['acctid'].'\' AND messageid IN (\''.join('\',\'',$_POST['msg']).'\')';
                            }
                            db_query($sql);
                            $int_affected = db_affected_rows();
                            $session['message'] = '`@Die '.$int_affected.' markierten Nachrichten wurden erfolgreich gelöscht.`0';
                            $session['refresh_maillink'] = true;
                            $session['refresh_minilink'] = true;

                        }
                        header('Location: mail.php');
                        exit();
                    }
                    break;
                    //Archiviere alle markierten Nachrichten
                case 'archive_marked':
                    {
                        if(getsetting('archive_yom_anabled',0) == 0)
                        {
                            $session['message'] = '`@Die Archivierung von Wölfe ist nicht erlaubt`0';
                            header('Location: mail.php');
                            exit();
                        }

                        //Überprüfen, ob überhaupt mehrere Nachrichten zum archicieren markiert wurden
                        if (!is_array($_REQUEST['msg']) || count($_REQUEST['msg'])<1)
                        {
                            $session['message'] = '`$`bEs wurden keine Nachrichten ausgewählt, es wurde nichts archiviert`b`0';
                        }
                        else
                        {
                            $sql_get_archived = 'SELECT  messageid FROM mail WHERE archived = 1 AND msgto = "'.$session['user']['acctid'].'"';
                            $int_count_archived_yom = db_num_rows(db_query($sql_get_archived));
                            $int_maximum_archived_yom = ($access_control->su_lvl_check(1) ? getsetting('archive_yom_mod_limit',100) : getsetting('archive_yom_limit',50));

                            if($int_count_archived_yom>=$int_maximum_archived_yom)
                            {
                                $session['message'] = '`@Die Archivierung ist fehl geschlagen, du hast bereits das Maximum von '.$int_maximum_archived_yom.' Wölfe archiviert`0';
                                header('Location: mail.php');
                                exit();
                            }

                            $int_count_new_archived_yom = 0;
                            foreach ($_REQUEST['msg'] as $int_yom_id)
                            {
                                if($int_count_archived_yom>=$int_maximum_archived_yom)
                                {
                                    $session['message'] = '`@Es konnten nicht alle Wölfe archviert werden, da
                                    du das Maximum von '.$int_maximum_archived_yom.' Wölfe erreicht hast.`0';
                                    header('Location: mail.php');
                                    exit();
                                }

                                $sql = 'UPDATE mail SET archived = 1 , seen = 1 WHERE messageid = '.(int)$int_yom_id.' AND msgto='.$session['user']['acctid'];

                                $query_result = db_query($sql);

                                $int_count_archived_yom++;
                                $int_count_new_archived_yom++;
                            }
                            $session['message'] = '`@Die '.$int_count_new_archived_yom.' markierten Nachrichten wurden erfolgreich archiviert.`0';
                            $session['refresh_maillink'] = true;
                            $session['refresh_minilink'] = true;

                        }
                        header('Location: mail.php');
                        exit();
                    }
                    break;
                // Mails als ungelesen markieren
                case 'unread_marked':
                    {
                        if(!is_array($_POST['msg']) || !count($_POST['msg']))
                        {
                            $session['message'] = '`$Es wurden keine Nachrichten ausgewählt!`0';
                        }
                        else
                        {
                            // Auf Limit checken
                            $str_sql = 'SELECT COUNT(*) AS c FROM mail WHERE seen=0 AND msgto='.(int)$session['user']['acctid'];
                            $res = db_query($str_sql);
                            $arr_tmp = db_fetch_assoc($res);

                            $int_max_unread = (int)getsetting('inboxlimit',50);

                            if($arr_tmp['c']+count($_POST['msg']) > $int_max_unread)
                            {
                                $session['message'] = '`$Soviele Nachrichten dürfen nicht auf ungelesen stehen - max. '.$int_max_unread.'!`0';
                            }
                            else
                            {
                                // Auf ungelesen stellen
                                $str_ids = implode(',',$_POST['msg']);
                                $str_ids = db_real_escape_string($str_ids);
                                $str_sql = 'UPDATE mail SET seen=0 WHERE messageid IN ('.$str_ids.') AND msgto='.(int)$session['user']['acctid'].' AND seen=1';
                                db_query($str_sql);
                                $session['message'] = '`@'.db_affected_rows().' Nachrichten wurden auf ungelesen gesetzt!`0';
                                $session['refresh_maillink'] = true;
                                $session['refresh_minilink'] = true;

                            }
                        }

                        header('Location: mail.php');
                        exit();
                    }
                    break;
                case 'message2mail':
                    {
                        if(getsetting('message2mail_activated',false)==false)
                        {
                            $session['message'] = '`$`bdiese Funktion ist abgeschaltet!`b`0';
                            header('Location: mail.php');
                            exit();
                        }

                        //Überprüfen, ob überhaupt mehrere Nachrichten zum Versand markiert wurden
                        if (!is_array($_POST['msg']) || count($_POST['msg'])<1)
                        {
                            $session['message'] = '`$`bEs wurden keine Nachrichten ausgewählt, es konnte somit nichts versendet werden!`b`0';
                        }
                        elseif(is_email($session['user']['emailaddress'])==false)
                        {
                            $session['message'] = '`$`bDu hast keine gültige E-Mail-Adresse hinterlegt, deswegen können Dir keine Mails zugesendet werden.`b`0';
                            header('Location: mail.php');
                            exit();
                        }
                        else
                        {
                            //Mails selektieren
                            $sql = 'SELECT mail.subject,mail.body,mail.sent,accounts_from.login AS msg_from, accounts_to.login AS msg_to FROM mail LEFT JOIN accounts accounts_from ON accounts_from.acctid=mail.msgfrom LEFT JOIN accounts accounts_to ON accounts_to.acctid=mail.msgto WHERE mail.messageid in (\''.join('\',\'',$_POST['msg']).'\')';
                            //Wenn die gewählten Nachrichten solche sind, die vom Verfasser zurückgerufen werden sollen

                            $query_result = db_query($sql);
                            $int_affected = db_affected_rows();

                            $str_mailbody = '';

                            //Mailbody erzeugen
                            while ($arr_message = db_fetch_assoc($query_result))
                            {
                                $str_mailbody .= '`&Datum: '.$arr_message['sent']."`n";
                                $str_mailbody .= '`&Von: '.$arr_message['msg_from']."`n";
                                $str_mailbody .= '`&An: '.$arr_message['msg_to']."`n";
                                $str_mailbody .= '`&Betreff: '.$arr_message['subject']."`n";
                                $str_mailbody .= "<hr />";
                                $str_mailbody .= '`&'.nl2br($arr_message['body']);
                                $str_mailbody .= "<hr />";
                            }

                            db_free_result($query_result);

                            $str_townname = getsetting('townname','Atrahor');

                            $str_css = '
                            * {
                                padding:0px;
                                margin:0px;
                            }
                            body {
                                background-color:#000000;
                                padding:0px;
                                margin:0px;
                            }
                            body *{
                                font-family: Verdana, Arial, Helvetica, sans-serif;
                                font-size: 11px;
                                color: #FFFFFF;
                            }'
                            .write_appoencode_css();

                            $body = '
                            <html>
                            <head>
                                <title>Deine Wölfe aus '.$str_townname.'</title>
                                <style>
                                <!--
                                    '.$str_css.'
                                -->
                                </style>
                            </head>
                            <body>
                                <b>`c`^Wölfe-Archivierung für '.$session['user']['login'].' aus
                                '.$str_townname.'`^, angefertigt am '.date('d. m. Y',time()).'`c`0</b><br><br>
                                    <hr />
                            ';

                            $body .= $str_mailbody;
                            $body .= '</body></html>';

                            $body = appoencode($body);

                            //Sollte jemand Empfangsprobleme mit 8bit-Kodierung haben muß das ganze noch durch convert.quoted-printable-encode (ab PHP 5.0) geschickt werden

                            $maili = getsetting('gameadminemail','');

                            $filename = 'tauben_'.date('dmyhis',time()).'.html';

                            $headers='';

                            $mailbody .= "Taubenarchivierung vom ".date('d. m. Y',time())."\n";


                            $arr_file = array();
                            $arr_file['content']     = $body;
                            $arr_file['name']         = $filename;
                            $arr_file['encoding']     = 'base64';
                            $arr_file['type']         = 'Content-type: text/html; charset=ISO-8859-1';


                            if(send_mail($session['user']['emailaddress'],'Wölfe aus '.$str_townname,$mailbody,$headers,null,$maili,null,null,array($arr_file)))
                            {
                                $session['message'] = 'Die '.$int_affected.' markierten Nachrichten wurden erfolgreich an Deine Mailadresse versendet.';
                                $session['refresh_maillink'] = true;
                                $session['refresh_minilink'] = true;
                                //Lösche alle markierten Nachrichten
                                $sql = 'DELETE FROM mail WHERE msgto=\''.$session['user']['acctid'].'\' AND messageid IN (\''.join('\',\'',$_POST['msg']).'\')';
                                db_query($sql);
                            }
                            else
                            {
                                $session['message'] = '`$Leider trat beim Versenden der Mail ein Fehler auf!`0';
                            }
                        }
                        header('Location: mail.php');
                        exit();

                    }
                    break;


                    $session['refresh_maillink'] = true;
                    $session['refresh_minilink'] = true;

                    header('Location: mail.php');
                    exit();
            }
        }
        break;
        //Sende eine Mail
    case 'send':
        {

            $str_subtitle = 'Wolf abschicken';

            // Dieses Formular wurde bereits einmal abgeschickt
            if($_POST['mailcounter'] != $session['mailcounter']) {

                $session['message'] = '`@Deine Nachricht wurde gesendet!`0`n';
                header('Location:mail.php');
                exit;
            }

            $session['mailcounter'] = '';

            //Wenn es sich nicht um eine Antwort auf eine Anfrage handelt
            if (isset($_POST['petitionid'])==false)
            {
                //Wenn man eine neue nachricht sendet
                if( isset($_POST['revoke_edit'])==false ){
                    //Suche nach dem Empfänger
                    $sql = 'SELECT acctid,superuser,emailaddress FROM accounts WHERE login="'.$_POST['to'].'"';
                    $result = db_query($sql);
                    //Existiert der Empfänger?
                    if (db_num_rows($result)>0)
                    {
                        $row1 = db_fetch_assoc($result);
                        //Überprüfen, ob dem Benutzer noch Mails geschickt werden können (Mailbox voll)
                        $sql = 'SELECT count(*) AS count FROM mail WHERE msgto="'.$row1['acctid'].'" AND seen=0 ';
                        $result = db_query($sql);
                        $row = db_fetch_assoc($result);
                        //Zuviele Mails in der Inbox des Empfängers
                        if (($row['count']>getsetting('inboxlimit',50)) || (($row1['superuser']>0) && ($row['count']>getsetting('modinboxlimit',50))))
                        {
                            $output.='Die Mailbox des Empfängers ist voll! Du kannst ihm keine Nachricht schicken.';
                        }
                        //Mail kann geschrieben werden
                        else
                        {
                            //Messagebody bearbeiten
                            $_POST['body']=str_replace('`n',"\n",$_POST['body']);
                            $_POST['body']=str_replace("\r\n","\n",$_POST['body']);
                            $_POST['body']=str_replace("\r","\n",$_POST['body']);
                            $_POST['body']=addslashes(substr(stripslashes($_POST['body']),0,(int)getsetting('mailsizelimit',1024)));
                            $_POST['body'] = closetags($_POST['body'],'`c`i`b');


                            if(!empty($_POST['subject']))
                            {
                                $_POST['subject']=closetags(str_replace('`n','',$_POST['subject']),'`c`i`b');
                            }
                            else
                            {
                                if(getsetting('automatic_header_generation',1) == 1)
                                {
                                    $int_length = getsetting('automatic_header_length',60);
                                    $_POST['subject'] = substr($_POST['body'],0,$int_length).'...';
                                }
                            }

                            //Soll die Nachricht an die Mailadresse gesendet werden?
                            //Wenn beim senden ein Fehler auftritt wird anschließend die Mail einfach als Taube versendet.
                            if(isset($_POST['yom_to_mail']) && $_POST['yom_to_mail'] == 1)
                            {
                                $str_from_name = ($_POST['yom_to_mail_server'] == 1?getsetting('teamname','Drachenserver-Team'):$session['user']['login']);
                                $str_from_mail = ($_POST['yom_to_mail_server'] == 1?getsetting('petitionemail','postmaster@localhost'):$session['user']['emailaddress']);

                                $_POST['body'] .= "\n\n\nAnmerkung:\nAntworten an diese Mailadresse werden nicht empfangen, bitte antworte ggf. per Anfrage.";

                                if(send_mail($row1['emailaddress'],$_POST['subject'],$_POST['body'],'',NULL,$str_from_mail,$str_from_name,NULL,array(),false))
                                {
                                    $session['message'] = '`@Deine Nachricht wurde and die Mailaddresse gesendet!`0`n';
                                    systemlog('versandte eine Mail an einen User',$session['user']['acctid'],$row1['acctid']);
                                    header('Location:mail.php');
                                    exit;
                                }
                            }
                            systemmail($row1['acctid'],$_POST['subject'],$_POST['body'],$session['user']['acctid']);
                            $session['message'] = '`@Deine Nachricht wurde gesendet!`0`n';

                            header('Location:mail.php');
                            exit;
                        }
                    }
                    //Der Empfänger konnte nicht gefunden werden
                    else
                    {
                        $output.='Der Empfänger konnte nicht gefunden werden. Bitte versuche es nochmal.';
                    }
                    db_free_result($result);
                }
                //wenn man eine gesendete nachricht editiert
                else{
                    $msg_id = intval($_POST['id']);
                    $sql     = 'SELECT seen FROM mail WHERE messageid="'.$msg_id.'"';
                    $result = db_query($sql);
                    $row     = db_fetch_assoc($result);
                    if( $row ){
                        if( !$row['seen'] ){
                            $_POST['subject']=closetags(str_replace('`n','',$_POST['subject']),'`c`i`b');
                            $sql     = 'UPDATE mail SET subject="'.addslashes($_POST['subject']).'", body="'.addslashes($_POST['body']).'" WHERE messageid="'.$msg_id.'"';
                            $result = db_query($sql);
                            $session['message'] = '`@Deine Nachricht wurde bearbeitet!`0`n';
                            header('Location:mail.php');
                            exit;
                        }
                        else{
                            $output.='Diese Nachricht wurde bereits gelesen!';
                        }
                    }
                    else{
                        $output.='Diese Nachricht wurde bereits gelöscht!';
                    }
                }
            }
            //Es handelt sich um die Antwort auf eine Anfrage
            else
            {
                $sql = 'SELECT count(*) AS count FROM petitionmail WHERE petitionid=\''.$_POST['petitionid'].'\' AND msgto=\''.$session['user']['acctid'].'\'';
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                //Handelt es sich um die Anfrage des Users?
                if ($row['count']==0)
                {
                    $output .= 'Du kannst nur zu deinen eigenen Anfragen etwas schreiben!';
                }
                //Der User darf antworten
                else
                {
                    $_POST['subject']=closetags(str_replace('`n','',$_POST['subject']),'`c`i`b');
                    $_POST['body']=str_replace('`n',"\n",$_POST['body']);
                    $_POST['body']=str_replace("\r\n","\n",$_POST['body']);
                    $_POST['body']=str_replace("\r","\n",$_POST['body']);
                    $_POST['body']=substr($_POST['body'],0,(int)getsetting('mailsizelimit',1024));
                    $_POST['body'] = closetags($_POST['body'],'`c`i`b');
                    petitionmail($_POST['subject'],$_POST['body'],$_POST['petitionid'],$session['user']['acctid']);
                    $output.='Deine Nachricht wurde gesendet!`n';
                }
                db_free_result($result);
            }
        }
        break;
        //Zeige alle gesendeten Nachrichten des Nutzers, die noch nicht vom Empfänger gelesen wurden
    case 'outbox':
        {

            $str_subtitle = 'Postausgang`0';

            $output .= '
                    Hier kannst Du von Dir gesendeten Wolf zurückrufen, wenn diese vom Empfänger noch nicht gelesen wurden`n';
            //Selektiere alle Mails
            $sql = 'SELECT mail.subject,mail.messageid,accounts.name,mail.msgto,mail.seen,mail.sent, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE mail.msgfrom=\''.$session['user']['acctid'].'\' and mail.seen=0 ORDER BY mail.sent';
            $result = db_query($sql);
            //Anzahl der Tupel bestimmen
            $int_mails = db_num_rows($result);
            //Wenn mindestens eine Mail vorhanden ist
            if ($int_mails>0)
            {
                $output.='<form action="mail.php?op=process&revoke_messages=1" method="POST">';
                $output.='<table>';

                $recent_date = date('dm',time());

                //Stelle jede Mail dar
                for ($i=0;$i<$int_mails;$i++)
                {
                    $row = db_fetch_assoc($result);

                    $senttime = strtotime($row['sent']);

                    if( date('dm',$senttime) == $recent_date )
                    {
                        $sent = 'Heute, '.date('H:i',$senttime);
                    }
                    else
                    {
                        $sent = date(MAIL_DATE_FORMAT,$senttime);
                    }

                    //Gib alle Details aus
                    $output.='
                <tr>
                    <td nowrap><input id="checkbox'.$i.'" type="checkbox" name="msg[]" value="'.$row['messageid'].'"><img src="images/newscroll.GIF" width="16" height="16" alt="ungelesen"></td>
                    <td><a href="mail.php?op=read&id='.$row['messageid'].'&revoke_messages=1">'.$row['subject'].'</a></td>
                    <td><a href="mail.php?op=read&id='.$row['messageid'].'&revoke_messages=1">'.$row['name'].'</a></td>
                    <td>'.$sent.'</td>
                </tr>';
                }
                $output.='</table>';

                //Bei einem Klick auf den Button wird jede Mail markiert
                $output.='<input type="button" value="Alle markieren" class="button" onClick="';
                $output.='for(i=0;i<document.getElementsByName(\'msg[]\').length;i++) {document.getElementsByName(\'msg[]\')[i].checked=true;}';
                $output.='">';

                $output.='<input type="button" value="Alle abwählen" class="button" onClick="';
                $output.='for(i=0;i<document.getElementsByName(\'msg[]\').length;i++) {document.getElementsByName(\'msg[]\')[i].checked=false;}';
                $output.='">';


                $output.='
                        <input type="hidden" name="process_type" value="delete_marked" />
                        <input type="submit" class="button" value="Markierte löschen" />
                    </form>
                ';
            }
            else
            {
                $output.='`iEntweder Du hast noch keine Mails versandt oder sie wurden bereits alle gelesen.`i';
            }
            db_free_result($result);
        }
        break;
        //Zeige das YOM Archiv an
    case 'archive':
        {
            $str_subtitle = 'Archivierte Wölfe`0';

            $output .= '
                    Manchmal haben die Nachrichten deine Wölfe einen besonders sentimentalen Wert für dich. Du möchtest sie nicht verlieren oder dem Verfall überlassn.
                    Hier kannst Du all\' jene Wölfe speichern.`n`n';

            //Selektiere alle Mails
            $sql = 'SELECT mail.subject,mail.messageid,accounts.name,mail.msgfrom,mail.seen,mail.sent, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE mail.msgto="'.$session['user']['acctid'].'" AND mail.archived = 1 ORDER BY mail.sent DESC';
            $result = db_query($sql);

            //Anzahl der Tupel bestimmen
            $int_mails = db_num_rows($result);
            //Wenn mindestens eine Mail vorhanden ist
            if ($int_mails>0)
            {
                $output.='<form action="mail.php?op=process" method="POST">';
                $output.='<table>';

                $recent_date = date('dm',time());

                //Stelle jede Mail dar
                for ($i=0;$i<$int_mails;$i++)
                {
                    $row = db_fetch_assoc($result);

                    $senttime = strtotime($row['sent']);

                    if( date('dm',$senttime) == $recent_date )
                    {
                        $sent = 'Heute, '.date('H:i',$senttime);
                    }
                    else
                    {
                        $sent = date(MAIL_DATE_FORMAT,$senttime);
                    }

                    //Gib alle Details aus
                    $output.='
                <tr>
                    <td nowrap><input id="checkbox'.$i.'" type="checkbox" name="msg[]" value="'.$row['messageid'].'"><img src="images/newscroll.GIF" width="16" height="16" alt="ungelesen"></td>
                    <td><a href="mail.php?op=read&id='.$row['messageid'].'">'.$row['subject'].'</a></td>
                    <td><a href="mail.php?op=read&id='.$row['messageid'].'">'.$row['name'].'</a></td>
                    <td>'.$sent.'</td>
                </tr>';
                }
                $output.='</table>';

                //Bei einem Klick auf den Button wird jede Mail markiert
                $output.='<input type="button" value="Alle markieren" class="button" onClick="';
                $output.='for(i=0;i<document.getElementsByName(\'msg[]\').length;i++) {document.getElementsByName(\'msg[]\')[i].checked=true;}';
                $output.='">';

                $output.='<input type="button" value="Alle abwählen" class="button" onClick="';
                $output.='for(i=0;i<document.getElementsByName(\'msg[]\').length;i++) {document.getElementsByName(\'msg[]\')[i].checked=false;}';
                $output.='">';

                $output.='
                <br />
                <select name="process_type">
                    <option value="delete_marked" selected>Lösche markierte Nachrichten</option>
                    ';

                //Email - Option nur zeigen wenn sie angeschaltet ist
                if( $access_control->su_lvl_check(1) || getsetting('message2mail_activated',false)==true)
                {
                    $output.='<option value="message2mail">Sende markierte Nachrichten an deine EMail Adresse</option>';
                }

                $output .= '

                    </select>
                    <input type="submit" class="button" value="Los!">
                </form>';
            }
            else
            {
                $output.='`iNoch hast du keine Wölfe archiviert.`i`n`n';
            }

            $output .= 'Du hast bereits '.$int_mails.' Wölfe archiviert und darfst maximal '.
            ( ($access_control->su_lvl_check(1))? getsetting('archive_yom_mod_limit',50):getsetting('archive_yom_limit',50) ).
            ' Wölfe archivieren';

            db_free_result($result);
        }
        break;
        //Lies eine angegebene Nachricht
    case 'read':
        {

            $str_subtitle     = 'Wölfe lesen`0';
            $revoke         = $_GET['revoke_messages'] == 1;
            $sql_add         = '1';
            //Verhindern, dass man sich fremde mails anguckt! Danke @ Baras fürs melden
            //by Alucard
            if( $revoke )
            {
                $sql_add = 'mail.msgfrom='.$session['user']['acctid'];
            }
            else
            {
                $sql_add = 'mail.msgto='.$session['user']['acctid'];
            }
            //Hole Daten zur Mail und deren Erzeuger und Empfänger aus der DB
            $sql = 'SELECT mail.*,accounts.name,accounts.acctid, petitionmail.petitionid
                    FROM mail
                    LEFT JOIN petitionmail USING(messageid)
                    LEFT JOIN accounts ON accounts.acctid=mail.msgfrom
                    WHERE mail.messageid="'.(int)$_GET['id'].'" AND '.$sql_add;
            $result = db_query($sql);
            if (db_num_rows($result)>0)
            {
                $row = db_fetch_assoc($result);
                db_free_result($result);
                //Setze die Mail auf den Status "gelesen", es sei denn es ist eine Mail die in der outbox lag und zurückgerufen werden soll
                if($row['seen'] == 0 && !$revoke)
                {
                    $sql = 'UPDATE mail SET seen=1 WHERE  msgto=\''.$session['user']['acctid'].'\' AND messageid=\''.$row['messageid'].'\'';
                    db_query($sql);
                }

                if ((int)$row['msgfrom']==0)
                {
                    if ((int)$row['petitionid']==0)
                    {
                        $row['name']='`^`iSystem`i`0';
                    }
                    else
                    {
                        $row['name'] = '`^`iAdmin`i`0';
                    }
                }

                $row['body'] = soap(closetags($row['body'],'`b`c`i'));
                // HTML-Sonderzeichen, die Probleme machen könnten, maskieren
                $row['body'] = str_replace(array('<','>'),array('&lt;','&gt;'),$row['body']);

                if(empty($row['subject'])) {
                    $row['subject'] = '`iKeiner`i';
                }
                else {
                    $row['subject'] = soap(closetags($row['subject'],'`b`c`i'));
                    $row['subject'] = str_replace(array('<','>'),array('&lt;','&gt;'),$row['subject']);
                    $row['subject'] = stripslashes($row['subject']);
                }
                if( $revoke ){
                    $edit_subject = str_replace('`','{#96}',$row['subject']);

                    $edit_body = str_replace('`','{#96}',$row['body']);
                    $edit_body = stripslashes($edit_body);
                    $edit_body = htmlentities($edit_body);

                    $output.='<form action="mail.php?op=send" method="POST" name="mail">
                    <input name="id" type="hidden" value="'.$row['messageid'].'">
                    <input name="revoke_edit" type="hidden" value="1">';
                }
                else
                {
                    $row['body'] = stripslashes($row['body']);
                }


                //Ausgabe des Mailtextes formatiert in 3 Tabellen. Header enthält Betreff und Absender, Sendezeit
                $output.='<table width="595" border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td class=scroll_label>`^'.$row['subject'].' `2`bvon`b ';
                        
                ///// SHADOWBOX
                if($session['user']['prefs']['sdwbox'] == 1)
                {    
                    $output.='`^'.$row['name'].($row['acctid'] > 0 ? ' <a href="bio.php?id='.$row['acctid'].'">`&[Bio]`0</a>':'').' ';
                }
                else
                {
                    $output.='`^'.$row['name'].($row['acctid'] > 0 ? ' <a href="javascript:void(0);" onclick="window.open(\'bio.php?id='.$row['acctid'].'\',\'bio\',\'scrollbars=yes,resizable=yes,width=750,height=450\');">`&[Bio]`0</a>':'').' ';
                }                
                ///// SHADOWBOX
                
                $output.='<div align="right">`2`bGesendet:`b `^'.date(MAIL_DATE_FORMAT,strtotime($row["sent"])).'&nbsp;&nbsp;&nbsp;&nbsp;`0</div>';
                $output.='`0</td>
                    </tr>
                </table>';

                //Hauptfeld mit Seitenrahmen, enthält Editfelder, Brieftext
                $output.='<table width="595" border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td class="scroll_border_l" />
                        <td class="scroll_main">';

                if( $revoke ){
                    $output.='<div id="yom_subject_edit" style="display: none;"><input name="subject" value="'.HTMLEntities($edit_subject).'">&nbsp;&nbsp;noch <input name="rv_counter" size="'.strlen(getsetting('mailsizelimit' ,0)).'" value="'.getsetting('mailsizelimit' ,0).'" readonly> Zeichen übrig.</div>';
                }

                $output.='<div id="yom_body">'.str_replace("\n",'`n',$row['body']).'</div>';
                if( $revoke ){
                    $output.='<div id="yom_body_edit" style="display: none;">
                                <textarea name="body" class="input" cols="40" rows="9" OnFocus="CountMax('.getsetting('mailsizelimit' ,0).');" OnClick="CountMax('.getsetting('mailsizelimit' ,0).');" OnChange="CountMax('.getsetting('mailsizelimit' ,0).');" onKeydown="CountMax('.getsetting('mailsizelimit' ,0).');" onKeyup="CountMax('.getsetting('mailsizelimit' ,0).');" wrap="virtual">'.$edit_body.'</textarea>
                            </div>';
                }

                $output.='</td>
                        <td class="scroll_border_r" />
                    </tr>
                </table>';

                //Footer, enthält Aktionen-Links
                $output.='<table width="595" border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td class="scroll_label_b" style="vertical-align: bottom;text-align:center;"><img src="images/trans.gif" width=55 height=1 style="float: left;clear: none;" alt="">';

                if($revoke)
                {
                    $output .= '<a href="javascript:void(0);" onClick="yom_show_edit()" class="motd" id="yom_edit">Editieren</a>';
                    $output .= '<script type="text/javascript">
                                function yom_show_edit(){
                                    document.getElementById("yom_save").style.display = "inline";
                                    document.getElementById("yom_cancel").style.display = "inline";
                                    document.getElementById("yom_body_edit").style.display = "block";
                                    document.getElementById("yom_subject_edit").style.display = "block";

                                    document.getElementById("yom_edit").style.display = "none";
                                    document.getElementById("yom_body").style.display = "none";
                                    document.getElementById("yom_subject").style.display = "none";
                                }

                                function yom_cancel_edit(){
                                    document.getElementById("yom_edit").style.display = "inline";
                                    document.getElementById("yom_body").style.display = "block";
                                    document.getElementById("yom_subject").style.display = "block";


                                    document.getElementById("yom_save").style.display = "none";
                                    document.getElementById("yom_cancel").style.display = "none";
                                    document.getElementById("yom_body_edit").style.display = "none";
                                    document.getElementById("yom_subject_edit").style.display = "none";
                                }

                                function yom_submit(){
                                    document.mail.submit();
                                }
                                </script>
                                <a href="javascript:void(0);" onClick="yom_submit();return false;" class="motd" id="yom_save" style="display: none;">Speichern</a>
                                <a href="javascript:void(0);" onClick="yom_cancel_edit()" class="motd" id="yom_cancel" style="display: none;">Abbrechen</a>
                                <a href="mail.php?op=del_revoked_message&id='.$row['messageid'].'" class="motd">Löschen</a>';
                }
                else
                {
                    $output .= '
                    <a href="mail.php?op=write&replyto='.$row['messageid'].'" class="motd">Antworten</a>
                    <a href="mail.php?op=del&id='.$row['messageid'].'" class="motd">Löschen</a>
                    <a href="mail.php?op=process&process_type=archive_marked&msg[]='.$row['messageid'].'" class="motd">Ins Archiv</a>';
                }

                //Wenn das Adressbuch angeschaltet ist dann wird der folgende Link angezeigt
                if(getsetting('show_yom_contacts',1)==1 || $session['user']['superuser']>0)
                {
                    $output.='<a href="mail.php?op=neuerkontakt2&id='.$row['acctid'].'" class="motd">Zu Kontakten</a>';
                }

                $output.='</td>
                    </tr>
                </table>';
                //hier Ende der 3 Anzeigetabellen

                // Mails in Hauptfenster aktualisieren
                if( !$revoke ){
                    $output .= '<script type="text/javascript">
                                    var obj = window.opener.document.getElementById("MAILBOXLINK");
                                    if( obj ){
                                        obj.innerHTML = "'.addslashes(maillink(true)).'";
                                    }
                                </script>';
                    if ($session['user']['prefs']['minimail'])
                    {
                        $output .= '<script type="text/javascript">
                                        var obj = window.opener.document.getElementById("MINILINK");
                                        if( obj ){
                                            obj.innerHTML = "'.addslashes(minimail(true)).'";
                                        }
                                    </script>';
                    }
                }
                else{
                    $output .= '</form>';
                }
            }
            //Die Nachricht konnte nicht gefunden werden
            else
            {
                $output.='Diese Nachricht wurde nicht gefunden!';
            }
        }
        break;
        //Schreib eine Mail
    case 'write':
        {
            $str_subtitle = 'Wolf verfassen`0';

            $bool_write_allowed = true;
            $subject='';
            $body='';
            $output.='
                    <form action="mail.php?op=send" method="POST" name="mail">';
            //Wenn Demo-Account
            if($session['user']['acctid']==getsetting('demouser_acctid',0))
            {
                $output.='</form>`$Wölfe verfassen ist beim Demo-Zugang nicht möglich.`n';
                $bool_write_allowed=false;
            }
            //Wenn die Mail eine Antwort auf eine vorhergehende Mail ist
            if ($_GET['replyto']!='')
            {
                //Lade alle Infomationen über die zu beantwortende Mail
                $sql = 'SELECT mail.body,mail.subject,accounts.login,accounts.name, accounts.laston,accounts.loggedin,accounts.activated,
                             petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE mail.msgto=\''.$session['user']['acctid'].'\' AND mail.messageid=\''.$_GET['replyto'].'\'';
                $result = db_query($sql);
                //Wenn die gesuchte Mail existiert
                if (db_num_rows($result)>0)
                {
                    $row = db_fetch_assoc($result);
                    db_free_result($result);
                    if ($row['login']=='' && (int)$row['petitionid']==0)
                    {
                        $output.='Du kannst nicht auf eine Systemnachricht antworten.`n';
                        $row=array();
                        $bool_write_allowed = false;
                    }
                }
                else
                {
                    $output.='Die Nachricht auf die Du antworten willst existiert nicht!`n';
                    $bool_write_allowed = false;
                }
            }
            //Wenn eine neue Nachricht erstellt werden soll
            if ($_GET['to']!='')
            {
                $to = $_GET['to'];

                $str_search = '
                <div id="search_div">
                `tWem willst Du einen Wolf schicken?`n`n
                '.form_header('mail.php?op=write','POST',false,'search_form','if(document.getElementById(\'search_sel\').selectedIndex > -1) {this.submit();} else {search();return false;}').'
                    '.jslib_search('document.getElementById("search_form").submit();','Übernehmen!').'
                </form>
                </div>
                ';

                //Überprüfe, ob der Empfänger existiert
                $sql = 'SELECT login,name,laston,loggedin,activated FROM accounts WHERE
                       '.(is_numeric($to) ? 'acctid='.$to : 'login="'.addslashes($_GET['to']).'"');
                $result = db_query($sql);
                //Wurde die Person gefunden?
                if (db_num_rows($result)>0)
                {
                    $row = db_fetch_assoc($result);
                }
                else
                {
                    $output.='Diese Person konnte nicht gefunden werden`n';
                    $bool_write_allowed = false;
                }
                db_free_result($result);
            }
            //Wenn der Empfänger gefunden wurde
            if (is_array($row))
            {
                //Check whether this mail is an answer to aMail, and avoid this RE: RE: RE: stuff
                if ($row['subject']!='')
                {
                    $subject=$row['subject'];
                    if (substr($subject,0,4)!='RE: ')
                    {
                        $subject='RE: '.$subject;
                    }
                }

                if ($row['body']!='')
                {
                    $body="\n\n---Vorherige Botschaft---\n".$row['body'];
                }
            }
            if ($row['petitionid']>0)
            {
                $output.='`2An: `^`iAdmin`i`n';
            }
            elseif ($row['login']!='')
            {
                $str_online = '';
                $str_online = (user_get_online(0,$row) ? ' `@(online)`0' : ' `4(offline)`0');

                $output.='<input type="hidden" name="to" value="'.HTMLEntities($row['login']).'">
                `2An: `^'.$row['name'].$str_online.'`n';
            }
            else
            {
                $output.='`2An: ';
                $string = str_create_search_string(stripslashes($_POST['to']));

                //$sql = 'SELECT login,name,laston,loggedin,activated FROM accounts WHERE name LIKE \''.addslashes($string).'\' AND locked=0 ORDER BY IF(login="'.addslashes($str_to).'",1,0) DESC, login ASC';
                $sql = 'SELECT login,name,laston,loggedin,activated FROM accounts WHERE name LIKE \''.addslashes($string).'\' AND locked=0 ORDER BY login="'.db_real_escape_string($_POST['to']).'" DESC, login ASC';
                $result = db_query($sql);

                $int_result_count = db_num_rows($result);

                if ($int_result_count==1)
                {
                    $row = db_fetch_assoc($result);

                    $str_online = '';
                    $str_online = (user_get_online(0,$row) ? ' `@(online)`0' : ' `4(offline)`0');

                    $output.='<input type="hidden" name="to" value="'.HTMLEntities($row['login']).'">
                `^'.$row['name'].$str_online.'`n';
                }
                else if($int_result_count == 0) {

                    $output.='Diese Person konnte nicht gefunden werden`n';
                    $bool_write_allowed = false;
                }
                else
                {
                    $output.='<select name="to">';
                    for ($i=0;$i<$int_result_count;$i++)
                    {
                        $row = db_fetch_assoc($result);
                        $str_online = '';
                        $str_online = (user_get_online(0,$row) ? ' (online)' : ' (offline)');

                        $output .= '<option value="'.HTMLEntities($row['login']).'">'.preg_replace('/[`]./','',$row['name']).$str_online;
                    }
                    $output.='</select>`n';
                }
                db_free_result($result);
            }

            if($bool_write_allowed)
            {
                // Doppeltes Verschicken einer Mail verhindern by talion
                $session['mailcounter'] = md5(time());

                // Formatierungstags in Betreff und Mail maskieren
                $subject = str_replace('`','{#96}',$subject);
                $body = str_replace('`','{#96}',$body);

                $output.='<input type="hidden" name="mailcounter" value="'.$session['mailcounter'].'">';

                $output.='`2Betreff:';
                $output.='<input name="subject" value="'.HTMLEntities($subject).HTMLEntities(stripslashes($_GET['subject'])).'">&nbsp;&nbsp;noch <span id="rv_counter">'.getsetting('mailsizelimit' ,0).'</span> Zeichen übrig.';
                $output.='`n`2Text:`0`n';
                $output.='<textarea name="body" class="input" cols="70" rows="12" OnFocus="CountMax('.getsetting('mailsizelimit' ,0).');" OnClick="CountMax('.getsetting('mailsizelimit' ,0).');" OnChange="CountMax('.getsetting('mailsizelimit' ,0).');" onKeydown="CountMax('.getsetting('mailsizelimit' ,0).');" onKeyup="CountMax('.getsetting('mailsizelimit' ,0).');" wrap="virtual">'.HTMLEntities($body).HTMLEntities(stripslashes($_GET['body'])).'</textarea><br>';
                $output.= $access_control->su_check(access_control::SU_RIGHT_WRITE_YOM_TO_MAIL)?'`$Sende den Wolf an die Mailadresse <input type="checkbox" name="yom_to_mail" value="1">':'';
                $output.= $access_control->su_check(access_control::SU_RIGHT_WRITE_YOM_TO_MAIL)?'`n`$Verwende als Absender die Serveraddresse (sonst Deine) <input type="checkbox" name="yom_to_mail_server" value="1" checked><hr>':'';
                $output.='`n<input type="submit" class="button" value="Senden">`n';
                if ($row['petitionid']>0)
                {
                    $output.='<input type="hidden" name="petitionid" value="'.$row['petitionid'].'">';
                }
                $output.='</form>';
            }
        }
        break;
        //Empfängersuche
    case 'address':
        {
            $str_subtitle = 'Wolf senden`0';
            /*$output .= '
                <form action="mail.php?op=write" method="POST">
                    `2<u>A</u>n:`0 <input name="to" accesskey="a"> <input type="submit" class="button" value="Suchen!">
                </form>
            ';*/
            $output .= '`0
                ' . form_header('mail.php?op=write') . '
                    `2An:`0' . jslib_autocomplete_name('to', true, true) . '
                </form>
            ';
        }
        break;
    case 'buch':
        {
            /**********************************************
            *Diese Box darf nicht entfernt werden!        *
            *-------------------------------------        *
            *Adressbuch von deZent und draKarr            *
            *Version: 0.5                                 *
            *www.plueschdrache.de                         *
            *etwas verändert von talion & Alucard          *
            **********************************************/

            $sql = "
                SELECT
                    y.row_id
                    ,y.player
                    ,y.descr
                    ,a.acctid
                    ,a.name
                    ,a.loggedin
                    ,a.laston
                    ,a.activated
                    ,(".user_get_online().")    AS online
                    ,a.alive
                FROM
                    yom_adressbuch y
                LEFT JOIN
                    accounts a
                ON
                    a.acctid=y.player
                WHERE
                    y.acctid=".$session['user']['acctid']."
                ORDER BY
                    online DESC
                    ,login ASC
            ";
            $result = db_query($sql);
            $menge = db_num_rows($result);
            $max_yom_contacts = getsetting('max_yom_contacts',1);
            //Number of contacts left;
            $yom_contacts_left = $max_yom_contacts;
            $yom_contacts_left-=$menge;

            $bool_online_shown = false;

            $str_subtitle = 'Adressbuch`0';

            // ADDRESS_PLUMI_CONTACTS
            define('AD_PL_CO', 'ADDRESS_PLUMI_CONTACTS');
            define('AD_PL_GU', 'ADDRESS_PLUMI_GUILD');
            define('AD_PL_TE', 'ADDRESS_PLUMI_TEAM');

            $bit_pl_CO = plu_mi_get_val(AD_PL_CO);
            $bit_pl_GU = plu_mi_get_val(AD_PL_GU);
            $bit_pl_TE = plu_mi_get_val(AD_PL_TE);

            $plumi_CO_str = plu_mi(AD_PL_CO, $bit_pl_CO);
            $pl_CO_ein = plu_mi_unique_id(AD_PL_CO);
            $pl_CO_aus = plu_mi_unique_id(AD_PL_CO);
            $output .= $plumi_CO_str. ' Kontaktliste '
                . '<span id="' . $pl_CO_ein . '" ' . ($bit_pl_CO?'style="display:none;" ':'') . '>einblenden</span>'
                . '<span id="' . $pl_CO_aus . '" ' . ($bit_pl_CO?'':'style="display:none;" ') . '>ausblenden</span>'
                . '    <script type="text/javascript">
                        function onPLUMI_' . AD_PL_CO . '(show)
                        {
                            if (show)
                            {
                                $("' . $pl_CO_ein . '").style.display = "none";
                                $("' . $pl_CO_aus . '").style.display = "inline";
                            }
                            else
                            {
                                $("' . $pl_CO_aus . '").style.display = "none";
                                $("' . $pl_CO_ein . '").style.display = "inline";
                            }
                        }
                    </script>`n'
            ;

            if ($session['user']['guildid'])
            {
                $plumi_GU_str = plu_mi(AD_PL_GU, $bit_pl_GU);
                $pl_GU_ein = plu_mi_unique_id(AD_PL_GU);
                $pl_GU_aus = plu_mi_unique_id(AD_PL_GU);
                $output .= $plumi_GU_str. ' Gildenliste '
                    . '<span id="' . $pl_GU_ein . '" ' . ($bit_pl_GU?'style="display:none;" ':'') . '>einblenden</span>'
                    . '<span id="' . $pl_GU_aus . '" ' . ($bit_pl_GU?'':'style="display:none;" ') . '>ausblenden</span>'
                    . '    <script type="text/javascript">
                            function onPLUMI_' . AD_PL_GU . '(show)
                            {
                                if (show)
                                {
                                    $("' . $pl_GU_ein . '").style.display = "none";
                                    $("' . $pl_GU_aus . '").style.display = "inline";
                                }
                                else
                                {
                                    $("' . $pl_GU_aus . '").style.display = "none";
                                    $("' . $pl_GU_ein . '").style.display = "inline";
                                }
                            }
                        </script>`n'
                ;
            }

            if ($access_control->su_check(access_control::SU_RIGHT_GROTTO))
            {
                $plumi_TE_str = plu_mi(AD_PL_TE, $bit_pl_TE);
                $pl_TE_ein = plu_mi_unique_id(AD_PL_TE);
                $pl_TE_aus = plu_mi_unique_id(AD_PL_TE);
                $output .= $plumi_TE_str. ' Teamliste '
                    . '<span id="' . $pl_TE_ein . '" ' . ($bit_pl_TE?'style="display:none;" ':'') . '>einblenden</span>'
                    . '<span id="' . $pl_TE_aus . '" ' . ($bit_pl_TE?'':'style="display:none;" ') . '>ausblenden</span>'
                    . '    <script type="text/javascript">
                            function onPLUMI_' . AD_PL_TE . '(show)
                            {
                                if (show)
                                {
                                    $("' . $pl_TE_ein . '").style.display = "none";
                                    $("' . $pl_TE_aus . '").style.display = "inline";
                                }
                                else
                                {
                                    $("' . $pl_TE_aus . '").style.display = "none";
                                    $("' . $pl_TE_ein . '").style.display = "inline";
                                }
                            }
                        </script>`n'
                ;
            }

            $output .= '`n<div id="' . plu_mi_unique_id(AD_PL_CO) . '" ' . ($bit_pl_CO?'':'style="display:none;" ') . '>';

            if($yom_contacts_left>0)
            {
                $output .= '
                        `n<a href="mail.php?op=neuerkontakt" class="motd">
                            Neuer Kontakt
                        </a>
                        &nbsp;('.$yom_contacts_left.'/'.$max_yom_contacts.' frei)`n`n
                ';
            }
            else
            {
                $output.='`4Das Maximum von '.$max_yom_contacts.' Kontakten wurde erreicht.`0`n';
            }
            $output .= '</div>';

            $output .= '<table>';
            $output .= '
                <tr id="' . plu_mi_unique_id(AD_PL_CO) . '" ' . ($bit_pl_CO?'':'style="display:none;" ') . '>
                    <td colspan="10">
                        `t`b`nMeine Kontaktliste:`b`0`n
                        <hr>
                    </td>
                </tr>
                ';

            if (!$menge)
            {
                $output.='
                    <tr id="' . plu_mi_unique_id(AD_PL_CO) . '" ' . ($bit_pl_CO?'':'style="display:none;" ') . '>
                        <td colspan="10">
                            `$Du hast noch keine Kontakte gespeichert.`0`n`n
                        </td>
                    </tr>';
            }
            else
            {
                for ($i=0;$i<$menge;$i++)
                {
                    $k = db_fetch_assoc($result);

                    if($k['online']) {
                        $bool_online_shown = true;
                    }
                    else {
                        if($bool_online_shown) {
                            $output .= '<tr id="' . plu_mi_unique_id(AD_PL_CO) . '" ' . ($bit_pl_CO?'':'style="display:none;" ') . '><td colspan="10"><hr></td></tr>';
                            $bool_online_shown = false;
                        }
                    }

                    $output.='
                <tr id="' . plu_mi_unique_id(AD_PL_CO) . '" ' . ($bit_pl_CO?'':'style="display:none;" ') . '>
                    <td><a href="mail.php?op=write&to='.$k['acctid'].'">&raquo; '.$k['name'].'</a></td>
                    <td>&nbsp;&nbsp;</td>
                    <td> '.$k['descr'].'</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>'.(($k['online'])?'`@online':'`4offline').' / '.(($k['alive'])?'`@lebend':'`4tot').'`0</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>';
                    
                    ///// SHADOWBOX
                    if($session['user']['prefs']['sdwbox'] == 1)
                    {    
                        $output.='<a href="bio.php?id='.$k['player'].'">`&[Bio]`0</a> </td>';
                    }
                    else
                    {
                        $output.='<a href="javascript:void(0);" onclick="window.open(\'bio.php?id='.$k['player'].'\',\'bio\',\'scrollbars=yes,resizable=yes,width=750,height=450\');">`&[Bio]`0</a> </td>';
                    }                
                    ///// SHADOWBOX
                    
                    $output.='<td>&nbsp;&nbsp;</td>
                    <td><a href="mail.php?op=editkontakt1&row='.$k['row_id'].'">`$[edit]`0</a> </td>
                    <td><a href="mail.php?op=delkontakt&row='.$k['row_id'].'">`$[del]`0</a> </td>
                </tr>';
                }

                $output .= '
                    <tr id="' . plu_mi_unique_id(AD_PL_CO) . '" ' . ($bit_pl_CO?'':'style="display:none;" ') . '>
                        <td colspan="10">
                            `n`n
                        </td>
                    </tr>
                ';
            }
            db_free_result($result);

            if ($session['user']['guildid'])
            {
                require_once(LIB_PATH . 'dg_funcs.lib.php');

                $sql = "
                    SELECT
                        a.acctid
                        ,a.name
                        ,a.loggedin
                        ,a.laston
                        ,a.activated
                        ,(".user_get_online().")    AS online
                        ,a.alive
                        ,a.guildfunc
                        ,a.sex
                    FROM
                        `accounts` a
                    WHERE
                        guildid        =    " . $session['user']['guildid'] . "    AND
                        acctid        !=    " . $session['user']['acctid'] . "    AND
                        guildfunc    !=    " . DG_FUNC_CANCELLED . "            AND
                        " . (DG_FUNC_MEMBERS == $session['user']['guildfunc'] || DG_FUNC_LEADER == $session['user']['guildfunc']?"1=1":"
                        guildfunc != " . DG_FUNC_APPLICANT ) . "
                    ORDER BY
                        online DESC,
                        a.guildfunc DESC,
                        a.login
                ";
                $res = db_query($sql);
                $output .= '
                    <tr id="' . plu_mi_unique_id(AD_PL_GU) . '" ' . ($bit_pl_GU?'':'style="display:none;" ') . '>
                        <td colspan="10">
                            <a name="guild"></a>
                            `t`bMeine Gilde:`b`0`n
                            <hr>
                        </td>
                    </tr>
                    ';

                while ($k = db_fetch_assoc($res))
                {
                    if($k['online']) {
                        $bool_online_shown = true;
                    }
                    else {
                        if($bool_online_shown) {
                            $output .= '<tr id="' . plu_mi_unique_id(AD_PL_GU) . '" ' . ($bit_pl_GU?'':'style="display:none;" ') . '>
                                            <td colspan="10">
                                                <hr>
                                            </td>
                                        </tr>';
                            $bool_online_shown = false;
                        }
                    }

                    $output.='
                <tr id="' . plu_mi_unique_id(AD_PL_GU) . '" ' . ($bit_pl_GU?'':'style="display:none;" ') . '>
                    <td><a href="mail.php?op=write&to='.$k['acctid'].'">&raquo; '.$k['name'].'</a></td>
                    <td>&nbsp;&nbsp;</td>
                    <td> '.$dg_funcs[$k['guildfunc']][$k['sex']].'</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>'.(($k['online'])?'`@online':'`4offline').' / '.(($k['alive'])?'`@lebend':'`4tot').'`0</td>
                    <td>&nbsp;&nbsp;</td>';
                    
                    ///// SHADOWBOX
                    if($session['user']['prefs']['sdwbox'] == 1)
                    {    
                        $output.='<td><a href="bio.php?id='.$k['acctid'].'">`&[Bio]`0</a> </td>';
                    }
                    else
                    {
                        $output.='<td><a href="javascript:void(0);" onclick="window.open(\'bio.php?id='.$k['acctid'].'\',\'bio\',\'scrollbars=yes,resizable=yes,width=750,height=450\');">`&[Bio]`0</a> </td>';
                    }                
                    ///// SHADOWBOX
                    
                    $output.='<td>&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                    <td>&nbsp;&nbsp;</td>
                </tr>
                                 ';
                }
                                 $output .= '
                    <tr id="' . plu_mi_unique_id(AD_PL_GU) . '" ' . ($bit_pl_GU?'':'style="display:none;" ') . '>
                        <td colspan="10">
                            `n`n
                        </td>
                    </tr>
                ';
            }
            //$output.='</table>';

            //$output.='`n`n';

            // Bei Superusern mit entsprechendem Recht: Team anzeigen ; )
            if($access_control->su_check(access_control::SU_RIGHT_GROTTO)) {
                //$output .= '<a name="team"></a>`n`n`n`t`bTeam:`b`0';
                //$output .= '`n`0<hr>';
                $output .= '
                    <tr id="' . plu_mi_unique_id(AD_PL_TE) . '" ' . ($bit_pl_TE?'':'style="display:none;" ') . '>
                        <td colspan="10">
                            `t`b' . getsetting('teamname', '') . ':`b`0`n
                            <hr>
                        </td>
                    </tr>
                    ';

                $sql = 'SELECT a.acctid,a.name,a.loggedin,a.laston,a.activated,a.superuser
                        FROM accounts a
                        WHERE a.acctid != '.$session['user']['acctid'].' AND a.superuser>0 AND ('.user_get_online().')
                        ORDER BY superuser DESC, login ASC;';
                $res = db_query($sql);
                $int_last_su = 0;
                if(!db_num_rows($res)) {
                    $output .= '
                        <tr id="' . plu_mi_unique_id(AD_PL_TE) . '" ' . ($bit_pl_TE?'':'style="display:none;" ') . '>
                            <td colspan="10">
                                Derzeit ist niemand aus dem Team online!
                            </td>
                        </tr>';
                }
                else {
                    //$output .= '<table>';
                    $arr_grps = user_get_sugroups();
                    while ($k = db_fetch_assoc($res)) {
                        if($int_last_su != $k['superuser'] || $int_last_su == 0)
                        {
                            $output .= '
                                <tr id="' . plu_mi_unique_id(AD_PL_TE) . '" ' . ($bit_pl_TE?'':'style="display:none;" ') . '>
                                    <td colspan="10">
                                        `b`& -- '.$arr_grps[$k['superuser']][1].':`0`b
                                    </td>
                                </tr>';
                            $int_last_su = $k['superuser'];
                        }
                        $output.='
                                <tr id="' . plu_mi_unique_id(AD_PL_TE) . '" ' . ($bit_pl_TE?'':'style="display:none;" ') . '>
                                    <td><a href="mail.php?op=write&to='.$k['acctid'].'">&raquo; '.$k['name'].'</a></td>
                                    <td>&nbsp;&nbsp;</td>
                                    <td>`@online`0</td>
                                </tr>';
                    }
                    //$output .= '</table>';
                }
            }
            // END Teamanzeige

            $output .= '</table>';
        }
        break;
        //Eingabe eines neuen Kontakts in das Adressbuch
    case 'neuerkontakt':
        {
            $str_subtitle = 'Neuer Kontakt';
            if ($_GET['name'])
            {
                $_GET['name'] = addslashes($_GET['name']);
                $output .= '<form action="mail.php?op=neuerkontakt2" method="POST">
                `2Name:`0 <input name="to" value="'.$_GET['name'].'"> <input type="submit" class="button" value="Kontakt suchen"></form>';
            }
            else
            {
                $output .= '`0
                    ' . form_header('?op=neuerkontakt2') . '
                        `2Name:`0 ' . jslib_autocomplete_name('to', false, true) . '<input type="submit" class="button" value="Kontakt suchen">
                    </form>
                ';
            }
        }
        break;
        //Suche des Kontakts in der Datenbank
    case 'neuerkontakt2':
        {
            $str_subtitle = 'Neuer Kontakt';
            $sql = 'SELECT COUNT(*) AS anzahl FROM yom_adressbuch WHERE acctid='.$session['user']['acctid'];
            $res = db_query($sql);
            $a = db_fetch_assoc($res);

            db_free_result($res);
            if($a['anzahl'] >= getsetting('max_yom_contacts',1))
            {
                $output.='`4Du hast mit '.$a['anzahl'].' bereits das Limit von '.getsetting('max_yom_contacts',1).' Kontakten erreicht!';
            }
            else
            {
                if($_POST['to'])
                {
                    $to = stripslashes($_POST['to']);

                    $output.='`2Name:`0 ';
                    $string = str_create_search_string($to);
                    $sql = 'SELECT name,acctid FROM accounts WHERE name LIKE \''.addslashes($string).'\' ORDER BY IF(login="'.addslashes($to).'",1,0) DESC, login ASC';
                    $result = db_query($sql);
                }
                else
                {
                    $sql = 'SELECT name,acctid FROM accounts WHERE acctid='.(int)$_GET['id'];
                    $result = db_query($sql);
                }

                $output .= '<form action="mail.php?op=neuerkontakt3" method="POST">';
                $int_rows = db_num_rows($result);
                if($int_rows == 0)
                {
                    $output.='`4Es gibt keinen Spieler mit diesem Namen!`0';
                    db_free_result($result);
                }
                else
                {
                    if ($int_rows==1)
                    {
                        $row = db_fetch_assoc($result);
                        $output .= '<input type="hidden" name="to" value="'.$row['acctid'].'">';
                        $output.='`^'.$row['name'].'`n';
                    }
                    else
                    {
                        $output .= '<select name="to">';
                        for ($i=0;$i<$int_rows;$i++)
                        {
                            $row = db_fetch_assoc($result);
                            $output.='<option value="'.$row['acctid'].'">';
                            $output.= preg_replace('/[`]./','',$row['name']);
                        }
                        $output.='</select><br>`n';
                    }
                    db_free_result($result);
                    $output.='<br>`&Beschreibung [max.80]:`0<input type="text" name="descr" maxlenght="80" size="13">
                <br><br><input type="submit" name="s1" value="Kontakt speichern" class="button"></form><br />';
                }

            }
        }
        break;
        //Speichern des Kontakts im Adressbuch
    case 'neuerkontakt3':
        {
            $str_subtitle = 'Neuer Kontakt';
            $sql = 'SELECT COUNT(*) as menge FROM yom_adressbuch WHERE acctid='.$session['user']['acctid'].' AND player='.(int)$_POST['to'];
            $result = db_query($sql);
            $anzahl = mysql_result($result,0,'menge');
            if ($anzahl>0)
            {
                $output.='`n`n`b`$Dieser Kontakt ist bereits gespeichert!`0`b';
            }
            else
            {
                $descr = mysql_escape_string($_POST['descr']);
                $sql='INSERT INTO yom_adressbuch SET acctid='.$session['user']['acctid'].', player='.(int)$_POST['to'].', descr=\''.$descr.'\'';
                db_query($sql);
                $output.='`n`n`b`@Der Kontakt wurde gespeichert.`0`b';
            }
        }
        break;
        //Editieren des Kontakts, Ausgabe der Maske
    case 'editkontakt1':
        {
            $str_subtitle = 'Adressbuch editieren';
            if($_GET['row'])
            {
                $sql = "SELECT y.row_id,y.descr,a.name FROM yom_adressbuch y, accounts a WHERE y.row_id=".(int)$_GET['row']." AND a.acctid=y.player";
                $res = db_query($sql);

                if(db_num_rows($res))
                {
                    $k = db_fetch_assoc($res);

                    $output.='<br>`2Name:`0 '.$k['name'].'`0
                <form action="mail.php?op=editkontakt2&row='.$k['row_id'].'" method="POST">
                <br>Beschreibung [max.80]:`0<input type="text" name="descr" maxlenght="80" size="13" value="'.$k['descr'].'">
                <br><br><input type="submit" name="s1" value="Kontakt speichern">`n
                </form>`n';
                }
                db_free_result($res);
            }
        }
        break;
        //Speichern des editierten Kontaktes
    case 'editkontakt2':
        {
            $str_subtitle = 'Adressbuch editieren';
            $descr = mysql_escape_string($_POST['descr']);
            $sql='UPDATE yom_adressbuch SET descr = \''.$descr.'\' WHERE row_id='.(int)$_GET['row'];
            db_query($sql);
            $output.='`n`n`b`@Der Kontakt wurde gespeichert.`0`b';
        }
        break;
        //Löschen eines Kontaktes aus dem Adressbuch
    case 'delkontakt':
        {
            $str_subtitle = 'Kontakt aus Adressbuch löschen';
            $sql='DELETE FROM yom_adressbuch WHERE row_id='.(int)$_GET['row'].' LIMIT 1 ';
            db_query($sql);
            $output.='`n`n`b`@Der Kontakt wurde gelöscht.`0`b';
        }
        break;

        //Wenn keine Operation angegeben wurde
    default:
        {
            $str_subtitle = 'Posteingang`0';

            //Automatische Weiterleitung anzeigen wenn angeschaltet
            if($access_control->su_check(access_control::SU_RIGHT_FORWARD_YOM_TO_SUPERUSER) && getsetting('forward_yom_admin_enable',1) && $session['user']['prefs']['forward_yom_to_superuser'] != -1 && !empty($session['user']['prefs']['forward_yom_to_superuser']))
            {
                $session['message'] = '`c`$Für deine Tauben ist eine Superuserweiterleitung aktiviert`0`c`n'.$session['message'];
            }

            //Ausgabe einer Statusnachricht wenn diese vorhanden ist
            if(!empty($session['message'])) {
                $output.=''.$session['message'].'`n';
                //Statusnachricht löschen
                unset($session['message']);
            }
            if(isset($session['refresh_maillink'])) {
                // Mails in Hauptfenster aktualisieren
                $output .= '<script type="text/javascript">window.opener.document.getElementById("MAILBOXLINK").innerHTML = "'.addslashes(maillink(true)).'";</script>';
                unset($session['refresh_maillink']);
            }

            if(isset($session['refresh_minilink']) && $session['user']['prefs']['minimail']) {
                // Minimails in Hauptfenster aktualisieren
                $output .= '<script>window.opener.document.getElementById("MINILINK").innerHTML = "'.addslashes(minimail(true)).'";</script>';
                unset($session['refresh_minilink']);
            }

            $sql = 'SELECT mail.subject,mail.messageid,accounts.name,mail.msgfrom,mail.seen,mail.sent, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE mail.msgto='.$session['user']['acctid'].' AND mail.archived = 0 ORDER BY mail.seen,mail.sent DESC';
            $result = db_query($sql);

            // Anzahl der ungelesenen Nachrichten bestimmen
            $int_unseen = 0;

            //Anzahl der Tupel bestimmen
            $int_mails = db_num_rows($result);
            //Wenn mindestens eine Mail vorhanden ist
            if ($int_mails>0)
            {
                $output.='<form action="mail.php?op=process" method="POST">';
                $output.='<table>';

                $recent_date = date('dm',time());

                //Stelle jede Mail dar
                for ($i=0;$i<$int_mails;$i++)
                {
                    $row = db_fetch_assoc($result);
                    //Falls die Nachricht von System stammt
                    if ((int)$row['msgfrom']==0)
                    {
                        //Stammt die nachricht vom System?
                        if ((int)$row['petitionid']==0)
                        {
                            $row['name']='`i`^System`0`i';
                        }
                        //Stammt die nachricht von einem Admin
                        else
                        {
                            $row['name']='`i`^Admin`0`i';
                        }
                    }

                    $senttime = strtotime($row['sent']);

                    if( date('dm',$senttime) == $recent_date )
                    {
                        $sent = 'Heute, '.date('H:i',$senttime);
                    }
                    else
                    {
                        $sent = date(MAIL_DATE_FORMAT,$senttime);
                    }

                    if(!$row['seen']) {
                        $int_unseen++;
                    }

                    $row['subject'] = stripslashes($row['subject']);

                    //Gib alle Details aus
                    $output.='
                    <tr>
                        <td nowrap>
                        <input type="checkbox" name="msg[]" value="'.$row['messageid'].'">
                        <img src="images/'.($row['seen']?'old':'new').'scroll.GIF" width="16" height="16" alt="'.($row['seen']?'Alt':'Neu').'">
                        </td>
                        <td><a href="mail.php?op=read&id='.$row['messageid'].'">'.$row['subject'].'</a></td>
                        <td><a href="mail.php?op=read&id='.$row['messageid'].'">'.$row['name'].'</a></td>
                        <td><a href="mail.php?op=read&id='.$row['messageid'].'">'.($row['seen']?'':'`^').$sent.'</a></td>
                    </tr>';
                }
                $output.='</table>';
                //Bei einem Klick auf den Button wird jede Mail markiert
                $output.='<input type="button" value="Alle markieren" class="button" onClick="';
                $output.='for(i=0;i<document.getElementsByName(\'msg[]\').length;i++) {document.getElementsByName(\'msg[]\')[i].checked=true;}';
                $output.='">';

                $output.='<input type="button" value="Alle abwählen" class="button" onClick="';
                $output.='for(i=0;i<document.getElementsByName(\'msg[]\').length;i++) {document.getElementsByName(\'msg[]\')[i].checked=false;}';
                $output.='">';

                $output.='
                <br />
                <select name="process_type">
                    <option value="sys">Lösche Systemnachrichten</option>
                    <option value="ugdel">Lösche ungelesene Nachrichten</option>
                    <option value="gdel">Lösche gelesene Nachrichten</option>
                    <option>----------</option>
                    <option value="unread_marked" selected>Setze markierte Nachrichten auf Ungelesen</option>
                    <option value="delete_marked" selected>Lösche markierte Nachrichten</option>
                    ';

                //Email - Option nur zeigen wenn sie angeschaltet ist
                if( $access_control->su_lvl_check(1) || getsetting('message2mail_activated',false)==true)
                {
                    $output.='<option value="message2mail">Sende markierte Nachrichten an deine EMail Adresse</option>';
                }

                //Email - Option nur zeigen wenn sie angeschaltet ist
                if( $access_control->su_lvl_check(1) || getsetting('archive_yom_anabled',1)==1)
                {
                    $output.='<option>----------</option>';
                    $output.='<option value="archive_marked">Archiviere die markierten Nachrichten</option>';
                }

                $output .= '

                    </select>
                    <input type="submit" class="button" value="Los!">
                </form>';
            }
            else
            {
                $output.='`iDu hast momentan keine Mails!`i';
            }
            db_free_result($result);

            //Zeige das Nachrichtenlimit an
            //Für Admins
            if ($access_control->su_lvl_check(1))
            {
                if($int_unseen>=getsetting('modinboxlimit',50))
                {
                    $output.='`n`n`b`4Du hast '.$int_unseen.' ungelesene Nachrichten in deiner Mailbox.`nDu kannst höchstens '.getsetting('modinboxlimit',50).' ungelesene Nachrichten hier speichern. Solange Du zu viele Nachrichten hast, kann dir niemand etwas schicken!`n';
                }
                else
                {
                    $output.='`n`n`iDu hast insgesamt '.$int_mails.' Nachrichten, davon '.$int_unseen.' ungelesen.`nDu kannst höchstens '.getsetting('modinboxlimit',50).' ungelesene Nachrichten hier speichern.`nNachrichten werden nach '.getsetting('modoldmail',14).' Tagen gelöscht.`i';
                }
            }
            //Für normale Benutzer
            else
            {
                if($int_unseen>=getsetting('inboxlimit',50))
                {
                    $output.='`n`n`b`4Du hast '.$int_unseen.' Nachrichten in deiner Mailbox.`nDu kannst höchstens '.getsetting('inboxlimit',50).' ungelesene Nachrichten hier speichern. Solange Du zu viele Nachrichten hast, kann dir niemand etwas schicken!`n';
                }
                else
                {
                    $output.='`n`n`iDu hast insgesamt '.$int_mails.' Nachrichten, davon '.$int_unseen.' ungelesen.`nDu kannst höchstens '.getsetting('inboxlimit',50).' ungelesene Nachrichten hier speichern.`nNachrichten werden nach '.getsetting('oldmail',14).' Tagen gelöscht.`i';
                }
            }

        }
        break;
}

//Header einstellen
$str_mailname = 'Taubenschlag von '.getsetting('townname','Atrahor').'';

popup_header($str_mailname, true);
$int_size_x = 700;
$int_size_y = 550;
//Erste Tabelle auf der Seite einstellen
$main_output .= '
<table width="100%" border="0">
    <tr>
        <td><a href="mail.php" class="motd">Inbox</a></td>
        <td><a href="mail.php?op=address" class="motd">Mail schreiben</a></td>';

//Adressbuch anzeigen wenn es eingeschaltet ist, oder wenn der User ein Admin ist
if(getsetting('show_yom_contacts',1)==1 || $access_control->su_lvl_check(1))
{
    //$int_size_x+=200;
    $main_output .= '
        <td><a href="mail.php?op=buch" class="motd">Adressbuch</a></td>';
}
$main_output .= '
        <td><a href="mail.php?op=outbox" class="motd">Mail zurückrufen</a></td>';
if(getsetting('archive_yom_anabled',1)==1 || $access_control->su_lvl_check(1))
{
    //$int_size_x+=200;
    $main_output .= '
            <td><a href="mail.php?op=archive" class="motd">Archiv</a></td>';
}
$main_output.='
    </tr>
</table>
<div>`c`b`&'.$str_subtitle.'`0`b`n~~~`n`c</div>
';

$main_output .= '<script language="javascript" type="text/javascript">window.resizeTo('.$int_size_x.','.$int_size_y.')</script>';

//Dieser Teil hier ist lotgd.drachenserver.de spezifisch, er enthält etwas Werbung
$tail_output .= '
<div style="text-align:center; margin:30px;">
    <script type="text/javascript"><!--
    google_ad_client = "pub-3924728103525542";
    google_ad_width = 468;
    google_ad_height = 60;
    google_ad_format = "468x60_as";
    google_ad_type = "text_image";
    google_ad_channel ="8622662812";
    google_color_border = "333333";
    google_color_bg = "000000";
    google_color_link = "FFFFFF";
    google_color_url = "999999";
    google_color_text = "CCCCCC";
    //--></script>
    <script type="text/javascript"
        src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
    </script>
</div>';


//Der gesammelte HTML Quelltext wird jetzt noch einmal durch den Parser gejagd, damit alle
//Farben und Formatierungen übernommen werden
$output = appoencode($main_output.$output.$tail_output,true);

// Maskierte Formatierungstags wieder zurückverwandeln
// Workaround, nicht optimal.
$output = str_replace('{#96}','`',$output);

//Anschließend wird die Seite geschlossen!
popup_footer(true);
?>

