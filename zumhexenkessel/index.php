<?php

// 09092004
if (isset($_POST['template'])){
setcookie("template",$_POST['template'],strtotime(date("r")."+45 days"));
$_COOKIE['template']=$_POST['template'];
}
require_once "common.php";

if ($session[loggedin]){
    redirect("badnav.php");
}
page_header();
output('`c<b><br>Willkommen in `QAr`qwen `tAno`quks `@LotgD `QSeaquinn, `&schamlos abgekupfert von Seth Ables Legend of the Red Dragon.</b>`c',true);
output('`c<b><br>`QSe`qit `tüb`qer `@13 `qJa`thr`qen `Qim `qNe`ttz.</b>`c',true);
output('`c<br>`&Zeiten für neue Spieltage:<br>`q02:00 - 04:00 - 06:00 - 08:00- 10:00 - 12:00 - 14:00 - 16:00 - 18:00 - 20:00 - 22:00 - 00:00`c',true);
output('`c<b><br>`8Es gab Probleme `6mit der `@Registrierung, `6das `8haben `6wir `8wieder `@behoben. `QEine `@Registrierung `Qund die `@Passwort vergessen `&Funktion `6sind wieder `8einsatzbereit!</b><br>`c',true);
output('`c<br>Unser besonderer Dank geht an das Team von <a href="http://anpera.homeip.net" target="_blank">Anpera.net</a> ohne die das betreiben des Spiels technisch nicht möglich gewesen wäre!`c',true);
output('`c<br><img src="images/start_2021.jpg" alt="Willkommen">`c',true);


output("`c`n");
if (getsetting('activategamedate','0')==1) output("`@Wir schreiben den `%".getgamedate()."`@.`0`n");
output("`@Die gegenwärtige Zeit im Dorf ist `%".getgametime()."`@.`0`n");

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
// $tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time)); 
// $tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));
output("`@Nächster neuer Tag in: `3".date("G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`n`n");

$newplayer=stripslashes(getsetting("newplayer",""));
if ($newplayer!="") output("`@Unser jüngster Spieler ist `^$newplayer`@!`0`n");
$newdk=stripslashes(getsetting("newdragonkill","")); 
if ($newdk!="") output("`@Der letzte Drachentöter war: `&$newdk`@!`0`n`n"); 

$result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'"));
$onlinecount = $result['onlinecount'];

// do not check if playerlimit is not reached!
if ($onlinecount >= getsetting("maxonline",10) && getsetting("maxonline",10)!=0) {
$id=$_COOKIE[lgi]; 
$sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0"; 
$result = db_query($sql) or die(db_error(LINK)); 
if (db_num_rows($result)>0) $is_superuser=1; 
else $is_superuser=0; 
}
else $is_superuser = 0;

if ($onlinecount<getsetting("maxonline",10) || getsetting("maxonline",10)==0 || $is_superuser){
output("Gib deinen Namen und dein Passwort ein, um diese Welt zu betreten.`n");
if ($_GET['op']=="timeout"){
    $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
    }
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output("<form action='login.php' method='POST'>"
.templatereplace("login",array("username"=>"<u>N</u>ame","password"=>"<u>P</u>asswort","button"=>"Einloggen"))
."</form>`c",true);
// Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
addnav("","login.php");
} else {
output("`^`bDer Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`b`nBitte warte, bis wieder ein Platz frei ist.`n`n");
if ($_GET['op']=="timeout"){
    $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
    }
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output(templatereplace("full")."`c",true);
}
output('`c<br>`c',true);
//Skin-Wechsel, gesehen bei Version 0.9.8 +, coded von Eliwood

rawoutput("<form action='index.php' method='POST'>");
rawoutput("<table align='center'><tr><td>");
$form = array("template"=>"Wähle einen Skin:");
//$prefs['template'] = $_COOKIE['template'];
//if ($prefs['template'] == "") $prefs['template'] = "eli2.htm";
output("$form[template] <select name='template' size=\"1\">",true);
if ($handle = @opendir("templates")){
$skins = array();
while (false !== ($file = @readdir($handle))){
if (strpos($file,".htm")>0){
array_push($skins,$file);
}
}
if (count($skins)==0){
output("`b`@Argh, dein Admin hat entschieden, daß du keine Skins benutzen darfst. Beschwer dich bei ihm, nicht bei mir.`n");
}else{
output("<b>Skin:</b><br>",true);
while (list($key,$val)=each($skins)){
//if($_COOKIE['template']==$val) $select = "selected='selected'";
output("<option name='template' $select value='$val'".($_COOKIE['template']==""&&$val=="yarbrough.htm" || $_COOKIE['template']==$val?" selected":"").">".substr($val,0,strpos($val,".htm"))."<br>",true);
}
}
}else{
output("`c`b`\$FEHLER!!!`b`c`&Kann den Ordner mit den Skins nicht finden. Bitte benachrichtige den Admin!!");
}
rawoutput("</select>");
//$submit = translate_inline("Choose");
rawoutput("</td><td><input type='submit' class='button' value='Bestätigen'></td>");
rawoutput("</tr></table></form>");

//Ende des Skinwechsler
output('`c<br>`c',true);
//output("`n`b`&**BETA**`0 This is a BETA of this website, things are likely to change now and again, as it is under active development (when I have time ;-)) `&**BETA**`0`n");
//output("`n`b`&".getsetting("loginbanner","*BETA* This is a BETA of this website, things are likely to change now and again, as it is under active development *BETA*")."`0`b`n");
$session[message]="";
output("`c`2Version auf diesem Gameserver: `@{$logd_version}`0`c");

clearnav();
addnav("Neu hier?");
addnav("Über LoGD","about.php");
addnav("F.A.Q.","petition.php?op=faq",false,true);
addnav("Charakter erstellen","create.php");
addnav("Das Spiel");
addnav("Liste der Kämpfer","list.php");
addnav("Tägliche News", "news.php");
addnav("Spieleinstellungen", "about.php?op=setup");
addnav("Passwort vergessen?","create.php?op=forgot");
//addnav("DAS Spielforum","http://logd-forum.zumhexenkessel.de/",false,false,true);
addnav("Die LoGD-Welt");
addnav("Seaquinn LotgD Topliste","https://lotgd.zumhexenkessel.de/tl/");
addnav("LoGD Netz","logdnet.php?op=list");
//addnav("DragonPrime","http://www.dragonprime.net",false,false,true);

output('`c<br><a href="http://lotgdtopliste.zumhexenkessel.de/" onmousedown="return hit("http://lotgdtopliste.zumhexenkessel.de/in.php?id=1")" target="_blank"><img src="https://lotgdtopliste.zumhexenkessel.de/img/tl_banner_468.jpg" alt="Legend of the Green Dragon Topliste" border="0"></a>`c',true);

page_footer();
?> 