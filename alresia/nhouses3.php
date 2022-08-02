<?php
/*######################################
# Erweitertes Wohnvietel mit PHP4 OOP
# Autor: Auric @ www.tharesia.de
#    mail: webmaster@blood-reaver.de
# date: 05.05.2006 - version: 0.1 Beta
# based on Anpera's Version
# Here the old credits....
######################################*/
/*
* Author:    anpera
* Email:        logd@anpera.de
* 
* Purpose:    Houses for storing gold and gems and for a save place to sleep (logout)
*        
* Features:    Build house, sell house, buy house, share house with others, private chat-area, PvP
*
* Every warrior can have his own house. He can build it with his own hands or buy one that was sold (or left) before.
* In a house he can store some of his gems and gold and houses are the savest place for log out.
* The player can give keys to other players. So he is able to share his gems and gold for example
* with his wife or he can make up a clan house. A player can only have one house but unlimited keys.
* Each house has its own private chat area. 
* Other players can rob a house if they beat the guard and all players that are sleeping in the house.
*
*
* SEE  INSTRUCTIONS  FOR  INSTALLATION  AT  http://www.anpera.net/forum/viewtopic.php?t=323
* English translation available at DragonPrime
*
* Added furniture 05/25/2004
*  (Buy at vendor - vendor.php)
* Added Durandil's hidden path 05/30/2004
*
* Ok, lets do the code...
*/
require_once "common.php";
require_once "classes3.php";
addcommentary();
checkday();
page_header("Das Wohnviertel");
switch($_GET['op']) {
    case "newday":
    output("`2Gut erholt wachst du im Haus auf und bist bereit für neue Abenteuer.");
    $session['user']['location']=0;
    $sql = "UPDATE items SET hvalue=0 WHERE hvalue>0 AND owner=".$session['user']['acctid']." AND class='Schlüssel3'";
    db_query($sql) or die(sql_error($sql));
    addnav("Tägliche News","news.php");
    addnav("Wohnviertel","nhouses3.php?op=betreten");
    addnav("Zurück zum Sonnenplatz","silanna_village.php");
    break;
    
    case "bio":
    if(!$_GET['id']) redirect("nhouses3.php");
    $haus = new haus($_GET['id']);
    $haus->bio();
    break;
    
    case "bauen":
        
    switch($_GET['act']) {
        case "start":    // Begin des Hausbaus
        /* Hausnummern-Ordnungs-System START */
        $zahl = 0;
        $sqlB="SELECT MAX(houseid) as maximum, MIN(houseid) AS minimum, COUNT(houseid) AS anzahl FROM houses3";
        $resultB = db_query($sqlB) or die(db_error(LINK));
        $max = db_fetch_assoc($resultB);
        if($max['maximum'] > $max['anzahl']) {
                // Wenn die größte ID höher ist als die Anzahl der Häuser...
            $testzahl = $max['minimum'] - 1;
            if($testzahl > 0) {
                $zahl = $testzahl;
                unset($testzahl);
            } else {
                $sqlA="SELECT houseid, housename FROM houses3 ORDER BY houseid ASC";
                $resultA = db_query($sqlA) or die(db_error(LINK));
                    // Liste Anfertigen
                $liste = array();
                for($i=1;$i <= $max['anzahl']; $i++) {
                    $row = db_fetch_assoc($resultA);
                    $liste[] = $row['houseid'];
                }
                for($i=1;$i<=$max['maximum'];$i++) {
                    if(!in_array($i,$liste)) {
                        $zahl = $i;
                        break;
                    }
                }
            }
        }    else $zahl = $max['maximum']+1;
        /* Hausnummern-Ordnungs-System ENDE */
        if($zahl == 0) {
            $sql = "INSERT INTO houses3 (owner,status,gold,gems,housename) VALUES (".$session['user']['acctid'].",0,0,0,'".$session['user']['login']."s Haus')";
        } else {
            $sql = "INSERT INTO houses3 (houseid,owner,status,gold,gems,housename) VALUES (".$zahl.",".$session['user']['acctid'].",0,0,0,'".$session['user']['login']."s Haus')";
        }
        db_query($sql) or die(db_error(LINK));
        $haus = new haus($session['user']['acctid'],true);
        $session['user']['house3']=$haus->id;
        output("`@Du erklärst das Fleckchen Erde zu deinem Besitz und kannst mit dem Bau von Hausnummer `^".$haus->id."`@ beginnen.`n`n");
        output("`0<form action='nhouses3.php?op=bauen&act=weiterbau' method='POST'>",true);
        output("`nGebe einen Namen für dein Haus ein: <input name='hausname' maxlength='25'>`n",true);
        output("`nWieviel Gold anzahlen? <input type='gold' name='gold'>`n",true);
        output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n",true);
        output("<input type='submit' class='button' value='Bauen'>",true);
        addnav("","nhouses3.php?op=bauen&act=weiterbau");
        break;
        
        case "weiterbau":
                // Weiterführender Hausbau
        $haus = new haus($session['user']['acctid'],true);
        $gezahltesgold = (int)$_POST['gold'];
        $gezahltegems = (int)$_POST['gems'];
            // Eventuelle Namensänderung:
        if ($_POST['hausname']>"") $haus->name = stripslashes($_POST['hausname']);
        if ($session['user']['gold'] < $gezahltesgold || $session['user']['gems'] < $gezahltegems) {
            output("`@Du hast nicht genug dabei!");
            addnav("Nochmal","nhouses3.php?op=bauen");
        } else if ($session['user']['turns'] < 1) {
            output("`@Du bist zu müde, um heute noch an deinem Haus zu arbeiten!");
        } else if ($gezahltesgold < 0 || $gezahltegems < 0) {
            output("`@Versuch hier besser nicht zu beschummeln.");
        } else {
            output("`@Du baust für `^".$gezahltesgold."`@ Gold und `#".$gezahltegems."`@ Edelsteine an deinem Haus \"`&".$haus->name."`@\"...`n");
                // Abrechnung Gold:
            $haus->gold += $gezahltesgold;
            $session['user']['gold'] -= $gezahltesgold;
            output("`nDu verlierst einen Waldkampf durch die aufwändige Arbeit.");
            $session['user']['turns']--;
            if ($haus->gold > $haus->goldkosten) {
                output("`nDu hast die kompletten Goldkosten bezahlt und bekommst das überschüssige Gold zurück.");
                $session['user']['gold'] += $haus->gold - $haus->goldkosten;
                $haus->gold=$haus->goldkosten;
            }
                // Abrechnung Gems:
            $haus->gems+=$gezahltegems;
            $session['user']['gems']-=$gezahltegems;
            if ($haus->gems > $haus->gemskosten) {
                output("`nDu hast die kompletten Edelsteinkosten bezahlt und bekommst überschüssige Edelsteine zurück.");
                $session['user']['gems']+=$haus->gems - $haus->gemskosten;
                $haus->gems=$haus->gemskosten;
            }
            $haus->eintragen();
                // Übersicht:
            $done=round(100-((100 * $gezahltesgold / $haus->goldkosten) + (100 * $gezahltegems / $haus->gemskosten))/2);
            if ($haus->gems >= $haus->gemskosten && $haus->gold >= $haus->goldkosten) $done = 100;
            output("`n`n".grafbar(100,$done,"100%",20)."`n",true);
            output("`nDein Haus ist damit zu `\$".$done."%`@ fertig. Du musst noch `^".($haus->goldkosten - $haus->gold)."`@ Gold und `#".($haus->gemskosten - $haus->gems)." `@Edelsteine bezahlen, bis du einziehen kannst.");
            if ($haus->gems >= $haus->gemskosten && $haus->gold >= $haus->goldkosten) $haus->hausbauen();
        }
        addnav("Zurück zum Wohnviertel","nhouses3.php");
        addnav("Zurück zum Sonnenplatz","silanna_village.php");
        break;
        
        default:
        if ($session['user']['hkey2'] > 0) {
            output("`@Du hast bereits Zugang zu einem fertigen Haus und brauchst kein zweites. Wenn du ein neues oder ein eigenes Haus bauen willst, musst du erst aus deinem jetzigen Zuhause ausziehen.");
        } else if ($session['user']['dragonkills'] < getsetting("abwannbauen",2)) {
            output("`@Du hast noch nicht genug Erfahrung, um ein eigenes Haus bauen zu können. Du kannst aber bei einem Freund einziehen, wenn er dir einen Schlüssel für sein Haus gibt.");
        } else if ($session['user']['turns'] < 1) {
            output("`@Du bist zu erschöpft, um heute noch irgendetwas zu bauen. Warte bis morgen.");
        } else if ($session['user']['house3'] > 0) {
            $haus = new haus($session['user']['acctid'],true);
            output("`@Du besichtigst die Baustelle deines neuen Hauses mit der Hausnummer `3".$haus->id." - ".$haus->name."`@.`n`n");
            $goldzuzahlen = $haus->goldkosten - $haus->gold;
            $gemszuzahlen = $haus->gemskosten - $haus->gems;
            $done=round(100-((100 * $goldzuzahlen / $haus->goldkosten) + (100 * $gemszuzahlen / $haus->gemskosten))/2);
            output(grafbar(100,$done,"100%",20),true);
            output("`nEs ist zu `\$".$done."%`@ fertig. Du musst noch `^$goldzuzahlen`@ Gold und `#$gemszuzahlen `@Edelsteine bezahlen.`nWillst du jetzt weiter bauen?`n`n");
            output("`0<form action='nhouses3.php?op=bauen&act=weiterbau' method='POST'>",true);
            output("`nWieviel Gold zahlen? <input type='gold' name='gold'>`n",true);
            output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n",true);
            output("<input type='submit' class='button' value='Bauen'>",true);
            addnav("","nhouses3.php?op=bauen&act=weiterbau");
        } else {
            output("`@Du siehst ein schönes Fleckchen für ein Haus und überlegst dir, ob du nicht selbst eines bauen solltest, anstatt ein vorhandenes zu kaufen oder noch länger in Kneipe und Feldern zu übernachten.");
            output(" Ein Haus zu bauen würde dich `^".getsetting("baukostengold2",50000)." Gold`@ und `#".getsetting("baukostengems2",50000)." Edelsteine`@ kosten. Du mußt das nicht auf einmal bezahlen, sondern könntest immer wieder mal für einen kleineren Betrag ein Stück ");
            output("weiter bauen. Wie schnell du zu deinem Haus kommst, hängt also davon ab, wie oft und wieviel du bezahlst.`n");
            output("Du kannst in deinem zukünftigen Haus alleine wohnen, oder es mit anderen teilen. Es bietet einen sicheren Platz zum Übernachten und einen Lagerplatz für einen Teil deiner Reichtümer.");
            output(" Ein gestartetes Bauvorhaben kann nicht abgebrochen werden.`n`nWillst du mit dem Hausbau beginnen?");
            addnav("Hausbau beginnen","nhouses3.php?op=bauen&act=start");
        }
        addnav("Zurück zum Wohnviertel","nhouses3.php");
        addnav("Zurück zum Sonnenplatz","silanna_village.php");
        break;
    }    // Ende Bauen-Switch
    break;
    
    case "einbrechen":
    if (!$_GET[id]) {
        if ($_POST['search']>"" || $_GET['search']>""){
            if ($_GET['search']>"") $_POST['search']=$_GET['search'];
            if (strcspn($_POST['search'],"0123456789")<=1){
                $search="houseid=".intval($_POST[search])." AND ";
            }else{
                $search="%";
                for ($x=0;$x<mb_strlen($_POST['search']);$x++){
                    $search .= mb_substr($_POST['search'],$x,1)."%";
                }
                $search="housename LIKE '".$search."' AND ";
            }
        }else{
            $search="";
        }
        $ppp=25;
        if (!$_GET[limit]) $page=0;
        else {
            $page=(int)$_GET[limit];
            addnav("Vorherige Strasse","nhouses3.php?op=einbechen&limit=".($page-1)."&search=$_POST[search]");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        output("`c`b`^Einbruch`b`c`0`n");
        output("`@Du siehst dich um und suchst dir ein bewohntes Haus für einen Einbruch aus. ");
        output("Leider kannst du nicht erkennen, wieviele Bewohner sich gerade darin aufhalten und wie stark diese sind. So ein Einbruch ist also sehr riskant.`nFür welches Haus entscheidest du dich?`n`n");
        output("<form action='nhouses3.php?op=einbrechen' method='POST'>Nach Hausname oder Nummer <input name='search' value='$_POST[search]'> <input type='submit' class='button' value='Suchen'></form>",true);
        addnav("","nhouses3.php?op=einbrechen");
        if ($session['user']['pvpflag']=="5013-10-06 00:42:00") output("`n`&(Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!)`0`n`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td><td>Stufe</td><td>Zimmer</td></tr>",true);
        $sql = "SELECT houseid, level FROM houses3 WHERE $search status=1 AND owner<>".$session['user']['acctid']." ORDER BY houseid ASC LIMIT $limit";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Nächste Strasse","nhouses3.php?op=einbruch&limit=".($page+1)."&search=$_POST[search]");
        if (db_num_rows($result)==0){
              output("<tr><td colspan=4 align='center'>`&`iEs gibt momentan keine bewohnten Häuser`i`0</td></tr>",true);
        }else{
            for ($i=0;$i<db_num_rows($result);$i++){
              $row = db_fetch_assoc($result);
              $haus = new haus($row['houseid']);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='".$bgcolor."'><td align='right'>".$haus->id."</td><td><a href='nhouses3.php?op=einbrechen&id=".$haus->id."'>".$haus->name."</a></td>",true);
                output("<td>".$haus->besitzer."</td><td>".$haus->level->name."</td><td>".count($haus->ausbauten)."</td></tr>",true);
                addnav("","nhouses3.php?op=einbrechen&id=".$haus->id);
            }
        }
        output("</table>",true);
        addnav("Umkehren","nhouses3.php");
    } else {
        if ($session['user']['turns']<1 || $session['user']['playerfights']<=0) {
            output("`nDu bist wirklich schon zu müde, um ein Haus zu überfallen.");
            addnav("Zurück","nhouses3.php");
        } else {
            output("`2Du näherst dich vorsichtig Haus Nummer ".$_GET['id']);
            $session['hkey2']=$_GET['id'];
            // Abfrage, ob Schlüssel vorhanden!!
            $sql = "SELECT id FROM items WHERE owner=".$session['user']['acctid']." AND class='Schlüssel3' AND value1=".(int)$_GET['id']." ORDER BY id DESC";
            $result = db_query($sql) or die(db_error(LINK));
            db_free_result($result);
            if (db_num_rows($result)>0) {
                db_free_result($result);
                output(". An der Haustür angekommen suchst du etwas, um die Tür möglichst unauffällig zu öffnen. Am besten dürfte dafür der Hausschlüssel geeignet sein, ");
                output(" den du einstecken hast.`nWolltest du wirklich gerade in ein Haus einbrechen, für das du einen Schlüssel hast?");
                addnav("Haus betreten","nhouses3.php?op=drin&id=$_GET[id]");
                addnav("Zurück zum Sonnenplatz","silanna_village.php");
            } else {
                db_free_result($result);
                // Wache besiegen
                output("Deine gebückte Haltung und der schleichende Gang machen eine Stadtwache aufmerksam...`n");
                $data = haus::strongest($_GET['id']);
                if ($data['athome']>0){
                    $badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"Holzknüppel","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>abs($session['user']['maxhitpoints'] - $date['hp'])+1, "diddamage"=>0);
                }else{
                    $badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"starker Holzknüppel","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>abs(max($session['user']['maxhitpoints'], $session['user']['hitpoints'])), "diddamage"=>0);
                    $session['user']['playerfights']--;
                    $session['user']['reputation']-=7;
                }
                $session['user']['badguy']=createstring($badguy); 
                $fight=true;
            }
        }
    }
    break;
    
    case "einsteigen":
    $data = haus::strongest();
    addnav("Flüchte","silanna_village.php");
    if ($data['athome']>0){
        output("`n Dir kommen $athome misstrauische Bewohner schwer bewaffnet entgegen. Der wahrscheinlich Stärkste von ihnen wird sich jeden Augenblick auf dich stürzen, ");
        output(" wenn du die Situation nicht sofort entschärfst.");
        addnav("Kämpfe","pvp.php?act=attack&bg=2&name=".rawurlencode($data['name']));
    } else {
        output(" Du hast Glück, denn es scheint niemand daheim zu sein. Das wird sicher ein Kinderspiel.");
        addnav("Einsteigen","nhouses3.php?op=klauen&id=$session[hkey2]");
    }    
    break;
    
    case "klauen":
    if(!isset($session['hkey2']) && isset($_GET['id'])) $session['hkey2']=$_GET['id'];
    $haus = new haus($session['hkey2']);
    $haus->klauen();
    break;
    
    case "run":
    output("`%Die Wache lässt dich nicht entkommen!`n");
    $session['user']['reputation']--;
    $fight=true; 
    break;
    
    case "fight":
    $fight=true;
    break;
    
    case "kaufen":
    if (!$_GET['id']) {
        $ppp=10; // Player Per Page to display
        if (!$_GET['limit']){
            $page=0;
        }else{
            $page=(int)$_GET[limit];
            addnav("Vorherige Seite","nhouses3.php?op=kaufen&limit=".($page-1)."");
        }
        $limit="".($page*$ppp).",".($ppp+1);
        $sql = "SELECT houseid FROM houses3 WHERE status=2 OR status=3 OR status=4 ORDER BY houseid ASC LIMIT $limit";
        output("`c`b`^Unbewohnte Häuser`b`c`0`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bGold`b</td><td>`bEdelsteine`b</td><td>Stufe</td><td>`bBemerkung`b</td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","nhouses3.php?op=kaufen&limit=".($page+1)."");
        if (db_num_rows($result)==0){
              output("<tr><td colspan=4 align='center'>`&`iEs stehen momentan keine Häuser zum Verkauf`i`0</td></tr>",true);
        }else{
            for ($i=0;$i<db_num_rows($result);$i++){
              $row = db_fetch_assoc($result);
              $haus = new haus($row['houseid']);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td align='right'>".$haus->id."</td><td><a href='nhouses3.php?op=kaufen&id=".$haus->id."'>".$haus->name."</a></td>",true);
                /*
                if(($haus->status != 3 && $haus->status != 4) && $haus->besitzerid == 0) $kosten = $haus->wert(false);
                elseif($haus->status == 4) {
                    $kosten = $haus->wert();
                    $kosten['gold'] = $haus->gold + round($kosten['gold']*0.9);
                    $kosten['gems'] = $haus->gems + round($kosten['gems']*0.9);
                }
                else $kosten=array("gold"=>$haus->gold, "gems"=>$haus->gems);
                if($kosten['gold'] == 0 || $kosten['gems'] == 0) $kosten = $haus->wert(false);
                */

                output("<td align='right'>".$haus->kosten['gold']."</td><td align='right'>".$haus->kosten['gems']."</td>",true);
                
                output("<td>".$haus->level->name."</td><td>",true);
                if($haus->status == 3 || $haus->status == 4) output($haus->baustatus);
                elseif($haus->besitzerid == 0) output("`^Maklerverkauf`0");
                else output("`6Privatverkauf`0");
                output("</td></tr>",true);
                addnav("","nhouses3.php?op=kaufen&id=".$haus->id);
            }
        }
        output("</table>",true);
    } else {
        $haus = new haus($_GET['id']);
        $haus->kaufen();
    }
    addnav("Zurück zum Wohnviertel","nhouses3.php");
    break;
    
    case "verkaufen":
    $haus = new haus($session['user']['acctid'],true);
    switch($_GET['act']) {
        case "verkauf":
        $haus->verkauf();
        break;
        
        case "makler":
        $haus->maklerverkauf();
        break;
        
        default:
        $kosten = $haus->wert(false);
        output("`@Gib einen Preis für dein Haus ein, oder lass einen Makler den Verkauf übernehmen.`n");
        output("`3Der schmierige Makler würde dir sofort `^".round($kosten['basisgold']/3)."`3 Gold und `#".round($kosten['basisgems']/3)."`3 Edelsteine ");
        output("plus `^".round($haus->kosten['levelgold']/3)." `3Gold und `#".round($kosten['levelgems']/3)." `3Edelsteine für deine Hausausbauten, ");
        output("`nalso insgesammt: `^".round($kosten['gold']/3)."`3 Gold und `#".round($kosten['gems']/3)."`3 Edelsteine geben.`n");
        output("`@Wenn du selbst verkaufst, kannst du vielleicht einen höheren Preis erzielen, musst aber auf dein Geld warten, bis jemand kauft.`nAlles, was sich noch im Haus befindet, wird ");
        output("gleichmässig unter allen Bewohnern aufgeteilt.`n`n");
        output("`0<form action='nhouses3.php?op=verkaufen&act=verkauf' method='POST'>",true);
        output("`nWieviel Gold willst du verlangen? <input type='gold' name='gold'>`n",true);
        output("`nWieviele Edelsteine soll das Haus kosten? <input type='gems' name='gems'>`n",true);
        output("<input type='submit' class='button' value='Anbieten'>",true);
        addnav("","nhouses3.php?op=verkaufen&act=verkauf");
        addnav("An den Makler","nhouses3.php?op=verkaufen&act=makler");
        addnav("W?Zurück zum Wohnviertel","nhouses3.php");
        addnav("Zurück zum Sonnenplatz","silanna_village.php");
        break;        
    }    
    break;
    
            //###################################################################################//
            //################################ "IM-HAUS" BEREICH ################################//
            //###################################################################################//    
    case "drin":
    if ($_GET['id']) $session['hkey2']=(int)$_GET['id'];
    if (!$session['hkey2']) redirect("nhouses3.php");
    $haus = new haus($session['hkey2']);
    switch($_GET['go']) {
        
        case "office": $haus->zimmer_laden('office'); break;
        
        case "schlafzimmer": $haus->zimmer_laden('schlafzimmer'); break;
        
        case "schatz": $haus->zimmer_laden('schatz'); break;
        
        default: 
            // Zimmermodule abfragen:
        $sql = "SELECT * FROM `zimmer3` WHERE aktiv=1 AND level < ".$haus->level->level." ORDER BY zimmerid ASC";
        //$sql = "SELECT * FROM `zimmer3` WHERE aktiv=1 ORDER BY zimmerid ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $counter = false;
        for($i=0;$i<db_num_rows($result);$i++) {
            $row = db_fetch_assoc($result);
            if($_GET['go'] == $row['name']) {
                $counter = true;                
                // Zimmer erstellen:
                $haus->zimmer_laden((string) $row['name']);
            }
        }    // Ende FOR
        
        if($counter == false) $haus->flur();
        break;
        
    }    // Ende Switch
    $haus->eintragen();
    break;
            //#################################################################################//
            //################################ Ausgangsbereich ################################//
            //#################################################################################//    
    
    case "betreten":
    output("`@Du hast Zugang zu folgenden Häusern:`n`n");
    $sql = "SELECT * FROM items WHERE owner=".$session['user']['acctid']." AND class='Schlüssel3' ORDER BY id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    $bgcolor=($i%2==1?"trlight":"trdark");
    output("<table class='$bgcolor' cellpadding=2 cellspacing=1 border=1 align='center'><tr class='trhead'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bStufe`b</tr>",true);
    $ppp=25; // Player Per Page +1 to display
    if (!$_GET['limit']){
        $page=0;
    }else{
        $page=(int)$_GET['limit'];
        addnav("Vorherige Straße","nhouses3.php?op=betreten&limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    if ($session['user']['house']>0 && $session['user']['hkey2']>0){
        $haus = new haus($session['user']['acctid'],true);
        output("<tr><td align='center'>".$haus->id."</td><td><a href='nhouses3.php?op=drin&id=".$haus->id."'>".$haus->name."</a> (dein eigenes)</td><td>".$haus->level->name."</td></tr>",true);
        addnav("","nhouses3.php?op=drin&id=".$haus->id);
    }else if ($session['user']['house']>0 && $session['user']['hkey2']==0){
        output("<tr><td colspan=2 align='center'>`&`iDein Haus ist noch im Bau oder steht zum Verkauf`i`0</td></tr>",true);
    }
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","nhouses3.php?op=betreten&limit=".($page+1)."");
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iDu hast keinen Schlüssel`i`0</td></tr>",true);
    }else{
        $rebuy=0;
        for ($i=0;$i<db_num_rows($result);$i++){
            $item = db_fetch_assoc($result);
            if ($item['value1']==$session['user']['house'] && $session['user']['hkey2']==0) $rebuy=1;
            $bgcolor=($i%2==1?"trlight":"trdark");
            $haus = new haus($item['value1']);
            if ($amt!=$item['value1'] && $haus->besitzerid!=$session['user']['acctid']){
                output("<tr class='$bgcolor'><td align='center'>".$haus->id."</td><td><a href='nhouses3.php?op=drin&id=".$haus->id."'>".$haus->name."</a></td><td>".$haus->level->name."</td></tr>",true);
                addnav("","nhouses3.php?op=drin&id=".$haus->id);
            }
            $amt=$item['value1'];
        }
    }
    output("</table>",true);
    if ($rebuy==1) addnav("Verkauf rückgängig","nhouses3.php?op=kaufen&id=".$session['user']['house']);
    addnav("Zurück zum Sonnenplatz","silanna_village.php");
    addnav("W?Zurück zum Wohnviertel","nhouses3.php");
    break;
    
    default:
    output("`@`b`cDas Wohnviertel`c`b`n`n");
        
    $session['hkey2']=0;
        // Prüfung auf vorhandene Schlüssel:
    $sql = "SELECT * FROM items WHERE owner=".$session['user']['acctid']." AND class='Schlüssel3' ORDER BY id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>0 || $session['user']['hkey2']>0) addnav("Haus betreten","nhouses3.php?op=betreten");
    
    output("Du verlässt den Dorfplatz und schlenderst Richtung Wohnviertel. In diesem schön angelegten Teil des Dorfes siehst du einige Baustellen zwischen bewohnten ");
    output("und unbewohnten Häusern. Hier wohnen also die Helden...`n`n");
        /* Suchbereich START */
    if ($_POST['search']>""){
        if ($_GET['search']>"" || $_GET['search']>"") $_POST['search']=$_GET['search'];
        if (strcspn($_POST['search'],"0123456789")<=1){
            $search="houseid=".intval($_POST[search])." AND ";
        }else{
            $search="%";
            for ($x=0;$x<mb_strlen($_POST['search']);$x++){
                $search .= mb_substr($_POST['search'],$x,1)."%";
            }
            $search="housename LIKE '".$search."' AND ";
        }
    }else{
        $search="";
    }    /* Suchbereich ENDE */
    $ppp=30; // Player Per Page +1 to display
    if (!$_GET['limit']){
        $page=0;
    }else{
        $page=(int)$_GET['limit'];
        addnav("Vorherige Straße","nhouses3.php?limit=".($page-1)."&search=$_POST[search]");
    }
    $limit="".($page*$ppp).",".($ppp+1);
    $sql = "SELECT houseid FROM houses3 WHERE $search status<100 ORDER BY houseid ASC LIMIT $limit";
    $result=db_query($sql) OR die(db_error(LINK));
    output("<form action='nhouses3.php' method='POST'>Nach Hausname oder Nummer <input name='search' value='$_POST[search]'> <input type='submit' class='button' value='Suchen'></form>",true);
    addnav("","nhouses3.php");
    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td><td>`bStufe`b</td><td>Zimmer</td><td>`bStatus`b</td>",true);
    if (db_num_rows($result)>$ppp) addnav("Nächste Straße","nhouses3.php?limit=".($page+1)."&search=$_POST[search]");
    if (db_num_rows($result)==0){
          output("<tr><td colspan=4 align='center'>`&`iEs gibt noch keine Häuser`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $haus = new haus($row['houseid']);
            $bgcolor=($i%2==1?"trlight":"trdark");
            output("<tr class='$bgcolor'><td align='right'>".$haus->id."</td><td><a href='nhouses3.php?op=bio&id=".$haus->id."'>".$haus->name."</a></td><td>",true);
            output(($haus->besitzer?$haus->besitzer:"Niemand")."</td><td>".$haus->level->name."</td><td style='text-align:center;'>".count($haus->ausbauten)."</td><td>".$haus->baustatus."</tr>",true);
            addnav("","nhouses3.php?op=bio&id=".$haus->id);
        }
    }
    output("</table>",true);
    if ($session['user']['hkey2']) output("`nStolz schwingst du den Schlüssel zu deinem Haus im Gehen hin und her.");    
    if ($session['user']['superuser']>2) addnav("Admin Grotte","superuser.php");
    if ($session['user']['house'] && $session['user']['hkey2']) {
        addnav("Haus verkaufen","nhouses3.php?op=verkaufen");
    } else {
        if (!$session[user][house]) addnav("Haus kaufen","nhouses3.php?op=kaufen");
        addnav("Haus bauen","nhouses3.php?op=bauen");
    }
    if (getsetting("pvp",1)==1) addnav("Einbrechen","nhouses3.php?op=einbrechen");
    addnav("Zurück zum Sonnenplatz","silanna_village.php");
    break;
}    // Ende Switch
if ($fight){ 
    if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!=""){ 
        $_GET['skill']=""; 
        if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']); 
        $session['bufflist']=array();
        output("`&Die ungewohnte Umgebung verhindert den Einsatz deiner besonderen Fähigkeiten!`0"); 
    } 
    include "battle.php";
    if ($victory){
        output("`n`#Du hast die Stadtwache besiegt und der Weg zum Haus ist frei!`nDu bekommst ein paar Erfahrungspunkte."); 
        addnav("Weiter zum Haus","nhouses3.php?op=einsteigen&id=$session[hkey2]");
        addnav("Zurück zum Sonnenplatz","silanna_village.php");
        $session['user']['experience']+=$session['user']['level']*10;
        $session['user']['turns']--;
        $badguy=array();
    }elseif ($defeat){ 
        output("`n`\$Die Stadtwache hat dich besiegt. Du bist tot!`nDu verlierst 10% deiner Erfahrungspunkte, aber kein Gold.`nDu kannst morgen wieder kämpfen."); 
        $session['user']['hitpoints']=0; 
        $session['user']['alive']=false;
        $session['user']['experience']=round($session['user']['experience']*0.9);
        $session['user']['badguy']="";
        addnews("`%".$session['user']['name']."`3 wurde von der Stadtwache bei einem Einbruch besiegt.");
        addnav("Tägliche News","news.php");
    }else{ 
        fightnav(false,true); 
    } 
}
page_footer();
?> 