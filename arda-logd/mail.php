<?php
require_once "common.php";

// (C) COPYRIGHT 2005-2009 Kevz
output('<script language="JavaScript"> 
<!-- 
function CountMax($var) { 
var wert,max; 
max = $var; 
wert = max-document.mail.body.value.length; 
if (wert < 0) { 
alert("Es dürfen nicht mehr als " + max + " Zeichen eingegeben werden!"); 
document.mail.body.value = document.mail.body.value.substring(0,max); 
wert = max-document.mail.body.value.length; 
document.mail.rv_counter.value = wert; 

} else { 
document.mail.rv_counter.value = max - document.mail.body.value.length; 
} 
} 
//--> 
</script> ',true);
/**************************************
 *                                    *
 * Kopfdaten, stehen immer da         *
 *                                    *
 *************************************/

popup_header("Ye Olde Poste Office");
output("<a href='mail.php' class='motd'>Inbox</a><a href='mail.php?op=address' class='motd'>Mail schreiben</a>`n`n",true);
//output("<a href='mail.php?op=buch' class='motd'>Adressbuch</a>",true);  // www.plueschdrache.de Adressbuch 
//output("<a href='mail.php?op=block' class='motd'>Ignorierliste</a>`n`n",true); // www.anaras.ch Blockliste
output("<a href='mail.php?op=outbox' class='motd'>Outbox</a>`n`n",true);

/**************************************
 *                                    *
 * Wir gucken jetzt mal was genau     *
 * gemacht wird                       *
 *                                    *
 *************************************/

switch($_GET['op']) {


/**************************************
 *                                    *
 * Die einzelnen Optionen,            *
 * die ausgeführt werden              *
 *                                    *
 *************************************/


case "del":
    /**************************************
     *                                    *
     * Eine Mail löschen                  *
     *                                    *
     *************************************/
    $sql = "DELETE FROM mail WHERE msgto='".$session[user][acctid]."' AND messageid='$_GET[id]'";
    db_query($sql);
    header("Location: mail.php");
    exit();    

    break;

case "process":
    /**************************************
     *                                    *
     * mehrere Mails löschen              *
     *                                    *
     *************************************/

    // keine Mails ausgewählt
    if (!is_array($_POST['msg']) || count($_POST['msg'])<1){
        $session['message'] = "`\$`bDu kannst 0 Nachrichten nicht löschen! Was das heißt? Du hast \"Markierte löschen\" geklickt, aber es sind keine Nachrichten markiert. Was für eine Welt ist das nur, in der Leute Knöpfe ohne Funktion drücken?!?`b`0";
        header("Location: mail.php");

    // Mails ausgewählt
    } else {
        $sql = "DELETE FROM mail WHERE msgto='".$session[user][acctid]."' AND messageid IN ('".join("','",$_POST[msg])."')";
        db_query($sql);
        header("Location: mail.php");
        exit();    
    }

    break;


case "send":
    /**************************************
     *                                    *
     * Eine Mail senden                   *
     *                                    *
     *************************************/

    if (empty($_POST['petitionid'])) {
        $sql = "SELECT acctid FROM accounts WHERE login='$_POST[to]'";
        $result = db_query($sql);
        if (db_num_rows($result)>0){
            $row1 = db_fetch_assoc($result);
            $sql = "SELECT count(messageid) AS count FROM mail WHERE msgto='".$row1[acctid]."' AND seen=0";
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            $sqlb = "SELECT blocked FROM block WHERE blocker='".$row1[acctid]."' AND blocked='".$session[user][acctid]."'";
            $resultb = db_query($sqlb);
            $block = db_fetch_assoc($resultb);
            $sqlb1 = "SELECT blocked FROM block WHERE blocker='".$session[user][acctid]."' AND blocked='".$row1[acctid]."'";
            $resultb1 = db_query($sqlb1);
            $block1 = db_fetch_assoc($resultb1);
            if(db_num_rows($resultb)>0){
                output("`\$`bDiese Person hat dich geblockt!`b`n`n`0");

            } else if(db_num_rows($resultb1)>0){
                output("`\$`bDu hast diese Person geblockt!`b`n`n`0");

            } else if ($row[count]>getsetting("inboxlimit",50)) {
                output("Die Mailbox dieser Person ist voll! Du kannst ihr keine Nachricht schicken.");

            }else{
                $_POST['subject']=closetags(str_replace("`n","",$_POST['subject']),'`c`i`b');
                $_POST['body']=str_replace("`n","\n",$_POST['body']);
                $_POST['body']=str_replace("\r\n","\n",$_POST['body']);
                $_POST['body']=str_replace("\r","\n",$_POST['body']);
                $_POST['body']=addslashes(substr(stripslashes($_POST['body']),0,(int)getsetting("mailsizelimit",1024))); 
                $_POST['body'] = closetags($_POST['body'],'`c`i`b');
                systemmail($row1['acctid'],$_POST['subject'],$_POST['body'],$session['user']['acctid']);
                output("Deine Nachricht wurde gesendet!`n");
            }
        }else{
            output("Konnte den Empfänger nicht finden. Bitte versuche es nochmal.`n");
        }
    } else {
        $sql = "SELECT count(messageid) AS count FROM petitionmail WHERE petitionid='$_POST[petitionid]' AND msgto='".$session['user']['acctid']."'";
        $row = db_fetch_assoc(db_query($sql));
        if ($row['count']==0) {
            output('Du kannst nur zu deinen eigenen Anfragen etwas schreiben!');

        } else {
            $_POST['subject']=closetags(str_replace("`n","",$_POST['subject']),'`c`i`b');
            $_POST['body']=str_replace("`n","\n",$_POST['body']);
            $_POST['body']=str_replace("\r\n","\n",$_POST['body']);
            $_POST['body']=str_replace("\r","\n",$_POST['body']);
            $_POST['body']=substr($_POST['body'],0,(int)getsetting("mailsizelimit",1024));
            $_POST['body'] = closetags($_POST['body'],'`c`i`b');
            petitionmail($_POST['subject'],$_POST['body'],$_POST['petitionid'],$session['user']['acctid']);
            output("Deine Nachricht wurde gesendet!`n");

        }
    }
    break;

/**************************************
 *                                    *
 * Die Standardansichten              *
 *                                    *
 *************************************/


default:
    /**************************************
     *                                    *
     * Posteingang                        *
     *                                    *
     **************************************/
    output("`b`iMail Box`i`b");
    output($session['message']);
    $session['message']="";
    $sql = "SELECT mail.subject,mail.messageid,accounts.name,mail.msgfrom,mail.seen,mail.sent, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE mail.msgto=\"".$session[user][acctid]."\" ORDER BY mail.seen,mail.sent";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        output("<form action='mail.php?op=process' method='POST'><table>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);

            if ((int)$row[msgfrom]==0) {
                if ((int)$row['petitionid']==0)    $row[name]="`i`^System`0`i";
                else $row['name'] = "`i`^Admin`0`i";
            }

            output("<tr>",true);
            output("<td nowrap><input id='checkbox$i' type='checkbox' name='msg[]' value='$row[messageid]'><img src='images/".($row[seen]?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row[seen]?"Alt":"Neu")."'></td>",true);
            output("<td><a href='mail.php?op=read&id=$row[messageid]'>",true);
            output($row[subject]);
            output("</a></td><td><a href='mail.php?op=read&id=$row[messageid]'>",true);
            output($row[name]);
            output("</a></td><td><a href='mail.php?op=read&id=$row[messageid]'>".date("M d, h:i a",strtotime($row[sent]))."</a></td>",true);
            output("</tr>",true);
        }

        output("</table>",true);

        $out="<input type='button' value='Alle markieren' class='button' onClick='";
        for ($i=$i-1;$i>=0;$i--){
            $out.="document.getElementById(\"checkbox$i\").checked=true;";
        }
        $out.="'>";

        output($out,true);
        output("<input type='submit' class='button' value='Markierte löschen'>",true);
        output("</form>",true);

    }else{
        output("`iOoooh, du hast keine Mails. Wie schade.`i");

    }

    output("`n`n`iDu hast ".db_num_rows($result)." Nachrichten in deiner Mailbox`nDu kannst höchstens ".getsetting('inboxlimit',50)." Nachrichten hier speichern.`nNachrichten werden nach ".getsetting("oldmail",14)." Tagen gelöscht.");
    break;
    
    /**********************************
    *      Postausgang                *
    **********************************/

/***********************************************
*Diese Box darf nicht entfernt werden!        *
*-------------------------------------        *
*Outbox von Tweety und Kelko                  *
*                                             *
*www.tugc-lotgd.6x.to                         *
***********************************************/
case "outbox":

    output("`b`iAusgang`i`b");
    output($session['message']);
    $session['message']="";
    $sql = "SELECT mail.subject,mail.messageid,accounts.name,mail.msgfrom,mail.seen,mail.sent, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) INNER JOIN accounts ON accounts.acctid=mail.msgto WHERE mail.msgfrom=\"".$session[user][acctid]."\" ORDER BY mail.seen,mail.sent";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        output("<table>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);

            output("<tr>",true);
            output("<td nowrap><img src='images/".($row[seen]?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row[seen]?"Alt":"Neu")."'></td>",true);
            output("<td><a href='mail.php?op=readSend&id=$row[messageid]'>",true);
            output($row[subject]);
            output("</a></td><td><a href='mail.php?op=readSend&id=$row[messageid]'>",true);
            output($row[name]);
            output("</a></td><td><a href='mail.php?op=readSend&id=$row[messageid]'>".date("M d, h:i a",strtotime($row[sent]))."</a></td>",true);
            output("</tr>",true);
        }

        output("</table>",true);

            output("</form>",true);

       }else{
            output("`iOoooh, du hast keine Mails. Wie schade.`i");

       }

       output("`n`n`iDu hast ".db_num_rows($result)." Nachrichten in deiner Mailbox`nDu kannst höchstens ".getsetting('outboxlimit',50)." Nachrichten hier speichern.`nNachrichten werden nach ".getsetting("oldmail",14)." Tagen gelöscht.");

    break;
