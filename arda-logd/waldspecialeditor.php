<?php
/* MOD:
Ersteller: www.plueschdrache.de
Datum: irgendwann 2004
Descr: - Ermöglicht jedem Waldspecial eine gewisse Wahrscheinlichkeit zuzuweisen, wie oft es auftritt
       - Ermöglicht eine bessere Übersicht über die Waldspecials --> Descr-Feld
       - Zählt die Häufigkeit wie oft welches Skript aufgerufen wurde
       - Ermöglicht einzele Specials erst ab einem höheren DK zu spielen
       - ist schön bunt

Sonst: Alles zwischen den "~~~~~" Linien darf gelöscht werden. "www.plueschdrache.de" darf nicht gelöscht / verändert / ausgeblendet werden!
       --> Die user sehen's ja nicht .. also keine Panik
*/

//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
// INSTALL
/*
/////////////////////
// Datenbank Teil: //
////////////////////

// führe diesen Code im PHPmyAdmin o.ä. aus.

CREATE TABLE `waldspecial` (
  `row_id` int(11) unsigned NOT NULL auto_increment,
  `filename` varchar(255) default '0',
  `descr` text,
  `prio` int(5) unsigned default '0',
  `dk` int(5) unsigned default '0',
  `anzahl` int(11) unsigned default '0',
  PRIMARY KEY  (`row_id`(,
  KEY `row_id` (`row_id`,`prio`,`dk`(
) TYPE=MyISAM;


/////////////////
// Datei Teil: //
/////////////////
1. Datei ins root Verzeichnis kopieren
2. superuser.php öffen und irgendwo in der Mitte
   if ($session[user][superuser]>2){
    addnav("www.plueschdrache.de");   // *g*
    addnav("Waldspecial-Editor","waldspecialeditor.php");
   }
3. öffne forest.php
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
!!! Wir haben deutlich mehr an der forest gedreht... darum ist dieses Stückchen Quellcode NICHT GETESTET!!!        !!!
!!! fragt Meteora --> der baut in letzter Zeit öfters gute Einbauanleitungen *g*                                   !!!
!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

suche:
-------
$events = array();
while (false !== ($file = readdir($handle))){
if (strpos($file,".php")>0){
// Skip the darkhorse if the horse knows the way
if ($session['user']['hashorse'] > 0 &&
$playermount['tavern'] > 0 &&
strpos($file, "darkhorse") !== false) {
continue;
}
array_push($events,$file);
}
}
$x = e_rand(0,count($events)-1);
if (count($events)==0){
output("`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben.  Beschwer dich bei ihm, nicht beim Programmierer.");
}else{
$y = $HTTP_GET_VARS[op];
$HTTP_GET_VARS[op]="";
//echo "$x including special/".$events[$x];
include("special/".$events[$x]);
$HTTP_GET_VARS[op]=$y;
}
}else{
output("`c`b`\$FEHLER!!!`b`c`&Es ist nicht möglich die besonderen Ereignisse zu öffnen! Bitte benachrichtige den Administrator!!");
}

ersetzte mit:
-------------
// Skip the darkhorse if the horse knows the way
if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0) {$sql_add=" AND filename <> 'darkhorse.php'";}
$waldspecial = @mysql_result(mysql_query("SELECT filename FROM waldspecial WHERE prio <= ".e_rand(0,3)." AND dk <=".$session[user][dragonkills]." ORDER BY RAND() LIMIT 1"),0,"filename");
if ($waldspecial=='') {output("`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben.  Beschwer dich bei ihm, nicht beim Programmierer. Es könnte natürlich auch sein, dass es kein Waldspecial gibt, das für dich freigeschalten ist... zu dumm..");}
$y = $HTTP_GET_VARS[op];
$HTTP_GET_VARS[op]="";
include("special/".$waldspecial);
$HTTP_GET_VARS[op]=$y;

4. Das wars..
*/
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
//~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

