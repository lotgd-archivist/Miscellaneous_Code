<?php

require_once "common.php";

FUNCTION REMOVEEVILATTRIBUTES($SOURCE) {
    $ALLOWEDTAGS = '<A><BR><B><H1><H2><H3><H4><I>' . '<IMG><LI><OL><P><STRONG><TABLE>' . '<TR><TD><TH><U><UL>';
    $SOURCE = STRIP_TAGS($SOURCE, $ALLOWEDTAGS);
    RETURN PREG_REPLACE('/<(.*?)>/IE', "'<'.REMOVEEVILATTRIBUTES('\\1').'>'", $SOURCE);
}

$result = db_query("SELECT login,name,superuser,level,slave,master,erlaubniss,memberid,rankid,weapon,armor,jobname,sex,title,specialty,hashorse,acctid,age,herotattoo,marriedto,pvpflag,charisma,veri,resurrections,bio,dragonkills,race,avatar,housekey,punch,orden,reputation,birthday,auszeichnung,ausz2 FROM accounts WHERE login='$_GET[char]'");
$row = db_fetch_assoc($result);

$row[login] = rawurlencode($row[login]);

if ($session[user][loggedin]) {
    popup_bioheader("Charakter Biographie: " . preg_replace("'[`].'", "", $row[name]), true);
}
else {
    popup_bioheader("Charakter Biographie: " . preg_replace("'[`].'", "", $row[name]));
}

$specialty = array(0 => "nicht spezifiziert", "Dunkle Künste", "Mystische Kräfte", "Diebeskunst");
output("<div id=biografie>", true);
output("Schreibe `0$row[name] an:   <a href=\"mail.php?op=write&to=$row[login]\" target=\"_blank\" onClick=\"" . popup("mail.php?op=write&to=$row[login]") . ";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>", true);
if ($row[avatar]) {
    $pic_size = getimagesize(addslashes("avatare/") . $row[avatar]);
    $pic_width = $pic_size[0];
    $pic_height = $pic_size[1];

    output("<table><tr><td align='top'>`n`n", true);
    $piccheck = "false";
    if ($pic_width > 500 && $pic_height > 500) {
        if ($pic_width > $pic_height) {
            output("<img src='" . addslashes("/avatare/") . $row[avatar] . "' width=\"500\" alt=\"" . preg_replace("'[`].'", "", $row[name]) . "\">", true);
        }
        else {
            output("<img src='" . addslashes("/avatare/") . $row[avatar] . "' height=\"500\" alt=\"" . preg_replace("'[`].'", "", $row[name]) . "\">", true);
        }
    }
    else if ($pic_height > 500) {
        output("<img src='" . addslashes("/avatare/") . $row[avatar] . "' height=\"500\" alt=\"" . preg_replace("'[`].'", "", $row[name]) . "\">", true);
        $piccheck = "true";
    }
    else if ($piccheck == "false" && $pic_width > 500) {
        output("<img src='" . addslashes("/avatare/") . $row[avatar] . "' width=\"500\" alt=\"" . preg_replace("'[`].'", "", $row[name]) . "\">", true);
    }
    else {
        output("<img src='" . addslashes("/avatare/") . $row[avatar] . "' alt=\"" . preg_replace("'[`].'", "", $row[name]) . "\">", true);
    }
    output("&nbsp;</td><td valign='top' style='width:50%;'>", true);
}
else {
    output('<table><tr><td width:"500">(Es wurde noch kein Avatar hochgeladen)&nbsp;&nbsp;&nbsp;</td><td style="width:50%;">', true);
}

output("`n`n`^Titel: `@$row[title]`n");
if (getsetting("activategamedate", "0") == 1 && $row[birthday] != "")
    output("`^Geburtstag: `@$row[birthday]`n");
output("`^Level: `@$row[level]`n");
output("`^Alter seit PK: `@$row[age]`^ Tage`n");
output("`^Wiedererweckt: `@$row[resurrections]x`n");
output("`^Rasse: `@" . $row['race'] . "`n");
output("`^Geschlecht: `@" . ($row[sex] ? "Weiblich" : "Männlich") . "`n");
output("`^Spezialgebiet: `@" . $specialty[$row[specialty]] . "`n");
if ($row['superuser'] == 1) {
    output("`^Beruf: `^Stadtwache`n");
}
else {
    output("`^Beruf: `@" . $row[jobname] . "`n");
}
if ($row['auszeichnung']) {
    output("`^Auszeichnungen: `@" . $row['auszeichnung'] . "`n",true);
}
if ($row['ausz2']) {
    output("`n<img src=\"$row[ausz2]\" `n", true);
}
output("`n`^Waffe: `@$row[weapon]`n");
output("`^Rüstung: `@$row[armor]`n");
$sql = "SELECT mountname FROM mounts WHERE mountid='{$row['hashorse']}'";
$result = db_query($sql);
$mount = db_fetch_assoc($result);
if ($mount['mountname'] == "")
    $mount['mountname'] = "`iKeines`i";
output("`n`^Tier: `@{$mount['mountname']}`n");

