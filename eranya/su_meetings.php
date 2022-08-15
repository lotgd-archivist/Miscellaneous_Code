
<?php
//06022009 <- ich frage mich, wieso ich das in jedes Skript schreibe, es ist ja doch nicht aktuell^^
/********************************************
* Kalender für Grottentreffen jeglicher Art *
* von n2code (Takehon/Takeo/Knightnike)     *
* n2code@herr-der-mails.de                  *
********************************************/

/*
CREATE TABLE `meetings` (
`meetingid` INT NOT NULL AUTO_INCREMENT ,
`name` VARCHAR( 100 ) NOT NULL ,
`date` INT NOT NULL ,
`group` VARCHAR( 100 ) NOT NULL ,
`priority` INT NOT NULL ,
`participants` TEXT NOT NULL ,
`comments` TEXT NOT NULL ,
`description` TEXT NOT NULL ,
PRIMARY KEY ( `meetingid` )
) ENGINE = MYISAM 
*/

require_once "common.php";
if ($_GET['type']=="access"){
page_header("Terminkalender");
$str_self=basename(__FILE__)."?type=access";
$str_output = "`c`b`&Terminkalender für Grottentreffen`b`c`n`n";
addnav('Zur Grotte','superuser.php');
addnav("Aktionen");
}

function meeting_countdown($accountid){

    $sql = "SELECT * FROM `meetings` ORDER BY `date` ASC";
    $result = db_query($sql);
    $str_nextmeeting = "`bNächster Termin`b: Du hast dich zu keinem Treffen angemeldet.";
    $str_color = "`0";

    
    if (db_num_rows($result) > 0){
        while ($row = db_fetch_assoc($result)){
            $array_participants = unserialize($row['participants']);
            foreach($array_participants as $index => $subarray_participants){
                if (($subarray_participants['acctid']==$accountid) && ($subarray_participants['state']=="aboard")){
                    $difference = $row['date'] - time();
                    $days = floor($difference / 86400);
                    $hours = floor(($difference - ($days*86400)) / 3600);
                    $minutes = floor(($difference - ($days*86400) - ($hours*3600)) / 60);
                    $str_nextmeeting = "`bNächster Termin`b in ".$days." Tagen ".$hours." Stunden und ".$minutes." Minuten!";
                    $str_color = "`&";
                    if ($difference<=345600) $str_color = "`y"; // 4 Tage
                    if ($difference<=259200) $str_color = "`8"; // 3 Tage
                    if ($difference<=172800) $str_color = "`/"; // 2 Tage
                    if ($difference<=86400) $str_color = "`^"; // 1 Tag
                    if ($difference<=43200) $str_color = "`6"; // 12 Stunden
                    if ($difference<=21600) $str_color = "`q"; // 6 Stunden
                    if ($difference<=10800) $str_color = "`d"; // 3 Stunden
                    if ($difference<=7200) $str_color = "`Q"; // 2 Stunden
                    if ($difference<=3600) $str_color = "`D"; // 1 Stunde
                    if ($difference<=0){
                        $str_nextmeeting = "`bNächster Termin`b: Ein Treffen läuft gerade!";
                        $str_color = "`$"; //Hat schon angefangen un dauert noch an (max. 2h)
                    }
                    if ($difference<(-7200)){
                        $str_color = "`(";
                        $str_nextmeeting = "`bNächster Termin`b: Du hast dich zu keinem Treffen angemeldet.";
                        break 1;
                    }
                    break 2;
                }
            }
        }
    } else {
        $str_color = "`(";
        $str_nextmeeting = "`bNächster Termin`b: Es stehen keine Treffen an.";
    }
    $str_return = $str_color.$str_nextmeeting."`0";
    return $str_return;
}

