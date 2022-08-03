<?php

// 12092004
require_once "dbwrapper.php";
require_once "anticheat.php";

$pagestarttime = getmicrotime();

$nestedtags = array();
$output = "";
function useronline() {// Wer ist hier online? V.1.0 by Devilzimti
    global $session, $SCRIPT_NAME;

    $useronline = "<img src='images/uscroll.GIF' width='100%' height='11' alt=''><br>
<table border='0' cellpadding='0' cellspacing='0' class='vitalinfo'>
<tr><td><tr><td><b>`6&nbsp;Hier anwesend:`0</b></td></tr>";
    $sql = "SELECT name,login FROM accounts
WHERE (accounts.restorepage LIKE '" . $SCRIPT_NAME . "%" . "' OR accounts.acctid=" . $session[user][acctid] . ")
AND loggedin = 1
AND locked = 0
AND laston>'" . date("Y-m-d H:i:s", strtotime("-" . getsetting("LOGINTIMEOUT", 900) . " seconds")) . "'
ORDER BY dragonkills,level;
";

    $query = db_query($sql);
    // output ( "<a href=\"biopopup.php?char=" . rawurlencode ( $row ['login'] ) . "\" target=\"_blank\" onClick=\"" . popup ( "biopopup.php?char=" . rawurlencode ( $row ['login'] ) . "" ) . ";return false;\">", true );

    while ($row = db_fetch_assoc($query)) {
        $useronline .= "<tr><td>&nbsp;<a href='mail.php?op=write&to=" . rawurlencode($row['login']) . "' target='_blank' onClick=\"" . popup("mail.php?op=write&to=" . rawurlencode($row['login'])) . ";return false;\"><img src='images/newscroll.GIF' width='16' height='16' alt='Mail schreiben' border='0'></a>&nbsp;";
        // $useronline.= "`0$row[name]</td></tr>";

        // Hier soll das Biopopup Angezeigt werden
        if ($session[user][prefs][oldBio] == 1) {
            $useronline .= "<a href=\"biopopup_backup.php?char=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popupbio("biopopup_backup.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">`0$row[name]</a></td></tr>";
            
        }
        else {
            $useronline .= "<a href=\"biopopup.php?char=" . rawurlencode($row['login']) . "\" target=\"_blank\" onClick=\"" . popupbio("biopopup.php?char=" . rawurlencode($row['login']) . "") . ";return false;\">`0$row[name]</a></td></tr>";
        }
    }

    $useronline .= "</td></tr></table>
<img src='images/lscroll.GIF' width='100%' height='11'>";

    $useronline = appoencode($useronline, true);
    return $useronline;
}

function pvpwarning($dokill = false) {
    global $session;
    $days = getsetting("pvpimmunity", 5);
    $exp = getsetting("pvpminexp", 1500);
    if ($session['user']['age'] <= $days && $session['user']['dragonkills'] == 0 && $session['user']['user']['pk'] == 0 && $session['user']['experience'] <= $exp) {
        if ($dokill) {
            output("`\$Warnung!`^ Da du selbst noch vor PvP geschützt warst, aber jetzt einen anderen Spieler angreifst, hast du deine Immunität verloren!!`n`n");
            $session['user']['pk'] = 1;
        }
        else {
            output("`\$Warnung!`^ Innerhalb der ersten $days  Tage in dieser Welt, oder bis sie $exp Erfahrungspunkte gesammelt haben, sind alle Spieler vor PvP-Angriffen geschützt. Wenn du einen anderen Spieler angreifst, verfällt diese Immunität für dich!`n`n");
        }
    }
}

function rawoutput($indata) {
    global $output;
    $output .= $indata . "\n";
}

function striptag($data, $search = false) {
    // 2005 by Eliwood
    if ($search === false)
        $search = array("`1", "`2", "`3", "`4", "`5", "`6", "`7", "`8", "`9", "`!", "`@", "`#", "`$", "`%", "`&", "`Q", "`q", "`R", "`r", "`*", "`~", "`?", "`V", "`v", "`G", "`g", "`T", "`t");
    $data = str_replace($search, "", $data);
    return $data;
}

function output($indata, $priv = false) {
    global $nestedtags, $output;
    $data = translate($indata);
    // Aprilscherz deaktiviert ;)
    // if (date("m-d")=="04-01"){
    // $out = appoencode($data,$priv);
    // if ($priv==false) $out = borkalize($out);
    // $output.=$out;
    // }else{
    $output .= appoencode($data, $priv);
    // }
    $output .= "\n";
    return 0;
}

function compress_out($input) {
    // Based on old YaBBSE code (c)
    // Open-Source Project by Zef Hemel (zef@zefnet.com <mailto:zef@zefnet.com>)
    // Copyright (c) 2001-2002 The YaBB Development Team
    if ((function_exists("gzcompress")) && (function_exists("crc32"))) {
        if (strpos(" " . $_SERVER['HTTP_ACCEPT_ENCODING'], "x-gzip")) {
            $encode = "x-gzip";
        }
        elseif (strpos(" " . $_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
            $encode = "gzip";
        }
        if (isset($encode)) {
            header("Content-Encoding: $encode");
            $encode_size = strlen($input);
            $encode_crc = crc32($input);
            $out = "\x1f\x8b\x08\x00\x00\x00\x00\x00";
            $out .= substr(gzcompress($input, 1), 0, -4);
            $out .= pack("V", $encode_crc);
            $out .= pack("V", $encode_size);
        }
        else {
            $out = $input;
        }
    }
    else {
        $out = $input;
    }
    return ($out);
}

function safeescape($input) {
    return preg_replace('/([^\\\\])(["\'])/s', "\\1\\\\\\2", $input);
}

// by Chaosmaker
function petitionmail($subject, $body, $petition, $from, $seen = 0, $to = 0, $messageid = 0) {
    $subject = safeescape($subject);
    $subject = str_replace("\n", "", $subject);
    $subject = str_replace("`n", "", $subject);
    $body = safeescape($body);

    $sql = "INSERT INTO petitionmail (petitionid,messageid,msgfrom,msgto,subject,body,sent,seen) VALUES ('" . ( int )$petition . "','" . ( int )$messageid . "','" . ( int )$from . "','" . ( int )$to . "','$subject','$body',now(),'$seen')";
    db_query($sql);
    $sql = 'UPDATE petitions SET lastact=NOW() WHERE petitionid="' . ( int )$petition . '"';
    db_query($sql);
}

// end petitionmail
function systemmail($to, $subject, $body, $from = 0, $noemail = false) {
    $subject = safeescape($subject);
    $subject = str_replace("\n", "", $subject);
    $subject = str_replace("`n", "", $subject);
    $body = safeescape($body);
    // echo $subject."<br>".$body;
    $sql = "SELECT prefs,emailaddress FROM accounts WHERE acctid='$to'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    db_free_result($result);
    $prefs = unserialize($row['prefs']);

    if ($prefs['dirtyemail']) {
        // output("Not cleaning: $prefs[dirtyemail]");
    }
    else {
        // output("Cleaning: $prefs[dirtyemail]");
        $subject = soap($subject);
        $body = soap($body);
    }

    $sql = "INSERT INTO mail (msgfrom,msgto,subject,body,sent) VALUES ('" . ( int )$from . "','" . ( int )$to . "','$subject','$body',now())";
    db_query($sql);
    $email = false;
    if ($prefs['emailonmail'] && $from > 0) {
        $email = true;
    }
    elseif ($prefs[emailonmail] && $from == 0 && $prefs[systemmail]) {
        $email = true;
    }
    if (!is_email($row['emailaddress']))
        $email = false;
    if ($email && !$noemail) {
        $sql = "SELECT name FROM accounts WHERE acctid='$from'";
        $result = db_query($sql);
        $row1 = db_fetch_assoc($result);
        db_free_result($result);
        if ($row1['name'] != "")
            $fromline = "From: " . preg_replace("'[`].'", "", $row1[name]) . "\n";
        // We've inserted it into the database, so.. strip out any formatting
        // codes from the actual email we send out... they make things
        // unreadable
        $body = preg_replace("'[`]n'", "\n", $body);
        $body = preg_replace("'[`].'", "", $body);
        mail($row['emailaddress'], "Neue LoGD Mail", "Du hast eine neue Nachricht von LoGD @ http://" . $_SERVER[HTTP_HOST] . dirname($_SERVER[SCRIPT_NAME]) . " empfangen.\n\n$fromline" . "Betreff: " . preg_replace("'[`].'", "", stripslashes($subject)) . "\n" . "Body: " . stripslashes($body) . "\n" . "\nDu kannst diese Meldungen in deinen Einstellungen abschalten.", "From: " . getsetting("gameadminemail", "postmaster@localhost"));
    }
}

function isnewday($level) {
    global $session;
    if ($session['user']['superuser'] < $level) {
        clearnav();
        $session['output'] = "";
        page_header("FREVEL!");
        $session['bufflist']['angrygods'] = array("name" => "`^Die Götter sind wütend!", "rounds" => 10, "wearoff" => "`^Es ist den Göttern langweilig geworden, dich zu quälen.", "minioncount" => $session['user']['level'], "maxgoodguydamage" => 2, "effectmsg" => "`7Die Götter verfluchen dich und machen dir `^{damage}`7 Schaden!", "effectnodmgmsg" => "`7Die Götter haben beschlossen, dich erstmal nicht zu quälen.", "activate" => "roundstart", "survivenewday" => 1, "newdaymessage" => "`6Die Götter sind dir immer noch böse!");
        output("Für den Versuch, die Götter zu betrügen, wurdest du niedergeschmettert!`n`n");
        output("`\$Ramius, der Gott der Toten`( erscheint dir in einer Vision. Dafür, dass du versucht hast, deinen Geist mit seinem zu messen, sagt er dir wortlos, dass du keinen Gefallen mehr bei ihm hast.`n`n");
        addnews("`&Für den Versuch, die Götter zu besudeln, wurde " . $session['user']['name'] . " zu Tode gequält! (Hackversuch gescheitert).");
        $session['user']['hitpoints'] = 0;
        $session['user']['alive'] = 0;
        $session['user']['soulpoints'] = 0;
        $session['user']['gravefights'] = 0;
        $session['user']['deathpower'] = 0;
        $session['user']['experience'] *= 0.75;
        addnav("Tägliche News", "news.php");
        page_footer();
        $sql = "SELECT acctid FROM accounts WHERE superuser>=3";
        $result = db_query($sql);
        while ($row = db_fetch_assoc($result)) {
            systemmail($row['acctid'], "`#{$session['user']['name']}`# hat versucht, Superuser-Seiten zu hacken!", "Böse(r), böse(r), böse(r) {$session['user']['name']}, du bist ein Hacker!");
        }
        exit();
    }
}

function forest($noshowmessage = false) {
    global $session, $playermount;
    $conf = unserialize($session['user']['donationconfig']);

    addnav("Kampf");
    if ($session['user']['admin'] >= 2) {
        addnav("B?Etwas zum Bekämpfen suchen", "forest.php?op=search");
        if ($session['user']['level'] > 1)
            addnav("H?Herumziehen", "forest.php?op=search&type=slum");
        addnav("N?Nervenkitzel suchen", "forest.php?op=search&type=thrill");
    }
    if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']) {
        $config = unserialize($session['user']['donationconfig']);
        if ($config['healer'] || $session[user][acctid] == getsetting("hasegg", 0))
            $golinda = 1;
        $loglev = log($session['user']['level']);
        $cost = ($loglev * ($session['user']['maxhitpoints'] - $session['user']['hitpoints'])) + ($loglev * 10);
        if ($golinda)
            $cost *= .5;
        $nc = 100;
        $newcost = round($nc * $cost / 100, 0);
        if ($session['user']['gold'] >= $newcost) {
            addnav("`\$Komplette Heilung`n `^$newcost `\$Gold", "healer.php?op=buy&pct=100");
        }
    }

    // if ($session[user][hashorse]>=2) addnav("D?Dark Horse Tavern","forest.php?op=darkhorse");
    addnav("Wege");
    if ($playermount['tavern'] >= 0)
        addnav("D?Nimm {$playermount['mountname']}`0 zur Dark Horse Taverne", "forest.php?op=darkhorse");
    if ($playermount['tavern'] > 0 && $conf['castle'])
        addnav("B?Nimm {$playermount['mountname']} zur Burg", "forest.php?op=castle");
    if ($conf['goldmine'] > 0)
        addnav("Goldmine (" . $conf[goldmine] . "x)", "paths.php?ziel=goldmine&pass=conf");
    addnav("Pilzsuche", "pilzsuche.php");
    if ($conf['healer'] || $session['user']['acctid'] == getsetting("hasegg", 0)) {
        addnav("H?Golindas Hütte", "healer.php");
    }
    else {
        addnav("H?Hütte des Heilers", "healer.php");
    }
    addnav("Trainingslager", "trainwald.php");
    addnav("Z?Zurück zur Kreuzung", "kreuzung.php");
    addnav("Zurück nach Symia", "sanela-reise2.php");
    addnav("Waldlichtung", "waldlichtung.php");
    addnav("", "forest.php");
    if ($session['user']['level'] >= 15 && $session['user']['seendragon'] == 0) {
        addnav("G?`RDen `wdun`Rkle `wPhoe`Rnix `@suchen", "forest.php?op=dragon");
    }
    addnav("Sonstiges");
    addnav("P?Plumpsklo", "outhouse.php");
    addnav("Postkutsche", "postkutsche.php");

    if ($session['user']['turns'] <= 1)
        addnav("Hexenhaus", "hexe.php");
    if ($noshowmessage != true) {
        output("`c<img src='images/wald.jpg' alt='' >`c`n", true);
        // Narjana
        output("`c`7`bDer Wald`b`0`c");
        output("Der Wald, Heimat von bösartigen Kreaturen und üblen Übeltätern aller Art.`n`n");
        output("Die dichten Blätter des Waldes erlauben an den meisten Stellen nur wenige Meter Sicht.  ");
        output("Die Wege würden dir verborgen bleiben, hättest du nicht ein so gut geschultes Auge. Du bewegst dich so leise wie ");
        output("eine milde Brise über den dicken Humus, der den Boden bedeckt. Dabei versuchst du es zu vermeiden ");
        output("auf dünne Zweige oder irgendwelche der ausgebleichten Knochenstücke zu treten, welche den Waldboden spicken. ");
        output("Du verbirgst deine Gegenwart vor den abscheulichen Monstern, die den Wald durchwandern.");
        if ($session['user']['turns'] <= 1)
            output(" In der Nähe siehst du wieder den Rauch aus dem Kamin eines windschiefen Hexenhäuschens aufsteigen, von dem du schwören könntest, es war eben noch nicht da. ");
    }
    if ($session['user']['superuser'] > 1) {
        output("`n`nSUPERUSER Specials:`n");
        $d = dir("special");
        while (false !== ($entry = $d -> read())) {
            // Skip non php files (including directories)
            if (strpos($entry, ".php") === false)
                continue;
            // Skip any hidden files
            if (substr($entry, 0, 1) == ".")
                continue;
            output("<a href='forest.php?specialinc=$entry'>$entry</a>`n", true);
            addnav("", "forest.php?specialinc=$entry");
        }
    }
}

function borkalize($in) {
    $out = $in;
    $out = str_replace(". ", ". Bork bork. ", $out);
    $out = str_replace(", ", ", bork, ", $out);
    $out = str_replace(" h", " hoor", $out);
    $out = str_replace(" v", " veer", $out);
    $out = str_replace("g ", "gen ", $out);
    $out = str_replace(" p", " pere", $out);
    $out = str_replace(" qu", " quee", $out);
    $out = str_replace("n ", "nen ", $out);
    $out = str_replace("e ", "eer ", $out);
    $out = str_replace("s ", "ses ", $out);
    return $out;
}

function getmicrotime() {
    list($usec, $sec) = explode(" ", microtime());
    return (( float )$usec + ( float )$sec);
}

function make_seed() {
    list($usec, $sec) = explode(' ', microtime());
    return ( float )$sec + (( float )$usec * 100000);
}

mt_srand(make_seed());
function e_rand($min = false, $max = false) {
    if ($min === false)
        return mt_rand();
    $min *= 1000;
    if ($max === false)
        return round(mt_rand($min) / 1000, 0);
    $max *= 1000;
    if ($min == $max)
        return round($min / 1000, 0);
    // if ($min==0 && $max==0) return 0; //do NOT as me why this line can be executed, it makes no sense, but it *does* get executed.
    if ($min < $max) {
        return round(@mt_rand($min, $max) / 1000, 0);
    }
    else if ($min > $max) {
        return round(@mt_rand($max, $min) / 1000, 0);
    }
}

function is_email($email) {
    return preg_match("/[[:alnum:]_.-]+[@][[:alnum:]_.-]{2,}.[[:alnum:]_.-]{2,}/", $email);
}

function checkban($login = false) {
    global $session;
    if ($session['banoverride'])
        return false;
    if ($login === false) {
        $ip = $_SERVER[REMOTE_ADDR];
        $id = $_COOKIE[lgi];
        // echo "<br>Orig output: $ip, $id<br>";
    }
    else {
        $sql = "SELECT lastip,uniqueid,banoverride FROM accounts WHERE login='$login'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($row['banoverride']) {
            $session['banoverride'] = true;
            // echo "`nYou are absolved of your bans, son.";
            return false;
        }
        else {
            // echo "`nNo absolution here, son.";
        }
        db_free_result($result);
        $ip = $row['lastip'];
        $id = $row['uniqueid'];
        // echo "<br>Secondary output: $ip, $id<br>";
    }
    $sql = "select * from bans where ((substring('$ip',1,length(ipfilter))=ipfilter AND ipfilter<>'') OR (uniqueid='$id' AND uniqueid<>'')) AND (banexpire='0000-00-00' OR banexpire>'" . date("Y-m-d") . "')";
    // echo $sql;
    $result = db_query($sql) or die(db_error(LINK));
    if (db_num_rows($result) > 0) {
        // $msg.=$session['message'];
        $session = array();
        // $session['message'] = $msg;
        // echo "Session Abandonment";
        $session[message] .= "`n`4Du bist einer Verbannung zum Opfer gefallen:`n";
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $session[message] .= $row[banreason];
            if ($row[banexpire] == "0000-00-00")

                $session[message] .= "  `\$Die Verbannung ist permanent!`0";
            if ($row[banexpire] != "0000-00-00")
                $session[message] .= "  `^Der Bann wird am " . date("M d, Y", strtotime($row[banexpire])) . " aufgehoben `0";
            $session[message] .= "`n";
        }
        $session[message] .= "`4Wenn du willst, kannst du mit einer Anfrage nach dem Grund fragen.";
        header("Location: index.php");
        exit();
    }
    db_free_result($result);
}

function increment_specialty() {
    global $session;
    if ($session[user][specialty] > 0) {
        $skillnames = array(1 => "Dunkle Künste", "Mystische Kräfte", "Diebeskunst");
        $skills = array(1 => "darkarts", "magic", "thievery");
        $skillpoints = array(1 => "darkartuses", "magicuses", "thieveryuses");
        $session[user][$skills[$session[user][specialty]]]++;
        output("`nDu steigst in `&" . $skillnames[$session[user][specialty]] . "`# ein Level auf " . $session[user][$skills[$session[user][specialty]]] . " auf. ");
        $x = ($session[user][$skills[$session[user][specialty]]]) % 3;
        if ($x == 0) {
            output("Du bekommst eine zusätzliche Anwendung!`n");
            $session[user][$skillpoints[$session[user][specialty]]]++;
        }
        else {
            output("Nur noch " . (3 - $x) . " weitere Stufen, bis du eine zusätzliche Anwendung erhältst!`n");
        }
    }
    else {
        output("`7Du wanderst ziel- und planlos durchs Leben. Du solltest eine Rast machen und einige wichtige Entscheidungen für dein weiteres Leben treffen.`n");
    }
}

