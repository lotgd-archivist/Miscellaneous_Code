
ï»¿<?php



// 20060406



// New Hall of Fame features by anpera with code by MightyE

// http://www.anpera.net/forum/viewforum.php?f=27





require_once("common.php");



page_header("Ruhmeshalle");

checkday();



$playersperpage = 50;



$op = "kills";

if ($_GET['op']) $op = $_GET['op'];

$subop = "most";

if ($_GET['subop']) $subop = $_GET['subop'];



$sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0";

if ($op == "kills") {

    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0";

} elseif ($op == "days") {

    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0 AND bestdragonage>0";

}



$result = db_query($sql);

$row = db_fetch_assoc($result);

$totalplayers = $row['c'];



$page = 1;

if ($_GET['page']) $page = (int)$_GET['page'];

$pageoffset = $page;

if ($pageoffset > 0) $pageoffset--;

$pageoffset *= $playersperpage;

$from = $pageoffset+1;

$to = min($pageoffset+$playersperpage, $totalplayers);

$limit = "$pageoffset,$playersperpage";



addnav("Bestenlisten");

addnav("Drachenkills", "hof.php?op=kills&subop=$subop&page=$page");

addnav("Reichtum", "hof.php?op=money&subop=$subop&page=$page");

addnav("Edelsteine", "hof.php?op=gems&subop=$subop&page=$page");

addnav("SchÃ¶nheit", "hof.php?op=charm&subop=$subop&page=$page");

addnav("StÃ¤rke", "hof.php?op=tough&subop=$subop&page=$page");

addnav("Schlagkraft","hof.php?op=punch&subop=$subop&page=$page");

addnav("Tollpatsche", "hof.php?op=resurrects&subop=$subop&page=$page");

addnav("Geschwindigkeit", "hof.php?op=days&subop=$subop&page=$page");

addnav("Monsterkills","hof.php?op=creaturekills&subop=$subop&page=$page");

addnav("ArenakÃ¤mpfer","hof.php?op=battlepoints&subop=$subop&page=$page");

if ($session[user][alive]==0) addnav("Ramius' Lieblinge","hof.php?op=grave&subop=$subop&page=$page");

addnav("Sortieren nach");

addnav("Besten", "hof.php?op=$op&subop=most&page=$page");

addnav("Schlechtesten", "hof.php?op=$op&subop=least&page=$page");

addnav("Seiten");

for($i = 0; $i < $totalplayers; $i+= $playersperpage) {

    $pnum = ($i/$playersperpage+1);

    $min = ($i+1);

    $max = min($i+$playersperpage,$totalplayers);

    addnav("Seite $pnum ($min-$max)", "hof.php?op=$op&subop=$subop&page=$pnum");

}

addnav("Sonstiges");

addnav("Statistiken","hof.php?op=statistics");

addnav("Paare dieser Welt","hof.php?op=paare");

if ($session['user']['alive']){

    addnav("ZurÃ¼ck zum Dorf","village.php");

}else{

    addnav("ZurÃ¼ck zu den Schatten","shades.php");

}



function display_table($title, $sql, $none=false, $foot=false, $data_header=false, $tag=false){

    global $session, $from, $to, $page;

    output("`c`b`^$title`0`b `7(Seite $page: $from-$to)`0`c`n");

    output('<table cellspacing="0" cellpadding="2" align="center"><tr class="trhead">',true);

    output("<td>`bRang`b</td><td>`bName`b</td>", true);

    if ($data_header !== false) {

        for ($i = 0; $i < count($data_header); $i++) {

            output("<td>`b".$data_header[$i]."`b</td>", true);

        }

    }

    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)==0){

        $size = ($data_header === false) ? 2 : 2+count($data_header);

        //echo $size;

        if ($none === false) $none = "Keine Spieler gefunden";

        output('<tr class="trlight"><td colspan="'. $size .'" align="center">`&' . $none .'`0</td></tr>',true);

    } else {

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            if ($row['name']==$session['user']['name']){

                output("<tr bgcolor='#005500'>",true);

            } else {

                output('<tr class="'.($i%2?"trlight":"trdark").'">',true);

            }

            output("<td>".($i+$from).".</td><td>`&{$row['name']}`0</td>",true);

            if ($data_header !== false) {

                for ($j = 0; $j < count($data_header); $j++) {

                    $id = "data" . ($j+1);

                    $val = $row[$id];

                    if ($tag !== false) $val = $val . " " . $tag[$j];

                    output("<td align='right'>$val</td>",true);

                }

            }

            output("</tr>",true);

        }

    }

    output("</table>", true);

    if ($foot !== false) output("`n`c$foot`c");

}



