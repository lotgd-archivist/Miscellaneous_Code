
<?php

# Idee, Texte, Bilder und Umsetzung by Shaddar und Filyina
# (C) Copyright by Soul of the black Dragon
# http://www.soul-of-the-black-dragon.de

require_once "common.php";
#isnewday(3);

page_header("Meister Editor");
output("`c`b`&Meister Editor`0`b`c`n ".
"`tViele Helden gibt es in diesen Landen, doch nur die wenigstens von Ihnen sind es Wert Meister genannt zu werden. ".
"Nur eine Hand voll ist in der Lage jungen Kriegerinnen und Kriegern die nötigen Kenntnisse zu vermitteln um in dieser Welt überleben zu können. ".
"Hier hast Du einen Überblick über jene, die diese Herausforderung gemeistert haben und somit selbst zu Meistern geworden sind.`0`n`n");

$towns = array ('Dorfplatz');
$health = array (1=>'21','32','43','54','65','76','87','98','109','120','131','142','153','164');
$attack = array (1=>'2','4','6','8','10','12','14','16','18','20','22','24','26','28');
$defence = array (1=>'2','4','6','8','10','12','14','16','18','20','22','24','26','28');

if ($_GET['op']=="") {
    if ($_GET['town']=="") $_GET['town'] = 0;
    $sql = "SELECT * FROM masters WHERE town=".$_GET['town']." ORDER BY creaturelevel ASC, creaturename ASC";
    $result = db_query($sql);
    output("<table border='0' cellspacing='0' cellpadding='2' align='center'>",true);
    output("<tr class='trhead'><td>Level:</td> <td>Name:</td> <td>Waffe:</td> <td></td> <td>Dorf:</td> <td>Optionen:</td></tr>",true);
    for ($i=0;$i<db_num_rows($result);$i++) {
        $row = db_fetch_assoc($result);
        output("<tr class='".($i%2?"trdark":"trlight")."'><td align='center'>`^".$row['creaturelevel']."`0</td> <td>".$row['creaturename']."</td> <td>".$row['creatureweapon']."</td> <td>".($row['sex']?"<img src='images/female.gif' title='Weiblich' alt='Weiblich' />":"<img src='images/male.gif' title='Männlich' alt='Männlich' />")."</td> <td>".$towns[$row['town']]."</td> <td><a href='editor-meister.php?op=view&id=".$row['creatureid']."'>[Ansehen]</a> <a href='editor-meister.php?op=edit&id=".$row['creatureid']."'>[Editieren]</a> <a href='editor-meister.php?op=del&id=".$row['creatureid']."'>[Löschen]</a></td></tr>",true);
        addnav("","editor-meister.php?op=view&id=".$row['creatureid']);
        addnav("","editor-meister.php?op=edit&id=".$row['creatureid']);
        addnav("","editor-meister.php?op=del&id=".$row['creatureid']); }
    output("</table>",true);

} else if ($_GET['op']=="view") {
    $sql = "SELECT * FROM masters WHERE creatureid=".$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    output("<table border='0' cellspacing='2' cellpadding='0' style='width: 400px;'>",true);
    output("<tr class='trhead'><td colspan='2'>Meister ansehen:</td></tr>",true);
    output("<tr><td>`hLevel:`0</td> <td>`^".$row['creaturelevel']."`0</td></tr>",true);
    output("<tr><td>`hName:`0</td> <td>".$row['creaturename']."</td></tr>",true);
    output("<tr><td>`hGeschlecht:`0</td> <td>".($row['sex']?"Weiblich <img src='images/female.gif' title='Weiblich' alt='Weiblich' />":"Männlich <img src='images/male.gif' title='Männlich' alt='Männlich' />")."</td></tr>",true);
    output("<tr><td>`hWaffe:`0</td> <td>".$row['creatureweapon']."</td></tr>",true);
    output("<tr><td>`hNachricht bei Sieg:`0</td> <td>".$row['creaturewin']."</td></tr>",true);
    output("<tr><td>`hNachricht bei Niederlage:`0</td> <td>".$row['creaturelose']."</td></tr>",true);
    output("<tr><td>`hIn welchem Dorf zu finden:`0</td> <td>`^".$towns[$row['town']]."`0</td></tr>",true);
    output("<tr><td>`hBild zum Meister:`0</td> <td>".($row['picture']!=""?"<img src='".$row['picture']."' />":"")."</td></tr>",true);
    output("<tr><td>-</td> <td>-</td></tr>",true);
    output("<tr><td></td> <td><a href='editor-meister.php?op=edit&id=".$_GET['id']."'>`g[Editieren]`0</a> <a href='editor-meister.php?op=del&id=".$_GET['id']."'>`\$[Löschen]`0</a></td></tr>",true);
    output("</table>",true);
    addnav("","editor-meister.php?op=edit&id=".$row['creatureid']);
    addnav("","editor-meister.php?op=del&id=".$row['creatureid']);

} else if ($_GET['op']=="edit") {
    if ($_GET['act']!="") {
        $_POST['name'] = stripslashes($_POST['name']);
        $_POST['creatureweapon'] = stripslashes($_POST['creatureweapon']);
        $_POST['creaturewin'] = stripslashes($_POST['creaturewin']);
        $_POST['creaturelose'] = stripslashes($_POST['creaturelose']);
        if ($_POST['name']=="" || $_POST['creatureweapon']=="") {
            output("`\$Du musst mindestens einen Namen und eine Waffe anegeben !`0");
        } else {
            if ($_GET['id'] > 0) {
                output("`@Meister erfolreich editiert.`0");
                $sql = "UPDATE masters SET creaturename='".$_POST['name']."',sex=".$_POST['sex'].",creaturelevel=".$_POST['level'].",creatureweapon='".$_POST['creatureweapon']."',creaturewin='".$_POST['creaturewin']."',creaturelose='".$_POST['creaturelose']."',creaturehealth=".$health[$_POST['level']].",creatureattack=".$attack[$_POST['level']].",creaturedefense=".$defence[$_POST['level']].",town=".$_POST['town'].",picture='".$_POST['picture']."' WHERE creatureid=".$_GET['id'];
                db_query($sql);
            } else {
                output("`@Meister erfolgreich gespeichert.`0");
                $sql = "INSERT INTO masters (creaturename,sex,creaturelevel,creatureweapon,creaturewin,creaturelose,creaturehealth,creatureattack,creaturedefense,town,picture) VALUES ('".$_POST['name']."',".$_POST['sex'].",".$_POST['level'].",'".$_POST['creatureweapon']."','".$_POST['creaturewin']."','".$_POST['creaturelose']."',".$health[$_POST['level']].",".$attack[$_POST['level']].",".$defence[$_POST['level']].",".$_POST['town'].",'".$_POST['picture']."')";
                db_query($sql);
                $_GET['id'] = mysql_insert_id(); }}}

    if ($_GET['id'] > 0) {
        $sql = "SELECT * FROM masters WHERE creatureid=".$_GET['id'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);        
    }

    rawoutput("<form action='editor-meister.php?op=edit&act=save&id=".$_GET['id']."' method='POST'>");
    rawoutput("<table border='0' cellspacing='2' cellpadding='0' style='width: 400px;'>");
    rawoutput("<tr class='trhead'><td colspan='2'>Meister editieren:</td></tr>");
    rawoutput("<tr><td>Level:</td> <td><select name='level'>");
    for($i=1;$i<=14;$i++) {
        rawoutput("<option value='".$i."' ".($row['creaturelevel']==$i?"selected='selected'":"").">Level ".$i."</option>"); }
    rawoutput("</select></td></tr>");
    rawoutput("<tr><td>Name:</td> <td><input type='text' name='name' size='35' value='".htmlspecialchars(stripslashes($row['creaturename']))."' /></td></tr>");
    rawoutput("<tr><td>Geschlecht:</td> <td><select name='sex'><option value='0' ".($row['sex']==0?"selected='selected'":"").">Männlich</option><option value='1' ".($row['sex']==1?"selected='selected'":"").">Weiblich</option></select></td></tr>");
    rawoutput("<tr><td>Waffe:</td> <td><input type='text' name='creatureweapon' size='35' value='".htmlspecialchars(stripslashes($row['creatureweapon']))."' /></td></tr>");
    rawoutput("<tr><td>Nachricht bei Sieg:</td> <td><input type='text' name='creaturewin' size='35' value='".htmlspecialchars(stripslashes($row['creaturewin']))."' /></td></tr>");
    rawoutput("<tr><td>Nachricht bei Niederlage:</td> <td><input type='text' name='creaturelose' size='35' value='".htmlspecialchars(stripslashes($row['creaturelose']))."' /></td></tr>");
    rawoutput("<tr><td>In welchem Dorf zu finden:</td> <td><select name='town'>");
    for ($i=0;$i<count($towns);$i++) {
        rawoutput("<option value='".$i."' ".($row['town']==$i?"selected='selected'":"").">".$towns[$i]."</option>"); }
    rawoutput("</select></td></tr>");
    rawoutput("<tr><td>Bild zum Meister:</td> <td><input type='text' name='picture' size='35' value='".$row['picture']."' /></td></tr>");
    rawoutput("<tr><td></td> <td><input type='submit' value='Speichern' /></td></tr>");
    rawoutput("</form>");
    rawoutput("</table>");
    addnav("","editor-meister.php?op=edit&act=save&id=".$_GET['id']);

} else if ($_GET['op']=="del") {
    $sql = "SELECT creaturename FROM masters WHERE creatureid=".$_GET['id'];
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    output("`tBist Du sicher, dass Du den Meister `#".$row['creaturename']."`t löschen willst ?`0`n`n");
    output("<a href='editor-meister.php?op=del2&id=".$_GET['id']."'>`@[ Ja ]`0</a> Meister löschen.`n`n",true);
    addnav("","editor-meister.php?op=del2&id=".$_GET['id']);
    output("<a href='editor-meister.php'>`\$[ Nein ]`0</a> besser doch nicht löschen.`n`n",true);
    addnav("","editor-meister.php");

} else if ($_GET['op']=="del2") {
    $sql = "DELETE FROM masters WHERE creatureid=".$_GET['id'];
    db_query($sql);
    output("`@Meister erfolgreich gelöscht.`0"); }

addnav("Aktionen");
addnav("Aktualisieren","editor-meister.php?town=".$_GET['town']);
addnav("Neuer Meister","editor-meister.php?op=edit");
addnav("Dörfer");
for ($i=0;$i<count($towns);$i++) {
    addnav($towns[$i],"editor-meister.php?town=".$i); }
addnav("Sonstiges");
addnav("Zur Grotte","superuser.php");
addnav("Zum Weltlichen","village.php");

page_footer();

?>

