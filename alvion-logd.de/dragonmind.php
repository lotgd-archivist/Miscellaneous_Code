
<?php

/*###############################################################
Skript : dragonmind.php
Ersteller: deZent / draKarr von www.plueschdrache.de
Edit by: Hadriel von http://www.hadrielnet.ch
Version: 0.15
Beschreibung : siehe Regeln im Spiel ;-)
Gründe für den chaoscode : Eigentlich sollte das Skript nur ein kleiner $_POST[]-Test werden.. Am Ende wurds halt Mastermind.
                           --> also nicht motzen--> besser machen
Install:
1. dragonmind.php in root Ordner kopieren
2. neuen Ordner "dragonmind" im "images" Ordner erstellen.
3. checken ob die Datei "transparentpixel.gif" schon im templates Ordner ist.
3.1 JA --> gut so
3.2 Nein --> also reinkopieren

in "inn.php"
------------
addnav("DragonMind","dragonmind.php");

DATENBANK:
----------
Ich habe absichtlich auf $session[user] Felder weitesgehend verzichtet.
Darum nutze ich das Feld "pqtemp" , das bereits in diversen Skripten angezogen wird.

Falls noch nicht vorhanden:
ALTER TABLE `accounts` ADD `pqtemp` TEXT NOT NULL
Bin mir nicht sicher ob das Feld orginalerweise vom Typ "TEXT" war. Hier würde eigentlich auch VARCHAR 255 locker reichen.

Bestenliste --- by Linus für www.die-waelder-von-alvion.de/logd

CREATE TABLE `dragonbest` (
    `id` tinyint(4) unsigned NOT NULL auto_increment,
    `acctid` int(11) NOT NULL default '0',
    `versuche` tinyint(4) unsigned NOT NULL default '10',
    `time` int(11) unsigned NOT NULL default '9999',
    `name` varchar(255) NOT NULL default '',
    PRIMARY KEY  (id)
    ) TYPE=MyISAM;
    
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','69');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','99');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','109');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','119');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','129');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','139');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','149');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','159');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','169');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','179');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','189');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','199');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','299');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','399');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','499');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','599');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','699');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','799');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','899');
    INSERT INTO dragonbest (acctid, name, time) VALUES ('0','unbekannter Spieler','999');
    


Viel Spaß,
deZent

P.S. WER WÜRDE MAL EIN PAAR EINTRÄGE FÜR DIE TAUNTS TABELLE BEI ANPERA POSTEN!?! *Verdammt!*
*/
require_once "common.php";
page_header("Dragonmind");
output("`c`b`&Dragonmind V0.15`0`b`c`n`n");

   $__anzahl_versuche = 10;    // wieviele Versuche um den Code zu knacken
   $__anzahl_farben   = 8;    // wieviele der 10 Farben?
   $__einsatz         = 0;   // Einsatz Gold
   $__gewinn          = 0;   // Gewinn Gold    achte darauf, dass der Gewinn nicht zu extrem wird, da es Programme gibt, die
                               // Mastermind in 5 Zügen lösen. Somit cheat-Gefahr... 
   
   $farbe[0][farbe]="#800000";
   $farbe[0][name]="dunkelrot";

   $farbe[1][farbe]="#008000";
   $farbe[1][name]="grün";

   $farbe[2][farbe]="#E6E629";
   $farbe[2][name]="gelb";

   $farbe[3][farbe]="#0000F0";
   $farbe[3][name]="blau";

   $farbe[4][farbe]="#800080";
   $farbe[4][name]="lila";

   $farbe[5][farbe]="#FF0000";
   $farbe[5][name]="rot";

   $farbe[6][farbe]="#14EAD3";
   $farbe[6][name]="türkis";

   $farbe[7][farbe]="#F26A10";
   $farbe[7][name]="orange";

   $farbe[8][farbe]="#00A8FF";
   $farbe[8][name]="hellblau";

   $farbe[9][farbe]="#FFFFFF";
   $farbe[9][name]="weiß";

