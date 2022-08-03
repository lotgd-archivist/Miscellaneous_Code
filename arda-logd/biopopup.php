<?php
/*
 * BioPopup made by Chire / Jayolino
 *
 * Dieses Popup basiert auf dem Grund PopUp Gerüst
 * Verbessert das man nun auch weitere BioInformationen sehen kann.
 *
 * Folgende Infos sind nun erreichbar:
 * - Datenblatt
 * - Biografie
 * - Tierbiografie
 *
 * made 4 Arda
 */
FUNCTION REMOVEEVILATTRIBUTES($SOURCE) {
    $ALLOWEDTAGS = '<A><BR><B><H1><H2><H3><H4><I>' . '<IMG><LI><OL><P><STRONG><TABLE>' . '<TR><TD><TH><U><UL>';
    $SOURCE = STRIP_TAGS($SOURCE, $ALLOWEDTAGS);
    RETURN PREG_REPLACE('/<(.*?)>/IE', "'<'.REMOVEEVILATTRIBUTES('\\1').'>'", $SOURCE);
}

require_once "common.php";

$result = db_query("SELECT login,name,superuser,level,slave,master,erlaubniss,memberid,rankid,weapon,armor,jobname,sex,title,specialty,hashorse,acctid,age,herotattoo,marriedto,pvpflag,charisma,veri,resurrections,bio,dragonkills,race,avatar,housekey,punch,orden,reputation,birthday,auszeichnung,ausz2,petbio,biopet,diary,prefs FROM accounts WHERE login='$_GET[char]'");
$row = db_fetch_assoc($result);
$row[login] = rawurlencode($row[login]);
$prefs = unserialize($row['prefs']);
$id = $row[acctid];
if ($session[user][loggedin]) {
    popup_bioheader("Charakter Biographie: " . preg_replace("'[`].'", "", $row[name]), true);
}
else {
    popup_bioheader("Charakter Biographie: " . preg_replace("'[`].'", "", $row[name]));
}
output("");

$specialty = array(0 => "nicht spezifiziert", "Dunkle Künste", "Mystische Kräfte", "Diebeskunst");

