<?php

//+-----------------------------------------+
//| BITTE DIESE BOX NICHT ENTFERNEN!!       |
//+-----------------------------------------+
//| Idee by: Lynera                         |
//| Umsetzung by: Draza´ar                  |
//| mail: drazaar@legend-of-vinestra.de     |
//| play: http://logd.legend-of-vinestra.de |
//| biobewertung.php v.1.0.8                |
//+-----------------------------------------+

/*
### INFO ###
Die Idee stammt von Lynera und wurde anfänglich von Draza´ar alleien gemacht. Geholfen haben später Eliwood und Blackfin. THX an dieser Stelle.
Das Ganze ist für Rollenspielserver gedacht, die schöne Bios von Spielern mit Donationpoints belohnen wollen. Das System sucht Spieler heraus, zeigt flexibel die Bio an und lässt die Punkte verteilen. Zudem schickt es dem User eine Mail und informiert ihn, dass seine Bio beurteilt wurde.


### EINBAU ###

SQL die Tabelle accounts um folgende Einträge ergänzen:

ALTER TABLE `accounts` ADD `biobew2` INT( 11 ) NOT NULL DEFAULT '0' ;
ALTER TABLE `accounts` ADD `biobewertdatum` DATETIME NOT NULL ;

----------------------------------------
ÖFFNE superuser.php

SUCHE:
addnav("Editoren");

füge DANACH ein:
if($session['user']['superuser']>=2) addnav("Biobewertungen","biobewertung.php");

SAVE, CLOSE & ULPLOAD.
Grad des Superusers kann man natürlich variieren...
----------------------------------------
Die Datei biobwertung.php ins root Verzeichnis laden!
----------------------------------------
ÖFFNE prefs.php

SUCHE:
  output("$msg");
  output("`nEinstellungen gespeichert");
}

füge DAVOR ein:
$session['user']['biotime']=date("Y-m-d H:i:s");

SAVE, CLOSE & UPLOAD.
----------------------------------------
ÖFFNE dragon.php

SUCHE:
,"name"=>1

füge DARUNTER ein:
,"biotime"=>1
,"biobewertdatum"=>1
,"biobew2"=>1

SAVE, CLOSE & UPLOAD.
----------------------------------------
*/

require_once "common.php";
isnewday(2);
page_header("Bio - Bewertungssystem");
output("`\$`b`cBIO BEWERTUNGEN`0`n`n`c`b");

// unnütze Farbcodeeinträge, etc müssen nicht mitgezählt werden ;)
if(!function_exists("stripcolors")){            //THX Blackfin
  function stripcolors($input) {
    $myout = preg_replace('/[`]./',"",$input);    
    return $myout ;
  }
}
//Funktion zur Unterteilung des Datums
if(!function_exists("getthisdate")){             //THX Blackfin
  function getthisdate($inputtext) {
    $now_day = date("d") ;
    $now_date = date("Y-m")."-".$now_day ;    
    $yes_day =   $now_day -1 ;    
    $two_days_before_day =   $now_day -2 ;    
    $yesterday_date = date("Y-m")."-".$yes_day;
    $two_days_before_date = date("Y-m")."-".$two_days_before_day;
    
    $this_poststring = $inputtext ;
    $this_tempdate = substr($this_poststring,0,10) ; 
    $this_temptime = substr($this_poststring,10,6) ; 
                   
    if($this_tempdate == $now_date) {
      $this_postdate = "`@Heute um ".$this_temptime."`0" ;
    }elseif($this_tempdate == $yesterday_date) { 
      $this_postdate = "`^Gestern um ".$this_temptime."`0" ;
    }elseif($this_tempdate == $two_days_before_date) { 
      $this_postdate = "`qVorgestern um ".$this_temptime."`0" ;    
    }else $this_postdate = date("d.m.Y u\m G:i",strtotime($this_poststring)) ;
    if($this_postdate == "30.11.1999 um 0:00") $this_postdate = "`\$ unbekannt`0" ;
    return $this_postdate ;
  }
}

$max = 75; #                                    <====maximale Donationpunkte, welcheman vergeben kann.

