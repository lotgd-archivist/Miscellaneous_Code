
ï»¿<?php



// 20060302



require_once("common.php");

isnewday(3);



if ($_GET['op']=="search"){

    $sql = "SELECT acctid FROM accounts WHERE ";

    $where="

    login LIKE '%{$_POST['q']}%' OR

    acctid LIKE '%{$_POST['q']}%' OR

    name LIKE '%{$_POST['q']}%' OR

    emailaddress LIKE '%{$_POST['q']}%' OR

    lastip LIKE '%{$_POST['q']}%' OR

    uniqueid LIKE '%{$_POST['q']}%' OR

    gentimecount LIKE '%{$_POST['q']}%' OR

    level LIKE '%{$_POST['q']}%'";

    $result = db_query($sql.$where);

    if (db_num_rows($result)<=0){

        output("`\$Keine Ergebnisse gefunden`0");

        $_GET['op']="";

        $where="";

    }elseif (db_num_rows($result)>100){

        output("`\$Zu viele Ergebnisse gefunden. Bitte Suche einengen.`0");

        $_GET['op']="";

        $where="";

    }elseif (db_num_rows($result)==1){

        //$row = db_fetch_assoc($result);

        //redirect("user.php?op=edit&userid=$row[acctid]");

        $_GET['op']="";

        $_GET['page']=0;

    }else{

        $_GET['op']="";

        $_GET['page']=0;

    }

}



page_header("User Editor");

output("<form action='user.php?op=search' method='POST'>Suche in allen Feldern: <input name='q' id='q'><input type='submit' class='button'></form>",true);

output("<script language='JavaScript'>document.getElementById('q').focus();</script>",true);

addnav("","user.php?op=search");

addnav("G?ZurÃ¼ck zur Grotte","superuser.php");

addnav("W?ZurÃ¼ck zum Weltlichen","village.php");

addnav("Verbannung","user.php?op=setupban");

addnav("Verbannungen anzeigen/entfernen","user.php?op=removeban");

$sql = "SELECT count(acctid) AS count FROM accounts";

$result = db_query($sql);

$row = db_fetch_assoc($result);

$page=0;

while ($row['count']>0){

    $page++;

    addnav("$page Seite $page","user.php?page=".($page-1)."&sort={$_GET['sort']}");

    $row['count']-=100;

}



$mounts=",0,Keins";

$sql = "SELECT mountid,mountname,mountcategory FROM mounts ORDER BY mountcategory";

$result = db_query($sql);

while ($row = db_fetch_assoc($result)){

    $mounts.=",{$row['mountid']},{$row['mountcategory']}: {$row['mountname']}";

}