case "readSend":
    /**************************************
     *                                    *
     * Mails ausgang lesen                         *
     *                                    *
     **************************************/

    $sql = "SELECT mail.*,accounts.name, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE mail.messageid=\"".$_GET[id]."\"";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);

        output("`b`2Empfänger:`b `^$row[name]`n");
        output("`b`2Betreff:`b `^$row[subject]`n");
        output("`b`2Gesendet:`b `^{$row['sent']}`n");
        output("<img src='images/uscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output(str_replace("\n","`n","$row[body]"));
        output("`n<img src='images/lscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);

    }else{
        output("Uff, so eine Nachricht wurde nicht gefunden!");

    }
    break;
case "read":
    /**************************************
     *                                    *
     * Mail lesen                         *
     *                                    *
     **************************************/

    $sql = "UPDATE mail SET seen=1 WHERE  msgto=\"".$session[user][acctid]."\" AND messageid=\"".$_GET[id]."\"";
    db_query($sql);
    $sql = "SELECT mail.*,accounts.name, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE mail.msgto=\"".$session[user][acctid]."\" AND mail.messageid=\"".$_GET[id]."\"";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);

        if ((int)$row[msgfrom]==0) {
            if ((int)$row['petitionid']==0)    $row[name]="`i`^System`0`i";
            else $row['name'] = "`i`^Admin`0`i";
        }        

        output("`b`2Absender:`b `^$row[name]`n");
        output("`b`2Betreff:`b `^$row[subject]`n");
        output("`b`2Gesendet:`b `^{$row['sent']}`n");
        output("<img src='images/uscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output(str_replace("\n","`n","$row[body]"));
        output("`n<img src='images/lscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output("<a href='mail.php?op=write&replyto=$row[messageid]' class='motd'>Antworten</a><a href='mail.php?op=del&id=$row[messageid]' class='motd'>Löschen</a>",true);

    }else{
        output("Uff, so eine Nachricht wurde nicht gefunden!");

    }
    break;