// (Permanente) Variablen zum ordnen der Tabellen
if(!isset($_GET[order])){
    $order = "acctid";
    $dir = "ASC";
    $ordbef = "acctid";
}else{
    $order = $_GET[order];
    $ordbef = $_GET[ordbef];
    $dir = $_GET[dir];
    if($ordbef==$order && $dir=="ASC" && $_GET[change]==1) $dir = "DESC" ;
    elseif($ordbef==$order && $dir=="DESC" && $_GET[change]==1) $dir = "ASC" ;
    $ordbef =  $order ;
    if(isset($_GET[islen]))  $order = " LENGTH(".$_GET[order].") " ;
}

//Seiten
$playersperpage = 50;
if($_GET[op]=="ungep"){
  $sql = "SELECT count(acctid) AS c FROM accounts WHERE biobew2<1";
}
elseif($_GET[op]=="gep"){
  $sql = "SELECT count(acctid) AS c FROM accounts WHERE biobew2>0";
}
$result = db_query($sql);
$row = db_fetch_assoc($result);
$totalplayers = $row['c'];
$page = 1;
if ($_GET['page']) $page = (int)$_GET['page'];
$pageoffset = $page;
if ($pageoffset > 0) $pageoffset--;
$pageoffset *= $playersperpage;
$from = $pageoffset+1;
$to = min($pageoffset+$playersperpage, $totalplayers);
$limit = "$pageoffset,$playersperpage";

$page = $_GET[page];

// Navigation (permanent)
addnav("--Auswahl--");
addnav("Nicht bewertete Bios","biobewertung.php?op=ungep");
addnav("Bewertete Bios","biobewertung.php?op=gep");
addnav("--Suchen--");
addnav("Spieler suchen","biobewertung.php?op=suchen");
addnav("--Zurück--");
addnav("Zurück zur Grotte","superuser.php");
addnav("Zurück zum Weltlichen","village.php");

