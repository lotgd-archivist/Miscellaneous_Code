<?php
require_once "common.php";
isnewday(3);

if ($_GET[op]=="search"){
    $sql = "SELECT acctid FROM accounts WHERE ";
    $where="
    login LIKE '%{$_REQUEST['q']}%' OR
    acctid LIKE '%{$_REQUEST['q']}%' OR
    name LIKE '%{$_REQUEST['q']}%' OR
    emailaddress LIKE '%{$_REQUEST['q']}%' OR
    lastip LIKE '%{$_REQUEST['q']}%' OR
    uniqueid LIKE '%{$_REQUEST['q']}%' OR
    gentimecount LIKE '%{$_REQUEST['q']}%' OR
    level LIKE '%{$_REQUEST['q']}%'";
    $result = db_query($sql.$where);
    if (db_num_rows($result)<=0){
        output("`\$Keine Ergebnisse gefunden`0");
        $_GET[op]="";
        $where="";
    }elseif (db_num_rows($result)>100){
        output("`\$Zu viele Ergebnisse gefunden. Bitte Suche einengen.`0");
        $_GET[op]="";
        $where="";
    }elseif (db_num_rows($result)==1){
        //$row = db_fetch_assoc($result);
        //redirect("user.php?op=edit&userid=$row[acctid]");
        $_GET[op]="";
        $_GET['page']=0;
    }else{
        $_GET[op]="";
        $_GET['page']=0;
    }
}

page_header("User Editor");
    output("<form action='user.php?op=search' method='POST'>Suche in allen Feldern: <input name='q' id='q'><input type='submit' class='button'></form>",true);
    output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);
    addnav("","user.php?op=search");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Freischaltung","activate.php");
addnav("Pranger","admin_jail.php");
addnav("Verbannung","user.php?op=setupban");
addnav("Verbannungen anzeigen/entfernen","user.php?op=removeban");
addnav("Superuser","user.php?page=0&supage=1");
//addnav("Benutzereditor","user.php");
$sql = "SELECT count(acctid) AS count FROM accounts";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$page=0;
while ($row[count]>0){
    $page++;
    addnav("$page Seite $page","user.php?page=".($page-1)."&sort=$_GET[sort]");
    $row[count]-=100;
}

