<?php
#----------------------------------#
#   Gildenadmintool - Version 1.1  #
#   Autor: Eliwood|Serra- 2005     #
#----------------------------------#

/* Wichtige (!) Dateien einbinden */
require_once "common.php";
require_once "lib/gildentoolfunc.php";
require_once "lib/gilden.php";
require_once "lib/formulaclass.php";
/* Superuserbefugnis checken */
isnewday(3);
/* Seitentitel */
page_header("Gildeneditor");

switch($_GET['op']):
  case "":
    output("`c`bGildeneditor`b`c`n");
    output("Hier darfst du die Grundwerte der Gilden ändern, Kostenlos Gilden erstellen, Gilden aktivieren und dieselbigen blockieren, sowie löschen.");
    break;
  case "create":
    if(!isset($_POST['gildenname']))
    {
      grundform("gildentool.php?op=create",true);
    }
    else
    {
      if(check_input_su()==false)
      {
        rawoutput($error);
        grundform("gildentool.php?op=create",true);
      }
      else
      {
        $sql = "INSERT INTO `gilden` (`gildenname`,`gildenname_b`,`gildenprefix`,`gildenprefix_b`,`leaderid`)";
        $sql.= "VALUES ('".$_POST['gildenname']."','".$_POST['gildenname_b']."','".$_POST['gildenprefix']."','".$_POST['gildenprefix_b']."','".$_POST['leaderid']."')";
        db_query($sql);
        $guildid = db_fetch_assoc(db_query("SELECT gildenid FROM gilden WHERE leaderid='".$_POST['leaderid']."'"));
        db_unbuffered_query("UPDATE accounts SET gildenactive='1',memberid='".$guildid['gildenid']."',isleader='".highestleader."' WHERE acctid='".$_POST['leaderid']."'");
        if($_POST['leaderid']==$session['user']['acctid'])
        {
          $session['user']['gildenactive'] = '1';
          $session['user']['memberid'] = $guildid['gildenid'];
          $session['user']['isleader'] = highestleader;
        }
        output("Gilde erfolreich erstellt!");
      }
    }
    break;
  case "showall":
    showguilds_su("gildentool.php?");
    break;
  case "edit":
    if(!isset($_POST['gildenname']))
      edit_form($_GET['id'],"gildentool.php?op=edit&id=".$_GET['id']);
    elseif(check_input_su()==false)
      edit_form($_GET['id'],"gildentool.php?op=edit&id=".$_GET['id']);
    else
    {
      update_guild_su($_GET['id']);
      showguilds_su("gildentool.php?");
    }
    break;
  case "drop":
    dropguild_su($_GET['id']);
    break;
  case "activate":
    activateguild($_GET['id'],$_GET['active']);
    showguilds_su("gildentool.php?");
    break;
  endswitch;

/* Navigation */
addnav("Gildentool");
addnav("Gilde erstellen",check_op("create","gildentool.php?op=create"));
addnav("Alle Gilden anzeigen",check_op("showall","gildentool.php?op=showall"));
addnav("Aktualisieren","gildentool.php");
addnav("Zurück");
addnav("Z?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
if(isset($session['user']['wo'])) $session['user']['wo'] = "Verschollen";
page_footer();
?>