$userinfo = array(

    "Account Info,title",

    "acctid"=>"User ID,viewonly",

    "login"=>"Login",

    "newpassword"=>"Neues Passwort",

    "emailaddress"=>"Email Adresse",

    "locked"=>"Account gesperrt,bool",

    "banoverride"=>"Verbannungen Ã¼bergehen,bool",

    "superuser"=>"Superuser,enum,0,Standard Spieltage pro Kalendertag,1,Unbegrenzt Spieltage pro Kalendertag,2,Kreaturen und Spott administrieren,3,User administrieren",



    "User Infos,title",

    "name"=>"Display Name",

    "title"=>"Titel (muss auch in Display Name)",

    "ctitle"=>"Eigener Titel (muss auch in Display Name)",

    "sex"=>"Geschlecht,enum,0,MÃ¤nnlich,1,Weiblich",

// we can't change this this way or their stats will be wrong.

    "race"=>"Rasse (NICHT Ã„NDERN!),enum,0,Unbekannt,1,Troll,2,Elf,3,Mensch,4,Zwerg,5,Echse,6,Vampir",

    "age"=>"Tage seit Level 1,int",

    "dragonkills"=>"Drachenkills,int",

    "dragonage"=>"Alter beim letzten Drachenkill,int",

    "bestdragonage"=>"JÃ¼ngstes Alter bei einem Drachenkill,int",

    "bio"=>"Bio",

    "grabinschrift"=>"Grabinschrift",



    "Werte,title",

    "level"=>"Level,int",

    "experience"=>"Erfahrung,int",

    "hitpoints"=>"Lebenspunkte (aktuell),int",

    "maxhitpoints"=>"Maximale Lebenspunkte,int",

    "turns"=>"Runden Ã¼brig,int",

    "playerfights"=>"SpielerkÃ¤mpfe Ã¼brig,int",

    "attack"=>"Angriffswert (inkl. Waffenschaden),int",

    "defence"=>"Verteidigung (inkl. RÃ¼stung),int",

    "spirits"=>"Stimmung (nur Anzeige),enum,-2,Sehr schlecht,-1,Schlecht,0,Normal,1,Gut,2,Sehr gut",

    "resurrections"=>"Auferstehungen,int",

    "alive"=>"Lebendig,int",

    "reputation"=>"Ansehen (-50 - +50),int",



    "SpezialitÃ¤ten,title",

    "specialty"=>"SpezialitÃ¤t,enum,0,Unspezifiziert,1,Dunkle KÃ¼nste,2,Mystische KrÃ¤fte,3,Diebeskunst,4,Kampfkunst,5,Spirituelle KrÃ¤fte,6,Naturkraft",

    "darkarts"=>"`4Stufe  in Dunklen KÃ¼nsten`0,int",

    "darkartuses"=>"`4^--heute Ã¼brig`0,int",

    "magic"=>"`%Stufe in Mystischen KrÃ¤ften`0,int",

    "magicuses"=>"`%^--heute Ã¼brig`0,int",

    "thievery"=>"`^Stufe in Diebeskunst`0,int",

    "thieveryuses"=>"`^^--heute Ã¼brig`0,int",

    "warriorsart"=>"`qStufe in Kampfkunst`0,int",

    "warriorsartuses"=>"`q^--heute Ã¼brig`0,int",

    "priestsart"=>"`#Stufe in Spirituelle KrÃ¤fte`0,int",

    "priestsartuses"=>"`#^--heute Ã¼brig`0,int",

    "rangersart"=>"`@Stufe in Naturkraft`0,int",

    "rangersartuses"=>"`@^--heute Ã¼brig`0,int",



    "GrabkÃ¤mpfe,title",

    "deathpower"=>"Gefallen bei Ramius,int",

    "gravefights"=>"GrabkÃ¤mpfe Ã¼brig,int",

    "soulpoints"=>"Seelenpunkte (HP im Tod),int",



    "Ausstattung,title",

    "gems"=>"Edelsteine,int",

    "gold"=>"Bargold,int",

    "goldinbank"=>"Gold auf der Bank,int",

    "transferredtoday"=>"Anzahl Transfers heute,int",

    "amountouttoday"=>"Heute ausgegangener Wert der Ãœberweisungen,int",

    "weapon"=>"Name der Waffe",

    "weapondmg"=>"Waffenschaden,int",

    "weaponvalue"=>"Kaufwert der Waffe,int",

    "armor"=>"Name der RÃ¼stung",

    "armordef"=>"Verteidigungswert,int",

    "armorvalue"=>"Kaufwert der RÃ¼stung,int",



    "Sonderinfos,title",

    "birthday"=>"Geburtstag",

    "house"=>"Haus-ID,int",

    "housekey"=>"HausschlÃ¼ssel?,int",

        "jail"=>"Am Pranger?,bool",

    "marriedto"=>"Partner-ID (4294967295 = Violet/Seth),int",

    "charisma"=>"Flirts (4294967295 = verheiratet mit Partner),int",

    "seenlover"=>"Geflirtet,bool",

    "seenbard"=>"Barden gehÃ¶rt,bool",

    "charm"=>"Charme,int",

    "seendragon"=>"Drachen heute gesucht,bool",

    "seenmaster"=>"Meister befragt,bool",

    "usedouthouse"=>"Plumpsklo besucht,bool",

    "fedmount"=>"Tier gefÃ¼ttert,bool",

    "gotfreeale"=>"Frei-Ale (MSB: getrunken - LSB: spendiert),int",

    "hashorse"=>"Tier,enum$mounts",

    "boughtroomtoday"=>"Zimmer fÃ¼r heute bezahlt,bool",

    "drunkenness"=>"Betrunken (0-100),int",

    "kleineswesen"=>"WKs durch kleines Wesen,int",

    "avatar"=>"Avatar:",



    "Weitere Infos,title",

    "beta"=>"Nimmt am Betatest teil,viewonly",

    "slainby"=>"Gekillt von Spieler,viewonly",

    "laston"=>"Zuletzt Online,viewonly",

    "lasthit"=>"Letzter neuer Tag,viewonly",

    "lastmotd"=>"Datum der letzten MOTD,viewonly",

    "lastip"=>"Letzte IP,viewonly",

    "uniqueid"=>"Unique ID,viewonly",

    "gentime"=>"Summe der Seitenerzeugungszeiten,viewonly",

    "gentimecount"=>"Seitentreffer,viewonly",

    "allowednavs"=>"ZulÃ¤ssige Navigation,viewonly",

    "dragonpoints"=>"Eingesetzte Drachenpunkte,viewonly",

    "bufflist"=>"Spruchliste,viewonly",

    "prefs"=>"Einstellungen,viewonly",

    "lastwebvote"=>"Zuletzt bei Top Wep Games gewÃ¤hlt,viewonly",

    "donationconfig"=>"SpendenkÃ¤ufe,viewonly"

    );



