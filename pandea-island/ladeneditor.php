<?php
/*
 * shop.php
 *
 * some shops lead by special users
 *
#
# Tabellenstruktur für Tabelle `shops` und `owner`
#

CREATE TABLE shops (
  shopid int(11) unsigned NOT NULL auto_increment,
  shopname varchar(20) default NULL,
  description varchar(255) NOT NULL,
  source varchar(20) NOT NULL,
  gems int(11) unsigned default '0',
  gold int(11) unsigned default '0',
  PRIMARY KEY  (shopid)
) TYPE=MyISAM;

CREATE TABLE shops_owner(
ownerid int(11) unsigned NOT NULL auto_increment,
acctid int(11) unsigned NOT NULL default '0',
shopid int(11) unsigned NOT NULL default '0',
PRIMARY KEY (ownerid)
) TYPE=MyISAM;

 */

require_once "common.php";
isnewday(3);

page_header("Shopeditor");

addnav("W?Zurück zum Weltlichen","village.php");
addnav("G?Zurück zur Grotte","superuser.php");

if ($_GET[op]=="newshop"){
    if ($_GET[subop]=="save"){ // shop sichern
        output("`@Neuer shop erstellt.");
        $sql = "INSERT INTO shops (shopname, description, source, gems, gold) VALUES ('$_POST[shopname]', '$_POST[description]', '$_POST[source]', 0, 0)";
        db_query($sql);
    } else {
        // shops erstellen - dieses kann nur von admins gemacht werden, nie durch die user selbst
        // ownerIDs sind erst standardmaessig auf 0 gesetzt
        output("`@Neuen Shop anlegen:`n`n");
        output("`0<form action=\"ladeneditor.php?op=newshop&subop=save\" method='POST'>",true);
        output("<table><tr><td>Shopname </td><td><input name='shopname' maxlength='25'></td></tr>",true);
        output("<tr><td>Beschreibung </td><td><input type='text' name='description' maxlength='250'></td></tr>",true);
        output("<tr><td>Source-Datei</td><td><input type='text' name='source' value='shop.php'></td></tr></table>",true);
        output("<input type='submit' class='button' value='Speichern'></form>",true);
        addnav("","ladeneditor.php?op=newshop&subop=save");
        output("`$ Die Dateien der Shops müssen im Verzeichnis \"shops\" vorhanden sein.");
    }
    addnav("Zurück zur Shopübersicht","ladeneditor.php");

} else if ($_GET[op]=="drin"){
    $sql="SELECT * FROM shops WHERE shopid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    output("`n`@Shopname: `^`b$row[shopname]`b");
    output("`n`@ShopID: `^`b$row[shopid]`b");
       output("`n`@Source-Datei: `^`b$row[source]`b");
    output("`n`@Beschreibung: `^`b$row[description]`b");
    output("`n`@Gold im Laden: `^`b$row[gold]`b");
    output("`n`@Gems im Laden: `^`b$row[gems]`b");
    /*
     * inhaber aus der datenbank holen
     */
    $sql="SELECT shops_owner.*,accounts.name FROM shops_owner LEFT JOIN accounts USING(acctid) WHERE shops_owner.shopid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    if(db_num_rows($result)== 0){
        output("`n`nNoch keine Inhaber definiert, shop befindet sich im Baustatus");
    } else {
        output("`n`n<table border='0' cellpadding='3' cellspacing='0'><tr><td>Nr</td><td>Name</td><td>Schlie&szlig;fach</td><td>Optionen</td></tr>",true);
        for($i=1; $i<= db_num_rows($result); $i++){
          $row = db_fetch_assoc($result);
          output("<tr><td>$i</td><td>$row[name]</td><td>$row[gold]</td><td>",true);
          output("<a href='ladeneditor.php?op=editowner&id=$_GET[id]&owner=$row[ownerid]'>Edit</a> | <a href='ladeneditor.php?op=deleteowner&ownerid=$row[ownerid]&shopid=$_GET[id]' onClick=\"return confirm('Diesen Inhaber wirklich löschen?');\">Löschen</a>",true);
            addnav("","ladeneditor.php?op=editowner&id=$_GET[id]&owner=$row[ownerid]");
            addnav("","ladeneditor.php?op=deleteowner&ownerid=$row[ownerid]&shopid=$_GET[id]");
          output("</td></tr>",true);
        }
        output("</table>",true);
    }
    addnav("Übersicht");
    addnav("Daten ändern","ladeneditor.php?op=edit&id=$row[shopid]");
    addnav("Inhaber hinzufügen","ladeneditor.php?op=new_owner&id=$_GET[id]");
    addnav("Zurück zur Shopübersicht","ladeneditor.php");
}else if ($_GET[op]=="edit"){
    $sql = "SELECT * FROM shops WHERE shopid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    addnav("$row[shopname]-Übersicht","ladeneditor.php?op=drin&id=$row[shopid]");
    if ($_GET[subop]=="save"){ // shop sichern
        output("`@Shop-Daten geaendert.");
        $sql = "UPDATE shops SET shopname='".rawurldecode($_POST[shopname])."', description='".rawurldecode($_POST[description])."', source='".rawurldecode($_POST[source])."', gold=$_POST[gold], gems=$_POST[gems] WHERE shopid=".$_GET[id];
        db_query($sql);
    } else {
        // shops erstellen - dieses kann nur von admins gemacht werden, nie durch die user selbst
        // ownerIDs sind erst standardmaessig auf 0 gesetzt
        output("`0<form action=\"ladeneditor.php?op=edit&subop=save&id=$_GET[id]\" method='POST'>",true);
        output("<table><tr><td>Name </td><td><input name='shopname' maxlength='25' value='".(rawurlencode($row[shopname]))."'></td></tr>",true);
        output("<tr><td>Beschreibung </td><td><input type='text' name='description' maxlength='250' value='".stripslashes((rawurlencode($row[description])))."'></td></tr>",true);
        output("<tr><td>Source-Datei</td><td><input type='text' name='source' value='$row[source]'></td></tr>",true);
        output("<tr><td>Gold im Laden</td><td><input type='text' name='gold' value='$row[gold]'></td></tr>",true);
        output("<tr><td>Gems im Laden</td><td><input type='text' name='gems' value='$row[gems]'></td></tr></table>",true);
        output("<input type='submit' class='button' value='Speichern'></form>",true);
        output("`0`n`nDaten, die nicht geändert werden sollen, `bnicht`b verändern!`n");
        addnav("","ladeneditor.php?op=edit&subop=save&id=$_GET[id]");
        output("`$ Die Dateien der Shops müssen im Verzeichnis \"shops\" vorhanden sein.");
    }

}else if ($_GET[op]=="new_owner"){
    $sql = "SELECT * FROM shops WHERE shopid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    addnav("$row[shopname]-Übersicht","ladeneditor.php?op=drin&id=$row[shopid]");
    if (!$_POST['ziel']){
        output("Inhaber zum ".$row[shopname]." hinzufuegen");
        output("<form action='ladeneditor.php?op=new_owner&id=$_GET[id]&owner=$_GET[owner]' method='POST'>",true);
        output("Wer soll Inhaber ".$_GET[owner]." werden? <input name='ziel'>`n", true);
        output("<input type='submit' class='button' value='Speichern'></form>",true);
        addnav("","ladeneditor.php?op=new_owner&id=$_GET[id]&owner=$_GET[owner]");
    }else {
        if ($_GET['subfinal']==1){
            $sql = "SELECT acctid,name,login,lastip,emailaddress FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";
        }else{
            $ziel = stripslashes(rawurldecode($_POST['ziel']));
            $name="%";
            for ($x=0;$x<strlen($ziel);$x++){
                $name.=substr($ziel,$x,1)."%";
            }
            $sql = "SELECT acctid,name,login,lastip,emailaddress FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";
        }
        $result2 = db_query($sql);
        if (db_num_rows($result2) == 0) {
            output("`2Es gibt niemanden mit einem solchen Namen. Versuchs nochmal.");
        } elseif(db_num_rows($result2) > 100) {
            output("`2Es gibt über 100 Krieger mit einem ähnlichen Namen. Bitte sei etwas genauer.");
        } elseif(db_num_rows($result2) > 1) {
            output("`2Es gibt mehrere mögliche Krieger, die diesen Posten übernehmen könnten.`n");
            output("<form action='ladeneditor.php?op=new_owner&id=$_GET[id]&owner=$_GET[owner]&subfinal=1' method='POST'>",true);
            output("`2Wen genau meinst du? <select name='ziel'>",true);
            for ($i=0;$i<db_num_rows($result2);$i++){
                $row2 = db_fetch_assoc($result2);
                output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Schlüssel übergeben'></form>",true);
            addnav("","ladeneditor.php?op=new_owner&id=$_GET[id]&owner=$_GET[owner]&subfinal=1");
        } else {
            $row2 = db_fetch_assoc($result2);
            output("`n`n$row2[name] wird jetzt als Inhaber hinzugefügt.");
            systemmail($row2[acctid],"`@Shop-Inhaber`0","Du wurdest als Inhaber für ".$row[shopname]." ausgewählt.");
            $sql = "INSERT INTO shops_owner (shopid, acctid) VALUES ('$_GET[id]', '$row2[acctid]')";
            db_query($sql);
        }
    }

}else if ($_GET[op]=="editowner"){
    $sql = "SELECT * FROM shops WHERE shopid=$_GET[id]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    addnav("$row[shopname]-Übersicht","ladeneditor.php?op=drin&id=$row[shopid]");
    if (!$_POST['ziel']){
        output("Inhaber des ".$row[shopname]."s bearbeiten");
        output("<form action='ladeneditor.php?op=editowner&id=$_GET[id]&owner=$_GET[owner]' method='POST'>",true);
        output("Wer soll Inhaber ".$_GET[owner]." werden? <input name='ziel'>`n", true);
        output("<input type='submit' class='button' value='Speichern'></form>",true);
        addnav("","ladeneditor.php?op=editowner&id=$_GET[id]&owner=$_GET[owner]");
    }else {
        if ($_GET['subfinal']==1){
            $sql = "SELECT acctid,name,login,lastip,emailaddress FROM accounts WHERE name='".addslashes(rawurldecode(stripslashes($_POST['ziel'])))."' AND locked=0";
        }else{
            $ziel = stripslashes(rawurldecode($_POST['ziel']));
            $name="%";
            for ($x=0;$x<strlen($ziel);$x++){
                $name.=substr($ziel,$x,1)."%";
            }
            $sql = "SELECT acctid,name,login,lastip,emailaddress FROM accounts WHERE name LIKE '".addslashes($name)."' AND locked=0";
        }
        $result2 = db_query($sql);
        if (db_num_rows($result2) == 0) {
            output("`2Es gibt niemanden mit einem solchen Namen. Versuchs nochmal.");
        } elseif(db_num_rows($result2) > 100) {
            output("`2Es gibt über 100 Krieger mit einem ähnlichen Namen. Bitte sei etwas genauer.");
        } elseif(db_num_rows($result2) > 1) {
            output("`2Es gibt mehrere mögliche Krieger, die diesen Posten übernehmen könnten.`n");
            output("<form action='ladeneditor.php?op=editowner&id=$_GET[id]&owner=$_GET[owner]&subfinal=1' method='POST'>",true);
            output("`2Wen genau meinst du? <select name='ziel'>",true);
            for ($i=0;$i<db_num_rows($result2);$i++){
                $row2 = db_fetch_assoc($result2);
                output("<option value=\"".rawurlencode($row2['name'])."\">".preg_replace("'[`].'","",$row2['name'])."</option>",true);
            }
            output("</select>`n`n",true);
            output("<input type='submit' class='button' value='Schlüssel übergeben'></form>",true);
            addnav("","ladeneditor.php?op=editowner&id=$_GET[id]&owner=$_GET[owner]&subfinal=1");
        } else {
            /* alten owner holen und mail schicken */
            $sql = "SELECT * FROM shops_owner WHERE ownerid=".$_GET[owner];
            $result3 = db_query($sql);
            $row3 = db_fetch_assoc($result3);
            systemmail($row3[acctid],"`@Shop-Inhaber`0","Du bist nicht mehr Inhaber von ".$row[shopname]);
            /* neuen owner eintragen und mail schicken */
            $row2 = db_fetch_assoc($result2);
            output("Inhaber ".$_GET[owner]." wird geändert.");
            output("`n`n$row2[name] ist jetzt inhaber.");
            systemmail($row2[acctid],"`@Shop-Inhaber`0","Du wurdest als Inhaber fuer ".$row[shopname]." auserwaehlt.");
            $sql = "UPDATE shops_owner SET acctid=$row2[acctid] WHERE ownerid=".$_GET[owner];
            db_query($sql);
        }
    }

}else if ($_GET[op]=="deleteowner"){
    $sql = "SELECT * FROM shops WHERE shopid=$_GET[shopid]";
    $result = db_query($sql) or die(db_error(LINK));
    $row = db_fetch_assoc($result);
    addnav("$row[shopname]-Übersicht","ladeneditor.php?op=drin&id=$row[shopid]");
    output("Inhaber gelöscht");
    systemmail($row[acctid],"`@Shop-Inhaber`0","Du bist nicht mehr Inhaber von ".$row[shopname]);
    $sql = "DELETE FROM shops_owner WHERE ownerid=$_GET[ownerid]";
    db_query($sql);


} else {
    output("Übersicht über vorhandene Shops`n`n");
    output("Wähle einen Shop:");
    output("<table cellpadding=2 align='center'><tr><td>`bShopNr.`b</td><td>`bName`b</td></tr>",true);
    $sql = "SELECT shopid,shopname FROM shops WHERE 1 ORDER BY shopid ASC";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)==0){
        output("<tr><td colspan=4 align='center'>`&`iEs gibt keine Shops`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $row2 = db_fetch_assoc($result);
            output("<tr><td align='center'>$row2[shopid]</td><td><a href='ladeneditor.php?op=drin&id=$row2[shopid]'>$row2[shopname]</a></td></tr>",true);
            addnav("","ladeneditor.php?op=drin&id=$row2[shopid]");
        }
    }
    output("</table>",true);
    addnav("Neuen Shop erstellen","ladeneditor.php?op=newshop");
}

page_footer();
?>