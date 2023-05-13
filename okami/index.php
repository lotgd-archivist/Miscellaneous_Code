
<?php
/**
 * index.php: Startseite für Atrahor
 * @author LOGD-Core / Drachenserver-Team (Überarbeitung, Performance, Liverestzeit)
 * @version DS-E V/2
*/

require_once 'common.php';

if ($session['loggedin'])
{
    redirect('badnav.php');
}

//Falls mehrere URLs auf den gleichen Server zeigen aber nur eine Domain verwendet werden soll leitet
//dieses Codefragment den User auf die richtige Domain um und setzt den Cookie neu, so dass er nicht in
//einer Aktualisierungssperre landet
/*
$arr_server = parse_url(getsetting('server_address','localhost'));
$str_referer = ($_SERVER['HTTPS']?'https://':'http://').$_SERVER['HTTP_HOST'];
if(!LOCAL_TESTSERVER && !preg_match('/'.preg_quote($arr_server['host']).'/i',$str_referer))
{
    setcookie('lasthit',0,strtotime(date('r').'+365 days'));
    redirect(getsetting('server_address','localhost'));
}
*/

page_header(WolfsRealm);

// Ausgabestring
$str_out = '';

// In Var speichern, wird mehrfach verwendet
$str_townname = getsetting('townname','Atrahor');
$int_maxonline = getsetting('maxonline',10);