if ($_GET['op']=="lasthit"){

    $output="";

    $sql = "SELECT output FROM accounts WHERE acctid='{$_GET['userid']}'";

    $result = db_query($sql);

    $row = db_fetch_assoc($result);

    echo str_replace("<iframe src=","<iframe Xsrc=",$row['output']);

    exit();

}elseif ($_GET['op']=="edit"){

    $result = db_query("SELECT * FROM accounts WHERE acctid='{$_GET['userid']}'") or die(db_error(LINK));

    $row = db_fetch_assoc($result) or die(db_error(LINK));

    output("<form action='user.php?op=special&userid={$_GET['userid']}".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);

    addnav("","user.php?op=special&userid={$_GET['userid']}".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");

    output("<input type='submit' class='button' name='newday' value='Neuen Tag gewÃ¤hren'>",true);

    output("<input type='submit' class='button' name='fixnavs' value='Defekte Navs reparieren'>",true);

    output("<input type='submit' class='button' name='clearvalidation' value='E-Mail als gÃ¼ltig markieren'>",true);

    output("</form>",true);



    if ($_GET['returnpetition']!=""){

        addnav("ZurÃ¼ck zur Anfrage","viewpetition.php?op=view&id={$_GET['returnpetition']}");

    }



    addnav("Letzten Treffer anzeigen","user.php?op=lasthit&userid={$_GET['userid']}",false,true);

    output("<form action='user.php?op=save&userid={$_GET['userid']}".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."' method='POST'>",true);

    addnav("","user.php?op=save&userid={$_GET['userid']}".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");

    addnav("","user.php?op=edit&userid={$_GET['userid']}".($_GET['returnpetition']!=""?"&returnpetition={$_GET['returnpetition']}":"")."");

    addnav("Verbannen","user.php?op=setupban&userid={$row['acctid']}");

    addnav("Debug-Log anzeigen","user.php?op=debuglog&userid={$_GET['userid']}");

    output("<input type='submit' class='button' value='Speichern'>",true);

    showform($userinfo,$row);

    output("</form>",true);

    output("<iframe src='user.php?op=lasthit&userid={$_GET['userid']}' width='100%' height='400'>Dein Browser muss iframes unterstÃ¼tzen, um die letzte Seite des Users anzeigen zu kÃ¶nnen. Benutze den Link im MenÃ¼ stattdessen.</iframe>",true);

    addnav("","user.php?op=lasthit&userid={$_GET['userid']}");

}elseif ($_GET['op']=="special"){

    if ($_POST['newday']!=""){

        $sql = "UPDATE accounts SET lasthit='".date("Y-m-d H:i:s",strtotime(date("Y-m-d H:i:s")."-".(86500/getsetting("daysperday",4))." seconds"))."' WHERE acctid='{$_GET['userid']}'";

    }elseif($_POST['fixnavs']!=""){

        $sql = "UPDATE accounts SET allowednavs='',output='' WHERE acctid={$_GET['userid']}";

    }elseif($_POST['clearvalidation']!=""){

        $sql = "UPDATE accounts SET emailvalidation='' WHERE acctid={$_GET['userid']}";

    }

    db_query($sql);

    if ($_GET['returnpetition']==""){

        redirect("user.php");

    }else{

        redirect("viewpetition.php?op=view&id={$_GET['returnpetition']}");

    }

}elseif ($_GET['op']=="save"){

    $sql = "UPDATE accounts SET ";

    // Ein paar Sicherheiten fÃ¼r Ã„nderungen

    // Gesamtname geÃ¤ndert

    if ($_POST['oldname']!=$_POST['name']) {

        $clearedname = preg_replace('/`./','',$_POST['name']);

        // Login bleibt gleich

        if (substr_count($clearedname,$_POST['login'])) {

            // Titel rausfinden

            $replace = '(`.)*';

            for ($i=0;$i<strlen($_POST['login']);$i++) {

                $replace .= $_POST['login']{$i}.'(`.)*';

            }

            $_POST['ctitle'] = rtrim(preg_replace('/'.$replace.'/','',$_POST['name']));

            if ($_POST['ctitle']=='') $_POST['title'] = '';

            elseif ($_POST['ctitle']==$_POST['title']) $_POST['ctitle'] = '';

        }

        // Neuer Login

        else {

            // Leerzeichen vorhanden

            if ($login = strrchr($_POST['name'],' ') && getsetting("spaceinname",0)==false) {

                $_POST['login'] = trim(strrchr($clearedname,' '));

                $_POST['ctitle'] = str_replace($login,'',$_POST['name']);

                if ($_POST['ctitle']==$_POST['title']) $_POST['ctitle'] = '';

            }

            // Kein Leerzeichen vorhanden

            else {

                $_POST['login'] = $clearedname;

                $_POST['title'] = $_POST['ctitle'] = '';

            }

        }

    }

    // Login geÃ¤ndert

    elseif ($_POST['oldlogin']!=$_POST['login']) {

        if ($_POST['ctitle']!='') $_POST['name'] = $_POST['ctitle'].' '.$_POST['login'];

        else $_POST['name'] = $_POST['title'].' '.$_POST['login'];

    }

    // Titel geÃ¤ndert

    elseif ($_POST['oldtitle']!=$_POST['title'] && $_POST['ctitle']=='') {

        if ($_POST['oldctitle']!='') $colname = str_replace($_POST['oldctitle'],'',$_POST['name']);

        else $colname = str_replace($_POST['oldtitle'],'',$_POST['name']);

        $_POST['name'] = $_POST['title'].$colname;

    }

    // Usertitel geÃ¤ndert

    elseif ($_POST['oldctitle']!=$_POST['ctitle']) {

        if ($_POST['oldctitle']!='') $colname = str_replace($_POST['oldctitle'],'',$_POST['name']);

        else $colname = str_replace($_POST['oldtitle'],'',$_POST['name']);

        if ($_POST['ctitle']=='') $_POST['name'] = $_POST['title'].$colname;

        else $_POST['name'] = $_POST['ctitle'].$colname;

    }



    reset($_POST);

    while (list($key,$val)=each($_POST)){

        if (isset($userinfo[$key])){

            if ($key=="newpassword" ){

                if ($val>"") $sql.="password = MD5(\"$val\"),";

            }else{

                $sql.="$key = \"$val\",";

            }

        }

    }

    $sql=substr($sql,0,strlen($sql)-1);

    $sql.=" WHERE acctid=\"{$_GET['userid']}\"";



    //we must manually redirect so that our changes go in to effect *after* our user save.

    addnav("","viewpetition.php?op=view&id={$_GET['returnpetition']}");

    addnav("","user.php");

    saveuser();

    db_query($sql) or die(db_error(LINK));

    if ($_GET['returnpetition']!=""){

        header("Location: viewpetition.php?op=view&id={$_GET['returnpetition']}");

    }else{

        header("Location: user.php");

    }



    exit();

}elseif ($_GET['op']=="del"){

    $sql = "SELECT name from accounts WHERE acctid='{$_GET['userid']}'";

    $res = db_query($sql);

    deleteuser($_GET['userid'],"`\$(von den GÃ¶ttern aufgelÃ¶st)");

    output( db_affected_rows()." Benutzer gelÃ¶scht.");

    while ($row = db_fetch_assoc($res)) {

        addnews("`#{$row['name']} wurde von den GÃ¶ttern aufgelÃ¶st.");

    }

}elseif($_GET['op']=="setupban"){

    $sql = "SELECT name,lastip,uniqueid FROM accounts WHERE acctid=\"{$_GET['userid']}\"";

    $result = db_query($sql) or die(db_error(LINK));

    $row = db_fetch_assoc($result);

    if ($row['name']!="") output("Verbannung fÃ¼r \"`\${$row['name']}\" einrichten`0");

    output("<form action='user.php?op=saveban' method='POST'>",true);

    output("Verbannung Ã¼ber IP oder ID (IP bevorzugt. Bei Usern hinter NAT kannst du eine Verbannung Ã¼ber ID versuchen, die aber leicht umgangen werden kann)`n");

    output("<input type='radio' value='ip' name='type' checked> IP: <input name='ip' value=\"".HTMLEntities($row['lastip'])."\">`n",true);

    output("<input type='radio' value='id' name='type'> ID: <input name='id' value=\"".HTMLEntities($row['uniqueid'])."\">`n",true);

    output("Dauer: <input name='duration' id='duration' size='3' value='14'> Tage (0 f&uuml;r permanent)`n",true);

    output("Grund fÃ¼r die Verbannung: <input name='reason' value=\"&Auml;rger mich nicht.\">`n",true);

    output("<input type='submit' class='button' value='Post Ban' onClick='if (document.getElementById(\"duration\").value==0) {return confirm(\"Willst du wirklich eine permanente Verbannung aussprechen?\");} else {return true;}'></form>",true);

    output("Bei einem IP-Bann gib entweder eine komplette IP ein, oder gebe nur den Anfang der IP ein, wenn du einen IP-Bereich sperren willst.");

    addnav("","user.php?op=saveban");

}elseif($_GET['op']=="saveban"){

    $sql = "INSERT INTO bans (";

    if ($_POST['type']=="ip"){

        $sql.="ipfilter";

    }else{

        $sql.="uniqueid";

    }

    $sql.=",banexpire,banreason) VALUES (";

    if ($_POST['type']=="ip"){

        $sql.="\"{$_POST['ip']}\"";

    }else{

        $sql.="\"{$_POST['id']}\"";

    }

    $sql.=",\"".((int)$_POST['duration']==0?"0000-00-00":date("Y-m-d",strtotime(date("Y-m-d H:i:s")."+{$_POST['duration']} days")))."\",";

    $sql.="\"{$_POST['reason']}\")";

    if ($_POST['type']=="ip"){

        if (substr($_SERVER['REMOTE_ADDR'],0,strlen($_POST['ip'])) == $_POST['ip']){

            $sql = "";

            output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene IP-Adresse!");

        }

    }else{

        if ($_COOKIE['lgi']==$_POST['id']){

            $sql = "";

            output("Du willst dich doch nicht wirklich selbst verbannen, oder?? Das ist deine eigene ID!");

        }

    }

    if ($sql!=""){

        db_query($sql) or die(db_error(LINK));

        output(db_affected_rows()." Bann eingetragen.`n`n");

        output(db_error(LINK));

    }

}elseif($_GET['op']=="delban"){

    $sql = "DELETE FROM bans WHERE ipfilter = '{$_GET['ipfilter']}' AND uniqueid = '{$_GET['uniqueid']}'";

    db_query($sql);

    redirect("user.php?op=removeban");

}elseif($_GET['op']=="removeban"){

    db_query("DELETE FROM bans WHERE banexpire < \"".date("Y-m-d")."\" AND banexpire>'0000-00-00'");



    $sql = "SELECT * FROM bans ORDER BY banexpire";

    $result = db_query($sql) or die(db_error(LINK));

    output("<table><tr><td>Ops</td><td>IP/ID</td><td>Dauer</td><td>Text</td><td>Betrifft:</td></tr>",true);

    for ($i=0;$i<db_num_rows($result);$i++){

        $row = db_fetch_assoc($result);

        output("<tr class='".($i%2?"trlight":"trdark")."'><td><a href='user.php?op=delban&ipfilter=".URLEncode($row['ipfilter'])."&uniqueid=".URLEncode($row['uniqueid'])."'>Bann&nbsp;aufheben</a>",true);

        addnav("","user.php?op=delban&ipfilter=".URLEncode($row['ipfilter'])."&uniqueid=".URLEncode($row['uniqueid']));

        output("</td><td>",true);

        output($row['ipfilter']);

        output($row['uniqueid']);

        output("</td><td>",true);

        $expire=round((strtotime($row['banexpire'])-strtotime(date("Y-m-d H:i:s"))) / 86400,0)." Tage";

        if (substr($expire,0,2)=="1 ") $expire="1 Tag";

        if (date("Y-m-d",strtotime($row['banexpire'])) == date("Y-m-d")) $expire="Heute";

        if (date("Y-m-d",strtotime($row['banexpire'])) == date("Y-m-d",strtotime("1 day"))) $expire="Morgen";

        if ($row['banexpire']=="0000-00-00") $expire="Nie";

        output($expire);

        output("</td><td>",true);

        output($row['banreason']);

        output("</td><td>",true);

        $sql = "SELECT DISTINCT accounts.name FROM bans, accounts WHERE (ipfilter='".addslashes($row['ipfilter'])."' AND bans.uniqueid='".addslashes($row['uniqueid'])."') AND ((substring(accounts.lastip,1,length(ipfilter))=ipfilter AND ipfilter<>'') OR (bans.uniqueid=accounts.uniqueid AND bans.uniqueid<>''))";

        $r = db_query($sql);

        for ($x=0;$x<db_num_rows($r);$x++){

            $ro = db_fetch_assoc($r);

            output("`0{$ro['name']}`n");

        }

        output("</td></tr>",true);

    }

    output("</table>",true);

}elseif ($_GET['op']=="debuglog"){

    $id = $_GET['userid'];

    $sql = "SELECT debuglog.*,a1.name as actorname,a2.name as targetname FROM debuglog LEFT JOIN accounts as a1 ON a1.acctid=debuglog.actor LEFT JOIN accounts as a2 ON a2.acctid=debuglog.target WHERE debuglog.actor=$id OR debuglog.target=$id ORDER by debuglog.date DESC,debuglog.id ASC LIMIT 500";

    addnav("User Info bearbeiten","user.php?op=edit&userid=$id");

    $result = db_query($sql);

    $odate = "";

    for ($i=0; $i<db_num_rows($result); $i++) {

        $row = db_fetch_assoc($result);

        $dom = date("D, M d",strtotime($row['date']));

        if ($odate != $dom){

            output("`n`b`@".$dom."`b`n");

            $odate = $dom;

        }

        $time = date("H:i:s", strtotime($row['date']));

        output("$time - {$row['actorname']} {$row['message']}");

        if ($row['target']) output(" {$row['targetname']}");

        output("`n");

    }

}elseif ($_GET['op']==""){

    if (isset($_GET['page'])){

        $order = "acctid";

        if ($_GET['sort']!="") $order = "{$_GET['sort']}";

        $offset=(int)$_GET['page']*100;

        $sql = "SELECT acctid,login,name,level,laston,gentimecount,lastip,uniqueid,emailaddress FROM accounts ".($where>""?"WHERE $where ":"")."ORDER BY \"$order\" LIMIT $offset,100";

        $result = db_query($sql) or die(db_error(LINK));

        output("<table>",true);

        output("<tr>

        <td>Ops</td>

        <td><a href='user.php?sort=login'>Login</a></td>

        <td><a href='user.php?sort=name'>Name</a></td>

        <td><a href='user.php?sort=level'>Lev</a></td>

        <td><a href='user.php?sort=laston'>Zuletzt da</a></td>

        <td><a href='user.php?sort=gentimecount'>Treffer</a></td>

        <td><a href='user.php?sort=lastip'>IP</a></td>

        <td><a href='user.php?sort=uniqueid'>ID</a></td>

        <td><a href='user.php?sort=emailaddress'>E-Mail</a></td>

        </tr>",true);

        addnav("","user.php?sort=login");

        addnav("","user.php?sort=name");

        addnav("","user.php?sort=level");

        addnav("","user.php?sort=laston");

        addnav("","user.php?sort=gentimecount");

        addnav("","user.php?sort=lastip");

        addnav("","user.php?sort=uniqueid");

        $rn=0;

        for ($i=0;$i<db_num_rows($result);$i++){

            $row=db_fetch_assoc($result);

            $laston=round((strtotime(date("Y-m-d H:i:s"))-strtotime($row['laston'])) / 86400,0)." Tage";

            if (substr($laston,0,2)=="1 ") $laston="1 Tag";

            if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d")) $laston="Heute";

            if (date("Y-m-d",strtotime($row['laston'])) == date("Y-m-d",strtotime(date("Y-m-d H:i:s")."-1 day"))) $laston="Gestern";

            if ($loggedin) $laston="Jetzt";

            $row['laston']=$laston;

            if ($row[$order]!=$oorder) $rn++;

            $oorder = $row[$order];

            output("<tr class='".($rn%2?"trlight":"trdark")."'>",true);



            output("<td>",true);

            output("[<a href='user.php?op=edit&userid={$row['acctid']}'>Edit</a>|".

                "<a href='user.php?op=del&userid={$row['acctid']}' onClick=\"return confirm('Willst du diesen User wirklich lÃ¶schen?');\">Del</a>|".

                "<a href='user.php?op=setupban&userid={$row['acctid']}'>Ban</a>|".

                "<a href='user.php?op=debuglog&userid={$row['acctid']}'>Log</a>]",true);

            addnav("","user.php?op=edit&userid={$row['acctid']}");

            addnav("","user.php?op=del&userid={$row['acctid']}");

            addnav("","user.php?op=setupban&userid={$row['acctid']}");

            addnav("","user.php?op=debuglog&userid={$row['acctid']}");

            output("</td><td>",true);

            output($row['login']);

            output("</td><td>",true);

            output($row['name']);

            output("</td><td>",true);

            output($row['level']);

            output("</td><td>",true);

            output($row['laston']);

            output("</td><td>",true);

            output($row['gentimecount']);

            output("</td><td>",true);

            output($row['lastip']);

            output("</td><td>",true);

            output($row['uniqueid']);

            output("</td><td>",true);

            output($row['emailaddress']);

            output("</td>",true);

            $gentimecount+=$row['gentimecount'];

            $gentime+=$row['gentime'];



            output("</tr>",true);

        }

        output("</table>",true);

        output("Treffer gesamt: $gentimecount`n");

        output("CPU-Zeit gesamt: ".round($gentime,3)."s`n");

        output("Durchschnittszeit fÃ¼r Seitenerzeugung: ".round($gentime/max($gentimecount,1),4)."s`n");

    }

}

page_footer();

?>

