<?php

/*
 *      raceeditor.php
 *      
 *      Copyright 2008 Basilius “Wasili” Sauter <basilius.sauter@hispeed.ch>
 *      
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *      
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *      
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 *
 *      * * * * * * * *
 *
 *        This is a proper version, based on the last fix of 19. June 2004
 *        All bugs should be removed, but no software is bugfree... And of
 *        course, it is compatible to every old version.
 *
 *        (However, this thing is not really good. Modules are better...)
 */
 
Require 'common.php';

// Check, if User > Sulevel 1
isnewday(2);

// Set page_header
page_header('Rasseneditor');

// Set header on output
output('<h2>`QRasseneditor`0</h2>', true);

// Main-Controll-Structer
switch ($_GET['op']) {
    default: {
        if($_GET['op'] == 'aktive') {
            $res = db_query("SELECT * FROM race WHERE raceid='".$_GET['race']."' LIMIT 1");
            $row = db_fetch_assoc($res);
            db_free_result($res);
            
            if($row['active'] == 1) {
                db_query("UPDATE race SET active='0' WHERE raceid='{$_GET['race']}'");
                output("<p>Die Rasse {$row['name']} wurde deaktiviert.</p>", true);
            }
            else {
                db_query("UPDATE race SET active='1' WHERE raceid='{$_GET['race']}'");
                output("<p>Die Rasse {$row['name']} wurde deaktiviert.</p>", true);
            }
        }
        elseif($_GET['op'] == 'create') {
            $bonus = array("lp"=>$_POST['lp']
                ,"atk"=>$_POST['atk']
                ,"def"=>$_POST['def']
                ,"wk"=>$_POST['wk']
                ,"da"=>$_POST['da']
                ,"mk"=>$_POST['mk']
                ,"tv"=>$_POST['tv']
                ,"hk"=>$_POST['hk']
                ,"fw"=>$_POST['fw']
                ,"cw"=>$_POST['cw']
            );
            
            $buff = array();
            foreach($_POST['buff'] as $key => $val) {
                $buff[$key] = stripslashes($val);
            }
            
            if(empty($buff['name'])) {
                $buff = '';
            }
            else {
                $buff = serialize($buff);
            }
            
            $sql = "INSERT INTO race (
                    name,color,colorname,category,story,link,dk,sex,bonus,buff
            ) VALUES (
                '{$_POST['name']}', 
                '{$_POST['color']}', 
                '{$_POST['color']}{$_POST['name']}',
                '{$_POST['category']}',
                '{$_POST['story']}',
                '{$_POST['link']}',
                '{$_POST['dk']}',
                '{$_POST['sex']}',
                '".serialize($bonus)."',
                '$buff'
            )";
            
            db_query($sql);
            
            rawoutput('<p>Rasse wurde erstellt.</p>');
            
        }
        elseif($_GET['op'] == 'switch') {
            $_POST['name'] = addslashes(stripslashes($_POST['name']));
            $_POST['color'] = addslashes(stripslashes($_POST['color']));
            $_POST['old'] = addslashes(stripslashes($_POST['old']));

            $sql = "UPDATE accounts 
                SET race='{$_POST['color']}{$_POST['name']}' 
                WHERE race='{$_POST['old']}' ";
            db_query($sql);
            
            $bonus = array("lp"=>$_POST['lp']
                ,"atk"=>$_POST['atk']
                ,"def"=>$_POST['def']
                ,"wk"=>$_POST['wk']
                ,"da"=>$_POST['da']
                ,"mk"=>$_POST['mk']
                ,"tv"=>$_POST['tv']
                ,"hk"=>$_POST['hk']
                ,"fw"=>$_POST['fw']
                ,"cw"=>$_POST['cw']
            );
            
            $bonus = serialize($bonus);
            
            $buff = array();
            foreach($_POST['buff'] as $key => $val) {
                $buff[$key] = stripslashes($val);
            }
            
            if(empty($buff['name'])) {
                $buff = '';
            }
            else {
                $buff = serialize($buff);
            }
            
            $_POST = array_map('stripslashes', $_POST);
            $_POST = array_map('addslashes', $_POST);
            
            $sql = "UPDATE race
                SET name='{$_POST['name']}',
                    color='{$_POST['color']}',
                    colorname='{$_POST['color']}{$_POST['name']}',
                    category='{$_POST['category']}',
                    story='{$_POST['story']}',
                    link='{$_POST['link']}',
                    dk='{$_POST['dk']}',
                    sex='{$_POST['sex']}',
                    bonus='$bonus',
                    buff='$buff'
                WHERE raceid = '{$_GET['race']}'";
            db_query($sql);
            
            // Output
            rawoutput('<p>Änderungen wurden gespeichert.</p>');
        }
        elseif($_GET['op'] == 'delete') {
            // Get the name of the race (Why do I save the racename in the accounts?!?!? Damn!)
            $res = db_query("SELECT name, color, colorname FROM race WHERE raceid='{$_GET['race']}'");
            $row = db_fetch_assoc($res);
            // Update the accounts and reset the race
            db_query("UPDATE `accounts` SET `race` = 'Unbekannt' WHERE race = '{$row['color']}{$row['name']}' OR race = '{$row['colorname']}'");
            // Delete the race now
            db_query("DELETE FROM race WHERE raceid='{$_GET['race']}'");
            
            // Output
            rawoutput("<p>Du hast gerade die Rasse ".$row[color].$row[name]."`0 gelöscht. 
                Alle User mit dieser Rasse müssen ihre Rasse erneut wählen.</p>");
        }
        
        // Default-Page, is show on every pagehit except "add" and "change"
        
        // Navigation
        addnav('Rasseneditor');
        addnav('Rasse hinzufügen', 'raceeditor.php?op=add');
        #addnav('Aktualisieren', $SCRIPT_NAME); What's to hell is that?!?
        
        addnav('Sonstiges');
        addnav('Zurück zur Grotte', 'superuser.php');
        addnav('Zurück zum Weltlichen', 'village.php');
        
        // Tabelle.
        $tablecontent = '';
        $res = db_query('SELECT * FROM race ORDER BY category ASC');
        $numrows = db_num_rows($res);
        
        if($numrows > 0) {
            $i = 0;
            while($row = db_fetch_assoc($res)) {
                $bgcolor = ++$i%2>0?'trdark':'trlight';
                $bonus = unserialize($row['bonus']);
                
                $tablecontent .= "<tr class=\"$bgcolor\">
                    <td>{$row['raceid']}</td>
                    <td>{$row['color']}{$row['name']}`0</td>
                    <td>{$row['category']}</td>
                    <td>{$row['dk']}</td>
                    <td>{$row['sex']}</td>
                    <td>{$bonus['lp']}</td>
                    <td>{$bonus['atk']}</td>
                    <td>{$bonus['def']}</td>
                    <td>{$bonus['wk']}</td>
                    <td>
                        <a href=\"raceeditor.php?op=delete&race={$row['raceid']}\" onClick='return confirm(\"Möchtest du die Rasse $row[name] wirklich löschen? Alle User mit dieser Rasse können ihre Rasse dann wieder wählen.\");'>[`4Löschen`0]</a><br>
                        <a href=\"raceeditor.php?op=aktive&race={$row['raceid']}\">".($row['active'] == 1 ? "[`^Deaktivieren`0]" : "[`^Aktivieren`0]")."</a><br>
                        <a href=\"raceeditor.php?op=change&race={$row['raceid']}\">[`2Bearbeiten`0]</a><br>
                    </td>
                </tr>";
                
                addnav("","raceeditor.php?op=delete&race={$row['raceid']}");
                addnav("","raceeditor.php?op=aktive&race={$row['raceid']}");
                addnav("","raceeditor.php?op=change&race={$row['raceid']}");
            }
        }
        else {
            $tablecontent = '<tr>
                <td colspan="9">Keine Rassen vorhanden</td>
            </tr>';
        }
        
        output('<table cellpadding="2" cellspacing="1" bgcolor="#999999">
            <tr class="trhead">
                <td>ID</td>
                <td>Name</td>
                <td>Kategorie</td>
                <td>DK</td>
                <td>sex</td>
                <td>LP</td>
                <td>ATK</td>
                <td>DEF</td>
                <td>WK</td>
                <td>Optionen</td>
            </tr>'.$tablecontent.'<tr>
                <td colspan="9">'.$numrows.' Rassen total</td>
            </tr>
        </table>', true);
        break;
    }
    
    case 'add': {
        // Navigation
        addnav('Rasseneditor');
        addnav('Rassenübersicht', 'raceeditor.php');
        
        addnav('Sonstiges');
        addnav('Zurück zur Grotte', 'superuser.php');
        addnav('Zurück zum Weltlichen', 'village.php');
        
        // Output (I know, this is *really* confusing. Sorry about that =/
        rawoutput("<form action='raceeditor.php?op=create' method='post'>
            <table><tr>
                    <td>Name der Rasse:</td>
                    <td><input name='name' maxlenght=50 size=30></td>
                </tr><tr>    
                    <td>Farbcode:</td>
                    <td><input name='color' maxlenght=2 size=5></td>
                </tr><tr>
                    <td>Kategorie</td>
                    <td><input name='category' maxlenght=50 size=30></td>
                </tr><tr>
                    <td>Hintergrundgeschichte</td>
                    <td><textarea name='story' rows=5 cols=30></textarea></td>
                </tr><tr>
                    <td>Link</td>
                    <td><textarea name='link' rows=5 cols=30></textarea></td>
                </tr><tr>
                    <td>Ab wievielen Drachenkills verfügbar?</td>
                    <td><input name='dk' maxlenght=5 size=5></td>
    
                </tr><tr>
                    <td>Für Welches Geschlecht? (0 Männlich, 1 Weiblich)</td>
                    <td><input name='sex' maxlenght=5 size=5></td>
                    </tr>
                
                <tr><td>Lebenspunkte Bonus:</td><td><input name='lp' maxlenght=5 size=5></td></tr>
                <tr><td>Angriff Bonus:</td><td><input name='atk' maxlenght=5 size=5></td></tr>
                <tr><td>Verteidigung Bonus:</td><td><input name='def' maxlenght=5 size=5></td></tr>
                <tr><td>Waldkampf Bonus:</td><td><input name='wk' maxlenght=5 size=5></td></tr>
                  <tr><td>Zusätzliche Anwendungen in den Dunkel Künsten:</td><td><input name='da' maxlenght=5 size=5></td></tr>
                <tr><td>Zusätzliche Anwendungen in den Mytischen Kräften:</td><td><input name='mk' maxlenght=5 size=5></td></tr>
                <tr><td>Zusätzliche Anwendungen in den Diebeskünsten:</td><td><input name='tv' maxlenght=5 size=5></td></tr>
            
                <tr>
                    <td> </td>
                    <td> </td>
                </tr><tr>
                    <td valign='top'>Buff</td>
                    <td>
                        <b>Meldungen:</b><br />
                        Buff Name: <input name='buff[name]'><br />
                        Meldung jede Runde: <input name='buff[roundmsg]'><Br/>
                        Ablaufmeldung: <input name='buff[wearoff]'><Br/>
                        Effektmeldung: <input name='buff[effectmsg]'><Br/>
                        Kein Schaden Meldung: <input name='buff[effectnodmgmsg]'><Br/>
                        Fehlgeschlagen Meldung: <input name='buff][effectfailmsg]'><Br/>
                        
                        <Br/><b>Effekt:</b><Br/>
                        Hält Runden (nach Aktivierung): <input value='0' name='buff[rounds]' size='5'><Br/>
                        Angriffsmulti Spieler: <input value='1' name='buff[atkmod]' size='5'><Br/>
                        Verteidigungsmulti Spieler: <input value='1' name='buff[defmod]' size='5'><Br/>
                        Angriffssummand Spieler: <input value='0' name='buff[atkmod2' size='5'><Br/>
                        Verteidigungssummand Spieler: <input value='0' name='buff[defmod2]' size='5'><Br/>
                        Regeneration: <input value='0' name='buff[regen]'><Br/>
                        Diener Anzahl: <input value='0' name='buff[minioncount]'><Br/>
                        Min Badguy Damage: <input value='0' name='buff[minbadguydamage]' size='5'><Br/>
                        Max Badguy Damage: <input value='0' name='buff[maxbadguydamage]' size='5'><Br/>
                        Lifetap: <input value='0' name='buff[lifetap]' size='5'><Br/>
                        Damage shield: <input value='1' name='buff[damageshield]' size='5'> (multiplier)<Br/>
                        Badguy Damage mod: <input value='1' name='buff[badguydmgmod]' size='5'> (multiplier)<Br/>
                        Badguy Atk mod: <input value='1' name='buff[badguyatkmod]' size='5'> (multiplier)<Br/>
                        Badguy Def mod: <inputvalue='1'  name='buff[badguydefmod]' size='5'> (multiplier)<Br/>
                        
                        <Br/><b>Aktiviert bei:</b><Br/>
                        <input type='radio' name='buff[active]' value='offense' />Offense<br />
                        <input type='radio' name='buff[active]' value='defence' />Defense<br />
                        <input type='radio' name='buff[active]' value='startround' />Startround<br />
                        <input type='radio' name='buff[active]' value='offense,defense' />Offense & Defense<br />
                        <input type='radio' name='buff[active]' value='defence,startround' />Defense & Startround<br />
                        <input type='radio' name='buff[active]' value='startround,offense' />Startround & Offense<br />
                        <input type='radio' name='buff[active]' value='startround,offense,defense' />All<br />
                        <Br/>
                    </td>
                </tr><tr>
                    <td colspan=2>
                        <input type='hidden' value='' name='old' maxlenght=5 size=5>
                        <input class=button type='submit' value='Rasse erstellen'>
                    </td>
            </tr></table>
        </form>");
        
        addnav("","raceeditor.php?op=create");
        break;
    }

    case 'change': {
        // Navigation
        addnav('Rasseneditor');
        addnav('Rassenübersicht', 'raceeditor.php');
        addnav('Rasse hinzufügen', 'raceeditor.php?op=add');

        addnav('Sonstiges');
        addnav('Zurück zur Grotte', 'superuser.php');
        addnav('Zurück zum Weltlichen', 'village.php');
        
        // Get the race from database
        $res = db_query("SELECT * FROM race WHERE raceid='{$_GET['race']}'");
        $row = db_fetch_Assoc($res);
        db_Free_Result($res);
        
        // Unserialize arrays
        $bonus = unserialize(stripslashes($row['bonus']));
        $buff = unserialize(stripslashes($row['buff']));
        
        // Editor... Strange thing, and i've no endurance to clean it up.. Sorry!
      
          rawoutput("<table><form action='raceeditor.php?op=switch&race={$row['raceid']}' method='post'>
            <tr><td>Name der Rasse:</td><td><input value='$row[name]' name='name' maxlenght=50 size=30></td></tr>
            <tr><td>Farbcode:</td><td><input value='$row[color]' name='color' maxlenght=2 size=5></td></tr>
            <tr><td>Kategorie</td><td><input value='$row[category]' name='category' maxlenght=50 size=30></td></tr>
            <tr><td>Hintergrundgeschichte</td><td><textarea wrap=virtual name='story' rows=5 cols=30>$row[story]</textarea></td></tr>
            <tr><td>Link</td><td><textarea wrap=virtual name='link' rows=5 cols=30>$row[link]</textarea></td></tr>
            <tr><td>Ab wievielen Drachenkills verfügbar?</td><td><input value='$row[dk]' name='dk' maxlenght=5 size=5></td></tr>
            <tr><td>Für Welches Geschlecht? (0 Männlich, 1 Weiblich)</td><td><input name='sex' maxlenght=5 size=5></td></tr> 
            <tr><td>Lebenspunkte Bonus:</td><td><input value='{$bonus[lp]}' name='lp' maxlenght=5 size=5></td></tr>
            <tr><td>Angriff Bonus:</td><td><input value='{$bonus[atk]}' name='atk' maxlenght=5 size=5></td></tr>
            <tr><td>Verteidigung Bonus:</td><td><input value='{$bonus[def]}' name='def' maxlenght=5 size=5></td></tr>
            <tr><td>Waldkampf Bonus:</td><td><input value='{$bonus[wk]}' name='wk' maxlenght=5 size=5></td></tr>
            
            <tr><td>Zusätzliche Anwendungen in den Dunkel Künsten:</td><td><input value='{$bonus[da]}' name='da' maxlenght=5 size=5></td></tr>
            <tr><td>Zusätzliche Anwendungen in den Mytischen Kräften:</td><td><input value='{$bonus[mk]}' name='mk' maxlenght=5 size=5></td></tr>
            <tr><td>Zusätzliche Anwendungen in den Diebeskünsten:</td><td><input value='{$bonus[tv]}' name='tv' maxlenght=5 size=5></td></tr>
            <tr><td>Zusätzliche Anwendungen in den Heiligen Kräften:</td><td><input value='{$bonus[hk]}' name='hk' maxlenght=5 size=5></td></tr>
            <tr><td>Zusätzliche Anwendungen in der Feuermagie:</td><td><input value='{$bonus[fw]}' name='fw' maxlenght=5 size=5></td></tr>
            <tr><td>Zusätzliche Anwendungen in der Chrono-Magie:</td><td><input value='{$bonus[cw]}' name='cw' maxlenght=5 size=5></td></tr>
      
              <tr><td valign='top'>Buff</td><td>
                <b>Meldungen:</b><br />
                Buff Name: <input name='buff[name]' value='{$buff['name']}'><br />
                Meldung jede Runde: <input name='buff[roundmsg]' value='{$buff['roundmsg']}'><Br/>
                Ablaufmeldung: <input name='buff[wearoff]' value='{$buff['wearoff']}'><Br/>
                Effektmeldung: <input name='ibuff[effectmsg]' value='{$buff['effectmsg']}'><Br/>
                Kein Schaden Meldung: <input name='buff[effectnodmgmsg]' value='{$buff['effectnodmgmsg']}'><Br/>
                Fehlgeschlagen Meldung: <input name='buff][effectfailmsg]' value='{$buff['effectfailmsg']}'><Br/>
                
                <Br/><b>Effekt:</b><Br/>
                Hält Runden (nach Aktivierung): <input name='buff[rounds]' value='{$buff['rounds']}' size='5'><Br/>
                Angriffsmulti Spieler: <input name='buff[atkmod]' value='{$buff['atkmod']}' size='5'><Br/>
                Verteidigungsmulti Spieler: <input name='buff[defmod]' value='{$buff['defmod']}' size='5'><Br/>
                Angriffssummand Spieler: <input name='buff[atkmod2]' value='{$buff['atkmod2']}' size='5'><Br/>
                Verteidigungssummand Spieler: <input name='buff[defmod2]' value='{$buff['defmod2']}' size='5'><Br/>
                Regeneration: <input name='buff[regen]' value='{$buff['regen']}'><Br/>
                Diener Anzahl: <input name='buff[minioncount]' value='{$buff['minioncount']}'><Br/>
                Min Badguy Damage: <input name='buff[minbadguydamage]' value='{$buff['minbadguydamage']}' size='5'><Br/>
                Max Badguy Damage: <input name='buff[maxbadguydamage]' value='{$buff['maxbadguydamage']}' size='5'><Br/>
                Lifetap: <input name='buff[lifetap]' size='5' value='{$buff['lifetap']}'><Br/>
                Damage shield: <input name='buff[damageshield]'  value='{$buff['damageshield']}'size='5'> (multiplier)<Br/>
                Badguy Damage mod: <input name='buff[badguydmgmod]'  value='{$buff['badguydmgmod']}'size='5'> (multiplier)<Br/>
                Badguy Atk mod: <input name='buff[badguyatkmod]'  value='{$buff['badguyatkmod']}'size='5'> (multiplier)<Br/>
                Badguy Def mod: <input name='buff[badguydefmod]'  value='{$buff['badguydefmod']}'size='5'> (multiplier)<Br/>
                
                <Br/><b>Aktiviert bei:</b><Br/>
                <input type='radio' name='buff[active]' value='offense' ".($buff['active']=="offense"?"checked":"")."/>Offense<br />
                <input type='radio' name='buff[active]' value='defence' ".($buff['active']=="defence"?"checked":"")."/>Defense<br />
                <input type='radio' name='buff[active]' value='startround' ".($buff['active']=="startround"?"checked":"")."/>Startround<br />
                <input type='radio' name='buff[active]' value='offense,defense' ".($buff['active']=="offense,defense"?"checked":"")."/>Offense & Defense<br />
                <input type='radio' name='buff[active]' value='defence,startround' ".($buff['active']=="defence,startround"?"checked":"")."/>Defense & Startround<br />
                <input type='radio' name='buff[active]' value='startround,offense' ".($buff['active']=="startround,offense"?"checked":"")."/>Startround & Offense<br />
                <input type='radio' name='buff[active]' value='startround,offense,defense' ".($buff['active']=="startround,offense,defense"?"checked":"")."/>All<br />
                  <Br/>
            </td></tr>
            
            <tr>
                <td colspan=2>
                   <input type='hidden' value='".$row['color'].$row['name']."' name='old' maxlenght=5 size=5>
                   <input class=bottom type='submit' value='Rasse speichern'>
                </td>
            </tr>
        </table>",true);
        
        addnav("","raceeditor.php?op=switch&race={$row['raceid']}");
        break;
    }
  }

page_footer();
?> 