function fightnav($allowspecial = true, $allowflee = true) {
    global $PHP_SELF, $session;
    // $script = str_replace("/","",$PHP_SELF);
    $script = substr($PHP_SELF, strrpos($PHP_SELF, "/") + 1);
    addnav("Kämpfen", "$script?op=fight");
    if ($allowflee) {
        addnav("Wegrennen", "$script?op=run");
    }
    if (getsetting("autofight", 0)) {
        addnav("AutoFight");
        addnav("5 Runden kämpfen", "$script?op=fight&auto=five");
        addnav("Bis zum bitteren Ende", "$script?op=fight&auto=full");
    }
    if ($allowspecial) {
        addnav("`bBesondere Fähigkeiten`b");
        if ($session[user][darkartuses] > 0) {
            addnav("`\$Dunkle Künste`0", "");
            addnav("`\$&#149; Skelette herbeirufen`7 (1/" . $session[user][darkartuses] . ")`0", "$script?op=fight&skill=DA&l=1", true);
        }
        if ($session[user][darkartuses] > 1)
            addnav("`\$&#149; Voodoo`7 (2/" . $session[user][darkartuses] . ")`0", "$script?op=fight&skill=DA&l=2", true);
        if ($session[user][darkartuses] > 2)
            addnav("`\$&#149; Geist verfluchen`7 (3/" . $session[user][darkartuses] . ")`0", "$script?op=fight&skill=DA&l=3", true);
        if ($session[user][darkartuses] > 4)
            addnav("`\$&#149; Seele verdorren`7 (5/" . $session[user][darkartuses] . ")`0", "$script?op=fight&skill=DA&l=5", true);

        if ($session[user][thieveryuses] > 0) {
            addnav("`^Diebeskünste`0", "");
            addnav("`^&#149; Beleidigen`7 (1/" . $session[user][thieveryuses] . ")`0", "$script?op=fight&skill=TS&l=1", true);
        }
        if ($session[user][thieveryuses] > 1)
            addnav("`^&#149; Waffe vergiften`7 (2/" . $session[user][thieveryuses] . ")`0", "$script?op=fight&skill=TS&l=2", true);
        if ($session[user][thieveryuses] > 2)
            addnav("`^&#149; Versteckter Angriff`7 (3/" . $session[user][thieveryuses] . ")`0", "$script?op=fight&skill=TS&l=3", true);
        if ($session[user][thieveryuses] > 4)
            addnav("`^&#149; Angriff von hinten`7 (5/" . $session[user][thieveryuses] . ")`0", "$script?op=fight&skill=TS&l=5", true);

        if ($session[user][magicuses] > 0) {
            addnav("`%Mystische Kräfte`0", "");
            // disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe
            addnav("g?`%&#149; Regeneration`7 (1/" . $session[user][magicuses] . ")`0", "$script?op=fight&skill=MP&l=1", true);
        }
        if ($session[user][magicuses] > 1)
            addnav("`%&#149; Erdenfaust`7 (2/" . $session[user][magicuses] . ")`0", "$script?op=fight&skill=MP&l=2", true);
        if ($session[user][magicuses] > 2)
            addnav("L?`%&#149; Leben absaugen`7 (3/" . $session[user][magicuses] . ")`0", "$script?op=fight&skill=MP&l=3", true);
        if ($session[user][magicuses] > 4)
            addnav("A?`%&#149; Blitz Aura`7 (5/" . $session[user][magicuses] . ")`0", "$script?op=fight&skill=MP&l=5", true);
        if ($session[user][superuser] >= 3) {
            addnav("`&Superuser`0", "");
            addnav("!?`&&#149; __GOD MODE", "$script?op=fight&skill=godmode", true);
        }
        // spells by anpera
        $sql = "SELECT * FROM items WHERE class='Zauber' AND owner=" . $session[user][acctid] . " AND value1>0 ORDER BY name ASC";
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) > 0)
            addnav("Zauber");
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $spellbuff = unserialize($row[buff]);
            addnav("`v$spellbuff[name] `0(" . $row[value1] . "x)", "$script?op=fight&skill=zauber&itemid=$row[id]");
        }
        // end spells
    }
}

function appoencode($data, $priv = false) {
    global $nestedtags, $session;
    while (!(($x = strpos($data, "`")) === false)) {
        $tag = substr($data, $x + 1, 1);
        $append = substr($data, 0, $x);
        // echo "<font color='green'>$tag</font><font color='red'>".((int)$x)."</font><font color='blue'>$data</font><br>";
        $output .= ($priv ? $append : HTMLSpecialChars($append));
        $data = substr($data, $x + 2);
        switch ($tag) {
            case "0" :
                if ($nestedtags[font])
                    $output .= "</span>";
                unset($nestedtags[font]);
                break;
            case "1" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkBlue'>";
                break;
            case "2" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkGreen'>";
                break;
            case "3" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkCyan'>";
                break;
            case "4" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkRed'>";
                break;
            case "5" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkMagenta'>";
                break;
            case "6" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkYellow'>";
                break;
            case "7" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkWhite'>";
                break;
            case "8" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLime'>";
                break;
            case "9" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colBlue'>";
                break;
            case "!" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtBlue'>";
                break;
            case "@" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtGreen'>";
                break;
            case "#" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtCyan'>";
                break;
            case "$" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtRed'>";
                break;
            case "%" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtMagenta'>";
                break;
            case "^" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtYellow'>";
                break;
            case "&" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtWhite'>";
                break;
            case "(" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='braun4'>";
                break;
            case "~" :
                // if (($session[user][dragonkills]>=5)||($session[user][superuser]>=2)){
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colBlack'>";
                // }
                break;
            case "Q" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkOrange'>";
                break;
            case "q" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colOrange'>";
                break;
            case "r" :
                // case "R":
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colRose'>";
                break;
            case "V" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colBlueViolet'>";
                break;
            case "v" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='coliceviolet'>";
                break;
            case "g" :
                // case "G":
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colXLtGreen'>";
                break;
            case "T" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colDkBrown'>";
                break;
            case "t" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colLtBrown'>";
                break;
            case "?" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colWhiteBlack'>";
                break;
            case "*" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colBack'>";
                break;
            // neue Farben
            // 1. Rot
            case "w" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='red1'>";
                break;
            case "W" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='red2'>";
                break;
            case "e" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='red3'>";
                break;
            case "E" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='red4'>";
                break;
            case "z" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='red5'>";
                // 2. Gelb
                break;
            case "Z" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='yellow1'>";
                // 3. Grün
                break;
            case "u" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green1'>";
                break;
            case "U" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green2'>";
                break;
            case "ï" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green3'>";
                break;
            case "I" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green4'>";
                break;
            case "o" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green5'>";
                break;
            case "O" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green6'>";
                break;
            case "p" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green7'>";
                break;
            case "P" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green8'>";
                break;
            case "s" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green9'>";
                break;
            case "S" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green10'>";
                break;
            case "d" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green11'>";
                break;
            case "D" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='green12'>";
                // 4. türkiese
                break;
            case "f" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='turkiese1'>";
                break;
            case "F" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='turkiese2'>";
                break;
            case "j" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='turkiese3'>";
                // 5. Blau
                break;
            case "y" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue1'>";
                break;
            case "Y" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue2'>";
                break;
            case "x" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue3'>";
                break;
            case "X" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue4'>";
                break;
            case "m" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue5'>";
                break;
            case "J" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue6'>";
                break;
            case "k" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue7'>";
                break;
            case "K" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue8'>";
                break;
            case "l" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue9'>";
                break;
            case "L" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='blue10'>";
                // 6. violett
                break;
            case "M" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett1'>";
                break;
            case "-" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett2'>";
                break;
            case "à" :
                if ($nestedtags[font])
                    $output .= "</span>";
                
else

                    $nestedtags[font] = true;
                $output .= "<span class='violett3'>";
                break;
            case "â" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett4'>";
                break;
            case "é" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett5'>";
                break;
            case "è" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett6'>";
                break;
            case "ê" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett7'>";
                break;
            case "í" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett8'>";
                break;
            case "ì" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett9'>";
                break;
            case "Á" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett10'>";
                break;
            case "À" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='violett11'>";
                // 7. Grau
                break;
            case "R" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='grey1'>";
                // hier edit da so großer abstand existierte
                break;
            case "G" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='grey2'>";
                break;
            case "ò" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='grey3'>";
                break;
            case "Ä" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='braun1'>";
                break;
            case "ö" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='braun2'>";
                break;
            case "B" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='braun3'>";
                break;
            case "h" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='braun5'>";
                break;
            // Ende
            case "A" :
            case "a" :
                if ($nestedtags[font])
                    $output .= "</span>";
                else
                    $nestedtags[font] = true;
                $output .= "<span class='colAttention'>";
                break;
            case "c" :
                if ($nestedtags[div]) {
                    $output .= "</div>";
                    unset($nestedtags[div]);
                }
                else {
                    $nestedtags[div] = true;
                    $output .= "<div align='center'>";
                }
                break;
            case "H" :
                if ($nestedtags[div]) {
                    $output .= "</span>";
                    unset($nestedtags[div]);
                }
                else {
                    $nestedtags[div] = true;
                    $output .= "<span class='navhi'>";
                }
                break;
            case "b" :
                if ($nestedtags[b]) {
                    $output .= "</b>";
                    unset($nestedtags[b]);
                }
                else {
                    $nestedtags[b] = true;
                    $output .= "<b>";
                }
                break;
            case "i" :
                if ($nestedtags[i]) {
                    $output .= "</i>";
                    unset($nestedtags[i]);
                }
                else {
                    $nestedtags[i] = true;
                    $output .= "<i>";
                }
                break;
            case "n" :
                $output .= "<br>\n";
                break;
            case "`" :
                $output .= "`";
                break;
            default :
                $output .= "`" . $tag;
        }
    }
    if ($priv) {
        $output .= $data;
    }
    else {
        $output .= HTMLSpecialChars($data);
    }
    return $output;
}

// Angegebene Tags am Ende des Strings schließen
// (macht keinen Sinn bei Farben, da die nicht geschlossen werden)
function closetags($string, $tags) {
    $tags = explode('`', $tags);
    foreach ($tags as $siht) {
        $siht = trim($siht);
        if ($siht == '')
            continue;
        if (substr_count($string, '`' . $siht) % 2)
            $string .= '`' . $siht;
    }
    return $string;
}

function templatereplace($itemname, $vals = false) {
    global $template;
    @reset($vals);
    if (!isset($template[$itemname]))
        output("`bWarnung:`b Das `i$itemname`i Template wurde nicht gefunden!`n");
    $out = $template[$itemname];
    // output($template[$itemname]."`n");
    while (list($key, $val) = @each($vals)) {
        if (strpos($out, "{" . $key . "}") === false)
            output("`bWarnung:`b Das `i$key`i Teil wurde im `i$itemname`i Template nicht gefunden! (" . $out . ")`n");
        $out = str_replace("{" . "$key" . "}", $val, $out);
    }
    return $out;
}

function addcharstat($title, $value = false) {
    // 2005 by Eliwood
    if ($value === false) {
        $charstat .= appoencode(templatereplace("stathead", array("title" => $title)), true);
    }
    else {
        $charstat .= appoencode(templatereplace("statrow", array("title" => "$title", "value" => "$value")), true);
    }
    return $charstat;
}

