<?php
/*****************************************
 *
 * houseshop.php
 * Author: Chaosmaker <webmaster@chaosonline.de>
 * Version: 1.3
 * Server: biosLoGD http://logd.chaosonline.de
 *
 * Features:
 *    - buy houses
 *    - sell own house
 *    - buy a building lot if required
 *
 *****************************************/

require_once('common.php');
checkday();

require_once('housefunctions.php');

page_header('Das Bauamt');

if (!empty($_GET['op'])) $op = $_GET['op'];
else $op = '';

switch ($op) {
    case 'listhouses':
        output('`c`bHaus kaufen`b`c`n`n');

        // house selected
        if (!empty($_GET['buy'])) {
            // get house data
            $sql = 'SELECT h.status, h.goldprice, h.gemprice, h.housename,
                    hc.defaultgoldprice, hc.defaultgemprice
                    FROM houses h
                    LEFT JOIN houseconfig hc USING(locid)
                    WHERE h.owner=0 AND hc.buy="1" AND h.houseid='.$_GET['buy'];
            $result = db_query($sql);
            if ($row = db_fetch_assoc($result)) {
                if ($row['status']=='build') {
                    $goldprice = round(($row['defaultgoldprice'] - $row['goldprice'])*2/3);
                    $gemprice = round(($row['defaultgemprice'] - $row['gemprice'])*2/3);
                }
                else {
                    $goldprice = round(($row['defaultgoldprice'] + $row['goldprice'])*2/3);
                    $gemprice = round(($row['defaultgemprice'] + $row['gemprice'])*2/3);
                }

                if ($session['user']['gold']<$goldprice || $session['user']['gems']<$gemprice) {
                    output('`8Der alte Mann fragt dich, wie du das Haus bezahlen willst - darüber
                            hast du noch gar nicht nachgedacht. Vielleicht solltest du es jetzt
                            nachholen...');
                }
                else {
                    // delete old keys
                    $sql = 'DELETE FROM items WHERE class="Schlüssel" AND value1='.$_GET['buy'];
                    db_query($sql);
                    addnews("`2".$session['user']['name']."`3 hat das Haus `2{$row['housename']}`3 gekauft.");
                    output("`8`bGlückwunsch!`b Du hast das Haus gekauft.");
                    $session['user']['house'] = $_GET['buy'];
                    if ($row['status']!='build') {
                        $session['user']['housekey'] = $_GET['buy'];
                        output("Du bekommst `b".getsetting('newhousekeys',10)."`b Schlüssel überreicht, von denen du ".(getsetting('newhousekeys',10)-1)." an andere weitergeben kannst und besitzt nun deine eigene kleine Burg.");
                        // add new keys for the house
                        $sql = '';
                        for ($i=1;$i<getsetting('newhousekeys',10);$i++) {
                            $sql .= ",('Hausschlüssel',".$session['user']['acctid'].",'Schlüssel',{$_GET['buy']},$i,0,0,'Schlüssel für Haus Nummer {$_GET['buy']}')";
                        }
                        if ($sql!='') {
                            $sql = 'INSERT INTO items (name,owner,class,value1,value2,gold,gems,description) VALUES '.substr($sql,1);
                            db_query($sql);
                            if (db_affected_rows(LINK)==0) output("`\$Fehler`^: Dein Inventar konnte nicht aktualisiert werden! Bitte benachrichtige den Admin.");
                        }
                    }
                    // update house
                    if ($row['status']=='sell') $row['status'] = 'ready';
                    $sql = 'UPDATE houses SET owner='.$session['user']['acctid'].',status="'.$row['status'].'" WHERE houseid='.$_GET['buy'];
                    db_query($sql);
                    $session['user']['gems'] -= $gemprice;
                    $session['user']['gold'] -= $goldprice;
                    // kill possible commentary
                    $sql = 'DELETE FROM commentary WHERE section="house-'.$session['user']['specialmisc']['houseid'].'" OR section="private-'.$session['user']['specialmisc']['houseid'].'"';
                    db_query($sql);
                }
            }
            else {
                output('`8So ein Pech - da war wohl jemand schneller als du!');
            }
        }
        else {
            // get all abandoned houses
            $sql = 'SELECT h.houseid, h.status, h.goldprice, h.gemprice, h.housename,
                    hc.locname, hc.defaultgoldprice, hc.defaultgemprice
                    FROM houses h
                    LEFT JOIN houseconfig hc USING(locid)
                    WHERE h.owner=0 AND hc.buy="1"
                    ORDER BY h.locid ASC, h.houseid ASC';
            $result = db_query($sql);
            if (db_num_rows($result)==0) {
                output('`8Leider will derzeit niemand ein Haus verkaufen.');
            }
            else {
                output('`8Du schaust dir die Zettel an, auf denen Häuser feilgeboten werden. Ob da
                        wohl was für dich dabei ist?`n');
                while ($row = db_fetch_assoc($result)) {
                    output('<table border="0"><tr class="trhead"><td colspan="2">',true);
                    output($row['housename']);
                    output('</td></tr><tr class="trlight"><td>',true);
                    output('Standort:');
                    output('</td><td>',true);
                    output($row['locname']);
                    output('</tr><tr class="trdark"><td>',true);
                    output('Hausnummer:');
                    output('</td><td>',true);
                    output($row['houseid']);
                    output('</tr><tr class="trlight"><td>',true);
                    output('Hausname:');
                    output('</td><td>',true);
                    output($row['housename']);
                    output('</tr><tr class="trdark"><td>',true);
                    output('Status:');
                    output('</td><td>',true);
                    if ($row['status']=='build') $status = 'Bauruine';
                    elseif ($row['status']=='ready') $status = 'verlassen';
                    else $status = 'wie neu';
                    output($status);
                    output('</tr><tr class="trlight"><td>',true);
                    output('Preis:');
                    output('</td><td>',true);
                    if ($row['status']=='build') {
                        $goldprice = round(($row['defaultgoldprice'] - $row['goldprice'])*2/3);
                        $gemprice = round(($row['defaultgemprice'] - $row['gemprice'])*2/3);
                    }
                    else {
                        $goldprice = round(($row['defaultgoldprice'] + $row['goldprice'])*2/3);
                        $gemprice = round(($row['defaultgemprice'] + $row['gemprice'])*2/3);
                    }
                    output('`^'.$goldprice.' Gold`8, `%'.$gemprice.' Edelsteine`8');
                    output('</tr><tr class="trdark"><td>',true);
                    output('');
                    output('</td><td>',true);
                    output('<a href="houseshop.php?op=listhouses&buy='.$row['houseid'].'">kaufen</a>',true);
                    output('</td></tr></table><br>',true);
                    addnav('','houseshop.php?op=listhouses&buy='.$row['houseid']);
                }
            }
        }
        addnav('Zurück zum Bauamt','houseshop.php');
        break;
    case 'buylot':
        output('`c`bBaugenehmigung beantragen`b`c`n`n');
        // get houses without owner
        $sql = 'SELECT houseconfig.locname, houseconfig.defaultgoldprice, houseconfig.defaultgemprice, houseconfig.buildprice_increase, houses.locid AS hloc, COUNT(*) AS zahl
                    FROM houseconfig
                    LEFT JOIN houses ON houses.locid=houseconfig.locid AND houses.owner=0
                    WHERE houseconfig.locid="'.$_GET['where'].'"
                    GROUP BY houseconfig.locid';
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        if ($row['hloc']>0) {
            $emptyhouses = $row['zahl'];
        }
        else {
            $emptyhouses = 0;
        }
        $faktor = $row['buildprice_increase']/100;
        $goldprice = round($row['defaultgoldprice']*(1+$emptyhouses*$faktor));
        $gemprice = round($row['defaultgemprice']*(1+$emptyhouses*$faktor));
        $sql = 'INSERT INTO houses (owner, status, goldprice, gemprice, housename, locid)
                VALUES ('.$session['user']['acctid'].',"build",'.$goldprice.','.$gemprice.',"'.$session['user']['login'].'s Haus",'.$_GET['where'].')';
        db_query($sql);
        $houseid = db_insert_id(LINK);
        $session['user']['house'] = $houseid;
        output('Der alte Mann händigt dir ein Papier aus, laut dem du von nun an der Besitzer des
                Grundstücks Nummer '.$houseid.' bist. Dein Haus wird unter dem Namen
                "`b'.$session['user']['login'].'s Haus`b" eingetragen. Du kannst den Namen allerdings
                später auch noch gegen eine geringe Gebühr ändern.');
        addnav('Zurück zum Bauamt','houseshop.php');
        break;
    case 'sellhouse':
        output('`c`bHausverkauf`b`c`n`n');

        // get worth of own house and installed modules
        $sql = 'SELECT houses.locid, status, housename, goldprice, gemprice, defaultgoldprice, defaultgemprice, buildprice_increase, sell
                    FROM houses
                    LEFT JOIN houseconfig USING(locid)
                    WHERE houseid='.$session['user']['house'];
        $result = db_query($sql);
        $row = db_fetch_assoc($result);
        $faktor = $row['buildprice_increase']/100;

        // get houses without owner
        $sql = 'SELECT COUNT(*) AS zahl
                    FROM houses
                    WHERE locid="'.$row['locid'].'" AND owner=0';
        $result = db_query($sql);
        $row2 = db_fetch_assoc($result);
        $emptyhouses = $row2['zahl'];

        if ($row['status']!='build') {
            $sellgoldprice = max(1,round(($row['goldprice']+$row['defaultgoldprice'])/3 * (1-$emptyhouses*$faktor)));
            $sellgemprice = max(1,round(($row['gemprice']+$row['defaultgemprice'])/3 * (1-$emptyhouses*$faktor)));
            $status = 'sell';
        }
        else {
            $sellgoldprice = max(1,round(($row['defaultgoldprice']-$row['goldprice'])/3 * (1-$emptyhouses*$faktor)));
            $sellgemprice = max(1,round(($row['defaultgemprice']-$row['gemprice'])/3 * (1-$emptyhouses*$faktor)));
            $status = 'build';
        }
        $housename = $row['housename'];

        if ($row['sell']==0) {
            output('`8Der Mann weist dich darauf hin, dass du nicht berechtigt bist, dein Haus zu
                    verkaufen. Du kannst deine Enttäuschung kaum verbergen.');
            addnav('Zurück zum Bauamt','houseshop.php');
        }
        elseif (!empty($_GET['sell'])) {
            // sell house
            $houseid = $session['user']['house'];
            $session['user']['house'] = $session['user']['housekey'] = 0;
            $session['user']['gold'] += $sellgoldprice;
            $session['user']['gems'] += $sellgemprice;
            $sql = 'UPDATE houses SET owner=0, status="'.$status.'" WHERE houseid='.$houseid;
            db_query($sql);
            output('`8Du unterzeichnest den Kaufvertrag und lässt dir die `^'.$sellgoldprice.'
                    Gold`8 sowie `%'.$sellgemprice.' Edelsteine`8 auszahlen.');
            // if treasury module installed, pay off...
            if ($mid = module_builtin('treasury',$houseid)) {
                $goldinhouse = (int)getmoduledata($mid,'gold',$houseid);
                $gemsinhouse = (int)getmoduledata($mid,'gems',$houseid);
                $sql = 'SELECT owner FROM items WHERE value1='.$houseid.' AND class="Schlüssel"
                        AND owner!=0 AND owner!='.$session['user']['acctid'];
                $result = db_query($sql);
                $keys = db_num_rows($result)+1;
                $goldgive = floor($goldinhouse/$keys);
                $gemsgive = floor($gemsinhouse/$keys);
                // get own gold
                $session['user']['gold'] += $goldgive;
                $session['user']['gems'] += $gemsgive;
                // pay gold to other players
                while ($row = db_fetch_assoc($result)) {
                    $sql = "UPDATE accounts SET goldinbank=goldinbank+$goldgive,gems=gems+$gemsgive WHERE acctid=".$row['owner'];
                    db_query($sql);
                    systemmail($row['owner'],"`@Haus verkauft!`0","`&{$session['user']['name']}`2 hat das Haus `b$housename`b`2 verkauft. Du bekommst `^$goldgive Gold`2 sowie `%$gemsgive Edelsteine`2 aus dem gemeinsamen Schatz ausbezahlt!");
                }
                // delete gold from house
                setmoduledata($mid,'gold','0',$houseid);
                setmoduledata($mid,'gems','0',$houseid);

                output('`nJeder Bewohner bekommt seinen Anteil von `^'.$goldgive.' Gold`8 und `%'.$gemsgive.' Edelsteine`8 aus dem
                        gemeinsamen Schatz ausgezahlt.');
            }
            else {
                $sql = 'SELECT owner FROM items WHERE value1='.$houseid.' AND class="Schlüssel"
                        AND owner!=0 AND owner!='.$session['user']['acctid'];
                $result = db_query($sql);
                while ($row = db_fetch_assoc($result)) {
                    systemmail($row['owner'],"`@Haus verkauft!`0","`&{$session['user']['name']}`2 hat das Haus `b$housename`b`2 verkauft!");
                }
            }
            $sql = 'DELETE FROM items WHERE value1='.$houseid.' AND class="Schlüssel"';
            db_query($sql);
            addnav('Zurück zum Bauamt','houseshop.php');
        }
        else {
            output('`8Du fragst den alten Mann, wieviel er dir für dein Haus geben würde.`n
                    "`6Lassen se mich mal nachsehn, junger Mann... ah, hier hab ichs! Sind harte
                    Zeiten heutzutage... aber weil Sie es sind, geb ich Ihnen `^'.$sellgoldprice.'
                    Gold`6 und `%'.$sellgemprice.' Edelsteine`6 für die Bruchbude.`8"`n
                    Irgendwie hast du das Gefühl, gar kein so gutes Geschäft zu machen - aber
                    wen wundert\'s, wenn es keine Konkurrenz gibt?');
            addnav('Angebot annehmen','houseshop.php?op=sellhouse&sell=1');
            addnav('Lieber ablehnen','houseshop.php');
        }
        break;
    default:
        output('`c`bDas Bauamt`b`c`n`n');
        output('`8Ein älterer Mann mit Augengläsern sitzt an einem großen Schreibtisch
                aus Eichenholz und schlürft in aller Ruhe seinen Kaffee.`n
                Als du den Raum betrittst, schaut er kurz auf, murmelt "`6Was wollnse?`8"
                und widmet sich dann wieder den Papieren, die auf dem Schreibtisch verteilt
                sind.`n`n');
        if ($session['user']['house']>0) {
            output('An der Wand hängen einige Angebote, aber da du bereits ein Haus besitzt,
                    interessierst du dich nicht dafür.');
        }
        else {
            output('An der Wand hängen einige Angebote, die du dir vielleicht mal genauer ansehen
                    solltest.`n');
            addnav('Angebote ansehen','houseshop.php?op=listhouses');
        }

        output('Außerdem siehst du dort einen Zettel, auf dem in großen Buchstaben steht:`n
                `6Kaufe jedes Haus zu einem angemessenen Preis. Bei Interesse einfach beim
                Bauamtsleiter nachfragen!`8`n
                Offenbar handelt es sich um die Anzeige eines Maklers.');
        if ($session['user']['house']>0) {
            if ($session['user']['housekey']==0) {
                output('`nBevor dein Haus fertig gebaut ist, wird er es aber kaum haben wollen');
            }
            else {
                addnav('Haus verkaufen','houseshop.php?op=sellhouse');
            }
        }

        if (getsetting('startbuild',1)==0 && $session['user']['house']==0 && ($session['user']['dragonkills']>getsetting('mindkbuild',1) || $session['user']['dragonkills']==getsetting('mindkbuild',1) && $session['user']['level']>=getsetting('minlevelbuild',5))) {
            // get houses without owner
            $sql = 'SELECT houseconfig.locid, houseconfig.locname, houseconfig.defaultgoldprice, houseconfig.defaultgemprice, houseconfig.buildprice_increase, houses.locid AS hloc, COUNT(*) AS zahl
                        FROM houseconfig
                        LEFT JOIN houses ON houses.locid=houseconfig.locid AND houses.owner=0
                        WHERE houseconfig.build="1"
                        GROUP BY houseconfig.locid
                        ORDER BY houseconfig.locname ASC';
            $result = db_query($sql);
            $empty = false;

            output('Da du den Mann offenbar recht hilflos ansiehst, erklärt er dir: "`6Wenn du
                    ein Haus selbst bauen willst, bist du hier richtig. Sofern du das nötige
                    Kleingold hast, unterschreib hier, hier und hier für eine Baugenehmigung!`8"`n');
            if (db_num_rows($result)==1) {
                    $row = db_fetch_assoc($result);
                    if ($row['hloc']>0) {
                        $emptyhouses = $row['zahl'];
                        if ($emptyhouses > 0) $empty = true;
                    }
                    else {
                        $emptyhouses = 0;
                    }
                    $faktor = $row['buildprice_increase']/100;
                    output ('Er hält dir ein Formular hin, aus dem hervorgeht, dass du für den Bau
                            nicht weniger als `^'.round($row['defaultgoldprice']*(1+$emptyhouses*$faktor)).'
                        Gold`8 sowie `%'.round($row['defaultgemprice']*(1+$emptyhouses*$faktor)).' Edelsteine`8
                            berappen musst. Natürlich nicht auf einmal, sondern in Raten während des Baus.');
                    addnav('Baugenehmigung beantragen','houseshop.php?op=buylot&where='.$row['locid']);
            }
            else {
                    output('Er hält dir ein Formular hin, aus dem folgende Preise hervorgehen:');
                    output('<table border="0">',true);
                    while ($row = db_fetch_assoc($result)) {
                        if ($row['hloc']>0) {
                            $emptyhouses = $row['zahl'];
                            if ($emptyhouses > 0) $empty = true;
                        }
                        else {
                            $emptyhouses = 0;
                        }
                        $faktor = $row['buildprice_increase']/100;
                        output('<tr><td>',true);
                        output($row['locname']);
                        output('</td><td>',true);
                        output('`^'.round($row['defaultgoldprice']*(1+$emptyhouses*$faktor)).' Gold`8, `%'.round($row['defaultgemprice']*(1+$emptyhouses*$faktor)).' Edelsteine`8');
                        output('</td><td>',true);
                        output('<a href="houseshop.php?op=buylot&where='.$row['locid'].'">Baugenehmigung beantragen</a>',true);
                        addnav('','houseshop.php?op=buylot&where='.$row['locid']);
                        output('</td></tr>',true);
                    }
                    output('</table>',true);
            }
        }
        break;
}

addnav('Zurück zur Stadt','village.php');

output('`n`n');
output('`c`b&copy; by <a href="http://logd.chaosonline.de" target="_blank">Chaosmaker</a>`b`c',true);

page_footer();
?>