<?php

/*
Berufe Script (0.5v)
by Savomas & Gimmick (a.k.a.Tiresias)
http://www.ocasia.de
------------------------------
Dies ist die Kontrolldatei für das Berufescript
Änderungen sollten möglichst nicht vorgenommen werden
es sei denn es handelt sich um bugs, allerdings  benachrichtigt ihr mich 
dann am besten, damit ich den fehler beheben kann
------------------------------
0.1v 
     -Erschaffen von Jobs und Editieren

0.2v
    -Bugs behoben
    - Source optimiert
    -Löschen von Jobs freigeschaltet
    -Runden variable geaddet
0.3v
    -Kleinere Bugfixes
    -Edit Funktion optimiert
    -Die Gestaltung umgestellt
0.4v
    -letzte bekannte Bugs ausgearbeitet
    -alles freigeschaltet
    -abgeschlossen
0.5v
    -Newjob-Bug behoben
    -Texte erweitert
    -Code Optimiert
-------------------------------
THX an Kevz (für seine sql-Hilfe-hotline *lol*)
*/
        require_once "common.php";
        addcommentary();
        page_header("Jobs erstellen");
if ($HTTP_GET_VARS['op']==""){

        if (!empty($_GET['saveorder'])) {
        asort($_POST['order']);
        $keys = array_keys($_POST['order']);
        $i = 0;
        foreach ($keys AS $key) {
            $i++;
            $sql = 'UPDATE jobs SET listorder="'.$i.'" WHERE id="'.$key.'"';
            db_query($sql);
        }
    }

            output("Übersicht  über die Jobs`n`n");
//Beginn der Übersichts Tabelle
    output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>Optionen</td><td>Name</td><td>Lohn</td><td>Runden</td></tr>",true);
    $sql = "SELECT name,lohn,turns,id FROM jobs";

    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0) {
        output("<tr class='trdark'><td colspan=5 align='center'>`&`iEs gibt noch keine Jobs`i`0</td></tr>",true);
    }
    else {
        $bgclass = '';
        while ($row = db_fetch_assoc($result)) {
            $bgclass = ($bgclass=='trdark'?'trlight':'trdark');
            output("<tr class='$bgclass'><td><a href=\"su-job.php?op=editjob&id=".$row['id']."\">Edit</a>  | 
            <a href=\"su-job.php?op=deljob&id=".$row['id']."\">",true);
            output("`0Delete</a></td><td>".$row['name']."</td><td>".$row['lohn']."</td><td>".$row['turns']."",true);
            output('</td></tr>',true);
            addnav("","su-job.php?op=editjob&id=".$row['id']);
            addnav("","su-job.php?op=deljob&id=".$row['id']);
        }
    }
    output("</table>",true);
//Ende der Übersichts-Tabelle
    addnav("Neuen Job erstellen","su-job.php?op=newjob");
    addnav("Zurück zur Grotte","superuser.php");
        }
//Neue Jobs können hier erstellt werden
if ($HTTP_GET_VARS['op']=="newjob"){
    addnav("Zur Jobübersicht","su-job.php");
//Die Eingabefelder
    output("<form action=\"su-job.php?op=newsave\" method='POST'>",true);
    output("<table><tr><td>Job (Name) </td><td><input name='name' maxlength='30'></td></tr>",true);
    output("<tr><td>Lohn </td><td><input name='lohn' maxlength='3'></td></tr>",true);
    output("<tr><td>Runden (Wieviele muss der Spieler IG-täglich Aufwenden?)</td><td><input name='turns' maxlength='3'></td></tr>",true);
    output("<tr><td>Ausbildungsid</td><td><input name='aubid' maxlength='2'></td></tr>",true);
    output("</table><input type='submit' class='button' value='Speichern'></form>",true);
    addnav("","su-job.php?op=newsave");
    output("`n");
//Ende Eingabefelder
//Jobs die Bereits vorhanden sind werden angezeigt
    output("`bVorhandene Jobs:`b`n`n");
    $sql = "SELECT name,lohn FROM jobs ORDER BY listorder ASC";
    $result = db_query($sql) or die(db_error(LINK));
    if(db_num_rows($result)==0) {
        output("Es gibt keine Jobs.");
    }
        else while($row = db_fetch_assoc($result)) {
        output($row['name']." `6(".$row['lohn']." Gold)`0`n");
        }
    }
