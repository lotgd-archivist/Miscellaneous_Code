
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

output("<img src='images/Eassos/Startseite_Eassos.gif' style='width:100%;'>`n`n`n`n`c",true);

if (getsetting('activategamedate','0')==1) output("`ÂµW`Gi`rr `)h`7a`&b`Ã¬e`Ã¹n`9 d`!en`0 `&".getgamedate().".`0`n");
output("#4a4a4aI#515151n #595959Ea#696969s#707070s#787878o#808080s #888888i#909090s#949494t #989898e#9c9c9cs #a1a1a1g#a5a5a5e#aaaaaan#aeaeaea#b2b2b2u`0 `&".getgametime()." #b7b7b7U#bbbbbbh#c0c0c0r.`0`n");

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));

$nextdattime = date("`@ \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"));
output('<div id="index_time">'.$nextdattime.'</div>
<script language="javascript">

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
var edit = "`GNÃ¤`Â¹ch`Â²st`rer`) Ta`7g in: `&";
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
index_set_time('.date('G, i, s',strtotime('1415-04-21 16:52:00 + '.$realsecstotomorrow.' seconds')).');
}
</script>
',true);


$newplayer = stripslashes(getsetting("newplayer",""));

output("" .newplayer($newplayer). " #c0c0c0i#bdbdbds#bbbbbbt #b9b9b9i#b6b6b6n #b4b4b4Ea#b0b0b0s#adadads#abababo#a9a9a9s #a7a7a7a#a4a4a4n#a2a2a2g#a0a0a0e#9e9e9ek#9b9b9bo#999999m#979797m#959595e#949494n#909090. #8c8c8cH#888888e#858585r#818181z#7d7d7dl#7a7a7ai#767676c#727272h #6f6f6fW#6b6b6bi#676767l#636363l#606060k#5c5c5co#585858m#555555m#515151e#4d4d4dn#4a4a4a!`0`n");

//output("`rServerbesitzer:`7 ZÃ¡rysa Anastazija Rimirovic`0`n");



//output("<img src='images/system/x-mas/newj.jpg' align='center'>`n`n`n",true);

