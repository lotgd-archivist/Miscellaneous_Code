<?php

// 11072004

// Item Editor
// by anpera; based on mount editor
//
// This is for administer items of all kind with anpera's item table
// (first introduced in houses mod)
// items table REQUIRED!
//
// insert:
// 	if ($session[user][superuser]>=2) addnav("Item Editor","itemeditor.php");
// into menu of superuser.php
//

require_once "common.php";
isnewday(4);
page_header("Item Editor");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

if ($_GET['op']=="del"){
	$sql = "DELETE FROM items WHERE id=".(int)$_GET['id'];
	db_query($sql);
	$_GET['op']='';
	$_GET['show']=$_GET['show']; // huh? weshalb hab ich das geschrieben?
}

if ($_GET['op']=="add"){
	output("Item erzeugen:`n");
	addnav("Item Editor","itemeditor.php");
	if ($_GET['show']) addnav($_GET['show'],"itemeditor.php?show=".urlencode($_GET['show'])."");
	itemform(array("class"=>$_GET['show']));
}elseif ($_GET['op']=="edit"){
	addnav("Item Editor","itemeditor.php");
	if ($_GET['show']) addnav($_GET['show'],"itemeditor.php?show=".urlencode($_GET['show'])."");
	$sql = "SELECT * FROM items WHERE id='".(int)$_GET['id']."'";
	$result = db_query($sql);
	if (db_num_rows($result)<=0){
		output("`iItem nicht vorhanden.`i");
	}else{
		output("Item Editor:`n");
		$row = db_fetch_assoc($result);
		$row['buff']=unserialize($row['buff']);
		itemform($row);
	}
}elseif ($_GET['op']=="save"){
	$buff = array();
	reset($_POST['item']['buff']);
	if (isset($_POST['item']['buff']['activate'])) $_POST['item']['buff']['activate']=join(",",$_POST['item']['buff']['activate']);
	foreach($_POST['item']['buff'] as $key => $val){
		if ($val>""){
			$buff[$key]=stripslashes($val);
		}
	}
	$_POST['item']['buff']=$buff;
	reset($_POST['item']);
	$keys='';
	$vals='';
	$sql='';
	$i=0;
	foreach($_POST['item'] as $key => $val){
		if (is_array($val)) $val = mysql_real_escape_string(serialize($val));
		if ($_GET['id']>""){
			$sql.=($i>0?",":"")."$key='$val'";
		}else{
			$keys.=($i>0?",":"")."$key";
			$vals.=($i>0?",":"")."'$val'";
		}
		$i++;
	}
	if ($_GET['id']>""){
		$sql="UPDATE items SET $sql WHERE id='".(int)$_GET['id']."'";
	}else{
		$sql="INSERT INTO items ($keys) VALUES ($vals)";
	}
	db_query($sql);
	if (db_affected_rows()>0){
		output("Item gespeichert!");
	}else{
		output("Item nicht gespeichert: $sql");
	}
	addnav("Item Editor","itemeditor.php");
	if ($_GET['show']) addnav($_GET['show'],"itemeditor.php?show=".urlencode($_GET['show'])."");
}else{
	if($_GET['show']){
		$ppp=50; // Player Per Page to display
		if (!$_GET[limit]){
			$page=0;
		}else{
			$page=(int)$_GET['limit'];
			addnav("Vorherige Seite","itemeditor.php?show=".urlencode($_GET['show'])."&limit=".($page1));
		}
		$limit="".($page$ppp).",".($ppp1);
		$sql = "SELECT items.*,accounts.name AS ownername FROM items
		LEFT JOIN accounts ON accounts.acctid=items.owner WHERE class='".$_GET['show']."' ORDER BY id LIMIT $limit";
	rawoutput("<table><tr><td>Ops</td><td>Name</td><td>Besitzer</td><td>Beschreibung</td></tr>");
		$result = db_query($sql);
		if (db_num_rows($result)>$ppp) addnav("Nächste Seite","itemeditor.php?show=".urlencode($_GET['show'])."&limit=".($page1)."");
		$cat = "";
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			rawoutput("<tr><td>[ <a href='itemeditor.php?op=edit&id=".$row['id']."&show=".urlencode($row['class'])."'>Edit</a> |");
			allownav("itemeditor.php?op=edit&id=".$row['id']."&show=".urlencode($row['class']));
			rawoutput(" <a href='itemeditor.php?op=del&id=".$row['id']."&show=".urlencode($row['class'])."' onClick=\"return confirm('Diesen Gegenstand wirklich löschen?');\">Löschen</a> ]</td>");
			allownav("itemeditor.php?op=del&id=".$row['id']."&show=".urlencode($row['class'])."");
			output("<td>".$row['name']."</td><td>".$row['ownername']."</td><td>".$row['description']."</td></tr>",true);
		}
		rawoutput('</table>');
		addnav("Item Editor","itemeditor.php");
		addnav("Item hinzufügen","itemeditor.php?op=add&show=".urlencode($_GET['show']));
	}else{
		$sql = "SELECT class FROM items ORDER BY class";
		rawoutput("Verfügbare 'classes':<br /><br /><table><tr><td>Name</td></tr>");
		$result = db_query($sql);
		$cat = "";
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			if ($cat!=$row['class']){
				rawoutput("<tr><td><a href='itemeditor.php?show=".urlencode($row['class'])."'>".$row['class']."</a></td></tr>");
				$cat = $row['class'];
				allownav("itemeditor.php?show=".urlencode($row['class'])."");
				rawoutput('</tr>');
			}
		}
		rawoutput('</table><br /><br />Um eine class zu löschen, müssen alle Items dieser class gelöscht werden.`nUm eine neue class zu erstellen, einfach ein Item erzeugen.');
		addnav("Item hinzufügen","itemeditor.php?op=add");
	}
}

