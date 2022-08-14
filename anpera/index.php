
ï»¿<?php



// 20140119



require_once("common.php");



if ($session['loggedin']) redirect("badnav.php");



page_header("LoGD 0.9.7 - anpera.net Version 4","onload='countdown();' onunload='stopit();'");

output("`cWillkommen bei Legend of the Green Dragon, schamlos abgekupfert von Seth Able's Legend of the Red Dragon.`n");

if (getsetting('activategamedate','0')==1) output("`@Wir schreiben den `%".getgamedate()."`@.`0`n");

output("`@Die gegenwÃ¤rtige Zeit im Dorf ist `%".getgametime()."`@.`0`n");



//Next New Day in ... is by JT from logd.dragoncat.net

//New by anpera

$tomorrow=timetotomorrow();

output("`@NÃ¤chster neuer Tag in: `3 ");

output("<script language='JavaScript' type='text/javascript'>

<!--

var timer = null;

var coun = ".timetotomorrow("realsecs").";

var count = ".timetotomorrow("realsecs").";



function stopit(){

  clearTimeout(timer)

}



function countdown() {

  count = coun;

  coun = coun - 1;

  var seconds = count % 60;

  if (coun<0){

    coun=-5;

    secs=((seconds%2)?1:0);

    seconds=\"00\";

    minutes=\"00\";

    hours=\"00\";

  }else{

    secs=1;

    seconds = ((seconds < 10) ? \"0\" : \"\") + seconds;

    count = Math.floor(count / 60);

    var minutes = count % 60;

    minutes = ((minutes < 10) ? \"0\" : \"\") + minutes;

    count = Math.floor(count / 60);

    hours = count;

    hours = ((hours < 10) ? \"0\" : \"\") + hours;

  }

  var zeit = hours + \" Stunden, \" + minutes + \" Minuten, \" + seconds + \" Sekunden\";

  if (secs==0){

    document.getElementById('codo').innerText  = \"`\$\" + zeit;

  }else{

    document.getElementById('codo').innerText  = zeit;

  }

  setTimeout(\"countdown()\",1000);

}

//-->

</script>",true);

output("<span id='codo'>{$tomorrow['hours']} Stunden, {$tomorrow['minutes']} Minuten, {$tomorrow['seconds']} Sekunden</span>.`0`n`n",true);

$newplayer=db_fetch_assoc(db_query("SELECT name FROM accounts WHERE emailvalidation='' AND (race<>0 OR specialty<>0) ORDER BY acctid DESC LIMIT 1"));

if ($newplayer['name']!="") output("`@Unser jÃ¼ngster Spieler ist `^{$newplayer['name']}`@!`0`n");

$newdk=stripslashes(getsetting("newdragonkill",""));

if ($newdk!="") output("`@Der letzte DrachentÃ¶ter war: `&$newdk`@!`0`n`n");



$result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime(date('Y-m-d H:i:s')." -".getsetting("LOGINTIMEOUT",900)." seconds"))."'"));

$onlinecount = $result['onlinecount'];



// do not check if playerlimit is not reached!

if ($onlinecount >= getsetting("maxonline",10) && getsetting("maxonline",10)!=0) {

    $id=$_COOKIE['lgi'];

    $sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0";

    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)>0){

        $is_superuser=1;

    }else{

        $is_superuser=0;

    }

}else{

    $is_superuser = 0;

}



if ($onlinecount<getsetting("maxonline",10) || getsetting("maxonline",10)==0 || $is_superuser){

    output("Gib deinen Namen und dein Passwort ein, um diese Welt zu betreten.`n");

    if ($_GET['op']=="timeout"){

        $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";

        if (!isset($_COOKIE['PHPSESSID'])){

            $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies mÃ¼ssen fÃ¼r diese Seite zugelassen werden.`n";

        }

    }

    if ($session['message']>"") output("`b`\${$session['message']}`b`n");

    output("<form action='login.php' method='POST'>"

        .templatereplace("login",array("username"=>"<u>N</u>ame","password"=>"<u>P</u>asswort","button"=>"Login"))

        ."</form>`c",true);

    // Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't

    addnav("","login.php");

}else{

    output("`^`bDer Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`b`nBitte warte, bis wieder ein Platz frei ist.`n`n");

    if ($_GET['op']=="timeout"){

        $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";

        if (!isset($_COOKIE['PHPSESSID'])){

            $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies mÃ¼ssen fÃ¼r diese Seite zugelassen werden.`n";

        }

    }

    if ($session['message']>"") output("`b`\${$session['message']}`b`n");

    output(templatereplace("full")."`c",true);

}



output("`n`b`&".getsetting("loginbanner","*BETA* This is a BETA of this website, things are likely to change now and again, as it is under active development *BETA*")."`0`b`n");

$session['message']="";

output("`c`2Version auf diesem Gameserver: `@{$logd_version}`0`c");



clearnav();

addnav("Neu hier?");

addnav("Ãœber LoGD","about.php"); // DO NOT REMOVE! // Dieser Link darf NICHT entfernt werden!

addnav("F.A.Q.","petition.php?op=faq",false,true);

addnav("Charakter erstellen","create.php");

addnav("Das Spiel");

addnav("Liste der KÃ¤mpfer","list.php");

addnav("TÃ¤gliche News", "news.php");

addnav("Spieleinstellungen", "about.php?op=setup");

addnav("Passwort vergessen?","create.php?op=forgot");



if (isset($_GET['amusic'])){

    $amusic=$_GET['amusic'];

    setcookie("music",$amusic,strtotime(date('Y-m-d H:i:s')." +365 days"));

}else{

    $amusic=$_COOKIE['music'];

}

addnav("Musik ".($amusic=="aus"?"an":"aus")."schalten","index.php?amusic=".($amusic=="aus"?"an":"aus"));

if ($amusic!="aus") output("<embed src=\"media/soundofsilence.mid\" width=\"10\" height=\"10\" autostart=\"true\" loop=\"true\" hidden=\"true\" volume=\"100\">",true);

addnav("Die LoGD-Welt");

addnav("LoGD Netz","logdnet.php?op=list"); // Dieser Link darf nicht entfernt werden, wenn der Server im LoGD-Netz registriert ist.

addnav("DragonPrime","http://www.dragonprime.net",false,false,true);

page_footer();

?>