if ($row['dragonkills'] > 0)
    output("`^Phoenixkills: `@{$row['dragonkills']}`n");
if ($row[herotattoo]) {
    output("`^Tätowierungen: ");
    for ($i = 1; $i <= $row[herotattoo]; $i++) {
        output("`@$ghosts[$i]");
        if ($i < $row[herotattoo])
            output(", ");
        else
            output(".`n");
    }
}
output("`^Bester Angriff: `@$row[punch]`n");
output("`^Orden: `@$row[orden]`n");
output("<table border='0' cellspacing='0' cellpadding='0'><tr><td>`^Ansehen:&nbsp;</td><td>" . grafbar(100, ($row['reputation'] + 50), 100, 12) . "</td></tr></table>", true);
if ($row[housekey]) {
    output("`^Hausnummer: `@$row[housekey]`n");
}
$sql = "SELECT name FROM accounts WHERE acctid='{$row['master']}'";
$result = db_query($sql);
$meister = db_fetch_assoc($result);

if ($row[slave] == 1) {
    output("`^Eigentum von:" . " `e{$meister['name']}`n");

}
$sql = "SELECT name FROM accounts WHERE acctid='{$row['slave']}'";
$result = db_query($sql);
$sklave = db_fetch_assoc($result);

if ($row[master] == 1) {
    output("`^Besitzer von:" . " `e{$sklave['name']}`n");
}
if ($row[marriedto]) {
    if ($row[marriedto] == 4294967295) {
        output("`^Verheiratet mit: `@" . ($row[sex] ? "Seth" : "Violet") . "`n");
    }
    elseif ($row[charisma] == 4294967295) {
        $sql = "SELECT name FROM accounts WHERE acctid='{$row['marriedto']}'";
        $result = db_query($sql);
        $partner = db_fetch_assoc($result);
        output("`^Verheiratet mit: `@{$partner['name']}`n");
    }
}
if ($row[ssstatus] > 0 && $row[ssmonat] <= 16) {
    output("`^Ist Schwanger`n");
}

if ($row[sex])
    $sqlkin = "SELECT * FROM kinder where mama = " . $row[acctid];
else
    $sqlkin = "SELECT * FROM kinder where papa = " . $row[acctid];

$resultkin = db_query($sqlkin);

$kinder = array();
while ($rowkin = db_fetch_assoc($resultkin)) {
    array_push($kinder, $rowkin[name]);
}
if ($kinder[0] != "") {
    if ($row[sex])
        output("`^Ist Mutter von:`@ ");
    else
        output("`^Ist Vater von:`@ ");

    output(implode(", ", $kinder));
    output("`0`n");
}
/* Gildenaddon by Eliwood für Eliwoods Gilden */
if ($row['memberid'] > 0) {
    $sql = "SELECT gildenid,gildenname,gildenprefix FROM gilden WHERE gildenid = '" . $row['memberid'] . "' LIMIT 1";
    $gilde = db_fetch_assoc(db_query($sql));
    output("`^Gildenmitgliedschaft: `@" . $gilde['gildenname'] . "`@ [`0<a href='showdetail.php?id=" . $gilde['gildenid'] . "' target='window_popup' onClick=\"" . popup("showdetail.php?id=" . $gilde['gildenid']) . "; return false;\">`&" . stripslashes($gilde['gildenprefix']) . "`&</a>`@]`n", true);
    $sql = "SELECT rankname FROM gildenranks WHERE rankid = '" . $row['rankid'] . "' LIMIT 1";
    $rank = db_fetch_assoc(db_query($sql));
    output("`^Rank: `@" . $rank['rankname'] . "`@`n");
}
if ($row['pvpflag'] == "5013-10-06 00:42:00")
    output("`4`iSteht unter besonderem Schutz`i");
if (getsetting("avatare", 0) == 1) {
    output("</td></tr></table>", true);
}
output("</td></tr></table>", true);
if ($row['bio'] > "") {
    output('</br></br><div id=bio style="width=982px;">', true);
    output("{$row['bio']}", true);
    output("</div>", true);
}
output("</div>", true);

/*
 * output("`n`^Letzte Leistungen (und Niederlagen) von $row[name]`^"); $result = db_query("SELECT * FROM news WHERE accountid=$row[acctid] ORDER BY newsdate DESC,newsid ASC LIMIT 100"); $odate=""; for ($i=0;$i<db_num_rows($result);$i++){ $row = db_fetch_assoc($result); if ($odate!=$row[newsdate]){ output("`n`b`@".date("D, M d",strtotime($row[newsdate]))."`b`n"); $odate=$row[newsdate]; } output($row[newstext]."`n"); }
 */
// Raus da unnötig by Ellalith
if ($_GET[ret] == "") {
    addnav("Zur Liste der Krieger", "list.php");
}
else {
    $return = preg_replace("'[&?]c=[[:digit:]-]+'", "", $_GET[ret]);
    $return = substr($return, strrpos($return, "/") + 1);
    addnav("Zurück", $return);
}
popup_biofooter();
?> 