function itemform($item){
	global $output;
	rawoutput("<form action='itemeditor.php?op=save&id=".$item['id']."' method='POST'>");
	allownav("itemeditor.php?op=save&id=".$item['id']);
	$output.="<table>";
	$output.="<tr><td>Item Name:</td><td><input name='item[name]' value=\"".htmlspecialchars($item['name'])."\" maxlength='50' /></td></tr>";
	$output.="<tr><td>Item Beschreibung:</td><td><input name='item[description]' value=\"".htmlspecialchars($item['description'])."\" maxlength='500' /></td></tr>";
	$output.="<tr><td>Item Class:</td><td><input name='item[class]' value=\"".htmlspecialchars($item['class'])."\" maxlength='25' /></td></tr>";
	$output.="<tr><td>Besitzer ID:</td><td><input name='item[owner]' value=\"".htmlspecialchars((int)$item['owner'])."\" size='5' /></td></tr>";
	$output.="<tr><td>Item Wert (Edelsteine):</td><td><input name='item[gems]' value=\"".htmlspecialchars((int)$item['gems'])."\" size='5' /></td></tr>";
	$output.="<tr><td>Item Wert (Gold):</td><td><input name='item[gold]' value=\"".htmlspecialchars((int)$item['gold'])."\" size='5' /></td></tr>";
	$output.="<tr><td>Item Wert 1:</td><td><input name='item[value1]' value=\"".htmlspecialchars((int)$item['value1'])."\" size='5' /></td></tr>";
	$output.="<tr><td>Item Wert 2:</td><td><input name='item[value2]' value=\"".htmlspecialchars((int)$item['value2'])."\" size='5' /></td></tr>";
	$output.="<tr><td>Versteckter Wert:</td><td><input name='item[hvalue]' value=\"".htmlspecialchars((int)$item['hvalue'])."\" size='5' /></td></tr>";
	$output.="<tr><td valign='top'>Item Buff:</td><td>";
	$output.="<b>Meldungen:</b><br/>";
	$output.="Buff Name: <input name='item[buff][name]' value=\"".htmlspecialchars($item['buff']['name'])."\" /><br/>";

	$output.="Meldung jede Runde: <input name='item[buff][roundmsg]' value=\"".htmlspecialchars($item['buff']['roundmsg'])."\" /><br/>";
	$output.="Ablaufmeldung: <input name='item[buff][wearoff]' value=\"".htmlspecialchars($item['buff']['wearoff'])."\" /><br/>";
	$output.="Effektmeldung: <input name='item[buff][effectmsg]' value=\"".htmlspecialchars($item['buff']['effectmsg'])."\" /><br/>";
	$output.="Kein Schaden Meldung: <input name='item[buff][effectnodmgmsg]' value=\"".htmlspecialchars($item['buff']['effectnodmgmsg'])."\" /><br/>";
	$output.="Fehlgeschlagen Meldung: <input name='item[buff][effectfailmsg]' value=\"".htmlspecialchars($item['buff']['effectfailmsg'])."\" /><br/>";
	$output.="<br/><b>Effekt:</b><br/>";
	$output.="Hält Runden (nach Aktivierung): <input name='item[buff][rounds]' value=\"".htmlspecialchars($item['buff']['rounds'])."\" size='5' /><br/>";
	$output.="Angriffsmulti Spieler: <input name='item[buff][atkmod]' value=\"".htmlspecialchars($item['buff']['atkmod'])."\" size='5' /><br/>";
	$output.="Verteidigungsmulti Spieler: <input name='item[buff][defmod]' value=\"".htmlspecialchars($item['buff']['defmod'])."\" size='5' /><br/>";
	$output.="Regen: <input name='item[buff][regen]' value=\"".htmlspecialchars($item['buff']['regen'])."\" /><br/>";
	$output.="Diener Anzahl: <input name='item[buff][minioncount]' value=\"".htmlspecialchars($item['buff']['minioncount'])."\" /><br/>";
	$output.="Min Badguy Damage: <input name='item[buff][minbadguydamage]' value=\"".htmlspecialchars($item['buff']['minbadguydamage'])."\" size='5' /><br/>";
	$output.="Max Badguy Damage: <input name='item[buff][maxbadguydamage]' value=\"".htmlspecialchars($item['buff']['maxbadguydamage'])."\" size='5' /><br/>";
	$output.="Lifetap: <input name='item[buff][lifetap]' value=\"".htmlspecialchars($item['buff']['lifetap'])."\" size='5' /><br/>";
	$output.="Damage shield: <input name='item[buff][damageshield]' value=\"".htmlspecialchars($item['buff']['damageshield'])."\" size='5' /> (multiplier)<br/>";
	$output.="Badguy Damage mod: <input name='item[buff][badguydmgmod]' value=\"".htmlspecialchars($item['buff']['badguydmgmod'])."\" size='5' /> (multiplier)<br/>";
	$output.="Badguy Atk mod: <input name='item[buff][badguyatkmod]' value=\"".htmlspecialchars($item['buff']['badguyatkmod'])."\" size='5' /> (multiplier)<br/>";
	$output.="Badguy Def mod: <input name='item[buff][badguydefmod]' value=\"".htmlspecialchars($item['buff']['badguydefmod'])."\" size='5' /> (multiplier)<br/>";

	
	$output.="<br/><b>Aktiviert bei:</b><br/>";
	$output.="<input type='checkbox' name='item[buff][activate][]' value=\"roundstart\"".(strpos_c($item['buff']['activate'],"roundstart")!==false?" checked":"")." /> Start der Runde<br/>";
	$output.="<input type='checkbox' name='item[buff][activate][]' value=\"offense\"".(strpos_c($item['buff']['activate'],"offense")!==false?" checked":"")." /> Bei Angriff<br/>";
	$output.="<input type='checkbox' name='item[buff][activate][]' value=\"defense\"".(strpos_c($item['buff']['activate'],"defense")!==false?" checked":"")." /> Bei Verteidigung<br/>";
	$output.="<br/>";
	$output.="</td></tr>";
	$output.="</table>";
	$output.="<input type='submit' class='button' value='Speichern' /></form>";
}
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>