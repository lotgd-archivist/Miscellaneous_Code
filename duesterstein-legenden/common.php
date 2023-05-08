
<?
// ***********************************************
// * common.php                                  *
// * Original by MightyE                         *
// * 25.10.2004                     *
// * modified by raven @ rabenthal.de            *
// * All Functions extracted in seperate Files   *
// * and included if needed                      *
// ***********************************************
require_once "dbwrapper.php";
//
// Clans and Guilds new
//
require_once "guildclanfuncs.php";
require_once "./functions/calcreturnpath.php";
require_once "./functions/color_sanitize.php";
require_once "./functions/create_guild_info.php";
require_once "./functions/populate_guilds.php";
require_once "./functions/safedisplaystring.php";
require_once "./functions/update_guild_info.php";
require_once "./functions/clanguildvar.php";
//
// Clans and Guilds Standard modified
//
require_once "./functions/addnews.php";
require_once "./functions/charstats.php";
require_once "./functions/checkday.php";
require_once "./functions/dumpitem.php";
require_once "./functions/fetchnews.php";
require_once "./functions/pagefooter.php";
require_once "./functions/pageheader.php";
require_once "./functions/redirect.php";
require_once "./functions/settingshandle.php";
require_once "./functions/showform.php";
require_once "./functions/viewcommentary.php";
//
// Standard
//
require_once "./functions/addcommentary.php";
require_once "./functions/addnav.php";
require_once "./functions/appoencode.php";
require_once "./functions/checkban.php";
require_once "./functions/clearnav.php";
require_once "./functions/closetags.php";
require_once "./functions/convertgametime.php";
require_once "./functions/cstat.php";            // User Statis cstat
require_once "./functions/cstat1.php";            // User Statis cstat1
require_once "./functions/debuglog.php";
require_once "./functions/dhms.php";
require_once "./functions/dorfjahr.php";
require_once "./functions/dorftag.php";
require_once "./functions/erand.php";
require_once "./functions/fightnav.php";
require_once "./functions/forest.php";
require_once "./functions/framestat.php";
require_once "./functions/getmicrotime.php";
require_once "./functions/incrspecialty.php";
require_once "./functions/infopanel.php";        // Buddys and Infos
require_once "./functions/isemail.php";
require_once "./functions/isnewday.php";
require_once "./functions/loadtemplate.php";
require_once "./functions/maillink.php";
require_once "./functions/makeseed.php";
require_once "./functions/maxlimit.php";
require_once "./functions/maxspieler.php";
require_once "./functions/motdlink.php";
require_once "./functions/output.php";
require_once "./functions/popup.php";
require_once "./functions/popupfooter.php";
require_once "./functions/popupheader.php";
require_once "./functions/pvpwarning.php";
require_once "./functions/registerglobal.php";
require_once "./functions/safeescape.php";
require_once "./functions/specialvar.php";
require_once "./functions/sql_error.php";
require_once "./functions/systemmail.php";
require_once "./functions/templatereplace.php";
require_once "./functions/updatetexts.php";
require_once "./functions/wahlperiode.php";
require_once "./functions/clearoutput.php";
require_once "./functions/soap.php";
require_once "./functions/saveuser.php";
require_once "./functions/createstring.php";
require_once "./functions/createarray.php";
require_once "./functions/outputarray.php";

require_once "./functions/is_new_day.php";
require_once "./functions/getdayofweek.php";
require_once "./functions/getgamehour.php";
require_once "./functions/getdayhour.php";
require_once "./functions/getgametime.php";
require_once "./functions/gametime.php";
require_once "./functions/ordinal.php";
require_once "./functions/messageboard.php";
require_once "./functions/getmount.php";

require_once "./functions/expbar.php";
require_once "./functions/grafbar.php";
require_once "./functions/logshame.php";


$pagestarttime = getmicrotime();

$nestedtags=array();
$accesskeys=array();
$quickkeys=array();
$output="";

mt_srand(make_seed());

if (file_exists("dbconnect.php")){
    require_once "dbconnect.php";
}else{
    echo "You must edit the file named \"dbconnect.php.dist,\" and provide the requested information, then save it as \"dbconnect.php\"".
    exit();
}

