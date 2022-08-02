<?php
// 17092004
/*
riddle editor for 0.9.7 ext GER by anpera
based on the code from creature editor by mightye
*/
require_once "common.php";
isnewday(2);
page_header("Rätseleditor");
if ($session['user']['superuser'] >=4){
	addnav('G?Zurück zur Grotte','superuser.php');
	addnav('W?Zurück zum Weltlichen','village.php');
	if ($_POST['save']<>''){
		if ($_POST['id']!=''){
			$sql="UPDATE riddles SET riddle='{$_POST['riddle']}',answer='{$_POST['answer']}' WHERE id={$_POST['id']}";
			output(db_affected_rows()." ".(db_affected_rows()==1?"Eintrag":"Einträge")." geändert.");
		}else{
			$sql="INSERT INTO riddles (riddle,answer) VALUES ('{$_POST['riddle']}','{$_POST['answer']}')";
		}
		db_query($sql);
	}
	if ($_GET['op']=='del'){
		$sql = "DELETE FROM riddles WHERE id={$_GET['id']}";
		db_query($sql);
		if (db_affected_rows()>0){
			output("Rätsel gelöscht`n`n");
		}else{
			output("Rätsel nicht gelöscht: ".db_error(LINK));
		}
		$_GET['op']='';
	}
	if ($_GET['op']==''){
		$sql = "SELECT * FROM riddles ORDER BY riddle";
		$result = db_query($sql);
		addnav("Rätsel hinzufügen","riddleditor.php?op=add");
		rawoutput("<table><tr><td>Ops</td><td width='50%'>Rätsel</td><td>Lösung</td></tr>");
		allownav("riddleditor.php");
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr><td valign='top'> [<a href='riddleditor.php?op=edit&id={$row['id']}'>Edit</a>|".
			"<a href='riddleditor.php?op=del&id={$row['id']}' onClick='return confirm(\"Bist du dir sicher, dass du dieses Rätsel löschen willst?\");'>Del</a>] </td><td>",true);
			allownav("riddleditor.php?op=edit&id={$row['id']}");
			allownav("riddleditor.php?op=del&id={$row['id']}");
			output($row['riddle']);
			rawoutput('</td><td>');
			output($row['answer']);
			rawoutput('</td></tr>');
		}
		rawoutput('</table>');
	}else{
		if ($_GET['op']=='edit' || $_GET['op']=='add'){
			if ($_GET['op']=='edit'){
				$sql = "SELECT * FROM riddles WHERE id=".(int)$_GET['id'];
				$result = db_query($sql);
				if (db_num_rows($result)<>1){
					output("`4Fehler`0, dieses Rätsel wurde nicht gefunden!");
				}else{
					$row = db_fetch_assoc($result);
				}
			}
			output("<form action='riddleditor.php' method='POST'><input name='id' value=\"".(int)$_GET['id']."\" type='hidden'><table border='0' cellpadding='2' cellspacing='0'><tr><td>Rätsel:</td><td><textarea name='riddle' class='input' cols='50' rows='9'>".htmlspecialchars(str_replace_c('`','``',$row['riddle'])) ."</textarea></td></tr><tr><td>Antwort: </td><td><input name='answer' maxlength='250' size='50' value=\"".htmlspecialchars($row['answer'])."\"></td></tr>",true);
			rawoutput("<tr><td colspan='2'><input type='hidden' name='save' value='Save'><input type='submit' class='button' name='submit' value='Speichern'></td></tr></table></form>");
			allownav("riddleditor.php");
		}else{
		}
		addnav('Zurück zum Rätsel-Editor","riddleditor.php');
	}
}else{
	output("Weil du versucht hast, die Götter zu betrügen, wurdest du niedergeschmettert!");
	addnews("`&".$session['user']['name']." wurde für den Versuch, die Götter zu betrügen, niedergeschmettert (hat versucht die Superuser-Seiten zu hacken).");
	$session['user']['hitpoints']=0;
}
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>