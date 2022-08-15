
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
    $sql = "UPDATE accounts SET sentnotice=1 WHERE acctid='$row[acctid]'";
    db_query($sql);
}


//echo "<pre>".HTMLEntities($sql)."</pre>";

if ($session[loggedin]){
    redirect("badnav.php");
}
page_header();
output("`cWelcome to Legend of the Green Dragon, a shameless knockoff of Seth Able's Legend of the Red Dragon.`n");
output("`@The current time in the village is `%".getgametime()."`@.`0`n");

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = $secstotomorrow / getsetting("daysperday",4);
output("`@Next new game day in: `$".date("G\\h, i\\m, s\\s \\(\\r\\e\\a\\l\\ \\t\\i\\m\\e\\)",strtotime("1970-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`n`n");
output("Enter your name and password to enter the realm.`n");
if ($_GET['op']=="timeout"){
    $session['message'].=" Your session has timed out, you must log in again.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Also, it appears that you may be blocking cookies from this site.  At least session cookies must be enabled in order to use this site.`n";
    }
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output("<form action='login.php' method='POST'>"
.templatereplace("login",array("username"=>translate("<u>U</u>sername"),"password"=>translate("<u>P</u>assword"),"button"=>translate("Log in")))
."</form>`c",true);
// Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
addnav("","login.php");
//output("`n`b`&**BETA**`0 This is a BETA of this website, things are likely to change now and again, as it is under active development (when I have time ;-)) `&**BETA**`0`n");
output("`n`b`&".getsetting("loginbanner","*BETA* This is a BETA of this website, things are likely to change now and again, as it is under active development *BETA*")."`0`b`n");
$session[message]="";
output("`c`2Game server running version: `@{$logd_version}`0`c");

clearnav();
addnav("New to LoGD?");
addnav("Create a character","create.php");
addnav("Other");
addnav("About LoGD","about.php");
addnav("List Warriors","list.php");
addnav("Daily News", "news.php");
addnav("Game Setup Info", "about.php?op=setup");
addnav("LoGD Net","logdnet.php?op=list");
addnav("Forgotten Password","create.php?op=forgot");

page_footer();
?>