$mounts=",0,Keins";
$sql = "SELECT mountid,mountname,mountcategory FROM mounts ORDER BY mountcategory";
$result = db_query($sql);
while ($row = db_fetch_assoc($result)){
    $mounts.=",{$row['mountid']},{$row['mountcategory']}: {$row['mountname']}";
}
$userinfo = array(
    "Account Info,title",
    "acctid"=>"User ID,viewonly",
    "login"=>"Login",
    "newpassword"=>"Neues Passwort",
    "emailaddress"=>"Email Adresse",
    "locked"=>"Account gesperrt,bool",
    "banoverride"=>"Verbannungen übergehen,bool",
    "invisible"=>"Unsichtbar in der Spielerliste,bool",
    "superuser"=>"Superuser,enum,0,Standard Spieltage pro Kalendertag,1,Unbegrenzt Spieltage pro Kalendertag,2,Kreaturen und Spott administrieren,3,User administrieren,4,Big Boss",

    "User Infos,title",
    "name"=>"Display Name",
    "title"=>"Titel (muss auch in Display Name)",
    "ctitle"=>"Eigener Titel (muss auch in Display Name)",
    "ctitle2"=>"Eigener Titel (muss auch in Display Name / hinter dem Namen)",
    "cname"=>"Namensteil (muss auch in Display Name)",
    "sex"=>"Geschlecht,enum,0,Männlich,1,Weiblich",
// we can't change this this way or their stats will be wrong.
//    "race"=>"Race,enum,0,Unknown,1,Troll,2,Elf,3,Human,4,Dwarf,5,Echse",
    "age"=>"Tage seit Level 1,int",
    "dragonkills"=>"Drachenkills,int",
    "dragonage"=>"Alter beim letzten Drachenkill,int",
    "bestdragonage"=>"Jüngstes Alter bei einem Drachenkill,int",
    "bio"=>"Bio",

    "Werte,title",
    "level"=>"Level,int",
    "experience"=>"Erfahrung,int",
    "hitpoints"=>"Lebenspunkte (aktuell),int",
    "maxhitpoints"=>"Maximale Lebenspunkte,int",
    "turns"=>"Runden übrig,int",
    "playerfights"=>"Spielerkämpfe übrig,int",
    "attack"=>"Angriffswert (inkl. Waffenschaden),int",
    "defence"=>"Verteidigung (inkl. Rüstung),int",
    "spirits"=>"Stimmung (nur Anzeige),enum,-2,Sehr schlecht,-1,Schlecht,0,Normal,1,Gut,2,Sehr gut",
    "resurrections"=>"Auferstehungen,int",
    "alive"=>"Lebendig,int",
    "rpstars"=>"RolePlay Auszeichnungen (max5),int",

    "Spezialitäten,title",
    "specialty"=>"Spezialität,enum,0,Unspezifiziert,1,Dunkle Künste,2,Mystische Kräfte,3,Diebeskunst",
    "darkarts"=>"`4Stufe  in Dunklen Künsten`0,int",
    "darkartuses"=>"`4^--heute übrig`0,int",
    "magic"=>"`%Stufe in Mystischen Kräften`0,int",
    "magicuses"=>"`%^--heute übrig`0,int",
    "thievery"=>"`^Stufe in Diebeskunst`0,int",
    "thieveryuses"=>"`^^--heute übrig`0,int",

    "Grabkämpfe,title",
    "deathpower"=>"Gefallen bei Ramius,int",
    "gravefights"=>"Grabkämpfe übrig,int",
    "soulpoints"=>"Seelenpunkte (HP im Tod),int",
    "thefttoday"=>"Diebstahlstatus,int",

    "Ausstattung,title",
    "gems"=>"Edelsteine,int",
    "gold"=>"Bargold,int",
    "goldinbank"=>"Gold auf der Bank,int",
    "transferredtoday"=>"Anzahl Transfers heute,int",
    "amountouttoday"=>"Heute ausgegengener Wert der Überweisungen,int",
    "weapon"=>"Name der Waffe",
    "weapondmg"=>"Waffenschaden,int",
    "weaponvalue"=>"Kaufwert der Waffe,int",
    "armor"=>"Name der Rüstung",
    "armordef"=>"Verteidigungswert,int",
    "armorvalue"=>"Kaufwert der Rüstung,int",

    "Sonderinfos,title",
    "house"=>"Haus-ID,int",
    "housekey"=>"Hausschlüssel?,int",
    "marriedto"=>"Partner-ID (4294967295 = Violet/Seth),int",
    "charisma"=>"Flirts (4294967295 = verheiratet mit Partner),int",
    "seenlover"=>"Geflirtet,bool",
    "seenbard"=>"Barden gehört,bool",
    "charm"=>"Charme,int",
    "seendragon"=>"Drachen heute gesucht,bool",
    "seenmaster"=>"Meister befragt,bool",
    "usedouthouse"=>"Plumpsklo besucht,bool",
    "fedmount"=>"Tier gefüttert,bool",
    "gotfreeale"=>"Frei-Ale (MSB: getrunken - LSB: spendiert),int",
    "hashorse"=>"Tier,enum$mounts",
    "boughtroomtoday"=>"Zimmer für heute bezahlt,bool",
    "drunkenness"=>"Betrunken (0-100),int",

    "Weitere Infos,title",
    "laston"=>"Zuletzt Online,viewonly",
    "lasthit"=>"Letzter neuer Tag,viewonly",
    "lastmotd"=>"Datum der letzten MOTD,viewonly",
    "lastip"=>"Letzte IP,viewonly",
    "uniqueid"=>"Unique ID,viewonly",
    "gentime"=>"Summe der Seitenerzeugungszeiten,viewonly",
    "gentimecount"=>"Seitentreffer,viewonly",
    "allowednavs"=>"Zulässige Navigation,viewonly",
    "dragonpoints"=>"Eingesetzte Drachenpunkte,viewonly",
    "bufflist"=>"Spruchliste,viewonly",
    "prefs"=>"Einstellungen,viewonly",
    "lastwebvote"=>"Zuletzt bei Top Wep Games gewählt,viewonly",
    "donationconfig"=>"Spendenkäufe,viewonly"
    );

