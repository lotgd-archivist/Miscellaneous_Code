
<?php//Author addon by Hadriel//Date Addon: Idea by Hadriel, scriptet by Anpera//more changes for Silienta by Rikkarda@silienta-logd.de for www.silienta-logd.de/*ALTER TABLE `motd` ADD  `motdprefix` varchar(30) NOT NULL default '',          ADD `motdauthor` int(11) NOT NULL default '0'; */require_once "common.php";require_once "func/popup.php";session_write_close();/*** Allow these tags*/$allowedTags='<br><b><h1><h2><h3><h4><i><hr>' .             '<img><li><ol><p><strong><table>' .             '<tr><td><th><u><ul><div><span><center><p><img>';/*** Disallow these attributes/prefix within a tag*/$stripAttrib = 'javascript&#058;|onclick|ondblclick|onmousedown|onmouseup|onmouseover|'.'onmousemove|onmouseout|onkeypress|onkeydown|onkeyup|onabort|'.'onfocus|onload|onblur|onchange|onerror|onreset|onselect|obsubmit|onunload|style';/*** @return string* @param string* @desc Strip forbidden attributes from a tag*/function removeEvilAttributes($tagSource){    global $stripAttrib;    return stripslashes(preg_replace("/$stripAttrib/i", 'forbidden',"<".$tagSource[1].">"));}/*** @return string* @param string* @desc Strip forbidden tags and delegate tag-source check to removeEvilAttributes()*/function removeEvilTags($source){    global $allowedTags;    $source = strip_tags($source, $allowedTags);    return preg_replace_callback('/<(.*?)>/i',removeEvilAttributes, $source);}//von Rikkarda@silienta-logd.de umwandlung von Link zu Hyperlinkfunction makeUrl($text) {    return  preg_replace( array(               "/[^\"'=]((http):\/\/[^\s\"']+)/i",               "/<a([^>]+)>/i"       ),         array(               "<a href=\"\\1\">HIER</a>",              "<a\\1 target=\"_blank\" >"           ),       $text       );}//db_query("ALTER TABLE `motd` ADD `motdprefix` VARCHAR( 30 ) NOT NULL ;");function db_prefix($input) { return $input; }popup_header("Nachrichten des Tages");if (!$session['user']['loggedin']){        output("Dich interessieren die neusten Nachrichten aus Alvion? Melde Dich an und werde ein Teil unserer Welt! Wir freuen uns auf jedes Wesen, welches den Weg in unser herrliches Dorf findet :)!");}else{output(($session['user']['superuser']>=3?" [<a href='motd.php?op=add'>MoTD erstellen</a>|<a href='motd.php?op=addpoll'>Umfrage erstellen</a>]`n":""),true);function motditem($subject, $body, $prefixe, $date, $author){    output("`^<b> $prefixe $subject</b>`0",true);    $result = "select motd.motdauthor,                      accounts.name,                      accounts.acctid              from motd, accounts              where accounts.name = '$author'";    $row = db_fetch_assoc(db_query($result));    output("`n`0".$author." `0- `#".$date."`n",true);    output(nl2br(makeUrl("$body")),true);    output("<hr>",true);}function pollitem($id, $subject, $body, $author, $date){    global $session;    $sql = "SELECT count(resultid) AS c, MAX(choice) AS choice FROM pollresults WHERE motditem='$id' AND account='{$session['user']['acctid']}'";    $result = db_query($sql);    $row = db_fetch_assoc($result);    $choice = $row['choice'];    $body = unserialize($body);    if ($row['c']==0 && 0){        output("<form action='motd.php?op=vote' method='POST'>",true);        output("<input type='hidden' name='motditem' value='$id'>",true);        output("`^Umfrage: $subject",true);        output("`n`0".$author."`0 - `#".$date."`n`n",true);        output("`2".stripslashes($body['body']."`n`n`n"));        while (list($key,$val)=each($body['opt'])){            if (trim($val)!=""){                output("`n<input type='radio' name='choice' value='$key'>",true);                output(stripslashes($val));            }        }        output("`n<input type='submit' class='button' value='Abstimmen'>",true);        output("</form>",true);    }else{        output("<form action='motd.php?op=vote' method='POST'>",true);        output("<input type='hidden' name='motditem' value='$id'>",true);        output("`^Umfrage: $subject`n",true);        output("`n`0".$author."`0 - `#".$date."`n`n",true);        output("`i`vBetreff:`i`n`3",true);        $body1 = ("".stripslashes(removeEvilTags(nl2br($body['body']))).""); //by Rikka        $body1= str_replace('<br />','`n',$body1);        output($body1);        $sql = "SELECT count(resultid) AS c, choice FROM pollresults WHERE motditem='$id' GROUP BY choice ORDER BY choice";        $result = db_query($sql);        $choices=array();        $totalanswers=0;        $maxitem = 0;        for ($i=0;$i<db_num_rows($result);$i++){            $row = db_fetch_assoc($result);            $choices[$row['choice']]=$row['c'];            $totalanswers+=$row['c'];            if ($row['c']>$maxitem) $maxitem = $row['c'];        }        while (list($key,$val)=each($body['opt'])){            if (trim($val)!=""){                if ($totalanswers<=0) $totalanswers=1;                $percent = round($choices[$key] / $totalanswers * 100,1);                output("`n<input type='radio' name='choice' value='$key'".($choice==$key?" checked":"").">",true);                output(stripslashes($val)." (".(int)$choices[$key]." - $percent%)");                if ($maxitem==0){ $width=1; } else { $width = round(($choices[$key]/$maxitem) * 400,0); }                $width = max($width,1);                output("`n<img src='images/rule.gif' width='$width' height='2' alt='$percent'>",true);                //output(stripslashes($val)."`n");            }        }        output("`n<input type='submit' class='button' value='Abstimmen'></form>",true);    }    output("<hr>",true);}if (isset($_GET['op'])){    if ($_GET['op']=="vote"){        $sql = "DELETE FROM pollresults WHERE motditem='{$_POST['motditem']}' AND account='{$session['user']['acctid']}'";        db_query($sql);        $sql = "INSERT INTO pollresults (choice,account,motditem) VALUES ('{$_POST['choice']}','{$session['user']['acctid']}','{$_POST['motditem']}')";        db_query($sql);        header("Location: motd.php");        exit();    }    if ($_GET['op']=="addpoll"){        if($session['user']['superuser']>=3){            if ($_POST['subject']=="" || $_POST['body']==""){                output("<form action='motd.php?op=addpoll' method='POST'>",true);                addnav("","motd.php?op=add");                output("<input type='text' size='50' name='subject' value=\"".HTMLSpecialChars(stripslashes($_POST[subject]))."\">`n",true);                rawoutput("<textarea class='input' name='body' cols='37' rows='5'>".HTMLSpecialChars(stripslashes(removeEvilTags(nl2br($_POST[body]))))."</textarea><br/>",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                output("Opt <input name='opt[]'>`n",true);                    output("<input type='submit' class='button' value='Hinzufügen'></form>",true);            }else{                $body = array("body"=>$_POST['body'],"opt"=>$_POST['opt']);                $sql = "INSERT INTO motd (motdtitle,motdbody,motddate,motdtype,motdauthor) VALUES (\"$_POST[subject]\",\"".addslashes(serialize($body))."\",now(),1,\"".$session[user][acctid]."\")";                db_query($sql);                header("Location: motd.php");                exit();            }        }else{            if ($session[user][loggedin]){                //$session[user][hitpoints]=0;                //$session[user][alive]=0;                $session[user][experience]=round($session[user][experience]*0.9,0);                addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");                output("Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.");                saveuser();            }        }    }    if ($_GET['op']=="add"){        if ($session[user][superuser]>=2){            if ($_POST[subject]=="" || $_POST[body]==""){                output("<table><form action='motd.php?op=add' method='POST'>",true);                addnav("","motd.php?op=add");                if ($session[user][superuser]>=3){                                //user-auswahl                    $user = $session['user']['acctid'];                    $name = $session['user']['name'];                    output("<tr><td>Autor: </td><td><select name='user'>",true);                    output("<option value='0'>Das Team</option>",true);                    output("<option selected='selected' value='$user'>$name</option>",true);                    output("</select></td></tr>",true);            }                     //motdprefixe [© 2005 by Day]                $prefixe = explode('|', '|Nachrichten|Wichtig|Ankündigung');                output("<tr><td>Prefix: </td><td><select name='prefix'>",true);                    while (list($key,$val)=each($prefixe)){                    output("<option value='$val'>$val</option>",true);                }                output("</select></td></tr>",true);                output("<tr><td>Titel: </td><td><input type='text' size='40' name='subject' value=\"".HTMLSpecialChars(stripslashes($_POST[subject]))."\"></td></tr>",true);                //end                output("<tr><td>Text: </td><td><textarea class='input' name='body' cols='60' rows='25'>".HTMLSpecialChars(stripslashes(removeEvilTags(nl2br($_POST[body]))))."</textarea></td></tr>",true);                output("<tr><td><input type='submit' class='button' value='Hinzufügen'></td></tr></form></table>",true);                }else{                //motdprefixe [© 2005 by Day]                if($_POST['prefix'] != "") $_POST['prefix'].=": ";                if($_POST['user'] == "" || $_POST['user'] == NULL) $_POST['user']=$session['user']['acctid'];                                $_POST[body]=mysqli_real_escape_string($mysqli, stripslashes(removeEvilTags($_POST[body])));                    $sql = "INSERT INTO motd (motdtitle,motdbody,motddate,motdprefix,motdauthor,acctid) VALUES (\"$_POST[subject]\",\"$_POST[body]\",now(),\"$_POST[prefix]\",'{$_POST['user']}',{$session['user']['acctid']})";                //end motdprefixe                db_query($sql);                header("Location: motd.php");                exit();            }        }else{            if ($session[user][loggedin]){                //$session[user][hitpoints]=0;                //$session[user][alive]=0;                $session[user][experience]=round($session[user][experience]*0.9,0);                addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");                output("Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.");                saveuser();            }        }    }    if ($_GET['op']=="del"){        if ($session[user][superuser]>=2){                $sql = "DELETE FROM motd WHERE motditem=\"$_GET[id]\"";                db_query($sql);                header("Location: motd.php");                exit();        }else{            if ($session[user][loggedin]){                //$session[user][hitpoints]=0;                //$session[user][alive]=0;                $session[user][experience]=round($session[user][experience]*0.9,0);                addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");                output("Du hast versucht die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weisst du nicht mehr.");                saveuser();            }        }    }    //original von darkangel modifiziert zum Einbau in Silienta by Rikkarda@silienta-logd.de thx an Eliwood für die Haue in meinem htmlgimpsein    if ($_GET['op']=="edit"){        if ($session[user][superuser]>=3){            $sql = "SELECT * FROM motd WHERE motditem='".$_GET[id]."'";            $result = db_query($sql);            $row = db_fetch_assoc($result);            output("<form action='motd.php?op=edit2&id=".$_GET[id]."' method='POST'>",true);            addnav("","motd.php?op=edit&id=".$_GET[id]."'");            output("<table><td width=100><b>Überschrift:</b></td><td>",true);            rawoutput("<textarea class='input' name='subject' cols='60' rows='1'>".stripslashes($row[motdtitle])."</textarea>",true);            output("</td></table>",true);            output("<table><td width=100><b>MoTD-Author:</b></td><td><input type='text' size='60' name='name' value='$row[motdauthor]'></td></table>",true);            output("<table><td width=100><b>Newstext:</b></td><td>",true);            rawoutput("<textarea class='input' name='body' cols='60' rows='25'>".stripslashes($row[motdbody])."</textarea>",true);            output("</td></table>",true);            output("<table><td width=100></td><td><input type='submit' class='button' value='Eintrag ändern'></form></td></table>",true);            addnav("","motd.php?op=edit2&id=".$_GET[id]."'");            output("</form>",true);        }    }        if ($_GET['op']=="edit2"){        // Werte vor dem MySQL-Query maskieren        $_POST[body]=stripslashes(removeEvilTags($_POST[body]));        $subject=mysqli_real_escape_string($mysqli, $_POST['subject']);        $autor=mysqli_real_escape_string($mysqli, $_POST['name']);        $body=mysqli_real_escape_string($mysqli, $_POST['body']);        $id=mysqli_real_escape_string($mysqli, $_GET['id']);        // MoTD-Eintrag in der Datenbank ändern        db_query("UPDATE motd SET motdtitle ='".$subject."', motdauthor =".$autor.", motdbody ='".$body."' WHERE motditem=".$id);        // Seite neu aufrufen, denn wir wollen ja sehen was wir geändert haben        header("Location: motd.php");        exit();    }        $sql = "SELECT * FROM motd ORDER BY motddate DESC limit 20";}if (!isset($_GET['op']) || $_GET['op']==""){    //month archiv [Code from 1.0.2 by Day]    $count = getsetting("motditems", 5);    if (isset($_GET['month'])) $m = $_GET['month'];    else $m="";    if ($m > ""){        $sql = "SELECT " .("motd") . ".*,name AS motdauthorname FROM " .("motd") . " LEFT JOIN " .("accounts") . " ON " .("accounts") . ".acctid = " .("motd") . ".motdauthor WHERE motddate >= '{$m}-01' AND motddate <= '{$m}-31' ORDER BY motddate DESC";        $result = db_query($sql);    }else{        $sql = "SELECT " .("motd") . ".*,name AS motdauthorname FROM " .("motd") . " LEFT JOIN " .("accounts") . " ON " .("accounts") . ".acctid = " .("motd") . ".motdauthor ORDER BY motddate DESC limit $count";        $result = db_query($sql);    }        for ($i=0;$i<db_num_rows($result);$i++){        $row = db_fetch_assoc($result);        if (!isset($session['user']['lastmotd']))            $session['user']['lastmotd']=0;        if ($row['motdauthorname']=="") $row['motdauthorname']="`@Das Team`0";        if ($row['motdtype']==0){            motditem($row['motdtitle'].' '.($session['user']['superuser']>=3?"<b>[<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">Del</a>]`b[<a href='motd.php?op=edit&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag editiert werden soll?');\">EDIT</a>]":""), $row['motdbody'],              $row['motdprefix'], $row['motddate'],              $row['motdauthorname'],              $row['motditem']);        }else{            pollitem($row['motditem'], $row['motdtitle'].' '.($session[user][superuser]>=4?"`<b>[<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">Del</a>]</b>":""), $row['motdbody'],               $row['motdauthorname'],$row['motddate'],               $row['motditem']);        }    }    output('`6');    $result = db_query("SELECT mid(motddate,1,7) AS d, count(*) AS c FROM ".("motd")." GROUP BY d ORDER BY d DESC");    rawoutput("<form action='motd.php' method='GET'>");        rawoutput('MoTD Archiv: ');    rawoutput("<select name='month' onChange='this.form.submit();' >");    rawoutput("<option value=''>--Current--</option>");    while ($row = db_fetch_assoc($result)){        $time = strtotime("{$row['d']}-01");        $m = date("M",$time);        rawoutput ("<option value='{$row['d']}'>$m".date(", Y",$time)." ({$row['c']})</option>");    }    rawoutput("</select>");    rawoutput("<input type='submit' value='&gt;' class='button'>");    rawoutput("</form>");    //end        output("`@Kommentare und Fehler bitte ins Forum.`n`vmehr als die letzen Nachrichten findet ihr im Archiv:`0`n");}$session['needtoviewmotd']=false;$sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";$result = db_query($sql);$row = db_fetch_assoc($result);$session['user']['lastmotd']=$row['motddate'];}popup_footer();