function charstats() {
    global $session, $SCRIPT_NAME;
    $u = &$session['user'];
    if ($session['loggedin']) {
        $u['hitpoints'] = round($u['hitpoints'], 0);
        $u['experience'] = round($u['experience'], 0);
        $u['maxhitpoints'] = round($u['maxhitpoints'], 0);
        $exparray = array(1 => 100, 400, 1002, 1912, 3140, 4707, 6641, 8985, 11795, 15143, 19121, 23840, 29437, 36071, 43930, 55000);
        while (list($key, $val) = each($exparray)) {
            $exparray[$key] = round($val + ($session['user']['dragonkills'] / 4) * $session['user']['level'] * 100, 0);
        }
        $exp = $session[user][experience] - $exparray[$session[user][level] - 1];
        $req = $exparray[$session[user][level]] - $exparray[$session[user][level] - 1];
        $spirits = array("-6" => "Wiedererweckt", "-2" => "Sehr schlecht", "-1" => "Schlecht", "0" => "Normal", "1" => "Gut", "2" => "Sehr gut");
        if ($u[alive]) {
        }
        else {
            $spirits[$u[spirits]] = "TOT";
        }
        reset($session[bufflist]);
        $atk = $u[attack];
        $def = $u[defence];
        while (list($key, $val) = each($session[bufflist])) {
            $buffs .= appoencode("`#$val[name] `7($val[rounds] Runden übrig)`n", true);
            if (isset($val[atkmod]))
                $atk *= $val[atkmod];
            if (isset($val[defmod]))
                $def *= $val[defmod];
        }
        $atk = round($atk, 2);
        $def = round($def, 2);
        $atk = ($atk == $u[attack] ? "`^" : ($atk > $u[attack] ? "`@" : "`$")) . "`b$atk`b`0";
        $def = ($def == $u[defence] ? "`^" : ($def > $u[defence] ? "`@" : "`$")) . "`b$def`b`0";
        $kla = array(0 => "Keine Klamotten", 1 => "Weises Kleid", 2 => "Drachenleder Kleid", 3 => "Schwarzes Jacket", 4 => "Drachenleder Jacket");

        $arm = array(0 => "Kein Armband", 1 => "Goldenes Armband", 2 => "Drachen Armband");

        $shoes = array(0 => "Keine Schuhe", 1 => "Wanderschuhe", 2 => "Sportschue", 3 => "Lederstiefel", 4 => "Drachen Stiefel");

        $hair = array(0 => "Glatze", 1 => "Glatze", 2 => "Glatze", 3 => "Glatze", 4 => "Glatze", 5 => "Glatze", 6 => "Glatze", 7 => "Glatze", 8 => "Glatze", 9 => "Glatze", 10 => "Kurze Haare", 11 => "Kurze Haare", 12 => "Kurze Haare", 13 => "Kurze Haare", 14 => "Kurze Haare", 15 => "Kurze Haare", 16 => "Kurze Haare", 17 => "Kurze Haare", 18 => "Kurze Haare", 19 => "Kurze Haare", 20 => "Normale Länge", 21 => "Normale Länge", 22 => "Normale Länge", 23 => "Normale Länge", 24 => "Normale Länge", 25 => "Normale Länge", 26 => "Normale Länge", 27 => "Normale Länge", 28 => "Normale Länge", 29 => "Normale Länge", 30 => "Normale Länge", 31 => "Normale Länge", 32 => "Normale Länge", 33 => "Normale Länge", 34 => "Normale Länge", 35 => "Normale Länge", 36 => "Normale Länge", 37 => "Normale Länge", 38 => "Normale Länge", 39 => "Normale Länge", 40 => "Lange Haare", 41 => "Lange Haare", 42 => "Lange Haare", 43 => "Lange Haare", 44 => "Lange Haare", 45 => "Lange Haare", 46 => "Lange Haare", 47 => "Lange Haare", 48 => "Lange Haare", 49 => "Lange Haare", 50 => "Lange Haare", 51 => "Lange Haare", 52 => "Lange Haare", 53 => "Lange Haare", 54 => "Lange Haare", 55 => "Lange Haare", 56 => "Lange Haare", 57 => "Lange Haare", 58 => "Lange Haare", 59 => "Lange Haare", 60 => "Lang und Ungeflegt", 61 => "Lang und Ungeflegt", 62 => "Lang und Ungeflegt", 63 => "Lang und Ungeflegt", 64 => "Lang und Ungeflegt", 65 => "Lang und Ungeflegt", 66 => "Lang und Ungeflegt", 67 => "Lang und Ungeflegt", 68 => "Lang und Ungeflegt", 69 => "Lang und Ungeflegt", 70 => "Lang und Ungeflegt", 71 => "Lang und Ungeflegt", 72 => "Lang und Ungeflegt", 73 => "Lang und Ungeflegt", 74 => "Lang und Ungeflegt", 75 => "Lang und Ungeflegt", 76 => "Lang und Ungeflegt", 77 => "Lang und Ungeflegt", 78 => "Lang und Ungeflegt", 79 => "Lang und Ungeflegt", 80 => "Lang und Ungeflegt");
        $hairco = array(0 => "Braune Haare", 1 => "Schwarze Haare", 2 => "Blonde Haare", 3 => "Grüne Haare", 4 => "Pinke Haare", 5 => "Rote Haare", 6 => "Blaue Haare");

        $nagel = array(0 => "Kurz", 1 => "Kurz", 2 => "Kurz", 3 => "Kurz", 4 => "Kurz", 5 => "Kurz", 6 => "Kurz", 7 => "Kurz", 8 => "Kurz", 9 => "Kurz", 10 => "Normal", 11 => "Normal", 12 => "Normal", 13 => "Normal", 14 => "Normal", 15 => "Normal", 16 => "Normal", 17 => "Normal", 18 => "Normal", 19 => "Normal", 20 => "Gepflegt", 21 => "Gepflegt", 22 => "Gepflegt", 23 => "Gepflegt", 24 => "Gepflegt", 25 => "Gepflegt", 26 => "Gepflegt", 27 => "Gepflegt", 28 => "Gepflegt", 29 => "Gepflegt", 30 => "Gepflegt", 31 => "Gepflegt", 32 => "Gepflegt", 33 => "Gepflegt", 34 => "Gepflegt", 35 => "Gepflegt", 36 => "Gepflegt", 37 => "Gepflegt", 38 => "Gepflegt", 39 => "Gepflegt", 40 => "Ungepflegt", 41 => "Ungepflegt", 42 => "Ungepflegt", 43 => "Ungepflegt", 44 => "Ungepflegt", 45 => "Ungepflegt", 46 => "Ungepflegt", 47 => "Ungepflegt", 48 => "Ungepflegt", 49 => "Ungepflegt", 50 => "Ungepflegt", 51 => "Ungepflegt", 52 => "Ungepflegt", 53 => "Ungepflegt", 54 => "Ungepflegt", 55 => "Ungepflegt", 56 => "Ungepflegt", 57 => "Ungepflegt", 58 => "Ungepflegt", 59 => "Ungepflegt", 60 => "Ungepflegt");

        $nagelco = array(0 => "Kein", 1 => "Schwarz", 2 => "Gelb", 3 => "Lila", 4 => "Rot", 5 => "Blau", 6 => "Pink", 7 => "Klarlack");
        if (count($session[bufflist]) == 0) {
            $buffs .= appoencode("`^Keine`0", true);
        }
        $charstat = appoencode(templatereplace("statstart") . templatereplace("stathead", array("title" => "Vital Info")) . templatereplace("statrow", array("title" => "Name", "value" => appoencode($u[name], false))), true);
        if ($session['user']['ssstatus'] == 1 && $session['user']['ssmonat'] <= 16) {
            switch ($session ['user'] ['ssmonat']) {
                case 16 :
                case 15 :
                    $charstat .= appoencode(templatereplace("statrow", array("title" => "Schwangerschaft", "value" => "Noch nichts zu sehen")), true);
                    break;
                case 14 :
                case 13 :
                case 12 :
                case 11 :
                    $charstat .= appoencode(templatereplace("statrow", array("title" => "Schwangerschaft", "value" => "Kleiner Bauch")), true);
                    break;
                case 10 :
                case 9 :
                case 8 :
                case 7 :
                    $charstat .= appoencode(templatereplace("statrow", array("title" => "Schwangerschaft", "value" => "Dicker Bauch")), true);
                    break;
                case 6 :
                case 5 :
                case 4 :
                case 3 :
                    $charstat .= appoencode(templatereplace("statrow", array("title" => "Schwangerschaft", "value" => "Sehr dicker Bauch")), true);
                    break;
                case 2 :
                case 1 :
                    $charstat .= appoencode(templatereplace("statrow", array("title" => "Schwangerschaft", "value" => "Hochschwanger")), true);
                    break;
            }
        }

        if ($session['user']['alive']) {

            $charstat .= appoencode(templatereplace("statrow", array("title" => "Lebenspunkte", "value" => "$u[hitpoints]`0/$u[maxhitpoints]" . grafbar($u[maxhitpoints], $u[hitpoints]))) . templatereplace("statrow", array("title" => "Runden", "value" => $u['turns'])), true);
        }
        else {
            $charstat .= appoencode(templatereplace("statrow", array("title" => "Seelenpunkte", "value" => "$u[soulpoints]" . grafbar((5 * $u[level] + 50), $u[soulpoints]))) . templatereplace("statrow", array("title" => "Foltern", "value" => $u['gravefights'])) . templatereplace("statrow", array('title' => 'Gefallen', 'value' => $u['deathpower'])), true);
        }
        $charstat .= appoencode(templatereplace("statrow", array("title" => "Erfahrung", "value" => "`t" . $u[experience] . "/" . $exparray[$session[user][level]] . "" . grafbar($req, $exp) . "")) . templatereplace("statrow", array('title' => 'RP-Punkte', 'value' => $u['rppunkte'])) . templatereplace("statrow", array("title" => "Stimmung", "value" => "`b" . $spirits[( string )$u['spirits']] . "`b")) . templatereplace("statrow", array("title" => "Charme", "value" => $u[charm])) . templatereplace("statrow", array("title" => "Level", "value" => "`b" . $u['level'] . "`b")) . ($session['user']['alive'] ? templatereplace("statrow", array("title" => "Angriff", "value" => $atk)) . templatereplace("statrow", array("title" => "Verteidigung", "value" => $def)) : templatereplace("statrow", array("title" => "Psyche", "value" => 10 + round(($u['level'] - 1) * 1.5))) . templatereplace("statrow", array("title" => "Geist", "value" => 10 + round(($u['level'] - 1) * 1.5)))) . templatereplace("stathead", array("title" => "Ausbildung")) . templatereplace("statrow", array("title" => "Ausbildungszweig", "value" => $u['aubid'])) . templatereplace("statrow", array("title" => "Lektion", "value" => $u['lektion'])) . templatereplace("statrow", array("title" => "Beruf", "value" => $u['jobname'])) . templatereplace("stathead", array("title" => "Links")) . templatereplace("statrow", array("title" => "Einwohnerliste", "value" => "<a href='list_popup.php' target='_blank' onClick=\"" . popup("list_popup.php") . ";return false;\">&nbsp`IEinwohnerliste`0</a>")) . templatereplace("statrow", array("title" => "Farben", "value" => "<a href='petition.php?op=faq4' target='_blank' onClick=\"" . popup("petition.php?op=faq4") . ";return false;\">&nbsp`IFarben`0</a>")) . templatereplace("stathead", array("title" => "Weitere Infos")) . templatereplace("statrow", array("title" => "Gold", "value" => $u['gold'])) . templatereplace("statrow", array("title" => "Gold auf Bank", "value" => $u['goldinbank'])) . templatereplace("statrow", array("title" => "Edelsteine", "value" => $u['gems'])) . templatereplace("statrow", array("title" => "Edelsteine auf Bank", "value" => $u['gemsinbank'])) . templatereplace("stathead", array("title" => "Ausrüstung")) . templatereplace("statrow", array("title" => "Waffe", "value" => $u['weapon'])) . templatereplace("statrow", array("title" => "Waffenschaden", "value" => $u['weapondmg'])) . templatereplace("statrow", array("title" => "Rüstung", "value" => $u['armor'])) . templatereplace("statrow", array("title" => "Rüstungsschutz", "value" => $u['armordef'])) . templatereplace("statrow", array("title" => "Klamotten", "value" => $kla[$u['klamotten']])) . templatereplace("statrow", array("title" => "Armband", "value" => $arm[$u['armband']])) . templatereplace("statrow", array("title" => "Schuhe", "value" => $shoes[$u['schuhe']])) . templatereplace("stathead", array("title" => "Aussehen")) . templatereplace("statrow", array("title" => "Haare", "value" => $hair[$u['frisur']])) . templatereplace("statrow", array("title" => "Haar Farbe", "value" => $hairco[$u['hairco']])) . templatereplace("statrow", array("title" => "Nägel", "value" => $nagel[$u['nagel']])) . templatereplace("statrow", array("title" => "Nägel Farbe", "value" => $nagelco[$u['nagelco']])) . templatereplace("statrow", array("title" => "Orden", "value" => $u[orden])), true);
        if ($session['user']['memberid'] > 0 && $session['user']['gildenactive'] == 1) {
            $gu = &$session['guild'];
            $charstat .= addcharstat("Gildeninformationen");
            $charstat .= addcharstat("Gilde", "`^" . $gu['gildenname'] . " `^(" . $gu['gildenprefix'] . "`^)");
            /* Rang holen */
            $rank = db_fetch_assoc(db_query("SELECT rankname FROM gildenranks WHERE rankid='" . $session['user']['rankid'] . "'"));
            /* Keinen zugewiesenen Rang? Oder Rangid ungültig? Macht nichts, einfach Ranglos anzeigen. */
            if ($rank['rankname'] == "")
                $rank['rankname'] = "Ranglos";
            $charstat .= addcharstat("Rang", $rank['rankname']);
            // $charstat.=addcharstat("Gold",$gu['gold']);
            // $charstat.=addcharstat("Edelsteine",$gu['gems']);
        }
        addnav('', 'prefs.php?op=inventory&restorpage=' . $SCRIPT_NAME . '');
        if ($u['petid'] > 0) {
            $pettime = strtotime($u['petfeed']) - time();
            $charstat .= appoencode(templatereplace('statrow', array('title' => 'Haustier', 'value' => grafbar(24 * 3600, $pettime))), true);
        }
        if (getsetting("dispnextday", 0)) {
            $time = gametime();
            $tomorrow = strtotime(date("Y-m-d H:i:s", $time) . " + 1 day");
            $tomorrow = strtotime(date("Y-m-d 00:00:00", $tomorrow));
            $secstotomorrow = $tomorrow - $time;
            $realsecstotomorrow = round($secstotomorrow / ( int ) getsetting("daysperday", 4));
            $charstat .= appoencode(templatereplace("statrow", array("title" => "Nächster Tag", "value" => date("G\\h, i\\m, s\\s \\", strtotime("1980-01-01 00:00:00 + $realsecstotomorrow seconds")))), true);
        }
        if (!is_array($session[bufflist]))
            $session[bufflist] = array();
        $charstat .= appoencode(templatereplace("statbuff", array("title" => "Aktionen", "value" => $buffs)), true);
        $charstat .= appoencode(templatereplace("statend"), true);
        return $charstat;
    }
    else {
        // return "Your character info will appear here after you've logged in.";
        // $sql = "SELECT name,alive,location,sex,level,laston,loggedin,lastip,uniqueid FROM accounts WHERE locked=0 AND loggedin=1 ORDER BY level DESC";
        $sql = "SELECT name,alive,location,sex,level,laston,loggedin,lastip,uniqueid FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'" . date("Y-m-d H:i:s", strtotime(date("r") . "-" . getsetting("LOGINTIMEOUT", 900) . " seconds")) . "' ORDER BY level DESC";
        $result = db_query($sql) or die(sql_error($sql));
        $count = db_num_rows($result);
        $ret .= appoencode("`b$count Spieler Online:`b`n");
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $ret .= appoencode("`^$row[name]`n");
            $onlinecount++;
        }
        db_free_result($result);
        if ($onlinecount == 0)
            $ret .= appoencode("`iNiemand`i");
        $ret .= (getsetting("maxonline", 10) > 0 ? grafbar(getsetting("maxonline", 10), (getsetting("maxonline", 10) - $onlinecount), 180) : "");
        return $ret;
    }
}

$accesskeys = array();
$quickkeys = array();
function addnav($text, $link = false, $priv = false, $pop = false, $newwin = false) {
    global $nav, $session, $accesskeys, $REQUEST_URI, $quickkeys;
    $text = translate($text);
    /*
     * if (date("m-d")=="04-01"){ $text = borkalize($text); }
     */
    if ($link === false) {
        $nav .= templatereplace("navhead", array("title" => appoencode($text, $priv)));
    }
    elseif ($link === "") {
        $nav .= templatereplace("navhelp", array("text" => appoencode($text, $priv)));
    }
    else {
        if ($text != "") {
            $extra = "";
            if ($newwin === false) {
                if (strpos($link, "?")) {
                    $extra = "&c=$session[counter]";
                }
                else {
                    $extra = "?c=$session[counter]";
                }
            }

            if ($newwin === false)
                $extra .= "-" . date("His");
            // $link = str_replace(" ","%20",$link);
            // hotkey for the link.
            $key = "";
            if (substr($text, 1, 1) == "?") {
                // check to see if a key was specified up front.
                if ($accesskeys[strtolower(substr($text, 0, 1))] == 1) {
                    // output ("key ".substr($text,0,1)." already taken`n");
                    $text = substr($text, 2);
                }
                else {
                    $key = substr($text, 0, 1);
                    $text = substr($text, 2);
                    // output("key set to $key`n");
                    $found = false;
                    for ($i = 0; $i < strlen($text); $i++) {
                        $char = substr($text, $i, 1);
                        if ($ignoreuntil == $char) {
                            $ignoreuntil = "";
                        }
                        else {
                            if ($ignoreuntil != "") {
                                if ($char == "<")
                                    $ignoreuntil = ">";
                                if ($char == "&")
                                    $ignoreuntil = ";";
                                if ($char == "`")
                                    $ignoreuntil = substr($text, $i + 1, 1);
                            }
                            else {
                                if ($char == $key) {
                                    $found = true;
                                    break;
                                }
                            }
                        }
                    }
                    if ($found == false) {
                        if (strpos($text, "__") !== false)
                            $text = str_replace("__", "(" . $key . ") ", $text);
                        else
                            $text = "(" . strtoupper($key) . ") " . $text;
                        $i = strpos($text, $key);
                        // output("Not found`n");
                    }
                }
                //
            }
            if ($key == "") {
                for ($i = 0; $i < strlen($text); $i++) {
                    $char = substr($text, $i, 1);
                    if ($ignoreuntil == $char) {
                        $ignoreuntil = "";
                    }
                    else {
                        if (($accesskeys[strtolower($char)] == 1) || (strpos("abcdefghijklmnopqrstuvwxyz0123456789", strtolower($char)) === false) || $ignoreuntil != "") {
                            if ($char == "<")
                                $ignoreuntil = ">";
                            if ($char == "&")
                                $ignoreuntil = ";";
                            if ($char == "`")
                                $ignoreuntil = substr($text, $i + 1, 1);
                        }
                        else {
                            break;
                        }
                    }
                }
            }
            if ($i < strlen($text)) {
                $key = substr($text, $i, 1);
                $accesskeys[strtolower($key)] = 1;
                $keyrep = " accesskey=\"$key\" ";
            }
            else {
                $key = "";
                $keyrep = "";
            }
            // output("Key is $key for $text`n");

            if ($key == "") {
                // $nav.="<a href=\"".HTMLSpecialChars($link.$extra)."\" class='nav'>".appoencode($text,$priv)."<br></a>";
                // $key==""; // This is useless
            }
            else {
                $text = substr($text, 0, strpos($text, $key)) . "`H" . $key . "`H" . substr($text, strpos($text, $key) + 1);
                if ($pop) {
                    $quickkeys[$key] = popup($link . $extra);
                }
                else {
                    $quickkeys[$key] = "window.location='$link$extra';";
                }
            }
            $nav .= templatereplace("navitem", array("text" => appoencode($text, $priv), "link" => HTMLSpecialChars($link . $extra), "accesskey" => $keyrep, "popup" => ($pop == true ? "target='_blank' onClick=\"" . popup($link . $extra) . "; return false;\"" : ($newwin == true ? "target='_blank'" : ""))));
            // $nav.="<a href=\"".HTMLSpecialChars($link.$extra)."\" $keyrep class='nav'>".appoencode($text,$priv)."<br></a>";
        }
        $session[allowednavs][$link . $extra] = true;
        $session[allowednavs][str_replace(" ", "%20", $link) . $extra] = true;
        $session[allowednavs][str_replace(" ", "+", $link) . $extra] = true;
    }
}

function savesetting($settingname, $value) {
    global $settings;
    loadsettings();
    if ($value > "") {
        if (!isset($settings[$settingname])) {
            $sql = "INSERT INTO settings (setting,value) VALUES (\"" . addslashes($settingname) . "\",\"" . addslashes($value) . "\")";
        }
        else {
            $sql = "UPDATE settings SET value=\"" . addslashes($value) . "\" WHERE setting=\"" . addslashes($settingname) . "\"";
        }
        db_query($sql) or die(db_error(LINK));
        $settings[$settingname] = $value;
        if (db_affected_rows() > 0)
            return true;
        else
            return false;
    }
    return false;
}

function loadsettings() {
    global $settings;
    // as this seems to be a common complaint, examine the execution path of this function,
    // it will only load the settings once per page hit, in subsequent calls to this function,
    // $settings will be an array, thus this function will do nothing.
    if (!is_array($settings)) {
        $settings = array();
        $sql = "SELECT * FROM settings";
        $result = db_query($sql) or die(db_error(LINK));
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $settings[$row[setting]] = $row[value];
        }
        db_free_result($result);
        $ch = 0;
        if ($ch = 1 && strpos($_SERVER['SCRIPT_NAME'], "login.php")) {
            // @file("http://www.mightye.org/logdserver?".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
        }
    }
}

function getsetting($settingname, $default) {
    global $settings;
    loadsettings();
    if (!isset($settings[$settingname])) {
        savesetting($settingname, $default);
        return $default;
    }
    else {
        if (trim($settings[$settingname]) == "")
            $settings[$settingname] = $default;
        return $settings[$settingname];
    }
}

function showform($layout, $row, $nosave = false) {
    global $output;
    output("<table>", true);
    while (list($key, $val) = each($layout)) {
        $info = split(",", $val);
        if ($info[1] == "title") {
            output("<tr><td colspan='2' bgcolor='#666666'>", true);
            output("`b`^$info[0]`0`b");
            output("</td></tr>", true);
        }
        else {
            output("<tr><td nowrap valign='top'>", true);
            output("$info[0]");
            output("</td><td>", true);
        }
        switch ($info [1]) {
            case "title" :
                break;
            case "enum" :
                reset($info);
                list($k, $v) = each($info);
                list($k, $v) = each($info);
                $output .= "<select name='$key'>";
                while (list($k, $v) = each($info)) {
                    $optval = $v;
                    list($k, $v) = each($info);
                    $optdis = $v;
                    $output .= "<option value='$optval'" . ($row[$key] == $optval ? " selected" : "") . ">" . HTMLSpecialChars("$optval : $optdis") . "</option>";
                }
                $output .= "</select>";
                break;
            case "password" :
                $output .= "<input type='password' name='$key' value='" . HTMLSpecialChars($row[$key]) . "'>";
                break;
            case "bool" :
                $output .= "<select name='$key'>";
                $output .= "<option value='0'" . ($row[$key] == 0 ? " selected" : "") . ">Nein</option>";
                $output .= "<option value='1'" . ($row[$key] == 1 ? " selected" : "") . ">Ja</option>";
                $output .= "</select>";
                break;
            case "truebool" :
                $output .= "<select name='$key'>";
                $output .= "<option value='1'" . ($row[$key] == 1 ? " selected" : "") . ">Ja</option>";
                $output .= "<option value='0'" . ($row[$key] == 0 ? " selected" : "") . ">Nein</option>";
                $output .= "</select>";
                break;
            case "hidden" :
                $output .= "<input type='hidden' name='$key' value=\"" . HTMLSpecialChars($row[$key]) . "\">" . HTMLSpecialChars($row[$key]);
                break;
            case "viewonly" :
                output(dump_item($row[$key]), true);
                // output(str_replace("{","<blockquote>{",str_replace("}","}</blockquote>",HTMLSpecialChars(preg_replace("'(b:[[:digit:]]+;)'","\\1`n",$row[$key])))),true);
                break;
            case "int" :
                $output .= "<input name='$key' value=\"" . HTMLSpecialChars($row[$key]) . "\" size='5'>";
                break;
            case "textarea" :
                $output .= "<textarea name='$key' class='input' cols='$info[2]' rows='$info[3]'>" . HTMLSpecialChars($row[$key]) . "</textarea>";
                break;
            case 'file' :
                $output .= "<input name='$key' type='file'>";
                break;
            case 'rpnumber' :
                $output .= "<input name='$key' type='number' min='6' max='60' step='2' value=\"" . htmlspecialchars($row[$key]) . "\">";
                break;
            default :
                $output .= ("<input size='50' name='$key' value=\"" . HTMLSpecialChars($row[$key]) . "\">");
            // output("`n$val");
        }
        output("</td></tr>", true);
    }
    output("</table>", true);
    if ($nosave) {
    }
    else
        output("<input type='submit' class='button' value='Speichern'>", true);
}

function clearnav() {
    $session[allowednavs] = array();
}

// Synchronisationsproblem
// Function by Raven @ www.rabenthal.de
//
function get_special_var($var = FALSE) {// by Raven @ rabenthal
    global $session;
    if ($var) {
        $sql = "SELECT * FROM specialvars WHERE player1 = " . $session[user][acctid] . " AND var = " . $var . "";
    }
    else {
        $sql = "SELECT * FROM specialvars WHERE player1 = " . $session[user][acctid] . "";
    }
    $result = db_query($sql);
    $anzahl = db_num_rows($result);
    for ($i = 0; $i <= $anzahl; $i++) {
        $row = db_fetch_assoc($result);
        // hole Datensatz
        $okay = 0;
        switch ($row ['var']) {
            case "charm" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][charm] = $session[user][charm] + $wert;
                $okay = 1;
                break;
            case "charisma" :
                $session[user][charisma] = $row[value];
                if ($row['text'] == "Heirat")
                    $session[user][marriedto] = $row['player2'];
                $okay = 1;
                break;
            case "seenlover" :
                $session[user][seenlover] = ( int )$row[value];
                $okay = 1;
                break;
            case "donation" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][donation] = $session[user][donation] + $wert;
                $okay = 1;
                break;
            case "goldinbank" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][goldinbank] = $session[user][goldinbank] + $wert;
                if (substr($row[text], 0, 11) == "Überweisung") {
                    systemmail($session[user][acctid], "`^Du hast eine Überweisung erhalten!`0", "`6{$row['text']}!");
                }
                $okay = 1;
                break;
            case "goldcginbank" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][goldinbank] = $session[user][goldinbank] + $wert;
                if (substr($row[text], 0, 11) == "Überweisung") {
                    systemmail($session[user][acctid], "`^Du hast eine Überweisung erhalten!`0", "`6{$row['text']}!");
                }
                $session[user][cg_getgold] += $wert;
                $okay = 1;
                break;
            case "gems" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][gems] = $session[user][gems] + $wert;
                if (substr($row[text], 0, 11) == "Überweisung") {
                    systemmail($session[user][acctid], "`^Du hast eine Überweisung erhalten!`0", "`6{$row['text']}!");
                }
                $okay = 1;
                break;
            case "cggems" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][gems] = $session[user][gems] + $wert;
                if (substr($row[text], 0, 11) == "Überweisung") {
                    systemmail($session[user][acctid], "`^Du hast eine Überweisung erhalten!`0", "`6{$row['text']}!");
                }
                $session[user][cg_getgems] += $wert;
                $okay = 1;
                break;
            case "location" :
                $session[user][location] = ( int )$row[value];
                $okay = 1;
                break;
            case "prayer" :
                $session[user][prayer] = ( int )$row[value];
                $okay = 1;
                break;
            case "loggedin" :
                $session[user][loggedin] = ( int )$row[value];
                $okay = 1;
                break;
            case "jailtime" :
                $session[user][jailtime] = ( int )$row[value];
                $okay = 1;
                break;
            case "transferredtoday" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][transferredtoday] = $session[user][transferredtoday] + $wert;
                $okay = 1;
                break;
            case "goldafterdk" :
                if ($row['assign'] == '0')
                    $wert = ( int )$row[value];
                else
                    $wert = ( int )$row[value] * -1;
                $session[user][goldafterdk] = $wert;
                $okay = 1;
                break;
        }
        if ($okay == 1) {
            $sql = "DELETE FROM specialvars WHERE id = " . $row[id] . "";
            db_query($sql);
        }
    }
}

