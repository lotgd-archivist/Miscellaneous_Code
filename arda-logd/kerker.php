<?php

/*
/
/ Kerker - Script für 0.9.7
/ Version 1.2 (Originaldateiname: kerker.php)
/ Diese Version wurde angefertigt im August 2009
/ Copyright by Lazalantin Arrogora
/ http://www.lemuria-legend.de
/
*/

/*
/ --- Einbauanleitung ---
/
/ Einträge in table accounts
/ ALTER TABLE `accounts` ADD `prison` tinyint(4) NOT NULL default '0';
/ ALTER TABLE `accounts` ADD `prisondays` int(10) NOT NULL default '0';
/
/ Verlinkung zu kerker.php erstellen
/
/ in village.php
/ if ($session['user']['prison']) redirect("kerker.php");
/
/ in newday.php
/ if ($session['user']['prison']) $session['user']['prisondays']--;
/
/ in pvp.php
/ bei der Abfrage, wer angezeigt wird "AND (prison = 0)"
/
/ in list.php
/ in der $sql prison bei accounts mitselecten
/ - ersetze:
/   if ($row['location'] == 0) output($loggedin?"`#Online`0":"`3Die Felder`0");
/ - mit:
/   if ($row['location'] == 0) output($loggedin?"`#Online`0":"".($row['prison']?"`3Im Kerker`0":"`3Die Felder`0")."");
/
/
/ --- optional ---
/
/ in houses.php
/ statt Tod nach Niederlage gegen Stadtwache -> Kerker
/
/ if ($badguy['creaturename']=='Stadtwache')
/     output("`n`\$Die Stadtwache hat dich besiegt und geschnappt. Du kommst jetzt für 2 Tage in Haft wegen versuchtem Hausfriedensbruch!");
/     $session['user']['hitpoints'] = 1;
/     $session['user']['prison'] = 1;
/     $session['user']['prisondays'] = 2;
/     addnews($session['user']['name']."`3 wurde von der Stadtwache bei einem Einbruch geschnappt und für einige Tage ins Gefängnis gesteckt.");
/     addnav("Strafe absitzen","kerker.php");
/
*/

require_once "common.php";
addcommentary();
checkday();

$filename = basename(__FILE__);

// Einstellungen
$dorfname = "Arda";

$erlaubeausbruch = true;
$ausbruchchance = e_rand(0,9);

