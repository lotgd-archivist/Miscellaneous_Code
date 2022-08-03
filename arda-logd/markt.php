<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();


addnav("Zurück zur Zwergenstadt","zwergenstadt.php");
//addnav("`bSonstiges`b");
//addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);

page_header("Marktplatz");
output("`c<img src='images/markt.jpg' alt='Marktplatz by Minaya'>`c",true);
output("`@`c`bMarkt`b`c`n`n");
output("`c`ÄDi`7re`^kt am Eingang der Kaverne befindet sich der Marktp`7la`Ätz.`n
De`7r g`^esamte Boden ist gepflastert und mit verschied`7en`Äen`n
Lä`7de`^n und Buden bedeckt. Selbst die Wände der H`7öh`Äle`n
si`7nd `^mit Läden übersäht. Noch während du dich fr`7ag`Äst,`n
wi`7e m`^an wohl diese erreicht, siehst du Zwerge, die schei`7nb`Äar`n
mü`7he`^los zwischen diesen umherschlendern. Dabei wird dir `7kl`Äar,`n
da`7ß d`^u wohl eher die Stände am Boden besuchen soll`7te`Äst,`n
we`7nn `^du nicht schwindelfrei bist. Jedoch sollte`7st `Ädu,`n
we`7nn `^du spezielle Sachen suchst, doch die Mühe auf `7di`Äch`n
ne`7hm`^en, die schmalen Treppen zu besteigen, denn nur `7do`Ärt`n
ob`7en `^findest du die talentierten Handwerker der Zw`7er`Äge.`c");
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
addnav("Wanderhändler","vendor.php");
addnav("Schwarzmarkthändler","vendor2.php");
addnav("W?MightyEs Waffen","weapons.php");
addnav("R?Pegasus Rüstungen","armor.php");
addnav("B?Die alte Bank","bank.php");
ADDNAV("Schmiede","xshop.php");
addnav("Kutschenhändler","kutsche.php");
addnav("Gespannhändler","pferd.php");
//addnav("Besondere Waffen","sweapons.php");
//addnav("Besondere Rüstungen","sarmors.php");
addnav ("Arbeitsvermittlung","job.php");
if (@file_exists("pavilion.php")) addnav("P?Auffälliger Pavilion","pavilion.php");

output("`n`n`%`@In der nähe hörst du einige Leute reden:`n");
viewcommentary("Markt","Hinzufügen",15);
page_footer();
?> 