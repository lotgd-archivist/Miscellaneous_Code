
<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'rename' with your module's (internal) name.
function module_getinfo_rename() {
    $info = array(
                    'modulename'=>'rename',         // internal name; use letters, numbers and underscores only!
                    'modulefile'=>basename(__FILE__),     // filename of the module; if you allow to rename the script, do NOT change this!
                    'moduleauthor'=>'Chaosmaker',             // the author's name
                    'moduleversion'=>'1.0',            // the module's version number
                    'built_in'=>'1',                // '1', if each house should have this module built-in; otherwise '0'
                    'linkcategory'=>'Funktionen',            // the category (in houses.php's menu) under which the link to this module should be shown
                    'linktitle'=>'Haus umbenennen',            // the link title of the module
                    'showto'=>'owner'                        // who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
    );
    return $info;
}

function module_install_rename() {
    // insert data into module table - do NOT change this (well... just change the function name ;))!
    $info = module_getinfo_rename();
    $sql = "INSERT INTO housemodules
                (modulefile, modulename, moduleversion, moduleauthor, built_in, linkcategory, linktitle,showto)
                VALUES ('{$info['modulefile']}', '{$info['modulename']}', '{$info['moduleversion']}', '{$info['moduleauthor']}', '{$info['built_in']}', '{$info['linkcategory']}', '{$info['linktitle']}', '{$info['showto']}')";
    db_query($sql);
    $moduleid = db_insert_id(LINK);

    // insert global module data (you can add several entries - but do NOT
    // change anything else than "FieldName" and "FieldValue"!)
    /*
    $sql = 'INSERT INTO housemoduledata (moduleid, name, houseid, value)
                VALUES ('.$moduleid.',"FieldName",0,"FieldValue")';
    db_query($sql);
    */
    /* install_moduledata begin */

/* install_moduledata end */

    // here you can change everything else needed (e.g. adding settings)
    // be careful: these changes must be global; per-house-changes will be done
    // in module_build()!
    /* install_other begin */

/* install_other end */
}

function module_uninstall_rename() {
    // uninstalling the module
    // this function should also contain all module_destroy contents

    // getting moduleid - do NOT change this (same as above... the function name should be changed)!
    $info = module_getinfo_rename();
    $moduleid = getmoduleid($info['modulename']);

    // deleting module from db - do NOT change this!
    $sql = 'DELETE FROM housemodules WHERE moduleid='.$moduleid;
    db_query($sql);

    // deleting internal module data - do NOT change this!
    $sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid;
    db_query($sql);

    // here you should delete all other added things (e.g. settings) of this module
    /* delete_other begin */

/* delete_other end */
}

function module_build_rename($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... blablabla)!
    $info = module_getinfo_rename();
    $moduleid = getmoduleid($info['modulename']);

    // setting flag for house - do NOT change this!
    $sql = 'INSERT INTO housemoduledata (moduleid, name, houseid, value)
                VALUES ('.$moduleid.',"#activated#",'.$houseid.',"1")';
    db_query($sql);

    // here you can change everything else needed (e.g. changing user settings)
    // be careful: these changes must be for this house only; global changes will be done
    // in module_install()!
    /* build_other begin */

/* build_other end */
}

function module_destroy_rename($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... moooooooooh!)!
    $info = module_getinfo_rename();
    $moduleid = getmoduleid($info['modulename']);

    // deleting module data of this house - do NOT change this!
    $sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
    db_query($sql);

    // here you should delete all other added things (e.g. user settings) of this module and house
    /* destroy_other begin */

/* destroy_other end */
}

function module_show_rename() {
    // this is the main part of the module where all output is done ;)
    // don't forget the navs; only the default module does not need them (but may add some)
    // to return to the main module, use this link: houses.php?op=drin&module (without id!)
    // don't forget 'global $session;' if you need the player's data (and you WILL need them!)
   
   /* content_show begin */
    global $session;
    if (empty($_POST['housename'])) {
        output("`2Das Haus umbenennen kostet `^1000`2 Gold und `#1`2 Edelstein.`n`n");
        output("`0<form action=\"houses.php?op=drin&act=rename\" method='POST'>",true);
        output("`nGib einen neuen Namen fÃ¼r dein Haus ein: <input name='housename'>`n",true);
        output("<input type='submit' class='button' value='Umbenennen'>",true);
        addnav("","houses.php?op=drin&act=rename");
    }
    else {
        if ($session['user']['gold']<1000 || $session['user']['gems']<1) {
            output("`2Das kannst du nicht bezahlen.");
        }
        else {
            $sql = 'SELECT housename FROM houses WHERE houseid='.$session['user']['specialmisc']['houseid'];
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            output("`2Dein Haus `@$row[housename]`2 heiÃŸt jetzt `@".stripslashes($_POST['housename'])."`2.");
            $sql = "UPDATE houses SET housename='".$_POST['housename']."' WHERE houseid=".$session['user']['specialmisc']['houseid'];
            db_query($sql);
            $session['user']['gold']-=1000;
            $session['user']['gems']-=1;
        }
    }
    addnav("ZurÃ¼ck zum Haus","houses.php?op=drin&module=");

    // uncomment these lines if you want to show the default navs even if this is not the default module
    // global $shownavs;
    // $shownavs = true;

    // uncomment these lines if you want to hide the default navs even if this is the default module
    // global $shownavs;
    // $shownavs = false;
/* content_show end */
}
?>

