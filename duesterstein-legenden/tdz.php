
<?
require_once("common.php");
if ($session[user][locate]!=49){
    $session[user][locate]=49;
    redirect("tdz.php");
}
   page_header("Dunkler Turm des Zweikampfs");

if ($_GET[op]=="") {
    addcommentary();
    checkday();
    
    $sql="SELECT * FROM tdz_spieler WHERE tdzplaid='".$session[user][acctid]."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    
    output("`b`c`2Die Eingangshalle des Turms`0`c`b");
    //output("`n`c<img src='gfx/Feuer100.gif' border='0'>`c`n",true);
    output("`n`%Du trittst in eine kleine Halle. Hinter einem kleinen Holztisch sitzt eine
            kleine Kreatur mit einem Monokel und schaut dich fragend an.`0");
    //output("`n");
    output("`n`n`QDu siehst aus als hättest du Mut genug um im Turm der Zweikämpfe deine Waffe
           gegen andere mutige Krieger erheben zu können. Oder täusche ich mich?`0");
    output("`n`n`%Die kleine Kreatur schaut dich noch einen Moment an und steckt seine Nase dann
           in einige Pergamente die sich auf dem Tisch befinden.`0");
    output("`$ `n`n****BETAPHASE****`nDieses Modul steckt in der Betaphase und ist nur teilweise verfügbar`n`n");
    viewcommentary("tdz","Hier reden",30,"sagt");
   
       if (db_num_rows($result)==0) {
       addnav("Ein Duell eröffnen","tdz.php?op=oeffnen");
       addnav("Ein Duell beitretten","tdz.php?op=beitreten");
       }else{
             if ($session[user][tdzbadguy] >= 1 || $session[user][tdzgoodguy] >= 1){
             }else{
             addnav("In die Duellhalle","tdz.php?op=duell&id=$row[tdzspielid]");
             }
       }
    addnav("Verkaufshalle","tdz.php?op=verkauf");
    addnav("Ein Duell anschauen","tdz.php?op=schau");
    addnav("Turm verlassen","village.php");
}else if ($_GET['op']=="verkauf"){

    output("`%Ein kleines Mädchen mit Windpocken schaut über eine, nicht viel grössere, Holztheke.
    Als sie dich entdeckt strahlen ihre kleinen Augen und schaut dich bewundernt an. Leise spricht
    sie zu dir: `n`n`0");
    output("`QHallohoooooooo duhu. Möchtest du dir etwas kaufen...lecker Hami Hami? Oder möchtest
    du ein weiteres Ticket kaufen um in der Arena zu kämpfen. Es kostet nur 5 Waldkämpfe. Na was
    möchtest du denn??? `n`n`0");
    output("`%Sie schaut dich fragend an und spielt mit einer kleinen Puppe die sie in ihren Händen trägt.
    Sie scheint abwartend zu sein, ein wenig nachdenklich.`n`n`0");
                 if ($session[user][tdzbadguy] >= 1 || $session[user][tdzgoodguy] >= 1){
            addnav("Ticket für Waldkämpfe kaufen","tdz.php?op=kaufzwei");
        }else{
        output("`4OHHHH...es ist gar nix da was ich dir verkaufen kann!`0 *sie schaut dich traurig an*");
        }
    addnav("Zurück","tdz.php");
}else if ($_GET['op']=="kaufzwei"){

      $sql="SELECT * FROM tdz_spieler WHERE tdzplaid='".$session[user][acctid]."'";
      $result = db_query($sql) or die(db_error(LINK));
      $row = db_fetch_assoc($result);
      $name1=$session[user][name];

    if ($session[user][turns] <= 4){
            output("`%´Du hast leider nicht genug Waldkämpfe um dir ein weiteres Ticket zu leisten. Um
            weiter in der Arena zu kämpfen musst du wohl bis zum nächsten Tag warten.`n`n`0");
    }else{
            output("`%´Du kaufst dir ein neues Ticket und kannst nun wieder in der Arena kämpfen`n`n`0");
            $session[user][turns]=$session[user][turns]-5;
            $session[user][tdzbadguy]=0;
            $session[user][tdzgoodguy]=0;
            $sql7 = "INSERT INTO tdz_commands (tdzspielnr,tdzspielcom,tdzwann) VALUES ('$row[tdzspielid]','`@Gerade kaufte sich $name1 ein neues `5Ticket `@für die Kampfarena',now())";
            db_query($sql7);
    }

    addnav("zurück","tdz.php");
}else if ($_GET['op']=="schau"){

    output("`n`n`%Du gehst in den Zuschauerraum. Dort stehen folgende Duelle:`n`n`0");

        $sql="SELECT * FROM tdz_spiele WHERE tdzspianz=tdzstatus ORDER BY tdznamegame ASC";
    output("<table cellspacing=0 cellpadding=2 align='left' border=1><tr><td width='200'>`b Name des Duells `b</td><td>`b Duellleiter`b</td><td>`b Krieger`b</td><td>`b Option`b</td>",true);
        if($session[user][superuser]>=4){
        output("<td>`c`bAdmin`b`c</td>",true);
        }
        output("</tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
              output("<tr><td colspan=4 align='center'>`&`i-Keine offenen Duelle-`i`0</td></tr>",true);

        }
        for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
             $sql2 = "SELECT name FROM accounts WHERE acctid='".$row[tdzpla]."'";
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td align='left' width='200'>$row[tdznamegame]</td><td>`&$row2[name]`0</td><td>$row[tdzspianz]`&</td><td align='center'><a href='tdz.php?op=schauzwei&id=".urlencode($row['tdzgameid'])."'>Duell anschauen</a></td>",true);
                if($session[user][superuser]>=4){
                output("<td><a href='tdz.php?op=loeschduell01&id=".urlencode($row['tdzgameid'])."' onClick='return confirm(\"Willst du das Duell wirklich löschen?\");'>`cEntfernen`c</a></td>",true);
                addnav("","tdz.php?op=loeschduell01&id=".urlencode($row['tdzgameid'])."");
                }
        output("</tr>",true);
        addnav("","tdz.php?op=schauzwei&id=".urlencode($row['tdzgameid'])."");
    }
    output("</table><br><br><br><br>",true);

        if (db_num_rows($result)==0) output("`n`n`QIhr seht ja selber. Nichts da wo ihr euch als Krieger beweisen könntet.`0");

    addnav("zurück","tdz.php");
}
else if ($_GET[op]=="loeschduell01") {
  $sql = "DELETE FROM tdz_commands WHERE tdzspielnr='{$_GET[id]}'";
        db_query($sql);
  $sql = "DELETE FROM tdz_spiele WHERE tdzgameid='{$_GET[id]}'";
        db_query($sql);
  $sql = "DELETE FROM tdz_spieler WHERE tdzspielid='{$_GET[id]}'";
        db_query($sql);
        redirect("tdz.php?op=schau");
}
else if ($_GET['op']=="schauzwei"){
    $sql="SELECT * FROM tdz_spiele WHERE tdzgameid='{$_GET[id]}'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

    output("`n`n`%Du gehst in die Duellarena:`n`0");

        $sql4="SELECT * FROM tdz_spieler WHERE tdzspielid='{$_GET[id]}' ORDER BY tdzplaid ASC";
         $result4 = db_query($sql4) or die(db_error(LINK));
        $nummer=1;
        
        $sql5="SELECT * FROM tdz_commands WHERE tdzspielnr='{$_GET[id]}' ORDER BY tdzwann ASC";
         $result5 = db_query($sql5) or die(db_error(LINK));

               output("`n`n`%Du schaust dich ersteinmal um wer wohl in diesem Duell gegeneinander Kämpft. Als du dir
               den ersten Überblick verschafft hast beginnst du deinen favoriten anzufeuern.`n`n`c`b`@Die Krieger:`b`c`n`0");
                output("<table cellspacing=0 cellpadding=2 align='center' border=1><tr><td>`b Nummer `b</td><td>`b Name des Kriegers`b</td><td>`b Schärpenfarbe`b</td></tr>",true);
                for ($i=0;$i<db_num_rows($result4);$i++){
                $row4 = db_fetch_assoc($result4);
             $sql2 = "SELECT name FROM accounts WHERE acctid='".$row4[tdzplaid]."'";
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
                $ergebnis[]="$row4[tdzsterne]";
                $farbe[]="$row4[tdzplaycolor]";
                output("<tr class='".($i%2?"trlight":"trdark")."'><td align='center'>$nummer</td><td>$row2[name]`0</td><td bgcolor=$row4[tdzplaycolor]>&nbsp;</td>",true);
                $nummer=$nummer+1;
                }
                output("</table><br>",true);

                $sql9="SELECT * FROM tdz_spieler WHERE tdzspielid='{$_GET[id]}' ORDER BY tdzsterne DESC";
                 $result9 = db_query($sql9) or die(db_error(LINK));

                output("`c`b`@Das derzeitige Ergebnis:`b`c`n`0");

                output("<table cellspacing=0 cellpadding=0 align='center' border=0 align='center'><tr>",true);
                for ($i=0;$i<db_num_rows($result9);$i++){
                    $row9 = db_fetch_assoc($result9);
                output("<tr><td>",true);
                output("<table cellspacing=0 cellpadding=2 align='left' border=0 width=$row9[tdzsterne]><tr><td width=$row9[tdzsterne] bgcolor=$row9[tdzplaycolor]>$row9[tdzsterne]</td></tr>",true);
                output("</table>",true);
                output("</tr></td>",true);
                }
                output("</table>",true);
                output("`n`n`c`b`@Die Ereignisse:`b`c`n`0");

                for ($i=0;$i<db_num_rows($result5);$i++){
                $row5 = db_fetch_assoc($result5);
                //output("`c");
                output($row5[tdzspielcom]);
                output("`n");
                //output("`c");
                }
                
    addnav("Zurück","tdz.php?op=schau");
}
else if ($_GET['op']=="duell"){
    $sql="SELECT * FROM tdz_spiele WHERE tdzgameid='{$_GET[id]}'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

    output("`n`n`%Du gehst in die Duellarena:`n`0");

        $sql4="SELECT * FROM tdz_spieler WHERE tdzspielid='{$_GET[id]}' AND tdzplaid<> '".$session['user']['acctid']."' ORDER BY tdzplaid ASC";
         $result4 = db_query($sql4) or die(db_error(LINK));
      
        if ($row[tdzstatus] < $row[tdzspianz]) {
        output("`n`n`2Leider kann das Duell noch nicht beginnen da noch Anmeldungen austehen.
        Von den zu vergebenden `%".$row[tdzspianz]." `2Plätzen haben sich folgende Krieger,
        zusätzlich zu dir, angemeldet:`n`0");
            for ($i=0;$i<db_num_rows($result4);$i++){
                $row4 = db_fetch_assoc($result4);
             $sql2 = "SELECT name FROM accounts WHERE acctid='".$row4[tdzplaid]."'";
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
        output("`n");
        output($row2[name]);
            }
        output("`n`n`2Möchtest du das Duell vielleicht chanceln? Habe sicher alle Angst vor dir und trauen sich nicht dich herrauszufordern`n`0");
                output("<a href='tdz.php?op=loeschduell01&id=".urlencode($row['tdzgameid'])."' onClick='return confirm(\"Willst du das Duell wirklich löschen?\");'>`cDuell löschen`c</a>",true);
                addnav("","tdz.php?op=loeschduell01&id=".urlencode($row['tdzgameid'])."");
         }else{
               output("`n`n`%Das Turnier hat begonnen!! Du schaust dich in der Arena um und entdeckst
               deine Gegner. Du packst deine Waffen und überlegst welchen der Krieger du angreifen möchtest.`n`n`0");
                output("<table cellspacing=0 cellpadding=2 align='center' border=1><tr><td>`b Bild `b</td><td>`b Name des Kriegers`b</td><td>`b Schärpenfarbe`b</td><td>`b Schädel`b</td><td>`b Optionen`b</td></tr>",true);
                for ($i=0;$i<db_num_rows($result4);$i++){
                $row4 = db_fetch_assoc($result4);
             $sql2 = "SELECT name FROM accounts WHERE acctid='".$row4[tdzplaid]."'";
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
                output("<tr class='".($i%2?"trlight":"trdark")."'><td align='center'>Kein Bild</a></td><td>$row2[name]`0</td><td bgcolor=$row4[tdzplaycolor]>&nbsp;</td><td align='center'>$row4[tdzsterne]</td><td><a href='tdz.php?op=attack&id=".urlencode($row['tdzgameid'])."&id2=".urlencode($row4['tdzplaid'])."'>Angreifen!!!</a></td>",true);
                addnav("","tdz.php?op=attack&id=".urlencode($row['tdzgameid'])."&id2=".urlencode($row4['tdzplaid'])."");
                }
                output("</table>",true);
         }
    addnav("Zurück","tdz.php");
}
else if ($_GET['op']=="attack"){
               $sql = "SELECT name FROM accounts WHERE acctid='".$_GET[id2]."'";
           $result = db_query($sql) or die(db_error(LINK));
               $row = db_fetch_assoc($result);

               $sql4="SELECT * FROM tdz_spieler WHERE tdzplaid='{$_GET[id2]}'";
               $result4 = db_query($sql4) or die(db_error(LINK));
               $row4 = db_fetch_assoc($result4);

               $sql5="SELECT * FROM tdz_spieler WHERE tdzplaid='".$session[user][acctid]."'";
               $result5 = db_query($sql5) or die(db_error(LINK));
               $row5 = db_fetch_assoc($result5);

               $bild1="Kein Bild";
               $name1=$session[user][name];
               $punkte1=$session[user][tdzgoodguy];
               $punkte2=$session[user][tdzbadguy];
               

               output("`n`n`c`b`@Der derzeitige Stand des Duells:`c`b`n`0");
               output("<table cellspacing=0 cellpadding=2 align='center' border=1><tr><td>`bBild des Angreifers`b</td><td>`bName des Angreifers`b</td><td>`bSiege/Angreifer`b</td><td>`bSiege/Verteidiger`b</td><td>`bName des Verteidigers`b</td><td>`bBild des Verteidigers`b</td></tr>",true);
               output("<tr><td align='center'><img src='images/buddybildas/$bild1' border='0' alt=''></td><td align='center'>$name1</td><td align='center'><font size='+2'>$punkte1</size></td><td align='center'><font size='+2'>$punkte2</size></td><td align='center'>$row[name]`0</td><td align='center'>Kein Bild</td></tr>",true);
               output("</table>",true);
               
               if ($session[user][tdzgoodguy] >= 3){
               output("`n`n`c`b`@Du gewinnst`c`b`n`0");
               //$sql9 = "UPDATE tdz_spieler SET tdzsterne = tdzsterne+1 WHERE tdzplaid='".$session[user][acctid]."'";
               //db_query($sql9) or die(db_error(LINK));;
               $sql8 = "UPDATE tdz_spieler SET tdzsterne = tdzsterne-2 WHERE tdzplaid='{$_GET[id2]}'";
               db_query($sql8) or die(db_error(LINK));;
               $sql7 = "INSERT INTO tdz_commands (tdzspielnr,tdzspielcom,tdzwann) VALUES ('$row4[tdzspielid]','`@Heute gewann $name1 `@gegen $row[name] `@mit dem Ergebnis `2$punkte1:$punkte2',now())";
               db_query($sql7);
               $sql4="SELECT * FROM tdz_spieler WHERE tdzplaid='{$_GET[id2]}'";
               $result4 = db_query($sql4) or die(db_error(LINK));
               $row4 = db_fetch_assoc($result4);
                               if ($row4[tdzsterne] == 0){
                                      output("`n`n`c`b`@_________ENDE__________`c`b`n`0");
                                      $sql9 = "UPDATE tdz_spiele SET tdzspianz = tdzspianz-1 WHERE tdzgameid='$row4[tdzspielid]'";
                                      db_query($sql9) or die(db_error(LINK));;
                                      $sql9 = "UPDATE tdz_spiele SET tdzstatus = tdzstatus-1 WHERE tdzgameid='$row4[tdzspielid]'";
                                      db_query($sql9) or die(db_error(LINK));;
                                      $sql = "DELETE FROM tdz_spieler WHERE tdzplaid='{$_GET[id2]}'";
                                      db_query($sql);
                                      $sql6="SELECT * FROM tdz_spieler WHERE tdzspielid='$row4[tdzspielid]'";
                                      $result6 = db_query($sql6) or die(db_error(LINK));
                                      $row6 = db_fetch_assoc($result6);
                                            if (db_num_rows($result)==1){
                                                    output("`n`n`c`b`@Duell zuende`c`b`n`0");
                                            }
                               }
               addnav("Zurück","tdz.php");
               }else if ($session[user][tdzbadguy] >= 3){
               output("`n`n`c`b`@Du verlierst`c`b`n`0");
               $sql9 = "UPDATE tdz_spieler SET tdzsterne = tdzsterne-1 WHERE tdzplaid='".$session[user][acctid]."'";
               db_query($sql9) or die(db_error(LINK));;
               //$sql8 = "UPDATE tdz_spieler SET tdzsterne = tdzsterne+1 WHERE tdzplaid='{$_GET[id2]}'";
               //db_query($sql8) or die(db_error(LINK));;
               $sql7 = "INSERT INTO tdz_commands (tdzspielnr,tdzspielcom,tdzwann) VALUES ('$row4[tdzspielid]','`@Heute verlor $name1 `@gegen $row[name] `@mit dem Ergebnis `2$punkte1:$punkte2',now())";
               db_query($sql7);
               $sql5="SELECT * FROM tdz_spieler WHERE tdzplaid='".$session[user][acctid]."'";
               $result5 = db_query($sql5) or die(db_error(LINK));
               $row5 = db_fetch_assoc($result5);
               output("Hier : ".$row5[tdzsterne]." Schädel");
                               if ($row5[tdzsterne] == 0){
                                      output("`n`n`c`b`@_________ENDE__________`c`b`n`0");
                                      $sql9 = "UPDATE tdz_spiele SET tdzspianz = tdzspianz-1 WHERE tdzgameid='$row5[tdzspielid]'";
                                      db_query($sql9) or die(db_error(LINK));;
                                      $sql9 = "UPDATE tdz_spiele SET tdzstatus = tdzstatus-1 WHERE tdzgameid='$row5[tdzspielid]'";
                                      db_query($sql9) or die(db_error(LINK));;
                                      $sql = "DELETE FROM tdz_spieler WHERE tdzplaid='".$session[user][acctid]."'";
                                      db_query($sql);
                                      $sql6="SELECT * FROM tdz_spieler WHERE tdzspielid='$row5[tdzspielid]'";
                                      $result6 = db_query($sql6) or die(db_error(LINK));
                                      $row6 = db_fetch_assoc($result6);
                                            if (db_num_rows($result)==1){
                                                    output("`n`n`c`b`@Duell zuende`c`b`n`0");
                                            }
                               }
               addnav("Zurück","tdz.php");
               }else{
               output("`n`n`c`b`@Wähle deine Waffe`c`b`n`0");
               output("<table cellspacing=0 cellpadding=2 align='center' border=10><tr><td><a href='tdz.php?op=attackzwei&id=1&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/arm.jpg' border='0' alt=''></a></td><td><a href='tdz.php?op=attackzwei&id=2&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/schwert.jpg' border='0' alt=''></a></td><td><a href='tdz.php?op=attackzwei&id=3&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/kriegshammer.jpg' border='0' alt=''></a></td></tr>",true);
               output("</table>",true);
               output("<table cellspacing=0 cellpadding=2 align='center' border=10><tr><td><a href='tdz.php?op=attackzwei&id=4&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/morgenstern.jpg' border='0' alt=''></a></td><td><a href='tdz.php?op=attackzwei&id=5&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/axt.jpg' border='0' alt=''></a></td></tr>",true);
               output("</table>",true);
               addnav("","tdz.php?op=attackzwei&id=1&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id=2&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id=3&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id=4&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id=5&id2=".urlencode($row4['tdzplaid'])."");

               $sql9="SELECT * FROM tdz_spieler WHERE tdzplaid='".$session[user][acctid]."'";
               $result9 = db_query($sql9) or die(db_error(LINK));
               $row9 = db_fetch_assoc($result9);

               for ($muhcount=1;$muhcount<6;$muhcount++) {
                   if ($muhcount == 1){
                      if ($row9[tdzspec01] == 0){
                         $specialbild="<img src='images/sss/leer.jpg' border='0' alt=''>";
                      }else if ($row9[tdzspec01] == 1){
                          $slot=1;
                         $specialbild="<a href='tdz.php?op=attackzwei&id3=1&id=6&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/zschwert.jpg' border='0' alt=''></a>";
                      }
                   }
                   if ($muhcount == 2){
                      if ($row9[tdzspec02] == 0){
                      $specialbild2="<img src='images/sss/leer.jpg' border='0' alt=''>";
                      }else if ($row9[tdzspec02] == 1){
                          $slot=2;
                         $specialbild2="<a href='tdz.php?op=attackzwei&id3=2&id=6&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/zschwert.jpg' border='0' alt=''></a>";
                      }
                   }
                   if ($muhcount == 3){
                      if ($row9[tdzspec03] == 0){
                      $specialbild3="<img src='images/sss/leer.jpg' border='0' alt=''>";
                      }else if ($row9[tdzspec03] == 1){
                          $slot=3;
                         $specialbild3="<a href='tdz.php?op=attackzwei&id3=3&id=6&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/zschwert.jpg' border='0' alt=''></a>";
                      }
                   }
                   if ($muhcount == 4){
                      if ($row9[tdzspec04] == 0){
                      $specialbild4="<img src='images/sss/leer.jpg' border='0' alt=''>";
                      }else if ($row9[tdzspec04] == 1){
                          $slot=4;
                         $specialbild4="<a href='tdz.php?op=attackzwei&id3=4&id=6&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/zschwert.jpg' border='0' alt=''></a>";
                      }
                   }
                   if ($muhcount == 5){
                      if ($row9[tdzspec05] == 0){
                      $specialbild5="<img src='images/sss/leer.jpg' border='0' alt=''>";
                      }else if ($row9[tdzspec05] == 1){
                         $specialbild5="<a href='tdz.php?op=attackzwei&id3=5&id=6&id2=".urlencode($row4['tdzplaid'])."'><img src='images/sss/zschwert.jpg' border='0' alt=''></a>";
                      }
                   }
               }

               output("`n`n`c`b`@Dein Inventar:`c`b`n`0");
               output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td>$specialbild</td><td>$specialbild2</td><td>$specialbild3</td></tr>",true);
               output("</table>",true);
               output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td>$specialbild4</td><td>$specialbild5</td></tr>",true);
               output("</table>",true);
               addnav("","tdz.php?op=attackzwei&id3=1&id=6&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id3=2&id=6&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id3=3&id=6&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id3=4&id=6&id2=".urlencode($row4['tdzplaid'])."");
               addnav("","tdz.php?op=attackzwei&id3=5&id=6&id2=".urlencode($row4['tdzplaid'])."");
               }

}
else if ($_GET['op']=="attackzwei"){
               $sql = "SELECT name FROM accounts WHERE acctid='".$_GET[id2]."'";
           $result = db_query($sql) or die(db_error(LINK));
               $row = db_fetch_assoc($result);
               $sql4="SELECT * FROM tdz_spieler WHERE tdzplaid='{$_GET[id2]}'";
               $result4 = db_query($sql4) or die(db_error(LINK));
               $row4 = db_fetch_assoc($result4);
               $waffe=$_GET[id];
               $slotdel=$_GET[id3];
               
                        switch(e_rand(1,5)){
                        case 1:
                        $gegner=1;
                        break;
                        case 2:
                        $gegner=2;
                        break;
                        case 3:
                        $gegner=3;
                        break;
                        case 4:
                        $gegner=4;
                        break;
                        case 5:
                        $gegner=5;
                        break;
                        }
               
               if ($waffe==1){

                  if ($gegner==1){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Schnell spannst du die Armbrust und setzt den Bolzen ein. Du zielst
                        auf ".$row[name]." `@und drückst ab. Einige bange Sekunden vergehen. `$ fi`4ts`Qch. `4TREFFER!!!
                        `@Du hast ".$row[name]." `@mit dem Bolzen getroffen. Respekt. Diese Runde geht klar an dich`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du spannst du die Armbrust und setzt den Bolzen ein. Deine Hände zittern als du
                        auf ".$row[name]." `@zielst. Du drückst ab. Einige bange Sekunden vergehen als du erkennst das dein
                        Bolzen das Ziel verfehlt. Innerlich fluchst du noch als an dir ein Bolzen knapp vorbei fliegt.
                        `@Dein Glück das ".$row[name]." `@genauso schlecht gezielt hat wie du`c`b`n`0");
                        break;
                        case 3:
                        output("`n`n`c`b`@Du spannst die Armbrust und setzt den Bolzen ein. Deine Hände zittern und du
                        bist aufgeregt. In dieser Aufregung fällt dir der Bolzen in den, leicht sandigen, Boden. Schnell
                        wirfst du dich auf den Boden und suchst ihn. Nach scheinbar unendlich langen Sekunden findest du ihn.
                        Du richtest dich auf und willst gerade den Bolzen einsetzen als du einen stechenden Schmerz spürst.
                        Als du zu Boden gehst musst du erkennen das ".$row[name]." `@schneller war als du und die Runde gewinnt`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                     output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/arm.jpg' border='0' alt=''></td><td><img src='images/sss/arm.jpg' border='0' alt=''></td></tr>",true);
                     output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                     output("</table>",true);
                  }else if ($gegner==2){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du näherst dich ".$row[name]." `@langsam. Du siehst das dein Gegner mit einem Schwert
                        bewaffnet ist. Du malst dir deine Chancen aus und weisst das das du Glück haben musst um diese Runde zu
                        gewinnen. Doch Mist! Dein Gegner hat dich entdeckt und stürzt sich auf dich. Schnell versuchst du noch
                        die Armbrust zu spannen. Doch zu spät. Du wirst niedergestreckt und verlierst die Runde.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du spannst leise die Armbrust und setzt den Bolzen ein.  ".$row[name]." `@hat dich noch
                        immer nicht gesehen und fuchtelt mit dem Schwert umher. Das ist deine Chance um aus dem Waffennachteil doch
                        einen Sieg zu schlagen. Du zielst und drückst ab. `2GRANDIOS!!! `@Getroffen... ".$row[name]." `@sinkt zu Boden.
                        Diese Runde geht klar an dich.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Als du den Kampfplatz betritts, stürzt sich schon ".$row[name]." `@auf dich. Darauf
                        warst du gar nicht vorbereitet und du versuchst noch zu retten was nicht zu retten ist. In der Hektik
                        fällt dir der Bolzen runter. Noch bevor du dich versehen kannst streckt dich ".$row[name]." `@nieder.
                        Diese Runde ist verloren. `c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/arm.jpg' border='0' alt=''></td><td><img src='images/sss/schwert.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else if ($gegner==3){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du kommst auf den Kampfplatz und erkennst auch gleich ".$row[name]." `@Dein Gegner
                        scheint grosse Probleme mit dem schweren Kriegshammer zu haben. Das ist deine Gelegenheit!. Schnell
                        spannst du die Armbrust und setzt den Bolzen ein. In dieser Zeit hat es ".$row[name]." `@nicht einmal
                        geschafft nur annähernd an dich ran zu kommen. Du zielst und drückst ab. Treffer! Ein leichter Sieg
                        für dich.`@`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Leise tapperst du auf dem Kampfplatz. Zu deiner Verwunderung ist es dunkel. Es dauert
                        einen Moment das sich deine Augen an die Dunkelheit gewöhnen. Du schaust dich um und suchst
                        ".$row[name]." `@. Leider kannst du niemanden entdecken. Dann hörst du hinter dir ein Geräusch. Du drehst
                        dich. Bevor du was erkennen kannst spürst du einen dumpfen Schlag und gehst zu Boden. Diese Runde ging an ".$row[name]." `@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Motiviert stampfst du auf dem Kampfplatz. Schnell entdeckst du ".$row[name]." `@. Schnell
                        spannst du deine Armbrust, setzt den Bolzen ein und zielst. ".$row[name]." `@kommt näher...näher...näher. Du
                        drückst ab und ".$row[name]." `@geht zu Boden. Du grinst und amüsierst dich darüber das ".$row[name]." `@
                        vielleicht doch noch gedacht hat dich zu besiegen. Diese Runde geht an dich.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/arm.jpg' border='0' alt=''></td><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else if ($gegner==4){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Langsam öffnet sich das Tor zur Arena. Die Zeit nutzt du um die Armbrust zu spannen und den Bolzen
                        einzusetzen. Als das Tor geöffnet ist springst du auf den Kampfplatz und schaust dich um. Du entdeckst ".$row[name]."
                        `@und zielst. Du hast genug Zeit um dir eine gute Schussposition zu verschaffen. Als du abdrückst ist der Kamof auch
                        schon vorbei. ".$row[name]." `@sinkt zu Boden. Die Runde ging an dich.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Hoch motiviert springst du auf dem Kampfplatz. Du entdeckst deinen gegner und packst deine Armbrust. Öh...
                        wo ist die Armbrust geblieben? Hattest du sie nicht auf den Rücken geschnallt? Klasse, irgendwie ist sie nicht da. Du suchst
                        und suchst. Doch zu spät. Schön siehst du die Geramos singen. Diese Runde ging klar an ".$row[name]." `@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Froh darüber das du die Armbrust gewählt hast, spazierst du in die Kampfarena. ".$row[name]." `@hat einen
                        schweren Morgenstern als Waffe gewählt. `2Pech gehabt! `@denkst du dir noch als du die Armbrust gemächlich lädst und zielst.
                        Du lässt deinen gegner noch ein wenig rankommen, ein wenig Spass muss ja sein, dann drückst du ab. Treffer! Diese Runde ging
                        klar an dich.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/arm.jpg' border='0' alt=''></td><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else{

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Als du dich in Richtung der Kampfarena machst merkst du das die Wahl der Waffe nicht so gut war.
                        Lustigerweise ist die Armbrust kaputt und der Bolzen eine Feder von einem Straus. Als du ".$row[name]." `@entdeckst,
                         tust du das was am besten in diesem Fall ist. WEGRENNEN. Leider klappt das nur bedingt gut. Du stolperst über
                        deine Füsse und stürzt auf einen Stein. Schon ist der Kampf vorbei.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        $huhn=0;
                        break;
                        case 2:
                        output("`n`n`c`b`@Noch als du dich sammeln kannst beginnt schon der Kampf. ".$row[name]." `@steht mit einer mächtigen
                        Kampfaxt vor dir. Du schliesst die Augen, lässt die Armbrust fallen und wartest auf den erlösenden Schlag. Dann hörst du
                        einen dumpfen Schlag. Du fühlst an dir runter. Alles scheint in Ordnung zu sein. Du öffnest langsam die Augen. Dann beginnst
                        du laut zu lachen. Die Schneide der Axt hat sich vom Stupf gelöst und ist ".$row[name]." `@auf den Kopf gefallen.
                        Somit geht die Runde an dich. Respekt. Ohne einen Schuss aus deiner Armbrust.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        $huhn=0;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du ziehst langsam dein Huhn vom Rücken...EIN HUHN?!?!...VERDAMMT wo ist den die Armbrust geblieben? Warum
                        hast du ein Huhn auf dem Rücken? Warum ist Luxx so gutaussehend? Viele Fragen blitzen dir durch den Kopf. In der Hecktik bemerkst
                        du nicht das sich ".$row[name]." `@dir leise nähert. Als du mit dem Huhn eine Grundsatzdiskussion beginnst, ist es schon zu spät.
                        Mit einem dumpfen Geräusch fällst du bewustlos zu Boden. Diese Runde ist ist verloren `c`b`n`0");
                        $session[user][tdzbadguy]++;
                        $huhn=1;
                        break;
                        }

                        if ($huhn=0) output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/arm.jpg' border='0' alt=''></td><td><img src='images/sss/axt.jpg' border='0' alt=''></td></tr>",true);
                        if ($huhn=1) output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/ghuhn.jpg' border='0' alt=''></td><td><img src='images/sss/axt.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }
               }else if ($waffe==2){

                  if ($gegner==1){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Langsam umgreifst du den Griff deines Schwertes und läufst in die Kampfarena.
                        Du entdeckst ".$row[name]." `@und greifst an. Als du dich deinem Gegner näherst, erkennst du das
                        er wohl Probleme mit der Armbrust hat. Dein Glück! Mit einem gezielten Schwertstoss streckst du
                        ihn nieder. Diese Runde geht an dich.`@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du kommst auf dem Kampfplatz und testest ersteinmal die Klinge des Schwertes.
                        Du dehnst hier ein wenig und drückst da ein bisschen. KRATSCH! Die Klinge ist gebrochen. Erschreckt
                        schaust du aufs kaputte Schwert als du einen stechenden Schmerz spürst. Der Bolzen von ".$row[name]." `@
                        hat dich getroffen und du gehst zu Boden.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Hoch konzentriert betrittst du den Kampfplatz und suchst deinen Gegner. Als du
                        ihn entdeckst zielt er gerade auf dich. Du reagierst schnell und duckst dich. Der Bolzen verfehlt
                        dich nur einige Millimeter. Nun hast du die Chance deinen Gegner nieder zu strecken. Du rennst
                        mit lauten Gebrüll los und schlägst zu. ".$row[name]." `@geht zu Boden.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }
                        
                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/schwert.jpg' border='0' alt=''></td><td><img src='images/sss/arm.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);

                  }else if ($gegner==2){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Als ihr euch in der Mitte der Kampfarena trefft, beginnt sofort der Kampf.
                        Immer wieder schlagt ihr auf euch ein, doch auf jeden Angriff folgt eine Parade. Die Zuschauer
                        jubeln und feuern euch an. Doch bald müsst ihr erkennen das ihr im Kampfe gleichwertig seid und
                        ihr einigt euch auf ein Unentschieden. Respekt für diese mutige Entscheidung.`c`b`n`0");
                        break;
                        case 2:
                        output("`n`n`c`b`@In der Mitte der Kampfarena triffst du auf deinen Gegner. Eure Schwerter kreuzen sich
                        und das Duell beginnt. Immer wieder wechseln die Vorteile der Kontrahenten. Doch dann hast du eine Idee.
                        Du wendest einen alten Schwerttrick an den du von deinem Meister lerntest und ja... ".$row[name]." `@ist so
                        überrascht das du den entscheidenen Hieb setzen kannst`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du bist ein wenig nervös als du in die Kampfarena betrittst. Ganz im Gegensatz zu ".$row[name]." `@.
                        Sofort wirst du attackiert und schwere Attacken peitschen über dich hinein. Etwas erschreckt wehrst du
                        die meisten ab. Doch dann rutscht du im Sand ein wenig weg und ".$row[name]." `@kann einen schweren Schlag
                        anbringen. Du gehst zu Boden.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/schwert.jpg' border='0' alt=''></td><td><img src='images/sss/schwert.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);

                  }else if ($gegner==3){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Mit lauten Gebrüll und einem riesigen Kriegshammer rennt  ".$row[name]." `@
                        auf dich zu. Dein mickriges Schwert kann wohl kaum etwas gegen diesen mächtigen Kriegshammer
                        ausrichten. Doch du hast einen Trick und nur einen Versuch. Als ".$row[name]." `@nah genug ist
                        versuchst du den Trick, aber vergeblich. Der Hammer trifft dich schwer`@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du atmest noch einmal durch. Du weißt das dein Gegner den Kriegshammer gewählt
                        hat und du im direkten Vergleich keine Chance gegen den Hammer hast. Doch weißt du auch wie schwer
                        der Hammer ist und das du, mit Geschick, auch gewinnen kannst. Als sich ".$row[name]." `@nähert
                        wendest du einen Schwerttrick an und hast Glück. Du kannst deinen Gegner niederstrecken.`@`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Grinsend schlürfst du in die Kampfarena. Als du ".$row[name]." `@entdeckst
                        vergeht dir langsam das Grinsen. Der Kriegshammer ist eine riesige Waffe mit der dir ".$row[name]." `@
                        entgegen kommt. Vor Schreck fällst du in Ohnmacht und bleibst bewusstlos liegen. Der Kampf ist
                        ironischerweise schon vorbei und die Zuschauer lachen.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/schwert.jpg' border='0' alt=''></td><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                        
                  }else if ($gegner==4){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du greifst ".$row[name]." `@mit deinem Schwert, das du in beiden
                        Händen trägst, an. ".$row[name]." `@holt zum Schlag gegen dich aus. Die Waffen
                        kreuzen sich und die Kette des Morgensterns umwickelt dein Schwert. Als ".$row[name]." `@
                        ruckartig seinen Morgenstern wegzieht, verlierst du deine Waffe. Diese Runde geht klar
                        an deinen Gegner.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Langsam ziehst du das Schwert aus der Scheide und entdeckst deinen Gegner.
                         ".$row[name]." `@fuchtelt wie wild mit den Morgenstern herum und dir wird ganz Angst und
                        Bange. Seine Übungen sehen sehr profesionell aus, bis...ja bis ".$row[name]." `@sich
                        selbst mit einem dumpfen Schlag niederstreckt. Diese Runde geht an dich und du kicherst ein wenig.`@`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Mit wilden Gebrüll rennst du auf ".$row[name]." `@zu, in der Hoffnung dein Auftritt
                        verschafft dir gleich Respekt. Doch leider weit gefehlt. ".$row[name]." `@Schaut dich nur lächelnd
                        an und greift dann selbst an. Die Waffen kreuzen sich und durch die Belastung die dein Schwert aushalten
                        muss zerbricht es. Der Kampf wird beendet und du verlierst die Runde.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/schwert.jpg' border='0' alt=''></td><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else{

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Hoch motiviert tritts du in die Kampfarena und greifst ".$row[name]." `@an. Es
                        scheint als hätte dein Gegner wenig Ahnung wie man anständig mit einer Axt umzugehen hat und
                        wurschtelt eher mit der Waffe herum. Das ist sehr gut für dich und so kannst du ihn gut überwältigen.
                        Diese Runde ging an dich auch wenn es keine Richtige Herrausforderung war.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du triffst ".$row[name]." `@an der südlichen Seite des Kampfplatzes und ihr kreuzt
                        eure Waffen. Die Sonne scheint und blendet dich stark. Du versuchst dennoch den Attacken von ".$row[name]." `@
                        auszuweichen. Doch die Sonnenstrahlen blenden so stark das du die letzte Attacke nicht ausweichen kanns.
                        Du wirst hart getroffen und gehst zu Boden.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Eure Waffen schmettern gegeneinander. ".$row[name]." `@prustet schon. Das scheint dein Glück
                        zu sein. Noch eine Weile musst du auf ".$row[name]." `@einschlagen damit ein Fehler den Kampf beendet.
                        Du erhöhst das Tempo und greifst immer und immer wieder an. Dann endlich ... ".$row[name]." `@wird unaufmerksam
                        und dein Hieb trifft. Die Runde geht an dich`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }
                        
                     output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/schwert.jpg' border='0' alt=''></td><td><img src='images/sss/axt.jpg' border='0' alt=''></td></tr>",true);
                     output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                     output("</table>",true);
                     
                     }
               }else if ($waffe==3){

                  if ($gegner==1){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du betrittst siegessicher die Kampfarena in der dein Gegner ".$row[name]." `@dich schon
                        mit seiner geladenen Armbrust erwartet. Trotzdem bist du nicht entmutigt und stürmst direkt am
                        Anfang auf ".$row[name]." `@los. Du weichst einigen seiner Bolzen aus .Als du bei ihm angelangt bist. Du holst
                        Zum finalen Stoß aus! Doch dann bemerkst du einen stechenden in deinem Gesicht !Dein Gegner ".$row[name]." `@hat dir tatsächlich
                        ein drittes Nasenloch verpasst !!Diese Runde verlierst du`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du betrittst nervös die Kampfarena in der ".$row[name]." `@schon auf dich wartet. Du
                        reibst noch mal langsam über deinen Kriegshammer und gehst dann zum Angriff über. Du rennst wie
                        ein Berserker auf ".$row[name]." `@zu der gerade seinen Pfeil in seine Armbrust legt. Plötzlich stolperst
                        du und dein Kriegshammer fliegt dir aus der Hand. Du erwartet schon den stechenden Schmerz als es
                        GLONG macht! Du blickst vorsichtig auf und bemerkst das dein Kampfhammer deinen Gegner voll am
                        Kopf getroffen hat. Du hattest glück diese runde geht an dich`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Mutig betrittst du Die Kampfarena in der dein Gegner ".$row[name]." `@dich schon  mit einer
                        Armbrust erwartet. Du greifst nach deinem Kampfhammer und stürmst los. Du weichst zwei Bolzen aus.
                        Doch der dritte trifft dich voll. Du gehst zu Boden und dein Gegner ".$row[name]." `@gewinnt den Kampf knapp.
                        Diese Runde musstest du leider als verlorgen geben, aber deine Rache wird noch kommen.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                     output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td><td><img src='images/sss/arm.jpg' border='0' alt=''></td></tr>",true);
                     output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                     output("</table>",true);
                     
                  }else if ($gegner==2){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du kommst müde in die Arena mit einem rostigen Kriegshammer in der Hand.
                        Du erblickst sofort ".$row[name]." `@und du wirst wütend angestarrt und wild mit einem Schwert
                        angegriffen. Du gähnst deinen Gegner nur müde an, doch das hättest du nicht tun sollen ".$row[name]." `@
                        fühlt sich provoziert und rennt wild fuchtelnd auf dich zu.. Du erschrickst und hälst dein
                        kampfhammer hin. Dein Gegner schlägt im Lauf noch gegen dein Kampfhammer, sodass die Rostsplitter
                        überall herum fliegen. Einer davon verirrt sich im Auge von ".$row[name]." `@und bricht sofort vor Schmerz
                        zusammen. Du gewinnst zwar mit glück aber du gewinnst.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du kommst hochmotiviert in die Arena und erblickst ".$row[name]." `@der ein Schwert in der
                        Hand hält. Du ziehst deinen Kampfhammer und läufst locker auf deinen Gegner zu. Dieser blickt
                        dich nun ängstlich an, du gehst aber unbeirrt weiter. Als du direkt vor ".$row[name]." `@stehst zuckt ".$row[name]." `@
                        zusammen und hält sich seine Hände direkt vors Gesicht. Du hast Mitleid mit dem Gegner und gibst
                        ihm eine weitere Angriffschance. Du drehst ihm den Rücken zu. Keine Sekunde später spürst du einen
                        stechenden Schmerz in deinem Rücken. Du gehst zu Boden und ".$row[name]." `@gewinnt unfair den Kampf.
                        Du bist einfach zu weich für den Job !!!!`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du kommst in die Arena mit deinem Kampfhammer im Anschlag Du erblickst deinen
                        Gegner der wilde Grimassen schneidet. Du hast keine rechte Lust zu kämpfen und lässt daher deinen
                        Gegner erst mal auf dich zukommen. Kurz bevor er dich erreicht wirfst du ihm deinen Hammer entgegen.
                        Du triffst ihm an Kopf und er lässt sein Schwert sofort fallen. Damit gewinnst du den Kampf.
                        Das hat klasse !!!!!`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td><td><img src='images/sss/schwert.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                        
                  }else if ($gegner==3){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du kommst neugierig in die Arena mit einem rosa Kampfhammer in der Hand.
                        Du erblickst als erstes deinen entschlossenen Gegner. Du winkst ihm verstohlen zu. Doch dies
                        macht in so wütend ,dass er sofort auf dich los stürmt. Gerade als er auf dich einschlagen
                        will siehst du eine rosa Blume und bückst dich nach ihr. So entkommst du dem todbringenden Schlag.
                        Als du dich wieder aufrichtest schlägst du mit deinem Kampfhammer deinem Gegner vors beste Stück !
                        Dieser bricht sofort schreiend zusammen. Du gewinnst !! Das war ein warmer Kampf puhh!!!!`@`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du kommst in die Arena mit deinem Kampfhammer im Anschlag. Du erblickst ".$row[name]."
                        `@der dich wütend anstarrt und  seinen Kriegshammer kreisen lässt. Du stürmst auf
                        deinen Gegner zu der aber verzuckt keine Wimper. Du bist nun verunsichert und hälst kurz vor ".$row[name]." `@.
                        Du vernachlässigst deine Deckung und dein Gegner nutzt das eiskalt aus. Kurz darauf liegst du blutend am Boden.
                        Angsthase !!`@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du kommst in die Arena mit deinem  Kampfhammer über der Schulter. Du erblickst
                        sofort deinen Gegner ".$row[name]." `@der mit seinem Kriegshammer spielt. Ihr zögert nicht lange und
                        rennt brüllend aufeinander zu , immer wieder eure Hämmer schwenkend. Ihr trefft euch in der Mitte
                        und ein wildes Gefecht entbrannt. Ihr gebt euer bestes aber keiner von euch kann einen guten Schlag
                        setzen. Plötzlich verharken sich eure Hämmer ineinander und ihr kriegt sie nicht mehr auseinander.
                        Der Kampfleiter erklärt den Kampf für unentschieden !!Pech gehabt !!`c`b`n`0");
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else if ($gegner==4){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du kommst in die Arena mit deinem Kampfhammer über die Schulter. Sofort erblickst
                        du deinen Gegner ".$row[name]." `@der wutschnaubend seinen Morgenstern kreisen lässt. Du läufst langsam auf
                        ihn zu: Währendessen vollführt ".$row[name]." `@die unmöglichsten Bewegungen. Du grinst nur. Plötzlich misslingt
                         ".$row[name]." `@ein Kunststück und er schlägt sich selber mit dem Morgenstern ins reich der Träume !!Du gewinnst !
                         ".$row[name]." `@soll zum Zirkus gehen`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du kommst in die Arena mit deinem Kamphammer in der Hand. Du erblickst deinen Gegner ".$row[name]." `@
                        mit seinem Morgenstern spielst. Du stürmst los doch dein Gegner bleibt locker. Du holst zum Schlag aus
                        doch dein Gegner tust das gleiche. Der Morgenstern umwickelt deinen Hammer. Dein Gegner entreißt dir den
                        Hammer! Du gibst besser auf da du keine Waffe mehr hast!!Pech gehabt `@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du kommst in die Arena mit deinem geliebten Kampfhammer. Du stürmst sofort auf deinen Gegner
                         ".$row[name]." `@los. Dieser ist total überrascht von deinem Willen zu siegen und zittert am ganzen Körper! Du bleibst
                        locker und schlägst einmal hart zu. ".$row[name]." `@kann deinen Schlag zwar parieren  aber sein Morgenstern zerbricht
                        dabei entzwei. Nun ist es dir ein leichtes deinen Gegner zu töten !!Keine Gnade und das ist gut so !!`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else{

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du schlurfst in die Arena ohne rechte Lust zu kämpfen. Du erblickst deinen Gegner
                        der mit der Axt rum fuchtelst. Du bleibst ganz locker und wirfst deinen Kampfhammer einfach auf den
                        Boden. Dein Gegner rennt siegesssicher auf dich zu. Und holt dann zum Schlag aus. Doch du schlägst
                        einfach mit der Faust zu! Dein Gegner bricht zusammen! Du gewinnst! Gut gemacht `c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du kommst in die Arena gerannt mit deinem Kampfhammer. Dein Gegner lächelt nur müde.
                        Du rennst auf ".$row[name]." `@zu doch dieser zögerst nicht lange und spaltet deinen Kopf mit einer Axt!!
                        Volltreffer`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du schleichst in die Arena mit deinem Kampfhammer über die Schulter gelegt. Dein
                        Gegner bemerkt nichts von deinem erscheinen. Du schleichst genau hinter ihn. Als du hinter ihm stehst
                        tippst du ihm von hinten auf die Schulter. ".$row[name]." `@zuckt zusammen und kriegt einen Schock als er
                        dein Gesicht sieht !!**** bricht vor Schreck zusammen!! Du gewinnst !Man musst du ne Fratze haben`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td><td><img src='images/sss/axt.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }
               }else if ($waffe==4){

                                 if ($gegner==1){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du bemerkst das der Morgenstern doch eine recht schwere Waffe ist und ziehst
                        ihn hinter dir her. Das Schauspiel hat ".$row[name]." `@natürlich schon entdeckt und kann in aller
                        Ruhe auf dich zielen. Als du bemerkst das du schon im Visir bist, ist es auch schon zu spät.
                        Ein Bolzen trifft dich schwer und du gehst zu Boden. Diese Runde geht eindeutig an ".$row[name]." `@.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Schnell rennst du, den Morgenstern kreisend über deinen Kopf, in die Kampfarena.
                        Schnell hast du ".$row[name]." `@entdeckt und vorallem auch überrascht. In der ersten Schreckenssekunde
                        fällt ".$row[name]." `@der Bolzen aus der Hand und verschwindet im sandigen Boden. Diese Gelegenheit
                        lässt du dir nicht entgehen und streckst ihn nieder. Respekt du hast gewonnen.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du lässt deinen Morgenstern über den Kopf kreisen und schlägst mit voller
                        Wucht in richtung von ".$row[name]." `@. Dein Gegner lächelt nur müde, drückt ab und trifft dich hart.
                        Bevor du deinen Schlag zuende führen kannst gehst du schon zu Boden. Diese Runde ist verloren obwohl
                        du dachtest das du der Sieg auf deiner Seite ist.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                     output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td><td><img src='images/sss/arm.jpg' border='0' alt=''></td></tr>",true);
                     output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                     output("</table>",true);
                  }else if ($gegner==2){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du entdeckst deinen Gegner und schmunzelst als du den rostigen Prügel siehst
                        den ".$row[name]." `@trägt. Mit voller Wucht schlägst du auf das Schwert von ".$row[name]." `@.
                        Deine harten Schläge kann das Schwert nur eine weile parieren. Dann bricht es und der Kampf wird
                        abgebrochen. Du hast diese Runde gewonnen, gratuliere dir!`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du kommst in die Kampfarena und zu deiner Verwunderung ist es stock dunkel hier.
                        Deine Augen brauchen eine Weile bis sie sich an die Dunkelheit gewöhnt haben. Du läufst
                        langsam durch die Arena. Plötzlich fällst du über einen Stein der in der Arena liegt.
                        Schon ist ".$row[name]." `@zur Stelle und bedroht dich mit dem Schwert. Diese Runde hast du verloren.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du schmetterst deinen Morgenstern in Richtng des Schwertes von ".$row[name]." `@. Die Kette
                        des Morgensterns dreht sich um das Schwert. Du ziehst und zerrst an dem Morgenstern und versuchst ".$row[name]." `@
                        das Schwert aus der hand zu reissen. GESCHAFFT. Nach einer kurzen Weile lässt ".$row[name]." `@das Schwert
                        los und du gehst als Gewinner hervor.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td><td><img src='images/sss/schwert.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                        
                  }else if ($gegner==3){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Den Morgenstern schwingend rennst du auf ".$row[name]." `@zu. Dein Gegner ist etwas
                        überrascht und scheinbar noch nicht bereit zum Kampf. Dies nutzt du natürlich gnadenlos aus
                        und schlägst mir dem Morgenstern zu. ".$row[name]." `@versucht noch auszuweichen, aber zu spät.
                        Dein Gegner geht zu Boden und die Runde geht klar an dich.`@`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du bist etwas müde weil es gestern in der Taverne doch etwas spät wurde. Etwas
                        verschlafen läufst du in die Kampfarena. ".$row[name]." `@ist weniger müde, dafür aber hoch motiviert.
                        Schon siehst du einen mächtigen Kriegshammer auf dich zufliegen. Du gehst zu Boden und weisst das dieser
                        Kampf verloren ist. Die Runde geht an ".$row[name]." `@.`@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Du triffst ".$row[name]." `@in der Mitte der Arena. Die Waffen kreuzen sich. Immer wieder
                        attakierst du deinen Gegner und dieser pariert. Schon eine Weile dauert der Kampf als ".$row[name]." `@den
                        Kopf des Hammers verliert. Nun ist es soweit, deine Chance den kampf für dich zu entscheiden ist gekommtn.
                        Du schlägst ".$row[name]." `@nieder und holst dir die Runde`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                        
                  }else if ($gegner==4){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du entdeckst ".$row[name]." `@. Zu deiner verwunderung wurdest du noch
                        nicht entdeckt. Du schleichst dich langsam an ".$row[name]." `@ran und holst aus. Dein gegner
                        fällt zu Boden. Mit deinem Fuss stupst du ihn leich an. Aber es scheint keinen zweifel
                        zu geben, du hast gewonnen. Diese Runde ging an dich, auch wenn es nicht sehr heldenhaft war.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Du und ".$row[name]." `@holen zu einem Schlag mit dem Morgenstern aus.
                        Die Morgensterne kreuzen sich und die Ketten verhacken sich. Ihr beide zieht und zehrt was
                        das zeug hält. Doch die Waffen bleiben verhackt. Fragend schaut ihr zu den Duellleitern.
                        Diese geben bekannt das das Duell als unentschieden gewertet wird. Schade, es sah sehr
                        gut aus für dich.`@`c`b`n`0");
                        break;
                        case 3:
                        output("`n`n`c`b`@Du schleichst in die Kampfarena um ".$row[name]." `@zu überraschen. Das
                        klappt auch alles sehr gut. Leise näherst du dich deinem Gegner. Doch die Kette an deinem
                        Morgenstern verrät dich. ".$row[name]." `@dreht sich um und schlägt sofort zu. Du bist überascht
                        und 2 Sekunden später schon bewustlos. Diese Runde hast du auf jeden Fall verloren.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else{

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Du liegst in der Kampfarena....mhhhhh....wieso liegst du in der Kampfarena?
                        Ein Kampfrichter kommt auf dich zu und stupst dich an. Du schläfst seelenruhig in der Arena.
                        Ein beissender geruch von Ale liegt in der Luft. Da du ersteinmal deinen Rausch ausschlafen
                        musst, bricht der Kampfrichter das Duell ab und macht ".$row[name]." `@zum Sieger des Duells.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Als du ".$row[name]." `@in der Kampfarena entdeckst rennst du sofort drauf los. Leider viel zu überhastet.
                        Natürlich wartet ".$row[name]." `@nur auf so einen Angriff. Dein Gegner lässt dich immer näher rankommen und du
                        zweifelst immer mehr ob dieser Angriff vernünftig wat. Als du noch am überlegen bist kommt dir eine Axt entgegen.
                        Mit einem mal ist der Kampf vorbei und die Runde für dich verloren.`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Dein Morgenstern kreisst durch deine Bewegungen schön umher. ".$row[name]." `@hat mit der
                        Kampfaxt scheinbar mehr Probleme. Die Waffen klatschen gegeneinander. Immer und immer wieder greifst du
                        an. ".$row[name]." `@hat grosse Probleme zu parieren und nach einer Zeit deiner Angriffe gibt sich ".$row[name]." `@
                        geschlagen. Du hast dich hervoragend verkauft.`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td><td><img src='images/sss/axt.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }
               }else if ($waffe==5){

                  if ($gegner==1){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Verloren`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                     output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/axt.jpg' border='0' alt=''></td><td><img src='images/sss/arm.jpg' border='0' alt=''></td></tr>",true);
                     output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                     output("</table>",true);
                  }else if ($gegner==2){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Verloren`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Verloren`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/axt.jpg' border='0' alt=''></td><td><img src='images/sss/schwert.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else if ($gegner==3){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Verloren`@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Gewonnen`@`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Verloren`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/axt.jpg' border='0' alt=''></td><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else if ($gegner==4){

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Verloren`@`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/axt.jpg' border='0' alt=''></td><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else{

                        switch(e_rand(1,3)){
                        case 1:
                        output("`n`n`c`b`@Verloren`c`b`n`0");
                        $session[user][tdzbadguy]++;
                        break;
                        case 2:
                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;
                        break;
                        case 3:
                        output("`n`n`c`b`@Unentschieden`c`b`n`0");
                        break;
                        }

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/axt.jpg' border='0' alt=''></td><td><img src='images/sss/axt.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }
               }else if ($waffe==6){

                  if ($gegner==1){

                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;

                     output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/zschwert.jpg' border='0' alt=''></td><td><img src='images/sss/arm.jpg' border='0' alt=''></td></tr>",true);
                     output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                     output("</table>",true);
                  }else if ($gegner==2){

                        output("`n`n`c`b`@Verloren`c`b`n`0");
                        $session[user][tdzbadguy]++;

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/zschwert.jpg' border='0' alt=''></td><td><img src='images/sss/schwert.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else if ($gegner==3){

                        output("`n`n`c`b`@Gewonnen`@`c`b`n`0");
                        $session[user][tdzgoodguy]++;

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/zschwert.jpg' border='0' alt=''></td><td><img src='images/sss/kriegshammer.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else if ($gegner==4){

                        output("`n`n`c`b`@Verloren`@`c`b`n`0");
                        $session[user][tdzbadguy]++;

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/zschwert.jpg' border='0' alt=''></td><td><img src='images/sss/morgenstern.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }else{

                        output("`n`n`c`b`@Gewonnen`c`b`n`0");
                        $session[user][tdzgoodguy]++;

                        output("<table cellspacing=0 cellpadding=2 align='center' border=0><tr><td><img src='images/sss/zschwert.jpg' border='0' alt=''></td><td><img src='images/sss/axt.jpg' border='0' alt=''></td></tr>",true);
                        output("<tr><td>`c`bDu`c`b</td><td>`b`cGegner`c`b</td></tr>",true);
                        output("</table>",true);
                  }
                  
                        if ($slotdel==1){
                           $sql9 = "UPDATE tdz_spieler SET tdzspec01=0 WHERE tdzplaid='".$session[user][acctid]."'";
                           db_query($sql9) or die(db_error(LINK));
                        }else if ($slotdel==2){
                              $sql9 = "UPDATE tdz_spieler SET tdzspec02=0 WHERE tdzplaid='".$session[user][acctid]."'";
                              db_query($sql9) or die(db_error(LINK));
                        }else if ($slotdel==3){
                              $sql9 = "UPDATE tdz_spieler SET tdzspec03=0 WHERE tdzplaid='".$session[user][acctid]."'";
                              db_query($sql9) or die(db_error(LINK));
                        }else if ($slotdel==4){
                              $sql9 = "UPDATE tdz_spieler SET tdzspec04=0 WHERE tdzplaid='".$session[user][acctid]."'";
                              db_query($sql9) or die(db_error(LINK));
                        }else{
                              $sql9 = "UPDATE tdz_spieler SET tdzspec05=0 WHERE tdzplaid='".$session[user][acctid]."'";
                              db_query($sql9) or die(db_error(LINK));
                        }

               }
                   addnav("Zurück","tdz.php?op=attack&id2=".urlencode($row4['tdzplaid'])."");
}
else if ($_GET['op']=="beitreten"){

    output("`n`n`QAhhhh, gut, du möchtest also in einem baldigen Duell mitkämpfen. Dies ist sehr lobenswert.
    Ich schaue mal welche Duelle derzeit noch auf so starke Krieger wie euch warten.`0");
    output("`n`n`%Er sucht in einigen Pergamentrollen, immer wieder murmelt er leise etwas. Dann zeigt er euch
    eine Liste:`n`n`0");

        $sql="SELECT * FROM tdz_spiele WHERE tdzspianz>tdzstatus ORDER BY tdznamegame ASC";
    output("<table cellspacing=0 cellpadding=2 align='left' border=1><tr><td width='200'>`b Name des Duells `b</td><td>`b Duellleiter`b</td><td>`b Krieger`b</td></tr>",true);
    $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)==0){
              output("<tr><td colspan=4 align='center'>`&`i-Keine offenen Duelle-`i`0</td></tr>",true);
           
        }
        for ($i=0;$i<db_num_rows($result);$i++){
                $row = db_fetch_assoc($result);
             $sql2 = "SELECT name FROM accounts WHERE acctid='".$row[tdzpla]."'";
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
        output("<tr class='".($i%2?"trlight":"trdark")."'><td align='left' width='200'><a href='tdz.php?op=einsteigen&id=".urlencode($row['tdzgameid'])."'>$row[tdznamegame]</a></td><td>`&$row2[name]`0</td><td>$row[tdzspianz]`&</td>",true);
        output("</tr>",true);
        addnav("","tdz.php?op=einsteigen&id=".urlencode($row['tdzgameid'])."");
    }
    output("</table><br><br><br><br>",true);
 
        if (db_num_rows($result)==0) output("`n`n`QIhr seht ja selber. Nichts da wo ihr euch als Krieger beweisen könntet.`0");
 
    addnav("zurück","tdz.php");
}
else if ($_GET['op']=="einsteigen"){
    $sql="SELECT * FROM tdz_spiele WHERE tdzgameid='{$_GET[id]}'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    
    output("`n`n`%Du schaust dir das Duell ein wenig näher an und beäugst wer sich für dieses Duell angemeldet hat:`n`0");

        $sql4="SELECT * FROM tdz_spieler WHERE tdzspielid='{$_GET[id]}' ORDER BY tdzplaid ASC";
         $result4 = db_query($sql4) or die(db_error(LINK));

        for ($i=0;$i<db_num_rows($result4);$i++){
                $row4 = db_fetch_assoc($result4);
             $sql2 = "SELECT name FROM accounts WHERE acctid='".$row4[tdzplaid]."'";
            $result2 = db_query($sql2) or die(db_error(LINK));
            $row2 = db_fetch_assoc($result2);
        output("`n");
        output($row2[name]);
    }
    addnav("Ja ich will dabei sein","tdz.php?op=add3&p1=$row[tdzgameid]");
    addnav("Nein ich such was neues","tdz.php");
}
else if ($_GET['op']=="add3"){
$idgame=$_GET[p1];

    $sql4="SELECT tdzstatus FROM tdz_spiele WHERE tdzgameid='{$_GET[p1]}'";
    $result4 = db_query($sql4) or die(db_error(LINK));
    $row4 = db_fetch_assoc($result4);
    
    if ($row4[tdzstatus]==1) $color="#00B000";
    if ($row4[tdzstatus]==2) $color="#00B0B0";
    if ($row4[tdzstatus]==3) $color="#B00000";
    if ($row4[tdzstatus]==4) $color="#B000CC";
    if ($row4[tdzstatus]==5) $color="#B0B000";
    if ($row4[tdzstatus]==6) $color="#FF9900";
    if ($row4[tdzstatus]==7) $color="#6b563f";

    $sql2 = "INSERT INTO tdz_spieler (tdzspielid,tdzplaid,tdzsterne,tdzplaycolor) VALUES ('".$idgame."','".$session['user']['acctid']."','80','".$color."')";
    db_query($sql2);
    
    db_query("UPDATE tdz_spiele SET tdzstatus=tdzstatus+1 WHERE tdzgameid='{$_GET['p1']}'");
    
    output("`n`n`%Du schreibst dich ins Duell ein`0");
    addnav("zurück","tdz.php");
}
else if ($_GET[op]=="oeffnen") {
    output("`b`c`2Antrag für ein Duell ausfüllen`0`c`b");
    output("`n`QJa ich dachte mir das ihr ein mutiger Krieger seit. Nun müssen wir noch einige Anträge
            ausfüllen und dann kann es losgehen.`0");
    output("`n`n`%Die kleine Kreatur reicht dir einige Pergamentrollen.`0");
    output("`n`n`QFüllt sie ruhig in Ruhe aus Krieger, ich habe Zeit...sehr viel Zeit`0");
    output("`n`n`b`c`2Antrag um ein Duell im Turm der Zweikämpfe zu führen`0`c`b");
    output("`n`2`cName: `@".$session[user][name]."`c`0");
    output("`n`c`2möchte ein Duell starten mit folgenden Regeln:`0");
        output("<form action='tdz.php?op=add1' method='POST'>",true);
    addnav("","tdz.php?op=add1");
    output("`b`nWie soll das Spiel heissen?:`n <textarea name='spielname' cols=40 rows=1></textarea>", true);
            output("`n`nWieviele Spieler sollen mitspielen?:`n <select name='anzahlspieler'>",true);
               output("<option value=\"2\">2</option>",true);
              output("<option value=\"3\">3</option>",true);
              output("<option value=\"4\">4</option>",true);
              output("<option value=\"5\">5</option>",true);
              output("<option value=\"6\">6</option>",true);
              output("<option value=\"7\">7</option>",true);
              output("<option value=\"8\">8</option>",true);
              output("</select>`n`n",true);
              output("`n`n`n<input type='submit' class='button' value='Antrag einreichen'>",true);
    output("</form>",true);
    output("`c");
    addnav("Anders entscheiden","tdz.php");
    addnav("Turm verlassen","village.php");
}
else if ($_GET['op']=="add1"){
$name=$_POST['spielname'];
$anzahl=$_POST['anzahlspieler'];
if ($name=="") $name="Duell der Meister";
    output("`n`n`%Du übergibst der kleinen Kreatur die Pergamentrolle. Sie liest einen Moment und
    schaut dich dann an. Dann spricht sie zu dir:`0");
    output("`n`n`QGut, wie ich hier lesen kann sollen `@".$anzahl." `QKrieger am Kampf teilnehmen?
    Der Name des Duells lautet `@".$name."`Q. Sind diese Angaben richtig?`0");
    output("`n`n`%Er schaut dich abwartend an und tippt mit seinem Zeigefinger immer zu auf den Schreibtisch.`0");
    addnav("Ja kann so bleiben","tdz.php?op=add2&p1=$name&p2=$anzahl");
    addnav("Lieber was ändern","tdz.php");
}
else if ($_GET['op']=="add2"){
$name=$_GET[p1];
$anzahl=$_GET[p2];
$anzahl2=$_GET[p2]-1;
    $sql = "INSERT INTO tdz_spiele (tdznamegame,tdzspianz,tdzpla,tdzstatus) VALUES ('$name','$anzahl','".$session['user']['acctid']."','1')";
    db_query($sql);
    $sql="SELECT tdzgameid FROM tdz_spiele WHERE tdzpla='".$session['user']['acctid']."'";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    $sql2 = "INSERT INTO tdz_spieler (tdzspielid,tdzplaid,tdzsterne,tdzplaycolor) VALUES ('".$row[tdzgameid]."','".$session['user']['acctid']."','80','#0000B0')";
    db_query($sql2);
    output("`n`n`%Zufrieden gibst du den Antrag ab und hoffst das dein Duell, ".$name.", gut besucht
    wird und das du noch ".$anzahl2." `%weitere Krieger findest. Noch dazu hast du eine Schärpe erhalten
    die die Farbe `1`bblau`b `%hat. Dies wird deine Kampffarbe sein die du in der Arena trägst.`0");
    addnav("zurück","tdz.php");
}
page_footer();
?>


