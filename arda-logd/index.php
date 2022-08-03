<?php

// 09092004

require_once "common.php";

if ($session[loggedin]){
    redirect("badnav.php");
}
page_header("www.arda-logd.com");
output("`cWillkommen bei Legend of the Green Dragon, schamlos abgekupfert von Seth Able's Legend of the Red Dragon.`n");
output("`n<h10>`\$Dies ist ein Ü18-Server!<h7>`n",true);
error_reporting( E_ALL );
//output("`4Dies ist nur der Beta-Testserver besucht uns bitte unter www.arda-logd.de`n");
if (getsetting('activategamedate','0')==1) output("`n`vWir schreiben den `%".getgamedate()."`v.`0`n");
output("`vDie gegenwärtige Zeit im Dorf ist `%".getgametime()."`v.`0`n");

//Next New Day in ... is by JT from logd.dragoncat.net
$time = gametime();
// $tomorrow = strtotime(date("Y-m-d H:i:s",$time)." + 1 day");
$tomorrow = mktime(0,0,0,date('m',$time),date('d',$time)+1,date('Y',$time)); 
// $tomorrow = strtotime(date("Y-m-d 00:00:00",$tomorrow));
$secstotomorrow = $tomorrow-$time;
$realsecstotomorrow = round($secstotomorrow / (int)getsetting("daysperday",4));
output("`vNächster neuer Tag in: `3".date("G \\S\\t\\u\\n\\d\\e\\n, i \\M\\i\\n\\u\\t\\e\\n, s \\S\\e\\k\\u\\n\\d\\e\\n\\ \\(\\E\\c\\h\\t\\z\\e\\i\\t\\)",strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds"))."`0`n`n");
output("<img src='images/ue18.png' alt='' >`n`n",true);

  // Account-Statistik: Version 0.3; 2006 by Eliwood aka Wasili 
        $sql = 'SELECT `race`,`specialty`,`gold`,`goldinbank`,`dragonkills`,`gems` FROM `accounts` ORDER BY acctid DESC'; 
        $res = db_query($sql); 

        $accounts = array(); 

        $i = 0; 
        while($row = db_fetch_assoc($res)) { 
          if(isset($accounts['races'][$row['race']])) { 
            $accounts['races'][$row['race']]++; 
            $accounts['specialty'][$row['specialty']]++; 
            $accounts['gold'] += $row['gold']; 
            $accounts['gold'] += $row['goldinbank']; 
            $accounts['gems'] += $row['gems']; 
            $accounts['dragonkills'] += $row['dragonkills']; 
          } else { 
            $accounts['races'][$row['race']] = 1; 
            $accounts['specialty'][$row['specialty']] = 1; 
            $accounts['gold'] = $row['gold']; 
            $accounts['gold'] += $row['goldinbank']; 
            $accounts['gems'] += $row['gems']; 
            $accounts['dragonkills'] += $row['dragonkills']; 
          } 
          $i++; 
        } 

       /* // Rassenverteilung 
        output('`n`l`b`cRassenverteilung`c`b`0`n'); 
        while (list($key,$val) = each($colraces)){ 
            if ($key !== 0 && $key !== 50){ 
                if(empty($accounts['races'][$key])) $accounts['races'][$key] = 0; 
                $perc = round(($accounts['races'][$key]/$i)*100,2); 
                output("`b".$val."`b`l: `%".$accounts['races'][$key]."`l User mit dieser Rasse (`%".$perc."%`l)`0`n"); 
            }
        }

        // Verteilung der Besonderen Fähigkeiten 
        output('`n`n`l`b`cVerteilung der Besonderen Fähigkeiten`c`b`0`n'); 
        $specialty = array(1=>"`\$Dunkle Künste`0","`%Mystische Kräfte`0","`lDiebeskunst`0","`qKampfkunst`0","`#Spirituelle Kräfte`0","`vNaturkraft`0");
        while(list($key,$val) = each($specialty)) { 
          if($key !== 0 && $key !== 50) { 
            if(empty($accounts['specialty'][$key])) $accounts['specialty'][$key] = 0; 

            $perc = round(($accounts['specialty'][$key]/$i)*100,2); 

            output('`b`l'.$val.'`0`b`l: `%'.$accounts['specialty'][$key].'`l User mit dieser besonderen Fähigkeit (`%'.$perc.'%`l)`0`n'); 
          } 
        } */

        // Durchschnittswerte & Maximalwerte 
        output('`n`n`l`b`iInteressante Infos`i`b`0`n`n'); 
        $newplayer=stripslashes(getsetting("newplayer",""));