output('<div class="menu">
            <div class="item">
                <a class="link icon_find"></a>
                <div class="item_content">
                    <a href="biopopup.php?char=' . $row[login] . '&op=bio" name="PetBio">Biografie</a>', true);
if ($row[petbio] == 1) {
    output('<br><a href="biopopup.php?char=' . $row[login] . '&op=petbio" name="PetBio">Tier-Biografie</a>', true);
}
output('</div>
            </div>
            <div class="item">
                <a class="link icon_home"></a>
                <div class="item_content">
                    <a href="biopopup.php?char=' . $row[login] . '&op=data" name="Datenblatt">Datenblatt</a>', true);
if ($row[diary] == 1) {
    output('<br><a href="biopopup.php?char=' . $row[login] . '&op=diary" name="Tagebuch">Tagebuch</a>', true);
}
output('</div>
            </div>
            <div class="item_mail">
                <a class="link icon_mail"></a>
                <div class="item_content_mail">', true);
output("<a class='mailtest' href=\"mail.php?op=write&to=$row[login]\" target=\"_blank\" onClick=\"" . popup("mail.php?op=write&to=$row[login]") . ";return false;\">Schreibe <br>`0$row[name] <br>`0 an</a>", true);
output('</div>
            </div>
        </div></div><br>', true);
output("" . $row[name]);
output('</b></td></tr><tr><td class="popupbioheaderfoot"><b></b></td></tr><tr><td valign="top" width="100%">', true);

if ($HTTP_GET_VARS[op] == "bio") {
    AvatarArea($row[avatar], $row[login], "bio", $prefs[bioAvatar]);
    if ($row['bio'] > "") {
        output('<div id="bio" style="width=982px;">', true);
        if ($session['user']['einlass']==1){
        output("{$row['bio']}", true);
        }else{
            output("`\$Bitte verifizieren, um Bios anzuschauen");
        }
        output("</div>", true);
    }
}
else if ($HTTP_GET_VARS[op] == "petbio") {

    if ($row['biopet'] > "") {
        output('<div id="petbio" style="width=982px;">', true);
        output("{$row['biopet']}", true);
        output("</div>", true);
    }
}
else if ($HTTP_GET_VARS[op] == "diary") {

    $sql = "SELECT * FROM `diary` WHERE `acctid`='" . $row[acctid] . "' ORDER BY `diaryID` ASC";

    $bio_res = db_query($sql);

    // showing each chapter
    for ($i = 0; $i < db_num_rows($bio_res); $i++) {

        $bio_row = db_fetch_assoc($bio_res);

        // the table is used for better centralized texts
        // no 'reorganizing' (shifting right) of the text when the paypal-icons end
        output("<table width='100%'><tr><td width='5%'></td><td width='90%'>", true);

        output("`c`!$bio_row[title]`0");
        output("`c`n");
        //output("$bio_row[body]");

        $body = str_replace("/me", $row[name]."`0", $bio_row['body']);

        output($body);

        // show this chapter
        //output(removeEvilTags(soap(nl2br($body)), "`c`b"), true);

        output("</td><td width='5%'></td></tr></table>", true);

        output("`n`n");
    }
}
else {
    output("<table><tr><td align='top'>`n`n", true);
    AvatarArea($row[avatar], $row[login], "data", 0);
    output("&nbsp;</td><td valign='top' style='width:50%;'>", true);
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
    
     if ($row[sex]) {
     $sqlkin = "SELECT * FROM kinder where mama = " . $row[acctid];
     }
     else {
     $sqlkin = "SELECT * FROM kinder where papa = " . $row[acctid];
     }

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
    output("</td></tr></table>", true);

    if (rawurlencode($row[login]) == "Kibarashi") {
        $chire = preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("chire.html"));
        output($chire, true);
    }
}
FUNCTION AvatarArea($avatar, $name, $from, $bioavatar) {
    // Avatar Bereich in der Biografie
    output('<div id="Avatar">', true);
    if ($avatar) {
        $pic_size = getimagesize(addslashes("avatare/") . $avatar);
        $pic_width = $pic_size[0];
        $pic_height = $pic_size[1];
        $piccheck = "false";
        if ($from == "bio") {
            if ($bioavatar == 0) {
                if ($pic_width > 500 && $pic_height > 500) {
                    if ($pic_width > $pic_height) {
                        output("<img src='" . addslashes("/avatare/") . $avatar . "' width=\"500\" style='position: relative;left: 30%;' alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                    }
                    else {
                        output("<img src='" . addslashes("/avatare/") . $avatar . "' height=\"500\" style='position: relative;left: 30%;' alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                    }
                }
                else if ($pic_height > 500) {
                    output("<img src='" . addslashes("/avatare/") . $avatar . "' height=\"500\" style='position: relative;left: 30%;' alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                    $piccheck = "true";
                }
                else if ($piccheck == "false" && $pic_width > 500) {
                    output("<img src='" . addslashes("/avatare/") . $avatar . "' width=\"500\" style='position: relative;left: 30%;' alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                }
                else {
                    output("<img src='" . addslashes("/avatare/") . $avatar . "' style='position: relative;left: 30%;' alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                }
            }
        }
        else {
            if ($pic_width > 500 && $pic_height > 500) {
                if ($pic_width > $pic_height) {
                    output("<img src='" . addslashes("/avatare/") . $avatar . "' width=\"500\" alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                }
                else {
                    output("<img src='" . addslashes("/avatare/") . $avatar . "' height=\"500\" alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                }
            }
            else if ($pic_height > 500) {
                output("<img src='" . addslashes("/avatare/") . $avatar . "' height=\"500\" alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
                $piccheck = "true";
            }
            else if ($piccheck == "false" && $pic_width > 500) {
                output("<img src='" . addslashes("/avatare/") . $avatar . "' width=\"500\" alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
            }
            else {
                output("<img src='" . addslashes("/avatare/") . $avatar . "' alt=\"Avatar-" . preg_replace("'[`].'", "", $name) . "\">", true);
            }

        }
    }
    else {
        output('(Es wurde noch kein Avatar hochgeladen)&nbsp;&nbsp;&nbsp;', true);
    }
    output("<br><br></div>", true);
    //ende Avatarbereich
}

popup_biofooter();
?> 