
<?php
// 09092004

require_once 'common.php';

if ($session['loggedin'])
{
    redirect('badnav.php');
}
page_header();
output('`c`OWillkommen bei Legend of the Green Dragon in der Eranya-Edition, schamlos abgekupfert von Seth Ables Legend of the Red Dragon.`n`n');

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
// $tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time));
// $tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));
$nextdattime = date('G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)',strtotime('1980-01-01 00:00:00 + '.$realsecstotomorrow.' seconds'));
output('`7Nächster Tagesabschnitt in: <div id="index_time">'.$nextdattime.'</div>`0`n`n');
output(
'<script language="javascript">
/*Kleines Schmankerl by Alucard 
    www.atrahor.de
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
    var s = Math.floor(diff / 3600000);
    diff %= 3600000;
    var m = Math.floor(diff / 60000);
    diff %= 60000;
    var sek = Math.floor(diff / 1000);
    

    
    index_time_div.innerHTML = s+" Stunde"+(s!=1 ? "n":"")+", "+(m<10 ? "0"+m : m)+" Minute"+(m!=1 ? "n" : "")+", "+(sek<10 ? "0"+sek : sek)+" Sekunde"+(sek!=1 ? "n" : "")+" (Echtzeit)";
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
    index_set_time('.date('G, i, s',strtotime('1980-01-01 00:00:00 + '.$realsecstotomorrow.' seconds')).');
}

</script>
'
,true);

if(getsetting('wartung',0) > 0) {
    output("`h`bDer Server befindet sich im Moment im Wartungsmodus, um Änderungen am Spiel oder dem Server störungsfrei vornehmen zu können.`b`nBitte warte, bis sich dies ändert.`n`n`0");
}

$result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND ".user_get_online() ));
$onlinecount = $result['onlinecount'];

// do not check if playerlimit is not reached!
if (( $onlinecount >= getsetting('maxonline',10) && getsetting('maxonline',10)!=0) || getsetting('wartung',0) > 0 )
{
    $id=$_COOKIE['lgi'];
    $sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0";
    $result = db_query($sql) or die(db_error(LINK));
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


output('<img src="images/eranya_banner.png" border="0">`n
        `n
        `f_____`X____`Y____`h_______`(_______`P____`8____`D_____`n
        `Y&#x95;&#x95;&curren;&#x95;&#x95;`n
        `n
        `n
        <img src="images/eranya_bild.png" alt="Illustration by Kerem Beyit © Wizards of the Coast, LLC">`n
        `n
        <script type="text/javascript" language="JavaScript" src="templates/plumi.js"></script>
        <a href="#"'.set_plumi_onclick('desc').' style="vertical-align: middle;">
          <table border="0"><tr><td style="vertical-align: middle;">
          '.set_plumi_img('desc').'
          </td><td>
          <img src="images/eranya_desc_title.png" alt="Eranya Beschreibung">
          </td></tr></table>
        </a>`n
        <div id="desc" style="max-width: 800px; display: none;">
        <p style="text-align: right;">
        `i`o"Es gibt eine Reihenfolge...`n
        Geschichten geschehen, dann sind sie Geschichte.`n
        Und manche Teile der Geschichte sind unfassbar, sie werden zu Sagen und Legenden.`n
        Und aus den Legenden wiederum webt man Märchen."`i`n
        <font face="Times New Roman">`)(Lucifer Morgenstern)</font>`n
        `n
        </p>
        <p style="text-align: justify;">'
        .get_extended_text('eranya_desc').'
        `n
        </p>
        </div>',true);
// Für User mit deaktiviertem JS
output('<noscript>
        <table style="border: 0; max-width: 800px;" cellpadding="5px">
        <tr>
        <td style="text-align: right;">
        `i`o"Es gibt eine Reihenfolge...`n
        Geschichten geschehen, dann sind sie Geschichte.`n
        Und manche Teile der Geschichte sind unfassbar, sie werden zu Sagen und Legenden.`n
        Und aus den Legenden wiederum webt man Märchen."`i`n
        <font face="Times New Roman">`)(Lucifer Morgenstern)</font>`n
        `n
        </td>
        </tr>
        <tr>
        <td style="text-align: justify;">'
        .get_extended_text('eranya_desc').'
        `n
        </td>
        </tr>
        </table>
        </noscript>`n
        `f_____`X____`Y____`h_______`(_______`P____`8____`D_____`n
        `Y&#x95;&#x95;&curren;&#x95;&#x95;`n
        `n
        `n',true);

if ( ($onlinecount<getsetting('maxonline',10) || getsetting('maxonline',10)==0 || $is_superuser) )
{
    output('<img src="images/startseite_login.png" border=0>`n`n',true);
    if ($_GET['op']=='timeout')
    {
        $session['message'].='`nDeine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n';
        if (!isset($_COOKIE['PHPSESSID']))
        {
            $session['message'].=' Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden. Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n';
        }
    }
    if ($session['message']!='')
    {
        output('`b`h'.$session['message'].'`b`n');
    }
    output("<form action='login.php' name='loginform' method='POST' $encoded_password_transfer_script><input type='hidden' name='hidden_pw' />"
    .templatereplace("login",array("username"=>"`o<u>N</u>ame`0","password"=>"`o<u>P</u>asswort`0","button"=>"Einloggen"))
    ."</form>`c",true);
    // Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
    addnav("","login.php");
}
else
{
    output("`^`bDer Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`b`nBitte warte, bis wieder ein Platz frei ist.`n`n");
    if ($_GET['op']=='timeout')
    {
        $session['message'].='`nDeine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n';
        if (!isset($_COOKIE['PHPSESSID']))
        {
            $session['message'].=' Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden. Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n';
        }
    }
    if ($session['message']!='')
    {
        output('`b`h'.$session['message'].'`b`n');
    }
    output(templatereplace('full').'`c',true);
}

output('`n`b`&'.getsetting('loginbanner','').'`0`b`n');
$session['message']='';

// Hotkeys auf Startseite?
$bool_hotkeys = false;
$bool_hotkeys_partner = false;

clearnav();
addnav('Neu hier?');
addnav('Über LoGD','about.php',false,false,false,$bool_hotkeys);
addnav('F.A.Q.','petition.php?op=faq',false,true,false,$bool_hotkeys);
addnav('Charakter erstellen','create_rules.php',false,false,false,$bool_hotkeys);
if(getsetting('demouser_public',0)>0)
{
    addnav('Schnupperzugang','demouser.php'.$str_ref,false,false,false,$bool_hotkeys);
}
addnav('Das Spiel');
addnav('Liste der Einwohner','list.php',false,false,false,$bool_hotkeys);
addnav('Neuigkeiten','news.php',false,false,false,false);
addnav('Spieleinstellungen','about.php?op=setup',false,false,false,$bool_hotkeys);
addnav('Datenschutzerklärung','about.php?op=datenschutzerklaerung',false,false,false,$bool_hotkeys);
addnav('Impressum','about.php?op=impressum',false,false,false,$bool_hotkeys);
addnav('Passwort vergessen?','create.php?op=forgot',false,false,false,$bool_hotkeys);
addnav('Rollenspiel-Namen');
addnav('Fantasy-Namen','http://fantasynamen.npage.de/',false,false,true,false);
addnav('Invertika Wiki','http://wiki.invertika.org/Liste_von_Fantasy_Namen',false,false,true,false);
addnav('Larisweb - Generator','http://www.larisweb.de/tools/namengenerator.php',false,false,true,false);
addnav('Behind the Name - Generator','http://www.behindthename.com/random/',false,false,true,false);
addnav('LoGD-Partner');
addnav('°#211810;(D°#48423c;a°#706c68;r°#989694;k) °#c0c0c0;E°#c0c0c0;a°#c0c0c0;s°#a0a691;s°#909979;o°#818d62;s','http://www.eassos.de/',false,false,true,$bool_hotkeys_partner);
addnav('`ÂG`Öl`áe`ois `á9 `|¾','http://www.gleisneundreiviertel.de/',false,false,true,$bool_hotkeys_partner);
addnav('`úN`9a`wde`9y`úa','http://www.nadeya.de',false,false,true,$bool_hotkeys_partner);
//addnav('`^P`qh`$o`4e`En`qi`^x','http://www.the-legend-of-phoenix.com/',false,false,true,$bool_hotkeys_partner);
addnav('`$S`4h`Ba`âc`Je`ùn`9t`wr`ja','http://shacentra-logd.de/index.php',false,false,true,$bool_hotkeys_partner);
addnav('`ÊSo`aul `mo`Zf `fth`Xe Bla`fck `ZD`mr`aag`Êon','http://www.soul-of-the-black-dragon.de/',false,false,true,$bool_hotkeys_partner);
addnav('Sonstiges');
addnav('LoGD Netz','logdnet.php?op=list',false,false,false,$bool_hotkeys);
addnav('Avatarbase','http://www.avatarbase.de',false,false,true,$bool_hotkeys_partner);

page_footer();
?> 