$kerkertexte['default'] = "`TLangsam wagst du es auf die Festung hoch oben auf einer der Klippen zuzuschreiten. Ein mulmiges Gefühl beschleicht dich, als du die hohen Türme betrachtest, die sich aus dem dichten Nebel schälen. Wachen schreiten über die Mauern, ihre Waffen immer kampfbereit. Ja, dies muss der Kerker sein! Vorsichtig gehst du weiter und stehst vor zwei grimmig dreinschauenden Wachen, die dich schweigend mustern. Ihre Lanzen sollen jeden stoppen, der aus- oder einbrechen will. Dein Blick fällt auf die stabilen Tore, im ersten Moment hältst du sie für stählern, doch schnell merkst du, dass da etwas nicht stimmt. Die Runen darauf, scheinen über die Oberfläche zu schwimmen. Zu gerne willst du sie berühren, doch weißt du, dass es dir nicht erlaubt ist. Noch einmal betrachtest du sie und erkennst die Zeichen von Narjana, der Göttin des Wahnsinns und Ellalith, dem Gott des Vergessens. Du schluckst und bist froh, dass du hier nicht gefangen bist. Schließlich lassen die Wachen dich lächelnd, aber immer noch grimmig drein blickend ein. Ob sie wohl Menschen sind? Wohl eher nicht.";
$kerkertexte['judge'] = "`tHier kannst du deine komplette Autorität die Kerkerbefugnisse betreffend ausleben. Du kannst Charaktere inhaftieren lassen, sie in Einzelhaft stecken oder begnadigen, falls sie ihre Schuld in deinen Augen vorzeitig verbüßt haben. Aber mache niemals den Fehler, deine Macht zu missbrauchen!";
$kerkertexte['imprisoned'] = "`tDu befindest dich immer noch im stinkenden Verließ von $dorfname und kannst nichts tun. Der Einzige, der dir dauerhaft Gesellschaft leistet und immer auf dich aufpasst, damit du nicht ausbrichst, ist der große stinkende Kerkertroll mit der riesigen Keule.";
$kerkertexte['trolltalk'] = "`tDu bist es leid, dich die ganze Zeit nur mit den Gefängnisinsassen und anderen Besuchern zu unterhalten und machst dich auf den Weg zur Wachkabine, um ein bisschen mit dem hässlichen Kerkertroll zu plaudern. Als du sein Zimmerchen betrittst, das ihm wegen seiner Größe eindeutig zu eng sein muss, schaut er dich nur an und mustert dich dann von oben bis unten.";
$kerkertexte['kopfgeld'] = "`tSofort kramt er aus seinem Tisch einen Haufen von Fahndungsblättern heraus und sieht sie durch. Dabei wirft er immer wieder einen Blick auf dich. Schwer schluckend verabschiedest du dich schnell und willst das Weite suchen, doch in dem Moment bricht der Troll hinter seinem Tisch hervor und schnappt dich mit seinen gewaltigen Pranken. Tja, du sitzt jetzt im Gefängis!";
$kerkertexte['suppe'] = "`tWeil du einen gewissen Hunger verspürst, bestellst du dir für einen wertvollen Edelstein einen heißen Teller Suppe, der dich hoffentlich wieder zu Kräften kommen lässt. Als der Troll dir endlich die Suppe bringt, merkst du schnell, dass sich die Investition alles andere als gelohnt hat. Die Suppe schmeckt nach nichts als Wasser und Salz. Trotzdem hat sie dich ein wenig zu Kräften kommen lassen.";
$kerkertexte['ausbruchchance'] = "`tNachdem du dem Troll den leeren Teller wieder zurück gegeben hast, ist er dir für einen Moment unvorsichtig nahe. Sein Blick ist auf den Teller konzentriert, während deiner verstohlen auf dem Schlüsselbund an seinem Gürtel hängt. Das ist die Chance! Wenn du den Schlüsselbund jetzt entwenden kannst, wird sich dir eine einmalige Chance zur Flucht bieten können.";
$kerkertexte['ausbruch'] = "`tVollkommen unauffällig schiebst du deine linke Hand durch die Gitterstäbe in Richtung des Schlüsselringes, während du dem Troll mit der anderen Hand den Teller reichst, den er dir sofort abnimmt. Als er sich dann von dir entfernt, scheint er nicht zu bemerken, dass du seine Schlüssel derweil in der Hand hälst. `n`nIn der darauffolgenden Nacht machst du dich so leise du kannst an den Schlössern deiner Zellentür zu schaffen. Als sie auf sind, legst du den Schlüsselbund auf einen Hocker und schleichst dich aus dem Kerker. Du hast es geschafft, du bist frei! Deine Freude verfliegt sehr schnell, als dir der Gedanke kommt, dass man womöglich ein hohes Kopfgeld auf dich aussetzen wird, sobald man deine Flucht bemerkt.";
$kerkertexte['no_ausbruch'] = "`t`tVollkommen unauffällig schiebst du deine linke Hand durch die Gitterstäbe in Richtung des Schlüsselringes, während du dem Troll mit der anderen Hand den Teller reichst, den er dir sofort abnimmt. Mit einer geschmeidigen Bewegung versuchst du, ihm den Ring abzunehmen, doch als er sich abwendet, um zu gehen, spürt er den Widerstand, als du versuchst, seine Schlüssel zu klauen. Mit einem schnellen, aber festen Hieb donnert er dir den Teller auf die Hand und macht einen abfälligen Grunzlaut. Der Teller zerspringt bei dieser Aktion und schneidet dir deine Hand auf. Das war wohl nichts! Zu allem Überflüss grinst der Troll dich an und verkündet, dass du nun wohl einen Tag länger im Gefängnis sitzen wirst!";
$kerkertexte['no_match'] = "`\$Es gibt in diesem Dorf keine/n Bewohner/in, auf den/die dieser Name zutrifft.";
// Einstellungen

