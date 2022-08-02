<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'treasury' with your module's (internal) name.
function module_getinfo_treasury() {
	$info = array(
					'modulename'=>'treasury', 		// internal name; use letters, numbers and underscores only!
					'modulefile'=>basename(__FILE__), 	// filename of the module; if you allow to rename the script, do NOT change this!
					'moduleauthor'=>'Chaosmaker', 			// the author's name
					'moduleversion'=>'1.0',			// the module's version number
					'built_in'=>'1',				// '1', if each house should have this module built-in; otherwise '0'
					'linkcategory'=>'Weitere Räume',			// the category (in houses.php's menu) under which the link to this module should be shown
					'linktitle'=>'Schatzkammer',			// the link title of the module
					'showto'=>'owner,guest'						// who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
	);
	return $info;
}

function module_install_treasury() {
	// insert data into module table - do NOT change this (well... just change the function name ;))!
	$info = module_getinfo_treasury();
	$sql = "INSERT INTO housemodules
				(modulefile, modulename, moduleversion, moduleauthor, built_in, linkcategory, linktitle,showto)
				VALUES ('{$info['modulefile']}', '{$info['modulename']}', '{$info['moduleversion']}', '{$info['moduleauthor']}', '{$info['built_in']}', '{$info['linkcategory']}', '{$info['linktitle']}', '{$info['showto']}')";
	db_query($sql);
	$moduleid = db_insert_id(LINK);

	// insert global module data (you can add several entries - but do NOT
	// change anything else than "FieldName" and "FieldValue"!)
	/*
	$sql = 'INSERT INTO housemoduledata (moduleid, name, houseid, value)
				VALUES ('.$moduleid.',"FieldName",0,"FieldValue")';
	db_query($sql);
	*/
	/* install_moduledata begin */

/* install_moduledata end */

	// here you can change everything else needed (e.g. adding settings)
	// be careful: these changes must be global; per-house-changes will be done
	// in module_build()!
	/* install_other begin */

/* install_other end */
}

function module_uninstall_treasury() {
	// uninstalling the module
	// this function should also contain all module_destroy contents

	// getting moduleid - do NOT change this (same as above... the function name should be changed)!
	$info = module_getinfo_treasury();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module from db - do NOT change this!
	$sql = 'DELETE FROM housemodules WHERE moduleid='.$moduleid;
	db_query($sql);

	// deleting internal module data - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid;
	db_query($sql);

	// here you should delete all other added things (e.g. settings) of this module
	/* delete_other begin */

/* delete_other end */
}

function module_build_treasury($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... blablabla)!
	$info = module_getinfo_treasury();
	$moduleid = getmoduleid($info['modulename']);

	// setting flag for house - do NOT change this!
	$sql = 'INSERT INTO housemoduledata (moduleid, name, houseid, value)
				VALUES ('.$moduleid.',"#activated#",'.$houseid.',"1")';
	db_query($sql);

	// here you can change everything else needed (e.g. changing user settings)
	// be careful: these changes must be for this house only; global changes will be done
	// in module_install()!
	/* build_other begin */

/* build_other end */
}

function module_destroy_treasury($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... moooooooooh!)!
	$info = module_getinfo_treasury();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module data of this house - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
	db_query($sql);

	// here you should delete all other added things (e.g. user settings) of this module and house
	/* destroy_other begin */

/* destroy_other end */
}

