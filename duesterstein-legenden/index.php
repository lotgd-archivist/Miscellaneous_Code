
<?
require_once "common.php";
//echo(strpos($_SERVER['SERVER_NAME'],"logd.mightye.org"));
$old = getsetting("expireoldacct",45);
$new = getsetting("expirenewacct",10);
$trash = getsetting("expiretrashacct",1);
$sql = "DELETE FROM accounts WHERE superuser<=1 AND (1=0\n"
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$old days"))."\")\n":"")
.($new>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$new days"))."\" AND level=1 AND dragonkills=0)\n":"")
.($trash>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-".($trash+1)." days"))."\" AND level=1 AND experience < 10 AND dragonkills=0)\n":"")
.")"; 
//echo "<pre>".HTMLEntities($sql)."</pre>";
db_query($sql) or die(db_error(LINK));

$old-=5;
$sql = "SELECT acctid,emailaddress FROM accounts WHERE 1=0 "
.($old>0?"OR (laston < \"".date("Y-m-d H:i:s",strtotime("-$old days"))."\")\n":"")
." AND emailaddress!='' AND sentnotice=0";
$result = db_query($sql);
for ($i=0;$i<db_num_rows($result);$i++){
    $row = db_fetch_assoc($result);
    mail($row[emailaddress],"LoGD Character Expiration",
    "
    One or more of your characters in Legend of the Green Dragon at
    ".$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME']."
    is about to expire.  If you wish to keep this character, you should
    log on to him or her soon!",
    "From: ".getsetting("gameadminemail","postmaster@localhost.com")
    );
   $sql = "UPDATE accounts SET charisma=0,marriedto=0 WHERE marriedto=$row[acctid]"; 
   db_query($sql); 
   $sql = "DELETE FROM pvp WHERE acctid2=$row[acctid] OR acctid1=$row[acctid]"; 
   db_query($sql) or die(db_error(LINK)); 
   $sql = "UPDATE accounts SET sentnotice=1,house=0,housekey=0 WHERE acctid='$row[acctid]'"; 
   if ($row[acctid]==getsetting("hasegg",0)) savesetting("hasegg",stripslashes(0));
   db_query($sql); 
   $sql = "UPDATE houses SET owner=0,status=3 WHERE owner=$row[acctid] AND status=1"; 
   db_query($sql); 
   $sql = "UPDATE houses SET owner=0,status=4 WHERE owner=$row[acctid] AND status=0"; 
   db_query($sql); 
   $sql = "UPDATE items SET owner=0 WHERE owner=$row[acctid]";
    db_query($sql);
}


//echo "<pre>".HTMLEntities($sql)."</pre>";

if ($session[loggedin]){
    redirect("badnav.php");
}
page_header("Duesterstein Legenden");
//output("`cWillkommen bei Legend of the Green Dragon, eine Nachmache von Seth Able's Legend of the Red Dragon.`n`n");
//output("`@Die aktuelle Zeit im Dorf ist `%".getgametime()."`@ Uhr.`0`n`n");
output("<table align='center' width='500' border='0' cellpadding='0' cellspacing='2'>",true);
output(" <tr>",true);
output("  <td>&nbsp;</td>",true);
output("  <td>&nbsp;</td>",true);
output("  <td>&nbsp;</td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td class='text10' width='230' valign='top'><img src='images/fontz2_a.jpg' alt='' border='0' width='27' height='28' align='left' vspace='5' hspace='5'>ls sich der Nebel des Krieges langsam verblaste, erkannten die Kontrahenten ihre Beitrag am Untergang der einst blühenden Stadt Düsterstein. Der Zorn, der Neid und die Falschheit",true);
output("  hatten ihren Weg gefunden und waren angekommen. Angekommen dort wo sie ihr grausames Spiel fortsetzen sollten. Unter der Last des Konfliktes und der vergifteten Atmosphäre brachen jene",true);
output("  zusammen die Düsterstein zusammenhielten. Das Ziel Düsterstein in die Knie zu zwingen war gelungen. Mehr noch ... es wurde gebrochen. So konnte es nicht weiter florieren und gab sich in die",true);
output("  Hände Ramius. Aus Düsterstein wurde eine Sage, ein Ort ohne Zukunft. Niemand sollte diesen Ort nochmals beschmutzen oder angreifen, so schworen sich die Hütter einst ... und",true);
output("  so wurde das Buch Düstersteins geschlossen</td>",true);
output("  <td>&nbsp;</td>",true);
output("  <td class='text10' width='230' valign='top'><img src='images/fontz2_d.jpg' alt='' border='0' width='27' height='28' align='left' vspace='5' hspace='5'>och eines Abends, so beschreibt es die Legende, erwuchs aus den Trümmern der einst so grossen Stadt, ein neuer Ort. Zwar waren die Träume von einst verloren gegangen, aber aus der",true);
output("  Vergangenheit erwuchsen neue. So erzählten die Narren von einem neuen Land. Auf den Ruinen Düstersteins aufgebaut. Es sollte genauso prachtvoll sein wie einst die kleine Stadt nahe des Kristallsee's.",true);
output("  Belächelt wurden die Erzähler, ja man nannte sie verrückt. Einen solchen Ort konnte es nicht mehr geben. So machtest du dich auf diesen Ort zu finden. Im Gepäck eine handvoll Erzählungen und die",true);
output("  Zuversicht deines Glückes Sohn zu sein. Nun, viele Tage später nach deiner Abreise, stehst du vor dieser Legende, dem Tor zu Düsterstein. Als denn, gib dein Lösungswort dem Wächter und tritt ein ...",true);
output("  wenn du dich traust.</td>",true);
output(" </tr>",true);
output(" <tr>",true);
output("  <td>&nbsp;</td>",true);
output("  <td>&nbsp;</td>",true);
output("  <td>&nbsp;</td>",true);
output(" </tr>",true);
output("</table>",true);
output("`c");
output("<img src='images/images/homepage11.jpg' alt='' border='0' width='501' height='5'>",true);
output("`n`n");
//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
//output("`@Der nächste Spieltag startet in: `$".date("G\\h, i\\m, s\\s \\(\\r\\e\\a\\l\\ \\t\\i\\m\\e\\)",strtotime("1990-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`n`n");