$link = db_pconnect($DB_HOST, $DB_USER, $DB_PASS) or die (db_error($link));
db_select_db ($DB_NAME) or die (db_error($link));
define("LINK",$link);

$appoencode = Load_Tags();
$appoencode_str = Get_Allowed_Tags();

require_once "translator.php";


session_register("session");

$session =& $_SESSION['session'];
//echo nl2br(htmlentities(output_array($session)));
//register_global($_SESSION);
register_global($_SERVER);

if (strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds") > $session['lasthit'] && $session['lasthit']>0 && $session[loggedin]){
    //force the abandoning of the session when the user should have been sent to the fields.
    //echo "Session abandon:".(strtotime("now")-$session[lasthit]);
    
    $session=array();
    $session['message'].="`nDeine Session ist abgelaufen!`n";
}
$session[lasthit]=strtotime("now");

$revertsession=$session;

if ($PATH_INFO != "") {
    $SCRIPT_NAME=$PATH_INFO;
    $REQUEST_URI="";
}
if ($REQUEST_URI==""){
    //necessary for some IIS installations (CGI in particular)
    if (is_array($_GET) && count($_GET)>0){
        $REQUEST_URI=$SCRIPT_NAME."?";
        reset($_GET);
        $i=0;
        while (list($key,$val)=each($_GET)){
            if ($i>0) $REQUEST_URI.="&";
            $REQUEST_URI.="$key=".URLEncode($val);
            $i++;
        }
    }else{
        $REQUEST_URI=$SCRIPT_NAME;
    }
    $_SERVER['REQUEST_URI'] = $REQUEST_URI;
}
$SCRIPT_NAME=substr($SCRIPT_NAME,strrpos($SCRIPT_NAME,"/")+1);
if (strpos($REQUEST_URI,"?")){
    $REQUEST_URI=$SCRIPT_NAME.substr($REQUEST_URI,strpos($REQUEST_URI,"?"));
}else{
    $REQUEST_URI=$SCRIPT_NAME;
}
$allowanonymous=array("index.php"=>true,"login.php"=>true,"create.php"=>true,"about.php"=>true,
            "list.php"=>true,"petition.php"=>true,"connector.php"=>true,"logdnet.php"=>true,
            "referral.php"=>true,"news.php"=>true,"motd.php"=>true,"topwebvote.php"=>true,
            "impressum.php"=>true
            );
$allownonnav = array("badnav.php"=>true,"motd.php"=>true,"petition.php"=>true,"mail.php"=>true,
            "topwebvote.php"=>true
            );
if ($session[loggedin]){
    $sql = "SELECT * FROM accounts WHERE acctid = '".$session[user][acctid]."'";
    $result = db_query($sql);
    if (db_num_rows($result)==1){
        $session[user]=db_fetch_assoc($result);
        $session[output]=$session[user][output];
        $session[user][dragonpoints]=unserialize($session[user][dragonpoints]);
        $session[user][prefs]=unserialize($session[user][prefs]);
        $session[user]['inventory']=unserialize($session[user]['inventory']);
        if (!is_array($session[user][dragonpoints])) $session[user][dragonpoints]=array();
        if (!is_array($session[user]['inventory'])) $session[user]['inventory']=array();
        if (is_array(unserialize($session[user][allowednavs]))){
            $session[allowednavs]=unserialize($session[user][allowednavs]);
        }else{
            //depreciated, left only for legacy support.
            $session[allowednavs]=createarray($session[user][allowednavs]);
        }
        if (($SCRIPT_NAME != "jail.php") && ($session[user][jailtime] > 0) && ($SCRIPT_NAME != "newday.php") && ($SCRIPT_NAME != "mail.php") && ($SCRIPT_NAME != "motd.php") && ($SCRIPT_NAME != "chat.php") && ($SCRIPT_NAME != "login.php")&& ($SCRIPT_NAME != "superuser.php")) {
            redirect("jail.php");
        }
        if (!$session[user][loggedin] || (0 && (date("U") - strtotime($session[user][laston])) > getsetting("LOGINTIMEOUT",900)) ){
            $session=array();
            redirect("index.php?op=timeout","Account ist nicht eingeloggt, aber die Session denkt, er ist es.");
        }
    }else{
        $session=array();
        $session[message]="`4Fehler! Dein Login war falsch.`0";
        redirect("index.php","Account verschwunden!");
    }
    db_free_result($result);
    if ($session[allowednavs][$REQUEST_URI] && !$allownonnav[$SCRIPT_NAME]){
        $session[allowednavs]=array();
    }else{
        if (!$allownonnav[$SCRIPT_NAME]){
            redirect("badnav.php","Navigation auf $REQUEST_URI nicht erlaubt");
        }
    }
        // free inventory from potential block
        $session['user']['blockinventory']=0; // Gargamel
}else{
    //if ($SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
    if (!$allowanonymous[$SCRIPT_NAME]){
        $session['message']="Du bist nicht eingeloggt. Wahrscheinlich ist deine Sessionzeit abgelaufen.";
        redirect("index.php?op=timeout","Not logged in: $REQUEST_URI");
    }
}
//if ($session[user][loggedin]!=true && $SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
if ($session[user][loggedin]!=true && !$allowanonymous[$SCRIPT_NAME]){
    redirect("login.php?op=logout");
}