function set_special_var($var, $value, $assign, $player_1, $player_2, $text) {// by Raven @ rabenthal
    $sql = "SELECT max(id) as max from specialvars";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $maxzahl = $row[max] + 1;

    $sql = "INSERT INTO specialvars (id,var,value,player1,player2,assign,text)
VALUES( " . $maxzahl . "
,'$var'
,'$value'
," . ( int )$player_1 . "
," . ( int )$player_2 . "
,'$assign'
,'$text'
)";
    return db_query($sql) or die(db_error($link));
}

function redirect($location, $reason = false) {
    global $session, $REQUEST_URI;
    if ($location != "badnav.php") {
        $session[allowednavs] = array();
        addnav("", $location);
    }
    if (strpos($location, "badnav.php") === false)
        $session[output] = "<a href=\"" . HTMLSpecialChars($location) . "\">Hier klicken</a>";
    $session['debug'] .= "Redirected to $location from $REQUEST_URI.  $reason\n";
    saveuser();
    header("Location: $location");
    echo $location;
    echo $session['debug'];
    exit();
}

function loadtemplate($templatename) {
    if (!file_exists("templates/$templatename") || $templatename == "")
        $templatename = "shadow.htm";
    $fulltemplate = join("", file("templates/$templatename"));
    $fulltemplate = split("<!--!", $fulltemplate);
    while (list($key, $val) = each($fulltemplate)) {
        $fieldname = substr($val, 0, strpos($val, "-->"));
        if ($fieldname != "") {
            $template[$fieldname] = substr($val, strpos($val, "-->") + 3);
        }
    }
    return $template;
}

function maillink() {
    global $session;
    $sql = "SELECT sum(if(seen=1,1,0)) AS seencount, sum(if(seen=0,1,0)) AS notseen FROM mail WHERE msgto=\"" . $session[user][acctid] . "\"";
    $result = db_query($sql) or die(mysql_error(LINK));
    $row = db_fetch_assoc($result);
    db_free_result($result);
    $row[seencount] = ( int )$row[seencount];
    $row[notseen] = ( int )$row[notseen];
    if ($row[notseen] > 0) {
        return "<a href='mail.php' target='_blank' onClick=\"" . popup("mail.php") . ";return false;\" class='hotmotd'>Ye Olde Mail: $row[notseen] neu, $row[seencount] alt</a>";
    }
    else {
        return "<a href='mail.php' target='_blank' onClick=\"" . popup("mail.php") . ";return false;\" class='motd'>Ye Olde Mail: $row[notseen] neu, $row[seencount] alt</a>";
    }
}

function motdlink() {
    // missing $session caused unread motd's to never highlight the link
    global $session;
    if ($session[needtoviewmotd]) {
        return "<a href='motd.php' target='_blank' onClick=\"" . popup("motd.php") . ";return false;\" class='hotmotd'><b>MoTD</b></a>";
    }
    else {
        return "<a href='motd.php' target='_blank' onClick=\"" . popup("motd.php") . ";return false;\" class='motd'><b>MoTD</b></a>";
    }
}

function page_header($title = "LoGD 0.9.7 +jt ext (GER) 3") {
    global $header, $SCRIPT_NAME, $session, $template;
    $nopopups["login.php"] = 1;
    $nopopups["motd.php"] = 1;
    $nopopups["index.php"] = 1;
    $nopopups["create.php"] = 1;
    $nopopups["about.php"] = 1;
    $nopopups["mail.php"] = 1;
    $nopopups["chat.php"] = 1;

    $header = $template['header'];
    $sql = "SELECT motddate FROM motd ORDER BY motditem DESC LIMIT 1";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    db_free_result($result);
    if (($row[motddate] > $session[user][lastmotd]) && $nopopups[$SCRIPT_NAME] != 1 && $session[user][loggedin]) {
        $header = str_replace("{headscript}", "<script language=\"JavaScript\" type=\"text/javascript\">" . popup("motd.php") . "</script>", $header);
        $session[needtoviewmotd] = true;
    }
    else {
        $header = str_replace("{headscript}", "", $header);
        $session[needtoviewmotd] = false;
    }
    $header = str_replace("{title}", $title, $header);
}

function popup($page) {
    return "window.open('$page','" . preg_replace("([^[:alnum:]])", "", $page) . "','scrollbars=yes,resizable=yes,width=550,height=300')";
}

function popupbio($page) {
    return "window.open('$page&op=bio','" . preg_replace("([^[:alnum:]])", "", $page) . "','scrollbars=yes,resizable=yes,width=940,height=900')";
}

function popupbackup($page) {
    return "window.open('$page','" . preg_replace("([^[:alnum:]])", "", $page) . "','scrollbars=yes,resizable=yes,width=940,height=900')";
}

function page_footer() {
    $forumlink = getsetting("forum", "http://lotgd.net/forum");
    // $forumlink="http://www.anpera.net/forum/index.php?c=12#";
    global $output, $nestedtags, $header, $nav, $session, $REMOTE_ADDR, $REQUEST_URI, $pagestarttime, $dbtimethishit, $dbqueriesthishit, $quickkeys, $template, $logd_version;

    while (list($key, $val) = each($nestedtags)) {
        $output .= "</$key>";

        unset($nestedtags[$key]);
    }
    // output keypress script
    $script .= '<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
document.onkeypress=keyevent;
function keyevent(event) {
var keyCode;
var target;

// ie
if (!event) {
event = window.event;
}

// firefox und chrome
if (event.target) {
target = event.target;
} else {
target = event.srcElement;
}

// pressed key abfrage
if (event.which) {
keyCode = event.which;
} else if (event.keyCode) {
keyCode = event.keyCode;
}

if (target.nodeName.toUpperCase()=="INPUT"
|| target.nodeName.toUpperCase()=="TEXTAREA"
|| event.altKey
|| event.ctrlKey) {
return true;
} else {
switch(String.fromCharCode(keyCode).toUpperCase()){';
    reset($quickkeys);
    foreach ($quickkeys as $key => $val) {
        $script .= "\n                case \"" . strtoupper($key) . "\": " . $val . "; return false; break;";
    }
    $script .= '
}
return true;
}
}
/*]]>*/
</script>';
    // chat preview mod by Chaosmaker - start
    $script .= <<<JS
<script type="text/javascript">
<!--
function appoencode(data) {
var Fundstelle = -1;
var tag = '';
var append = '';
var output = '<br />Vorschau: ';
var openspan = false;
while ((Fundstelle = data.search(/`/)) != -1) {
tag = data.substr(Fundstelle+1, 1);
append = data.substr(0,Fundstelle);
append = append.replace(/</,'&lt;');
append = append.replace(/>/,'&gt;');
output = output+ append;
if (data.length >= Fundstelle+2) data = data.substring(Fundstelle+2,data.length);
else data = '';
switch (tag) {
case "0":
if (openspan) output= output+"</span>";
openspan = false;
break;
case "1":
if (openspan) output= output+"</span>"; else openspan = true;
output= output+"<span class='colDkBlue'>";
break;
case "2":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkGreen'>";
break;
case "3":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkCyan'>";
break;
case "4":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkRed'>";
break;
case "5":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkMagenta'>";
break;
case "6":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkYellow'>";
break;
case "7":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkWhite'>";
break;
case "8":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLime'>";
break;
case "9":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colBlue'>";
break;
case "!":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtBlue'>";
break;
case "@":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtGreen'>";
break;
case "#":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtCyan'>";
break;
case "$":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtRed'>";
break;
case "%":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtMagenta'>";
break;
case "^":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtYellow'>";
break;
case "&":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtWhite'>";
break;
case "~":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colBlack'>";
break;
case "Q":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkOrange'>";
break;
case "q":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colOrange'>";
break;
case "r":
//                 case "R":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colRose'>";
break;
case "V":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colBlueViolet'>";
break;
case "v":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='coliceviolet'>";
break;
case "g":
//                  case "G":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colXLtGreen'>";
break;
case "T":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colDkBrown'>";
break;
case "t":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colLtBrown'>";
break;
case "?":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colWhiteBlack'>";
break;
case "*":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colBack'>";
break;
case "A":
case "a":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='colAttention'>";
break;
case "Ä":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='braun1'>";
break;
case "ö":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='braun2'>";
break;
case "h":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='braun5'>";
break;
case "B" :
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='braun3'>";
break;
case "(":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='braun4'>";
break;
case "w":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='red1'>";
break;
case "W":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='red2'>";
break;
case "e":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='red3'>";
break;
case "E":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='red4'>";
break;
case "z":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='red5'>";
break;
case "Z":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='yellow1'>";
break;
case "u":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green1'>";
break;
case "U":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green2'>";
break;
case "i":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green3'>";
break;
case "I":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green4'>";
break;
case "o":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green5'>";
break;
case "O":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green6'>";
break;
case "p":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green7'>";
break;
case "P":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green8'>";
break;
case "s":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green9'>";
break;
case "S":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green10'>";
break;
case "d":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green11'>";
break;
case "D":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='green12'>";
break;
case "f":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='turkiese1'>";
break;
case "F":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='turkiese2'>";
break;
case "j":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='turkiese3'>";
break;
case "y":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue1'>";
break;
case "Y":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue2'>";
break;
case "x":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue3'>";
break;
case "X":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue4'>";
break;
case "m":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue5'>";
break;
case "J":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue6'>";
break;
case "k":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue7'>";
break;
case "K":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue8'>";
break;
case "l":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue9'>";
break;
case "L":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='blue10'>";
break;
case "M":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett1'>";
break;
case "-":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett2'>";
break;
case "à":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett3'>";
break;
case "â":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett4'>";
break;
case "é":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett5'>";
break;
case "è":
if (openspan) output= output+"</span>"; else openspan=true;

output= output+"<span class='violett6'>";
break;
case "ê":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett7'>";
break;
case "í":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett8'>";
break;
case "ì":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett9'>";
break;
case "Á":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett10'>";
break;
case "À":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='violett11'>";
break;
case "R":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='grey1'>";
break;
case "G":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='grey2'>";
break;
case "ò":
if (openspan) output= output+"</span>"; else openspan=true;
output= output+"<span class='grey3'>";
break;

case "`":
output= output+"`";
break;
default:
output= output+"`"+tag;
}
}
output += data;
if (openspan) output += '</span>';
return output;
}
//-->
</script>
JS;
    // chat preview mod by Chaosmaker - end

    $footer = $template['footer'];
    if (strpos($footer, "{paypal}") || strpos($header, "{paypal}")) {
        $palreplace = "{paypal}";
    }
    else {
        $palreplace = "{stats}";
    }

    // NOTICE
    // NOTICE Although I will not deny you the ability to remove the below paypal link, I do request, as the author of this software
    // NOTICE that you leave it in.
    // NOTICE
    $paypalstr = '<table align="center"><tr><td>';
    $paypalstr .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="nahdude81@hotmail.com">
<input type="hidden" name="item_name" value="Legend of the Green Dragon Author Donation from ' . preg_replace("/[`]./", "", $session['user']['name']) . '">
<input type="hidden" name="item_number" value="' . HTMLSpecialChars($session['user']['login']) . ":" . $_SERVER['HTTP_HOST'] . "/" . $_SERVER['REQUEST_URI'] . '">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="cn" value="Your Character Name">
<input type="hidden" name="cs" value="1">
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="tax" value="0">
<input type="image" src="images/paypal1.gif" border="0" name="submit" alt="Donate!">
</form>';
    $paysite = getsetting("paypalemail", "");
    if ($paysite != "") {
        $paypalstr .= '</td><td>';
        $paypalstr .= '<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_xclick">
<input type="hidden" name="business" value="' . $paysite . '">
<input type="hidden" name="item_name" value="Legend of the Green Dragon Site Donation from ' . preg_replace("/[`]./", "", $session['user']['name']) . '">
<input type="hidden" name="item_number" value="' . HTMLSpecialChars($session['user']['login']) . ":" . $_SERVER['HTTP_HOST'] . "/" . $_SERVER['REQUEST_URI'] . '">
<input type="hidden" name="no_shipping" value="1">
<input type="hidden" name="cn" value="Your Character Name">
<input type="hidden" name="cs" value="1">
<input type="hidden" name="currency_code" value="EUR">
<input type="hidden" name="tax" value="0">
<input type="image" src="images/paypal2.gif" border="0" name="submit" alt="Donate!">
</form>';
    }
    $paypalstr .= '</td></tr></table>';
    // $paypalstr .= '</td></tr><tr><td span="2" align="right" valign="top"><a href="http://www.anpera.net" target="_blank"><img src="images/anpnet-klein.gif" alt="Sponsor" border="0"></a></td></tr></table>';
    $footer = str_replace($palreplace, (strpos($palreplace, "paypal") ? "" : "{stats}") . $paypalstr, $footer);
    $header = str_replace($palreplace, (strpos($palreplace, "paypal") ? "" : "{stats}") . $paypalstr, $header);
    // NOTICE
    // NOTICE Although I will not deny you the ability to remove the above paypal link, I do request, as the author of this software
    // NOTICE that you leave it in.
    // NOTICE
    $header = str_replace("{nav}", $nav, $header);
    $footer = str_replace("{nav}", $nav, $footer);

    $header = str_replace("{motd}", motdlink(), $header);
    $footer = str_replace("{motd}", motdlink(), $footer);
    $header = str_replace("{forum}", "<a href='$forumlink' target='_blank' class='motd'>Forum</a>", $header);
    $footer = str_replace("{forum}", "<a href='$forumlink' target='_blank' class='motd'>Forum</a>", $footer);

    if ($session[user][acctid] > 0) {
        $header = str_replace("{mail}", maillink(), $header);
        $footer = str_replace("{mail}", maillink(), $footer);
        $header = str_replace("{useronline}", useronline(), $header);
        $footer = str_replace("{useronline}", useronline(), $footer);
        if ($session[user][veri] > 0) {
        $header = str_replace("{chat}", "<a href='/chat/index.php' target='_blank' class='motd' onClick=\"" . popup("/chat/index.php") . ";return false;\">OOC-Chat</a>", $header);
        $footer = str_replace("{chat}", "<a href='/chat/index.php' target='_blank' class='motd' onClick=\"" . popup("/chat/index.php") . ";return false;\">OOC-Chat</a>", $footer);
    }else{
     $header = str_replace("{chat}", "", $header);
        $footer = str_replace("{chat}", "", $footer);
    }    
    }
    else {
        $header = str_replace("{mail}", "", $header);
        $footer = str_replace("{mail}", "", $footer);
        $header = str_replace("{useronline}", "", $header);
        $footer = str_replace("{useronline}", "", $footer);
        $header = str_replace("{chat}", "", $header);
        $footer = str_replace("{chat}", "", $footer);
    }
    $header = str_replace("{petition}", "<a href='petition.php' onClick=\"" . popup("petition.php") . ";return false;\" target='_blank' align='right' class='motd'>Hilfe anfordern</a>", $header);
    $footer = str_replace("{petition}", "<a href='petition.php' onClick=\"" . popup("petition.php") . ";return false;\" target='_blank' align='right' class='motd'>Hilfe anfordern</a>", $footer);
    if ($session['user']['superuser'] > 1) {
        // $sql = "SELECT count(petitionid) AS c,status FROM petitions GROUP BY status";
        $sql = "SELECT max(lastact) AS lastact, count(petitionid) AS c,status FROM petitions GROUP BY status";
        $result = db_query($sql);
        $petitions = array(0 => 0, 1 => 0, 2 => 0);
        $petitions['unread'] = false;
        for ($i = 0; $i < db_num_rows($result); $i++) {
            $row = db_fetch_assoc($result);
            $petitions[( int )$row['status']] = $row['c'];
            if ($row['lastact'] > $session['lastlogoff'])
                $petitions['unread'] = true;
        }
        db_free_result($result);
        /* // Neue Petitionen; schauen, ob Sternchen nötig ist */
        $petitions['star'] = '';
        if ($petitions['unread']) {
            $sql = 'SELECT petitionid, lastact FROM petitions WHERE lastact > "' . $session['lastlogoff'] . '"';
            $result = db_query($sql);
            while ($row = db_fetch_assoc($result)) {
                if (!$session['petitions'][$row['petitionid']]) {
                    $petitions['star'] = '<span class="colDkRed">*</span>';
                }
            }
            db_free_result($result);
        }
        $footer = "<table border='0' cellpadding='5' cellspacing='0' align='right'><tr><td><b><a href='viewpetition.php'>Anfragen</a>$petitions[star]:</b> $petitions[0] Ungelesen, $petitions[1] Gelesen, $petitions[2] Geschlossen.</td></tr></table>" . $footer;
        // $footer = "<table border='0' cellpadding='5' cellspacing='0' align='right'><tr><td><b>Anfragen:</b> $petitions[0] Ungelesen, $petitions[1] Gelesen, $petitions[2] Geschlossen.</td></tr></table>".$footer;
        addnav('', 'viewpetition.php');
    }
    $footer = str_replace("{stats}", charstats(), $footer);
    $header = str_replace("{stats}", charstats(), $header);
    $header = str_replace("{script}", $script, $header);
    if ($session[user][loggedin]) {
        $footer = str_replace("{source}", "<a href='source.php?url=" . preg_replace("/[?].*/", "", ($_SERVER['REQUEST_URI'])) . "' target='_blank'>Source</a>", $footer);
        $header = str_replace("{source}", "<a href='source.php?url=" . preg_replace("/[?].*/", "", ($_SERVER['REQUEST_URI'])) . "' target='_blank'>Source</a>", $header);
    }
    else {
        $footer = str_replace("{source}", "<a href='source.php' target='_blank'>Source</a>", $footer);
        $header = str_replace("{source}", "<a href='source.php' target='_blank'>Source</a>", $header);
    }
    $footer = str_replace("{impressum}", "<a href='impressum.php' target='_blank'>Impressum</a>", $footer);
        $footer = str_replace("{datenschutz}", "<a href='schutz.php' target='_blank'>Datenschutz</a>", $footer);
    $footer = str_replace("{copyright}", "Copyright 2002-2003, Game: Eric Stevens", $footer);
    $footer = str_replace("{version}", "Version: $logd_version", $footer);
    $gentime = getmicrotime() - $pagestarttime;
    $session[user][gentime] += $gentime;
    $session[user][gentimecount]++;
    $dbtimethishit = round($dbtimethishit, 2);
    // $footer=str_replace("{pagegen}","Seitengenerierung: ".round($gentime,2)."s, Schnitt: ".round($session[user][gentime]/$session[user][gentimecount],2)."s - ".round($session[user][gentime],2)."/".round($session[user][gentimecount],2)."".($session[user][superuser]>1?"; DB: $dbqueriesthishit in $dbtimethishit s":"")."",$footer);
    $footer = str_replace("{pagegen}", "Seitengenerierung: " . round($gentime, 2) . "s, Schnitt: " . round($session[user][gentime] / $session[user][gentimecount], 2) . "s" . ($session[user][superuser] > 1 ? "; DB: $dbqueriesthishit in " . $dbtimethishit . "s" : "") . "", $footer);
    if (strpos($_SERVER['HTTP_HOST'], "lotgd.net") !== false) {
        $footer = str_replace("</html>", '<script language="JavaScript" type="text/JavaScript" src="http://www.reinvigorate.net/archive/app.bin/jsinclude.php?5193"></script></html>', $footer);
    }

    $output = $header . $output . $footer;
    $session['user']['gensize'] += strlen($output);
    $session[output] = $output;
    saveuser();

    session_write_close();
    // `mpg123 -g 100 -q hit.mp3 2>&1 > /dev/null`;
    // echo compress_out($output);
    echo $output;
    exit();
}

