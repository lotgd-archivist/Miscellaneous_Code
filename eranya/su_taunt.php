
<?php
require_once "common.php";
su_check(SU_RIGHT_EDITORWORLD,true);

page_header("Spott Editor");
addnav("G?Zurück zur Grotte","superuser.php");


addnav('W?Zurück zum Weltlichen',$session['su_return']);
if ($_GET[op]=="edit"){
    addnav("Spotteditor","su_taunt.php");
    output("<form action='su_taunt.php?op=save&tauntid=$_GET[tauntid]' method='POST'>",true);
    addnav("","su_taunt.php?op=save&tauntid=$_GET[tauntid]");
    if ($_GET[tauntid]!=""){
        $sql = "SELECT * FROM taunts WHERE tauntid=\"$_GET[tauntid]\"";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $taunt = $row[taunt];
        $taunt = str_replace("%s","ihn",$taunt);
        $taunt = str_replace("%o","er",$taunt);
        $taunt = str_replace("%p","sein",$taunt);
        $taunt = str_replace("%x","Zahnstocher",$taunt);
        $taunt = str_replace("%X","Scharfe Zähne",$taunt);
        $taunt = str_replace("%W","Grosse grüne Ratte",$taunt);
        $taunt = str_replace("%w","JoeBloe",$taunt);
        output("Vorschau: $taunt`0`n`n");
    }
    $output.="Taunt: <input name='taunt' value=\"".HTMLEntities($row[taunt])."\" size='70'><br>";
    output("`nDie folgenden Codes werden unterstützt (Groß- und Kleinschriebung wird unterschieden):`n");
    output("%w = Name des Verlierers`n");
    output("%x = Waffe des Verlierers`n");
    output("%s = Geschlecht des Verlierers (ihn/sie)`n");
    output("%p = Geschlecht des Verlierers (sein/ihr)`n");
    output("%o = Geschlecht des Verlierers (er/sie)`n");
    output("%W = Name des Gewinners`n");
    output("%X = Waffe des Gewinners`n");
    output("<input type='submit' class='button' value='Speichern'>",true);
    output("</form>",true);
}else if($_GET[op]=="del"){
    $sql = "DELETE FROM taunts WHERE tauntid=\"$_GET[tauntid]\"";
    db_query($sql) or die(db_error(LINK));
    redirect("su_taunt.php?c=x");
}else if($_GET[op]=="save"){
    if ($_GET[tauntid]!=""){
        $sql = "UPDATE taunts SET taunt=\"$_POST[taunt]\",editor=\"".addslashes($session[user][login])."\" WHERE tauntid=\"$_GET[tauntid]\"";
    }else{
        $sql = "INSERT INTO taunts (taunt,editor) VALUES (\"$_POST[taunt]\",\"".addslashes($session[user][login])."\")";
    }
    db_query($sql) or die(db_error(LINK));
    redirect("su_taunt.php?c=x");
}else{
    $sql = "SELECT * FROM taunts";
    $result = db_query($sql) or die(db_error(LINK));
    output("<table>",true);
    for ($i=0;$i<db_num_rows($result);$i++){
        $row=db_fetch_assoc($result);
        output("<tr>",true);
        output("<td>",true);
        output("[<a href='su_taunt.php?op=edit&tauntid=$row[tauntid]'>Edit</a>|<a href='su_taunt.php?op=del&tauntid=$row[tauntid]' onClick='return confirm(\"Diesen Eintrag wirklich löschen?\");'>Löschen</a>]",true);
        addnav("","su_taunt.php?op=edit&tauntid=$row[tauntid]");
        addnav("","su_taunt.php?op=del&tauntid=$row[tauntid]");
        output("</td>",true);
        output("<td>",true);
        output($row[taunt]);
        output("</td>",true);
        output("<td>",true);
        output($row[editor]);
        output("</td>",true);
        output("</tr>",true);
    }
    addnav("","su_taunt.php?c=$_GET[c]");
    output("</table>",true);
    addnav("Spott hinzufügen","su_taunt.php?op=edit");
}
page_footer();
?>

