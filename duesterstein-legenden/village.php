
<?php
require_once "common.php";
addcommentary();
checkday();
get_special_var();
if ($session[user][guildID]!=0){
    get_clanguild_var($session[user][guildID]);
}else if($session[user][clanID]!=0){
    get_clanguild_var($session[user][clanID]);
}
//output("<embed src=\"media/sound2.mp3\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
//modified for chapel script by lordraven
if ($session['user']['alive']){ }else{
    redirect("shades.php");
}
if ($session[user][locate]==1 
    || $session[user][locate]==2 
    ){
    if ($session[user][eventname] != NULL){
        $session[user][name]=$session[user][eventname];
        $session[user][eventname] = NULL;
    }
}
if (!$session[event][karneval]){
    if ($session[user][eventname] != NULL){
        $session[user][name]=$session[user][eventname];
        $session[user][eventname] = NULL;
    }
}

//Location festlegen
if ($session[user][locate]!=3){
    $session[user][locate]=3;
    redirect("village.php");
}
//end chapel
$sql="SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if(($row[acctid1]==$session[user][acctid] && $row[turn]==1) || ($row[acctid2]==$session[user][acctid] && $row[turn]==2)){
    redirect("pvparena.php");
}
if (getsetting("automaster",1) && $session['user']['seenmaster']!=1){
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
addnav("W?Wald","forest.php");
if (@file_exists("houses.php")) addnav("Wohnviertel","houses.php");
addnav("Klingengasse");
addnav("T?Trainingslager","train.php");
if (getsetting("pvp",1)){
    addnav("G?Gegen andere Spieler kämpfen","pvp.php");
    addnav("D?Die PvP Arena","pvparena.php");
}
addnav("Turm der Zweikämpfe","tdz.php");

addnav("Marktplatz");
addnav("M?MightyE's Waffen","weapons.php");
addnav("P?Pegasus Rüstungen","armor.php");
addnav("B?Die alte Bank","bank.php");
addnav("Z?Zigeunerzelt","gypsy.php");
if (@file_exists("pavilion.php")) addnav("P?Auffälliger Pavilion","pavilion.php");

addnav("Tavernenstrasse");
addnav("K?Die Kneipe","inn.php",true);
addnav("S?Merick's Ställe","stables.php");
if (@file_exists("lodge.php"))    addnav("J?Jägerhütte","lodge.php");
addnav("F?Seltsamer Felsen", "rock.php");

addnav("Rathausstrasse");
addnav("Bürgeramt", "dorfamt.php");
addnav("C?Gilden & Clans","guild.php");  //guilds
//addnav("Dorfinfos","infoboard.php");
addnav("Z?Zum Pranger","jail.php");

addnav("Träumergasse");
addnav("G?Der Garten", "gardens.php");
addnav("Geschenkeladen","newgiftshop.php");
addnav("Die Kapelle","chapel.php");

addnav("`bSonstiges`b");
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
addnav("Tägliche News","news.php");
addnav("Profil","prefs.php");
addnav("A?Aktualisieren","village.php");
//addnav("Kämpferliste","list.php"); wird eh kein buchstabe gehighlightet; jetzt in den dorfinfos zu finden
addnav("In die Felder (Logout)","login.php?op=mainlogout&wo=0",true);

if ($session[user][superuser]>=2){
  addnav("X?`bAdmin Grotte`b","superuser.php");
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

if ($session[user][superuser]>=4){
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

page_header("Dorfplatz zu Duesterstein");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`@`c`bDorfplatz`b`c
    Die Einwohner rennen geschäftig umher.  Keiner bemerkt wirklich, dass Du dort stehst.`n
    Leise hörst Du manche Einwohner munkeln, dass heute schon bis zu ".maxspieler(3)." Einwohner gleichzeitig
    auf dem Dorfplatz gewesen sein sollen.");
output("  Du siehst verschiedene Geschäfte und Läden entlang der Strasse.  Es gibt einen merkwürdig aussehenden Felsen auf einer Seite.  ");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("Auf diesem kannst du die neuste Meldung lesen:`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird das Dorf von tiefem dunklem Wald umgeben.");
//output("Die Uhr an der Kneipe zeigt `^".getgametime()."`@ Uhr.");
//$time = gametime();
//echo ($time);
//$tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
//$tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
//$secstotomorrow = $tomorrow-$time;
//$realsecstotomorrow = round($secstotomorrow / getsetting("daysperday",4));
//output(" `@Der nächste Spieltag startet in: `^".date("G\\h, i\\m, s\\s \\(\\r\\e\\a\\l\\ \\t\\i\\m\\e\\)",strtotime("1990-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`@");
//$daysalive=getsetting("daysalive",0);
//output("`n`@Dieses Dorf erlebt seinen `^".dorftag(0)."`@. Tag des Jahres `^".dorfjahr(0)."`@");
//output(" und das heutige Wetter ist: `6".$settings['weather']."`@.");
//output("`nEs ist `6".getdayofweek()."`@.");
//$sql="SELECT name FROM accounts 
//        WHERE locked=0 
//        AND loggedin=1 
//        AND locate=3 
//        AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'";
//            $result=db_query($sql) or die(sql_error($sql));
//            $count=db_num_rows($result);
//            $names=$count?"":"niemandem";
//            for ($i=0;$i<$count;$i++){ 
//                $row=db_fetch_assoc($result); 
//                $names.="`^$row[name]"; 
//                if ($i<$count) $names.=", "; 
//            } 
//            db_free_result($result); 
//            output("`n`n`@Anwesend auf dem Dorfplatz sind:`n$names`5.");
//    $t1 = strtotime("now")*getsetting("daysperday",4);
//    $t2 = strtotime($session[user][lasthit])*getsetting("daysperday",4);
//    $d1 = date("Y-m-d",$t1);
//    $d2 = date("Y-m-d",$t2);
//output("`n`nToday is $d1, your last new day was $d2");
output("`n`n`%`@In der Nähe reden einige Dorfbewohner:`n");
viewcommentary("village","Hinzufügen",25);
page_footer();
?>


