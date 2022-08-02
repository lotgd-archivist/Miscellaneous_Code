<?php
require_once "common.php";
addcommentary();
checkday();

$session['user']['specialinc'] = "";

if ($session['user']['alive']){ }else{
        redirect("shades.php");
}
$sql="SELECT acctid1,acctid2,turn FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if(($row[acctid1]==$session[user][acctid] && $row[turn]==1) || ($row[acctid2]==$session[user][acctid] && $row[turn]==2)){
        redirect("pvparena.php");
}

if (getsetting("automaster",1) && $session['user']['seenmaster']!=1){
        $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
        $expprodk=array(1=>10,22,37,57,80,107,138,174,214,259,309,363,424,490,563,635);
        while (list($key,$val)=each($exparray)){
                $exparray[$key]= round(
                        //$val + ($session['user']['dragonkills']/4) * $key * 100
                        $val + $session['user']['dragonkills'] * $expprodk[$key]
                        ,0);
        }
        $expreqd=$exparray[$session['user']['level']+1];
        if ($session['user']['experience']>$expreqd && $session['user']['level']<15){
                redirect("train.php?op=autochallenge");
        }else if ($session['user']['experience']>$expreqd && $session['user']['level']>=15){
                redirect("dragon.php?op=autochallenge");
        }else if ($session['user']['age']>350){
                redirect("dragon.php?op=altersmuedigkeit");
        }
}

$sql = "SELECT name,reason FROM jail WHERE freedate > NOW()";
$result = db_query($sql);
for($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
                if ($row['name']==$session['user']['name']) $gotojail=1;
}
if ($gotojail==1) redirect("jail.php");

addnav("Wald","forest.php");

addnav("Wohnviertel","houses.php");

addnav("Klingengasse");
addnav("Trainingslager","train.php");
if (getsetting("pvp",1)){
   addnav("Kämpfe mit anderen Spielern","pvp.php");
   addnav("A?Die Arena","pvparena.php");
}
addnav("Ruhmeshalle","hof.php");
//if($session['user']['superuser']>=3||$session['user']['login']=="Delvaria"||$session['user']['login']=="Saiadina"||$session['user']['login']=="nightshade"||$session['user']['login']=="Jiriki"||$session['user']['login']=="Kuri") addnav("Y?Dunkle Gasse","gasse.php");

addnav("Marktplatz");
if (@file_exists('shop.php')) addnav ("Handelsstrasse","shop.php");
addnav("B?Die alte Bank","bank.php");
addnav("Z?Zigeunerzelt","gypsy.php");
addnav("u?Dorfbrunnen","brunnen.php");
//addnav("kleines Zelt","cardhouse.php");
if (getsetting("candy",0)==1) addnav("C`qandy","candy.php");
if (@file_exists("jail.php")) addnav("P?Der Pranger","jail.php");
if (@file_exists("pavilion.php")) addnav("P?Auffälliger Pavilion","pavilion.php");


addnav("Tavernenstrasse");
addnav("K?Die Kneipe","inn.php",true);
addnav("Merick's Ställe","stables.php");
if (@file_exists("lodge.php"))  addnav("J?Jägerhütte","lodge.php");
addnav("G?Der Garten", "gardens.php");
addnav("Seltsamer Felsen", "rock.php");

addnav("`bSonstiges`b");
addnav("??F.A.Q. (für neue Spieler)", "petition.php?op=faq",false,true);
#addnav("PI unterstützen","vote.php");
addnav("Tägliche News","news.php");
addnav("Profil","prefs.php");
addnav("Kämpferliste","list.php");
addnav("Das Team", "team.php");
addnav("In die Felder (Logout)","login.php?op=logout",true);
IF ($session[user][gm]>0)
{
        addnav("SL-Grotte","slgrotte.php");
}

if ($session[user][superuser]>2)
{
        addnav("X?`bAdmin Grotte`b","superuser.php");
        if (@file_exists("test.php")) addnav("Test","test.php");

}
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
//addnav("","referers.php");
addnav("","retitle.php");
addnav("","stats.php");
addnav("","viewpetition.php");
addnav("","weaponeditor.php");