if($_GET[op]=="suchen"){        
        output("`n`n`c`b`7Spieler suchen`0`b`c");
        output("`c<form action='biobewertung.php?op=search' method='POST'>Nach Name suchen: <input name='name'><input type='submit' class='button' value='Suchen'></form>`c",true);
        addnav("","biobewertung.php?op=search");
}
elseif($_GET[op]=="ungep" || $_GET[op]=="gep" || $_GET[op]=="search"){
        if($_GET[op]=="search"){
                output("`n`n`c`b`7Spieler suchen`0`b`c`n");
                $_POST['name'] = stripslashes($_POST['name']); 
                $StrLen = StrLen($_POST['name']); 
                $who = ''; 
                for($i = 0; $i < $StrLen; $i++) { 
                    $who .= '%'.$_POST['name']{$i};
                } 
                $who .= '%'; 
                $who = mysql_real_escape_string($who);
                //output('`$Debug: `0'.$who);
                $sql = " SELECT acctid,name,biobew2,bio,biotime FROM accounts WHERE locked=0 AND name LIKE '%$who%' ORDER by acctid ASC";
                $result = db_query($sql) or die(sql_error($sql));
                if(db_num_rows($result)<1){ 
                        output("Keine Spieler vorhanden");
                }
        }
        elseif($_GET[op]=="gep"){
                output("`n`n`c`b`7Bereits bewertete Spieler`b`c`n`n");
                $link = "biobewertung.php?op=gep&dir=".$dir."&ordbef=".$ordbef."&order=";
                $sql = "SELECT name,biobew2,bio,biotime,acctid,biobewertdatum FROM accounts WHERE biobew2>0 ORDER BY ".$order." ".$dir." LIMIT $limit";
                $result = db_query($sql);
        }
        elseif($_GET[op]=="ungep"){
                output("`n`n`c`b`7Noch unbewertete Spieler`b`c`n`n");
                $link = "biobewertung.php?op=ungep&dir=".$dir."&ordbef=".$ordbef."&order=";
                $sql = "SELECT name,biobew2,bio,biotime,acctid FROM accounts WHERE biobew2<1 ORDER BY ".$order." ".$dir." LIMIT $limit";
                $result = db_query($sql) or die(sql_error($sql));
        }
        
        if($_GET[op]!="search"){
                if($order=="acctid") $what = "ID";
                elseif($order=="login") $what = "Name";
                elseif($order==" LENGTH(".$_GET[order].") ") $what = "Biolänge";
                elseif($order=="biobew2") $what = "bekommenen Punkten";
                elseif($order=="biobewertdatum") $what = "Bewertungsdatum";
                elseif($order=="biotime") $what = "zuletzt bearbeiteten Bios";
                $dirwort = ($dir == ASC)?"Aufsteigend":"Absteigend";        //Übersicht
                output("`c`7".strtoupper($dirwort)."`& sortiert nach `7".strtoupper($what)."`&`c`n`n`n",true) ;
        }
        if($_GET[op]=="search" && db_num_rows($result)>25){ 
                output("`c`b`7Zu viele Suchergebnisse. Suche bitte eingrenzen!");
        }else{
                output("`c<table border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);
                output("<tr class='trhead'>
                          <td align='center'>
                            ".($_GET[op]=="search"?"<b>ID</b>":"<a href='".$link."acctid&change=1&page=$page'><b>ID</b></a>")."
                          </td><td align='center'>
                            ".($_GET[op]=="search"?"<b>Name</b>":"<a href='".$link."login&change=1&page=$page'><b>Name</b></a>")."
                          </td><td align='center'>
                            ".($_GET[op]=="search"?"<b>Wortanzahl</b>":"<a href='".$link."bio&islen=1&change=1&page=$page'><b>Wortanzahl</b></a>")."
                          </td><td align='center'>
                            ".($_GET[op]=="search"?"<b>Zuletzt geupdated</b>":"<a href='".$link."biotime&change=1&page=$page'><b>Zuletzt geupdated</b></a>")."
                          </td>
                ",true);
                if($_GET[op]=="search") output("<td align='center'>
                                                  <b>`&Bewertet?</b>
                                                </td>
                                        ",true);
                if($_GET[op]=="ungep" || $_GET[op]=="search")  output("<td align='center'>
                                                                          <b>ansehen/bewerten</b>
                                                                        </td>
                                                                ",true);
                if($_GET[op]=="gep")  output("<td align='center'>
                                                <a href='".$link."biobew2&change=1&page=$page'><b>Vergebene Punkte</b></a>
                                              </td><td align='center'>
                                                <a href='".$link."biobewertdatum&change=1&page=$page'><b>Bewertet am:</b></a>
                                              </td><td align='center'>
                                                <b>Ansehen und/oder`nneu bewerten</b>
                                              </td>
                                      ",true);
        
                output("</tr>",true);
                addnav("",$link."acctid&change=1&page=$page");
                addnav("",$link."login&change=1&page=$page");
                addnav("",$link."bio&islen=1&change=1&page=$page");
                addnav("",$link."biotime&change=1&page=$page");
                addnav("",$link."biobewertdatum&change=1&page=$page");
                addnav("",$link."biobew2&change=1&page=$page");
                for($i=0;$i<db_num_rows($result);$i++){
                    $row = db_fetch_assoc($result);        
                    $biocount = str_word_count(stripcolors($row['bio']));
                    $biotime = getthisdate($row['biotime']);
                    $zeit = getthisdate($row[biobewertdatum]);
                    output("<tr class='".($i%2?"trdark":"trlight")."'>
                              <td align='center'>
                                ".$row[acctid]."
                                </td><td>
                                ".$row[name]."
                              </td><td align='center'>
                                `^".$biocount."
                              </td><td align='center'>
                                ".$biotime."
                              </td>
                    ",true);    
                    if($_GET[op]=="search") output("<td align='center'>
                                                      ".($row[biobew2]>0?"`@Ja":"`4Nein")."
                                                    </td>
                                            ",true);
                    if($_GET[op]=="ungep" || $_GET[op]=="search")  output("<td align='center'>
                                                                              <a href='biobewertung.php?op=bio&userid=$row[acctid]'>Bio</a>
                                                                            </td>
                                                                    ",true);
                    if($_GET[op]=="gep")  output("<td align='center'>
                                                    ".$row[biobew2]."
                                                  </td><td align='center'>
                                                    ".$zeit."
                                                  </td><td align='center'>
                                                    <a href='biobewertung.php?op=bio&userid=$row[acctid]'>Bio</a>
                                                  </td>
                                          ",true);
            
                    addnav("","biobewertung.php?op=bio&userid=$row[acctid]");
                }
                output("</table>",true);
        }        
        if($_GET[op]!="search"){
                addnav("--Seiten--");
                for($i = 0; $i < $totalplayers; $i+= $playersperpage){
                    $pnum = ($i/$playersperpage+1);
                    $min = ($i+1);
                    $max = min($i+$playersperpage,$totalplayers);
                    if($_GET[op]=="gep") addnav("Seite $pnum ($min-$max)", "biobewertung.php?op=gep&dir={$dir}&ordbef={$ordbef}&order={$order}&page={$pnum}");
                    if($_GET[op]=="ungep") addnav("Seite $pnum ($min-$max)", "biobewertung.php?op=ungep&dir=$dir&ordbef=$ordbef&order=$order&page=$pnum");
                }
        }
}
elseif($_GET[op]=="bio"){
        $bio = $_GET[userid];
        $sql = "SELECT name,bio,acctid,biobew2 FROM accounts WHERE acctid='$bio'";
        $result = db_query($sql) or die(sql_error($sql));
        $row = db_fetch_assoc($result);
    
        output("`c`\$Bio von $row[name]`0`c`n`n`n"); 
        output("`c<table border=0 cellpadding=2 cellspacing=1 bgcolor='#000000'>",true);
        output("<tr class='trhead'>
                  <b>INFOS</b>
                </tr>
        ",true);
        for($i=0;$i<db_num_rows($result);$i++){
            output("<tr class='".($i%2?"trdark":"trlight")."'>
                      <td>
                        <b>`&NAME:`0</b></td><td>`&$row[name]`0</td></tr>
                    <tr>
                      <td>
                        <b>`@BEWERTET?</b></td>
                      <td>`%".($row[biobew2]>0?"`@JA!":"`\$NEIN!`0 <a href='biobewertung.php?op=bew&userid={$row[acctid]}'>Als bewertet eintragen!</a>")."
                      </td>
                    </tr>
            ",true);
            addnav("","biobewertung.php?op=bew&userid={$row[acctid]}");
            output("<tr><td><b>`&Bewerten?</b></td><td><form action='biobewertung.php?op=hinz1&userid=$row[acctid]' method='POST'>
            ",true);
            addnav("","biobewertung.php?op=hinz1&userid=$row[acctid]");
            output("<input name='amt' size='3'>
                    <input type='submit' class='button' value='Donation hinzufügen'>
                    </form>
                    </td></tr>
                    <tr>
                      <td>
                        <b>`&BIO:`0</b>
                      </td>
                      <td>
            ",true);
            if($row[bio]!="") output(stripslashes("`c$row[bio]`c"),true);
            else output("`^`cKeine Biographie!`c`0");     //Hat keine Bio Oo
            output("</td></tr>",true);
        }
    output("</table>",true);
}
elseif($_GET[op]=="hinz1"){
        if($_POST[amt]>$max){               //Keine Spieler bevorzugen ;)
                output("`7Findest du es nicht übertrieben für eine schöne Bio mehr als ".$max." Donationpunkte zu geben??");
        }else{
                $bio = $_GET[userid];
                $sql = "SELECT name,acctid,biobew2 FROM accounts WHERE acctid='$bio'";
                $result = db_query($sql);
                $row = db_fetch_assoc($result);
                $amt = $_POST[amt]*(-1);
                if($_POST[amt]>0){
                        output("`7`bWillst du wirklich ".$row[name]."`7's Bio mit `^".$_POST[amt]."`7 Donationpunkten belohnen?`n`n");
                        output("<a href='biobewertung.php?op=hinz2&userid=$row[acctid]&amt=$_POST[amt]'>JA! ".$_POST[amt]." Donationpunkte an ".$row[name]."</a>",true);
                        addnav("","biobewertung.php?op=hinz2&userid=".$row[acctid]."&amt=".$_POST[amt]."");
                }elseif($_POST[amt]<0 && $amt<=$row[biobew2]){
                        output("`7`bWillst du wirklich ".$row[name]." `7für seine Bio `^".$amt." `7Punkte abziehen?`n`n");
                        output("<a href='biobewertung.php?op=hinz2&userid=$row[acctid]&amt=$_POST[amt]'>JA! ".$row[name]." `^".$amt." `0Donationpunkte abziehen.</a>",true);
                        addnav("","biobewertung.php?op=hinz2&userid=".$row[acctid]."&amt=".$_POST[amt]."");
                }elseif($_POST[amt]<0 && $amt>$row[biobew2]){
                        output("Es wäre doch unfair ".$row[name]." für seine Bio mehr abzuziehen, als er überhaupt bekommen hätte, nicht?");
                }elseif($_POST[amt]==0){
                        output("`7`b0 Punkte zu vergeben wäre unsinnig!!");
                }
        }
}
elseif($_GET[op]=="hinz2"){
        //Wenn der Punkteverteiler sich selbst belohnen will...
        if($session[user][acctid]==$_GET[userid]){
                $session[user][donation]+=$_GET[amt];
                $session[user][biobew2]+=$_GET[amt];
                $session[user][biobewertdatum]=date('Y-m-d H:i:s');
        }
        $bio = $_GET[userid];
        $sql = "UPDATE accounts SET "."biobew2=biobew2+".intval($_GET[amt]).", "."donation=donation+".intval($_GET[amt])." WHERE acctid = ".intval($bio);
        db_query($sql);    
        $sql = "SELECT name,acctid,donation,donationspent,biobew2 FROM accounts WHERE acctid='$bio'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $amt = $_GET[amt]*(-1);
        $donation = $row[donation] - $row[donationspent];
        if($_GET[amt]>0){
                output("`7Du hast erfolgreich `^".$_GET[amt]."`7 Donationpunkte an ".$row[name]."`7 übertragen! ".($row[biobew2]==$_GET[amt]?"Die Bio wurde bewertet und als bewertet eingetragen.":"")."");
                if($row[biobew2]==$_GET[amt]) systemmail($row['acctid'],"`^Deine Bio wurde bewertet!`0","`QDie Administration`6 hat deine Bio bewertet. Du hast `^".$_GET[amt]."`6 Donationpunkte bekommen.`n`nMfG das `QAdmin `6- Team.");
                else systemmail($row['acctid'],"`^Erneute Biobewertung!`0","`QDie Administration`6 hat deine Bio nochmals bewertet. Du hast `^".$_GET[amt]."`6 Donationpunkte hinzubekommen.`n`nMfG das `QAdmin `6- Team.");
        }
        elseif($_GET[amt]<0 && $donation >= $_GET[amt]){
                output("`7Du hast ".$row[name]." erfolgreich `^".$amt."`7 Donationpunkte abgezogen.");
                systemmail($row['acctid'],"`^Punkteabzug wegen Bio`0","`QDie Administration`6 hat deine Bio erneut bewertet. Leider fanden sie die Biographie nicht mehr so schön wie zuvor, weshalb du einen Punkteabzug von `^".$amt." `6Punkten bekommen hast.`n`nMfG das `QAdmin `6- Team.");
        }
        
        $sql ="UPDATE accounts SET biobewertdatum = '".date('Y-m-d H:i:s')."' WHERE acctid='$bio'";
        db_query($sql);
}
elseif($_GET[op]=="bew"){
        if($session[user][acctid]==$_GET[userid]){
                $session[user][biobew2]=1; //Falls man sich selbst einträgt
                $session[user][biobewertdatum]= date('Y-m-d H:i:s');
        }
        $sql = "UPDATE accounts SET biobew2='1' WHERE acctid='$_GET[userid]'";
        db_query($sql);
        
        $sql = "SELECT name FROM accounts WHERE acctid='$_GET[userid]'";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        output("`7Du hast erfolgreich $row[name]`7's Bio als bewertet eingetragen!");

        $sql ="UPDATE accounts SET biobewertdatum = '".date('Y-m-d H:i:s')."' WHERE acctid='$bio'";
        db_query($sql);
}
page_footer();
?> 