if ($newplayer!="") output("`n`v`l$newplayer`v ist als letztes auf unserer Welt aufgetaucht!`0`n");
$newdk=stripslashes(getsetting("newdragonkill","")); 
if ($newdk!="") output("`vDen letzten Phoenix hat `&$newdk`v getötet!`0`n");
        output('`vEs wurden schon `l'.$accounts['dragonkills'].' `vPhoenixe getötet.`0`n');
$sql = "SELECT * FROM commentary WHERE 1 ORDER BY commentid DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("`vEs wurden schon `l$row[commentid] `vPosts geschrieben`n"); 
$sql = "SELECT * FROM accounts WHERE 1 ORDER BY dragonkills DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("`vDie meisten Phoenixe hat `l$row[name] `vgetötet.`n");
      //  output('`vDurchschittlich getötete Phoenix pro Spieler: `l'.number_format(($accounts['dragonkills']/$i),2,',','\'').' Stück`v.`0`n'); 
      //  output('`vGesammeltes Gold: `l'.$accounts['gold'].' Goldstücke`v.`0`n'); 
       // output('`vDurchschnittlich gesammeltes Gold pro User: `l'.number_format(($accounts['gold']/$i),2,',','\'').' Goldstücke`v.`0`n'); 
        //output('`vGesammelte Edelsteine: `l'.$accounts['gems'].' Edelsteine`v.`0`n'); 
       // output('`vDurchschnittlich gesammelte Edelsteine pro User: `l'.number_format(($accounts['gems']/$i),2,',','\'').' Edelsteine`v.`0`n'); 

//output('`vDurchschnittliche Posts pro User: `l'.number_format(($row['commentid']/$i),2,',','\'').' Posts`v.`0`n`n`n');
$sql = "SELECT * FROM accounts WHERE 1 ORDER BY rppunkte DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("`vDie meisten RP-Punkte hat `l$row[name] `verspielt.`n");
$sql = "SELECT * FROM accounts WHERE  herotattoo > 0 ORDER BY herotattoo DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
if ($row[name]!="") { output("`vDie meisten Tattoos hat `l$row[name] `vsich stechen lassen.`n"); }
$sql = "SELECT * FROM accounts WHERE 1 ORDER BY charm DESC LIMIT 1";
$result = db_query($sql) or die(db_error(LINK));
$row = db_fetch_assoc($result);
output("`vDer schönste Spieler ist `l$row[name]`n`n`n");
        // Ende Account-Statistik: Version 0.3; 2006 by Eliwood aka Wasili

$result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." seconds"))."'"));
$onlinecount = $result['onlinecount'];

// do not check if playerlimit is not reached!
if ($onlinecount >= getsetting("maxonline",10) && getsetting("maxonline",10)!=0) {
$id=$_COOKIE[lgi]; 
$sql = "SELECT superuser,uniqueid FROM accounts WHERE uniqueid='$id' AND superuser>0"; 
$result = db_query($sql) or die(db_error(LINK)); 
if (db_num_rows($result)>0) $is_superuser=1; 
else $is_superuser=0; 
}
else $is_superuser = 0;

