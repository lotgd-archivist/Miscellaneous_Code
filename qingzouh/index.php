
<?php

// 09092004

require_once "common.php";

$date_check = date("d.m");

$saint_omar_season = array(                12    => "winter",
                            1    => "winter",
                            2    => "winter",
                            3     => "spring",
                            4    => "spring",
                            5    => "spring",
                            6    => "summer",
                            7    => "summer",
                            8    => "summer",
                            9    => "fall",
                            10    => "fall",
                            11    => "fall"
                           );

if($date_check == "31.10"){
    output("<script type=\"text/javascript\" src=\"templates/common/halloween.js\"></script>",true);
}


if ($settings['xmas'] > '0') {
    if ($_COOKIE['snow'] == "") {
        setcookie("snow",1);
        redirect("index.php");
    } else if($_GET['snow'] == "on") {
        setcookie("snow",1);
        redirect("index.php");
    } else if($_GET['snow'] == "off") {
        setcookie("snow",0);
        redirect("index.php");
    }

    if ($_COOKIE['snow'] == 1) {
        output("<script type=\"text/javascript\" src=\"templates/common/snow.js\"></script>",true);
        output("<a href='index.php?snow=off'>`&Schnee aus`0</a>",true);
    } else if ($_COOKIE['snow'] == 0) {
        output("<a href='index.php?snow=on'>`&Schnee an`0</a>",true);
    }
}

if($date_check >= "31.12"){
        output("<SCRIPT LANGUAGE='JavaScript' SRC='templates/common/firework/animate2.js' AUTHOR='www.Roy.Whittle.com'></SCRIPT>",true);
        output("<SCRIPT LANGUAGE='JavaScript' SRC='templates/common/firework/fireworks2.js' AUTHOR='www.Roy.Whittle.com'></SCRIPT>",true);    
}


if ($session['loggedin']){
    redirect("badnav.php");
}

page_header($server_name);

output("<table align='center' cellpadding='1' cellspacing='1' border='0'><tr><td>",true);

output("<br><br><br><br><center><img src='images/quin/index.png' style='width:500px;'></center>`n`n`n`n`c",true);

if (getsetting('activategamedate','0')==1) output("`µW`Gi`rr `)h`7a`&b`ìe`ùn`9 d`!en`0 `&".getgamedate().".`0`n");
output("In New Orleans ist es genau`0 `&".getgametime()." Uhr.`0`n");

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));

