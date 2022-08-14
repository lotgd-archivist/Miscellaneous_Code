
<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'default' with your module's (internal) name.
function module_getinfo_default() {
    $info = array(
                    'modulename'=>'default',         // internal name; use letters, numbers and underscores only!
                    'modulefile'=>basename(__FILE__),     // filename of the module; if you allow to rename the script, do NOT change this!
                    'moduleauthor'=>'Chaosmaker',             // the author's name
                    'moduleversion'=>'1.0',            // the module's version number
                    'built_in'=>'1',                // '1', if each house should have this module built-in; otherwise '0'
                    'linkcategory'=>'',            // the category (in houses.php's menu) under which the link to this module should be shown
                    'linktitle'=>'',            // the link title of the module
                    'showto'=>'owner,guest'                        // who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
    );
    return $info;
}

function module_install_default() {
    // insert data into module table - do NOT change this (well... just change the function name ;))!
    $info = module_getinfo_default();
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

function module_uninstall_default() {
    // uninstalling the module
    // this function should also contain all module_destroy contents

    // getting moduleid - do NOT change this (same as above... the function name should be changed)!
    $info = module_getinfo_default();
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

function module_build_default($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... blablabla)!
    $info = module_getinfo_default();
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

function module_destroy_default($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... moooooooooh!)!
    $info = module_getinfo_default();
    $moduleid = getmoduleid($info['modulename']);

    // deleting module data of this house - do NOT change this!
    $sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
    db_query($sql);

    // here you should delete all other added things (e.g. user settings) of this module and house
    /* destroy_other begin */

/* destroy_other end */
}

function module_show_default() {
    // this is the main part of the module where all output is done ;)
    // don't forget the navs; only the default module does not need them (but may add some)
    // to return to the main module, use this link: houses.php?op=drin&module (without id!)
    // don't forget 'global $session;' if you need the player's data (and you WILL need them!)
   
   /* content_show begin */
    global $session;

    addcommentary();

    $sql = 'SELECT houses.housename, houses.description, houses.owner, accounts.name AS ownername FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE houses.houseid='.$session['user']['specialmisc']['houseid'];
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);

    output("`2`b`c$row[housename] `0(".($row['ownername']!=''?$row['ownername'].'`0':'`iverlassen`i').")`c`b`n");
    if ($row['description']!='') output("`0`c$row[description]`c`2`n");

    // show treasury status
    if ($mid = module_builtin('treasury',$session['user']['specialmisc']['houseid'])) {
        $goldinhouse = (int)getmoduledata($mid,'gold',$session['user']['specialmisc']['houseid']);
        $gemsinhouse = (int)getmoduledata($mid,'gems',$session['user']['specialmisc']['houseid']);
        if ($goldinhouse > 0) {
            if ($gemsinhouse > 0) output("`2Du und deine Mitbewohner haben `^$goldinhouse Gold`2 und `%$gemsinhouse Edelsteine`2 im Haus gelagert.`n");
            else output("`2Du und deine Mitbewohner haben `^$goldinhouse`2 Gold im Haus gelagert.`n");
        }
        elseif ($gemsinhouse > 0) output("`2Du und deine Mitbewohner haben `%$gemsinhouse Edelsteine`2 im Haus gelagert.`n");
        else output("`2Du und deine Mitbewohner haben nichts im Haus gelagert.`n");
    }

    // show ingame date
    if (getsetting('activategamedate','0')==1) {
        output('Wir schreiben den `^'.getgamedate().'`2.`n');
    }
    // show ingame time
    output('Es ist jetzt `^'.getgametime().'`2 Uhr.`n`n');
    // show commentary
    viewcommentary("house-".$session['user']['specialmisc']['houseid'],"Mit Mitbewohnern reden:",30,"sagt");

    // show keys
    output("`n`n`n<table border='0'><tr><td>`2`bDie SchlÃ¼ssel:`b `0</td><td>`2`bExtra Ausstattung`b</td></tr><tr><td valign='top'>",true);
    $sql = "SELECT items.id,items.hvalue,items.owner,accounts.acctid AS aid,accounts.name AS besitzer FROM items LEFT JOIN accounts ON accounts.acctid=items.owner WHERE value1={$session['user']['specialmisc']['houseid']} AND class='SchlÃ¼ssel' ORDER BY items.id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    for ($i=1;$i<=db_num_rows($result);$i++){
        $item = db_fetch_assoc($result);
        if ($item['besitzer']==""){
            output("`n`2$i: `4`iVerloren`i`0");
        }else{
            output("`n`2$i: `&$item[besitzer]`0");
        }
        if ($item['aid']==$row['owner']) output(" (der EigentÃ¼mer) ");
        if ($item['hvalue']>0 && $item['owner']>0) output(" `ischlÃ¤ft hier`i");
    }

    // check if owner sleeps at home
    $sql = 'SELECT COUNT(acctid) AS num FROM accounts
                LEFT JOIN items ON items.hvalue > 0 AND items.value1!='.$session['user']['specialmisc']['houseid'].' AND items.class="SchlÃ¼ssel" AND items.owner='.$row['owner'].'
                WHERE accounts.acctid='.$row['owner'].' AND accounts.location=2 AND items.id IS NULL';
    $result = db_query($sql);
    $sleephome = db_fetch_assoc($result);
    if ($sleephome['num']==1) output("`nDer EigentÃ¼mer schlÃ¤ft hier");

    output("</td><td valign='top'>",true);

    // show furniture
    $sql = "SELECT name,description FROM items WHERE value1={$session['user']['specialmisc']['houseid']} AND class='MÃ¶bel' ORDER BY class,id ASC";
    $result = db_query($sql) or die(db_error(LINK));
    while ($item = db_fetch_assoc($result)) {
        output("`n`&$item[name]`0 (`i$item[description]`i)");
    }

    output("</td></tr></table>",true);


// uncomment these lines if you want to show the default navs even if this is not the default module
// global $shownavs;
// $shownavs = true;

// uncomment these lines if you want to hide the default navs even if this is the default module
// global $shownavs;
// $shownavs = false;
/* content_show end */
}
?>

