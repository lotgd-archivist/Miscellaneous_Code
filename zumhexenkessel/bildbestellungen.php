<?php

# Idee, Texte, Bilder und Umsetzung by Shaddar und Filyina
# (C) Copyright by Soul of the black Dragon
# http://www.soul-of-the-black-dragon.de

/*
CREATE TABLE IF NOT EXISTS `bildbestellungen` (
  `id` int(11) NOT NULL auto_increment,
  `ersteller` int(11) NOT NULL,
  `bearbeiter` int(11) NOT NULL,
  `datum` datetime NOT NULL,
  `alter` int(11) NOT NULL,
  `augenfarbe` varchar(125) collate latin1_german2_ci NOT NULL,
  `typ` varchar(125) collate latin1_german2_ci NOT NULL,
  `kuenstler` varchar(125) collate latin1_german2_ci NOT NULL,
  `frisur` varchar(125) collate latin1_german2_ci NOT NULL,
  `geschlecht` varchar(125) collate latin1_german2_ci NOT NULL,
  `haarfarbe` varchar(125) collate latin1_german2_ci NOT NULL,
  `haarlaenge` varchar(125) collate latin1_german2_ci NOT NULL,
  `hautfarbe` varchar(125) collate latin1_german2_ci NOT NULL,
  `statur` varchar(125) collate latin1_german2_ci NOT NULL,
  `besonderes` text collate latin1_german2_ci NOT NULL,
  `pose` text collate latin1_german2_ci NOT NULL,
  `szene` text collate latin1_german2_ci NOT NULL,
  `ruestungen` text collate latin1_german2_ci NOT NULL,
  `waffen` text collate latin1_german2_ci NOT NULL,
  `infos` text collate latin1_german2_ci NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german2_ci AUTO_INCREMENT=7 ;
*/

require_once "common.php";
page_header("Bildbestellungen",false);
isnewday("kuenstler");

$codes = "`p`Q`w`q`U`6`M`^`Y`x`X";

page_header("Bildbestellungen",false);
setPageFirstTitle(colorText("Bildbestellungen", $codes, "fb", 1));
setPageDescription(colorText(SERVER_NAME." bietet seinen Spielerinnen und Spielern ein ganz besonderes Feature - die Avatarbestellung. ".
"Einzigartig auf allen LoGD-Servern haben diese so die Möglichkeit einen Steckbrief entsprechend Ihren Wünschen auszufüllen und im Anschluss daran ein passendes, maßgeschneidertes Bild zu erhalten. ".
"An dieser Stelle kommst du ins Spiel - den als eingetragener Künstler hast du in diesem Bereich einen Überblick über alle eingegangenen Bildbestellungen, deren hinterlegte Steckbriefe usw.", $codes));

