
<?php

//20050708

/*
 * Local changes:
 *
  # gold transfer:
  INSERT INTO housemoduledata (moduleid, name, houseid, value)
  SELECT {id-of-treasury}, 'gold', houseid, gold FROM houses WHERE status=1;
  INSERT INTO housemoduledata (moduleid, name, houseid, value)
  SELECT {id-of-treasury}, 'gems', houseid, gems FROM houses WHERE status=1;

  INSERT INTO `houseconfig` ( `locid` , `location` , `locname` , `buy` , `sell` , `build` , `rob` , `defaultgoldprice` , `defaultgemprice` , `buildprice_increase` )
  VALUES (
  '', 'village.php', 'Wohnviertel', '1', '1', '1', '1', '30000', '50', '5'
  );

  ALTER TABLE `houses` CHANGE `status` `status` ENUM( 'build', 'ready', 'sell' ) DEFAULT 'build' NOT NULL,
  CHANGE `gems` `gemprice` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL,
  CHANGE `location` `locid` INT( 10 ) UNSIGNED DEFAULT '1' NOT NULL;
  UPDATE `houses` SET `locid`=1;
  # this will delete all stored gold; warn the people! ;)
  UPDATE `houses` SET `status`='ready' WHERE `status`='build';
  UPDATE `houses` SET `status`='build' WHERE `status`='' OR owner=0;
  UPDATE `houses` SET `goldprice`=0,`gemprice`=0 WHERE `status`!='build';
  UPDATE `houses` SET `goldprice`=30000-`goldprice`, `gemprice`=50-`gemprice` WHERE `status`='build';
 *
 *
 */

/* * ****************************************
 *
 * Author: Daniel Rathjen <webmaster@chaosonline.de>
 * Version: 1.0
 * Server: biosLoGD Experimental Server
 * URL: http://logd.chaosonline.de
 *
 * This is a rewrite of anpera's houses script:
 *   Author: anpera
 *   Email:logd@anpera.de
 *   URL: http://www.anpera.net/forum/viewtopic.php?t=323
 * This script still consists of his ideas and parts of his code, so all homages to him please ;)
 *
 * Purpose: Same as the old houses script...
 *
 * Features:
 *         - Build house (if allowed)
 *         - Buy house (if allowed)
 *         - Sell house (if allowed)
 *         - Sleeping in a house
 *         - Storing gold in a house (module 'treasury')
 *         - Adding useful modules (rooms)
 *
 * Some important information:
 *
  #
  # Tabellenstruktur für Tabelle `houseconfig`
  #
  DROP TABLE IF EXISTS `houseconfig`;
  CREATE TABLE `houseconfig` (
  `locid` int(10) unsigned NOT NULL auto_increment,
  `location` varchar(255) NOT NULL default 'village.php',
  `locname` varchar(50) NOT NULL default 'Wohnviertel',
  `buy` enum('0','1') NOT NULL default '0',
  `sell` enum('0','1') NOT NULL default '0',
  `build` enum('0','1') NOT NULL default '0',
  `rob` enum('0','1') NOT NULL default '0',
  `defaultgoldprice` int(10) unsigned NOT NULL default '30000',
  `defaultgemprice` int(10) unsigned NOT NULL default '50',
  `buildprice_increase` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY (`locid`)
  ) TYPE=MyISAM COMMENT='Konfiguration der Wohnanlagen - was ist erlaubt?';

  #
  # Tabellenstruktur für Tabelle `housemodules`
  #
  CREATE TABLE `housemodules` (
  `moduleid` int(10) unsigned NOT NULL auto_increment,
  `modulefile` varchar(50) NOT NULL default 'default.php',
  `modulename` varchar(50) NOT NULL default 'living_room',
  `moduleauthor` varchar(50) NOT NULL default '',
  `moduleversion` varchar(20) NOT NULL default '',
  `built_in` enum('0','1') NOT NULL default '1',
  `linkcategory` varchar(50) NOT NULL default 'Räume',
  `linktitle` varchar(50) NOT NULL default '',
  `linkorder` smallint(3) unsigned NOT NULL default '1',
  `showto` SET( 'owner', 'guest' ) DEFAULT 'owner,guest' NOT NULL,
  PRIMARY KEY (`moduleid`),
  UNIQUE `modulename` (`modulename`),
  KEY `built_in` (`built_in`),
  KEY `linkorder` (`linkorder`),
  KEY `showto` (`showto`)
  ) TYPE=MyISAM COMMENT='Module der Häuser';
  #
  # Tabellenstruktur für Tabelle `housemoduledata`
  #
  # Predefined names:
  # - #activated# (together with houseid: value=1 if module is built-in in the house
  #
  CREATE TABLE `housemoduledata` (
  `moduleid` int(10) unsigned NOT NULL,
  `name` varchar(50) NOT NULL default '',
  `houseid` int(10) unsigned NOT NULL default '0',
  `value` varchar(255) NULL,
  PRIMARY KEY (`moduleid`,`name`,`houseid`)
  ) TYPE=MyISAM COMMENT='Moduldaten für die Häuser';
  #
  # Tabellenstruktur für Tabelle `houses`
  #
  DROP TABLE IF EXISTS `houses`;
  CREATE TABLE `houses` (
  `houseid` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) unsigned NOT NULL default '0',
  `status` enum('build','sell','ready') NOT NULL default 'build',
  `goldprice` int(10) unsigned NOT NULL default '30000',
  `gemprice` int(10) unsigned NOT NULL default '50',
  `housename` varchar(25) default NULL,
  `description` text NOT NULL default '',
  `locid` int(10) UNSIGNED NOT NULL default '1',
  PRIMARY KEY  (`houseid`),
  KEY `locid` (`locid`),
  KEY `owner` (`owner`)
  ) TYPE=MyISAM;
  #
  # Daten für Tabelle `settings`
  #
  INSERT INTO `settings` VALUES
  ('startbuild','0'),
  ('mindkbuild','1'),
  ('minlevelbuild','5'),
  ('defaulthousemodule','1');

 *
 *
 * pet-update 20041212:
 *

  ALTER TABLE `accounts`
  ADD `petid` INT UNSIGNED NOT NULL AFTER `hashorse`,
  ADD `petfeed` DATETIME NOT NULL AFTER `petid` ;

  INSERT INTO `items` (`id`, `name`, `class`, `owner`, `value1`, `value2`, `gold`, `gems`, `description`, `hvalue`, `buff`) VALUES (241, 'Kleiner Wachdackel', 'Haust.Prot', 0, 100, 0, 5000, 15, 'Ein niedlicher kleiner Dackel, der Einbrecher erschreckt.', 0, 'a:4:{s:4:"name";s:14:"Lautes Kläffen";s:6:"atkmod";s:2:"20";s:6:"defmod";s:2:"20";s:5:"regen";s:3:"100";}');
  INSERT INTO `items` (`id`, `name`, `class`, `owner`, `value1`, `value2`, `gold`, `gems`, `description`, `hvalue`, `buff`) VALUES (242, 'Großer Haushund', 'Haust.Prot', 0, 0, 1, 15000, 30, 'Ein großer Wachhund, der Haus und Herrchen zu schützen weiß.', 0, 'a:4:{s:4:"name";s:11:"Bissattacke";s:6:"atkmod";s:2:"30";s:6:"defmod";s:2:"30";s:5:"regen";s:3:"150";}');
  INSERT INTO `items` (`id`, `name`, `class`, `owner`, `value1`, `value2`, `gold`, `gems`, `description`, `hvalue`, `buff`) VALUES (243, 'Ausgewachsener Kampfhund', 'Haust.Prot', 0, 100, 1, 25000, 45, 'Ein riesiger Kampfhund, der bereit ist, jeden Eindringling sofort zu zerfleischen.', 0, 'a:4:{s:4:"name";s:21:"Wütendes Zerfleischen";s:6:"atkmod";s:2:"40";s:6:"defmod";s:2:"40";s:5:"regen";s:3:"200";}');

 *
 * **************************************** */

require_once('common.php');
addcommentary();
require_once('lib/include/housefunctions.php');

output("<script type=\"text/javascript\" src=\"templates/common/wz_tooltip.js\"></script>", true);