$session[counter]++;
$nokeeprestore=array("newday.php"=>1,"badnav.php"=>1,"motd.php"=>1,"mail.php"=>1,"petition.php"=>1);
if (!$nokeeprestore[$SCRIPT_NAME]) { //strpos($REQUEST_URI,"newday.php")===false && strpos($REQUEST_URI,"badnav.php")===false && strpos($REQUEST_URI,"motd.php")===false && strpos($REQUEST_URI,"mail.php")===false
  $session[user][restorepage]=$REQUEST_URI;
}else{

}

if ($session['user']['hitpoints']>0){
    $session['user']['alive']=true;
}else{
    $session['user']['alive']=false;
}

$session[bufflist]=unserialize($session[user][bufflist]);
if (!is_array($session[bufflist])) $session[bufflist]=array();
$session[user][lastip]=$REMOTE_ADDR;
if (strlen($_COOKIE[lgi])<32){
    if (strlen($session[user][uniqueid])<32){
        $u=md5(microtime());
        setcookie("lgi",$u,strtotime("+365 days"));
        $_COOKIE['lgi']=$u;
        $session[user][uniqueid]=$u;
    }else{
        setcookie("lgi",$session[user][uniqueid],strtotime("+365 days"));
    }
}else{
    $session[user][uniqueid]=$_COOKIE[lgi];
}
$url = "http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['REQUEST_URI']);
$url = substr($url,0,strlen($url)-1);

/*
if (substr($_SERVER['HTTP_REFERER'],0,strlen($url))==$url || $_SERVER['HTTP_REFERER']==""){

}else{
    $sql = "SELECT * FROM referers WHERE uri='{$_SERVER['HTTP_REFERER']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    db_free_result($result);
    $site = str_replace("http://","",$_SERVER['HTTP_REFERER']);
    if (strpos($site,"/")) 
        $site = substr($site,0,strpos($site,"/"));
    if ($row['refererid']>""){
        $sql = "UPDATE referers SET count=count+1,last=now(),site='".addslashes($site)."' WHERE refererid='{$row['refererid']}'";
    }else{
        $sql = "INSERT INTO referers (uri,count,last,site) VALUES ('{$_SERVER['HTTP_REFERER']}',1,now(),'".addslashes($site)."')";
    }
    db_query($sql);
}
*/
if ($_COOKIE['template']!="") $templatename=$_COOKIE['template'];
if ($templatename=="" || !file_exists("templates/$templatename")) $templatename="duesterstein.htm";
$template = loadtemplate($templatename);
//tags that must appear in the header
$templatetags=array("title","headscript","script");
while (list($key,$val)=each($templatetags)){
    if (strpos($template['header'],"{".$val."}")===false) $templatemessage.="You do not have {".$val."} defined in your header\n";
}
//tags that must appear in the footer
$templatetags=array();
while (list($key,$val)=each($templatetags)){
    if (strpos($template['footer'],"{".$val."}")===false) $templatemessage.="You do not have {".$val."} defined in your footer\n";
}
//tags that may appear anywhere but must appear
$templatetags=array("nav","stats","petition","motd","mail","paypal","copyright","source");
while (list($key,$val)=each($templatetags)){
    if (strpos($template['header'],"{".$val."}")===false && strpos($template['footer'],"{".$val."}")===false) $templatemessage.="You do not have {".$val."} defined in either your header or footer\n";
}

