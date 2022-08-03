<?php
define('NOBIO', true);
require_once "common.php";
if ($session[user][loggedin]) {
addcommentary();
session_write_close();
popup_header("Ausrufer von Arda");
output(($session[user][superuser]>=1?" [<a href='motd.php?op=add'>MoTD erstellen</a>|<a href='motd.php?op=addpoll'>Umfrage erstellen</a>]`n`n":""), true);

output("`c`i`WUnkenntnis der ausgerufenen Neuigkeiten wird nicht als Ausrede bei Verstößen akzeptiert! `nEbenso sind auch ältere Neuigkeiten zu beachten!`i`c`n");
output("<hr/>`n",true);

function motditem($subject, $poster, $zeit, $body) {
    output("<table width=\"100%\" style=\"border-width:0px\"><tr><td align=\"left\">`b".stripslashes($subject)."`b</td>", true);
    output("<td align=\"right\">$poster, $zeit</td></tr>",true);
    output("<tr><td colspan=\"2\">",true);
        output(stripslashes($body),true);
        output("</td></tr></table>",true);
    output("<hr>",true);
}

function motditem2($subject, $body) {
    output("<table width=\"100%\" style=\"border-width:0px\"><tr><td align=\"left\">`b$subject`b</td><tr>", true);
    output("<tr><td colspan=\"2\">$body</td></tr></table>", true);
    output("<hr>",true);
}
function pollitem($id, $subject, $poster, $zeit, $body) {
    global $session;
    $sql = "SELECT count(resultid) AS c, MAX(choice) AS choice FROM pollresults WHERE motditem='$id' AND account='{$session['user']['acctid']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $choice = $row['choice'];
    $body = unserialize($body);
    if ($row['c'] == 0 && 0) {
        output("<form action='motd.php?op=vote' method='POST'>", true);
        output("<input type='hidden' name='motditem' value='$id'>", true);
        output("<table width=\"100%\" style=\"border-width:0px\"><tr><td align=\"left\">`bUmfrage: ".stripslashes($subject)."`b</td>", true);
        output("<td align=\"right\">$poster, $zeit</td></tr>", true);
        output("<tr><td colspan=\"2\">".stripslashes($body['body'])."</td></tr></table>", true);
        while (list($key,$val) = each($body['opt'])){
            if (trim($val)!=""){
                output("`n<input type='radio' name='choice' value='$key'>", true);
                output(stripslashes($val));
            }
        }
        output("`n<input type='submit' class='button' value='Abstimmen'>", true);
        output("</form>", true);
    } elseif ($session[user][loggedin]) {
        output("<form action='motd.php?op=vote' method='POST'>", true);
        output("<input type='hidden' name='motditem' value='$id'>", true);
        output("<table width=\"100%\" style=\"border-width:0px\"><tr><td align=\"left\">`bUmfrage: $subject`b</td>", true);
        output("<td align=\"right\">$poster, $zeit</td></tr>", true);
        output("<tr><td colspan=\"2\">".stripslashes($body['body'])."</td></tr></table>", true);
        $sql = "SELECT count(resultid) AS c, choice FROM pollresults WHERE motditem='$id' GROUP BY choice ORDER BY choice";
        $result = db_query($sql);
        $choices = array();
        $totalanswers = 0;
        $maxitem = 0;
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $choices[$row['choice']] = $row['c'];
            $totalanswers += $row['c'];
            if ($row['c'] > $maxitem) {
                $maxitem = $row['c'];
            }
        }
        while (list($key, $val) = each($body['opt'])) {
            if (trim($val) != ""){
                if ($totalanswers<=0) $totalanswers=1;
                $percent = round($choices[$key] / $totalanswers * 100, 1);
                output("`n<input type='radio' name='choice' value='$key'".($choice == $key ? " checked" : "").">", true);
                output(stripslashes($val)." (".(int)$choices[$key]." - $percent%)");
                if ($maxitem == 0) {
                    $width = 1;
                } else {
                    $width = round(($choices[$key] / $maxitem) * 400, 0);
                }
                $width = max($width, 1);
                output("`n<img src='images/rule1.gif' width='$width' height='2' alt='$percent'>", true);
                //output(stripslashes($val)."`n");
            }
        }
        output("`n`n<input type='submit' class='button' value='Abstimmen'></form>", true);
    } else {
        output("<table width=\"100%\" style=\"border-width:0px\"><tr><td align=\"left\">`bUmfrage: $subject`b</td>", true);
        output("<td align=\"right\">$poster, $zeit</td></tr>", true);
        output("<tr><td colspan=\"2\">".stripslashes($body['body'])."</td></tr></table>", true);
        $sql = "SELECT count(resultid) AS c, choice FROM pollresults WHERE motditem='$id' GROUP BY choice ORDER BY choice";
        $result = db_query($sql);
        $choices = array();
        $totalanswers = 0;
        $maxitem = 0;
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $choices[$row['choice']] = $row['c'];
            $totalanswers += $row['c'];
            if ($row['c'] > $maxitem) {
                $maxitem = $row['c'];
            }
        }
        while (list($key, $val) = each($body['opt'])) {
            if (trim($val) != "") {
                if ($totalanswers<=0) $totalanswers=1;
                $percent = round($choices[$key] / $totalanswers * 100,1);
                output("`n".stripslashes($val)." (".(int)$choices[$key]." - $percent%)");
                if ($maxitem==0) { $width=1; } else { $width = round(($choices[$key]/$maxitem) * 400,0); }
                $width = max($width, 1);
                //output("`n<img src='images/rule1.gif' width='$width' height='2' alt='$percent'>", true);
            }
        }
        output("`n`n");
    }
    output("<hr>",true);
}
if ($_GET[op] == "vote") {
    if ($session[user][loggedin]) {
        $sql = "DELETE FROM pollresults WHERE motditem='{$_POST['motditem']}' AND account='{$session['user']['acctid']}'";
        db_query($sql);
        $sql = "INSERT INTO pollresults (choice,account,motditem) VALUES ('{$_POST['choice']}','{$session['user']['acctid']}','{$_POST['motditem']}')";
        db_query($sql);
        header("Location: motd.php");
        exit();
    } else {
        output("Nicht eingeloggt!");
    }
} elseif ($_GET[op] == "addpoll") {
    if($session[user][superuser]>=1) {
        if ($_POST['subject'] == "" || $_POST['body'] == "" || $_POST[number] != "" || $_POST['opt'][0] == "" || $_POST['opt'][1] == "") {
            if ($_POST[number] < 2) {
                $number = 2;
            } else {
                $number = $_POST[number];
            }
            output("<form action='motd.php?op=addpoll' method='POST'>", true);
            addnav("","motd.php?op=add");
            output("Titel: <input type='text' size='50' name='subject' value=\"".HTMLEntities(stripslashes($_POST[subject]))."\">`n", true);
            output("<textarea class='input' name='body' cols='50' rows='7'>".HTMLEntities(stripslashes($_POST[body]))."</textarea>`n`n", true);
            for ($i = 0; $i < $number; $i++) {
                output("Option ".($i + 1).": <input name='opt[]' value='".$_POST['opt'][$i]."'>`n",true);
            }
            output("`n<input type='text' size='2' maxlength='2' name='number'> Zahl eintragen, um Anzahl der Optionen zu ändern`n`n", true);
            output("<input type='submit' class='button' value='Hinzufügen'></form>", true);
        } else {
            $body = array("body"=>$_POST['body'],"opt"=>$_POST['opt']);
            $sql = "INSERT INTO motd (motdtitle,motdbody,motddate,motdtype,poster) VALUES (\"$_POST[subject]\",\"".addslashes(serialize($body))."\",now(),1,\"".$session[user][login]."\")";
            db_query($sql);
            header("Location: motd.php");
            exit();
        }
    } else {
        if ($session[user][loggedin]) {
            //$session[user][hitpoints] = 0;
            //$session[user][alive] = 0;
            $session[user][experience] = round($session[user][experience] * 0.9, 0);
            addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
            output("Du hast versucht, die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weißt du nicht mehr.");
            saveuser();
        }
    }
} elseif ($_GET[op] == "add") {
    if ($session[user][superuser] >= 1) {
        if ($_POST[subject] == "" || $_POST[body] == "") {
            output("<form action='motd.php?op=add' method='POST'>",true);
            addnav("","motd.php?op=add");
            output("Titel: <input type='text' size='50' name='subject' value=\"".HTMLEntities(stripslashes($_POST[subject]))."\">`n", true);
            output("<textarea class='input' name='body' cols='50' rows='7'>".HTMLEntities(stripslashes($_POST[body]))."</textarea>`n`n", true);
            output("<input type='submit' class='button' value='Hinzufügen'></form>",true);
        } else {
            $sql = "INSERT INTO motd (motdtitle,motdbody,motddate,poster) VALUES (\"$_POST[subject]\",\"$_POST[body]\",now(),\"".$session[user][login]."\")";
            db_query($sql);
            header("Location: motd.php");
            exit();
        }
    } else {
        if ($session[user][loggedin]) {
            //$session[user][hitpoints] = 0;
            //$session[user][alive] = 0;
            $session[user][experience] = round($session[user][experience] * 0.9, 0);
            addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
            output("Du hast versucht, die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weißt du nicht mehr.");
            saveuser();
        }
    }
} elseif ($_GET[op] == "del") {
    if ($session[user][superuser] >= 1){
            $sql = "DELETE FROM motd WHERE motditem=\"$_GET[id]\"";
            db_query($sql);
            $sql = "DELETE FROM pollresults WHERE motditem=\"$_GET[id]\"";
            db_query($sql);
            header("Location: motd.php");
            exit();
    } else {
        if ($session[user][loggedin]) {
            //$session[user][hitpoints] = 0;
            //$session[user][alive] = 0;
            $session[user][experience] = round($session[user][experience] * 0.9, 0);
            addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
            output("Du hast versucht, die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weißt du nicht mehr.");
            saveuser();
        }
    }
} elseif ($_GET[op] == "edit") {
    if ($session[user][superuser] >= 1) {
        if ($_POST[subject]=="" || $_POST[body] == "") {
            $sql = "SELECT motdtitle, motdbody FROM motd WHERE motditem=\"$_GET[id]\"";
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            $row[motdbody] = preg_replace("/(<br \/><br \/>`0<span style='font-size:10'>Bearbeitet von)(.*)(Uhr\.<\/span>)/", "", $row[motdbody]);
            output("<form action='motd.php?op=edit&id=$_GET[id]' method='POST'>", true);
            addnav("","motd.php?op=edit&id=$_GET[id]");
            rawoutput("Titel: <input type='text' size='50' name='subject' value=\"".HTMLEntities(stripslashes($row[motdtitle]))."\"><br />");
            rawoutput("<textarea class='input' name='body' cols='50' rows='7'>".HTMLEntities(stripslashes($row[motdbody]))."</textarea><br /><br />");
            output("<input type='submit' class='button' value='Hinzufügen'></form>", true);
        } else {
            $body = $_POST[body]."<br /><br />`0<span style='font-size:10'>Bearbeitet von ".$session[user][login].", ".date("j.n.Y, G:i")." Uhr.</span>";
            $sql = "UPDATE motd SET motdtitle=\"$_POST[subject]\", motdbody=\"$body\" WHERE motditem=\"$_GET[id]\"";
            db_query($sql);
            header("Location: motd.php");
            exit();
        }
    } else {
        if ($session[user][loggedin]) {
            //$session[user][hitpoints] = 0;
            //$session[user][alive] = 0;
            $session[user][experience]=round($session[user][experience]*0.9,0);
            addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
            output("Du hast versucht, die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weißt du nicht mehr.");
            saveuser();
        }
    }
} elseif ($_GET[op] == "editpoll") {
    if($session[user][superuser]>=1) {
        if ($_POST['subject'] == "" || $_POST['body'] == "" || $_POST[number] != "" || $_POST['opt'][0] == "" || $_POST['opt'][1] == "") {
            $sql = "SELECT motdtitle, motdbody FROM motd WHERE motditem=\"$_GET[id]\"";
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
            $body = unserialize($row['motdbody']);
            $body['body'] = preg_replace("/(<br \/><br \/>`0<span style='font-size:10'>Bearbeitet von)(.*)(Uhr\.<\/span>)/", "", $body['body']);
            if ($_POST['subject'] != "") {
                $row['motdtitle'] = $_POST['subject'];
            }
            if ($_POST['body'] != "") {
                $body['body'] = $_POST['body'];
            }
            if ($_POST[number] == "" && $_POST[number] < count($body['opt'])) {
                $number = count($body['opt']);
            } elseif ($_POST[number] < 2) {
                $number = 2;
            } else {
                $number = $_POST[number];
            }
            output("<form action='motd.php?op=editpoll&id=$_GET[id]' method='POST'>", true);
            addnav("","motd.php?op=editpoll&id=$_GET[id]");
            rawoutput("Titel: <input type='text' size='50' name='subject' value=\"".HTMLEntities(stripslashes($row['motdtitle']))."\"><br />");
            rawoutput("<textarea class='input' name='body' cols='50' rows='7'>".HTMLEntities(stripslashes($body['body']))."</textarea><br /><br />");
            for ($i = 0; $i < count($body['opt']); $i++) {
                if ($_POST['opt'][$i] == "") {
                    $_POST['opt'][$i] = $body['opt'][$i];
                }
            }
            for ($i = 0; $i < $number; $i++) {
                output("Option ".($i + 1).": <input name='opt[]' value='".$_POST['opt'][$i]."'>`n",true);
            }
            output("`n<input type='text' size='2' maxlength='2' name='number'> Zahl eintragen, um Anzahl der Optionen zu ändern`n`n", true);
            output("<input type='hidden' name='oldcount' value='".count($body['opt'])."'>", true);
            output("<input type='submit' class='button' value='Hinzufügen'></form>", true);
        } else {
            $body = array("body"=>$_POST['body']."<br /><br />`0<span style='font-size:10'>Bearbeitet von ".$session[user][login].", ".date("j.n.Y, G:i")." Uhr.</span>","opt"=>$_POST['opt']);
            $sql = "UPDATE motd SET motdtitle=\"$_POST[subject]\", motdbody=\"".addslashes(serialize($body))."\" WHERE motditem=\"$_GET[id]\"";
            db_query($sql);
            if (count($_POST['opt']) < $_POST[oldcount]) {
                $sql = "DELETE FROM pollresults WHERE choice>".(count($_POST['opt']) - 1)." AND motditem=\"$_GET[id]\"";
                db_query($sql);
            }
            header("Location: motd.php");
            exit();
        }
    } else {
        if ($session[user][loggedin]) {
            //$session[user][hitpoints] = 0;
            //$session[user][alive] = 0;
            $session[user][experience] = round($session[user][experience] * 0.9, 0);
            addnews($session[user][name]." wurde für den Versuch, die Götter zu betrügen, bestraft.");
            output("Du hast versucht, die Götter zu betrügen. Du wurdest mit Vergessen bestraft. Einiges von dem, was du einmal gewusst hast, weißt du nicht mehr.");
            saveuser();
        }
    }
} elseif ($_GET[op] == "") {
    output("`&");
    //motditem2("Beta!","Bitte beachte die Hinweise ganz unten.");
    output("`%");

    $sql = "SELECT * FROM motd ORDER BY motddate DESC limit 25";
    $result = db_query($sql);
    for ($i=0; $i < db_num_rows($result); $i++) {
        $row = db_fetch_assoc($result);
        if ($row['motddate'] > $session['user']['lastmotd'] || $i < 15) {
            if ($row['motdtype'] == 0) {
                motditem($row[motdtitle], $row[poster], date("j.n.Y, G:i", strtotime($row[motddate]))." Uhr".($session[user][superuser] >= 1 ? " [<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">del</a>|<a href='motd.php?op=edit&id=$row[motditem]'>edit</a>]" : ""), $row[motdbody]);
            } else {
                pollitem($row['motditem'], $row['motdtitle'], $row[poster], date("j.n.Y, G:i",strtotime($row[motddate]))." Uhr".($session[user][superuser] >= 1 ? " [<a href='motd.php?op=del&id=$row[motditem]' onClick=\"return confirm('Bist du sicher, dass dieser Eintrag gelöscht werden soll?');\">del</a>|<a href='motd.php?op=editpoll&id=$row[motditem]'>edit</a>]" : ""), $row[motdbody]);
            }
        }
    }
    output("`&");
    //motditem2("Beta!","Diese Seite ist im BETA Status! Ich bastel daran herum, wenn ich Zeit habe. Auch Änderungen von offizieller Seite (MightyE) werden hier übernommen. Das ist KEIN Freibrief zum Ausnutzen von Bugs, sondern alle Spieler (Teilnehmer am Beta-Test) sind verpflichtet, gefundene Fehler zu melden! Wünsche und Anregungen werden ebenfalls jederzeit gern angenommen. :-)");
    
}

$session[needtoviewmotd] = false;

    $sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $session[user][lastmotd]=$row[motddate];

popup_footer();
} else { 
popup_header("Ausrufer von Arda");
output("`\$Bitte melde dich an, um die Nachrichten des Ausrufers zu lesen.");
popup_footer(); }
?>