
<?php

/*
* Version:    26.02.2008
* Author:    Linus
* Email:    webmaster@alvion-logd.de
*
* Beschreibung:    Admintool für Namensänderungen
*
* BETA !!
*
*/

require_once("common.php");
require_once "func/isnewday.php";isnewday(3);

page_header("Persönliche Schatztruhen");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("G?Zurück zur Grotte","superuser.php");

if ($_GET['op']=="drin"){
    if ($_GET['act']=="save") { // save data
        $sql="UPDATE house_PersonalChests SET PChestGold=$_POST[gold], PChestGems=$_POST[gems] WHERE PChestUser=".$_GET['id'];
        $result=db_query($sql);
        addnav("Zurück","su_pchests.php?op=drin&id=$_GET[id]");
    }else{
        addnav("Zurück","su_pchests.php");
        $sql="SELECT * FROM house_PersonalChests WHERE PChestUser=".$_REQUEST['id'];
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)){
            $row = db_fetch_assoc($result);
            $sql2="SELECT name FROM accounts WHERE acctid=".$row['PChestUser'];
            $row2=db_fetch_assoc(db_query($sql2));

            output("`@Inhalt der persönlichen Schatztruhe von `b$row2[name]`b ändern:`n`n");
            output("`n`@ChestId: `^`b$row[PChestID]`b");
            output("`n`@UserId: `^`b$row[PChestUser]`b");
            output("`n`@User: `^`b$row2[name]`b`n");

            output("`0<form action=\"su_pchests.php?op=drin&act=save&id=$row[PChestUser]\" method='POST'>",true);
            output("<table><tr><td>Goldeinlage</td><td><input type='text' name='gold' value='$row[PChestGold]'></td></tr>",true);
            output("<tr><td>Edelsteine</td><td><input type='text' name='gems' value='$row[PChestGems]'></td></tr></table>`n",true);
            output("<input type='submit' class='button' value='Speichern'></form>",true);
            addnav("","su_pchests.php?op=drin&act=save&id=$row[PChestUser]");
        }else{
            output("`@Dieser Spieler existiert nicht oder besitzt keine persönliche Schatztruhe`n`n");
        }
    }

} else {
    output("`@`b`cDie persönlichen Schatztruhen`c`b`n`n");

    output('Schatztruhen filtern: ');
    output('<form action="su_pchests.php?op=drin" method="post">',true);
    addnav('','su_pchests.php?op=drin');

    output('Springe direkt zu UserId. ');
    output('<input type="text" name="id" size="4">',true);
    output('<input type="submit" value="anzeigen"></form>',true);


    output("Wähle die Truhe:`n`n");
    output("<table cellpadding=2 align='center'><tr><td>`bChestId.`b</td><td>`bUserId.`b</td><td>`bName`b</td><td>`bGold`b</td><td>`bGems`b</td></tr>",true);
    $ppp=25; // Player Per Page +1 to display
    if (!$_GET['limit']){
        $page=0;
    }else{
        $page=(int)$_GET['limit'];
        addnav("Vorherige Seite","su_pchests.php?limit=".($page-1)."");
    }
    $limit="".($page*$ppp).",".($ppp+1);

    $sql = "SELECT * FROM house_PersonalChests WHERE 1 ORDER BY PChestUser ASC LIMIT $limit";
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result)>$ppp) addnav("Nächste Seite","su_pchests.php?limit=".($page+1)."");
    if (db_num_rows($result)==0){
        output("<tr><td colspan=3 align='center'>`&`iEs gibt keine Schatztruhen`i`0</td></tr>",true);
    }else{
        for ($i=0;$i<db_num_rows($result);$i++){
            $row2 = db_fetch_assoc($result);
            $sql2="SELECT name FROM accounts WHERE acctid=".$row2['PChestUser'];
            $row3=db_fetch_assoc(db_query($sql2));

            output("<tr><td align='center'>$row2[PChestID]</td><td align='center'>$row2[PChestUser]</td><td><a href='su_pchests.php?op=drin&id=$row2[PChestUser]'>$row3[name]</a></td><td>$row2[PChestGold]</td><td>$row2[PChestGems]</td></tr>",true);
            addnav("","su_pchests.php?op=drin&id=$row2[PChestUser]");
        }
    }
    output("</table>",true);
}

output("`n<div align='right'>`)2007 by Linus, based on suhouses.php by anpera and Chaosmaker/div>",true);
page_footer();
?>

