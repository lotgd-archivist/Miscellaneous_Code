<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();

masterchase();//Jay sein ding



addnav("Straßenzüge");
addnav("`MDe`-r M`Rarktp`-la`Mtz","marktplatz.php");
addnav("Dunkle Treppe","grotte.php");
addnav("`eDie Blutbar","blutbar.php?op=Gasse");

//addnav("Klingengasse","klingengasse.php");
//addnav("Zum Hafen","necron_hafen.php");Narjana
//addnav("Turm der Elemente","turm.php"); //erstmal raus Ellalith    
//addnav("Turm der 7 Sünden","turm7suenden.php");  //erstmal raus Ellalith
addnav("zurück");
addnav("Wegkreuzung","kreuzung.php");//Narjana
addnav("Die Sümpfe der Verlorenen","moor.php");//Narjana funzt

addnav("`bSonstiges`b");
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
addnav("Unter die Brücke (Logout)","zylyma.php?op=logout",true);
//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);
if ($HTTP_GET_VARS[op]=="logout")
{
//debuglog("logged out in necron");
                $session['user']['donationconfig']=serialize($config);
                $session['user']['location']=3;
                $session['user']['loggedin']=0;
                saveuser();
                $session=array();
                redirect("index.php");
}
page_header("Zylyma");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`c`MZ`-y`âl`ây`-m`Ma`c`n`n
`c`MMi`-tt`Ren im Sumpf liegt die Stadt, über die es nur Gerüchte `-gi`Mbt,`n
ei`-n d`Runkler Ort voller Hass und Zwietr`-ac`Mht,`n
wo `-nu`Rr das das Recht des Stärkeren herrsch`-t u`Mnd`n
se`-hr `Roft ist der Sumpf selbst der Gewi`-nn`Mer.`n
Ve`-rf`Rall und Verwesung sind allgegenwärtig und der Gestank raubt dir den A`-te`Mm.`n
Hä`-us`Rer gibt es hier nicht, man hat einfache Holzpfosten in den B`-od`Men`n
ge`-ra`Rmmt und ein Dach darüber ge`-le`Mgt,`n
de`-nn `Rder Sumpf ändert sich immer und ist genau so gefähr`-li`Mch`n
wi`-e d`Rie Leute, die sich hier tum`-me`Mln.`n`n
`MJe`-de`Rr Schritt, den du tiefer in diese Stadt machst, führt dich auf festeren B`-od`Men,`n
zu`-er`Rst einfache Holzbohlen, schließlich sogar gepflasterte `-We`Mge.`n
Au`-ch `Rdie Unterkünfte werden immer besser, denn hie`-r h`Mat`n
na`-ch `Rund nach der Sumpf seine Macht verl`-or`Men.`n
An`-fa`Rngs haben die überdachten Schlafmöglichkeiten auch einen Bretterb`-od`Men,`n
da`-nn `RWände. Schließlich wird das Holz nach und nach durch Stein ers`-et`Mzt.`n
Do`-ch `Rdu merkst auch, daß diese Sicherheit, die sich hier bietet, trügerisc`-h i`Mst.`n
De`-nn `Rdie Stimmung ist hier genauso so feindselig wie der S`-um`Mpf.`n`c`n`n");
/*$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("`âHier kannst du die Meldungen aus Arda`â lesen`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird Zylyma von Sumpf umgeben.`n");
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Phoenix.`n");
output("Die Uhr am Rathaus - welches Rathaus? Auf jedenfall auf einem heruntergekommenen, etwas verschmutzen gebäude zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//        $t1 = strtotime("now")*getsetting("daysperday",4);
//        $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//        $d1 = date("Y-m-d",$t1);
//        $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");

output("`n`n`%`@In der nähe hörst du einige Zylymer reden:`n");*/
viewcommentary("necron","Hinzufügen",10);
page_footer();
?>