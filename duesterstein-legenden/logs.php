
<?php 
/* 

View faillogs and user mail 
Find multi accounts and cheaters 

22052004 by anpera 

*/ 

     
require_once("common.php"); 

page_header("Logs und Mail"); 

if ($_GET[op]=="mail"){ 
    if ($_GET[in]){ 
        $ppp=25; // Player Per Page to display 
        if (!$_GET[limit]){ 
            $page=0; 
        }else{ 
            $page=(int)$_GET[limit]; 
            addnav("Vorherige Seite","logs.php?op=mail&in=$_GET[in]&limit=".($page-1).""); 
        } 
        $limit="".($page*$ppp).",".($ppp+1); 
        output("`qYe Olde Mail (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Alle Mails: Inbox $_GET[in] `0`n`n"); 
        $sql = "SELECT mail.*,accounts.login AS absender FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgfrom WHERE msgto=$_GET[in] ORDER BY sent DESC LIMIT $limit"; 
        $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=mail&in=$_GET[in]&limit=".($page+1).""); 
        output("<table align='center'><tr><td>`bDatum`b</td><td>`bAbsender`b</td><td>`bBetreff`b</td></tr>",true); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
            output("<tr><td>$row[sent]</td><td>$row[absender]</td><td>$row[subject]</td></tr><tr><td colspan='3'>$row[body]</td></tr>",true); 
            output("<tr><td colspan='3'><hr></td></tr>",true); 
        } 
        output("</table>",true); 
        addnav("Zurück","logs.php?op=mail"); 
    }else if ($_GET[out]){ 
        $ppp=25; // Player Per Page to display 
        if (!$_GET[limit]){ 
            $page=0; 
        }else{ 
            $page=(int)$_GET[limit]; 
            addnav("Vorherige Seite","logs.php?op=mail&out=$_GET[in]&limit=".($page-1).""); 
        } 
        $limit="".($page*$ppp).",".($ppp+1); 
        output("`qYe Olde Mail (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- Alle Mails: Outbox $_GET[out] `0`n`n"); 
        $sql = "SELECT mail.*,accounts.login AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE msgfrom=$_GET[out] ORDER BY sent DESC LIMIT $limit"; 
        $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=mail&out=$_GET[out]&limit=".($page+1).""); 
        output("<table align='center'><tr><td>`bDatum`b</td><td>`bEmpfänger`b</td><td>`bBetreff`b</td></tr>",true); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
            output("<tr><td>$row[sent]</td><td>$row[empfaenger]</td><td>$row[subject]</td></tr><tr><td colspan='3'>$row[body]</td></tr>",true); 
            output("<tr><td colspan='3'><hr></td></tr>",true); 
        } 
        output("</table>",true); 
        addnav("Zurück","logs.php?op=mail"); 
    }else{ 
        if (!$_GET[subop]) $_GET[subop]="system"; 
        $ppp=25; // Player Per Page to display 
        if (!$_GET[limit]){ 
            $page=0; 
        }else{ 
            $page=(int)$_GET[limit]; 
            addnav("Vorherige Seite","logs.php?op=mail&limit=".($page-1)."&subop=$_GET[subop]&order=$sort"); 
        } 
        $limit="".($page*$ppp).",".($ppp+1); 
        $sort="sent"; 
        if ($_GET[order]) $sort=$_GET[order]; 
        output("`qYe Olde Mail (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") -- ".($_GET[subop]=="all"?"Private Mails":"Systemnachrichten")."`0`n`n"); 
        addnav($_GET[subop]=="system"?"Normale Nachrichten":"Systemnachrichten","logs.php?op=mail&subop=".($_GET[subop]=="system"?"all":"system")."&order=$_GET[order]&limit=$_GET[limit]"); 
        $sql = "SELECT mail.*,accounts.login AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE ".($_GET[subop]=="system"?"msgfrom=0":"msgfrom<>0")." AND msgto<>0 ORDER BY $sort DESC LIMIT $limit"; 
        $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=mail&limit=".($page+1)."&order=$sort&subop=$_GET[subop]"); 
        output("<table align='center'><tr><td>`b<a href='logs.php?op=mail&limit=$page&order=sent&subop=$_GET[subop]'>Datum</a>`b</td><td>`b<a href='logs.php?op=mail&limit=$page&order=msgfrom&subop=$_GET[subop]'>Absender</a>`b</td><td>`b<a href='logs.php?op=mail&limit=$page&order=msgto&subop=$_GET[subop]'>Empfänger</a>`b</td><td>`bBetreff`b</td></tr>",true); 
        addnav("","logs.php?op=mail&limit=$page&order=sent&subop=$_GET[subop]"); 
        addnav("","logs.php?op=mail&limit=$page&order=msgto&subop=$_GET[subop]"); 
        addnav("","logs.php?op=mail&limit=$page&order=msgfrom&subop=$_GET[subop]"); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
            $row2=db_fetch_assoc(db_query("SELECT acctid,login FROM accounts WHERE acctid=$row[msgfrom]")); 
            output("<tr><td>$row[sent]</td><td><a href='logs.php?op=mail&out=$row2[acctid]'>$row2[login]</a></td><td><a href='logs.php?op=mail&in=$row[msgto]'>$row[empfaenger]</a></td><td>$row[subject]</td></tr><tr><td colspan='4'>$row[body]</td></tr>",true); 
            output("<tr><td colspan='4'><hr></td></tr>",true); 
            addnav("","logs.php?op=mail&in=$row[msgto]"); 
            addnav("","logs.php?op=mail&out=$row2[acctid]"); 
        } 
        output("</table>",true); 
        addnav("Zurück","logs.php"); 
        output("`n`iUm das Postfach eines Spielers zu sehen, auf seinen Namen unter \"Empfänger\" klicken.`nUm zu sehen, was ein Spieler gesendet hat, auf seinen Namen unter \"Absender\" klicken.`i"); 
    } 
}else if ($_GET[op]=="faillog"){ 
    if ($_GET[id]){ 
        $ppp=25; // Player Per Page to display 
        if (!$_GET[limit]){ 
            $page=0; 
        }else{ 
            $page=(int)$_GET[limit]; 
            addnav("Vorherige Seite","logs.php?op=faillog&id=$_GET[id]&limit=".($page-1)."&order=$sort&pw=$_GET[pw]"); 
        } 
        $limit="".($page*$ppp).",".($ppp+1); 
        $sort="date"; 
        if ($_GET[order]) $sort=$_GET[order]; 
        $sql = "SELECT * FROM faillog WHERE acctid=$_GET[id] ORDER BY $sort DESC LIMIT $limit"; 
        $result = db_query($sql) or die(db_error(LINK)); 
        output("Fehlgeschlagene Logins (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") für ID $_GET[id]`n`n"); 
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=faillog&id=$_GET[id]&limit=".($page+1)."&order=$sort&pw=$_GET[pw]"); 
        output("<table align='center'><tr><td>`b<a href='logs.php?op=faillog&id=$_GET[id]&limit=$page&order=date'>Datum</a>`b</td><td>`b<a href='logs.php?op=faillog&id=$_GET[id]&limit=$page&order=ip'>IP</a>`b</td>".($_GET[pw]?"<td>`bfalsches PW`b</td>":"")."</tr>",true); 
        addnav("","logs.php?op=faillog&id=$_GET[id]&limit=$page&order=date"); 
        addnav("","logs.php?op=faillog&id=$_GET[id]&limit=$page&order=ip"); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
            if ($_GET[pw] && $session[user][superuser]>=3) $row[post]=unserialize($row[post]); 
            output("<tr><td>$row[date]</td><td>$row[ip]</td>".($_GET[pw]?"<td>".$row[post][password]."</td>":"")."</tr>",true); 
        } 
        addnav("Zurück","logs.php?op=faillog&order=$_GET[order]"); 
        output("</table>",true); 
        if ($session[user][superuser]>=3) addnav("PWs ".($_GET[pw]?"ausblenden":"einblenden")."","logs.php?op=faillog&id=$_GET[id]".($_GET[pw]?"":"&pw=true")."&order=$_GET[order]&limit=$_GET[limit]"); 
        if ($_GET[pw] && $i>=3) output("`n`iDie Anzeige der falsch eingegebenen Passwörter dient dazu, um festzustellen, wo Passwörter geraten werden und wo nur ein Tippfehler vorliegt.`i"); 
    }else if ($_GET[ip]){ 
        $ppp=25; // Player Per Page to display 
        if (!$_GET[limit]){ 
            $page=0; 
        }else{ 
            $page=(int)$_GET[limit]; 
            addnav("Vorherige Seite","logs.php?op=faillog&ip=$_GET[ip]&limit=".($page-1)."&pw=$_GET[pw]"); 
        } 
        $limit="".($page*$ppp).",".($ppp+1); 
        $sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE ip='$_GET[ip]' ORDER BY date DESC LIMIT $limit"; 
        $result = db_query($sql) or die(db_error(LINK)); 
        output("Fehlgeschlagene Logins (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).") von IP $_GET[ip]`n`n"); 
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=faillog&ip=$_GET[ip]&limit=".($page+1)."&pw=$_GET[pw]"); 
        output("<table align='center'><tr><td>`bDatum`b</td><td>`bName`b</td>".($_GET[pw]?"<td>`bfalsches PW`b</td>":"")."</tr>",true); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
            if ($_GET[pw] && $session[user][superuser]>=3) $row[post]=unserialize($row[post]); 
            output("<tr><td>$row[date]</td><td>$row[absender]</td>".($_GET[pw]?"<td>".$row[post][password]."</td>":"")."</tr>",true); 
        } 
        addnav("Zurück","logs.php?op=faillog&order=$_GET[order]"); 
        output("</table>",true); 
        if ($session[user][superuser]>=3) addnav("PWs ".($_GET[pw]?"ausblenden":"einblenden")."","logs.php?op=faillog&ip=$_GET[ip]".($_GET[pw]?"":"&pw=true")."&order=$_GET[order]&limit=$_GET[limit]"); 
        if ($_GET[pw] && $i>=3) output("`n`iDie Anzeige der falsch eingegebenen Passwörter dient dazu, um festzustellen, wo Passwörter geraten werden und wo nur ein Tippfehler vorliegt.`i"); 
    }else{ 
        $ppp=25; // Player Per Page to display 
        output("Fehlgeschlagene Logins (".($_GET[limit]*$ppp)."-".($_GET[limit]*$ppp+$ppp).")`n`iSpielername oder IP anklicken, um alle Fehlversuche anzuzeigen.`i`n`n"); 
        if (!$_GET[limit]){ 
            $page=0; 
        }else{ 
            $page=(int)$_GET[limit]; 
            addnav("Vorherige Seite","logs.php?op=faillog&limit=".($page-1)."&order=$sort"); 
        } 
        $limit="".($page*$ppp).",".($ppp+1); 
        $sort="date"; 
        if ($_GET[order]) $sort=$_GET[order]; 
        $sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE 1 ORDER BY $sort DESC LIMIT $limit"; 
        $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","logs.php?op=faillog&limit=".($page+1)."&order=$sort"); 
        output("<table align='center'><tr><td>`b<a href='logs.php?op=faillog&limit=$page&order=date'>Datum</a>`b</td><td>`b<a href='logs.php?op=faillog&limit=$page&order=acctid'>Acctid</a>`b</td><td>`bName`b</td><td>`b<a href='logs.php?op=faillog&limit=$page&order=ip'>IP</a>`b</td></tr>",true); 
        addnav("","logs.php?op=faillog&limit=$page&order=date"); 
        addnav("","logs.php?op=faillog&limit=$page&order=acctid"); 
        addnav("","logs.php?op=faillog&limit=$page&order=ip"); 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
            output("<tr><td>$row[date]</td><td>$row[acctid]</td><td><a href='logs.php?op=faillog&id=$row[acctid]&order=$sort'>$row[absender]</a></td><td><a href='logs.php?op=faillog&ip=$row[ip]&order=$sort'>$row[ip]</a></td></tr>",true); 
            addnav("","logs.php?op=faillog&id=$row[acctid]&order=$sort"); 
            addnav("","logs.php?op=faillog&ip=$row[ip]&order=$sort"); 
        } 
        output("</table>",true); 
        addnav("Zurück","logs.php"); 
    } 
} elseif ($_GET['op']=='multi') {
    if (!empty($_POST['setupban']) && count($_POST['userid'])>0) {
        output("<form action='logs.php?op=multi&act=saveban' method='POST'>",true);
        if ($_POST['setupban']=='IPs bannen') {
            $sql = 'SELECT lastip FROM accounts WHERE acctid IN ("'.implode('","',$_POST['userid']).'") GROUP BY lastip';
            $result = db_query($sql);
            $ips = array();
            while ($row = db_fetch_assoc($result)) $ips[] = $row['lastip'];
            output('Sperre für die IP '.implode(', ',$ips).'`n');
            output('<input type="hidden" name="type" value="ip"><input type="hidden" name="ip" value="'.implode('|',$ips).'">',true);
        }
        else {
            $sql = 'SELECT uniqueid FROM accounts WHERE acctid IN ("'.implode('","',$_POST['userid']).'") GROUP BY uniqueid';
            $result = db_query($sql);
            $ids = array();
            while ($row = db_fetch_assoc($result)) $ids[] = $row['uniqueid'];
            output('Sperre für die ID '.implode(', ',$ids).'`n');
            output('<input type="hidden" name="type" value="id"><input type="hidden" name="id" value="'.implode('|',$ids).'">',true);
        }
        output("Dauer: <input name='duration' id='duration' size='3' value='14'> days (0 for permanent)`n",true);
        output("Grund für die Verbannung: <input name='reason' value=\"Ärger mich nicht.\">`n",true);
        output("<input type='submit' class='button' value='Post Ban' onClick='if (document.getElementById(\"duration\").value==0) {return confirm(\"Willst du wirklich eine permanente Verbannung aussprechen?\");} else {return true;}'></form>",true);
        addnav("","logs.php?op=multi&act=saveban");
    }
    elseif ($_GET['act']=='saveban') {
        if ($_POST['type']=='ip') $vals = explode('|',$_POST['ip']);
        else $vals = explode('|',$_POST['id']);

        foreach ($vals AS $this2) {
            $sql = "INSERT INTO bans (";
            if ($_POST[type]=="ip"){
                $sql.="ipfilter";
            }else{
                $sql.="uniqueid";
            }
            $sql.=",banexpire,banreason) VALUES (";
            $sql.="\"$this2\"";
            $sql.=",\"".((int)$_POST[duration]==0?"0000-00-00":date("Y-m-d",strtotime("+$_POST[duration] days")))."\",";
            $sql.="\"$_POST[reason]\")";
            if ($_POST[type]=="ip"){
                if (substr($_SERVER['REMOTE_ADDR'],0,strlen($this2)) == $this2){
                    $sql = "";
                    output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene IP-Adresse!");
                }
            }else{
                if ($_COOKIE[lgi]==$this2){
                    $sql = "";
                    output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene ID!");
                }
            }
            if ($sql!=""){
                db_query($sql) or die(db_error(LINK));
                output(db_affected_rows()." Bann eingetragen.`n`n");
                output(db_error(LINK));
            }
        }
        output('`n`n`n`n');
    }elseif ($_GET[op]=="del"){
        $sql = "SELECT name from accounts WHERE acctid='$_GET[userid]'";
        $res = db_query($sql);
        $sql = "UPDATE items SET owner=0 WHERE owner=$_GET[userid]"; 
        db_query($sql); 
        $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$_GET[userid] AND status=1"; 
        db_query($sql); 
        $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$_GET[userid] AND status=0"; 
        db_query($sql);
        $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$_GET[userid]"; 
        db_query($sql); 
        $sql = "DELETE FROM pvp WHERE acctid2=$_GET[userid] OR acctid1=$_GET[userid]"; 
           db_query($sql) or die(db_error(LINK)); 
        $sql = "DELETE FROM accounts WHERE acctid='$_GET[userid]'";
        db_query($sql);
        output( db_affected_rows()." user deleted.");
        while ($row = db_fetch_assoc($res)) {
            addnews("`#{$row['name']} wurde von den Göttern aufgelöst.");
        }
    }else output('`n`n`n`n');
    
    
    $in_ip = $in_id = '';
    $sql = 'SELECT lastip FROM accounts WHERE lastip!="" GROUP BY lastip HAVING COUNT(*) > 1';
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result)) {
        $in_ip .= ',"'.$row['lastip'].'"';
    }
    $sql = 'SELECT uniqueid FROM accounts WHERE uniqueid!="" GROUP BY uniqueid HAVING COUNT(*) > 1';
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result)) {
        $in_id .= ',"'.$row['uniqueid'].'"';
    }
    
    $ip = $id = $users = array();
    $sql = 'SELECT acctid,name,lastip,uniqueid,dragonkills,level FROM accounts WHERE (lastip IN (-1'.$in_ip.') OR uniqueid IN (-1'.$in_id.')) AND locked="0" ORDER BY dragonkills ASC, level ASC';
    $result = db_query($sql) or die(db_error(LINK));
    while ($row = db_fetch_assoc($result)) {
        if (!isset($id[$row['uniqueid']]) && !isset($ip[$row['lastip']])) {
            $ip[$row['lastip']] = count($users);
            $id[$row['uniqueid']] = count($users);
            $users[] = array($row);
        }
        elseif (isset($id[$row['uniqueid']])) {
            $ip[$row['lastip']] = $id[$row['uniqueid']];
            $users[$id[$row['uniqueid']]][] = $row;
        }
        else {
            $id[$row['uniqueid']] = $ip[$row['lastip']];
            $users[$ip[$row['lastip']]][] = $row;
        }        
    }  
    
    output('`n`bMultiaccounts`b`nNaaa, wer cheatet denn so alles?`n`n');
    output('<table><tr><td>',true);
    foreach ($users AS $list) {
        if (count($list)<3) continue;
        //$tmpstr = '';
        $tmpstr = "";
        $ips = $ids = $accts = array();
        foreach ($list AS $this2) {
//            $tmpstr .= ('<tr><td><input type="checkbox" name="userid[]" value="'.$this2['acctid'].'"></td>
            /*$tmpstr .= ('<tr><td><td>'.$this2['name'].'</td>
                                <td>'.$this2['lastip'].'</td>
                                <td>'.$this2['uniqueid'].'</td>
                                <td>'.$this2['dragonkills'].'</td>
                                <td>'.$this2['level'].'</td></tr>');
           */ $tmpstr .= ("<tr><td><td><a href='user.php?op=debuglog&userid=".$this2['acctid']."'>".$this2['name']."</a></td>
                                <td>".$this2['lastip']."</td>
                                <td>".$this2['uniqueid']."</td>
                                <td>".$this2['dragonkills']."</td>
                                <td>".$this2['level']."</td></tr>");

              $tmp = $this2['acctid'];
              addnav("","user.php?op=debuglog&userid=$tmp");
        }
        output('<form action="logs.php?op=multi" method="post">',true);
        addnav('','logs.php?op=multi');
        output("<table align='center' class='input' width='100%'><tr><td>&nbsp;</td>
                        <td>`bName`b</td>
                        <td>`bIP`b</td>
                        <td>`bID`b</td>
                        <td>`bDK`b</td>
                        <td>`bLevel`b</td>
                        </tr>",true);

        output($tmpstr,true);

/*        output('<tr><td colspan="6" align="left">
                        <input type="submit" name="deleteuser" value="löschen">
                        <input type="submit" name="setupban" value="IPs bannen">
                        <input type="submit" name="setupban" value="IDs bannen">
                    </td></tr>',true);*/
        output('</table>`n`n',true);
        output('</form>',true);
    }
    output('</td></tr></table>',true);
    addnav('Aktualisieren','logs.php?op=multi');
    addnav('Zurück','logs.php');
}else{ 
    output("Die 5 letzten fehlgeschlagenen Logins:`n`n"); 
    $sql = "SELECT faillog.*,accounts.name AS absender FROM faillog LEFT JOIN accounts ON accounts.acctid=faillog.acctid WHERE 1 ORDER BY date DESC LIMIT 5"; 
    $result = db_query($sql) or die(db_error(LINK)); 
    output("<table align='center'><tr><td>`bDatum`b</td><td>`bAcctid`b</td><td>`bName`b</td><td>`bIP`b</td></tr>",true); 
    for ($i=0;$i<db_num_rows($result);$i++){ 
        $row = db_fetch_assoc($result); 
        output("<tr><td>$row[date]</td><td>$row[acctid]</td><td>$row[absender]</td><td>$row[ip]</td></tr>",true); 
    } 
    output("</table>`n`nDie 5 letzten Systemmails:`n`n",true); 
    $sql = "SELECT mail.*,accounts.name AS empfaenger FROM mail LEFT JOIN accounts ON accounts.acctid=mail.msgto WHERE msgfrom=0 ORDER BY sent DESC LIMIT 5"; 
    $result = db_query($sql) or die(db_error(LINK)); 
    output("<table align='center'><tr><td>`bDatum`b</td><td>`bEmpfänger`b</td><td>`bBetreff`b</td></tr>",true); 
    for ($i=0;$i<db_num_rows($result);$i++){ 
        $row = db_fetch_assoc($result); 
        output("<tr><td>$row[sent]</td><td>$row[empfaenger]</td><td>$row[subject]</td></tr>",true); 
    } 
    output("</table>`n",true); 
    addnav("Faillog","logs.php?op=faillog"); 
    addnav("Usermails","logs.php?op=mail"); 
    addnav("Multiaccounts","logs.php?op=multi");
    addnav("Aktualisieren","logs.php"); 
} 
addnav("Zurück zur Grotte","superuser.php"); 
addnav("Zurück zum Weltlichen","village.php"); 
output("`n<div align='right'>`)2004 by anpera</div>",true); 
page_footer(); 
?>


