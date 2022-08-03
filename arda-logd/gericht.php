<?php

// RPG-Gericht
// 15052005
// http://logd.gloth.org/
// -DoM adrian@gloth.org

require_once "common.php";
//$session[user][location]=13;
page_header("Gericht"); 
//output("`^`c`bGericht`b`c`6"); 
switch($_GET[op])
{
   case "klagen":
      $sql = "INSERT INTO petitions (author,date,body,pageinfo,lastact) VALUES (".(int)$session[user][acctid].",now(),\"".addslashes(output_array($_POST))."\",\"".addslashes(output_array($session,"Session:"))."\",NOW())";
      db_query($sql);
      output("Deine Klage wird nun vom Gericht geprüft, unter Umständen wird sich ein Gerichtshelfer oder Richter vor Prozessbeginn melden, falls Unklarheiten vorliegen.");
      addnav ("Zurück","gericht.php");
   break;
    case "klagenv":
    output("</form>",true);
        output("`(Bitte so ausführlich wie möglich! Andernfalls könnte die Klage von Anfang an abgelehnt werden! Falls es einen `#Zeugen`( gibt, bitte mit angeben, man kann auch einen `#Anwalt`( mit angeben. `sAnklagen können nicht mehr zurückgenommen werden! Überlege gut, ob du sie einreichen willst. Ziehst du deine Anklage vor dem Gericht dennoch zurück, wirst du für die Kosten aufkommen müßen. Es gelten KEINE Anklagen, wie: `jIch wurde im Haus/Schenke/Felder umgebracht! `sDas ist `xPvp `sund `xkein RPG (Rollenspiel)`s, es sei denn eine Einbruchserie dient zu einem RPG.`0`n`n<form action='gericht.php?op=klagen' method='POST'>
                Name des Beklagten: <input name='Beklagter'>`n
        Klage:`n
        <textarea name='Klage' cols='30' rows='5' class='input'></textarea>`n
        Forderung:`n
        <textarea name='Forderung' cols='30' rows='5' class='input'></textarea>`n
        <input type='submit' class='button' value='Einreichen'>`n
        </form>
        ",true);
    
        addnav("","gericht.php?op=klagen");
        addnav ("Abbrechen","gericht.php");
    break;
    case "klages":
        $sql = "INSERT INTO gericht VALUES ('0', NOW() , '".addslashes($_POST[klage])."', '1', '".addslashes($_POST[klaeger])."', '".addslashes($_POST[beklagter])."', '".addslashes($_POST[zeuge])."', '".addslashes($_POST[anwalt])."', '".addslashes($_POST[richter1])."', '0', '".addslashes($_POST[richter2])."', '0', '".addslashes($_POST[richter3])."', '0', '');";
//        ( `gid` , `datum` , `text` , `status` , `klaeger` , `beklagter` , `zeuge`, `richter1` , `richter1e` , `richter2` , `richter2e` , `richter3` , `richter3e` , `urteil` , `urteiltext` ) 
        db_query($sql);
        systemmail($_POST[richter1], "Richterberufung zu einer Klage", "Ihr wurdet zum vorsitzenden Richter berufen! Hier der Klage Text:`n" . $_POST[klage]);
        systemmail($_POST[richter2], "Richterberufung zu einer Klage", "Ihr wurdet zum beisitzenden Richter berufen! Hier der Klage Text:`n" . $_POST[klage]);
        systemmail($_POST[richter3], "Richterberufung zu einer Klage", "Ihr wurdet zum beisitzenden Richter berufen! Hier der Klage Text:`n" . $_POST[klage]);
        systemmail($_POST[klaeger], "Eure Klage wurde eingereicht", "Eure Klage wurde vom Gericht akzeptiert! Hier der Klage Text:`n" . $_POST[klage]);
        systemmail($_POST[beklagter], "Ihr wurdet verklagt", "Ihr wurdet verklagt! Hier der Klage Text:`n" . $_POST[klage]);
        systemmail($_POST[zeuge], "Ihr werdet vorgeladen", "Ihr werdet vorgeladen als Zeuge! Hier der Klage Text:`n" . $_POST[klage]);
        systemmail($_POST[anwalt], "Ihr werdet vorgeladen", "Ihr werdet vorgeladen als Anwalt! Hier der Klage Text:`n" . $_POST[klage]);
        addnav ("Zurück","gericht.php");
    break;
    case "klage":
        output("<form action='gericht.php?op=klages' method='POST'>
                ID des Klägers: <input name='klaeger'>`n
                ID des Beklagten: <input name='beklagter'>`n
                ID des Zeugen: <input name='zeuge'>`n
                ID des Anwalt: <input name='anwalt'>`n
        Klagetext:`n
        <textarea name='klage' cols='30' rows='5' class='input'></textarea>`n
        ",true);

        output("
                ID des 1. Richters: <input name='richter1'>`n
                ID des 2. Richters: <input name='richter2'>`n
                ID des 3. Richters: <input name='richter3'>`n
        <input type='submit' class='button' value='Speichern'>`n
        </form>
        ",true);
        addnav("","gericht.php?op=klages");
        addnav ("Abbrechen","gericht.php");
    break;
    case "laufende":
        $sql = "SELECT * FROM gericht WHERE status=1";
        output("`c`b`&Laufende Prozesse`b`c`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`b Kläger`b</td><td></td><td>`bBeklagter`b</td><td></td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0)
        {
            output("<tr><td colspan=4 align='center'>`&`iKeine Prozesse gefunden`i`0</td></tr>",true);
        }
        
        for ($i=0;$i<db_num_rows($result);$i++)
        {
            $row = db_fetch_assoc($result);
            
        $sqlk = "SELECT name FROM accounts WHERE acctid=".$row[klaeger];
        $resultk = db_query($sqlk) or die(db_error(LINK));
        $rowk = db_fetch_assoc($resultk);
        $sqlb = "SELECT name FROM accounts WHERE acctid=".$row[beklagter];
        $resultb = db_query($sqlb) or die(db_error(LINK));
        $rowb = db_fetch_assoc($resultb);
        $sqlz = "SELECT name FROM accounts WHERE acctid=".$row[zeuge];
        $resultz = db_query($sqlz) or die(db_error(LINK));
        $rowz = db_fetch_assoc($resultz);
        $sqla = "SELECT name FROM accounts WHERE acctid=".$row[anwalt];
        $resulta = db_query($sqla) or die(db_error(LINK));
        $rowa = db_fetch_assoc($resulta);
        
            output("<tr><td>" . $rowk[name] . "</td><td></td><td>" . $rowb[name] . "</td><td><a href=gericht.php?op=beiwohnen&gid=" . $row[gid] . ">Beiwohnen</a></td></tr>",true);
            addnav("","gericht.php?op=beiwohnen&gid=" . $row[gid]);
        }
        output("</table>",true);
        addnav ("Zurück","gericht.php");
    break;
    case "beendete":
        $sql = "SELECT * FROM gericht WHERE status=2";
        output("`c`b`&Beendete Prozesse`b`c`n");
        output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bKläger`b</td><td></td><td>`bBeklagter`b</td><td></td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0)
        {
            output("<tr><td colspan=4 align='center'>`&`iKeine Prozesse gefunden`i`0</td></tr>",true);
        }
        
        for ($i=0;$i<db_num_rows($result);$i++)
        {
            $row = db_fetch_assoc($result);
            
        $sqlk = "SELECT name FROM accounts WHERE acctid=".$row[klaeger];
        $resultk = db_query($sqlk) or die(db_error(LINK));
        $rowk = db_fetch_assoc($resultk);
        $sqlb = "SELECT name FROM accounts WHERE acctid=".$row[beklagter];
        $resultb = db_query($sqlb) or die(db_error(LINK));
        $rowb = db_fetch_assoc($resultb);
        $sqlz = "SELECT name FROM accounts WHERE acctid=".$row[zeuge];
        $resultz = db_query($sqlz) or die(db_error(LINK));
        $rowz = db_fetch_assoc($resultz);
        $sqla = "SELECT name FROM accounts WHERE acctid=".$row[anwalt];
        $resulta = db_query($sqla) or die(db_error(LINK));
        $rowa = db_fetch_assoc($resulta);
        
            output("<tr><td>".$rowk[name]."</td><td></td><td>".$rowb[name]."</td><td><a href=gericht.php?op=beiwohnen&gid=".$row[gid].">Beiwohnen</a></td></tr>",true);
            addnav("","gericht.php?op=beiwohnen&gid=".$row[gid]);
        }
        output("</table>",true);
        addnav ("Zurück","gericht.php");
    break;
    case "beiwohnen":
        $sql = "SELECT * FROM gericht WHERE gid=".$_GET[gid];
        $result = db_query($sql) or die(db_error(LINK));
        if($row = db_fetch_assoc($result))
        {
            $sqlr = "SELECT name FROM accounts WHERE acctid=" . $row[richter1];
            $resultr = db_query($sqlr) or die(db_error(LINK));
            $rowr = db_fetch_assoc($resultr);
            output("`6Vorsitzender Richter: " . $rowr[name]);
            if($session[user][superuser] >= 2)
            {
                if($row[richter1e] == 1)
                    output("`& Pro Kläger");
                else
                    if($row[richter1e] == 2)
                        output("`& Contra Kläger");
            }
            $sqlr = "SELECT name FROM accounts WHERE acctid=" . $row[richter2];
            $resultr = db_query($sqlr) or die(db_error(LINK));
            $rowr = db_fetch_assoc($resultr);
            output("`n`6Beisitzender Richter: " . $rowr[name]);
            if($session[user][superuser] >= 3)
            {
                if($row[richter2e] == 1)
                    output("`& Pro Kläger");
                else
                    if($row[richter1e] == 2)
                        output("`& Contra Kläger");
            }
            $sqlr = "SELECT name FROM accounts WHERE acctid=" . $row[richter3];
            $resultr = db_query($sqlr) or die(db_error(LINK));
            $rowr = db_fetch_assoc($resultr);
            output("`n`6Beisitzender Richter: " . $rowr[name]);
            if($session[user][superuser] >= 3)
            {
                if($row[richter3e] == 1)
                    output("`& Pro Kläger");
                else
                    if($row[richter3e] == 2)
                        output("`& Contra Kläger");
            }
            $sqlr = "SELECT name FROM accounts WHERE acctid=" . $row[klaeger];
            $resultr = db_query($sqlr) or die(db_error(LINK));
            $rowr = db_fetch_assoc($resultr);
            output("`n`n`6Kläger: " . $rowr[name] . "`n");
            $sqlr = "SELECT name FROM accounts WHERE acctid=" . $row[beklagter];
            $resultr = db_query($sqlr) or die(db_error(LINK));
            $rowr = db_fetch_assoc($resultr);
            output("`6Beklagter: " . $rowr[name] . "`n");
            $sqlr = "SELECT name FROM accounts WHERE acctid=" . $row[zeuge];
            $resultr = db_query($sqlr) or die(db_error(LINK));
            $rowr = db_fetch_assoc($resultr);
            output("`6Zeuge: " . $rowr[name] . "`n");
            $sqlr = "SELECT name FROM accounts WHERE acctid=" . $row[anwalt];
            $resultr = db_query($sqlr) or die(db_error(LINK));
            $rowr = db_fetch_assoc($resultr);
            output("`6Anwalt: " . $rowr[name] . "`n");
            output("`n`6Klage:`n" . $row[text] . "`n");

            if($_POST[pk] == 1)
            {
                if($session[user][acctid] == $row[richter1])
                    $r = "richter1e";
                if($session[user][acctid] == $row[richter2])
                    $r = "richter2e";
                if($session[user][acctid] == $row[richter3])
                    $r = "richter3e";
                    
                $sqlr = "UPDATE gericht SET $r = 1 WHERE gid=" . $row[gid];
                $resultr = db_query($sqlr) or die(db_error(LINK));
                $row[$r] = 1;
            }
            if($_POST[pb] == 1)
            {
                if($session[user][acctid] == $row[richter1])
                    $r = "richter1e";
                if($session[user][acctid] == $row[richter2])
                    $r = "richter2e";
                if($session[user][acctid] == $row[richter3])
                    $r = "richter3e";
                    
                $sqlr = "UPDATE gericht SET $r = 2 WHERE gid=" . $row[gid];
                $resultr = db_query($sqlr) or die(db_error(LINK));
                $row[$r] = 2;
            }

            if(($session[user][acctid] == $row[richter1] && $row[richter1e] == 0) || ($session[user][acctid] == $row[richter2] && $row[richter2e] == 0) || ($session[user][acctid] == $row[richter3]&& $row[richter3e] == 0))
            {
                output("<form action='gericht.php?op=beiwohnen&gid=". $_GET[gid]."' method='POST'><input name='pk' type=hidden value=1>
                <input type='submit' class='button' value='Pro Kläger'></form>
                ",true);
                addnav("","gericht.php?op=beiwohnen&gid=". $_GET[gid]);
                
                output("<form action='gericht.php?op=beiwohnen&gid=". $_GET[gid]."' method='POST'><input name='pb' type=hidden value=1>
                <input type='submit' class='button' value='Contra Kläger'></form>
                ",true);
                addnav("","gericht.php?op=beiwohnen&gid=". $_GET[gid]);
            }
            output("`n`6Protokoll:`n");
            
            if($_POST[ptext])
            {
                $sql = "INSERT INTO gericht_konversation VALUES ('0', " . $row[gid] . " , " . $session[user][acctid] . ", '".addslashes($_POST[ptext])."');";
                db_query($sql);
            }
            $sqlp = "SELECT * FROM gericht_konversation WHERE gid=".$row[gid];
            $resultp = db_query($sqlp) or die(db_error(LINK));
            while($rowp = db_fetch_assoc($resultp))
            {
                $sqlr = "SELECT name FROM accounts WHERE acctid=" . $rowp[acctid];
                $resultr = db_query($sqlr) or die(db_error(LINK));
                if($rowr = db_fetch_assoc($resultr))
                    output($rowr[name] . "`&: " . $rowp[text] . "`n`&");
                else
                    output("Unbekannt (".$rowp[acctid] . ")`&: " . $rowp[text] . "`n`&");
            }
            
            if($row[richter1e] == 0 || $row[richter2e] == 0 || $row[richter3e] == 0)
            {
                if(($session[user][acctid] == $row[richter1] || $session[user][acctid] == $row[richter2] || $session[user][acctid] == $row[richter3] || 
                $session[user][acctid] == $row[klaeger] || $session[user][acctid] == $row[beklagter] || $session[user][acctid] == $row[zeuge] || $session[user][acctid] == $row[anwalt]) && $row[status] == 1)
                {
                    
                    output("<form action='gericht.php?op=beiwohnen&gid=". $_GET[gid]."' method='POST'>
                    Zu Protokoll geben: <input name='ptext' maxlength=250>
                    <input type='submit' class='button' value='Sagen'>`n
                    </form>
                    ",true);
                    output("<a href=gericht.php?op=beiwohnen&gid=".$row[gid].">Aktualisieren</a>",true);
                    addnav("","gericht.php?op=beiwohnen&gid=". $_GET[gid]);
                }
            }
            else
            {
                if($_POST[utext])
                {
                    $sqlr = "UPDATE gericht SET urteil = '" . addslashes($_POST[utext]) . "', status = 2 WHERE gid=" . $row[gid];
                    $resultr = db_query($sqlr) or die(db_error(LINK));
                    $row[urteil] = $_POST[utext];
                }
                if($row[urteil] == "")
                {
                    output("`n`n`6Es wird auf das Urteil gewartet...`n");
                    if($session[user][acctid] == $row[richter1])
                    {
                        output("<form action='gericht.php?op=beiwohnen&gid=". $_GET[gid]."' method='POST'>
                        Urteil:`n <textarea name='utext' rows=5 cols=40></textarea>`n
                        <input type='submit' class='button' value='Urteil verkünden'>`n
                        </form>
                        ",true);
                        addnav("","gericht.php?op=beiwohnen&gid=". $_GET[gid]);
                    }
                }
                else
                    output("`n`n`&Das Urteil:`n".$row[urteil]);
            }
        }        
        addnav ("Aktualisieren","gericht.php?op=beiwohnen&gid=".$row[gid]);
        addnav ("Zurück","gericht.php?op=laufende");
    break;
    default:
    output("`@`c`b`xDas Gericht`b`c</font>",true);
     // output("`c<img src=\"images/Gericht.jpg\">`c","gericht.php",true);
        output ("`n`n`eDu befindest dich in der Haupthalle des Gerichthauses, die mächtige Justitia-Statue steht majestätisch vor dir. Ein riesiger prächtiger Kronleuchter ziert die Decke des Saales. Hier fällen die Richter ihr Urteil.`n
        An der Wand ist das Wappen den Patriziers `iGrigorie`i sowie ein Porträt von ihm und dem Großrat zu sehen.`n`n") ;
        addcommentary();
        viewcommentary("gericht","`vSprechen",20);
        addnav ("Laufende Verhandlungen","gericht.php?op=laufende");
        addnav ("Beendete Verhandlungen","gericht.php?op=beendete");
        addnav ("`\$Klagen`0","gericht.php?op=klagenv");
        if($session[user][superuser] >= 3){ 
    addnav ("ID-Anzeige","idwiz.php"); 
    addnav ("(ADMIN)Klage speichern","gericht.php?op=klage"); 
    }
        if ($session[user][superuser]>=3||$session[user][jobname]=="Richter/in" || $session[user][jobname]=="Staatsanwalt/in"){ 
        addnav("Besprechungszimmer","gerichtbesprech.php");}
        addnav("Zurück ins Dorf","village.php"); 
    break;
}
page_footer();
?>