require_once "common.php";
page_header("Einstellungen Waldspecial");
addnav("Waldspecialeditor");
addnav("Files aktualisieren","waldspecialeditor.php?op=neu");
addnav("Eigenschaften festlegen","waldspecialeditor.php?op=edit");
addnav("Zurück");
addnav("Zur Grotte","superuser.php");
addnav("Zum Wald","forest.php");


if ($HTTP_GET_VARS[op]==''){
  $ausgabe.='`7<h3>Kleiner Waldspecialeditor by www.plueschdrache.de</h3>
              Mit diesem Tool könnt ihr festlagen welches Waldspecial ab welchem DK mit welcher Häufigkeit kommen soll.`n
              Sicherlich nicht der Weisheit letzter Schuss ähm Schluss, jedoch sicherlich besser als das Orginal.`n`n
              MfG `qde`QZ`qent`7';
}
elseif ($HTTP_GET_VARS[op]=='neu'){

    if ($handle = @opendir("special")){
        $filename = array();
        while (false !== ($file = @readdir($handle))){
            if (strpos($file,".php")>0){
                array_push($filename,$file);
            }
        }
        if (count($filename)==0){
             $ausgabe.="`b`@<h1>Keine Waldspecials-- keine Einstellungen... So ist das Leben.</h1>`n";
        }else{
            output("`7<b>Waldspecial Einstellungen:</b><br>",true);

            // eingetragene specials auslesen
            $sql="SELECT filename FROM waldspecial";
            $result=mysql_query($sql);
            $anzahl=mysql_num_rows($result);
            // in array speichern
            while($row=mysql_fetch_assoc($result)){
              $files[$row[filename]]='yupp';
            }
            // checken
            $i=0;
            while (list($key,$val)=each($filename)){
              if ($files[$val]!='yupp'){
                $sql="INSERT INTO waldspecial (row_id, filename, descr, prio, dk, anzahl) VALUES (NULL, '".$val."', 'keine Beschreibung vorhanden', 0, 0, 0)";
                mysql_query($sql);
                $i++;
              }
              else{
                $files[$val]='alt';
              }
            }
            if ($i) $ausgabe.="<h3>Es wurden <b><u>$i</u></b> neue Waldspecials eingetragen. Diese können jetzt angepasst werden</h3><br>";
                }
    }else{
         $ausgabe.="`c`b`\$FEHLER!!!`b`c`&Kann den Ordner mit den Waldspecials nicht finden. Bitte benachrichtige den Admin!! Du bist der Admin?!?... Ja... das könnte sich zum Problem entwickeln";
    }

        // gelöschte Waldspecials aus DB löschen
        $j=0;

        if (count($files)){
           reset($files);
           while (list($key,$val)=each($files)){
            if ($val!='alt'){
               $sql="DELETE FROM waldspecial WHERE filename='$key'";
               mysql_query($sql);
               $ausgabe.="$sql <br>";
               $j++;
            }
          }
        }

        if ($j) $ausgabe.="<h3>Es wurden <b><u>$j</u></b> neue Waldspecials aus der Datenbank gelöscht</h3><br>";

        if ($ausgabe=='') $ausgabe='<h2>Es gibt keine Veränderungen im special-Ordner... </h2>';

}
elseif($HTTP_GET_VARS[op]=='edit'){
    $sql="SELECT * FROM waldspecial ORDER BY filename";
    $result=mysql_query($sql);
    $anzahl=mysql_num_rows($result);
 if ($anzahl){
    $namen[0] ='deZent';
    $namen[1] ='draKarr';
    $namen[2] ='Kwaen';
    shuffle ($namen);
    $name=$namen[0].' / '.$namen[1].' / '.$namen[2];
    $ausgabe.="
    `n`n
     Waldspecial Editor by $name`n`n
     Priorität absteigend! Je niedrieger die Prio ist, desto öfters kommt das Special dran!`n
     Achte darauf, dass mind. ein Waldspecial Prio 0 und DK 0 hat!`n
`n`n .. nicht schön... aber selten...`n
     <form action='waldspecialeditor.php?op=save' method='POST'>";
    addnav("","waldspecialeditor.php?op=save");
    $ausgabe.="<table width='600px'>";
    $ausgabe.="<tr>
               <td>SpecialNr.</td>
               <td>file-Name</td>
               <td>Priorität</td>
               <td>MinDk</td>
               <td>Anzahl</td>
               <td>Beschreibung</td>
             </tr>";
     $i=0;
    while($row=mysql_fetch_assoc($result)){
       $color[0]='#008000';
       $color[1]='#14EAD3';
       $color[2]='#E6E629';
       $color[3]='#F26A10';
       $color[4]='#FF0000';

       $ausgabe.='<tr style="background-color:'.$color[$row[prio]].'">';
       $ausgabe.="<td>".($i+1)."</td>";
       $ausgabe.="<td><font size=+1 color=black>$row[filename]</font></td>";
       $ausgabe.="<td><select name='data[".$i."][prio]'>
                        <option value='0' ".($row[prio]=='0'?"selected":"")." style='background-color:".$color[0]."; color:black;'>sehr häufig</option>
                        <option value='1' ".($row[prio]=='1'?"selected":"")." style='background-color:".$color[1]."; color:black;'>häufig</option>
                        <option value='2' ".($row[prio]=='2'?"selected":"")." style='background-color:".$color[2]."; color:black;'>recht selten</option>
                        <option value='3' ".($row[prio]=='3'?"selected":"")." style='background-color:".$color[3]."; color:black;'>sehr selten</option>
                        <option value='4' ".($row[prio]=='4'?"selected":"")." style='background-color:".$color[4]."; color:black;'>deaktiviert</option>
                       </select>
                 </td>";
       $ausgabe.="<td><font color=black><b>DK</b></font><input type='text' name='data[".$i."][dk]' value='$row[dk]' size='3'>
                 </td>";
       $ausgabe.="<td><font color=black></font><input type='text' name='data[".$i."][anzahl]' value='$row[anzahl]' size='5'>
                 </td>";
       $ausgabe.="<td><textarea name='data[".$i."][descr]' rows='3' cols='40'>$row[descr]</textarea></td>";
       $ausgabe.="<input type='hidden' name='data[".$i."][filename]' value='$row[filename]'>";
       $ausgabe.="<input type='hidden' name='data[".$i."][row_id]' value='$row[row_id]'>";
       $ausgabe.='</tr>';
    $i++;
    }

    $ausgabe.="</table><br>";
    $ausgabe.="<input type='submit' name='s1' value='Einstellungen speichern'></form>";
 } // ende check ob was in DB steht
 else{  // steht nix in DB
   $ausgabe.='<h1>Du solltest erstmal ein paar Specials importieren!</h1>';
 }
}
elseif($HTTP_GET_VARS[op]=='save'){
  for ($i=0;$i<count($HTTP_POST_VARS[data]);$i++){

    $sql='UPDATE waldspecial SET prio='.$HTTP_POST_VARS[data][$i][prio].', dk='.$HTTP_POST_VARS[data][$i][dk].', descr="'.mysql_escape_string($HTTP_POST_VARS[data][$i][descr]).'", anzahl='.$HTTP_POST_VARS[data][$i][anzahl].' WHERE row_id='.$HTTP_POST_VARS[data][$i][row_id] ;
    mysql_query($sql);
    //$ausgabe.=$HTTP_POST_VARS[data][$i][filename].'--> "'.$sql.'" <br><br>';
    $check= mysql_error();
    if ($check!='')  $ausgabe.='<br><b>'.$check.'</b><br>';
    $ausgabe.='<h2>Jupp, das wars.</h2>';
  }
}

output("$ausgabe",true);
page_footer();
?>