function popup_bioheader($title = "Legend of the Green Dragon") {
    global $bioheader;
    $bioheader .= "<html><head><title>$title</title>";
    $bioheader .= preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("biopopup_head.html"));
    $bioheader .= "<body bgcolor='#111111' text='#CCCCCC'><table cellpadding=5 cellspacing=0 width='100%'><tr>
<td class='popupbioheader'><b>";
}

function popup_biofooter() {
    global $output, $nestedtags, $bioheader, $nav, $session;
    while (list($key, $val) = each($nestedtags)) {
        $output .= "</$key>";
        unset($nestedtags[$key]);
    }
    $output .= "</td></tr><tr><td bgcolor='#330000' align='center'>Copyright 2002, Eric Stevens</td></tr></table>";
    $output .= preg_replace("/<__(\w.+?)__>/e", "\$\\1", tparser("biobody.html"));
    $output .= "</body></html>";
    $output = $bioheader . $output;
    // $session[output]=$output;

    saveuser();
    echo $output;
    exit();
}

function popup_header($title = "Legend of the Green Dragon") {
    global $header;
    $header .= "<html><head><title>$title</title>";
    $header .= "<link href=\"newstyle.css\" rel=\"stylesheet\" type=\"text/css\">";
    $header .= "</head><body bgcolor='#000000' text='#CCCCCC'><table cellpadding=5 cellspacing=0 width='100%'>";
    $header .= "<tr><td class='popupheader'><b>$title</b></td></tr>";
    $header .= "<tr><td valign='top' width='100%'>";
}

function popup_footer() {
    global $output, $nestedtags, $header, $nav, $session;
    while (list($key, $val) = each($nestedtags)) {
        $output .= "</$key>";
        unset($nestedtags[$key]);
    }
    $output .= "</td></tr><tr><td bgcolor='#330000' align='center'>Copyright 2002, Eric Stevens</td></tr></table></body></html>";
    $output = $header . $output;
    saveuser();
    echo $output;
    exit();
}

function clearoutput() {
    global $output, $nestedtags, $header, $nav, $session;
    $session[allowednavs] = "";
    $output = "";
    unset($nestedtags);
    $header = "";
    $nav = "";
}

function soap($input) {
    if (getsetting("soap", 1)) {
        $sql = "SELECT * FROM nastywords";
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $search = $row['words'];
        $search = str_replace("a", '[a4@]', $search);
        $search = str_replace("l", '[l1!]', $search);
        $search = str_replace("i", '[li1!]', $search);
        $search = str_replace("e", '[e3]', $search);
        $search = str_replace("t", '[t7+]', $search);
        $search = str_replace("o", '[o0]', $search);
        $search = str_replace("s", '[sz$]', $search);
        $search = str_replace("k", 'c', $search);
        $search = str_replace("c", '[c(k]', $search);
        $start = "'(\s|\A)";
        $end = "(\s|\Z)'iU";
        $search = str_replace("*", "([[:alnum:]]*)", $search);
        $search = str_replace(" ", "$end $start", $search);
        $search = "$start" . $search . "$end";
        // echo $search;
        $search = split(" ", $search);
        // $input = " $input ";

        return preg_replace($search, "\\1`i$@#%`i\\2", $input);
    }
    else {
        return $input;
    }
}

function saveuser() {
    global $session, $dbqueriesthishit;
    // $cmd = date("Y-m-d H:i:s")." $dbqueriesthishit ".$_SERVER['REQUEST_URI'];
    // @exec("echo $cmd >> /home/groups/l/lo/lotgd/sessiondata/data/queryusage-".$session['user']['login'].".txt");
    if ($session[loggedin] && $session[user][acctid] != "") {
        // changes by Eliwood for www.silienta-logd.de geändert für das txtfilesystem
        $file = fopen('./cache/c' . $session['user']['acctid'] . '.txt', 'wb');
        fwrite($file, $session['output']);
        fclose($file);
        chmod('./cache/c' . $session['user']['acctid'] . '.txt', 0777);
        $session[user][allowednavs] = serialize($session[allowednavs]);
        $session[user][bufflist] = serialize($session[bufflist]);
        if (is_array($session[user][prefs]))
            $session[user][prefs] = serialize($session[user][prefs]);
        if (is_array($session[user][dragonpoints]))
            $session[user][dragonpoints] = serialize($session[user][dragonpoints]);
        // $session[user][laston] = date("Y-m-d H:i:s");
        $sql = "UPDATE accounts SET ";
        reset($session[user]);
        while (list($key, $val) = each($session[user])) {
            if (is_array($val)) {
                $sql .= "$key='" . addslashes(serialize($val)) . "', ";
            }
            else {
                $sql .= "$key='" . addslashes($val) . "', ";
            }
        }
        $sql = substr($sql, 0, strlen($sql) - 2);
        $sql .= " WHERE acctid = " . $session[user][acctid];
        db_query($sql);
    }
}

function createstring($array) {
    if (is_array($array)) {
        reset($array);
        while (list($key, $val) = each($array)) {
            $output .= rawurlencode(rawurlencode($key) . "\"" . rawurlencode($val)) . "\"";
        }
        $output = substr($output, 0, strlen($output) - 1);
    }
    return $output;
}

function createarray($string) {
    $arr1 = split("\"", $string);
    $output = array();
    while (list($key, $val) = each($arr1)) {
        $arr2 = split("\"", rawurldecode($val));
        $output[rawurldecode($arr2[0])] = rawurldecode($arr2[1]);
    }
    return $output;
}

function output_array($array, $prefix = "") {
    while (list($key, $val) = @each($array)) {
        $output .= $prefix . "[$key] = ";
        if (is_array($val)) {
            $output .= "array{\n" . output_array($val, $prefix . "[$key]") . "\n}\n";
        }
        else {
            $output .= $val . "\n";
        }
    }
    return $output;
}

function dump_item($item) {
    $output = "";
    if (is_array($item))
        $temp = $item;
    else
        $temp = unserialize($item);
    if (is_array($temp)) {
        $output .= "array(" . count($temp) . ") {<blockquote>";
        while (list($key, $val) = @each($temp)) {
            $output .= "'$key' = '" . dump_item($val) . "'`n";
        }
        $output .= "</blockquote>}";
    }
    else {
        $output .= $item;
    }
    return $output;
}

function addnews($news) {
    global $session;
    $sql = "INSERT INTO news(newstext,newsdate,accountid) VALUES ('" . addslashes($news) . "',NOW()," . $session[user][acctid] . ")";
    return db_query($sql) or die(db_error($link));
}

function checkday() {
    global $session, $revertsession, $REQUEST_URI;
    // output("`#`iChecking to see if you're due for a new day: ".$session[user][laston].", ".date("Y-m-d H:i:s")."`i`n`0");
    if ($session['user']['loggedin']) {
        output("<!--CheckNewDay()-->", true);
        if (is_new_day()) {
            $session = $revertsession;
            $session[user][restorepage] = $REQUEST_URI;
            $session[allowednavs] = array();
            addnav("", "newday.php");
            redirect("newday.php");
        }
    }
}

function is_new_day() {
    global $session;
    $t1 = gametime();
    $t2 = convertgametime(strtotime($session[user][lasthit]));
    $d1 = date("Y-m-d", $t1);
    $d2 = date("Y-m-d", $t2);
    if ($d1 != $d2) {
        return true;
    }
    else {
        return false;
    }
}

function getgametime() {
    // return date("g:i a",gametime());
    return date(getsetting('gametimeformat', 'g:i a'), gametime());
}

// Gamedate-Mod by Chaosmaker
function getgamedate() {
    $date = explode('-', getsetting('gamedate', '0005-01-01'));
    $find = array('%Y', '%y', '%m', '%n', '%d', '%j');
    $replace = array($date[0], sprintf('%02d', $date[0] % 100), sprintf('%02d', $date[1]), ( int )$date[1], sprintf('%02d', $date[2]), ( int )$date[2]);
    return str_replace($find, $replace, getsetting('gamedateformat', '%Y-%m-%d'));
}

function gametime() {
    $time = convertgametime(strtotime(date("r")));
    return $time;
}

function convertgametime($intime) {
    // Hehe, einen hamwa noch, einen hamwa noch: by JT & anpera
    $multi = getsetting("daysperday", 4);
    $offset = getsetting("gameoffsetseconds", 0);
    $fixtime = mktime(0, 0, 0 - $offset, date("m") - $multi, date("d"), date("Y"));
    $time = $multi * (strtotime(date("Y-m-d H:i:s", $intime)) - $fixtime);
    $time = strtotime(date("Y-m-d H:i:s", $time) . "+" . ($multi * date("I", $intime)) . " hour");
    $time = strtotime(date("Y-m-d H:i:s", $time) . "-" . date("I", $time) . " hour");
    $time = strtotime(date("Y-m-d H:i:s", $time) . "+" . (23 - $multi) . " hour");
    return $time;
}

function sql_error($sql) {
    global $session;
    return output_array($session) . "SQL = <pre>$sql</pre>" . db_error(LINK);
}

function ordinal($val) {
    $exceptions = array(1 => "ten", 2 => "ten", 3 => "ten", 11 => "ten", 12 => "ten", 13 => "ten");
    $x = ($val % 100);
    if (isset($exceptions[$x])) {
        return $val . $exceptions[$x];
    }
    else {
        $x = ($val % 10);
        if (isset($exceptions[$x])) {
            return $val . $exceptions[$x];
        }
        else {
            return $val . "ten";
        }
    }
}

function addcommentary() {
    global $HTTP_POST_VARS, $session, $REQUEST_URI, $HTTP_GET_VARS, $doublepost;
    $doublepost = 0;

    $section = $HTTP_POST_VARS['section'];
    $talkline = $HTTP_POST_VARS['talkline'];
    if ($HTTP_POST_VARS[insertcommentary][$section] !== NULL && trim($HTTP_POST_VARS[insertcommentary][$section]) != "") {
        $commentary = str_replace("`n", "", soap($HTTP_POST_VARS[insertcommentary][$section]));
        $y = strlen($commentary);
        for ($x = 0; $x < $y; $x++) {
            if (substr($commentary, $x, 1) == "`") {
                $colorcount++;
                if ($colorcount >= getsetting("maxcolors", 10)) {
                    $commentary = substr($commentary, 0, $x) . preg_replace("'[`].'", "", substr($commentary, $x));
                    $x = $y;
                }
                $x++;
            }
        }
        if (substr($commentary, 0, 2) == "/X")
            $talkline = "sagt";
        if (substr($commentary, 0, 1) != ":" && substr($commentary, 0, 2) != "::" && substr($commentary, 0, 3) != "/me" && substr($commentary, 0, 2) != "/X" && $session['user']['drunkenness'] > 0) {
            // drunk people shouldn't talk very straight.
            $straight = $commentary;
            $replacements = 0;
            while ($replacements / strlen($straight) < ($session['user']['drunkenness']) / 500) {
                $slurs = array("a" => "aa", "e" => "ee", "f" => "ff", "h" => "hh", "i" => "ij", "l" => "ll", "m" => "mm", "n" => "nn", "o" => "oo", "r" => "rr", "s" => "sh", "u" => "uu", "v" => "vv", "w" => "ww", "y" => "yy", "z" => "zz");
                if (e_rand(0, 9)) {
                    srand(e_rand());
                    $letter = array_rand($slurs);
                    $x = strpos(strtolower($commentary), $letter);
                    if ($x !== false && substr($comentary, $x, 5) != "*hic*" && substr($commentary, max($x - 1, 0), 5) != "*hic*" && substr($commentary, max($x - 2, 0), 5) != "*hic*" && substr($commentary, max($x - 3, 0), 5) != "*hic*" && substr($commentary, max($x - 4, 0), 5) != "*hic*") {
                        if (substr($commentary, $x, 1) != strtolower($letter))
                            $slurs[$letter] = strtoupper($slurs[$letter]);
                        else
                            $slurs[$letter] = strtolower($slurs[$letter]);
                        $commentary = substr($commentary, 0, $x) . $slurs[$letter] . substr($commentary, $x + 1);
                        $replacements++;
                    }
                }
                else {
                    $x = e_rand(0, strlen($commentary));
                    if (substr($commentary, $x, 5) == "*hic*") {
                        $x += 5;
                    }// output("moved 5 to $x ");
                    if (substr($commentary, max($x - 1, 0), 5) == "*hic*") {
                        $x += 4;
                    }// output("moved 4 to $x ");
                    if (substr($commentary, max($x - 2, 0), 5) == "*hic*") {
                        $x += 3;
                    }// output("moved 3 to $x ");
                    if (substr($commentary, max($x - 3, 0), 5) == "*hic*") {
                        $x += 2;
                    }// output("moved 2 to $x ");
                    if (substr($commentary, max($x - 4, 0), 5) == "*hic*") {
                        $x += 1;
                    }// output("moved 1 to $x ");
                    $commentary = substr($commentary, 0, $x) . "*hic*" . substr($commentary, $x);
                    // output($commentary."`n");
                    $replacements++;
                } // end if
            }// end while
            // output("$replacements replacements (".($replacements/strlen($straight)).")`n");
            while (strpos($commentary, "*hic**hic*"))
                $commentary = str_replace("*hic**hic*", "*hic*hic*", $commentary);
        }// end if
        // //////////////////////////////////////////
        // - Schneeballmod
        // - Idee & Umsetzung: Cassandra (cassandra@leensworld.de)
        // - Grundlage: rpcmd-Mod von Hadriel
        // - Bedarf: Weihnachtsspecial von Cassandra/Leen (www.anpera.net)
        // - Chatbefehl: /schball 'Name'
        // //////////////////////////////////////////
        if ((substr($commentary, 0, 8) == '/schball')) {
            $intro = explode(' ', $commentary, 2);
            $datum = getsetting('weihnacht', '');
            if ($datum != 0)
                $frage = 1;
            else
                $frage = 0;
            $name1 = strtolower($intro[1]);
            $name2 = strtolower($session['user']['login']);
            if ($frage == 1) {
                if ($session['user']['schneeball'] < 20)// <- Menge festlegen
                {
                    if (!empty($intro[1]) && $name1 != $name2) {
                        $res = db_query('SELECT name,loggedin,laston FROM accounts WHERE login = "' . $intro[1] . '" AND location=0 AND laston>"' . date("Y-m-d H:i:s", strtotime(date("r") . "-" . getsetting("LOGINTIMEOUT", 900) . " seconds")) . '"');
                        $row = db_fetch_assoc($res);
                        if ($row['loggedin'] > 0) {
                            $name = $row['name'];
                            $result1 = db_query('SELECT schneerang,schneeball FROM accounts WHERE acctid = ' . $session['user']['acctid'] . '');
                            $row1 = db_fetch_assoc($result1);
                            switch (e_rand ( 1, 5 )) {
                                case 1 :
                                case 2 :
                                case 3 :
                                    $commentary = '/me `&trifft `#' . $name . '`& mit einem Schneeball und macht damit `#1 Punkt!';
                                    $session['user']['schneerang']++;
                                    $session['user']['schneeball']++;
                                    $schneerang = $row1['schneerang'] + 1;
                                    $schneeball = $row1['schneeball'] + 1;
                                    output('`b`4Schneeball geworfen ' . $intro[1] . ' - Treffer, + 1 Punkt, Punkte insgesamt: ' . $schneerang . ' , Schneebälle heute: ' . $schneeball . '`b`n<hr>', true);
                                    break;
                                case 4 :
                                    $commentary = '/me `&trifft `#' . $name . '`& mit einem Schneeball sehr gut und macht damit `#2 Punkte!';
                                    $session['user']['schneerang'] += 2;
                                    $session['user']['schneeball']++;
                                    $schneerang = $row1['schneerang'] + 2;
                                    $schneeball = $row1['schneeball'] + 1;
                                    output('`b`4Schneeball geworfen ' . $intro[1] . ' - Treffer, + 2 Punkt, Punkte insgesamt: ' . $schneerang . ', Schneebälle heute: ' . $schneeball . '`b`n<hr>', true);
                                    break;
                                case 5 :
                                    $commentary = '/me `&versucht `#' . $name . '`& mit einem Schneeball zu treffen, wirft aber daneben - das macht `#einen Minuspunkt!';
                                    $session['user']['schneerang']--;
                                    $session['user']['schneeball']++;
                                    $schneerang = $row1['schneerang'] - 1;
                                    $schneeball = $row1['schneeball'] + 1;
                                    output('`b`4Schneeball geworfen ' . $intro[1] . ' - kein Treffer, - 1 Punkt, Punkte insgesamt: ' . $schneerang . ', Schneebälle heute: ' . $schneeball . '`b`n<hr>', true);
                                    break;
                            }
                        }
                        else {
                            output('`b`4Fehler: ' . $intro[1] . ': Nicht anwesend!`b`n<hr>', true);
                            $commentary = '/me `#hat versucht einen Geist zu treffen!';
                        }
                    }
                    else {
                        output('`b`4Fehler: Du kannst dich nicht selbst bewerfen!`b`n<hr>', true);
                        $commentary = '/me `#hat versucht sich selbst zu treffen!';
                    }
                }
                else {
                    output('`b`4Fehler: Du kannst keinen einzigen Ball mehr für heute werden!`b`n<hr>', true);
                    $commentary = '/me `#ist zu schwach um den Schneeball zu werfen!';
                }
            }
            else if ($frage == 0) {
                output('`b`4Fehler: Du kannst nur zu Weihnachten dieses Tool nutzen!`b`n<hr>', true);
                $commentary = '/me `#hat versucht ohne Schnee einen Schneeball zu werfen!';
            }
        }
        $commentary = preg_replace("'([^[:space:]]{45,45})([^[:space:]])'", "\\1 \\2", $commentary);
        if ($session['user']['drunkenness'] > 50)
            $talkline = "lallt";
        $talkline = translate($talkline);
        if (substr($commentary, 0, 1) == ':' || substr($commentary, 0, 3) == '/me') {
            if (substr($commentary, 0, 3) == '/me')
                $strpos = 3;
            elseif (substr($commentary, 0, 2) == '::')
                $strpos = 2;
            else
                $strpos = 1;
            if ($session['user']['prefs']['commentemotecolor'])
                $commentary = substr($commentary, 0, $strpos) . $session['user']['prefs']['commentemotecolor'] . substr($commentary, $strpos);
        }
        else {
            if ($session['user']['prefs']['commenttalkcolor'])
                $commentary = $session['user']['prefs']['commenttalkcolor'] . $commentary;
        }
        if ($talkline != "sagt" && // do an emote if the area has a custom talkline and the user isn't trying to emote already.
            substr($commentary, 0, 1) != ":" && substr($commentary, 0, 2) != "::" && substr($commentary, 0, 2) != "/X" && substr($commentary, 0, 3) != "/me")
            $commentary = ":`3$talkline: \\\"`#$commentary`3\\\"";
        $sql = "SELECT commentary.comment,commentary.author FROM commentary WHERE section='$section' ORDER BY commentid DESC LIMIT 1";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        db_free_result($result);
        if ($row[comment] != stripslashes($commentary) || $row[author] != $session[user][acctid]) {
            // if ($row[comment]!=$commentary || $row[author]!=$session[user][acctid]){
            $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'$section'," . $session[user][acctid] . ",\"$commentary\")";
            db_query($sql) or die(db_error(LINK));
            $length = strlen($commentary);
            $session['user']['rppunkte'] += 1;
            $session['user']['rppunkte'] += floor($length / 100);
            require_once ('rpg.php');
            return true;
        }
        else {
            $doublepost = 1;
        }
    }
    return false;
}