if ($templatemessage!=""){
    echo "<b>Du hast einen oder mehrere Fehler in deinem Template!</b><br>".nl2br($templatemessage);
    $template=loadtemplate("duesterstein.htm");
}

$stone=array(1=>"`\$Merrick's Stein`0",2=>"`9Cedrick's Stein`0",3=>"`#Pegasus' Stein`0",
       4=>"`4Stein der Verwundbarkeit`0",5=>"`qMightyE's Stein`0",6=>"`2Stein der Angst`0",
       7=>"`6Stein des Königs`0",8=>"`vStein der Schwäche`0", 9=>"`%Violet's Stein`0",
       10=>"`7Seth's Stein`0",11=>"`5Stein der Königin`0",12=>"`!Stein der Armut`0",
       13=>"`^Goldstein`0",14=>"`@Stein des Raben`0",15=>"`3Stein der Weisheit`0",
       16=>"`TRamius' Stein`0",17=>"`tStein der Demut`0",18=>"`QFeuerstein`0",
       19=>"`VLavastein`0",20=>"`gStein der Langeweile`0",21=>"`8Ritterstein`0",
       22=>"`rKnappenstein`0",23=>"`1Mönchsstein`0",24=>"`5Druidenstein`0");
       
$races=array(1=>"Troll",2=>"Elf",3=>"Mensch",4=>"Zwerg",5=>"Ork",0=>"Unbekannt",50=>"Hoversheep");

$logd_version = "0.9.7+jt ext (GER) + Duesterstein Edition";
$session['user']['laston']=date("Y-m-d H:i:s");

$playermount = getmount($session['user']['hashorse']);

