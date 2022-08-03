<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();


addnav("Zurück zum Dorf","zwergenstadt.php");
addnav("Trainingslager","train.php");
addnav("Trainingshalle","training.php");
if (getsetting("pvp",1)){
        addnav("Spieler töten","pvp.php");
        addnav("A?Die Arena","pvparena.php");
}
addnav("Ruhmeshalle","hof.php");
addnav("`bSonstiges`b");
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);

page_header("Klingengasse");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`c`n`ÄSu`7ch`^end schaust du dich nach der Klingengasse um, die `7hi`Äer`n
ir`7ge`^ndwo sein soll. Schließlich findest du einen Wegwe`7is`Äer,`n
de`7r d`^ich dahin führt. Doch ist der Name Klingengasse irrefüh`7re`Änd,`n
de`7nn `^sie ist nur ein schmaler Steg, zu dem du nur über eine sch`7ma`Äle,`n
ge`7wu`^ndene, in den Stein gehauene Arrt Treppe ko`7mm`Äst.`c`n`n");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
/*output("Hier kannst du die Meldungen aus Nightwood lesen:`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird Necron von Meer umgeben.`n");*/
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Phoenix.`n");
output("Die Uhr am Rathaus zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//        $t1 = strtotime("now")*getsetting("daysperday",4);
//        $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//        $d1 = date("Y-m-d",$t1);
//        $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");

output("`n`n`%`@In der nähe hörst du einige Zwerge und andere Wesen reden:`n");
viewcommentary("klingengasse","Hinzufügen",15);
page_footer();
?> 