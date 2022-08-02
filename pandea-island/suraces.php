<?php
/**********************************************************
 *
 * Editor für Rassen
 * by Chaosmaker
 *
 **********************************************************/
require_once "common.php";
isnewday(3);

page_header("Rasseneditor");

addnav("G?Zurück zur Grotte","superuser.php");
addnav("W?Zurück zum Weltlichen","village.php");

if ($_GET['op']=='del' && isset($_POST['newrace'])) {
    $sql = 'UPDATE accounts SET race="'.$_POST['newrace'].'",
                                                            defence=defence+'.($races[$_POST['newrace']]['buffs']['defence']-$races[$_GET['id']]['buffs']['defence']).',
                                                            attack=attack+'.($races[$_POST['newrace']]['buffs']['attack']-$races[$_GET['id']]['buffs']['attack']).',
                                                            maxhitpoints=maxhitpoints+'.($races[$_POST['newrace']]['buffs']['maxhitpoints']-$races[$_GET['id']]['buffs']['maxhitpoints']).'
                                                    WHERE race="'.$_GET['id'].'"';
    db_query($sql) or die(sql_error(LINK));
    $sql = 'DELETE FROM races WHERE raceid="'.$_GET['id'].'"';
    db_query($sql) or die(sql_error(LINK));
    unset($races[$_GET['id']]);
    adminlog();
}