$order = "DESC";

if ($_GET['subop'] == "least") $order = "ASC";

$sexsel = "IF(sex,'<img src=\"images/female.gif\">&nbsp; &nbsp;','<img src=\"images/male.gif\">&nbsp; &nbsp;')";

$racesel = "CASE race WHEN 1 THEN '`2Troll`0' WHEN 2 THEN '`^Elf`0' WHEN 3 THEN '`&Mensch`0' WHEN 4 THEN '`#Zwerg`0' WHEN 5 THEN '`5Echse`0' WHEN 6 THEN '`4Vampir`0' ELSE '`7Unbekannt`0' END";



if ($_GET['op']=="money"){

    $sql = "SELECT name,(goldinbank+gold+round((((rand()*10)-5)/100)*(goldinbank+gold))) AS data1 FROM accounts WHERE locked=0 ORDER BY data1 $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "reichsten";

    if ($_GET['subop'] == "least") $adverb = "Ã¤rmsten";

    $title = "Die $adverb Krieger in diesem Land";

    $foot = "(VermÃ¶gen +/- 5%)";

    $headers = array("GeschÃ¤tztes VermÃ¶gen");

    $tags = array("Gold");

    display_table($title, $sql, false, $foot, $headers, $tags);

} elseif ($_GET['op'] == "gems") {

    $sql = "SELECT name FROM accounts WHERE locked=0 ORDER BY gems $order, level $order, experience $order, acctid $order LIMIT $limit";

    if ($_GET['subop'] == "least") $adverb = "wenigsten";

    else $adverb = "meisten";

    $title = "Die Krieger mit den $adverb Edelsteinen";

    display_table($title, $sql);

} elseif ($_GET['op']=="charm"){

    $sql = "SELECT name,$sexsel AS data1,$racesel AS data2 FROM accounts WHERE locked=0 ORDER BY charm $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "schÃ¶nsten";

    if ($_GET['subop'] == "least") $adverb = "hÃ¤sslichsten";

    $title = "Die $adverb Krieger in diesem Land.";

    $headers = array("<img src=\"images/female.gif\">/<img src=\"images/male.gif\">", "Rasse");

    display_table($title, $sql, false, false, $headers, false);

} elseif ($_GET['op']=="tough"){

    $sql = "SELECT name,level AS data2 ,$racesel as data1 FROM accounts WHERE locked=0 ORDER BY maxhitpoints $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "stÃ¤rksten";

    if ($_GET['subop'] == "least") $adverb = "schwÃ¤chsten";

    $title = "Die $adverb Krieger in diesem Land";

    $headers = array("Rasse", "Level");

    display_table($title, $sql, false, false, $headers, false);

} elseif ($_GET['op']=="creaturekills"){

    $sql = "SELECT name,creaturekills AS data1, dragonkills AS data2, level AS data3 FROM accounts WHERE locked=0 ORDER BY creaturekills $order, dragonkills $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "tÃ¶dlichsten";

    if ($_GET['subop'] == "least") $adverb = "harmlosesten";

    $title = "Die $adverb Krieger in diesem Land";

    $headers = array("Monsterkills","Drachenkills","Level");

    display_table($title, $sql, false, false, $headers, false);

}elseif ($_GET['op']=="punch"){

    $sql = "SELECT name,punch AS data1,$racesel AS data2 FROM accounts WHERE locked=0 ORDER BY data1 $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "hÃ¤rtesten";

    if ($_GET['subop'] == "least") $adverb = "armseligsten";

    $title = "Die $adverb SchlÃ¤ge aller Zeiten";

    $headers = array("Punkte","Rasse");

    display_table($title, $sql, false, false, $headers, false);

} elseif ($_GET['op']=="resurrects"){

    $sql = "SELECT name,level AS data1 FROM accounts WHERE locked=0 ORDER BY resurrections $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "tollpatschigsten";

    if ($_GET['subop'] == "least") $adverb = "geschicktesten";

    $title = "Die $adverb Krieger in diesem Land";

    $headers = array("Level");

    display_table($title, $sql, false, false, $headers, false);

} elseif ($_GET['op']=="grave"){

    $sql = "SELECT name,deathpower,location,loggedin,laston,alive FROM accounts WHERE locked=0 ORDER BY deathpower $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "fleissigste";

    if ($_GET['subop'] == "least") $adverb = "faulste";

    $title = "Ramius' $adverb Krieger";

    output("`c`b`^$title`0`b `7(Seite $page: $from-$to)`0`c`n");

    output('<table cellspacing="0" cellpadding="2" align="center"><tr class="trhead">',true);

    output("<td>`bRang`b</td><td>`bName`b</td><td>`bGefallen`b</td><td>`bOrt`b</td><td>`bStatus`b</td></tr>", true);

    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)==0){

        output('<tr class="trlight"><td colspan="5" align="center">`&Keine Spieler gefunden`0</td></tr>',true);

    } else {

        for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

            if ($row['name']==$session['user']['name']){

                output("<tr bgcolor='#005500'>",true);

            } else {

                output('<tr class="'.($i%2?"trlight":"trdark").'">',true);

            }

            output("<td>".($i+$from).".</td><td>`&{$row[name]}`0</td><td align='right'>`){$row[deathpower]}`0</td><td>",true);

            $loggedin=(date("U") - strtotime($row['laston']) < getsetting("LOGINTIMEOUT",900) && $row['loggedin']);

            if ($row['location']==0) output($loggedin?"`#Online`0":"`3Die Felder`0");

            if ($row['location']==1) output("`3Zimmer in Kneipe`0");

            if ($row['location']==2) output("`3Im Haus`0");

            output("</td><td>",true);

            output($row['alive']?"`1Lebt`0":"`4Tot`0");

            output("</td></tr>",true);

        }

    }

    output("</table>", true);

} elseif ($_GET['op']=="days") {

    $order = "ASC";

    if ($_GET['subop'] == "least") $order = "DESC";

    $sql = "SELECT name, IF(bestdragonage,bestdragonage,'Unknown') AS data1 FROM accounts WHERE dragonkills>0 AND locked=0 AND bestdragonage>0 ORDER BY bestdragonage $order, level $order, experience $order, acctid $order LIMIT $limit";

    $adverb = "schnellsten";

    if ($_GET['subop'] == "least") $adverb = "langsamsten";

    $title = "Helden mit den $adverb Drachenkills";

    $headers = array("Bestzeit Tage");

    $none = "Es gibt noch keine Helden in diesem Land";

    display_table($title, $sql, $none, false, $headers, false);

} elseif ($_GET['op']=="battlepoints"){

    $sql = "SELECT name,battlepoints AS data1,dragonkills AS data2 FROM accounts WHERE locked=0 ORDER BY battlepoints $order, dragonkills $order, acctid $order LIMIT $limit";

    $adverb = "besten";

    if ($_GET['subop'] == "least") $adverb = "schlechtesten";

    $title = "Die $adverb ArenakÃ¤mpfer in diesem Land";

    $headers = array("Punkte","Drachenkills");

    display_table($title, $sql, false, false, $headers, false);

}else if ($_GET['op']=="paare"){

    output("In einem Nebenraum der Ruhmeshalle findest du eine Liste mit Helden ganz anderer Art. Diese Helden Meistern gemeinsam die Gefahren der Ehe!`n`n");

    $sql = "SELECT acctid,name,marriedto FROM accounts WHERE sex=0 AND charisma=4294967295 ORDER BY acctid DESC";

    output("`c`b`&Heldenpaare dieser Welt`b`c`n");

    output("<table cellspacing=0 cellpadding=2 align='center'><tr><td><img src=\"images/female.gif\">`b Name`b</td><td></td><td><img src=\"images/male.gif\">`b Name`b</td></tr>",true);

    $result = db_query($sql) or die(db_error(LINK));

    if (db_num_rows($result)==0){

          output("<tr><td colspan=4 align='center'>`&`iIn diesem Land gibt es keine Paare`i`0</td></tr>",true);

    }

    for ($i=0;$i<db_num_rows($result);$i++){

            $row = db_fetch_assoc($result);

        $sql2 = "SELECT name FROM accounts WHERE acctid=".$row['marriedto']."";

        $result2 = db_query($sql2) or die(db_error(LINK));

            $row2 = db_fetch_assoc($result2);

          output("<tr class='".($i%2?"trlight":"trdark")."'><td>`&{$row2['name']}`0</td><td>`) und `0</td><td>`&",true);

        output("{$row['name']}`0</td></tr>",true);

    }

    output("</table>",true);

}else if ($_GET['op']=="statistics"){

    output("`3Du betrachtest die BevÃ¶lkerungsstatistiken des Dorfes:`n`n");

    $result=db_query("SELECT race,specialty,alive FROM accounts WHERE locked=0 AND emailvalidation=''");

    output("`3Das Dorf hat `^".db_num_rows($result)."`3 Einwohner. Davon sind:`n`n");

    output("<table border='0' cellspacing='3' cellpadding='0'>",true);

    $races_stats=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);

    $specialty_stats=array(0=>0,1=>0,2=>0,3=>0,4=>0,5=>0,6=>0);

    $spec_col=array(1=>"`\$",2=>"`%",3=>"`^",4=>"`q",5=>"`#",6=>"`@");

    $alive=0;

    while($stats=db_fetch_assoc($result)){

        $races_stats[$stats['race']]++;

        $specialty_stats[$stats['specialty']]++;

        if ($stats['alive']) $alive++;

    }

    for ($i=1;$i<=6;$i++){

        output("<tr><td align='right'>`^".$races_stats[$i]."</td><td align='left'>`3 ".$colraces[$i]."</td></tr>",true);

    }

    output("<tr height='12'><td colspan='2'></td></tr>",true);

    for ($i=1;$i<=6;$i++){

        output("<tr><td align='right'>`^".$specialty_stats[$i]."</td><td align='left'>".$spec_col[$i].$specialty[$i]."</td></tr>",true);

    }

    output("<tr height='12'><td colspan='2'></td></tr>",true);

    output("<tr><td align='right'>`^".$alive."</td><td align='left'>`3 lebendig</td></tr>",true);

    output("</table>",true);

} else {

    $sql = "SELECT name,dragonkills AS data1,level AS data2,'&nbsp;' AS data3, IF(dragonage,dragonage,'Unknown') AS data4, '&nbsp;' AS data5, IF(bestdragonage,bestdragonage,'Unknown') AS data6 FROM accounts WHERE dragonkills>0 AND locked=0 ORDER BY dragonkills $order,level $order,experience $order, acctid $order LIMIT $limit";

    $adverb = "meisten";

    if ($_GET['subop'] == "least") $adverb = "wenigsten";

    $title = "Helden mit den $adverb Drachenkills";

    $headers = array("Kills", "Level", "&nbsp;", "Tage", "&nbsp;", "Bestzeit");

    $none = "Es gibt noch keine Helden in diesem Land";

    display_table($title, $sql, $none, false, $headers, false);

}



page_footer();

?>

