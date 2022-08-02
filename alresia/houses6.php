<?php

//20050708

/*
 * Local changes:
 *
    # gold transfer:
    INSERT INTO housemoduledata (moduleid, name, houseid, value)
        SELECT {id-of-treasury}, 'gold', houseid, gold FROM houses6 WHERE status=1;
    INSERT INTO housemoduledata (moduleid, name, houseid, value)
        SELECT {id-of-treasury}, 'gems', houseid, gems FROM houses6 WHERE status=1;

    INSERT INTO `houseconfig` ( `locid` , `location` , `locname` , `buy` , `sell` , `build` , `rob` , `defaultgoldprice` , `defaultgemprice` , `buildprice_increase` )
    VALUES (
    '', 'village.php', 'Wohnviertel', '1', '1', '1', '1', '30000', '50', '5'
    );

    ALTER TABLE `houses6` CHANGE `status` `status` ENUM( 'build', 'ready', 'sell' ) DEFAULT 'build' NOT NULL,
                                CHANGE `gold` `goldprice` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL ,
                                CHANGE `gems` `gemprice` INT( 10 ) UNSIGNED DEFAULT '0' NOT NULL,
                                CHANGE `location` `locid` INT( 10 ) UNSIGNED DEFAULT '1' NOT NULL;
    UPDATE `houses6` SET `locid`=1;
    # this will delete all stored gold; warn the people! ;)
    UPDATE `houses6` SET `status`='ready' WHERE `status`='build';
    UPDATE `houses6` SET `status`='build' WHERE `status`='' OR owner=0;
    UPDATE `houses6` SET `goldprice`=0,`gemprice`=0 WHERE `status`!='build';
    UPDATE `houses6` SET `goldprice`=30000-`goldprice`, `gemprice`=50-`gemprice` WHERE `status`='build';
 *
 *
 */

/******************************************
 *
 * Author: Daniel Rathjen <webmaster@chaosonline.de>
 * Version: 1.0
 * Server: biosLoGD Experimental Server
 * URL: http://logd.chaosonline.de
 *
 * This is a rewrite of anpera's houses6 script:
 *   Author: anpera
 *   Email:logd@anpera.de
 *   URL: http://www.anpera.net/forum/viewtopic.php?t=323
 * This script still consists of his ideas and parts of his code, so all homages to him please ;)
 *
 * Purpose: Same as the old houses6 script...
 *
 * Features:
 *        - Build house (if allowed)
 *        - Buy house (if allowed)
 *        - Sell house (if allowed)
 *        - Sleeping in a house
 *        - Storing gold in a house (module 'treasury')
 *        - Adding useful modules (rooms)
 *
 * Some important information:
 *
    #
    # Tabellenstruktur für Tabelle `houseconfig`
    #
    DROP TABLE IF EXISTS `houseconfig`;
    CREATE TABLE `houseconfig` (
      `locid` int(10) unsigned NOT NULL auto_increment,
      `location` varchar(255) NOT NULL default 'village.php',
      `locname` varchar(50) NOT NULL default 'Wohnviertel',
      `buy` enum('0','1') NOT NULL default '0',
      `sell` enum('0','1') NOT NULL default '0',
      `build` enum('0','1') NOT NULL default '0',
      `rob` enum('0','1') NOT NULL default '0',
      `defaultgoldprice` int(10) unsigned NOT NULL default '30000',
      `defaultgemprice` int(10) unsigned NOT NULL default '50',
      `buildprice_increase` tinyint(3) unsigned NOT NULL default '0',
      PRIMARY KEY (`locid`)
    ) TYPE=MyISAM COMMENT='Konfiguration der Wohnanlagen - was ist erlaubt?';

    #
    # Tabellenstruktur für Tabelle `housemodules`
    #
    CREATE TABLE `housemodules` (
      `moduleid` int(10) unsigned NOT NULL auto_increment,
      `modulefile` varchar(50) NOT NULL default 'default.php',
      `modulename` varchar(50) NOT NULL default 'living_room',
      `moduleauthor` varchar(50) NOT NULL default '',
      `moduleversion` varchar(20) NOT NULL default '',
      `built_in` enum('0','1') NOT NULL default '1',
      `linkcategory` varchar(50) NOT NULL default 'Räume',
      `linktitle` varchar(50) NOT NULL default '',
      `linkorder` smallint(3) unsigned NOT NULL default '1',
      `showto` SET( 'owner', 'guest' ) DEFAULT 'owner,guest' NOT NULL,
      PRIMARY KEY (`moduleid`),
      UNIQUE `modulename` (`modulename`),
      KEY `built_in` (`built_in`),
      KEY `linkorder` (`linkorder`),
      KEY `showto` (`showto`)
    ) TYPE=MyISAM COMMENT='Module der Häuser';
    #
    # Tabellenstruktur für Tabelle `housemoduledata`
    #
    # Predefined names:
    # - #activated# (together with houseid: value=1 if module is built-in in the house
    #
    CREATE TABLE `housemoduledata` (
      `moduleid` int(10) unsigned NOT NULL,
      `name` varchar(50) NOT NULL default '',
      `houseid` int(10) unsigned NOT NULL default '0',
      `value` varchar(255) NULL,
      PRIMARY KEY (`moduleid`,`name`,`houseid`)
    ) TYPE=MyISAM COMMENT='Moduldaten für die Häuser';
    #
    # Tabellenstruktur für Tabelle `houses6`
    #
    DROP TABLE IF EXISTS `houses6`;
    CREATE TABLE `houses6` (
      `houseid` int(10) unsigned NOT NULL auto_increment,
      `owner` int(10) unsigned NOT NULL default '0',
      `status` enum('build','sell','ready') NOT NULL default 'build',
      `goldprice` int(10) unsigned NOT NULL default '30000',
      `gemprice` int(10) unsigned NOT NULL default '50',
      `housename` varchar(25) default NULL,
      `description` text NOT NULL default '',
      `locid` int(10) UNSIGNED NOT NULL default '1',
      PRIMARY KEY  (`houseid`),
      KEY `locid` (`locid`),
      KEY `owner` (`owner`)
    ) TYPE=MyISAM;
    #
    # Daten für Tabelle `settings`
    #
    INSERT INTO `settings` VALUES
        ('startbuild','0'),
        ('mindkbuild','1'),
        ('minlevelbuild','5'),
        ('defaulthousemodule','1');

 *
 *
 * pet-update 20041212:
 *

    ALTER TABLE `accounts`
        ADD `petid` INT UNSIGNED NOT NULL AFTER `hashorse`,
        ADD `petfeed` DATETIME NOT NULL AFTER `petid` ;

    INSERT INTO `items` (`id`, `name`, `class`, `owner`, `value1`, `value2`, `gold`, `gems`, `description`, `hvalue`, `buff`) VALUES (241, 'Kleiner Wachdackel', 'Haust.Prot', 0, 100, 0, 5000, 15, 'Ein niedlicher kleiner Dackel, der Einbrecher erschreckt.', 0, 'a:4:{s:4:"name";s:14:"Lautes Kläffen";s:6:"atkmod";s:2:"20";s:6:"defmod";s:2:"20";s:5:"regen";s:3:"100";}');
    INSERT INTO `items` (`id`, `name`, `class`, `owner`, `value1`, `value2`, `gold`, `gems`, `description`, `hvalue`, `buff`) VALUES (242, 'Großer Haushund', 'Haust.Prot', 0, 0, 1, 15000, 30, 'Ein großer Wachhund, der Haus und Herrchen zu schützen weiß.', 0, 'a:4:{s:4:"name";s:11:"Bissattacke";s:6:"atkmod";s:2:"30";s:6:"defmod";s:2:"30";s:5:"regen";s:3:"150";}');
    INSERT INTO `items` (`id`, `name`, `class`, `owner`, `value1`, `value2`, `gold`, `gems`, `description`, `hvalue`, `buff`) VALUES (243, 'Ausgewachsener Kampfhund', 'Haust.Prot', 0, 100, 1, 25000, 45, 'Ein riesiger Kampfhund, der bereit ist, jeden Eindringling sofort zu zerfleischen.', 0, 'a:4:{s:4:"name";s:21:"Wütendes Zerfleischen";s:6:"atkmod";s:2:"40";s:6:"defmod";s:2:"40";s:5:"regen";s:3:"200";}');

 *
 ******************************************/

