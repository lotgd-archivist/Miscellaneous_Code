<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();


addnav("Zurück nach Arda","village.php");
addnav("`bSonstiges`b");
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);

page_header("Testort");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`@`c`Markt`b`c");
output("Du siehst nur wenige Geschäfte.");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
/*output("Hier kannst du die Meldungen aus Nightwood lesen:`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird Necron von Meer umgeben.`n");
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Phoenix.`n");
output("Die Uhr am Rathaus zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//    $t1 = strtotime("now")*getsetting("daysperday",4);
//    $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//    $d1 = date("Y-m-d",$t1);
//    $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");*/
if (getsetting("vendor",0)==1) addnav("Wanderhändler","vendor.php");
addnav("W?MightyEs Waffen","weapons.php");
addnav("R?Pegasus Rüstungen","armor.php");
addnav("B?Die alte Bank","bank.php");

addnav ("Arbeitsvermittlung","job.php");
if (@file_exists("pavilion.php")) addnav("P?Auffälliger Pavilion","pavilion.php");

output("`n`n`%`@In der nähe hörst du einige Leute reden:`n");
viewcommentary("Markt","Hinzufügen",25);
page_footer();
?>