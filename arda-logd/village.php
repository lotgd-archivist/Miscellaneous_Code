<?php
/*Dorfplatz Arda
Neu sortiert
von Narjana*/
// 21072004

require_once "common.php";
addcommentary();
$aktiv = getsetting("angriff","0");
 if ($aktiv==1) {
 $anzahl = getsetting("dangreifer","0");
 }
checkday();
if($session[user][age]==1 && $session[user][slave]==1){
redirect("sklavenhandel.php?op=beginn");
}
if($session[user][slave]==1 && $session[user][erlaubniss]==0){
redirect("sklavenhandel.php");
}
if ($session['user']['alive']){ }else{
        redirect("shades.php");
        }
if($session['user']['prison']>=1){
        redirect("kerker.php");
   }

$sql="SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if(($row[acctid1]==$session[user][acctid] && $row[turn]==1) || ($row[acctid2]==$session[user][acctid] && $row[turn]==2)){
        redirect("pvparena.php");
}

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
$session['user']['specialinc']="";
$session['user']['specialmisc']="";

//Links mal sortiert
$feuer = getsetting("feuer","0");
$stein = getsetting("stein","0");
 if ($aktiv==1) addnav("`\$Die Stadt verteidigen","dorfangriff.php");
addnav("Reisen");

 if ($aktiv!=1) addnav("zur Wegkreuzung","kreuzung.php");
 if ($aktiv!=1) addnav("Magische Barriere","dorftor.php");
//addnav("Kornkammern Ardas");

addnav("Auf dem Dorfplatz");
//addnav("Badehaus");
//addnav("Dorfamt");
addnav("Makler","houseshop.php");
if ($session['user']['house']>0) {
addnav("Bautrollshop","trollshop.php");
}
if ($settings['weihnacht'] !== '0')
        {
        addnav("Weihnachtsmarkt von Arda");
        addnav("Schneeball-wettkampf","weihnachtsmarkt.php?op=schball");
        }
//addnav("Arbeitsvermittlung","job.php");
addnav("Bank-Filiale","bankarda.php");

addnav("Gericht","gericht.php");
addnav("Kerker","kerker.php");

addnav("Straßen");
if ($stein<=1){addnav("Wohnviertel","houses.php?location=1");}else{
if ($feuer<=1){addnav("Wohnviertel aufbau","wvfeuer.php?op=aufbau");
                }else{
                addnav("FEUER!!!","wvfeuer.php");
                }}
//addnav("Tavernenstrasse"); Narjanas Sortierstunde
addnav("Universitätsviertel","univier.php");
addnav("Gildenstrasse","gildenstrasse.php");
addnav("`5Dunkle Gasse","slums.php");

ADDNAV("Hafen","hafen.php");
//addnav("Gildenstrasse","gildenstrasse.php");
//addnav("Markplatz");


addnav("Park");
if (@file_exists("pavilion.php")) addnav("P?Auffälliger Pavilion","pavilion.php"); //Ellalith
addnav("G?Der Garten", "gardens.php");
addnav("F?Seltsamer Felsen", "rock.php");
//addnav("Das Geisterschloss", "geisterschloss.php");
/*if ($session['user']['level'] == 15)  {
                        addnav("Seltsame Lichtung","cruxis.php");
                }*/

addnav("`bSonstiges`b");
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
addnav("Lageplan","lageplan.php");
//addnav("Kreativhütte","todolist2.php");
/*if ($session['user']['rlalter']==0){
  addnav("Alter verifizieren","perso.php");
}*/
addnav("N?Tägliche News","news.php");
addnav("Profil & Inventar","prefs.php");
addnav("Kämpferliste","list.php");
addnav("In die Felder (Logout)","login.php?op=logout",true);
if ($session[user][superuser]>=2){
        addnav("Mod");
  addnav("X?`bAdmin Grotte`b","superuser.php");
  if (@file_exists("test.php")) addnav("Test","test.php");
}
/*if ($session[user][superuser]>=2){
  addnav("Neuer Tag","newday.php");
} */


/*
if ($session['user']['rlalter']>=18){
addnav("~ Ü18 ~");
addnav("Freudenhaus","frdnhaus.php");
} nach Zylyma*/
//addnav("Klingengasse");
//addnav("Klingengasse","klingengasse.php");

//addnav("Markt"); Ellalith
//addnav("Marktplatz","markt.php");

/*if (getsetting("vendor",0)==1) addnav("Wanderhändler","vendor.php");
addnav("W?MightyEs Waffen","weapons.php");
addnav("R?Pegasus Rüstungen","armor.php");
addnav("B?Die alte Bank","bank.php");*/

//addnav("Spiel-Forum","http://www.logd-welt.de/forum/index.php",false,false,true);



