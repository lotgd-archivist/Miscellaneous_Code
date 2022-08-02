<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'private_chamber' with your module's (internal) name.
function module_getinfo_private_chamber() {
	$info = array(
					'modulename'=>'private_chamber', 		// internal name; use letters, numbers and underscores only!
					'modulefile'=>basename(__FILE__), 	// filename of the module; if you allow to rename the script, do NOT change this!
					'moduleauthor'=>'Chaosmaker', 			// the author's name
					'moduleversion'=>'1.0',			// the module's version number
					'built_in'=>'1',				// '1', if each house should have this module built-in; otherwise '0'
					'linkcategory'=>'Weitere Räume',			// the category (in houses.php's menu) under which the link to this module should be shown
					'linktitle'=>'Privatgemach',			// the link title of the module
					'showto'=>'owner,guest'						// who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
	);
	return $info;
}

function module_install_private_chamber() {
	// insert data into module table - do NOT change this (well... just change the function name ;))!
	$info = module_getinfo_private_chamber();
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

function module_uninstall_private_chamber() {
	// uninstalling the module
	// this function should also contain all module_destroy contents

	// getting moduleid - do NOT change this (same as above... the function name should be changed)!
	$info = module_getinfo_private_chamber();
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

function module_build_private_chamber($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... blablabla)!
	$info = module_getinfo_private_chamber();
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

function module_destroy_private_chamber($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... moooooooooh!)!
	$info = module_getinfo_private_chamber();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module data of this house - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
	db_query($sql);

	// here you should delete all other added things (e.g. user settings) of this module and house
	/* destroy_other begin */

/* destroy_other end */
}

function module_show_private_chamber() {
	// this is the main part of the module where all output is done ;)
	// don't forget the navs; only the default module does not need them (but may add some)
	// to return to the main module, use this link: houses.php?op=drin&module (without id!)
	// don't forget 'global $session;' if you need the player's data (and you WILL need them!)
   
   /* content_show begin */
	global $session;

	addcommentary();

	$sql = 'SELECT owner FROM houses WHERE houseid='.$session['user']['specialmisc']['houseid'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	// falls eigentuemer oder partner
	if ($session['user']['house']==$session['user']['specialmisc']['houseid'] || $session['user']['marriedto']==$row['owner']){
		output('`qDu trittst durch eine unscheinbare Tür in dein privates Schlafgemach. Es ist angenehm warm, kein Wunder, denn in dem schön verzierten Kamin in der Ecke prasselt ein kleines Feuer. Auf einem steinernen Vorsprung der Mauer, der mehr schon eine Bank zu sein scheint, liegt zusammengefaltet ein großes, kuscheliges Fell, wohl eine Erinnerung an deinen letzten Jagdausflug.`nDie andere Ecke des Raumes nimmt dein großes, mit Holzschnitzereien versehenes Himmelbett ein. Die seidenen Vorhänge sind geöffnet und geben den Blick frei auf rote, mit Goldfäden bestickte Kissen. Du überlegst, was dich mehr anzieht, das Fell vor dem Kamin, die Steinbank oder dein gemütliches Bett, entscheidest dich dann aber, zuerst ein wenig im Zuber des angrenzenden Bads zu planschen.`n');
		// Verheiratet?
		if($session['user']['ehe']==1) output('Du hörst Geräusche vor der Tür und hoffst, dass '.($session['user']['sex']?'dein Liebster':'deine Liebste').' inzwischen heimgekommen ist.`n');
		output('`n');
		viewcommentary("private-".$session['user']['specialmisc']['houseid'],($session['user']['sex']?"Deinem":"Deiner")." Liebsten zuflüstern:",10,"flüstert");
	} else { // jeder andere
		output('Du rüttelst ein wenig an der Tür - verschlossen.`n Tja, in den Privatgemächern hast du eben nichts zu suchen.');
	}
	addnav("Zurück zum Haus","houses.php?op=drin&module=");

	// uncomment these lines if you want to show the default navs even if this is not the default module
	// global $shownavs;
	// $shownavs = true;

	// uncomment these lines if you want to hide the default navs even if this is the default module
	// global $shownavs;
	// $shownavs = false;
/* content_show end */
}
?>