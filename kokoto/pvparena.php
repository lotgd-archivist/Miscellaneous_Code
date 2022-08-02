<?php

// 24072004

/*****************************************************
PvP-Arena by anpera 2004

For "Legend of the Green Dragon" 0.9.7 by Eric Stevens

This gives players the possibility to fight against other players with all
their specialties, mounts and buffs. The first non-automated PvP
system for LoGD that comes pretty close to real online PvP. :)

(Quite rough by now but Im still working on it.)

Fully compatible with lonnyl's battle-arena and battlepoint system!

Needs modifications on several files and in database!
SEE INSTALLATION INSTRUCTIONS AT:
http://www.anpera.net/forum/viewtopic.php?t=369

Modified with an idea by LordRaven:
Superuser is able to end fights.

ToDo:
- systemmail on interrupted battles
- bet system (place bets on winner as long as both players have
  at least half of their maxhitpoints
*****************************************************/

require_once "common.php";
addcommentary();
global $session;   
page_header("Kampfarena von Kokoto");

function stats($row){  // Shows player stats
global $session;
output("<table align='center' border='0' cellpadding='2' cellspacing='0' class='vitalinfo'><tr><td class='charhead' colspan='4'>`^`bVital Info`b für diesen Kampf</td><tr><td class='charinfo'>`&Level: `^`b",true);
if ($row['bufflist1']) $row['bufflist1']=unserialize($row['bufflist1']);
if ($row['bufflist2']) $row['bufflist2']=unserialize($row['bufflist2']);
if ($row['bufflist1']) reset($row['bufflist1']);
if ($row['bufflist2']) reset($row['bufflist2']);
if ($row['acctid1']==$session['user']['acctid']){
$atk=$row['att1'];
$def=$row['def1'];
foreach($row['bufflist1'] as $key => $val){
$buffs.=appoencode("`#".$val['name']." `7(".$val['rounds']." Runden übrig)`n",true);
if (isset($val['atkmod'])) $atk *= $val['atkmod'];
if (isset($val['defmod'])) $def *= $val['defmod'];
}
if ($row['bufflist2']){
foreach($row['bufflist2'] as $key => $val){

if (isset($val['badguyatkmod'])) $atk *= $val['badguyatkmod'];
if (isset($val['badguydefmod'])) $def *= $val['badguydefmod'];
}
}
$atk = round($atk, 2);
$def = round($def, 2);
$atk = ($atk == $row['att1'] ? "`^" : ($atk > $row['att1'] ? "`@" : "`$")) . "`b$atk`b`0";
$def = ($def == $row['def1'] ? "`^" : ($def > $row['def1'] ? "`@" : "`$")) . "`b$def`b`0";
if (count($row['bufflist1'])==0){
$buffs.=appoencode("`^Keine`0",true);
}
output($row['lvl1']."`b</td><td class='charinfo'>`&Lebenspunkte: `^".$row['hp1']."/`0".$row['maxhp1']."</td><td class='charinfo'>`&Angriff: `^`b$atk`b</td><td class='charinfo'>`&Verteidigung: `^`b$def`b</td>",true);
output("</tr><tr><td class='charinfo' colspan='2'>`&Waffe: `^".$row['weapon1']."</td><td class='charinfo' colspan='2'>`&Rüstung: `^".$row['armor1']."</td>",true);
output("</tr><tr><td colspan='4' class='charinfo'>`&Aktionen:`n$buffs</td>",true);
}
if ($row['acctid2']==$session['user']['acctid']){
$atk=$row['att2'];
$def=$row['def2'];
foreach($row['bufflist2'] as $key => $val){
$buffs.=appoencode("`#".$val['name']." `7(".$val['rounds']." Runden übrig)`n",true);
if (isset($val['atkmod'])) $atk *= $val['atkmod'];
if (isset($val['defmod'])) $def *= $val['defmod'];
}
if ($row['bufflist1']){
foreach($row['bufflist1'] as $key => $val){
if (isset($val['badguyatkmod'])) $atk *= $val['badguyatkmod'];
if (isset($val['badguydefmod'])) $def *= $val['badguydefmod'];
}
}
$atk = round($atk, 2);
$def = round($def, 2);
$atk = ($atk == $row['att2'] ? "`^" : ($atk > $row['att2'] ? "`@" : "`$")) . "`b$atk`b`0";
$def = ($def == $row['def2'] ? "`^" : ($def > $row['def2'] ? "`@" : "`$")) . "`b$def`b`0";
if (count($row['bufflist2'])==0){
$buffs.=appoencode("`^Keine`0",true);
}
output($row['lvl2']."`b</td><td class='charinfo'>`&Lebenspunkte: `^".$row['hp2']."/`0".$row['maxhp2']."</td><td class='charinfo'>`&Angriff: `^`b$atk`b</td><td class='charinfo'>`&Verteidigung: `^`b$def`b</td> </tr><tr><td class='charinfo' colspan='2'>`&Waffe: `^".$row['weapon2']."</td><td class='charinfo' colspan='2'>`&Rüstung: `^".$row['armor2']."</td> </tr><tr><td class='charinfo' colspan='4'>`&Aktionen:`n$buffs</td>",true);
}
output("</tr></table>`n",true);
if ($row['bufflist1']) $row['bufflist1']=serialize($row['bufflist1']);
if ($row['bufflist2']) $row['bufflist2']=serialize($row['bufflist2']);
}
function arenanav($row){ // Navigation during fight
if ($row['turn']==1){
 $badguy = array("acctid"=>$row['acctid2'],"name"=>$row['name2'],"level"=>$row['lvl2'],"hitpoints"=>$row['hp2'],"attack"=>$row['att2'],"defense"=>$row['def2'],"weapon"=>$row['weapon2'],"armor"=>$row['armor2'],"bufflist"=>$row['bufflist2']);
 $goodguy = array("name"=>$row['name1'],"level"=>$row['lvl1'],"hitpoints"=>$row['hp1'],"maxhitpoints"=>$row['maxhp1'],"attack"=>$row['att1'],"defense"=>$row['def1'],"weapon"=>$row['weapon1'],"armor"=>$row['armor1'],"darkartuses"=>$row['darkartuses1'],"magicuses"=>$row['magicuses1'],"thieveryuses"=>$row['thieveryuses1'],"bufflist"=>$row['bufflist1']);
}
if ($row['turn']==2){
 $badguy = array("acctid"=>$row['acctid1'],"name"=>$row['name1'],"level"=>$row['lvl1'],"hitpoints"=>$row['hp1'],"attack"=>$row['att1'],"defense"=>$row['def1'],"weapon"=>$row['weapon1'],"armor"=>$row['armor1'],"bufflist"=>$row['bufflist1']);
 $goodguy = array("name"=>$row['name2'],"level"=>$row['lvl2'],"hitpoints"=>$row['hp2'],"maxhitpoints"=>$row['maxhp2'],"attack"=>$row['att2'],"defense"=>$row['def2'],"weapon"=>$row['weapon2'],"armor"=>$row['armor2'],"darkartuses"=>$row['darkartuses2'],"magicuses"=>$row['magicuses2'],"thieveryuses"=>$row['thieveryuses2'],"bufflist"=>$row['bufflist2']);
}
if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0) {
output ("`\$`c`b~ ~ ~ Kampf ~ ~ ~`b`c`0 `n`@Du hast den Gegner `^".$badguy['name']."`@ entdeckt, der sich mit seiner Waffe `%".$badguy['weapon']."`@");
// Let's display what buffs the opponent is using - oh yeah
$buffs='';
$disp['bufflist']=unserialize($badguy['bufflist']);
reset($disp['bufflist']);
foreach($disp['bufflist'] as $key => $val){
$buffs.=" `@und `#".$val['name']." `7(".$val['rounds']." Runden)";
}
if (count($disp['bufflist'])==0){
$buffs.=appoencode("",true);
}
output("$buffs");
output(" `@auf dich stürzt!`0`n`n`2Level: `6".$badguy['level']."`0`n`2`bErgebnis der letzten Runde:`b`n`2".$badguy['name']."`2's Lebenspunkte: `6".$badguy['hitpoints']."`0`n".$row['lastmsg']);
}
addnav('Kampf');
addnav('Kämpfen','pvparena.php?op=fight&act=fight');
addnav('`bBesondere Fähigkeiten`b');
if ($goodguy['darkartuses']>0) {
addnav("`\$Dunkle Künste`0", "");
addnav("`\$&#149; Skelette herbeirufen`7 (1/".$goodguy['darkartuses'].")`0","pvparena.php?op=fight&skill=DA&l=1",true);
}
if ($goodguy['darkartuses']>1)
addnav("`\$&#149; Voodoo`7 (2/".$goodguy['darkartuses'].")`0","pvparena.php?op=fight&skill=DA&l=2",true);
if ($goodguy['darkartuses']>2)
addnav("`\$&#149; Geist verfluchen`7 (3/".$goodguy['darkartuses'].")`0","pvparena.php?op=fight&skill=DA&l=3",true);
if ($goodguy['darkartuses']>4)
addnav("`\$&#149; Seele verdorren`7 (5/".$goodguy['darkartuses'].")`0","pvparena.php?op=fight&skill=DA&l=5",true);
if ($goodguy['thieveryuses']>0) {
addnav("`^Diebeskünste`0","");
addnav("`^&#149; Beleidigen`7 (1/".$goodguy['thieveryuses'].")`0","pvparena.php?op=fight&skill=TS&l=1",true);
}
if ($goodguy['thieveryuses']>1)
addnav("`^&#149; Waffe vergiften`7 (2/".$goodguy['thieveryuses'].")`0","pvparena.php?op=fight&skill=TS&l=2",true);
if ($goodguy['thieveryuses']>2)
addnav("`^&#149; Versteckter Angriff`7 (3/".$goodguy['thieveryuses'].")`0","pvparena.php?op=fight&skill=TS&l=3",true);
if ($goodguy['thieveryuses']>4)
addnav("`^&#149; Angriff von hinten`7 (5/".$goodguy['thieveryuses'].")`0","pvparena.php?op=fight&skill=TS&l=5",true);
if ($goodguy['magicuses']>0) {
addnav("`%Mystische Kräfte`0","");
//disagree with making this 'n', players shouldn't have their behavior dictated by convenience of god mode, hehe
addnav("g?`%&#149; Regeneration`7 (1/".$goodguy['magicuses'].")`0","pvparena.php?op=fight&skill=MP&l=1",true);
}
if ($goodguy['magicuses']>1)
addnav("`%&#149; Erdenfaust`7 (2/".$goodguy['magicuses'].")`0","pvparena.php?op=fight&skill=MP&l=2",true);
if ($goodguy['magicuses']>2)
addnav("L?`%&#149; Leben absaugen`7 (3/".$goodguy['magicuses'].")`0","pvparena.php?op=fight&skill=MP&l=3",true);
if ($goodguy['magicuses']>4)
addnav("A?`%&#149; Blitz Aura`7 (5/".$goodguy['magicuses'].")`0","pvparena.php?op=fight&skill=MP&l=5",true);
// spells
if ($row['turn']==1) $sql="SELECT * FROM items WHERE class='Zauber' AND owner=".$row['acctid1']." AND value1>0 ORDER BY name ASC";
if ($row['turn']==2) $sql="SELECT * FROM items WHERE class='Zauber' AND owner=".$row['acctid2']." AND value1>0 ORDER BY name ASC";
$resultz=db_query($sql);
if (db_num_rows($resultz)>0) addnav("Zauber");
for ($i=0;$i<db_num_rows($resultz);$i++){
$rowz = db_fetch_assoc($resultz);
$spellbuff=unserialize($rowz['buff']);
addnav($spellbuff['name']." (".$rowz['value1']."x)","pvparena.php?op=fight&skill=zauber&itemid=".$rowz['id']);
}
// end spells
}
function activate_buffs($tag) { // activate buffs (from battle.php with modifications for multiplayer battle)
global $goodguy,$badguy,$message;
reset($goodguy['bufflist']);
reset($badguy['bufflist']);
$result = array();
$result['invulnerable'] = 0; // not in use
$result['dmgmod'] = 1;
$result['badguydmgmod'] = 1; // not in use
$result['atkmod'] = 1;
$result['badguyatkmod'] = 1; // not in use
$result['defmod'] = 1;
$result['badguydefmod'] = 1;
$result['lifetap'] = array();
$result['dmgshield'] = array();
foreach($goodguy['bufflist'] as $key => $buff){
if (isset($buff['startmsg'])) {
$msg = $buff['startmsg'];
$msg = str_replace_c("{badguy}", $badguy['name'], $msg);
output("`%$msg`0");
$message=$message.$goodguy['name'].": \"`i$msg`i\"`n";
unset($goodguy['bufflist'][$key]['startmsg']);
}
$activate = strpos_c($buff['activate'], $tag);
if ($activate !== false) $activate = true; // handle strpos_c == 0;
// If this should activate now and it hasn't already activated,
// do the round message and mark it.
if ($activate && !$buff['used']) {
// mark it used.
$goodguy['bufflist'][$key]['used'] = 1;
// if it has a 'round message', run it.
if (isset($buff['roundmsg'])) {
$msg = $buff['roundmsg'];
$msg = str_replace_c("{badguy}", $badguy['name'], $msg);
output("`)$msg`0`n");
$message=$message.$goodguy['name'].": \"`i$msg`i\"`n";
}
}
// Now, calculate any effects and run them if needed.
if (isset($buff['invulnerable'])) {
$result['invulnerable'] = 1;
}
if (isset($buff['atkmod'])) {
$result['atkmod'] *= $buff['atkmod'];
}
if (isset($buff['badguyatkmod'])) {
$result['badguyatkmod'] *= $buff['badguyatkmod'];
}
if (isset($buff['defmod'])) {
$result['defmod'] *= $buff['defmod'];
}
if (isset($buff['badguydefmod'])) {
$result['badguydefmod'] *= $buff['badguydefmod'];
}
if (isset($buff['dmgmod'])) {
$result['dmgmod'] *= $buff['dmgmod'];
}
if (isset($buff['badguydmgmod'])) {
$result['badguydmgmod'] *= $buff['badguydmgmod'];
}
if (isset($buff['lifetap'])) {
array_push($result['lifetap'], $buff);
}
if (isset($buff['damageshield'])) {
array_push($result['dmgshield'], $buff);
}
if (isset($buff['regen']) && $activate) {
$hptoregen = (int)$buff['regen'];
$hpdiff = $goodguy['maxhitpoints'] 
$goodguy['hitpoints'];
// Don't regen if we are above max hp
if ($hpdiff < 0) $hpdiff = 0;
if ($hpdiff < $hptoregen) $hptoregen = $hpdiff;
$goodguy['hitpoints'] += $hptoregen;
// Now, take abs value just incase this was a damaging buff
$hptoregen = abs($hptoregen);
if ($hptoregen == 0) $msg = $buff['effectnodmgmsg'];
else $msg = $buff['effectmsg'];
$msg = str_replace_c("{badguy}", $badguy['name'], $msg);
$msg = str_replace_c("{damage}", $hptoregen, $msg);
output("`)$msg`0`n");
$message=$message.$goodguy['name'].": \"`i$msg`i\"`n";
}
if (isset($buff['minioncount']) && $activate) {
$who = 1;
if (isset($buff['maxbadguydamage'])) {
if (isset($buff['maxbadguydamage'])) {
$buff['maxbadguydamage'] = stripslashes($buff['maxbadguydamage']);
eval("\$buff['maxbadguydamage'] = $buff[maxbadguydamage];");
}
$max = $buff['maxbadguydamage'];

if (isset($buff['minbadguydamage'])) {
$buff['minbadguydamage'] = stripslashes($buff['minbadguydamage']);
eval("\$buff['minbadguydamage'] = $buff[minbadguydamage];");
}
$min = $buff['minbadguydamage'];

$who = 0;
} else {
$max = $buff['maxgoodguydamage'];
$min = $buff['mingoodguydamage'];
$who = 1;
}
for ($i = 0; $who >= 0 && $i < $buff['minioncount']; $i++) {
$damage = e_rand($min, $max);
if ($who == 0) {
$badguy['hitpoints'] -= $damage;
} else if ($who == 1) {
$goodguy['hitpoints'] -= $damage;
}
if ($damage < 0) {
$msg = $buff['effectfailmsg'];
} else if ($damage == 0) {
$msg = $buff['effectnodmgmsg'];
} else if ($damage > 0) {
$msg = $buff['effectmsg'];
}
if ($msg>"") {
$msg = str_replace_c("{badguy}", $badguy['name'], $msg);
$msg = str_replace_c("{goodguy}", $session['user']['name'], $msg);
$msg = str_replace_c("{damage}", $damage, $msg);
output("`)$msg`0`n");
$message=$message.$goodguy['name'].": \"`i$msg`i\"`n";
}
}
}
}

foreach($badguy['bufflist'] as $key => $buff){ // check badguy buffs
$activate = strpos_c($buff['activate'], $tag);
if ($activate !== false) $activate = true;
if ($activate && !$buff['used']) {
$badguy['bufflist'][$key]['used'] = 1;
}
if (isset($buff['atkmod'])) {
$result['badguyatkmod'] *= $buff['atkmod'];
}
if (isset($buff['defmod'])) {
$result['badguydefmod'] *= $buff['defmod'];
}
if (isset($buff['badguyatkmod'])) {
$result['atkmod'] *= $buff['badguyatkmod'];
}
if (isset($buff['badguydefmod'])) {
$result['defmod'] *= $buff['badguydefmod'];
}
if (isset($buff['badguydmgmod'])) {
$result['dmgmod'] *= $buff['badguydmgmod'];
}
}
return $result;
}
function process_lifetaps($ltaps, $damage) {
global $goodguy,$badguy,$message;
reset($ltaps);
foreach($ltaps as $key => $buff){
$healhp = $goodguy['maxhitpoints']  $goodguy['hitpoints'];
if ($healhp < 0) $healhp = 0;
if ($healhp == 0) {
$msg = $buff['effectnodmgmsg'];
} else {
if ($healhp > $damage  $buff['lifetap'])
$healhp = $damage  $buff['lifetap'];
if ($healhp < 0) $healhp = 0;
if ($damage > 0) {
$msg = $buff['effectmsg'];
} else if ($damage == 0) {
$msg = $buff['effectfailmsg'];
} else if ($damage < 0) {
$msg = $buff['effectfailmsg'];
}
}
$goodguy['hitpoints'] += $healhp;
$msg = str_replace_c("{badguy}",$badguy['name'], $msg);
$msg = str_replace_c("{damage}",$healhp, $msg);
if ($msg > ""){
output("`)$msg`n");
$message=$message.$goodguy['name'].": \"`i$msg`i\"`n";
}
}
}