output("`n`n<table><tr><td width='300' align='justify'>

Suchender, deine Reise hat ein Ende gefunden, denn Abenteuer, Magie und fremde Kreaturen, sagenumwobene StÃ¤dte und fantastische Welten, erwarten dich. 
Nicht nur der grÃ¼ne Drache treibt hier sein Unwesen, sondern auch die lÃ¤ngst als ausgestorben geltenden Drachen. Der Vater der Drachen, der Herr
der WÃ¼ste, der Qayssar von Al Barra kennt ihr Geheimnis und macht den SÃ¼den von Eassos zu einer heiÃŸen und fast vergessenen Welt.
Ist dir der SÃ¼den zu heiÃŸ, wartet im Osten des Landes die Hauptstadt Astaros. Die mediterrane Stadt liegt direkt am kristallklaren Meer und versprÃ¼ht seinen
ganz besonderen Charme. Die ZÃ¡rysa ist noch Jung, doch eine gerechte und wohlwollende Herrscherin. So verwundert es nicht, dass sich in der reichen Stadt Wesen 
tummeln, die verschiedener nicht sein kÃ¶nnten und dabei ist es egal ob diese von hohem Adel oder gefÃ¼rchtete Piraten sind.
Interessierst du dich fÃ¼r Eis und Schnee, so ist Carvo die Stadt deiner Wahl. Dort im hohen Norden herrscht das ewige Eis und die bittere KÃ¤lte. Nicht jedes Wesen
ist in der Lage, dort zu Ã¼berleben. Einst eine Ruine, ist Carvo heute einer der schÃ¶nsten StÃ¤dte in Eassos.
Aber nicht alles ist Gold, was glÃ¤nzt. Versteckt im Westen erwartet die erfahrenen Reisenden eine Stadt der etwas anderen Art. Lust und VergnÃ¼gen wird hier sehr groÃŸ 
geschrieben und nichts dort hat mit Sitte und Anstand zu tun. Wer also zu hohe Moralvorstellungen pflegt, sollte sich von der Stadt Baalos lieber fernhalten. 

</td><td width='50'></td><td width='300' align='justify'>
Wo Licht ist, ist auch Schatten. So ergeht es auch Eassos. Unterhalb von Eassos, in einem langen und riesigen Netzwerk aus Tunneln und HÃ¶hlen, liegt verborgen
das dunkle Reich. Dark Eassos ist der verbotene Teil der Geschichte um das glorreiche Eassos und der Beweis, dass man das BÃ¶se nicht ausrotten kann. Man kann es
nicht immer sehen und nicht viele wissen, dass es Ã¼berhaupt existiert. Die Herrscher in Eassos vermeiden die ErwÃ¤hnung des Untergrundlandes, um die Einwohner 
nicht zu besorgen, doch immer wieder schaffen es die dunklen Wesen an die OberflÃ¤che und die perfekte Fassade bekommt Risse. Unterhalb von Astaros liegt auch die
Hauptstadt von Dark Eassos. Kryphton ist riesig und auf makabere Art wunderschÃ¶n, doch sollte man sich nicht von der dunklen SchÃ¶nheit tÃ¤uschen lassen. Denn hier 
gelten andere Rechte und Gesetze und kein gewÃ¶hnlicher Sterblicher, sollte sich auch nur in die NÃ¤he der Stadt trauen, fÃ¼r alles andere wird keinerlei Haftung 
Ã¼bernommen. Weiter im Westen findet man eine eher ungewÃ¶hnliche Stadt. Illyorzzad wird nur von Drows und deren AnhÃ¤nger bewohnt. Die Stadt ist kleiner als Kryphton,
aber durchaus imposant im Erscheinungsbild. 
Wer immer noch nicht genug hat, kann dann im Norden, unterhalb von Carvo die imposanten Bauten der Zwerge bestaunen. Die Zwerge pflegen ihre eigene Kultur und halten
sich zumeist aus den Angelegenheiten der Anderen und Oberweltler heraus, denn ihr Interesse ist gÃ¤nzlich anderer Natur.

</td></tr></table>`0`n",true);

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
output("<font family='Century Gothic,times new roman'><font size='3'>#4a4a4aW#4d4d4de#505050i#535353s#565656e #595959d#5c5c5ci#606060c#636363h #666666a#696969u#6c6c6cs #6f6f6fu#727272n#767676d #797979b#7c7c7ce#7f7f7ft#828282r#858585e#888888t#8c8c8ce #8f8f8fd#929292i#949494e #959595s#979797a#999999g#9b9b9be#9d9d9dn#9f9f9fh#a0a0a0a#a2a2a2f#a4a4a4t#a6a6a6e #a8a8a8W#aaaaaae#abababl#adadadt #afafafv#b1b1b1o#b3b3b3n #b5b5b5Ea#b8b8b8s#bababas#bcbcbco#bebebes#c0c0c0!`0</font></font>`n`n",true);

if ($_GET['op']=="timeout"){
    $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies mÃ¼ssen fÃ¼r diese Seite zugelassen werden.`n";
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
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies mÃ¼ssen fÃ¼r diese Seite zugelassen werden.`n";
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
   background-color: #c0c0c0;
   font-family: Times;
   font-size: 20px;
   color: #000000;
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

<table width=550 border=0 cellpadding=5 cellspacing=0>
 <tr>
 <td colspan='2'>
   <div class='head'>Informationen</div>
 </td>
 </tr>

 <tr>

 <td width='250'>
  <div class='name'>Schnupperzugang</div><P>
<div valign='top' class='work'><blockquote>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ Name:<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tester<BR><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ Passwort<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 123456</blockquote>
</div>
  <div class='name'>Facebook&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><P>
<div class='work'><blockquote>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ <a href=https://www.facebook.com/groups/380958102106088/>&raquo;FB Gruppe Eassos&laquo;</a><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ <a href=http://www.facebook.com/eassos>&raquo;FB Seite Eassos&laquo;</a>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ <a href=https://discord.gg/6BUE5yb>&raquo;Discord Server Eassos&laquo;</a><BR>
</blockquote>
</td> 
<td width='300'><div class='name'>Kontakte bei Fragen und...</div><P>

<div valign='top' class='work'><blockquote>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ ...Verifizierungsmail<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;admina@eassos.de<BR><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ ...Partnerserver<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;admina@eassos.de<BR><BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;â€¢ ...Hilfestellung<BR>
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;admina@eassos.de<BR><BR>
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
$form = array("template"=>"WÃ¤hle einen Skin:`0");
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
output("</td><td><input type='submit' class='button' value='BestÃ¤tigen'></td>",true);
output("</tr></table></form>",true);
//Ende des Skinwechsler

output("`n".servertime());

output("</td></tr></table>",true);



output("`n`n");
/*
output("<table align='left' width='100%' cellpadding='1' cellspacing='1' border='0'><tr><td>",true);

    //Vote-Ecke
    output("<table border='3' cellpadding='1' cellspacing='1' width='300' align='left' bgcolor='#FFFFFF'><tr><td>",true);
        output("<table border='0' cellpadding='1' cellspacing='1' width='300' bgcolor='#000000'>",true);
            output("<tr align='center'>",true);
                output("<td colspan='3'>`$`bVotet bitte alle einmal am Tag fÃ¼r uns!!!`b`0</td>",true);
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
                output("<td colspan='3'>`$`bDanke sagt das Team von Eassos!`b`0</td>",true);
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
//addnav("Charakter erstellen","create.php?op=rules");
addnav("Passwort vergessen?","create.php?op=forgot");
addnav("Die Bewohnerliste","list.php");
addnav("Impressum","impressum.php");
addnav("DSGVO","dsgvo.php");

//addnav("Infos Ã¼ber Eassos","server_des.php");

addnav("Die Welt des LoGD");
addnav("Ãœber LoGD","about.php");
addnav("LoGD - Netz","logdnet.php?op=list");
addnav("Namensgenerator","http://namensgenerator.game-dragon.de/MenschenGenerator.shtml",false,false,true);
addnav("Fantasy Namensgenerator","http://www.vornamen-wiki.de",false,false,true);
addnav("Zwergen Namensgenerator","http://www.vornamen-wiki.de/zwergen-namen-generator.html",false,false,true);
addnav("Lari Fari Namensgenerator","http://www.larisweb.de/tools/namengenerator.php",false,false,true);
addnav("Avatarschmiede","http://www.avatarschmiede.de",false,false,true);
addnav("Avatarbase","http://www.avatarbase.de",false,false,true);
addnav("DeviantArt","http://www.deviantart.com",false,false,true);

addnav("Schwesterserver");
addnav("#1e1919N#3b2a22e#583c2bw#764d34-#935f3dO#a26842r#875839l#6d4831e#523829a#382821n#1e1919s","http://new-orleans.crare.de/",false,false,true);


addnav("Partnerserver");
addnav("#30271CS#423526o#52422Fu#6B563Fl #917951o#B19961f #D1B770t#F8DB83h#FAE6ABe #FDF3D5B#FDF3D5l#FAE6ABa#F8DB83c#D1B770k #B19961D#917951r#6B563Fa#52422Fg#524226o#30271Cn","http://www.sotbd.de",false,false,true);
addnav("`2Al`@vio`2ns `2WÃ¤`@ld`2er","http://www.alvion-logd.de/logd/",false,false,true);
addnav("#b2823dE#d6b74dr#fbec5da#c9cb3en#97ab1fy#97ab1fa","http://eranya.de/",false,false,true);
addnav("Gleis 9 3/4","http://www.gleisneundreiviertel.de/",false,false,true);
addnav("Aphroditus","http://www.aphroditus.de/",false,false,true);


page_footer();

?>


