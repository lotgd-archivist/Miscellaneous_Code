<?php

// 15082004
require_once "common.php";
if ($session[user][loggedin]) {

    addcommentary();
    // Ooc schreiben - Narjana
   // checkday();
    if ($session[user][alive]) {
        addnav("Zurück zum Dorf", "village.php");
    }
    else {
        addnav("Zurück zu den Schatten", "shades.php");
    }
    addnav("Gerade Online", "list.php");
    addnav("Das Team", "list.php?op=team");

    if ($session[user][rplamp] == 0)
        addnav("RP-Bereitschaft ein", "list.php?op=rpon");
    if ($session[user][rplamp] > 0)
        addnav("RP-Bereitschaft aus", "list.php?op=rpoff");

}
else {
    addnav("Login Seite", "index.php");
    addnav("Gerade Online", "list.php");
}
page_header("Kämpferliste");
if ($_GET[op] == "rpon") {
    $session['user']['rplamp'] = 1;
    redirect("list.php");
}

if ($_GET[op] == "rpoff") {
    $session['user']['rplamp'] = 0;
    redirect("list.php");
}
$playersperpage = 30;

$sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";
$result = db_query($sql);
$row = db_fetch_assoc($result);
$totalplayers = $row['c'];

if ($_GET['op'] == "search") {
    $search = "%";
    for ($x = 0; $x < strlen($_POST['name']); $x++) {
        $search .= substr($_POST['name'], $x, 1) . "%";
    }
    $search = " AND name LIKE '" . addslashes($search) . "' ";
    // addnav("List Warriors","list.php");
}
else {
    $pageoffset = ( int )$_GET['page'];
    if ($pageoffset > 0)
        $pageoffset--;
    $pageoffset *= $playersperpage;
    $from = $pageoffset + 1;
    $to = min($pageoffset + $playersperpage, $totalplayers);
    $limit = " LIMIT $pageoffset,$playersperpage ";
}

addnav("Seiten");
for ($i = 0; $i < $totalplayers; $i += $playersperpage) {
    addnav("Seite " . ($i / $playersperpage + 1) . " (" . ($i + 1) . "-" . min($i + $playersperpage, $totalplayers) . ")", "list.php?page=" . ($i / $playersperpage + 1));
}

// Order the list by level, dragonkills, name so that the ordering is total!
// Without this, some users would show up on multiple pages and some users
// wouldn't show up
if ($_GET['page'] == "" && $_GET['op'] == "") {

    output("`c`bDiese Krieger sind gerade online`b`c");
    $sql = "SELECT acctid,name,login,alive,superuser,admin,location,sex,level,laston,loggedin,lastip,uniqueid,crace,prison,rplamp FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'" . date("Y-m-d H:i:s", strtotime(date("r") . "-" . getsetting("LOGINTIMEOUT", 900) . " seconds")) . "' ORDER BY level DESC, dragonkills DESC, login ASC";
}
else if ($_GET['op'] == "team") {
    output("`c`bDies ist das Team von Arda`b`c");
    $sql = "SELECT acctid,name,login,alive,superuser,admin,location,sex,level,laston,loggedin,lastip,uniqueid,crace,prison,rplamp FROM accounts WHERE superuser > 1 ORDER BY superuser DESC, login ASC";

}
else {
    output("`c`bKrieger in dieser Welt (Seite " . ($pageoffset / $playersperpage + 1) . ": $from-$to von $totalplayers)`b`c");
    $sql = "SELECT acctid,name,login,alive,admin,superuser,location,sex,level,laston,loggedin,lastip,uniqueid,crace,prison,rplamp FROM accounts WHERE locked=0 $search ORDER BY level DESC, dragonkills DESC, login ASC $limit";
}
if ($session[user][loggedin]) {
    output("<form action='list.php?op=search' method='POST'>Nach Name suchen: <input name='name'><input type='submit' class='button' value='Suchen'></form>", true);
    addnav("", "list.php?op=search");
}

