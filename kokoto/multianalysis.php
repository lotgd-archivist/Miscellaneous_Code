<? 
require_once "common.php"; 
page_header("Schlumpf-Tool"); 
isnewday(4);


addnav("Aktualisieren","multianalysis.php"); 
addnav("Grotte","superuser.php"); 
addnav("Zurück ins Dorf","village.php"); 

$ip = $idlist = $users = array(); 
$sql = 'SELECT uniqueid FROM accounts WHERE uniqueid!="" GROUP BY uniqueid HAVING COUNT(*) > 1'; 
$result = db_query($sql); 
while ($row = db_fetch_assoc($result)) { 
$idlist[] = $row['uniqueid']; 
}

output("`n`bCheat-Schlümpfe`b`n`n`0"); 

foreach ($idlist AS $id) {
$sql = "SELECT acctid,name,lastip,uniqueid,dragonkills,level,laston FROM accounts 
WHERE uniqueid = '$id' AND locked='0' 
ORDER BY dragonkills ASC, level ASC"; 
$result = db_query($sql); 
while ($row = db_fetch_assoc($result)) { 
$userlist[] = $row; 
} 
$usergroup[] = $userlist; 
unset($userlist); 
} 

foreach ($usergroup AS $group) { 
$in_acctid = ''; 
$i = count($group); 
$cookie = $group[0]['uniqueid']; 
output("`n`n`Q$i Characters mit Cookie: $cookie`0`n"); 
rawoutput("<table border=0 cellpadding=2 cellspacing=1 ><tr class='input'><td>Spieler/in</td><td>letzte IP</td><td>Drachen</td><td>Level</td><td>Last On</td>"); 
foreach ($group AS $member) { 
rawoutput("<tr class='trmain'>"); 
output("<td>".$member['name']."</td><td>".$member['lastip']."</td><td align='center'>" 
.$member['dragonkills']."</td><td align='center'>".$member['level']."</td>",true); 
$in_acctid .= ','.$member['acctid']; 
$laston=round((strtotime("0 days")strtotime($member['laston']))  86400,0)." Tage"; 
if (substr_c($laston,0,2)=="1 ") $laston="1 Tag"; 
if (date("Y-m-d",strtotime($member['laston'])) == date("Y-m-d")) $laston="Heute"; 
if (date("Y-m-d",strtotime($member['laston'])) == date("Y-m-d",strtotime("-1 day"))) $laston="Gestern"; 
if ($loggedin) $laston="Jetzt"; 
rawoutput("<td>".$laston."</td>"); 

rawoutput('</tr>'); 
} 
rawoutput('</table>'); 
// -> debuglog 
$sql = 'SELECT debuglog.*,date,actor,target,a1.name as actorname,a2.name as targetname FROM debuglog LEFT JOIN accounts as a1 ON a1.acctid=debuglog.actor LEFT JOIN accounts as a2 ON a2.acctid=debuglog.target WHERE actor IN (-1'.$in_acctid.') AND target IN (-1'.$in_acctid.') AND date > "'.date("Y-m-d H:i:s",strtotime($abdatum)).'" ORDER BY date DESC, actor ASC'; 
$result = db_query($sql); 
    if (db_num_rows($result) == 0) { 
        output("`^Debuglog:`0`n Keine Interaktion. `@Guuuuuut.`0`n"); 
    }else{ 
        output("`^Debuglog:`0`n Die folgenden Interaktionen wurden gefunden:`n"); 
            while ($row = db_fetch_assoc($result)) { 
                output("`$ Log:`0 ".$row['date']." - ".$row['actorname']." ".$row['message']." ".$row['targetname']."`n" ); 
            } 
    }  
// -> houses Eigentümer 
$comarray = array(); 
$vergehen=0; 
$owner=0; 
$sql = 'SELECT houseid,owner,status,housename,a1.name as hausholder 
FROM houses 
LEFT JOIN accounts as a1 ON a1.acctid=houses.owner 
WHERE owner IN (-1'.$in_acctid.') 
ORDER BY owner ASC'; 
$result = db_query($sql); 
if (db_num_rows($result) == 0) { 
output("`^Häuser:`0 Keine Hauseingentümer!`0`n"); 
} 
else { 
$i = 1; 
output("`^Häuser:`0`n"); 
while ($row = db_fetch_assoc($result)) { 
$sql2 = 'SELECT houses.houseid, houses.status, a1.name as hausholder, a2.name as keyholder, 
items.owner as keyowner 
FROM houses, items 
LEFT JOIN accounts as a1 ON a1.acctid=houses.owner 
LEFT JOIN accounts as a2 ON a2.acctid=items.owner 
WHERE houses.owner = '.$row['owner'].' 
AND items.value1 = houses.houseid 
AND items.owner IN (-1'.$in_acctid.') 
AND items.owner != '.$row['owner'].' 
ORDER BY keyholder ASC'; 
$result2 = db_query($sql2); 
if (db_num_rows($result2) == 0) { 
output("Hauseingentümer ".$row['hausholder']." `@ohne Schlüssel-Vergehen!`0`n"); 
} 
else { 
while ($row2 = db_fetch_assoc($result2)) { 
output("`$ Vergehen $i:`0 Hausbesitzer ist ".$row2['hausholder'].", 
Schüssel hat ".$row2['keyholder']." `n"); 

$row2['status']= $i; 
$comarray[] = $row2; 
$vergehen++; 

if ( $owner != $row['owner'] ) { 
$row2['keyowner']=$row['owner']; 
$comarray[] = $row2; 
$owner=$row['owner']; 
} 
// 
$i++; 
} 
} 
} 
} 
// -> Houses Eigentümer commentary 
$flag = 0; 
if ( $vergehen > 0 ) { 
$i=1; 
$hoch = 0; 
foreach ($comarray AS $com) { 
if ( $flag == 0 ) { 
output("`6Comment Summary:`0`n"); 
$flag++; 
} 
$i = $com['status']; 

$section = "house-".$com['houseid']; 
$author = $com['keyowner']; 
$hausnr = $com['houseid']; 

$sql = 'SELECT *, a1.name as actorname 
FROM commentary LEFT JOIN accounts as a1 ON a1.acctid=commentary.author 
WHERE author ='.$author.' 
AND section ="'.$section.'" 
ORDER BY commentid ASC'; 
$result = db_query($sql); 
if (db_num_rows($result) == 0) { 
output("`6($i)`0 Keine Einträge`n"); 
} 
else { 
$dg = $ng = $de = $ne = 0; 
while ($row = db_fetch_assoc($result)) { 
$text = $row['comment']; 
$wer = $row['actorname']; 
$text2 = str_replace_c("/me ", " ", $row['comment']); 


if ( !strpos_c( $text, "Edelsteine") ) { 
if ( !strpos_c( $text, "nimmt") ) { 
$tmp = str_replace_c("/me `@deponiert `^", "", $text); 
$tmp = str_replace_c("`@ Gold.", "", $tmp); 
$dg += $tmp; 
} 
else { 
$tmp = str_replace_c("/me `\$nimmt `^", "", $text); 
$tmp = str_replace_c("`$ Gold.", "", $tmp); 
$ng += $tmp; 
} 
} 
else { 
if ( !strpos_c( $text, "nimmt") ) { 
$tmp = str_replace_c("/me `@deponiert `#", "", $text); 
$tmp = str_replace_c("`@ Edelsteine.", "", $tmp); 
$de += $tmp; 
} 
else { 
$tmp = str_replace_c("/me `\$nimmt `#", "", $text); 
$tmp = str_replace_c("`$ Edelsteine.", "", $tmp); 
$ne += $tmp; 
} 
} 
} 
output("`0`6($i) Haus $hausnr:`0 $wer `$ deponiert $dg Gold und $de Gems, 
nimmt $ng Gold und $ne Gems.`0`n" ); 
} 
if ( $hoch == 0 ) { 
$hoch = 1; 
} else { 
$i++; 
$hoch = 0; 
} 
} 
} 
unset($comarray); 


// -> houses Bewohner 
$comarray = array(); 
$id_alt=0; 
$sql = 'SELECT items.value1, items.owner AS keyowner, houses.owner AS houseowner 
FROM items 
LEFT JOIN houses ON houses.houseid=items.value1 
WHERE items.owner!=houses.owner AND 
items.class="Schlüssel" AND 
items.owner IN (-1'.$in_acctid.') 
ORDER BY houses.owner ASC'; 
$result = db_query($sql); 
if (db_num_rows($result) == 0) { 
output("`^Schlüssel:`0 Kein Schlüsselbesitz!`0`n"); 
} 
else { 
$i = 1; 
output("`^Schlüssel:`0`n"); 
while ($row = db_fetch_assoc($result)) { 
if ( $id_alt != $row['houseowner'] ) { 
$id_alt = $row['houseowner']; 
$sql2 = 'SELECT items.value1, items.owner AS keyowner, houses.owner AS houseowner, 
a1.name as hausholder, a2.name as keyholder 
FROM items 
LEFT JOIN houses ON houses.houseid=items.value1 
LEFT JOIN accounts as a1 ON a1.acctid=houses.owner 
LEFT JOIN accounts as a2 ON a2.acctid=items.owner 
WHERE items.owner!=houses.owner AND 
items.class="Schlüssel" AND 
items.owner IN (-1'.$in_acctid.') 
and houses.owner = '.$id_alt.' 
ORDER BY houses.owner ASC'; 
$result2 = db_query($sql2); 
if (db_num_rows($result2) == 0) { 
output("Hauseingentümer ".$row['hausholder']." `@ohne Schlüssel-Vergehen!`0`n"); 
} 
else if (db_num_rows($result2) == 1) { 
$row2 = db_fetch_assoc($result2); 
output("Hausbesitzer Nr. ".$row2['value1']." ist ".$row2['hausholder'].", 
Schüssel hat ".$row2['keyholder']." `@Ok.`0`n"); 
} 
else { 
while ($row2 = db_fetch_assoc($result2)) { 
output("`$ Vergehen $i:`0 Hausbesitzer Nr. ".$row2['value1']." ist ".$row2['hausholder'].", 
Schüssel hat ".$row2['keyholder']." `n"); 
$comarray[] = $row2; 
$i++; 
} 
} 
} 
} 
} 

// -> Houses Bewohner commentary 
$flag = 0; 
$i = 1; 
foreach ($comarray AS $com) { 
if ( $flag == 0 ) { 
output("`6Comment Summary:`0`n"); 
$flag++; 
} 

$section = "house-".$com['value1']; 
$author = $com['keyowner']; 
$hausnr = $com['value1']; 

$sql = 'SELECT *, a1.name as actorname 
FROM commentary LEFT JOIN accounts as a1 ON a1.acctid=commentary.author 
WHERE author ='.$author.' 
AND section ="'.$section.'" 
ORDER BY commentid ASC'; 
$result = db_query($sql); 
if (db_num_rows($result) == 0) { 
output("`6($i)`0 Keine Einträge`n"); 
} 
else { 
$dg = $ng = $de = $ne = 0; 
while ($row = db_fetch_assoc($result)) { 
$text = $row['comment']; 
$wer = $row['actorname']; 
$text2 = str_replace_c("/me ", " ", $row['comment']); 
//output($row['actorname']."`0 ".$text2."`0`n" ); 

if ( !strpos_c( $text, "Edelsteine") ) { 
if ( !strpos_c( $text, "nimmt") ) { 
$tmp = str_replace_c("/me `@deponiert `^", "", $text); 
$tmp = str_replace_c("`@ Gold.", "", $tmp); 
$dg += $tmp; 
} 
else { 
$tmp = str_replace_c("/me `\$nimmt `^", "", $text); 
$tmp = str_replace_c("`$ Gold.", "", $tmp); 
$ng += $tmp; 
} 
} 
else { 
if ( !strpos_c( $text, "nimmt") ) { 
$tmp = str_replace_c("/me `@deponiert `#", "", $text); 
$tmp = str_replace_c("`@ Edelsteine.", "", $tmp); 
$de += $tmp; 
} 
else { 
$tmp = str_replace_c("/me `\$nimmt `#", "", $text); 
$tmp = str_replace_c("`$ Edelsteine.", "", $tmp); 
$ne += $tmp; 
} 
} 
} 
output("`0`6($i) Haus $hausnr:`0 $wer `$ deponiert $dg Gold und $de Gems, 
nimmt $ng Gold und $ne Gems.`0`n" ); 
} 
$i++; 
} 
unset($comarray); 

output("`n`n"); 
} 
$session['user']['standort'] = "Geheime Grotte";
page_footer(); 
?> 