<?php
// commonroom module
// ModulAuthor: Kevz <webmaster@lotgd.dyndns.biz>
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
function module_getinfo_commonroom() {
	$info = array(
					'modulename'=>'commonroom', 		// internal name; use letters, numbers and underscores only!
					'modulefile'=>basename(__FILE__), 				// filename of the module; if you allow to rename the script, do NOT change this!
					'moduleauthor'=>'`2K`@e`2v`@z', 				// the author's name
					'moduleversion'=>'1.0',							// the module's version number
					'built_in'=>'1',										// '1', if each house should have this module built-in; otherwise '0'
					'linkcategory'=>'Zimmer',						// the category (in houses.php's menu) under which the link to this module should be shown
					'linktitle'=>'Gemeinschaftsraum',					// the link title of the module
					'showto'=>'owner,guest'								// who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
	);
	return $info;
}

function module_install_commonroom() {
	// insert data into module table - do NOT change this (well... just change the function name ;))!
	$info = module_getinfo_commonroom();
	$sql = "INSERT INTO housemodules
				(modulefile, modulename, moduleversion, moduleauthor, built_in, linkcategory, linktitle, showto)
				VALUES ('{$info['modulefile']}','{$info['modulename']}','{$info['moduleversion']}','{$info['moduleauthor']}','{$info['built_in']}','{$info['linkcategory']}','{$info['linktitle']}','{$info['showto']}')";
	db_query($sql);
	$moduleid = db_insert_id(LINK);

	// insert global module data (you can add several entries - but do NOT
	// change anything else than "FieldName" and FieldValue"!)

	// here you can change everything else needed (e.g. adding settings)
	// be careful: these changes must be global; per-house-changes will be done
	// in module_build()!
}

function module_uninstall_commonroom() {
	// uninstalling the module
	// this function should also contain all module_destroy contents

	// getting moduleid - do NOT change this (same as above... the function name should be changed)!
	$info = module_getinfo_commonroom();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module from db - do NOT change this!
	$sql = 'DELETE FROM housemodules WHERE moduleid='.$moduleid;
	db_query($sql);

	// deleting internal module data - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid;
	db_query($sql);

	// here you should delete all other added things (e.g. settings) of this module
}

function module_build_commonroom($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... blablabla)!
	$info = module_getinfo_commonroom();
	$moduleid = getmoduleid($info['modulename']);

	// setting flag for house - do NOT change this!
	$sql = 'INSERT INTO housemoduledata (moduleid, name, houseid, value)
				VALUES ('.$moduleid.',"#activated#",'.$houseid.',"1")';
	db_query($sql);

	// here you can change everything else needed (e.g. changing user settings)
	// be careful: these changes must be for this house only; global changes will be done
	// in module_install()!
}

function module_destroy_commonroom($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... moooooooooh!)!
	$info = module_getinfo_commonroom();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module data of this house - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
db_query($sql);

	// here you should delete all other added things (e.g. user settings) of this module and house
}

function module_show_commonroom() {
	// this is the main part of the module where all output is done ;)
	// don't forget the navs; only the default module does not need them (but may add some)
	// to return to the main module, use this link: houses.php?op=drin&module (without id!)
	global $session;

	addcommentary();
	
	$sql = 'SELECT houses.housename, houses.owner FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE houses.houseid='.$session['user']['specialmisc']['houseid'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	
	output("`2`b`c".$row['housename']." `2 (Im Gemeinschaftsraum)`c`b`n`n `2Es ist jetzt `^".getgametime()."`2 Uhr, und du befindest dich im Gemeinschaftsraum.`n`n`n");
	
	viewcommentary("house-".$session['user']['specialmisc']['houseid'],"Mit Mitbewohnern reden:",10,"sagt");
	
	// show furniture
	output("`n`n`n<table border='0'><tr><td>`2`bExtra Ausstattung`b</td></tr><tr>",true);
        rawoutput('</td><td>');
		
	$sql = "SELECT name,description FROM items WHERE value1={$session['user']['specialmisc']['houseid']} AND class='Möbel' ORDER BY class,id ASC";
	$result = db_query($sql) or die(db_error(LINK));
	while ($item = db_fetch_assoc($result)) {
		output("`n`&".$item['name']."`0 (`i".$item['description']."`i)");
	}

	rawoutput('</td></tr></table>');
	
	
	addnav("Zurück zum Haus","houses.php?op=drin&module=");

	// uncomment these lines if you want to show the default navs even if this is not the default module
	// global $shownavs;
	// $shownavs = true;

	// uncomment these lines if you want to hide the default navs even if this is the default module
	// global $shownavs;
	// $shownavs = false;
}
?>