//War in der 0.4v noch eine subop der newjob, führte aber zu einem unangenehmen Bug
if ($_GET['op']=="newsave") {
        $sql = 'SELECT MAX(listorder) AS maxjob FROM jobs';
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $row['maxjob']++;
        $sql = "INSERT INTO jobs (name,id,lohn,aubid,turns,listorder) VALUES ('".$_POST['name']."','".$_POST['id']."','".$_POST['lohn']."', '".$_POST['aubid']."','".$_POST['turns']."',{$row['maxjob']})";
        $result = db_query($sql) or die(db_error(LINK));
        output("`\$Neuer Job wurde angelegt.`0`n`n");
        output ("<a href=\"su-job.php?op=newjob\">`0 Noch einen neuen Job erstellen</a>",true);
        addnav ("","su-job.php?op=newjob");
        addnav ("Zurück zur Jobübersicht","su-job.php");

    }
//Editieren der Jobs
if ($HTTP_GET_VARS['op']=="editjob"){
    addnav("Zur Jobübersicht","su-job.php");
    addnav("Zur Grotte","superuser.php");
    if ($_GET['subop']=="save") {
        $_POST['name'] = addslashes(closetags(stripslashes($_POST['name']),'`i`b`c`H'));
        $_POST['lohn'] = addslashes(closetags(stripslashes($_POST['lohn']),'`i`b`c`H'));
        $_POST['turns'] = addslashes(closetags(stripslashes($_POST['turns']),'`i`b`c`H'));
        $_POST['aubid'] = addslashes(closetags(stripslashes($_POST['aubid']),'`i`b`c`H'));
        $sql = "UPDATE jobs SET name='".$_POST['name']."',turns='".$_POST['turns']."',lohn='".$_POST['lohn']."',aubid='".$_POST['aubid']."' WHERE id=".$_GET['id'];
        $result = db_query($sql) or die(db_error(LINK));
        //output("Job wurde geändert.`n`n");
        redirect("su-job.php");
    }
    else {
        output("Hier kann der Job geändert werden.");
        $sql = "SELECT name,lohn,turns,aubid FROM jobs WHERE id=".$_GET['id']."";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("<form action=\"su-job.php?op=editjob&subop=save&id=".$_GET['id']."\" method='POST'>
            Name: <input name='name' value='".htmlentities(str_replace("`","``",$row['name']),ENT_QUOTES)."' maxlength='30' size='30'>`n
            Lohn: <input name='lohn' value='".htmlentities(str_replace("`","``",$row['lohn']),ENT_QUOTES)."' maxlength='3' size='10'>`n
            Runden: <input name='turns' value='".htmlentities(str_replace("`","``",$row['turns']),ENT_QUOTES)."' maxlength='3' size='5'>`n
            Ausbildungsid: <input name='aubid' value='".htmlentities(str_replace("`","``",$row['aubid']),ENT_QUOTES)."' maxlength='1' size='5'>`n
            `n<input type='submit' class='button' value='Speichern'></form>",true);
        addnav("","su-job.php?op=editjob&subop=save&id=".$_GET['id']);
    }
}
//Löschen von Jobs
if ($HTTP_GET_VARS['op']=="deljob"){
$sql = "SELECT name,id,aubid FROM jobs";
          $result = db_query('SELECT `name`,`id` FROM `jobs` WHERE `id` = '.$_GET['id']);
              while ($del = db_fetch_assoc($result)) {
output ("`$ `b Bist du sicher dass du den Job `0 ".$del['name']." mit der ID ".$del['id']." löschen willst?");
addnav ("`$ `b ".$del['name']."`b Löschen","su-job.php?op=deljob2&id=".$del['id']);
addnav ("Nichts löschen","su-job.php");
}
}
if ($HTTP_GET_VARS['op']=="deljob2"){
    addnav("Zur Jobübersicht","su-job.php");
        $sql = "DELETE FROM jobs WHERE id=".$_GET['id'];
        db_query($sql) or die(db_error(LINK));
    output("Job gelöscht.");
}

page_footer();
?> 