output("<font size=3>`c`b`A<Font face='Cracked Johnnie'>`n...Wilkommen Reisender!...</font></Font>`n`n`b");
output("`AKommt und taucht ein in das Reich <font size=2>`b`TW`Qo`ql`tf`&'s R`te`qa`Ql`Tm`b`A.`n</font>
`Adas Reich der Wölfe und jener die ein zu Hause suchen.`n
Besuche die Stadt und werde eins mit unserer Welt,`n
teile dein Abenteuer mit anderen und finde deinen`n
Platz, in unserer Mitte.`n`n

Doch Vorsicht vor den beiden Mächten die hier kämpfen.`n
Sie liefern sich einen ewigen und erbitterten Kampf.`n
Der endlos sich wiederholt, wem stehst du zur Seite.`n
`eW`Qol`qf`Asg`lö`8tt`li`An S`qh`Qir`ea `AGott dieses Reiches,`n
stark und mutig hält sie ihre Pfote über uns.`n
`TW`qo`tlf`&sg`7o`Ltt `TS`qh`ta`&r`7e`Lm `Aaus dem Reich der Götter,`n
er will nur sehen wie seine Tochter scheitert.`n`n`n");

if (getsetting('activategamedate','0')==1)
{
$realdatum = time();
$datum = date('d-m',$realdatum);
$wann = 1971; //Das Datum vor wie vielen Jahren? 
$start = mktime(0,0,0,1,1,2006); //Irgendwo solls ja anfange, ne? 
$jetzt = time(); //Momentane Zeit 
$diff = getdate($jetzt-$start);//Die Differenz  
$y = $diff['year'] - $wann;//und das Jahr 
        output('`AWir schreiben den `l'.$datum.'-'.$y.'`A.`0`n');
}

$date = date("G:i");
output('`ADer Kirchturm in Wolfs Realm schlägt `l'.$date.'`A.`0`n');

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
// $tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
// $tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));

$calctime = strtotime('1980-01-01 00:00:00 + '.$realsecstotomorrow.' seconds');

$nextdattime = date('G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)',$calctime);
$str_out .='`INächster neuer Tagesabschnitt in: `0<div id="index_time">`y'.$nextdattime.'`0</div>`n`n';
$str_out .='
<script language="javascript" type="text/javascript">
/*Kleines Schmankerl by Alucard
    www.atrahor.de
*/
var index_time_div = document.getElementById("index_time");
var index_time_day = Math.ceil(24/'.(int)getsetting("daysperday",4).');
var index_dest_time = 0;
</script>
<script language="javascript" type="text/javascript" src="'.TEMPLATE_PATH.'stuff.js"></script>
<script language="javascript" type="text/javascript">
if( index_time_div ){
    index_set_time('.date('G, i, s',$calctime).');
}
</script>
';

$newplayer=stripslashes(getsetting('newplayer',''));
if ($newplayer!='')
{
        output('`AAls letztes schloss sich `f'.$newplayer.'`A uns an!`0`n`n');
}

$newdk=stripslashes(getsetting('newdragonkill',''));
if ($newdk!='')
{
output('`ADas letzte mal hat `&'.$newdk.' `Aerfolgreiche `TW`qo`tlf`&sg`7o`Ltt `TS`qh`ta`&r`7e`Lm `Aaufgehalten!`0`n');
}

$guild=stripslashes(getsetting('dgtopguild',''));
if ($guild!='')
{
output('`ADas angesehenste Rudel '.getsetting('townname','Atrahor').'s heißt zur Zeit: `&'.$guild.'`A!`0`n`n');
}

$dkcounter = number_format( (int)getsetting('dkcounterges',0) , 0 , ' ', ' ' );
if ($dkcounter>0)
{
output('`AInsgesamt haben unsere Helden `TW`qo`tlf`&sg`7o`Ltt `TS`qh`ta`&r`7e`Lm `Abereits `&'.$dkcounter.'`A mal aufgehalten!`0`n`n');
}

$fuerst=stripslashes(getsetting('fuerst',''));
if ($fuerst!='')
{
    $str_out .='`IDen Fürstentitel '.$str_townname.'s trägt zur Zeit: `0`b`y'.$fuerst.'`0`b`I!`0`n`n';
}

if(getsetting('wartung',0) > 0) {
    $str_out .='`b`^Der Server befindet sich im Moment im Wartungsmodus, um Änderungen am Spiel oder dem Server störungsfrei vornehmen zu können.`0`b`^`nBitte warte, bis sich dies ändert.`n`n`0';
}

$result = db_fetch_assoc(db_query("SELECT COUNT(*) AS onlinecount FROM accounts WHERE locked=0 AND ".user_get_online() ));
$onlinecount = $result['onlinecount'];

// do not check if playerlimit is not reached!
if (( $onlinecount >= $int_maxonline && $int_maxonline!=0) || getsetting('wartung',0) > 0 )
{
    $id=$_COOKIE['lgi'];
    $sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0";
    $result = db_query($sql);
    if (db_num_rows($result)>0)
    {
        $row = db_fetch_assoc($result);
        $is_superuser=$row['superuser'];
    }
    else
    {
        $is_superuser=0;
    }
}
else
{
    $is_superuser = 0;
}

if ( ($onlinecount<$int_maxonline || $int_maxonline==0 || $is_superuser) )
{
    output('`lGib deinen Namen und dein Passwort ein, um '.getsetting('townname','Atrahor').' zu betreten.`n');
    if ($_GET['op']=='timeout' )
    {
        $session['message'].='`nDeine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n';
        if (!isset($_COOKIE['PHPSESSID']))
        {
            $session['message'].=' Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n';
        }
    }
    if ($session['message']!='')
    {
        output('`b`$'.$session['message'].'`b`n');
    }
    $encoded_password_transfer_script = 'onSubmit="document.forms.loginform.hidden_pw.value = calcMD5(document.forms.loginform.password.value);document.forms.loginform.password.value=\'\';"';
    $str_out .= "
    <noscript><b style=\"color: #FF0000;\">Javascript wird zur korrekten Funktionsweise benötigt. Bitte aktiviere es! Tipps und Hinweise hierzu findest du in der <a href='petition.php?op=faq' target='_blank'>FAQ</a>.</b></noscript>
    <div id=\"NOT_COMP\" style=\"display: none;\"><br /><br /><br /><b style=\"color: #FF0000;\">Dein Browser erfüllt leider nicht die Anforderungen des Spiels. Weitere Informationen hierzu findest du in der <a href='petition.php?op=faq' target='_blank'>FAQ</a>.</b></div>
    <div id=\"JSLIB_SAFARI\" style=\"display: none;\">`n`n`#Lieber Safari-Benutzer,`nda wir keine Möglichkeit haben, die Funktionalität im Safari-Browser zu gewährleisten, ist der Login leider nicht für dich verfügbar.`nMozilla Firefox wird hingegen unterstützt: `0<a href='http://www.firefox-browser.de/mac.php' target='_blank'>Firefox für MAC</a></div>
    <div id=\"LOGIN_FORM\" style=\"display: none;\"><form action='login.php' name='loginform' method='POST' $encoded_password_transfer_script>
    <input type='hidden' name='hidden_pw' />"
    .templatereplace("login",array("username"=>"<u>N</u>ame","password"=>"<u>P</u>asswort","button"=>"Einloggen"))
    .'</form></div>`c
    <script language="JavaScript" type="text/javascript" src="templates/md5.js"></script>
    <script type="text/javascript" language="JavaScript">

                var is_c = LOTGD.compCheck();
                document.getElementById("NOT_COMP").style.display = (is_c ? "none" : "block");
                if( Browser.isSafari ){
                    document.getElementById("JSLIB_SAFARI").style.display = "block";
                }
                document.getElementById("LOGIN_FORM").style.display = "block";//(is_c ? "block" : "none");
                document.forms.loginform.name.focus();
            </script>';
    // Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
    addnav('','login.php');
}
else
{
    $str_out .='`b`^Der Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`0`b`^`nBitte warte, bis wieder ein Platz frei ist.`n`n`0';
    if ($_GET['op']=='timeout')
    {
        $session['message'].='`nDeine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n';
        if (!isset($_COOKIE['PHPSESSID']))
        {
            $session['message'].=' Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n';
        }
    }
    if ($session['message']!='')
    {
        $str_out .='`b`$'.$session['message'].'`b`n';
    }
    $str_out .=templatereplace('full').'`c';
}


$str_out .='`n`c`b`&'.getsetting('loginbanner','').'`0`b`c`n';
$session['message']='';
$str_out .='`c`t'.$str_townname.' läuft unter: `y'.GAME_VERSION.'`0`c';

$session[message]="";
$str_out.="`n`c`ANeustes aus dem Reich:`n";
 $sql = "SELECT * FROM news ORDER BY newsid DESC LIMIT 1";
 $result = db_query($sql) or die(db_error(LINK));
 $str_out.="<marquee align='middle' scrollamount='2' scrolldelay='2' width='550'style='border-color:#6b563f;border-style:inset;border-width:1px;padding-top:3px;padding-bottom:3px;'>";
 $str_out.=mysql_result($result, 0, "newstext");
 $str_out.="</marquee>";
 $str_out.="`0`n";

//serverzeit 
$jahr = 2022; 
$monat = 9; 
$tag = 19; 
$newDate = mktime(0,0,0, $monat,$tag,$jahr); 
// aktuelle Zeit im Unixformat erzeugen 
$actDate = time(); 
// Differenz berechnen (in Sekunden) 
$diffDate = ($actDate-$newDate); 
// Anzahl Jahre = Sekunden /24/60/60/365
// floor() liefert nur den Anteil vor dem Komma 
$years = floor($diffDate / 24 / 60 / 60 / 365 );
$diffDate = $diffDate - ($years*365*24*60*60); 
// den Tagesanteil herausrechnen 
$days = floor($diffDate / 24 / 60 / 60 ); 
$diffDate = $diffDate - ($days*24*60*60); 
// den Stundenanteil herausrechnen 
$hours = floor($diffDate / 60 / 60); 
$diffDate = ($diffDate - ($hours*60*60)); 
// den Minutenanteil 
$minutes = floor($diffDate/60); 
$diffDate = $diffDate - ($minutes*60); 
// die verbleibenden Sekunden 
$seconds = floor($diffDate);
//serverzeit2 
$jahr2 = 2015;
$monat2 = 1;
$tag2 = 1;
 
$newDate2 = mktime(0,0,0, $monat2,$tag2,$jahr2); 
// aktuelle Zeit im Unixformat erzeugen 
$actDate2 = time(); 
// Differenz berechnen (in Sekunden) 
$diffDate2 = ($actDate2-$newDate2); 
// Anzahl Jahre = Sekunden /24/60/60/365
// floor() liefert nur den Anteil vor dem Komma 
$years2 = floor($diffDate2 / 24 / 60 / 60 / 365 );
$diffDate2 = $diffDate2 - ($years2*365*24*60*60); 
// den Tagesanteil herausrechnen 
$days2 = floor($diffDate2 / 24 / 60 / 60 ); 
$diffDate2 = $diffDate2 - ($days2*24*60*60); 
// den Stundenanteil herausrechnen 
$hours2 = floor($diffDate2 / 60 / 60); 
$diffDate2 = ($diffDate2 - ($hours2*60*60)); 
// den Minutenanteil 
$minutes2 = floor($diffDate2/60); 
$diffDate2 = $diffDate2 - ($minutes2*60); 
// die verbleibenden Sekunden 
$seconds2 = floor($diffDate2);

$str_out.="`n`ASeit `s".($years==1?"1 `AJahr":"$years `AJahren")."`A, `s".($days==1?"einem `ATag":"$days `ATagen")."`A, `s".($hours==1?"einer `AStunde":"$hours `AStunden")."`A, `s".($minutes==1?"einer `AMinute":"$minutes `AMinuten")." und `s".($seconds==1?"einer `ASekunde":"$seconds `ASekunden")." online.`0";
$str_out.="`n`qDas einst verlassen Reich wird nun wieder aufgebaut und zu dem Gemacht zu dem es einst bestimmt war.`nZu einem freien Reich. Fast alle Bewohner sind einfach weitergezogen und haben den magischen Ort vergessen.";


// Ausgabe
output($str_out);

// Hotkeys auf Startseite?
$bool_hotkeys = false;
$int_ref=intval($_GET['r']);
$str_ref=($int_ref>0?'?r='.$int_ref:'');
$str_ref2=($int_ref>0?'&r='.$int_ref:'');
clearnav();
addnav('Neu hier?');
addnav('Über LoGD','about.php',false,false,false,$bool_hotkeys);
addnav('F.A.Q.','petition.php?op=faq',false,true,false,$bool_hotkeys);
addnav('Mix-Char erstellen','create_rules.php',false,false,false,$bool_hotkeys);
addnav('Rp-Char erstellen','create_rules2.php',false,false,false,$bool_hotkeys);
addnav('Das Spiel');
addnav('Liste der Einwohner','list.php',false,false,false,$bool_hotkeys);
addnav('Wolf Times','news.php',false,false,false,$bool_hotkeys);
addnav('Spieleinstellungen', 'about.php?op=setup',false,false,false,$bool_hotkeys);
addnav('Passwort vergessen?','create.php?op=forgot',false,false,false,$bool_hotkeys);
addnav('Rund um '.getsetting('townname','Atrahor'));
addnav('`&P`la`^r`At`qn`Qe`$r u`Qn`qd `Am`^e`lh`&r`0','werb.php',false,false,false,$bool_hotkeys);
addnav('Impressum', 'about.php?op=impressum',false,false,false,$bool_hotkeys);
addnav(getsetting('townname','Atrahor').' Forum','http://wolfsrealm.at/forum/',false,false,true,$bool_hotkeys);
addnav('Das Team','team.php?op=3',false,false,false,$bool_hotkeys);
//addnav('Die LoGD-Welt');
//addnav('LoGD Netz','logdnet.php?op=list');
//addnav('DragonPrime','http://www.dragonprime.net',false,false,true);

page_footer();
?>