$result = db_query($sql) or die(sql_error($sql));
$max = db_num_rows($result);
if ($max > 100) {
    output("`\$Es treffen zu viele Namen auf diese Suche zu. Nur die ersten 100 werden angezeigt.`0`n");
}
if ($_GET['op'] == "team") {

    output("<table border=1 cellpadding=2 cellspacing=1 bgcolor='#999999'>", true);
    output("<tr class='trhead'><td align=\"center\"><b>Level</b></td><td align=\"center\"><b>RP</b></td><td align=\"center\"><b>Name</b></td><td align=\"center\"><b>Rasse</b></td><td align=\"center\"><b>Charstatus</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td align=\"center\"><b>Status</b></td align=\"center\"><td><b>Online/Offline</b></tr>", true);

    for ($i = 0; $i < $max; $i++) {
        $row = db_fetch_assoc($result);
        if ($row['login'] == 'ADMIN') {
        }
        else {
            output("<tr class='" . ($i % 2 ? "trdark" : "trlight") . "'><td align=\"center\">", true);
            output("`^$row[level]`0");
            output("</td><td align=\"center\">", true);
            output($row[rplamp] ? "<img src=\"images/on.png\">" : "<img src=\"images/off.png\">", true);
            output("</td><td>", true);
            if ($session[user][loggedin])
                output("<a href=\"mail.php?op=write&to=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popup("mail.php?op=write&to=" . rawurlencode($row['login']) . "") . ";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>", true);
            if ($session[user][loggedin])
                if ($session[user][prefs][oldBio]) {
                    output("<a href=\"biopopup_backup.php?char=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popupbio("biopopup_backup.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">", true);
                }
                else {
                    output("<a href=\"biopopup.php?char=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popupbio("biopopup.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">", true);
                }
            // if ($session[user][loggedin]) output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
            if ($session[user][loggedin])
                addnav("", "biopopup.php?char=" . rawurlencode($row['login']) . "");
            output("`" . ($row[acctid] == getsetting("hasegg", 0) ? "^" : "&") . "$row[name]`0");
            if ($session[user][loggedin])
                output("</a>", true);
            output("</td><td align=\"center\">", true);
            output($row['crace']);
            output("</td><td align=\"center\">", true);

           if ($row['superuser'] == 1)
            output('`IKon`8to`pll`8kob`Iold/`ISta`8d`ptw`8äch`Iter`0`');
        if ($row['superuser'] == 2)
            output('`lMär`Lchen`Lkob`lold/`lBio`Lwäch`lter`0');
        if ($row['superuser'] == 3)
            output('`dGr`Sot`ste`Pnk`sob`Sol`dd/`dMo`Sd`ser`Sat`dor`0');
        if ($row['superuser'] == 4) {
            if ($row['login'] == "Kibarashi") {
                output('`fC`Fo`jd`De-`rG`êr`àe`lml`Kin`0/`$Admin`0`');
            }
            else {
                output('`#Gr`Fo`3tte`fno`#lm/`FA`3d`fm`3i`Fn`0');
            }
        }
            output("</td><td align=\"center\">", true);
            output($row[sex] ? "<img src=\"images/female.gif\">" : "<img src=\"images/male.gif\">", true);
            output("</td><td align=\"center\">", true);
            $loggedin = (date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT", 900) && $row[loggedin]);
            output($row[alive] ? "`yLebt`0" : "`4Tot`0");
            output("</td><td align=\"center\">", true);
            // $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
            $laston = round((strtotime(date("r")) - strtotime($row[laston])) / 86400, 0) . " Tage";
            if ($loggedin) {
                $laston = "`#O`Fn`3li`Fn`#e`0";
            }
            else {
                $laston = "`WO`ef`\$fl`ein`We`0";
            }
            output($laston);
            output("</td></tr>", true);
        }
    }
    output("</table>", true);
}
else {
    output("<table border=1 cellpadding=2 cellspacing=1 bgcolor='#999999'>", true);
    output("<tr class='trhead'><td align=\"center\"><b>Level</b></td><td align=\"center\"><b>RP</b></td><td align=\"center\"><b>Name</b></td><td align=\"center\"><b>Rasse</b></td><td align=\"center\"><b>Charstatus</b></td><td><b><img src=\"images/female.gif\">/<img src=\"images/male.gif\"></b></td><td align=\"center\"><b>Status</b></td align=\"center\"><td><b>Online/Offline</b></tr>", true);
    for ($i = 0; $i < $max; $i++) {
        $row = db_fetch_assoc($result);
        output("<tr class='" . ($i % 2 ? "trdark" : "trlight") . "'><td align=\"center\">", true);
        output("`^$row[level]`0");
        output("</td><td align=\"center\">", true);
        output($row[rplamp] ? "<img src=\"images/on.png\">" : "<img src=\"images/off.png\">", true);
        output("</td><td>", true);
        if ($session[user][loggedin])
            output("<a href=\"mail.php?op=write&to=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popup("mail.php?op=write&to=" . rawurlencode($row['login']) . "") . ";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>", true);
        if ($session[user][loggedin])
            if ($session[user][prefs][oldBio]) {
                output("<a href=\"biopopup_backup.php?char=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popupbio("biopopup_backup.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">", true);
            }
            else {
                output("<a href=\"biopopup.php?char=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popupbio("biopopup.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">", true);
            }
        // if ($session[user][loggedin]) output("<a href='bio.php?char=".rawurlencode($row['login'])."'>",true);
        if ($session[user][loggedin])
            addnav("", "biopopup.php?char=" . rawurlencode($row['login']) . "");
        output("`" . ($row[acctid] == getsetting("hasegg", 0) ? "^" : "&") . "$row[name]`0");
        if ($session[user][loggedin])
            output("</a>", true);
        output("</td><td align=\"center\">", true);
        output($row['crace']);
        output("</td><td align=\"center\">", true);

        if ($row['superuser'] == 0)
            output($row[sex] ? '`ÀSpi`êele`Àrin' : '`XSp`miel`Xer');
        if ($row['superuser'] == 1)
            output('`IKon`8to`pll`8kob`Iold/`ISta`8d`ptw`8äch`Iter`0`');
        if ($row['superuser'] == 2)
            output('`lMär`Lchen`Lkob`lold/`lBio`Lwäch`lter`0');
        if ($row['superuser'] == 3)
            output('`dGr`Sot`ste`Pnk`sob`Sol`dd/`dMo`Sd`ser`Sat`dor`0');
        if ($row['superuser'] == 4) {
            if ($row['login'] == "Kibarashi") {
                output('`fC`Fo`jd`De-`rG`êr`àe`lml`Kin`0/`$Admin`0`');
            }
            else {
                output('`#Gr`Fo`3tte`fno`#lm/`FA`3d`fm`3i`Fn`0');
            }
        }
        output("</td><td align=\"center\">", true);
        output($row[sex] ? "<img src=\"images/female.gif\">" : "<img src=\"images/male.gif\">", true);
        output("</td><td align=\"center\">", true);
        $loggedin = (date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT", 900) && $row[loggedin]);
        output($row[alive] ? "`YL`9e`yb`Yt`0" : "`wT`4o`wt`0");
        output("</td><td align=\"center\">", true);
        // $laston=round((strtotime("0 days")-strtotime($row[laston])) / 86400,0)." Tage";
        $laston = round((strtotime(date("r")) - strtotime($row[laston])) / 86400, 0) . " Tage";
        if ($loggedin) {
            $laston = "`#O`Fn`3li`Fn`#e`0";
        }else{
        if($laston==0)
        {$laston = "`^Heute";} }
        /*else {
            $laston = "`4Offline`0";
        }*/
        output($laston);
        output("</td></tr>", true);
    }
    output("</table>", true);
    /**output("<div id='Legende'>", true);
    output("Legende:`n`^Kontrollkobold=Stadtwächter, `@Märchenkobold=Biowächter, `3Grottenkobold=Moderator, `\$Grottenolm=Admin");
    output("</div>", true);
**/
    output("`n`n");
    if ($session[user][loggedin]){
    if ($session[user][einlass]==1) {
        output("`\$Das ist der OOC, bitte auch so handeln!`0`n`n`n");
        viewcommentary("list", "mit anderen unterhalten", 25);
        // ooc schreiben - Narjana
    }
    }
}
page_footer();
?> 