function viewcommentary($section, $message = "Kommentar hinzufügen?", $limit = 10, $talkline = "sagt") {
    global $HTTP_POST_VARS, $session, $REQUEST_URI, $HTTP_GET_VARS, $doublepost;
    if ($session[user][prefs][rplimit] > 0) {
        $limit = $session[user][prefs][rplimit];
    }
    else {
        $limit = 15;
    }
    $nobios = array("motd.php" => true);
    if ($nobios[basename($_SERVER['SCRIPT_NAME'])])
        $linkbios = false;
    else
        $linkbios = true;
    // output("`b".basename($_SERVER['SCRIPT_NAME'])."`b`n");
    if ($doublepost)
        output("`\$`bDoppelpost?`b`0`n");
    $message = translate($message);

    $com = ( int )$HTTP_GET_VARS[comscroll];
    $rmax = db_query("SELECT count(commentid) AS c FROM commentary WHERE section='" . $section . "'");
    $rmax = db_fetch_assoc($rmax);
    $rmax = ($rmax[c]) / ($limit);
    $rmax = floor($rmax);
    $sql = "SELECT commentary.*,
accounts.name, 
                   accounts.login, 
                   accounts.prefs, 
                   accounts.loggedin, 
                   accounts.location, 
                   accounts.laston, 
                   accounts.memberid, 
                   accounts.acctid, 
                   gilden.gildenprefix, 
                   gilden.gildenid, 
                   gilden.leaderid 
              FROM commentary 
             INNER JOIN accounts 
                ON accounts.acctid = commentary.author 
             LEFT JOIN gilden 
                ON gilden.leaderid = accounts.acctid OR gilden.gildenid = accounts.memberid 
             WHERE section = '$section' 
               AND accounts.locked=0 
             ORDER BY commentid DESC 
             LIMIT ".($com*$limit).",$limit"; 
    $result = db_query($sql) or die(db_error(LINK));
    $counttoday = 0;
    for ($i = 0; $i < db_num_rows($result); $i++) {
        $row = db_fetch_assoc($result);
        $row[comment] = preg_replace("'[`][^123456789!@#$%&QqRr*~^?VvGgTtAaWwEeZzUuIiOoPpSsDdFfJjYyXxMmKkLl-áàâéèêíìòÁÀ)ÄäB(hXï-]ö'", "", $row[comment]);
        // eingefügt WwEeZzUuIiOoPpSsDdFfJjYyXxMmKkLl-áàâéèêíìòÁÀ
        $commentids[$i] = $row[commentid];
        /* Who wrote? */
        $authors[$i] = $row['name'];
        /*
         * limit posts if (date("Y-m-d",strtotime($row[postdate]))==date("Y-m-d")){ // if ($row[name]==$session[user][name] && substr($section,0,5)!="house") $counttoday++; }
         */
        $x = 0;
        $ft = "";
        if ($session['user']['prefs']['timestamps'])
            $timest = "`0[" . date("H:i", strtotime($row['postdate'])) . "] ";
        // Ellalith
        for ($x = 0; strlen($ft) < 3 && $x < strlen($row[comment]); $x++) {
            if (substr($row[comment], $x, 1) == "`" && strlen($ft) == 0) {
                $x++;
            }
            else {
                $ft .= substr($row[comment], $x, 1);
            }
        }
        if ($session[user][prefs][oldBio] == 1) {
            $link = "biopopup_backup.php?char=" . rawurlencode($row[login]) . "&ret=" . URLEncode($_SERVER['REQUEST_URI']);
            $link2 = "`~[`0<a href='showdetail.php?id=".$row['gildenid']."' target='window_popup' onClick=\"".popup("showdetail.php?id=".$row['gildenid'])."; return false;\">`&".stripslashes($row[gildenprefix])."`&</a>`~]";
        }
        else {
            $link = "biopopup.php?char=" . rawurlencode($row[login]) . "&ret=" . URLEncode($_SERVER['REQUEST_URI']);
            $link2 = "`~[`0<a href='showdetail.php?id=".$row['gildenid']."' target='window_popup' onClick=\"".popup("showdetail.php?id=".$row['gildenid'])."; return false;\">`&".stripslashes($row[gildenprefix])."`&</a>`~]";
        }
        if (substr($ft, 0, 2) == "::")
            $ft = substr($ft, 0, 2);
        else if (substr($ft, 0, 2) == "/X")
            $ft = substr($ft, 0, 2);
        else if (substr($ft, 0, 1) == ":")
            $ft = substr($ft, 0, 1);
        /*
         * Landschafts-Emote by Eliwood
         */
        if ($ft == "/X") {
            $x = strpos($row[comment], $ft);
            if ($x !== false) {
                if ($linkbios)
                    $op[$i] = $timest . str_replace("&amp;", "&", HTMLSpecialChars(substr($row['comment'], 0, $x))) . // Ellalith
                    "`0\n`& " . str_replace("&amp;", "&", HTMLSpecialChars(substr($row[comment], $x + strlen($ft)))) . "`0`n";
                else
                    $op[$i] = $timest . str_replace("&amp;", "&", HTMLSpecialChars(substr($row['comment'], 0, $x))) . // Ellalith
                    "`0\n`& " . str_replace("&amp;", "&", HTMLSpecialChars(substr($row[comment], $x + strlen($ft)))) . "`0`n";
            }
            // Stealthwatcher by Wraith
            if ($session[user][prefs][xmote] == 1) {
                if ($session[user][superuser] >= 3) {
                    $op[$i] .= "von <a href='$link'>`&$row[name]</a>`n";
                    $session[allowednavs][$link] = true;
                }
            } /* Landschaft-Emote Ende */
        }
        elseif ($ft == "::" || $ft == "/me" || $ft == ":") {
            $x = strpos($row[comment], $ft);
            if ($x !== false) {
                if ($linkbios)
                    $op[$i] = $timest . str_replace("&amp;", "&", HTMLSpecialChars(substr($row['comment'], 0, $x))) . // Ellalith
                    "`0".$link2."<a href=\"$link\" style='text-decoration: none' target=\"_blank\" onClick=\"" . popupbio($link) . ";return false;\"> \n`&$row[name]`0</a>\n`& " . str_replace("&amp;", "&", HTMLSpecialChars(substr($row[comment], $x + strlen($ft)))) . "`0`n";
                else
                    $op[$i] = $timest . str_replace("&amp;", "&", HTMLSpecialChars(substr($row['comment'], 0, $x))) . // Ellalith
                    "`0\n`&$row[name]`0\n`& " . str_replace("&amp;", "&", HTMLSpecialChars(substr($row[comment], $x + strlen($ft)))) . "`0`n";
            }
        }
        if ($op[$i] == "")
            if ($linkbios)
                $op[$i] = $timest . "`0".$link2."<a href=\"$link\" style='text-decoration: none' target=\"_blank\" onClick=\"" . popupbio($link) . ";return false;\"> \n`&$row[name]`0</a>`3 sagt: \"`#" . // Ellalith
                str_replace("&amp;", "&", HTMLSpecialChars($row[comment])) . "`3\"`0`n";
            else
                $op[$i] = $timest . "`0`&{$row['name']}`0`3 sagt: \"`#" . // Ellalith
                str_replace("&amp;", "&", HTMLSpecialChars($row[comment])) . "`3\"`0`n";
        if ($message == "X")
            $op[$i] = "`0($row[section]) " . $op[$i];
        $loggedin = (date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT", 900) && $row[loggedin] && $row[location] == 0);
        if ($row['postdate'] >= $session['user']['recentcomments'])
            $op[$i] = ($loggedin ? "<img src='images/new-online.gif' alt='&gt;' width='3' height='5' align='absmiddle'> " : "<img src='images/new.gif' alt='&gt;' width='3' height='5' align='absmiddle'> ") . $op[$i];
        // addnav("",$link);
    }
    $i--;
    $outputcomments = array();
    $sect = "x";
    for (; $i >= 0; $i--) {
        $out = "<div id='rp_play'>";
        if ($session[user][superuser] >= 3 && $message == "X") {
            $out .= "`0[ <a href='superuser.php?op=commentdelete&commentid=$commentids[$i]&return=" . URLEncode($_SERVER['REQUEST_URI']) . "'>Löschen</a> ]" . $authors[$i] . ":&nbsp;";
            addnav("", "superuser.php?op=commentdelete&commentid=$commentids[$i]&return=" . URLEncode($_SERVER['REQUEST_URI']));
            $matches = array();
            preg_match("/[(][^)]*[)]/", $op[$i], $matches);
            $sect = $matches[0];
        }
        // output($op[$i],true);
        $out .= $op[$i];
        $out .= '</div>';
        if (!is_array($outputcomments[$sect]))
            $outputcomments[$sect] = array();
        array_push($outputcomments[$sect], $out);
    }
    ksort($outputcomments);
    reset($outputcomments);
    while (list($sec, $v) = each($outputcomments)) {
        if ($sec != "x")
            output("`n`b$sec`b`n");
        output(implode('', $v), true);
    }

    if ($session[user][loggedin]) {
        // if ($counttoday<($limit/2) || $session['user']['superuser']>=2){
        if ($message != "X") {

            if ($talkline != "says")
                $tll = strlen($talkline) + 11;
            else

                $tll = 0;
            // chat preview mod by Chaosmaker
            output("<form action=\"$REQUEST_URI\" method='POST'>`@$message`n<textarea cols='80' rows='4' name='insertcommentary[$section]'
onkeyup=\"document.getElementById('chatpreview').innerHTML = appoencode(this.value);\"></textarea><input type='hidden'
name='talkline' value='$talkline'><input type='hidden' name='section' value='$section'><br>
<input type='submit' class='button' value='Hinzufügen'><span id='chatpreview'></span>`n" . (round($limit / 2, 0) - $counttoday < 3 ? "`((Du hast noch " . (round($limit / 2, 0) - $counttoday) . " Beiträge für heute übrig)" : "") . "`0`n</form>", true);
            addnav("", $REQUEST_URI);
        }
        // }else{
        // output("`@$message`nSorry, du hast deine Beiträge in dieser Region für heute aufgebraucht.`0`n");
        // }
    }
    if ($com != $rmax) {
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'", "", $REQUEST_URI) . "&comscroll=" . $rmax;
        // $req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$_GET[c]"."&comscroll=".($com-1);
        $req = str_replace("?&", "?", $req);
        if (!strpos($req, "?"))
            $req = str_replace("&", "?", $req);
        // output(" <a href=\"$req\">Next &gt;&gt;</a>",true);
        output(" <a href=\"$req\">&lt;&lt;&lt; Letzte </a>", true);
        addnav("", $req);
    }
    if (db_num_rows($result) >= $limit) {
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]-])*'", "", $REQUEST_URI) . "&comscroll=" . ($com + 1);
        // $req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$HTTP_GET_VARS[c]"."&comscroll=".($com+1);
        $req = str_replace("?&", "?", $req);
        if (!strpos($req, "?"))
            $req = str_replace("&", "?", $req);
        output("<a href=\"$req\">&lt;&lt; Vorherige</a>", true);
        addnav("", $req);
    }
    $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'", "", $REQUEST_URI) . "&comscroll=0";
    // $req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$HTTP_GET_VARS[c]"."&comscroll=".($com-1);
    $req = str_replace("?&", "?", $req);
    if (!strpos($req, "?"))
        $req = str_replace("&", "?", $req);
    output("&nbsp;<a href=\"$req\">Aktualisieren</a>&nbsp;", true);
    addnav("", $req);
    if ($com > 0) {
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'", "", $REQUEST_URI) . "&comscroll=" . ($com - 1);
        // $req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$HTTP_GET_VARS[c]"."&comscroll=".($com-1);
        $req = str_replace("?&", "?", $req);
        if (!strpos($req, "?"))
            $req = str_replace("&", "?", $req);
        output(" <a href=\"$req\">Nächste &gt;&gt;</a>", true);
        addnav("", $req);
    }
    output("<a href=\"chatdelete.php?op=edit&section=" . $section . "&restore=$REQUEST_URI\">`\$&lt;&lt;Editieren&gt;&gt;`0</a>", true);
    addnav("", "chatdelete.php?op=edit&section=" . $section . "&restore=$REQUEST_URI");
    if ($com != 0) {
        $req = preg_replace("'[&]?c(omscroll)?=([[:digit:]]|-)*'", "", $REQUEST_URI) . "&comscroll=0";
        // $req = substr($REQUEST_URI,0,strpos($REQUEST_URI,"c="))."&c=$_GET[c]"."&comscroll=".($com-1);
        $req = str_replace("?&", "?", $req);
        if (!strpos($req, "?"))
            $req = str_replace("&", "?", $req);
        // output(" <a href=\"$req\">Next &gt;&gt;</a>",true);
        output(" <a href=\"$req\">Erste &gt;&gt;&gt;</a>", true);
        addnav("", $req);
    }
    db_free_result($result);
}

function dhms($secs, $dec = false) {
    if ($dec === false)
        $secs = round($secs, 0);
    return ( int )($secs / 86400) . "d" . ( int )($secs / 3600 % 24) . "h" . ( int )($secs / 60 % 60) . "m" . ($secs % 60) . ($dec ? substr($secs - ( int )$secs, 1) : "") . "s";
}

function getmount($horse = 0) {
    $sql = "SELECT * FROM mounts WHERE mountid='$horse'";
    $result = db_query($sql);
    if (db_num_rows($result) > 0) {
        return db_fetch_assoc($result);
    }
    else {
        return array();
    }
}

function debuglog($message, $target = 0) {
    global $session;
    $sql = "DELETE from debuglog WHERE date <'" . date("Y-m-d H:i:s", strtotime(date("r") . "-" . (getsetting("expirecontent", 180) / 10) . " days")) . "'";
    db_query($sql);
    $sql = "INSERT INTO debuglog VALUES(0,now(),{$session['user']['acctid']},$target,'" . addslashes($message) . "')";
    db_query($sql);
}

// exp bar mod coded by: dvd871 with modifications by: anpera
function expbar() {
    global $session;
    $exparray = array(1 => 100, 400, 1002, 1912, 3140, 4707, 6641, 8985, 11795, 15143, 19121, 23840, 29437, 36071, 43930, 55000);
    while (list($key, $val) = each($exparray)) {
        $exparray[$key] = round($val + ($session['user']['dragonkills'] / 4) * $key * 100, 0);
    }
    $exp = $session[user][experience] - $exparray[$session[user][level] - 1];
    $req = $exparray[$session[user][level]] - $exparray[$session[user][level] - 1];
    $u = "<font face=\"verdana\" size=1>" . $session[user][experience] . "<br>" . grafbar($req, $exp) . "</font>";
    return ($u);
}

// end exp bar mod
function grafbar($full, $left, $width = 70, $height = 5) {
    $col2 = "#000000";
    if ($left <= 0) {
        $col = "#000000";
    }
    else if ($left < $full / 4) {
        $col = "#FF0000";
    }
    else if ($left < $full / 2) {
        $col = "yellow";
    }
    else if ($left >= $full) {
        $col = "#5858FA";
        $col2 = "#5858FA";
    }
    else {
        $col = "#00FF00";
    }
    if ($full == 0)
        $full = 1;
    $u = "<table cellspacing=\"0\" style=\"border: solid 1px #000000\" width=\"$width\" height=\"$height\"><tr><td width=\"" . ($left / $full * 100) . "%\" bgcolor=\"$col\"></td><td width=\"" . (100 - ($left / $full * 100)) . "%\" bgcolor=\"$col2\"></td></tr></table>";
    return ($u);
}

function tparser($tmplname) {
    $lines = implode("", file($tmplname));

    return $lines;
}

function masterchase() {
    if ($session['user']['einlass'] == 1) {
        if (getsetting("automaster", 1) && $session['user']['seenmaster'] != 1) {
            // masters hunt down truant students
            $exparray = array(1 => 100, 400, 1002, 1912, 3140, 4707, 6641, 8985, 11795, 15143, 19121, 23840, 29437, 36071, 43930, 55000);
            while (list($key, $val) = each($exparray)) {
                $exparray[$key] = round($val + ($session['user']['dragonkills'] / 4) * $session['user']['level'] * 100, 0);
            }
            $expreqd = $exparray[$session['user']['level'] + 1];
            if ($session['user']['experience'] > $expreqd && $session['user']['level'] < 15) {
                redirect("train.php?op=autochallenge");
            }
            else if ($session['user']['experience'] > $expreqd && $session['user']['level'] >= 15) {
                redirect("dragon.php?op=autochallenge");
            }
        }
    }
}

if (file_exists("dbconnect.php")) {
    require_once "dbconnect.php";
}
else {
    echo "Du must die benötigten Informationen in die Datei \"dbconnect.php.dist\" eintragen und sie unter dem Namen \"dbconnect.php\" speichern." . exit();
}

$link = db_pconnect($DB_HOST, $DB_USER, $DB_PASS) or die(db_error($link));
db_select_db($DB_NAME) or die(db_error($link));
define("LINK", $link);

require_once "translator.php";

session_register("session");
function register_global(&$var) {
    @reset($var);
    while (list($key, $val) = @each($var)) {
        global $$key;
        $$key = $val;
    }

    @reset($var);
}

$session = &$_SESSION['session'];
// echo nl2br(HTMLSpecialChars(output_array($session)));
// register_global($_SESSION);
register_global($_SERVER);

if (strtotime(date("r") . "-" . getsetting("LOGINTIMEOUT", 900) . " seconds") > $session['lasthit'] && $session['lasthit'] > 0 && $session[loggedin]) {
    // force the abandoning of the session when the user should have been sent to the fields.
    // echo "Session abandon:".(strtotime("now")-$session[lasthit]);

    $session = array();
    $session['message'] .= "`nDeine Session ist abgelaufen!`n";
}
$session[lasthit] = strtotime(date("r"));

