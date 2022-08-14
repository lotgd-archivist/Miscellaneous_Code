
<?php

require_once "common.php";

if ($_GET['type'] == "fast") {
    if ($_GET['location'] == "house") {
        $sql = "SELECT h.locid FROM houses h, accounts a WHERE h.houseid=" . $session['user']['house'] . " AND owner = " . $session['user']['acctid'] . " AND a.acctid = owner ORDER BY h.houseid DESC LIMIT 25";
        $row2 = db_fetch_assoc(db_query($sql));
        $restore = "houses.php?op=newday";

        $sql = "SELECT h.houseid, hc.* FROM houses h, houseconfig hc WHERE hc.locid = '" . $row2['locid'] . "' AND h.owner = " . $session['user']['acctid'];
        $result = db_fetch_assoc(db_query($sql));
        $session['user']['specialmisc'] = $result;

        db_query("UPDATE accounts SET loggedin=0,location=2,restorepage='$restore' WHERE acctid = " . $session['user']['acctid']);
    } else {
        $restore = "village.php";
        db_query("UPDATE accounts SET restorepage = '$restore' WHERE acctid = " . $session['user']['acctid']);
    }
    $session['user']['restorepage'] = $restore;
    saveuser();
}

if ($_POST['name'] != "") {
    if ($session['loggedin']) {

        //redirect("badnav.php");
        $session['admin_msg'] = 'schnischnaschnappi';
        redirect($session['user']['restorepage']);
    } else {
        // Anzahl der Spieler laden, die online sind
        $result = db_fetch_assoc(db_query("SELECT COUNT(acctid) AS onlinecount FROM accounts WHERE locked=0 AND loggedin=1 AND laston>'" . date("Y-m-d H:i:s", strtotime(date("c") . "-" . getsetting("LOGINTIMEOUT", 900) . " seconds")) . "'"));
        $onlinecount = $result['onlinecount'];

        // Hole Account, der eingeloggt werdn soll aus der DB
        $password = addslashes($password);
        $sql = "SELECT * FROM accounts WHERE login = '" . addslashes(stripslashes($_POST['name'])) . "' AND password=MD5('{$_POST['password']}') AND locked=0";
        $result = db_query($sql);

        // Genau 1 spieler gefunden
        if (db_num_rows($result) == 1) {

            // $session['user'] erzeugen und ein Backup davon anlegen - warum auch immer
            $session['user'] = db_fetch_assoc($result);
            $userbackup = $session['user'];
            unset($userbackup['output'], $userbackup['allowednavs']); // This field changes every hit =)
            // Bans ï¿½berprï¿½fen
            checkban($session['user']['login']); //check if this account is banned
            checkban(); //check if this computer is banned
            // Verimail noch nicht gecheckt.
            if ($session['user']['emailvalidation'] != "" && substr($session['user']['emailvalidation'], 0, 1) != "x") {
                $session['user'] = array();
                $session['message'] = "`4Fehler: Du musst deine E-Mail Adresse bestÃ¤tigen lassen, bevor du dich einloggen kannst.";
                echo $session['message'];
                header("Location: index.php");
                exit();
            } else {
                // Spieler ist Admin oder es sind noch nicht zu viele Spieler online
                if ($onlinecount < getsetting("maxonline", 10) || getsetting("maxonline", 10) == 0 || $session['user']['superuser'] > 0) {

                    $session['loggedin'] = true;
                    //$session['output'] = file_get_contents('./cache/c'.$session['user']['acctid'].'.txt'); //by Eliwood
                    $session['output'] = $session['user']['output'];
                    $session['petitions'] = array();
                    $session['laston'] = date("Y-m-d H:i:s");
                    $session['sentnotice'] = 0;
                    $session['user']['dragonpoints'] = unserialize($session['user']['dragonpoints']);
                    $session['user']['prefs'] = unserialize($session['user']['prefs']);
                    $session['bufflist'] = unserialize($session['user']['bufflist']);
                    if (!isset($session['user']['restorepage'])) {
                        $session['user']['restorepage'] = "village.php";
                        addnav("", "village.php");
                    }
                    if (!is_array($session['user']['dragonpoints']))
                        $session['user']['dragonpoints'] = array();
                    if ($session['user']['loggedin']) {
                        $session['allowednavs'] = unserialize($session['user']['allowednavs']);
                        saveuser();
                        //$r_page = $session['user']['restorepage'];
                        $session['admin_msg'] = 'Check 1, 2. 1, 2';
                        //header("Location: {$r_page}");+

                        redirect($session['user']['restorepage']);
                        return;
                    }
                    db_query("UPDATE accounts SET loggedin=" . true . ", location=0 WHERE acctid = " . $session['user']['acctid']);
                    $session['user']['loggedin'] = true;
                    $location = $session['user']['location'];
                    $session['user']['location'] = 0;
                    debuglog("logged in ");

                    if ($session[user][alive] == 0 && $session[user][slainby] != "") {
                        //they're not really dead, they were killed in pvp.
                        $session[user][alive] = true;
                    }
                    if (getsetting("logdnet", 0)) {
                        //register with LoGDnet
                        @file(getsetting("logdnetserver", "http://lotgd.net/") . "logdnet.php?addy=" . URLEncode(getsetting("serverurl", "http://" . $_SERVER['SERVER_NAME'] . dirname($_SERVER['REQUEST_URI']))) . "&desc=" . URLEncode(getsetting("serverdesc", "Another LoGD Server")) . "&version=" . URLEncode($logd_version) . "");
                    }
                    /*                         if($session['user']['newplayer'] == 1){
                      redirect("bootsanleger.php");
                      }
                     */
                    if ($session['user']['prison'] == 1) {
                        redirect("kerker.php");
                    }
                    if ($session['user']['einzelhaft'] == 1) {
                        redirect("kerker.php");
                    }
                    //if ($location==0){
                    //    redirect("news.php");
                    //}else
                    if ($location == 1) {
                        redirect("inn.php?op=strolldown");
                    } else if ($location == 2) {
                        redirect("houses.php?op=newday");
                    } else if ($location == 3) {
                        redirect("necron.php?op=");
                    } else {
                        saveuser();
                        //header("Location: {$session['user']['restorepage']}");
                        if ($session['user']['jailtime'] > 0)
                            redirect('pranger.php');
                        else
                            redirect($session['user']['restorepage']);
                        return;
                    }
                } else {
                    $session['user'] = array();
                    $session[message] = "`4Fehler: Der Server ist voll.`0";
                    redirect("index.php");
                }
            }
        } else {
            $session[message] = "`4Fehler: Login-Daten waren ungï¿½ltig.`0";
            //now we'll log the failed attempt and begin to issue bans if there are too many, plus notify the admins.
            $sql = "DELETE FROM faillog WHERE date<'" . date("Y-m-d H:i:s", strtotime(date("c") . "-" . (getsetting("expirecontent", 180) / 4) . " days")) . "'";
            checkban();
            db_query($sql);
            $sql = "SELECT acctid FROM accounts WHERE login='{$_POST['name']}'";
            $result = db_query($sql);
            if (db_num_rows($result) > 0) { // just in case there manage to be multiple accounts on this name.
                while ($row = db_fetch_assoc($result)) {
                    $sql = "INSERT INTO faillog VALUES (0,now(),'" . addslashes(serialize($_POST)) . "','{$_SERVER['REMOTE_ADDR']}','{$row['acctid']}','{$_COOKIE['lgi']}')";
                    db_query($sql);
                    $sql = "SELECT faillog.*,accounts.superuser,name,login FROM faillog INNER JOIN accounts ON accounts.acctid=faillog.acctid WHERE ip='{$_SERVER['REMOTE_ADDR']}' AND date>'" . date("Y-m-d H:i:s", strtotime(date("c") . "-1 day")) . "'";
                    $result2 = db_query($sql);
                    $c = 0;
                    $alert = "";
                    $su = false;
                    while ($row2 = db_fetch_assoc($result2)) {
                        if ($row2['superuser'] > 0) {
                            $c += 1;
                            $su = true;
                        }
                        $c += 1;
                        $alert .= "`3{$row2['date']}`7: Failed attempt from `&{$row2['ip']}`7 [`3{$row2['id']}`7] to log on to `^{$row2['login']}`7 ({$row2['name']}`7)`n";
                    }
                    if ($c >= 10) { // 5 failed attempts for superuser, 10 for regular user
                        $sql = "INSERT INTO bans VALUES ('{$_SERVER['REMOTE_ADDR']}','','" . date("Y-m-d H:i:s", strtotime(date("c") . "+" . ($c * 3) . " hours")) . "','Automatischer Systembann: Zu viele fehlgeschlagene Loginversuche.')";
                        db_query($sql);
                        if ($su) { // send a system message to admins regarding this failed attempt if it includes superusers.
                            $sql = "SELECT acctid FROM accounts WHERE superuser>=3";
                            $result2 = db_query($sql);
                            $subj = "`#{$_SERVER['REMOTE_ADDR']} failed to log in too many times!";
                            for ($i = 0; $i < db_num_rows($result2); $i++) {
                                $row2 = db_fetch_assoc($result2);
                                //delete old messages that
                                $sql = "DELETE FROM mail WHERE msgto={$row2['acctid']} AND msgfrom=0 AND subject = '$subj' AND seen=0";
                                db_query($sql);
                                if (db_affected_rows() > 0)
                                    $noemail = true;
                                else
                                    $noemail = false;
                                systemmail($row2['acctid'], "$subj", "This message is generated as a result of one or more of the accounts having been a superuser account.  Log Follows:`n`n$alert", 0, $noemail);
                            }//end for
                        }//end if($su)
                    }//end if($c>=10)
                }//end while
            } else {

            }//end if (db_num_rows)
            redirect("index.php");
        }
    }
} else if ($_GET['op'] == "logout") {
    //die($session['debig']);

    if ($session['user']['loggedin']) {
        debuglog("logged out ");
        $sql = "UPDATE accounts SET loggedin=0 WHERE acctid = " . $session['user']['acctid'];
        db_query($sql) or die(sql_error($sql));
    }
    $session = array();
    $session['olddebug'] = $session['debug'];

    redirect("index.php");
}
// If you enter an empty username, don't just say oops.. do something useful.
$session = array();
$session[message] = "`4Fehler: Die Login-Daten waren fehlerhaft.`0";
redirect("index.php");
?>

