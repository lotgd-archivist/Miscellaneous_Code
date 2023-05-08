
<? 
// Dorfamt 1.0 by raven @ rabenthal.de
//
// Erstellt am 29.05.2004  Raven
//

require_once "common.php";
addcommentary();
checkday(); 
page_header("Das Dorfamt"); 
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'","",$REQUEST_URI)."&comscroll=0";
        //$req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$HTTP_GET_VARS[c]"."&comscroll=".($com-1);
        $req = str_replace("?&","?",$req);
        if (!strpos($req,"?")) $req = str_replace("&","?",$req);
if ($_GET[op]=="dorfinfo"){
    output("`n`b`c`2Die Infotafel`0`c`b`n`n");
    $dorfalter = getsetting("daysalive",0);
    //$sql="SELECT name, firstday FROM accounts WHERE superuser=4 AND acctid<>721 ORDER BY firstday ASC";
    output("<table cellspacing=0 border=0 align='center'><tr><td width=30>&nbsp;</td><td align='left'>",true);
    //output("`b`&Götter dieser Welt`b`n`n");
    //output("<table cellspacing=0 cellpadding=2 align='left'><tr><td>`b Name `b</td><td>`b Alter`b</td></tr>",true);
    //$result = db_query($sql) or die(db_error(LINK)); 
        //if (db_num_rows($result)==0){ 
        //      output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Götter in dieser Welt`i`0</td></tr>",true); 
        //} 
        //for ($i=0;$i<db_num_rows($result);$i++){ 
        //        $row = db_fetch_assoc($result);
    //    $alter = $dorfalter-$row[firstday];
    //    output("<tr class='".($i%2?"trlight":"trdark")."'>
    //            <td align='left'>`&$row[name] &nbsp;&nbsp;</td>
    //            <td align='right'>`&$alter Tage`0</td>
    //        </tr>`&",true);
    //}
    //output("</table>",true);
    //output("</td></tr><tr><td>&nbsp;</td><td>",true);
    $sql="SELECT name, firstday FROM accounts WHERE superuser=3 ORDER BY firstday ASC";
    output("`n`n`b`&Halbgötter in dieser Welt`b`n`n");
    output("<table cellspacing=0 cellpadding=2 align='left'><tr><td>`b Name `b</td><td>`b Alter`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)==0){ 
              output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Halbgötter in dieser Welt`i`0</td></tr>",true); 
        } 
        for ($i=0;$i<db_num_rows($result);$i++){ 
                $row = db_fetch_assoc($result);
        $alter = $dorfalter-$row[firstday];
        output("<tr class='".($i%2?"trlight":"trdark")."'>
                <td align='left'>`&$row[name] &nbsp;&nbsp;</td>
                <td align='right'>`&$alter Tage`0</td>
            </tr>`&",true);
    }
    output("</table>",true);
    output("</td></tr><tr><td>&nbsp;</td><td>",true);
    $sql="SELECT name, firstday FROM accounts WHERE prayer>0 ORDER BY firstday ASC";
    output("`n`n`b`&Priester in dieser Welt`b`n`n");
    output("<table cellspacing=0 cellpadding=2 align='left'><tr><td>`b Name `b</td><td>`b Alter`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK)); 
        if (db_num_rows($result)==0){ 
              output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Priester in dieser Welt`i`0</td></tr>",true); 
        } 
        for ($i=0;$i<db_num_rows($result);$i++){ 
                $row = db_fetch_assoc($result);
        $alter = $dorfalter-$row[firstday];
        output("<tr class='".($i%2?"trlight":"trdark")."'>
                <td align='left'>`&$row[name] &nbsp;&nbsp;</td>
                <td align='right'>`&$alter Tage`0</td>
            </tr>`&",true);
    }
    output("</table>",true);
    output("</td></tr></table>",true);
    //output("</div>",true);
}else if($_GET[op]=="verwaltungsbuero"){
    if ($session[user][superuser]>=3 || $session[user][prayer]){
            $session[user][locate]=34;
        if ($_GET[act]==""){
            output("`b`c`2Das Verwaltungsbüro`0`c`b");
            output("`6Du betrittst das Verwaltungsbüro von Düsterstein. Dein alter Schreibtisch, der Dir schon
                seit Jahren treue Dienste leistet biegt sich vor Akten, die auf ihm rumliegen. Wann willst
                Du diese viele Arbeit bloß schaffen?");
            output("`6Du überlegst, wie Du der Arbeit Herr werden kannst und kommst zu einem Entschluß.");
            output("`6Was hast Du entschieden, als nächstes zu erledigen?`n`n");
            addnav("- Allgemeines -");
            addnav("Gold auszahlen","dorfamt.php?op=verwaltungsbuero&act=addgold");
            addnav("Spieler umbenennen","dorfamt.php?op=verwaltungsbuero&act=rename");
            addnav("- Strafen -");
            addnav("Spieler rauswerfen","dorfamt.php?op=verwaltungsbuero&act=kick");
            addnav("Spieler anprangern","admin_jail.php");
            addnav("- Tools -");
                    addnav("Schlumpf-Tool","multianalysis.php");
                    addnav("Registratur","registratur.php");
            viewcommentary("verwaltungsbuero","Hier reden",30,"sagt");
        }
    }else{
        output("`6Hier haben leider nur die Verwaltungsbeamten Zutritt.");
    }
    if ($_GET[act]=="addgold"){
        output("<form action='dorfamt.php?op=verwaltungsbuero&act=addgold2' method='POST'>",true);
        addnav("","dorfamt.php?op=verwaltungsbuero&act=addgold2");
        output("`bGold auszahlen an:`b`nCharacter: <input name='name'> `nGold: <input name='amt' size='3'>`n<input type='submit' class='button' value='Auszahlen'>",true);
        output("</form>",true);
    }else if ($_GET[act]=="addgold2"){
        $search="%";
        for ($i=0;$i<strlen($_POST['name']);$i++){
            $search.=substr($_POST['name'],$i,1)."%";
        }
        $sql = "SELECT name,acctid,gold FROM accounts WHERE login LIKE '$search'";
        $result = db_query($sql);
        output("Bestätige die Auszahlung von {$_POST['amt']} Gold an:`n`n");
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<a href='dorfamt.php?op=verwaltungsbuero&act=addgold3&id={$row['acctid']}&amt={$_POST['amt']}'>",true);
            output("".$row['name']."");
            output("</a>`n",true);
            addnav("","dorfamt.php?op=verwaltungsbuero&act=addgold3&id={$row['acctid']}&amt={$_POST['amt']}");
            addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
        }
    }else if ($_GET[act]=="addgold3"){
        if ($_GET['id']==$session['user']['acctid']){
            output("`6Eine Auszahlung an sich selbst ist leider nicht erlaubt.");
            addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
        }else{
            $sql = "UPDATE accounts SET goldinbank=goldinbank+'{$_GET['amt']}' WHERE acctid='{$_GET['id']}'";
            db_query($sql);
            $_GET['op']="";
            systemmail($_GET[id],"`%Es wurde Gold an Dich ausgezahlt!`0","`&Du hast heute aus der Verwaltung Gold ausgezahlt bekommen.
                    ".$session[user][name]." hat veranlasst, daß Du ".$_GET[amt]." Gold auf Dein Konto
                    ausbezahlt bekommen hast");
            debuglog("`^Verwaltungsbuero: `6Auszahlung von ".$_GET[amt]." Gold an acctid ".$_GET[id]." veranlasst");
            addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
        }
    }else if ($_GET[act]=="kick"){
        output("<form action='dorfamt.php?op=verwaltungsbuero&act=kick2' method='POST'>",true);
        addnav("","dorfamt.php?op=verwaltungsbuero&act=kick2");
        output("`bFolgenden Spieler kicken:`b`nCharacter: <input name='name'>`n<input type='submit' class='button' value='Kicken'>",true);
        output("</form>",true);
        addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
    }else if ($_GET[act]=="kick2"){
        $search="%";
        for ($i=0;$i<strlen($_POST['name']);$i++){
            $search.=substr($_POST['name'],$i,1)."%";
        }
        $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
        $result = db_query($sql);
        output("Bestätige das Kicken von:`n`n");
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<a href='dorfamt.php?op=verwaltungsbuero&act=kick3&id={$row['acctid']}'>",true);
            output("".$row['name']."");
            output("</a>`n",true);
            addnav("","dorfamt.php?op=verwaltungsbuero&act=kick3&id={$row['acctid']}");
            addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
        }
    }else if ($_GET[act]=="kick3"){
        set_special_var("location","0","0",$_GET[id],0," ");
        set_special_var("loggedin","0","0",$_GET[id],0," ");
        output("Der Kick wurde durcjhgeführt");
        addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
    }else if ($_GET[act]=="rename"){
        output("<form action='dorfamt.php?op=verwaltungsbuero&act=rename2' method='POST'>",true);
        addnav("","dorfamt.php?op=verwaltungsbuero&act=rename2");
        output("`bFolgenden Charakter umbennenen:`b`nLogin name: <input name='name'><input type='submit' class='button' value='Umbennenen'>",true);
        output("</form>",true);
    }else if ($_GET[act]=="rename2"){
        $search="%";
        for ($i=0;$i<strlen($_POST['name']);$i++){
            $search.=substr($_POST['name'],$i,1)."%";
        }
        $sql = "SELECT name,acctid FROM accounts WHERE login LIKE '$search'";
        $result = db_query($sql);
        output("Bitte Wähle den richtigen Charakter aus:`n`n");
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            output("<a href='dorfamt.php?op=verwaltungsbuero&act=rename3&id={$row['acctid']}'>",true);
            output("".$row['name']."");
            output("</a>`n",true);
            addnav("","dorfamt.php?op=verwaltungsbuero&act=rename3&id={$row['acctid']}");
            addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
        }
    }else if ($_GET[act]=="rename3"){
        output("`6Bitte gebe die neuen Daten des Accounts ein.");
        $sql="SELECT name, acctid, login, ctitle, title FROM accounts where acctid = ".$_GET[id]."";
        $result=db_query($sql);
        $row=db_fetch_assoc($result);
        //output("<form action='dorfamt.php?op=verwaltungsbuero&act=rename4&old=".$row[name]."' method='POST'>",true);
            output("<form action='dorfamt.php?op=verwaltungsbuero&act=rename4&old={$row['name']}' method='POST'>",true);
        addnav("","dorfamt.php?op=verwaltungsbuero&act=rename4&old=".$row[name]."");
        output("<table cellspacing=0 cellpadding=2 align='left'><tr><td>&nbsp;</td><td>`bAlt`b</td><td>`bNeu</td></tr>",true);
        output("<tr><td>Login</td><td>".$row[login]."</td><td><input name='login'></td></tr>",true);
        output("<tr><td>CTitel</td><td>".$row[ctitle]."</td><td><input name='ctitle'></td></tr>",true);
        output("<tr><td>Name</td><td>".$row[name]."</td><td><input name='name'></td></tr>",true);
        output("<tr><td><input type='hidden' name='acctid' value='".$_GET[id]."'><input type='submit' class='button' value='Umbennenen'></td></tr></table>",true);
        output("</form>",true);
        addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
    }else if($_GET[act]=="rename4"){
        //check, ob name verfügbar ist
        $sqlcheck="SELECT login from accounts WHERE login='".$_POST[login]."'";
        $result = db_query($sqlcheck);
        $rc=db_num_rows($result);
        if ($rc!=0) {
            output("`$ Der Name ist bereits in Benutzung. `n
            Die Umbenennung ist daher nicht möglich!`n`0");
            addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
            addnav("Neue Umbenennung","dorfamt.php?op=verwaltungsbuero&act=rename3&id={$_POST['acctid']}");
        } else {
            //umbenennung durchführen
            $nameneu=$_POST[name];
            $namealt=$_GET[old];
            $boardid="namenwechsel";
            $message="".$namealt." ist nun unter ".$nameneu." bekannt";
            $sql="UPDATE accounts SET login='".$_POST[login]."'
                    , name='".$_POST[name]."'
                    , ctitle='".$_POST[ctitle]."'
                    WHERE acctid = ".$_POST[acctid]."";
            db_query($sql);
            $sql='INSERT INTO messageboard (boardid, acctid, name, message) VALUES ("'.$boardid.'","'.$session[user][acctid].'","'.$session[user][name].'","'.$message.'")';
            db_query($sql) or die(db_error(LINK));
            output("`6Das Update wurde erfolgreich ausgeführt");
            addnav("Zurück zum Büro","dorfamt.php?op=verwaltungsbuero");
        }
    }
}else if($_GET['op']=="honor"){
    if($_GET['act']=="winner"){
        if (!$_POST['ziel']){
            output("<form action='dorfamt.php?op=honor&act=winner' method='POST'>",true); 
            output("Wer war der Gewinner des letzten Rätsels? <input name='ziel'>`n", true); 
            output("<input type='submit' class='button' value='Eintragen'></form>",true); 
            addnav("","dorfamt.php?op=honor&act=winner"); 
        } else {
            if ($_GET['subfinal']==1){
                $sql = "SELECT acctid,name,login,lastip,emailaddress FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";
            }else{
                $ziel = stripslashes(rawurldecode($_POST['ziel']));
                $name="%";
                for ($x=0;$x<strlen($ziel);$x++){
                    $name.=substr($ziel,$x,1)."%";
                }
                $sql = "SELECT acctid,name,login,lastip,emailaddress FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";
            }
            $result2 = db_query($sql);
            if (db_num_rows($result2) == 0) {
                output("`2Es gibt niemanden mit einem solchen Namen. Versuchs nochmal.");
            } elseif(db_num_rows($result2) > 100) {
                output("`2Es gibt über 100 Krieger mit einem ähnlichen Namen. Bitte sei etwas genauer.");
            } elseif(db_num_rows($result2) > 1) {
                output("`2Es gibt mehrere mögliche Krieger, die gewonnen haben könnten.`n");
                output("<form action='dorfamt.php?op=honor&act=winner&subfinal=1' method='POST'>",true);
                output("`2Wen genau meinst du? <select name='ziel'>",true);
                for ($i=0;$i<db_num_rows($result2);$i++){
                    $row2 = db_fetch_assoc($result2);
                    output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
                }
                output("</select>`n`n",true);
                output("<input type='submit' class='button' value='Eintragen'></form>",true);
                addnav("","dorfamt.php?op=honor&act=winner&subfinal=1");
            } else {
                $row2 = db_fetch_assoc($result2);
                output("`n`n$row2[name] wird jetzt als Gewinner aufgeführt.");
                $sql = "UPDATE settings SET value='".$row2['name']."' WHERE setting='newriddle'";
                db_query($sql);
            }
        }
    }else{
        output("Du siehst dir den Teil der Infotafel an, die angibt, wer in letzter Zeit zu besonderen Ehren gekommen ist.`n`n");
        $sql = "SELECT * FROM settings WHERE setting = 'newplayer'";
        $result = db_query($sql);
        $player = db_fetch_assoc($result);
        $sql2 = "SELECT * FROM settings WHERE setting = 'newdragonkill'";
        $result2 = db_query($sql2);
        $dragonkiller = db_fetch_assoc($result2);
        $sql3 = "SELECT * FROM settings WHERE setting = 'newriddle'";
        $result3 = db_query($sql3);
        $riddler = db_fetch_assoc($result3);
        output("<table border=0 cellpadding=2 cellspacing=1>",true);
        output("<tr><td>jüngster Spieler: </td><td>".$player[value]."</td></tr>",true);
        output("<tr><td>letzter Drachenkämpfer: </td><td>".$dragonkiller[value]."</td></tr>",true);
        output("<tr><td>Gewinner des letzten Rätsels: </td><td>".$riddler[value]."</td></tr>",true);
        output("</table>",true);
        if($session[user][superuser]>=2){
            addnav("Gewinner eintragen","dorfamt.php?op=honor&act=winner");
        }
    }
} else if($_GET['op']=="msgboard"){
    $boardid = "infoboard";
    if($_GET['act'] == "add1") {
        if (addmessageboard()) {
            output("In der Hoffnung, dass jeder von deiner wichtigen Notiz Kenntnis nimmt, hängst du sie gut sichtbar an.");
        }
        elseif ($doublepost) {
            output("Es hängt schon ein solcher Zettel.");
        }
        else {
            output("Du kramst einen Zettel und einen Stift hervor und schreibst ein paar Zeilen.`n`n");                
            formmessageboard($boardid,'Notiz hinterlassen');
        }
        addnav("Nachrichten ansehen","dorfamt.php?op=msgboard");
    }else{
        output("An dieser Stelle ist es jedem Bewohner erlaubt, Notizen zu hinterlassen.");
        output("Gewöhnlich werden hier kleinere Verkäufe angepriesen, ");
        output("teilweise wird auch nach Bewohnern für ein Haus gesucht.`n");
        output("Manchmal sogar, kann man Liebeserklärungen hier finden.`n`n");
        viewmessageboard($boardid,'`nDies sind Notizen, die man hierhingehängt hat.','`nEs sind keine Notizen angebracht');
        addnav("Selber hinzufügen","dorfamt.php?op=msgboard&act=add1");
    }
} else if($_GET['op']=="namenwechsel"){
    $boardid = "namenwechsel";
        output("Hier findest Du die Namen der Bewohner, dessen Namen leider durch die");
        output(" Verwalter dieser Welt geändert werden mußten:");
        viewmessageboard($boardid,'`nDies sind Notizen, die man hierhingehängt hat.','`nEs sind keine Notizen angebracht');
        addnav("Zurück zur Wartehalle","dorfamt.php");
} else if ($_GET['op']=="karneval"){
    output("`n`6In dem Feld kannst Du Dich Deiner bisherigen Kleidung entledigen und Dich anziehen und 
            farbig bemalen, wie es Dir gefällt. Am Ende des Karnevals wirst Du diese Kleidung allerdings wieder
            hergeben müssen.
        `n`n");
    output("<form action='dorfamt.php?op=umziehen2' method='POST'>Wie möchtest Du für den Karneval aussehen?: <input name='putanzug' id='putanzug' accesskey='b' width='50'>",true);  
        output("<input type='submit' class='button' value='OK'></form>",true); 
        output("<script language='javascript'>document.getElementById('putanzug').focus();</script>",true); 
        addnav("","dorfamt.php?op=umziehen2");
    addnav("Zurück","dorfamt.php");
} else if ($_GET['op']=="umziehen2"){
    if ($session[user][eventname]==NULL){    
        $session[user][eventname]=$session[user][name];
    }
    if ($_POST['putanzug']==" "){
        output("`6Es wurde kein anderer Name gewählt.");
    }else{
        $session[user][name]=$_POST['putanzug'];
    }
    $session[user][name].="`0";
    output("`6Du wirst als ".$session[user][name]." in den Karneval ziehen.");
    addnav("Zum Dorfamt","dorfamt.php");
    
}else{
        if ($session[user][locate]!=13){
            $session[user][locate]=13;
        redirect("dorfamt.php");
        }
        output("`b`c`2Die Wartehalle`0`c`b"); 
    //if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/vogel.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);     
        output("`n`n`6Durch die große Eichentür betrittst Du das Dorfamt von Düsterstein, welches unmittelbar neben dem Dorfplatz liegt.
        Dich befällt sofort eine demütige Stimmung, spürst Du doch die Schwingungen der Macht, die durch dieses
        Gebäude schweben. Du weißt, in diesem Haus wird über das Wohl oder Unwohl von Düsterstein entschieden und
        Du hoffst, vielleicht selber einmal eines Tages einer derjenigen zu sein, die nicht nur herkommen, um sich
        Informationen zu besorgen sondern einer derjeningen zu sein, für die sämtliche Räume dieses Hauses aufstehen. "); 
        output("`nNachdem Du eine Zeitlang in Ehrfurcht in der großen Wartehalle erstarrt bist überlegst Du, was Du hier
        eigentlich wolltest?`0`n`n"); 
        viewcommentary("dorfamt","Hier reden",30,"sagt");
    addnav("Allgemeines");
    addnav("Funktionäre","dorfamt.php?op=dorfinfo");
        addnav("Kämpferliste","list.php");
    addnav("Suchen und finden","dorfamt.php?op=msgboard");
    addnav("Galerien");
    addnav("H?Halle der Ehre","hof.php");
     addnav("Galerie der Schande","shamelist.php");
    addnav("Offizielles");
    if ($session[event][karneval]) addnav("Karneval","dorfamt.php?op=karneval");
    addnav("Wahllokal","wahlen.php");
    addnav("Namenwechsel","dorfamt.php?op=namenwechsel");
    addnav("Besondere Ehrungen","dorfamt.php?op=honor");
    addnav("Verwaltungsbüro","dorfamt.php?op=verwaltungsbuero");
}
addnav("-");
if (!$_GET[op]=="") addnav("Zurück zur Wartehalle","dorfamt.php");
if (!$session[event][karneval]) addnav("A?Aktualisieren","$req");
addnav("Zurück zum Dorf","village.php"); 
page_footer(); 
?>


