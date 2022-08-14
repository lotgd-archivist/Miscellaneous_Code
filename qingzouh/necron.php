
<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();


addnav("Schrein","necron-shrine.php");
addnav("Bar","necron-bar.php");
addnav("Casino","necron_casino.php");
addnav("Zum Hafen","necron_hafen.php");
addnav("`bSonstiges`b");
//addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
addnav("Unter die Brücke (Logout)","necron.php?op=logout",true);
//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);
if ($HTTP_GET_VARS[op]=="logout"){
debuglog("logged out in necron");
        $session['user']['donationconfig']=serialize($config);
        $session['user']['location']=3;
        $session['user']['loggedin']=0;
        saveuser();
        $session=array();
        redirect("index.php");
}
page_header("Necron");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`@`c`bNecron`b`cHier in dieser ehemaligen Kolonie fühlst du dich wohl, Sonne, Strand und Promenaden......");
output("Du siehst nur wenige Geschäfte aber der Strand und das Meer sehen bezaubernd aus.");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("Hier kannst du die Meldungen aus Nightwood lesen:`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird Necron von Meer umgeben.`n");
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Drachen.`n");
output("Die Uhr am Rathaus zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//    $t1 = strtotime("now")*getsetting("daysperday",4);
//    $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//    $d1 = date("Y-m-d",$t1);
//    $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");

output("`n`n`%`@In der nähe hörst du einige Necroner reden:`n");
viewcommentary("necron","Hinzufügen",25);
page_footer();
?>

