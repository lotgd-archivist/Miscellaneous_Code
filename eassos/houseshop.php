
<?php

/* * ***************************************
 *
 * houseshop.php
 * Author: Chaosmaker <webmaster@chaosonline.de>
 * Version: 1.3
 * Server: biosLoGD http://logd.chaosonline.de
 *
 * Features:
 *    - buy houses
 *    - sell own house
 *    - buy a building lot if required
 *
 * *************************************** */

require_once('common.php');
checkday();

require_once('lib/include/housefunctions.php');

page_header('Das Bauamt');

$session['user']['standort'] = "Bauamt";

$goldcost                 = 10000;
$gemcost                 = 15;
$max_treasury             = 20;
$treasury_improve_cost     = 12000;

if (!empty($_GET['op']))
    $op     = $_GET['op'];
else
    $op     = '';

switch ($op) {
    case 'listhouses':

        output("`c`n`n`n`n<font face='Harrington' size='6'>
`7Haus kaufen
</font>`c`n`n`0", true);

        // house selected
        if (!empty($_GET['buy'])) {
            // get house data
            $sql     = 'SELECT h.status, h.goldprice, h.gemprice, h.housename,
                    hc.defaultgoldprice, hc.defaultgemprice
                    FROM houses h
                    LEFT JOIN houseconfig hc USING(locid)
                    WHERE h.owner=0 AND hc.buy="1" AND h.houseid=' . $_GET['buy'];
            $result     = db_query($sql);
            if ($row     = db_fetch_assoc($result)) {
                if ($row['status'] == 'build') {
                    $goldprice     = round(($row['defaultgoldprice'] - $row['goldprice']) * 2 / 3);
                    $gemprice     = round(($row['defaultgemprice'] - $row['gemprice']) * 2 / 3);
                } else {
                    $goldprice     = round(($row['defaultgoldprice'] + $row['goldprice']) * 2 / 3);
                    $gemprice     = round(($row['defaultgemprice'] + $row['gemprice']) * 2 / 3);
                }

                if ($session['user']['gold'] < $goldprice || $session['user']['gems'] < $gemprice) {
                    output('`c`7Der alte Mann fragt dich, wie du das Haus bezahlen willst - darüber
                            hast du noch gar nicht nachgedacht. Vielleicht solltest du es jetzt
                            nachholen...`c`n`n`n`n`0');
                } else {
                    // delete old keys
                    $sql                         = 'DELETE FROM items WHERE class="Schlüssel" AND value1=' . $_GET['buy'];
                    db_query($sql);
                    addnews("`x" . $session['user']['name'] . "`í hat das Haus `ò{$row['housename']}`í gekauft.`0");
                    output("`n`n`n`n`c`b`7Glückwunsch!`b `rDu hast das Haus gekauft.`n`n`c`0");
                    $session['user']['house']     = $_GET['buy'];
                    if ($row['status'] != 'build') {
                        $session['user']['housekey'] = $_GET['buy'];
                        output("`c`XDu`x be`éko`ímm`óst `b" . getsetting('newhousekeys', 10) . "`b `òSchlüssel überreicht, von denen du `b" . (getsetting('newhousekeys', 10) - 1) . "`b an andere weitergeben kannst und besitzt nun deine eigene k`óle`íin`ée B`xur`Xg.`c`0`n`n");
                        // add new keys for the house
                        $sql                         = '';
                        for ($i = 1; $i < getsetting('newhousekeys', 10); $i++) {
                            $sql .= ",('Hausschlüssel'," . $session['user']['acctid'] . ",'Schlüssel',{$_GET['buy']},$i,0,0,'Schlüssel für Haus Nummer {$_GET['buy']}')";
                        }
                        if ($sql != '') {
                            $sql = 'INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES ' . substr($sql, 1);
                            db_query($sql);
                            if (db_affected_rows(LINK) == 0)
                                output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin.");
                        }
                        $owner     = $session['user']['acctid'];
                        $sql     = "UPDATE items SET owner = $owner WHERE class='Möbel' AND value1 = " . $_GET['buy'];
                        db_query($sql);
                    }
                    // update house
                    if ($row['status'] == 'sell')
                        $row['status']     = 'ready';
                    $sql             = 'UPDATE houses SET owner=' . $session['user']['acctid'] . ',status="' . $row['status'] . '" WHERE houseid=' . $_GET['buy'];
                    db_query($sql);

                    $session['user']['gems'] -= $gemprice;
                    $session['user']['gold'] -= $goldprice;
                    // kill possible commentary
                    //$sql = 'DELETE FROM commentary WHERE section="house-'.$session['user']['specialmisc']['houseid'].'" OR section="house_private-'.$session['user']['specialmisc']['houseid'].'"';
                    $sql = 'DELETE FROM commentary WHERE 
section="house_private-' . $session['user']['house'] . '"
OR section="house_' . $session['user']['house'] . '"
OR section="house-' . $session['user']['house'] . '"
OR section="house_fireplace-' . $session['user']['house'] . '"
OR section="house_kitchen-' . $session['user']['house'] . '"
OR section="house_stable-' . $session['user']['house'] . '"
OR section="house_bath-' . $session['user']['house'] . '"
OR section="house_garteb-' . $session['user']['house'] . '"
OR section="house_veranda-' . $session['user']['house'] . '"'


                    ;
                    db_query($sql);
                }
            }
            else {
                output('`c`n`n`n`n`4So ein Pech - da war wohl jemand schneller als du!`c`0`n`n`n`n');
            }
        } else {
            // get all abandoned houses
            $sql     = 'SELECT h.houseid, h.status, h.goldprice, h.gemprice, h.housename,
                    hc.locname, hc.defaultgoldprice, hc.defaultgemprice
                    FROM houses h
                    LEFT JOIN houseconfig hc USING(locid)
                    WHERE h.owner=0 AND hc.buy="1"
                    ORDER BY h.locid ASC, h.houseid ASC';
            $result     = db_query($sql);
            if (db_num_rows($result) == 0) {
                output('`c`n`n`n`n`7Leider will derzeit niemand ein Haus verkaufen.`c`0`n`n`n`n');
            } else {
                output('`c`n`n`7Du schaust dir die Zettel an, auf denen Häuser feilgeboten werden. Ob da
                        wohl was für dich dabei ist?`c`0`n`n');
                while ($row = db_fetch_assoc($result)) {
                    output('<table border="0"><tr class="trhead"><td colspan="2">', true);
                    output($row['housename']);
                    output('</td></tr><tr class="trlight"><td>', true);
                    output('Standort:');
                    output('</td><td>', true);
                    output($row['locname']);
                    output('</tr><tr class="trdark"><td>', true);
                    output('Hausnummer:');
                    output('</td><td>', true);
                    output($row['houseid']);
                    output('</tr><tr class="trlight"><td>', true);
                    output('Hausname:');
                    output('</td><td>', true);
                    output($row['housename']);
                    output('</tr><tr class="trdark"><td>', true);
                    output('Status:');
                    output('</td><td>', true);
                    if ($row['status'] == 'build')
                        $status     = 'Bauruine';
                    elseif ($row['status'] == 'ready')
                        $status     = 'verlassen';
                    else
                        $status     = 'wie neu';
                    output($status);
                    output('</tr><tr class="trlight"><td>', true);
                    output('Preis:');
                    output('</td><td>', true);
                    if ($row['status'] == 'build') {
                        $goldprice     = round(($row['defaultgoldprice'] - $row['goldprice']) * 2 / 3);
                        $gemprice     = round(($row['defaultgemprice'] - $row['gemprice']) * 2 / 3);
                    } else {
                        $goldprice     = round(($row['defaultgoldprice'] + $row['goldprice']) * 2 / 3);
                        $gemprice     = round(($row['defaultgemprice'] + $row['gemprice']) * 2 / 3);
                    }
                    output('`ò' . $goldprice . ' Gold`x, `ò' . $gemprice . ' Edelsteine`x');
                    output('</tr><tr class="trdark"><td>', true);
                    output('');
                    output('</td><td>', true);
                    output('<a href="houseshop.php?op=listhouses&buy=' . $row['houseid'] . '">kaufen</a>', true);
                    output('</td></tr></table><br>', true);
                    addnav('', 'houseshop.php?op=listhouses&buy=' . $row['houseid']);
                }
            }
        }
        break;
    case 'buylot':
        output("`c`n`n`n`n<font face='Harrington' size='6'>
`7Baugenehmigung beantragen
</font>`c`n`n`0", true);

        // get houses without owner
        $sql     = 'SELECT houseconfig.locname, houseconfig.defaultgoldprice, houseconfig.defaultgemprice, houseconfig.buildprice_increase, houses.locid AS hloc, COUNT(*) AS zahl
                    FROM houseconfig
                    LEFT JOIN houses ON houses.locid=houseconfig.locid AND houses.owner=0
                    WHERE houseconfig.locid="' . $_GET['where'] . '"
                    GROUP BY houseconfig.locid';
        $result     = db_query($sql);
        $row     = db_fetch_assoc($result);
        if ($row['hloc'] > 0) {
            $emptyhouses = $row['zahl'];
        } else {
            $emptyhouses = 0;
        }
        $faktor                         = $row['buildprice_increase'] / 100;
        $goldprice                     = round($row['defaultgoldprice'] * (1 + $emptyhouses * $faktor));
        $gemprice                     = round($row['defaultgemprice'] * (1 + $emptyhouses * $faktor));
        $sql                         = 'INSERT INTO houses (owner, status, goldprice, gemprice, housename, locid)
                VALUES (' . $session['user']['acctid'] . ',"build",' . $goldprice . ',' . $gemprice . ',"' . $session['user']['login'] . 's Haus",' . $_GET['where'] . ')';
        db_query($sql);
        $houseid                     = db_insert_id(LINK);
        $session['user']['house']     = $houseid;
        output('`n`n`n`n`c`7Der alte Mann händigt dir ein Papier aus, laut dem du von nun an der Besitzer des
                Grundstücks `rNummer ' . $houseid . ' `7bist. Dein Haus wird unter dem Namen`&
                "`b' . $session['user']['login'] . 's Haus`b" `7eingetragen. Du kannst den Namen allerdings
                später auch noch gegen eine geringe Gebühr ändern.`c`n`n`n`n`0');

        addnav("Zurück");
        addnav('Zum Bauamt', 'houseshop.php');
        addnav("Wohnviertel", "houses.php");
        addnav("Zur Stadt", "village.php");
        break;
    case 'sellhouse':

        output("`c`n`n`n`n<font face='Harrington' size='6'>
`rHausverkauf
</font>`c`n`n`0", true);

        // get worth of own house and installed modules
        $sql     = 'SELECT houses.locid, status, housename, goldprice, gemprice, defaultgoldprice, defaultgemprice, buildprice_increase, sell
                    FROM houses
                    LEFT JOIN houseconfig USING(locid)
                    WHERE houseid=' . $session['user']['house'];
        $result     = db_query($sql);
        $row     = db_fetch_assoc($result);
        $faktor     = $row['buildprice_increase'] / 100;

        // get houses without owner
        $sql         = 'SELECT COUNT(*) AS zahl
                    FROM houses
                    WHERE locid="' . $row['locid'] . '" AND owner=0';
        $result         = db_query($sql);
        $row2         = db_fetch_assoc($result);
        $emptyhouses = $row2['zahl'];

        if ($row['status'] != 'build') {
            $sellgoldprice     = max(1, round(($row['goldprice'] + $row['defaultgoldprice']) / 3 * (1 - $emptyhouses * $faktor)));
            $sellgemprice     = max(1, round(($row['gemprice'] + $row['defaultgemprice']) / 3 * (1 - $emptyhouses * $faktor)));
            $status             = 'sell';
        } else {
            $sellgoldprice     = max(1, round(($row['defaultgoldprice'] - $row['goldprice']) / 3 * (1 - $emptyhouses * $faktor)));
            $sellgemprice     = max(1, round(($row['defaultgemprice'] - $row['gemprice']) / 3 * (1 - $emptyhouses * $faktor)));
            $status             = 'build';
        }
        $housename = $row['housename'];

        if ($row['sell'] == 0) {
            output('`n`n`c`7Der Mann weist dich darauf hin, dass du nicht berechtigt bist, dein Haus zu
                    verkaufen. Du kannst deine Enttäuschung kaum verbergen.`c`n`n`n`n`0');
            addnav("Zurück");
            addnav('Zum Bauamt', 'houseshop.php');
            addnav("Wohnviertel", "houses.php");
            addnav("Zur Stadt", "village.php");
        } elseif (!empty($_GET['sell'])) {
            // sell house
            $houseid                     = $session['user']['house'];
            $session['user']['house']     = $session['user']['housekey'] = 0;
            $session['user']['gold'] += $sellgoldprice;
            $session['user']['gems'] += $sellgemprice;
            $sql                         = 'UPDATE houses SET owner=0, status="' . $status . '" WHERE houseid=' . $houseid;
            db_query($sql);
            $owner                         = $session['user']['acctid'];
            db_query("DELETE FROM items WHERE value2 = 0 AND class='Möbel' AND owner = $owner");
            db_query("DELETE FROM items WHERE value2 = 0 AND class='Einladung' AND value1 = $houseid");
            //$sql1 = 'DELETE FROM commentary WHERE section="house-'.$session['user']['specialmisc']['houseid'].'" OR section="house_private-'.$session['user']['specialmisc']['houseid'].'"';
            //db_query($sql1);
            output('`n`n`c`7Du unterzeichnest den Kaufvertrag und lässt dir die `r' . $sellgoldprice . '
                    Gold`7 sowie `r' . $sellgemprice . ' Edelsteine`7 auszahlen.`c`n`n`0');
            // if treasury module installed, pay off...
            if ($mid                         = module_builtin('treasury', $houseid)) {
                $goldinhouse = (int) getmoduledata($mid, 'gold', $houseid);
                $gemsinhouse = (int) getmoduledata($mid, 'gems', $houseid);
                $sql         = 'SELECT owner FROM items WHERE value1=' . $houseid . ' AND class="Schlüssel"
                        AND owner!=0 AND owner!=' . $session['user']['acctid'];
                $result         = db_query($sql);
                $keys         = db_num_rows($result) + 1;
                $goldgive     = floor($goldinhouse / $keys);
                $gemsgive     = floor($gemsinhouse / $keys);
                // get own gold
                $session['user']['gold'] += $goldgive;
                $session['user']['gems'] += $gemsgive;
                // pay gold to other players
                while ($row         = db_fetch_assoc($result)) {
                    $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid=" . $row['owner'];
                    db_query($sql);
                    systemmail($row['owner'], "`íHaus verkauft!`0", "`x{$session['user']['name']}`ò hat das Haus`x `b$housename`b`ò verkauft. Du bekommst `é$goldgive Gold`ò sowie `é$gemsgive Edelsteine`ò aus dem gemeinsamen Schatz ausbezahlt!`0");
                }
                // delete gold from house
                setmoduledata($mid, 'gold', '0', $houseid);
                setmoduledata($mid, 'gems', '0', $houseid);

                output('`n`n`c`7Jeder Bewohner bekommt seinen Anteil von `r' . $goldgive . ' Gold`7 und `r' . $gemsgive . ' Edelsteine`7 aus dem
                        gemeinsamen Schatz ausgezahlt.`c`n`n`n`n`0');
            } else {
                $sql     = 'SELECT owner FROM items WHERE value1=' . $houseid . ' AND class="Schlüssel"
                        AND owner!=0 AND owner!=' . $session['user']['acctid'];
                $result     = db_query($sql);
                while ($row     = db_fetch_assoc($result)) {
                    systemmail($row['owner'], "`rHaus verkauft!`0", "`7{$session['user']['name']}`r hat das Haus `7`b$housename`b`r verkauft!`0");
                }
            }
            $sql = 'DELETE FROM items WHERE value1=' . $houseid . ' AND class="Schlüssel"';
            db_query($sql);
        } else {
            output('`n`n`c`7Du fragst den alten Mann, wieviel er dir für dein Haus geben würde.`n
                    "`íLassen se mich mal nachsehn, junger Mann... ah, hier hab ichs! Sind harte
                    Zeiten heutzutage... aber weil Sie es sind, geb ich Ihnen `r' . $sellgoldprice . '
                    Gold`í und `r' . $sellgemprice . ' Edelsteine`í für die Bruchbude.`7"`n
                    `7Irgendwie hast du das Gefühl, gar kein so gutes Geschäft zu machen - aber
                    wen wundert\'s, wenn es keine Konkurrenz gibt?`c`n`n`n`n`0');
            addnav("Verkauf");
            addnav('Angebot annehmen', 'houseshop.php?op=sellhouse&sell=1');
            addnav('Lieber ablehnen', 'houseshop.php');
        }
        break;

    case "listmodules":
        if ($session['user']['housekey']) {
            //output("`n`n`aDu hast folgende Räume bereits gebaut:",true);
            $sql     = "SELECT hm.linktitle, hm.moduleid, hm.built_in, hmd.value FROM housemodules hm LEFT JOIN housemoduledata hmd ON hmd.moduleid=hm.moduleid WHERE hmd.houseid='" . $session['user']['housekey'] . "' AND hmd.name='#activated#' AND hm.built_in='0'";
            $result     = db_query($sql) or die(db_error(LINK));
            $count     = db_num_rows($result);
            $raum     = "";

            if ($count == 0) {
                output("`\$`nDu hast noch keine Räume in Deinem Haus bauen lassen.", true);
            } else {
                output("`a`nDu hast bereits " . $count . " Räume ausbauen lassen:`n`n", true);
                for ($y = 0; $y < $count; $y++) {
                    $row = db_fetch_assoc($result);
                    if ($y % 2 == 0)
                        output("`Q", true);
                    else
                        output("`a", true);
                    output($row['linktitle'] . "`n", true);
                    $raum.="AND moduleid!='" . $row['moduleid'] . "' ";
                }
            }

            $sql     = "SELECT linktitle, moduleid, built_in FROM housemodules WHERE built_in='0' " . $raum;
            $result1 = db_query($sql) or die(db_error(LINK));
            $count1     = db_num_rows($result1);
            output("`n`n`aDiese Räume könntest Du Deinem Haus noch zufügen:`n", true);
            if ($count1 == 0) {
                output("`\$`nDu hast bereits alle möglichen Räume in Dein Haus einbauen lassen. Frage die Admins, wann es wieder neue gibt.", true);
            } else {
                output("`a`nDu kannst noch " . $count1 . " Räume ausbauen lassen, wähle weise, denn jeder kostet `^" . $goldcost . " Münzen `aund `%" . $gemcost . " Edelsteine`a:`n", true);
                for ($x = 0; $x < $count1; $x++) {
                    $row1 = db_fetch_assoc($result1);
                    if ($x % 2 == 0)
                        output("`)", true);
                    else
                        output("`a", true);
                    if ($session['user']['gold'] >= $goldcost && $session['user']['gems'] >= $gemcost)
                        output("<a href='houseshop.php?op=room&mid=" . $row1['moduleid'] . "'>", true);
                    output($row1['linktitle'] . "`n", true);
                    if ($session['user']['gold'] >= $goldcost && $session['user']['gems'] >= $gemcost) {
                        output("</a>", true);
                        addnav("", "houseshop.php?op=room&mid=" . $row1['moduleid']);

                        addnav($row1['linktitle'], 'houseshop.php?op=room&mid=' . $row1['moduleid']);
                    }
                }
            }
        } else {
            output("`n`n`\$Du hast noch kein Haus, das Du ausbauen lassen könntest.", true);
        }

        break;

    case "room":
        if ($session['user']['gold'] >= $goldcost && $session['user']['gems'] >= $gemcost) {
            output("`n`aDer Mann nickt Dir zu und schreibt einen Zettel, den er in die Ablage schiebt. \"`#Einige unserer Mitarbeiter machen sich direkt auf den Weg, es sollte bald getan sein.`a\" Zufrieden wendest du Dich zum Gehen.", true);
            $session['user']['gold']-=$goldcost;
            $session['user']['gems']-=$gemcost;
            $sql = "INSERT INTO housemoduledata (moduleid,name,houseid,value) VALUES ('" . $_GET['mid'] . "','#activated#','" . $session['user']['housekey'] . "','1')";
            db_query($sql) or die(db_error(LINK));
        } else {
            output("`n`aVerärgert schüttelt der mann den Kopf. \"`#So viel Geld habt Ihr doch gar nicht`a\" und läßt Dich stehen.", true);
        }

        addnav("Zum Bauamt", "houseshop.php");
        break;


    case "treasury_improve":

        $row     = executeDbSelect('houses', 'treasury_level', 'owner=' . $session['user']['acctid']);
        $costs     = $row[0]['treasury_level'] * $treasury_improve_cost;

        $str_out = '`n`n`c`òDer Mann blättert geduldig in seinen Unterlagen, hebt dann wieder seinen Blick und ';


        if (!$_POST['do']) {
            if ($row[0]['treasury_level'] == $max_treasury) {
                $str_out .= 'sieht dich mit einem Stirnrunzeln an.    "`éIhre Schatzkammer ist schon vollkommen ausgebaut. Noch mehr Gold und Edelsteine und
                        das Ding stürzt in sich zusammen.`ò" Ungeduldig schüttelt er den Kopf und murmelt etwas von "`é...Gier...`ò" vor sich hin`c';
                addnav('Zurück', 'houseshop.php');
            } else {
                $str_out .= 'lächelt schließlich. "`éGeht klar. Aber das wird Sie `^' . $costs . '`é Gold kosten!`ó"';

                if ($session['user']['gold'] < $costs) {
                    $str_out .='`n`nVerflucht! Das kannst du dir nicht leisten.`n`n`$Es ist mehr Gold erforderlich`0!';
                } else {
                    $str_out .= '`n`nDein dickes Portemonnaie gibt das natürlich her, allerdings ist das doch eine ganze Menge Gold.`n
                            Bist du sicher?`0
                            <form action="houseshop.php?op=treasury_improve" method="POST">
                            <input type="submit" class="button" name ="do" value="Jawoll!">
                            </form>';
                    addnav('', 'houseshop.php?op=treasury_improve');
                }

                output($str_out, true);
            }
        } else {
            $sql = 'UPDATE houses SET treasury_level=' . ($row[0]['treasury_level'] + 1) . ' WHERE owner=' . $session['user']['acctid'];
            db_query($sql);

            $session['user']['gold'] -= $costs;

            output('`ò"`éGratuliere. Jetzt können Sie weiter Ihren ergaunerten Reichtu-...ich meine Ihr sauer verdientes Geld in Ihr Haus verfrachten!`ò`n`n
                `$Ausbau abgeschlossen!`0"');
        }

        break;

    default:

        output("`c`n`n`n`n<font face='Harrington' size='6'>
`XD`xa`és `íB`óa`òuamt
</font>`c`n`n`0", true);

        output('`n`n`c`7Ein älterer Mann mit Augengläsern sitzt an einem großen Schreibtisch
                aus Eichenholz und schlürft in aller Ruhe seinen Kaffee.`n
                Als du den Raum betrittst, schaut er kurz auf, murmelt "`éWas wollnse?`7"
                und widmet sich dann wieder den Papieren, die auf dem Schreibtisch verteilt
                sind.`c`n`n`0');
        if ($session['user']['house'] > 0) {
            output('`c`7An der Wand hängen einige Angebote, aber da du bereits ein Haus besitzt,
                    interessierst du dich nicht dafür.`c`n`n`0');
            //addnav("Ausbauten ansehen","houseshop.php?op=listmodules");
        } else {
            output('`c`7An der Wand hängen einige Angebote, die du dir vielleicht mal genauer ansehen
                    soltest.`c`n`n`0');
            addnav("Möglichkeiten");
            addnav('Angebote ansehen', 'houseshop.php?op=listhouses');
        }

        output('`c`7Außerdem siehst du dort einen Zettel, auf dem in großen Buchstaben steht:`n
                ``éKaufe jedes Haus zu einem angemessenen Preis. Bei Interesse einfach beim
                Bauamtsleiter nachfragen!`7`n
                Offenbar handelt es sich um die Anzeige eines Maklers.`c`n`n`0');
        if ($session['user']['house'] > 0) {
            if ($session['user']['housekey'] == 0) {
                output('`n`n`c`7Bevor dein Haus fertig gebaut ist, wird er es aber kaum haben wollen`c`n`n`0');
            } else {
                addnav("Verkauf");
                addnav('Haus verkaufen', 'houseshop.php?op=sellhouse');
            }
        }

        if (getsetting('startbuild', 1) == 0 && $session['user']['house'] == 0 && ($session['user']['dragonkills'] > getsetting('mindkbuild', 1) || $session['user']['dragonkills'] == getsetting('mindkbuild', 1) && $session['user']['level'] >= getsetting('minlevelbuild', 5))) {
            // get houses without owner
            $sql     = 'SELECT houseconfig.locid, houseconfig.locname, houseconfig.defaultgoldprice, houseconfig.defaultgemprice, houseconfig.buildprice_increase, houses.locid AS hloc, COUNT(*) AS zahl
                        FROM houseconfig
                        LEFT JOIN houses ON houses.locid=houseconfig.locid AND houses.owner=0
                        WHERE houseconfig.build="1"
                        GROUP BY houseconfig.locid
                        ORDER BY houseconfig.locname ASC';
            $result     = db_query($sql);
            $empty     = false;

            output('`n`n`c`7Da du den Mann offenbar recht hilflos ansiehst, erklärt er dir: "`íWenn du
                    ein Haus selbst bauen willst, bist du hier richtig. Sofern du das nötige
                    Kleingold hast, unterschreib hier, hier und hier für eine Baugenehmigung!`ò"`c`n`n`0');
            if (db_num_rows($result) == 1) {
                $row = db_fetch_assoc($result);
                if ($row['hloc'] > 0) {
                    $emptyhouses = $row['zahl'];
                    if ($emptyhouses > 0)
                        $empty         = true;
                }
                else {
                    $emptyhouses = 0;
                }
                $faktor = $row['buildprice_increase'] / 100;
                output('`n`n`c`7Er hält dir ein Formular hin, aus dem hervorgeht, dass du für den Bau
                            nicht weniger als `r' . round($row['defaultgoldprice'] * (1 + $emptyhouses * $faktor)) . '
                        Gold`7 sowie `r' . round($row['defaultgemprice'] * (1 + $emptyhouses * $faktor)) . ' Edelsteine`7
                            berappen musst. Natürlich nicht auf einmal, sondern in Raten während des Baus.`0`c`n`n');
                addnav("Genehmigung");
                addnav('Baugenehmigung beantragen', 'houseshop.php?op=buylot&where=' . $row['locid']);
            } else {
                output('`n`7Er hält dir ein Formular hin, aus dem folgende Preise hervorgehen:`c`n`n`0');
                output('<table border="0">', true);
                while ($row = db_fetch_assoc($result)) {
                    if ($row['hloc'] > 0) {
                        $emptyhouses = $row['zahl'];
                        if ($emptyhouses > 0)
                            $empty         = true;
                    }
                    else {
                        $emptyhouses = 0;
                    }
                    $faktor = $row['buildprice_increase'] / 100;
                    output('<tr><td>', true);
                    output($row['locname']);
                    output('</td><td>', true);
                    output('`^' . round($row['defaultgoldprice'] * (1 + $emptyhouses * $faktor)) . ' Gold`8, `%' . round($row['defaultgemprice'] * (1 + $emptyhouses * $faktor)) . ' Edelsteine`8');
                    output('</td><td>', true);
                    output('<a href="houseshop.php?op=buylot&where=' . $row['locid'] . '">Baugenehmigung beantragen</a>', true);
                    addnav('', 'houseshop.php?op=buylot&where=' . $row['locid']);
                    output('</td></tr>', true);
                }
                output('</table>', true);
            }
        }
        addnav('Verbesserungen');
        addnav('Schatzkammer ausbauen', 'houseshop.php?op=treasury_improve');

        break;
}
addnav("Zurück");
if ($_GET['op'] != '')
    addnav("Zum Bauamt", "houseshop.php");

addnav("Wohnviertel", "houses.php");
addnav("Zur Stadt", "village.php");


output('`n`n');
output('`c`b&copy; by <a href="http://logd.chaosonline.de" target="_blank">Chaosmaker</a>`b`c', true);

page_footer();
?>