$nextdattime = date("`@ \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"));
output('<div id="index_time">'.$nextdattime.'</div>
<script language="javascript">
/*
Kleines Schmankerl by Alucard
http://www.atrahor.de
*/
var index_time_div = document.getElementById("index_time");
var index_time_day = Math.ceil(24/'.(int)getsetting("daysperday",4).');
var index_dest_time = 0;
function index_act_time()
{
var jetzt = new Date();
var tm = jetzt.getTime();
if( tm > index_dest_time ){
index_dest_time += index_time_day*3600000+ (tm-index_dest_time);
}
var diff = index_dest_time - tm;
var edit = "Nächster Tag in: `&";
var s = Math.floor(diff / 3600000);
diff %= 3600000;
var m = Math.floor(diff / 60000);
diff %= 60000;
var sek = Math.floor(diff / 1000);
index_time_div.innerHTML = edit+s+" `0`&Stunde"+(s!=1 ? "n":"")+",`& "+(m<10 ? "0"+m : (m==71 || m==72 ? "<font color=\"#FFFFFF\"><b>"+m+"</b></font>" : m))+" `0`&Minute"+(m!=1 ? "n" : "")+",`& "+(sek<10 ? "0"+sek : sek)+" `0`&Sekunde"+(sek!=1 ? "n" : "")+".`0";
window.setTimeout("index_act_time()", 1000);
}
function index_set_time(s,m,sek)
{
if( !index_dest_time ){
var jetzt = new Date();
index_dest_time = jetzt.getTime() + 1000*sek + 60000*m + 3600000*s;
}
window.setTimeout("index_act_time()", 1);
}
if( index_time_div ){
index_set_time('.date('G, i, s',strtotime('2015-03-17 16:52:00 + '.$realsecstotomorrow.' seconds')).');
}
</script>
',true);


$newplayer = stripslashes(getsetting("newplayer",""));

output("" .newplayer($newplayer). " ist in Qingzoug angekommen. Herzlich Willkommen!`0`n");

//output("`rServerbesitzer:`7 Ich`0`n");



//output("<img src='images/system/x-mas/newj.jpg' align='center'>`n`n`n",true);

output("`n`n<table><tr><td width='300' align='justify'>
xxx</td>


<td width='50'></td><td width='300' align='justify'>
xxxx
</td>
</tr>
</table>

`n<table><tr><td width='600' align='justify'>
blabla
</td>
</tr>
</table>
`0`n",true);

$result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",3600)." seconds"))."'"));
$onlinecount = $result['onlinecount'];

// do not check if playerlimit is not reached!
if ($onlinecount >= getsetting("maxonline",10) && getsetting("maxonline",10)!=0) {
$id=$_COOKIE[lgi];
$sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0";
$result = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result)>0) $is_superuser=1;
else $is_superuser=0;
}
else $is_superuser = 0;

if ($onlinecount<getsetting("maxonline",10) || getsetting("maxonline",10)==0 || $is_superuser){
output("<font family='Century Gothic,times new roman'><font size='3'>Weise dich aus und betrete die sagenhafte Stadt New Orleans!`0</font></font>`n`n",true);

if ($_GET['op']=="timeout"){
    $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
    }
}

if ($session[message]>"") output("`b`\$$session[message]`b`n");
if ($_COOKIE['template'] != "mediele.html" && $_COOKIE['template'] != "unknown.html" && $_COOKIE['template'] != "newsaint.html") {
    output("<form action='login.php' method='POST'>"
    .templatereplace("login",array("button"=>"Einloggen"))
    ."</form>`c",true);
} else {
    output("<form action='login.php' method='POST'>"
    .templatereplace("login",array("button"=>"Einloggen"))
    ."</form>`c",true);
}
// Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
addnav("","login.php");
} else {
output("`^`bDer Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`b`nBitte warte, bis wieder ein Platz frei ist.`n");

if ($_GET['op']=="timeout"){
    $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
    }
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output(templatereplace("full")."`c",true);
}

output("`n`b`&".getsetting("loginbanner","*BETA* This is a BETA of this website, things are likely to change now and again, as it is under active development *BETA*")."`0`b`n");
$session[message]="";