$revertsession = $session;

if ($PATH_INFO != "") {
    $SCRIPT_NAME = $PATH_INFO;
    $REQUEST_URI = "";
}
if ($REQUEST_URI == "") {
    // necessary for some IIS installations (CGI in particular)
    if (is_array($_GET) && count($_GET) > 0) {
        $REQUEST_URI = $SCRIPT_NAME . "?";
        reset($_GET);
        $i = 0;
        while (list($key, $val) = each($_GET)) {
            if ($i > 0)
                $REQUEST_URI .= "&";
            $REQUEST_URI .= "$key=" . URLEncode($val);
            $i++;
        }
    }
    else {
        $REQUEST_URI = $SCRIPT_NAME;
    }
    $_SERVER['REQUEST_URI'] = $REQUEST_URI;
}
$SCRIPT_NAME = substr($SCRIPT_NAME, strrpos($SCRIPT_NAME, "/") + 1);
if (strpos($REQUEST_URI, "?")) {
    $REQUEST_URI = $SCRIPT_NAME . substr($REQUEST_URI, strpos($REQUEST_URI, "?"));
}
else {
    $REQUEST_URI = $SCRIPT_NAME;
}

$allowanonymous = array("index.php" => true, "login.php" => true, "create.php" => true, "about.php" => true, "list.php" => true, "petition.php" => true, "connector.php" => true, "logdnet.php" => true, "referral.php" => true, "news.php" => true, "motd.php" => true, "topwebvote.php" => true, "source.php" => true);
$allownonnav = array("badnav.php" => true, "showdetail.php" => true, "buff_tut.php" => true, "motd.php" => true, "petition.php" => true, "mail.php" => true, "topwebvote.php" => true, "chat.php" => true, "source.php" => true, "list_popup.php" => true, "biopopup.php" => true, "biopopup_backup.php" => true);
if ($session[loggedin]) {
    $sql = "SELECT * FROM accounts WHERE acctid = '" . $session[user][acctid] . "'";
    $result = db_query($sql);
    if (db_num_rows($result) == 1) {
        $session[user] = db_fetch_assoc($result);
        $session['output'] = file_get_contents('./cache/c' . $session['user']['acctid'] . '.txt');
        // by Eliwood
        $session[user][dragonpoints] = unserialize($session[user][dragonpoints]);
        $session[user][prefs] = unserialize($session[user][prefs]);
        if (!is_array($session[user][dragonpoints]))
            $session[user][dragonpoints] = array();
        if (is_array(unserialize($session[user][allowednavs]))) {
            $session[allowednavs] = unserialize($session[user][allowednavs]);
        }
        else {
            // depreciated, left only for legacy support.
            $session[allowednavs] = createarray($session[user][allowednavs]);
        }
        if (!$session[user][loggedin] || (0 && (date("U") - strtotime($session[user][laston])) > getsetting("LOGINTIMEOUT", 900))) {
            $session = array();
            redirect("index.php?op=timeout", "Account ist nicht eingeloggt, aber die Session denkt, er ist es.");
        }
    }
    else {
        $session = array();
        $session[message] = "`4Fehler! Dein Login war falsch.`0";
        redirect("index.php", "Account verschwunden!");
    }
    db_free_result($result);
    if ($session[allowednavs][$REQUEST_URI] && !$allownonnav[$SCRIPT_NAME]) {
        $session[allowednavs] = array();
    }
    else {
        if (!$allownonnav[$SCRIPT_NAME]) {
            redirect("badnav.php", "Navigation auf $REQUEST_URI nicht erlaubt");
        }
    }
}
else {
    // if ($SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
    if (!$allowanonymous[$SCRIPT_NAME]) {
        $session['message'] = "Du bist nicht eingeloggt. Wahrscheinlich ist deine Sessionzeit abgelaufen.";
        redirect("index.php?op=timeout", "Not logged in: $REQUEST_URI");
    }
}
// if ($session[user][loggedin]!=true && $SCRIPT_NAME!="index.php" && $SCRIPT_NAME!="login.php" && $SCRIPT_NAME!="create.php" && $SCRIPT_NAME!="about.php"){
if ($session[user][loggedin] != true && !$allowanonymous[$SCRIPT_NAME]) {
    redirect("login.php?op=logout");
}

$session[counter]++;
$nokeeprestore = array("newday.php" => 1, "badnav.php" => 1, "motd.php" => 1, "mail.php" => 1, "petition.php" => 1, "chat.php" => 1, "list_popup.php" => 1, "biopopup.php" => 1, "biopopup_backup.php" => true);
if (!$nokeeprestore[$SCRIPT_NAME]) {// strpos($REQUEST_URI,"newday.php")===false && strpos($REQUEST_URI,"badnav.php")===false && strpos($REQUEST_URI,"motd.php")===false && strpos($REQUEST_URI,"mail.php")===false
    $session[user][restorepage] = $REQUEST_URI;
}
else {
}

if ($session['user']['hitpoints'] > 0) {
    $session['user']['alive'] = true;
}
else {
    $session['user']['alive'] = false;
}

$session[bufflist] = unserialize($session[user][bufflist]);
if (!is_array($session[bufflist]))
    $session[bufflist] = array();
$session[user][lastip] = $REMOTE_ADDR;
if (strlen($_COOKIE[lgi]) < 32) {
    if (strlen($session[user][uniqueid]) < 32) {
        $u = md5(microtime());
        setcookie("lgi", $u, strtotime(date("r") . "+365 days"));
        $_COOKIE['lgi'] = $u;
        $session[user][uniqueid] = $u;
    }
    else {
        setcookie("lgi", $session[user][uniqueid], strtotime(date("r") . "+365 days"));
    }
}
else {
    $session[user][uniqueid] = $_COOKIE[lgi];
}
$url = "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']);
$url = substr($url, 0, strlen($url) - 1);

if (substr($_SERVER['HTTP_REFERER'], 0, strlen($url)) == $url || $_SERVER['HTTP_REFERER'] == "") {
}
else {
    $sql = "SELECT * FROM referers WHERE uri='{$_SERVER['HTTP_REFERER']}'";
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    db_free_result($result);
    $site = str_replace("http://", "", $_SERVER['HTTP_REFERER']);
    if (strpos($site, "/"))
        $site = substr($site, 0, strpos($site, "/"));
    if ($row['refererid'] > "") {
        $sql = "UPDATE referers SET count=count+1,last=now(),site='" . addslashes($site) . "' WHERE refererid='{$row['refererid']}'";
    }
    else {
        $sql = "INSERT INTO referers (uri,count,last,site) VALUES ('{$_SERVER['HTTP_REFERER']}',1,now(),'" . addslashes($site) . "')";
    }
    db_query($sql);
}
if ($_COOKIE['template'] != "")
    $templatename = $_COOKIE['template'];
if ($templatename == "" || !file_exists("templates/$templatename"))
    $templatename = "shadow.htm";
$template = loadtemplate($templatename);
// tags that must appear in the header
$templatetags = array("title", "headscript", "script");
foreach ($templatetags as $val) {
    if (strpos($template['header'], "{" . $val . "}") === false)
        $templatemessage .= "You do not have {" . $val . "} defined in your header\n";
}
// tags that must appear in the footer
$templatetags = array();
foreach ($templatetags as $val) {
    if (strpos($template['footer'], "{" . $val . "}") === false)
        $templatemessage .= "You do not have {" . $val . "} defined in your footer\n";
}
// tags that may appear anywhere but must appear
// touch the copyright and we will force your server to be shut down
$templatetags = array("nav", "stats", "petition", "motd", "mail", "paypal", "copyright", "source");
foreach ($templatetags as $val) {
    if (strpos($template['header'], "{" . $val . "}") === false && strpos($template['footer'], "{" . $val . "}") === false)
        $templatemessage .= "You do not have {" . $val . "} defined in either your header or footer\n";
}
if ($templatemessage != "") {
    echo "<b>Du hast einen oder mehrere Fehler in deinem Template!</b><br>" . nl2br($templatemessage);
    $template = loadtemplate("shadow.htm");
}

$races = array(1 => "Troll", 2 => "Elf", 3 => "Mensch", 4 => "Zwerg", 5 => "Echse", 0 => "Unbekannt", 50 => "Hoverschaf");
$colraces = array(1 => "`2Troll`0", 2 => "`^Elf`0", 3 => "`0Mensch", 4 => "`#Zwerg`0", 5 => "`5Echse`0", 0 => "`(Unbekannt`0", 50 => "Hoverschaf");

$logd_version = "0.9.7+jt ext (GER)";
$session['user']['laston'] = date("Y-m-d H:i:s");

$playermount = getmount($session['user']['hashorse']);

// Strafregister von Thibaud Roth
$penal_record_type = array(1 => "Verwarnung wegen Spam an öffentlichen Plätzen", 2 => "Verwarnung wegen Sonstigem");