if ($_GET['op']=="") {
    if ($_GET['act']=="loeschen" && $_GET['id']!="") {
        statusmeldung(0,"Bildbestellung wurde erfolgreich gelöscht.");
        $sql = "DELETE FROM bildbestellungen WHERE id=".$_GET['id'];
        db_query($sql);
    }

    $sql = "SELECT bildbestellungen.*, a1.name AS autor, a2.name AS umsetzer FROM bildbestellungen LEFT JOIN accounts a1 ON a1.acctid=bildbestellungen.ersteller LEFT JOIN accounts a2 ON a2.acctid=bildbestellungen.bearbeiter ORDER BY datum ASC";
    $result = db_query($sql);
    output("<table border='0' cellspacing='0' cellpadding='2' width='100%'>",true);
    output("<tr class='trhead'><td>ID:</td> <td>Aufgabe:</td> <td>Eingegangen:</td> <td>Künstler:</td></tr>",true);
    for($i=0;$i<db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td>`&# ".$row['id']."`0</td> <td><a href='bildbestellungen.php?op=ansehen&id=".$row['id']."'>`g".$row['typ']."`0</a><br /><font size='-2'>`&für`0 ".$row['autor']."</font></td>",true);
        output("<td>`&".date("d.m.Y, H:i",strtotime($row['datum']))." Uhr`0</td> <td>".($row['umsetzer']!=""?$row['umsetzer']:"Niemand")."</td>",true);
        addnav("","bildbestellungen.php?op=ansehen&id=".$row['id']);
    }
    output("</table>`n`n",true);

} else if ($_GET['op']=="ansehen") {
    if ($_GET['act']=="speichern" && $_GET['id']!="") {
        statusmeldung(0,"Bildbestellung wurde erfolgreich gespeichert.");
        $sql = "UPDATE bildbestellungen SET `alter`=".$_POST['alter'].",augenfarbe='".stripslashes($_POST['augenfarbe'])."',frisur='".stripslashes($_POST['frisur'])."',haarfarbe='".stripslashes($_POST['haarfarbe'])."',haarlaenge='".stripslashes($_POST['haarlaenge'])."',hautfarbe='".stripslashes($_POST['hautfarbe'])."',statur='".stripslashes($_POST['statur'])."',besonderes='".stripslashes($_POST['besonderes'])."',pose='".stripslashes($_POST['pose'])."',szene='".stripslashes($_POST['szene'])."',ruestungen='".stripslashes($_POST['ruestungen'])."',waffen='".stripslashes($_POST['waffen'])."',infos='".stripslashes($_POST['infos'])."' WHERE id=".$_GET['id'];
        db_query($sql);
    } else if ($_GET['act']=="uebernehmen" && $_GET['id']!="") {
        statusmeldung(0,"Bildbestellung wurde erfolgreich übernommen.");
        $sql = "UPDATE bildbestellungen SET bearbeiter=".$session['user']['acctid']." WHERE id=".$_GET['id'];
        db_query($sql);
    } else     if ($_GET['act']=="abgeben" && $_GET['id']!="") {
        statusmeldung(0,"Bildbestellung wurde erfolgreich abgegeben.");
        $sql = "UPDATE bildbestellungen SET bearbeiter=0 WHERE id=".$_GET['id'];
        db_query($sql);
    }

    $sql = 'SELECT bildbestellungen.*, a1.name AS autor, a2.name AS umsetzer FROM bildbestellungen LEFT JOIN accounts a1 ON a1.acctid=bildbestellungen.ersteller LEFT JOIN accounts a2 ON a2.acctid=bildbestellungen.bearbeiter WHERE bildbestellungen.id='.$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);

    output("<form action='bildbestellungen.php?op=ansehen&act=speichern&id=".$_GET['id']."' method='POST'>",true);
    output("<table border='0' cellspacing='2' cellpadding='0'>",true);
    output("<tr class='trhead'><td colspan='2'>".$row['typ']." für ".($row['autor']==""?"Niemand":$row['autor'])."`0 wird derzeit von ".($row['umsetzer']==""?"Niemand":$row['umsetzer'])."`0 bearbeitet:</td></tr>",true);
    output("<tr><td>`hAlter (optisch):`0</td> <td> <input type='text' name='alter' size='3' value='".$row['alter']."' /> `&Jahre`0</td></tr>",true);
    output("<tr><td>`hAugenfarbe:`0</td> <td> <input type='text' name='augenfarbe' size='35' value='".farbcodes(stripslashes($row['augenfarbe']))."' /></td></tr>",true);
    output("<tr><td>`hBilderart:`0</td> <td>`&".$row['typ']."`0</td></tr>",true);
    output("<tr><td>`hKünstler:`0</td> <td>`&".$row['kuenstler']."`0</td></tr>",true);
    output("<tr><td>`hFrisur (offen, gebunden, etc.):`0</td> <td><textarea name='frisur' cols='35' rows='4'>".farbcodes(stripslashes($row['infos']))."</textarea></td></tr>",true);
    output("<tr><td>`hGeschlecht:`0</td> <td>`&".$row['geschlecht']."`0</td></tr>",true);
    output("<tr><td>`hHaarfarbe:`0</td> <td> <input type='text' name='haarfarbe' size='35' value='".farbcodes(stripslashes($row['haarfarbe']))."' /></td></tr>",true);
    output("<tr><td>`hHaarlänge:`0</td> <td> <input type='text' name='haarlaenge' size='35' value='".farbcodes(stripslashes($row['haarlaenge']))."' /></td></tr>",true);
    output("<tr><td>`hHautfarbe:`0</td> <td> <input type='text' name='hautfarbe' size='35' value='".farbcodes(stripslashes($row['hautfarbe']))."' /></td></tr>",true);
    output("<tr><td>`hStatur:`0</td> <td> <input type='text' name='statur' size='35' value='".farbcodes(stripslashes($row['statur']))."' /></td></tr>",true);
    output("<tr><td>`hBesonderes (Schmuck, Tätowierungen, etc.):`0</td> <td><textarea name='besonderes' cols='35' rows='4'>".farbcodes(stripslashes($row['besonderes']))."</textarea></td></tr>",true);
    output("<tr><td>`hMimik / Gesichtsausdruck:`0</td> <td><textarea name='pose' cols='35' rows='4'>".farbcodes(stripslashes($row['mimik']))."</textarea></td></tr>",true);
    output("<tr><td>`hHaltung / Pose (Kampf, Sitzend, etc.):`0</td> <td><textarea name='pose' cols='35' rows='4'>".farbcodes(stripslashes($row['pose']))."</textarea></td></tr>",true);
    output("<tr><td>`hHintergrund / Szene:`0</td> <td><textarea name='szene' cols='35' rows='4'>".farbcodes(stripslashes($row['szene']))."</textarea></td></tr>",true);
    output("<tr><td>`hKleidung / Rüstung:`0</td> <td><textarea name='ruestungen' cols='35' rows='4'>".farbcodes(stripslashes($row['ruestungen']))."</textarea></td></tr>",true);
    output("<tr><td>`hWaffen:`0</td> <td><textarea name='waffen' cols='35' rows='4'>".farbcodes(stripslashes($row['waffen']))."</textarea></td></tr>",true);
    output("<tr><td>`hZusätzliche Informationen:`0</td> <td><textarea name='infos' cols='35' rows='4'>".farbcodes(stripslashes($row['infos']))."</textarea></td></tr>",true);
    output("<tr><td></td> <td><input type='submit' value='Speichern' /></td></tr>",true);
    output("</table>",true);
    output("</form>`n`n",true);
    output("<a href='bildbestellungen.php?act=loeschen&id=".$row['id']."' onClick=\"return confirm('Bist du sicher, dass du diese Bildbestellung löschen möchtest ?');\">".button("loeschen")."</a> ".($session['user']['acctid']!=$row['bearbeiter']?"<a href='bildbestellungen.php?op=ansehen&act=uebernehmen&id=".$_GET['id']."'>".button("uebernehmen")."</a>":"")." ".($session['user']['acctid']==$row['bearbeiter']?"<a href='bildbestellungen.php?op=ansehen&act=abgeben&id=".$_GET['id']."'>".button("abgeben")."</a>":""),true);
    addnav("","bildbestellungen.php?op=ansehen&act=speichern&id=".$_GET['id']);
    addnav("","bildbestellungen.php?act=loeschen&id=".$_GET['id']);
    addnav("","bildbestellungen.php?op=ansehen&act=uebernehmen&id=".$_GET['id']);
    addnav("","bildbestellungen.php?op=ansehen&act=abgeben&id=".$_GET['id']);
}

addnav("Aktionen");
addnav("Aktualisieren","bildbestellungen.php");
addnav("Sonstiges");
if ($_GET['op']!="") addnav("Zu Bildbestellungen","bildbestellungen.php");
addnav("Zur Grotte","superuser.php");
addnav("Zum Weltlichen","village.php");

page_footer();

?> 