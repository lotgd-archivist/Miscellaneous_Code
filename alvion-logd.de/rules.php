
<?// Make By:      DarkAngel// Email:        webmaster@esport-network.de// Website:      http://lotgd.esport-network.derequire_once "common.php";$author = "Copyright <a href='http://lotgd.esport-network.de' target='_blank'>`&Göttervater `^DarkAngel</a>";$rulesperpage=6;$sql = "SELECT count(id) AS c FROM rules";$result = db_query($sql);$row = db_fetch_assoc($result);$totalrules = $row['c'];if ($_GET['op']=="search"){    $search="%";    for ($x=0;$x<strlen($_POST['name']);$x++){        $search .= substr($_POST['name'],$x,1)."%";    }    $search=" AND name LIKE '".addslashes($search)."' ";}else{    $pageoffset = (int)$_GET['page'];    if ($pageoffset>0) $pageoffset--;    $pageoffset*=$rulesperpage;    $from = $pageoffset+1;    $to = min($pageoffset+$rulesperpage,$totalrules);    $limit=" LIMIT $pageoffset,$rulesperpage ";}addnav("Seiten");for ($i=0;$i<$totalrules;$i+=$rulesperpage){    addnav("Seite ".($i/$rulesperpage+1)." (".($i+1)."-".min($i+$rulesperpage,$totalrules).")","rules.php?page=".($i/$rulesperpage+1));}page_header("Alvion - Regeln und Gesetze");output("`Q`b`cAlvion - Regeln und Gesetze`c`b`0`n`n");if (!isset($session)) exit();if ($_GET[op]==""){    output("`2Du stehst vor einer riesigen Steintafel mit der Überschrift - `qGesetze und Regeln dieser Welt !!!! `2- `ndie den Stadtbewohnern von den Göttern dieser Welt geschenkt wurde. Hochachtungsvoll liest du dir Punkt für Punkt durch:");        output("`c`n<table width=700 border=0 cellpadding=2 cellspacing=1 bgcolor='#999999'>",true);        output("<tr class='trhead'><td width=28%><b>Paragraph / Regelstichwort</b></td><td><b>Regelbeschreibung</b></td>",true);        $sql = "SELECT * FROM rules ORDER BY para ASC $limit";        $result = db_query($sql);        if (db_num_rows($result)==0){ output("<tr><td colspan=4 align='center'>`&`i`cEs sind keine Einträge vorhanden`c`i`0</td></tr>",true);}        for ($i=0;$i<db_num_rows($result);$i++){        $row = db_fetch_assoc($result);        output("<tr class='".($i%2?"trdark":"trlight")."'><td>",true);        output("`^§$row[para] |");        output("`3 $row[ueberschrift]`n");        output("</td><td>",true);        output("`q $row[beschreibung]`n");        output("</td>",true);        }        output("</table>`c",true);        output("`n$author",true);    addnav("Aktualisieren","rules.php");if ($session['user']['jailtime'] > 0){    addnav("Zurück");    addnav("Z?Zum Pranger","jail.php");}elseif ($session[user][alive]==1){    addnav("Zur Stadt");    addnav("Z?Zurück zur Stadt","village.php");}elseif ($session[user][alive]==0){    addnav("Schattenreich");    addnav("Zu den Schatten","shades.php");}}page_footer();?>
