
<? 
// Galerie der Schande
// based upon:
//   New Hall of Fame features by anpera
//   http://www.anpera.net/forum/viewforum.php?f=27
//   with modifications from centralserver for 0.9.8; re-imported to 0.9.7
// with modifications by gargamel @ www.rabenthal.de

require_once "common.php"; 

page_header("Galerie der Schande");
checkday(); 

$op = "burglary";
if ($_GET['op']) $op = $_GET['op']; 
$subop = "shame";
if ($_GET['subop']) $subop = $_GET['subop']; 

$sql = "SELECT count(shame.acctid) AS c FROM accounts, shame WHERE shame.acctid=accounts.acctid AND burglary > 0";
$sql2 = "SELECT count(shame.acctid) AS c FROM accounts, shame WHERE shame.acctid=accounts.acctid AND ( stolengold > 0 OR stolengems > 0 )";
//if ($op == "kills") {
//    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0";
//} elseif ($op == "days") {
//    $sql = "SELECT count(acctid) AS c FROM accounts WHERE locked=0 AND dragonkills>0 AND bestdragonage>0";
//}

if ($_GET[op]=="burglary") {
    $result = db_query($sql);
    $row = db_fetch_assoc($result);
    $totalplayers = $row['c'];
    $playersperpage = 20;
} else {
    $result = db_query($sql2);
    $row = db_fetch_assoc($result);
    $totalplayers = $row['c'];
    $playersperpage = 20;
}

$page = 1; 
if ($_GET['page']) $page = (int)$_GET['page']; 
$pageoffset = $page; 
if ($pageoffset > 0) $pageoffset--; 
$pageoffset *= $playersperpage; 
$from = $pageoffset+1; 
$to = min($pageoffset+$playersperpage, $totalplayers); 
$limit = "$pageoffset,$playersperpage"; 