if ($_GET[op]==''){
    addnav("Dragonmind");
    output('Du betrittst einen etwas abgeschiedenen Bereich,`nIn feurig roten Lettern steht an der Wand `n`n
    <font size=+1>`b`$D`4ragon`$M`4ind`b </font>`n`n`7 geschrieben.`n`n',true);
      
    //Liste der besten Spieler
    output('`n`n<font size=+1>`c`b`6Unsere besten Spieler`b`c`0 </font>`n',true);
    
    $sql="SELECT * from dragonbest ORDER BY id";
    $result=db_query($sql);
    output('<table cellspacing="0" cellpadding="2" align="center"><tr class="trhead">',true);
    output("<td>`bRang`b</td><td>`bName`b</td><td>`bZeit`b</td><td>`bVersuche`b</td></tr>", true);
    for($i=1; $i<=20; $i++) {
        $row=db_fetch_assoc($result);
        if($row['name']!='' && $row['acctid']>0) output("<tr><td align='center'>`6".$row['id']."</td><td>`&".$row['name']."</td><td align='center'>`6".$row['time']."</td><td align='center'>`6".$row['versuche']."</td></tr>",true);
    }
    output("</table>",true);
    
/*
       if ($session[user][gold]<$__einsatz){
          output("Jemand raunzt dich an:`7\"`9Hier kannst du um ein paar Goldstücke spielen. Der Spieleinsatz ist jedoch 100 Gold. Soviel Gold hast du wohl nicht! *HARHAR*`7\".");
       }
       else{
         output("Jemand raunzt dich an:`7\"`9Hier kannst du um ein paar Goldstücke spielen.`7\".");
         addnav("Spiel Spielen","dragonmind.php?op=new");
       }
*/

         addnav("Spiel Spielen","dragonmind.php?op=new");
   addnav("Regeln","dragonmind.php?op=regeln");
   addnav("Zurück");
   addnav("Zur Spielauswahl","shades_spiele.php");
}elseif($_GET[op]=='new'){
   $session[user][gold]-=$__einsatz;  
    addnav("Du hast noch:");
     addnav("$__anzahl_versuche Versuche");

   // farbkombi festlegen
   $zuf=array();
   for ($i=0;$i<4;$i++){
      while(true){
        $check=e_rand(0,($__anzahl_farben-1));
        if (array_search($check,$zuf)===false){
           $zuf[$i]=$check;
           break;
        }
      }
      $zufall[$i][farbe]=$farbe[$check][farbe];
      $zufall[$i][name]=$farbe[$check][name];
   }
   $session[user][pqtemp]=serialize($zufall);
   output("`7 Wähle Deine Farben:`n`n");
   output("<form action='dragonmind.php?op=play' method='post' name='f1'>",true);
   for ($i=0;$i<4;$i++){
       output("<select name='auswahl[".$i."]'>",true);
if($__anzahl_farben<10) $sel=$__anzahl_farben;
            if($__anzahl_farben>=10) $sel=count($farbe);
              for ($j=0;$j<($sel);$j++){
          output("<option value='".$farbe[$j][farbe]."' style='background-color:".$farbe[$j][farbe]."'>".$farbe[$j][name]."</option>",true);
        }
       output("</select>",true);
   }

   output("<br><br><input type='submit' name='rate' value='Tipp abgeben'>",true);
   output("</form>",true);
   addnav("","dragonmind.php?op=play");
   $_SESSION['dmtime'] = time();
}elseif($_GET[op]=='play'){
   // erstmal die such farben wieder auslesen
   $farben=unserialize($session[user][pqtemp]);
   // mal schauen ob er was erraten hat.
   $rs=0; // richtige stelle  + richtige Farbe
   $rf=0; // richtige farbe
   // check ob richtige farbe an richtiger stelle
   for ($i=0;$i<count($farben);$i++){
      for ($j=0;$j<count($farben);$j++){
         if ($_POST[auswahl][$i]==$farben[$j][farbe]){
           if ($i==$j){
             $rs++;
             $farben[$j][farbe]='';
           }
         }
      }
   }
   // richtige farbe aber falsche stelle
   for ($i=0;$i<count($farben);$i++){
      for ($j=0;$j<count($farben);$j++){
         if ($_POST[auswahl][$i]==$farben[$j][farbe]){
             $rf++;
             $farben[$j][farbe]='';
          }
       }
    }
   $farben=unserialize($session[user][pqtemp]);
   // Farbpunkte für aktuellen rateversuch zusammenbauen
   for ($i=0;$i<$rs;$i++){
     $bilder_aktuell.='<img src="./images/dragonmind/gruen.gif" alt="Richtige Farbe an richtiger Stelle">';
   }
   for ($i=0;$i<$rf;$i++){
     $bilder_aktuell.='<img src="./images/dragonmind/rot.gif" alt="Richtige Farbe an falscher Stelle">';
   }

   if ($rs==4){
      $gewonnen=true;
    $zeit = time() - $_SESSION['dmtime'];
   }  // player hat gewonnen
   addnav("","dragonmind.php?op=play");
   output("<form action='dragonmind.php?op=play' method='post' name='f1'>",true);
   if (count($_POST[versuche])>=$__anzahl_versuche-1 || $gewonnen){
   output("<table>
               <tr><td colspan='5' align='center' style='background-color:#AFDB02;color:#000000;font-weight:bold;'>LÖSUNG</td></tr>
               <tr>
                <td bgcolor=".$farben[0][farbe].">
                <img src='./templates/transparentpixel.gif' width='63' height='10'>
                </td>
                <td bgcolor=".$farben[1][farbe].">
                <img src='./templates/transparentpixel.gif' width='63' height='10'>
                </td>
                <td bgcolor=".$farben[2][farbe].">
                <img src='./templates/transparentpixel.gif' width='63' height='10'>
                </td>
                <td bgcolor=".$farben[3][farbe].">
                <img src='./templates/transparentpixel.gif' width='63' height='10'>
                </td>
                <td>
                 Lösung
                </td>
              </tr>",true);
   }
   else{
     output("<table>",true);
   }
    output("<tr>
                  <td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
                   Farbe 1
                   </td>
                  <td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
                   Farbe 2
                  </td>
                  <td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
                   Farbe 3
                  </td>
                  <td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
                   Farbe 4
                  </td>
                  <td style='background-color:#AFDB02;color:#000000;font-weight:bold;' align='center'>
                    Info
                  </td>
                </tr>",true);
   for ($i=0;$i<count($_POST[versuche]);$i++){
      // letzte auswertung auslesen und Bilder -code schreiben.
      $auswertung = explode("-",$_POST[tip][$i]);
      $bilder='';
      for ($k=0;$k<$auswertung[0];$k++){
        $bilder.='<img src="./images/dragonmind/gruen.gif" alt="Richtige Farbe an richtiger Stelle">';
      }
      for ($k=0;$k<$auswertung[1];$k++){
        $bilder.='<img src="./images/dragonmind/rot.gif" alt="Richtige Farbe an falscher Stelle">';
      }
      output("<tr>
                  <td bgcolor=".$_POST[versuche][$i][0].">
                  &nbsp;
                  </td>
                  <td bgcolor=".$_POST[versuche][$i][1].">
                  </td>
                  <td bgcolor=".$_POST[versuche][$i][2].">
                  </td>
                  <td bgcolor=".$_POST[versuche][$i][3].">
                  </td>
                  <td>
                    $bilder
                  </td>
                </tr>",true);
       for ($k=0;$k<count($_POST[versuche][$i]);$k++){
         output("<input type='hidden' name='versuche[$i][$k]' value='".$_POST[versuche][$i][$k]."'> ",true);
       }
       output("<input type='hidden' name='tip[".$i."]' value='".$_POST[tip][$i]."'> ",true);
   }
      output("<tr>
                <td bgcolor=".$_POST[auswahl][0].">
                &nbsp;
                </td>
                <td bgcolor=".$_POST[auswahl][1].">
                &nbsp;
                </td>
                <td bgcolor=".$_POST[auswahl][2].">
                &nbsp;
                </td>
                <td bgcolor=".$_POST[auswahl][3].">
                &nbsp;
                </td>
                <td>
                $bilder_aktuell
                </td>
              </tr>",true);

       for ($k=0;$k<count($_POST[auswahl]);$k++){
         output("<input type='hidden' name='versuche[".$i."][$k]' value='".$_POST[auswahl][$k]."'>",true);
       }
       output("<input type='hidden' name='tip[".$i."]' value='".$rs."-".$rf."'> ",true);

   if ((count($_POST[versuche])<$__anzahl_versuche-1) && $gewonnen!=true){
         output("<tr>",true);

         for ($i=0;$i<4;$i++){
             output("<td><select name='auswahl[".$i."]'>",true);
            if($__anzahl_farben<10) $sel=$__anzahl_farben;
            if($__anzahl_farben>=10) $sel=count($farbe);
              for ($j=0;$j<($sel);$j++){
                output("<option value='".$farbe[$j][farbe]."' style='background-color:".$farbe[$j][farbe]."' ".($_POST[auswahl][$i] == $farbe[$j][farbe]?"selected":"").">
                        ".$farbe[$j][name]."
                        </option>",true);
              }
             output("</select></td>",true);
         }
         output("</tr></table>",true);
      output("<br><br><input type='submit' name='rate' value='Tipp abgeben'>",true);
      output("</form>",true);
      $versuche=$__anzahl_versuche - count($_POST[versuche]) -1;
      if ($versuche!=0){
         addnav("Du hast noch");
         addnav("$versuche Versuche");
         addnav("");
         addnav("Zurück");
    addnav("Neues Spiel","dragonmind.php");
   addnav("Zur Spielauswahl","shades_spiele.php");
      }
   }
   else{ // fertig
     output("</table></form>",true);
     //schauen ob gewonnen oder Ende
     if ($gewonnen){
       output("<h1>DU HAST GEWONNEN</h1>",true);
       $session[user][gold]+=$__gewinn +$__einsatz;
//       output("`$ $zeit`0, `^".count($_POST[versuche])."`0`n");

    //Eintrag in Bestenliste?
    $sql="SELECT * from dragonbest ORDER BY id";
    $result=db_query($sql);
    $temp=array();
    $added=false;
    $platz=0;
    for($i=1; $i<=20; $i++) {
        $row=db_fetch_assoc($result);
        if($added==true){
            $sql2="UPDATE dragonbest SET time='".$temp['time']."', acctid='".$temp['acctid']."', name='".$temp['name']."', versuche='".$temp['versuche']."' WHERE id='".$i."'";
            $result2=db_query($sql2);
            $temp=$row;
        }
        
        if(($zeit<$row['time'] || ($zeit==$row['time'] && $_POST['versuche']<$row['versuche'])) && $added==false){
            $added=true;
            $platz=$i;
            $temp=$row;
            $sql2="UPDATE dragonbest SET time='".$zeit."', acctid='".$session['user']['acctid']."', name='".$session['user']['name']."', versuche='".count($_POST['versuche'])."' WHERE id='".$i."'";
            $result2=db_query($sql2);
        }
    }
    if($added){
        if($platz==1){
            output("<h1>Glückwunsch, du hast einen neuen Rekord aufgestellt</h1>",true);
            output("`n`6Ramius schenkt dir 100 Gefallen`n");
            $session['user']['deathpower']+=100;
            addnews($session['user']['name']." `6stellte einen neuen Rekord bei Dragonmind auf");
        } else {
            output("<h1>Glückwunsch, du hast es in die Liste der Besten geschaft</h1>",true);
            output("`n`6Ramius schenkt dir 50 Gefallen`n");
            $session['user']['deathpower']+=50;
            addnews($session['user']['name']." `6schaffte bei Dragonmind den Sprung unter die zwanzig besten Spieler");
        }
    } else {
        if($zeit<=120){
            output("`n`6Ramius schenkt dir 5 Gefallen`n");
            $session['user']['deathpower']+=5;
        } else {
            output("`n`6Ramius schenkt dir 2 Gefallen`n");
            $session['user']['deathpower']+=2;
        }
    }

       
     }
     else{
        output("<h1>DU HAST VERLOREN</h1>",true);
     }
     if ($session[user][gold]>=$__einsatz) addnav("Erneut spielen","dragonmind.php");
     addnav("Zurück");
   addnav("Zur Spielauswahl","shades_spiele.php");
   }

}elseif($_GET[op]=='regeln'){
   addnav("Zurück","dragonmind.php");
   output("`c`b`&Dragonmind Regeln `0`b`c`n`n");
   output("`Q ### `2allgemeiner Ablauf des Spiels `Q####`n`n
           `7Das Ziel des Spiels ist das Erraten der Farbkombination, die der Spielführer ausgewählt hat.`n
           Jede Farbe kommt nur EINMAL vor!`n
           Du wählst in den Drop-Down Feldern deine Farbkombination aus und drückst auf 'raten'`n
           Daraufhin erscheint deine Auswahl. Dahinter erscheinen farbige Punkte.`n`n

           Ein `$ roter Punkt `7 gibt an, dass eine deiner Farben in der Farbkombination des Spielführers vorkommt.`n
           Ein `@ grüner Punkt `7 gibt an, dass eine deiner Farben sogar an der richtigen Stelle ist.`n
           `qKein Punkt `7 hinter deiner Auswahl zeigt dir, dass keine deiner Farben in der Auswahl vorkommt.`n`n

           Die Reihenfolge der Punkte hat nichts mit der Reihenfolge deiner Auswahl zu tun!`n`n
           Du hast maximal 10 Versuche die richtige Auswahl zu erraten.`n`n
           P.S.  Bei 10 Farben gibt es 5040 verschiedene Farbkombinationen... Nur durch probieren wirst du es wohl eher nicht schaffen.`n`n
           www.plueschdrache.de
         ");
}
//show_array($_POST);
page_footer();
?>