function process_dmgshield($dshield, $damage) {
global $session,$badguy,$message;
reset($dshield);
foreach($dshield as $key => $buff){
$realdamage = $damage  $buff['damageshield'];
if ($realdamage < 0) $realdamage = 0;
if ($realdamage > 0) {
$msg = $buff['effectmsg'];
} else if ($realdamage == 0) {
$msg = $buff['effectnodmgmsg'];
} else if ($realdamage < 0) {
$msg = $buff['effectfailmsg'];
}
$badguy['hitpoints'] -= $realdamage;
$msg = str_replace_c("{badguy}",$badguy['name'], $msg);
$msg = str_replace_c("{damage}",$realdamage, $msg);
if ($msg > ""){
output("`)$msg`n");
$message=$message.$goodguy['name'].": \"`i$msg`i\"`n";
}
}
}
function expire_buffs() {
global $goodguy,$badguy;
reset($goodguy['bufflist']);
reset($badguy['bufflist']);
foreach($goodguy['bufflist'] as $key => $buff){
if ($buff['used']) {
$goodguy['bufflist'][$key]['used'] = 0;
$goodguy['bufflist'][$key]['rounds']--;
if ($goodguy['bufflist'][$key]['rounds'] <= 0) {
if ($buff['wearoff']) {
$msg = $buff['wearoff'];
$msg = str_replace_c("{badguy}", $badguy['name'], $msg);
output("`)$msg`n");
$message=$message.$goodguy['name'].": \"`i$msg`i\"`n";
}
unset($goodguy['bufflist'][$key]);
}
}
}
}
if ( $session['user']['guildID'] > 0 ) {
populate_guilds();
$MyGuild=$session['guilds'][$session['user']['guildID']];
}
if (isset($MyGuild)) { $guilddisc=$MyGuild['PvPDiscount']100; }else{ $guilddisc=0; }

