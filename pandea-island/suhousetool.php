<?php

/* WOHNVIERTEL-AUFRÄUMTOOL
written by Angel for www.pandea-island.de
modified by: aragon


Durch dieses Tool wird allen bewohnten oder im Bau stehenden Häusern eine neue ID zugewiesen
und verlassene Häuser gelöscht. Anschließend muss die AutoID-Konstante in der DB noch
manuell richtig gesetzt werden um die richtige Fortführung der Hausnummern sicher zu stellen.


info: if anything gets changed, add comments! or I'll punch you in the face over the internet !!!

changes:
2013-09-30    aragon     there has to be something repaired about the items
2013-09-30    aragon    "item-fix 1": Delete items inb4 houses get deleted
2013-09-30    aragon    "item-fix 2": SQL-statements and code better readable
2013-09-30    aragon    "performance-fix": killed idiotic queries ... srsly you dont need 2 loops if you can do it with 1 SQL statement

modification:
2013-09-30    aragon    "security-fix": if someone got inhere, he must be able to leave the same way. plus checkday removed. shades and village do checkday on their own
2013-09-30    aragon    commentaries should be moved and deleted too!!

*/

require_once "common.php";

isnewday(2); // 2013-09-30 by aragon ... "security-fix"

//if ($session[user][loggedin]) { // 2013-09-30 by aragon ... "security-fix"
//        checkday(); // checkday will be called on village.php and shades.php ... so we don't need it here
if ($session[user][alive]) {
        addnav("W?Zurück zum Weltlichen","village.php");
} else {
        addnav("W?Zurück zu den Schatten","shades.php");
}
//if ($session[user][superuser]>=3) // 2013-09-30 by aragon ... "security-fix"
    addnav("Zurück zur Grotte","superuser.php");
addnav("Hausmeister","suhouses.php");
//} // 2013-09-30 by aragon ... "security-fix"
page_header("SUPER-DUPER-WOHNVIERTELAUFRÄUMTOOL");

$todelete=0;

