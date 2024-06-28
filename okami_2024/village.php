
<?php

// 21072004

require_once "common.php";
addcommentary();
checkday();

if ($session['user']['alive']){ }else{
    redirect("shades.php");
}
$sql="SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if(($row[acctid1]==$session[user][acctid] && $row[turn]==1) || ($row[acctid2]==$session[user][acctid] && $row[turn]==2)){
    redirect("pvparena.php");
}

// if (getsetting("automaster",1) && $session['user']['seenmaster']!=1){
if (getsetting("automaster",1) && $session['user']['seenmaster']!=2){
    //masters hunt down truant students
    $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
    while (list($key,$val)=each($exparray)){
        $exparray[$key]= round(
            $val + ($session['user']['dragonkills']/4) * $session['user']['level'] * 100
            ,0);
    }
    $expreqd=$exparray[$session['user']['level']+1];
    if ($session['user']['experience']>$expreqd && $session['user']['level']<15){
        redirect("train.php?op=autochallenge");
    }else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15){
        redirect("dragon.php?op=autochallenge");
    }
}
$session['user']['specialinc']="";
$session['user']['specialmisc']="";
addnav("Wald","forest.php");
if (@file_exists("houses.php")) addnav("Wohnviertel","houses.php");
addnav("Gassen");
addnav("Kriegergasse","kg.php");
addnav("Marktgasse","mg.php");

addnav("Tavernenstrasse");
addnav("Schenke Brennenden Wolf","inn.php",true);
addnav("Paradies", "gardens.php");
addnav("F?Seltsamer Felsen", "rock.php");

addnav("`bSonstiges`b");
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
addnav("N?Tägliche News","news.php");
addnav("Profil & Inventar","prefs.php");
addnav("Kämpferliste","list.php");
addnav("In die Felder (Logout)","login.php?op=logout",true);
//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);

if ($session[user][superuser]>=2){
  addnav("X?`bReich der Götter`b","superuser.php");
  if (@file_exists("test.php")) addnav("Test","test.php");
}
//let users try to cheat, we protect against this and will know if they try.
addnav("","superuser.php");
addnav("","user.php");
addnav("","taunt.php");
addnav("","creatures.php");
addnav("","configuration.php");
addnav("","badword.php");
addnav("","armoreditor.php");
addnav("","bios.php");
addnav("","badword.php");
addnav("","donators.php");
addnav("","referers.php");
addnav("","retitle.php");
addnav("","stats.php");
addnav("","viewpetition.php");
addnav("","weaponeditor.php");

if ($session[user][superuser]){
  addnav("Neuer Tag","newday.php");
}

if (getsetting("topwebid", 0) != 0) {
    addnav("Top Web Games");
    if (date("Y-W", strtotime($session['user']['lastwebvote'])) < date("Y-W"))
        $hilight="`&";
    else
        $hilight="";
    addnav("S?".$hilight."Stimme abgeben", "http://www.topwebgames.com/in.asp?id=".getsetting("topwebid", 0)."&acctid={$session['user']['acctid']}", false, true);
}

page_header("Dorfplatz");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`Q`c`bPlatz`b`c`qDu stehst in mitten von Trümmer und nirgends ist ein Wesen zu sehen");
output("Du siehst verschiedene eine Gassen in den die alten Geschäfte und Läden zu finden war.`n
Es gibt einen merkwürdig aussehenden Felsen auf einer Seite.  ");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("Auf diesem kannst du die neueste Meldung lesen:`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird das Reich von tiefem dunklem Wald und Trümmern umgeben.`n");
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Wolfes.`n");
output("Die Uhr an der Kneipe zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//    $t1 = strtotime("now")*getsetting("daysperday",4);
//    $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//    $d1 = date("Y-m-d",$t1);
//    $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");

output("`n`n`%`@In der Nähe reden einige Dorfbewohner:`n");
output("`1``1,`2``2,`3``3,`4``4,`5``5,`6``6,`7``7,`8``8,`9``9,`!``!,`@``@,`#``#,`$``$,`%``%,`^``^,`&``&,`)``),`~``~,`Q``Q,`q``q,`r``r,`R``R,`V``V,`v``v,`g``g,`G``G,`T``T,`t``t
`n");
viewcommentary("village","Hinzufügen",25);
page_footer();
?>