case "address":
    /**************************************
     *                                    *
     * Empfänger wählen                   *
     *                                    *
     **************************************/
    output("<form action='mail.php?op=write' method='POST'>",true);
    output("`b`2Empfänger:`b`n");
    output("`2<u>A</u>n: <input name='to' accesskey='a'> <input type='submit' class='button' value='Search'></form>",true);

    break;

case "write":
    /**************************************
     *                                    *
     * Eine Mail schreiben                *
     *                                    *
     **************************************/

    $subject="";
    $body="";
    output("<form action='mail.php?op=send' method='POST' name='mail'>",true);
    if ($_GET[replyto]!=""){
        $sql = "SELECT mail.body,mail.subject,accounts.login,accounts.name, petitionmail.petitionid FROM mail LEFT JOIN petitionmail USING(messageid) LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE mail.msgto=\"".$session[user][acctid]."\" AND mail.messageid=\"".$_GET[replyto]."\"";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
            if ($row[login]=="" && (int)$row['petitionid']==0) {
                output("Du kannst nicht auf eine Systemnachricht antworten.`n");
                $row=array();
            }
        }else{
            output("Uff, so eine Nachricht wurde nicht gefunden!`n");
        }
    }
    if ($_GET[to]!=""){
        $sql = "SELECT login,name FROM accounts WHERE login=\"$_GET[to]\"";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
        }else{
            output("Konnte diese Person nicht finden.`n");
        }
    }
    if (is_array($row)){
        if ($row[subject]!=""){
            $subject=$row[subject];
            if (substr($subject,0,4)!="RE: ") $subject="RE: $subject";
        }
        if ($row[body]!=""){
            $body="\n\n---Original Message---\n".$row[body];
        }
    }
    if ($row['petitionid']>0) {
        output("`2An: `^`iAdmin`i`n");
    } elseif ($row[login]!=""){
        output("<input type='hidden' name='to' value=\"".HTMLEntities($row[login])."\">",true);
        output("`2An: `^$row[name]`n");
    }else{
        output("`2An: ");
        $string="%";
        for ($x=0;$x<strlen($_POST['to']);$x++){
            $string .= substr($_POST['to'],$x,1)."%";
        }
        $sql = "SELECT login,name FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY login";
        $result = db_query($sql);
        if (db_num_rows($result)==1){
            $row = db_fetch_assoc($result);
            output("<input type='hidden' name='to' value=\"".HTMLEntities($row[login])."\">",true);
            output("`^$row[name]`n");
        }else{
            output("<select name='to'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".HTMLEntities($row[login])."\">",true);
                output(preg_replace("/[`]./","",$row[name]));
            }
            output("</select>`n",true);
        }
    }
    output("`2Betreff:");
    $output.=("<input name='subject' value=\"".HTMLEntities($subject).HTMLEntities(stripslashes($_GET['subject']))."\">Noch <input typ='hidden' name='rv_counter' size='".strlen(getsetting('mailsizelimit' ,0))."' value='".getsetting('mailsizelimit' ,0)."' readonly> Zeichen übrig.");
    output("`n`2Text:`n");
    $output.="<textarea name='body' class='input' cols='40' rows='9' OnFocus='CountMax(".getsetting('mailsizelimit' ,0).");' OnClick='CountMax(".getsetting('mailsizelimit' ,0).");' OnChange='CountMax(".getsetting('mailsizelimit' ,0).");' onKeydown='CountMax(".getsetting('mailsizelimit' ,0).");' onKeyup='CountMax(".getsetting('mailsizelimit' ,0).");' wrap='virtual'>".HTMLEntities($body).HTMLEntities(stripslashes($_GET['body']))."</textarea><br>";
    output("<input type='submit' class='button' value='Senden'>`n",true);
    if ($row['petitionid']>0) output('<input type="hidden" name="petitionid" value="'.$row['petitionid'].'">',true);
    output("</form>",true);
    break;

//Ende vom switch
}

/**************************************
 *                                    *
 * Fußdaten, stehen immer da          *
 *                                    *
 *************************************/

popup_footer();
?>