if ($_GET[op]=="delete"){
    adminlog(); // 2013-09-30 ... adminlog ! never forget!
    // 2013-09-30    aragon    delete old comments from "to be deleted" houses
    $sql="SELECT houseid FROM houses WHERE status >1";
    $res=db_query($sql);
    $todelete=db_num_rows($res);
    if($todelete>0)
    {
        while($row=db_fetch_assoc($res))
        {
                // 2013-09-30    by aragon    comments should be deleted to !!
            $sql="DELETE FROM commentary WHERE
            section=\"house-".$row['houseid']."\" OR
            section=\"private-".$row['houseid']."\" OR
            section=\"treasure-".$row['houseid']."\" ";
            db_query($sql);
        }
        // ** 2013-09-30 by aragon ... "item-fix 1"
            // the 'select' gives an array-like return, so we don't need to do more queries
        $sql="DELETE FROM items WHERE (class='Möbel' OR class='Schlüssel') AND value1 IN (SELECT houseid FROM houses WHERE status >1);";
        db_query($sql);
        // */ 2013-09-30 by aragon ... "item-fix 1"

        $delete1 = 'DELETE FROM `houses` WHERE status >1';
        db_query($delete1);

    //    output("`%alle blöden Häuser gelöscht`n`0"); // */ 2013-09-30 by aragon ... "item-fix 1" ... let's be nicer
        output("`%alle alten, unbenutzten, verschimmelten`nHäuser,`nSchlüssel,`nKommentare`n...gelöscht`n`0");
        $sql="select count(*) as a from houses";
        $r=db_fetch_assoc(db_query($sql));
        $sql="ALTER TABLE houses AUTO_INCREMENT = ".($r['a']+1);
        db_query($sql);
}else{
        output("`%es gibt nichts zu löschen!`n`0");
    }
}
elseif ($_GET[op]=="clean"){
    adminlog(); // 2013-09-30 ... adminlog ! never forget!
   $sqla = 'SELECT * FROM houses WHERE status <2 ORDER BY houseid ASC'; //alle bewohnten oder im Bau stehenden Häuser auswählen
   $resulta = db_query($sqla); //Befehl senden
   $maxa = db_num_rows($resulta);
   for($p=1;$p<=$maxa;$p++){
//        $rowa = db_fetch_assoc($resulta); // 2013-09-30    by aragon    too confusing, renaming every rowa to row...
        $row = db_fetch_assoc($resulta);
        if ($row['houseid']!=$p){
           $sqlb = "UPDATE `houses` SET `houseid` = $p  WHERE `houseid` = ".$row['houseid'].";";
           db_query($sqlb);
//           $sqlc = "UPDATE `items` SET value1 = $p WHERE value1=".$rowa[houseid]." AND class='Möbel'";            // 2013-09-30    by aragon    "item-fix 2"
//           db_query($sqlc);            // 2013-09-30    by aragon    "item-fix 2"
//           $sqld = "UPDATE `items` SET value1 = $p WHERE value1=".$rowa[houseid]." AND class='Schlüssel'";            // 2013-09-30    by aragon    "item-fix 2"
//           db_query($sqld);            // 2013-09-30    by aragon    "item-fix 2"
           if ($row[status]==1) $sqle= "UPDATE accounts SET house = $p, housekey=$p WHERE house=".$row['houseid'].";";
           if ($row[status]==0) $sqle= "UPDATE accounts SET house = $p WHERE house=".$row['houseid'].";";
           db_query($sqle);
            // 2013-09-30    by aragon    "item-fix 2"
            $sql = "UPDATE `items` SET value1 = $p WHERE value1=".$row[houseid]." AND (class='Möbel' OR class='Schlüssel')";
            db_query($sql);

            // 2013-09-30    by aragon    comments should be moved to !!
            $sql="UPDATE commentary SET section=\"house-".$p."\" WHERE section=\"houes-".$row['houseid']."\";";
            db_query($sql);
            $sql="UPDATE commentary SET section=\"private-".$p."\" WHERE section=\"private-".$row['houseid']."\";";
            db_query($sql);
            $sql="UPDATE commentary SET section=\"treasure-".$p."\" WHERE section=\"treasure-".$row['houseid']."\";";
            db_query($sql);

            // 2013-09-30    by aragon    output moved ... could be too confusing
           output("Haus {$row[houseid]} hat jetzt die Hausnummer $p");
           output("+ eventuelle Möbel verschoben");
           output("+ eventuelle Schlüssel verschoben");
           output("+ eventuell schlafende Bewohner verschoben");
           output("+ Besitzer wurde umgeschrieben");


        }else{
              output("Haus ".$row[houseid]." musste nicht verschoben werden");
        }

        output("`n");     // 2013-09-30    by aragon    cosmetic fix ... so that it is better readable
   }
   for ($l=1;$l<$maxa;$l++){
       $sql = "UPDATE items SET description='Schlüssel für Hausnummer $l' WHERE class='Schlüssel' AND value1=$l";
       db_query($sql);
   }
    addnav("V?Zurück zur Auflistung","suhousetool.php");

}
else
{
    $sql = 'SELECT h.*,a.name FROM houses h LEFT JOIN accounts a on h.owner=a.acctid WHERE status <2 ORDER BY houseid ASC'; //alle bewohnten oder im Bau stehenden Häuser auswählen
    $result = db_query($sql); //Befehl senden
    $max = db_num_rows($result);


    // Start der Übersichtstabelle
    output("`%Ersetzung:");
    output("<br><br><table  cellpadding=2 cellspacing=1 border=0 bgcolor='#999999'>",true);
    output("<tr><td>",true);
    output("`^owner`0");
    output("</td><td colspan=\"2\">",true);
    output("`^Haus");
    output("</td><td>",true);
    output("`^neue Hausnummer wäre`0");
    output("</td><td>",true);
    output("`^mitzunehmende Möbel`0");
    output("</td><td>",true);
    output("`^mitzunehmende Schlüssel`0");
    output("</td><td>",true);
//    output("`^mitzunehmende Wertsachen`0"); // 20130930 ... unnötig, gold und gems werden im haus gespeichert
//    output("</td><td>",true);
    output("`^mitzunehmende Schlafmützen`0");
    output("</td></tr>",true);

    // Tabelleneinträge: Bewohnte Häuser und ihre Eigenschaften auflisten
    for($i=1;$i<=$max;$i++){
            $row = db_fetch_assoc($result);
            output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);
            output("`^$row[name]`0"); //bewohntes Haus
            output("</td><td>",true);
            output("`^$row[housename]`0"); //bewohntes Haus
            output("</td><td>",true);
            output("`^$row[houseid]`0"); //bewohntes Haus
            output("</td><td>",true);
            output("$i");      //neue Hausnummer
            output("</td><td>",true);
            //Möbel raussuchen
            $sql2 = "SELECT name FROM items WHERE value1=$row[houseid] AND class='Möbel' ORDER BY class,id ASC";
            $result2 = db_query($sql2) or die(db_error(LINK));
            while ($item = db_fetch_assoc($result2)) {
                  output("`n`&$item[name]`0");
            }
            output("</td><td>",true);
            //Schlüssel raussuchen
            $sql3 = "SELECT name FROM items WHERE value1=$row[houseid] AND class='Schlüssel' ORDER BY class,id ASC";
            $result3 = db_query($sql3) or die(db_error(LINK));
            $maxs = db_num_rows($result3);
            /*
            $a=1;
            // warum so herum? wäre doch viel einfacher einfach $maxs dazu zu zählen ...
            while ($a<=$maxs){
                  $a++;
            }
            */
            output("$maxs Schlüssel");
            output("</td><td>",true);
            //mitzunehmende Goldstücke/Edelsteine // unnötig weils im haus gespeichert wird !
//            output("$row[gold] Gold, $row[gems] Gems");
//            output("</td><td>",true);
            //mitzunehmende Schlafmützen
/*            $sql4 = "SELECT * FROM items WHERE value1=$row[houseid] AND class='Schlüssel' AND hvalue>0";
            $result4 = db_query($sql4) or die(db_error(LINK));
            $count = db_num_rows($result4);
            if ($count==0){
               output("");
            }else{
                  for($x=1;$x<=$count;$x++){
                     $row4 = db_fetch_assoc($result4);
                     $sql5 = "SELECT * FROM accounts WHERE acctid=$row4[owner]";
                     $result5 = db_query($sql5) or die(db_error(LINK));
                     $row5 = db_fetch_assoc($result5);
                     output("$row5[name]`n`0");
                  }

            }
*/

// *** 2013-09-30    aragon    "performance-fix"
            $sql4 = "SELECT i.value1,i.hvalue,i.class,a.acctid,a.name FROM items i LEFT JOIN accounts a ON i.owner=a.acctid WHERE i.value1=$row[houseid] AND i.class='Schlüssel' AND i.hvalue>0";
            $result4 = db_query($sql4) or die(db_error(LINK));
            $count = db_num_rows($result4);
            if ($count==0){
               output("");
            }else{
                  while($row4 = db_fetch_assoc($result4))
                     output("$row4[name]`n`0");
            }
// *** 2013-09-30    aragon    "performance-fix"



            output("</td></tr>",true);

    }
    output("</table>",true);
}

addnav("Aktualisieren","suhousetool.php");
addnav("`%Achtung!!`0");
addnav("`%Nur mit Ankündigung!!`0");
if($todelete>0)addnav("V?Aufräumen","suhousetool.php?op=clean");
else addnav("Verlassene Häuser löschen","suhousetool.php?op=delete");
page_footer();
?> 