if ($onlinecount<getsetting("maxonline",10) || getsetting("maxonline",10)==0 || $is_superuser){
output("`dEin kleines, gnomartiges Wesen, wuselt auf dich zu`n
und schmettert dir schon von weitem ein fröhliches`n
\"Hallo\" entgegen,
doch dann ändert sich dieses bunt schimmernde Wesen,`n
bekommt Flügel und spitze Zähnchen.`n
\"Komm ruhig näher, Fremder, hier wird dir nichts passieren!\"`n
Unsicher schaust du auf das Wesen,`n
was jetzt wie eine flauschige Kugel aussieht.`n
\"Komm und sag die Parole, dann lass ich dich durch!\"`n
Es hat sich jetzt auf einen großen Stein gesetzt`n
und sieht dich abwartend an.`n`&");
if ($_GET['op']=="timeout"){
    $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
    }
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output("<form action='login.php' method='POST'>"
.templatereplace("login",array("username"=>"<u>`s`bD</u>`sein Name","password"=>"<u>`sD</u>`seine Parole`b","button"=>"Einloggen"))
."</form>`c",true);
// Without this, I had one user constantly get 'badnav.php' :/  Everyone else worked, but he didn't
addnav("","login.php");
} else {
output("`l`bDer Server ist im Moment ausgelastet, die maximale Anzahl an Usern ist bereits online.`b`nBitte warte, bis wieder ein Platz frei ist.`n`n");
if ($_GET['op']=="timeout"){
    $session['message'].=" Deine Sessionzeit ist abgelaufen. Bitte neu einloggen.`n";
    if (!isset($_COOKIE['PHPSESSID'])){
        $session['message'].=" Es scheint, als ob die Cookies dieser Seite von deinem System blockiert werden.  Zumindest Sessioncookies müssen für diese Seite zugelassen werden.`n";
    }
}
if ($session[message]>"") output("`b`\$$session[message]`b`n");
output(templatereplace("full")."`c",true);
}

//output("`n`b`&**BETA**`0 This is a BETA of this website, things are likely to change now and again, as it is under active development (when I have time ;-)) `&**BETA**`0`n");
output("`n`b`&".getsetting("loginbanner","*BETA* This is a BETA of this website, things are likely to change now and again, as it is under active development *BETA*")."`0`b`n");
$session[message]="";

output("`c`2Version auf diesem Gameserver: `v{$logd_version} mit eigenen Erweiterungen`0`c");


clearnav();
addnav("Neu hier?");
addnav("Über LoGD","about.php");
addnav("F.A.Q.","petition.php?op=faq",false,true);
addnav("`9Charakter erstellen","create.php");
addnav("Das Spiel");
addnav("Liste der Bewohner","list.php");
addnav("Neuigkeiten", "news.php");
//addnav("Spieleinstellungen", "about.php?op=setup");
addnav("Passwort vergessen?","create.php?op=forgot");
addnav("Unser Forum","http://forum.arda-logd.de/",false,false,true);
//addnav("Unser Chat","http://chat.arda-logd.com/",false,false,true);
addnav("Facebook","https://www.facebook.com/pages/Arda-Logd/386370884795553",false,false,true);
/*addnav("Unsere Künstlerin");
addnav("Minayas Studio","http://www.minayas-studio.de/",false,false,true);
addnav("Minaya bei Deviantart","http://minaya86.deviantart.com/gallery/",false,false,true);
*/addnav("Die LoGD-Welt");
addnav("LoGD Netz","logdnet.php?op=list");
addnav("DragonPrime","http://www.dragonprime.net",false,false,true);
addnav("Partnerserver");
//addnav("1?`wK`4y`Wro`4n`wa`0","http://www.kyrona.de/",false,false,true);
addnav("1?`7Gleis 9 ¾`0","http://www.gleisneundreiviertel.de/index.php",false,false,true);
addnav("2?`^F`Za`8rf`ma`Yl`9l`ya`0","http://www.farfalla-logd.de/index.php",false,false,true);
addnav("3?`^P`qh`\$o`4e`\$n`qi`^x`0","http://www.the-legend-of-phoenix.com//",false,false,true);
addnav("4?`kMo`!nd`9sc`mhat`9te`!n L`kogd`0","http://www.mondschatten-logd.de",false,false,true);
addnav("Voting");
addnav("Top50-Liste","http://www6.topsites24.de/ts/ts.cgi?klick=85&tl=ravendragon",false,false,true);
addnav("Avatare");
addnav("Avatarschmiede","http://www.avatarschmiede.de/",false,false,true);
addnav("Avatarsia","http://www.avatarsia.de/",false,false,true);
addnav("Rechtliches");
addnav("Impressum","impressum.php",false,false,true);
addnav("Datenschutz-Erklärung","schutz.php",false,false,true);
page_footer();
?>