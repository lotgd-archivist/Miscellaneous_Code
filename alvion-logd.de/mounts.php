
<?
#-----------------------------#
#   Translated by Devilzimti  #
#                             #
#   Coded by Eric Stevens?    #
#-----------------------------#

/* SQL für Alvion-Addons:

ALTER TABLE `mounts`  ADD mountcostdp INT UNSIGNED NOT NULL DEFAULT '0',
        ADD mountmindk  INT UNSIGNED NOT NULL DEFAULT '0',
        ADD mountcostgemsrp  INT UNSIGNED NOT NULL DEFAULT '0',
        ADD mountcostgoldrp   INT UNSIGNED NOT NULL DEFAULT '0',
        ADD mountcostdprp   INT UNSIGNED NOT NULL DEFAULT '0';
*/

require_once "common.php";
require_once "func/isnewday.php";isnewday(3);

page_header("Stalltier Editor");
addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");
addnav("Stalltier erstellen","mounts.php?op=add");

if ($_GET['op']=="del"){
    $sql = "UPDATE mounts SET mountactive=0 WHERE mountid='{$_GET['id']}'";
    db_query($sql);
    $_GET['op']="";
}
if ($_GET['op']=="undel"){
    $sql = "UPDATE mounts SET mountactive=1 WHERE mountid='{$_GET['id']}'";
    db_query($sql);
    $_GET['op']="";
}

if ($_GET['op']==""){
    $sql = "SELECT * FROM mounts ORDER BY mountcategory, mountcostgems, mountcostgold";
    output("<table>",true);
    output("<tr><td>Ops</td><td>Name</td><td>Preis (`&Kämpfer`0/`^RP-Char`0)</td><td>&nbsp;</td></tr>",true);
    $result = db_query($sql);
    $cat = "";
    for ($i=0;$i<db_num_rows($result);$i++){
        $row = db_fetch_assoc($result);
        if ($cat!=$row['mountcategory']){
            output("<tr><td colspan='4'>Kategorie: {$row['mountcategory']}</td></tr>",true);
            $cat = $row['mountcategory'];
        }
        output("<tr>",true);
        output("<td>[ <a href='mounts.php?op=edit&id={$row['mountid']}'>Bearbeiten</a> |",true);
        addnav("","mounts.php?op=edit&id={$row['mountid']}");
        if ($row['mountactive']) {
            output(" <a href='mounts.php?op=del&id={$row['mountid']}'>Deaktivieren</a> ]</td>",true);
            addnav("","mounts.php?op=del&id={$row['mountid']}");
        }else{
            output(" <a href='mounts.php?op=undel&id={$row['mountid']}'>Aktivieren</a> ]</td>",true);
            addnav("","mounts.php?op=undel&id={$row['mountid']}");
        }
        output("<td>{$row['mountname']}</td>",true);
        output("<td>`&{$row['mountcostgems']} Gems, {$row['mountcostgold']} Gold, {$row['mountcostdp']} DP, {$row['mountmindk']} DK, `^{$row['mountcostgemsrp']} Gems, {$row['mountcostgoldrp']} Gold, {$row['mountcostdprp']} DP`0</td>",true);
        output("<td>WK: {$row['mountforestfights']}, Reiten: {$row['tavern']}</td>",true);
        output("</tr>",true);
    }
    output("</table>",true);
}elseif ($_GET['op']=="add"){
    output("Stalltier erstellen:`n");
    addnav("Zurück zum Stalltier Editor","mounts.php");
    mountform(array());
}elseif ($_GET['op']=="edit"){
    addnav("Zurück zum Stalltier Editor","mounts.php");
    $sql = "SELECT * FROM mounts WHERE mountid='{$_GET['id']}'";
    $result = db_query($sql);
    if (db_num_rows($result)<=0){
        output("`iStalltier nicht gefunden.`i");
    }else{
        output("Stalltier Editor:`n");
        $row = db_fetch_assoc($result);
        $row['mountbuff']=unserialize($row['mountbuff']);
        mountform($row);
    }
}elseif ($_GET['op']=="save"){
    $buff = array();
    reset($_POST['mount']['mountbuff']);
    $_POST['mount']['mountbuff']['activate']=join(",",$_POST['mount']['mountbuff']['activate']);
    while (list($key,$val)=each($_POST['mount']['mountbuff'])){
        if ($val>""){
            $buff[$key]=stripslashes($val);
        }
    }
    //$buff['activate']=join(",",$buff['activate']);
    $_POST['mount']['mountbuff']=$buff;
    reset($_POST['mount']);
    $keys='';
    $vals='';
    $sql='';
    $i=0;
    while (list($key,$val)=each($_POST['mount'])){
        if (is_array($val)) $val = addslashes(serialize($val));
        if ($_GET['id']>""){
            $sql.=($i>0?",":"")."$key='$val'";
        }else{
            $keys.=($i>0?",":"")."$key";
            $vals.=($i>0?",":"")."'$val'";
        }
        $i++;
    }
    if ($_GET['id']>""){
        $sql="UPDATE mounts SET $sql WHERE mountid='{$_GET['id']}'";
    }else{
        $sql="INSERT INTO mounts ($keys) VALUES ($vals)";
    }
    db_query($sql);
    if (db_affected_rows()>0){
        output("Stalltier gespeichert!");
    }else{
        output("Fehler beim Speichern: $sql");
    }
    addnav("Zurück zum Stalltier Editor","mounts.php");
}

