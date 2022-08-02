<?php
// default module template
// use this to create modules for houses
// Author: Chaosmaker <webmaster@chaosonline.de>

// all function names MUST end with the module's name (as in $info['modulename'])!!!
// hint: use search&replace ;) replace 'Lesezimmer' with your module's (internal) name.
function module_getinfo_Lesezimmer() {
	$info = array(
					'modulename'=>'Lesezimmer', 		// internal name; use letters, numbers and underscores only!
					'modulefile'=>basename(__FILE__), 	// filename of the module; if you allow to rename the script, do NOT change this!
					'moduleauthor'=>'Eliwood', 			// the author's name
					'moduleversion'=>'1.1',			// the module's version number
					'built_in'=>'1',				// '1', if each house should have this module built-in; otherwise '0'
					'linkcategory'=>'Zimmer',			// the category (in houses.php's menu) under which the link to this module should be shown
					'linktitle'=>'Lesezimmer',			// the link title of the module
					'showto'=>'owner,guest'						// who should use this module? possible options: 'owner', 'guest' and 'owner,guest'
	);
	return $info;
}

function module_install_Lesezimmer() {
	// insert data into module table - do NOT change this (well... just change the function name ;))!
	$info = module_getinfo_Lesezimmer();
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

  $sql = " CREATE TABLE IF NOT EXISTS `houses_library` (
             `bookid` INT( 11 ) NOT NULL AUTO_INCREMENT ,
             `title` VARCHAR( 100 ) NOT NULL ,
             `body` LONGBLOB NOT NULL ,
             `author` INT( 11 ) NOT NULL ,
             `houseid` INT( 11 ) NOT NULL ,
             PRIMARY KEY ( `bookid` )
           );";
  $sql = trim($sql);
  db_query($sql);
  
/* install_other end */
}

function module_uninstall_Lesezimmer() {
	// uninstalling the module
	// this function should also contain all module_destroy contents

	// getting moduleid - do NOT change this (same as above... the function name should be changed)!
	$info = module_getinfo_Lesezimmer();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module from db - do NOT change this!
	$sql = 'DELETE FROM housemodules WHERE moduleid='.$moduleid;
	db_query($sql);

	// deleting internal module data - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid;
	db_query($sql);

	// here you should delete all other added things (e.g. settings) of this module
	/* delete_other begin */
  $sql = "DROP TABLE `houses_library`";
  db_query($sql);
/* delete_other end */
}

function module_build_Lesezimmer($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... blablabla)!
	$info = module_getinfo_Lesezimmer();
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

function module_destroy_Lesezimmer($houseid) {
	// this is only needed if 'built_in' in module_info() is set to 0

	// getting moduleid - do NOT change this (function name... moooooooooh!)!
	$info = module_getinfo_Lesezimmer();
	$moduleid = getmoduleid($info['modulename']);

	// deleting module data of this house - do NOT change this!
	$sql = 'DELETE FROM housemoduledata WHERE moduleid='.$moduleid.' AND houseid='.$houseid;
	db_query($sql);

	// here you should delete all other added things (e.g. user settings) of this module and house
	/* destroy_other begin */

/* destroy_other end */
}

