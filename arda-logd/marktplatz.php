<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();

if (@file_exists("lodge.php"))        addnav("J?Jägerhütte","lodge.php");
addnav("Z?Zigeunerzelt","gypsy.php");
//addnav("`wDa`4rk `RAs`4sy`wlum","asylum.php");
addnav("`^Ca`Zsi`^no","zylyma_casino.php");
addnav("`eDas `WFr`eeu`\$den`eha`Wus","frdnhaus.php");
addnav("`kDer `JSk`Mla`-ve`Mnm`Jarkt","sklavenhandel.php");

addnav("Zurück nach Zylyma","zylyma.php");


page_header("Marktplatz Zylyma");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`c`MDe`-r M`Rarktp`-la`Mtz`c`n");
output("`c`MDie `-Hä`Rndler scheinen nicht vertrauenswürdig, doch bieten sie hier all`-es `Man.`n
`MVo`-n H`Raushaltswaren über Möbel bis hin zum Sklaven wirst du hier alles fi`-nd`Men.`n
`MNu`-r e`Rin Ort scheint hier bewacht zu `-se`Min.`n
`kDer `JSk`Mla`-ve`Mnm`Jarkt.`n
`MDe`-r T`Rreffpunkt aller hohen Tiere des La`-nd`Mes.`n
`MEs `-hei`Mßt:`n
\"`1Nur wer einen Sklaven hat, hat auch einen Namen.\"
`n`MUn`-d a`Ruch du merkst... Dies ist nicht nur eine Ph`-ra`Mse.`n
`MNi`-ch`Rt weit entfernt von diesem Ort des Menschenhandels finde`-st `Mdu:`n
`eDas `WFr`eeu`\$den`eha`Wus.
`n`MWo`-hl `Rder sicherste Ort bezahlter `-Lu`Mst.`n
`MHi`-er `Rkannst du aber auch dein Glück herausfor`-de`Mrn`n
`Mun`-d d`Ras `^Ca`Zsi`^no `Rals strahlender Sieger oder Verlierer verla`-ss`Men.`n`n`n`c");
/*$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("Hier kannst du die Meldungen aus Nightwood lesen:`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird Necron von Meer umgeben.`n");
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Phoenix.`n");
output("Die Uhr am Rathaus zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//        $t1 = strtotime("now")*getsetting("daysperday",4);
//        $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//        $d1 = date("Y-m-d",$t1);
//        $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");*/

//output("`n`n`%`@In der nähe hörst du einige Zylymer reden:`n");
viewcommentary("marktplatz","Hinzufügen",15);
page_footer();
?> 