output("`c
<style>
.head {
   background-color: #1e1919;
   font-family: Times;
   font-size: 20px;
   color: #a26842;
   text-align: center;
   letter-spacing: 0.1em;
}

.name {
   font-size:12px;
   font-weight:bold;
   font-style: italic;
   letter-spacing: 0.1em;
   text-align: center;
      line-height:150%;
}

.work {
   font-family: arial;
   font-size:10px;
   letter-spacing: 0.1em;
      line-height:160%;
}
</style>

<table width=600 border=0 cellpadding=5 cellspacing=0>
 <tr>
 <td colspan='2'>
   <div class='head'>Informationen</div>
 </td>
 </tr>

 <tr>

 <td width='300'>
  <div class='name'>Schnupperzugang</div><P>
<div valign='top' class='work'><blockquote>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Name:<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tester<BR><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• Passwort<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 123456</blockquote>
</div>
  <div class='name'>Facebook&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><P>
<div class='work'><blockquote>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• <a href=https://www.facebook.com/groups/1691294924443977>&raquo;FB Gruppe New Orleans&laquo;</a><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• <a href=https://www.facebook.com/neworleanslogd/>&raquo;FB Seite New Orleans&laquo;</a></blockquote>
</td>
<td width='300'><div class='name'>Kontakte bei Fragen und...</div><P>

<div valign='top' class='work'><blockquote>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• ...Verimail & Allgemeines<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;admina@new-orleans-logd.de<BR><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• ...Partnerserver<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;xxx@new-orleans-logd.de<BR><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;• ...Hilfestellung<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;astaroth@new-orleans-logd.de<BR><BR>
</blockquote>
</div></td></tr>

</table>
`c
",true);



//Skin-Wechsel, gesehen bei Version 0.9.8 +, coded von Eliwood
if (isset($_POST['template'])){
    setcookie("template",$_POST['template'],strtotime(date("c")."+45 days"));
    $_COOKIE['template'] = $_POST['template'];
    redirect("index.php");
}

output("`n<form action='index.php' method='POST' onchange='this.submit();'>",true);
output("<table align='center' ><tr><td>",true);
/*$form = array("template"=>"`05W`06ä`07h`08l`09e`10 e`11i`12n`13e`14n Skin:`00");
output("$form[template] <select name='template' size=\"1\">",true);

    $sql = "SELECT templatename, tsrc FROM templates WHERE freefor='0' ORDER BY templatename";
    $result = db_query($sql) or die (db_error(LINK));
    if (db_num_rows($result)) {
        $templ = $_COOKIE['template'];
        while ($row = db_fetch_assoc($result)){
            if($row['tsrc'] == $templ){
                output("<option name='template' selected value=".$row['tsrc'].">".$row['templatename']."</option>",true);
            }elseif($templ=="" && $row['tsrc']=="newsaint.htm"){
                output("<option name='template' selected value=".$row['tsrc'].">".$row['templatename']."</option>",true);
            }else{
                output("<option name='template' value=".$row['tsrc'].">".$row['templatename']."</option>",true);
            }
        }
    }else{
        rawoutput('<strong style="color: #FF0000;">Es sind keine Templates in der Tabelle vorhanden!</strong><br /><br />');
    }


output("</select>",true);
output("</td><td><input type='submit' class='button' value='Bestätigen'></td>",true);*/
output("</tr></table></form>",true);
//Ende des Skinwechsler

//output("`n".servertime());

output("</td></tr></table>",true);



output("`n`n");
/*
output("<table align='left' width='100%' cellpadding='1' cellspacing='1' border='0'><tr><td>",true);

    //Vote-Ecke
    output("<table border='3' cellpadding='1' cellspacing='1' width='300' align='left' bgcolor='#FFFFFF'><tr><td>",true);
        output("<table border='0' cellpadding='1' cellspacing='1' width='300' bgcolor='#000000'>",true);
            output("<tr align='center'>",true);
                output("<td colspan='3'>`$`bVotet bitte alle einmal am Tag für uns!!!`b`0</td>",true);
            output("</tr>",true);
            output("<tr align='center'>",true);
                output("<td><script language='Javascript' type='text/javascript' src='http://game-toplist.de/image3.php?id=MorganleFay'></script></td>",true);
                //output("<td><a href='http://www.mythana.de/voting/vote.php?refid=87' target='_blank'><img src='http://www.mythana.de/voting/logo.jpg' border='0'></a></td>",true);
                output("<td>",true);
                    output("<a href='http://taladas.de/php/Linklist/Linklist.php3?vote=113932' target='_blank'><img src='http://taladas.de/Taladas/Img/icons/TOP100.gif' width='88' height='31' border='0' alt='hier abstimmen'></a>`n",true);
                    output("<a href='http://www6.topsites24.de/ts/ts.cgi?klick=26&tl=ravendragon' target='_blank'><img src='http://ravendragon.de/logo3.gif' alt='Hier gehts zur Topliste' border='0'></a>`n",true);
                    output('<span style="width:88px; height:31px; display:inline-block; overflow:hidden; background-image:url(http://www.kostenlose-browsergames.de/images/bgbutton.gif); background-repeat:no-repeat; text-align:left;"><a href="http://www.kostenlose-browsergames.de" target="_blank" style="width:87px; height:27px; display:inline-block; margin: 4px 0 0 1px; font-family:Arial,sans-serif; font-size:11px; font-weight:bold; line-height:12px; letter-spacing:0px; color:#ffffff; text-decoration:none;">kostenlose browsergames</a></span>',true);
                    output('<div style="background-image:url(http://www.top-logd.com/images/buttonbg.gif);width:88px;height:31px;display:block;"><a href="http://www.top-logd.com" target="_blank" style="font:bold italic 13px/12px sans-serif;text-decoration:none;color:#00B449;padding:2px 2px 2px 34px;width:52px;height:27px;display:block;">Top LogD</a></div>',true);
                output("</td>",true);
            output("</tr>",true);
            output("<tr align='center'>",true);
                output("<td colspan='3'>`$`bDanke sagt das Team von Assos!`b`0</td>",true);
            output("</tr>",true);
        output("</table>",true);
    output("</td></tr></table>",true);
    //Ende Vote-Ecke

output("</td>",true);
output("<td valign='bottom'>",true);

    // OpenSource-Ecke
    output("<table border='3' cellpadding='1' cellspacing='1' align='right' width='300' bgcolor='#FFFFFF'><tr><td>",true);
        output("<table border='0' cellpadding='1' cellspacing='1' width='300' bgcolor='#FFFFFF'>",true);
            output("<tr align='center'>",true);
                output("<td><h3>`$`b`cWessos is runnig on <a href='http://www.youtube.com/watch?v=zLkCWT2neuI&feature=related' target='blank'>Mary-Lou</a>`c`b`0</h3></td>",true);
            output("</tr>",true);
            output("<tr align='center'>",true);
                output("<td align='center'>",true);
                    output("<img src='images/system/common/debian.gif' alt='Debian'>`n <img src='images/system/common/apache.gif' alt='Apache Webserver'>`n <img src='images/system/common/php.gif' alt='PHP'>$nbsp $nbsp $nbsp $nbsp <img src='images/system/common/mysql.png' alt='MySQL'>",true);
                output("</td>",true);
            output("</tr>",true);
        output("</table>",true);
    output("</td></tr></table>",true);
    //Ende OpenSource-Ecke

output("</td></tr></table>",true);
*/

clearnav();

addnav("Zum Server");
addnav("Charakter erstellen","create.php?op=rules");
addnav("Passwort vergessen?","create.php?op=forgot");
addnav("Die Bewohnerliste","list.php");
addnav("Impressum","impressum.php");
//addnav("Infos über Eassos","server_des.php");

addnav("Die Welt des LoGD");
addnav("Über LoGD","about.php");
addnav("LoGD - Netz","logdnet.php?op=list");
addnav("Namensgenerator","http://namensgenerator.game-dragon.de/MenschenGenerator.shtml",false,false,true);
addnav("Fantasy Namensgenerator","http://www.vornamen-wiki.de",false,false,true);
addnav("Zwergen Namensgenerator","http://www.vornamen-wiki.de/zwergen-namen-generator.html",false,false,true);
addnav("Lari Fari Namensgenerator","http://www.larisweb.de/tools/namengenerator.php",false,false,true);
addnav("Avatarschmiede","http://www.avatarschmiede.de",false,false,true);
addnav("DeviantArt","http://www.deviantart.com",false,false,true);

addnav("Schwesterserver");
addnav("#211810(D#48423ca#706c68r#989694k) #c0c0c0E#c0c0c0a#c0c0c0s#a0a691s#909979o#818d62s","http://www.eassos.de/",false,false,true);
addnav("#202020H#575757a#8f8f8fu#c7c7c7p#fffffft#ffffffs#c6c6d6t#8e8eaea#555586d#1d1d5et #1d1d5eS#555586a#8e8eaei#c6c6d6n#fffffft#ffffff-#c7c7c7O#8f8f8fm#575757a#202020r","http://saint-omar.de/",false,false,true);

addnav("Partnerserver");
addnav("Bei Interesse einfach melden!","",false,false,true);
/*
addnav("#2b2b24B#3b3b33r#4b4b42o#5c5b52o#5c5b52k#694842l#773532y#852222n","http://www.brooklyn.rpg-server.de/index.php",false,false,true);
addnav("#30271CS#423526o#52422Fu#6B563Fl #917951o#B19961f #D1B770t#F8DB83h#FAE6ABe #FDF3D5B#FDF3D5l#FAE6ABa#F8DB83c#D1B770k #B19961D#917951r#6B563Fa#52422Fg#524226o#30271Cn","http://www.sotbd.de",false,false,true);
addnav("`2Al`@vio`2ns `2Wä`@ld`2er","http://www.alvion-logd.de/logd/",false,false,true);
addnav("Silent City","http://silent-city.rpg-server.de/",false,false,true);
addnav("Atlantis","http://www.atlantis-logd.de/",false,false,true);
addnav("Gleis 9 3/4","http://www.gleisneundreiviertel.de/",false,false,true);
addnav("Phoenix","http://www.the-legend-of-phoenix.com/",false,false,true);
addnav("Isla Maleppa","http://maleppa.rp-nexus.de/index.php",false,false,true);
addnav("Dreythal","http://www.dreythal.de/",false,false,true);
*/

page_footer();

?>