if ($_GET['op']=='edit') {
    addnav("Zurück zur Rassenübersicht","suraces.php");

    if ($_POST['subop']=='save') {
        if (!is_array($_POST['buffs']['cantmarry'])) $_POST['buffs']['cantmarry'] = array();
        if ($_GET['id']!='X') {
            $sql = 'UPDATE accounts SET defence=defence+'.($_POST['buffs']['defence']-$races[$_GET['id']]['buffs']['defence']).',
                                                                    attack=attack+'.($_POST['buffs']['attack']-$races[$_GET['id']]['buffs']['attack']).',
                                                                    maxhitpoints=maxhitpoints+'.($_POST['buffs']['maxhitpoints']-$races[$_GET['id']]['buffs']['maxhitpoints']).'
                                                            WHERE race="'.$_GET['id'].'"';
            db_query($sql) or die(sql_error(LINK));
            $sql = "UPDATE races SET title='$_POST[title]',
                                                            title_plural='$_POST[title_plural]',
                                                            description='$_POST[description]',
                                                            grownup='$_POST[grownup]',
                                                            color='$_POST[color]',
                                                            buffs='".addslashes(serialize($_POST['buffs']))."',
                                                            activated='$_POST[activated]',
                                                            adminonly='$_POST[adminonly]',
                                                            mindk='$_POST[mindk]',
                                                            maxdk='$_POST[maxdk]',
                                                            nopvp='$_POST[nopvp]',
                                                            rprace='$_POST[rprace]',
                                                            undeadbonus='$_POST[undeadbonus]',
                                                            undeadfights='$_POST[undeadfights]'
                            WHERE raceid='$_GET[id]'";
            db_query($sql) or die(sql_error(LINK));
            output('`^Änderung gespeichert!`0`n');
        } else {
            $sql = "INSERT INTO races (title,title_plural,description,grownup,color,buffs,activated,adminonly,mindk,maxdk,nopvp,rprace,undeadbonus,undeadfights)
                            VALUES('$_POST[title]','$_POST[title_plural]','$_POST[description]','$_POST[grownup]','$_POST[color]','".addslashes(serialize($_POST['buffs']))."','$_POST[activated]','$_POST[adminonly]','$_POST[mindk]','$_POST[maxdk]','$_POST[nopvp]','$_POST[rprace]','$_POST[undeadbonus]','$_POST[undeadfights]')";
            db_query($sql) or die(sql_error(LINK));
            output('`^Rasse erstellt!`0`n');
            $_GET['id'] = db_insert_id(LINK);
        }
        $races[$_GET['id']] = $_POST;
        $races[$_GET['id']]['raceid'] = $_GET['id'];
        adminlog();
    }

    //$sql = 'SELECT * FROM races WHERE raceid='.$_GET['id'];
    //$row = db_fetch_assoc(db_query($sql));
    if ($_GET['id']!='X') {
        $row = $races[$_GET['id']];
        $row['description'] = str_replace('`','``',$row['description']);
        $row['grownup'] = str_replace('`','``',$row['grownup']);
        output('`c`bRasse editieren`b`c`n`n');
    }
    else {
        $row = array('title'=>'',
                        'title_plural'=>'',
                        'activated'=>1,
                        'adminonly'=>0,
                        'nopvp'=>0,
                        'rprace'=>0,
                        'mindk'=>0,
                        'maxdk'=>0,
                        'undeadfights'=>0,
                        'undeadbonus'=>0,
                        'description'=>'',
                        'grownup'=>'',
                        'color'=>'`7',
                        'buffs'=>array('cantmarry'=>array()));
        output('`c`bRasse erstellen`b`c`n`n');
    }
    output('<form action="suraces.php?op=edit&id='.$_GET['id'].'" method="post">',true);
    output('<input type="hidden" name="subop" value="save">',true);
    addnav('','suraces.php?op=edit&id='.$_GET['id']);
    output('<table>',true);
    output('<tr><td>Rassenname:</td><td><input type="text" name="title" maxlength="30" value="'.$row['title'].'"></td></tr>',true);
    output('<tr><td>Plural:</td><td><input type="text" name="title_plural" maxlength="30" value="'.$row['title_plural'].'"></td></tr>',true);
    output('<tr><td>aktiviert:</td><td><select name="activated" size="1"><option value="1">ja</option><option value="0" '.($row['activated']==0?'selected="selected"':'').'>nein</option></select></td></tr>',true);
    output('<tr><td>nur für Admins:</td><td><select name="adminonly" size="1"><option value="0">nein</option><option value="1" '.($row['adminonly']==1?'selected="selected"':'').'>ja</option></select></td></tr>',true);
    output('<tr><td>PVP erlauben:</td><td><select name="nopvp" size="1"><option value="0">ja</option><option value="1" '.($row['nopvp']==1?'selected="selected"':'').'>nein</option></select></td></tr>',true);
    output('<tr><td>RolePlay-Auszeichnung benötigt:</td><td><select name="rprace" size="1"><option value="0">nein</option><option value="1" '.($row['rprace']==1?'selected="selected"':'').'>ja</option></select></td></tr>',true);
    output('<tr><td>erforderliche Drachenkills:</td><td><input type="text" name="mindk" size="3" maxlength="3" value="'.$row['mindk'].'"></td></tr>',true);
    output('<tr><td>maximale Drachenkills (0 = aus):</td><td><input type="text" name="maxdk" size="3" maxlength="3" value="'.$row['maxdk'].'"></td></tr>',true);
    #UNDEADMODIFIKATION ang3l@arcor.de
    output('<tr><td>zusätzliche LP in Unterwelt:</td><td><input type="text" name="undeadbonus" size="3" maxlength="3" value="'.$row['undeadbonus'].'"></td></tr>',true);
    output('<tr><td>zusätzliche Grabkämpfe:</td><td><input type="text" name="undeadfights" size="3" maxlength="3" value="'.$row['undeadfights'].'"></td></tr>',true);
    #end of modifikation
    output('<tr><td valign="top">Beschreibung:</td><td><textarea name="description" cols="30" rows="4">'.$row['description'].'</textarea></td></tr>',true);
    output('<tr><td valign="top">Herkunft:</td><td><textarea name="grownup" cols="30" rows="4">'.$row['grownup'].'</textarea>`n(mit &lt;a&gt; und &lt;/a&gt; den Bereich einschließen,`nder als Link dargestellt werden soll)</td></tr>',true);
    output('<tr><td>Farbcode:</td><td><input type="text" name="color" size="2" maxlength="2" value="`'.$row['color'].'"></td></tr>',true);
    output('<tr><td colspan="2">Buffs:</td></tr>',true);
    output('<tr><td>- Gold (in % des Normalen, >=0!):</td><td><input type="text" name="buffs[goldfactor]" size="3" maxlength="3" value="'.$row['buffs']['goldfactor'].'"></td></tr>',true);
    output('<tr><td>- Angriffspunkte:</td><td><input type="text" name="buffs[attack]" size="3" maxlength="3" value="'.$row['buffs']['attack'].'"></td></tr>',true);
    output('<tr><td>- Verteidigungspunkte:</td><td><input type="text" name="buffs[defence]" size="3" maxlength="3" value="'.$row['buffs']['defence'].'"></td></tr>',true);
    output('<tr><td>- Lebenspunkte:</td><td><input type="text" name="buffs[maxhitpoints]" size="3" maxlength="3" value="'.$row['buffs']['maxhitpoints'].'"></td></tr>',true);
    output('<tr><td>- Waldkämpfe:</td><td><input type="text" name="buffs[fight]" size="3" maxlength="3" value="'.$row['buffs']['fight'].'"></td></tr>',true);
    output('<tr><td>- feste Waldkämpfe (0 = aus):</td><td valign="top"><input type="text" name="buffs[absfight]" size="3" maxlength="3" value="'.$row['buffs']['absfight'].'"></td></tr>',true);
    output('<tr><td>- keine WK durch Drachenpunkte:</td><td><input type="checkbox" name="buffs[nodragonwk]" value="1" '.($row['buffs']['nodragonwk']==1?'checked="checked"':'').'></td></tr>',true);
    output('<tr><td>- keine WK durch Donationpoints:</td><td><input type="checkbox" name="buffs[nodonationwk]" value="1" '.($row['buffs']['nodonationwk']==1?'checked="checked"':'').'></td></tr>',true);
    output('<tr><td>- keine WK durch Stimmung:</td><td><input type="checkbox" name="buffs[nospiritwk]" value="1" '.($row['buffs']['nospiritwk']==1?'checked="checked"':'').'></td></tr>',true);
    output('<tr><td>- keine WK durch Tiere:</td><td><input type="checkbox" name="buffs[nohorsewk]" value="1" '.($row['buffs']['nohorsewk']==1?'checked="checked"':'').'></td></tr>',true);
    output('<tr><td valign="top">- Heirat unmöglich mit:</td><td><select name="buffs[cantmarry][]" size="'.count($races).'" multiple="multiple">',true);
    foreach ($races AS $tihsrace) {
        if (in_array($tihsrace['raceid'],$row['buffs']['cantmarry'])) $selected = 'selected="selected"';
        else $selected = '';
        output('<option value="'.$tihsrace['raceid'].'" '.$selected.'>'.$tihsrace['title'].'</option>',true);
    }
    output('</select></td></tr></table>',true);
    output('`n<input type="submit" class="button" value="Rasse Speichern"></form>',true);

    if ($_GET['id']!=$startrace['raceid']) {
        output('`n`c`bRasse löschen`b`c`n');
        output('<form action="suraces.php?op=del&id='.$_GET['id'].'" method="post">',true);
        addnav('','suraces.php?op=del&id='.$_GET['id']);
        output('`4ACHTUNG! Hiermit kannst du einiges kaputtmachen! Lösche NUR nicht verwendete Rassen oder dann, wenn kein Spieler mit der Rasse spielt!`0`n');
        $sql = "SELECT COUNT(acctid) AS zahl, SUM(laston >= '".date("Y-m-d H:i:s",strtotime("-".getsetting("LOGINTIMEOUT",900)." sec"))."' AND loggedin=1) AS online FROM accounts WHERE race='".$_GET['id']."'";
        $no = db_fetch_assoc(db_query($sql));
        output('`4Momentan haben '.$no['zahl'].' Spieler diese Rasse, davon sind '.$no['online'].' gerade aktiv.`0`n');
        output('Welche Rasse sollen die betroffenen Spieler bekommen?`n');
        output('<select name="newrace" size="1">',true);
        foreach ($races AS $tihsrace) {
            if ($tihsrace['raceid']==$_GET['id']) continue;
            output('<option value="'.$tihsrace['raceid'].'">'.$tihsrace['title'].'</option>',true);
        }
        output('</select>',true);
        output('<input type="submit" class="button" value="Rasse löschen" onClick="return confirm(\'Bist du dir ganz sicher?\');"></form>',true);
    }
}
else {
    output('`c`bRasseneditor`b`c`n`n');
    $sql = 'SELECT race, COUNT(*) AS zahl FROM accounts GROUP BY race';
    $result = db_query($sql);
    while ($row = db_fetch_assoc($result)) $races[$row['race']]['count'] = $row['zahl'];
    foreach ($races AS $tihsrace) {
        output('<a href="suraces.php?op=edit&id='.$tihsrace['raceid'].'">'.$tihsrace['title'].'</a> ('.(int)$tihsrace['count'].' Spieler)`n',true);
        addnav('','suraces.php?op=edit&id='.$tihsrace['raceid']);
    }
    output('`n<a href="suraces.php?op=edit&id=X">Neue Rasse</a>',true);
    addnav('','suraces.php?op=edit&id=X');
}

page_footer();
?>