function module_show_treasury() {
	// this is the main part of the module where all output is done ;)
	// don't forget the navs; only the default module does not need them (but may add some)
	// to return to the main module, use this link: houses.php?op=drin&module (without id!)
	// don't forget 'global $session;' if you need the player's data (and you WILL need them!)
   
   /* content_show begin */
	global $session;

	if ($_GET['act']=="takekey") {
		if (empty($_POST['ziel'])) {
			$sql = "SELECT items.owner, accounts.name FROM items LEFT JOIN accounts ON items.owner=accounts.acctid WHERE items.value1={$session['user']['specialmisc']['houseid']} AND items.class='Schlüssel' AND accounts.acctid > 0 AND items.owner!='{$session['user']['acctid']}' ORDER BY items.value2 ASC";
			$result = db_query($sql);
	output("<form action='houses.php?op=drin&act=takekey' method='POST'>`2Wem willst du den Schlüssel wegnehmen? <select name='ziel'>",true);
			while ($item = db_fetch_assoc($result)) {
				output("<option value=\"".rawurlencode($item['name'])."\">".preg_replace_c("'[`].'","",$item['name'])."</option>",true);
			}
			rawoutput("</select><br /><br /><input type='submit' class='button' value='Schlüssel abnehmen'></form>");
			allownav("houses.php?op=drin&act=takekey");
		}
		else {
			$sql = "SELECT acctid,name,login,gold,gems FROM accounts WHERE name='".mysql_real_escape_string(rawurldecode(stripslashes($_POST['ziel'])))."'";
			$result2 = db_query($sql);
			$row2  = db_fetch_assoc($result2);
			output("`2Du verlangst den Schlüssel von `&".$row2['name']."`2 zurück.`n");
			$sql = "SELECT COUNT(id) AS num FROM items WHERE value1={$session['user']['specialmisc']['houseid']} AND class='Schlüssel' AND owner!=0 AND owner!=".$session['user']['acctid'];
			$result = db_query($sql);
			$keynum = db_fetch_assoc($result);

			$sql = 'SELECT housename FROM houses WHERE houseid='.$session['user']['specialmisc']['houseid'];
			$result = db_query($sql);
			$row = db_fetch_assoc($result);

			$info = module_getinfo_treasury();
			$moduleid = getmoduleid($info['modulename']);
			$goldinhouse = (int)getmoduledata($moduleid,'gold',$session['user']['specialmisc']['houseid']);
			$gemsinhouse = (int)getmoduledata($moduleid,'gems',$session['user']['specialmisc']['houseid']);
			$goldgive=round($goldinhouse  ($keynum['num']1));
			$gemsgive=round($gemsinhouse  ($keynum['num']1));
			if ($gemsgive > 0) $getstr = "`%$gemsgive Edelsteine`2";
			else $getstr = '';
			if ($goldgive > 0) {
				if ($getstr!='') $getstr .= ' und ';
				$getstr .= "`^$goldgive Gold`2 auf die Bank";
			}
			systemmail($row2['acctid'],"`@Schlüssel zurückverlangt!`0","`&{$session['user']['name']}`2 hat den Schlüssel zu Haus Nummer `b{$session['user']['specialmisc']['houseid']}`b (".$row['housename']."`2) zurückverlangt. Du bekommst $getstr aus dem gemeinsamen Schatz ausbezahlt!");
			output($row2['name']."`2 bekommt `^$goldgive Gold`2 und `%$gemsgive Edelsteine`2 aus dem gemeinsamen Schatz.");
			$sql = "UPDATE items SET owner={$session['user']['acctid']},hvalue=0 WHERE owner=".$row2['acctid']." AND class='Schlüssel' AND value1=".$session['user']['specialmisc']['houseid'];
			db_query($sql);
			$sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid=".$row2['acctid'];
			db_query($sql);
			setmoduledata($moduleid,'gold',$goldinhouse$goldgive,$session['user']['specialmisc']['houseid']);
			setmoduledata($moduleid,'gems',$gemsinhouse$gemsgive,$session['user']['specialmisc']['houseid']);
			$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `^nimmt ".$row2['name']."`^ einen Schlüssel ab. ".$row2['name']."`^ bekommt einen Teil aus dem Schatz. (`%$gemsgive Edelsteine`2 und `^$goldgive Gold`2)')";
			db_query($sql);
		}
		addnav("Schatzkammer","houses.php?op=drin");
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
	}
	elseif ($_GET['act']=="givekey") {
		if (empty($_POST['ziel'])) {
			output("`2Einen Schlüssel für dieses Haus hat:`n`n");
			$sql = "SELECT items.*,accounts.name AS besitzer FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE value1={$session['user']['specialmisc']['houseid']} AND class='Schlüssel' AND owner!=".$session['user']['acctid']." ORDER BY value2 ASC";
			$result = db_query($sql);
			while ($item = db_fetch_assoc($result)) {
				output("`c`& ".$item['besitzer']."`0`c");
			}
			$sql = "SELECT COUNT(id) AS num FROM items WHERE value1={$session['user']['specialmisc']['houseid']} AND class='Schlüssel' AND owner=".$session['user']['acctid'];
			$result = db_query($sql);
			$keynum = db_fetch_assoc($result);
			if ($keynum['num']>0) {
				output("`n`2Du kannst noch `b".$keynum['num']."`b Schlüssel vergeben.");
				rawoutput("<form action='houses.php?op=drin&act=givekey' method='POST'> An wen willst du einen Schlüssel übergeben? <input name='ziel'><br /> <input type='submit' class='button' value='Übergeben'></form>");
				output("`n`nWenn du einen Schlüssel vergibst, wird der Schatz des Hauses gemeinsam genutzt. Du kannst einem Mitbewohner zwar jederzeit den Schlüssel wieder wegnehmen, aber er wird dann einen gerechten Anteil aus dem gemeinsamen Schatz bekommen.");
				allownav("houses.php?op=drin&act=givekey");
			}
			else {
				output("`n`2Du hast keine Schlüssel mehr übrig. Vielleicht kannst du in der Jägerhütte noch einen nachmachen lassen?");
			}
		}
		else {
			if ($_GET['subfinal']==1) {
				$sql = "SELECT acctid,name,login,lastip,emailaddress FROM accounts WHERE name='".mysql_real_escape_string(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0 AND dragonkills >= ".getsetting('housekeymindk',0);
			}
			else {
				$ziel = stripslashes(rawurldecode($_POST['ziel']));
				$name="%";
				for ($x=0;$x<strlen_c($ziel);$x++) {
					$name.=substr_c($ziel,$x,1)."%";
				}
				$sql = "SELECT acctid,name,login,lastip FROM accounts WHERE name LIKE '".mysql_real_escape_string($name)."' AND locked=0 AND dragonkills >= ".getsetting('housekeymindk',0)." AND acctid!=".$session['user']['acctid'];
			}
			$result2 = db_query($sql);
			if (db_num_rows($result2) == 0) {
				output("`2Es gibt niemanden mit einem solchen Namen. Versuchs nochmal.");
			}else if (db_num_rows($result2) > 100) {
				output("`2Es gibt über 100 Krieger mit einem ähnlichen Namen. Bitte sei etwas genauer.");
			}else if (db_num_rows($result2) > 1) {
				output("`2Es gibt mehrere mögliche Krieger, denen du einen Schlüssel übergeben kannst.`n");
				output("`2Wen genau meinst du? ");
				rawoutput("<form action='houses.php?op=drin&act=givekey&subfinal=1' method='POST'> <select name='ziel'>");
				
				while ($row2 = db_fetch_assoc($result2)) {
				output("<option value='".rawurlencode($row2['name'])."'>".preg_replace_c("'[`].'","",$row2['name'])."</option>",true);
				}
				output("</select>`n`n<input type='submit' class='button' value='Schlüssel übergeben'></form>",true);
				allownav("houses.php?op=drin&act=givekey&subfinal=1");
			}
			else {
				$row2  = db_fetch_assoc($result2);
				$sql = "SELECT COUNT(owner) AS zahl FROM items WHERE owner=".$row2['acctid']." AND value1={$session['user']['specialmisc']['houseid']} AND class='Schlüssel' ORDER BY id ASC";
				$result = db_query($sql);
				$item = db_fetch_assoc($result);
				if ($row2['login'] == $session['user']['login']) {
					output("`2Du kannst dir nicht selbst einen Schlüssel geben.");
				}
				elseif ($item['zahl']>0) {
					output("`2".$row2['name']."`2 hat bereits einen Schlüssel!");
		 		}
		 		elseif (ac_check($row2)){
					output("`2Deine Charaktere dürfen leider nicht miteinander interagieren!");
				}
				else {
					$sql = "SELECT value2 FROM items WHERE value1={$session['user']['specialmisc']['houseid']} AND class='Schlüssel' AND owner={$session['user']['acctid']} ORDER BY id ASC LIMIT 1";
					$result = db_query($sql);
					$knr = db_fetch_assoc($result);
					$knr=$knr['value2'];
					$sql = 'SELECT housename FROM houses WHERE houseid='.$session['user']['specialmisc']['houseid'];
					$result = db_query($sql);
					$row = db_fetch_assoc($result);
					output("`2Du übergibst `&".$row2['name']."`2 einen Schlüssel für dein Haus. Du kannst den Schlüssel zum Haus jederzeit wieder wegnehmen, aber ".$row2['name']."`2 wird dann einen gerechten Anteil aus dem gemeinsamen Schatz des Hauses bekommen.`n");
					systemmail($row2['acctid'],"`@Schlüssel erhalten!`0","`&{$session['user']['name']}`2 hat dir einen Schlüssel zu Haus Nummer `b{$session['user']['specialmisc']['houseid']}`b (".$row['housename']."`2) gegeben!");
					$sql = "UPDATE items SET owner=".$row2['acctid'].",hvalue=0 WHERE owner={$session['user']['acctid']} AND class='Schlüssel' AND value1={$session['user']['specialmisc']['houseid']} AND value2=$knr";
					db_query($sql);
					$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `^gibt ".$row2['name']."`^ einen Schlüssel.')";
					db_query($sql);
				}
			}
		}
		addnav("Schatzkammer","houses.php?op=drin");
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
	}
	elseif ($_GET['act']=='givebackkey') {
		$sql = 'SELECT owner, housename FROM houses WHERE houseid='.$session['user']['specialmisc']['houseid'];
		$result = db_query($sql);
		$row = db_fetch_assoc($result);
		output("`2Du legst den Schlüssel für `&".$row['housename']."`2 auf den Schlüsselkasten.`n");
		$sql = "UPDATE items SET owner=".$row['owner'].",hvalue=0 WHERE owner=".$session['user']['acctid']." AND class='Schlüssel' AND value1=".$session['user']['specialmisc']['houseid'];
		db_query($sql);
		$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `^gibt einen Schlüssel zurück.')";
		db_query($sql);
		addnav('Wohnviertel',"houses.php");
		addnav('v?Wohnviertel verlassen','houses.php?op=leave');
	}
	elseif ($_GET['act']=="takegold"){
		$maxtfer = $session['user']['level']getsetting("transferperlevel","25");
		$info = module_getinfo_treasury();
		$moduleid = getmoduleid($info['modulename']);
		$goldinhouse = (int)getmoduledata($moduleid,'gold',$session['user']['specialmisc']['houseid']);
		if (empty($_POST['gold'])) {
			$transleft = getsetting("transferreceive",3)  $session['user']['transferredtoday'];
			output("`2Es befindet sich `^$goldinhouse`2 Gold in der Schatztruhe des Hauses.`nDu darfst heute noch {$transleft}x bis zu `^$maxtfer`2 Gold mitnehmen.`n");
			rawoutput("<form action='houses.php?op=drin&act=takegold' method='POST'>");
			output("`n`2Wieviel Gold mitnehmen? <input type='gold' name='gold'>`n`n<input type='submit' class='button' value='Mitnehmen'>",true);
			allownav("houses.php?op=drin&act=takegold");
		}
		else {
			$amt=abs((int)$_POST['gold']);
			if ($amt>$goldinhouse){
				output("`2So viel Gold ist nicht mehr da.");
			}
			elseif ($maxtfer<$amt) {
				output("`2Du darfst maximal `^$maxtfer`2 Gold auf einmal nehmen.");
			}
			elseif ($amt<0) {
				output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");
			}
			elseif ($session['user']['transferredtoday']>=getsetting("transferreceive",3)) {
				output("`2Du hast heute schon genug Gold bekommen. Du wirst bis morgen warten müssen.");
			}
			else {
				$goldinhouse -= $amt;
				setmoduledata($moduleid,'gold',$goldinhouse,$session['user']['specialmisc']['houseid']);
				$session['user']['gold'] += $amt;
                                houselog($session['user']['specialmisc']['houseid'], $session['user']['acctid'], '+', $amt,0);
				$session['user']['transferredtoday']++;
				output("`2Du hast `^$amt`2 Gold genommen. Insgesamt befindet sich jetzt noch `^$goldinhouse`2 Gold im Haus.");
				$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `\$nimmt `^$amt Gold`\$.')";
				db_query($sql);
			}
		}
		addnav("Schatzkammer","houses.php?op=drin");
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
	}
        elseif ($_GET['act']=="takemaxgold"){
       $maxtfer = $session['user']['level']getsetting("transferperlevel",25);
		$info = module_getinfo_treasury();
		$moduleid = getmoduleid($info['modulename']);
		$goldinhouse = (int)getmoduledata($moduleid,'gold',$session['user']['specialmisc']['houseid']);
		$transleft = getsetting("transferreceive",3)  $session['user']['transferredtoday'];
		$gold=$transleft$maxtfer;

			$amt=abs((int)$gold);
			if ($amt>$goldinhouse){
				output("`2So viel Gold ist nicht mehr da.");
			}
			elseif ($amt<0) {
				output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");
			}
			elseif ($session['user']['transferredtoday']>=getsetting("transferreceive",3)) {
				output("`2Du hast heute schon genug Gold bekommen. Du wirst bis morgen warten müssen.");
			}
			else {
				$goldinhouse -= $amt;
				setmoduledata($moduleid,'gold',$goldinhouse,$session['user']['specialmisc']['houseid']);
				$session['user']['gold'] += $amt;
                houselog($session['user']['specialmisc']['houseid'], $session['user']['acctid'], '+', $amt,0);
				$session['user']['transferredtoday']+=$transleft;
				output("`2Du hast `^$amt`2 Gold genommen. Insgesamt befindet sich jetzt noch `^$goldinhouse`2 Gold im Haus.");
				$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `\$nimmt `^$amt Gold`\$.')";
				db_query($sql);
			}
		
		addnav("Schatzkammer","houses.php?op=drin");
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
      }elseif ($_GET['act']=="givegold") {
		$maxout = $session['user']['level']getsetting("maxtransferout",25);
		$info = module_getinfo_treasury();
		$moduleid = getmoduleid($info['modulename']);
		$goldinhouse = (int)getmoduledata($moduleid,'gold',$session['user']['specialmisc']['houseid']);
		if (empty($_POST['gold'])) {
			$transleft = $maxout  $session['user']['amountouttoday'];
			output("`2Du darfst heute noch `^$transleft`2 Gold deponieren.`n");
			output("`2Derzeit befinden sich `^{$goldinhouse}`2 Gold im Schatz.`n");
			output("`2<form action=\"houses.php?op=drin&act=givegold\" method='POST'>",true);
			output("`nWieviel Gold deponieren? <input type='text' name='gold'>`n`n",true);
			output("<input type='submit' class='button' value='Deponieren'>",true);
			allownav("houses.php?op=drin&act=givegold");
		}
		else {
			$amt=abs((int)$_POST['gold']);
			if ($amt>$session['user']['gold']) {
				output("`2So viel Gold hast du nicht dabei.");
			}
			elseif ($goldinhouse >= 5000000) {
				output("`2Der Schatz ist voll.");
			}
			elseif ($amt>(5000000$goldinhouse)) {
				output("`2Du gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");
			}
			elseif ($amt<0) {
				output("`2Wenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");
			}
			elseif ($session['user']['amountouttoday']$amt > $maxout) {
				output("`2Du darfst nicht mehr als `^$maxout`2 Gold pro Tag deponieren.");
			}
			else {
				$goldinhouse += $amt;
				setmoduledata($moduleid,'gold',$goldinhouse,$session['user']['specialmisc']['houseid']);
				$session['user']['gold'] -= $amt;
                                houselog($session['user']['specialmisc']['houseid'], $session['user']['acctid'], '+', $amt,0); 
				$session['user']['amountouttoday'] += $amt;
				output("`2Du hast `^$amt`2 Gold deponiert. Insgesamt befinden sich jetzt `^$goldinhouse`2 Gold im Haus.");
				$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `@deponiert `^$amt Gold`@.')";
				db_query($sql);
			}
		}
		addnav("Schatzkammer","houses.php?op=drin");
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
	}
	elseif ($_GET['act']=="takegems"){
		$info = module_getinfo_treasury();
		$moduleid = getmoduleid($info['modulename']);
		$gemsinhouse = (int)getmoduledata($moduleid,'gems',$session['user']['specialmisc']['houseid']);
		if (empty($_POST['gems'])) {
			output("`2Es befinden sich `%$gemsinhouse Edelsteine`2 in der Schatztruhe des Hauses.`n");
			rawoutput("<form action='houses.php?op=drin&act=takegems' method='POST'>");
			output("`2`nWieviele Edelsteine mitnehmen? <input type='text' name='gems'>`n`n",true);
			rawoutput("<input type='submit' class='button' value='Mitnehmen'>");
			allownav("houses.php?op=drin&act=takegems");
		}
		else {
			$amt=abs((int)$_POST['gems']);
			if ($amt>$gemsinhouse){
				output("`2So viele Edelsteine sind nicht mehr da.");
			}
			elseif ($amt<0) {
				output("`2Wenn du etwas in den Schatz legen willst, versuche nicht, etwas negatives herauszunehmen.");
			}
			else {
				$gemsinhouse -= $amt;
				setmoduledata($moduleid,'gems',$gemsinhouse,$session['user']['specialmisc']['houseid']);
				$session['user']['gems'] += $amt;
                                houselog($session['user']['specialmisc']['houseid'], $session['user']['acctid'], '-', 0,$amt); 
				output("`2Du hast `%$amt Edelsteine`2 genommen. Insgesamt befindet sich jetzt noch `%$gemsinhouse Edelsteine`2 im Haus.");
				$sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `\$nimmt `%$amt Edelsteine`\$.')";
				db_query($sql);
			}
		}
		addnav("Schatzkammer","houses.php?op=drin");
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
	}
	elseif ($_GET['act']=="givegems") {
		$info = module_getinfo_treasury();
		$moduleid = getmoduleid($info['modulename']);
		$gemsinhouse = (int)getmoduledata($moduleid,'gems',$session['user']['specialmisc']['houseid']);
		if (empty($_POST['gems'])) {
			output("`2Derzeit befinden sich `%{$gemsinhouse} Edelsteine`2 im Schatz.`n");
			output("`2<form action=\"houses.php?op=drin&act=givegems\" method='POST'>",true);
			output("`nWieviele Edelsteine deponieren? <input type='text' name='gems'>`n`n",true);
			output("<input type='submit' class='button' value='Deponieren'>",true);
			allownav("houses.php?op=drin&act=givegems");
		}
		else {
			$amt=abs((int)$_POST['gems']);
			if ($amt>$session['user']['gems']) {
				output("`2So viele Edelsteine hast du nicht dabei.");
			}
			elseif ($gemsinhouse >= 1000000) {
				output("`2Der Schatz ist voll.");
			}
			elseif ($amt>(1000000$gemsinhouse)) {
				output("`2Du gibst alles, aber du bekommst beim besten Willen nicht so viel in den Schatz.");
			}
			elseif ($amt<0) {
				output("`2Wenn du etwas aus dem Schatz nehmen willst, versuche nicht, etwas negatives hineinzutun.");
			}
			else {
				$gemsinhouse += $amt;
				setmoduledata($moduleid,'gems',$gemsinhouse,$session['user']['specialmisc']['houseid']);
				$session['user']['gems'] -= $amt;
                                houselog($session['user']['specialmisc']['houseid'], $session['user']['acctid'], '+', 0,$amt);
				output("`2Du hast `%$amt Edelsteine`2 deponiert. Insgesamt befinden sich jetzt `%$gemsinhouse Edelsteine`2 im Haus.");
				$sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."-treasure',".$session['user']['acctid'].",'/me `@deponiert `%$amt Edelsteine`@.')";
				db_query($sql);
			}
		}
		addnav("Schatzkammer","houses.php?op=drin");
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
	}
	else {
		output('`2Du betrittst die `^Schatzkammer`2 des Hauses - hier werden die Reichtümer
				der Bewohner aufbewahrt. Außerdem erblickst du einen verschlossenen Schlüsselkasten.');

		addnav('Gold');
		addnav("Deponieren","houses.php?op=drin&act=givegold");
		addnav("Mitnehmen","houses.php?op=drin&act=takegold");
		addnav("Maximal Mitnehmen","houses.php?op=drin&act=takemaxgold");
		addnav('Edelsteine');
		addnav("Deponieren","houses.php?op=drin&act=givegems");
		addnav("Mitnehmen","houses.php?op=drin&act=takegems");

		addnav("Schlüssel");
		if ($session['user']['house']==$session['user']['specialmisc']['houseid']){
			addnav("Vergeben","houses.php?op=drin&act=givekey");
			addnav("n?Zurücknehmen","houses.php?op=drin&act=takekey");
		}
		else {
			addnav("n?Zurückgeben","houses.php?op=drin&act=givebackkey");
		}
		addnav('Sonstiges');
		addnav("Zurück zum Haus","houses.php?op=drin&module=");
	}

	// uncomment these lines if you want to show the default navs even if this is not the default module
	// global $shownavs;
	// $shownavs = true;

	// uncomment these lines if you want to hide the default navs even if this is the default module
	// global $shownavs;
	// $shownavs = false;
/* content_show end */
}
?>