if ($_GET[op]=="lasthit"){
    $output="";
    $sql = "SELECT output FROM accounts_text WHERE acctid='{$_GET['userid']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    echo str_replace("<iframe src=","<iframe Xsrc=",$row['output']);
    exit();
}elseif ($_GET[op]=="edit"){
    $result = db_query("SELECT * FROM accounts LEFT JOIN accounts_text USING(acctid) WHERE accounts.acctid='$_GET[userid]'") or die(db_error(LINK));
    $row = db_fetch_assoc($result) or die(db_error(LINK));
    output("<form action='user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
    addnav("","user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
    output("<input type='submit' class='button' name='newday' value='Neuen Tag gewähren'>",true);
    output("<input type='submit' class='button' name='fixnavs' value='Defekte Navs reparieren'>",true);
    output("<input type='submit' class='button' name='clearvalidation' value='E-Mail als gültig markieren'>",true);
    output("<input type='submit' class='button' name='checkcommentary' value='Kommentare anzeigen'>",true);
    output("<input type='submit' class='button' name='showitems' value='Items'>",true);
    output("</form>",true);

    if ($_GET['returnpetition']!=""){
        addnav("Zurück zur Anfrage","viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }

    addnav("Letzten Treffer anzeigen","user.php?op=lasthit&userid={$_GET['userid']}",false,true);
    addnav("Verbannen","user.php?op=setupban&userid=$row[acctid]");
    addnav("Debug-Log anzeigen","user.php?op=debuglog&userid={$_GET['userid']}");

    if ($_GET['subop']!='items') {
        output("<form action='user.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
        addnav("","user.php?op=save&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
        addnav("","user.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
        output("<input type='submit' class='button' value='Speichern'>",true);
        $output .= "<input type='hidden' name='oldname' value='".str_replace("'","&#039;",$row['name'])."'>";
        $output .= "<input type='hidden' name='oldlogin' value='".str_replace("'","&#039;",$row['login'])."'>";
        $output .= "<input type='hidden' name='oldctitle' value='".str_replace("'","&#039;",$row['ctitle'])."'>";
        $output .= "<input type='hidden' name='oldtitle' value='".str_replace("'","&#039;",$row['title'])."'>";
        $output .= "<input type='hidden' name='oldsuperuser' value='".$row['superuser']."'>";
        showform($userinfo,$row);
        output("</form>",true);
        output("<iframe src='user.php?op=lasthit&userid={$_GET['userid']}' width='100%' height='400'>Dein Browser muss iframes unterstützen, um die letzte Seite des Users anzeigen zu können. Benutze den Link im Menü stattdessen.</iframe>",true);
        addnav("","user.php?op=lasthit&userid={$_GET['userid']}");
    }
    else {
        output("<form action='user.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);
        addnav("","user.php?op=edit&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");
        output("<input type='submit' class='button' value='Zur&uuml;ck'>",true);
        output('</form>',true);
        output("`c`bDie Besitztümer von ".$row['name']."`b`c`n`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bItem`b</td><td>`bKlasse`b</td><td>`bWert 1`b</td><td>`bWert 2`b</td><td>`bVerkaufswert`b</td><td>&nbsp;</td></tr>",true);
        $sql = "SELECT * FROM items WHERE owner=".$_GET['userid']." ORDER BY class ASC";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
              output("<tr><td colspan=5 align='center'>`&`i$row[name] hat nichts im Inventar`i`0</td></tr>",true);
        }else{
            for ($i=0;$i<db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td>`&$item[name]`0</td><td>`!$item[class]`0</td><td align='right'>$item[value1]</td><td align='right'>$item[value2]</td><td>",true);
                if ($item[gold]==0 && $item[gems]==0){
                    output("`4Unverkäuflich`0");
                }else{
                    output("`^$item[gold]`0 Gold, `#$item[gems]`0 Edelsteine");
                }
                output("</td><td rowspan='2' valign='top'><a href='user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&takeitem=".$item['id']."'>entfernen</a>",true);
                addnav('',"user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&takeitem=".$item['id']);
                if ($item['class']=='Schlüssel') {
                    output("<a href='user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&putbackitem=".$item['id']."'>zur&uuml;ckgeben</a>",true);
                    addnav('',"user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&putbackitem=".$item['id']);
                    output("<a href='user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&loseitem=".$item['id']."'>verloren</a>",true);
                    addnav('',"user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&loseitem=".$item['id']);
                }
                output("</td></tr><tr class='$bgcolor'><td align='right'>Beschreibung:</td><td colspan=4>$item[description]</td></tr>",true);
            }
        }
        if (getsetting("hasegg",0)==$_GET['userid']){
                $bgcolor=($i%2==1?"trdark":"trlight");
                output("<tr class='$bgcolor'><td>`^Das goldene Ei`0</td><td></td><td></td><td></td><td>`4Unverkäuflich`0</td>",true);
                output("<td><a href='user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&takeitem=egg'>entfernen</a>",true);
                addnav('',"user.php?op=special&userid=$_GET[userid]".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."&takeitem=egg");
                output("</td></tr>",true);
        }
        output("</table>",true);
    }
}elseif ($_GET[op]=="special"){
    if ($_POST[newday]!=""){
        $sql = "UPDATE accounts SET lasthit='".date("Y-m-d H:i:s",strtotime("-".(86500/getsetting("daysperday",4))." seconds"))."' WHERE acctid='$_GET[userid]'";
    }elseif($_POST[fixnavs]!=""){
        $sql = "UPDATE accounts_text SET allowednavs='',output=\"\" WHERE acctid='$_GET[userid]'";
    }elseif($_POST[clearvalidation]!=""){
        $sql = "UPDATE accounts SET emailvalidation='' WHERE acctid='$_GET[userid]'";
    }elseif ($_POST['checkcommentary']!='') {
        $sql = 'SELECT login FROM accounts WHERE acctid="'.$_GET['userid'].'"';
        $row = db_fetch_assoc(db_query($sql));
        redirect('superuser.php?op=checkcommentary&subop=user&commentsof='.rawurlencode($row['login']));
    }elseif ($_POST['showitems']!='') {
        redirect('user.php?op=edit&subop=items&userid='.$_GET['userid'].($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":""));
    }elseif ($_GET['takeitem']!='') {
        if ($_GET['takeitem']=='egg') savesetting("hasegg",0);
        else {
            db_query('DELETE FROM items WHERE id="'.$_GET['takeitem'].'"');
            adminlog();
            redirect('user.php?op=edit&subop=items&userid='.$_GET['userid'].($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":""));
        }
    }elseif ($_GET['loseitem']!='') {
        db_query('UPDATE items SET owner=0 WHERE class="Schlüssel" AND id='.(int)$_GET['loseitem']) or die(db_error(LINK));
        adminlog();
        redirect('user.php?op=edit&subop=items&userid='.$_GET['userid'].($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":""));
    }elseif ($_GET['putbackitem']!='') {
        $sql = 'SELECT houses.owner FROM items LEFT JOIN houses ON items.value1=houses.houseid WHERE items.id='.$_GET['putbackitem'];
        $row = db_fetch_assoc(db_query($sql));
        $sql = "UPDATE items SET owner=$row[owner] WHERE class='Schlüssel' AND id='$_GET[putbackitem]'";
        db_query($sql);
        adminlog();
        redirect('user.php?op=edit&subop=items&userid='.$_GET['userid'].($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":""));
    }

    db_query($sql);
    adminlog();
    if ($_GET['returnpetition']==""){
        redirect("user.php?".db_affected_rows());
    }else{
        redirect("viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }
}elseif ($_GET[op]=="save"){
    $sql = "UPDATE accounts SET ";

    // Ein paar Sicherheiten für Änderungen
    // Gesamtname geändert
    if ($_POST['oldname']!=$_POST['name']) {
        $clearedname = preg_replace('/`./','',$_POST['name']);
        // Login bleibt gleich
        if (substr_count($clearedname,$_POST['login'])) {
            // Titel rausfinden
            $replace = '(`.)*';
            for ($i=0;$i<strlen($_POST['login']);$i++) {
                $replace .= $_POST['login']{$i}.'(`.)*';
            }
            $_POST['ctitle'] = rtrim(preg_replace('/'.$replace.'/','',$_POST['name']));
            if ($_POST['ctitle']=='') $_POST['title'] = '';
            elseif ($_POST['ctitle']==$_POST['title']) $_POST['ctitle'] = '';
        }
        // Neuer Login
        else {
            // Leerzeichen vorhanden
            if ($login = strrchr($_POST['name'],' ')) {
                $_POST['login'] = trim(strrchr($clearedname,' '));
                $_POST['ctitle'] = str_replace($login,'',$_POST['name']);
                if ($_POST['ctitle']==$_POST['title']) $_POST['ctitle'] = '';
            }
            // Kein Leerzeichen vorhanden
            else {
                $_POST['login'] = $clearedname;
                $_POST['title'] = $_POST['ctitle'] = '';
            }
        }
    }
    // Login geändert
    elseif ($_POST['oldlogin']!=$_POST['login']) {
        if ($_POST['ctitle']!='') $_POST['name'] = $_POST['ctitle'].' '.$_POST['login'];
        else $_POST['name'] = $_POST['title'].' '.$_POST['login'];
    }
    // Titel geändert
    elseif ($_POST['oldtitle']!=$_POST['title'] && $_POST['ctitle']=='') {
        if ($_POST['oldctitle']!='') $colname = str_replace($_POST['oldctitle'],'',$_POST['name']);
        else $colname = str_replace($_POST['oldtitle'],'',$_POST['name']);
        $_POST['name'] = $_POST['title'].$colname;
    }
    // Usertitel geändert
    elseif ($_POST['oldctitle']!=$_POST['ctitle']) {
        if ($_POST['oldctitle']!='') $colname = str_replace($_POST['oldctitle'],'',$_POST['name']);
        else $colname = str_replace($_POST['oldtitle'],'',$_POST['name']);
        if ($_POST['ctitle']=='') $_POST['name'] = $_POST['title'].$colname;
        else $_POST['name'] = $_POST['ctitle'].$colname;
    }

    reset($_POST);
    while (list($key,$val)=each($_POST)){
        if (isset($userinfo[$key])){
            if ($key=='superuser' && ($val>$session['user']['superuser'] || $_POST['oldsuperuser']>$session['user']['superuser'])) continue;
            if ($key=="newpassword" ){
                if ($val>"") $sql.="password = MD5(\"$val\"),";
            }else{
                $sql.="$key = \"$val\",";
            }
        }
    }
    $sql=substr($sql,0,strlen($sql)-1);
    $sql.=" WHERE acctid=\"$_GET[userid]\"";
    //output("<pre>$sql</pre>");
    //echo "<pre>$sql</pre>";
    //redirect("user.php");
    //output( db_affected_rows()." rows affected");

    //we must manually redirect so that our changes go in to effect *after* our user save.
    addnav("","viewpetition.php?op=view&id={$_GET['returnpetition']}");
    addnav("","user.php");
    adminlog();
    saveuser();
    db_query($sql) or die(db_error(LINK));
    if ($_GET['returnpetition']!=""){
        header("Location: viewpetition.php?op=view&id={$_GET['returnpetition']}");
    }else{
        header("Location: user.php");
    }

    exit();
}elseif($_GET[op]=="setupban"){
    $sql = "SELECT name,lastip,uniqueid FROM accounts WHERE acctid=\"$_GET[userid]\"";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    if ($row[name]!="") output("Setting up ban information based on `\$$row[name]`0");
    output("<form action='user.php?op=saveban' method='POST'>",true);
    output("Verbannung über IP oder ID (IP bevorzugt. Bei Usern hinter NAT kannst du eine Verbannung über ID versuchen, die aber leicht ungangen werden kann)`n");
    output("<input type='radio' value='ip' name='type' checked> IP: <input name='ip' value=\"".HTMLEntities($row[lastip])."\">`n",true);
    output("<input type='radio' value='id' name='type'> ID: <input name='id' value=\"".HTMLEntities($row[uniqueid])."\">`n",true);
    output("Dauer: <input name='duration' id='duration' size='3' value='14'> days (0 for permanent)`n",true);
    output("Grund für die Verbannung: <input name='reason' value=\"Ärger mich nicht.\">`n",true);
    output("<input type='submit' class='button' value='Post Ban' onClick='if (document.getElementById(\"duration\").value==0) {return confirm(\"Willst du wirklich eine permanente Verbannung aussprechen?\");} else {return true;}'></form>",true);
    output("Bei einem IP-Bann gib entweder eine komplette IP ein, oder gebe nur den Anfang der IP ein, wenn du einen IP-Bereich sperren willst.");
    addnav("","user.php?op=saveban");
}elseif($_GET[op]=="saveban"){
    $sql = "INSERT INTO bans (";
    if ($_POST[type]=="ip"){
        $sql.="ipfilter";
    }else{
        $sql.="uniqueid";
    }
    $sql.=",banexpire,banreason) VALUES (";
    if ($_POST[type]=="ip"){
        $sql.="\"$_POST[ip]\"";
    }else{
        $sql.="\"$_POST[id]\"";
    }
    $sql.=",\"".((int)$_POST[duration]==0?"0000-00-00":date("Y-m-d",strtotime("+$_POST[duration] days")))."\",";
    $sql.="\"$_POST[reason]\")";
    if ($_POST[type]=="ip"){
        if (substr($_SERVER['REMOTE_ADDR'],0,strlen($_POST[ip])) == $_POST[ip]){
            $sql = "";
            output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene IP-Adresse!");
        }
    }else{
        if ($_COOKIE[lgi]==$_POST[id]){
            $sql = "";
            output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene ID!");
        }
    }
    if ($sql!=""){
        db_query($sql) or die(db_error(LINK));
        output(db_affected_rows()." Bann eingetragen.`n`n");
        output(db_error(LINK));
        adminlog();
    }
}elseif($_GET[op]=="delban"){
    $sql = "DELETE FROM bans WHERE ipfilter = '$_GET[ipfilter]' AND uniqueid = '$_GET[uniqueid]'";
    db_query($sql);
    adminlog();
    //output($sql);
    redirect("user.php?op=removeban");
}elseif($_GET[op]=="removeban"){
    db_query("DELETE FROM bans WHERE banexpire < \"".date("Y-m-d")."\" AND banexpire>'0000-00-00'");

    $sql = "SELECT * FROM bans ORDER BY banexpire";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table><tr><td>Ops</td><td>IP/ID</td><td>Dauer</td><td>Text</td><td>Betrifft:</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='user.php?op=delban&ipfilter=".URLEncode($row[ipfilter])."&uniqueid=".URLEncode($row[uniqueid])."'>Bann&nbsp;aufheben</a>",true);
        addnav("","user.php?op=delban&ipfilter=".URLEncode($row[ipfilter])."&uniqueid=".URLEncode($row[uniqueid]));
        output("</td><td>",true);
        output($row[ipfilter]);
        output($row[uniqueid]);
        output("</td><td>",true);
        $expire=round((strtotime($row[banexpire])-strtotime("now")) / 86400,0)." Tage";
        if (substr($expire,0,2)=="1 ") $expire="1 Tag";
        if (date("Y-m-d",strtotime($row[banexpire])) == date("Y-m-d")) $expire="Heute";
        if (date("Y-m-d",strtotime($row[banexpire])) == date("Y-m-d",strtotime("1 day"))) $expire="Morgen";
        if ($row[banexpire]=="0000-00-00") $expire="Nie";
        output($expire);
        output("</td><td>",true);
        output($row[banreason]);
        output("</td><td>",true);
        $sql = "SELECT DISTINCT accounts.name FROM bans, accounts WHERE (ipfilter='".addslashes($row['ipfilter'])."' AND bans.uniqueid='".addslashes($row['uniqueid'])."') AND ((substring(accounts.lastip,1,length(ipfilter))=ipfilter AND ipfilter<>'') OR (bans.uniqueid=accounts.uniqueid AND bans.uniqueid<>''))";
        $r = db_query($sql);
        for ($x=0;$x<db_num_rows($r);$x++){
            $ro = db_fetch_assoc($r);
            output("`0{$ro['name']}`n");
        }
        output("</td></tr>",true);
    }
    output("</table>",true);
}elseif ($_GET[op]=="debuglog"){
    $id = $_GET['userid'];
    $sql = "SELECT debuglog.*,a1.name as actorname,a2.name as targetname FROM debuglog LEFT JOIN accounts as a1 ON a1.acctid=debuglog.actor LEFT JOIN accounts as a2 ON a2.acctid=debuglog.target WHERE debuglog.actor=$id OR debuglog.target=$id ORDER by debuglog.date DESC,debuglog.id ASC LIMIT 500";
    addnav("User Info bearbeiten","user.php?op=edit&userid=$id");
    $result = db_query($sql);
    $odate = "";
    for ($i=0; $i<db_num_rows($result); $i++) {
        $row = db_fetch_assoc($result);
        $dom = date("D, M d",strtotime($row['date']));
        if ($odate != $dom){
            output("`n`b`@".$dom."`b`n");
            $odate = $dom;
        }
        $time = date("H:i:s", strtotime($row['date']));
        output("$time - {$row['actorname']} {$row['message']}");
        if ($row['target']) output(" {$row['targetname']}");
        output("`n");
    }
}elseif ($_GET[op]==""){
    if (isset($_GET['page'])){
        $order = "acctid";
        if ($_GET[sort]!="") $order = "$_GET[sort]";
        if (!empty($_GET['supage'])) $supage = 1;
        else $supage = 0;
        $offset=(int)$_GET['page']*100;
        $sql = "SELECT acctid,login,name,level,laston,gentimecount,lastip,uniqueid,emailaddress FROM accounts WHERE 1".($where>""?" AND ($where) ":"").($supage?" AND superuser > 0 ":"")." ORDER BY \"$order\"".($supage?'':" LIMIT $offset,100");
        $result = db_query($sql) or die(db_error(LINK));
        output("<table>",true);
        output("<tr>
        <td>Ops</td>
        <td><a href='user.php?sort=login&supage=$supage&page=$_GET[page]'>Login</a></td>
        <td><a href='user.php?sort=name&supage=$supage&page=$_GET[page]'>Name</a></td>
        <td><a href='user.php?sort=level&supage=$supage&page=$_GET[page]'>Lev</a></td>
        <td><a href='user.php?sort=laston&supage=$supage&page=$_GET[page]'>Zuletzt da</a></td>
        <td><a href='user.php?sort=gentimecount&supage=$supage&page=$_GET[page]'>Treffer</a></td>
        <td><a href='user.php?sort=lastip&supage=$supage&page=$_GET[page]'>IP</a></td>
        <td><a href='user.php?sort=uniqueid&supage=$supage&page=$_GET[page]'>ID</a></td>
        <td><a href='user.php?sort=emailaddress&supage=$supage&page=$_GET[page]'>E-Mail</a></td>
        </tr>",true);
        addnav("","user.php?sort=login&supage=$supage&page=$_GET[page]");
        addnav("","user.php?sort=name&supage=$supage&page=$_GET[page]");
        addnav("","user.php?sort=level&supage=$supage&page=$_GET[page]");
        addnav("","user.php?sort=laston&supage=$supage&page=$_GET[page]");
        addnav("","user.php?sort=gentimecount&supage=$supage&page=$_GET[page]");
        addnav("","user.php?sort=lastip&supage=$supage&page=$_GET[page]");
        addnav("","user.php?sort=uniqueid&supage=$supage&page=$_GET[page]");
        $rn=0;
        for ($i=0;$i<db_num_rows($result);$i++){
            $row=db_fetch_assoc($result);
            $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
            if (substr($laston,0,2)=="1 ") $laston="1 Tag";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d")) $laston="Heute";
            if (date("Y-m-d",strtotime($row[laston])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern";
            if ($loggedin) $laston="Jetzt";
            $row[laston]=$laston;
            if ($row[$order]!=$oorder) $rn++;
            $oorder = $row[$order];
            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);

            output("<td>",true);
            output("[<a href='user.php?op=edit&userid=$row[acctid]'>Edit</a>|".
                "<a href='superuser.php?op=userdelete&userid=$row[acctid]&return=".rawurlencode("user.php?page=$_GET[page]&sort=$_GET[sort]&supage=$supage")."'>Del</a>|".
                "<a href='user.php?op=setupban&userid=$row[acctid]'>Ban</a>|".
                "<a href='user.php?op=debuglog&userid=$row[acctid]'>Log</a>]",true);
            addnav("","user.php?op=edit&userid=$row[acctid]");
            addnav("","superuser.php?op=userdelete&userid=$row[acctid]&return=".rawurlencode("user.php?page=$_GET[page]&sort=$_GET[sort]&supage=$supage"));
            addnav("","user.php?op=setupban&userid=$row[acctid]");
            addnav("","user.php?op=debuglog&userid=$row[acctid]");
            output("</td><td>",true);
            output($row[login]);
            output("</td><td>",true);
            output($row[name]);
            output("</td><td>",true);
            output($row[level]);
            output("</td><td>",true);
            output($row[laston]);
            output("</td><td>",true);
            output($row[gentimecount]);
            output("</td><td>",true);
            output($row[lastip]);
            output("</td><td>",true);
            output($row[uniqueid]);
            output("</td><td>",true);
            output($row[emailaddress]);
            output("</td>",true);
            $gentimecount+=$row[gentimecount];
            $gentime+=$row[gentime];

            output("</tr>",true);
        }
        output("</table>",true);
        output("Treffer gesamt: $gentimecount`n");
        output("CPU-Zeit gesamt: ".round($gentime,3)."s`n");
        output("Durchschnittszeit für Seitenerzeugung: ".round($gentime/max($gentimecount,1),4)."s`n");
    }
}
page_footer();
?>