require_once('common.php');
checkday();


require_once('housefunctions.php');


if (isset($_GET['location'])) {
    $sql = 'SELECT * FROM houseconfig WHERE locid="'.(int)$_GET['location'].'"';
    $result = db_query($sql);
    $session['user']['specialmisc'] = db_fetch_assoc($result);
}
elseif (!is_array($session['user']['specialmisc'])) {
    $session['user']['specialmisc'] = unserialize($session['user']['specialmisc']);
}

page_header($session['user']['specialmisc']['locname']);

// ok, now show the page...
switch ($_GET['op']) {
    // leave
    case 'leave':
        $location = $session['user']['specialmisc']['location'];
        $session['user']['specialmisc'] = '';
        redirect($location);
    // log in
    case 'newday':
        output("`2Gut erholt wachst du im Haus auf und bist bereit für neue Abenteuer.");
        $session['user']['location'] = 0;


        $sql = "UPDATE items SET hvalue=0 WHERE hvalue > 0 AND owner=".$session['user']['acctid']." AND class='Schlüssel'";
        db_query($sql) or die(sql_error($sql));

        addnav("Tägliche News","news.php");

        if ($session['user']['housekey']==$session['user']['specialmisc']['houseid']) {
            // stay in house...
            addnav('Zum Haus','houses6.php?op=drin&module=');
        }
        else {
            // check for housekey
            $sql = 'SELECT COUNT(*) AS zahl FROM items WHERE class="Schlüssel" AND owner='.$session['user']['acctid'].' AND value1="'.$session['user']['specialmisc']['houseid'].'"';
            $result = db_query($sql);
            $row = db_fetch_assoc($result);
          if ($row['zahl']>0) {
                // stay in house...
                addnav('Zum Haus','houses6.php?op=drin&module=');
            }
            else output('`n`nDir wurde inzwischen der Schlüssel für das Haus abgenommen, daher kannst
                            du nicht    dorthin zurückkehren!');
        }
        addnav("v?Wohnviertel verlassen",'houses6.php?op=leave');
        break;
    // look at the house from outdoor
    case 'bio':
        $sql = "SELECT houseid, housename, description FROM houses6 WHERE houseid='{$_GET['id']}'";
        $result = db_query($sql) or die(db_error(LINK));
        if (!($row=db_fetch_assoc($result))) redirect("houses6.php");

        output("`c`b`@Infos über Haus Nummer {$row['houseid']}`b`c`n`n
                `2Du näherst dich Haus Nummer {$row['houseid']}, um es aus der Nähe zu betrachten.");
        if($row['description']!='') {
            output("Über dem Eingang  von {$row['housename']}`2 steht geschrieben:`n`& {$row['description']}`n`n");
        }
        else {
            output("Das Haus trägt den Namen \"`&{$row['housename']}`2\".`n");
        }
        $sql = "SELECT * FROM items WHERE class='Möbel' AND value1={$_GET['id']} ORDER BY id ASC";
        $result = db_query($sql);
        output("`2Du riskierst einen Blick durch eines der");
        if (db_num_rows($result)>0){
            $comma = false;
            output("Fenster und erkennst ");
            while ($row2 = db_fetch_assoc($result)) {
                if($comma) output(", ");
                else $comma = true;
                output("`@{$row2['name']}`2");
            }
            output(".`n");
        }
        else {
            output("Fenster, aber das Haus hat sonst nichts weiter zu bieten.");
        }
        if ($_GET['id']==$session['user']['housekey']) addnav("Haus betreten","houses6.php?op=drin&id={$_GET['id']}");
        addnav("Zurück","houses6.php");
        break;
    // build a house
    case 'build':
        // show build startpage
        if (empty($_GET['act'])) {
            if ($session['user']['housekey']>0) {
                output("`@Du hast bereits Zugang zu einem fertigen Haus und brauchst kein zweites. Wenn du ein neues oder ein eigenes Haus bauen willst, musst du erst aus deinem jetzigen Zuhause ausziehen.");
            }
            elseif ($session['user']['dragonkills']<getsetting('mindkbuild','1') || ($session['user']['dragonkills']==getsetting('mindkbuild','1') && $session['user']['level']<getsetting('minlevelbuild','5'))) {
                output("`@Du hast noch nicht genug Erfahrung, um ein eigenes Haus bauen zu können. Du kannst aber eventuell bei einem Freund einziehen, wenn er dir einen Schlüssel für sein Haus gibt.");
            }
            elseif ($session['user']['turns']<1) {
                output("`@Du bist zu erschöpft, um heute noch irgendetwas zu bauen. Warte bis morgen.");
            }
            elseif ($session['user']['house']>0) {
                $sql = "SELECT houseid, gemprice, goldprice FROM houses6 WHERE status='build' AND owner=".$session['user']['acctid']." AND locid='{$session['user']['specialmisc']['locid']}'";
                $result = db_query($sql) or die(db_error(LINK));
                if ($row = db_fetch_assoc($result)) {
                    output("`@Du besichtigst die Baustelle deines neuen Hauses mit der Hausnummer `3{$row['houseid']}`@.`n`n");
                    output("Du musst noch `^{$row['goldprice']}`@ Gold und `#{$row['gemprice']}`@ Edelsteine bezahlen.`nWillst du jetzt weiter bauen?`n`n");
                    output("`0<form action=\"houses6.php?op=build&act=build2\" method='POST'>",true);
                    output("`nWieviel Gold zahlen? <input type='gold' name='gold'>`n",true);
                    output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n",true);
                    output("<input type='submit' class='button' value='Bauen'>",true);
                    addnav("","houses6.php?op=build&act=build2");
                }
                else {
                    output("`@Dir fällt ein, dass die Baustelle deines neuen Hauses ja ganz woanders ist. Hier gibt es leider nichts zu tun.");
                }
            }
            elseif (!checkbuild()) {
                output('`@Du bist nicht berechtigt, hier zu bauen. Besorg dir erstmal eine Baugenehmigung!');
            }
            else {
                $goldcost = $session['user']['specialmisc']['defaultgoldprice'];
                $gemcost = $session['user']['specialmisc']['defaultgemprice'];
                output("`@Du siehst ein schönes Fleckchen für ein Haus und überlegst dir, ob du nicht selbst eines bauen solltest, anstatt ein vorhandenes zu kaufen oder noch länger in Kneipe und Feldern zu übernachten.");
                output(" Ein Haus zu bauen würde dich `^$goldcost Gold`@ und `#$gemcost Edelsteine`@ kosten. Du musst das nicht auf einmal bezahlen, sondern könntest immer wieder mal für einen kleineren Betrag ein Stück ");
                output("weiter bauen. Wie schnell du zu deinem Haus kommst, hängt also davon ab, wie oft und wieviel du bezahlst.`n");
                output("Du kannst in deinem zukünftigen Haus alleine wohnen oder es mit anderen teilen. Es bietet einen sicheren Platz zum Übernachten".(module_installed('treasury')?" und einen Lagerplatz für einen Teil deiner Reichtümer":"").".");
                output(" Ein gestartetes Bauvorhaben kann nicht abgebrochen werden.`n`nWillst du mit dem Hausbau beginnen?");
                addnav("Hausbau beginnen","houses6.php?op=build&act=start");
            }
        }
        // start building a house
        elseif ($_GET['act']=="start") {
            $sql = "INSERT INTO houses6 (owner,status,goldprice,gemprice,housename,locid) VALUES ('{$session['user']['acctid']}','build','{$session['user']['specialmisc']['defaultgoldprice']}','{$session['user']['specialmisc']['defaultgemprice']}','{$session['user']['login']}s Haus','{$session['user']['specialmisc']['locid']}')";
            db_query($sql) or die(db_error(LINK));
            if (db_affected_rows(LINK)==0) redirect("houses6.php");
            $session['user']['house'] = db_insert_id(LINK);
            output("`@Du erklärst das Fleckchen Erde zu deinem Besitz und kannst mit dem Bau von Hausnummer `^{$session['user']['house']}`@ beginnen.`n`n");
            output("`0<form action=\"houses6.php?op=build&act=build2\" method='POST'>",true);
            output("`nGib einen Namen für dein Haus ein: <input name='housename' maxlength='25'>`n",true);
            output("`nWieviel Gold anzahlen? <input type='gold' name='gold'>`n",true);
            output("`nWieviele Edelsteine? <input type='gems' name='gems'>`n",true);
            output("<input type='submit' class='button' value='Bauen'>",true);
            addnav("","houses6.php?op=build&act=build2");
        }
        // continue building a house
        elseif ($_GET[act]=="build2") {
            $sql = "SELECT houseid, housename, goldprice, gemprice, status FROM houses6 WHERE status='build' AND owner=".$session['user']['acctid'];
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            $paidgold=(int)$_POST['gold'];
            $paidgems=(int)$_POST['gems'];
            if (!empty($_POST['housename'])){
                $housename=stripslashes($_POST['housename']);
            }
            else{
                $housename=stripslashes($row['housename']);
            }

            if ($session['user']['gold']<$paidgold || $session['user']['gems']<$paidgems) {
                output("`@Du hast nicht genug dabei!");
                addnav("Nochmal","houses6.php?op=build");
            }
            elseif ($session['user']['turns']<1) {
                output("`@Du bist zu müde, um heute noch an deinem Haus zu arbeiten!");
            }
            elseif ($paidgold<0 || $paidgems<0) {
                output("`@Versuch hier besser nicht zu schummeln!");
            }
            else {
                output("`@Du baust für `^$paidgold`@ Gold und `#$paidgems`@ Edelsteine an deinem Haus \"`&$housename`@\"...`n");
                output("`nDu verlierst einen Waldkampf.");
                $row['goldprice'] -= $paidgold;
                $session['user']['gold'] -= $paidgold;
                $session['user']['turns']--;
                if ($row['goldprice'] < 0) {
                    output("`nDu hast die kompletten Goldkosten bezahlt und bekommst das überschüssige Gold zurück.");
                    // subtract negative value - so ist an addition ;)
                    $session['user']['gold'] -= $row['goldprice'];
                    $row['goldprice'] = 0;
                }
                $row['gemprice'] -= $paidgems;
                $session['user']['gems'] -= $paidgems;
                if ($row['gemprice'] < 0) {
                    output("`nDu hast die kompletten Edelsteinkosten bezahlt und bekommst überschüssige Edelsteine zurück.");
                    // subtract negative value - so ist an addition ;)
                    $session['user']['gems'] -= $row['gemprice'];
                    $row['gemprice'] = 0;
                }
                // finished building house
                if ($row['gemprice']==0 && $row['goldprice']==0) {
                    output("`n`n`bGlückwunsch!`b Dein Haus ist fertig. Du bekommst `b".getsetting('newhousekeys',10)."`b Schlüssel überreicht, von denen du ".(getsetting('newhousekeys',10)-1)." an andere weitergeben kannst und besitzt nun deine eigene kleine Burg.");
                    $session['user']['housekey']=$row['houseid'];
                    $row['status'] = 'ready';
                    addnews("`2".$session['user']['name']."`3 hat das Haus `2{$row['housename']}`3 fertiggestellt.");
                    // add keys for the new house
                    $sql = '';
                    for ($i=1;$i<getsetting('newhousekeys',10);$i++) {
                        $sql .= ",('Hausschlüssel',".$session['user']['acctid'].",'Schlüssel',{$row['houseid']},$i,0,0,'Schlüssel für Haus Nummer {$row['houseid']}')";
                    }
                    if ($sql!='') {
                        $sql = 'INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES '.substr($sql,1);
                        db_query($sql);
                        if (db_affected_rows(LINK)==0) output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin.");
                    }
                }
                // still some payment left
                else {
                    output("`nDu musst noch `^{$row['goldprice']}`@ Gold und `#{$row['gemprice']}`@ Edelsteine bezahlen, bis du einziehen kannst.");
                }
                $sql = "UPDATE houses6 SET goldprice={$row['goldprice']},gemprice={$row['gemprice']},housename='".addslashes($housename)."',status='{$row['status']}' WHERE houseid={$row['houseid']}";
                db_query($sql);
            }
        }
        addnav('Wohnviertel','houses6.php');
        addnav('v?Wohnviertel verlassen','houses6.php?op=leave');
        break;
        // end of building house
    // burgling houses6 part 1
    case 'einbruch':
        // player did not choose a house yet
        if (empty($_GET['id'])) {
            if (!empty($_POST['search'])) {
                // search for house number...
                if (strcspn($_POST['search'],"0123456789")<=1) {
                    $search = "houses6.houseid='".(int)$_POST['search']."' AND ";
                }
                // search for house name
                else {
                    $search = "%";
                    for ($x=0; $x<strlen($_POST['search']); $x++) {
                        $search .= $_POST['search']{$x}."%";
                    }
                    $search = "houses6.housename LIKE '".$search."' AND ";
                }
            }else{
                $search = '';
            }
            $ppp=25; // Player Per Page to display
            if (empty($_GET['limit'])){
                $page = 0;
            }else{
                $page = (int)$_GET['limit'];
                addnav("Vorherige Seite","houses6.php?op=einbruch&limit=".($page-1));
            }
            $limit = ($page*$ppp).",".($ppp+1);

            output("`c`b`^Einbruch`b`c`0`n");
            output("`@Du siehst dich um und suchst dir ein bewohntes Haus für einen Einbruch aus. ");
            output("Leider kannst du nicht erkennen, wieviele Bewohner sich gerade darin aufhalten und wie stark diese sind. So ein Einbruch ist also sehr riskant.`nFür welches Haus entscheidest du dich?`n`n");
            output("<form action='houses6.php?op=einbruch' method='POST'>Nach Hausname oder Nummer <input name='search' value='{$_POST['search']}'> <input type='submit' class='button' value='Suchen'></form>",true);
            addnav("","houses6.php?op=einbruch");
            if ($session['user']['pvpflag']=="5013-10-06 00:42:00") output("`n`&(Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!)`0`n`n");
            output("<table cellspacing=0 cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td></tr>",true);

            // get possible targets
            $sql = "SELECT houses6.houseid, houses6.housename,accounts.name FROM houses6 LEFT JOIN accounts ON accounts.acctid=houses6.owner WHERE $search houses6.status='ready' AND houses6.owner!=".$session['user']['acctid']." AND houses6.locid='{$session['user']['specialmisc']['locid']}' ORDER BY houses6.houseid ASC LIMIT $limit";
            $result = db_query($sql) or die(db_error(LINK));
            // no houses6 found
            if (db_num_rows($result)==0) {
                  output("<tr><td colspan=4 align='center'>`&`iEs gibt momentan keine bewohnten Häuser!`i`0</td></tr>",true);
            }
            else {
                // more than one page to display
                if (db_num_rows($result)>$ppp) addnav("Nächste Seite","houses6.php?op=einbruch&limit=".($page+1));
                for ($i=0; $i<db_num_rows($result); $i++) {
                      $row = db_fetch_assoc($result);
                    $bgcolor = ($i%2==1?"trlight":"trdark");
                    output("<tr class='$bgcolor'><td align='right'>{$row['houseid']}</td><td><a href='houses6.php?op=einbruch&id={$row['houseid']}'>{$row['housename']}</a></td><td>",true);
                    output("{$row['name']}</td></tr>",true);
                    addnav("","houses6.php?op=einbruch&id={$row['houseid']}");
                }
            }
            output("</table>",true);
            addnav("Umkehren","houses6.php");
        }
        // a house has already been chosen
        else {
            if ($session['user']['turns']==0 || $session['user']['playerfights']==0){
                output("`nDu bist wirklich schon zu müde, um ein Haus zu überfallen.");
                addnav("Zurück","houses6.php");
            }else{
                output("`2Du näherst dich vorsichtig Haus Nummer {$_GET['id']}.");
                $session['user']['specialmisc']['houseid'] = $_GET['id'];
                // check if user has a key of this house
                $sql = "SELECT COUNT(id) AS zahl FROM items WHERE owner=".$session['user']['acctid']." AND class='Schlüssel' AND value1=".(int)$_GET['id'];
                $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                if ($row['zahl']>0) {
                    // use key to enter house
                    output(" An der Haustür angekommen suchst du etwas, um die Tür möglichst unauffällig zu öffnen. Am besten dürfte dafür der Hausschlüssel geeignet sein, ");
                    output(" den du dabei hast.`nWolltest du wirklich gerade in ein Haus einbrechen, für das du einen Schlüssel hast?");
                    addnav("Haus betreten","houses6.php?op=drin&id={$_GET['id']}");
                    addnav("Zurück",'houses6.php?op=leave');
                } else {
                    // fight against guard
                    output("Deine gebückte Haltung und der schleichende Gang machen eine Stadtwache aufmerksam...`n");
                    $result = getpvpdata('a.maxhitpoints','a.maxhitpoints DESC',1);
                    if (db_num_rows($result)>0) {
                        // somebody is in the house so let's weaken the guard
                        $row = db_fetch_assoc($result);
                        $badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"Holzknüppel","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>abs($session['user']['maxhitpoints']-$row['maxhitpoints'])+1, "diddamage"=>0);
                    }else{
                        // nobody in the house - full power to the guard!
                        $badguy = array("creaturename"=>"Stadtwache","creaturelevel"=>$session['user']['level'],"creatureweapon"=>"starker Holzknüppel","creatureattack"=>$session['user']['attack'],"creaturedefense"=>$session['user']['defence'],"creaturehealth"=>abs($session['user']['maxhitpoints']), "diddamage"=>0);
                        $session['user']['playerfights']--;
                    }
                    $session['user']['badguy'] = createstring($badguy);
                    $fight=true;
                }
            }
        } // end of house chosen
        break;
        // end of burgling houses6 part 1
    // player wants (or is made wanting ;)) to fight
    case 'fight':
        $fight = true;
        break;
    // player tries to run away during fight
    case 'run':
        $badguy = createarray($session['user']['badguy']);
        // fight against guard
        if ($badguy['creaturename']=='Stadtwache') {
            output("`%Die Wache lässt dich nicht entkommen!`n");
        }
        // fight against pet
        else {
            output("`%".$badguy['creaturename']."`% lässt dich nicht entkommen!`n");
        }
        $fight=true;
        break;
    // burgling house part 2
    case 'einbruch2':
        $badguy = createarray($session['user']['badguy']);
        $fightpet = false;
        // check for pet
        if ($badguy['creaturename']=='Stadtwache') {
            $sql = 'SELECT accounts.petid AS pet, items.name, items.buff FROM accounts LEFT JOIN items ON accounts.petid=items.id WHERE accounts.house6='.$session['user']['specialmisc']['houseid'].' AND accounts.petfeed > NOW()';
            $result = db_query($sql);
            if ($row = db_fetch_assoc($result)) {
                if ($row['pet']>0) {
                    $petbuff = unserialize($row['buff']);
                    $badguy = array('creaturename'=>$row['name'],
                                        'creaturelevel'=>$session['user']['level'],
                                        'creatureweapon'=>$petbuff['name'],
                                        'creatureattack'=>$petbuff['atkmod'],
                                        'creaturedefense'=>$petbuff['defmod'],
                                        'creaturehealth'=>$petbuff['regen'],
                                        'diddamage'=>0);
                    $session['user']['badguy'] = createstring($badguy);
                    $fight = $fightpet = true;
                    output('`$Gerade willst du ins Haus schleichen, als du hinter dir plötzlich ein Knurren vernimmst.`0`n');
                }
            }
        }

        if (!$fightpet) {
            // run, forrest, run! ;)
            addnav("Flüchte",'houses6.php?op=leave');
            // fight against player
            $result = getpvpdata('COUNT(*) AS athome','',0);
            $row = db_fetch_assoc($result);
            // somebody at home, so lets fight...
            if ($row['athome']>0) {
                if ($row['athome']==1) output("`nDir kommt ein misstrauischer Bewohner schwer bewaffnet entgegen. Er wird sich jeden Augenblick auf dich stürzen, ");
                else output("`nDir kommen {$row['athome']} misstrauische Bewohner schwer bewaffnet entgegen. Der wahrscheinlich Stärkste von ihnen wird sich jeden Augenblick auf dich stürzen, ");
                output("wenn du die Situation nicht sofort entschärfst.");
                addnav('Kämpfe',"houses6.php?op=einbruch3&id={$session['user']['specialmisc']['houseid']}");
            }
            // yeah, lets take away everything valuable
            else {
                output("Du hast Glück, denn es scheint niemand daheim zu sein. Das wird sicher ein Kinderspiel.");
                addnav("Einsteigen","houses6.php?op=klauen&id={$session['user']['specialmisc']['houseid']}");
            }
        }
        break;
    // burgling house part 3
    case 'einbruch3':
        // finally go to pvp.php for fighting
        $result = getpvpdata('a.login','a.maxhitpoints',1);
        $row = db_fetch_assoc($result);
        redirect("pvp.php?act=attack&bg=2&name=".rawurlencode($row['login']));
        break;
    // robbing something
    case 'klauen':
        if (empty($_GET['id'])) {
            output("Und jetzt? Bitte benachrichtige den Admin. Ich weiß nicht, was ich jetzt tun soll...");
            addnav("Zurück","houses6.php?op=leave");
            break;
        }

        $sql = "SELECT owner FROM houses6 WHERE houseid=".$session['user']['specialmisc']['houseid']." ORDER BY houseid ASC";
        $result = db_query($sql) or die(db_error(LINK));
        $hdata = db_fetch_assoc($result);
        addnav("Zurück",'houses6.php?op=leave');
        // if treasury-module is installed, gimme some money...
        if ($mid = module_builtin('treasury',$session['user']['specialmisc']['houseid'])) {
            $goldinhouse = getmoduledata($mid,'gold',$session['user']['specialmisc']['houseid']);
            $gemsinhouse = getmoduledata($mid,'gems',$session['user']['specialmisc']['houseid']);
            // found money, so take 5-15% of it
            if ($goldinhouse > 0 || $gemsinhouse > 0) {
                $getgold = e_rand($goldinhouse*0.05,$goldinhouse*0.15);
                $getgems = e_rand($gemsinhouse*0.05,$gemsinhouse*0.15);
                // bugfix for 1 gold-exploit (user deposits 1 gold, bad guy always gets 0)
                if ($getgold==0) $getgold = $goldinhouse;
                if ($getgems==0) $getgems = $gemsinhouse;
                
                $session['user']['gold'] += $getgold;
                $session['user']['gems'] += $getgems;
                // take the money away from house
                setmoduledata($mid,'gold',$goldinhouse-$getgold,$session['user']['specialmisc']['houseid']);
                setmoduledata($mid,'gems',$gemsinhouse-$getgems,$session['user']['specialmisc']['houseid']);
                // inform both affected players
                if ($getgold > 0 && $getgems > 0) $str = "`^$getgold Gold`@ und `%$getgems Edelsteine`@";
                elseif ($getgold > 0) $str = "`^$getgold Gold`@";
                else $str = "`%$getgems Edelsteine`@";
                output("`@Es gelingt dir, $str aus dem Schatz zu klauen!");

                $mailadd = $newsadd = '';
                // if user got too less money, destroy furniture
                if ($getgold < $session['user']['level']*10 && $goldinhouse < 2500) {
                    // chance of 5%+X to destroy furniture...
                    $chance = e_rand(1,21);
                    $left500 = floor((2500-$goldinhouse)/500);
                    if ($chance <= $left500) {
                        // check if there's furniture to destroy
                        $sql = 'SELECT id,name FROM items WHERE class="Möbel" AND value1="'.$session['user']['specialmisc']['houseid'].'" ORDER BY RAND('.e_rand().') LIMIT 1';
                        $result = db_query($sql) or die(db_error(LINK));
                        if ($row = db_fetch_assoc($result)) {
                            output('`nDu lässt deiner Enttäuschung über die miese Beute freien Lauf und zerstörst dabei `^'.$row['name'].'`@.');
                            $sql = 'DELETE FROM items WHERE id="'.$row['id'].'"';
                            db_query($sql);
                            $mailadd = " sowie `^{$row['name']} `2zerstört`6";
                            $newsadd = " und zerstört dabei Möbel";
                        }
                    }
                }

                addnews("`6".$session['user']['name']."`6 erbeutet Gold bei einem Einbruch$newsadd!");
                if ($getgold > 0 && $getgems > 0) $str = "`^$getgold Gold`6 und `%$getgems Edelsteine`6";
                elseif ($getgold > 0) $str = "`^$getgold Gold`6";
                else $str = "`%$getgems Edelsteine`6";
                systemmail($hdata['owner'],"`\$Einbruch!`0","`6Jemand ist in dein Haus eingebrochen und hat $str erbeutet$mailadd!");
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."',".$hdata['owner'].",'/me `6entdeckt, dass bei einem Einbruch $str gestohlen$mailadd wurden.')";
                db_query($sql) or die(db_error(LINK));
                break;
            }
        }

        // got no money... damn!
        output('`@Leider findest du nichts, das du stehlen könntest.');
        // chance of 5% to destroy furniture...
        if (e_rand(1,21)<=5) {
            // check if there's furniture to destroy
            $sql = 'SELECT id,name FROM items WHERE class="Möbel" AND value1="'.$session['user']['specialmisc']['houseid'].'" ORDER BY RAND('.e_rand().') LIMIT 1';
            $result = db_query($sql) or die(db_error(LINK));
            if ($row = db_fetch_assoc($result)) {
                output('Du lässt deiner Enttäuschung freien Lauf und zerstörst dabei `^'.$row['name'].'`@.');
                addnews("`6".$session['user']['name']."`6 zerstört Möbel bei einem Einbruch!");
                systemmail($hdata['owner'],"`\$Einbruch!`0","`6Jemand ist in dein Haus eingebrochen und hat `^{$row['name']} `2zerstört`6!");
                $sql = 'DELETE FROM items WHERE id="'.$row['id'].'"';
                db_query($sql);
                $sql="INSERT INTO commentary (postdate,section,author,comment) VALUES (now(),'house-".$session['user']['specialmisc']['houseid']."',".$hdata['owner'].",'/me `6entdeckt, dass bei einem Einbruch `^{$row['name']} `2zerstört`6 wurde.')";
                db_query($sql) or die(db_error(LINK));
            }
        }
        break;
        // end of robbing something
    // buy a house - we don't do it here anymore, so it's useless...
    case 'buy':
    // sell a house - same as buy...
    case 'sell':
    // player wants to enter a house
    case 'enter':
        output("`@Du hast Zugang zu folgenden Häusern:`n`n");
        // delete possible user-in-house-flags
        $sql = "UPDATE items SET hvalue=0 WHERE hvalue>0 AND owner=".$session['user']['acctid']." AND class='Schlüssel'";
        db_query($sql) or die(sql_error($sql));
        // search houses6 the player can enter
        $sql = "SELECT i.value1,h.housename,a.name AS ownername FROM items i LEFT JOIN houses6 h ON i.value1=h.houseid LEFT JOIN accounts a ON a.acctid=h.owner WHERE i.owner=".$session['user']['acctid']." AND i.class='Schlüssel' AND h.locid='{$session['user']['specialmisc']['locid']}' GROUP BY i.value1 ORDER BY i.id ASC";
        $result = db_query($sql) or die(db_error(LINK));
        output("<table cellpadding=2 align='center'><tr><td>`bHausNr.`b</td><td>`bName`b</td><td>`bBesitzer`b</td></tr>",true);
        $ppp = 25; // Player Per Page +1 to display
        if (empty($_GET['limit'])) {
            $page = 0;
        }
        else {
            $page = (int)$_GET['limit'];
            addnav("Vorherige Straße","houses6.php?op=enter&limit=".($page-1));
        }
        $limit = ($page*$ppp).",".($ppp+1);
        // show link to own house at the top
        if ($session['user']['house']>0 && $session['user']['housekey']>0) {
            $sql = "SELECT houseid,housename FROM houses6 WHERE houseid=".$session['user']['house']." AND locid='{$session['user']['specialmisc']['locid']}' ORDER BY houseid DESC LIMIT $limit";
            $result2 = db_query($sql) or die(db_error(LINK));
            if ($row2 = db_fetch_assoc($result2)) {
                output("<tr><td align='center'>{$row2['houseid']}</td><td><a href='houses6.php?op=drin&id={$row2['houseid']}'>{$row2['housename']}</a></td><td>`idein eigenes`i</td></tr>",true);
                addnav("","houses6.php?op=drin&id={$row2['houseid']}");
            }
        }
        // house is not ready yet
        elseif ($session['user']['house']>0) {
            output("<tr><td colspan=3 align='center'>`&`iDein Haus ist noch im Bau oder steht zum Verkauf`i`0</td></tr>",true);
        }
        if (db_num_rows($result)>$ppp) addnav("Nächste Seite","houses6.php?op=enter&limit=".($page+1));
        if (db_num_rows($result)==0) {
            output("<tr><td colspan=3 align='center'>`&`iDu hast keinen Schlüssel`i`0</td></tr>",true);
        }
        // show houses6
        else {
            for ($i=0;$i<db_num_rows($result);$i++){
                $item = db_fetch_assoc($result);
                $bgcolor=($i%2==1?"trlight":"trdark");
                // don't show own house - we did this before ;)
                if ($item['value1']!=$session['user']['house']){
                    output("<tr class='$bgcolor'><td align='center'>{$item['value1']}</td><td><a href='houses6.php?op=drin&id={$item['value1']}'>{$item['housename']}</a></td><td>",true);
                    if ($item['ownername']!='') output($item['ownername'].'`0');
                    else output('`iverlassen`i');
                    output("</td></tr>",true);
                    addnav("","houses6.php?op=drin&id={$item['value1']}");
                }
            }
        }
        output("</table>",true);

        addnav('Wohnviertel',"houses6.php");
        addnav('v?Wohnviertel verlassen','houses6.php?op=leave');
        break;
    // main part: player has entered a house. let's start with modules...
    case 'drin':
        if (empty($session['user']['specialmisc']['houseid'])) {
            $session['user']['specialmisc']['houseid'] = $_GET['id'];
        }
        // flag if default navs should be shown or not
        $shownavs = false;
        // any module selected? then show it...
        if (!empty($_GET['module'])) {
            $sql = 'SELECT modulefile, modulename FROM housemodules WHERE moduleid="'.$_GET['module'].'"';
            $result = db_query($sql);
            if ($row = db_fetch_assoc($result)) {
                if ($_GET['module']==getsetting('defaulthousemodule','1')) $shownavs = true;
                $session['user']['specialmisc']['modulefile'] = $row['modulefile'];
                $session['user']['specialmisc']['modulename'] = $row['modulename'];
            }
            else redirect('houses6.php?op=drin');
        }
        // no module selected - get the default module
        elseif (empty($session['user']['specialmisc']['modulefile']) || isset($_GET['module'])) {
            $sql = 'SELECT modulefile, modulename FROM housemodules WHERE moduleid="'.getsetting('defaulthousemodule','1').'"';
            $result = db_query($sql);
            if ($row = db_fetch_assoc($result)) {
                $shownavs = true;
                $session['user']['specialmisc']['modulefile'] = $row['modulefile'];
                $session['user']['specialmisc']['modulename'] = $row['modulename'];
            }
            else redirect('houses6.php');
        }
        elseif (getmoduleid($session['user']['specialmisc']['modulename'])==getsetting('defaulthousemodule','1')) {
            $shownavs = true;
        }

        // so for now, show module!
        require_once('housemodules/'.$session['user']['specialmisc']['modulefile']);
        $function = 'module_show_'.$session['user']['specialmisc']['modulename'];
        $function();

        // if module doesn't set any navs, it's the default module - so set navs now!
        if ($shownavs || !is_array($session['allowednavs']) || count($session['allowednavs'])==0) {
            // get modulenavs now
            if ($session['user']['specialmisc']['houseid']==$session['user']['house']) {
                $for = 'owner';
            }
            else $for = 'guest';
            $modules = array();
            $lastcategory = '';
            $sql = 'SELECT hm.moduleid, hm.linkcategory, hm.linktitle
                        FROM housemodules hm
                        LEFT JOIN housemoduledata hmd
                        ON hmd.moduleid=hm.moduleid
                        AND hmd.houseid="'.$session['user']['specialmisc']['houseid'].'"
                        AND hmd.name="#activated#"
                        WHERE (hm.built_in="1" OR hmd.value="1")
                        AND hm.moduleid!="'.getsetting('defaulthousemodule','1').'"
                        AND FIND_IN_SET("'.$for.'",hm.showto)>0
                        ORDER BY hm.linkorder ASC';
            $result = db_query($sql);
            while ($row = db_fetch_assoc($result)) {
                if ($lastcategory!=$row['linkcategory'] && $row['linkcategory']!='') {
                    addnav($row['linkcategory']);
                    $lastcategory = $row['linkcategory'];
                }
                addnav($row['linktitle'],'houses6.php?op=drin&module='.$row['moduleid']);
            }
            if ($lastcategory!='Sonstiges') addnav('Sonstiges');
            addnav('Wohnviertel','houses6.php');
            addnav('v?Wohnviertel verlassen','houses6.php?op=leave');
        }
        break;
    // show the start page of houses6 script
    default:
        if (!empty($session['user']['specialmisc']['houseid'])) {
            $session['user']['specialmisc']['houseid'] = 0;
            $session['user']['specialmisc']['modulefile'] = '';
            $session['user']['specialmisc']['modulename'] = '';
        }
        // check if you have a key for a house located here
        $sql = "SELECT COUNT(i.id) AS keycount FROM items i LEFT JOIN houses6 h ON h.houseid=i.value1 WHERE i.owner=".$session['user']['acctid']." AND i.class='Schlüssel' AND h.locid='{$session['user']['specialmisc']['locid']}'";
        $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        // search own house
        if ($session['user']['house']) {
            $sql = 'SELECT locid FROM houses6 WHERE houseid='.$session['user']['house'];
            $result = db_query($sql);
            $ownhouse = db_fetch_assoc($result);
        }
       // add link to enter house if found any (and if player has a key naturally)
        if ($row['keycount']>0 || ($ownhouse['locid']==$session['user']['specialmisc']['locid'] && $session['user']['housekey']>0)) addnav("Haus betreten","houses6.php?op=enter");
        output('`@`b`c'.$session['user']['specialmisc']['locname'].'`c`b`n`n
               Du schlenderst Richtung Wohnviertel. In diesem schön angelegten Teil des Dorfes siehst
               du einige Baustellen zwischen bewohnten und unbewohnten Häusern. Hier wohnen also die
               Helden...`n`n');
        // search house
        if (!empty($_POST['search'])) {
            if (strcspn($_POST['search'],"0123456789")<=1){
                $search = "houseid=".(int)$_POST['search']." AND ";
            }
            else {
                $search="%";
                for ($x=0; $x<strlen($_POST['search']); $x++) {
                    $search .= substr($_POST['search'],$x,1)."%";
                }
                $search = "housename LIKE '".$search."' AND ";
            }
        }
        else {
            $search = "";
        }
        // show pages (streets)
        $ppp=30; // Player Per Page +1 to display
        if (empty($_GET['limit'])) {
            $page = 0;
        }
        else {
            $page = (int)$_GET['limit'];
            addnav("Vorherige Straße","houses6.php?limit=".($page-1));
        }
        $limit = ($page*$ppp).",".($ppp+1);
        $sql = "SELECT houses6.houseid,houses6.status,houses6.owner,houses6.housename,accounts.name AS schluesselinhaber
                    FROM houses6
                    LEFT JOIN accounts ON accounts.acctid=houses6.owner
                    WHERE $search houses6.locid='{$session['user']['specialmisc']['locid']}'
                    ORDER BY houseid ASC LIMIT $limit";
        output("<form action='houses6.php' method='POST'>Nach Hausname oder Nummer <input name='search' value='{$_POST['search']}'> <input type='submit' class='button' value='Suchen'></form>",true);
        addnav("",'houses6.php');
        output("<table cellpadding=2 cellspacing=1 bgcolor='#999999' align='center'><tr class='trhead'><td>`bHausNr.`b</td><td>`bName`b</td><td>`bEigentümer`b</td><td>`bStatus`b</td></tr>",true);
        $result = db_query($sql) or die(db_error(LINK));
        if (db_num_rows($result)>$ppp) addnav("Nächste Straße","houses6.php?limit=".($page+1)."&location=".urlencode($location));
        // no houses6 found
        if (db_num_rows($result)==0) {
              output("<tr><td colspan=4 align='center'>`&`iEs gibt noch keine Häuser`i`0</td></tr>",true);
        }
        // list houses6
        else {
            for ($i=0; $i<db_num_rows($result); $i++) {
                $row = db_fetch_assoc($result);
                $bgcolor=($i%2==1?"trlight":"trdark");
                output("<tr class='$bgcolor'><td align='right'>{$row['houseid']}</td><td><a href='houses6.php?op=bio&id={$row['houseid']}'>{$row['housename']}</a></td><td>",true);
                addnav("","houses6.php?op=bio&id={$row['houseid']}");
                output("{$row['schluesselinhaber']}");
                output("</td><td>",true);
                if ($row['status']=='build') {
                    if ($row['owner']>0) output("`6im Bau`0");
                    else output("`\$Bauruine`0");
                }
                elseif ($row['status']=='ready') {
                    if ($row['owner']>0) output("`!bewohnt`0");
                    else output("`4verlassen`0");
                }
                elseif ($row['status']=='sell') output("`^zum Verkauf`0");
                output("</tr>",true);
            }
        }
        output("</table>",true);
        // player owns a house
        if ($session['user']['housekey']>0) {
            output("`nStolz schwingst du den Schlüssel zu deinem Haus im Gehen hin und her.");
        }

        // show link for building a house
        if ($session['user']['housekey']==0) {
            if (($session['user']['house6']==0 || $ownhouse['locid']==$session['user']['specialmisc']['locid']) && $session['user']['specialmisc']['build']==1) addnav("Haus bauen","houses6.php?op=build");
        }
        // show link for pvp
        if (getsetting("pvp",1)==1 && $session['user']['specialmisc']['rob']==1) addnav("Einbrechen","houses6.php?op=einbruch");

        if ($session['user']['superuser']) addnav("Admin Grotte","superuser.php");

        addnav('v?Wohnviertel verlassen','houses6.php?op=leave');
        break; // we really don't need it, but... why not? :D
}

// let's fight, boy!
if ($fight) {
    if (count($session['bufflist'])>0 && is_array($session['bufflist']) || $_GET['skill']!="") {
        $_GET['skill'] = "";
        $session['user']['buffbackup'] = serialize($session['bufflist']);
        $session['bufflist']=array();
        output("`&Die ungewohnte Umgebung verhindert den Einsatz deiner besonderen Fähigkeiten!`0");
    }
    include "battle.php";

    if ($victory) {
        addnav("H?Weiter zum Haus","houses6.php?op=einbruch2&id={$session['user']['specialmisc']['houseid']}");
        addnav('Wohnviertel',"houses6.php");
        addnav('v?Wohnviertel verlassen',"houses6.php?op=leave");
        // check for pet
        if ($badguy['creaturename']=='Stadtwache') {
            output("`n`#Du hast die Stadtwache besiegt und der Weg zum Haus ist frei!`nDu bekommst ein paar Erfahrungspunkte.");
            $session['user']['experience'] += $session['user']['level']*10;
            $session['user']['turns']--;
        }
        else {
            output('`n`#'.$badguy['creaturename'].'`# zieht sich jaulend zurück und gibt den Weg zum Haus frei!');
        }
        $badguy=array();
    }
    elseif ($defeat) {
        // check for pet
        if ($badguy['creaturename']=='Stadtwache') {
            output("`n`\$Die Stadtwache hat dich besiegt. Du bist tot!`nDu verlierst 10% deiner Erfahrungspunkte, aber kein Gold.`nDu kannst morgen wieder kämpfen.");
            $session['user']['hitpoints'] = 0;
            $session['user']['alive'] = false;
            $session['user']['experience'] = round($session['user']['experience']*0.9);
            addnews("`%".$session['user']['name']."`3 wurde von der Stadtwache bei einem Einbruch besiegt.");
            addnav('Tägliche News','news.php');
        }
        else {
            output('`n`$'.$badguy['creaturename'].'`$ hat dich besiegt. Du liegst schwer verletzt am Boden!`nDu verlierst 3 Charmepunkte und fast alle Lebenspunkte.');
            $session['user']['hitpoints'] = 1;
            $session['user']['charm'] -= 3;
            addnews("`%".$session['user']['name']."`3 stieß bei einem Einbruch auf unerwartete Gegenwehr und verletzte sich schwer.");
            addnav('Davonkriechen',"houses6.php?op=leave");
        }
        $session['user']['badguy'] = '';
    }
    else {
        fightnav(false,true);
    }
}

output('`n`n');
//output('`c`b&copy; by <a href="http://logd.chaosonline.de" target="_blank">Chaosmaker</a>`b`c',true);

page_footer();
?>