if ($_GET[op]==""){
    output("`nAngewiedert schaust Du Dich in der Galerie der Schande um. In diesem
    etwas heruntergekommenen Raum wirst Du über die Schandtaten informiert, die sich
    die Bewohner von Düsterstein bereits geleistet haben.`n
    `9Schnell merkst Du, dass dies hier wirklich keine Bestenliste ist!`0");
}

addnav("Schandregister");
addnav("angeprangerte Einbrüche", "shamelist.php?op=burglary&subop=$subop&page=1");
addnav("Diebeskarrieren", "shamelist.php?op=career&subop=$subop&page=1");
addnav("Gold-Diebstahl", "shamelist.php?op=biggold&subop=$subop&page=1");
addnav("Edelstein-Klau", "shamelist.php?op=biggem&subop=$subop&page=1");

if ($_GET[op]=="career") {
    addnav("Sortieren nach");
    addnav("Gold", "shamelist.php?op=$op&subop=gold&page=$page");
    addnav("Edelsteine", "shamelist.php?op=$op&subop=gem&page=$page");
}
if ($_GET[op]!="") {
    addnav("Seiten");
    for($i = 0; $i < $totalplayers; $i+= $playersperpage) {
        $pnum = ($i/$playersperpage+1);
        $min = ($i+1);
        $max = min($i+$playersperpage,$totalplayers);
        addnav("Seite $pnum ($min-$max)", "shamelist.php?op=$op&subop=$subop&page=$pnum");
    }
}
addnav("Sonstiges");
if ($session[user][alive]){
    addnav("Zurück zur Wartehalle","dorfamt.php");
    //addnav("Zurück zum Dorf","village.php");
}else{ 
    addnav("Zurück zu den Schatten","shades.php"); 
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
        echo $size; 
        if ($none === false) $none = "Keine Spieler gefunden"; 
        output('<tr class="trlight"><td colspan="'. $size .'" align="center">`&' . $none .'`0</td></tr>',true); 
    } else { 
        for ($i=0;$i<db_num_rows($result);$i++){ 
            $row = db_fetch_assoc($result); 
            if ($row[name]==$session[user][name]){ 
                //output("<tr class='hilight'>",true); 
                output("<tr bgcolor='#005500'>",true); 
            } else { 
                output('<tr class="'.($i%2?"trlight":"trdark").'">',true); 
            } 
            output("<td>".($i+$from).".</td><td>`&{$row[name]}`0</td>",true); 
            if ($data_header !== false) { 
                for ($j = 0; $j < count($data_header); $j++) { 
                    $id = "data" . ($j+1); 
                    $val = $row[$id]; 
                    if ($tag !== false) $val = $val . " " . $tag[$j]; 
                    if ($tag == "lastjailtime") {
                        $val = substr($val,5,3).". Tag ".substr($val,0,4);
                    }
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
if ($_GET[subop] == "honor") $order = "ASC";

if ($_GET[op]=="burglary"){
    $sql = "SELECT name, burglary AS data1 FROM accounts, shame WHERE shame.acctid=accounts.acctid
    AND burglary > 0 ORDER BY burglary $order LIMIT $limit";
    $adverb = "meist-geprangerten";
    if ($_GET[subop] == "honor") $adverb = "wenig-geprangerten";
    $title = "Die $adverb Einbrecher in diesem Land";
    $foot = "Seit dem 259. Tag des Jahres 1002";
    $headers = array("angeprangerte Einbrüche");
    display_table($title, $sql, false, $foot, $headers, false);
}
if ($_GET[op]=="career"){
    $sort = $_GET[subop];
    if ( $sort == 'gem' ) {
        $sql = "SELECT name, stolengold AS data1, stolengems AS data2 FROM accounts, shame WHERE shame.acctid=accounts.acctid
        AND ( stolengold > 0 OR stolengems > 0 ) ORDER BY stolengems $order, stolengold $order LIMIT $limit";
    }
    else {
        $sql = "SELECT name, stolengold AS data1, stolengems AS data2 FROM accounts, shame WHERE shame.acctid=accounts.acctid
        AND ( stolengold > 0 OR stolengems > 0 ) ORDER BY stolengold $order, stolengems $order LIMIT $limit";
    }
    
    $adverb = "schlimmsten";
    if ($_GET[subop] == "honor") $adverb = "kleinsten";
    $title = "Die $adverb Verbrecherkarrieren in diesem Land";
    $foot = "Seit dem 361. Tag des Jahres 1002";
    $headers = array("Gold-Diebstahl","Edelsteinklau");
    //$tags = "lastjailtime";
    display_table($title, $sql, false, $foot, $headers, false);
}
if ($_GET[op]=="biggold"){
    $sql = "SELECT name, maxstolengold AS data1 FROM accounts, shame WHERE shame.acctid=accounts.acctid
    AND ( maxstolengold > 0  ) ORDER BY maxstolengold $order, maxstolengems $order LIMIT $limit";
    $adverb = "schlimmsten";
    if ($_GET[subop] == "honor") $adverb = "kleinsten";
    $title = "Die $adverb Beutezüge in diesem Land";
    $foot = "Seit dem 361. Tag des Jahres 1002";
    $headers = array("erbeutetes Gold");
    //$tags = "lastjailtime";
    display_table($title, $sql, false, $foot, $headers, false);
}
if ($_GET[op]=="biggem"){
    $sql = "SELECT name, maxstolengems AS data1 FROM accounts, shame WHERE shame.acctid=accounts.acctid
    AND ( maxstolengems > 0 ) ORDER BY maxstolengems $order, maxstolengold $order LIMIT $limit";
    $adverb = "schlimmsten";
    if ($_GET[subop] == "honor") $adverb = "kleinsten";
    $title = "Die $adverb Beutezüge in diesem Land";
    $foot = "Seit dem 361. Tag des Jahres 1002";
    $headers = array("geklaute Edelsteine");
    //$tags = "lastjailtime";
    display_table($title, $sql, false, $foot, $headers, false);
}

page_footer();
?> 

