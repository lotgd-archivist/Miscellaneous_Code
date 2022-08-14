
<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'private_chamber' with your module's (internal) name.
function module_getinfo_private_chamber() {
    $info = array(
                    'modulename'=>'private_chamber',         // internal name; use letters, numbers and underscores only!
                    'modulefile'=>basename(__FILE__),     // filename of the module; if you allow to rename the script, do NOT change this!
                    'moduleauthor'=>'Chaosmaker',             // the author's name
                    'moduleversion'=>'1.0',            // the module's version number
                    'built_in'=>'1',                // '1', if each house should have this module built-in; otherwise '0'
                    'linkcategory'=>'Weitere Räume',            // the category (in houses.php's menu) under which the link to this module should be shown
                    'linktitle'=>'Privatgemach',            // the link title of the module
                    'showto'=>'owner,guest'                        // who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
    );
    return $info;
}

function module_install_private_chamber() {
    // insert data into module table - do NOT change this (well... just change the function name ;))!
    $info = module_getinfo_private_chamber();
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

function module_uninstall_private_chamber() {
    // uninstalling the module
    // this function should also contain all module_destroy contents

    // getting moduleid - do NOT change this (same as above... the function name should be changed)!
    $info = module_getinfo_private_chamber();
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

function module_build_private_chamber($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... blablabla)!
    $info = module_getinfo_private_chamber();
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

function module_destroy_private_chamber($houseid) {
    // this is only needed if 'built_in' in module_info() is set to 0

    // getting moduleid - do NOT change this (function name... moooooooooh!)!
    $info = module_getinfo_private_chamber();
    $moduleid = getmoduleid($info['modulename']);

    // deleting module data of this house - do NOT change this!
    $sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
    db_query($sql);

    // here you should delete all other added things (e.g. user settings) of this module and house
    /* destroy_other begin */

/* destroy_other end */
}

function module_show_private_chamber() {
    // this is the main part of the module where all output is done ;)
    // don't forget the navs; only the default module does not need them (but may add some)
    // to return to the main module, use this link: houses.php?op=drin&module (without id!)
    // don't forget 'global $session;' if you need the player's data (and you WILL need them!)

   /* content_show begin */
    global $session;

    $moduleid = module_getinfo_private_chamber();
    $moduleid = getmoduleid($moduleid['modulename']);

    $sql = db_query("SELECT * FROM houses WHERE houseid=".$session['user']['specialmisc']['houseid']."");
    $row = db_fetch_assoc($sql);
    
    //Kindersystem - Schwangerschaftsdauer
    $schwanger_dauer = getsetting("schwanger_dauer",40); //In-Game Tage
    
    if($_GET[act]==""){
        addcommentary();

        $sql = 'SELECT marriedto FROM accounts WHERE house='.$session['user']['specialmisc']['houseid'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);

        $privat = "house_private-".$session['user']['specialmisc']['houseid'];

        $sql2 = 'SELECT privatzimmer FROM houses WHERE houseid="'.$session['user']['specialmisc']['houseid'].'"';
        $result2 = db_query($sql2);
        $row2 = db_fetch_assoc($result2);
        
        $sql_priv = "SELECT owner FROM items WHERE value1=".$session['user']['specialmisc']['houseid']." AND owner=".$session['user']['acctid']." AND class='PrivEinladung'";
        $result_priv = db_query($sql_priv);
        $row_priv = db_fetch_assoc($result_priv);

        $sql_sex_check_nav = "SELECT * FROM sexwermitwem WHERE partner_a=".$session['user']['acctid']." OR partner_b=".$session['user']['acctid']."";
        $result_sex_check_nav = db_query($sql_sex_check_nav) or die(db_error(LINK));
        $row_sex_check_nav = db_fetch_assoc($result_sex_check_nav);

        // falls eigentuemer oder partner
        if($session['user']['house'] == $session['user']['specialmisc']['houseid']){
            addnav("Einladung");
            addnav("Einladung verschicken","houses.php?op=drin&act=privinvite");
            addnav("Einladung zurückziehen","houses.php?op=drin&act=privoutvite");
        }

        if($session['user']['house'] == $session['user']['specialmisc']['houseid'] OR $session['user']['acctid'] == $row['marriedto']){
            addnav("Aktionen");
            addnav("Sich Lieben","houses.php?op=drin&act=sex");
        }else if(($row_sex_check_nav['partner_a'] == $session['user']['acctid'] AND $row_sex_check_nav['partner_a_ok'] == 1) OR ($row_sex_check_nav['partner_b'] == $session['user']['acctid'] AND $row_sex_check_nav['partner_b_ok'] == 0)){
            addnav("Aktionen");
            addnav("Sich Lieben","houses.php?op=drin&act=sex");
        }else if(($row_sex_check_nav['partner_a'] == $session['user']['acctid'] AND $row_sex_check_nav['partner_a_ok'] == 0) OR ($row_sex_check_nav['partner_b'] == $session['user']['acctid'] AND $row_sex_check_nav['partner_b_ok'] == 1)){
            addnav("Aktionen");
            addnav("Sich Lieben","houses.php?op=drin&act=sex");
        }
        
        if($session['user']['house'] == $session['user']['specialmisc']['houseid'] OR 
           $session['user']['acctid'] == $row['marriedto'] OR 
           $row_priv['owner'] OR
           ($session['user']['superuser'] == 3 || $session['user']['superuser'] == 5)) {
            output($row2['privatzimmer']);

output("`c`n`n`7Privatgemach`n`n`c",true);
            
            viewcommentary("house_private-".$session['user']['specialmisc']['houseid'],($session['user']['sex']?"Deinem":"Deiner")." Liebsten zuflüstern:");

        // Möbel
        $sql = "SELECT * FROM items WHERE class='Möbel' AND value1=".$session['user']['specialmisc']['houseid']." AND value2=5 AND name!='' ORDER BY id ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $sql = "SELECT * FROM items WHERE class='Geschenk' AND value1=".$session['user']['specialmisc']['houseid']." AND value2=5 AND name!='' ORDER BY id ASC";
        $result1 = db_query($sql) or die(db_error(LINK));
        $sql = "SELECT * FROM items WHERE class='Deko' AND value1=".$session['user']['specialmisc']['houseid']." AND value2=5 AND name!='' ORDER BY id ASC";
        $result2 = db_query($sql) or die(db_error(LINK));
        
        output("`n`n`n`r`bBewohner die hier Zutritt haben:`0`b`n",true);        
        
        $sql_guest = "SELECT owner FROM items WHERE value1=".$session['user']['specialmisc']['houseid']." AND class='PrivEinladung' ORDER BY value2 ASC";
        $result_guest = db_query($sql_guest) or die(db_error(LINK));
        
        if(db_num_rows($result_guest) > 0){
            for ($i=0;$i<db_num_rows($result_guest);$i++){
                $row_guest = db_fetch_assoc($result_guest);
                
                $sql_guest2 = "SELECT name FROM accounts WHERE acctid=$row_guest[owner] ORDER BY login DESC";
                $result_guest2 = db_query($sql_guest2) or die(db_error(LINK));
                $row_guest2 = db_fetch_assoc($result_guest2);

                output($row_guest2['name']."`0`n");
            }
        } else {
            output("`i`&noch Niemand`0`i");
        }
        
        output("`n`n`n`r`bExtra Ausstattung:`0`b`n",true);
        
        if(db_num_rows($result) > 0){
            output("`n`bMöbel:`b");
            for($i=0;$i<=db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                if($item['name']) output("`n`&".$item['name']."`0 (`i".$item['description']."`0`i)",true);
            }
            output("`n");
        }else{
            $fur1 = 1;
        }
            
        if(db_num_rows($result2) > 0){
            output("`n`bDeko:`b");
            for($i=0;$i<=db_num_rows($result2);$i++){
                $item2 = db_fetch_assoc($result2);
                if($item2['name']) output("`n`&".$item2['name']."`0 (`i".$item2['description']."`0`i)",true);
            }
            output("`n");
        }else{
            $fur2 = 1;
        }
            
        if(db_num_rows($result1) > 0){
            output("`n`bGeschenke:`b");
            for($i=0;$i<=db_num_rows($result1);$i++){
                $item1 = db_fetch_assoc($result1);
                if($item1['name']) output("`n`&".$item1['name']."`0 (`i".$item1['description']."`0`i)",true);
            }
            output("`n");
        }else{
            $fur3 = 1;
        }
            
        if($fur1 == 1 AND $fur2 == 1 AND $fur3 == 1) output("`i`&noch keine`0`i");
        // Möbel Ende

        }else{ //jeder andere
            output("`ÔDu rüttelst ein wenig an der Tür - verschlossen.`n Tja, in den Privatgemächern hast du eben nichts zu suchen.`0");
        }
        addnav("Zurück");
        addnav("zum Gemeinschaftsraum","houses.php?op=drin&module=");
    
    }else if($_GET[act]=="privinvite"){
        if (!$_POST['ziel']){
            output("`.Folgende Personen haben eine Einladung in das Privatgemach:`n`n`0");
            $sql = "SELECT * FROM items WHERE value1=$row[houseid] AND class='PrivEinladung' ORDER BY value2 ASC";
            $result = db_query($sql) or die(db_error(LINK));
            for ($i=0;$i<db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                $sql = "SELECT acctid,name,login FROM accounts WHERE acctid=$item[owner] ORDER BY login DESC";
                $result2 = db_query($sql) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                if ($amt!=$row2[acctid]) {
                    output("`c`& $row2[name]`0",true);
                    if ($row2[acctid]==$row[owner]) output(" (Eigentümer)`n");
                    output("`c");
                }
                $amt=$row2[acctid];
            }
            $sql = "SELECT value2 FROM items WHERE value1=$row[houseid] AND class='PrivEinladung' AND owner=$row[owner] ORDER BY id ASC";
            $result = db_query($sql) or die(db_error(LINK));
       
            $sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='Schlüssel' ORDER BY value2 ASC";
            $result = db_query($sql) or die(db_error(LINK));

            output("<form action='houses.php?op=drin&act=privinvite' method='POST'>",true);
            output("`.Wen willst du einladen?`0 <select name='ziel'>",true);

            for ($i=0;$i<db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                
                //if($session['user']['charisma'] == 4294967295){
                    //$sql = "SELECT acctid,name,login FROM accounts WHERE acctid=".$item['owner']." AND acctid!=".$session['user']['marriedto']." ORDER BY login DESC";
                //}else{
                    $sql = "SELECT acctid,name,login FROM accounts WHERE acctid=".$item['owner']." ORDER BY login DESC";
                //}
                $result2 = db_query($sql) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                
                $sql_c = "SELECT owner FROM items WHERE value1=".$row['houseid']." AND class='PrivEinladung' AND owner=".$item['owner']."";
                $result_c = db_query($sql_c) or die(db_error(LINK));
                $item_c = db_fetch_assoc($result_c);

                if ($amt!=$row2['acctid'] AND $row2['acctid']!=$row['owner'] AND $row2['acctid']!=0 AND $row2['acctid']!=$item_c['owner']) output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
                $amt = $row2['acctid'];
            }

            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Einladen'></form>",true);
            addnav("","houses.php?op=drin&act=privinvite");
        }else{
            if($_GET['subfinal']==1){
                $sql = "SELECT acctid,name,login,lastip,uniqueid,emailaddress FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";
            }else{
                $ziel = stripslashes(rawurldecode($_POST['ziel']));
                $name="%";
                for ($x=0;$x<strlen($ziel);$x++){
                    $name.=substr($ziel,$x,1)."%";
                }
                $sql = "SELECT acctid,name,login,lastip,uniqueid,emailaddress FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";
            }

            $result2 = db_query($sql);
            if(db_num_rows($result2) == 0){
                output("`2Es gibt niemanden mit einem solchen Namen. Versuchs nochmal.");
            }elseif(db_num_rows($result2) > 100){
                output("`2Es gibt über 100 Krieger mit einem ähnlichen Namen. Bitte sei etwas genauer.");
            }elseif(db_num_rows($result2) > 1){
                output("`2Es gibt mehrere mögliche Krieger, denen du einen Schlüssel übergeben kannst.`n");
                output("<form action='houses.php?op=drin&act=privinvite&subfinal=1' method='POST'>",true);
                output("`2Wen genau meinst du? <select name='ziel'>",true);
                for ($i=0;$i<db_num_rows($result2);$i++){
                    $row2 = db_fetch_assoc($result2);
                    output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
                }

                output("</select>`n`n",true);
                output("<input type='submit' class='button' value='PrivEinladen'></form>",true);
                addnav("","houses.php?op=drin&act=privinvite&subfinal=1");
            }else{
                $row2  = db_fetch_assoc($result2);
                $sql = "SELECT owner FROM items WHERE owner=$row2[acctid] AND value1=$row[houseid] AND (class='PrivEinladung') ORDER BY id ASC";
                $result = db_query($sql) or die(db_error(LINK));
                $item = db_fetch_assoc($result);
                if ($row2['login'] == $session[user][login]){
                    output("`2Du kannst dir nicht selbst einen Einladung schicken.");
                }elseif($item['owner']==$row2[acctid]){
                   output("`2$row2[name]`2 hat bereits eine Einladung!");
                //}elseif($session['user']['uniqueid'] == $row2['uniqueid'] || ($session['user']['emailaddress'] == $row2['emailaddress'] && $row2['emailaddress'])){
                //  output("`2Deine Charaktere dürfen leider nicht miteinander interagieren!");
                }else{
                    $sql = "SELECT value2 FROM items WHERE value1=$row[houseid] AND class='Schlüssel' AND owner=$row[owner] ORDER BY id ASC";
                    $result = db_query($sql) or die(db_error(LINK));
                    $knr = db_fetch_assoc($result);
                    $knr = $knr['value2'];
                    output("`2Du schickst `&$row2[name]`2 eine Einladung.`n");
                    systemmail($row2['acctid'],"`@Einladung erhalten!`0","`&{$session['user']['name']}`2 hat dir eine Einladung in das Privatgemach vom Haus Nummer `b$row[houseid]`b ($row[housename]`2) gegeben!");
                    $sql = "INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ('PrivEinladung',".$row2['acctid'].",'PrivEinladung',$row[houseid],1,0,0,'Einladung in das Privatgemach im Haus Nummer $row[houseid]')";
                    db_query($sql) or die(db_error(LINK));
                    $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house_tresor-".$session['user']['specialmisc']['houseid']."',".$session['user']['acctid'].",'".$session['user']['name']."`0 läd ".$row2['name']." `0einen.')";
                    db_query($sql) or die(db_error(LINK));
                }
            }
        }
        addnav("Zurück");
        addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");
     }else if ($_GET[act]=='privoutvite'){
        if (!$_POST[ziel]){
            $sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='PrivEinladung' ORDER BY value2 ASC";
            $result = db_query($sql) or die(db_error(LINK));

            output("<form action='houses.php?op=drin&act=privoutvite' method='POST'>",true);
            output("`.Wem willst du die Einladung entziehen?`0 <select name='ziel'>",true);

            for ($i=0;$i<db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                $sql = "SELECT acctid,name,login FROM accounts WHERE acctid=$item[owner] ORDER BY login DESC";
                $result2 = db_query($sql) or die(db_error(LINK));
                $row2 = db_fetch_assoc($result2);
                if ($amt!=$row2['acctid'] && $row2['acctid']!=$row['owner']) output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
                $amt = $row2['acctid'];
            }

            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Einladung zurückziehen'></form>",true);
            addnav("","houses.php?op=drin&act=privoutvite");
        }else{
            $sql = "SELECT acctid,name,login,gold,gems FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";
            $result2 = db_query($sql);
            $row2  = db_fetch_assoc($result2);
            output("`2Du ziehst die Einladung an `&$row2[name]`2 zurück.`n");

            systemmail($row2['acctid'],"`@Einladung zurückgezogen!`0","`&{$session['user']['name']}`2 hat seine Einladung für das Privatgemach im Haus Nummer `b$row[houseid]`b ($row[housename]`2) zurückgezogen.");

            $sql = "DELETE FROM items WHERE owner=$row2[acctid] AND class='PrivEinladung' AND value1=$row[houseid]";
            db_query($sql);

            $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house_tresor-".$session['user']['specialmisc']['houseid']."',".$session['user']['acctid'].",'".$session['user']['name']."`0 zieht die Einladug an ".$row2['name']." `0zurück.')";
            db_query($sql) or die(db_error(LINK));

        }
        addnav("Zurück");
        addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");

    }else if($_GET[act] == "sex"){
        addnav("Aktualisieren","houses.php?op=drin&act=sex");
        addnav("Zurück");
        addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");

        output("`c`)Mit wem möchtest du eine Liebesnacht verbringen?`0`c`n`n",true);
        
        output("<table border='0' align='center' cellspacing='2' cellpadding='2' bgcolor='#444455'>",true);
        
        $sql_sex_check = "SELECT * FROM sexwermitwem WHERE partner_a=".$session['user']['acctid']." OR partner_b=".$session['user']['acctid']."";
        $result_sex_check = db_query($sql_sex_check) or die(db_error(LINK));
        if(db_num_rows($result_sex_check) > 0){
            $row_sex_check = db_fetch_assoc($result_sex_check);
            
            if($row_sex_check['partner_a'] == $session['user']['acctid']){
                $sex_checke_partner = $row_sex_check['partner_b'];
            }else{
                $sex_checke_partner = $row_sex_check['partner_a'];
            }
            
            if($row_sex_check['type'] == 0){
                $sex_info = "`i(geschützt)`ì";
            }else{
                $sex_info = "`i(ungeschützt)`ì";
            }
        }

        if($session['user']['marriedto'] > 0 AND $session['user']['charisma'] == 4294967295){
            $sql_ehepartner = "SELECT acctid,name FROM accounts WHERE acctid=".$session['user']['marriedto']."";
            $result_ehepartner = db_query($sql_ehepartner) or die(db_error(LINK));
            $row_ehepartner = db_fetch_assoc($result_ehepartner);
            
            output("<tr class='trhead'><td colspan='2'>`c`bDein Ehepartner:`b`c</td><tr>",true);
            output("<tr class='trdark'><td>".$row_ehepartner['name']."</td>",true);
            
            # Sex verheiratet
            
            if($row_sex_check['partner_a_ok'] == 1 OR $row_sex_check['partner_b_ok'] == 1){
                if($row_sex_check['partner_a'] == $session['user']['acctid'] AND $row_sex_check['partner_a_ok'] == 1){
                    output("<td>`c`i`&Du wartest noch auf deinen Partner`i`n".$sex_info."`c`n`c<a href='houses.php?op=drin&act=abort&partner=".$row_sex_check['partner_b']."' onClick=\"return confirm('Willst du die Anfrage wirklich zurückziehen?');\">`4lieber doch nicht`0</a>`c</td></tr>",true);
                    addnav("","houses.php?op=drin&act=abort&partner=".$row_sex_check['partner_b']."");
                }else if($row_sex_check['partner_b'] == $session['user']['acctid'] AND $row_sex_check['partner_b_ok'] == 1){
                    output("<td>`c`i`&Du wartest noch auf deinen Partner`i`n".$sex_info."`c`n`c<a href='houses.php?op=drin&act=abort&partner=".$row_sex_check['partner_a']."' onClick=\"return confirm('Willst du die Anfrage wirklich zurückziehen?');\">`4lieber doch nicht`0</a>`c</td></tr>",true);
                    addnav("","houses.php?op=drin&act=abort&partner=".$row_sex_check['partner_a']."");
                }else{
                    if($row_sex_check['type'] == 0){
                        output("<td>(geschützter Sex) [<a href='houses.php?op=drin&act=safe&partner=".$sex_checke_partner."'>`2Annehmen`0</a>] || [<a href='houses.php?op=drin&act=no&partner=".$sex_checke_partner."'>`4Ablehnen`0</a>]</td></tr>",true);
                        
                        addnav("","houses.php?op=drin&act=safe&partner=".$sex_checke_partner."");
                    }else{
                        output("<td>(ungeschützter Sex) [<a href='houses.php?op=drin&act=kind&partner=".$sex_checke_partner."'>`2Annehmen`0</a>] || [<a href='houses.php?op=drin&act=no&partner=".$sex_checke_partner."'>`4Ablehnen`0</a>]</td></tr>",true);
                        
                        addnav("","houses.php?op=drin&act=kind&partner=".$sex_checke_partner."");
                    }
                    addnav("","houses.php?op=drin&act=no&partner=".$sex_checke_partner."");
                }
            }else{
                output("<td>`c[<a href='houses.php?op=drin&act=doit&type=0&partner=".$row_ehepartner['acctid']."'>Geschützt</a>] || [<a href='houses.php?op=drin&act=doit&type=1&partner=".$row_ehepartner['acctid']."'>Ungeschützt</a>]`c</td></tr>",true);
                
                addnav("","houses.php?op=drin&act=doit&type=0&partner=".$row_ehepartner['acctid']."");
                addnav("","houses.php?op=drin&act=doit&type=1&partner=".$row_ehepartner['acctid']."");
            }
        }
        
        if($session['user']['house'] == $session['user']['specialmisc']['houseid']){
            output("<tr class='trhead'><td colspan='2'>`c`bMögliche Partner:`b`c</td><tr>",true);
            output("<tr class='trhead'><td>`c`bName`b`c</td><td>`c`bSich lieben`b`c</td></tr>",true);

            $sql = "SELECT owner FROM items WHERE value1=$row[houseid] AND class='PrivEinladung' AND owner!=".$session['user']['acctid']." AND owner>0 ORDER BY value2 ASC";
            $result = db_query($sql) or die(db_error(LINK));
            
            if(db_num_rows($result) == 0) {
                output("<tr class='trdark'><td colspan='2' align='center'>`&`ikeine Partner vorhanden`i`0</td></tr>",true);
            }else{
                for($i=0;$i<db_num_rows($result);$i++){
                    $privinv = db_fetch_assoc($result);
                    
                    $sql_partner = "SELECT acctid,name FROM accounts WHERE acctid=".$privinv['owner']." AND acctid!=".$session['user']['acctid']."";
                    $result_partner = db_query($sql_partner) or die(db_error(LINK));
                    $row_partner = db_fetch_assoc($result_partner);
                    $bgcolor = ($i%2==1?"trlight":"trdark");
                    
                    $sql_sex_check2 = "SELECT * FROM sexwermitwem WHERE (partner_a=".$session['user']['acctid']." OR partner_a=".$row_partner['acctid'].") AND (partner_b=".$session['user']['acctid']." OR partner_b=".$row_partner['acctid'].")";
                    $result_sex_check2 = db_query($sql_sex_check2) or die(db_error(LINK));
                    if(db_num_rows($result_sex_check2) > 0){
                        $row_sex_check2 = db_fetch_assoc($result_sex_check2);
                        
                        if($row_sex_check2['partner_a'] == $session['user']['acctid']){
                            $sex_checke_partner2 = $row_sex_check2['partner_b'];
                        }else{
                            $sex_checke_partner2 = $row_sex_check2['partner_a'];
                        }
                        
                        if($row_sex_check2['type'] == 0){
                            $sex_info = "`i(geschützt)`ì";
                        }else{
                            $sex_info = "`i(ungeschützt)`ì";
                        }
                    }

                    output("<tr class='$bgcolor'><td>".$row_partner['name']."</td>",true);
                    
                    # Sex im eigenen Haus
                    
                    if($row_sex_check2['partner_a_ok'] == 1 OR $row_sex_check2['partner_b_ok'] == 1){
                        if($row_sex_check2['partner_a'] == $session['user']['acctid'] AND $row_sex_check2['partner_a_ok'] == 1){
                            output("<td>`c`i`&Du wartest noch auf deinen Partner`i`n".$sex_info."`c`n`c<a href='houses.php?op=drin&act=abort&partner=".$row_sex_check2['partner_b']."' onClick=\"return confirm('Willst du die Anfrage wirklich zurückziehen?');\">`4lieber doch nicht`0</a>`c</td></tr>",true);
                            addnav("","houses.php?op=drin&act=abort&partner=".$row_sex_check2['partner_b']."");
                        }else if($row_sex_check2['partner_b'] == $session['user']['acctid'] AND $row_sex_check2['partner_b_ok'] == 1){
                            output("<td>`c`i`&Du wartest noch auf deinen Partner`i`n".$sex_info."`c`n`c<a href='houses.php?op=drin&act=abort&partner=".$row_sex_check2['partner_a']."' onClick=\"return confirm('Willst du die Anfrage wirklich zurückziehen?');\">`4lieber doch nicht`0</a>`c</td></tr>",true);
                            addnav("","houses.php?op=drin&act=abort&partner=".$row_sex_check2['partner_a']."");
                        }else{
                            if($row_sex_check['type'] == 0){
                                output("<td>(geschützter Sex) [<a href='houses.php?op=drin&act=safe&partner=".$sex_checke_partner2."'>`2Annehmen`0</a>] || [<a href='houses.php?op=drin&act=no&partner=".$sex_checke_partner2."'>`4Ablehnen`0</a>]</td></tr>",true);
                                
                                addnav("","houses.php?op=drin&act=safe&partner=".$sex_checke_partner2."");
                            }else{
                                output("<td>(ungeschützter Sex) [<a href='houses.php?op=drin&act=kind&partner=".$sex_checke_partner2."'>`2Annehmen`0</a>] || [<a href='houses.php?op=drin&act=no&partner=".$sex_checke_partner2."'>`4Ablehnen`0</a>]</td></tr>",true);
                                
                                addnav("","houses.php?op=drin&act=kind&partner=".$sex_checke_partner2."");
                            }
                            addnav("","houses.php?op=drin&act=no&partner=".$sex_checke_partner2."");
                        }
                    }else{
                        output("<td>`c[<a href='houses.php?op=drin&act=doit&type=0&partner=".$row_partner['acctid']."'>Geschützt</a>] || [<a href='houses.php?op=drin&act=doit&type=1&partner=".$row_partner['acctid']."'>Ungeschützt</a>]`c</td></tr>",true);
                        
                        addnav("","houses.php?op=drin&act=doit&type=0&partner=".$row_partner['acctid']."");
                        addnav("","houses.php?op=drin&act=doit&type=1&partner=".$row_partner['acctid']."");
                    }
                }
            }
        }
        
        if($session['user']['house'] != $session['user']['specialmisc']['houseid']){
            output("<tr class='trhead'><td colspan='2'>`c`bMögliche Partner:`b`c</td><tr>",true);
            output("<tr class='trhead'><td>`c`bName`b`c</td><td>`c`bSich lieben`b`c</td></tr>",true);
            
            $sql_partner2 = "SELECT acctid,name FROM accounts WHERE house=".$session['user']['specialmisc']['houseid']."";
            $result_partner2 = db_query($sql_partner2) or die(db_error(LINK));
            $row_partner2 = db_fetch_assoc($result_partner2);
            $bgcolor = ($i%2==1?"trlight":"trdark");
            
            $sql_sex_check3 = "SELECT * FROM sexwermitwem WHERE (partner_a=".$session['user']['acctid']." OR partner_a=".$row_partner2['acctid'].") AND (partner_b=".$session['user']['acctid']." OR partner_b=".$row_partner2['acctid'].")";
            $result_sex_check3 = db_query($sql_sex_check3) or die(db_error(LINK));
            if(db_num_rows($result_sex_check3) > 0){
                $row_sex_check3 = db_fetch_assoc($result_sex_check3);
                        
                if($row_sex_check3['partner_a'] == $session['user']['acctid']){
                    $sex_checke_partner3 = $row_sex_check3['partner_b'];
                }else{
                    $sex_checke_partner3 = $row_sex_check3['partner_a'];
                }
                
                if($row_sex_check3['type'] == 0){
                    $sex_info = "`i(geschützt)`ì";
                }else{
                    $sex_info = "`i(ungeschützt)`ì";
                }
            }

            output("<tr class='$bgcolor'><td>".$row_partner2['name']."</td>",true);
            
            # Sex in einem fremden Haus
            
            if($row_sex_check3['partner_a_ok'] == 1 OR $row_sex_check3['partner_b_ok'] == 1){
                if($row_sex_check3['partner_a'] == $session['user']['acctid'] AND $row_sex_check3['partner_a_ok'] == 1){
                    output("<td>`c`i`&Du wartest noch auf deinen Partner`i`n".$sex_info."`c`n`c<a href='houses.php?op=drin&act=abort&partner=".$row_sex_check3['partner_b']."' onClick=\"return confirm('Willst du die Anfrage wirklich zurückziehen?');\">`4lieber doch nicht`0</a>`c</td></tr>",true);
                    addnav("","houses.php?op=drin&act=abort&partner=".$row_sex_check3['partner_b']."");
                }else if($row_sex_check3['partner_b'] == $session['user']['acctid'] AND $row_sex_check3['partner_b_ok'] == 1){
                    output("<td>`c`i`&Du wartest noch auf deinen Partner`i`n".$sex_info."`c`n`c<a href='houses.php?op=drin&act=abort&partner=".$row_sex_check3['partner_a']."' onClick=\"return confirm('Willst du die Anfrage wirklich zurückziehen?');\">`4lieber doch nicht`0</a>`c</td></tr>",true);
                    addnav("","houses.php?op=drin&act=abort&partner=".$row_sex_check3['partner_a']."");
                }else{
                    if($row_sex_check3['type'] == 0){
                        output("<td>(geschützter Sex) [<a href='houses.php?op=drin&act=safe&partner=".$row_partner2['acctid']."'>`2Annehmen`0</a>] || [<a href='houses.php?op=drin&act=no&partner=".$row_partner2['acctid']."'>`4Ablehnen`0</a>]</td></tr>",true);
                        addnav("","houses.php?op=drin&act=safe&partner=".$row_partner2['acctid']."");
                    }else{
                        output("<td>(ungeschützter Sex) [<a href='houses.php?op=drin&act=kind&partner=".$row_partner2['acctid']."'>`2Annehmen`0</a>] || [<a href='houses.php?op=drin&act=no&partner=".$row_partner2['acctid']."'>`4Ablehnen`0</a>]</td></tr>",true);
                        addnav("","houses.php?op=drin&act=kind&partner=".$row_partner2['acctid']."");
                    }
                    addnav("","houses.php?op=drin&act=no&partner=".$row_partner2['acctid']."");
                }
            }else{
                output("<td>`c[<a href='houses.php?op=drin&act=doit&type=0&partner=".$row_partner2['acctid']."'>Geschützt</a>] || [<a href='houses.php?op=drin&act=doit&type=1&partner=".$row_partner2['acctid']."'>Ungeschützt</a>]`c</td></tr>",true);
                    
                addnav("","houses.php?op=drin&act=doit&type=0&partner=".$row_partner2['acctid']."");
                addnav("","houses.php?op=drin&act=doit&type=1&partner=".$row_partner2['acctid']."");
            }
        }
        output("</table>",true);
    
    }else if($_GET[act] == "doit"){
        $sql_partner = "SELECT acctid,name,sex,biodata FROM accounts WHERE acctid=".$_GET['partner'];
        $result_partner = db_query($sql_partner) or die(db_error(LINK));
        $row_partner = db_fetch_assoc($result_partner);
        
        if($row_partner['acctid'] < $session['user']['acctid']){
            $partner_a = $row_partner['acctid'];
            $partner_b = $session['user']['acctid'];
            
            $checking = $partner_a."<->".$partner_b;
            
            $sql_present_check = "SELECT name FROM sexwermitwem WHERE name='".$checking."'";
            $result_present_check = db_query($sql_present_check) or die(db_error(LINK));
                        
            if(db_num_rows($result_present_check) == 0){
                $sql_sex_promt = "INSERT INTO sexwermitwem (name,partner_a, partner_b, partner_b_ok, type) VALUE ('".$checking."',".$partner_a.",".$partner_b.",1,".$_GET['type'].")";
                db_query($sql_sex_promt) or die(db_error(LINK));
            }else{
                $sql_sex_promt = "UPDATE sexwermitwem SET partner_b_ok=1, type=".$_GET['type']." WHERE partner_a=".$partner_a." AND partner_b=".$partner_b." AND name='".$checking."'";
                db_query($sql_sex_promt) or die(db_error(LINK));
            }
            systemmail($row_partner['acctid'],"`2Ein paar schöne Stunden?`0","`&{$session['user']['name']}`2 möchte mit dir ein paar schöne Stunden im Haus (Nummer: ".$row['houseid']." ($row[housename]`2) verbringen. Hast du nicht auch Lust?`0");
        }else{
            $partner_a = $session['user']['acctid'];
            $partner_b = $row_partner['acctid'];
            
            $checking = $partner_a."<->".$partner_b;

            $sql_present_check = "SELECT name FROM sexwermitwem WHERE name='".$checking."'";
            $result_present_check = db_query($sql_present_check) or die(db_error(LINK));
                    
            if(db_num_rows($result_present_check) == 0){
                $sql_sex_promt = "INSERT INTO sexwermitwem (name,partner_a, partner_b, partner_a_ok, type) VALUE ('".$checking."',".$partner_a.",".$partner_b.",1,".$_GET['type'].")";
                db_query($sql_sex_promt) or die(db_error(LINK));
            }else{
                $sql_sex_promt = "UPDATE sexwermitwem SET partner_a_ok=1, type=".$_GET['type']." WHERE partner_a=".$partner_a." AND partner_b=".$partner_b." AND name='".$checking."'";
                db_query($sql_sex_promt) or die(db_error(LINK));
            }    
            systemmail($row_partner['acctid'],"`2Ein paar schöne Stunden?`0","`&{$session['user']['name']}`2 möchte mit dir ein paar schöne Stunden im Haus (Nummer: ".$row['houseid']." ($row[housename]`2) verbringen. Hast du nicht auch Lust?`0");
        }
        output("`qDu möchtest mit `&".$row_partner['name']."`q ein paar schöne Stunden verbringen und machst mehr als eindeutige Angebote. Ob `&".$row_partner['name']."`q darauf eingehen wird?");
        
        addnav("Zurück");
        addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");

    }else if($_GET[act] == "safe"){
        $sql_partner = "SELECT acctid,name,sex,biodata FROM accounts WHERE acctid=".$_GET['partner'];
        $result_partner = db_query($sql_partner) or die(db_error(LINK));
        $row_partner = db_fetch_assoc($result_partner);
        
        if($row_partner['acctid'] < $session['user']['acctid']){
            $partner_a = $row_partner['acctid'];
            $partner_b = $session['user']['acctid'];
        }else{
            $partner_a = $session['user']['acctid'];
            $partner_b = $row_partner['acctid'];
        }
        
        if($row_partner['sex'] == 0){
            $text = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
        }else if($row_partner['sex'] == 1){
            $text = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
        }
        output("`qAls `0".$row_partner['name']." ".$text);
        
        if($row_partner['sex'] == 1){
            $text_mail = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
        }else if($row_partner['sex'] == 0){
            $text_mail = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
        }
        systemmail($row_partner['acctid'],"`2Ein aufregendes Abenteuer.`0","`qAls `0".$session['user']['name']." ".$text_mail);

        $sql_sex = "UPDATE sexwermitwem SET sexzahl = sexzahl + 1, partner_a_ok=0, partner_b_ok=0  WHERE partner_a=".$partner_a." AND partner_b=".$partner_b."";
        db_query($sql_sex) or die(db_error(LINK));

        addnav("Zurück");
        addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");

    }else if($_GET[act] == "kind"){
        // Partner aus der DB holen
        $sql_partner = "SELECT acctid,name,sex,biodata FROM accounts WHERE acctid=".$_GET['partner'];
        $result_partner = db_query($sql_partner) or die(db_error(LINK));
        $row_partner = db_fetch_assoc($result_partner);
        
        
        if($row_partner['acctid'] < $session['user']['acctid']){
            $partner_a = $row_partner['acctid'];
            $partner_b = $session['user']['acctid'];
        }else{
            $partner_a = $session['user']['acctid'];
            $partner_b = $row_partner['acctid'];
        }
        
        $sql_sex = "UPDATE sexwermitwem SET sexzahl = sexzahl + 1, partner_a_ok=0, partner_b_ok=0  WHERE partner_a=".$partner_a." AND partner_b=".$partner_b."";
        db_query($sql_sex) or die(db_error(LINK));
        
        if($session['user']['sex'] != $row_partner['sex']){
            $kindokay = true;
        }else{
            $kindokay = false;
        }
        
        if($kindokay){
            $sql_sex = "SELECT * FROM sexwermitwem WHERE partner_a=".$partner_a." AND partner_b=".$partner_b."";
            $result_sex = db_query($sql_sex) or die(db_error(LINK));
            if($row_sex = db_fetch_assoc($result_sex)){
                $sql_sex = "UPDATE sexwermitwem SET sexzahl = sexzahl + 1 WHERE partner_a=".$partner_a." AND partner_b=".$partner_b."";
            }else{
                $sql_sex = "INSERT INTO sexwermitwem VALUES($partner_a, $partner_b, 1)";
            }
            db_query($sql_sex) or die(db_error(LINK));

            $data1 = $session['user']['biodata'];
            $data2 = $row_partner['biodata'];
            $empf = round((($data1+$data2)/2));
                
            $new = e_rand(1,100);
            if($new > $empf){
                $schwanger = false;
            }else{
                if($kindokay){
                    $schwanger = true;
                }else{
                    $schwanger = false;
                }
            }
            
            if($schwanger){
                switch(e_rand(1,20)){
                    case 1:
                    case 2:
                    case 3:
                    case 5:
                    case 6:
                    case 7:
                    case 8:
                    case 9:
                    case 10:
                    case 11:
                    case 12:
                    case 13:
                    case 14:
                    case 15:
                    case 16:
                    case 17:
                    case 18:
                    case 19:
                        $kind = 1;
                    break;
                    
                    case 20:
                        $kind = 2;
                    break;
                }
                
                if($session['user']['sex']){
                    if($session['user']['smonat'] == 0){
                        $session['user']['sstatus'] = $kind;
                        $session['user']['serzeug'] = $row_partner['acctid'];
                        $session['user']['smonat'] = $schwanger_dauer;
                    }
                 }else{
                    if($row_partner['smonat'] == 0){
                        $sql_empf = "UPDATE accounts SET sstatus=".$kind.",serzeug=".$session['user']['acctid'].",smonat=".$schwanger_dauer." WHERE acctid=".$row_partner['acctid']."";
                        db_query($sql_empf) or die(db_error(LINK));
                    }
                }
                
                
                if($session['user']['sex'] == 0)
                {
                    $text = "`q Als ".$row_partner['name']." eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`n
                             Ihr bleibt schmusend noch ein wenig liegen. Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein und du hast den Eindruck, dass ".$row_partner['name']."`0 schwanger sein könnte ....`0";
                }
                else
                {
                    $text = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`n
                             Ihr bleibt schmusend noch ein wenig liegen.  Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein und dir dämmert, dass du etwas unterm Herzen tragen könntest .... `0";
                }
                output("`qAls `0".$row_partner['name']." ".$text);
                
                
                if($session['user']['sex'] == 0)
                {
                    $text_mail = "`q Als ".$row_parter['name']." eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`n
                                  Ihr bleibt schmusend noch ein wenig liegen. Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein und du hast den Eindruck, dass du etwas unterm Herzen tragen könntest .... `0";
                }
                else
                {
                    $text_mail = "`qAls du eigentlich nur kurz in eurem Schlafgemach vorbeischaust, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`n
                                  Ihr bleibt schmusend noch ein wenig liegen. Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein und du hast den Eindruck, dass ".$session['user']['name']."`0 schwanger sein könnte ....`0";
                }
                
                systemmail($row_partner['acctid'],"`2Ein aufregendes Abenteuer.`0",$text_mail);
            }else{
                if($row_partner['sex'] == 0){
                    $text = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen. Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein .... `0";
                }else if($row_partner['sex'] == 1){
                    $text = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen. Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein ....`0";
                }
                output("`qAls `0".$row_partner['name']." ".$text);
            
                if($row_partner['sex'] == 1){
                    $text_mail = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen. Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein .... `0";
                }else if($row_partner['sex'] == 0){
                    $text_mail = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen. Doch irgendwie habt ihr das Gefühl, es könnte etwas geschehen sein ....`0";
                }
                
                systemmail($row_partner['acctid'],"`2Ein aufregendes Abenteuer.`0","`qAls `0".$session['user']['name']." ".$text_mail);
            }
        }else{
            if($row_partner['sex'] == 0){
                $text = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
            }else if($row_partner['sex'] == 1){
                $text = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
            }
            output("`qAls `0".$row_partner['name']." ".$text);

            if($row_partner['sex'] == 1){
                $text_mail = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du ihn ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
            }else if($row_partner['sex'] == 0){
                $text_mail = "`q eigentlich nur kurz in euren Schlafgemächern vorbei schaut, ziehst Du sie ins Bett und ihr liebt euch den ganzen restlichen Tag heiß und innig.`nIhr bleibt schmusend noch ein wenig liegen ...`0";
            }
            
            systemmail($row_partner['acctid'],"`2Ein aufregendes Abenteuer.`0","`qAls `0".$session['user']['name']." ".$text_mail);
        }
            
    addnav("Zurück");
    addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");

    }else if($_GET[act] == "no"){
        $sql_partner = "SELECT acctid,name,sex,biodata FROM accounts WHERE acctid=".$_GET['partner'];
        $result_partner = db_query($sql_partner) or die(db_error(LINK));
        $row_partner = db_fetch_assoc($result_partner);

        if($row_partner['acctid'] < $session['user']['acctid']){
            $partner_a = $row_partner['acctid'];
            $partner_b = $session['user']['acctid'];
        }else{
            $partner_a = $session['user']['acctid'];
            $partner_b = $row_partner['acctid'];
        }

        $sql_sex = "UPDATE sexwermitwem SET partner_a_ok=0, partner_b_ok=0  WHERE partner_a=".$partner_a." AND partner_b=".$partner_b."";
        db_query($sql_sex) or die(db_error(LINK));
            
        if($row_partner['acctid'] < $session['user']['acctid']){
            systemmail($row_partner['acctid'],"`4Keine schönen Stunden.`0","`&{$session['user']['name']}`4 hat dein Angebot, mit dir ein paar schöne Stunden im Haus verbringen, abgelehnt.`0");
        }else{
            systemmail($row_partner['acctid'],"`4Keine schönen Stunden.`0","`&{$session['user']['name']}`4 hat dein Angebot, mit dir ein paar schöne Stunden im Haus verbringen, abgelehnt.`0");
        }
            
        output("`4Du hast einfach keine Lust jetzt.`0");
            
    addnav("Zurück");
    addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");
    
    }else if($_GET[act] == "abort"){
        $sql_partner = "SELECT acctid,name,sex,biodata FROM accounts WHERE acctid=".$_GET['partner'];
        $result_partner = db_query($sql_partner) or die(db_error(LINK));
        $row_partner = db_fetch_assoc($result_partner);
        
        if($row_partner['acctid'] < $session['user']['acctid']){
            $partner_a = $row_partner['acctid'];
            $partner_b = $session['user']['acctid'];
        }else{
            $partner_a = $session['user']['acctid'];
            $partner_b = $row_partner['acctid'];
        }

        $sql_sex = "UPDATE sexwermitwem SET partner_a_ok=0, partner_b_ok=0  WHERE partner_a=".$partner_a." AND partner_b=".$partner_b."";
        db_query($sql_sex) or die(db_error(LINK));
            
        if($row_partner['acctid'] < $session['user']['acctid']){
            systemmail($row_partner['acctid'],"`4Doch keine schönen Stunden.`0","`&{$session['user']['name']}`4 hat sein Anfrage, mit dir ein paar schöne Stunden im Haus verbringen, zurückgenommen.`0");
        }else{
            systemmail($row_partner['acctid'],"`4Doch keine schönen Stunden.`0","`&{$session['user']['name']}`4 hat sein Anfrage, mit dir ein paar schöne Stunden im Haus verbringen, zurückgenommen.`0");
        }
            
        //output("`4Du hast einfach keine Lust jetzt.`0");
        
        redirect("houses.php?op=drin&act=sex");
    //addnav("Zurück");
    //addnav("zum Privatgemach","houses.php?op=drin&module=".$moduleid."");
    }
    
    // uncomment these lines if you want to show the default navs even if this is not the default module
    // global $shownavs;
    // $shownavs = true;

    // uncomment these lines if you want to hide the default navs even if this is the default module
    // global $shownavs;
    // $shownavs = false;
/* content_show end */

}
?>

