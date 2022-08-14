
<?php

// New-Saint Bio
// Stand: 27.12.2012

require_once "common.php";
require_once "lib/include/bio_content.php";

output('<script type="text/javascript" src="lib/js/jquery-1.4.4.js"></script>', true);
output('<script type="text/javascript" src="lib/js/bio.js"></script>', true);
output("<link href='lib/styles/bio_style.css' rel='stylesheet' type='text/css'>", true);

$tattoos = array( //Der Array für die Tattoos
    0 => ("Fehu"), //spezielle Tiere 1
    1 => ("Uruz"), //ATK +1
    2 => ("Algis"), //DEF +1
    3 => ("ein Keltischer Knoten"), //ATK +2
    4 => ("Nauthiz"), //DEF +2
    5 => ("Loa Ogoun"), //ATK +3
    6 => ("der Drudenfuß"), //DEF +3
    7 => ("das Auge des Horus"), //ATK +4
    8 => ("Ankh"), //DEF +4
    9 => ("der Schlüssel des Salomon") //spezielle Tiere 2
);

global $session;

if (is_numeric($_GET['char']))
    $lookfor = "acctid";
else
    $lookfor = "login";

$sql = "SELECT accounts.*,bios.* FROM accounts,bios WHERE accounts." . $lookfor . "='" . $_GET['char'] . "' AND accounts.acctid=bios.acctid";
$result = db_query($sql) or die(db_error(LINK));
$row   = db_fetch_assoc($result);
$prefs = unserialize($row['prefs']);

popup_header("Charakter Biographie: " . preg_replace("/#[0-9a-f]{6}|[`]./i", "", $row['name']));


$sql_check_su = "SELECT editor_blocks FROM su_rights WHERE su_id = " . $session['user']['acctid'] . " LIMIT 1;";
$result_check_su = db_query($sql_check_su) or die(db_error(LINK));
$row_check_su = db_fetch_assoc($result_check_su);



output("<div id='bioBody'>", true);
output("<table border='0' cellpadding='2' cellspacing='2' width='850' align='center'>", true);
output("<tr><td>", true);

output("<table border='0' cellpadding='2' cellspacing='2' width='800' align='center'>", true);
output("<tr align='center' valign='middle'>", true);
output("<td colspan='10'><hr></td>", true);
output("</tr><tr align='center' valign='middle'>", true);
output("<td width='20'>&nbsp;</td>", true);
output("<td width='100'><a href='popup_bio.php?char=" . $row['acctid'] . "'>Biografie</a></td>", true);
output("<td width='100'><a href='popup_bio.php?op=veribio&char=" . $row['acctid'] . "'>ü18-Biografie</a></td>", true);
output("<td width='100'><a href='popup_bio.php?op=tage&char=" . $row['acctid'] . "'>Memoiren</a></td>", true);
if ($session['user']['superuser'] > 2) {
    output("<td width='100'><a href='popup_bio.php?op=family&char=" . $row['acctid'] . "'>Familie</a></td>", true);
}
output("<td width='100'><a href='popup_bio.php?op=gb&char=" . $row['acctid'] . "'>Gästebuch</a></td>", true);
output("<td width='100'><a href='popup_bio.php?op=spass&char=" . $row['acctid'] . "'>Out of Character</a></td>", true);
//output("<td width='100'><a href='popup_bio.php?op=perso&char=" . $row['acctid'] . "'>Persönliches</a></td>", true);
if ($session['user']['acctid'] == $row['acctid']){
        output("<td width='100'><a href='popup_bio.php?op=multis&char=" . $row['acctid'] . "'>Multiaccounts</a></td>", true);
output("<td width='100'><a href='popup_bio.php?op=settings&char=" . $row['acctid'] . "'>Einstellungen</a></td>", true);}



output("<td width='20'>&nbsp;</td>", true);
output("</tr><tr align='center' valign='middle'>", true);
output("<td colspan='10'><hr></td>", true);
output("</tr></table>", true);

output("</td></tr>", true);
output("<tr><td>", true);

