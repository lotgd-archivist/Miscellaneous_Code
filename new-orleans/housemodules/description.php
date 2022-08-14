
<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'description' with your module's (internal) name.
function module_getinfo_description() {
    $info = array(
                    'modulename'=>'description',         // internal name; use letters, numbers and underscores only!
                    'modulefile'=>basename(__FILE__),     // filename of the module; if you allow to rename the script, do NOT change this!
                    'moduleauthor'=>'Chaosmaker',             // the author's name
                    'moduleversion'=>'1.0',            // the module's version number
                    'built_in'=>'1',                // '1', if each house should have this module built-in; otherwise '0'
                    'linkcategory'=>'Funktionen',            // the category (in houses.php's menu) under which the link to this module should be shown
                    'linktitle'=>'Beschreibungen Ã¤ndern',            // the link title of the module
                    'showto'=>'owner'                        // who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
    );
    return $info;
}

function module_install_description() {
    // insert data into module table - do NOT change this (well... just change the function name ;))!
    $info = module_getinfo_description();
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

function module_uninstall_description() {
    // uninstalling the module
    // this function should also contain all module_destroy contents

    // getting moduleid - do NOT change this (same as above... the function name should be changed)!
    $info = module_getinfo_description();
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

function module_build_description($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... blablabla)!
    $info = module_getinfo_description();
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

function module_destroy_description($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... moooooooooh!)!
    $info = module_getinfo_description();
    $moduleid = getmoduleid($info['modulename']);

    // deleting module data of this house - do NOT change this!
    $sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
    db_query($sql);

    // here you should delete all other added things (e.g. user settings) of this module and house
    /* destroy_other begin */

/* destroy_other end */
}

function module_show_description() {
    // this is the main part of the module where all output is done ;)
    // don't forget the navs; only the default module does not need them (but may add some)
    // to return to the main module, use this link: houses.php?op=drin&module (without id!)
    // don't forget 'global $session;' if you need the player's data (and you WILL need them!)
   
   /* content_show begin */
    global $session;
    if (empty($_POST['desc'])) {
        $sql = 'SELECT description,description_out,house_ava,privatzimmer FROM houses WHERE houseid="'.$session['user']['specialmisc']['houseid'].'"';
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        output("`2`bHier kannst du das Bild fÃ¼r dein Haus Ã¤ndern.`b`n`nDas aktuelle Bild sieht so aus: `0<img src='".$row['house_ava']."' align='right'>`0`n",true);
        output("`0<form action=\"houses.php?op=drin&act=desc\" method='POST'>",true);
        output("`n`2Link zum Bild fÃ¼r dein Haus:`n<input name='ava' size='50' value='".$row['house_ava']."'>`n",true);
        output("<input type='submit' class='button' value='Abschicken'>",true);
        addnav("","houses.php?op=drin&act=desc");

        output("`n`n`n`n`2`bHier kannst du die Beschreibung fÃ¼r dein Haus Ã¤ndern.`b`n`nDie aktuelle Beschreibung fÃ¼r Innen lautet: `0".$row['description']."`0`n");
        output("`0<form action=\"houses.php?op=drin&act=desc\" method='POST'>",true);
        output("`n`2Gib eine Beschreibung fÃ¼r dein Haus ein:`n<textarea class='input' name='desc' cols='37' rows='5'>".HTMLEntities(str_replace("`","``",$row['description']),ENT_QUOTES)."</textarea>`n",true);
        output("<input type='submit' class='button' value='Abschicken'>",true);
        addnav("","houses.php?op=drin&act=desc");

        output("`n`n`nDie aktuelle Beschreibung fÃ¼r AuÃŸen (Wohnviertel-Liste) lautet: `0".$row['description_out']."`0`n");
        output("`0<form action=\"houses.php?op=drin&act=desc\" method='POST'>",true);
        output("`n`2Gib eine Beschreibung fÃ¼r dein Haus ein:`n<textarea class='input' name='desc2' cols='37' rows='5'>".HTMLEntities(str_replace("`","``",$row['description_out']),ENT_QUOTES)."</textarea>`n",true);
        output("<input type='submit' class='button' value='Abschicken'>",true);
        addnav("","houses.php?op=drin&act=desc");

        output("`n`n`n`2`bHier kannst du den Eingangstext fÃ¼r Dein Privatzimmer Ã¤ndern.`b`n`nDie aktuelle Beschreibung lautet: `0".$row['privatzimmer']."`0`n");
        output("`0<form action=\"houses.php?op=drin&act=desc\" method='POST'>",true);
        output("`n`2Gib einen Eingangstext fÃ¼r Dein Privatzimmer ein:`n<textarea class='input' name='desc3' cols='37' rows='5'>".HTMLEntities(str_replace("`","``",$row['privatzimmer']),ENT_QUOTES)."</textarea>`n",true);
        output("<input type='submit' class='button' value='Abschicken'>",true);
        addnav("","houses.php?op=drin&act=desc");
    }else{
        $_POST['desc'] = str_replace("`n","\n",$_POST['desc']);
        $_POST['desc'] = str_replace("\r\n","\n",$_POST['desc']);
        $_POST['desc'] = str_replace("\r","\n",$_POST['desc']);
        $_POST['desc'] = str_replace("`n","",$_POST['desc']);
        $_POST['desc'] = closetags($_POST['desc'],'`c`i`b');

        $_POST['desc2'] = str_replace("`n","\n",$_POST['desc2']);
        $_POST['desc2'] = str_replace("\r\n","\n",$_POST['desc2']);
        $_POST['desc2'] = str_replace("\r","\n",$_POST['desc2']);
        $_POST['desc2'] = str_replace("`n","",$_POST['desc2']);
        $_POST['desc2'] = closetags($_POST['desc2'],'`c`i`b');

        $_POST['desc3'] = str_replace("`n","\n",$_POST['desc3']);
        $_POST['desc3'] = str_replace("\r\n","\n",$_POST['desc3']);
        $_POST['desc3'] = str_replace("\r","\n",$_POST['desc3']);
        $_POST['desc3'] = str_replace("`n","",$_POST['desc3']);
        $_POST['desc3'] = closetags($_POST['desc3'],'`c`i`b');

        output("`n`n`2Avatar gespeichert.`0");
        $sql = "UPDATE houses SET house_ava='".$_POST['ava']."' WHERE houseid=".$session['user']['specialmisc']['houseid'];
        db_query($sql);

        output("`n`n`2Die Beschreibung fÃ¼r Innen wurde geÃ¤ndert.`0`n`n".str_replace("\n","`n","".$_POST['desc']."")."`2");
        $sql = "UPDATE houses SET description='".$_POST['desc']."' WHERE houseid=".$session['user']['specialmisc']['houseid'];
        db_query($sql);
        
        output("`n`n`2Die Beschreibung fÃ¼r AuÃŸen wurde geÃ¤ndert.`0`n`n".str_replace("\n","`n","".$_POST['desc2']."")."`2");
        $sql = "UPDATE houses SET description_out='".$_POST['desc2']."' WHERE houseid=".$session['user']['specialmisc']['houseid'];
        db_query($sql);

        output("`n`n`2Die Privatzimmer-Beschreibung wurde geÃ¤ndert.`0`n`n".str_replace("\n","`n","".$_POST['desc3']."")."`2");
        $sql = "UPDATE houses SET privatzimmer='".$_POST['desc3']."' WHERE houseid=".$session['user']['specialmisc']['houseid'];
        db_query($sql);
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

