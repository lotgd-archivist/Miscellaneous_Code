
<?
require_once "common.php";
$sql = "DELETE FROM mail WHERE sent<'".date("Y-m-d H:i:s",strtotime("-".getsetting("oldmail",14)."days"))."'";
db_query($sql);


if($_GET[op]=="del"){
    $sql = "DELETE FROM mail WHERE msgto='".$session[user][acctid]."' AND messageid='$_GET[id]'";
    db_query($sql);
    header("Location: mail.php");
    exit();    
}elseif($_GET[op]=="process"){
    if (!is_array($_POST['msg']) || count($_POST['msg'])<1){
        $session['message'] = "`\$`bDu kannst 0 Nachrichten nicht löschen! Was das heißt? Du hast \"Markierte löschen\" geklickt, aber es sind keine Nachrichten markiert. Was für eine Welt ist das nur, in der Leute Knöpfe ohne Funktion drücken?!?`b`0";
        header("Location: mail.php");
    }else{
        $sql = "DELETE FROM mail WHERE msgto='".$session[user][acctid]."' AND messageid IN ('".join("','",$_POST[msg])."')";
        db_query($sql);
        header("Location: mail.php");
        exit();    
    }
}

popup_header("Ye Olde Poste Office");
output("<a href='mail.php' class='motd'>Inbox</a><a href='mail.php?op=address' class='motd'>Mail schreiben</a>`n`n",true);

if($_GET[op]=="send"){
    $sql = "SELECT acctid FROM accounts WHERE login='$_POST[to]'";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        $row1 = db_fetch_assoc($result);
        $sql = "SELECT count(messageid) AS count FROM mail WHERE msgto='".$row1[acctid]."' AND seen=0";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if ($row[count]>getsetting("inboxlimit",50)) {
            output("Die Mailbox dieser Person ist voll! Du kannst ihr keine Nachricht schicken.");
        }else{
            $_POST[subject]=str_replace("`n","",$_POST[subject]);
            $_POST[body]=str_replace("`n","\n",$_POST[body]);
            $_POST[body]=str_replace("\r\n","\n",$_POST[body]);
            $_POST[body]=str_replace("\r","\n",$_POST[body]);
            $_POST[body]=substr($_POST[body],0,(int)getsetting("mailsizelimit",1024));
            systemmail($row1[acctid],$_POST[subject],$_POST[body],$session[user][acctid]);
            output("Deine Nachricht wurde gesendet!`n");
        }
    }else{
        output("Konnte den Empfänger nicht finden. Bitte versuche es nochmal.`n");
    }
    $_GET['op']="";
}

