<?php
//Author addon by Hadriel
//Date Addon: Idea by Hadriel, scriptet by Anpera


require_once ("common.php");
session_write_close();

//von Rikkarda@silienta-logd.de umwandlung von Link zu Hyperlink

function makeUrl($text) {
    return  preg_replace_c( array("/[^\"'=]((http):\/\/[^\s\"']+)/i","/<a([^>]+)>/i"),
         array("<a href=\"\\1\">Klick Mich</a>","<a\\1 target=\"_blank\" >"),$text);
}
//db_query("ALTER TABLE `motd` ADD `motdprefix` VARCHAR( 30 ) NOT NULL ;");
//function db_prefix($input) { return $input; }
function kill() {
        if ($session['user']['loggedin']){
            $session['user']['experience']=round($session['user']['experience']0.9,0);
            addnews($session['user']['name']." wurde für den Versuch, die Götter zu betrügen, bestraft.");
            rawoutput('Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.');
            saveuser();
        }
 }
popup_header("Kokotos Nachrichtendienst");

rawoutput(($session['user']['superuser']>=4?" [<a href='motd.php?op=add'>MoTD erstellen</a>|<a href='motd.php?op=addpoll'>Umfrage erstellen</a>]<br />":""));
function motditem($subject, $body, $prefixe, $date, $author){
    output("`e<b> $prefixe $subject</b>`e",true);
    $result = "SELECT motd.motdauthor,accounts.name,accounts.acctid FROM motd, accounts WHERE accounts.name = '$author'";
    $row = db_fetch_assoc(db_query($result));
    output("`n`0".$author." `0- `#".$date."`n",true);
    output(nl2br(makeUrl($body)),true);
    rawoutput("<hr />");
}
function pollitem($id, $subject, $body, $author, $date){
    global $session;
$sql = "SELECT count(resultid) AS c, MAX(choice) AS choice FROM pollresults WHERE motditem='$id' AND account='".$session['user']['acctid']."'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $choice = $row['choice'];
    $body = unserialize($body);
        rawoutput("<form action='motd.php?op=vote' method='POST'><input type='hidden' name='motditem' value='$id' />");
        output("`^Umfrage:`e $subject`n `n`0".$author."`0 - `#".$date."`n`n`v",true);
        output(nl2br(makeUrl(stripslashes($body['body']))),true);
        $sql = "SELECT count(resultid) AS c, choice FROM pollresults WHERE motditem='$id' GROUP BY choice ORDER BY choice";
        $result = db_query($sql);
        $choices=array();
        $totalanswers=0;
        $maxitem = 0;
        for ($i=0;$i<db_num_rows($result);$i++){
            $row = db_fetch_assoc($result);
            $choices[$row['choice']]=$row['c'];
            $totalanswers+=$row['c'];
            if ($row['c']>$maxitem) $maxitem = $row['c'];
        }
		foreach($body['opt'] as $key => $val){
            if (trim($val)!=''){
                if ($totalanswers<=0) $totalanswers=1;
                $percent = round($choices[$key]  $totalanswers  100,1);
                output("`n<input type='radio' name='choice' value='$key'".($choice==$key?" checked":"")." />",true);
                output(stripslashes($val)." (".(int)$choices[$key]." - $percent%)");
                if ($maxitem==0){ $width=1; } else { $width = round(($choices[$key]$maxitem)  400,0); }
                $width = max($width,1);
                rawoutput("<br /><img src='images/rule.gif' width='$width' height='2' alt='$percent' />");
            }
        }
        rawoutput("<br /><input type='submit' class='button' value='Abstimmen' /></form>");
    
    rawoutput('<hr />');
}
if ($_GET['op']=="vote"){
    //$sql = "DELETE FROM pollresults WHERE motditem='".$_POST['motditem']."' AND account='".$session['user']['acctid']."'";
    //db_query($sql);
    $count = db_query("SELECT * FROM pollresults WHERE motditem=".$_POST['motditem']." AND account=".$session['user']['acctid']);
	if (db_num_rows($count)>0){
	$sql = "UPDATE pollresults SET choice = ".$_POST['choice']." WHERE account = ".$session['user']['acctid']." AND motditem = ".$_POST['motditem'];
	}else{
	$sql = "INSERT INTO pollresults (choice,account,motditem) VALUES (".$_POST['choice'].",".$session['user']['acctid'].",".$_POST['motditem'].")";
	}
	db_query($sql);
    header("Location: motd.php");
    exit();
}
if ($_GET['op']=='addpoll'){
    if($session['user']['superuser']>=4){
        if ($_POST['subject']=='' || $_POST['body']==''){
            rawoutput("<form action='motd.php?op=addpoll' method='POST'>");
            allownav("motd.php?op=add");
            output("<input type='text' size='50' name='subject' value=\"".htmlspecialchars(stripslashes($_POST['subject']))."\">`n",true);
            output("<textarea class='input' name='body' cols='37' rows='5'>".htmlspecialchars(nl2br($_POST['body']))."</textarea>`n",true);
            output("Opt <input name='opt[]'>`n Opt <input name='opt[]'>`n Opt <input name='opt[]' />`n Opt <input name='opt[]' />`n Opt <input name='opt[]' />`n Opt <input name='opt[]' />`n",true);
            rawoutput("<input type='submit' class='button' value='Hinzufügen'></form>");
        }else{
            $body = array("body"=>$_POST['body'],"opt"=>$_POST['opt']);
            $sql = "INSERT INTO motd (motdtitle,motdbody,motddate,motdtype,motdauthor) VALUES (\"".$_POST['subject']."\",\"".mysql_real_escape_string(serialize($body))."\",now(),1,\"".$session['user']['acctid']."\")";
            db_query($sql);
            header("Location: motd.php");
            exit();
        }
    }else{
			kill();
    }
}
if ($_GET['op']=='add'){
    if ($session['user']['superuser']>=4){
        if ($_POST['subject']=='' || $_POST['body']==''){
            rawoutput("<form action='motd.php?op=add' method='POST'>");
            allownav("motd.php?op=add");
            //motdprefixe [© 2005 by Day]
            $prefixe = explode('|', getsetting('motdprefixe','|Wichtig|Ankündigung'));
            output("<select name='prefix'>",true);
			foreach($prefixe as $key => $val){
                output("<option value='$val'>$val</option>",true);
            }
            rawoutput("</select>");
            output("<input type='text' size='39' name='subject' value=\"".htmlspecialchars(stripslashes($_POST['subject']))."\" />`n",true);
            //end
            rawoutput("<textarea class='input' name='body' cols='37' rows='5'>".htmlspecialchars(stripslashes(nl2br($_POST['body'])))."</textarea>",true);
            rawoutput("<input type='submit' class='button' value='Hinzufügen' /></form>");
        }else{
            //motdprefixe [© 2005 by Day]
            if($_POST['prefix'] != "") $_POST['prefix'].=": ";
            $sql = "INSERT INTO motd (motdtitle,motdbody,motddate,motdprefix,motdauthor) VALUES (\"".$_POST['subject']."\",\"".$_POST['body']."\",now(),\"".$_POST['prefix']."\",'".$session['user']['acctid']."')";
            //end motdprefixe
            db_query($sql);
            header("Location: motd.php");
            exit();
        }
    }else{
			kill();
    }
}
if ($_GET['op']=='del'){
    if ($session['user']['superuser']>=4){
            $sql = "DELETE FROM motd WHERE motditem=\"".(int)$_GET['id']."\"";
            db_query($sql);
            header("Location: motd.php");
            exit();
    }else{
	kill();
    }
}
//original von darkangel modifiziert zum Einbau in Silienta
if ($_GET['op']=='edit'){
    if ($session['user']['superuser']>=4){
$sql = "SELECT * FROM motd WHERE motditem='".(int)$_GET['id']."'";
$result = db_query($sql);
$row = db_fetch_assoc($result);
rawoutput("<form action='motd.php?op=edit2&id=".(int)$_GET['id']."' method='POST'>");
allownav("motd.php?op=edit&id=".(int)$_GET['id']."'");
rawoutput("<table><tr><td width=100><b>Überschrift:</b></td><td><input type='text' size='50' name='subject' value='".$row['motdtitle']."' /></td></tr><tr><
<td width=100><b>MoTD-Author:</b></td><td><input type='text' size='50' name='name' value='".$row['motdauthor']."' /></td></tr><tr><td width=100><b>Newstext:</b></td><td><textarea class='input' name='body' cols='38' rows='5'>".$row['motdbody']."</textarea></td></tr><tr><td width=100></td><td><input type='submit' class='button' value='Eintrag ändern' /></form></td></tr></table>");
allownav("motd.php?op=edit2&id=".(int)$_GET['id']."'");
rawoutput("</form>");

        }else{ kill(); }
}
if ($_GET['op']=='edit2'){
db_query("UPDATE motd SET motdtitle ='".$_POST['subject']."', motdauthor ='".$_POST['name']."', motdbody ='".$_POST['body']."' WHERE motditem='".(int)$_GET['id']."'");
header("Location: motd.php");
exit();
}

if ($_GET['op']==''){
    //month archiv [Code from 1.0.2 by Day]
    //$count = getsetting("motditems", 5);
output("`vBeta!`nDieses Spiel befindet sich in der Aufbauphase Fehler und Verbesserungen sind im Forum zu Posten, oder dem Admin zu melden, Ausnutzen der Fehler wird mit Verbannung oder löschung des Accounts bestraft, daher immer die Fehler melden!`n`n");
    $m = (int)$_GET["month"];
    if ($m > ""){
        $sql = "SELECT motd.*,name AS motdauthorname FROM motd LEFT JOIN accounts ON accounts.acctid = motd.motdauthor WHERE motddate >= '{$m}-01' AND motddate <= '{$m}-31' ORDER BY motddate DESC";
        $result = db_query($sql);
    }else{
        $sql = "SELECT motd.*,name AS motdauthorname FROM motd LEFT JOIN accounts ON accounts.acctid = motd.motdauthor ORDER BY motddate DESC";
        $result = db_query($sql);
    }
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if (!isset($session['user']['lastmotd']))
            $session['user']['lastmotd']=0;
        if ($row['motdtype']==0){
            motditem($row['motdtitle'].' '.($session['user']['superuser']>=4?"<b>[<a href='motd.php?op=del&id=".$row['motditem']."' onClick=\"return confirm('Bist du sicher, dass der Eintrag _".stripcolors($row['motdtitle'])."_ gelöscht werden soll?');\">Del</a>][<a href='motd.php?op=edit&id=".$row['motditem']."' onClick=\"return confirm('Bist du sicher, dass der Eintrag _".stripcolors($row['motdtitle'])."_  editiert werden soll?');\">Edit</a>]</b>":""), $row['motdbody'],$row['motdprefix'], $row['motddate'],$row['motdauthorname'],$row['motditem']);
        }else{
            pollitem($row['motditem'], $row['motdtitle'].' '.($session['user']['superuser']>=4?"<b>[<a href='motd.php?op=delpoll&id=".$row['motditem']."' onClick=\"return confirm('Bist du sicher, dass die Umfrage _".stripcolors($row['motdtitle'])."_ gelöscht werden soll?');\">Del</a>][<a href='motd.php?op=editpoll&id=".$row['motditem']."' onClick=\"return confirm('Bist du sicher, dass die Umfrage _".stripcolors($row['motdtitle'])."_  editiert werden soll?');\">Edit</a>]</b>":""), $row['motdbody'],$row['motdauthorname'],$row['motddate'],$row['motditem']);
        }
    }

   
	output("`n`n`vBeta!`nDieses Spiel befindet sich in der Aufbauphase Fehler und Verbesserungen sind im Forum zu Posten, oder dem Admin zu melden, Ausnutzen der Fehler wird mit Verbannung oder löschung des Accounts bestraft, daher immer die Fehler melden!`e");
    $result = db_query("SELECT mid(motddate,1,7) AS d, count(*) AS c FROM motd GROUP BY d ORDER BY d DESC");
    $row = db_fetch_assoc($result);
    rawoutput("<form action='motd.php' method='GET'>");
    rawoutput('MoTD Archiv: ');
    rawoutput("<select name='month' onChange='this.form.submit();' >");
    rawoutput("<option value=''>--Jetziger Monat--</option>");
    while ($row = db_fetch_assoc($result)){
        $time = strtotime("{$row['d']}-01");
        $m = date("M",$time);
        rawoutput ("<option value='{$row['d']}'>$m".date(", Y",$time)." ({$row['c']})</option>");
    }
    rawoutput("</select><input type='submit' value='&gt;' class='button' /></form>");

    //end
}

$session['needtoviewmotd']=false;

    $sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $session['user']['lastmotd']=$row['motddate'];

popup_footer();
?>