if (isset($_GET['location'])) {
    $sql                             = 'SELECT * FROM houseconfig WHERE locid="' . (int) $_GET['location'] . '"';
    $result                             = db_query($sql);
    $session['user']['specialmisc']     = db_fetch_assoc($result);
} elseif (!is_array($session['user']['specialmisc'])) {
    $result                             = db_fetch_assoc(db_query("SELECT specialmisc FROM accounts WHERE acctid = " . $session['user']['acctid']));
    $session['user']['specialmisc']     = unserialize($result['specialmisc']);
}
place(true);

if (!isset($session['user']['specialmisc']['locname'])) {
    if (isset($_GET['id']))
        $sql                             = "SELECT hc.* FROM houses h, houseconfig hc WHERE hc.locid = h.locid AND h.houseid = '" . $_GET['id'] . "'";
    else
        $sql                             = "SELECT * FROM houseconfig WHERE locid = '" . $session['user']['specialmisc']['locid'] . "'";
    $result                             = db_fetch_assoc(db_query($sql));
    $session['user']['specialmisc']     = $result;
}

page_header($session['user']['specialmisc']['locname']);
$session['user']['standort'] = $session['user']['specialmisc']['locname'];

// ok, now show the page...
switch ($_GET['op']) {
    // leave
    case 'leave':
        $location                         = $session['user']['specialmisc']['location'];
        $session['user']['specialmisc']     = '';
        redirect($location);
    // log in
    case 'newday':
        output("`n`n`c`YGut erholt wachst du im Haus auf und bist bereit für neue Abenteuer.`c`n`n`0");
        $session['user']['location']     = 0;

        $sql = "UPDATE items SET hvalue=0 WHERE hvalue > 0 AND owner=" . $session['user']['acctid'] . " AND class='Schlüssel'";
        db_query($sql) or die(sql_error($sql));

        addnav("Tägliche News", "news.php");
        
        if ($session['user']['housekey'] == $session['user']['specialmisc']['houseid']) {
            // stay in house...
            addnav('Zum Haus', 'houses.php?op=drin&module=');
        } else {
            // check for housekey
            $sql     = 'SELECT COUNT(*) AS zahl FROM items WHERE class="Schlüssel" AND owner=' . $session['user']['acctid'] . ' AND value1="' . $session['user']['specialmisc']['houseid'] . '"';
            $result     = db_query($sql);
            $row     = db_fetch_assoc($result);
            if ($row['zahl'] > 0) {
                // stay in house...
                addnav('Zum Haus', 'houses.php?op=drin&module=');
            } else
                output('`n`n`c`7Dir wurde inzwischen der Schlüssel für das Haus abgenommen, daher kannst du nicht dorthin zurückkehren!`c`n`n`0');
        }
        addnav("v?Wohnviertel verlassen", 'houses.php?op=leave');
        break;
    // look at the house from outdoor
    case 'bio':
        $sql     = "SELECT houseid, housename, description_out FROM houses WHERE houseid='{$_GET['id']}'";
        $result     = db_query($sql) or die(db_error(LINK));
        if (!($row     = db_fetch_assoc($result)))
            redirect("houses.php");

        output("`n`n`c`b`7Infos über Haus Nummer {$row['houseid']}`b`c`n`n`0
                `c`7Du näherst dich Haus Nummer {$row['houseid']}, um es aus der Nähe zu betrachten.`c`n`n`0");
        if ($row['description_out'] != '') {
            output("`n`n`c`7Über dem Eingang von `) {$row['housename']}`7 steht geschrieben:`n`n`& {$row['description_out']}`c`n`n`0");
        } else {
            output("`n`n`c`7Das Haus trägt den Namen \"`){$row['housename']}`7\".`c`n`n`0");
        }
        $sql     = "SELECT * FROM items WHERE class='Möbel' AND value1={$_GET['id']} ORDER BY id ASC";
        $result     = db_query($sql);
        output("`c`GDu riskierst einen Blick durch eines der`c`0");
        if (db_num_rows($result) > 0) {
            $comma     = false;
            output("`G`cFenster und erkennst`c`0");
            while ($row2     = db_fetch_assoc($result)) {
                if ($comma)
                    output(", ");
                else
                    $comma = true;
                output("`c`7{$row2['name']}`G`c`0");
            }
            output("`G`c.`c`n`n`0");
        }else {
            output("`G`cFenster, aber das Haus hat sonst nichts weiter zu bieten.`c`0`n`n");
        }

        $sql_check_key         = "SELECT value1 FROM items WHERE owner=" . $session['user']['acctid'] . " AND class='Schlüssel' AND value1=" . $_GET['id'] . " LIMIT 1";
        $result_check_key     = db_query($sql_check_key) or die(db_error(LINK));
        $item_check_key         = db_fetch_assoc($result_check_key);

        if ($_GET['id'] == $session['user']['housekey']) {
            addnav("Haus betreten", "houses.php?op=drin&id={$_GET['id']}");
        } else if ($item_check_key) {
            addnav("Haus betreten", "houses.php?op=drin&id={$_GET['id']}");
        } else if ($session['user']['superuser'] == 3 || $session['user']['superuser'] == 5) {
            addnav("Haus betreten", "houses.php?op=drin&id={$_GET['id']}");
        }

        addnav("Zurück");
        addnav("zum Wohnviertel", "houses.php");
        break;
    // build a house
    case 'build':
        // show build startpage
        if (empty($_GET['act'])) {
            if ($session['user']['housekey'] > 0) {
                output("`n`n`c`7Du hast bereits Zugang zu einem fertigen Haus und brauchst kein zweites. Wenn du ein neues oder ein eigenes Haus bauen willst, musst du erst aus deinem jetzigen Zuhause ausziehen.`c`n`n`0");
            } elseif ($session['user']['rp_char'] != 1 && ($session['user']['dragonkills'] < getsetting('mindkbuild', '1') || ($session['user']['dragonkills'] == getsetting('mindkbuild', '1') && $session['user']['level'] < getsetting('minlevelbuild', '5')))) {
                output("`n`n`c`7Du hast noch nicht genug Erfahrung, um ein eigenes Haus bauen zu können. Du kannst aber eventuell bei einem Freund einziehen, wenn er dir einen Schlüssel für sein Haus gibt.`c`n`n`0");
            } elseif ($session['user']['turns'] < 1) {
                output("`n`n`c`7Du bist zu erschöpft, um heute noch irgendetwas zu bauen. Warte bis morgen.`c`n`n`0");
            } elseif ($session['user']['house'] > 0) {
                $sql     = "SELECT houseid, gemprice, goldprice FROM houses WHERE status='build' AND owner=" . $session['user']['acctid'] . " AND locid='{$session['user']['specialmisc']['locid']}'";
                $result     = db_query($sql) or die(db_error(LINK));
                if ($row     = db_fetch_assoc($result)) {
                    output("`@Du besichtigst die Baustelle deines neuen Hauses mit der Hausnummer `3{$row['houseid']}`@.`n`n");
                    output("Du musst noch `^{$row['goldprice']}`@ Gold und `#{$row['gemprice']}`@ Edelsteine bezahlen.`nWillst du jetzt weiter bauen?`n`n");
                    output("`0<form action=\"houses.php?op=build&act=build2\" method='POST'>", true);
                    output("`nWieviel Gold zahlen? <input type='gold' name='gold'>`n", true);
                    output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n", true);
                    output("<input type='submit' class='button' value='Bauen'>", true);
                    addnav("", "houses.php?op=build&act=build2");
                } else {
                    output("`G`n`n`cDir fällt ein, dass die Baustelle deines neuen Hauses ja ganz woanders ist. Hier gibt es leider nichts zu tun.`c`n`n`0");
                }
            } elseif (!checkbuild()) {
                output('`G`n`n`cDu bist nicht berechtigt, hier zu bauen. Besorg dir erstmal eine Baugenehmigung!`c`n`n`0');
            } else {
                $goldcost     = $session['user']['specialmisc']['defaultgoldprice'];
                $gemcost     = $session['user']['specialmisc']['defaultgemprice'];
                output("`n`n`c`GDu siehst ein schönes Fleckchen für ein Haus und überlegst dir, ob du nicht selbst eines bauen solltest, anstatt ein vorhandenes zu kaufen oder noch länger in Kneipe und Feldern zu übernachten.`c`n`0");
                output("`c`GEin Haus zu bauen würde dich `&$goldcost Gold`G und `&$gemcost Edelsteine`G kosten. Du musst das nicht auf einmal bezahlen, sondern könntest immer wieder mal für einen kleineren Betrag ein Stück `c`n`0");
                output("`c`Gweiter bauen. Wie schnell du zu deinem Haus kommst, hängt also davon ab, wie oft und wieviel du bezahlst.`c`n`0");
                output("`c`GDu kannst in deinem zukünftigen Haus alleine wohnen oder es mit anderen teilen. Es bietet einen sicheren Platz zum Übernachten" . (module_installed('treasury') ? " und einen Lagerplatz für einen Teil deiner Reichtümer" : "") . ".`c`n`0");
                output("`c`GEin gestartetes Bauvorhaben kann nicht abgebrochen werden.`n`nWillst du mit dem Hausbau beginnen?`c`n`n`0");
                addnav("Hausbau beginnen", "houses.php?op=build&act=start");
            }
        }
        // start building a house
        elseif ($_GET['act'] == "start") {
            $sql                         = "INSERT INTO houses (owner,status,goldprice,gemprice,housename,locid) VALUES ('{$session['user']['acctid']}','build','{$session['user']['specialmisc']['defaultgoldprice']}','{$session['user']['specialmisc']['defaultgemprice']}','{$session['user']['login']}s Haus','{$session['user']['specialmisc']['locid']}')";
            db_query($sql) or die(db_error(LINK));
            if (db_affected_rows(LINK) == 0)
                redirect("houses.php");
            $session['user']['house']     = db_insert_id(LINK);
            output("`n`n`c`GDu erklärst das Fleckchen Erde zu deinem Besitz und kannst mit dem Bau von Hausnummer `&{$session['user']['house']}`G beginnen.`c`n`n`0");
            output("`0<form action=\"houses.php?op=build&act=build2\" method='POST'>", true);
            output("`c`GGib einen `7Namen`G für dein Haus ein: <input name='housename' maxlength='25'>`c`n`0", true);
            output("`c`GWieviel `7Gold`G anzahlen? <input type='gold' name='gold'>`c`n`0", true);
            output("`c`GWieviele `7Edelsteine`G? <input type='gems' name='gems'>`c`n`n`0", true);
            output("<input type='submit' class='button' value='Bauen'>", true);
            addnav("", "houses.php?op=build&act=build2");
        }
        // continue building a house
        elseif ($_GET[act] == "build2") {
            $sql         = "SELECT houseid, housename, goldprice, gemprice, status FROM houses WHERE status='build' AND owner=" . $session['user']['acctid'];
            $result         = db_query($sql) or die(db_error(LINK));
            $row         = db_fetch_assoc($result);
            $paidgold     = (int) $_POST['gold'];
            $paidgems     = (int) $_POST['gems'];
            if (!empty($_POST['housename'])) {
                $housename = stripslashes($_POST['housename']);
            } else {
                $housename = stripslashes($row['housename']);
            }

            if ($session['user']['gold'] < $paidgold || $session['user']['gems'] < $paidgems) {
                output("`n`n`c`7Du hast nicht genug dabei!`c`n`n`0");
                addnav("Nochmal", "houses.php?op=build");
            } elseif ($session['user']['turns'] < 1) {
                output("`n`n`c`7Du bist zu müde, um heute noch an deinem Haus zu arbeiten!`c`n`n`0");
            } elseif ($paidgold < 0 || $paidgems < 0) {
                output("`n`n`c`7Versuch hier besser nicht zu schummeln!`c`n`n`0");
            } else {
                output("`n`n`c`7Du baust für `&$paidgold`7 Gold und `&$paidgems`7 Edelsteine an deinem Haus \"`&$housename`7\"...`c`n`n`0");
                output("`c`7Du verlierst einen Waldkampf.`c`n`n`0");
                $row['goldprice'] -= $paidgold;
                $session['user']['gold'] -= $paidgold;
                $session['user']['turns'] --;
                if ($row['goldprice'] < 0) {
                    output("`n`n`c`7Du hast die kompletten Goldkosten bezahlt und bekommst das überschüssige Gold zurück.`c`n`n`0");
                    // subtract negative value - so ist an addition ;)
                    $session['user']['gold'] -= $row['goldprice'];
                    $row['goldprice'] = 0;
                }
                $row['gemprice'] -= $paidgems;
                $session['user']['gems'] -= $paidgems;
                if ($row['gemprice'] < 0) {
                    output("`n`n`c`7Du hast die kompletten Edelsteinkosten bezahlt und bekommst überschüssige Edelsteine zurück.`c`n`n`0");
                    // subtract negative value - so ist an addition ;)
                    $session['user']['gems'] -= $row['gemprice'];
                    $row['gemprice'] = 0;
                }
                // finished building house
                if ($row['gemprice'] == 0 && $row['goldprice'] == 0) {
                    output("`c`n`n`b`7Glückwunsch!`b `GDein Haus ist fertig. Du bekommst `b`&" . getsetting('newhousekeys', 10) . "`b`G Schlüssel überreicht, von denen du `&" . (getsetting('newhousekeys', 10) - 1) . " `Gan andere weitergeben kannst und besitzt nun deine eigene kleine Burg.`0`n`n`c");
                    $session['user']['housekey'] = $row['houseid'];
                    $row['status']                 = 'ready';
                    addnews("`&" . $session['user']['name'] . "`9 hat das Haus `&{$row['housename']}`9 fertiggestellt.`0");
                    // add keys for the new house
                    $sql                         = '';
                    for ($i = 1; $i < getsetting('newhousekeys', 10); $i++) {
                        $sql .= ",('Hausschlüssel'," . $session['user']['acctid'] . ",'Schlüssel',{$row['houseid']},$i,0,0,'Schlüssel für Haus Nummer {$row['houseid']}')";
                    }
                    if ($sql != '') {
                        $sql = 'INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ' . substr($sql, 1);
                        db_query($sql);
                        if (db_affected_rows(LINK) == 0)
                            output("`c`n`n`\$Fehler`r: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin.`c`n`n`0");
                    }
                }
                // still some payment left
                else {
                    output("`n`n`c`GDu musst noch `&{$row['goldprice']}`G Gold und `&{$row['gemprice']}`G Edelsteine bezahlen, bis du einziehen kannst.`0`n`n`c");
                }
                $sql = "UPDATE houses SET goldprice={$row['goldprice']},gemprice={$row['gemprice']},housename='" . addslashes($housename) . "',status='{$row['status']}' WHERE houseid={$row['houseid']}";
                db_query($sql);
            }
        }
        addnav('Wohnviertel', 'houses.php');
        addnav('v?Wohnviertel verlassen', 'houses.php?op=leave');
        break;
    // end of building house
    // burgling houses part 1
    case 'einbruch':
        // player did not choose a house yet
        if (empty($_GET['id'])) {
            if (!empty($_POST['search'])) {
                // search for house number...
                if (strcspn($_POST['search'], "0123456789") <= 1) {
                    $search = "houses.houseid='" . (int) $_POST['search'] . "' AND ";
                }
                // search for house name
                else {
                    $search = "%";
                    for ($x = 0; $x < strlen($_POST['search']); $x++) {
                        $search .= $_POST['search']{$x} . "%";
                    }
                    $search = "houses.housename LIKE '" . $search . "' AND ";
                }
            } else {
                $search = '';
            }
            $ppp = 25; // Player Per Page to display
            if (empty($_GET['limit'])) {
                $page = 0;
            } else {
                $page = (int) $_GET['limit'];
                addnav("Vorherige Seite", "houses.php?op=einbruch&limit=" . ($page - 1));
            }
            $limit = ($page * $ppp) . "," . ($ppp + 1);

            output("`n`n`c<font face='Harrington' size='6'>
`XE`xi`én`íb`ór`òuch
</font>`c`0`n`n", true);
            output("`n`n`cDu fühlst dich wie ein Schwerverbrecher, als du zwischen den zahlreichen Häusern,`n
 die teils sehr vornehm wirken, hindurch streunerst.
Heute scheint dir der richtige Tag dich zu bereichern.`n
 Neugierig huscht dein Blick über die vielen Gebäude, in denen wahrlich Schätze lagern.`n
 Allerdings kannst du nicht erkennen, ob 
hinter den Mauern die Bewohner schlafen.`n
 Es ist also ein Risiko, wenn du dir unrechtmäßig Zutritt verschaffst.`n
 Möglicherweise findest du dich vielen starken Gegnern gegenüber.`n
Das könnte ein großer Anfang oder ein jämmerliches Ende sein.`n
 Wie wir`óst du `ídich `éents`xchei`Xden?`n`n`c`0");

            output("`n`n`c<form action='houses.php?op=einbruch' method='POST'>Nach Hausname oder Nummer`0<input name='search' value='{$_POST['search']}'> <input type='submit' class='button' value='Suchen'></form>`c`n`n", true);
            addnav("", "houses.php?op=einbruch");
            if ($session['user']['pvpflag'] == "5013-10-06 00:42:00")
                output("`n`n`c`ò(Du`ó has`ít Pv`éP-I`xmmu`Xnität gekauft. Diese verfällt, wenn du j`xetz`ét an`ígre`óifs`òt!)`c`0`n`n");
            output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td></tr>", true);

            // get possible targets
            $sql     = "SELECT houses.houseid, houses.housename,accounts.name FROM houses LEFT JOIN accounts ON accounts.acctid=houses.owner WHERE $search houses.status='ready' AND houses.owner!=" . $session['user']['acctid'] . " AND houses.locid='{$session['user']['specialmisc']['locid']}' ORDER BY houses.houseid ASC LIMIT $limit";
            $result     = db_query($sql) or die(db_error(LINK));
            // no houses found
            if (db_num_rows($result) == 0) {
                output("<tr><td colspan=4 align='center'>`n`n`c`&`iEs gibt momentan keine bewohnten Häuser!`i`c`n`n`0</td></tr>", true);
            } else {
                // more than one page to display
                if (db_num_rows($result) > $ppp)
                    addnav("Nächste Seite", "houses.php?op=einbruch&limit=" . ($page + 1));
                for ($i = 0; $i < db_num_rows($result); $i++) {
                    $row     = db_fetch_assoc($result);
                    $bgcolor = ($i % 2 == 1 ? "trlight" : "trdark");
                    output("<tr class='$bgcolor'><td align='right'>{$row['houseid']}</td><td><a href='houses.php?op=einbruch&id={$row['houseid']}'>{$row['housename']}</a></td><td>", true);
                    output("{$row['name']}</td></tr>", true);
                    addnav("", "houses.php?op=einbruch&id={$row['houseid']}");
                }
            }
            output("</table>", true);
            addnav("Umkehren", "houses.php");
        }
        // a house has already been chosen
        else {
            if ($session['user']['turns'] == 0 || $session['user']['playerfights'] == 0) {
                output("`n`n`cDu bist wirklich schon zu müde, um ein Haus zu überfallen.`c`n`n`0");
                addnav("Zurück", "houses.php");
            } else {
                output("`n`n`cDu näherst dich vorsichtig Haus Nummer`x {$_GET['id']}`ò.`c`0`n`n");
                $session['user']['specialmisc']['houseid']     = $_GET['id'];
                // check if user has a key of this house
                $sql                                         = "SELECT COUNT(id) AS zahl FROM items WHERE owner=" . $session['user']['acctid'] . " AND class='Schlüssel' AND value1=" . (int) $_GET['id'];
                $result                                         = db_query($sql) or die(db_error(LINK));
                $row                                         = db_fetch_assoc($result);
                if ($row['zahl'] > 0) {
                    // use key to enter house
                    output("`n`n`cAn der Haustür angekommen suchst du etwas, um die Tür möglichst unauffällig zu öffnen. Am besten dürfte dafür der Hausschlüssel geeignet sein, den du dabei hast.`n
                                        Wolltest du wirklich gerade in ein Haus einbrechen, für das du einen Schlüssel hast?`n`n`c`0");
                    addnav("Haus betreten", "houses.php?op=drin&id={$_GET['id']}");
                    addnav("Zurück", 'houses.php?op=leave');
                } else {
                    // fight against guard
                    output("`n`n`cDeine gebückte Haltung und der schleichende Gang machen eine Stadtwache aufmerksam...`n`n`c`0");
                    $result = getpvpdata('a.maxhitpoints', 'a.maxhitpoints DESC', 1);
                    if (db_num_rows($result) > 0) {
                        // somebody is in the house so let's weaken the guard
                        $row     = db_fetch_assoc($result);
                        $badguy     = array("creaturename" => "Stadtwache", "creaturelevel" => $session['user']['level'], "creatureweapon" => "Holzknüppel", "creatureattack" => $session['user']['attack'] + 100, "creaturedefense" => $session['user']['defence'] + 100, "creaturehealth" => abs($session['user']['maxhitpoints'] - $row['maxhitpoints']) + 1, "diddamage" => 10);
                    } else {
                        // nobody in the house - full power to the guard!
                        $badguy = array("creaturename" => "Stadtwache", "creaturelevel" => $session['user']['level'], "creatureweapon" => "starker Holzknüppel", "creatureattack" => $session['user']['attack'], "creaturedefense" => $session['user']['defence'] + 10, "creaturehealth" => abs($session['user']['maxhitpoints']), "diddamage" => 0);
                        $session['user']['playerfights'] --;
                    }
                    $session['user']['badguy']     = createstring($badguy);
                    $fight                         = true;
                }
            }
        } // end of house chosen
        break;
    // end of burgling houses part 1
    // player wants (or is made wanting ;)) to fight
    case 'fight':
        $fight     = true;
        break;
    // player tries to run away during fight
    case 'run':
            $badguy = createarray($session['user']['badguy']);
    // fight against guard
    if ($badguy['creaturename']=='Stadtwache') {
        output("`%Die Wache lässt dich nicht entkommen!`n");
        $session[user][reputation]--;
    }
    // fight against pet
    else {
        output("`%".$badguy['creaturename']."`% lässt dich nicht entkommen!`n");
    }
    $fight=true;

    // burgling house part 2
    case 'einbruch2':
        $badguy         = createarray($session['user']['badguy']);
        $fightpet     = false;
        // check for pet
        if ($badguy['creaturename'] == 'Stadtwache') {
            $sql     = 'SELECT accounts.petid AS pet, items.name, items.buff FROM accounts LEFT JOIN items ON accounts.petid=items.id WHERE accounts.house=' . $session['user']['specialmisc']['houseid'] . ' AND accounts.petfeed > NOW()';
            $result     = db_query($sql);
            if ($row     = db_fetch_assoc($result)) {
                if ($row['pet'] > 0) {
                    $petbuff                     = unserialize($row['buff']);
                    $badguy                         = array('creaturename'         => $row['name'],
                        'creaturelevel'         => $session['user']['level'],
                        'creatureweapon'     => $petbuff['name'],
                        'creatureattack'     => $petbuff['atkmod'],
                        'creaturedefense'     => $petbuff['defmod'],
                        'creaturehealth'     => $petbuff['regen'],
                        'diddamage'             => 0);
                    $session['user']['badguy']     = createstring($badguy);
                    $fight                         = $fightpet                     = true;
                    output('`n`n`cGerade willst du ins Haus schleichen, als du hinter dir plötzlich ein Knurren vernimmst.`0`n`n`c');
                }
            }
        }

        if (!$fightpet) {
            // run, forrest, run! ;)
            addnav("Flüchte", 'houses.php?op=leave');
            // fight against player
            $result     = getpvpdata('COUNT(*) AS athome', '', 0);
            $row     = db_fetch_assoc($result);
            // somebody at home, so lets fight...
            if ($row['athome'] > 0) {
                if ($row['athome'] == 1)
                    output("`n`n`cDir kommt ein misstrauischer Bewohner schwer bewaffnet entgegen. Er wird sich jeden Augenblick auf dich stürzen,`c`n ");
                else
                    output("`n`n`cDir kommen {$row['athome']} misstrauische Bewohner schwer bewaffnet entgegen. Der wahrscheinlich Stärkste von ihnen wird sich jeden Augenblick auf dich stürzen,`c`n");
                output("`c`òwenn du die Situation nicht sofort e`ónt`ísc`éhä`xrf`Xst.`c`0`n`n");
                addnav('Kämpfe', "houses.php?op=einbruch3&id={$session['user']['specialmisc']['houseid']}");
            }
            // yeah, lets take away everything valuable
            else {
                output("`n`n`cDu hast Glück, denn es scheint niemand daheim zu sein. Das wird sicher ein Kinderspiel.`c`n`n`0");
                addnav("Einsteigen", "houses.php?op=klauen&id={$session['user']['specialmisc']['houseid']}");
            }
        }
        break;
    // burgling house part 3
    case 'einbruch3':
        // finally go to heldengasse_pvp.php for fighting
        $result     = getpvpdata('a.login', 'a.maxhitpoints', 1);
        $row     = db_fetch_assoc($result);
        redirect("heldengasse_pvp.php?act=attack&bg=2&name=" . rawurlencode($row['login']));
        break;
    // robbing something
    case 'klauen':
        if (empty($_GET['id'])) {
            output("`n`n`cUnd jetzt? Bitte benachrichtige den Admin. Ich weiß nicht, was ich jetzt tun soll...`c`n`n`0");
            addnav("Zurück", "houses.php?op=leave");
            break;
        }

        $sql     = "SELECT owner FROM houses WHERE houseid=" . $session['user']['specialmisc']['houseid'] . " ORDER BY houseid ASC";
        $result     = db_query($sql) or die(db_error(LINK));
        $hdata     = db_fetch_assoc($result);
        addnav("Zurück", 'houses.php?op=leave');
        // if treasury-module is installed, gimme some money...
        if ($mid     = module_builtin('treasury', $session['user']['specialmisc']['houseid'])) {
            $goldinhouse = getmoduledata($mid, 'gold', $session['user']['specialmisc']['houseid']);
            $gemsinhouse = getmoduledata($mid, 'gems', $session['user']['specialmisc']['houseid']);
            // found money, so take 5-15% of it
            if ($goldinhouse > 0 || $gemsinhouse > 0) {
                $getgold = e_rand($goldinhouse * 0.05, $goldinhouse * 0.15);
                $getgems = e_rand($gemsinhouse * 0.05, $gemsinhouse * 0.15);
                // bugfix for 1 gold-exploit (user deposits 1 gold, bad guy always gets 0)
                if ($getgold == 0)
                    $getgold = $goldinhouse;
                if ($getgems == 0)
                    $getgems = $gemsinhouse;

                $session['user']['gold'] += $getgold;
                $session['user']['gems'] += $getgems;
                // take the money away from house
                setmoduledata($mid, 'gold', $goldinhouse - $getgold, $session['user']['specialmisc']['houseid']);
                setmoduledata($mid, 'gems', $gemsinhouse - $getgems, $session['user']['specialmisc']['houseid']);
                // inform both affected players
                if ($getgold > 0 && $getgems > 0)
                    $str = "`^$getgold Gold`@ und `%$getgems Edelsteine`@";
                elseif ($getgold > 0)
                    $str = "`^$getgold Gold`@";
                else
                    $str = "`%$getgems Edelsteine`@";
                output("`n`n`cEs gelingt dir, $str aus dem Schatz zu klauen`X!");

                $mailadd = $newsadd = '';
                // if user got too less money, destroy furniture
                if ($getgold < $session['user']['level'] * 10 && $goldinhouse < 2500) {
                    // chance of 5%+X to destroy furniture...
                    $chance     = e_rand(1, 21);
                    $left500 = floor((2500 - $goldinhouse) / 500);
                    if ($chance <= $left500) {
                        // check if there's furniture to destroy
                        $sql     = 'SELECT id,name FROM items WHERE class="Möbel" AND value1="' . $session['user']['specialmisc']['houseid'] . '" ORDER BY RAND(' . e_rand() . ') LIMIT 1';
                        $result     = db_query($sql) or die(db_error(LINK));
                        if ($row     = db_fetch_assoc($result)) {
                            output('`n`n`cDu lässt deiner Enttäuschung über die miese Beute freien Lauf und zerstörst dabei `x' . $row['name'] . '`ò.`0`c`n`n');
                            $sql     = 'DELETE FROM items WHERE id="' . $row['id'] . '"';
                            db_query($sql);
                            $mailadd = " sowie `í{$row['name']} `xzerstört`ò";
                            $newsadd = " und zerstört dabei Möbel";
                        }
                    }
                }

                addnews("`X" . $session['user']['name'] . "`ò erbeutet Gold bei einem Einbruch$newsadd!");
                if ($getgold > 0 && $getgems > 0)
                    $str = "`x$getgold Gold`ò und `x$getgems Edelsteine`ò";
                elseif ($getgold > 0)
                    $str = "`x$getgold Gold`ò";
                else
                    $str = "`x$getgems Edelsteine`ò";
                systemmail($hdata['owner'], "`\$Einbruch!`0", "`XJe`xma`énd`í is`ót i`òn dein Haus eingebrochen und hat`x $str `òerbeutet$mailadd!`0");
                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-" . $session['user']['specialmisc']['houseid'] . "'," . $hdata['owner'] . ",'/me `6entdeckt, dass bei einem Einbruch $str gestohlen$mailadd wurden.')";
                db_query($sql) or die(db_error(LINK));
                break;
            }
        }

        // got no money... damn!
        output('`n`n`cLeider findest du nichts, das du stehlen könntest.`c`n`n`0');
        // chance of 5% to destroy furniture...
        if (e_rand(1, 21) <= 5) {
            // check if there's furniture to destroy
            $sql     = 'SELECT id,name FROM items WHERE class="Möbel" AND value1="' . $session['user']['specialmisc']['houseid'] . '" ORDER BY RAND(' . e_rand() . ') LIMIT 1';
            $result     = db_query($sql) or die(db_error(LINK));
            if ($row     = db_fetch_assoc($result)) {
                output('`n`n`c`XDu `xlä`éss`ít d`óei`òner Enttäuschung freien Lauf und zerstörst dabei `x' . $row['name'] . '`ò.');
                addnews("`x" . $session['user']['name'] . "`ò zerstört Möbel bei einem Einbruch!`0");
                systemmail($hdata['owner'], "`\$Einbruch!`0", "`XJe`xma`énd `íis`ót i`òn dein Haus eingebrochen und hat `x{$row['name']} `òzerstört!`0");
                $sql = 'DELETE FROM items WHERE id="' . $row['id'] . '"';
                db_query($sql);
                $sql = "INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-" . $session['user']['specialmisc']['houseid'] . "'," . $hdata['owner'] . ",'/me `6entdeckt, dass bei einem Einbruch `^{$row['name']} `2zerstört`6 wurde.')";
                db_query($sql) or die(db_error(LINK));
            }
        }
        break;
    // end of robbing something
    // buy a house - we don't do it here anymore, so it's useless...
    case 'buy':
    // sell a house - same as buy...
    case 'sell':
    // player wants to enter a house
    case 'enter':
        output("`n`n`cDu hast Zugang zu folgenden Häusern:`c`n`n`0");
        // delete possible user-in-house-flags
        $sql = "UPDATE items SET hvalue=0 WHERE hvalue>0 AND owner=" . $session['user']['acctid'] . " AND class='Schlüssel'";
        db_query($sql) or die(sql_error($sql));
        // search houses the player can enter
        //Eigenes Haus
        output("<table border='0' align='center' cellspacing='1' cellpadding='2' bgcolor='#444455'>", true);
        output("<tr class='trhead'><td colspan='2'>`c`bDein Haus:`b`c</td><tr>", true);
        output("<tr class='trhead'><td>`c`bHausNr.`b`c</td><td>`c`bName`b`c</td></tr>", true);
        if ($session['user']['house'] > 0 && $session['user']['housekey'] > 0) {
            $sql     = "SELECT houseid,housename FROM houses WHERE houseid=" . $session['user']['house'] . " AND locid='{$session['user']['specialmisc']['locid']}' ORDER BY houseid DESC LIMIT 25";
            $result2 = db_query($sql) or die(db_error(LINK));
            if ($row2     = db_fetch_assoc($result2)) {
                output("<tr class='trdark'><td align='center'>{$row2['houseid']}</td><td><a href='houses.php?op=drin&id={$row2['houseid']}'>{$row2['housename']}</a></td></tr>", true);
                addnav("", "houses.php?op=drin&id={$row2['houseid']}");
            }
        }
        // house is not ready yet
        elseif ($session['user']['house'] > 0) {
            output("<tr><td colspan=3 align='center'>`&`iDein Haus ist noch im Bau oder steht zum Verkauf`i`0</td></tr>", true);
        }
        output("</table>", true);

        //Schlüssel für andere Häuser
        $sql     = "SELECT i.value1,h.housename,a.name AS ownername FROM items i LEFT JOIN houses h ON i.value1=h.houseid LEFT JOIN accounts a ON a.acctid=h.owner WHERE i.owner=" . $session['user']['acctid'] . " AND (i.class='Schlüssel') AND h.locid='{$session['user']['specialmisc']['locid']}' AND h.owner!=" . $session['user']['acctid'] . " GROUP BY i.value1 ORDER BY i.id ASC";
        $result     = db_query($sql) or die(db_error(LINK));
        output("`n`n");
        output("<table border='0' align='center' cellspacing='1' cellpadding='2' bgcolor='#444455'>", true);
        output("<tr class='trhead'><td colspan='3'>`c`bSchlüssel für andere Häuser:`c`b</td><tr>", true);
        output("<tr class='trhead'><td>`c`bHausNr.`b`c</td><td>`c`bName`b`c</td><td>`c`bBesitzer`b`c</td></tr>", true);

        $ppp = 25; // Player Per Page +1 to display
        if (empty($_GET['limit'])) {
            $page = 0;
        } else {
            $page = (int) $_GET['limit'];
            addnav("Vorherige Straße", "houses.php?limit=" . ($page - 1) . "&location=" . $session['user']['specialmisc']['locid']);
        }
        $limit = ($page * $ppp) . "," . ($ppp + 1);
        if (db_num_rows($result) > $ppp)
            addnav("Nächste Seite", "houses.php?op=enter&limit=" . ($page + 1));
        if (db_num_rows($result) == 0) {
            output("<tr class='trdark'><td colspan=3 align='center'>`&`iDu hast keinen Schlüssel`i`0</td></tr>", true);
        } else { // show houses
            for ($i = 0; $i < db_num_rows($result); $i++) {
                $item     = db_fetch_assoc($result);
                $bgcolor = ($i % 2 == 1 ? "trlight" : "trdark");
                // don't show own house - we did this before ;)
                if ($item['value1'] != $session['user']['house']) {
                    output("<tr class='$bgcolor'><td align='center'>{$item['value1']}</td><td><a href='houses.php?op=drin&id={$item['value1']}'>{$item['housename']}</a></td><td>", true);
                    if ($item['ownername'] != '')
                        output($item['ownername'] . '`0', true);
                    else
                        output('`iverlassen`i');
                    output("</td></tr>", true);
                    addnav("", "houses.php?op=drin&id={$item['value1']}");
                }
            }
        }
        output("</table>", true);

        //Einladungen
        $sql3     = "SELECT i.value1,h.housename,a.name AS ownername FROM items i LEFT JOIN houses h ON i.value1=h.houseid LEFT JOIN accounts a ON a.acctid=h.owner WHERE i.owner=" . $session['user']['acctid'] . " AND (i.class='Einladung') AND h.locid='{$session['user']['specialmisc']['locid']}' GROUP BY i.value1 ORDER BY i.id ASC";
        $result3 = db_query($sql3) or die(db_error(LINK));
        output("`n`n");
        output("<table border='0' align='center' cellspacing='1' cellpadding='2' bgcolor='#444455'>", true);
        output("<tr class='trhead'><td colspan='3'>`c`bEinladungen für andere Häuser:`b`c</td><tr>", true);
        output("<tr class='trhead'><td>`c`bHausNr.`b`c</td><td>`c`bName`b`c</td><td>`c`bBesitzer`b`c</td></tr>", true);

        $ppp2 = 25; // Player Per Page +1 to display
        if (empty($_GET['limit'])) {
            $page2 = 0;
        } else {
            $page2 = (int) $_GET['limit'];
            addnav("Vorherige Straße", "houses.php?limit=" . ($page2 - 1) . "&location=" . $session['user']['specialmisc']['locid']);
        }
        $limit2 = ($page2 * $ppp2) . "," . ($ppp2 + 1);
        // show houses
        if (db_num_rows($result3) > $ppp)
            addnav("Nächste Seite", "houses.php?op=enter&limit=" . ($page + 1));
        if (db_num_rows($result3) == 0) {
            output("<tr class='trdark'><td colspan=3 align='center'>`&`iDu hast keine Einladungen`i`0</td></tr>", true);
        } else {
            for ($i = 0; $i < db_num_rows($result3); $i++) {
                $item2     = db_fetch_assoc($result3);
                $bgcolor = ($i % 2 == 1 ? "trlight" : "trdark");
                // don't show own house - we did this before ;)
                if ($item2['value1'] != $session['user']['house']) {
                    output("<tr class='$bgcolor'><td align='center'>{$item2['value1']}</td><td><a href='houses.php?op=drin&id={$item2['value1']}&module=13'>{$item2['housename']}</a></td><td>", true);
                    if ($item2['ownername'] != '')
                        output($item2['ownername'] . '`0', true);
                    else
                        output('`iverlassen`i');
                    output("</td></tr>", true);
                    addnav("", "houses.php?op=drin&id={$item2['value1']}&module=13");
                }
            }
        }
        output("</table>`n`n`n`n", true);

        addnav("Orte");
        addnav('Im Wohnviertel', "houses.php");
        addnav('v?Wohnviertel verlassen', 'houses.php?op=leave');
        break;
    // main part: player has entered a house. let's start with modules...
    case 'drin':
        $houseid = isset($_GET['id']) ? $_GET['id'] : $session['user']['specialmisc']['houseid'];

        $oldSet                             = $session['user']['specialmisc'];
        $result                             = db_fetch_assoc(db_query("SELECT h.*, hc.* FROM houses h, houseconfig hc WHERE hc.locid = h.locid AND h.houseid = '" . $houseid . "'"));
        $session['user']['specialmisc']     = $result;
        if (!isset($session['user']['standort'])) {
            $session['user']['standort'] = $result['locname'];
        }
        $session['user']['specialmisc'] = array_merge($oldSet, $session['user']['specialmisc']);

        // flag if default navs should be shown or not
        $shownavs = false;
        // any module selected? then show it...
        if (!empty($_GET['module'])) {
            $sql     = 'SELECT modulefile, modulename FROM housemodules WHERE moduleid="' . $_GET['module'] . '"';
            $result     = db_query($sql);
            if ($row     = db_fetch_assoc($result)) {
                if ($_GET['module'] == getsetting('defaulthousemodule', '1'))
                    $shownavs                                         = true;
                $session['user']['specialmisc']['modulefile']     = $row['modulefile'];
                $session['user']['specialmisc']['modulename']     = $row['modulename'];
            } else
                redirect('houses.php?op=drin');
        }
        // no module selected - get the default module
        elseif (empty($session['user']['specialmisc']['modulefile']) || isset($_GET['module'])) {
            $sql     = 'SELECT modulefile, modulename FROM housemodules WHERE moduleid="' . getsetting('defaulthousemodule', '1') . '"';
            $result     = db_query($sql);
            if ($row     = db_fetch_assoc($result)) {
                $shownavs                                         = true;
                $session['user']['specialmisc']['modulefile']     = $row['modulefile'];
                $session['user']['specialmisc']['modulename']     = $row['modulename'];
            } else
                redirect('houses.php');
        }
        elseif (getmoduleid($session['user']['specialmisc']['modulename']) == getsetting('defaulthousemodule', '1')) {
            $shownavs = true;
        }

        // so for now, show module!
        require_once('housemodules/' . $session['user']['specialmisc']['modulefile']);
        $function = 'module_show_' . $session['user']['specialmisc']['modulename'];
        $function();

        /*
          -----
          I see no use for this in Omar.
          So I changed it to fit better in my own system, which allows you to create your own rooms.
          Negative aspect: the bigger part of the module System will disappear. But I think it's worth doing it :)
          -----

          // if module doesn't set any navs, it's the default module - so set navs now!
          if ($shownavs || !is_array($session['allowednavs']) || count($session['allowednavs'])==0) {
          // get modulenavs now
          if ($session['user']['specialmisc']['houseid']==$session['user']['house']) {
          $for = 'owner';
          }
          else $for = 'guest';
          $modules = array();
          $lastcategory = '';
          $sql = 'SELECT hm.moduleid, hm.linkcategory, hm.linktitle
          FROM housemodules hm
          LEFT JOIN housemoduledata hmd
          ON hmd.moduleid=hm.moduleid
          AND hmd.houseid="'.$session['user']['specialmisc']['houseid'].'"
          AND hmd.name="#activated#"
          WHERE (hm.built_in="1" OR hmd.value="1")
          AND hm.moduleid!="'.getsetting('defaulthousemodule','1').'"
          AND FIND_IN_SET("'.$for.'",hm.showto)>0
          ORDER BY hm.linkorder ASC';
          $result = db_query($sql);
          while ($row = db_fetch_assoc($result)) {
          if ($lastcategory!=$row['linkcategory'] && $row['linkcategory']!='') {
          addnav($row['linkcategory']);
          $lastcategory = $row['linkcategory'];
          }
          addnav($row['linktitle'],'houses.php?op=drin&module='.$row['moduleid']);
          }
          if ($lastcategory!='Sonstiges') addnav('Sonstiges');
          addnav('Wohnviertel','houses.php');
          addnav('v?Wohnviertel verlassen','houses.php?op=leave');
          } */
        if ($shownavs || !is_array($session['allowednavs']) || count($session['allowednavs']) == 0) {
            $room_sql     = 'SELECT 
                                roomid,
                                is_category,
                                linkname,
                                open
                         FROM
                                houserooms
                         WHERE 
                                house = ' . $session['user']['specialmisc']['houseid'] . '
                         ORDER BY
                                sort ASC';
            $room_result = db_query($room_sql);
            $n             = db_num_rows($room_result);

            if ($n > 0) {
                while ($room = db_fetch_assoc($room_result)) {
                    if ($session['user']['specialmisc']['houseid'] != $session['user']['house'] && !$room['open'])
                        continue;

                    if ($room['is_category'])
                        addnav(stripslashes($room['linkname']));
                    else
                    
                
                
                        addnav(stripslashes($room['linkname']),'houses.php?op=drin&module=19&roomid=' . $room['roomid']);
                }
            }


            if ($session['user']['specialmisc']['houseid'] == $session['user']['house'] ||
                ($session['user']['superuser'] == 3 || $session['user']['superuser'] == 5))
                $for             = 'owner';
            else
                $for             = 'guest';
            $modules         = array();
            $lastcategory     = '';
            $sql             = 'SELECT 
                            hm.moduleid, 
                            hm.linkcategory, 
                            hm.linktitle
                    FROM 
                            housemodules hm
                    LEFT JOIN 
                            housemoduledata hmd
                    ON 
                            hmd.moduleid=hm.moduleid
                        AND 
                            hmd.houseid="' . $session['user']['specialmisc']['houseid'] . '"
                        AND 
                            hmd.name="#activated#"
                    WHERE 
                            (hm.built_in="1" OR hmd.value="1")
                        AND 
                            hm.moduleid!="' . getsetting('defaulthousemodule', '1') . '"
                        AND 
                            FIND_IN_SET("' . $for . '",hm.showto)>0
                    ORDER BY 
                            hm.linkorder ASC';
            $result             = db_query($sql);

            while ($row = db_fetch_assoc($result)) {
                if ($lastcategory != $row['linkcategory'] && $row['linkcategory'] != '') {
                    if ($row['moduleid'] != 19) {
                        addnav($row['linkcategory']);
                        $lastcategory = $row['linkcategory'];
                    }
                }
                if ($row['moduleid'] != 19)
                    addnav($row['linktitle'], 'houses.php?op=drin&module=' . $row['moduleid']);
            }


            addnav('Wohnviertel', 'houses.php');
            addnav('v?Wohnviertel verlassen', 'houses.php?op=leave');
        }
        break;



    // show the start page of houses script
    default:
        if (!empty($session['user']['specialmisc']['houseid'])) {
            $session['user']['specialmisc']['houseid']         = 0;
            $session['user']['specialmisc']['modulefile']     = '';
            $session['user']['specialmisc']['modulename']     = '';
        }
        // check if you have a key for a house located here
        $sql     = "SELECT COUNT(i.id) AS keycount FROM items i LEFT JOIN houses h ON h.houseid=i.value1 WHERE i.owner=" . $session['user']['acctid'] . " AND (i.class='Schlüssel' OR i.class='Einladung') AND h.locid='{$session['user']['specialmisc']['locid']}'";
        $result     = db_query($sql) or die(db_error(LINK));
        $row     = db_fetch_assoc($result);
        // search own house
        if ($session['user']['house']) {
            $sql         = 'SELECT locid FROM houses WHERE houseid=' . $session['user']['house'];
            $result         = db_query($sql);
            $ownhouse     = db_fetch_assoc($result);
        }
        // add link to enter house if found any (and if player has a key naturally)
        if ($row['keycount'] > 0 || ($ownhouse['locid'] == $session['user']['specialmisc']['locid'] && $session['user']['housekey'] > 0))
            addnav("Haus betreten", "houses.php?op=enter");

        //output("`n`n`n`n<font face='Harrington' size='6'><u>", true);
        //output('`c`é' . $session['user']['specialmisc']['locname'] . '`0`c');
        //output("</u></font>`n`n", true);
        //output("`c<table width='530'><tr>
//<td align='justify' width='300'>
//`XDe`xin `éWe`íg f`óüh`òrt dich etwas abseits d`óer `íSt`éad`xt, `Xwo 
//`Xsc`xhe`éin`íba`ór d`òie Bewohner leben. Hier `óre`íih`éen `xsi`Xch 
//`Xvi`xel`ée v`íer`ósc`òhiedene Behausungen ane`óin`ían`éde`xr. `XEs 
//`Xsc`xhe`éin`ít d`óir`ò, als wäre hier alles zu `ófi`índ`éen`x, v`Xom 
//`XSc`xhl`éos`ís b`óis`ò zur baufälligen Hütte. Du v`óer`ísi`énk`xst `Xin 
//`XGe`xda`énk`íen`ó, w`òelches Haus am besten z`óu d`íir `épa`xss`Xen 
//`Xwü`xrd`ée o`íde`ór o`òb du es gut finden `ówü`írd`ées`xt m`Xit 
//`XJe`xma`énd`íem`ó zu`òsammenzuziehen. `óUm `íme`éhr `xüb`Xer 
//`Xdi`xes`ées `íVi`óer`òtel zu erfahren machs`ót d`íu d`éic`xh a`Xuf 
//`Xzw`xis`éch`íen`ó de`òn Häusern etwas umherzus`óch`íle`énd`xer`Xn. 
//</td>
//<td width='30'></td>
//<td>
//<img src='images/places/saint/ph.jpg' width='200' hight='200'>
//</td>
//</tr></table>`c`n`n`0", true);

        // search house
        if (!empty($_POST['search'])) {
            if (strcspn($_POST['search'], "0123456789") <= 1) {
                $search = "houses.houseid=" . (int) $_POST['search'] . " AND ";
            } else {
                $search = "%";
                for ($x = 0; $x < strlen($_POST['search']); $x++) {
                    $search .= substr($_POST['search'], $x, 1) . "%";
                }
                $search = "housename LIKE '" . $search . "' AND ";
            }
        } else {
            $search = "";
        }
        // show pages (streets)
        $ppp = 30; // Player Per Page +1 to display
        if (empty($_GET['limit'])) {
            $page = 0;
        } else {
            $page = (int) $_GET['limit'];
            addnav("Vorherige Straße", "houses.php?limit=" . ($page - 1));
        }
        $limit     = ($page * $ppp) . "," . ($ppp + 1);
        $sql     = "SELECT houses.houseid,houses.status,houses.owner,houses.housename,houses.description_out,houses.house_ava,accounts.name AS schluesselinhaber
                    FROM houses
                    LEFT JOIN accounts ON accounts.acctid=houses.owner
                    WHERE $search houses.locid='{$session['user']['specialmisc']['locid']}'
                    ORDER BY houseid ASC LIMIT $limit";
        output("`c<form action='houses.php' method='POST'>`XNac`xh Ha`éusn`íame`ó ode`òr Nummer <input name='search' value='{$_POST['search']}'> <input type='submit' class='button' value='Suchen'></form>`c`n`n", true);
        addnav("", 'houses.php');
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td><td>`bStatus`b</td></tr>", true);
        $result     = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result) > $ppp)
            addnav("Nächste Straße", "houses.php?limit=" . ($page + 1) . "&location=" . $session['user']['specialmisc']['locid']);
        // no houses found
        if (db_num_rows($result) == 0) {
            output("<tr><td colspan=4 align='center'>`&`iEs gibt noch keine Häuser`i`0</td></tr>", true);
        }
        // list houses
        else {

            for ($i = 0; $i < db_num_rows($result); $i++) {



                $row     = db_fetch_assoc($result);
                $bgcolor = ($i % 2 == 1 ? "trlight" : "trdark");
                output("<tr class='$bgcolor'><td align='right'>{$row['houseid']}</td><td><a href='houses.php?op=bio&id={$row['houseid']}' onmouseover=\"TagToTip('" . $row['houseid'] . "',TITLEBGCOLOR,'#550000' , BGCOLOR, '#050505',FONTCOLOR, '#EEEEEE' ,BORDERWIDTH, 2,BORDERCOLOR,'#880000', TITLE, 'Kurzinformationen über " . preg_replace("/[`].|#[0-9a-fA-F]{6}/", "", $row['housename']) . "`0')\" onmouseout=\"UnTip()\">{$row['housename']}</a></td><td>", true);
                /*
                  if($row['house_ava']){
                  $table_width = 100;

                  $ava = img_resize($row['house_ava'],100,1)." border=\"1\" alt=\"".preg_replace("/[`]./","",$row['housename'])."\">";
                  }else{
                  $table_width = 100;
                  $ava = "Kein Avatar";
                  } */

                output("<span id=" . $row['houseid'] . ">
                            <table>
                                <tr>
                                    <td width=\"" . $table_width . "\">`c" . $ava . "`c</td>
                                    <td width=\"200\">" . $row['description_out'] . "</td>
                                </tr>
                            </table>
                        </span>", true);

                addnav("", "houses.php?op=bio&id={$row['houseid']}");
                output("{$row['schluesselinhaber']}", true);
                output("</td><td>", true);
                if ($row['status'] == 'build') {
                    if ($row['owner'] > 0)
                        output("`6im Bau`0");
                    else
                        output("`\$Bauruine`0");
                }
                elseif ($row['status'] == 'ready') {
                    if ($row['owner'] > 0)
                        output("`!bewohnt`0");
                    else
                        output("`4verlassen`0");
                }
                elseif ($row['status'] == 'sell')
                    output("`^zum Verkauf`0");
                output("</tr>", true);
            }
        }
        output("</table>", true);
        // player owns a house
        if ($session['user']['housekey'] > 0) {
            output("`n`n`cStolz schwingst du den Schlüssel zu deinem Haus im Gehen hin und her.`c`n`n`n`n");
        }

        addnav("Wohnviertel");


        // show link for building a house
        if ($session['user']['housekey'] == 0) {
            if (($session['user']['house'] == 0 || $ownhouse['locid'] == $session['user']['specialmisc']['locid']) && $session['user']['specialmisc']['build'] == 1)
                addnav("Haus bauen", "houses.php?op=build");
        }
        // show link for pvp
        if (getsetting("pvp", 1) == 1 && $session['user']['specialmisc']['rob'] == 1)
            addnav("Einbrechen", "houses.php?op=einbruch");

        addnav("Bauamt", "houseshop.php");

        if ($session['user']['specialmisc']['locid'] == 1) {
            addnav("Weitere Orte");
            //addnav("Zwischen den Häusern", "houses_streets.php?op=hauptstrasse");
            
        viewcommentary("jacksonsquare_kreolischevillen","Hinzufügen:`0");
        }

        if ($session['user']['specialmisc']['locid'] == 2) {

        viewcommentary("moonwalk_hausboote","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 3) {

        viewcommentary("royalstreet_bungalows","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 4) {

        viewcommentary("upperwards_apartmentkomplex","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 5) {
            addnav("Garden District Streets");
            addnav("Jackson Avenue", "houses_streets.php?op=jacksonavenue");
            addnav("Louisiana Avenue", "houses_streets.php?op=louisianaavenue");
            addnav("Magazine Street", "houses_streets.php?op=magazinestreet");
        viewcommentary("uptown_gardendistrict_villen","Hinzufügen:`0");
        }

        if ($session['user']['specialmisc']['locid'] == 6) {

        viewcommentary("bayou_wohnsiedlung","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 7) {

        viewcommentary("centralcity_wohnungen","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 8) {

        viewcommentary("gentilly_campus_wohnheim","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 9) {

        viewcommentary("mythalgiers_hellesviertel_wohnviertel","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 10) {

        viewcommentary("mythalgiers_dunklesviertel_wohnviertel","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 11) {

        viewcommentary("zhul_wohnviertel_reich","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 12) {

        viewcommentary("deassos_wohnviertel","Hinzufügen:`0");

        }

        if ($session['user']['specialmisc']['locid'] == 13) {

        viewcommentary("americanquarter_lafayettesquare","Hinzufügen:`0");

        }
        addnav("Zurück");
        addnav('v?Wohnviertel verlassen', 'houses.php?op=leave');

        break; // we really don't need it, but... why not? :D
}

// let's fight, boy!
if ($fight) {
    if (count($session['bufflist']) > 0 && is_array($session['bufflist']) || $_GET['skill'] != "") {
        $_GET['skill']                     = "";
        $session['user']['buffbackup']     = serialize($session['bufflist']);
        $session['bufflist']             = array();
        output("`c`n`n`&Die ungewohnte Umgebung verhindert den Einsatz deiner besonderen Fähigkeiten!`0`n`n`c");
    }
    include "battle.php";

    if ($victory) {
        addnav("H?Weiter zum Haus", "houses.php?op=einbruch2&id={$session['user']['specialmisc']['houseid']}");
        addnav('Wohnviertel', "houses.php");
        addnav('v?Wohnviertel verlassen', "houses.php?op=leave");
        // check for pet
        if ($badguy['creaturename'] == 'Stadtwache') {
            output("`n`n`c`&Du hast die Stadtwache besiegt und der Weg zum Haus ist frei!`nDu bekommst ein paar Erfahrungspunkte.`c`0`n`n");
            $session['user']['experience'] += $session['user']['level'] * 10;
            $session['user']['turns'] --;
        } else {
            output('`c`n`n`9' . $badguy['creaturename'] . '`& zieht sich jaulend zurück und gibt den Weg zum Haus frei!`c`0`n`n');
        }
        $badguy = array();
    } elseif ($defeat) {
        // check for pet
        if ($badguy['creaturename'] == 'Stadtwache') {
            output("`n`n`c`&Die Stadtwache hat dich besiegt. Du bist tot!`nDu verlierst 10% deiner Erfahrungspunkte, aber kein Gold.`nDu kannst morgen wieder kämpfen.`c`n`n`0");
            $session['user']['hitpoints']     = 0;
            $session['user']['alive']         = false;
            $session['user']['experience']     = round($session['user']['experience'] * 0.9);
            addnews("`%" . $session['user']['name'] . "`9 wurde von der Stadtwache bei einem Einbruch besiegt.`0");
            addnav('Tägliche News', 'news.php');
        } else {
            output('`n`n`c`&' . $badguy['creaturename'] . '`r hat dich besiegt. Du liegst schwer verletzt am Boden!`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.`0`n`n`c');
            $session['user']['hitpoints'] = 1;
            $session['user']['charm'] -= 3;
            addnews("`&" . $session['user']['name'] . "`9 stieß bei einem Einbruch auf unerwartete Gegenwehr und verletzte sich schwer.`0");
            addnav('Davonkriechen', "houses.php?op=leave");
        }
        $session['user']['badguy'] = '';
    } else {
        fightnav(false, true);
    }
}

output('`n`n');
output('`c`b&copy; by <a href="http://logd.chaosonline.de" target="_blank">Chaosmaker</a>`b`c', true);

checkday();


page_footer();
?>

