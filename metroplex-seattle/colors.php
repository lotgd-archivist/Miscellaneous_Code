
<?php

/////////////////////////////////////////////////////////////////////////////////
///   Editor für Hack von Eliwood & Serra (Farbcodes in der Datenbank)        ///
///   Autor: Feranor          Idee: Eliwood & Feranor                         ///
///   geschrieben am: 6.11.05                                                 ///
///   Erweiterungen und Fehlerkorrektur: Linus für alvion-logd.de             ///
///   in 10/2007                                                              ///
///   Voraussetzungen:                                                        ///
///    - "Farbcodes in der Datenbank"-Hack by Eliwood & Serra                 ///
///    - Verlinkung in der superuser.php ("addnav("Farbcodes","colors.php")") ///
/////////////////////////////////////////////////////////////////////////////////

    require_once "common.php";

    page_header("Farbcodes");
   $session[user][ort]='Administration';

    if ($_GET[op] == "add") {
        $show = false;

        addnav("Zurück","colors.php");
        addnav("","colors.php?op=add2");
        addnav("","colorpicker.php");

        rawoutput("<form action='colors.php?op=add2' method='post' name='form1'>");
        rawoutput("<table bgcolor='#999999' cellspacing='1' cellpadding='2' align='center'>");
        rawoutput("<tr class='trlight'><td class='trhead'>Code (ohne &#0096;, 1 Zeichen)</td><td><input name='code'></td></tr>");
        rawoutput("<tr class='trdark'><td class='trhead'>HEX-Farbe (ohne #) <a href='colorpicker.php' target='_blank'>Farbauswähler</a></td><td><input name='color'></td></tr>");
        rawoutput("<tr class='trlight'><td class='trhead'>HTML-Tag</td><td><input name='tag'></td></tr>");
        rawoutput("<tr class='trdark'><td class='trhead'>Style</td><td><input name='style'></td></tr>");
        rawoutput("<tr class='trlight'><td class='trhead'>dürfen User benutzen</td><td><input type='checkbox' name='allowed'></td></tr>");
        rawoutput("</table><center><input type='submit' value='erstellen'></center></form>");
    } elseif ($_GET[op] == "add2") {
        $show = true;
        $sql = "SELECT * FROM appoencode";
        $result = db_query($sql);
        $codes=array();
        $i=0;
        while($c = DB_Fetch_Assoc($result)) {
            $codes[$i] = $c['code'];
            $i++;
        }
        if (in_array($_POST[code], $codes)) {
            output("`$ Code (&#0096;$_POST[code]) bereits vorhanden.`0`n`n",true);
        } else {
            if ($_POST[code] == "") {
                output("`$ Bitte korrigiere deine Eingabe!`0`n`n");
            } else {
                if ($_POST[color] == "" && $_POST[tag] == "") {
                    output("`$ Es muss entweder eine Farbe oder ein Tag angegeben werden.`0`n`n");
                } else {
                    $sql = "INSERT INTO appoencode SET code='".$_POST[code]."',color=";

                    if ($_POST[color] == "") $sql .= "NULL";
                    else $sql .= "'".$_POST[color]."'";

                    $sql .= ",tag=";

                    if ($_POST[tag] == "") $sql .= "NULL";
                    else $sql .= "'".$_POST[tag]."'";

                    $sql .= ",style=";

                    if ($_POST[style] == "") $sql .= "NULL";
                    else $sql .= "'".$_POST[style]."'";

                    $sql .= ",allowed='";
                    if ($_POST[allowed] == "on") $sql .= "1'";
                    else $sql .= "0'";
                    db_query($sql);
                    output("<h2>`^ Farbcode erstellt.`0</h2><br/>",true);
                }
            }
        }
    } elseif ($_GET[op] == "del") {
        $sql = "DELETE FROM appoencode WHERE id=$_GET[id]";
        db_query($sql);
        output("<h2>`^ Farbcode erfolgreich gelöscht!`0</h2><br/>",true);
        $show = true;
    } elseif ($_GET[op] == "edit") {
        $show = false;
        addnav("Zurück","colors.php");
        addnav("","colors.php?op=edit2&id=$_GET[id]");
        addnav("","colorpicker.php");
        $sql = "SELECT code,color,tag,style,allowed FROM appoencode WHERE id=$_GET[id]";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);

        rawoutput("<form action='colors.php?op=edit2&id=$_GET[id]' method='post'>");
        rawoutput("<table bgcolor='#999999' cellspacing='1' cellpadding='2' align='center'>");
        rawoutput("<tr class='trlight'><td class='trhead'>Code (ohne &#0096;, 1 Zeichen)</td><td><input name='code' value='$row[code]'></td></tr>");
        rawoutput("<tr class='trdark'><td class='trhead'>HEX-Farbe (ohne #) <a href='colorpicker.php' target='_blank'>Farbauswähler</a></td><td><input name='color' value='".strtoupper($row[color])."'></td></tr>");
        rawoutput("<tr class='trlight'><td class='trhead'>HTML-Tag</td><td><input name='tag' value='$row[tag]'></td></tr>");
        rawoutput("<tr class='trdark'><td class='trhead'>Style</td><td><input name='style' value='$row[style]'></td></tr>");
        rawoutput("<tr class='trlight'><td class='trhead'>dürfen User benutzen</td><td><input type='checkbox' name='allowed' ".($row[allowed] ? "checked" : "")."></td></tr>");
        rawoutput("</table><center><input type='submit' value='ändern'></center></form>");
    } elseif ($_GET[op] == "edit2") {
        $show = true;
        $sql = "SELECT * FROM appoencode WHERE code='".$_POST['code']."'";
        $result = db_query($sql);
        $codes=array();
        $i=0; $count=0;
        while($c = DB_Fetch_Assoc($result)) {
            if(($c['code']===$_POST['code'])&&($c['id']!=$_GET['id'])) $count++;
            $i++;
        }

        if ($count > 0) {
            output("<h2>`$ Code (&#0096;$_POST[code]) bereits vorhanden.`0</h2><br/>",true);
        } else {
            if ($_POST['code'] == "") {
                output("<h2>`$ Kein Code angegeben. Bitte korrigiere deine Eingabe!`0</h2><br/>",true);
            } else {
                if ($_POST['color'] == "" && $_POST['tag'] == "") {
                    output("<h2>`$ Es muss entweder eine Farbe oder ein Tag angegeben werden.`0</h2><br/>",true);
                } else {
                    $sql = "UPDATE appoencode SET code='".$_POST['code']."',color=";

                    if ($_POST['color'] == "") $sql .= "NULL";
                    else $sql .= "'".$_POST['color']."'";

                    $sql .= ", tag=";
                    if ($_POST['tag'] == "") $sql .= "NULL";
                    else $sql .= "'".$_POST['tag']."'";

                    $sql .= ",style=";
                    if ($_POST['style'] == "") $sql .= "NULL";
                    else $sql .= "'".$_POST['style']."'";

                    $sql .= ",allowed='";
                    if ($_POST['allowed'] == "on") $sql .= "1'";
                    else $sql .= "0'";

                    $sql .= " WHERE id='".$_GET['id']."'";
                    db_query($sql);
                    output("<h2>`^ Änderungen erfolgreich übernommen.</h2><br/>",true);
                }
            }
        }
    } elseif ($_GET[op] == "reset") {
        $show = true;
        $sql = array();
        $sql[] = "TRUNCATE TABLE appoencode";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (1, '1', '0000B0', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (2, '2', '00B000', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (3, '3', '00B0B0', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (4, '4', 'B00000', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (5, '5', 'B000CC', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (6, '6', 'B0B000', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (7, '7', 'B0B0B0', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (8, '8', 'DDFFBB', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (9, '9', '0070FF', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (10, '!', '0000FF', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (11, '@', '00FF00', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (12, '#', '00FFFF', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (13, '$', 'FF0000', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (14, '%', 'FF00FF', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (15, '^', 'FFFF00', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (16, '&', 'FFFFFF', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (17, ')', '999999', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (18, '~', '222222', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (19, 'Q', 'FF6600', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (20, 'q', 'FF9900', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (21, 'r', 'FF88EE', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (22, 'R', 'EEBBEE', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (23, 'V', '9A5BEE', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (24, 'v', 'AABBEE', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (25, 'g', '77EE77', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (26, 'G', 'aaff99', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (27, 'T', '6b563f', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (28, 't', 'F8DB83', NULL, NULL, '1');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (38, 'c', NULL, 'center', NULL, '0');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (39, 'H', NULL, 'span', 'class=''navhi''', '0');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (40, 'b', NULL, 'strong', NULL, '0');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (41, '¬', NULL, 'pre', NULL, '0');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (42, 'i', NULL, 'i', NULL, '0');";
        $sql[] = "INSERT INTO appoencode (id, code, color, tag, style, allowed) VALUES (43, 'n', NULL, 'br /', NULL, '0');";

        for ($i = 0;$i < count($sql);$i++) {
            db_query($sql[$i]);
        }
        output("Standartfarben erfolgreich wiederhergestellt.`n`n");
    } else {
        $show = true;
    }


    if ($show == true) {
        addnav("Zurück zur Verwaltung","superuser.php");
        addnav("W?Zurück zum Stadtplatz","village.php");
            addnav("Farben");
//            addnav("Standartfarben wiederherstellen","colors.php?op=reset");
            addnav("Hinzufügen","colors.php?op=add");
            addnav("Aktualisieren","colors.php");
       

        $sql = "SELECT id,code,color,tag,style,allowed FROM appoencode ORDER BY id";
        $result = db_query($sql);
        output("<h3>`$ `bBitte nichts an den HTML-Tags ändern! Das sind die ID's 38 ... 43!`b`0</h3><br/>",true);

        output("Vorhandene Farben:");
        output("<table bgcolor='#999999' cellspacing='1' cellpadding='2' align='center'><tr class='trhead'><td>ID</td><td>Code</td><td>HEX-Farbe</td><td>Farbe</td><td>HTML-Tag</td><td>Style</td><td>dürfen User benutzen</td>",true);
        output("<td>Ops.</td>",true);
        output("</tr>",true);
        $i = 0;
        while ($row = db_fetch_assoc($result)) {
            $i++;

            $code = "&#0096;$row[code]";
            $color = "<font color='#".$row['color']."'>".strtoupper($row['color'])."</font>";
            $tag = $row[tag];
            $style = $row[style];

            $id = $row[id];
            $color2 = $row[color];
            $bgcolor = ($i % 2 == 1 ? "trlight" : "trdark");
            output("<tr class='$bgcolor'><td>$id</td><td>$code</td><td>$color</td><td width='50' height='20' bgcolor='#$color2'></td><td>$tag</td><td>$style</td><td>",true);
            if ($row[allowed] == 0) output("nein");
            else output("ja");
                output("</td><td>[<a href='colors.php?op=del&id=$id' style='color:red;' onClick='return confirm(\"Diese Farbe wirklich löschen? \");'>löschen</a>|<a href='colors.php?op=edit&id=$id'>ändern</a>]</td>",true);
                addnav("","colors.php?op=del&id=$id");
                addnav("","colors.php?op=edit&id=$id");
            output("</tr>",true);
        }
        output("</table>",true);
    }

    // ich kann euch natürlich nicht zwingen das Copyright drinnen zu lassen, bitte euch aber darum
    output("<br/><center>&copy; 2005 by Feranor</center>",true);

    page_footer();
?> 