function mountform($mount){
    global $output;
    output("<form action='mounts.php?op=save&id={$mount['mountid']}' method='POST'>",true);
    addnav("","mounts.php?op=save&id={$mount['mountid']}");
    $output.="<table>";
    $output.="<tr><td>Name:</td><td><input name='mount[mountname]' value=\"".htmlentities($mount['mountname'])."\"></td></tr>";
    $output.="<tr><td>Beschreibung:</td><td><input name='mount[mountdesc]' value=\"".htmlentities($mount['mountdesc'])."\"></td></tr>";
    $output.="<tr><td>Kategorie:</td><td><input name='mount[mountcategory]' value=\"".htmlentities($mount['mountcategory'])."\"></td></tr>";

    $output.="<tr><td>Preis (Edelsteine):</td><td><input name='mount[mountcostgems]' value=\"".htmlentities((int)$mount['mountcostgems'])."\"></td></tr>";
    $output.="<tr><td>Preis (Gold):</td><td><input name='mount[mountcostgold]' value=\"".htmlentities((int)$mount['mountcostgold'])."\"></td></tr>";
    $output.="<tr><td>Preis (Donationpoints):</td><td><input name='mount[mountcostdp]' value=\"".htmlentities((int)$mount['mountcostdp'])."\"></td></tr>";
    $output.="<tr><td>Mindestens DK (nur Kämpfer):</td><td><input name='mount[mountmindk]' value=\"".htmlentities((int)$mount['mountmindk'])."\"></td></tr>";

    $output.="<tr><td>Preis (Edelsteine) (nur RPG-Spieler):</td><td><input name='mount[mountcostgemsrp]' value=\"".htmlentities((int)$mount['mountcostgemsrp'])."\"></td></tr>";
    $output.="<tr><td>Preis (Gold) (nur RPG-Spieler):</td><td><input name='mount[mountcostgoldrp]' value=\"".htmlentities((int)$mount['mountcostgoldrp'])."\"></td></tr>";
    $output.="<tr><td>Preis (Donationpoints) (nur RPG-Spieler):</td><td><input name='mount[mountcostdprp]' value=\"".htmlentities((int)$mount['mountcostdprp'])."\"></td></tr>";


    $output.="<tr><td>Waldkämpfe:</td><td><input name='mount[mountforestfights]' value=\"".htmlentities((int)$mount['mountforestfights'])."\" size='5'></td></tr>";
    $output.="<tr><td>Tavernenzugang:</td><td><input name='mount[tavern]' value=\"".htmlentities((int)$mount['tavern'])."\" size='1'></td></tr>";
    $output.="<tr><td>\"Neuer Tag\" Nachricht:</td><td><input name='mount[newday]' value=\"".htmlentities($mount['newday'])."\" size='40'></td></tr>";
    $output.="<tr><td>\"Volle Heilung\" Nachricht</td><td><input name='mount[recharge]' value=\"".htmlentities($mount['recharge'])."\" size='40'></td></tr>";
    $output.="<tr><td>\"Teilweise Heilung\" Nachricht:</td><td><input name='mount[partrecharge]' value=\"".htmlentities($mount['partrecharge'])."\" size='40'></td></tr>";
    $output.="<tr><td>Wahrscheinlichkeit, dass Tier die Mine betritt:</td><td><input name='mount[mine_canenter]' value=\"".htmlentities((int)$mount['mine_canenter'])."\">%</td></tr>";
    $output.="<tr><td>Wahrscheinlichkeit, dass Tier in der Mine stirbt:):</td><td><input name='mount[mine_candie]' value=\"".htmlentities((int)$mount['mine_candie'])."\">%</td></tr>";
    $output.="<tr><td>Wahrscheinlichkeit, dass Tier Spieler rettet :</td><td><input name='mount[mine_cansave]' value=\"".htmlentities((int)$mount['mine_cansave'])."\">%</td></tr>";
    $output.="<tr><td>Nachricht, wenn Tier in die Mine kommt:</td><td><input name='mount[mine_tethermsg]' value=\"".htmlentities($mount['mine_tethermsg'])."\" size='40'></td></tr>";
    $output.="<tr><td>Nachricht, falls Tier sterben sollte:</td><td><input name='mount[mine_deathmsg]' value=\"".htmlentities($mount['mine_deathmsg'])."\" size='40'></td></tr>";
    $output.="<tr><td>Nachricht, falls Tier spieler retten sollte:</td><td><input name='mount[mine_savemsg]' value=\"".htmlentities($mount['mine_savemsg'])."\" size='40'></td></tr>";
    $output.="<tr><td valign='top'>Stalltier-Buff (Aktion):</td><td>";
    $output.="<b>Nachrichten:</b><Br/>";
    $output.="Buff-Name: <input name='mount[mountbuff][name]' value=\"".htmlentities($mount['mountbuff']['name'])."\"><Br/>";
    //output("Initial Message: <input name='mount[mountbuff][startmsg]' value=\"".htmlentities($mount['mountbuff']['startmsg'])."\">`n",true);
    $output.="Nachricht all Runde: <input name='mount[mountbuff][roundmsg]' value=\"".htmlentities($mount['mountbuff']['roundmsg'])."\"><Br/>";
    $output.="Nachricht, wenn Tier erschöpft: <input name='mount[mountbuff][wearoff]' value=\"".htmlentities($mount['mountbuff']['wearoff'])."\"><Br/>";
    $output.="Nachricht, wenn Effekt funktioniert: <input name='mount[mountbuff][effectmsg]' value=\"".htmlentities($mount['mountbuff']['effectmsg'])."\"><Br/>";
    $output.="Nachticht, wenn Effekt keinen Schaden macht: <input name='mount[mountbuff][effectnodmgmsg]' value=\"".htmlentities($mount['mountbuff']['effectnodmgmsg'])."\"><Br/>";
    $output.="Nachricht, wenn Effekt fehlschlägt: <input name='mount[mountbuff][effectfailmsg]' value=\"".htmlentities($mount['mountbuff']['effectfailmsg'])."\"><Br/>";
    $output.="<Br/><b>Effekte:</b><Br/>";
    $output.="Buffdauer (In Runden): <input name='mount[mountbuff][rounds]' value=\"".htmlentities((int)$mount['mountbuff']['rounds'])."\" size='5'><Br/>";
    $output.="Spieler Angriffsmodifikator: <input name='mount[mountbuff][atkmod]' value=\"".htmlentities($mount['mountbuff']['atkmod'])."\" size='5'><Br/>";
    $output.="Spieler Verteidigungsmodifikator: <input name='mount[mountbuff][defmod]' value=\"".htmlentities($mount['mountbuff']['defmod'])."\" size='5'><Br/>";
    $output.="Heilung: <input name='mount[mountbuff][regen]' value=\"".htmlentities($mount['mountbuff']['regen'])."\"><Br/>";
    $output.="Zähler: <input name='mount[mountbuff][minioncount]' value=\"".htmlentities($mount['mountbuff']['minioncount'])."\"><Br/>";
  /* Addes by Eliwood */
  $output.="Minimaler Schaden beim Spieler: <input name='mount[mountbuff][mingoodguydamage]' value=\"".htmlentities($mount['mountbuff']['mingoodguydamage'])."\" size='5'><Br/>";
    $output.="Maximaler Schaden beim Spieler: <input name='mount[mountbuff][maxgoodguydamage]' value=\"".htmlentities($mount['mountbuff']['maxgoodguydamage'])."\" size='5'><Br/>";
  /* End Adds by Eliwood */
  $output.="Minimaler Schaden beim Gegner: <input name='mount[mountbuff][minbadguydamage]' value=\"".htmlentities($mount['mountbuff']['minbadguydamage'])."\" size='5'><Br/>";
    $output.="Maximaler Schaden beim Gegner: <input name='mount[mountbuff][maxbadguydamage]' value=\"".htmlentities($mount['mountbuff']['maxbadguydamage'])."\" size='5'><Br/>";
    $output.="Lifetap: <input name='mount[mountbuff][lifetap]' value=\"".htmlentities($mount['mountbuff']['lifetap'])."\" size='5'> (Multiplikator)<Br/>";
    $output.="Schadenschild: <input name='mount[mountbuff][damageshield]' value=\"".htmlentities($mount['mountbuff']['damageshield'])."\" size='5'> (Multiplikator)<Br/>";
    $output.="Geger Schadenmodifikator: <input name='mount[mountbuff][badguydmgmod]' value=\"".htmlentities($mount['mountbuff']['badguydmgmod'])."\" size='5'> (Multiplikator)<Br/>";
    $output.="Gegner Angriffsmodifikator: <input name='mount[mountbuff][badguyatkmod]' value=\"".htmlentities($mount['mountbuff']['badguyatkmod'])."\" size='5'> (Multiplikator)<Br/>";
    $output.="Gegner Schadensmodifikator: <input name='mount[mountbuff][badguydefmod]' value=\"".htmlentities($mount['mountbuff']['badguydefmod'])."\" size='5'> (Multiplikator)<Br/>";
    //$output.=": <input name='mount[mountbuff][]' value=\"".htmlentities($mount['mountbuff'][''])."\">`n",true);

    $output.="<Br/><b>Aktivieren:</b><Br/>";
    $output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"roundstart\"".(strpos($mount['mountbuff']['activate'],"roundstart")!==false?" checked":"")."> Rundenstart<Br/>";
    $output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"offense\"".(strpos($mount['mountbuff']['activate'],"offense")!==false?" checked":"")."> Angriff<Br/>";
    $output.="<input type='checkbox' name='mount[mountbuff][activate][]' value=\"defense\"".(strpos($mount['mountbuff']['activate'],"defense")!==false?" checked":"")."> Verteidigung<Br/>";
    $output.="<Br/>";
    $output.="</td></tr>";
    $output.="</table>";
    $output.="<input type='submit' class='button' value='Speichern'></form>";
}

page_footer();
?>