$titles = array(0 => array("Fremder", "Fremde"), 1 => array("`fS`3a`Fn`#d`jsi`#e`Fb`3e`fr", "`fS`3a`Fn`#d`jsieb`#e`Fr`3i`fn"), 2 => array("`fS`3t`Fr`#eu`Fn`3e`fr", "`fS`3t`Fr`#e`jun`#e`Fr`3i`fn"), 3 => array("`fH`3a`Fr`#le`Fk`3i`fn", "`fH`3e`Fl`#l`je`#q`Fu`3i`fn"), 4 => array("`fG`3l`Fü`#c`jksverkä`#u`Ff`3e`fr ", "`fG`3l`Fü`#c`jksverkäuf`#e`Fr`3i`fn"), 5 => array("`fO`3b`Fd`#a`jchl`#o`Fs`3e`fr", "`fO`3b`Fd`#a`jch`#l`Fo`3s`fe"), 6 => array("`fG`3e`Fp`#r`jüfter Handtuchtroc`#k`Fn`3e`fr", "`fG`3e`Fp`#r`jüfte Handtuchtrockn`#e`Fr`3i`fn "), 7 => array("`fK`3n`Fec`3h`ft ", "`fM`3ag`fd"), 8 => array("`fH`3o`Ff`#fn`Fa`3r`fr", "`fH`3o`Ff`#n`jä`#r`Fr`3i`fn"), 9 => array("`fD`3i`Fen`3e`fr", "`fD`3i`Fe`#n`j`#e`Fr`3i`fn"), 10 => array("`fK`3i`Fr`#s`jchenentste`#i`Fn`3e`fr", "`fK`3i`Fr`#s`jchenentstein`#e`Fr`3i`fn"), 11 => array("`fM`3u`Fn`#d`jsc`#h`Fe`3n`fk", "`fM`3u`Fn`#d`jsche`#n`Fk`3i`fn"), 12 => array("`fB`3o`Ft`#e", "`fB`3o`Ft`3i`fn"), 13 => array("`fV`3e`Fr`#g`jissmeinnichtzuste`#l`Fl`3e`fr", "`fV`3e`Fr`#g`jissmeinnichtzustell`#e`Fr`3i`fn"), 14 => array("`fA`3d`Fj`#u`Fn`3k`ft", "`fA`3d`Fj`#u`jn`#k`Ft`3i`fn"), 15 => array("`fS`3c`Fh`#ü`Fl`3e`fr", "`fS`3c`Fh`#ü`jl`#e`Fr`3i`fn"), 16 => array("`fP`3a`Fp`#i`jerfliegerfa`#l`Ft`3e`fr", "`fP`3a`Fp`#i`jerfliegerfalt`#e`Fr`3i`fn"), 17 => array("`fL`3e`Fh`#rl`Fi`3n`fg", "`fL`3e`Fh`#rl`Fi`3n`fg"), 18 => array("`fG`3e`Fs`#e`Fl`3l`fe", "`fG`3e`Fs`#e`j`#l`Fl`3i`fn"), 19 => array("`fG`3r`Fa`#s`jhalmzä`#h`Fl`3e`fr", "`fG`3r`Fa`#s`jhalmzähl`#e`Fr`3i`fn"), 20 => array("`fB`3a`Fu`3e`fr", "`fB`3ä`Fu`#e`Fr`3i`fn"), 21 => array("`fG`3r`Fo`#ß`jb`#a`Fu`3e`fr", "`fG`3r`Fo`#ß`jbäu`#e`Fr`3i`fn"), 22 => array("`fR`3e`Fg`#e`jntropfenpfl`#e`Fg`3e`fr", "`fR`3e`Fg`#e`jntropfenpfleg`#e`Fr`3i`fn"), 23 => array("`fS`3c`Fh`#m`Fi`3e`fd", "`fS`3c`Fh`#m`ji`#e`Fd`3i`fn"), 24 => array("`fH`3a`Fr`#f`jenb`#a`Fu`3e`fr", "`fH`3a`Fr`#f`jenbau`#e`Fr`3i`fn"), 25 => array("`fW`3a`Ff`#f`jensch`#m`Fi`3e`fd", "`fW`3a`Ff`#f`jenschmi`#e`Fd`3i`fn"), 26 => array("`fR`3ü`Fs`#t`jungssch`#m`Fi`3e`fd ", "`fR`3ü`Fs`#t`jungsschmi`#e`Fd`3i`fn"), 27 => array("`fK`3u`Fn`#s`jtsch`#m`Fi`3e`fd ", "`fK`3u`Fn`#s`jtschmi`#e`Fd`3i`fn"), 28 => array("`fG`3l`Fü`#h`jwürmchenglühbirnenwech`#s`Fl`3e`fr ", "`fG`3l`Fü`#h`jwürmchenglühbirnenwechsl`#e`Fr`3i`fn"), 29 => array("`fG`3r`Fo`#ß`jgrundbesi`#t`Fz`3e`fr", "`fG`3r`Fo`#ß`jgrundbesitz`#e`Fr`3i`fn"), 30 => array("`fS`3t`Fu`#d`Fe`3n`ft", "`fS`3t`Fu`#d`je`#n`Ft`3i`fn"), 31 => array("`fM`3e`Fi`#s`jtersch`#ü`Fl`3e`fr", "`fM`3e`Fi`#s`jterschül`#e`Fr`3i`fn"), 32 => array("`fF`3l`Fi`#e`jgenkoteinsam`#m`Fl`3e`fr", "`fF`3l`Fi`#e`jgenkoteinsammle`#r`Fl`3i`fn"), 33 => array("`fA`3r`Fb`#ei`Ft`3e`fr", "`fA`3r`Fb`#e`jit`#e`Fr`3i`fn"), 34 => array("`fW`3o`Fl`#k`jenf`#e`Fg`3e`fr", "`fW`3o`Fl`#k`jenfeg`#e`Fr`3i`fn"), 35 => array("`fS`3t`Fa`#d`jtschre`#i`Fb`3e`fr", "`fS`3t`Fa`#d`jtschreib`#e`Fr`3i`fn"), 36 => array("`fP`3o`Fs`#t`jzä`#h`Fl`3e`fr", "`fP`3o`Fs`#t`jzähl`#e`Fr`3i`fn"), 37 => array("`fP`3e`Fd`3e`fl", "`fP`3e`Fd`3e`fl"), 38 => array("`fB`3a`Fk`#k`jalau`#r`Fe`3u`fs", "`fB`3a`Fk`#k`jala`#u`Fr`3e`fa"), 39 => array("`fL`3i`Fz`#e`jn`#z`Fi`3a`ft", "`fL`3i`Fz`#e`jnzi`#a`Ft`3i`fn"), 40 => array("`fL`3e`Fhr`3e`fr", "`fL`3e`Fh`#re`Fr`3i`fn"), 41 => array("`fH`3e`Fro`3l`fd", "`fH`3e`Fro`3l`fd"), 42 => array("`fD`3i`Fp`#lo`Fm`3a`ft", "`fD`3i`Fp`#l`jom`#a`Ft`3i`fn"), 43 => array("`fN`3a`Fc`#h`jtschattenverwa`#l`Ft`3e`fr", "`fN`3a`Fc`#h`jtschattenverwalt`#e`Fr`3i`fn"), 44 => array("`fR`3i`Fc`#h`Ft`3e`fr", "`fR`3i`Fc`#h`jt`#e`Fr`3i`fn"), 45 => array("`fM`3a`Fg`#is`Ft`3e`fr", "`fM`3a`Fg`#is`Ft`3r`fa"), 46 => array("`fM`3a`Fg`#i`jster Ar`#t`Fi`3u`fm", "`fM`3a`Fg`#i`jstra Ar`#t`Fi`3u`fm"), 47 => array("`fM`3a`Fg`#i`jster Rerum Public`#a`Fr`3u`fm", "`fM`3a`Fg`#i`jtra Rerum Public`#a`Fr`3u`fm"), 48 => array("`fD`3o`Fkt`3o`fr", "`fD`3o`Fk`#to`Fr`3i`fn"), 49 => array("`fD`3o`Fk`#t`jor Ar`#t`Fi`3u`fm", "`fD`3o`Fk`#t`jorin Ar`#t`Fi`3u`fm"), 50 => array("`fD`3o`Fk`#t`jor Rerum Public`#a`Fr`3u`fm", "`fD`3o`Fk`#t`jorin Rerum Public`#a`Fr`3u`fm"), 51 => array("`fP`3r`Fo`#f`je`#s`Fs`3o`fr ", "`fP`3r`Fo`#f`jess`#o`Fr`3i`fn"), 52 => array("`fP`3r`Fo`#f`jessor Ar`#t`Fi`3u`fm", "`fP`3r`Fo`#f`jessorin Ar`#t`Fi`3u`fm"), 53 => array("`fP`3r`Fo`#f`jessor Rerum Public`#a`Fr`3u`fm", " `fP`3r`Fo`#f`jessorin Rerum Public`#a`Fr`3u`fm"), 54 => array("`fS`3t`Fu`#d`jie`#n`Fr`3a`ft", "`fS`3t`Fu`#d`jienr`#ä`Ft`3i`fn"), 55 => array("`fD`3e`Fk`3a`fn", "`fD`3e`Fk`#a`Fn`3i`fn"), 56 => array("`fD`3i`Fr`#ek`Ft`3o`fr", "`fD`3i`Fr`#e`jk`#to`Fr`3i`fn"), 57 => array("`fA`3r`Fi`#s`jto`#k`Fr`3a`ft", "`fA`3r`Fi`#s`jtokr`#a`Ft`3i`fn"), 58 => array("`wD`4i`We`eb", "`fD`3i`Feb`3i`fn"), 59 => array("`wG`4a`Wun`4e`wr", "`wG`4a`Wu`en`ee`Wr`4i`wn"), 60 => array("`wR`4ä`Wub`4e`wr", "`wR`4ä`Wu`eb`\$erb`er`Wa`4u`wt"), 61 => array("`wM`4ö`Wrd`4e`wr", "`wM`4ö`Wr`ede`Wr`4i`wn"), 62 => array("`wM`4e`Wi`es`\$ter`ed`Wi`4e`wb", "`wM`4e`Wi`es`\$terdi`ee`Wb`4i`wn"), 63 => array("`wW`4a`Wl`ed`\$lä`eu`Wf`4e`wr", "`wW`4a`Wl`ed`\$läuf`ee`Wr`4i`wn"), 64 => array("`wB`4o`Wg`ee`\$nsch`eü`Wt`4z`we", "`wB`4o`Wg`ee`\$nschü`et`Wz`4i`wn"), 65 => array("`wS`4c`Wh`ea`\$rfsch`eü`Wt`4z`we", "`wS`4c`Wh`ea`\$rfschü`et`Wz`4i`wn"), 66 => array("`wA`4r`Wm`eb`\$rustsch`eü`Wt`4z`we", "`wA`4r`Wm`eb`\$rustschü`et`Wz`4i`wn"), 67 => array("`wA`4r`Wa`eb`\$alistasch`eü`Wt`4z`we", "`wA`4r`Wa`eb`\$alistaschü`et`Wz`4i`wn"), 68 => array("`wM`4e`Wi`es`\$tersch`eü`Wt`4z`we", "`wM`4e`Wi`es`\$terschü`et`Wz`4i`wn"), 69 => array("`wK`4n`Wap`4p`we", "`wK`4n`Wa`ep`Wp`4i`wn"), 70 => array("`wA`4b`We`en`\$dfahnenl`ee`Wg`4e`wr", "`wA`4b`We`en`\$dfahnenleg`ee`Wr`4i`wn"), 71 => array("`wS`4c`Wh`ei`\$ldkn`ea`Wp`4p`we", "`wS`4c`Wh`ei`\$ldkna`ep`Wp`4i`wn"), 72 => array("`wM`4i`Wl`4i`wz", "`wM`4i`Wli`4z`win"), 73 => array("`wG`4a`Wr`ed`Wi`4s`wt", "`wG`4a`Wr`ed`\$i`es`Wt`4i`wn"), 74 => array("`wS`4o`Wld`4a`wt", "`wS`4o`Wl`ed`\$`ea`Wt`4i`wn"), 75 => array("`wG`4l`Wü`ec`\$kspfortenj`eä`Wg`4e`wr", "`wG`4l`Wü`ec`\$kspfortenjäg`ee`Wr`4i`wn"), 76 => array("`wB`4e`Ws`ec`\$hü`et`Wz`4e`wr", "`wB`4e`Ws`ec`\$hütz`ee`Wr`4i`wn"), 77 => array("`wA`4t`Wl`ea`\$ntisfeuerwehr`em`Wa`4n`wn", "`wA`4t`Wl`ea`\$ntisfeuerwehr`ef`Wr`4a`wu"), 78 => array("`wV`4e`Wr`et`\$eid`ei`Wg`4e`wr", "`wV`4e`Wr`et`\$eidig`ee`Wr`4i`wn"), 79 => array("`wW`4ä`Wc`eh`Wt`4e`wr", "`wW`4ä`Wc`eh`\$t`ee`Wr`4i`wn"), 80 => array("`wC`4h`Wa`eo`\$saufrä`eu`Wm`4e`wr", "`wC`4h`Wa`eo`\$saufräum`ee`Wr`4i`wn"), 81 => array("`wS`4ä`Wb`ee`\$lras`es`Wl`4e`wr", "`wS`4ä`Wb`ee`\$lrassl`ee`Wr`4i`wn"), 82 => array("`wC`4h`Wa`eo`\$sverbre`ei`Wt`4e`wr", "`wC`4h`Wa`eo`\$sverbreit`ee`Wr`4i`wn"), 83 => array("`wA`4x`Wt`es`\$chwi`en`Wg`4e`wr", "`wA`4x`Wt`es`\$chwing`ee`Wr`4i`wn"), 84 => array("`wS`4ö`Wl`ed`Wn`4e`wr", "`wS`4ö`Wl`ed`\$n`ee`Wr`4i`wn"), 85 => array("`wS`4c`Wh`el`\$äc`eh`Wt`4e`wr", "`wS`4c`Wh`el`$ächt`ee`Wr`4i`wn"), 86 => array("`wB`4a`Wrb`4a`wr", "`wA`4m`Wa`ez`Wo`4n`we"), 87 => array("`wS`4o`Wn`en`\$enst`eö`Wr`4e`wr", "`wS`4o`Wn`en`\$enstör`ee`Wr`4i`wn"), 88 => array("`wB`4e`Wr`es`\$e`er`Wk`4e`wr", "`wB`4e`Wr`es`\$erk`ee`Wr`4i`wn"), 89 => array("`wK`4l`We`eri`Wk`4e`wr", "`wK`4l`We`er`\$ik`ee`Wr`4i`wn"), 90 => array("`wO`4r`Wd`ee`\$nsri`et`Wt`4e`wr", "`wO`4r`Wd`ee`\$nsritt`ee`Wr`4i`wn"), 91 => array("`wP`4a`Wl`ea`Wd`4i`wn", "`wP`4a`Wl`ea`Wd`4i`wn"), 92 => array("`wH`4e`Wr`ed`\$plattenwärmete`es`Wt`4e`wr", "`wH`4e`Wr`ed`\$plattenwärmetest`ee`Wr`4i`wn"), 93 => array("`wP`4h`Wö`en`\$ixbänd`ei`Wg`4e`wr", "`wP`4h`Wö`en`\$ixbändig`ee`Wr`4i`wn"), 94 => array("`wP`4h`Wö`en`\$ixt`eö`Wt`4e`wr ", "`wP`4h`Wö`en`\$ixtöt`ee`Wr`4i`wn"), 95 => array("`wG`4l`Wa`ed`\$i`ea`Wt`4o`wr", "`wG`4l`Wa`ed`\$iat`eo`Wr`4i`wn"), 96 => array("`wL`4e`Wg`eio`Wn`4ä`wr", "`wL`4e`Wg`ei`\$on`eä`Wr`4i`wn"), 97 => array("`wZ`4e`Wn`etu`Wr`4i`wo", "`wZ`4e`Wn`etu`Wr`4i`wa"), 98 => array("`wP`4r`Wi`em`\$us P`ei`Wl`4u`ws", "`wP`4r`Wi`em`\$us P`ei`Wl`4u`ws"), 99 => array("`wP`4r`Wa`ee`\$fectus Castr`eo`Wr`4u`wm", "`wP`4r`Wa`ee`\$fectus Castr`eo`Wr`4u`wm "), 100 => array("`wT`4r`Wib`4u`wn", "`wT`4r`Wi`eb`\$un`ei`Wc`4i`wa"), 101 => array("`wL`4e`Wg`4a`wt", "`wL`4e`Wg`ea`Wt`4i`wn"), 102 => array("`uB`oü`Ur`@g`Oermei`@s`Ut`oe`ur", "`uB`oü`Ur`@g`Oermeist`@e`Ur`oi`un"), 103 => array("`uD`oe`Ur`@ `OKlotz am `@B`Ue`oi`un", "`uD`oe`Ur`@ `OKlotz am `@B`Ue`oi`un"), 104 => array("`uV`oo`Ug`@t", "`uV`oö`Ugt`oi`un"), 105 => array("`uB`oa`Ur`oo`un", "`uB`oa`Ur`@on`Ue`os`us"), 106 => array("`uG`or`Ua`@f", "`uG`or`Uäf`oi`un"), 107 => array("`uF`oü`Ur`os`ut", "`uF`oü`Ur`@s`Ut`oi`un"), 108 => array("`uM`oa`Ur`@q`Uu`oi`us", "`uM`oa`Ur`@qu`Ui`os`ue"), 109 => array("`uH`oe`Urz`oo`ug", "`uH`oe`Ur`@zo`Ug`oi`un"), 110 => array("`uK`ou`Ur`@pr`Ui`on`uz", "`uK`ou`Ur`@p`Orinze`@s`Us`oi`un"), 111 => array("`uK`ou`Ur`@fü`Ur`os`ut", "`uK`ou`Ur`@f`Oür`@s`Ut`oi`un"), 112 => array("`uR`oe`Ug`@e`Onbogenanstrei`@c`Uh`oe`ur", "`uR`oe`Ug`@e`Onbogenanstreich`@e`Ur`oi`un"), 113 => array("`uG`or`Uo`@ß`Ohe`@r`Uz`oo`ug", "`uG`or`Uo`@ß`Oherz`@o`Ug`oi`un"), 114 => array("`uE`or`Uz`@h`Oe`@r`Uz`oo`ug", "`uE`or`Uz`@h`Oerz`@o`Ug`oi`un"), 115 => array("`uT`or`Uu`@c`Oh`@s`Ue`os`us", "`uT`or`Uu`@c`Oh`@s`Ue`os`uz"), 116 => array("`uQ`ou`Uä`@s`Ut`oo`ur", "`uQ`ou`Uä`@s`Ot`@o`Ur`oi`un"), 117 => array("`uÄ`od`Ui`@l", "`uÄ`od`Ui`ol`ua"), 118 => array("`uV`oo`Ul`@k`Ostr`@i`Ub`ou`un", "`uV`oo`Ul`@k`Ostribun`@i`Uc`oi`ua"), 119 => array("`uP`or`Uät`oo`ur", "`uP`or`Uä`@to`Ur`oi`un"), 120 => array("`uP`or`Uo`@k`Oo`@n`Us`ou`ul", "`uP`or`Uo`@k`Oons`@u`Ul`oi`un"), 121 => array("`uK`oo`Uns`ou`ul", " `uK`oo`Un`@su`Ul`oi`un"), 122 => array("`uC`oe`Uns`oo`ur", "`uC`oe`Un`@s`Uo`or`ui"), 123 => array("`uM`oa`Ug`@i`Os`@t`Ur`oa`ut", "`uM`oa`Ug`@i`Ostr`@a`Ut`oi`un"), 124 => array("`uS`ou`Uf`oe`ut", "`uS`ou`Uf`oe`ut"), 125 => array("`uS`oh`Uog`ou`un", "`uS`oh`Uog`ou`un"), 126 => array("`uA`or`Uc`@h`Oon Polemar`@c`Uh`oo`us", "`uA`or`Uc`@h`Oontin Polemar`@c`Uh`oo`us"), 127 => array("`uA`or`Uc`@h`Oon Epon`@y`Um`oo`us", "`uA`or`Uc`@h`Oontin Epon`@y`Um`oo`us"), 128 => array("`uB`oa`Us`@il`Ue`ou`us", "`uB`oa`Us`@i`Ol`@i`Us`os`ua"), 129 => array("`uA`ou`Ug`@us`Ut`ou`us", "`uA`ou`Ug`@u`Us`ot`ua"), 130 => array("`uM`oo`Ur`@g`Oendämmerungsmei`@s`Ut`oe`ur", "`uM`oo`Ur`@g`Oendämmerungsmeist`@e`Ur`oi`un"), 131 => array("`uD`oi`Uk`@ta`Ut`oo`ur", "`uD`oi`Uk`@t`Oat`@o`Ur`oi`un"), 132 => array("`uA`ob`Ue`@n`Oddämmerungsr`@u`Uf`oe`ur", "`uA`ob`Ue`@n`Oddämmerungsruf`@e`Ur`oi`un"), 133 => array("`uP`or`Ui`on`uz", "`uP`or`Ui`@n`Oze`@s`Us`oi`un"), 134 => array("`uK`or`Uo`@n`Op`@r`Ui`on`uz", "`uK`or`Uo`@n`Oprinze`@s`Us`oi`un"), 135 => array("`uK`oö`Un`oi`ug", "`uK`oö`Un`@i`Ug`oi`un"), 136 => array("`uI`om`Up`@e`Or`@a`Ut`oo`ur", "`uI`om`Up`@e`Orat`@r`Ui`oc`ue"), 137 => array("`uK`oa`Uis`oe`ur", "`uK`oa`Ui`@se`Ur`oi`un"), 138 => array("`KT`Io`Lt`ve`&nausgr`vä`Lb`Ie`Kr", "`KT`Io`Lt`ve`&nausgräb`ve`Lr`Ii`Kn"), 139 => array("`KN`Io`Lvi`Iz`Ke", "`KN`Io`Lv`vi`Lz`Ii`Kn"), 140 => array("`KL`Ia`Lb`ve`&rta`vs`Lc`Ih`Ke", "`KL`Ia`Lb`ve`&rta`vs`Lc`Ih`Ke"), 141 => array("`KS`Ic`Lh`ve`&rz`vk`Le`Ik`Ks", "`KS`Ic`Lh`ve`&rz`vk`Le`Ik`Ks"), 142 => array("`KS`Ic`Lh`vam`La`In`Ke", "`KS`Ic`Lh`va`&m`va`Ln`Ii`Kn"), 143 => array("`KW`Ia`Lh`vr`&s`va`Lg`Ie`Kr", "`KW`Ia`Lh`vr`&sag`ve`Lr`Ii`Kn"), 144 => array("`KD`Ir`Lui`Id`Ke", "`KD`Ir`Lu`vi`Ld`Ii`Kn"), 145 => array("`KB`Ir`Lud`Ie`Kr", "`KS`Ic`Lh`vw`&e`vs`Lt`Ie`Kr"), 146 => array("`KM`Iö`Ln`Ic`Kh", "`KN`Io`Ln`In`Ke"), 147 => array("`KS`Io`Ln`vn`&enstrahlenfä`vn`Lg`Ie`Kr", "`KS`Io`Ln`vn`&enstrahlenfäng`ve`Lr`Ii`Kn"), 148 => array("`KP`Ir`Li`Io`Kr", "`KP`Ir`Li`Im`Ka"), 149 => array("`KA`Ip`Lo`vs`Lt`Ie`Kl", "`KA`Ip`Lo`vs`Lt`Ie`Kl"), 150 => array("`KK`Ia`Lpl`Ia`Kn", "`KK`Ia`Lpl`Ia`Kn"), 151 => array("`KP`Ir`Li`ves`Lt`Ie`Kr", "`KP`Ir`Li`ve`&st`ve`Lr`Ii`Kn"), 152 => array("`KP`If`La`vr`Lr`Ie`Kr", "`KP`If`La`vr`&r`ve`Lr`Ii`Kn"), 153 => array("`KW`Ie`Li`vs`&er Ratg`ve`Lb`Ie`Kr", "`KW`Ie`Li`vs`&e Ratgeb`ve`Lr`Ii`Kn"), 154 => array("`KG`Ie`Li`vs`&tlicher Ber`va`Lt`Ie`Kr", "`KG`Ie`Li`vs`&tliche Berat`ve`Lr`Ii`Kn"), 155 => array("`KG`Ie`Ld`vä`&chtnislückenzä`vh`Ll`Ie`Kr", "`KG`Ie`Ld`vä`&chtnislückenzähl`ve`Lr`Ii`Kn"), 156 => array("`KA`Ib`Lt", "`KÄ`Ib`Lt`vi`&`vs`Ls`Ii`Kn"), 157 => array("`KV`Ii`Lk`Ia`Kr", "`KV`Ii`Lk`va`Lr`Ii`Kn"), 158 => array("`KR`Ie`Lg`ve`&nzä`vh`Ll`Ie`Kr ", "`KR`Ie`Lg`ve`&nzähl`ve`Lr`Ii`Kn"), 159 => array("`KB`Ii`Ls`vc`Lh`Io`Kf", "`KB`Ii`Ls`vc`&h`vö`Lf`Ii`Kn"), 160 => array("`KE`Ir`Lz`vb`&is`vc`Lh`Io`Kf", "`KE`Ir`Lz`vb`&isch`vö`Lf`Ii`Kn"), 161 => array("`KK`Ia`Lr`vdi`Ln`Ia`Kl", "`KK`Ia`Lr`vd`&in`vä`Ll`Ii`Kn"), 162 => array("`KI`In`Lq`vu`&is`vi`Lt`Io`Kr", "`KI`In`Lq`vu`&isit`vo`Lr`Ii`Kn"), 163 => array("`KP`Io`Ln`vt`&ifex Max`vi`Lm`Iu`Ks", "`KP`Io`Ln`vt`&ifex Ma`vx`Li`Im`Ka"), 164 => array("`KP`Ir`Le`vf`&e`vr`Li`It`Ki", "`KP`Ir`Le`vf`&e`vr`Li`It`Ki"), 165 => array("`KP`Ia`Lb`Is`Kt", "`KP`Iä`Lp`vs`Lt`Ii`Kn"), 166 => array("`KV`Ia`Lm`vp`&irischer Sonnenanb`ve`Lt`Ie`Kr", "`KV`Ia`Lm`vp`&irische Sonnenanbet`ve`Lr`Ii`Kn"), 167 => array("`KZ`Ie`Lr`vs`&plitterter Seelensu`vc`Lh`Ie`Kr", "`KZ`Ie`Lr`vs`&plitterte Seelensuch`ve`Lr`Ii`Kn"), 168 => array("`PS`se`Se`sl`Pe", "`PS`se`Se`sl`Pe"), 169 => array("`PH`so`Sn`di`Dgkuchengrinsep`df`Se`sr`Pd", "`PH`so`Sn`di`Dgkuchengrinsep`df`Se`sr`Pd"), 170 => array("`PI`sn`Sc`du`Sb`su`Ps", "`PS`su`Sc`dcu`Sb`su`Ps"), 171 => array("`PE`sr`Sz`ddä`Sm`so`Pn", "`PE`sr`Sz`dd`Däm`do`Sn`si`Pn"), 172 => array("`PE`si`Se`dr`Dwä`dr`Sm`se`Pr", "`PE`si`Se`dr`Dwärm`de`Sr`si`Pn"), 173 => array("`PT`se`Suf`se`Pl", "`PT`se`Su`dfe`Sl`si`Pn"), 174 => array("`PB`se`Se`dl`Dz`de`Sb`su`Pb", "`PH`sö`Sl`dl`Den`db`Sr`su`Pt"), 175 => array("`PS`sa`St`sa`Pn", "`PS`sa`St`da`Dnsb`dr`Sa`su`Pt"), 176 => array("`PE`sn`Sg`se`Pl", "`PE`sn`Sg`se`Pl"), 177 => array("`PR`sa`Sc`dh`Dee`dn`Sg`se`Pl", "`PR`sa`Sc`dh`Dee`dn`Sg`se`Pl"), 178 => array("`PE`sr`Sz`den`Sg`se`Pl", "`PE`sr`Sz`den`Sg`se`Pl"), 179 => array("`PC`sh`Se`dru`Sb`si`Pm", "`PC`sh`Se`dr`Du`db`Si`sm`Pa"), 180 => array("`PS`se`Sr`dap`Sh`si`Pm", "`PS`se`Sr`da`Dp`dh`Si`sm`Pa"), 181 => array("`PT`se`Slk`si`Pn", "`PT`se`Sl`dk`Si`sn`Pa"), 182 => array("`PT`si`St`sa`Pn", "`PT`si`St`da`Sn`si`Pn"), 183 => array("`PH`sa`Sl`dbg`So`st`Pt", "`PH`sa`Sl`db`Dgö`dt`St`si`Pn"), 184 => array("`PG`so`St`dt", "`PG`sö`Stt`si`Pn"), 185 => array("`PN`sa`Sd`de`Dl im Heuha`du`Sf`se`Pn", "`PN`sa`Sd`de`Dl im Heuha`du`Sf`se`Pn"), 186 => array("`PD`sa`Ss`d ´S´ `Din `d`S´E`ss`P´", "`PD`sa`Ss`d ´S´ `Din `d`S´E`ss`P´"), 187 => array("`PN`se`Sm`do`Dfi`dn`Sd`se`Pr", "`PN`se`Sm`do`Dfind`de`Sr`si`Pn"), 188 => array("`PO`sb`Se`dr`Dchefsesselinh`da`Sb`se`Pr", "`PO`sb`Se`dr`Dchefsesselinhab`de`Sr`si`Pn"));
$ghosts = array(1 => "`uSc`Uhl`gan`uge", 2 => "`qFu`Qch`qs", 3 => "`öE`Äbe`ör", 4 => "`BAd`(le`Br", 5 => "`RW`Gol`7f", 6 => "`TP`Äfer`Td");
$beta = (getsetting("beta", 0) == 1 || $session['user']['beta'] == 1);
?> 