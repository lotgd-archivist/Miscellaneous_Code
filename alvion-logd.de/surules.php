
<?// Make By:      DarkAngel// Email:        webmaster@esport-network.de// Website:      http://lotgd.esport-network.derequire_once "common.php";$rulesperpage=6;$sql = "SELECT count(id) AS c FROM rules";$result = db_query($sql);$row = db_fetch_assoc($result);$totalrules = $row['c'];if ($_GET['op']=="search"){    $search="%";    for ($x=0;$x<strlen($_POST['name']);$x++){        $search .= substr($_POST['name'],$x,1)."%";    }    $search=" AND name LIKE '".addslashes($search)."' ";}else{    $pageoffset = (int)$_GET['page'];    if ($pageoffset>0) $pageoffset--;    $pageoffset*=$rulesperpage;    $from = $pageoffset+1;    $to = min($pageoffset+$rulesperpage,$totalrules);    $limit=" LIMIT $pageoffset,$rulesperpage ";}addnav("Seiten");for ($i=0;$i<$totalrules;$i+=$rulesperpage){    addnav("Seite ".($i/$rulesperpage+1)." (".($i+1)."-".min($i+$rulesperpage,$totalrules).")","surules.php?page=".($i/$rulesperpage+1));}page_header("The Esport-Network - Legend of the Green Dragon - Regeln");output("`Q`b`cThe Esport-Network - Legend of the Green Dragon - Regeln`c`b`0`n`n");if ($_GET[op]==""){        output("`n`c<table width=650 border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);        output("<tr class='trhead'><td><b>§</b></td><td><b>Überschrift</b></td><td><b>Beschreibung</b></td><td><b>EDIT</td></b><td><b>DEL</b></td>",true);        $sql = "SELECT * FROM rules ORDER BY para ASC $limit";        $result = db_query($sql);        if (db_num_rows($result)==0){ output("<tr><td colspan=4 align='center'>`&`i`cEs sind keine Einträge vorhanden`c`i`0</td></tr>",true);}        for ($i=0;$i<db_num_rows($result);$i++){        $row = db_fetch_assoc($result);        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);        output("`q$row[para]`n");        output("</td><td>",true);        output("`^$row[ueberschrift]`n");        output("</td><td>",true);        output("`6$row[beschreibung]`n");        output("</td>",true);                output("<td><a href='surules.php?op=edit&id=".urlencode($row[id])."'>`cEDIT`c</a></td>",true);        addnav("","surules.php?op=edit&id=".urlencode($row[id])."");        output("</td>",true);if ($session[user][superuser]>=3){        output("<td><a href='surules.php?op=del&id=".urlencode($row[id])."'>`cDEL`c</a></td>",true);        addnav("","surules.php?op=del&id=".urlencode($row[id])."");}else{output("<td>",true);output("`6----`n");        output("</td>",true);}        }        output("</table>`c",true);        }if ($_GET[op]=="del"){        db_query("DELETE FROM rules WHERE id = '".$_GET[id]."'");        redirect("surules.php");        }if ($_GET[op]=="edit"){        $sql = "SELECT * FROM rules WHERE id = '".$_GET[id]."'";                $result = db_query($sql) or die(db_error(LINK));                $row = db_fetch_assoc($result);addnav("`3Zurück","surules.php");        output("`n`n`n<form action=\"surules.php?op=edit2&id=".urlencode($_GET['id'])."\"  method='POST' action=''>",true);        $output.="        <table width='250' border='0' align='center' cellpadding='0' cellspacing='0'>          <tr>        <td width='86'>Paragraph:</td>        <td width='114'><input name='para' type='text' value='$row[para]'></td>          </tr>          <tr>        <td>Überschrift:</td>        <td><input name='ueberschrift' type='text' value='$row[ueberschrift]'></td>          </tr>          <tr></table>    <table width='250' border='0' align='center' cellpadding='0' cellspacing='0'>        <td width='86'>Beschreibung:</td>        <td width='114'><textarea name='beschreibung'  class='input' cols='45' rows='10'>".HTMLSpecialChars($row['beschreibung'])."</textarea></td>          </tr>          <tr><br><br><br>        <td colspan='2' align='center'><input type='submit' class='button' value='Eintrag ändern'></td>          </tr>        </table>        ";        output("</form>",true);        addnav("","surules.php?op=edit2&id=".urlencode($_GET['id'])."");                }if ($_GET[op]=="edit2"){$sql = "SELECT * FROM rules ";$result = db_query($sql);$row = db_fetch_assoc($result);$beschreibung= mysqli_real_escape_string($mysqli, stripslashes($_POST['beschreibung']));db_query("UPDATE rules SET para ='".$_POST[para]."',ueberschrift ='".$_POST[ueberschrift]."',beschreibung ='$beschreibung' WHERE id='".$_GET[id]."'");//    db_query("UPDATE rules SET ueberschrift ='".$_POST[ueberschrift]."' WHERE '".$row['id']."'");//    db_query("UPDATE rules SET beschreibung ='$_POST[beschreibung]' WHERE '".$row['id']."'");redirect("surules.php");        }//$sql = "UPDATE accounts SET emailhide = '1' WHERE emailhide = '0' AND acctid = '".$session[user][acctid]."'";if ($_GET[op]=="create"){        addnav("`3Zurück","surules.php");        output("`n`n`n<form action=\"surules.php?op=create2\" method='POST' action=''>",true);        output("        <table width='250' border='0' align='center' cellpadding='0' cellspacing='0'>          <tr>        <td width='86'>Paragraph:</td>        <td width='114'><input name='para' type='text'></td>          </tr>          <tr>        <td>Überschrift:</td>        <td><input name='ueberschrift' type='text'></td>          </tr>          <tr></table>    <table width='250' border='0' align='center' cellpadding='0' cellspacing='0'>        <td width='86'>Beschreibung:</td>        <td width='114'><textarea cols=45 rows=10  name='beschreibung' type='text'></textarea></td>          </tr>          <tr><br><br><br>        <td colspan='2'>`c<input type='submit' class='button' value='Eintrag erstellen'>`c</td>          </tr>        </table>        ",true);        output("</form>",true);        addnav("","surules.php?op=create2");        }if ($_GET[op]=="create2"){    $beschreibung= mysqli_real_escape_string($mysqli, stripslashes($_POST['beschreibung']));    db_query("INSERT INTO rules (para,ueberschrift,beschreibung) VALUES ('$_POST[para]','$_POST[ueberschrift]','$beschreibung')");    redirect("surules.php");}addnav ("Möglichkeit");addnav ("Regel erstellen","surules.php?op=create");addnav ("Sonstiges");addnav ("Aktualisieren","surules.php");addnav ("Zurück zur Grotte","superuser.php");page_footer();