page_header("Die Verliese von $dorfname");

$art = array("","Haft","Einzelhaft");

$text = "`b`c`\$Die Kerker von $dorfname`b`c `n`n";

switch ($_GET['op'])
{
    case "begnadigen":
         if (!$_GET['id']) redirect($filename);
         $spieler = db_fetch_assoc(db_query("SELECT name FROM accounts WHERE acctid = ".$_GET['id'].""));
         if ($_GET['do'])
         {
             $text .= "`tDu hast `&".$spieler['name']." `tbegnadigt! Hoffentlich war es die richtige Entscheidung.";
             if ($_GET['id'] == $session['user']['acctid'])
             {
                 $session['user']['prison'] = 0;
                 $session['user']['prisondays'] = 0;
             }
             else
             {
                 db_query("UPDATE accounts SET prison = 0, prisondays = 0 WHERE acctid = ".$_GET['id']."");
                 systemmail($_GET['id'],"Du wurdest begnadigt!","`tDie Wache hat deine restliche Gefängnisstrafe aufgehoben. Du bist nun wieder frei.");
             }
             addnews($session['user']['name']."`t wird heute aus dem Gefängis entlassen!");
         }
         else
         {
             $text .= "`tWillst du `&".$spieler['name']." `twirklich begnadigen? Wenn du das tust, musst du dir dessen wirklich sicher sein.";
             addnav("Ja",$filename."?op=begnadigen&do=1&id=".$_GET['id']."");
         }
         output($text,true);
         addnav("Zurück",$filename);
    break;
    
    case "catch":
         if (!$_GET['id'] || !$_GET['do']) redirect($filename);
         $spieler = db_fetch_assoc(db_query("SELECT name FROM accounts WHERE acctid = ".$_GET['id'].""));
         if ($_POST['tage'])
         {
             if ((int) $_POST['tage'] < 0) $msg = "`tDu kannst einen Dorfbewohner nicht weniger als einen Tag inhaftieren sitzen lassen.";
             if ($msg)
             {
                 $text .= $msg;
                 unset($_POST['tage']);
                 addnav("Nochmal versuchen",$filename."?op=catch&do=".$_GET['do']."&id=".$_GET['id']."");
             }
             else
             {
                 $text .= "`tDu hast `&".$spieler['name']."`t für `&".$_POST['tage']."`t in ".$art[$_GET['do']]." gesteckt! Hoffentlich war es gerechtfertigt.";
                 db_query("UPDATE accounts SET prison = ".$_GET['do'].", prisondays = ".$_POST['tage']." WHERE acctid = ".$_GET['id']."");
                 systemmail($_GET['id'],"Du wurdest eingekerkert!","`tDie Wache hat dich für `&".$_POST['tage']."`t in ".$art[$_GET['do']]." gesteckt! Du wirst deine Strafe wohl absitzen müssen.");
             }
         }
         else
         {
             $text .= "`tGib bitte ein, für wieviele Tage du `&".$spieler['name']."`t in ".$art['do']." schicken willst.`n`n";
             $text .= "<form action='$filename?op=catch&do=".$_GET['do']."&id=".$_GET['id']."' method='post'><input name='tage' width=25> <input type='submit' class='button' value='Einkerkern'>";
             addnav("",$filename."?op=catch&do=".$_GET['do']."&id=".$_GET['id']."");
         }

         output($text,true);
         addnav("Zurück",$filename);
    break;
    
    case "suche":
         if (!$_POST['spieler']) redirect($filename);
         $spieler = $_POST['spieler'];
         $result = db_query("SELECT acctid, name, level FROM accounts WHERE name LIKE '%{$spieler}%' OR login LIKE '%{$spieler}%' ORDER BY acctid");
         if (db_num_rows($result) > 0)
         {
             $text .= "`tDeine Suche nach `&$spieler `that folgende Treffer ergeben:`n`n";
             $text .= "<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td><b>Name</b></td><td><b>Level</b></td>$textzusatz<td><b>Optionen</b></td></tr>";

             for ($i = 0;$i < db_num_rows($result);$i++)
             {
                 $row = db_fetch_assoc($result);
                 $text .= "<tr class=".($i%2?"trdark":"trlight")."><td>".$row['name']."</td><td>".$row['level']."</td><td>";
                 $text .= "<a href='$filename?op=catch&do=1&id=".$row['acctid']."'>In Haft stecken</a>`n<a href='$filename?op=catch&do=2&id=".$row['acctid']."'>In Einzelhaft stecken</a>";
                 addnav("",$filename."?op=catch&do=1&id=".$row['acctid']);
                 addnav("",$filename."?op=catch&do=2&id=".$row['acctid']);
                 $text .= "</td></tr></table>";
             }
         }
         else
         {
             $text .= $kerkertexte['no_match'];
             addnav("Nochmal versuchen",$filename."?op=judge");
         }
         output($text,true);
         addnav("Zurück",$filename);
    break;
    
    case "suppe":
         if ($_GET['act'] == "versuch")
         {
             if ($ausbruchchance == 1)
             {
                 $text .= $kerkertexte['ausbruch'];
                 $session['user']['prison'] = 0;
                 $session['user']['prisondays'] = 0;
                 addnews($session['user']['name']."`t ist aus dem Gefängnis ausgebrochen! Es wurde ein hohes Kopfgeld auf ihn ausgesetzt!");
                 addnav("In die Freiheit","village.php");
             }
             else
             {
                 $text .= $kerkertexte['no_ausbruch'];
                 $session['user']['hitpoints']--;
                 $session['user']['prisondays']++;
                 addnav("Weiter abhängen",$filename);
             }
         }
         else
         {
             $text .= $kerkertexte['suppe'];
             $session['user']['gems']--;
             if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']) $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
             if ($erlaubeausbruch)
             {
                 $text .= "`n`n".$kerkertexte['ausbruchchance'];
                 addnav("Schlüssel schnappen",$filename."?op=suppe&act=versuch");
             }
             addnav("Weiter abhängen",$filename);
         }
         output($text,true);
    break;

    case "judge":
         $text .= $kerkertexte['judge'];
         $text .= "`n`nGib hier den Namen des Dorfbewohners oder der Dorfbewohnerin ein, der deiner Meinung nach eine Strafe im Kerker absitzen muss oder begnadigt werden soll.";
         $text .= "`n`n<form action='$filename?op=suche' method='post'><input name='spieler' width=25> <input type='submit' class='button' value='Suchen'></form>";
         output($text,true);
         addnav("",$filename."?op=suche");
         addnav("Zurück",$filename);
    break;
    
    case "talk":
         $text .= $kerkertexte['trolltalk'];
         if ($session['user']['bounty'] || e_rand(0,24) == 1)
         {
             $text .= "`n`n".$kerkertexte['kopfgeld'];
             if ($session['user']['bounty'])
             {
                 addnews($session['user']['name']."`t wurde aufgrund seines Kopfgeldes vom Kerkertroll außer Gefecht gesetzt und inhaftiert.");
             }
             else
             {
                 addnews($session['user']['name']."`t wurde vom Kerkertroll als Schwerverbrecher identifiziert und inhaftiert.");
             }
             $session['user']['prison'] = 1;
             $session['user']['prisondays'] = e_rand(1,3);
             $session['user']['bounty'] = 0;
         }
         output($text,true);
         addnav("Zurück",$filename);
    break;
    
    default:
         $zahl['1'] = db_num_rows(db_query("SELECT acctid FROM accounts WHERE prison = 1"));
         $zahl['2'] = db_num_rows(db_query("SELECT acctid FROM accounts WHERE prison = 2"));
         
         addnav("Der Kerker");
         if (!$session['user']['prison']) addnav("Mit dem Troll reden",$filename."?op=talk");
         if ($session['user']['superuser'] >= 1) addnav("Kerkerauthorität wahrnehmen",$filename."?op=judge");
         
         $haft = $session['user']['prison'];
         if ($haft)
         {
             if ($session['user']['prisondays'] > 0)
             {
                 $text .= $kerkertexte['imprisoned'];
                 if ($haft == 1) $zusatz = "`n`nDu kannst dich nur mit anderen Sträflingen und mit Besuchern unterhalten oder auf deiner Pritsche vor dich hindösen.";
                 if ($haft == 1 && ($zahl['1']-1) > 0) $text .= "`nWenn du dein Blick so durch die Zellen wandern lässt, erkennst du, dass sich mit dir zusammen `&".$zahl['1']."`t Häftlinge hier aufhalten.";
                 $text .= "`nDu musst noch `&".$session['user']['prisondays']." `tTage in ".$art[$haft]." verbringen.";
                 output($text,true);
                 if ($haft == 1){
                     listprisoners(1,$filename);
                     talken();
                 }
                 if ($session['user']['gems']) addnav("Eine Suppe essen (1 Edelstein)",$filename."?op=suppe");
                 addnav("Schlafen legen","login.php?op=logout");
             }
             else
             {
                 $text .= "`tDeine Gefängnisstrafe ist heute vorbei und du wirst wieder in die Freiheit entlassen. Bleib sauber!";
                 $session['user']['prison'] = 0;
                 addnews($session['user']['name']."`t wird heute aus dem Gefängis entlassen!");
                 addnav("In die Freiheit","village.php");
                 output($text,true);
             }
         }
         else
         {
             $text .= $kerkertexte['default'];
             $text .= "`n`n`TDu siehst an einer am Eingang hängenden, kleinen Tafel, dass zur Zeit `&".$zahl['1']." `TKrieger/innen in Haft und weitere `&".$zahl['2']." `Tin Einzelhaft sitzen.";
             output($text,true);
             if ($zahl['1'] || $zahl['2'])
             {
                 for ($i = 1;$i < count($zahl);$i++)
                 {
                     if ($zahl[$i]) listprisoners($i,$filename);
                 }
             }
             talken();
             addnav("Wege");
             addnav("Zurück","village.php");
         }
    break;
}