$cost=round(($session['user']['level']20)(1$guilddisc));

if ($_GET['op']=='challenge'){
if($_GET['name']=='' && $session['user']['playerfights']>0 && ($session['user']['level']>4 || $session['user']['dragonkills']>0)){
if (isset($_POST['search']) || $_GET['search']>''){
if ($_GET['search']>'') $_POST['search']=$_GET['search'];
$search="%";
for ($x=0;$x<strlen_c($_POST['search']);$x++){
$search .= substr_c($_POST['search'],$x,1)."%";
}
$search="name LIKE '".$search."' AND ";
}else{
$search="";
}
$ppp=25; // Players Per Page to display
if (!$_GET['limit']){
$page=0;
}else{
$page=(int)$_GET['limit'];
addnav("Vorherige Seite","pvparena.php?op=challenge&limit=".($page1)."&search=".$_POST['search']);
}
$limit="".($page$ppp).",".($ppp1); // love PHP for this ;)
output("`6Wen willst du zu einem Duell mit allen Fähigkeiten herausfordern? Die Arenagebühr kostet dich `^$cost `6Gold. (Dank deiner Gilde bekommst du `^".($guilddisc100)."`6% Rabatt). `6Dein Gegner wird nicht unvorbereitet zustimmen.`nDu kannst heute noch `4".$session['user']['playerfights']."`6 mal gegen einen anderen Krieger antreten.`n`n");
rawoutput("<form action='pvparena.php?op=challenge' method='POST'>Nach Name suchen: <input name='search' value='".$_POST['search']."'><input type='submit' class='button' value='Suchen'></form>");
allownav("pvparena.php?op=challenge");
  $sql = "SELECT name,acctid,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE 
$search
(locked=0) AND
(level >= ".($session['user']['level']1)." AND level <= ".($session['user']['level']2).") AND 
(acctid <> ".$session['user']['acctid'].")
ORDER BY level DESC LIMIT $limit";
  $result = db_query($sql);
if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
output("`n`&Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!`0`n`n");
}
if (db_num_rows($result)>$ppp) addnav("Nächste Seite","pvparena.php?op=challenge&limit=".($page1)."&search=".$_POST['search']);
output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Status</td><td>Ops</td></tr>",true);
for ($i=0;$i<db_num_rows($result);$i++){
$row = db_fetch_assoc($result);
if ($row['pvpflag']!="5013-10-06 00:42:00"){
if ($session['user']['prefs']['biopop']==1){
$biolink = "biopop.php?id=".$row['acctid']."' target=\"_blank\" onClick=\"".popup("biopop.php?id=".$row['acctid']."").";return false;\" ";
}else{
$biolink="bio.php?id=".$row['acctid']."&ret=".urlencode($_SERVER['REQUEST_URI']);
}
  allownav($biolink);
$loggedin=(date("U")  strtotime($row['laston']) < getsetting("logintimeout",900) && $row['loggedin']);
  output("<tr class='".($i2?"trlight":"trdark")."'><td>".$row['name']."</td><td>".$row['level']."</td><td>".($loggedin?"`#Online`0":"`3Offline`0")."</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvparena.php?op=challenge&name=".rawurlencode($row['login'])."'>Herausfordern</a> ]</td></tr>",true);
allownav("pvparena.php?op=challenge&name=".rawurlencode($row['login']));
}
}
rawoutput('</table>');
  addnav('Zurück zur Arena','pvparena.php');
}else if ($session['user']['playerfights']<=0){
output('`6Du kannst heute keinen weiteren Krieger mehr herausfordern.');
addnav('Zurück zur Arena','pvparena.php');
}else if ($session['user']['level']<=4 && $session['user']['dragonkills']==0){
output('`6Der Arenawärter lacht sich darüber kaputt, dass ein so kleiner Schwächling wie du in der Arena kämpfen will. Vielleicht solltest du wirklich erst etwas mehr Kampferfahrung sammeln.');
addnav('Zurück zur Arena','pvparena.php');

}else{
if ($session['user']['gold']>=$cost){
  $sql = "SELECT acctid,name,level,sex,hitpoints,maxhitpoints,lastip,emailaddress,uniqueid FROM accounts WHERE login='".mysql_real_escape_string($_GET['name'])."'"; 
  $result = db_query($sql);
$row = db_fetch_assoc($result);

if (ac_check($row)){
output("`n`4Du kannst deine eigenen oder derart verwandte Spieler nicht zu einem Duell herausfordern!`0`n`n");
}else{
$sql = "SELECT * FROM pvp WHERE acctid2=".$session['user']['acctid']." OR acctid1=".$row['acctid']." OR acctid2=".$row['acctid']."";
  $result = db_query($sql);
if (db_num_rows($result)){
output("`6Bei dieser Herausforderung ist dir jemand zuvor gekommen!");
}else{
$sql = "INSERT INTO pvp (acctid1,acctid2,name1,name2,lvl1,lvl2,hp1,maxhp1,att1,def1,weapon1,armor1,darkartuses1,magicuses1,thieveryuses1,bufflist1,turn) 
VALUES (".$session['user']['acctid'].",".$row['acctid'].",'".mysql_real_escape_string($session['user']['name'])."','".mysql_real_escape_string($row['name'])."',".$session['user']['level'].",".$row['level'].",".$session['user']['hitpoints'].",".$session['user']['maxhitpoints'].",".$session['user']['attack'].",".$session['user']['defence'].",'".mysql_real_escape_string($session['user']['weapon'])."','".mysql_real_escape_string($session['user']['armor'])."',".$session['user']['darkartuses'].",".$session['user']['magicuses'].",".$session['user']['thieveryuses'].",'".mysql_real_escape_string($session['user']['bufflist'])."',0)";
db_query($sql);
if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
output("`6Du hast`4 ".$row['name']." `6zu einem Duell herausgefordert und wartest nun auf ".($row['sex']?"ihre":"seine")." Antwort. Du könntest ".$row['name']."`6 den Kampf  schmackhafter machen, indem du ".($row['sex']?"ihr":"ihm")." die Arenagebühr von  `^".($row['level']20)."`6 Gold überweist.`n");
if ($session['user']['dragonkills']<2) output("`n`n`i(Du kannst jetzt ganz normal weiterspielen. Wenn ".$row['name']."`6 sich meldet, bekommst du eine Nachricht.)`i");
 systemmail($row['acctid'],"`2Du wurdest herausgefordert!","`2".$session['user']['name']."`2 (Level ".$session['user']['level'].") hat dich zu einem Duell in der Arena herausgefordert. Du kannst diese Herausforderung in der Arena annehmen oder ablehnen, solange sich dein Level nicht ändert.`nBereite dich gut vor und betrete die Arena erst dann!"); 
$session['user']['gold']-=$cost;
$session['user']['reputation']++;
} 
}
  addnav('Zurück zur Arena','pvparena.php');
}else{
output('`4Du hast nicht genug Gold dabei, um die Arenagebühr zu bezahlen. Mit rotem Kopf ziehst du ab.');
addnav('Zurück zur Arena','pvparena.php');
}
}
  addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
        addnav('Arenaställe','arenastables.php');
}else if ($_GET['op']=='deny'){
$sql = "DELETE FROM pvp WHERE acctid2=".$session['user']['acctid'];
db_query($sql);
$sql="SELECT acctid,name FROM accounts WHERE acctid=".(int)$_GET['id'];
  $result = db_query($sql);
$row = db_fetch_assoc($result);
$session['user']['reputation']--;
output("`6Beim Anblick deines Gegners ".$row['name']." `6wird dir Angst und Bang. Mit ein paar sehr dürftigen Ausreden wie \"nicht genug Gold\" lehnst du die Herausforderung ab und verlässt schnell die Arena.");
systemmail($row['acctid'],"`2Herausforderung abgelehnt","`2".$session['user']['name']."`2 hat deine Herausforderung abgelehnt. Vielleicht solltest du ".($session['user']['sex']?"ihr":"ihm")." etwas für den Kampf anbieten - falls ".($session['user']['sex']?"sie":"er")." dich besiegen kann."); 
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
        addnav('Arenaställe','arenastables.php');
}else if ($_GET['op']=="accept"){
if($session['user']['gold']<$cost){
output("`4Du kannst dir die Arena Nutzungsgebühr von `^$cost`4 Gold nicht leisten.");
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
addnav('Arenaställe','arenastables.php');
}else if($session['user']['playerfights']<=0){
output('`4Du kannst heute nicht mehr gegen andere Krieger antreten.');
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
addnav('Arenaställe','arenastables.php');
}else{
 $sql = "UPDATE pvp SET name2='".mysql_real_escape_string($session['user']['name'])."',hp2=".$session['user']['hitpoints'].",maxhp2=".$session['user']['maxhitpoints'].",att2=".$session['user']['attack'].",def2=".$session['user']['defence'].",weapon2='".mysql_real_escape_string($session['user']['weapon'])."',armor2='".mysql_real_escape_string($session['user']['armor'])."',darkartuses2=".$session['user']['darkartuses'].",magicuses2=".$session['user']['magicuses'].",thieveryuses2=".$session['user']['thieveryuses'].",bufflist2='".mysql_real_escape_string($session['user']['bufflist'])."',turn=2 WHERE acctid2=".$session['user']['acctid']."";
db_query($sql);
if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
$sql="SELECT * FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";
  $result = db_query($sql);
$row = db_fetch_assoc($result);
$session['user']['gold']-=$cost;
$session['user']['reputation']++;
arenanav($row);
stats($row);
}
}else if ($_GET['op']=='back'){
$sql="SELECT acctid,name FROM accounts WHERE acctid=".(int)$_GET['id'];
  $result = db_query($sql);
$row = db_fetch_assoc($result);
output("`6Du bist es Leid, auf ".$row['name']."`6 zu warten und ziehst deine Herausforderung zurück. Die Arenagebühr bekommst aber trotz langer Verhandlungen mit der Arena-Leitung nicht zurück.`n");
$sql = "DELETE FROM pvp WHERE acctid1=".$session['user']['acctid'];
db_query($sql);
$session['user']['reputation']--;
systemmail($row['acctid'],"`2Herausforderung zurückgezogen","`2".$session['user']['name']."`2 hat seine Herausforderung zurückgezogen."); 
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
        addnav('Arenaställe','arenastables.php');
}else if ($_GET['op']=='fight'){
$sql="SELECT * FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid']."";
  $result = db_query($sql);
$row = db_fetch_assoc($result);
if ($row['turn']==1){
 $badguy = array("acctid"=>$row['acctid2'],"name"=>$row['name2'],"level"=>$row['lvl2'],"hitpoints"=>$row['hp2'],"attack"=>$row['att2'],"defense"=>$row['def2'],"weapon"=>$row['weapon2'],"armor"=>$row['armor2'],"bufflist"=>$row['bufflist2']);
 $goodguy = array("name"=>$row['name1'],"level"=>$row['lvl1'],"hitpoints"=>$row['hp1'],"maxhitpoints"=>$row['maxhp1'],"attack"=>$row['att1'],"defense"=>$row['def1'],"weapon"=>$row['weapon1'],"armor"=>$row['armor1'],"darkartuses"=>$row['darkartuses1'],"magicuses"=>$row['magicuses1'],"thieveryuses"=>$row['thieveryuses1'],"bufflist"=>$row['bufflist1']);
}
if ($row['turn']==2){
 $badguy = array("acctid"=>$row['acctid1'],"name"=>$row['name1'],"level"=>$row['lvl1'],"hitpoints"=>$row['hp1'],"attack"=>$row['att1'],"defense"=>$row['def1'],"weapon"=>$row['weapon1'],"armor"=>$row['armor1'],"bufflist"=>$row['bufflist1']);
 $goodguy = array("name"=>$row['name2'],"level"=>$row['lvl2'],"hitpoints"=>$row['hp2'],"maxhitpoints"=>$row['maxhp2'],"attack"=>$row['att2'],"defense"=>$row['def2'],"weapon"=>$row['weapon2'],"armor"=>$row['armor2'],"darkartuses"=>$row['darkartuses2'],"magicuses"=>$row['magicuses2'],"thieveryuses"=>$row['thieveryuses2'],"bufflist"=>$row['bufflist2']);
}
stats($row);
$adjustment=1;
$goodguy['bufflist']=unserialize($goodguy['bufflist']);
$badguy['bufflist']=unserialize($badguy['bufflist']);
// spells
if ($_GET['skill']=='zauber'){
$resultz=db_query("SELECT * FROM items WHERE id=".(int)$_GET['itemid']);
$zauber = db_fetch_assoc($resultz);
$spellbuff=unserialize($zauber['buff']);
$goodguy['bufflist'][$spellbuff['name']]=$spellbuff;
$zauber['gold']=round($zauber['gold']($zauber['value1']($zauber['value2']1)));
$zauber['gems']=round($zauber['gems']($zauber['value1']($zauber['value2']1)));
$zauber['value1']--;
if ($zauber['value1']<=0 && $zauber['hvalue']<=0){
db_query("DELETE FROM items WHERE id=".(int)$_GET['itemid']);
}else{
db_query("UPDATE items SET value1=".$zauber['value1'].", gems=".$zauber['gems'].", gold=".$zauber['gold']." WHERE id=".(int)$_GET['itemid']);
}
}
// end spells
if ($_GET['skill']=='MP'){
if ($goodguy['magicuses'] >= (int)$_GET['l']){
$creaturedmg = 0;
switch($_GET['l']){
case '1':
$goodguy['bufflist']['mp1'] = array(
"startmsg"=>"`n`^Du fängst an zu regenerieren!`n`n",
"name"=>"`%Regeneration",
"rounds"=>5,
"wearoff"=>"Deine Regeneration hat aufgehört",
"regen"=>$goodguy['level'],
"effectmsg"=>"Du regenerierst um {damage} Punkte.",
"effectnodmgmsg"=>"Du bist völlig gesund.",
"activate"=>"roundstart");
break;
case '2':
$goodguy['bufflist']['mp2'] = array(
"startmsg"=>"`n`^{badguy}`% wird von einer Klaue aus Erde gepackt und auf den Boden geschleudert!`n`n",
"name"=>"`%Erdenfaust",
"rounds"=>5,
"wearoff"=>"Die erdene Faust zerfällt zu staub.",
"minioncount"=>1,
"effectmsg"=>"`) Eine gewaltige Faust aus Erde trifft {badguy} `)mit `^{damage}`) Schadenspunkten.",
"minbadguydamage"=>1,
"maxbadguydamage"=>$goodguy['level']3,
"activate"=>"roundstart"
);
break;
case '3':
$goodguy['bufflist']['mp3'] = array(
"startmsg"=>"`n`^Deine Waffe glüht in einem überirdischen Schein.`n`n",
"name"=>"`%Leben absaugen",
"rounds"=>5,
"wearoff"=>"Die Aura deiner Waffe verschwindet.",
"lifetap"=>1, //ratio of damage healed to damage dealt
"effectmsg"=>"Du wirst um {damage} Punkte geheilt.",
"effectnodmgmsg"=>"Du fühlst ein Prickeln, als deine Waffe versucht, deinen vollständig gesunden Körper zu heilen.",
"effectfailmsg"=>"Deine Waffe scheint zu jammern, als du deinem Gegner keinen Schaden machst.",
"activate"=>"offense,defense",
);
break;
case 5:
$goodguy['bufflist']['mp5'] = array(
"startmsg"=>"`n`^Deine Haut glitzert, als du dir eine Aura aus Blitzen zulegst`n`n",
"name"=>"`%Blitzaura",
"rounds"=>5,
"wearoff"=>"Mit einem Zischen wird deine Haut wieder normal.",
"damageshield"=>2,
"effectmsg"=>"{badguy}`)wird von einem Blitzbogen aus deiner Haut mit `^{damage}`) Schadenspunkten zurückgeworfen.",
"effectnodmg"=>"{badguy} `)ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt.",
"effectfailmsg"=>"{badguy}`) ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt.",
"activate"=>"offense,defense"
);
break;
}
$goodguy['magicuses']-=(int)$_GET['l'];
}else{
$goodguy['bufflist']['mp0'] = array(
"startmsg"=>"`nDu legst deine Stirn in Falten und beschwörst die Elemente.  Eine kleine Flamme erscheint. {badguy} zündet sich eine Zigarette daran an, dankt dir und stürzt sich wieder auf dich.`n`n",
"rounds"=>1,
"activate"=>"roundstart"
);
}
}
if ($_GET['skill']=='DA'){
if ($goodguy['darkartuses'] >= $_GET['l']){
$creaturedmg = 0;
switch($_GET['l']){
case '1':
$goodguy['bufflist']['da1']=array(
"startmsg"=>"`n`\$Du rufst die Geister der Toten und skelettartige Hände zerren an {badguy} aus den Tiefen ihrer Gräber.`n`n",
"name"=>"`\$Skelettdiener",
"rounds"=>5,
"wearoff"=>"Deine Skelettdiener zerbröckeln zu staub.",
"minioncount"=>round($goodguy['level']3)1,
"maxbadguydamage"=>round($goodguy['level']2,0)1,
"effectmsg"=>"`)Ein untoter Diener trifft {badguy} mit `^{damage}`) Schadenspunkten.",
"effectnodmgmsg"=>"`)Ein untoter Diener versucht {badguy} zu treffen, aber `\$TRIFFT NICHT`)!",
"activate"=>"roundstart"
);
break;
case '2':
$goodguy['bufflist']['da2']=array(
"startmsg"=>"`n`\$Du holst eine winzige Puppe die aussieht wie {badguy} hervor`n`n",
"effectmsg"=>"Du stößt eine Nadel in die {badguy}-Puppe und machst damit `^{damage}`) Schadenspunkte!",
"minioncount"=>1,
"maxbadguydamage"=>round($goodguy['attack']3,0),
"minbadguydamage"=>round($goodguy['attack']1.5,0),
"activate"=>"roundstart"
);
break;
case '3':
$goodguy['bufflist']['da3']=array(
"startmsg"=>"`n`\$Du sprichst einen Fluch auf die Ahnen von {badguy}.`n`n",
"name"=>"`\$Geist verfluchen",
"rounds"=>5,
"wearoff"=>"Dein Fluch ist gewichen.",
"badguydmgmod"=>0.5,
"roundmsg"=>"{badguy} taumelt unter der Gewalt deines Fluchs und macht nur halben Schaden.",
"activate"=>"defense"
);
break;
case '5':
$goodguy['bufflist']['da5']=array(
"startmsg"=>"`n`\$Du streckst deine Hand aus und {badguy} `\$fängt an, aus den Ohren zu bluten.`n`n",
"name"=>"`\$Seele verdorren",
"rounds"=>5,
"wearoff"=>"Die Seele deines Opfers hat sich erholt.",
"badguyatkmod"=>0,
"badguydefmod"=>0,
"roundmsg"=>"{badguy} kratzt sich beim Versuch, die eigene Seele zu befreien, fast die Augen aus und kann nicht angreifen oder sich verteidigen.",
"activate"=>"offense,defense"
);
break;
}
$goodguy['darkartuses']-=(int)$_GET['l'];
}else{
$goodguy['bufflist']['da0'] = array(
"startmsg"=>"`nErschöpft versuchst du deine dunkelste Magie: einen schlechten Witz.  {badguy} schaut dich nachdenklich eine Minute lang an. Endlich versteht er den Witz und stürzt sich lachend wieder auf dich.`n`n",
"rounds"=>1,
"activate"=>"roundstart"
);
}
}
if ($_GET['skill']=='TS'){
if ($goodguy['thieveryuses'] >= $_GET['l']){
$creaturedmg = 0;
switch($_GET['l']){
case '1':
$goodguy['bufflist']['ts1']=array(
"startmsg"=>"`n`^Du gibst deinem Gegner einen schlimmen Namen und bringst {badguy} zum Weinen.`n`n",
"name"=>"`^Beleidigung",
"rounds"=>5,
"wearoff"=>"Dein Gegner putzt sich die Nase und hört auf zu weinen.",
"roundmsg"=>"{badguy} ist deprimiert und kann nicht so gut angreifen.",
"badguyatkmod"=>0.5,
"activate"=>"defense"
);
break;
case '2':
$goodguy['bufflist']['ts2']=array(
"startmsg"=>"`n`^Du reibst Gift auf dein(e/n) ".$goodguy['weapon'].".`n`n",
"name"=>"`^Vergiftete Waffe",
"rounds"=>5,
"wearoff"=>"Das Blut deines Gegners hat das Gift von deiner Waffe gewaschen.",
"atkmod"=>2,
"roundmsg"=>"Dein Angriffswert vervielfacht sich!",
"activate"=>"offense"
);
break;
case '3':
$goodguy['bufflist']['ts3'] = array(
"startmsg"=>"`n`^Mit dem Geschick eines erfahrenen Diebs scheinst du zu verschwinden und kannst {badguy} aus einer günstigeren und sichereren Position angreifen.`n`n",
"name"=>"`^Versteckter Angriff",
"rounds"=>5,
"wearoff"=>"Dein Opfer hat dich gefunden.",
"roundmsg"=>"{badguy} kann dich nicht finden.",
"badguyatkmod"=>0,
"activate"=>"defense"
);
break;
case '5':
$goodguy['bufflist']['ts5']=array(
"startmsg"=>"`n`^Mit deinen Fähigkeiten als Dieb verschwindest du und schiebst {badguy} von hinten eine dünne Klinge zwischen die Rückenwirbel!`n`n",
"name"=>"`^Angriff von hinten",
"rounds"=>5,
"wearoff"=>"Dein Opfer ist nicht mehr so nett, dich hinter sich zu lassen!",
"atkmod"=>3,
"defmod"=>3,
"roundmsg"=>"Dein Angriffswert und deine Verteidigung vervielfachen sich!",
"activate"=>"offense,defense"
);
break;
}
$goodguy['thieveryuses']-=(int)$_GET['l'];
}else{
$goodguy['bufflist']['ts0'] = array(
"startmsg"=>"`nDu versuchst, {badguy} anzugreifen, indem du deine besten Diebeskünste in die Praxis umsetzt - aber du stolperst über deine eigenen Füsse.`n`n",
"rounds"=>1,
"activate"=>"roundstart"
);
}
}
if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0) {
output ("`\$`c`b~ ~ ~ Kampf ~ ~ ~`b`c`0`n `@Du hast den Gegner `^".$badguy['name']."`@ entdeckt, der sich mit seiner Waffe `%".$badguy['weapon']."`@");
// Let's display what buffs the opponent is using - oh yeah
$buffs="";
$disp['bufflist']=$badguy['bufflist'];
reset($disp['bufflist']);
foreach($disp['bufflist'] as $key => $val){
$buffs.=" `@und `#".$val['name']." `7(".$val['rounds']." Runden)";
}
if (count($disp['bufflist'])==0){
$buffs.=appoencode("",true);
}
output("$buffs `@auf dich stürzt!`0`n`n`2Level: `6".$badguy['level']."`0`n`2`bBeginn der Runde:`b`n`2".$badguy['name']."`2's Lebenspunkte: `6".$badguy['hitpoints']."`0`n`2DEINE Lebenspunkte: `6".$goodguy['hitpoints']."`0`n");
}
reset($goodguy['bufflist']);
foreach($goodguy['bufflist'] as $key => $buff){
$buff['used']=0;
}
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0){
$buffset = activate_buffs("roundstart");
$creaturedefmod=$buffset['badguydefmod'];
$creatureatkmod=$buffset['badguyatkmod'];
$atkmod=$buffset['atkmod'];
$defmod=$buffset['defmod'];
}
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0){
$adjustedcreaturedefense = $badguy['defense'];
$creatureattack = $badguy['attack']$creatureatkmod;
$adjustedselfdefense = ($goodguy['defense']  $adjustment  $defmod);
while($creaturedmg==0 && $selfdmg==0){
$atk = $goodguy['attack']$atkmod;
if (e_rand(1,20)==1) $atk*=3;
$patkroll = e_rand(0,$atk);
$catkroll = e_rand(0,$adjustedcreaturedefense);
$creaturedmg = 0(int)($catkroll  $patkroll);
if ($creaturedmg<0) {
$creaturedmg = (int)($creaturedmg2);
$creaturedmg = round($buffset['badguydmgmod']$creaturedmg,0);
}
if ($creaturedmg > 0) {
$creaturedmg = round($buffset['dmgmod']$creaturedmg,0);
}
$pdefroll = e_rand(0,$adjustedselfdefense);
$catkroll = e_rand(0,$creatureattack);
$selfdmg = 0(int)($pdefroll  $catkroll);
if ($selfdmg<0) {
$selfdmg=(int)($selfdmg2);
$selfdmg = round($selfdmg$buffset['dmgmod'], 0);
}
if ($selfdmg > 0) {
$selfdmg = round($selfdmg$buffset['badguydmgmod'], 0);
}
}
}
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0){
$buffset = activate_buffs("offense");
if ($atk > $goodguy['attack']) {
if ($atk > $goodguy['attack']3){
if ($atk>$godguy['attack']4){
output("`&`bDu holst zu einem <font size='+1'>MEGA</font> Powerschlag aus!!!`b`n",true);
}else{
output("`&`bDu holst zu einem DOPPELTEN Powerschlag aus!!!`b`n");
}
}else{
if ($atk>$goodguy['attack']2){
output('`&`bDu holst zu einem Powerschlag aus!!!`b`0`n');
}elseif ($atk>$goodguy['attack']1.25){
output('`7`bDu holst zu einem kleinen Powerschlag aus!`b`0`n');
}
}
}
if ($creaturedmg==0){
output("`4Du versuchst `^".$badguy['name']."`4 zu treffen, aber `\$TRIFFST NICHT!`n");
$message=$message."`^".$goodguy['name']."`4 versucht dich zu treffen, aber `\$TRIFFT NICHT!`n";
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) process_dmgshield($buffset['dmgshield'], 0);
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) process_lifetaps($buffset['lifetap'], 0);
}else if ($creaturedmg<0){
output("`4Du versuchst `^".$badguy['name']."`4 zu treffen, aber der `\$ABWEHRSCHLAG `4trifft dich mit `\$".(0$creaturedmg)."`4 Schadenspunkten!`n");
$message=$message."`^".$goodguy['name']."`4 versucht dich zu treffen, aber dein `\$ABWEHRSCHLAG`4 trifft mit `\$".(0$creaturedmg)."`4 Schadenspunkten!`n";
$badguy['diddamage']=1;
$goodguy['hitpoints']+=$creaturedmg;
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) process_dmgshield($buffset['dmgshield'],$creaturedmg);
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) process_lifetaps($buffset['lifetap'],$creaturedmg);
}else{
output("`4Du triffst `^".$badguy['creaturename']."`4 mit `^$creaturedmg`4 Schadenspunkten!`n");
$message=$message."`^".$goodguy['name']."`4 trifft dich mit `^$creaturedmg`4 Schadenspunkten!`n";
$badguy['hitpoints']-=$creaturedmg;
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) process_dmgshield($buffset['dmgshield'],$creaturedmg);
if ($badguy['hitpoints']>0 && $goodguy['hitpoints']>0) process_lifetaps($buffset['lifetap'],$creaturedmg);
}
// from hardest punch mod -- remove if not installed!!
if ($creaturedmg>$session['user']['punch']){
$session['user']['punch']=$creaturedmg;
output('`@`b`c--- DAS WAR DEIN BISHER HÄRTESTER SCHLAG! ---`c`b`n');
}
// end hardest punch
}
if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0) $buffset = activate_buffs("defense");
expire_buffs();
if ($goodguy['hitpoints']>0 && $badguy['hitpoints']>0){
output("`2`bEnde der Runde:`b`n`2".$badguy['name']."`2's Lebenspunkte: `6".$badguy['hitpoints']."`0`n`2DEINE Lebenspunkte: `6".$goodguy['hitpoints']."`0`n");
}

