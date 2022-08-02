<?
require_once "common.php";
isnewday(2);

page_header("Spott Editor");
addnav('G?Zurück zur Grotte','superuser.php');
addnav('W?Zurück zum Weltlichen','village.php');
if ($_GET['op']=='edit'){
	addnav('Spotteditor','taunt.php');
	rawoutput("<form action='taunt.php?op=save&tauntid=".$_GET['tauntid']."' method='POST'>");
	allownav("taunt.php?op=save&tauntid=".$_GET['tauntid']);
	if ($_GET['tauntid']!=''){
		$sql = "SELECT * FROM taunts WHERE tauntid=\"".(int)$_GET['tauntid']."\"";
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$taunt = stripslashes($row['taunt']);
		$taunt = str_replace_c("%s","`\$ihn",$taunt);
		$taunt = str_replace_c("%o","`\$er",$taunt);
		$taunt = str_replace_c("%p","`\$sein",$taunt);
		$taunt = str_replace_c("%x","`\$Schwert",$taunt);
		$taunt = str_replace_c("%X","`\$Scharfe Zähne",$taunt);
		$taunt = str_replace_c("%W","`\$Grosse Ratte",$taunt);
		$taunt = str_replace_c("%w","`\$Tidus",$taunt);
		output("Vorschau: $taunt`0`n`n");
	}
	$output.="Taunt: <input name='taunt' value=\"".stripslashes(htmlspecialchars($row['taunt']))."\" size='70'><br />";
	output("`nDie folgenden Codes werden unterstützt (Groß- und Kleinschriebung wird unterschieden):`n%w = Name des Spielers`n %W = Name des Monsters`n %x = Waffe des Spielers`n  %X = Waffe des Monsters`n %s = Geschlecht des Spielers (ihn/sie)`n %p = Geschlecht des Spielers (sein/ihr)`n %o = Geschlecht des Spielers (er/sie)`n ");
	rawoutput("<input type='submit' class='button' value='Speichern'></form>");
}else if($_GET['op']=='del'){
	$sql = "DELETE FROM taunts WHERE tauntid='".(int)$_GET['tauntid']."'";
	db_query($sql);
	redirect("taunt.php?c=x");
}else if($_GET['op']=='save'){
	if ($_GET['tauntid']!=""){
		$sql = "UPDATE taunts SET taunt=\"".mysql_real_escape_string($_POST['taunt'])."\",editor=\"".mysql_real_escape_string($session['user']['login'])."\" WHERE tauntid=\"".(int)$_GET['tauntid']."\"";
	}else{
		$sql = "INSERT INTO taunts (taunt,editor) VALUES (\"".mysql_real_escape_string($_POST['taunt'])."\",\"".mysql_real_escape_string($session['user']['login'])."\")";
	}
	db_query($sql);
	redirect("taunt.php?c=x");
}else{
	$sql = "SELECT * FROM taunts";
	$result = db_query($sql);
	rawoutput('<table>');
	for ($i=0;$i<db_num_rows($result);$i++){
		$row=db_fetch_assoc($result);
		$taunt = stripslashes($row['taunt']);
		$taunt = str_replace_c("%s","`\$ihn",$taunt);
		$taunt = str_replace_c("%o","`\$er",$taunt);
		$taunt = str_replace_c("%p","`\$sein",$taunt);
		$taunt = str_replace_c("%x","`\$Schwert",$taunt);
		$taunt = str_replace_c("%X","`\$Scharfe Zähne",$taunt);
		$taunt = str_replace_c("%W","`\$Grosse Ratte",$taunt);
		$taunt = str_replace_c("%w","`\$Tidus",$taunt);
		rawoutput("<tr><td>");
		rawoutput("[<a href='taunt.php?op=edit&tauntid=".$row['tauntid']."'>Edit</a>]");
		allownav("taunt.php?op=edit&tauntid=".$row['tauntid']);
		if ($session['user']['superuser']>=4){
		output("[<a href='taunt.php?op=del&tauntid=".$row['tauntid']."' onClick='return confirm(\"Diesen Eintrag ".mysql_real_escape_string(stripcolors($taunt))." wirklich löschen?\");'>Löschen</a>]",true);
		allownav("taunt.php?op=del&tauntid=".$row['tauntid']);
		}
		rawoutput("</td><td>");
		output($taunt);
		rawoutput("</td><td>");
		rawoutput(stripslashes($row['editor']));
		rawoutput("</td><td>");
	}
	allownav("taunt.php?c=".$_GET['c']);
	rawoutput('</table>');
	addnav('Spott hinzufügen','taunt.php?op=edit');
}
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>