if ($session[user][superuser]){
  addnav("!?Neuer Tag","newday.php");
}
if (getsetting("topwebid", 0) != 0) {
        addnav("Top Web Games");
        if (date("Y-W", strtotime($session['user']['lastwebvote'])) < date("Y-W"))
                $hilight="`&";
        else
                $hilight="";
        addnav("S?".$hilight."Stimme abgeben", "http://www.topwebgames.com/in.asp?id=".getsetting("topwebid", 0)."&acctid={$session['user']['acctid']}", false, true);
}

page_header("Dorfplatz");
//output("<img src='images/trans.gif' width='1' height='700' alt='' align='right'>",true);
output("`@`c`bDorfplatz`b`cAuf dem zentralen Platz im Ort herrscht meist reges Treiben. Bürger schlendern gemächlich an aufgebauten Warenständen vorbei oder unterhalten sich angeregt zwischen den hektisch aufflatternden Tauben. ");
output("Fremde werden für gewöhnlich nur mit Desinteresse gemustert und sonst in Frieden gelassen. ");
output("So ist es zu belebten Stunden leicht, nicht in der Menge aufzufallen - wer es allerdings darauf anlegt, kann schnell Ärger mit Bürgern, die sich gestört fühlen, oder gar mit der Stadtwache bekommen. ");
function insideshop($shopid){
        global $session;
        $sql1="SELECT COUNT(*) AS owner FROM shops_owner so
                        LEFT JOIN shops s USING(shopid)
                        LEFT JOIN accounts a ON a.acctid=so.acctid
                        WHERE so.shopid='$shopid'
                        AND (
                                        a.specialinc=s.source
                                        AND a.loggedin='1'
                                        AND a.locked='0'
                                        AND a.laston > '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'
                        )
                        ";
        $result1 = db_query($sql1) or die(db_error(LINK));
        $row1 = db_fetch_assoc($result1);
        if ($row1['owner']>0) return true;
        else return false;
}
//Voraussetzung, dass Folgendes funktioniert: ID des Juwelierladens muss 1 sein (lässt sich natürlich editieren).
if (insideshop(1)) {output("`n`@Verschiedene Geschäfte und Läden reihen sich am Rande des Platzes und entlang der darin mündenden Straßen aneinander. Im Schaufenster des Juwelierladens kann man eine kleine Auswahl der angebotenen Ware begutachten. Das Schild an der Türe informiert, dass der Laden `&geöffnet `@hat.");}
              else {output("`n`@Verschiedene Geschäfte und Läden reihen sich am Rande des Platzes und entlang der darin mündenden Straßen aneinander. Im Schaufenster des Juwelierladens kann man eine kleine Auswahl der angebotenen Ware begutachten. Das Schild an der Türe informiert, dass der Laden `&geschlossen`@ hat.");}
output("`nEine Seite des Platzes allerdings ist zum Wald hin offen, mittlerweile wieder völlig ungeschützt. ");
output("`nAn einem eigentlich recht zentralen Ort auf dem Platz, der seltsamerweise von vielen Bewohnern gemieden wird, erhebt sich ein hölzernes Podest, auf dem ein Herold des Königs steht und lauthals die neuesten Nachrichten verkündet: ");
$sql = "SELECT * FROM news WHERE 1 ORDER BY newsid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("`n`n`c`i$row[newstext]`i`c`n");
output("`@Auf jeder Seite wird das Dorf von tiefem dunklem Wald umgeben.`n");
if (getsetting('activategamedate','0')==1) output("Wir schreiben den `^".getgamedate()."`@.`n");
output("Die Uhr an der Kneipe zeigt `^".getgametime()."`@.");
output(" Das heutige Wetter: `6".$settings['weather']."`@.");
$newdk=stripslashes(getsetting("newdragonkill",""));
if ($newdk!="") output("`n`@Der letzte Drachentöter war: `&$newdk`@!`0");
output("`n`n`%`@In der Nähe reden einige Dorfbewohner:`n");
viewcommentary("village","Hinzufügen",25);

page_footer();
?> 