function listprisoners($what,$filename)
{
    global $session;
    $name = array("","Haft","Einzelhaft");
    $sql = "SELECT acctid, name, level, prisondays FROM accounts WHERE prison = $what";
    $result = db_query($sql);
    if ($session['user']['superuser'] >=1 ) $rechte = 1;
    output("`n`n`nSpieler die in ".$name[$what]." sitzen:`n`n",true);
    for ($i = 0;$i < db_num_rows($result);$i++)
    {
        $row = db_fetch_assoc($result);
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td><b>Gefangener</b></td><td><b>Level</b></td><td><b>Tage in ".$name[$what]."</b></td>".($rechte?"<td><b>Optionen</b></td>":"")."</tr>",true);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>`&".$row['name']."`n</td><td>`&".$row['level']."`n</td><td>`&".$row['prisondays']."`n</td>".($rechte?"<td><a href='".$filename."?op=begnadigen&id=".$row['acctid']."'>Begnadigen</a></td>":"")."</tr></table>",true);
        if ($session['user']['superuser'] >= 1) addnav("",$filename."?op=begnadigen&id=".$row['acctid']."");
    }
}

function talken()
{
    output("`T`n`nEinige Leute, die zu Besuch sind und die Gefangenen unterhalten sich:`n`n");
    viewcommentary("kerker","Unterhalte dich mit den Insassen oder Besuchern",25);
    //output("`n`1&#0096;1 `2&#0096;2 `3&#0096;3 `4&#0096;4 `5&#0096;5 `6&#0096;6 `7&#0096;7 `8&#0096;8 `9&#0096;9 `n`!&#0096;! `@&#0096;@ `#&#0096;# `\$&#0096;\$ `%&#0096;% `^&#0096;^ `q&#0096;q `Q&#0096;Q `&&#0096;& `n `T&#0096;T `t&#0096;t `R&#0096;R `r&#0096;r `V&#0096;V `v&#0096;v `g&#0096;g`n",true);
}

page_footer();

?>