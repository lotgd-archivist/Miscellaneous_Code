
<?phpini_set( 'default_charset', 'iso-8859-1' );// 09092004require_once "common.php";require_once "func/clearnav.php";global $mysqli;// print_r($_GET);if(isset($_GET['btnCookieOk'])){   setcookie("eu-cookie","1",strtotime(date("c")."+365 days"));}// Adventsspecial für Merydiâ (c) by Leen/Cassandra (cassandra@leensworld.de)$realdatum = time();$datum = date('m-d',$realdatum);$jahr = date('Y',$realdatum);// settings -end-// Datum festlegen und welcher Dezember gerade istif ($datum >= '12-24' && $datum <= '12-26'){    $weihnacht = $datum;}else $weihnacht = '0';if ($datum >= '12-01' && $datum <= '12-24'){    $weihnachtszeit = $datum;}else $weihnachtszeit = '0';if (($datum >= '12-01' &&  $datum <= '12-30') || ($datum >= '01-02' &&  $datum <= '01-31')){    $schnee = $datum;}else $schnee = '0';if ($datum == '12-31' || $datum == '01-01'){    $feuerwerk = $datum;}else $feuerwerk = '0';// Ende der Datumsabfrage// speichern in Settingssavesetting('weihnachtszeit',$weihnachtszeit);savesetting('schnee',$schnee);if (isset($session['loggedin']) && $session['loggedin']){    redirect("badnav.php");}page_header();if ($schnee) require_once "snow.htm";if ($feuerwerk) require_once "feuerwerk.htm";output("<table valign='top' border='0' align=center>",true);output("<tr align=center><td>Willkommen in den Wäldern von Alvion, schamlos abgekupfert von Seth Able's Legend of the Red Dragon.`n`n</td></tr>", true);if (getsetting('activategamedate','0')==1) output("<tr align=center><td>`@Wir schreiben den `3".getgamedate()."`@.`0`n</td></tr>", true);output("<tr align=center><td>`@Die gegenwärtige Zeit im Dorf ist `3".getgametime()."`@.`0`n</td></tr>", true);//Next New Day in ... is by JT from logd.dragoncat.net$time = gametime();$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));$secstotomorrow = $tomorrow-$time;$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));$calctime = strtotime('1980-01-01 00:00:00 + '.$realsecstotomorrow.' seconds');$nextdattime = date('G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n',$calctime);$strtime="";$strtime .='<div id="index_time">'.$nextdattime.'</div>`n';$strtime .='<script language="javascript" type="text/javascript">    var index_time_div = document.getElementById("index_time");    var index_time_day = Math.ceil(24/'.(int)getsetting("daysperday",4).');    var index_dest_time = 0;    </script>    <script language="javascript" type="text/javascript" src="./templates/stuff.js"></script>    <script language="javascript" type="text/javascript">    if( index_time_div ){        index_set_time('.date('G, i, s',$calctime).');    }    </script>    ';output("<tr align=center><td>".$strtime."`0</td></tr>",true);output("<tr align=center><td><img src='./images/alvion.gif' border='0'/><br/><br/></td></tr>", true);// Letzter DK$newdk=stripslashes(getsetting("newdragonkill",""));if ($newdk!="") {    $sql = "SELECT name,acctid,sex FROM accounts WHERE name='".mysqli_real_escape_string($mysqli, $newdk)."'";    $dk = db_fetch_assoc(db_query($sql));    output("<tr align=center><td>`@".($dk['sex']?"Die letzte Drachentöterin":"Der letzte Drachentöter")." war `&$dk[name]`@.`0`n</td></tr>", true);}//Das goldene Eiif (getsetting("hasegg",0)>0){  $sql = "SELECT name FROM accounts WHERE acctid = ".getsetting("hasegg",0);  $result1 = db_query($sql) or die(db_error(LINK));  $row = db_fetch_assoc($result1);  output("<tr align=center><td>`@Das `^goldene Ei `@ist im Besitz von `&$row[name]`@.`n</td></tr>", true);}//Der Schattenringif (getsetting("hasring",0)){    $sql="SELECT name FROM accounts WHERE acctid=".getsetting("hasring",0);    $result = db_query($sql) or die(db_error(LINK));    $row = db_fetch_assoc($result);    output("<tr align=center><td>`@Der `SRing der Schatten `@ist im Besitz von $row[name]`@.`n`0</td></tr>", true);}$sql = "SELECT name,dragonkills,sex FROM accounts WHERE 1 ORDER BY dragonkills DESC LIMIT 1";$result = db_query($sql);$row = db_fetch_assoc($result);$dker = $row['name'];$kills = $row['dragonkills'];$sql2 = "SELECT name,herotattoo,sex FROM accounts WHERE 1 ORDER BY herotattoo DESC, dragonkills DESC, level DESC LIMIT 1";$result2 = db_query($sql2);$row2 = db_fetch_assoc($result2);$hero1 = $row2['name'];$hero2 = $row2['herotattoo'];$sql3 = "SELECT gildenname,gildenpunkte,gildenprefix FROM gilden WHERE 1 ORDER BY gildenpunkte DESC LIMIT 1";$result3 = db_query($sql3);$row3 = db_fetch_assoc($result3);$guildname = $row3['gildenname'];$guildpoints = $row3['gildenpunkte'];$guildprefix = $row3['gildenprefix'];$sql4 = "SELECT name,battlepoints,sex FROM accounts WHERE 1 ORDER BY battlepoints DESC LIMIT 1";$result4 = db_query($sql4);$row4 = db_fetch_assoc($result4);$bhero = $row4['name'];$bpoints =$row4['battlepoints'];$sql5 = "SELECT name,rp_points,sex FROM accounts WHERE 1 ORDER BY rp_points DESC LIMIT 1";$result5 = db_query($sql5);$row5 = db_fetch_assoc($result5);$labertasche = $row5['name'];$laberpoints =$row5['rp_points'];if (db_num_rows($result3)>0) output("<tr align=center><td>`@Die erfolgreichste Gilde`@: $guildname `@ mit `^ $guildpoints `@Punkten.`n</td></tr>", true);output("<tr align=center><td>`@".($row2['sex']?"Die Spielerin":"Der Spieler")." mit den meisten Tätowierungen: $hero1`@ mit `^$hero2 `@Tattoos.`n</td></tr>", true);output("<tr align=center><td>`@".($row['sex']?"Die Spielerin":"Der Spieler")." mit den meisten Drachenkills: $dker`@ mit `^$kills `@Drachenkills.`n</td></tr>", true);output("<tr align=center><td>`@".($row4['sex']?"Die":"Der")." beste ".($row4['sex']?"Arenakämpferin":"Arenakämpfer").": $bhero`@ mit `^$bpoints `@Kampfpunkten.`n</td></tr>", true);output("<tr align=center><td>`@".($row5['sex']?"Die":"Der")." fleißigste RP-Spieler".($row5['sex']?"in":"")."`@: $labertasche`@ mit`^ $laberpoints `@RP-Punkten.`n</td></tr>", true);$result = db_query("SELECT sum(orden) as orden from accounts");$row = db_fetch_assoc($result);output("<tr align=center><td>`@Die Dorfbewohner haben insgesamt `^{$row['orden']} `@Orden erkämpft.`n</td></tr>", true);// $result = db_query("SELECT sum(dragonkills) as dragonkills from accounts");// $row = db_fetch_assoc($result);$gesamt_dk=getsetting('drachen_sum',0);output("<tr align=center><td>`@In Alvion `@starben bereits `^{$gesamt_dk} `@grüne Drachen.`n</td></tr>", true);if ($datum=='12-31'){    output("<tr align=center><td><font size='4'><br/>`^Das Team von Alvion wünscht einen guten Rutsch und ein glückliches Jahr ".($jahr+1)."</font></td></tr>", true);}elseif ($datum=='01-01'){    output("<tr align=center><td><font size='4'><br/>`^Das Team von Alvion wünscht ein frohes und glückliches Jahr ".$jahr."</font></td></tr>", true);}elseif ($weihnacht){    output("<tr align=center><td><font size='4'><br/>`^Das Team von Alvion wünscht ein frohes Weihnachtsfest</font></td></tr>", true);}else{    //Newstext    $sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";    $result = db_query($sql) or die(db_error(LINK));    $row = db_fetch_assoc($result);    output("<tr align=\"center\"><td  align=\"center\"><a>`n`^Neues in Alvion:`n</a><marquee scrollamount=\"2\" scrolldelay=\"1\"width= \"480\"\">$row[newstext] </marquee></td></tr>",true);}$result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'"));$onlinecount = $result['onlinecount'];// do not check if playerlimit is not reached!if($_COOKIE['eu-cookie'] != '1' && (!isset($_GET['btnCookieOk']))) {    output ("<tr align='center'><td><form action='index.php?btnCookieOk=1' method='POST'><br /><br /><FONT SIZE='+1'><strong>Diese Seite verwendet Cookies. Wenn du diesen Dienst nutzen möchtest,`n musst du der Verwendung von Cookies zustimmen.</strong></FONT><br /><input type='submit' class='button' value='Zustimmen' name='btnCookieOk'></form></td></tr>",true);    allownav("index.php?btnCookieOk=1");}if ($onlinecount >= getsetting("maxonline",10) && getsetting("maxonline",10)!=0) {    $id=$_COOKIE[lgi];    $sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0";    $result = db_query($sql) or die(db_error(LINK));    if (db_num_rows($result)>0) $is_superuser=1;    else $is_superuser=0;}else $is_superuser = 0;if ($onlinecount<getsetting("maxonline",10) || getsetting("maxonline",10)==0 || $is_superuser){if($_COOKIE['eu-cookie'] == '1' || (isset($_GET['btnCookieOk']))) {    output("<tr  align=\"center\"><td>`n`nGib deinen Namen und dein Passwort ein, um diese Welt zu betreten.`n`n</td></tr>",true);    if (isset($_GET['op']) && $_GET['op']=="timeout"){        $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";        if (!isset($_COOKIE['PHPSESSID'])){            $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";        }    }    if (isset($session['message']) && $session['message']>"") output("`b`\$$session[message]`b`n");        output("<tr  align=\"center\"><td><form action='login.php' method='POST'>"        .templatereplace("login",array("username"=>"<u>N</u>ame","password"=>"<u>P</u>asswort","button"=>"Einloggen"))        ."</form></td></tr>",true);        // Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't        allownav("login.php");    }} else {    output("<tr  align=\"center\"><td>`^`bDer Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`b`nBitte warte, bis wieder ein Platz frei ist.`n`n</td></tr>", true);    if ($_GET['op']=="timeout"){        $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";        if (!isset($_COOKIE['PHPSESSID'])){            $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";        }    }    if (isset($session['message']) && $session['message']>"") output("`b`\$$session[message]`b`n");    output(templatereplace("full"),true);}/*//----------------- Vote-Buttons ----------------------------output("<tr  align=\"center\"><td>`n`n`&Bitte votet für die `2Wä`@ld`2er Al`@vio`2ns`&, damit sie wachsen können`n`n</td></tr>",true);output("<tr border='0' align='center' ><td><a href='http://taladas.de/php/Linklist/Linklist.php3?vote=113710' target='_blank'> <img src='./images/Taladas/TOP100.gif' width='88' height='31' border='0' alt='hier abstimmen'> </a>&nbsp;&nbsp;&nbsp;<a href='http://www.galaxy-news.de/charts/?op=vote&amp;game_id=2091' target='_blank'><img src='./images/vote.gif' style='border:0;' alt='Die besten Browsergames in den Galaxy-News MMOG-Charts!'/></a><br/><br/><br/>",true);output("</td></tr>",true);*/output("<tr  align=\"center\"><td>`n`b`&".getsetting("loginbanner","*BETA* This is a BETA of this website, things are likely to change now and again, as it is under active development *BETA*")."`0`b`n</td></tr>",true);$session['message']="";$newDate = mktime(20,00,00, 07, 01, 2007);// aktuelle Zeit im Unixformat erzeugen$actDate = time();// Differenz berechnen (in Sekunden)$diffDate = ($actDate-$newDate);// Anzahl Tage = Sekunden /24/60/60// floor() liefert nur den Anteil vor dem Komma$days = floor($diffDate / 24 / 60 / 60 );// den verbleibenden Rest berechnen = Stunden$diffDate = $diffDate - ($days*24*60*60);// den Stundenanteil herausrechnen$hours = floor($diffDate / 60 / 60);$diffDate = ($diffDate - ($hours*60*60));// den Minutenanteil$minutes = floor($diffDate/60);$diffDate = $diffDate - ($minutes*60);// die verbleibenden Sekunden$seconds = floor($diffDate);output("<tr  align=\"center\"><td>`b`c`n`2Der Server ist seit `6$days `2Tagen, `6$hours `2Stunden, `6$minutes `2Minuten und `6$seconds `2Sekunden Online!`c`b`n</td></tr>",true);output("<tr  align=\"center\"><td>`c`2Version auf diesem Gameserver: `@{$logd_version}`0`c</td></tr>",true);output("</table>",true);clearnav();addnav("Kontakt");addnav("Impressum","kontakt.php");addnav("Datenschutz");addnav("Datenschutzerklärung","datenschutz.php",false,true);addnav("Neu hier?");addnav("Ü?Über LoGD","about.php");addnav("F.A.Q.","petition.php?op=faq",false,true);addnav("Kämpfer-Charakter erstellen","create.php");addnav("RPG-Charakter erstellen","create.php?op=rpg");addnav("Das Spiel");addnav("Liste der Kämpfer","list.php");addnav("ä?Tägliche News", "news.php");addnav("Spieleinstellungen", "about.php?op=setup");addnav("Passwort vergessen?","create.php?op=forgot");addnav("Die LoGD-Welt");addnav("LoGD Netz","logdnet.php?op=list");addnav("DragonPrime","http://www.dragonprime.net",false,false,true);addnav("Partner");addnav('`ÓEassos','http://www.eassos.de',false,false,true);// addnav("`9F`(a`,r`Df`Ya`Ol`ìl`^a","http://farfalla-logd.de/",false,false,true);addnav("`1M`!o`9n`(d`,s`Yc`&ha`Yt`,t`(e`9n`!t`1al","http://www.mondschattental.de/",false,false,true);addnav("`1N`!a`9d`(e`!y`1a","http://www.nadeya.de/",false,false,true);addnav("`TSo`Bul `Cof `hth`ze bla`hck `CDr`Bag`Ton`0","http://www.soul-of-the-black-dragon.de",false,false,true);// Eigener Link zum Verteilen an andere Dörfer// addnav("`2Al`@vio`2ns `2Wä`@ld`2er","http://www.alvion-logd.de/logd/",false,false,true);addnav("Links");addnav('`ßAvatarbase','http://www.avatarbase.de',false,false,true);addnav('Grafiktutorial','http://alvion-logd.de/tutorials/index.html',false,false,true);page_footer();