if ($_GET['type']=="access"){
if ($_GET['op']==""){
    
    $sql = "SELECT * FROM `meetings` ORDER BY `date` ASC";
    $result = db_query($sql);
    
    if (db_num_rows($result) > 0)
    {
        $str_output .= "<table width='100%'><tr class='trhead'><th align='center'>Name</th><th align='center'>Datum</th><th align='center'>Wer?</th><th align='center'>Priorität (1-9)</th></tr>";
        $int_counter = 0;
        while ($row = db_fetch_assoc($result)){
            $str_output .= "<tr class='".($int_counter++%2?'trlight':'trdark')."'>";
            $array_participants = unserialize($row['participants']);
            $bool_signed = false;
            $bool_old = false;
            foreach($array_participants as $index => $subarray_participants){
                if (($subarray_participants['acctid']==$session['user']['acctid']) && ($subarray_participants['state']=="aboard")){
                    if (($row['date']+7200-time())<0){
                        $bool_old = true;
                    } else {
                        $bool_signed = true;
                    }
                    break;
                } elseif (($row['date']+7200-time())<0){
                    $bool_old = true;
                }
            }
            $str_output .= "<td><a href='".$str_self."&op=show&id=".$row['meetingid']."'>".($bool_signed?"`b`2":"").($bool_old?"`(":"").$row['name'].($bool_signed?"`b":"")."`0</a></td>";
            $str_output .= "<td>".($bool_old?"`(":"").date("d.m.Y H:i",$row['date'])." Uhr`0</td>";
            $str_output .= "<td>".($bool_old?"`(":"").$row['group']."`0</td>";
            $str_output .= "<td>".($bool_old?"`(":"").$row['priority']."`0</td>";
            $str_output .= "</tr>";
            addnav("",$str_self."&op=show&id=".$row['meetingid']);
        }
        $str_output .= "</table>";
    } else {
        $str_output .= "Es sind keine Treffen eingetragen!";
    }
    
    $str_output .= "`n".meeting_countdown($session['user']['acctid']);
    
    addnav("Treffen hinzufügen",$str_self."&op=add");
    
} elseif ($_GET['op']=="add"){
    
    if ($_GET['subop']=="submit"){
        
        $name = $_POST['name'];
        $datum = mktime($_POST['stunden'],$_POST['minuten'],0,$_POST['monat'],$_POST['tag'],$_POST['jahr']);
        $gruppe = $_POST['gruppe'];
        $beschreibung = $_POST['beschreibung'];
        $prioritaet = $_POST['prioritaet'];
        $teilnehmer = serialize(array());
        $kommentare = serialize(array());
        
        $sql = "INSERT INTO `meetings` (`meetingid` ,`name` ,`date` ,`group` ,`priority` ,`participants` ,`comments` ,`description`) VALUES (NULL , '".$name."', '".$datum."', '".$gruppe."', '".$prioritaet."', '".$teilnehmer."', '".$kommentare."', '".$beschreibung."')";
        db_query($sql);
        $str_output .= "`bTreffen am ".date("d.m.Y",$datum)." eingetragen!`b";
        addnav("Weiter",$str_self);
        
    } else {
        $str_output .= "<table border='0'><form action='".$str_self."&op=add&subop=submit' method='POST'><tr><td>Name des Treffens:</td><td><input name='name' size='30' maxlength='99'></td></tr>";
        $str_output .= "<tr><td>Datum:</td><td>Am <input name='tag' size='3' maxlength='2' value='".date("j")."'>.<input name='monat' size='3' maxlength='2' value='".date("n")."'>.<input name='jahr' size='5' maxlength='4' value='".date("Y")."'> um <input name='stunden' size='3' maxlength='2' value='".date("G")."'>:<input name='minuten' size='3' maxlength='2' value='".date("i")."'> Uhr</td></tr>";
        $str_output .="<tr><td>Gruppe:</td><td><input name='gruppe' maxlength='99'></td></tr><tr><td>Beschreibung:</td><td><textarea name='beschreibung' cols='50' rows='10'></textarea></td></tr>";
        $str_output .= "<tr><td>Priorität (1 = höchste, 9 = niedrigste):</td><td><input name='prioritaet' size='2' maxlength='1' value='4'></td></tr><tr><td>`n<input type='submit' class='button' value='Eintragen'></td></tr></form></table>`n`^Alle Felder sind auszufüllen!";
        addnav("",$str_self."&op=add&subop=submit");
        addnav("Zurück zur Liste",$str_self);
    }
    
} elseif ($_GET['op']=="edit"){
    
    if ($_GET['subop']=="submit"){
        
        $meetingid = $_POST['meetingid'];
        $name = $_POST['name'];
        $datum = mktime($_POST['stunden'],$_POST['minuten'],0,$_POST['monat'],$_POST['tag'],$_POST['jahr']);
        $gruppe = $_POST['gruppe'];
        $beschreibung = $_POST['beschreibung'];
        $prioritaet = $_POST['prioritaet'];
        $sql = "UPDATE `meetings` SET `name` = '".$name."',`date` = '".$datum."',`group` = '".$gruppe."',`priority` = '".$prioritaet."',`description` = '".$beschreibung."' WHERE `meetingid` = '".$meetingid."' LIMIT 1";
        db_query($sql);
        $str_output .= "`bTreffen am ".date("d.m.Y",$datum)." geändert!`b";
        clearnav();
        addnav("Weiter",$str_self."&op=show&id=".$meetingid);
        
    } elseif ($_GET['subop']=="delete"){
        
        $sql = "DELETE FROM `meetings` WHERE `meetingid` = '".$_GET['id']."' LIMIT 1";
        db_query($sql);
        $str_output .= "`bTreffen gelöscht!`b";
        clearnav();
        addnav("Weiter",$str_self);
        
    } else {
        
        $sql = "SELECT * FROM `meetings` WHERE `meetingid` = '".$_GET['id']."' LIMIT 1";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $str_output .= "<table border='0'><form action='".$str_self."&op=edit&subop=submit' method='POST'><tr><td>Name des Treffens:</td><td><input name='name' size='30' maxlength='99' value='".$row['name']."'></td></tr>";
        $str_output .= "<tr><td>Datum:</td><td>Am <input name='tag' size='3' maxlength='2' value='".date("d",$row['date'])."'>.<input name='monat' size='3' maxlength='2' value='".date("m",$row['date'])."'>.<input name='jahr' size='5' maxlength='4' value='".date("Y",$row['date'])."'> um <input name='stunden' size='3' maxlength='2' value='".date("H",$row['date'])."'>:<input name='minuten' size='3' maxlength='2' value='".date("i",$row['date'])."'> Uhr</td></tr>";
        $str_output .="<tr><td>Gruppe:</td><td><input name='gruppe' maxlength='99' value='".$row['group']."'></td></tr><tr><td>Beschreibung:</td><td><textarea name='beschreibung' cols='50' rows='10'>".$row['description']."</textarea></td></tr>";
        $str_output .= "<tr><td>Priorität (1 = höchste, 9 = niedrigste):</td><td><input name='prioritaet' size='2' maxlength='1' value='".$row['priority']."'></td></tr><tr><td><input type='hidden' name='meetingid' value='".$row['meetingid']."'><input type='submit' class='button' value='Änderungen speichern'></td></tr></form></table>";
        addnav("",$str_self."&op=edit&subop=submit");
        addnav("Zurück zum Termin",$str_self."&op=show&id=".$row['meetingid']);
        addnav("Vorsicht");
        addnav("`4Termin löschen`0",$str_self."&op=edit&id=".$row['meetingid']."&subop=delete",false,false,false,false,"Willst du diesen Termin wirklich streichen?");
    }

} elseif ($_GET['op']=="show"){

    $sql = "SELECT * FROM `meetings` WHERE `meetingid` = '".$_GET['id']."' LIMIT 1";
    $result = db_query($sql);

    if (db_num_rows($result) > 0)
    {
        $row = db_fetch_assoc($result);
        addnav("Aktualisieren",$str_self."&op=show&id=".$row['meetingid']);
        addnav("",$str_self."&op=show&id=".$row['meetingid']);
        $str_output .= "`bGrottentreffen am ".date("d.m.Y, H:i",$row['date'])." Uhr: ".$row['name']."`b [ <a href='".$str_self."&op=edit&id=".$row['meetingid']."'>Bearbeiten</a> ]`n";
        addnav("",$str_self."&op=edit&id=".$row['meetingid']);
        $str_output .= "Priorität: ".$row['priority']."`n`n";
        $str_output .= "Beschreibung:`n".$row['description']."`n`n`n";

        $array_participants = unserialize($row['participants']);
        if ($_GET['subop']=="setstate"){
            foreach($array_participants as $index => $subarray){
                if ($subarray['acctid']==$session['user']['acctid']){
                    unset($array_participants[$index]);
                    break;
                }
            }
            if (($_GET['state']!="reset") && (strlen($_GET['state'])>0)){
                $insert_array=array();
                $insert_array['acctid']=$session['user']['acctid'];
                $insert_array['state']=$_GET['state'];
                $array_participants[]=$insert_array;
            }
            db_query("UPDATE `meetings` SET `participants` = '".addslashes(serialize($array_participants))."' WHERE `meetingid` = '".$row['meetingid']."' LIMIT 1");
        }
        $mystate = "nothing";
        $participantsection = "An-/Abmeldungsliste:`n<table><tr class='trhead'><th align='center'>`2Anwesend`0</th><th align='center'>`4Abwesend`0</th></tr><tr><td valign='top'>";
        foreach($array_participants as $subarray){
            if ($subarray['state']=="aboard"){
                $request = db_fetch_assoc(db_query("SELECT `name` FROM `accounts` WHERE `acctid` = '".$subarray['acctid']."' LIMIT 1"));
                $participantsection .= $request['name']."`n";
                if ($request['name']==$session['user']['name']) $mystate = "aboard";
            }
        }
        $participantsection .= "</td><td valign='top'>";
        foreach($array_participants as $subarray){
            if ($subarray['state']=="absent"){
                $request = db_fetch_assoc(db_query("SELECT `name` FROM `accounts` WHERE `acctid` = '".$subarray['acctid']."' LIMIT 1"));
                $participantsection .= $request['name']."`n";
                if ($request['name']==$session['user']['name']) $mystate = "absent";
            }
        }
        $participantsection .= "</td></tr></table>`n`n";
        $str_output .= $participantsection;
        
        $array_comments = unserialize($row['comments']);
        $commentsection = "";
        foreach($array_comments as $subarray){
            $commentsection .= "`#`b".$subarray['poster'].":`b`3 ".$subarray['content']."`0`n";
        }
        if (($_GET['subop']=="comment") && (strlen($_POST['comment'])>0)){
            $array_comments[count($array_comments)]['poster'] = $session['user']['login'];
            $array_comments[(count($array_comments)-1)]['content'] = stripslashes($_POST['comment']);
            $serialized_comments = serialize($array_comments);
            $sql2 = "UPDATE `meetings` SET `comments` = '".addslashes($serialized_comments)."' WHERE `meetingid` = '".$row['meetingid']."' LIMIT 1";
            $result2 = db_query($sql2);
            $commentsection .= "`#`b".$session['user']['login'].":`b`3 ".stripslashes($_POST['comment'])."`0`n";
        }
        $str_output .= "`#`bKommentare:`b`0`n".$commentsection."`n<hr>`n<form action='".$str_self."&op=show&id=".$row['meetingid']."&subop=comment' method='POST'><input name='comment' size='50'><input type='submit' class='button' value='Hinzufügen!'>`n[ <a href='".$str_self."&op=show&id=".$row['meetingid']."'>Aktualisieren</a> ]</form>";
        addnav("",$str_self."&op=show&id=".$row['meetingid']."&subop=comment");

        
        
        addnav("Zurück zur Liste",$str_self);
        addnav("Mein Status");
        addnav((($mystate=="aboard")?("`2"):(""))."Ja, ich werde kommen :)`0",$str_self."&op=show&id=".$row['meetingid']."&subop=setstate&state=aboard");
        addnav((($mystate=="nothing")?("`^"):(""))."Ich weiß noch nicht!`0",$str_self."&op=show&id=".$row['meetingid']."&subop=setstate&state=reset");
        addnav((($mystate=="absent")?("`4"):(""))."Nein, ich habe keine Zeit :(`0",$str_self."&op=show&id=".$row['meetingid']."&subop=setstate&state=absent");


    } else {
        $str_output .= "Treffen nicht gefunden (soeben gelöscht?)";
        clearnav();
        addnav("Zurück",$str_self);
    }

}
}

if ($_GET['type']!="access"){
    $str_output .= meeting_countdown($session['user']['acctid']);
}

output($str_output,true);
if ($_GET['type']=="access") page_footer();
?>