$titles = array(
    0=>array("Bauernjunge","Bauernmädchen"),
    1=>array("Knecht", "Magd"),
    2=>array("Bauer", "Bäuerin"),
    3=>array("Grossbauer", "Grossbäuerin"),
    4=>array("Gutshofverwalter","Gutshofverwalterin"),
    5=>array("Gutsherr","Gutsherrin"),
    6=>array("Bürger","Bürgerin"),
    7=>array("Gladiator","Gladiatorin"),
    8=>array("Legionär","Legionärin"),
    9=>array("Centurio","Centurioness"),
    10=>array("Meister","Meisterin"),
    11=>array("Ratsherr", "Ratsfrau"),
    12=>array("Verwalter","Verwalterin"),
    13=>array("Bürgermeister", "Bürgermeisterin"),
    14=>array("Major", "Major"),
    15=>array("General", "General"),
    16=>array("Edler", "Edle"),
    17=>array("Ritter", "Ritterin"),
    18=>array("Junker", "Junkerin"),
    19=>array("Kreuzritter", "Kreuzritterin"),
    20=>array("Freiherr", "Freifrau"),
    21=>array("Baumeister", "Baumeisterin"),
    22=>array("Baron", "Baronin"),
    23=>array("Paladin", "Paladina"),
    24=>array("Vasall", "Vasallin"),
    25=>array("Herold", "Heroldin"),
    26=>array("Burgvogt", "Burgvogtin"),
    27=>array("Graf", "Gräfin"),
    28=>array("Landgraf", "Landgräfin"),
    29=>array("Markgraf", "Markgräfin"),
    30=>array("Fürst", "Fürstin"),
    31=>array("Grossfürst", "Grossfürstin"),
    32=>array("Herzog", "Herzogin"),
    33=>array("Grossherzog", "Grossherzogin"),
    34=>array("Kämmerer", "Kämmerin"),
    35=>array("Minister", "Ministerin"),
    36=>array("Kanzler", "Kanzlerin"),
    37=>array("Kronvasall", "Kronvasallin"),
    38=>array("Prinz", "Prinzessin"),
    39=>array("Kronprinz", "Kronprinzessin"),
    40=>array("König", "Königin"),
    41=>array("Kaiser", "Kaiserin"),
    42=>array("Drachenkämpfer", "Drachenkämpferin"),
    43=>array("Drachentöter","Drachentöterin"),
    44=>array("Pilger", "Pilgerin"),
    45=>array("Abt", "Äbtissin"),
    46=>array("Prätor", "Prätorin"),
    47=>array("Erzprätor", "Erzprätorin"),
    48=>array("Bischof","Bischöfin"),
    49=>array("Erzbischof", "Erzbischöfin"),
    50=>array("Weihbischof", "Weihbischöfin"),
    51=>array("Exzellenz", "Exzellenzia"),
    52=>array("Metropolit", "Metropolitin"),
    53=>array("Patriarch", "Patriarchin"),
    54=>array("Repräsentant", "Repräsentantin"),
    55=>array("Kardinal", "Kardinälin"),
    56=>array("Kurienkardinal", "Kurienkardinälin"),
    57=>array("Eminenz", "Eminenzia"),
    58=>array("Inquisitor", "Inquisitorin"),
    59=>array("Erhabener", "Erhabene"),
    60=>array("Wallfahrer", "Wallfahrerin"),
    61=>array("Papst", "Päpstin"),
    62=>array("Seele", "Seele"),
    63=>array("Geist", "Geist"),
    64=>array("Seliger", "Selige"),
    65=>array("Heiliger", "Heilige"),
    66=>array("Wächter", "Wächterin"),
    67=>array("Patron", "Patronin"),
    68=>array("Engel", "Engel"),
    69=>array("Erzengel", "Erzengel"),
    70=>array("Archai", "Archai"),
    71=>array("Autorität", "Autorität"),
    72=>array("Stärke", "Stärke"),
    73=>array("Kraft", "Kraft"),
    74=>array("Macht", "Macht"),
    75=>array("Herrschaft", "Herrschaft"),
    76=>array("Gewalt", "Gewalt"),
    77=>array("Wind", "Wind"),
    78=>array("Blitz", "Blitz"),
    79=>array("Donner", "Donner"),
    80=>array("Feuer", "Feuer"),
    81=>array("Flut", "Flut"),
    82=>array("Sturm", "Sturm"),
    83=>array("Sturmflut", "Sturmflut"),
    84=>array("Orkan", "Orkan"),
    85=>array("Tornado", "Tornado"),
    86=>array("Insignie", "Insignia"),
    87=>array("Dynastie", "Dynastie"),
    88=>array("Exusiai", "Exusiai"),
    89=>array("Dynameis", "Dynameis"),
    90=>array("Kyriotetes", "Kyriotetes"),
    91=>array("Thron", "Thron"),
    92=>array("Elohim", "Elohim"),
    93=>array("Ophanim", "Ophanim"),
    94=>array("Galgalim", "Galgalim"),
    95=>array("Sinanim", "Sinanim"),
    96=>array("Chasmallim", "Chasmallim"),
    97=>array("Cherubim", "Cherubim"),
    98=>array("Sehagim", "Sehagim"),
    99=>array("Seraphim", "Seraphim"),
   100=>array("Phönir", "Phönir"),
   101=>array("Ältester", "Älteste"),
   102=>array("Titan", "Titan"),
   103=>array("Meteor", "Meteor"),
   104=>array("Komet", "Komet"),
   105=>array("Merkur", "Merkur"),
   106=>array("Venus", "Venus"),
   107=>array("Mond", "Mond"),
   108=>array("Mars", "Mars"),
   109=>array("Jupiter", "Jupiter"),
   110=>array("Saturn", "Saturn"),
   111=>array("Uranus", "Uranus"),
   112=>array("Neptun", "Neptun"),
   113=>array("Pluto", "Pluto"),
   114=>array("Sonne", "Sonne"),
   115=>array("Stern", "Stern"),
   116=>array("Galaxie", "Galaxie"),
);

$beta = (getsetting("beta",0) == 1 || $session['user']['beta']==1);
?>