$goodguy['bufflist']=serialize($goodguy['bufflist']);
$badguy['bufflist']=serialize($badguy['bufflist']);
if ($row['acctid1']){ // battle still in DB? Result of round:
if ($badguy['hitpoints']>0 and $goodguy['hitpoints']>0){
$message=mysql_real_escape_string($message);
if ($row['turn']==1) $sql = "UPDATE pvp SET hp1=".$goodguy['hitpoints'].",hp2=".$badguy['hitpoints'].",thieveryuses1=".$goodguy['thieveryuses'].",darkartuses1=".$goodguy['darkartuses'].",magicuses1=".$goodguy['magicuses'].",bufflist1='".mysql_real_escape_string($goodguy['bufflist'])."',lastmsg='$message',turn=2 WHERE acctid1=".$session['user']['acctid'];
if ($row['turn']==2) $sql = "UPDATE pvp SET hp1=".$badguy['hitpoints'].",hp2=".$goodguy['hitpoints'].",thieveryuses2=".$goodguy['thieveryuses'].",darkartuses2=".$goodguy['darkartuses'].",magicuses2=".$goodguy['magicuses'].",bufflist2='".mysql_real_escape_string($goodguy['bufflist'])."',lastmsg='$message',turn=1 WHERE acctid2=".$session['user']['acctid'];
db_query($sql);
if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
output("`n`n`2Du erwartest den Zug deines Gegners.");
addnav("Aktualisieren","pvparena.php");
}else if ($badguy['hitpoints']<=0){
$win=$badguy['level']20$goodguy['level']20;
$exp=$badguy['level']10(abs($goodguy['level']$badguy['level'])10);
if ($badguy['level']<=$goodguy['level']){
$session['user']['battlepoints']+=2;
}else{
$session['user']['battlepoints']+=3($badguy['level']$goodguy['level']);
}

$session['user']['reputation']+=5;
output("`n`&Kurz vor deinem finalen Todesstoß beendet der Arenawärter euren Kampf und erklärt dich zum Sieger!`0`n`b`\$Du hast ".$badguy['name']."`\$besiegt!`0`b`n`#Du bekommst das Preisgeld in Höhe von `^$win`# Gold und deinen gerechten Lohn an Arenakampfpunkten!`nDu bekommst insgesamt `^$exp`# Erfahrungspunkte!`n`0");
$session['user']['gold']+=$win;
$session['user']['playerfights']--;
$session['user']['experience']+=$exp;
$session['user']['donation']+=1;
######################################## // Guilds Code, Dasher
            populate_guilds();
            $guildName=$session['guilds'][$session['user']['guildID']]['Name'];
            if ($guildName=="") $guildName="ohne";
            $guildDisplayRank=FindRankForPerson($u['guildID'],$u['acctid'],false);
            if ($guildDisplayRank=="Bugger" or $guildDisplayRank==""){
                   if ($session['user']['guildrank']<>0){
                          $sql="select DisplayTitle from lotbd_guildranks where RankID=".$session['user']['guildrank'];
                          $result=db_query($sql);
                          $row = db_fetch_assoc($result);
                          $guildDisplayRank=$row['DisplayTitle'];
                  //$guildDisplayRank="None";
                   }else{
                       $guildDisplayRank="ohne";
                }
            }
            // End Guilds Code 

##############################################
// Guilds/Clans Code
            if ($session['user']['guildID']!=0) {
                $MyGuild=&$session['guilds'][$session['user']['guildID']];
                if (isset($MyGuild)) {
                    $sitepoints=1;
                    if ($badguy['guildID']!=0) {
                        $TheirGuild=$session['guilds'][$badguy['guildID']];
                    } else {
                        // They are not a member of a guild
                    }
                    $GuildFee = round((($MyGuild['PercentOfFightsEarned']['PvP']100)  $badguy['creaturegold']),0);
                    if ($GuildFee<=0) $GuildFee=(($session['user']['level']10)  ($MyGuild['PercentOfFightsEarned']['PvP']100)1);
                    output("`&`nDeine Gilde fordert ihren Anteil. Du zahlst `^".$GuildFee." Gold `&Tribut.");
                    output("`%`nDeine Gilde bekommt `&".$sitepoints." `%Punkte für diesen Kampf.");
                    $MyGuild['SitePoints']+=$sitepoints;
                    if ($session['user']['gold']<$GuildFee) {
                        $session['user']['goldinbank']+=($session['user']['gold']$GuildFee);
                        $session['user']['gold']=0;
                        output("`nDu zahlst einen Teil des Tributs direkt von der Bank!");
                    } else {
                        $session['user']['gold']-=$GuildFee;
                    }
                    $MyGuild['gold']+=$GuildFee;
                    update_guild_info($MyGuild);
                    addnews("`6Im Namen seiner Gilde ".$guildName."`6 besiegte ".$session['user']['name']."`6 seinen
                    Gegner ".$badguy['name']."`6 in der `8Arena!");
                } else {
                    // Error
                    // Their guildID is set but the information cannot be retrieved
                    $debug=print_r($session['user']['guildID'],true);
                    debuglog("MyGuild isn't set: ".$debug);
                }
            } else {
                // They don't belong to a guild
            } //


$exp = round(getsetting("pvpdeflose",5)10,0);
$sql = "UPDATE accounts SET charm=charm-1,experience=experience-$exp,playerfights=playerfights-1 WHERE acctid=".$badguy['acctid'];
db_query($sql);
$mailmessage = "`^".$goodguy['name']."`2 hat dich mit %p `^".$goodguy['weapon']."`2 in der Arena besiegt!"
." `n`n%o hatte am Ende noch `^".$goodguy['hitpoints']."`2 Lebenspunkte übrig."
." `n`nDu hast `\$$exp`2 deiner Erfahrungspunkte verloren.";
 $mailmessage = str_replace_c("%p",($session['user']['sex']?"ihre(r/m)":"seine(r/m)"),$mailmessage);
 $mailmessage = str_replace_c("%o",($session['user']['sex']?"Sie":"Er"),$mailmessage);
 systemmail($badguy['acctid'],"`2Du wurdest in der Arena besiegt",$mailmessage); 
if ($session['user']['guildID']==0)addnews("`$".$goodguy['name']."`6 besiegt `$".$badguy['name']."`6 bei einem Duell in der `8Arena`6!");
$sql = "DELETE FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid'];
db_query($sql);
}else if ($goodguy['hitpoints']<=0){
$exp=$badguy['level']10(abs($goodguy['level']$badguy['level'])10);
$win=$badguy['level']20$goodguy['level']20;
if ($badguy['level']>=$goodguy['level']){
$points=2;
}else{
$points=3($goodguy['level']$badguy['level']);
}

			$sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
			$result = db_query($sql);
			$taunt = db_fetch_assoc($result);
			$taunt = stripslashes($taunt['taunt']);
			$taunt = str_replace_c("%s",($session['user']['sex']?"sie":"ihn"),$taunt);
			$taunt = str_replace_c("%o",($session['user']['sex']?"sie":"er"),$taunt);
			$taunt = str_replace_c("%p",($session['user']['sex']?"ihr":"sein"),$taunt);
			$taunt = str_replace_c("%x",($session['user']['weapon']),$taunt);
			$taunt = str_replace_c("%X",$badguy['weapon'],$taunt);
			$taunt = str_replace_c("%W",$badguy['name'],$taunt);
			$taunt = str_replace_c("%w",$session['user']['name'],$taunt);
$badguy['acctid']=(int)$badguy['acctid'];
$badguy[creaturegold]=(int)$badguy['creaturegold'];
systemmail($badguy['acctid'],"`2 du warst in der Arena erfolgreich! ","`^".$session['user']['name']."`2 hat in der Arena verloren!`n`nDafür hast du `^$exp`2 Erfahrungspunkte und `^$win`2 Gold erhalten!"); 
$sql = "UPDATE accounts SET gold=gold+$win,experience=experience+$exp,donation=donation+1,playerfights=playerfights-1,battlepoints=battlepoints+$points,reputation=reputation+5 WHERE acctid=".$badguy['acctid'];
db_query($sql);
$exp = round(getsetting("pvpdeflose",5)10,0);
$session['user']['experience']-=$exp;
$session['user']['playerfights']--;
output("`n`b`&Du wurdest von `%".$badguy['name']."`& besiegt!!!`n $taunt `n`4Du hast `^$exp Erfahrungspunkte verloren!`n");
if ($session['user']['charm']>0) $session['user']['charm']--;
if ($session['user']['guildID']==0)addnews("`$".$badguy['name']."`6 besiegt `$".$goodguy['name']."`6 bei einem Duell in der `8Arena`6!");
$sql = "DELETE FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid'];
db_query($sql);
}
}else{
output('`6Euer Kampf wurde vorzeitig beendet. Die Nutzungsgebühr kommt dem Ausbau der Arena zugute.');
}
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
        addnav("Arenaställe","arenastables.php");
}else if ($_GET['op']=="del"){
$sql="DELETE FROM pvp WHERE acctid1=".(int)$_GET['kid1']." AND acctid2=".(int)$_GET['kid2'];
db_query($sql);
output("Der Arenarichter beendet einen langweiligen Kampf.");
systemmail((int)$_GET['kid1'],"`2Dein Arenakampf wurde beendet!","`@Dein Kampf in der Arena wurde vom Kampfrichter beendet."); 
systemmail((int)$_GET['kid2'],"`2Dein Arenakampf wurde beendet!","`@Dein Kampf in der Arena wurde vom Kampfrichter beendet.");

addnav('Zur Arena','pvparena.php');
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
        addnav('Arenaställe','arenastables.php');
}else if ($_GET['op']==''){
$sql="SELECT * FROM pvp WHERE acctid1=".$session['user']['acctid']." OR acctid2=".$session['user']['acctid'];
  $result = db_query($sql);
$row = db_fetch_assoc($result);
$text=0;
if($row['acctid1']==$session['user']['acctid'] && $row['turn']==0){
 $text=1;
output("`6Da du noch eine Herausforderung mit `&".$row['name2']." `6offen hast, machst du dich auf in Richtung Arena, um nach deinem Gegner Ausschau zu halten. Doch der scheint nirgendwo in Sicht zu sein.`n");
addnav('Herausforderung zurücknehmen',"pvparena.php?op=back&id=".$row['acctid2']);
 addnav('Gladiator herausfordern','battlearena.php'); 
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
addnav('Arenaställe','arenastables.php');
stats($row);
}else if($row['acctid1']==$session['user']['acctid'] && $row['turn']==1){
stats($row);
arenanav($row);
}else if($row['acctid1']==$session['user']['acctid'] && $row['turn']==2){
stats($row);
output("`6Dein Gegner `&".$row['name2']."`6 hat seinen Zug noch nicht gemacht.`n`n");
$text=1;
addnav('Gladiator herausfordern','battlearena.php');
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
addnav('Arenaställe','arenastables.php');
}else if($row['acctid2']==$session['user']['acctid'] && $row['turn']==0){
output("`6Du wurdest von `&".$row['name1']." `6herausgefordert. Wenn du die Herausforderung annimmst, musst du `^$cost`6 Gold Arenagebühr bezahlen.`n");
addnav("Du wurdest von ".$row['name1']." herausgefordert");
addnav("Herausforderung annehmen","pvparena.php?op=accept");
addnav("Feige ablehnen","pvparena.php?op=deny&id=".$row['acctid1']);
}else if($row['acctid2']==$session['user']['acctid'] && $row['turn']==1){
stats($row);
output("`6Dein Gegner `&".$row['name1']."`6 hat seinen Zug noch nicht gemacht.`n`n");
$text=1;
 addnav('Gladiator herausfordern','battlearena.php');
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
addnav('Arenaställe','arenastables.php');
}else if($row['acctid2']==$session['user']['acctid'] && $row['turn']==2){
stats($row);
arenanav($row);
}else{
$text=1;
addnav('Spieler herausfordern','pvparena.php?op=challenge');
addnav('Gladiator herausfordern','battlearena.php');
addnav('Zurück zur Klingengasse','village.php?op=klingengasse');
                addnav('Arenaställe','arenastables.php');
}
if($text==1){
checkday();
  addnav('Aktualisieren','pvparena.php');
output('`6Du betrittst die große Kampfarena in der Nähe des Trainingslagers. Hier kämpfen einge der Krieger gegeneinander und üben sich in ihren besonderen Fertigkeiten, um herauszufinden, wer von ihnen der Beste ist. Es geht um die Ehre - und um eine gute Platzierung in der Kämpferliga.`n'); 
$sql="SELECT * FROM pvp WHERE acctid1 AND acctid2 AND turn>0";
  $result = db_query($sql);
if (db_num_rows($result)){
output(" Du beobachtest das bunte Treiben auf dem Platz eine Weile.`nFolgende Krieger kämpfen gerade gegeneinander:`n`n<table border='0' cellpadding='3' cellspacing='0'><tr><td align='center'>`bHerausforderer`b</td><td align='center'>`bVerteidiger`b</td><td align='center'>`bStand (LP)`b</td>".($session['user']['superuser']>=4?"<td>Ops</td>":"")."</tr>",true);
for ($i=0;$i<db_num_rows($result);$i++){
$row = db_fetch_assoc($result);
output("<tr class='".($i2?"trlight":"trdark")."'><td>".$row['name1']."</td><td>".$row['name2']."</td><td>".$row['hp1']." : ".$row['hp2']."</td>".($session['user']['superuser']>=4?"<td><a href='pvparena.php?op=del&kid1=".$row['acctid1']."&kid2=".$row['acctid2']."'>Löschen</a></td>":"")."</tr>",true);
if ($session['user']['superuser']>=4) allownav("pvparena.php?op=del&kid1=".$row['acctid1']."&kid2=".$row['acctid2']);
}
output("</table>`n`n",true);
}else{
output('`n`n`6Im Moment laufen keine Kämpfe zwischen den Helden dieser Welt.`n`n');
}
viewcommentary("pvparena","Rufen:",10,"ruft");
output("`n`n");
$result2=db_query("SELECT newsdate,newstext FROM news WHERE newstext LIKE '%Arena`6!' ORDER BY newsid DESC LIMIT 10");
for ($i=0;$i<db_num_rows($result2);$i++){
  $row2 = db_fetch_assoc($result2);
output("`n`0".$row2['newsdate'].": ".$row2['newstext']);
}
}
}

page_footer();
?>