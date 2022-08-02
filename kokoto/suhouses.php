<?php
/*
* Version:	25.04.2004
* Author:	anpera
* Email:		logd@anpera.de
*
* Purpose:	Admin tool for houses
*
* BETA !!
*
* Ok, lets do the code...
*
*
* Version:	02.07.2005
* Revision:	Chaosmaker
* Email:		webmaster@chaosonline.de
*/


require_once("common.php");
isnewday(4);
require_once('housefunctions.php');

page_header("Hausmeister");

addnav('W?Zurück zum Weltlichen','village.php');
addnav('G?Zurück zur Grotte','superuser.php');

if ($_GET['op']=="drin"){
	if (!empty($_GET['subop'])) {
		if ($_GET['subop']=='addmodule') {
			// add a module
			$sql = 'SELECT modulefile, modulename FROM housemodules WHERE moduleid='.(int)$_GET['mid'];
			$result = db_query($sql);
			if ($row = db_fetch_assoc($result)) {
				require_once('housemodules/'.$row['modulefile']);
				$function = 'module_build_'.$row['modulename'];
				$function($_GET['id']);
			}
		}
		elseif ($_GET['subop']=='delmodule') {
			// delete a module
			$sql = 'SELECT modulefile, modulename FROM housemodules WHERE moduleid='.(int)$_GET['mid'];
			$result = db_query($sql);
			if ($row = db_fetch_assoc($result)) {
				require_once('housemodules/'.$row['modulefile']);
				$function = 'module_destroy_'.$row['modulename'];
				$function($_GET['id']);
			}
		}
	}
	addnav("Schlüssel hinzufügen","suhouses.php?op=keys&hid=".$_REQUEST['id']);
	addnav("Daten ändern","suhouses.php?op=data&id=".$_REQUEST['id']);
	addnav("Haus zerstören","suhouses.php?op=destroy&id=".$_REQUEST['id']); // bad idea
	addnav("Kommentare","suhouses.php?op=comment&id=".$_REQUEST['id']);
	addnav("Hausmeister","suhouses.php");
	$sql="SELECT * FROM houses WHERE houseid=".$_REQUEST['id'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	output("`n`@Hausnummer: `^`b".$row['houseid']."`b `n`@Name: `^`b".$row['housename']."`b `n`@Beschreibung: `^`b".$row['description']."`b `n`@Goldpreis (wenn im Bau): `^`b".$row['goldprice']."`b `n`@Edelsteinpreis (wenn im Bau): `^`b".$row['gemprice']."`b `n`@Status: `^`b".$row['status']."`b (");
	if ($row['status']=='build') {
		if ($row['owner']>0) output("`6im Bau`0");
		else output("`\$Bauruine`0");
	}
	elseif ($row['status']=='ready') {
		if ($row['owner']>0) output("`!bewohnt`0");
		else output("`4verlassen`0");
	}
	elseif ($row['status']=='sell') output("`^zum Verkauf`0");
	if ($row['owner']>0) {
		$sql = "SELECT name FROM accounts WHERE acctid=".$row['owner'];
		$result2 = db_query($sql);
		$row2  = db_fetch_assoc($result2);
		output("`^)`n`@Besitzer: `^`b".$row['owner']."`b (".$row2['name']."`^)");
	}
	else output("`^)`n`@Besitzer: `^`b`iderzeit niemand`i`b");
	output("`n`n`@Schlüssel: `^`n");
	output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nr.</td><td>Owner ID (Name)</td><td>Hausnr</td><td>Nr. (DB)</td><td>gebraucht?</td><td>Ops</td></tr>",true);
	$sql = "SELECT items.*,accounts.acctid, accounts.name FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE items.value1=$row[houseid] AND items.class='Schlüssel' ORDER BY items.value2 ASC,items.id ASC";
	$result = db_query($sql) or die(db_error(LINK));
	for ($i=1;$i<=db_num_rows($result);$i++){
		$item = db_fetch_assoc($result);
		output("<tr><td>`b$i`b</td><td>".($item['acctid']?"".$item['acctid']." (".$item['name'].")":"0 (`4Verloren`0)")."</td><td>".$item['value1']."</td><td>".$item['value2']."</td><td>".$item['hvalue']."</td><td>",true);
		if ($row2['name']==''){
			output("<a href='suhouses.php?op=keys&subop=change&hid=".$_REQUEST['id']."&id2=$i&owner=".$row['owner']."'>Reset</a> | ",true);
			allownav("suhouses.php?op=keys&subop=change&hid=".$_REQUEST['id']."&id2=$i&owner=".$row['owner']);
		}
		rawoutput("<a href='suhouses.php?op=keys&subop=edit&id=".$item['id']."&hid=".$_REQUEST['id']."'>Edit</a> | <a href='suhouses.php?op=keys&subop=delete&id=".$item['id']."&hid=".$_REQUEST['id']."' onClick=\"return confirm('Diesen Schlüssel wirklich löschen?');\">Löschen</a>");
		allownav("suhouses.php?op=keys&subop=edit&id=".$item['id']."&hid=".$_REQUEST['id']);
		allownav("suhouses.php?op=keys&subop=delete&id=".$item['id']."&hid=".$_REQUEST['id']);
		rawoutput('</td></tr>');
	}
	rawoutput('</table>');
	output('`n`n`n`@Gebaute Module: `^`n');
	rawoutput("<table border='0' cellpadding='3' cellspacing='0'><tr><td>eingebaut</td><td>interner Name</td><td>externer Name</td><td>Kategorie</td><td>immer inklusive</td><td>Ops</td></tr>");
	$sql = 'SELECT hm.moduleid, hm.linkcategory, hm.linktitle, hm.modulename, hm.built_in, hmd.value
				FROM housemodules hm
				LEFT JOIN housemoduledata hmd
				ON hmd.moduleid=hm.moduleid
				AND hmd.houseid="'.$_REQUEST['id'].'"
				AND hmd.name="#activated#"
				ORDER BY hm.linkorder ASC';
	$result = db_query($sql) or die(db_error(LINK));
	while ($row = db_fetch_assoc($result)) {
		if ($row['built_in']==1 || $row['value']==1) output('<tr><td>`2ja`0</td>',true);
		else output('<tr><td>`4nein`0</td>',true);
output('<td>'.$row['modulename'].'</td><td>'.$row['linktitle'].'</td><td>'.$row['linkcategory'].'</td><td>'.$row['built_in'].'</td>',true);
		if ($row['built_in']==0) {
			if ($row['value']==1) {
			output('<td><a href="suhouses.php?op=drin&subop=delmodule&mid='.$row['moduleid'].'&id='.$_REQUEST['id'].'">entfernen</a></td>',true);
			allownav('suhouses.php?op=drin&subop=delmodule&mid='.$row['moduleid'].'&id='.$_REQUEST['id']);
			}
			else {
				output('<td><a href="suhouses.php?op=drin&subop=addmodule&mid='.$row['moduleid'].'&id='.$_REQUEST['id'].'">hinzufügen</a></td>',true);
				allownav('suhouses.php?op=drin&subop=addmodule&mid='.$row['moduleid'].'&id='.$_REQUEST['id']);
			}
		}
		else rawoutput('<td>&nbsp;</td>');
		rawoutput('</tr>');
	}
	output('</table>',true);
}else if ($_GET['op']=="comment"){
	if ($_GET['subop']=="delete"){
		$sql = "DELETE FROM commentary WHERE commentid='".(int)$_GET['commentid']."'";
		db_query($sql);
	}
	viewcommentary("house-".$_GET['id'],"X",30);
	addnav("Zurück zu Haus ".$_GET['id'],"suhouses.php?op=drin&id=".$_GET['id']);
}else if ($_GET['op']=="info"){
	$sql="SELECT acctid,name,house,housekey FROM accounts WHERE house ORDER BY house ASC";
	output("<table cellpadding=2 align='center'><tr><td>`bacctid`b</td><td>`bName`b</td><td>`bhouse`b</td><td>`bhousekey`b</td></tr>",true);
	$result = db_query($sql);
	if (db_num_rows($result)==0){
		output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Häuser`i`0</td></tr>",true);
	}else{
		for ($i=0;$i<db_num_rows($result);$i++){
			$row = db_fetch_assoc($result);
			output("<tr><td align='center'>".$row['acctid']."</td><td>".$row['name']."</td><td>".$row['house']."</td><td>".$row['housekey']."</td></tr>",true);
		}
	}
	rawoutput('</table>');
	addnav("Hausmeister","suhouses.php");
}else if ($_GET['op']=="destroy"){ // bad idea! write this code on your own risk! .. ok, i wrote it
	if ($_GET['subop']=="confirmed"){
		// first, delete all modules of this house
		$sql = 'SELECT modulefile, modulename FROM housemoduledata LEFT JOIN housemodules USING(moduleid) WHERE value="#activated#"';
		$result = db_query($sql);
		while ($row = db_fetch_assoc($result)) {
			require_once('housemodules/'.$row['modulefile']);
			$function = 'module_destroy_'.$row['modulename'];
			$function($_GET['id']);
		}

		$sql="DELETE FROM houses WHERE houseid=".(int)$_GET['id'];
		db_query($sql);
		$sql="DELETE FROM items WHERE class='Schlüssel' AND value1=".(int)$_GET['id'];
		db_query($sql);
		$sql="UPDATE accounts SET house=0,housekey=0 WHERE house=".(int)$_GET['id'];
		db_query($sql);
		output("`@Haus gelöscht");
	}else{
		output("`b`\$Haus Nummer ".$_GET['id']." und alle Schlüssel wirklich löschen?`b");
		addnav("LÖSCHEN","suhouses.php?op=destroy&subop=confirmed&id=".(int)$_GET['id']);
	}
	addnav("Hausmeister","suhouses.php");
}
elseif ($_GET['op']=="newhouse") {
	addnav("Hausmeister","suhouses.php");
	if ($_GET['subop']=="save") { // save new house
		if ($_POST['auto']=="true") { // check given data
			$sql = "SELECT house,housekey FROM accounts WHERE acctid=".$_POST['owner'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			if ($row['house']>0 && !empty($_POST['owner'])) {
				output("`\$Fehler: Zielperson besitzt bereits ein anderes Haus oder existiert nicht.");
			}
			elseif (empty($_POST['housename'])) {
				output("`\$Fehler: Du musst einen Namen für das Haus eingeben.");
			}
			else {
				if ($_POST['status']=='sell' && (int)$_POST['owner']>0) {
					output("`^Warnung: Diesem Status darf kein Besitzer zugeordnet werden. Besitzer auf 0 gesetzt.`n");
					$_POST['owner'] = "0";
				}
				output("`@Neues Haus erstellt.`n");
				$sql = "INSERT INTO houses (owner,status,goldprice,gemprice,housename,description,locid) VALUES (".$_POST['owner'].",'".$_POST['status']."',".$_POST['goldprice'].",".$_POST['gemprice'].",'".$_POST['housename']."','".$_POST['description']."','".$_POST['locid']."')";
				db_query($sql);
				$sql = "SELECT houseid FROM houses WHERE owner=".$_POST['owner']." ORDER BY houseid DESC LIMIT 1";
				$result2 = db_query($sql);
				$row2 = db_fetch_assoc($result2);
				if ($_POST['status']=="ready") {
					for ($i=1;$i<getsetting('newhousekeys',10);$i++) {
						$sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('Hausschlüssel',".($_POST['owner']>0?"".$_POST['owner']."":"0").",'Schlüssel',".$row2['houseid'].",$i,'Schlüssel für Haus Nummer ".$row2['houseid']."')";
						db_query($sql);
					}
					output("`@Schlüssel in Datenbank eingetragen`n");
				}
				if ($_POST['status']!="sell"){
					$sql="UPDATE accounts SET house=".$row2['houseid'].",housekey=".($_POST['status']=="ready"?"".$row2['houseid']."":"0")." WHERE acctid=".$_POST['owner'];
					output("`@Userdatenbank angepasst`n");
					db_query($sql);
				}
			}
		}
		else {
			output("`@Neues Haus erstellt.");
			$sql = "INSERT INTO houses (owner,status,goldprice,gemprice,housename,description,locid) VALUES (".$_POST['owner'].",'".$_POST['status']."',".$_POST['goldprice'].",".$_POST['gemprice'].",'".$_POST['housename']."','".$_POST['description']."','".$_POST['locid']."')";
			db_query($sql);
		}
	}
	else {
		output("`@Neues Haus anlegen:`n`n`0");
		rawoutput("<form action=\"suhouses.php?op=newhouse&subop=save\" method='POST'> <table><tr><td>Name </td><td><input name='housename' maxlength='25'></td></tr> <tr><td>Goldpreis (im Bau) </td><td><input type='text' name='goldprice' value='0'> </td></tr><tr><td>Edelsteinpreis (im Bau) </td><td><input type='text' name='gemprice' value='0'></td></tr> <tr><td>Beschreibung </td><td><input type='text' name='description' maxlength='250'></td></tr> <tr><td>Standort </td><td><select name='locid'>");
		$sql = 'SELECT locid, locname FROM houseconfig ORDER BY locname ASC';
		$result = db_query($sql);
		while ($row = db_fetch_assoc($result)) output('<option value="'.$row['locid'].'">'.$row['locname'].'</option>',true);
		rawoutput('</select></td></tr>');
		output("<tr><td>Status </td><td><select name='status'><option value='build'>im Bau</option><option value='ready'>fertig</option><option value='sell'>zum Verkauf</option></select></td></tr> <tr><td>`4Besitzer (ID)`0 </td><td><input type='text' name='owner' value='0'> `4(VORSICHT!)`0</td></tr> <tr><td>`4Sicherer Modus`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(VORSICHT!)`0</td></tr></table>`n <input type='submit' class='button' value='Speichern'></form>",true);
		output('`0`n`nIm unsicheren Modus Haus auch im User-Editor beim Besitzer eintragen! Status berücksichtigen! Schlüsselverwaltung!');
		allownav("suhouses.php?op=newhouse&subop=save");
	}
}else if ($_GET['op']=="keys"){
	addnav("Hausmeister","suhouses.php");
	addnav("Zurück zu Haus ".$_GET['hid'],"suhouses.php?op=drin&id=".$_GET['hid']);
	if ($_GET['subop']=="change"){ // reset key owner
		$sql="UPDATE items SET owner=".(int)$_GET['owner']." WHERE value1=".(int)$_GET['hid']." AND class='Schlüssel' AND value2=".(int)$_GET['id2'];
		db_query($sql);
		output("`@Schlüssel `^".$_GET['id2']."`@ für Haus Nummer `^".$_GET['hid']."`@ zurückgesetzt.");
	}
	elseif ($_GET['subop']=="edit"){ // enter new values for key
		$sql = "SELECT * FROM items WHERE id=".(int)$_GET['id'];
		$result = db_query($sql);
		$item = db_fetch_assoc($result);
		output("`@Schlüssel Nr. ".$item['value2']." (item-ID ".$_GET['id'].") für Haus ".$_GET['hid']." bearbeiten:`n`n `0<form action=\"suhouses.php?op=keys&subop=edit2&id=".$_GET['id']."&hid=".$_GET['hid']."\" method='POST'> <table> <tr><td>Besitzer (owner: acctid) </td><td><input type='text' name='owner' value='".$item['owner']."'></td></tr> <tr><td>In Gebrauch? (hvalue: 0 oder Hausnr.) </td><td><input type='text' name='hvalue' value='".$item['hvalue']."'></td></tr> <tr><td>`4Schlüssel-ID (value2: Laufende Nr.)`0 </td><td><input type='text' name='value2' value='".$item['value2']."'> `4(VORSICHT!)`0</td></tr> </table>`n<input type='submit' class='button' value='Speichern'></form>",true);
		output('`0`n`nSchlüssel-ID darf nicht doppelt vergeben werden.`nSchlüssel ohne Besitzer werden als verloren behandelt.');
		allownav("suhouses.php?op=keys&subop=edit2&id=".$_GET['id']."&hid=".$_GET['hid']);
	}
	elseif ($_GET['subop']=="edit2"){ // save new values into DB
		$sql = "SELECT * FROM items WHERE id=".(int)$_GET['id'];
		$result = db_query($sql);
		$item = db_fetch_assoc($result);
		$action=false;
		if ((int)$_POST['value2']!=(int)$item['value2']){
			$sql = "SELECT id FROM items WHERE class='Schlüssel' AND value1=".(int)$_GET['hid']." AND value2=".$_POST['value2'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			if ($row['id']){
				output("`\$Fehler: Diese ID ist bereits vergeben.");
			}else{
				$action=true;
			}
		}
		if ((int)$item['owner']!=(int)$_POST['owner']){
			$action=false;
			$sql = "SELECT acctid FROM accounts WHERE acctid=".$_POST['owner'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			if (!$row['acctid']){
				output("`\$Fehler: Der User existiert nicht.");
			}else{
				$action=true;
			}
		}
		if ($action){
			$sql = "UPDATE items SET owner=".$_POST['owner'].",value2=".$_POST['value2'].",hvalue=".$_POST['hvalue']." WHERE id=".$_GET['id'];
			db_query($sql);
			output("`@Änderungen übernommen.");
		}
	}
	elseif ($_GET['subop']=="savenew"){ // save new key
		if ($_POST['value2']){
			$sql = "SELECT value1,value2 FROM items WHERE class='Schlüssel' AND value2=".$_POST['value2']." AND value1=".(int)$_GET['hid'];
			$result = db_query($sql);
			$item = db_fetch_assoc($result);
			$sql="SELECT COUNT(acctid) AS num FROM accounts WHERE acctid=".$_POST['owner'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
		}
		if (empty($_POST['value2'])){
			output("`\$Fehler: Du musst eine Schlüssel-ID angeben");
		}
		elseif ((int)$item['value2']==(int)$_POST['value2']){
			output("`\$Fehler: Diese ID ist bereits vergeben.");
		}
		elseif ($row['num']==0){
			output("`\$Fehler: Der User existiert nicht.");
		}
		else{
			$sql = "INSERT INTO items (name,owner,class,value1,value2,hvalue,description) VALUES ('Hausschlüssel',".$_POST['owner'].",'Schlüssel',".(int)$_GET['hid'].",".$_POST['value2'].",".$_POST['hvalue'].",'Schlüssel für Haus Nummer ".$_GET['hid']."')";
			db_query($sql);
			output("`@Schlüssel eingetragen.");
		}
	}
	elseif ($_GET['subop']=="delete"){ // delete key
		output("`@Schlüssel gelöscht.");
		$sql = "DELETE FROM items WHERE id=".(int)$_GET['id'];
		db_query($sql);
	}
	else{ // enter new key
		output("`@Neuen Schlüssel für Haus ".$_GET['hid']." anlegen:`n`n`0<form action=\"suhouses.php?op=keys&subop=savenew&hid=".$_GET['hid']."\" method='POST'> <table>",true);
		rawoutput("<tr><td>Besitzer (owner: acctid) </td><td><input type='text' name='owner' value='0'></td></tr> <tr><td>In Gebrauch? (hvalue: 0 oder Hausnr.) </td><td><input type='text' name='hvalue' value='0'></td></tr>");
		output("<tr><td>`4Schlüssel-ID (value2: Laufende Nr.)`0 </td><td><input type='text' name='value2'> `4(VORSICHT!)`0</td></tr> </table>`n",true);
		rawoutput("<input type='submit' class='button' value='Speichern'></form>");
		output('`0`n`nSchlüssel-ID darf nicht doppelt vergeben werden.`nSchlüssel ohne Besitzer werden als verloren behandelt.');
		allownav("suhouses.php?op=keys&subop=savenew&hid=".$_GET['hid']);
	}
}else if ($_GET['op']=="data"){
	addnav("Hausmeister","suhouses.php");
	addnav("Zurück zu Haus ".$_GET['id'],"suhouses.php?op=drin&id=".$_GET['id']);
	if ($_GET['subop']=="save"){ // save values
		$action=false;
		if ($_POST['auto']=="true"){ // check given data
			$sql = "SELECT * FROM houses WHERE houseid=".(int)$_GET['id'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);
			$sql = "SELECT house,housekey FROM accounts WHERE acctid=".$_POST['owner'];
			$result2 = db_query($sql);
			$row2 = db_fetch_assoc($result2);
			if ($row2['house']!=$_GET['id'] && $row2['house']>0){
				output("`\$Fehler: Zielperson besitzt bereits ein anderes Haus oder existiert nicht. Datenbank nicht aktualisiert.");
			}
			elseif ($row['status']!=$_POST['status'] && $row['owner']!=$_POST['owner']){
				output("`\$Fehler: Status und Besitzer können im sicheren Modus nicht gleichzeitig geändert werden. Datenbank nicht aktualisiert.");
			}
			else{
				if ($row['owner']!=$_POST['owner'] && $_POST['status']=='sell'){
					$_POST['status']="build";
					output("`^Warnung: Status dieses Hauses lässt keinen Besitzer zu. Status auf 'im Bau' gesetzt.`n");
				}
				elseif ($row['status']!=$_POST['status'] && $_POST['status']=='sell' && (int)$_POST['owner']>0){
					$_POST['owner']="0";
					output("`^Warnung: Dieser Statuswechsel lässt keinen Besitzer zu. Besitzer auf 0 gesetzt.`n");
				}
				$action=true;
				if ($_POST['status']!=$row['status']){
					if ($_POST['status']=="build" || $_POST['status']=="sell"){
						$sql="DELETE FROM items WHERE class='Schlüssel' AND value1=".(int)$_GET['id'];
						db_query($sql);
						if ($_POST['status']=="build") $house=(int)$_GET['id'];
						else $house=0;
						$housekey=0;
						output("`@Schlüssel aus Datenbank gelöscht`n");
					}
					elseif ($_POST['status']=="ready"){
						$sql="DELETE FROM items WHERE class='Schlüssel' AND value1=".$_GET['id'];
						db_query($sql);
						for ($i=1;$i<getsetting('newhousekeys',10);$i++){
							$sql = "INSERT INTO items (name,owner,class,value1,value2,description) VALUES ('Hausschlüssel',".$_POST['owner'].",'Schlüssel',".(int)$_GET['id'].",$i,'Schlüssel für Haus Nummer ".(int)$_GET['id']."')";
							db_query($sql);
						}
						$house=(int)$_GET['id'];
						$housekey=(int)$_GET['id'];
						output("`@Schlüssel in Datenbank eingetragen`n");
					}

					$sql="UPDATE accounts SET house=$house,housekey=$housekey WHERE acctid='".$row['owner']."'";
					db_query($sql);
				}
				else{
					$sql="UPDATE accounts SET house=0,housekey=0 WHERE acctid=".$row['owner'];
					db_query($sql);
					if ($_POST['status']=="ready"){
						$housekey = (int)$_GET['id'];
						$house = (int)$_GET['id'];
					}else{
						$housekey = 0;
						if ($_POST['status']=='sell') $house = 0;
						else $house = (int)$_GET['id'];
					}
					$sql="UPDATE accounts SET house='$house',housekey='$housekey' WHERE acctid='".$_POST['owner']."'";
					db_query($sql);
					if ($_POST['status']=='ready') {
						$sql="UPDATE items SET owner=".$_POST['owner']." WHERE class='Schlüssel' AND owner=".$row['owner']." AND value1=".(int)$_GET['id'];
						db_query($sql);
					}
				}
			}
		}else{
			$action=true;
		}
		if ($action){
			output("`@Daten gespeichert.");
			$sql="UPDATE houses SET owner=".$_POST['owner'].",housename='".rawurldecode($_POST['housename'])."',goldprice=".$_POST['goldprice'].",gemprice=".$_POST['gemprice'].",status='".$_POST['status']."',description='".rawurldecode($_POST['description'])."',locid='".$_POST['locid']."' WHERE houseid='".(int)$_GET['id']."'";
			db_query($sql);
		}
	}else{
		$sql = "SELECT * FROM houses WHERE houseid=".$_GET['id'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		output("`@Daten für Haus `b".$_GET['id']."`b ändern:`n`n`0");
		rawoutput("<form action=\"suhouses.php?op=data&subop=save&id=".$_GET['id']."\" method='POST'><table><tr><td>Name </td><td><input name='housename' maxlength='25' value='".$row['housename']."'></td></tr> <tr><td>Goldpreis (wenn im Bau) </td><td><input type='text' name='goldprice' value='".$row['goldprice']."'> </td></tr> <tr><td>Edelsteinpreis (wenn im Bau) </td><td><input type='text' name='gemprice' value='".$row['gemprice']."'></td></tr> <tr><td>Beschreibung </td><td><input type='text' name='description' maxlength='250' value='".$row['description']."'></td></tr> <tr><td>Standort </td><td><select name='locid'>",true);
		$sql = 'SELECT locid, locname FROM houseconfig ORDER BY locname ASC';
		$result = db_query($sql);
		while ($row2 = db_fetch_assoc($result)) output('<option value="'.$row2['locid'].'" '.($row['locid']==$row2['locid']?'selected="selected"':'').'>'.$row2['locname'].'</option>',true);
		rawoutput('</select></td></tr>');
		rawoutput("<tr><td>Status </td><td><select name='status'>
					<option value='build' ".($row['status']=='build'?'selected="selected"':'').">im Bau</option>
					<option value='ready' ".($row['status']=='ready'?'selected="selected"':'').">fertig</option>
					<option value='sell' ".($row['status']=='sell'?'selected="selected"':'').">zum Verkauf</option></select></td></tr>");
		output("<tr><td>`4Besitzer (ID)`0 </td><td><input type='text' name='owner' value='".$row['owner']."'> `4(VORSICHT!)`0</td></tr> <tr><td>`4Sicherer Modus`0 </td><td><input type='checkbox' name='auto' checked='true' value='true'> `4(VORSICHT!)`0</td></tr></table>`n <input type='submit' class='button' value='Speichern'></form>",true);
		output('`0`n`nDaten, die nicht geändert werden sollen, `bnicht`b verändern!`nStatusänderung kann Auswirkungen auf die Schlüsselverwaltung haben!`nBesitzer- und Statusänderungen müssen im unsicheren Modus manuell übertragen werden!`n');
		allownav("suhouses.php?op=data&subop=save&id=".$_GET['id']);
	}
} elseif ($_GET['op']=='location') {
	addnav("Hausmeister","suhouses.php");
	if ($_GET['subop']=='new') {
		addnav('Zu den Wohngebieten','suhouses.php?op=location');
		// Neues Wohngebiet eintragen
		if (!empty($_POST['location'])) {
			$sql = "INSERT INTO houseconfig (location,locname,buy,sell,build,rob,defaultgoldprice,defaultgemprice,buildprice_increase) VALUES ('".$_POST['location']."','".$_POST['locname']."','".$_POST['buy']."','".$_POST['sell']."','".$_POST['build']."','".$_POST['rob']."','".$_POST['defaultgoldprice']."','".$_POST['defaultgemprice']."','".$_POST['buildprice_increase']."')";
			db_query($sql);
			output('Das Wohngebiet wurde erstellt. die ID (für Links) ist '.db_insert_id(LINK));
		}
		// Erstellformular
		else {
			output('`c`bWohngebiet erstellen`b`c`n`n<form action="suhouses.php?op=location&subop=new" method="POST">',true);
			
			output('<table><tr><td>Standort (Script):</td><td><input type="text" name="location" size="50" maxlength="255"></td></tr>
			<tr><td>Name:</td><td><input type="text" name="locname" size="50" maxlength="50"></td></tr> 
			<tr><td>Hauskauf ermöglichen:</td>
							<td><select name="buy" size="1"><option value="1">ja</option>
							<option value="0">nein</option></select></td></tr>
						<tr><td>Hausverkauf ermöglichen:</td>
							<td><select name="sell" size="1"><option value="1">ja</option>
							<option value="0">nein</option></select></td></tr>
						<tr><td>Hausbau ermöglichen:</td>
							<td><select name="build" size="1"><option value="1">ja</option>
							<option value="0">nein</option></select></td></tr>
						<tr><td>Einbruch ermöglichen:</td>
							<td><select name="rob" size="1"><option value="1">ja</option>
							<option value="0">nein</option></select></td></tr>
						<tr><td>Standard-Hauspreis Gold:</td>
							<td><input type="text" name="defaultgoldprice" size="6" value="30000"></td></tr>
						<tr><td>Standard-Hauspreis Edelsteine:</td>
							<td><input type="text" name="defaultgemprice" size="6" value="50"></td></tr>
						<tr><td>Teuerungsfaktor pro leerstehendem Haus:</td>
							<td><input type="text" name="buildprice_increase" size="3" value="0">%</td></tr>
							</table><input type="Submit" value="Erstellen"></form>',true);
			allownav('suhouses.php?op=location&subop=new');
		}
	}
	elseif ($_GET['subop']=='edit') {
		addnav('Zu den Wohngebieten','suhouses.php?op=location');
		// Änderung übernehmen
		if (!empty($_POST['location'])) {
			if ($_POST['delete']==1) $sql = "DELETE FROM houseconfig WHERE locid='".(int)$_GET['locid']."'";
			else $sql = "UPDATE houseconfig SET location='".$_POST['location']."',locname='".$_POST['locname']."',buy='".$_POST['buy']."',sell='".$_POST['sell']."',build='".$_POST['build']."',rob='".$_POST['rob']."',defaultgoldprice='".$_POST['defaultgoldprice']."',defaultgemprice='".$_POST['defaultgemprice']."',buildprice_increase='".$_POST['buildprice_increase']."' WHERE locid='".$_GET['locid']."'";
			db_query($sql);
			output('Änderung übernommen!');
		}
		// Änderungsformular
		else {
			$sql = 'SELECT * FROM houseconfig WHERE locid="'.$_GET['locid'].'"';
			$row = db_fetch_assoc(db_query($sql));
			output('`c`bWohngebiet bearbeiten`b`c`n`n
			<form action="suhouses.php?op=location&subop=edit&locid='.$_GET['locid'].'" method="POST">
			<table><tr><td>ID (für Links):</td><td>'.$_GET['locid'].'</td></tr><tr><td>Standort:</td><td><input type="text" name="location" size="50" maxlength="255" value="'.$row['location'].'"></td></tr><tr><td>Name:</td><td><input type="text" name="locname" size="50" maxlength="50" value="'.$row['locname'].'"></td></tr><tr><td>Hauskauf ermöglichen:</td>
							<td><select name="buy" size="1"><option value="1">ja</option>
						<option value="0" '.($row['buy']==1?'':'selected').'>nein</option></select></td></tr>
						<tr><td>Hausverkauf ermöglichen:</td>
							<td><select name="sell" size="1"><option value="1">ja</option>
							<option value="0" '.($row['sell']==1?'':'selected').'>nein</option></select></td></tr>
						<tr><td>Hausbau ermöglichen:</td>
							<td><select name="build" size="1"><option value="1">ja</option>
							<option value="0" '.($row['build']==1?'':'selected').'>nein</option></select></td></tr>
						<tr><td>Einbruch ermöglichen:</td>
							<td><select name="rob" size="1"><option value="1">ja</option>
							<option value="0" '.($row['rob']==1?'':'selected').'>nein</option></select></td></tr>
						<tr><td>Standard-Hauspreis Gold:</td>
							<td><input type="text" name="defaultgoldprice" size="6" value="'.$row['defaultgoldprice'].'"></td></tr>
						<tr><td>Standard-Hauspreis Edelsteine:</td>
							<td><input type="text" name="defaultgemprice" size="6" value="'.$row['defaultgemprice'].'"></td></tr>
						<tr><td>Teuerungsfaktor pro leerstehendem Haus:</td>
							<td><input type="text" name="buildprice_increase" size="3" value="'.$row['buildprice_increase'].'">%</td></tr>
						<tr><td>`4Löschen:`0</td>
							<td><input type="checkbox" name="delete" value="1"> `4(VORSICHT!)`0</td></tr>
							</table><input type="Submit" value="Ändern"></form>',true);
						allownav('suhouses.php?op=location&subop=edit&locid='.$_GET['locid']);

		}
	}
	else {
		$sql = 'SELECT locid,locname FROM houseconfig ORDER BY locname ASC';
		$result = db_query($sql);
		output('`c`bWohngebiete`b`c`n`n');
		output('`c<table><tr><td>ID</td><td>Wohngebiet</td></tr>',true);
		while ($row = db_fetch_assoc($result)) {
			output('<tr><td>'.$row['locid'].'</td><td><a href="suhouses.php?op=location&subop=edit&locid='.$row['locid'].'">'.$row['locname'].'</a></td></tr>',true);
			allownav('suhouses.php?op=location&subop=edit&locid='.$row['locid']);
		}
		output('</table>`c',true);
		addnav('Neues Wohngebiet','suhouses.php?op=location&subop=new');
	}
}
elseif ($_GET['op']=='modules') {
	addnav('Zurück zum Hausmeister','suhouses.php');
	output('`@`b`cHausmodule`c`b`0`n`n');
	if (empty($_GET['subop']) || $_GET['subop']!='create') {
		if (file_exists('./housemodules/_default_template.tpl') && (fileperms('./housemodules') & 0x0002)) {
			addnav('Modul erstellen / bearbeiten','suhouses.php?op=modules&subop=create');
		}
		
		// get all module files
		$dir = dir('./housemodules');
		while ($file = $dir->read()) {
			if (substr_c($file,4)=='.php') {
				include('./housemodules/'.$file);
			}
			elseif (substr_c($file,4)=='.tmp') {
				unlink('./housemodules/'.$file);
			}
		}
		$dir->close();
		// do action now
		if (!empty($_GET['subop'])) {
			if ($_GET['subop']=='save') {
				$sql = 'SELECT linkorder FROM housemodules WHERE moduleid="'.(int)$_GET['mid'].'"';
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				if ($row['linkorder']>$_POST['linkorder']) {
					$sql = 'UPDATE  housemodules SET linkorder=linkorder+1 WHERE linkorder >= "'.$_POST['linkorder'].'" AND linkorder < '.$row['linkorder'];
					db_query($sql);
				}
				else {
					$sql = 'UPDATE  housemodules SET linkorder=linkorder-1 WHERE linkorder <= "'.$_POST['linkorder'].'" AND linkorder > '.$row['linkorder'];
					db_query($sql);
				}
				$sql = 'UPDATE housemodules SET linkorder="'.$_POST['linkorder'].'" WHERE moduleid="'.(int)$_GET['mid'].'"';
				db_query($sql);
			}
			elseif ($_GET['subop']=='uninstall') {
				$sql = 'SELECT linkorder FROM housemodules WHERE modulename="'.$_GET['name'].'"';
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				$sql = 'UPDATE  housemodules SET linkorder=linkorder-1 WHERE linkorder > '.$row['linkorder'];
				db_query($sql);
				$fname = 'module_uninstall_'.$_GET['name'];
				$fname();
			}
			elseif ($_GET['subop']=='install') {
				$fname = 'module_install_'.$_GET['name'];
				$fname();
				$moduleid = getmoduleid($_GET['name']);
				$sql = 'SELECT MAX(linkorder) AS maximum FROM housemodules';
				$result = db_query($sql);
				$row = db_fetch_assoc($result);
				$sql = 'UPDATE housemodules SET linkorder='.($row['maximum']1).' WHERE moduleid='.$moduleid;
				db_query($sql);
			}
		}
		// check for module functions
		$function_array = get_defined_functions();
		$modules = array();
		foreach ($function_array['user'] AS $thisfunction) {
			if (substr_c($thisfunction,0,15)=='module_getinfo_') $modules[] = $thisfunction;
		}
		// unset large array to free memory
		unset($function_array);
		// get all installed modules
		$installed = array();
		$sql = 'SELECT * FROM housemodules ORDER BY linkorder ASC';
		$result = db_query($sql);
		while ($row = db_fetch_assoc($result)) {
			$installed[$row['modulename']] = $row;
		}
		// show installed modules first
		foreach ($installed AS $thismod) {
			if (file_exists('./housemodules/'.$thismod['modulefile'])) {
				$status = '`2installiert`0';
			}
			else $status = '`qFehler: installiert, aber Datei fehlt!`0';
			output('<form action="suhouses.php?op=modules&subop=save&mid='.$thismod['moduleid'].'" method="post">',true);
			allownav('suhouses.php?op=modules&subop=save&mid='.$thismod['moduleid']);
			$str = '<div style="float:right;"><a href="suhouses.php?op=modules&subop=create&module='.$thismod['modulename'].'">bearbeiten</a></div>';
			allownav('suhouses.php?op=modules&subop=create&module='.$thismod['modulename']);
			output('<table border="0"><tr class="trhead"><td colspan="2">'.$str.$thismod['modulename'].'</td></tr>
						<tr class="trlight"><td>Datei:</td><td>'.$thismod['modulefile'].'</td></tr>
						<tr class="trdark"><td>Autor:</td><td>'.$thismod['moduleauthor'].'</td></tr>
						<tr class="trlight"><td>Version:</td><td>'.$thismod['moduleversion'].'</td></tr>
						<tr class="trdark"><td>Immer eingebaut:</td><td>'.$thismod['built_in'].'</td></tr>
						<tr class="trlight"><td>Linkkategorie:</td><td>'.$thismod['linkcategory'].'</td></tr>
						<tr class="trdark"><td>Linktitel:</td><td>'.$thismod['linktitle'].'</td></tr>
						<tr class="trlight"><td>Anzeige:</td><td>'.$thismod['showto'].'</td></tr>
						<tr class="trdark"><td>Sortierung:</td><td><input type="text" class="input" name="linkorder" value="'.$thismod['linkorder'].'"><input type="submit" class="button" name="save" value="Speichern"></td></tr>
						<tr class="trlight"><td>Status:</td><td>'.$status.' [<a href="suhouses.php?op=modules&subop=uninstall&name='.$thismod['modulename'].'">deinstallieren</a>]</td></tr>
					</table>',true);
			allownav('suhouses.php?op=modules&subop=uninstall&name='.$thismod['modulename']);
			output('</form>',true);
		}
		// show other modules now
		sort($modules);
		foreach ($modules AS $thismodule) {
			$modinfo = $thismodule();
			if (isset($installed[$modinfo['modulename']])) continue;
			$str = '<div style="float:right;"><a href="suhouses.php?op=modules&subop=create&module='.$modinfo['modulename'].'">bearbeiten</a></div>';
			allownav('suhouses.php?op=modules&subop=create&module='.$modinfo['modulename']);
			output('<table border="0"><tr class="trhead"><td colspan="2">'.$str.$modinfo['modulename'].'</td></tr>
						<tr class="trlight"><td>Datei:</td><td>'.$modinfo['modulefile'].'</td></tr>
						<tr class="trdark"><td>Autor:</td><td>'.$modinfo['moduleauthor'].'</td></tr>
						<tr class="trlight"><td>Version:</td><td>'.$modinfo['moduleversion'].'</td></tr>
						<tr class="trdark"><td>Immer eingebaut:</td><td>'.$modinfo['built_in'].'</td></tr>
						<tr class="trlight"><td>Linkkategorie:</td><td>'.$modinfo['linkcategory'].'</td></tr>
						<tr class="trdark"><td>Linktitel:</td><td>'.$modinfo['linktitle'].'</td></tr>
						<tr class="trlight"><td>Anzeige:</td><td>'.$modinfo['showto'].'</td></tr>
						<tr class="trdark"><td>Status:</td><td>`4nicht installiert`0 [<a href="suhouses.php?op=modules&subop=install&name='.$modinfo['modulename'].'">installieren</a>]</td></tr>
					</table><br /><br />',true);
			allownav('suhouses.php?op=modules&subop=install&name='.$modinfo['modulename']);
		}
	}
	else {
		addnav("Fertige Module","suhouses.php?op=modules");
		$step = isset($_GET['step']) ? $_GET['step'] : 0;
		switch ($step) {
			case 0:
				if (!isset($_GET['module'])) {
					// show first data
					output('<form action="suhouses.php?op=modules&subop=create&step=1" method="post">',true);
					allownav('suhouses.php?op=modules&subop=create&step=1');
					output('<table border="0"><tr class="trhead"><td colspan="2">Grunddaten</td></tr>
								<tr class="trlight"><td>Interner Name (nur Buchstaben, Zahlen, _):</td><td><input type="text" name="int_name"></td></tr>
								<tr class="trdark"><td>Autor:</td><td><input type="text" name="author"></td></tr>
								<tr class="trlight"><td>Version:</td><td><input type="text" name="version"></td></tr>
								<tr class="trdark"><td>Immer eingebaut:</td><td><select name="built_in"><option value="1">ja</option><option value="0">nein</option></select></td></tr>
								<tr class="trlight"><td>Linkkategorie:</td><td><input type="text" name="linkcategory"></td></tr>
								<tr class="trdark"><td>Linktitel:</td><td><input type="text" name="linktitle"></td></tr>
								<tr class="trlight"><td>Anzeige für:</td><td><select name="showto"><option value="owner,guest">Besitzer und G&auml;ste</option><option value="owner">Besitzer</option><option value="guest">G&auml;ste</option></select></td></tr>
								<tr class="trhead"><td colspan="2" align="right"><input type="submit" class="submit" value="Weiter"></td></tr>
							</table></form>',true);
					break;
				}
				else {
					if (!isset($_POST)) $_POST = array();
					include('./housemodules/'.$_GET['module'].'.php');
					$fname   = 'module_getinfo_'.$_GET['module'];
					$modinfo = $fname();
					if (!is_array($modinfo)) redirect('suhouses.php?op=modules&subop=create');
					$_POST['int_name']     = $modinfo['modulename'];
					$_POST['author']       = $modinfo['moduleauthor'];
					$_POST['version']      = $modinfo['moduleversion'];
					$_POST['built_in']     = $modinfo['built_in'];
					$_POST['linkcategory'] = $modinfo['linkcategory'];
					$_POST['linktitle']    = $modinfo['linktitle'];
					$_POST['showto']       = $modinfo['showto'];
				}
			case 1:
				// show editable source
				$find     = array('{modulename}','{author}','{version}','{built_in}','{category}','{linktitle}','{showto}');
				$replace  = array($_POST['int_name'],$_POST['author'],$_POST['version'],$_POST['built_in'],$_POST['linkcategory'],$_POST['linktitle'],$_POST['showto']);
				$tpl      = file_get_contents('./housemodules/_default_template.tpl');
				$tpl      = str_replace_c($find, $replace, $tpl);
				$tpl      = highlight_string($tpl,true);
				
				// Wenn das Modul schon existiert: Editieren!
				if (file_exists('./housemodules/'.$_POST['int_name'].'.tmp')) {
					$content = file_get_contents('./housemodules/'.$_POST['int_name'].'.tmp');
					preg_match_all('/\/\* (\w+) begin \*\/\s*(.*)\s*\/\* \w+ end \*\//Us',$content,$matches); 
					$values  = array();
					foreach ($matches[1] as $key=>$val) {
						$values[$val] = $matches[2][$key];
					}
				}
				elseif (file_exists('./housemodules/'.$_POST['int_name'].'.php')) {
					$content = file_get_contents('./housemodules/'.$_POST['int_name'].'.php');
					preg_match_all('/\/\* (\w+) begin \*\/\s*(.*)\s*\/\* \w+ end \*\//Us',$content,$matches); 
					$values  = array();
					foreach ($matches[1] as $key=>$val) {
						$values[$val] = $matches[2][$key];
					}
				}
				$find     = array('"{install_moduledata}"','"{install_other}"','"{delete_other}"','"{build_other}"','"{destroy_other}"');
				$raw      = '<textarea name="{name}" cols="100" rows="20" style="white-space:nowrap;">{value}</textarea>';
				$replace  = array();
				foreach ($find as $part) {
					if (isset($values) && isset($values[substr_c($part,2,2)])) {
						$v = htmlspecialchars($values[substr_c($part,2,2)]);
					}
					else $v = '';
					$replace[] = str_replace_c(array('{name}','{value}'),array(substr_c($part,2,2),$v),$raw);
				}
				$tpl      = str_replace_c($find, $replace, $tpl);
				$find     = '"{content_show}"';
				if (isset($values) && isset($values['content_show'])) {
					$v = htmlspecialchars($values['content_show']);
				}
				else $v = <<< PHP



// uncomment these lines if you want to show the default navs even if this is not the default module
// global \$shownavs;
// \$shownavs = true;

// uncomment these lines if you want to hide the default navs even if this is the default module
// global \$shownavs;
// \$shownavs = false;
PHP;
				$v = '<textarea name="content_show" cols="100" rows="20" style="white-space:nowrap;">'.$v.'</textarea>';
				$tpl      = str_replace_c($find, $v, $tpl);
				output('<form action="suhouses.php?op=modules&subop=create&step=2" method="post">',true);
				allownav('suhouses.php?op=modules&subop=create&step=2');
				output('<input type="hidden" name="int_name" value="'.$_POST['int_name'].'"
				<input type="hidden" name="author" value="'.$_POST['author'].'"
				<input type="hidden" name="version" value="'.$_POST['version'].'"
				<input type="hidden" name="built_in" value="'.$_POST['built_in'].'"
				<input type="hidden" name="linkcategory" value="'.$_POST['linkcategory'].'"
				<input type="hidden" name="linktitle" value="'.$_POST['linktitle'].'"
				<input type="hidden" name="showto" value="'.$_POST['showto'].'"
				<div style="background-color:#FFF;color:#000;">',true);
				output($tpl,true);
				output('</div><input type="submit" class="submit" style="float:right;" value="Fertigstellen"></form>',true);
				allownav('suhouses.php?op=modules&subop=create&step=1');
				break;
			case 2:
				ini_set('error_prepend_string',
				        '<html>
				        		<head></head>
				        		<body>
				        			<form action="suhouses.php?op=modules&subop=create&step=1" method="post">
				        			<input type="hidden" name="int_name" value="'.$_POST['int_name'].'">
				        			<input type="hidden" name="author" value="'.$_POST['author'].'">
				        			<input type="hidden" name="version" value="'.$_POST['version'].'">
				        			<input type="hidden" name="built_in" value="'.$_POST['built_in'].'">
				        			<input type="hidden" name="linkcategory" value="'.$_POST['linkcategory'].'">
				        			<input type="hidden" name="linktitle" value="'.$_POST['linktitle'].'">
				        			<input type="hidden" name="showto" value="'.$_POST['showto'].'">
				        			Leider ist das Script fehlerhaft:<br />');
				ini_set('error_append_string','<br /><input type="submit" value="Fehler beheben"></form></body></html>');
				// create module script
				$find     = array('{modulename}','{author}','{version}','{built_in}','{category}','{linktitle}','{showto}');
				$replace  = array($_POST['int_name'],$_POST['author'],$_POST['version'],$_POST['built_in'],$_POST['linkcategory'],$_POST['linktitle'],$_POST['showto']);
				$tpl      = file_get_contents('./housemodules/_default_template.tpl');
				$tpl      = str_replace_c($find, $replace, $tpl);
				$find     = array('"{install_moduledata}"','"{install_other}"','"{delete_other}"','"{build_other}"','"{destroy_other}"','"{content_show}"');
				$replace  = array();
				foreach ($find as $part) {
					$cleanpart = substr_c($part,2,2);
					$replace[] = "/* $cleanpart begin */\n".stripslashes($_POST[$cleanpart])."\n/* $cleanpart end */";
				}
				$tpl      = str_replace_c($find, $replace, $tpl);
				$fp       = fopen('./housemodules/'.$_POST['int_name'].'.tmp','w+');
				fwrite($fp, $tpl);
				fclose($fp);
				chmod('./housemodules/'.$_POST['int_name'].'.tmp',0777);
				include('./housemodules/'.$_POST['int_name'].'.tmp');
				unlink('./housemodules/'.$_POST['int_name'].'.php');
				rename('./housemodules/'.$_POST['int_name'].'.tmp', './housemodules/'.$_POST['int_name'].'.php');
				output('`b`2Das Modul wurde gespeichert!`0`b`n`n');
				addnav('Modul erstellen / bearbeiten','suhouses.php?op=modules&subop=create');
				break;
		}
	}
}
else {
	output("`@`b`cDas Wohnviertel`c`b`n`n");
	output('<form action="suhouses.php" method="post">',true);
	allownav('suhouses.php');
	output('Häuser filtern: ');
	output('<select name="status" size="1">
				<option value="">--- alle ---</option>
				<option value="0" '.($_REQUEST['status']==='0'?'selected="selected"':'').'>im Bau</option>
				<option value="1" '.($_REQUEST['status']==1?'selected="selected"':'').'>Bauruine</option>
				<option value="2" '.($_REQUEST['status']==2?'selected="selected"':'').'>bewohnt</option>
				<option value="3" '.($_REQUEST['status']==3?'selected="selected"':'').'>verlassen</option>
				<option value="4" '.($_REQUEST['status']==4?'selected="selected"':'').'>zum Verkauf</option>
				</select> <input type="submit" value="anzeigen"></form>',true);
	output('<form action="suhouses.php?op=drin" method="post">',true);
	allownav('suhouses.php?op=drin');
	output('Springe direkt zu Haus Nr. ');
	output('<input type="text" name="id" size="4">',true);
	output('<input type="submit" value="anzeigen"></form>',true);
	output("Wähle das Haus:`n`n");
	output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bStatus`b</td></tr>",true);
	$ppp=25; // Player Per Page +1 to display
	if (!$_GET['limit']){
		$page=0;
	}else{
		$page=(int)$_GET['limit'];
		addnav("Vorherige Straße","suhouses.php?limit=".($page1).($_REQUEST['status']!=''?"&status=$_REQUEST[status]":""));
	}
	$limit="".($page$ppp).",".($ppp1);
	if ($_REQUEST['status']!='') {
		switch ($_REQUEST['status']) {
			case '0': $search = ' AND status="build" AND owner > 0'; break;
			case 1: $search = ' AND status="build" AND owner=0'; break;
			case 2: $search = ' AND status="ready" AND owner > 0'; break;
			case 3: $search = ' AND status="ready" AND owner=0'; break;
			case 4: $search = ' AND status="sell"'; break;
			default: $search = '';
		}
	}
	else $search = '';
	$sql = "SELECT houseid,housename,status FROM houses WHERE 1$search ORDER BY houseid ASC LIMIT $limit";
	$result = db_query($sql);
	if (db_num_rows($result)>$ppp) addnav("Nächste Seite","suhouses.php?limit=".($page1).($_REQUEST['status']!=''?"&status=".$_REQUEST['status']."":""));
	if (db_num_rows($result)==0){
		output("<tr><td colspan=3 align='center'>`&`iEs gibt keine Häuser`i`0</td></tr>",true);
	}else{
		for ($i=0;$i<db_num_rows($result);$i++){
			$row2 = db_fetch_assoc($result);
			output("<tr><td align='center'>".$row2['houseid']."</td><td><a href='suhouses.php?op=drin&id=".$row2['houseid']."'>".$row2['housename']."</a></td><td>".$row2['status']."</td></tr>",true);
			allownav("suhouses.php?op=drin&id=".$row2['houseid']);
		}
	}
	rawoutput('</table>');
	addnav("User mit Haus","suhouses.php?op=info");
	addnav("Neues Haus","suhouses.php?op=newhouse");
	addnav("Wohnorte","suhouses.php?op=location");
	addnav("Module","suhouses.php?op=modules");
}

output("`n<div align='right'>`)2004 by anpera & 2005 Chaosmaker</div>",true);
$session['user']['standort'] = "Geheime Grotte";
page_footer();
?>