if ($_GET[op]==""){
    output("`b`iMail Box`i`b");
    output($session['message']);
    $session['message']="";
    $sql = "SELECT subject,messageid,accounts.name,msgfrom,seen,sent FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE msgto=\"".$session[user][acctid]."\" ORDER BY seen,sent";
    $result = db_query($sql);
    if (db_num_rows($result)>0){
        output("<form action='mail.php?op=process' method='POST'><table>",true);
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            if ((int)$row[msgfrom]==0) $row[name]="`i`^System`0`i";
            output("<tr>",true);
            output("<td nowrap><input id='checkbox$i' type='checkbox' name='msg[]' value='$row[messageid]'><img src='images/".($row[seen]?"old":"new")."scroll.GIF' width='16' height='16' alt='".($row[seen]?"Alt":"Neu")."'></td>",true);
            output("<td><a href='mail.php?op=read&id=$row[messageid]'>",true);
            output($row[subject]);
            output("</a></td><td><a href='mail.php?op=read&id=$row[messageid]'>",true);
            output($row[name]);
            output("</a></td><td><a href='mail.php?op=read&id=$row[messageid]'>".date("M d, h:i a",strtotime($row[sent]))."</a></td>",true);
            output("</tr>",true);
        }
        output("</table>",true);
        $out="<input type='button' value='Alle markieren' class='button' onClick='";
        for ($i=$i-1;$i>=0;$i--){
            $out.="document.getElementById(\"checkbox$i\").checked=true;";
        }
        $out.="'>";
        output($out,true);
        output("<input type='submit' class='button' value='Markierte löschen'>",true);
        output("</form>",true);
    }else{
        output("`iOoooh, du hast keine Mails. Wie schade.`i");
    }
    output("`n`n`iDu hast ".db_num_rows($result)." Nachrichten in deiner Mailbox`nDu kannst höchstens ".getsetting('inboxlimit',50)." Nachrichten hier speichern.`nNachrichten werden nach ".getsetting("oldmail",14)." Tagen gelöscht.");
}elseif ($_GET[op]=="read"){
    $sql = "UPDATE mail SET seen=1 WHERE  msgto=\"".$session[user][acctid]."\" AND messageid=\"".$_GET[id]."\"";
    db_query($sql);
    $sql = "SELECT mail.*,accounts.name FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE msgto=\"".$session[user][acctid]."\" AND messageid=\"".$_GET[id]."\"";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0){
        $row = db_fetch_assoc($result);
        if ($row[msgfrom]=="") $row[name]="`iSystem`i";
        output("`b`2Absender:`b `^$row[name]`n");
        output("`b`2Betreff:`b `^$row[subject]`n");
        output("`b`2Gesendet:`b `^{$row['sent']}`n");
        output("<img src='images/uscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output(str_replace("\n","`n","$row[body]"));
        output("`n<img src='images/lscroll.GIF' width='182' height='11' alt='' align='center'>`n",true);
        output("<a href='mail.php?op=write&replyto=$row[messageid]' class='motd'>Antworten</a><a href='mail.php?op=del&id=$row[messageid]' class='motd'>Löschen</a>",true);
    }else{
        output("Uff, so eine Nachricht wurde nicht gefunden!");
    }
}elseif($_GET['op']=="address"){
    output("<form action='mail.php?op=write' method='POST'>",true);
    output("`b`2Empfänger:`b`n");
    output("`2<u>A</u>n: <input name='to' accesskey='a'> <input type='submit' class='button' value='Search'></form>",true);
}elseif($_GET[op]=="write"){
    $subject="";
    $body="";
    output("<form action='mail.php?op=send' method='POST'>",true);
    if ($_GET[replyto]!=""){
        $sql = "SELECT mail.body,mail.subject,accounts.login,accounts.name FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE msgto=\"".$session[user][acctid]."\" AND messageid=\"".$_GET[replyto]."\"";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
            if ($row[login]=="") {
                output("Du kannst nicht auf eine Systemnachricht antworten.`n");
                $row=array();
            }
        }else{
            output("Uff, so eine Nachricht wurde nicht gefunden!`n");
        }
    }
    if ($_GET[to]!=""){
        $sql = "SELECT login,name FROM accounts WHERE login=\"$_GET[to]\"";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>0){
            $row = db_fetch_assoc($result);
        }else{
            output("Konnte diese Person nicht finden.`n");
        }
    }
    if (is_array($row)){
        if ($row[subject]!=""){
            $subject=$row[subject];
            if (substr($subject,0,4)!="RE: ") $subject="RE: $subject";
        }
        if ($row[body]!=""){
            $body="\n\n---Original Message---\n".$row[body];
        }
    }
    if ($row[login]!=""){
        output("<input type='hidden' name='to' value=\"".HTMLEntities($row[login])."\">",true);
        output("`2An: `^$row[name]`n");
    }else{
        output("`2An: ");
        $string="%";
        for ($x=0;$x<strlen($_POST['to']);$x++){
            $string .= substr($_POST['to'],$x,1)."%";
        }
        $sql = "SELECT login,name FROM accounts WHERE name LIKE '".addslashes($string)."' AND locked=0 ORDER BY login";
        $result = db_query($sql);
        if (db_num_rows($result)==1){
            $row = db_fetch_assoc($result);
            output("<input type='hidden' name='to' value=\"".HTMLEntities($row[login])."\">",true);
            output("`^$row[name]`n");
        }else{
            output("<select name='to'>",true);
            for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                output("<option value=\"".HTMLEntities($row[login])."\">",true);
                output(preg_replace("/[`]./","",$row[name]));
            }
            output("</select>`n",true);
        }
    }
    output("`2Betreff:");
    $output.=("<input name='subject' value=\"".HTMLEntities($subject).HTMLEntities(stripslashes($_GET['subject']))."\">");
    output("`n`2Text:`n");
    $output.="<textarea name='body' class='input' cols='40' rows='9'>".HTMLEntities($body).HTMLEntities(stripslashes($_GET['body']))."</textarea><br>";
    output("<input type='submit' class='button' value='Senden'>`n",true);
    output("</form>",true);
}
popup_footer();
?>


