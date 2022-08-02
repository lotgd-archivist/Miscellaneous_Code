<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'default_module_template' with your module's (internal) name.
function module_getinfo_default_module_template() {
	$info = array(
					'modulename'=>'default_module_template', 		// internal name; use letters, numbers and underscores only!
					'modulefile'=>basename(__FILE__), 				// filename of the module; if you allow to rename the script, do NOT change this!
					'moduleauthor'=>'Your name here!', 				// the author's name
					'moduleversion'=>'0.1b',							// the module's version number
					'built_in'=>'0',										// '1', if each house should have this module built-in; otherwise '0'
					'linkcategory'=>'Category',						// the category (in houses.php's menu) under which the link to this module should be shown
					'linktitle'=>'My default module',				// the link title of the module
					'showto'=>'owner,guest'								// who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
	);
	return $info;
}

function module_install_default_module_template() {
	// insert data into module table - do NOT change this (well... just change the function name ;))!
	$info = module_getinfo_default_module_template();
	$sql = "INSERT INTO housemodules
				(modulefile, modulename, moduleversion, moduleauthor, built_in, linkcategory, linktitle,showto)
				VALUES ('{$info['modulefile']}','{$info['modulename']}','{$info['moduleversion']}','{$info['moduleauthor']}','{$info['built_in']}','{$info['linkcategory']}','{$info['linktitle']}','{$info['showto']}')";
	db_query($sql);
	$moduleid = db_insert_id(LINK);

	// insert global module data (you can add several entries - but do NOT
	// change anything else than "FieldName" and FieldValue"!)
	/*
	$sql = 'INSERT INTO housemoduledata (moduleid, name, houseid, value)
				VALUES ('.$moduleid.',"FieldName",0,"FieldValue")';
	db_query($sql);
	*/

	// here you can change everything else needed (e.g. adding settings)
	// be careful: these changes must be global; per-house-changes will be done
	// in module_build()!
}

function module_uninstall_default_module_template() {
	// uninstalling the module
	// this function should also contain all module_destroy contents

	// getting moduleid - do NOT change this (same as above... the function name should be changed)!
	$info = module_getinfo_default_module_template();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module from db - do NOT change this!
	$sql = 'DELETE FROM housemodules WHERE moduleid='.$moduleid;
	db_query($sql);

	// deleting internal module data - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid;
	db_query($sql);

	// here you should delete all other added things (e.g. settings) of this module
}

function module_build_default_module_template($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... blablabla)!
	$info = module_getinfo_default_module_template();
	$moduleid = getmoduleid($info['modulename']);

	// setting flag for house - do NOT change this!
	$sql = 'INSERT INTO housemoduledata (moduleid, name, houseid, value)
				VALUES ('.$moduleid.',"#activated#",'.$houseid.',"1")';
	db_query($sql);

	// here you can change everything else needed (e.g. changing user settings)
	// be careful: these changes must be for this house only; global changes will be done
	// in module_install()!
}

function module_destroy_default_module_template($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... moooooooooh!)!
	$info = module_getinfo_default_module_template();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module data of this house - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
	db_query($sql);

	// here you should delete all other added things (e.g. user settings) of this module and house
}

function module_show_default_module_template() {
	// this is the main part of the module where all output is done ;)
	// don't forget the navs; only the default module does not need them (but may add some)
	// to return to the main module, use this link: houses.php?op=drin&module (without id!)
	// don't forget 'global $session;' if you need the player's data (and you WILL need them!)

	// uncomment these lines if you want to show the default navs even if this is not the default module
	// global $shownavs;
	// $shownavs = true;

	// uncomment these lines if you want to hide the default navs even if this is the default module
	// global $shownavs;
	// $shownavs = false;
}
?>
