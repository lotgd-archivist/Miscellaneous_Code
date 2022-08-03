<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();

if (getsetting("automaster",1) && $session['user']['seenmaster']!=1){
//if (getsetting("automaster",1) && $session['user']['seenmaster']!=2){
        //masters hunt down truant students
        $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
        while (list($key,$val)=each($exparray)){
                $exparray[$key]= round(
                        $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
                        ,0);
        }
        $expreqd=$exparray[$session['user']['level']+1];
        if ($session['user']['experience']>$expreqd && $session['user']['level']<15){
                redirect("train.php?op=autochallenge");
        }else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15){
                redirect("dragon.php?op=autochallenge");
        }
}
//addnav("Zurück nach Arda","village.php");
//addnav("Sonstiges");
//addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);

page_header("Zwergenstadt");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`@`c`b`^Zwergenstadt`b`c");
output("`c`ÄVo`7r d`^ir schiebt sich langsam eine große Steinplatte zur S`7ei`Äte`n
un`7d g`^ewährt dir so den Zutritt zu der sagenumwobenen S`7ta`Ädt`n
de`7r Z`^werge, die tief im Inneren der Berge in einer riesigen Höhle l`7ie`Ägt.`n
Ka`7um`^ hast du einen Schritt in die Höhle gemacht, schließt sich `7au`Äch`n
sc`7ho`^n die Steinplatte hinter dir. Das leise Klirren ist hier drin zu e`7in`Äem`n
oh`7re`^nbetäubenden Lärm angestiegen, überall hörst du das Klirre`7n u`Änd`n
Sc`7he`^ppern von Rüstungen und Kettenhemden und den Lärm vi`7el`Äer`n
ta`7us`^ender Schmieden. Sobald du dich an den Lärm gewöhnt `7ha`Äst,`n
sc`7ha`^ust du dich interessiert um und dir eröffnet sich ein atemberaube`7nd`Äes`n
Bi`7ld. `^An den Wänden ragen die Häuser der Zwerge bis zur Decke e`7mp`Äor,`n
sc`7he`^inen sie in den Fels des Berges gehauen worden zu sein. Eine stein`7er`Äne`n
Tr`7ep`^pe schlängelt sich an der Wand entlang bis zur Decke und verbi`7nd`Äet`n
so `7di`^e einzelnen Häuser aneinander. Für Licht sorgen mehrere Feuerkrist`7al`Äle,`n
di`7e ü`^berall verteilt sind, der größte allerdings ist an der Decke in der Mitt`7e d`Äer`n
Hö`7hl`^e und taucht alles in ein leicht rötliches L`7ic`Äht.`c`n`n");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("Hier kannst du die Meldungen aus Arda lesen:`n`n`c`i$row[newstext]`i`c`n");
//output("`@Auf jeder Seite wird Necron von Meer umgeben.`n");
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Phoenix.`n");
output("Die Uhr am Rathaus zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//        $t1 = strtotime("now")*getsetting("daysperday",4);
//        $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//        $d1 = date("Y-m-d",$t1);
//        $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");
//if (getsetting("vendor",0)==1) addnav("Wanderhändler","vendor.php");
addnav("Die Straßen");
addnav("Marktplatz","markt.php");
//addnav("Klingengasse");
addnav("Klingengasse","klingengasse.php");
//addnav("Zwergenschacht"); comming soon - Narjana
addnav("Zwergenbar");
addnav("Bar","bar.php");
addnav("befestigtes Lager","herocamp.php");
if ($session['user']['dragonkills']>25) {
addnav("Seltsamer Weg");
addnav("Olymp","olymp.php");
}

addnav("zurück");
addnav("Wegkreuzung","kreuzung.php");
addnav("In die Berge","berge.php");

output("`n`n`%`@In der nähe hörst du einige Leute reden:`n");
viewcommentary("Zwergenstadt","Hinzufügen",10);
page_footer();
?>