function module_show_Lesezimmer() {
	// this is the main part of the module where all output is done ;)
	// don't forget the navs; only the default module does not need them (but may add some)
	// to return to the main module, use this link: houses.php?op=drin&module (without id!)
	// don't forget 'global $session;' if you need the player's data (and you WILL need them!)
   
  /* content_show begin */
  global $session, $shownavs;
  
  /* settings */
  $shownavs = false;
  $book_limit = 30;
  $info = module_getinfo_Lesezimmer();
	$moduleid = getmoduleid($info['modulename']);
	$houseid = $session['user']['specialmisc']['houseid'];
	
	$sql = 'SELECT owner FROM houses WHERE houseid='.$session['user']['specialmisc']['houseid'];
	$result = db_query($sql);
	$row = db_fetch_assoc($result);
	
	$owner = $row['owner'];
  unset($sql,$result,$row);
  $is_owner = ($session['user']['acctid']==$owner?true:false);

  switch($_GET['act'])
  {
    case "block":
      $sql = "SELECT `value` FROM `housemoduledata` "
            ."WHERE `moduleid`='".$moduleid."' "
            ."AND `houseid`='".$houseid."' "
            ."AND `name`='Notizblock' "
            ."LIMIT 1";
      $result = db_query($sql);
      $row = db_fetch_assoc($result);
      
      output("`c`b`4Notizblock`0`b`c`n`n");
      
      rawoutput("<table align='center' bgcolor='#999999' width='300'><tr><td>");
      $ausgabe = nl2br(htmlspecialchars($row['value']));
      if(empty($ausgabe)) $ausgabe = "`c`^`bLeerer Inhalt`b`c";
      output($ausgabe,true);
      rawoutput("</td></tr></table>");
      
      addnav("Reinschreiben","houses.php?op=drin&act=writeblock");
      break;
      
    case "writeblock":
      $sql = "SELECT `value` FROM `housemoduledata` "
            ."WHERE `moduleid`='".$moduleid."' "
            ."AND `houseid`='".$houseid."' "
            ."AND `name`='Notizblock' "
            ."LIMIT 1";
      $result = db_query($sql);
      $row = db_fetch_assoc($result);
      
      output("`c`b`4Notizblock`0`b`c`n`n");

      addnav("","houses.php?op=drin&act=writeblock2");
      
      rawoutput("<table align='center'width='300'><tr><td>");
      rawoutput("<form action='houses.php?op=drin&act=writeblock2' method='POST'>");
      
      $ausgabe = htmlspecialchars($row['value']);
      rawoutput("<textarea name='text' cols='50' rows='15'>"
               .$ausgabe
               ."</textarea>");
               
      rawoutput("<input type='submit' class='button' value='Reinschreiben!' />");
      rawoutput("</form>");
      rawoutput("</td></tr></table>");
      break;

    case "writeblock2":
      $sql = "UPDATE `housemoduledata` SET `value` = '".$_POST['text']."' "
            ."WHERE `name`='Notizblock' "
            ."AND `moduleid`='".$moduleid."' "
            ."AND `houseid`='".$houseid."' ";
      db_query($sql);
      $affectet = mysql_affected_rows();
      
      if($affectet == 0)
      {
        $sql = "INSERT INTO `housemoduledata` "
              ."(`value`,`name`,`moduleid`,`houseid`) "
              ."VALUES ('".$_POST['text']."','Notizblock','".$moduleid."','".$houseid."')";
        db_query($sql);
      }
      redirect("houses.php?op=drin");
      break;

    case "showbook";
      $bookid = (int)$_GET['book'];
      
      $sql = "SELECT l.title,l.body,l.author,l.bookid,a.name "
            ."FROM `houses_library` l "
            ."INNER JOIN `accounts` a "
            ."ON a.acctid = l.author "
            ."AND l.bookid = '".$bookid."'";
      $result = db_query($sql);
      $row = db_fetch_assoc($result);
      
      $is_bookowner = ($session['user']['acctid'] == $row['author']?true:false);
      
      $title = htmlspecialchars($row['title']);
      $author = $row['name'];
      $book = nl2br(htmlspecialchars($row['body']));
      
      output("`c`b`4".$title."`0`b`c`n`n`3",true);
      output("`c".$book."`c`0`n`n",true);
      output("`c`@~~ Author: ".$author."`@ ~~`c`n",true);
      
      if($is_owner === true || $is_bookowner === true)
      {
        $edit = "houses.php?op=drin&act=edit&book=".$row['bookid'];
        $del = "houses.php?op=drin&act=del&book=".$row['bookid'];
        
        allownav($edit);
        allownav($del);
        
        rawoutput("<br /><br /><center>"
                 ."[ <a href='$edit'>Editieren</a> "
                 ."| <a href='$del' onClick='return confirm(\"Willst du dieses Buch wirklich löschen?\");'>Löschen</a> "
                 ."]</center>");
      }

      break;
      
    case "edit":
      output("`c`b`4Buch editieren`0`b`c`n`n");

      /* Buch holen */
      $bookid = (int)$_GET['book'];
      
      $sql = "SELECT l.title,l.body,l.bookid "
            ."FROM `houses_library` l "
            ."WHERE l.bookid = '".$bookid."'";
      $row = db_fetch_assoc(db_query($sql));

      /* Styles */
      $style_input = "font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px; background-color: #303030;border: 1px solid; border-color: #000000 #707070 #707070 #000000; color: #e7e7e7;";
      $style_fieldset = "color: #e7e7e7; border: 1px solid; border-color: #707070 #000000 #000000 #707070; background: #303030;";
      $style_button = "font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #d0a700; background-color: #303030; border: 1px solid; border-color: #707070 #000000 #000000 #707070;";
      $style_textarea = "background-color: #303030; border: 1px solid; border-color: #000000 #707070 #707070 #000000;color: #e7e7e7;";

      rawoutput("<form action='houses.php?op=drin&act=edit2&book=".$bookid."' method='POST'>"
               ."<fieldset style='$style_fieldset'>"
               ."<center style='color: #e7e7e7;'>Buchtitel:<br />"
               ."<input type='text' name='title' value='".$row['title']."' maxlenght='100' style='$style_input' />"
               ."<br /><br />"
               ."Inhalt<br /><textarea style='$style_textarea' name='body' cols='50' rows='15'>".$row['body']
               ."</textarea><br /><br />"
               ."<input type='submit' style='$style_button' value='Schreiben!'/></center>"
               ."</fieldset></form>");

      addnav("","houses.php?op=drin&act=edit2&book=".$bookid);
      break;

    case "edit2":
      $title = mysql_real_escape_string($_POST['title']);
      $body = mysql_real_escape_string($_POST['body']);
      $bookid = (int)$_GET['book'];

      $sql = "UPDATE `houses_library` SET "
            ."`title` = '".$title."', "
            ."`body` = '".$body."' "
            ."WHERE `bookid` = '".$bookid."'";
      db_query($sql);
      redirect("houses.php?op=drin");
      break;

    case "del":
      $bookid = (int)$_GET['book'];
      
      db_query("DELETE FROM `houses_library` WHERE `bookid` = '".$bookid."'");
      redirect("houses.php?op=drin");
      break;

    case "write":
      output("`c`b`4Buch verfassen`0`b`c`n`n");
      
      /* Styles */
      $style_input = "font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 10px; background-color: #303030;border: 1px solid; border-color: #000000 #707070 #707070 #000000; color: #e7e7e7;";
      $style_fieldset = "color: #e7e7e7; border: 1px solid; border-color: #707070 #000000 #000000 #707070; background: #303030;";
      $style_button = "font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 12px; color: #d0a700; background-color: #303030; border: 1px solid; border-color: #707070 #000000 #000000 #707070;";
      $style_textarea = "background-color: #303030; border: 1px solid; border-color: #000000 #707070 #707070 #000000;color: #e7e7e7;";

      rawoutput("<form action='houses.php?op=drin&act=write2' method='POST'>"
               ."<fieldset style='$style_fieldset'>"
               ."<center style='color: #e7e7e7;'>Buchtitel:<br />"
               ."<input type='text' name='title' maxlenght='100' style='$style_input' /><br /><br />"
               ."Inhalt<br /><textarea style='$style_textarea' name='body' cols='50' rows='15'></textarea><br /><br />"
               ."<input type='submit' style='$style_button' value='Schreiben!'/></center>"
               ."</fieldset></form>");

      allownav("houses.php?op=drin&act=write2");
      break;
      
    case "write2":
      $title = mysql_real_escape_string($_POST['title']);
      $body = mysql_real_escape_string($_POST['body']);
      
      $sql = "INSERT INTO `houses_library` (`houseid`,`author`,`title`,`body`) "
            ."VALUES ('".$houseid."','".$session['user']['acctid']."','".$title."','".$body."')";
      db_query($sql);
      redirect("houses.php?op=drin");
      break;
      
    default:
      output("`c`b`4Lesezimmer`0`b`c`n"
            ."`4Eine sanfte Ruhe schwelgt durch den Raum. Hier solltest Du leise sein,es ist ein Ort der Stillte.`n"
            ."Buch an Buch steht an der Wand aufgereiht. Langsam stöberst Du durch die Reihen, vielleicht findest "
            ."Du ja was für dich. "
            ."In einer Ecke steht ein Berg Pergament, ein Tintenfässchen und eine schöne, goldene Feder. Hier darfst "
            ."Du deiner Fantasien nachgehen und ein Buch schreiben - Welches nur Für dein Haus zugänglich ist.");
      
      break;
  }
  
  addnav("Lesezimmer");
  addnav("Notizblock","houses.php?op=drin&act=block");
  
  /* Bücher */
  $sql = "SELECT `bookid`,`title` FROM `houses_library` WHERE  `houseid`='".$houseid."'";
  $result = db_query($sql) or die(mysql_error());
  
  $num_rows = db_num_rows($result);
  if($num_rows == 0)
  {
    addnav("Es gibt noch keine Bücher","");
  }
  else
  {
    while($row = db_fetch_assoc($result))
      addnav(preg_replace_c("#[`].#","",$row['title'])." ","houses.php?op=drin&act=showbook&book=".$row['bookid']);
  }

  if($num_rows <= $book_limit) addnav("Buch schreiben","houses.php?op=drin&act=write");
  addnav("Zurück");
  addnav("Zurück","houses.php?op=drin&module=");
  /* content_show end */
}
?>