$newplayer=stripslashes(getsetting("newplayer",""));
//if ($newplayer!="") output("`@Unser jüngster Spieler ist `^$newplayer`@!`0`n");
$newdk=stripslashes(getsetting("newdragonkill","")); 
//if ($newdk!="") output("`@Der letzte Drachentöter war: `&$newdk`@!`0`n`n");
//    output("`cGib Dein Passwort ein, um das Spiel zu beginnen.`n");
//hierzwieschen
    if ($_GET['op']=="timeout"){
        $session['message'].=" Deine Sitzung wurde wegen einem Time out beendet, bitte melde Dich erneut an.`n";
        if (!isset($_COOKIE['PHPSESSID'])){
            $session['message'].=" Es kann auch sein, daß Du Cookies von dieser Seite blockierst.  Um diese Seite zu benutzen, müssen Cookies akzeptiert werden.`n";
        }
    }
    if ($_GET['op']=="full"){
        $session['message'].=" Der Server ist im Moment ausgelastet, bitte versuch es später nochmal`n";
    }
    if ($session[message]>"") output("`b`\$$session[message]`b`n");
    output("<form action='login.php' method='POST'>"
    .templatereplace("login",array("username"=>translate("<u>U</u>sername"),"password"=>translate("<u>P</u>assword"),"button"=>translate("Log in")))
    ."</form>`c",true);
    // Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
    addnav("","login.php");
//hierzwieschen
//output("`n`b`&**BETA**`0 This is a BETA of this website, things are likely to change now and again, as it is under active development (when I have time ;-)) `&**BETA**`0`n");
$newDate = mktime(0,0,0, 2, 25, 2004);
// aktuelle Zeit im Unixformat erzeugen
$actDate = time();
// Differenz berechnen (in Sekunden)
$diffDate = ($actDate-$newDate);

// Anzahl Tage = Sekunden /24/60/60
// floor() liefert nur den Anteil vor dem Komma
$days = floor($diffDate / 24 / 60 / 60 );
// den verbleibenden Rest berechnen = Stunden
$diffDate = $diffDate - ($days*24*60*60);
// den Stundenanteil herausrechnen
$hours = floor($diffDate / 60 / 60);
$diffDate = ($diffDate - ($hours*60*60));
// den Minutenanteil
$minutes = floor($diffDate/60);
$diffDate = $diffDate - ($minutes*60);
// die verbleibenden Sekunden
$seconds = floor($diffDate);

// und das ganze dann Anzeigen:
output("`b`cSchon: $days Tage, $hours Stunden, $minutes Minuten und $seconds Sekunden online`c`b");
//output("`4`b`cDieser Server wird heute zeitweise nicht oder sehr eingeschränkt zur Verfügung stehen. 
//        Wenn dieser Hinweis weg ist, dann steht er auch wieder allen zur Verfügung bis dahin 
//        bitte NICHT einloggen, auch wenn es möglich sein sollte.`c`b");
output("`n`b`&".getsetting("loginbanner"," ")."`0`b`n");
$session[message]="";
output("`c`2Game server running version: `@{$logd_version}`0`c");

clearnav();
addnav("Neu bei LoGD?");
addnav("Über LoGD","about.php");
addnav("Einen neuen Charakter erstellen","create.php");
addnav("Anderes");
addnav("Impressum","impressum.php");
addnav("Krieger auflisten","list.php");
addnav("Tägliche News", "news.php");
//addnav("Game Setup Info", "about.php?op=setup");
addnav("LoGD Netz","logdnet.php?op=list");
addnav("Passwort vergessen?","create.php?op=forgot");

page_footer();
?>


