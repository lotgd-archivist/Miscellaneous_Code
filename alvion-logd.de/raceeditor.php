
<?php/*    Copyright, don't remove it!    Rasseneditor by Eliwood    Last Fix: 19.06.2004    E-Mail: basilius.sauter@hispeed.ch        Bugfixes vor alvion-logd.de    by Linus 2007 ... 2010    Last Fix: 27.06.2010    eMail: webmaster@alvion-logd.de*/require_once "common.php";require_once "func/isnewday.php";isnewday(2);page_header("Rasseneditor");output("`Q`b`cRasseneditor`c`b`0`n`n");switch ($_GET['op']){    case "":    case "create":    case "delete":    case "switch":    case "aktive":        if ($_GET['op']=="aktive"){            $sql = "SELECT * FROM race WHERE raceid='".$_GET[race]."' LIMIT 1";            $result = db_query($sql);            $row = db_fetch_assoc($result);            if ($row['active']==1){                $sql = "UPDATE race SET active=0 WHERE raceid='$_GET[race]'";                db_query($sql);                output("Die Rasse $row[name] wurde deaktiviert.`n`n");            }else{                $sql = "UPDATE race SET active=1 WHERE raceid='$_GET[race]'";                db_query($sql);                output("Die Rasse $row[name] wurde aktiviert.`n`n");            }        }        if ($_GET['op']=="create"){            $bonus = array("lp"=>$_POST['lp']                ,"atk"=>$_POST['atk']                ,"def"=>$_POST['def']                ,"wk"=>$_POST['wk']                ,"da"=>$_POST['da']                ,"mk"=>$_POST['mk']                ,"tv"=>$_POST['tv']                ,"hk"=>$_POST['hk']                ,"fw"=>$_POST['fw']                ,"cw"=>$_POST['cw']            );            $buff = array();            reset($_POST['buff']);            while (list($key,$val)=each($_POST['buff'])){                if ($val>""){                    $buff[$key]=stripslashes($val);                }            }            if ($buff['name']=="") $buff = "";            $buff = serialize($buff);                $sql = "INSERT INTO race (name,color,colorname,category,story,link,dk,bonus,buff) ".                "VALUES ('".$_POST['name']."', ".                "'".$_POST['color']."', ".                "'".$_POST['color'].$_POST['name']."',".                "'".$_POST['category']."',".                "'".$_POST['story']."',".                "'".$_POST['link']."',".                "'".$_POST['dk']."',".                "'"./*addslashes(serialize($bonus))*/serialize($bonus)."',".                "'".$buff."'".                ");";            db_query($sql);            output("Rasse wurde erstellt`n`n");        }            if ($_GET['op']=="switch"){            $sql = "UPDATE accounts "                ."SET race='".$_POST['color'].$_POST['name']."' "                ."WHERE race='".$_POST['old']."' ";            db_query($sql);            $bonus = array("lp"=>$_POST['lp']                ,"atk"=>$_POST['atk']                ,"def"=>$_POST['def']                ,"wk"=>$_POST['wk']                ,"da"=>$_POST['da']                ,"mk"=>$_POST['mk']                ,"tv"=>$_POST['tv']                ,"hk"=>$_POST['hk']                ,"fw"=>$_POST['fw']                ,"cw"=>$_POST['cw']            );            $bonus = serialize($bonus);                reset($_POST['buff']);            while (list($key,$val)=each($_POST['buff'])){                if ($val>""){                    $buff[$key]=stripslashes($val);                }            }            if ($buff['name']=="") $buff = "";            $buff = serialize($buff);                $sql = "UPDATE race "                ."SET name='".$_POST['name']."',"                ."color='".$_POST['color']."',"                ."colorname='".$_POST['color'].$_POST['name']."',"                ."category='".$_POST['category']."',"                ."story='".$_POST['story']."',"                ."link='".$_POST['link']."',"                ."dk='".$_POST['dk']."',"                ."bonus='$bonus', "                ."buff='$buff' "                ."WHERE raceid='$_GET[race]'                ";            db_query($sql);            output("Änderungen wurden gespeichert`n`n");        }            if ($_GET['op']=="delete"){            $sql = "SELECT * FROM race WHERE raceid='$_GET[race]'";            $result = db_query($sql);            $row = db_fetch_assoc($result);            $sql = "DELETE FROM race WHERE raceid='$_GET[race]'";            db_query($sql);            $sql = "UPDATE accounts SET race='' WHERE race='".$row[color].$row[name]."'";            db_query($sql);                output("Du hast gerade die Rasse ".$row[color].$row[name]."`0 gelöscht. Alle User mit dieser Rasse "                ."müssen ihre Rasse erneut wählen`n`n");        }            //Navigation:        addnav("Der Editor:");        addnav("Rasse hinzufügen","raceeditor.php?op=add");        addnav("Aktualisieren",$SCRIPT_NAME);        addnav("Sonstiges");        addnav("Zurück zur Grotte","superuser.php");        //Ausgabe:        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999'>"            ."<tr class=trhead>"            ."<td>ID:</td>"            ."<td>Name:</td>"            ."<td>Kategorie</td>"            ."<td>Drachenkillfreigabe</td>"            ."<td>Lp Bonus</td>"            ."<td>Atk Bonus</td>"            ."<td>Def Bonus</td>"            ."<td>Waldkampf Bonus</td>"            ."<td>Optionen</td>"            ."</tr>",true);        $sql = "SELECT * FROM race ORDER BY 'category' ASC";        $result = db_query($sql);        if (db_num_rows($result)){            $i = 0;            while($row = db_fetch_assoc($result)){                $bgcolor=($i%2==1?"trlight":"trdark");                $bonus = unserialize($row['bonus']);                output("<tr class=$bgcolor><td>{$row['raceid']}</td>"                    ."<td>{$row['color']}{$row['name']}`0</td>"                    ."<td>{$row['category']}</td>"                    ."<td>{$row['dk']}</td>"                    ."<td>{$bonus['lp']}</td>"                    ."<td>{$bonus['atk']}</td>"                    ."<td>{$bonus['def']}</td>"                    ."<td>{$bonus['wk']}</td>"                    ."<td>"                    ."<a href=\"raceeditor.php?op=delete&race={$row['raceid']}\" onClick='return confirm(\"Möchtest du die Rasse $row[name] wirklich löschen? Alle User mit dieser Rasse können ihre Rasse dann wieder wählen.\");'>[`4Löschen`0]</a><br>"                    ."<a href=\"raceeditor.php?op=aktive&race={$row['raceid']}\">".($row['active'] == 1 ? "[`^Deaktivieren`0]" : "[`^Aktivieren`0]")."</a><br>"                    ."<a href=\"raceeditor.php?op=change&race={$row['raceid']}\">[`2Bearbeiten`0]</a><br>"                    ."</td>"                    ."</tr>"                    ,true);                addnav("","raceeditor.php?op=delete&race={$row['raceid']}");                addnav("","raceeditor.php?op=aktive&race={$row['raceid']}");                addnav("","raceeditor.php?op=change&race={$row['raceid']}");                //output("<tr class=$bgcolor><td colspan=9>{$row['link']}</td></tr>",true);                //output("<tr class=$bgcolor><td colspan=9>{$row['story']}</td></tr>",true);                $i++;            }        }else{            output("<tr><td colspan=11>Keine Rassen vorhanden</td></tr>",true);        }    break;    case "add":        //Navigation:        addnav("Der Editor:");        addnav("Rassenübersicht","raceeditor.php");        addnav("Sonstiges");        addnav("Zurück zur Grotte","superuser.php");        //Ausgabe:        rawoutput("<table><form action='raceeditor.php?op=create' method='post'>"            ."<tr><td>Name der Rasse:</td><td><input name='name' maxlenght=50 size=30></td></tr>"            ."<tr><td>Farbcode:</td><td><input name='color' maxlenght=2 size=5></td></tr>"            ."<tr><td>Kategorie</td><td><input name='category' maxlenght=50 size=30></td></tr>"            ."<tr><td>Hintergrundgeschichte</td><td><textarea wrap=virtual name='story' rows=5 cols=30></textarea></td></tr>"            ."<tr><td>Link</td><td><textarea wrap=virtual name='link' rows=5 cols=30></textarea></td></tr>"            ."<tr><td>Ab wievielen Drachenkills verfügbar?</td><td><input name='dk' maxlenght=5 size=5></td></tr>"            ."<tr><td>Lebenspunkte Bonus:</td><td><input name='lp' maxlenght=5 size=5></td></tr>"            ."<tr><td>Angriff Bonus:</td><td><input name='atk' maxlenght=5 size=5></td></tr>"            ."<tr><td>Verteidigung Bonus:</td><td><input name='def' maxlenght=5 size=5></td></tr>"            ."<tr><td>Waldkampf Bonus:</td><td><input name='wk' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in den Dunklen Künsten:</td><td><input name='da' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in den Mytischen Kräften:</td><td><input name='mk' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in den Diebeskünsten:</td><td><input name='tv' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in der Feuermagie:</td><td><input name='fw' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in der Weißen Magie:</td><td><input name='wm' maxlenght=5 size=5></td></tr>"            ."<tr><td> </td><td> </td></tr>"            ."<tr><td valign='top'>Buff</td><td>"            ."<b>Meldungen:</b><br />"            ."Buff Name: <input name='buff[name]'><br />"            ."Meldung jede Runde: <input name='buff[roundmsg]'><Br/>"            ."Ablaufmeldung: <input name='buff[wearoff]'><Br/>"            ."Effektmeldung: <input name='buff[effectmsg]'><Br/>"            ."Kein Schaden Meldung: <input name='buff[effectnodmgmsg]'><Br/>"            ."Fehlgeschlagen Meldung: <input name='buff][effectfailmsg]'><Br/>"            ."<Br/><b>Effekt:</b><Br/>"            ."Hält Runden (nach Aktivierung): <input value='0' name='buff[rounds]' size='5'><Br/>"            ."Angriffsmulti Spieler: <input value='1' name='buff[atkmod]' size='5'><Br/>"            ."Verteidigungsmulti Spieler: <input value='1' name='buff[defmod]' size='5'><Br/>"            ."Angriffssummand Spieler: <input value='0' name='buff[atkmod2' size='5'><Br/>"            ."Verteidigungssummand Spieler: <input value='0' name='buff[defmod2]' size='5'><Br/>"            ."Regeneration: <input value='0' name='buff[regen]'><Br/>"            ."Diener Anzahl: <input value='0' name='buff[minioncount]'><Br/>"            ."Min Badguy Damage: <input value='0' name='buff[minbadguydamage]' size='5'><Br/>"            ."Max Badguy Damage: <input value='0' name='buff[maxbadguydamage]' size='5'><Br/>"            ."Lifetap: <input value='0' name='buff[lifetap]' size='5'><Br/>"            ."Damage shield: <input value='1' name='buff[damageshield]' size='5'> (multiplier)<Br/>"            ."Badguy Damage mod: <input value='1' name='buff[badguydmgmod]' size='5'> (multiplier)<Br/>"            ."Badguy Atk mod: <input value='1' name='buff[badguyatkmod]' size='5'> (multiplier)<Br/>"            ."Badguy Def mod: <inputvalue='1'  name='buff[badguydefmod]' size='5'> (multiplier)<Br/>"            ."<Br/><b>Aktiviert bei:</b><Br/>"            ."<input type='radio' name='buff[active]' value='offense' />Offense<br />"            ."<input type='radio' name='buff[active]' value='defence' />Defense<br />"            ."<input type='radio' name='buff[active]' value='startround' />Startround<br />"            ."<input type='radio' name='buff[active]' value='offense,defense' />Offense & Defense<br />"            ."<input type='radio' name='buff[active]' value='defence,startround' />Defense & Startround<br />"            ."<input type='radio' name='buff[active]' value='startround,offense' />Startround & Offense<br />"            ."<input type='radio' name='buff[active]' value='startround,offense,defense' />All<br />"            ."<Br/>"            ."</td></tr>"            ."<tr><td colspan=2>"            ."<input type='hidden' value='' name='old' maxlenght=5 size=5>"            ."<input class=button type='submit' value='Rasse erstellen'></td></tr>"        );        addnav("","raceeditor.php?op=create");    break;    case "change":        //Navigation:        addnav("Der Editor:");        addnav("Rassenübersicht","raceeditor.php");        addnav("Rasse hinzufügen","raceeditor.php?op=add");        addnav("Sonstiges");        addnav("Zurück zur Grotte","superuser.php");        //SQL-Abfragen        $sql = "SELECT * FROM race WHERE raceid='$_GET[race]'";        $result = db_query($sql);        $row = db_fetch_assoc($result);        //Navigation        //$bonus = unserialize(stripslashes($row['bonus']));        $bonus = unserialize($row['bonus']);        $buff = unserialize($row['buff']);        rawoutput("<table><form action='raceeditor.php?op=switch&race={$row['raceid']}' method='post'>            <tr><td>Name der Rasse:</td><td><input value='$row[name]' name='name' maxlenght=50 size=30></td></tr>            <tr><td>Farbcode:</td><td><input value='$row[color]' name='color' maxlenght=2 size=5></td></tr>            <tr><td>Kategorie</td><td><input value='$row[category]' name='category' maxlenght=50 size=30></td></tr>            <tr><td>Hintergrundgeschichte</td><td><textarea wrap=virtual name='story' rows=5 cols=30>$row[story]</textarea></td></tr>            <tr><td>Link</td><td><textarea wrap=virtual name='link' rows=5 cols=30>$row[link]</textarea></td></tr>            <tr><td>Ab wievielen Drachenkills verfügbar?</td><td><input value='$row[dk]' name='dk' maxlenght=5 size=5></td></tr>            <tr><td>Lebenspunkte Bonus:</td><td><input value='{$bonus[lp]}' name='lp' maxlenght=5 size=5></td></tr>            <tr><td>Angriff Bonus:</td><td><input value='{$bonus[atk]}' name='atk' maxlenght=5 size=5></td></tr>            <tr><td>Verteidigung Bonus:</td><td><input value='{$bonus[def]}' name='def' maxlenght=5 size=5></td></tr>            <tr><td>Waldkampf Bonus:</td><td><input value='{$bonus[wk]}' name='wk' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in den Dunklen Künsten:</td><td><input value='{$bonus[da]}' name='da' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in den Mytischen Kräften:</td><td><input value='{$bonus[mk]}' name='mk' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in den Diebeskünsten:</td><td><input value='{$bonus[tv]}' name='tv' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in den Feuermagie:</td><td><input value='{$bonus[fw]}' name='hk' maxlenght=5 size=5></td></tr>"            ."<tr><td>Zusätzliche Anwendungen in der Weißen Magie:</td><td><input value='{$bonus[wm]}' name='fw' maxlenght=5 size=5></td></tr>"            ."<tr><td valign='top'>Buff</td><td>"            ."<b>Meldungen:</b><br />"            ."Buff Name: <input name='buff[name]' value='{$buff['name']}'><br />"            ."Meldung jede Runde: <input name='buff[roundmsg]' value='{$buff['roundmsg']}'><Br/>"            ."Ablaufmeldung: <input name='buff[wearoff]' value='{$buff['wearoff']}'><Br/>"            ."Effektmeldung: <input name='ibuff[effectmsg]' value='{$buff['effectmsg']}'><Br/>"            ."Kein Schaden Meldung: <input name='buff[effectnodmgmsg]' value='{$buff['effectnodmgmsg']}'><Br/>"            ."Fehlgeschlagen Meldung: <input name='buff][effectfailmsg]' value='{$buff['effectfailmsg']}'><Br/>"            ."<Br/><b>Effekt:</b><Br/>"            ."Hält Runden (nach Aktivierung): <input name='buff[rounds]' value='{$buff['rounds']}' size='5'><Br/>"            ."Angriffsmulti Spieler: <input name='buff[atkmod]' value='{$buff['atkmod']}' size='5'><Br/>"            ."Verteidigungsmulti Spieler: <input name='buff[defmod]' value='{$buff['defmod']}' size='5'><Br/>"            ."Angriffssummand Spieler: <input name='buff[atkmod2]' value='{$buff['atkmod2']}' size='5'><Br/>"            ."Verteidigungssummand Spieler: <input name='buff[defmod2]' value='{$buff['defmod2']}' size='5'><Br/>"            ."Regeneration: <input name='buff[regen]' value='{$buff['regen']}'><Br/>"            ."Diener Anzahl: <input name='buff[minioncount]' value='{$buff['minioncount']}'><Br/>"            ."Min Badguy Damage: <input name='buff[minbadguydamage]' value='{$buff['minbadguydamage']}' size='5'><Br/>"            ."Max Badguy Damage: <input name='buff[maxbadguydamage]' value='{$buff['maxbadguydamage']}' size='5'><Br/>"            ."Lifetap: <input name='buff[lifetap]' size='5' value='{$buff['lifetap']}'><Br/>"            ."Damage shield: <input name='buff[damageshield]'  value='{$buff['damageshield']}'size='5'> (multiplier)<Br/>"            ."Badguy Damage mod: <input name='buff[badguydmgmod]'  value='{$buff['badguydmgmod']}'size='5'> (multiplier)<Br/>"            ."Badguy Atk mod: <input name='buff[badguyatkmod]'  value='{$buff['badguyatkmod']}'size='5'> (multiplier)<Br/>"            ."Badguy Def mod: <input name='buff[badguydefmod]'  value='{$buff['badguydefmod']}'size='5'> (multiplier)<Br/>"            ."<Br/><b>Aktiviert bei:</b><Br/>"            ."<input type='radio' name='buff[active]' value='offense' ".($buff['active']=="offense"?"checked":"")."/>Offense<br />"            ."<input type='radio' name='buff[active]' value='defence' ".($buff['active']=="defence"?"checked":"")."/>Defense<br />"            ."<input type='radio' name='buff[active]' value='startround' ".($buff['active']=="startround"?"checked":"")."/>Startround<br />"            ."<input type='radio' name='buff[active]' value='offense,defense' ".($buff['active']=="offense,defense"?"checked":"")."/>Offense & Defense<br />"            ."<input type='radio' name='buff[active]' value='defence,startround' ".($buff['active']=="defence,startround"?"checked":"")."/>Defense & Startround<br />"            ."<input type='radio' name='buff[active]' value='startround,offense' ".($buff['active']=="startround,offense"?"checked":"")."/>Startround & Offense<br />"            ."<input type='radio' name='buff[active]' value='startround,offense,defense' ".($buff['active']=="startround,offense,defense"?"checked":"")."/>All<br />"            ."<Br/>"            ."</td></tr>"            ."<tr><td colspan=2>"            ."<input type='hidden' value='".$row['color'].$row['name']."' name='old' maxlenght=5 size=5>"            ."<input class=bottom type='submit' value='Rasse speichern'></td></tr>"            ."</table>"        ,true);        addnav("","raceeditor.php?op=switch&race={$row['raceid']}");    break;}page_footer();