switch ($_GET['op']) {
    case "":
        output("`n`G<font face='Andalus'><font size=6>#4a4a4aB#575757i#646464o#727272g#7f7f7fr#8d8d8da#949494p#9b9b9bh#a2a2a2i#aaaaaae #b1b1b1v#b8b8b8o#c0c0c0n`0 $row[name]</font face></font size>", true);
        if ($session['user']['loggedin'])
            output("<a href=\"popup_mail.php?op=write&to={$row['login']}\" target=\"_blank\" onClick=\"" . popup("popup_mail.php?op=write&to={$row['login']}") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>", true);
        
        if (($row['acctid'] == $session['user']['acctid'] && $session['user']['acctid'] != 4) || $session['user']['superuser'] > 2)
            output("<span class='editButton' id='bioEdit'><img src='images/system/iface/edit.png' alt='Bio editieren' title='Bio editieren' border='0' ></span>", true);
        output("`n`n");
        
        output("<form action='popup_bio.php?op=savebio&char=" . $row['acctid'] . "&user=" . $row['acctid'] . "' method='POST'>", true);
        addnav("", "popup_bio.php?op=savebio&char=" . $row['acctid'] . "&user=" . $row['acctid']);
        output("<div class='saveButton'><input type='submit' class='bioSaveButton' value='Speichern'></div>", true);
        output("<div class='overlay'></div>", true);
        
        output("<table border='0' width='100%'><tr>", true);
        
        # Avatar 
        output("<td  id='avaRow' width='400'>", true);
        
        if (getsetting("avatare", 0) == 1) {
            if ($row['avatar']) {
                $pic_size   = @getimagesize($row['avatar']);
                $pic_width  = $pic_size[0];
                $pic_height = $pic_size[1];
                
                if ($pic_width > 400 || $pic_height > 400)
                    output("<img src='images/avatar-replace/" . mt_rand(1, 5) . ".jpg'", true);
                else
                    output("<img src='" . $row['avatar'] . "'", true);
                
                output(" align='center' alt=\"" . preg_replace("/#[0-9a-f]{6}|'[`].'/i", "", $row['name']) . "\">", true);
                
            } else {
                output("<label>(kein Bild)</label>", true);
            }
        }
        
        
        output("            
                <div class='avaBox'>
                    <fieldset>
                        <legend> Avatar </legend>
                        <label for='bioava' >Link zum Avatar (max. 400x400): </label>
                        <input type='text' name='bioava' value='" . $row['avatar'] . "' size='60'>
                        <label for='bioavaname' >Künstler/Model des Avatars: </label>
                        <input type='text' name='bioavaname' value='" . $row['avatar_name'] . "' size='45'>
                    </fieldset>
                </div>
                ", true);
        output("</td>", true);
        
        output("<td valign='middle'>", true);
        
        
        // Status-Block
        output("`n`n`b#c0c0c0Status:`0`b`n", true);
        
        // Zeigt Titel falls Option gesetzt
        // Titel = Systemwert
        if ($prefs['show_no_title'] != false)
            output("<label>`GTitel:</label> `7" . $row['title'] . "`0`n", true);
        
        
        # Rasse
        if ($row['race']) {
            $rasse = $row['race'];
        } else {
            $rasse = "keine";
        }
        
        output("<div id='bioRace'><label>`GRasse:</label> `7" . $rasse . "`0</div>", true);
        
        
        output("            
            <div id='raceBox'>
                <fieldset>
                    <legend> Rasse </legend>
                    <input type='text' name='biorace' value='" . escapeTags($row['race']) . "' size='40'>
                </fieldset>
            </div>
            ", true);
        
        # Spezifizierung
        if ($row['urace']) {
            $urasse = $row['urace'];
        } else {
            $urasse = "keine";
        }
        
        output("<div id='bioUrace'><label>`GSpezifizierung:</label> `7" . $urasse . "`0</div>", true);
        
        
        output("            
            <div id='UraceBox'>
                <fieldset>
                    <legend> Spezifikation </legend>
                    <input type='text' name='bioUrace' value='" . escapeTags($row['urace']) . "' size='40'>
                </fieldset>
            </div>
            ", true);
        
        // Plot-Chars von Andras für New Orleans.
      /* switch($row['is_plot_char'])
        {
            case 0:
                $plot_char = '`7Ungewiss`0';
                break;
            case 1:
                $plot_char = '`7Nein`0';
                break;
            case 2:
                $plot_char = '`7Ja`0';
                break;
        }
        output("`GPlot-Char? `7".$plot_char." `n");*/
        
        /*if ($row['is_plot_char'] == 0)
            output("`GPlotchar: `7Keine Angabe`n");
        if ($row['is_plot_char'] == 1)
            output("`GPlotchar: `7Nein`n");
        if ($row['is_plot_char'] == 2)
            output("`GPlotchar: `7Ja`n");*/
        
        
        // Geschlecht = Systemwert
        output("`GGeschlecht: `7" . ($row['sex'] ? "Weiblich" : "Männlich") . "`n");
        output("`GGesinnung: `7" . $gesinnung[$row['gesinnung']] . "`n");
        if ($row['fsk'] == 0)
            output("`GVolljährig: `7Keine Angabe`n");
        if ($row['fsk'] == 1)
            output("`GVolljährig: `7Nein`n");
        if ($row['fsk'] == 2)
            output("`GVolljährig: `7Ja`n");





        
        
        # Kampf-Block
        // Zeigt Titel falls Option gesetzt
        // Systemwerte
        if ($prefs['show_no_fight'] != false) {
            output("`n`n`b#c0c0c0Kampfeinstellung:`0`b`n");
            output("`GLevel: `7" . $row['level'] . "`n", true);
            output("`GDrachenkills: `7{$row['dragonkills']}`n");
            output("`GAlter seit DK: `7" . $row['age'] . "`7 Tage`n");
            output("`GWiedererweckt: `7" . $row['resurrections'] . "x`n");
            output("`GSpezialgebiet: `7" . $specialty[$row['specialty']] . "`n");
            output("`GBester Angriff: `7{$row['punch']}`n");
        }
        
        if ($row['char_parents'] != "" || $row['char_bro'] != "" || $row['char_sis'] != "" || $row['char_adopted'] != "" || $row['char_pate'] != "" || $row['char_affairs'] != "" || $row['char_affairs'] != "" || $row['char_begleiter'] != "" || $row['char_friend'] != "" || $row['marriedto'] != "" || $row['sex'] != "" || $row['childrenAdopted'] != "")
            output("`n`n`b#c0c0c0Familiäres Umfeld:`0`b`n");
        if ($row['char_parents'] != "")
            output("`GEltern: `7" . $row['char_parents'] . "`n");
        if ($row['char_vater'] != "")
            output("`GVater: `7" . $row['char_vater'] . "`n");
        if ($row['char_mutter'] != "")
            output("`GMutter: `7" . $row['char_mutter'] . "`n");
        if ($row['char_bro'] != "")
            output("`GBrüder: `7" . $row['char_bro'] . "`n");
        if ($row['char_sis'] != "")
            output("`GSchwestern: `7" . $row['char_sis'] . "`n");
        if ($row['char_adopted'] != "")
            output("`GAdoptierte Kinder: `7" . $row['char_adopted'] . "`n");
        if ($row['char_pate'] != "")
            output("`GPate: `7" . $row['char_pate'] . "`n");
        if ($row['char_affairs'] != "")
            output("`GAffären: `7" . $row['char_affairs'] . "`n");
        if ($row['char_begleiter'] != "")
            output("`GBegleiter: `7" . $row['char_begleiter'] . "`n");
        if ($row['char_friend'] != "")
            output("`GFreunde: `7" . $row['char_friend'] . "`n");
        
        output("            
            <div id='familyBox'>
                <fieldset>
                    <legend> Familiäres Umfeld </legend>
                    <label for='bioEltern'>Eltern: </label><input type='text' name='bioEltern' value='" . escapeTags($row['char_parents']) . "' size='40'><br />
                    <label for='bioVater'>Vater: </label><input type='text' name='bioVater' value='" . escapeTags($row['char_vater']) . "' size='40'><br />
                    <label for='bioMutter'>Mutter: </label><input type='text' name='bioMutter' value='" . escapeTags($row['char_mutter']) . "' size='40'><br />
                                        <label for='bioBruder'>Brüder: </label><input type='text' name='bioBruder' value='" . escapeTags($row['char_bro']) . "' size='40'><br />
                    <label for='bioSchwester'>Schwestern: </label><input type='text' name='bioSchwester' value='" . escapeTags($row['char_sis']) . "' size='40'><br />
                    <label for='bioAdoptiert'>Adoptierte Kinder: </label><input type='text' name='bioAdoptiert' value='" . escapeTags($row['char_adopted']) . "' size='40'><br />
                    <label for='bioPate'>Pate: </label><input type='text' name='bioPate' value='" . escapeTags($row['char_pate']) . "' size='40'><br />
                    <label for='bioAffaeren'>Affären: </label><input type='text' name='bioAffaeren' value='" . escapeTags($row['char_affairs']) . "' size='40'><br />
                                        <label for='bioBegleiter'>Begleiter: </label><input type='text' name='bioBegleiter' value='" . escapeTags($row['char_begleiter']) . "' size='40'><br />
                    <label for='bioFreunde'>Freunde: </label><input type='text' name='bioFreunde' value='" . escapeTags($row['char_friend']) . "' size='40'><br />
                              </fieldset>
            </div>
            ", true);
        
        
        if ($row['marriedto']) {
            if ($row['marriedto'] == 4294967295) {
                output("`GVerheiratet mit: `7" . ($row['sex'] ? "Seth" : "Violet") . "`n");
            } else if ($row['charisma'] == 4294967295) {
                $sql     = "SELECT name FROM accounts WHERE acctid='{$row['marriedto']}'";
                $result  = db_query($sql);
                $partner = db_fetch_assoc($result);
                output("`GVerheiratet mit: `7{$partner['name']}`n");
            } else if ($row['charisma'] != 4294967295 && $row['charisma'] >= 5) {
                $sql     = "SELECT name,charisma,marriedto FROM accounts WHERE acctid='{$row['marriedto']}'";
                $result  = db_query($sql);
                $partner = db_fetch_assoc($result);
                if ($partner['charisma'] >= 5 && $partner['marriedto'] == $row['acctid']) {
                    output("`GVerlobt mit: `7{$partner['name']}`n");
                }
            }
        }
        
        if ($row['sex']) {
            $sqlkin = "SELECT * FROM kinder WHERE mama = " . $row['acctid'];
        } else {
            $sqlkin = "SELECT * FROM kinder WHERE papa = " . $row['acctid'];
        }
        $resultkin = db_query($sqlkin);
        $n_kin     = db_num_rows($result);
        if ($n_kin > 0) {
            $i               = 0;
            $children        = array();
            $childrenAdopted = array();
            while ($rowkin = db_fetch_assoc($resultkin)) {
                if ($rowkin["adopted"]) {
                    array_push($childrenAdopted, $rowkin["name"]);
                } else {
                    array_push($children, $rowkin["name"]);
                }
            }
            $role = $row["sex"] ? "Mutter" : "Vater";
            if ($children[0] != "") {
                output("`GIst $role von:`7 ");
                output(implode(", ", $children));
                output("`n");
            }
            if ($childrenAdopted[0] != "") {
                output("`GIst Adoptiv-$role von:`7 ");
                output(implode(", ", $childrenAdopted));
            }
        }
        if ($row['sstatus'] > 0 AND $row['smonat'] <= getsetting("schwanger_dauer", 20)) {
            output("`n`rIst Schwanger`n");
            
            $dauer = getsetting("schwanger_dauer", 20);
            $monat = round($dauer / 9);
            
            if ($session['user']['acctid'] == 0) {
                output($row['smonat']);
                output($dauer);
                output($monat);
            }
            if ($row['smonat'] >= ($dauer - $monat)) {
                output("(Vom Bauch ist noch nichts zu sehen.)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 2))) {
                output("(Der Bauch ist nur im Ansatz zu sehen.)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 3))) {
                output("(Der Bauch ist ein wenig zu sehen.)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 4))) {
                output("(Der Bauch ist schon gut zu erkennen.)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 5))) {
                output("(Der Bauch ist deutlich zu sehen.)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 6))) {
                output("(Der Bauch ist schön rundlich.)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 7))) {
                output("(Der Bauch ist kugelrund.)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 8))) {
                output("(Sie ist hochschwanger!)");
            } else if ($row['smonat'] >= ($dauer - ($monat * 9))) {
                output("(Es kann jeden Tag soweit sein!)");
            }
            
            output("`0`n`n");
        }
        
        if ($row['sklavenhandel'] == 1) {
            output("`rDer Chara ist ein Sklave!`0");
        }
        if ($row['sklavenhandel'] == 2) {
            output("`rDer Chara ist ein Sklavenhändler!`0");
        }
        if ($row['sklavenhandel'] == 3) {
            output("`rDer Chara ist ein Sklave!`0");
        }
        
        output("</td></tr>", true);
        
        output("<tr><td>", true);
        output("<table align='center' valign='top'><tr><td>", true);
        
        output("`n`n`b#c0c0c0Charinformationen:`0`b`n");
        /*if ($row['schmuck'] == 0)
            output("`GRingträger: `7Nein`n");
        if ($row['schmuck'] == 1)
            output("`GRingträger: `7Ja`n");*/
        if ($row['jobid'])
            output("`GArbeit: `7" . $jobid[$row['jobid']] . "`n");
        if ($row['char_place'])
            output("`GHerkunft : `7" . $row['char_place'] . "`n");
        if ($row['char_age'])
            output("`GAlter: `7" . $row['char_age'] . "`n");
        if ($row['gott'])
            output("`GGottheit: `7" . $gott[$row['gott']] . "`n");
        if ($row['housekey'])
            output("`GHausnummer: `7{$row['housekey']}`n");
        
        $sql    = "SELECT mountname FROM mounts WHERE mountid='{$row['hashorse']}'";
        $result = db_query($sql);
        $mount  = db_fetch_assoc($result);
        if ($mount['mountname'] == "")
            $mount['mountname'] = "`iKeines`i";
        if ($row['tiername'] == "") {
            output("`GTier: `7{$mount['mountname']}`n");
        } else if ($row['tiername'] > "") {
            output("`GTier: `7{$mount['mountname']}`@`n");
            output("`GTiername `7" . $row['tiername'] . "`0`n", true);
        }
    
            $sql = "SELECT name FROM co_roles WHERE co_roles.acctid ='{$row['acctid']}'"; 
            $result     = db_query($sql);
            output("`n#c0c0c0`bAufgaben:`b`n");
            while ($posten = db_fetch_assoc($result))
            {
                output("`7" . $posten['name'] . "`n");
            }
            output("`n",true);


    
        output("</td></tr></table>", true);
        output("</td>", true);
        
        output("<td valign='top'>", true);
        output("<table border='0'><tr><td>", true);
        
        output("`n`n`b#c0c0c0Gilde:`0`b`n");
        /* Gildenaddon by Eliwood für Eliwoods Gilden */
        if ($row['memberid'] > 0) {
            $sql   = "SELECT gildenid,gildenname,gildenprefix FROM gilden WHERE gildenid = '" . $row['memberid'] . "' LIMIT 1";
            $gilde = db_fetch_assoc(db_query($sql));
            output("`GVerbindungsmitglied: `0" . $gilde['gildenname'] . "`0 `2[`0<a href='popup_showdetail.php?op=guild&id=" . $gilde['gildenid'] . "' target='window_popup' onClick=\"" . popup_edit("popup_showdetail.php?op=guild&id=" . $gilde['gildenid']) . "; return false;\">`&" . stripslashes($gilde['gildenprefix']) . "</a>`0`2]`0`n", true);
            $sql  = "SELECT rankname FROM gildenranks WHERE rankid = '" . $row['rankid'] . "' LIMIT 1";
            $rank = db_fetch_assoc(db_query($sql));
            if ($row['rankid'] > 0)
                output("`GRang: #c0c0c0" . $rank['rankname'] . "#c0c0c0`n");
        } else {
            output("`GNicht bekannt");
        }
        output("</td></tr></table><br>", true);
        
        output("<table border='0'><tr><td>", true);
        output("`n`n`b#c0c0c0Clan:`0`b`n");
        
        if ($row['c_memberid'] > 0) {
            $sql  = "SELECT clanid,clanname FROM clans WHERE clanid = '" . $row['c_memberid'] . "' LIMIT 1";
            $clan = db_fetch_assoc(db_query($sql));
            output("`GGemeinschaftsmitglied: `0<a href='popup_showdetail.php?op=clan&id=" . $clan['clanid'] . "' target='window_popup' onClick=\"" . popup_edit("popup_showdetail.php?op=clan&id=" . $clan['clanid']) . "; return false;\">`&" . stripslashes($clan['clanname']) . "</a>`0`n", true);
            $sql   = "SELECT rankname FROM clanranks WHERE rankid = '" . $row['c_rankid'] . "' LIMIT 1";
            $rank2 = db_fetch_assoc(db_query($sql));
            if ($row['c_rankid'] > 0)
                output("`GRang: #c0c0c0" . $rank2['rankname'] . "#c0c0c0`n");
        } else {
            output("`GNicht bekannt");
        }
        output("</td></tr></table>", true);
        
        output("</td></tr>", true);
        
        output("<tr><td>", true);
        output("<table align='center' valign='top'><tr><td>", true);
        
        output("`n`n`b#c0c0c0Erscheinung:`0`b`n");
        if ($row['bio_size'])
            output("`GGröße: `7" . $row['bio_size'] . "`n", true);
        if ($row['bio_frisur'])
            output("`GHaare: `7" . $row['bio_frisur'] . "`n", true);
        if ($row['bio_hairco'])
            output("`GHaarfarbe: `7" . $row['bio_hairco'] . "`n", true);
        if ($row['bio_nagelco'])
            output("`GNagelfarbe: `7" . $row['bio_nagelco'] . "`n", true);
        if ($row['bio_hautco'])
            output("`GHautfarbe: `7" . $row['bio_hautco'] . "`n", true);
        if ($row['bio_eyeco'])
            output("`GAugenfarbe: `7" . $row['bio_eyeco'] . "`n", true);
        
        if ($row['klamotten'] != 0)
            output("`GKlamotten: `7" . $kla[$row['klamotten']] . "`n", true);
        if ($row['schuhe'] != 0)
            output("`GSchuhe: `7" . $shoes[$row['schuhe']] . "`n", true);
        if ($row['armband'] != 0)
            output("`GSchmuck: `7" . $arm[$row['armband']] . "`n", true);
        if ($row['armor'] && $row['rp_char'] != 1)
            output("`GRüstung: `7" . $row['armor'] . "`n", true);
        if ($row['weapon'] && $row['rp_char'] != 1)
            output("`GWaffe: `7" . $row['weapon'] . "`n", true);
        
        output("</td></tr></table>", true);
        
        output("            
            <div id='lookBox'>
                <fieldset>
                    <legend> Aussehen </legend>
                    <label for='bioSize'>Größe: </label><input type='text' name='bioSize' value='" . escapeTags($row['bio_size']) . "' size='40'><br />
                    <label for='bioFrisur'>Haare: </label><input type='text' name='bioFrisur' value='" . escapeTags($row['bio_frisur']) . "' size='40'><br />
                    <label for='bioHairco'>Haarfarbe: </label><input type='text' name='bioHairco' value='" . escapeTags($row['bio_hairco']) . "' size='40'><br />
                    <label for='bioNagelco'>Nagelfarbe: </label><input type='text' name='bioNagelco' value='" . escapeTags($row['bio_nagelco']) . "' size='40'><br />
                    <label for='bioHautco'>Hautfarbe: </label><input type='text' name='bioHautco' value='" . escapeTags($row['bio_hautco']) . "' size ='40'><br />
                    <label for='bioEyeco'>Augenfarbe: </label><input type='text' name='bioEyeco' value='" . escapeTags($row['bio_eyeco']) . "' size ='40'><br />
                </fieldset>
            </div>
            ", true);
        
        output("            
            <div id='allgemeinBox'>
                <fieldset>
                    <legend> Andere Angaben </legend>
                    <label for='bioPlace'>Herkunft: </label><input type='text' name='bioPlace' value='" . escapeTags($row['char_place']) . "' size='40'><br />
                    <label for='bioAge'>Alter: </label><input type='text' name='bioAge' value='" . escapeTags($row['char_age']) . "' size='40'><br />
                    <label for='gesinnung'>Gesinnung: </label><select name='gesinnung'>", true);
        
        $gesinnung_n = count($gesinnung);
        for($i = 1; $i < $gesinnung_n; $i++)
            rawoutput('<option value="'.$i.'"'.($i == $row['gesinnung'] ? ' selected' : '').'> '.$gesinnung[$i].'</option>');
        
        output("</select><br />".
                    /*<label for='plotChar'>Plot-Char: </label><select name='is_plot_char'>
                    <option value='1'".($row['is_plot_char'] == 1 ? 'selected' : '').">Nein</option>
                    <option value='2'".($row['is_plot_char'] == 2 ? 'selected' : '').">Ja</optoin>
                    </select>*/
                "</fieldset>
            </div>
            ", true);
        
        output("</td>", true);
        
        output("<td>", true);
        output("<table valign='top'><tr><td>", true);
        
        output("`n`n`b#c0c0c0Sonstiges:`0`b`n");
        if ($row['choc1'] != 8) {
            //chocobo1
            if ($row['choc1'] == 1)
                output('`1Chocobo 1: `7<img src="/logd/images/chocobos/blau.gif"/>`n', true);
            if ($row['choc1'] == 2)
                output('`1Chocobo 1: `7<img src="/logd/images/chocobos/gelb.gif"/>`n', true);
            if ($row['choc1'] == 3)
                output('`1Chocobo 1: `7<img src="/logd/images/chocobos/gruen.gif"/>`n', true);
            if ($row['choc1'] == 4)
                output('`1Chocobo 1: `7<img src="/logd/images/chocobos/lila.gif"/>`n', true);
            //chocobo2
            if ($row['choc2'] == 1)
                output("`1Chocobo 2: `7<img src='/logd/images/chocobos/blau.gif'/>`n", true);
            if ($row['choc2'] == 2)
                output("`1Chocobo 2: `7<img src='/logd/images/chocobos/gelb.gif'/>`n", true);
            if ($row['choc2'] == 3)
                output("`1Chocobo 2: `7<img src='/logd/images/chocobos/gruen.gif'/>`n", true);
            if ($row['choc2'] == 4)
                output("`1Chocobo 2: `7<img src='/logd/images/chocobos/lila.gif'/>`n", true);
            //chocobo3
            if ($row['choc3'] == 5)
                output("`1Chocobo 3: `7<img src='/logd/images/chocobos/weiss.gif'/>`n", true);
            if ($row['choc3'] == 6)
                output("`1Chocobo 3: `7<img src='/logd/images/chocobos/schwarz.gif'/>`n", true);
            //chocobo4
            if ($row['choc4'] == 5)
                output("`1Chocobo 4: `7<img src='/logd/images/chocobos/weiss.gif'/>`n", true);
            if ($row['choc4'] == 6)
                output("`1Chocobo 4: `7<img src='/logd/images/chocobos/schwarz.gif'/>`n", true);
            //chocobo5
            if ($row['choc5'] == 7)
                output("`1Chocobo 5: `7<img src='/logd/images/chocobos/gold.gif'/>`n", true);
            //chocobo6
            if ($row['choc6'] == 7)
                output("`1Chocobo 6: `7<img src='/logd/images/chocobos/gold.gif'/>`n", true);
        } else {
            output("`1Chocobo: `7<img src='/logd/images/chocobos/gross.gif'/>`n", true);
        }
        
        //Chocoborennen - Preise
        if ($row['choc1']) {
            if ($row['chocnormalprice'] == "kein")
                output("`1Normale Liga: `7kein Preis");
            if ($row['chocnormalprice'] == "platzeins")
                output("`n`1Normale Liga: `7<img src='/logd/images/system/chocobos/platz1.gif'/>", true);
            if ($row['chocnormalprice'] == "platzzwei")
                output("`n`1Normale Liga: `7<img src='/logd/images/system/chocobos/platz2.gif'/>", true);
            if ($row['chocnormalprice'] == "platzdrei")
                output("`n`1Normale Liga: `7<img src='/logd/images/system/chocobos/platz3.gif'/>", true);
            if ($row['chocseltenprice'] == "kein")
                output("`n`1Seltene Liga: `7kein Preis");
            if ($row['chocseltenprice'] == "platzeins")
                output("`n`1Seltene Liga: `7<img src='/logd/images/system/chocobos/platz1.gif'/>", true);
            if ($row['chocseltenprice'] == "platzzwei")
                output("`n`1Seltene Liga: `7<img src='/logd/images/system/chocobos/platz2.gif'/>", true);
            if ($row['chocseltenprice'] == "platzdrei")
                output("`n`1Seltene Liga: `7<img src='/logd/images/system/chocobos/platz3.gif'/>", true);
            if ($row['chocsagenprice'] == "kein")
                output("`n`1Sagenumwobene Liga: `7kein Preis");
            if ($row['chocsagenprice'] == "platzeins")
                output("`n`1Sagenumwobene Liga: `7<img src='/logd/images/system/chocobos/platz1.gif'/>", true);
            if ($row['chocsagenprice'] == "platzzwei")
                output("`n`1Sagenumwobene Liga: `7<img src='/logd/images/system/chocobos/platz2.gif'/>", true);
            if ($row['chocsagenprice'] == "platzdrei")
                output("`n`1Sagenumwobene Liga: `7<img src='/logd/images/system/chocobos/platz3.gif'/>", true);
            if ($row['chocgrossprice'] == "kein")
                output("`n`1Große Liga: `7kein Preis");
            if ($row['chocgrossprice'] == "platzeins")
                output("`n`1Große Liga: `7<img src='/logd/images/system/chocobos/platz1.gif'/>", true);
            if ($row['chocgrossprice'] == "platzzwei")
                output("`n`1Große Liga: `7<img src='/logd/images/system/chocobos/platz2.gif'/>", true);
            if ($row['chocgrossprice'] == "platzdrei")
                output("`n`1Große Liga: `7<img src='/logd/images/system/chocobos/platz3.gif'/>", true);
        }
        
        output("<table border='0' cellspacing='0' cellpadding='0'><tr><td>`GAnsehen:&nbsp;</td><td>" . grafbar(100, ($row['reputation'] + 50), 100, 12) . "</td></tr></table>", true);

        $charmsteps = array( 'sehr hässlich' => 30, 'hässlich' => 100, 'unschön' => 150, 'durchschnittlich schön' => 300, 'schön' => 500, 'sehr schön' => 750, 'unbeschreiblich schön' => 1000, 'Stadtschönheit' => 6000);
        $c_i = 0;
        $c_name = $c_val = 0;
        $c_nxtname = '';
        //note by Salator: Ich weiß nicht warum, aber mit der auf Atrahor installierten PHP-Version müssen die Zeilen mit next weg
        foreach( $charmsteps as $k => $v )
        {
            $c_i++;
            if( $v >= $row['charm'] )
            {
                $c_name = $k;
                $c_val  = $v;

                if(sizeof($charmsteps) > $c_i)
                {
                    //next($charmsteps);
                    $c_nxtname = ' &raquo; '.key(array_slice($charmsteps, $c_i, 1, true));
                    //$c_nxtname = ' &raquo; '.key($charmsteps);
                }

                break;
            }

        }

        if( !$c_val ){
            $max_charm = db_fetch_assoc(db_query('SELECT acctid,charm FROM accounts WHERE sex='.$row['sex'].' ORDER BY charm DESC LIMIT 1'));
            //$c_val = $max_charm['charm'];
            if( $max_charm['acctid'] == $row['acctid'] )
            {
                $c_name = '`b`i'.($row['sex'] ? 'The Sexiest Woman Alive' : 'The Sexiest Man Alive').'!`i`b';
                $c_val  = 0;
            }
            else
            {
                $i = count($charmsteps)-1;
                $c_name = array_keys($charmsteps);
                $c_name = $c_name[ $i ];
                $c_val     = $charmsteps[ $c_name ];
            }
        }

        output("<table border='0' cellspacing='0' cellpadding='0'><tr><td>`GAussehen:&nbsp;</td><td>" . $c_name . "<br>". grafbar($c_val, ($row['charm']), 200, 12) ."</td></tr></table>", true);

        output("`GOrden:");
        if ($row['orden'] == 0) {
            output("#c0c0c0Keine`n");
        }
        if ($row['orden'] == 1) {
            output("#c0c0c0Orden des Wassers`n");
        }
        if ($row['orden'] == 2) {
            output("#c0c0c0Orden des Wassers, Orden des Feuers`n");
        }
        if ($row['orden'] == 3) {
            output("#c0c0c0Orden des Wassers, Orden des Feuers, Orden des Windes`n");
        }
        if ($row['orden'] == 4) {
            output("#c0c0c0Orden des Wassers, Orden des Feuers, Orden des Windes, Orden der Erde`n");
        }
        
        if ($row['herotattoo']) {
            output("`GTätowierungen: ");
            for ($i = 1; $i <= $row['herotattoo']; $i++) {
                output("#c0c0c0" . $tattoos[$i]);
                if ($i < $row['herotattoo'])
                    output(", ");
                else
                    output("`n");
            }
        }
        
        if ($row['pvpflag'] == "5013-10-06 00:42:00")
            output("`n#c0c0c0`iSteht unter besonderem Schutz`i");
        
        output("</td></tr></table>", true);
        output("</td></tr>", true);
        if (getsetting("avatare", 0) == 1)
            output("</td></tr></table>", true);
        
        //RP-Orden von dunkler Lord Artus anfang
        if ($row['postanzahl'] >= 1000) {
            output("<font size='5'>`n`n`c`GRP-Orden`0`c`n</font size>", true);
            output("`c<img src='images/system/common/wappen.jpg' width='150' hight='150' border='0' align=center alt='Bild von Orden1'>`", true);
        }
        if ($row['postanzahl'] >= 2000) {
            output("<img src='images/system/common/wappen.jpg' width='150' hight='150' border='0' align=center alt='Bild von Orden2'>", true);
        }
        if ($row['postanzahl'] >= 3000) {
            output("<img src='images/system/common/wappen.jpg' width='150' hight='150' border='0' align=center alt='Bild von Orden3'>", true);
        }
        if ($row['postanzahl'] >= 4000) {
            output("<img src='images/system/common/wappen.jpg' width='150' hight='150' border='0' align=center alt='Bild von Orden4'>", true);
        }
        if ($row['postanzahl'] >= 5000) {
            output("<img src='images/system/common/wappen.jpg' width='150' hight='150' border='0' align=center alt='Bild von Orden5'>", true);
        }
        if ($row['postanzahl'] >= 1000) {
            output("`c");
        }
        //RP-Orden von dunkler Lord Artus ende
        
        
        
        $bio = $row['bio'];
        
        // Verändern, ob man FSK18 Inhalte sehen oder nicht sehen will
        if (isset($_GET['show_content']))
            $session['user']['prefs']['show_fsk_content'] = $_GET['show_content'];
        
        // FSK 18 Content anzeigen oder ausblenden switch
        // ist die User, dem die Bio gehört überhaupt als volljährig eingetragen?
        if ($row['fsk'] == 2) {
            
            // Wird in seiner Bio ein [fsk18] oder [/fsk18] Tag benutzt?
            if (StrPos(StrToLower($bio), '[fsk18]') !== false || StrPos(StrToLower($bio), '[/fsk18]') !== false) {
                
                // Wenn ja und der aktuelle User ist über 18, Link zum Ein-/Ausstellen der FSK18 Inhalte anzeigen
                if ($session['user']['fsk'] == 2) {
                    if ($session['user']['prefs']['show_fsk_content'])
                        output('`n`n`n`c<a href="popup_bio.php?show_content=0&char=' . RawURLEncode($row['login']) . '">FSK18 Inhalte der Bio ausblenden</a>`c`n`n`n', true);
                    else
                        output('`n`n`n`c<a href="popup_bio.php?show_content=1&char=' . RawURLEncode($row['login']) . '">FSK18 Inhalte der Bio einblenden</a>`c`n`n`n', true);
                }
                
                // die [FSK18] Tags klein machen
                $fsk_klein = str_replace(array(
                    '[FSK18]',
                    '[/FSK18]'
                ), array(
                    '[fsk18]',
                    '[/fsk18]'
                ), $bio);
                
                // Anfangstags zählen
                $bio_set_parts = explode('[fsk18]', $fsk_klein);
                $set_parts_n   = count($bio_set_parts) - 1;
                
                // Endtags zählen
                $bio_offset_parts = explode('[/fsk18]', $fsk_klein);
                $offset_parts_n   = count($bio_offset_parts) - 1;
                
                // Differenz ermitteln
                $difference = $set_parts_n - $offset_parts_n;
                
                // Wenn mehr [/FSK18] Tags vorhanden sind als [FSK18] Tags, dann [/FSK18] Tags hinzufügen
                if ($difference > 0) {
                    for ($i = 0; $i < $difference; $i++)
                        $bio = $bio . '[/fsk18]';
                }
                
                if ($session['user']['fsk'] == 2 && $session['user']['prefs']['show_fsk_content']) {
                    $bio = preg_replace('#\[/?fsk18\]#isU', '', $bio);
                } else {
                    $bio = preg_replace('#\[fsk18\].*\[/fsk18\]#isU', '', $bio);
                    $bio = preg_replace('#\[/?fsk18\]#isU', '', $bio);
                }
            }
        } else {
            // Wenn nein, trotzdem FSK Tags entfernen, wenn der User lustig war ;)
            $bio = preg_replace('#\[fsk18\].*\[/fsk18\]#isU', '', $bio);
            $bio = preg_replace('#\[/?fsk18\]#isU', '', $bio);
        }
        
        
        if ($bio == "")
            output("`n`n`n`r<span id='noBio' style='display: block; height: 520px; '>Keine Kurzbeschreibung vorhanden</span>`n`n`n", true);
        else {
            $prefs = unserialize($row['prefs']);
            
            if ($prefs['use_n'] == 0)
                output("`n`n`n`r" . $bio . "`n`n`n", true);
            else {
                output("`n`n`n`r" . nl2br($bio) . "`n`n`n", true);
            }
        }
        

    
            output("            
            <div id='biofieldBox'>
                <fieldset>
                    <legend> Bio </legend>
                    <table border='0'><tr><td>
                    <p><label>Biohilfen: </label><span><a href='http://www.eassos.de/biotool/index.php' target='_blank'>Bioschreibmaschine</a></span></p>
                    <textarea name='biofield' cols='110' rows='50'>".HTMLEntities(str_replace("`","``",$row['bio']),ENT_QUOTES)."</textarea></td><td>", true);
        rawoutput('<a href=\'javascript:insert("'.$session['user']['name'].'", "", "bio")\' onmouseover=\'Tip("Fügt deinen Namen mit Titel an der Stelle im Eingabefeld ein,
                  wo sich dein Cursor befindet.", DELAY, 1500, FADEOUT, 650, FADEIN, 650, TITLE, "<center>Info</center>", BORDERCOLOR, "#FFFFFF", TITLEBGCOLOR, "#FFFFFF", BGCOLOR, "#222222",
                  TITLEFONTCOLOR, "#000000", FONTCOLOR, "#FFFFFF", WIDTH, 275, FOLLOWMOUSE, false)\' onmouseout=\'UnTip()\'>');
        output($session['user']['name']."
                    </td></tr></table>
                </fieldset>
            </div>
            ",true);

        output("</form>",true);
    break;
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    case "gb":
        $char = $_GET['char'];
        output("`n<font face='Andalus'><font size=6>#303030G#3d3d3dä#4a4a4as#575757t#646464e#717171b#7e7e7eu#8b8b8bc#989898h #a5a5a5v#b2b2b2o#c0c0c0n`0 " . $row['name'] . "`7</font face></font size>", true);
        if ($session['user']['loggedin'])
            output(" <a href=\"popup_mail.php?op=write&to={$row['login']}\" target=\"_blank\" onClick=\"" . popup("popup_mail.php?op=write&to={$row['login']}") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>`n`n", true);

        if ($session['user']['acctid'] != 4 && $row['acctid'] != 4)
            output("<hr>`n`c<a href='popup_bio.php?op=add&char=" . $row['acctid'] . "'>`rEintrag machen</a>`c`n<hr>`n`n`n", true);
        
        
        $sql_gb = "SELECT * FROM bio_gb WHERE bio=" . $row['acctid'] . "";
        $result_gb = db_query($sql_gb) or die(db_error(LINK));
        while ($row_gb = db_fetch_assoc($result_gb)) {
            if ($row_gb['bio'] == $row['acctid']) {
                output("<u>`bDatum:`b`</u> " . $row_gb['gb_date'] . " ", true);
                if ($row['acctid'] == $session['user']['acctid'] || (strpos($row_check_su['editor_blocks'], 'Bio') !== false || $row_check_su['editor_blocks'] == 'all')) {
                    output("[<a href='popup_bio.php?op=del_gb&id=" . $row_gb['gbid'] . "&char=" . $row['acctid'] . "'>Delete</a>]`n", true);
                } else {
                    output("`n");
                }
                output("<u>`bVon:`b`</u> " . $row_gb['gb_from'] . "`n", true);
                output("<u>`bNachricht:`b`</u> `n" . $row_gb['gb_content'] . "`n`n<hr>`n`n", true);
                addnav("", "popup_bio.php?op=del_gb&id=" . $row_gb['gbid'] . "&char=" . $row['acctid'] . "");
            } else {
                output("`c`b`4Noch keine Einträge`0`b`c");
            }
        }
        output("`n`n`n`n");
        break;
    
    case "add":
        if ($_POST['content'] == "") {
            $char = $_GET['char'];
            output("`n`c`bEintrag verfassen`b`c`n`n");
            output("<form action='popup_bio.php?op=add&char=$char' method='POST'>", true);
            addnav("", "popup_bio.php?op=add&char=$char");
            output("<textarea class='input' name='content' cols='37' rows='5'>" . HTMLEntities(stripslashes($_POST['content'])) . "</textarea>`n", true);
            output("<input type='submit' class='button' value='Absenden'></form>", true);
        } else {
            $_POST['content'] .= "`0";
            $char = $_GET['char'];
            $sql  = "INSERT INTO bio_gb (bio,gb_from,gb_date,gb_content) VALUES (" . $row['acctid'] . ",'" . $session['user']['name'] . "',now(),'" . $_POST['content'] . "')";
            db_query($sql);
            systemmail($row['acctid'], "`^Gästebucheintrag!`0", "`&" . $session['user']['name'] . "`3 hat dir einen Eintrag in deinem Gästebuch hinterlassen.");
            header("Location: popup_bio.php?op=gb&char=$char");
            exit();
        }
        break;
    
    case "del_gb":
        $char = $_GET['char'];
        $sql  = "DELETE FROM bio_gb WHERE gbid=" . $_GET['id'];
        db_query($sql) or die(db_error(LINK));
        header("Location: popup_bio.php?op=gb&char=$char");
        exit();
        break;
    
    case "tage":
        $char = $_GET['char'];
        output("`n<font face='Andalus'><font size=6>#303030M#3e3e3ee#4c4c4cm#5b5b5bo#696969i#787878r#868686e#949494n #a3a3a3v#b1b1b1o#c0c0c0n`0 " . $row['name'] . "`7</font face></font size>", true);
        if ($session['user']['loggedin'])
            output(" <a href=\"popup_mail.php?op=write&to={$row['login']}\" target=\"_blank\" onClick=\"" . popup("popup_mail.php?op=write&to={$row['login']}") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>`n`n", true);
        
        if ($session['user']['acctid'] == $row['acctid'] && $session['user']['acctid'] != 4) {
            output("<hr>`n`c<a href='popup_bio.php?op=add_tage&char=" . $row['acctid'] . "'>`rEintrag machen</a>`c`n<hr>`n`n`n", true);
        }
        
        $sql_tage = "SELECT * FROM bio_tage WHERE bio=" . $row['acctid'] . " ORDER BY tageid ASC";
        $result_tage = db_query($sql_tage) or die(db_error(LINK));
        while ($row_tage = db_fetch_assoc($result_tage)) {
            if ($row_tage['bio'] == $row['acctid']) {
                if ($row['acctid'] == $session['user']['acctid'] || (strpos($row_check_su['editor_blocks'], 'Bio') !== false || $row_check_su['editor_blocks'] == 'all')) {
                    output("[<a href='popup_bio.php?op=edit_tage&id=" . $row_tage['tageid'] . "&char=" . $row['acctid'] . "'>Edit</a>] || [<a href='popup_bio.php?op=del_tage&id=" . $row_tage['tageid'] . "&char=" . $row['acctid'] . "'>Del</a>]`n", true);
                } else {
                    output("`n");
                }
                output("`n" . $row_tage['tage_content'] . "`n`n", true);
                addnav("", "popup_bio.php?op=del_tage&id=" . $row_tage['tageid'] . "&char=" . $row['acctid'] . "");
                addnav("", "popup_bio.php?op=edit_tage&id=" . $row_tage['tageid'] . "&char=" . $row['acctid'] . "");
            } else {
                output("`c`b`4Noch keine Einträge`0`b`c");
            }
        }
        output("`n`n`n`n");
        
        
        
        
        
        break;
    
    case "add_tage":
        if ($_POST['content'] == "") {
            $char = $_GET['char'];
            output("`n`c`bEintrag verfassen`b`c`n`n");
            output("<form action='popup_bio.php?op=add_tage&char=$char' method='POST'>", true);
            addnav("", "popup_bio.php?op=add_tage&char=$char");
            output("<textarea class='input' name='content' cols='37' rows='5'>" . HTMLEntities(stripslashes($_POST['content'])) . "</textarea>`n", true);
            output("<input type='submit' class='button' value='Absenden'></form>", true);
        } else {
            $char = $_GET['char'];
            $sql  = "INSERT INTO bio_tage (bio,tage_date,tage_content) VALUES (" . $session['user']['acctid'] . ",now(),'" . $_POST['content'] . "')";
            db_query($sql);
            header("Location: popup_bio.php?op=tage&char=$char");
            exit();
        }
        break;
    
    case "edit_tage":
        if ($_POST['content'] == "") {
            $char = $_GET['char'];
            
            $sql_tage = "SELECT * FROM bio_tage WHERE tageid=" . $_GET['id'] . "";
            $result_tage = db_query($sql_tage) or die(db_error(LINK));
            $row_tage = db_fetch_assoc($result_tage);
            
            output("`n`c`bEintrag editieren`b`c`n`n");
            output("<form action='popup_bio.php?op=edit_tage&id=" . $_GET['id'] . "&char=$char' method='POST'>", true);
            addnav("", "popup_bio.php?op=edit_tage&id=" . $_GET['id'] . "&char=$char");
            output("<textarea class='input' name='content' cols='37' rows='5'>" . escapeTags($row_tage['tage_content']) . "</textarea>`n", true);
            output("<input type='submit' class='button' value='Absenden'></form>", true);
        } else {
            $char = $_GET['char'];
            $sql  = "UPDATE bio_tage SET tage_content='" . $_POST['content'] . "' WHERE tageid=" . $_GET['id'] . "";
            db_query($sql);
            header("Location: popup_bio.php?op=tage&char=$char");
            exit();
        }
        break;
    
    case "del_tage":
        $char = $_GET['char'];
        $sql  = "DELETE FROM bio_tage WHERE tageid=" . $_GET['id'];
        db_query($sql) or die(db_error(LINK));
        header("Location: popup_bio.php?op=tage&char=$char");
        exit();
        break;
    
    case "spass":
        $char = $_GET['char'];
        output("`n<font face='Andalus'><font size=6>#303030O#3b3b3bu#464646t #515151O#5c5c5cf #676767C#727272h#7d7d7da#888888r#939393a#9e9e9ec#a9a9a9t#b4b4b4e#c0c0c0r`0 " . $row['name'] . "`7</font face></font size>", true);
        if ($session['user']['loggedin'])
            output(" <a href=\"popup_mail.php?op=write&to={$row['login']}\" target=\"_blank\" onClick=\"" . popup("popup_mail.php?op=write&to={$row['login']}") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>`n`n", true);
        
        if ($session['user']['acctid'] == $row['acctid'] && $session['user']['acctid'] != 4) {
            output("<hr>`n`c<a href='popup_bio.php?op=add_spass&char=" . $row['acctid'] . "'>`rEintrag machen</a>`c`n<hr>`n`n`n", true);
        }
        
        $sql_tage = "SELECT * FROM bio_spass WHERE bio=" . $row['acctid'] . " ORDER BY spid ASC";
        $result_tage = db_query($sql_tage) or die(db_error(LINK));
        while ($row_tage = db_fetch_assoc($result_tage)) {
            if ($row_tage['bio'] == $row['acctid']) {
                if ($row['acctid'] == $session['user']['acctid'] || (strpos($row_check_su['editor_blocks'], 'Bio') !== false || $row_check_su['editor_blocks'] == 'all')) {
                    output("[<a href='popup_bio.php?op=edit_spass&id=" . $row_tage['spid'] . "&char=" . $row['acctid'] . "'>Edit</a>] || [<a href='popup_bio.php?op=del_spass&id=" . $row_tage['spid'] . "&char=" . $row['acctid'] . "'>Del</a>]`n", true);
                } else {
                    output("`n");
                }
                output("`n" . $row_tage['spass_content'] . "`n`n", true);
                addnav("", "popup_bio.php?op=del_spass&id=" . $row_tage['spid'] . "&char=" . $row['acctid'] . "");
                addnav("", "popup_bio.php?op=edit_spass&id=" . $row_tage['spid'] . "&char=" . $row['acctid'] . "");
            } else {
                output("`c`b`4Noch keine Einträge`0`b`c");
            }
        }
        output("`n`n`n`n");
        break;
    
    case "add_spass":
        if ($_POST['content'] == "") {
            $char = $_GET['char'];
            output("`n`c`bEintrag verfassen`b`c`n`n");
            output("<form action='popup_bio.php?op=add_spass&char=$char' method='POST'>", true);
            addnav("", "popup_bio.php?op=add_spass&char=$char");
            output("<textarea class='input' name='content' cols='37' rows='5'>" . HTMLEntities(stripslashes($_POST['content'])) . "</textarea>`n", true);
            output("<input type='submit' class='button' value='Absenden'></form>", true);
        } else {
            $char = $_GET['char'];
            $sql  = "INSERT INTO bio_spass (bio,spass_date,spass_content) VALUES (" . $session['user']['acctid'] . ",now(),'" . $_POST['content'] . "')";
            db_query($sql);
            header("Location: popup_bio.php?op=spass&char=$char");
            exit();
        }
        break;
    
    case "edit_spass":
        if ($_POST['content'] == "") {
            $char = $_GET['char'];
            
            $sql_tage = "SELECT * FROM bio_spass WHERE spid=" . $_GET['id'] . "";
            $result_tage = db_query($sql_tage) or die(db_error(LINK));
            $row_tage = db_fetch_assoc($result_tage);
            
            output("`n`c`bEintrag editieren`b`c`n`n");
            output("<form action='popup_bio.php?op=edit_spass&id=" . $_GET['id'] . "&char=$char' method='POST'>", true);
            addnav("", "popup_bio.php?op=edit_spass&id=" . $_GET['id'] . "&char=$char");
            output("<textarea class='input' name='content' cols='37' rows='5'>" . escapeTags($row_tage['spass_content']) . "</textarea>`n", true);
            output("<input type='submit' class='button' value='Absenden'></form>", true);
        } else {
            $char = $_GET['char'];
            $sql  = "UPDATE bio_spass SET spass_content='" . $_POST['content'] . "' WHERE spid=" . $_GET['id'] . "";
            db_query($sql);
            header("Location: popup_bio.php?op=spass&char=$char");
            exit();
        }
        break;
    
    case "del_spass":
        $char = $_GET['char'];
        $sql  = "DELETE FROM bio_spass WHERE spid=" . $_GET['id'];
        db_query($sql) or die(db_error(LINK));
        header("Location: popup_bio.php?op=spass&char=$char");
        exit();
        break;
    
    case "perso":
        output("`n`G<font face='Andalus'><font size=6>#4a4a4aP#515151e#595959r#616161s#696969ö#707070n#787878l#808080i#888888c#909090h#949494e #989898A#9c9c9cn#a1a1a1g#a5a5a5a#aaaaaab#aeaeaee#b2b2b2n #b7b7b7v#bbbbbbo#c0c0c0n`0 $row[name]`7</font face></font size>", true);
        if ($session['user']['loggedin'])
            output(" <a href=\"popup_mail.php?op=write&to={$row['login']}\" target=\"_blank\" onClick=\"" . popup("popup_mail.php?op=write&to={$row['login']}") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>", true);
        
        if (($row['acctid'] == $session['user']['acctid'] && $session['user']['acctid'] != 4) || $session['user']['superuser'] > 2)
            output("<span class='editButton' id='bioPersonalEdit'><img src='images/system/iface/edit.png' alt='Bio editieren' title='Bio editieren' border='0' ></span>", true);
        output("`n`n");
        
        output("<form action='popup_bio.php?op=savepersonal&char=" . $row['acctid'] . "&user=" . $row['acctid'] . "&ret=pers' method='POST'>", true);
        addnav("", "popup_bio.php?op=savepersonal&char=" . $row['acctid'] . "&user=" . $row['acctid'] . "&ret=pers");
        output("<div class='saveButton'><input type='submit' class='bioSaveButton' value='Speichern'></div>", true);
        output("<div class='overlay'></div>", true);
        
        output("<table width='100%' border='0' cellpadding='2' cellspacing='2'><tr id='personalAvaRow'>", true);
        if (getsetting("avatare", 0) == 1) {
            if ($row['personal_avatar']) {
                $pic_size   = @getimagesize($row['personal_avatar']);
                $pic_width  = $pic_size[0];
                $pic_height = $pic_size[1];
                output("<td valign='middle'  align='center' width='400'>`n`n<img src='" . $row['personal_avatar'] . "'", true);
                if ($pic_width > 400)
                    output("width=\"400\" ", true);
                if ($pic_height > 400)
                    output("height=\"400\" ", true);
                output("alt=\"" . stripcolors($row['personal_name']) . "\">&nbsp;</td>", true);
            } else {
                output("<td align='center'>(kein Bild)&nbsp;&nbsp;&nbsp;</td>", true);
            }
        }
        
        output("            
                <div class='avaBox'>
                    <fieldset>
                        <legend> Bild von dir </legend>
                        <label for='personalBioava' >Link zum Bild: </label>
                        <input type='text' name='personalBioava' value='" . $row['personal_avatar'] . "' size='60'>
                    </fieldset>
                </div>
                ", true);
        
        output("<td valign='middle' width='400'>", true);
        if ($row['personal_name'])
            output("`GName: `7" . $row['personal_name'] . "`n");
        if ($row['personal_sex'] == 0)
            output("`GGeschlecht: `7Keine Angabe`n");
        if ($row['personal_sex'] == 1)
            output("`GGeschlecht: `7Männlich`n");
        if ($row['personal_sex'] == 2)
            output("`GGeschlecht: `7Weiblich`n");
        if ($row['personal_place'])
            output("`GWohnort: `7" . $row['personal_place'] . "`n");
        if ($row['personal_bday'])
            output("`GGeburtstag: `7" . $row['personal_bday'] . "`n");
        if ($row['personal_couple'])
            output("`GFamilienstand: `7" . $row['personal_couple'] . "`n");
        if ($row['personal_hobby'])
            output("`GHobbys: `7" . $row['personal_hobby'] . "`n");
        if ($row['personal_about']) {
            $personal_about = $row['personal_about'];
        } else {
            $personal_about = "Keine Angabe";
        }
        output("`GÜber mich: `7" . $personal_about . "`n");
        
        if ($row['fsk'] == 0)
            output("`GVolljährig: `7Keine Angabe`n");
        if ($row['fsk'] == 1)
            output("`GVolljährig: `7Nein`n");
        if ($row['fsk'] == 2)
            output("`GVolljährig: `7Ja`n");
        output("</td></tr></table>`n`n", true);
        
        output("            
                <div id='personalBox'>
                    <fieldset>
                        <legend> Persönliche Informationen </legend>
                        <label for='personalName' >Name: </label> <input type='text' name='personalName' value='" . escapeTags($row['personal_name']) . "' size='40'><br />
                        <label for='personalSex' >Geschlecht: </label>
                            <select name='personalSex' size='1'>", true);
        if ($row['personal_sex'] == 0) {
            output("<option name='personalSex' value=0 selected>keine Angabe</option>", true);
            output("<option name='personalSex' value=1>Männlich</option>", true);
            output("<option name='personalSex' value=2>Weiblich</option>", true);
        } else if ($row['personal_sex'] == 1) {
            output("<option name='personalSex' value=0>keine Angabe</option>", true);
            output("<option name='personalSex' value=1 selected>Männlich</option>", true);
            output("<option name='personalSex' value=2>Weiblich</option>", true);
        } else if ($row['personal_sex'] == 2) {
            output("<option name='personalSex' value=0>keine Angabe</option>", true);
            output("<option name='personalSex' value=1>Männlich</option>", true);
            output("<option name='personalSex' value=2 selected>Weiblich</option>", true);
        }
        output("    </select><br />
                        <label for='personalPlace' >Wohnort: </label> <input type='text' name='personalPlace' value='" . escapeTags($row['personal_place']) . "' size='40'><br />
                        <label for='personalBday' >Geburtstag: </label> <input type='text' name='personalBday' value='" . escapeTags($row['personal_bday']) . "' size='40'><br />
                        <label for='personalCouple' >Familienstand: </label> <input type='text' name='personalCouple' value='" . escapeTags($row['personal_couple']) . "' size='40'><br />
                        <label for='personalHobby' >Hobbys: </label> <input type='text' name='personalHobby' value='" . escapeTags($row['personal_hobby']) . "' size='40'><br />
                        <label for='personalAbout' >Über mich: </label> <textarea name='personalAbout' cols='30' rows='10'>" . escapeTags($row['personal_about']) . "</textarea>
                    </fieldset>
                </div>
                ", true);
        
        
        output("</form>", true);
        break;
    
    case "veribio":
        output("`n`G<font face='Andalus'><font size=6>#303030Ü#383838b#404040e#494949r #5151511#5a5a5a8 #626262B#6b6b6bi#737373o#7c7c7cg#848484r#8d8d8da#959595f#9e9e9ei#a6a6a6e #afafafv#b7b7b7o#c0c0c0n`0 $row[name]`7</font face></font size>", true);
        if ($session['user']['loggedin'])
            output(" <a href=\"popup_mail.php?op=write&to={$row['login']}\" target=\"_blank\" onClick=\"" . popup("popup_mail.php?op=write&to={$row['login']}") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>", true);
        
        if (($row['acctid'] == $session['user']['acctid'] && $session['user']['acctid'] != 4) || $session['user']['superuser'] > 2)
            output("<span class='editButton' id='bioVeriEdit'><img src='images/system/iface/edit.png' alt='Bio editieren' title='Bio editieren' border='0' ></span>", true);
        output("`n`n");
        
        output("<form action='popup_bio.php?op=saveveri&char=" . $row['acctid'] . "&user=" . $row['acctid'] . "&ret=pers' method='POST'>", true);
        addnav("", "popup_bio.php?op=saveveri&char=" . $row['acctid'] . "&user=" . $row['acctid'] . "&ret=pers");
        output("<div class='saveButton'><input type='submit' class='veriSaveButton' value='Speichern'></div>", true);
        output("<div class='overlay'></div>", true);
        

        
        output("            
            <div id='veriBox'>
                <fieldset>
                    <legend> ü18 Bio </legend>
                    <p><label>Biohilfen: </label><span><a href='http://www.eassos.de/biotool/index.php' target='_blank'>Bioschreibmaschine</a></span></p>
                    <textarea name='veribiofield' cols='110' rows='20'>" . escapeTags($row['veribio']) . "</textarea>
                </fieldset>
            </div>
            ", true);
        
        if ($session['user']['fsk'] != 2) {
            output("`c`b`rDa du keine Altersverifizierung hast, kannst du diesen Teil leider nicht sehen.`b`c`n`n");
        }
        
        if ($row['fsk'] != 2) {
            output("`c`b`r Der gewählte Charakter ist nicht verifiziert und besitzt daher eine solche Bio nicht.`b`c`n`n");
        } else if ($session['user']['fsk'] == 2 && $row['fsk'] == 2) {
            output("`n`n`n" . $row['veribio'] . "`n", true);
        }
        
        output("<div id='placeholder'></div>", true);
        output("</form>", true);
        break;
    
    
    case "family":
        output("<link href='lib/styles/stammbaum_style.css' rel='stylesheet' type='text/css'>", true);
        
        $char = $_GET['char'];
        output("`n<font face='Andalus'><font size=6>#303030A#3c3c3ch#484848n#545454e#606060n#6c6c6cr#787878e#848484i#909090h#9c9c9ce #a8a8a8v#b4b4b4o#c0c0c0n`0 " . $row['name'] . "`7</font face></font size>", true);
        if ($session['user']['loggedin'])
            output(" <a href=\"popup_mail.php?op=write&to=$row[acctid]\" target=\"_blank\" onClick=\"" . popup("popup_mail.php?op=write&to=$row[acctid]") . ";return false;\"><img src='images/Assos/Icons/newsroll.png' width='16' height='16' alt='Mail schreiben' border='0'></a>`n`n", true);
        
        if ($session['user']['acctid'] == $row['acctid']) {
            output("<hr>`n`c<a href='popup_bio.php?op=add_family&char=" . $row['acctid'] . "'>`rEintrag machen</a>`c`n<hr>`n", true);
        }
        
        
        
        $sql_fam = "SELECT * FROM bio_family WHERE bio=" . $row['acctid'];
        $result_fam = db_query($sql_fam) or die(db_error(LINK));
        
        if (db_num_rows($result_fam) > 0) {
            output("<div class='fam_box_type1'>", true);
            output($row['name']);
            output("</div>", true);
            
            while ($row_fam = db_fetch_assoc($result_fam)) {
                $fam_list = explode('_', $row_fam['fam_grad']);
                
                if (in_array("child", $fam_list))
                    output("Ok");
                
                output("<div class='fam_box_type2'>", true);
                output($row_fam['fam_name']);
                
                if ($row['acctid'] == $session['user']['acctid']) {
                    output("<div class='ops'>", true);
                    output("<a href='popup_bio.php?op=edit_fam&id=" . $row_fam['fam_id'] . "&char=" . $row['acctid'] . "'>Edit</a>", true);
                    output("<a href='popup_bio.php?op=del_fam&id=" . $row_fam['fam_id'] . "&char=" . $row['acctid'] . "'>Del</a>", true);
                    output("</div>", true);
                }
                
                output("</div>", true);
                
                addnav("", "popup_bio.php?op=del_fam&id=" . $row_fam['fam_id'] . "&char=" . $row['acctid'] . "");
                addnav("", "popup_bio.php?op=edit_fam&id=" . $row_fam['dam_id'] . "&char=" . $row['acctid'] . "");
            }
        } else {
            output("`c`b`4Noch keine Einträge`0`b`c");
        }
        
        break;
    
    case "add_family":
        if ($_POST['fam_name'] == "") {
            $char = $_GET['char'];
            
            $sql_check_fam = "SELECT * FROM bio_family WHERE bio=" . $session['user']['acctid'];
            $result_check_fam = db_query($sql_check_fam) or die(db_error(LINK));
            
            output("`n`c`bIn Stammbaum eintragen`b`c`n`n");
            output("<form action='popup_bio.php?op=add_family&char=$char' method='POST'>", true);
            output("<table border='0' cellpadding='2' cellspacing='2'>", true);
            output("<tr>", true);
            output("<td><label for='fam_name'>Names des Familienmitglieds:</label><td/>", true);
            output("<td><input type='text' name='fam_name'></td>", true);
            output("</tr>", true);
            
            output("<tr>", true);
            output("<td><label for='fam_sex'>Geschlecht:</label><td/>", true);
            output("<td><input type='radio' name='fam_sex' value=0><img src=\"images/system/common/male.gif\"></input> <input type='radio' name='fam_sex' value=1><img src=\"images/system/common/female.gif\"></input></td>", true);
            output("</tr>", true);
            
            output("<tr>", true);
            output("<td><label for='fam_grad'>Verwandschaftsgrad:</label><td/>", true);
            output("<td>", true);
            if (db_num_rows($result_check_fam) > 0) {
                output("<label>" . $session['user']['name'] . "`0</label><br />", true);
                output("<input type='radio' name='fam_grad' value='partner_" . $session['user']['acctid'] . "'>Partner von " . $session['user']['name'] . "`0</input><br />", true);
                output("<input type='radio' name='fam_grad' value='child_" . $session['user']['acctid'] . "'>Kind von " . $session['user']['name'] . "`0</input><br /><br />", true);
                
                while ($row_check_fam = db_fetch_assoc($result_check_fam)) {
                    output("<label>" . $row_check_fam['fam_name'] . "`0</label><br />", true);
                    output("<input type='radio' name='fam_grad' value='partner_" . $row_check_fam['fam_id'] . "'>Partner von " . $row_check_fam['fam_name'] . "`0</input><br />", true);
                    output("<input type='radio' name='fam_grad' value='child_" . $row_check_fam['fam_id'] . "'>Kind von " . $row_check_fam['fam_name'] . "`0</input><br /><br />", true);
                }
            } else {
                output("<input type='radio' name='fam_grad' value='partner_" . $session['user']['acctid'] . "'>Partner von " . $session['user']['name'] . "`0</input><br />", true);
                output("<input type='radio' name='fam_grad' value='child_" . $session['user']['acctid'] . "'>Kind von " . $session['user']['name'] . "`0</input>", true);
            }
            output("</td>", true);
            output("</tr>", true);
            
            output("<tr>", true);
            output("<td colspan='3'>`c<input type='submit' class='button' value='Absenden'>`c</td>", true);
            output("</tr>", true);
            output("</table></form>", true);
            
            addnav("", "popup_bio.php?op=add_family&char=$char");
        } else {
            $char = $_GET['char'];
            $sql  = "INSERT INTO bio_family (bio, fam_grad, fam_name, fam_sex) VALUES (" . $session['user']['acctid'] . ", '" . $_POST['fam_grad'] . "', '" . $_POST['fam_name'] . "', '" . $_POST['fam_sex'] . "')";
            db_query($sql);
            header("Location: popup_bio.php?op=family&char=$char");
            exit();
        }
        
        break;
    
    
    case "settings":
        $char = $_GET['char'];
        
        if ($_GET['action'] == "save") {
            if (isset($_POST['template'])) {
                setcookie("template", $_POST['template'], strtotime(date("c") . "+45 days"));
                $_COOKIE['template'] = $_POST['template'];
            }
            
            if ($session['user']['acctid'] != 4) {
                if ($_POST['pass1'] != '' && $_POST['pass2'] != '') {
                    if ($_POST['pass1'] != $_POST['pass2'])
                        $session['alert_msg'] = '`$Die angegebenen Passwörter stimmen nicht überein!';
                    elseif (StrLen($_POST['pass1']) < 4)
                        $session['alert_msg'] = '`$Dein Passwort muss mindestens 4 Zeichen lang sein!';
                    else
                        $session['user']['password'] = md5($_POST['pass1']);
                }
                
                if (!empty($_POST['email']))
                    $session['user']['emailaddress'] = $_POST['email'];
            } else
                $session['alert_msg'] = '`$Du kannst mit dem Testchara nicht das Passwort und die Email ändern.';
            
            
            reset($_POST);
            $no_prefs = array(
                'pass1' => true,
                'pass2' => true,
                'email' => true
            );
            
            // Bearbeiten von nervigen \ in Strings
            if (!empty($_POST['afk']))
                $_POST['afk'] = htmlentities(stripslashes($_POST['afk']));
            if (!empty($_POST['yom_sig']))
                $_POST['yom_sig'] = htmlentities(stripSlashes($_POST['yom_sig']));
            if (!empty($_POST['tags_1']))
                $_POST['tags_1'] = htmlentities(stripslashes($_POST['tags_1']));
            if (!empty($_POST['tags_2']))
                $_POST['tags_2'] = htmlentities(stripslashes($_POST['tags_2']));
            if (!empty($_POST['tags_3']))
                $_POST['tags_3'] = htmlentities(stripslashes($_POST['tags_3']));
            if (!empty($_POST['tags_4']))
                $_POST['tags_4'] = htmlentities(stripslashes($_POST['tags_4']));
            if (!empty($_POST['tags_5']))
                $_POST['tags_5'] = htmlentities(stripslashes($_POST['tags_5']));
            if (!empty($_POST['tags_6']))
                $_POST['tags_6'] = htmlentities(stripslashes($_POST['tags_6']));
            if (!empty($_POST['tags_7']))
                $_POST['tags_7'] = htmlentities(stripslashes($_POST['tags_7']));
            if (!empty($_POST['tags_8']))
                $_POST['tags_8'] = htmlentities(stripslashes($_POST['tags_8']));
            if (!empty($_POST['tags_9']))
                $_POST['tags_9'] = htmlentities(stripslashes($_POST['tags_9']));
            if (!empty($_POST['tags_10']))
                $_POST['tags_10'] = htmlentities(stripslashes($_POST['tags_10']));
            if (!empty($_POST['info']))
                $_POST['info'] = htmlentities(stripslashes($_POST['info']));
            
            foreach ($_POST as $key => $val) {
                if (!$no_prefs[$key])
                    $session['user']['prefs'][$key] = $_POST[$key];
            }
            header("Location: popup_bio.php?op=settings&char=" . $char . "&return=1");
            //exit();
        } else {
            output("`n<font face='Andalus'><font size=6>#303030E#393939i#434343n#4c4c4cs#565656t#606060e#696969l#737373l#7c7c7cu#868686n#909090g#999999e#a3a3a3n`n</font face></font size>", true);
            if (isset($session['alert_msg'])) {
                output('`n`n' . $session['alert_msg'] . '`n`n');
                
                unset($session['alert_msg']);
            }
            
            $biosettings = array(
                "Accounteinstellungen,title",
                'pass1' => 'Neues Passwort,toolpassword,Gib hier ein neues Passwort ein. Das Passwort muss mindestens 4 Zeichen lang sein!',
                'pass2' => 'Neues Passwort wiederholen,password',
                'email' => 'Email ändern',
                "Darstellungsoptionen für die Bio,title",
                "show_no_title" => "Standard-Systemtitel anzeigen?,bool",
                "show_no_fight" => "Kampfinformationen anzeigen?,bool",
                "use_n" => "in der Bio Absätze mit Enter statt mit ``n machen?,bool",
                "Allgemein Einstellungen,title",
                "directspeach" => "Direkte Rede automatisch einfärben?,bool",
                //"nosounds"             => "Die Sounds deaktivieren?,bool",
                "otset" => "OOC Chat,enum,0,Einwohnerliste,1,Obere Navigation,2,Beides",
                "flist" => "Freundesliste anzeigen?,toolbool,Die Freundesliste ist identisch mit dem Adressbuch in deinen YoMs. Füge dort Freunde hinzu und diese werden auch in der Freundesliste sichtbar. Die Freundesliste befindet sich unter den Charakterwerten[KOMMA] überhalb der Hier anwesend Liste[KOMMA] falls diese aktiviert wurde.",
                "ulist" => "'Wer ist hier' anzeigen?,toolbool,Diese Liste erscheint unter den Charakterwerten[KOMMA] bzw. unter der Freundesliste[KOMMA] falls diese aktiviert wurde. Sie zeigt an[KOMMA] welcher Spieler sich noch am selben Ort/Platz befindet wie Du.<br /><i>Achtung: Im Wohnviertel oder den Magischen Orten müssen die anderen angezeigten Spieler sich nicht im gleichen Raum/Ort wie Du aufhalten!</i>",
                "log" => "Changelog anzeigen?,toolbool,Der Changelog zeigt die Veränderungen am Server an. Er wird bei fast jeder noch so kleinen Arbeit am Server durch die Techadmins aktualisiert. Falls Du wissen willst[KOMMA] was an Eassos gearbeitet wurde[KOMMA] stelle dies hier auf ja. Der Link dafür wird ganz unten bei deinen Charakterwerten sichtbar.",
                "list_type" => "Einwohnerlisten Stil:,enum,0,alt,1,neu",
                "Farb- und Kommentareinstellungen,title",
                "commenttalkcolor" => "Standardfarbe bei Gesprächen",
                "commentemotecolor" => "Standardfarbe bei Emotes (/me)",
                "comlim" => "Angezeigte Kommentare auf Plätzen`n(`bStandart 10`b),int",
                "yom_sig" => "Signatur für YoM-Nachrichten",
                "Schnelleingaben,tooltitle,Schnelleingaben werden unter dem Kommentarfeld nach einem Klick auf Tags zeigen angezeigt. Hier kannst Du längere Wörter (auch mit Farbe) hinterlegen. Z.B. den farbigen Namen eines Partners oder eines Begleiters.",
                "tags_1" => "Schnelleingabe 1",
                "tags_2" => "Schnelleingabe 2",
                "tags_3" => "Schnelleingabe 3",
                "tags_4" => "Schnelleingabe 4",
                "tags_5" => "Schnelleingabe 5",
                "tags_6" => "Schnelleingabe 6",
                "tags_7" => "Schnelleingabe 7",
                "tags_8" => "Schnelleingabe 8",
                "tags_9" => "Schnelleingabe 9",
                "tags_10" => "Schnelleingabe 10",
                "Info-Meldung (Kämpferliste),title",
                "info" => "Informationsmeldung für die Kämpferliste:,textarea,44,10",
                "info2" => "Info?,toolbool,Soll die Info Meldung angezeigt werden[KOMMA] muss Info? auf Ja stehen. "
            );
            
            $prefs = $session['user']['prefs'];
            if (isset($prefs['tags_1']))
                $prefs['tags_1'] = html_entity_decode(stripslashes($prefs['tags_1']));
            if (isset($prefs['tags_2']))
                $prefs['tags_2'] = html_entity_decode(stripslashes($prefs['tags_2']));
            if (isset($prefs['tags_3']))
                $prefs['tags_3'] = html_entity_decode(stripslashes($prefs['tags_3']));
            if (isset($prefs['tags_4']))
                $prefs['tags_4'] = html_entity_decode(stripslashes($prefs['tags_4']));
            if (isset($prefs['tags_5']))
                $prefs['tags_5'] = html_entity_decode(stripslashes($prefs['tags_5']));
            if (isset($prefs['tags_6']))
                $prefs['tags_6'] = html_entity_decode(stripslashes($prefs['tags_6']));
            if (isset($prefs['tags_7']))
                $prefs['tags_7'] = html_entity_decode(stripslashes($prefs['tags_7']));
            if (isset($prefs['tags_8']))
                $prefs['tags_8'] = html_entity_decode(stripslashes($prefs['tags_8']));
            if (isset($prefs['tags_9']))
                $prefs['tags_9'] = html_entity_decode(stripslashes($prefs['tags_9']));
            if (isset($prefs['tags_10']))
                $prefs['tags_10'] = html_entity_decode(stripslashes($prefs['tags_10']));
            if (isset($prefs['afk']))
                $prefs['afk'] = html_entity_decode(stripslashes($prefs['afk']));
            $prefs['email'] = $session['user']['emailaddress'];
            if (isset($prefs['yom_sig']))
                $prefs['yom_sig'] = html_entity_decode(stripSlashes($prefs['yom_sig']));
            if (isset($prefs['info']))
                $prefs['info'] = html_entity_decode(stripSlashes($prefs['info']));
         
            if ($_GET['return'] == 1)
                output("`n`n<div id='saved'>`bEinstellungen gespeichert.`b</div>`n`n", true);
            
            
            /*
            Bio Backupsystem von Andras für Saint-Omar
            */
            
            // Überprüfe, ob es bereits ein Backup gibt
            $path = './text/bio_backups/' . $session['user']['acctid'] . '.txt';
            if (file_exists($path)) {
                // öffne txt Datei
                $content = file_get_contents($path);
                
                // verarbeite die Datei
                if (StrPos($content, '---[DELIMITER]---') !== false) {
                    $content_parts = explode('---[DELIMITER]---', $content);
                    
                    $date     = $content_parts[0];
                    $date_sek = date('U', StrToTime($date));
                    $now      = time();
                    $time_dif = $now - $date_sek;
                    
                    $month = 60 * 60 * 24 * 30;
                    
                    output('`b`&Bio Backups:`b `n`n
                            <table style="width: 300px">
                                <tr>
                                    <td style="width: 50%"> `&Backup erzeugt </td>
                                    <td style="width: 50%; text-align: right"> `&' . showDate($date) . ' </td>
                                </tr><tr>
                                    ' . ($time_dif > $month ? '<tr><td colspan="2" style="text-align: center">`i`$Dein Backup ist über einen Monat alt. Du solltest einmal ein neues anlegen.`0 </td></tr><tr>' : '') . '
                                    <td colspan="2" style="text-align: center"> ' . createLink('neues Backup erzeugen', 'popup_bio.php?op=create_backup&char=' . $char, false, 'Achtung, Dein bisheriges Backup wird überschrieben, wenn du ein neues anfertigst! Fortfahren?') . ' </td>
                                </tr><tr>
                                    <td colspan="2" style="text-align: center"> &nbsp `n ' . createLink('Backup anzeigen', 'popup_bio.php?op=show_backup&char=' . $char, false) . ' </td>
                                </tr>
                            </table> `n`n`n', true);
                } else {
                    output('`b`$Schwerer Fehler. Trennerzeichen nicht gefunden. Kontaktiere den Techadmin!`b');
                }
            } else {
                output('`b`&Bio Backups`b`n`n
                        `$Es wurde bisher kein Backup angefertigt. Du solltest das tun, wenn du eine Bio besitzt! `n`n
                        ' . createLink('Backup erstellen', 'popup_bio.php?op=create_backup&char=' . $char, false) . ' `n`n`n`n', true);
            }
            
            
            
            output("<form action='popup_bio.php?op=settings&action=save&char=" . $char . "' method='POST'>", true);
            
            $result = db_query('SELECT `templatename` AS `tpl_name`, `tsrc` AS `tpl`, `freefor` AS `user` FROM `templates` ORDER BY `templatename`') or die(db_error(LINK));
            if (db_num_rows($result)) {
                output('`&`bWelchen Skin willst Du benutzen?`b`0`n<table><tbody>', true);
                while ($row = db_fetch_assoc($result)) {
                    if ($session['user']['superuser'] >= $row['user'])
                        if ($_COOKIE['template'] == $row['tpl']) {
                            rawoutput('<tr><td><input type="radio" checked name="template" value="' . $row['tpl'] . '">' . $row['tpl_name'] . '</td></tr>');
                        } elseif ($_COOKIE['template'] == "" && $row['tpl'] == "newsaint.htm") {
                            rawoutput('<tr><td><input type="radio" checked name="template" value="' . $row['tpl'] . '">' . $row['tpl_name'] . '</td></tr>');
                        } else {
                            rawoutput('<tr><td><input type="radio" name="template" value="' . $row['tpl'] . '">' . $row['tpl_name'] . '</td></tr>');
                        }
                }
                rawoutput('</tbody></table><br /><br />');
            } else
                rawoutput('<strong style="color: #FF0000;">Es sind keine Templates in der Tabelle vorhanden!</strong><br /><br />');
            
            
            showform($biosettings, $prefs);
            output("</form>", true);
            
            
            
            addnav("", "popup_bio.php?op=settings&action=save&char=" . $char);
        }
        
        break;
    
    case 'show_backup':
        $path = './text/bio_backups/' . $session['user']['acctid'] . '.txt';
        if (file_exists($path)) {
            // öffne txt Datei
            $content = file_get_contents($path);
            
            // verarbeite die Datei
            if (StrPos($content, '---[DELIMITER]---') !== false) {
                $content_parts = explode('---[DELIMITER]---', $content);
                
                $date   = $content_parts[0];
                $avatar = $content_parts[1];
                $bio    = $content_parts[2];
                
                
                output('`b`&Backup vom ' . date('d.m.Y', StrToTime($date)) . '`b `n
                        `n
                        `c`&Avatar:`n
                        <img src="' . $avatar . '" alt="Dein Avatar"> `n
                        <input value="' . $avatar . '" size="50"> `n
                        `n
                        `n
                        `&Bio:`c `n
                        `n
                        ' . CloseTags(StripSlashes($bio), '`i`b`c') . ' `n
                        `n', true);
                rawoutput('<textarea cols="70" rows="20" class="input">' . $bio . '</textarea>');
            }
        }
        break;
    
    case 'create_backup':
        $content = date('Y-m-d H:i:s') . '---[DELIMITER]---' . $row['avatar'] . '---[DELIMITER]---' . stripslashes($row['bio']);
        
        file_put_contents('./text/bio_backups/' . $session['user']['acctid'] . '.txt', $content);
        
        header('Location: popup_bio.php?op=settings&char=' . $_GET['char']);
        break;
    
    
    //Gott, ist das hässlich. -_-
    //Warum zum Teufel wird der Wert erst in ne extra-Variable geschrieben, bevor er gespeichert wird? ôo (Nashyan)
    case "savebio":
        $player = $_GET['user'];
        $char   = $_GET['char'];
        
        
        if($_POST['gesinnung'] != $row['gesinnung']) {
            db_query('UPDATE accounts SET gesinnung = '.(INT)$_POST['gesinnung'].' WHERE acctid = '.$player);
        }
        
        /*if($_POST['is_plot_char'] != $row['is_plot_char']) 
        {
            db_query('UPDATE accounts SET is_plot_char = '.(INT)$_POST['is_plot_char'].' WHERE acctid = '.$player);
        }*/
        
        if ($_POST['bioava'] != $row['bioava']) {
            $ava = stripslashes(preg_replace("'[\"\'\\><@?*&#; ]'", "", $_POST['bioava']));
            
            $sql = "UPDATE bios SET avatar='" . $ava . "', avadate='" . date("Y-m-d H:i:s") . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioavaname'] != $row['avatar_name']) {
            $ava_name = $_POST['bioavaname'];
            
            $sql = "UPDATE bios SET avatar_name='" . $ava_name . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['biorace'] != $row['race']) {
            $race = $_POST['biorace'];
            
            $sql = "UPDATE accounts SET race='" . $race . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioUrace'] != $row['urace']) {
            $urace = $_POST['bioUrace'];
            
            $sql = "UPDATE accounts SET urace='" . $urace . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioEltern'] != $row['char_parents']) {
            $eltern = $_POST['bioEltern'];
            
            $sql = "UPDATE bios SET char_parents='" . $eltern . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioVater'] != $row['char_vater']) {
            $vater = $_POST['bioVater'];
            
            $sql = "UPDATE bios SET char_vater='" . $vater . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        if ($_POST['bioMutter'] != $row['char_mutter']) {
            $mutter = $_POST['bioMutter'];
            
            $sql = "UPDATE bios SET char_mutter='" . $mutter . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioBruder'] != $row['char_bro']) {
            $bruder = $_POST['bioBruder'];
            
            $sql = "UPDATE bios SET char_bro='" . $bruder . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioSchwester'] != $row['char_sis']) {
            $schwester = $_POST['bioSchwester'];
            
            $sql = "UPDATE bios SET char_sis='" . $schwester . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioAdoptiert'] != $row['char_adopted']) {
            $adoptiert = $_POST['bioAdoptiert'];
            
            $sql = "UPDATE bios SET char_adopted='" . $adoptiert . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioPate'] != $row['char_pate']) {
            $pate = $_POST['bioPate'];
            
            $sql = "UPDATE bios SET char_pate='" . $pate . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioBegleiter'] != $row['char_begleiter']) {
            $begleiter = $_POST['bioBegleiter'];
            
            $sql = "UPDATE bios SET char_begleiter='" . $begleiter . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioFreunde'] != $row['char_friend']) {
            $freunde = $_POST['bioFreunde'];
            
            $sql = "UPDATE bios SET char_friend='" . $freunde . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioAffaeren'] != $row['char_affairs']) {
            $affaere = $_POST['bioAffaeren'];
            
            $sql = "UPDATE bios SET char_affairs='" . $affaere . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioSize'] != $row['bio_size']) {
            $sql = "UPDATE bios SET bio_size='" . $_POST['bioSize'] . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioFrisur'] != $row['bio_frisur']) {
            $frisur = $_POST['bioFrisur'];
            
            $sql = "UPDATE bios SET bio_frisur='" . $frisur . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioHairco'] != $row['bio_hairco']) {
            $frisurfarbe = $_POST['bioHairco'];
            
            $sql = "UPDATE bios SET bio_hairco='" . $frisurfarbe . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioNagelco'] != $row['bio_nagelco']) {
            $nagelfarbe = $_POST['bioNagelco'];
            
            $sql = "UPDATE bios SET bio_nagelco='" . $nagelfarbe . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioHautco'] != $row['bio_hautco']) {
            $hautfarbe = $_POST['bioHautco'];
            
            $sql = "UPDATE bios SET bio_hautco='" . $hautfarbe . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        
        if ($_POST['bioEyeco'] != $row['bio_eyeco']) {
            $augenfarbe = $_POST['bioEyeco'];
            
            $sql = "UPDATE bios SET bio_eyeco='" . $augenfarbe . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioPlace'] != $row['char_place']) {
            $place = $_POST['bioPlace'];
            
            $sql = "UPDATE bios SET char_place='" . $place . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['bioAge'] != $row['char_age']) {
            $age = $_POST['bioAge'];
            
            $sql = "UPDATE bios SET char_age='" . $age . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['biofield'] != $row['bio']) {
            $newBio = $_POST['biofield'];
            
            $sql = "UPDATE bios SET bio='" . $newBio . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        header("Location: popup_bio.php?char=" . $char);
        exit();
        break;
    
    case "savepersonal":
        $player = $_GET['user'];
        $char   = $_GET['char'];
        
        if ($_POST['personalBioava'] != $row['personal_avatar']) {
            $perso_bio = $_POST['personalBioava'];
            
            $sql = "UPDATE bios SET personal_avatar='" . $perso_bio . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['personalName'] != $row['personal_name']) {
            $perso_name = $_POST['personalName'];
            
            $sql = "UPDATE bios SET personal_name='" . $perso_name . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['personalSex'] != $row['personal_sex']) {
            $perso_sex = $_POST['personalSex'];
            
            $sql = "UPDATE bios SET personal_sex='" . $perso_sex . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['personalPlace'] != $row['personal_place']) {
            $perso_place = $_POST['personalPlace'];
            
            $sql = "UPDATE bios SET personal_place='" . $perso_place . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['personalBday'] != $row['personal_bday']) {
            $perso_bday = $_POST['personalBday'];
            
            $sql = "UPDATE bios SET personal_bday='" . $perso_bday . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['personalCouple'] != $row['personal_couple']) {
            $perso_couple = $_POST['personalCouple'];
            
            $sql = "UPDATE bios SET personal_couple='" . $perso_couple . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['personalHobby'] != $row['personal_hobby']) {
            $perso_hobby = $_POST['personalHobby'];
            
            $sql = "UPDATE bios SET personal_hobby='" . $perso_hobby . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        if ($_POST['personalAbout'] != $row['personal_about']) {
            $perso_about = $_POST['personalAbout'];
            
            $sql = "UPDATE bios SET personal_about='" . $perso_about . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        header("Location: popup_bio.php?op=perso&char=" . $char);
        exit();
        break;
    
    case "saveveri":
        $player = $_GET['user'];
        $char   = $_GET['char'];
        
        if ($_POST['veribiofield'] != $row['veribio']) {
            $veri_bio = $_POST['veribiofield'];
            
            $sql = "UPDATE bios SET veribio='" . $veri_bio . "' WHERE acctid='" . $player . "'";
            db_query($sql) or die(db_error(LINK));
        }
        
        header("Location: popup_bio.php?op=veribio&char=" . $char);
        exit();
        break;
        
        
    case 'multis':
        include './lib/include/multi_funcs.php';
        
        // haha... no
        if($session['user']['banned_from_multilist'])
            header('Location: popup_bio.php?char='.$session['user']['acctid']);
        
        $multis = getMultis($session['user']['acctid']);
        if($multis === false)
        {
            output('Bisher keine Multis eingetragen oder vorhanden. ');
            
            $multilists = getMultilists($session['user']['acctid']);
            if($multilists)
            {
                output('Das System hat erkannt, dass bereits '.count($multilists).' vorhandene Liste'.(count($multilists) > 1 ? 'n' : '').' mit Dir existieren. Willst Du eine dieser Listen übernehmen? `n`n');
                
                rawoutput('<script src="./lib/js/multis.js"></script>');
                foreach($multilists AS $multilist)
                {
                    output('<div class="multis_toggle" style="width: 750px;">
                                <span style="width: 100%; display: block; padding: 5px; font-size: 13px" class="trhead">
                                    `b#808080Multiliste von '.$multilist['owner'].'#808080`b
                                    <div style="float: right">#808080[ '.createLink('Diese Liste übernehmen', 'popup_bio.php?op=insert_multilist&list_id='.$multilist['multi_group'].'&char='.$session['user']['acctid'], false, 'Willst Du wirklich '.stripColors($multilist['owner']).'s Liste für diesen Char übernehmen?').' #808080] </div>
                                </span> `n
                                <span style="cursor: pointer;" class="multis_toggler">Dazugehörige Chars:</span> `n`n', true);
                    
                    $multilist_chars = getMultis($multilist['multi_group']);
                    if($multilist_chars)
                    {
                        output('<table cellpadding="10" style="display: none">
                                    <tr class="trhead">
                                        <td> `b#000000acctid`b </td>
                                        <td> `b#000000Name`b </td>
                                        <td> `b#000000Email`b </td>
                                    </tr>', true);
                        foreach($multilist_chars AS $multilist_char)
                        {
                            output('<tr>
                                        <td style="text-align: center"> '.$multilist_char['acctid'].' </td>
                                        <td> '.$multilist_char['name'].' </td>
                                        <td> '.$multilist_char['emailaddress'].' </td>
                                    </tr>', true);
                        }
                        output('</table>', true);
                    }
                    output('</div> `n', true);
                }
                
                output('`n`n');
            }
            else 
                output('Entweder Du hast keine Multis oder hast diese noch nicht eingetragen. `n
                        Falls Du welche besitzt, kannst Du diese aus der Liste unten auswählen oder einen Multi manuell suchen. `n`n`n');
        }
        else
        {
            $n = count($multis);
            
            output('`bDu hast bisher '.$n.' Multi'.($n > 1 ? 's' : '').' eingetragen!`b `n
                    `n
                    <table cellpadding="10">
                        <tr class="trhead">
                            <td> `b#000000acctid`b </td>
                            <td> `b#000000Name`b </td>
                            <td> `b#000000Email`b </td>
                            <td> `b#000000ungelese Yoms?`b </td>
                        </tr>', true);
            foreach($multis AS $multi)
            {
                output('<tr>
                            <td style="text-align: center"> '.$multi['acctid'].' </td>
                            <td> '.$multi['name'].' </td>
                            <td> '.$multi['emailaddress'].' </td>
                            <td style="text-align: center"> '.($multi['new_yoms'] > 0 ? '#00FF00' : '').$multi['new_yoms'].' </td>
                        </tr>', true);
            }
            output('</table> `n`n`n', true);
        }
                
        
        $new_multis = getNewMultis(true);
        $n             = count($new_multis);
        if($n)
        {
            output('`n`n`bDas System hat `i'.$n.'`i neue'.($n > 1 ? '' : 'n').' Multi'.($n > 1 ? 's' : '').' von Dir gefunden. Du kannst diese nun mit eingabe des Passwortes verifizieren und zu Deiner Liste hinzufügen.`b`n`n
                    <table cellpadding="10">
                        <tr class="trhead">
                            <td> `b#000000acctid`b </td>
                            <td> `b#000000Name`b </td>
                            <td> `b#000000Email`b </td>
                            <td> `b#000000Letzte IP`b </td>
                            <td> `b#000000Unique ID`b </td>
                            <td style="text-align: center"> `b#000000Ops`b </td>
                        </tr>', true);
            
            foreach($new_multis AS $new_multi)
            {
                output('<tr>
                            <td style="text-align: center"> '.$new_multi['acctid'].' </td>
                            <td> '.$new_multi['name'].' </td>
                            <td> '.($new_multi['emailaddress'] == $session['user']['emailaddress'] ? '#00FF00' : '').$new_multi['emailaddress'].' </td>
                            <td> '.($new_multi['lastip'] == $session['user']['lastip'] ? '#00FF00' : '').$new_multi['lastip'].' </td>
                            <td> '.($new_multi['uniqueid'] == $session['user']['uniqueid'] ? '#00FF00' : '').$new_multi['uniqueid'].' </td>
                            <td> '.createLink('hinzufügen', 'popup_bio.php?op=confirm_multi&user_id='.$new_multi['acctid'].'&char='.$session['user']['acctid']).' </td>
                        </tr>', true);
            }
            
            output('</table> `n`n', true);
        }
        else 
            output('Das System hat keine neuen Multis von dir gefunden. `n`n');
        
        output('Hier kannst Du manuell nach einem Multi suchen. Jedoch weisen wir darauf hin, dass nach den Regeln alle Multis dieselbe Emailadresse benutzen sollten! `n
                Sollte der gewünschte Multi nicht in der List weiter oben angezeigt werden, liegt das wahrscheinlich daran, dass Du ihn schon länger nicht mehr eingeloggt hast, den Browser gewechselt hast und eine andere Emailadresse für ihn verwendet hast. `n
                <form action="popup_bio.php?op=multis&act=search&char='.$session['user']['acctid'].'" method="POST">
                    <input type="text" name="name"> <input type="submit" class="button" value="Suchen">
                </form> `n`n', true);
        
        if($_GET['act'] == 'search')
        {
            $_POST['name']     = stripslashes($_POST['name']);
            $StrLen             = StrLen($_POST['name']);

            $who = '';
            for ($i = 0; $i < $StrLen; $i++) {
                $who .= '%' . $_POST['name']{$i};
            }

            $who .= '%';
            $who = mysql_real_escape_string($who);
            
            $sql = 'SELECT acctid, name, laston FROM accounts WHERE name LIKE "'.$who.'"';
            $result = db_query($sql);
            $n = db_num_rows($result);
            
            if($n == 0)
                output('Keine Ergebnisse für Deine Suche gefunden.');
            
            elseif($n > 50)
                output('Mehr als 50 Spieler mit Deiner Eingabe gefunden. Präzisiere Deine Eingabe!');
            
            else
            {
                output('<table cellpadding="10">
                            <tr class="trhead">
                                <td> `b#000000acctid`b </td>
                                <td> `b#000000Name`b </td>
                                <td style="text-align: center"> `b#000000Ops`b </td>
                            </tr>', true);
                
                for($i = 0; $i < $n; $i++)
                {
                    $row = db_fetch_assoc($result);
                    
                    output('<tr>
                                <td> '.$row['acctid'].' </td>
                                <td> '.$row['name'].' </td>
                                <td> '.createLink('hinzufügen', 'popup_bio.php?op=confirm_multi&user_id='.$row['acctid'].'&char='.$session['user']['acctid']).' </td>
                            </tr>', true);
                }
                
                output('</table>', true);
                            
            }
        }
        break;
    
    case 'insert_multilist':
        include './lib/include/multi_funcs.php';
        
        // Keine Listen-ID übermittelt? Da brechen wir ab...
        if(!isset($_GET['list_id']))
            header('Location: popup_bio.php?op=multis&char='.$session['user']['acctid']);

        $multilist = getMultis((INT)$_GET['list_id']);
        $prepare_sql     = 'INSERT INTO multis (multi_group, acctid, email) VALUES ';
        $found_me        = false;
        $i                 = 0;
        foreach($multilist AS $char)
        {
            if($char['acctid'] == $session['user']['acctid'])
            {
                $found_me = true;
                continue;
            }
            
            $prepare_sql .= '('.$session['user']['acctid'].', '.$char['acctid'].', "'.$char['emailaddress'].'"), ';
            $i++;
        }
        
        if($found_me)
        {
            $prepare_sql .= '('.$session['user']['acctid'].', '.(INT)$_GET['list_id'].', "'.$session['user']['emailaddress'].'")';
            output('Es wurden '.++$i.' Chars als Deine Multis hinzugefügt.`n');
            
            db_query($prepare_sql);
        }
        else 
        {
            output('Du wurdest in der ausgewählten Liste gar nicht gefunden... Hmmm... komisch... wie kommst Du denn dann überhaupt hier her?');
            $prepare_sql = '';
        }
        break;
    
    case 'confirm_multi':
        if(!isset($_GET['user_id']))
            header('Location: popup_bio.php?op=multis&char='.$session['user']['acctid']);
        
        // haha... no
        if($session['user']['banned_from_multilist'])
            header('Location: popup_bio.php?char='.$session['user']['acctid']);
        
        if(!isset($session['remaining_pw_tries']))
            $session['remaining_pw_tries'] = 5;
        
        $sql     = 'SELECT acctid, name FROM accounts WHERE acctid = '.intval($_GET['user_id']);
        $result = db_query($sql);
        if(db_num_rows($result))
        {
            $row = db_fetch_assoc($result);
            output('#808080Du willst '.$row['name'].'#808080 als einen Deiner Multis bestätigen. Gib hierfür bitte das zu '.$row['name'].' #808080gehörige Passwort ein! `n`n
                    <center>
                    <form action="popup_bio.php?op=add_multi&user_id='.$_GET['user_id'].'&char='.$session['user']['acctid'].'" method="POST">
                        <input type="password" name="pw" autofocus> <input type="submit" class="button" value="Hinzufügen">
                    </form></center>', true);
        }
        else
            output('Es wurde kein Account mit der ID '.$_GET['user_id'].' gefunden...`n
                    Komisch... wie kommst Du hier denn überhaupt hin?');
        break;
    
    case 'add_multi':
        if(!isset($_GET['user_id']))
            header('Location: popup_bio.php?op=multis&char='.$session['user']['acctid']);
        
        // haha... no
        if($session['user']['banned_from_multilist'])
            header('Location: popup_bio.php?char='.$session['user']['acctid']);
        
        $sql     = 'SELECT name, acctid, emailaddress FROM accounts WHERE acctid = '.intval($_GET['user_id']).' AND password = MD5("'.$_POST['pw'].'")';
        $result = db_query($sql);
            
        if(db_num_rows($result) == 1)
        {
            $row = db_fetch_assoc($result);
            
            db_query('INSERT INTO multis (acctid, multi_group, email) VALUES ('.$row['acctid'].', '.$session['user']['acctid'].', "'.$row['emailaddress'].'")');
            unset($session['remaining_pw_tries']);
            header('Location: popup_bio.php?op=multis&char='.$session['user']['acctid']);
        }
        else
        {
            $session['remaining_pw_tries']--;
            
            if($session['remaining_pw_tries'] < 1)
            {
                $session['user']['banned_from_multilist'] = 1;
                
                output('#FF0000Du hast das Passwort fünf Mal falsch eingegeben! Dein Account wurde vorübergehend von der Multiliste gesperrt. Das Adminteam wurde benachrichtigt.');
                
                $user_sql = 'SELECT name FROM accounts WHERE acctid = '.intval($_GET['user_id']);
                $user_res = db_query($user_sql);
                $user_row = db_fetch_assoc($user_result);
                global_Log($session['user']['name'].'#808080 ('.$session['user']['acctid'].') hat fünf Mal das falsche Passwort eingegeben, um den Char mit der `bID '.$_GET['user_id'].'`b ('.$user_row['name'].'#808080) zu seinen Multis hinzuzufügen.');
                
                $sql     = 'SELECT acctid FROM accounts WHERE superuser > 0';
                $result = db_query($sql);
                while($admin = db_fetch_assoc($result))
                {
                    $message = $session['user']['name'].'#808080 ('.$session['user']['acctid'].') hat fünf Mal das falsche Passwort eingegeben, um den Char mit der `bID '.$_GET['user_id'].'`b('.$user_row['name'].'#808080)  zu seinen Multis hinzuzufügen.';
                    systemmail($admin['acctid'], '#FF0000Spieler von Multiliste gesperrt!', $message);
                }
                
                unset($session['remaining_pw_tries']);
            }
            else {
                output('#808080Das von dir angegebene Passwort war falsch. Versuche es erneut, Du hast noch `b'.$session['remaining_pw_tries'].'`b Versuche. `n
                        `c '.createLink('Erneut versuchen', 'popup_bio.php?op=confirm_multi&user_id='.$_GET['user_id'].'&char='.$session['user']['acctid']).' `c', true);
                        
                                    }
        
        }
        
        break;
        
        
}

output("</td></tr>", true);
output("</table>", true);
output("</div>", true);

popup_footer();
?>