//let users try to cheat, we protect against this and will know if they try.
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



if (getsetting("topwebid", 0) != 0) {
        addnav("Top Web Games");
        if (date("Y-W", strtotime($session['user']['lastwebvote'])) < date("Y-W"))
                $hilight="`&";
        else
                $hilight="";
        addnav("S?".$hilight."Stimme abgeben", "http://www.topwebgames.com/in.asp?id=".getsetting("topwebid", 0)."&acctid={$session['user']['acctid']}", false, true);
}

page_header("Dorfplatz");
output("`c<img src='images/stadtplatz2.jpg' alt='' >`c`n",true);
if ($aktiv==1) output("<h3>`b`c`\$ACHTUNG!`n Die Stadt wird belagert!`0`c`b</h3>`c`\$Der Späher der Stadtwache meldet, dass es noch $anzahl Angreifer sind`0`c`n",true);
output("`c`wVo`4r d`Wir `eli`\$eg`Et das Zentrum Ardas – der Stadtplatz. Hier laufen große und kleine, dunkle und helle Gassen zusammen, die sich sonst in der ganzen Stadt verzweigen. Den Mittelpunkt bildet ein von alten Bäumen umrahmter `jB`#r`Fu`3n`fn`3e`Fn`E, dessen Fontänen jedes Mal aufs neue ein Spektakel sind. Mal schimmern sie in den Farben des `WR`\$e`Qg`qe`^n`Zb`Oo`2g`3e`fn`5s`E, dann wieder sieht es so aus, als würde das Wasser in den `jB`#r`Fu`3n`fn`3e`Fn `Ehinein, statt aus ihm heraus laufen, doch immer haben die Bewohner der Stadt genügend frisches Wasser. Auch zum Rasten lädt dieser Platz ein, die Bänke, von denen aus man das Schauspiel des Wassers betrachten kann sind gemütlich und selten voll besetzt. Die Wesen laufen geschäftig über diesen Platz, auf dem Weg zu ihren Häusern, Läden oder ihrer Arbeit. Kaum jemand scheint dich überhaupt zu bemerken, weshalb du problemlos einige Passanten dabei belauschen kannst, wie sie über den Handel am Hafen sprechen. Der Weg nach Ardenien steht dir von dort aus offen, sicher ist die Insel ebenso interessant wie diese Stadt, in der sich ständig etwas zu verändern scheint. War das Haus, in dem die Traverne untergebracht ist nicht eben noch `wb`4a`Wc`4k`ws`4t`We`4i`wn`4r`Wo`4t`E? Du zweifelst kurz an deinem Verstand als du es nun in einem wundervollen `öK`Bupferto`ön`E erblickst. Sicher solltest du einen Weg hier nicht anhand der Hausfarbe beschreiben, aber wohin soll dein Weg nun führen? Du hast eine riesige Auswahl an sternförmig vom Platz ab gehenden Straßen, die alle zu anderen Zielen führen werden. Vielleicht solltest du aber auch einfach noch etwas sitzen bleiben und dem Farbspiel des `jB`#r`Fu`3n`fn`3e`Fn`#s `Ezusehen. Mit etwas Glück siehst du sogar einen der sagenumwobenen fliegenden `\$F`qa`^r`Ob`3g`5n`Ro`Äm`öe`E, die alles in dieser Welt erst in Fa`\$rb`ee t`Wau`4ch`wen.`c`n");
//output("`c`4Die Einwohner rennen geschäftig umher. Keiner bemerkt wirklich, dass Du dort stehst. Vor der `Qdunklen Gasse `4hat man dich gewarnt und hier ist auch der Weg zum`9 Hafen`4, um auf die anderen `2Inseln `4zu gelangen. Es gibt einen merkwürdig aussehenden`T Felsen`4 auf einer Seite.`c");
output("`c`5Es gibt einen merkwürdig aussehenden Felsen auf einer Seite.  `n`n");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("`5Auf diesem kannst du die neueste Meldung lesen:`c`n`n`c`i$row[newstext]`i`c`n");
//output("`@Auf jeder Seite wird das Dorf von tiefem dunklem Wald umgeben.`n");
if (getsetting('activategamedate','0')==1) output("`@Wir schreiben den `^".getgamedate()."`@ im Zeitalter des Phoenix.`n");
output("Die Uhr an der Kneipe zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");

//        $t1 = strtotime("now")*getsetting("daysperday",4);
//        $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//        $d1 = date("Y-m-d",$t1);
//        $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");

output("`n`n`%`@In der Nähe reden einige Dorfbewohner:`n");
viewcommentary("village","Hinzufügen",15);
if($session['user']['einlass']==0){
                       redirect("dorftor.php");
                      }
page_footer();
?>