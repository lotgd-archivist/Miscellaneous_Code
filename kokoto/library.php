<?php
/*
* author: bibir (logd_bibir@email.de)
*      and Chaosmaker (webmaster@chaosonline.de)
*      for http://logd.chaosonline.de
*
* version: 1.2
*
*     a library with text from users to help other
* 	   a bit like faq
*
* details:
*  (15.11.04) start of idea
*  (15.01.05) project finished
*  (16.01.05) version 1.2: several minor bugfixes
*/
require_once "common.php";
addcommentary();
checkday();

if(!isset($_GET['op'])) $_GET['op']='';

addnav('Zurück zur Hauptstraße','village.php?op=hauptstrasse');
addnav('Bibliothek');


$sql = "SELECT count(bookid) AS anz FROM lib_books WHERE activated='1'";
$result = db_query($sql);
$books = db_fetch_assoc($result);
page_header("Bibirs Bibliothek");
output("`c`b`9Bibirs Bibliothek des gesammelten Wissens in ".($books['anz']==1?'einem Band':$books['anz'].' Bänden')."`0`b`c`n");

switch($_GET['op']){
case "browse":
	addnav("H?Zurück in die Halle","library.php");
	addnav("Buch einreichen","library.php?op=offer");
	output("`tDu gehst durch die Regalreihen und siehst, dass alle Bücher ordentlich nach Themen einsortiert sind.`n
	Folgende Themen stehen derzeit zur Auswahl:`n`n");
	$sql = "SELECT t.*, COUNT(b.bookid) as anz FROM lib_themes t
			LEFT JOIN lib_books b ON b.themeid=t.themeid AND b.activated='1'
			GROUP BY themeid
			ORDER BY listorder ASC";
	$result = db_query($sql);
	output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td>Thema</td><td>Bücher</td></tr>",true);
	$bgclass = '';
	addnav("Themen");
	while ($row = db_fetch_assoc($result)) {
		$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
		if ($row['anz']>0) {
			output("<tr class='$bgclass'><td><a href=\"library.php?op=theme&id=".$row['themeid']."\">",true);
			output($row['theme']);
			output("`0</a></td><td align='right'>".$row['anz']."</td></tr>",true);
		}
		else {
			output("<tr class='$bgclass'><td>",true);
			output($row['theme']);
			output("`0</td><td>kein Buch</td></tr>",true);
		}
		allownav("library.php?op=theme&id=".$row['themeid']);
		addnav($row['theme'],"library.php?op=theme&id=".$row['themeid']);
	}
	rawoutput('</table>');
	break;

case "theme":
	addnav("H?Zurück in die Halle","library.php");
	addnav("Buch einreichen","library.php?op=offer");

	addnav("Themen");
	$sql = "SELECT themeid, theme FROM lib_themes ORDER BY listorder ASC";
	$result = db_query($sql);
	while ($row = db_fetch_assoc($result)) {
		if ($row['themeid']!=(int)$_GET['id']) {
			addnav($row['theme'],"library.php?op=theme&id=".$row['themeid']);
 		}
		else {
			addnav($row['theme'],'');
			$thistheme = $row['theme'];
		}
	}

	output("`c`b".$thistheme."`0`b`c");
	output("`n`6Zu diesem Thema stehen dir folgende Bücher zur Verfügung:`n`n");

	$sql = "SELECT title, bookid, author FROM lib_books
			WHERE themeid=".(int)$_GET['id']." AND activated='1' ORDER BY listorder ASC";
	$result = db_query($sql);
	output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trhead'><td>Titel</td><td>Autor</td></tr>",true);
	if (db_num_rows($result)==0) {
		rawoutput("<tr class='trdark'><td colspan='2'>Es gibt leider bisher noch keine Bücher zu diesem Thema.</td></tr>");
	}
	else {
		addnav('Bücher');
		$bgclass = '';
		while ($row = db_fetch_assoc($result)) {
			$bgclass = ($bgclass=='trdark'?'trlight':'trdark');
			output("<tr class='$bgclass'><td><a href=\"library.php?op=book&bookid=".$row['bookid']."\">",true);
			output($row['title']);
			output("`0</a></td><td>",true);
			output($row['author']);
			output("`0</td></tr>",true);
			allownav("library.php?op=book&bookid=".$row['bookid']);
			addnav($row['title'],'library.php?op=book&bookid='.$row['bookid']);
		}
	}
	rawoutput('</table>');
	break;

case "book":
	addnav("H?Zurück in die Halle","library.php");

	$sql = "SELECT t.theme, b.themeid, b.title, b.book, b.author FROM lib_books b
			LEFT JOIN lib_themes t USING(themeid)
			WHERE bookid=".(int)$_GET['bookid'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);

	addnav("Buch einreichen","library.php?op=offer");

	addnav("Themen");
	$sql = "SELECT themeid, theme FROM lib_themes ORDER BY listorder ASC";
	$result = db_query($sql);
	while ($row2 = db_fetch_assoc($result)) {
		addnav($row2['theme'],"library.php?op=theme&id=".$row2['themeid']);
	}

	addnav('Bücher');
	$sql = 'SELECT title, bookid FROM lib_books WHERE themeid='.$row['themeid'].' AND activated="1" ORDER BY listorder ASC';
	$result = db_query($sql);
	while ($row2 = db_fetch_assoc($result)) {
		if ($row2['bookid']!=(int)$_GET['bookid']) addnav($row2['title'],'library.php?op=book&bookid='.$row2['bookid']);
		else addnav($row2['title'],'');
	}

	//nichts editierbar
	rawoutput("<table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trdark'><td>Thema:</td><td>");
	output($row['theme']);
	rawoutput("</span></td></tr><tr class='trlight'><td>Titel:</td><td>");
	output($row['title']);
	rawoutput("</span></td></tr><tr class='trdark'><td>Autor:</td><td>");
	output($row['author']);
	rawoutput("</span></td></tr><tr class='trlight'><td colspan='2'>");
	output(str_replace_c("\n",'`n',$row['book']));
	rawoutput('</td></tr></table>');
	break;

case "offer":
	addnav("H?Zurück in die Halle","library.php");
	if ($_GET['subop']=="save" && !empty($_POST['title']) && !empty($_POST['book'])) {
		addnav("Weiteres Buch schreiben","library.php?op=offer");
		output("`tDein Buch wurde zum Druck eingereicht.`0");
		// maximale sortiernummer holen
		$sql = 'SELECT MAX(listorder) AS maxorder FROM lib_books';
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		$sql = "INSERT INTO lib_books (themeid, acctid, author, title, book, listorder)
			VALUES ('{$_POST['themeid']}', '{$session['user']['acctid']}', '{$session['user']['name']}', '{$_POST['title']}', '{$_POST['book']}', '{$row['maxorder']}')";
		db_query($sql);
	}
	else {
		if ($_GET['subop']=='save') {
			output('`c`$Wie soll ein Buch gedruckt werden, wenn nicht Titel und Inhalt existieren?`0`c`n`n');
			$_POST['title'] = str_replace_c('`','``',$_POST['title']);
			$_POST['book'] = str_replace_c('`','``',$_POST['book']);
		}
		else $_POST['title'] = $_POST['book'] = $_POST['themeid'] = '';
		output("`tHier hast du die Möglichkeit, eigenes Wissen niederzuschreiben und anderen damit zur Verfügung zu stellen.`n`n
		Nun liegt es an dir, die Zeilen auf das Pergament zu bringen, die du dein Wissen nennst.`0");
		output("<form action=\"library.php?op=offer&subop=save\" method='POST'><table cellpadding=2 cellspacing=1 bgcolor='#999999'><tr class='trdark'><td>Thema:</td><td><select name='themeid'>",true);
		$sql2 = "SELECT * FROM lib_themes ORDER BY listorder ASC";
		$result2 = db_query($sql2);
		while ($row2 = db_fetch_assoc($result2)) {
			output("<option value='".$row2['themeid']."' ".($row2['themeid']==$_POST['themeid']?" selected='selected'":"").">".preg_replace_c('/`./','',$row2['theme'])."</option>",true);
		}
		rawoutput("</select></td></tr><tr class='trlight'><td>Titel:</td><td><input class='input' type='text' name='title' value='{$_POST['title']}' maxlength='50' size='50'></td></tr><tr class='trdark'><td colspan='2'>Mein Wissen über dieses Thema:</td></tr><tr class='trdark'><td colspan='2'><textarea name='book' class='input' cols='60' rows='10'>{$_POST['book']}</textarea></td></tr><tr class='trlight'><td colspan='2'><input type='submit' class='button' value='Einreichen'></td></tr></table></form>");
		allownav("library.php?op=offer&subop=save");
	}
	break;

default:
	output('`tAm Eingang zur Bibliothek hängt ein Plakat. Du liest:`n`n`qDie Bibliothek ist ein Ort des Wissens.`n
           Dieses Wissen kann aber nur gehalten werden, wenn jemand es niedergeschrieben hat.`n
           Dazu steht in dieser Bibliothek die Möglichkeit bereit, Texte zu verfassen und diese einzureichen.`n
           Nach Genehmigung durch Regenten oder Bevollmächtigte wird das Buch gedruckt und in die Regale der Bücherei gestellt.`n
           Von nun an hat jeder die Möglichkeit, einen Blick in dieses Buch zu werfen und sowohl interessante als auch nützliche Informationen zu bekommen.`n
           Sollte das geschriebene Buch gedruckt werden, erhält der Autor ein Dankeschön in Form von '.getsetting("libdp","25").' Punkten in J.C. Petersens Jägerhütte.`n`n');
	output('`tDu betrittst den großen Raum mit den unzähligen Regalen.`n
	Hier sind gedämpfte Unterhaltungen zu hören und in den vielen bequemen
	Drachenleder-Sesseln sitzen eifrige Kämpfer, um zu lesen.`n
	An ein paar Tischen kannst du hin und wieder auch sehr erfahrene Drachentöter finden,
	die ihr Wissen in Büchern niederschreiben.');
	output("`n`nEin paar Leute unterhalten sich leise:`n`0");
	viewcommentary("library","Leise flüstern:",10);
	addnav("Stöbern","library.php?op=browse");
	addnav("Buch einreichen","library.php?op=offer");
}

page_footer();
?>