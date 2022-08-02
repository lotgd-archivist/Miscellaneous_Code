<?php

// 28062004

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
page_header("Kämpferarena!");

function stats($row){  // Shows player stats
        global $session;
        output("<table align='center' border='0' cellpadding='2' cellspacing='0' class='vitalinfo'><tr><td class='charhead' colspan='4'>`^`bVital Info`b für diesen Kampf</td><tr><td class='charinfo'>`&Level: `^`b",true);
        if ($row[bufflist1]) $row[bufflist1]=unserialize($row[bufflist1]);
        if ($row[bufflist2]) $row[bufflist2]=unserialize($row[bufflist2]);
        if ($row[bufflist1]) reset($row[bufflist1]);
        if ($row[bufflist2]) reset($row[bufflist2]);
        if ($row[acctid1]==$session[user][acctid]){
                $atk=$row[att1];
                $def=$row[def1];
                while (list($key,$val)=each($row[bufflist1])){
                        //output(" $val[name]/$val[badguyatkmod]/$val[atkmod]/$val[badguydefmod]/$val[defmod]`n");
                        $buffs.=appoencode("`#$val[name] `7($val[rounds] Runden übrig)`n",true);
                        if (isset($val[atkmod])) $atk *= $val[atkmod];
                        if (isset($val[defmod])) $def *= $val[defmod];
                }
                if ($row[bufflist2]){
                    while (list($key,$val)=each($row[bufflist2])){
                //output(" $val[name]/$val[badguyatkmod]/$val[atkmod]/$val[badguydefmod]/$val[defmod]`n");
                if (isset($val[badguyatkmod])) $atk *= $val[badguyatkmod];
                if (isset($val[badguydefmod])) $def *= $val[badguydefmod];
            }
                }
                $atk = round($atk, 2);
                $def = round($def, 2);
                $atk = ($atk == $row[att1] ? "`^" : ($atk > $row[att1] ? "`@" : "`$")) . "`b$atk`b`0";
                $def = ($def == $row[def1] ? "`^" : ($def > $row[def1] ? "`@" : "`$")) . "`b$def`b`0";
                if (count($row[bufflist1])==0){
                        $buffs.=appoencode("`^Keine`0",true);
                }
                output("$row[lvl1]`b</td><td class='charinfo'>`&Lebenspunkte: `^$row[hp1]/`0$row[maxhp1]</td><td class='charinfo'>`&Angriff: `^`b$atk`b</td><td class='charinfo'>`&Verteidigung: `^`b$def`b</td>",true);
                output("</tr><tr><td class='charinfo' colspan='2'>`&Waffe: `^$row[weapon1]</td><td class='charinfo' colspan='2'>`&Rüstung: `^$row[armor1]</td>",true);
                output("</tr><tr><td colspan='4' class='charinfo'>`&Aktionen:`n$buffs",true);
                output("</td>",true);
        }
        if ($row[acctid2]==$session[user][acctid]){
                $atk=$row[att2];
                $def=$row[def2];
                while (list($key,$val)=each($row[bufflist2])){
                        $buffs.=appoencode("`#$val[name] `7($val[rounds] Runden übrig)`n",true);
                        if (isset($val[atkmod])) $atk *= $val[atkmod];
                        if (isset($val[defmod])) $def *= $val[defmod];
                }
                if ($row[bufflist1]){
                    while (list($key,$val)=each($row[bufflist1])){
                if (isset($val[badguyatkmod])) $atk *= $val[badguyatkmod];
                if (isset($val[badguydefmod])) $def *= $val[badguydefmod];
            }
                }
                $atk = round($atk, 2);
                $def = round($def, 2);
                $atk = ($atk == $row[att2] ? "`^" : ($atk > $row[att2] ? "`@" : "`$")) . "`b$atk`b`0";
                $def = ($def == $row[def2] ? "`^" : ($def > $row[def2] ? "`@" : "`$")) . "`b$def`b`0";
                if (count($row[bufflist2])==0){
                        $buffs.=appoencode("`^Keine`0",true);
                }
                output("$row[lvl2]`b</td><td class='charinfo'>`&Lebenspunkte: `^$row[hp2]/`0$row[maxhp2]</td><td class='charinfo'>`&Angriff: `^`b$atk`b</td><td class='charinfo'>`&Verteidigung: `^`b$def`b</td>",true);
                output("</tr><tr><td class='charinfo' colspan='2'>`&Waffe: `^$row[weapon2]</td><td class='charinfo' colspan='2'>`&Rüstung: `^$row[armor2]</td>",true);
                output("</tr><tr><td class='charinfo' colspan='4'>`&Aktionen:`n$buffs",true);
                output("</td>",true);
        }
        output("</tr></table>`n",true);
        if ($row[bufflist1]) $row[bufflist1]=serialize($row[bufflist1]);
        if ($row[bufflist2]) $row[bufflist2]=serialize($row[bufflist2]);
        if ($row[typus]==0) output("`b`c`&Kampf: `@Normale Herausforderung`0`n`n`b`c");
        if ($row[typus]==1) output("`b`c`&Kampf: `4Wildkampf`0`n`n`b`c");
}
function arenanav($row){ // Navigation during fight
        if ($row[turn]==1){
                 $badguy = array("acctid"=>$row[acctid2],"name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"bufflist"=>$row[bufflist2]);
                 $goodguy = array("name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"maxhitpoints"=>$row[maxhp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"skill"=>$row[skill1],"skillpoints"=>$row[skillpoints1],"bufflist"=>$row[bufflist1]);
        }
        if ($row[turn]==2){
                 $badguy = array("acctid"=>$row[acctid1],"name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"bufflist"=>$row[bufflist1]);
                 $goodguy = array("name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"maxhitpoints"=>$row[maxhp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"skill"=>$row[skill2],"skillpoints"=>$row[skillpoints2],"bufflist"=>$row[bufflist2]);
        }
        if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0) {
                output ("`\$`c`b~ ~ ~ Kampf ~ ~ ~`b`c`0");
                output("`@Du hast den Gegner `^$badguy[name]`@ entdeckt, der sich mit seiner Waffe `%$badguy[weapon]`@");
                // Let's display what buffs the opponent is using - oh yeah
                $buffs="";
                $disp[bufflist]=unserialize($badguy[bufflist]);
                reset($disp[bufflist]);
                while (list($key,$val)=each($disp[bufflist])){
                        $buffs.=" `@und `#$val[name] `7($val[rounds] Runden)";
                }
                if (count($disp[bufflist])==0){
                        $buffs.=appoencode("",true);
                }
                output("$buffs");
                output(" `@auf dich stürzt!`0`n`n");
                output("`2Level: `6$badguy[level]`0`n");
                output("`2`bErgebnis der letzten Runde:`b`n");
                output("`2$badguy[name]`2's Lebenspunkte: `6$badguy[hitpoints]`0`n");
                output("`2DEINE Lebenspunkte: `6$goodguy[hitpoints]`0`n");
                output("$row[lastmsg]");
        }
        addnav("Kampf");
        addnav("Kämpfen","pvparena.php?op=fight&act=fight");
        addnav("`bBesondere Fähigkeiten`b");
        //SKILLSMOD BY ANGEL START
        if ($goodguy[skill]!=0){
           addnav("`bBesondere Fähigkeiten`b");
           $sql2="SELECT * FROM skills WHERE id='".$goodguy[skill]."'";
           $result2 = db_query($sql2); //Befehl senden
           $row2 = db_fetch_assoc($result2);
           $c=$row2[color];
           if ($goodguy[skillpoints]>0){
                   addnav("$c $row2[name]`0", "");
                   addnav("$c &#149; $row2[force1]`7 (1/".$goodguy[skillpoints].")`0","pvparena.php?op=fight&skill=SK&1=1",true);
           }
           if ($goodguy[skillpoints]>1)
                addnav("$c &#149; $row2[force2]`7 (2/".$goodguy[skillpoints].")`0","pvparena.php?op=fight&skill=SK&1=2",true);
           if ($goodguy[skillpoints]>2)
                addnav("$c &#149; $row2[force3]`7 (3/".$goodguy[skillpoints].")`0","pvparena.php?op=fight&skill=SK&1=3",true);
           if ($goodguy[skillpoints]>4)
                addnav("$c &#149; $row2[force4]`7 (5/".$goodguy[skillpoints].")`0","pvparena.php?op=fight&skill=SK&1=5",true);
        }
        /*
        if ($goodguy[specialty]!=0){
                //SKILLSMOD BY ANGEL ENDE/
                if ($goodguy[darkartuses]>0) {
                        addnav("`\$Dunkle Künste`0", "");
                        addnav("`\$&#149; Skelette herbeirufen`7 (1/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=1",true);
                }
                if ($goodguy[darkartuses]>1)
                        addnav("`\$&#149; Voodoo`7 (2/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=2",true);
                if ($goodguy[darkartuses]>2)
                        addnav("`\$&#149; Geist verfluchen`7 (3/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=3",true);
                if ($goodguy[darkartuses]>4)
                        addnav("`\$&#149; Seele verdorren`7 (5/".$goodguy[darkartuses].")`0","pvparena.php?op=fight&skill=DA&l=5",true);
                if ($goodguy[thieveryuses]>0) {
                        addnav("`^Diebeskünste`0","");
                        addnav("`^&#149; Beleidigen`7 (1/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=1",true);
                }
                if ($goodguy[thieveryuses]>1)
                        addnav("`^&#149; Waffe vergiften`7 (2/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=2",true);
                if ($goodguy[thieveryuses]>2)
                        addnav("`^&#149; Versteckter Angriff`7 (3/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=3",true);
                if ($goodguy[thieveryuses]>4)
                        addnav("`^&#149; Angriff von hinten`7 (5/".$goodguy[thieveryuses].")`0","pvparena.php?op=fight&skill=TS&l=5",true);
                if ($goodguy[magicuses]>0) {
                        addnav("`%Mystische Kräfte`0","");
                        addnav("g?`%&#149; Regeneration`7 (1/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=1",true);
                }
                if ($goodguy[magicuses]>1)
                        addnav("`%&#149; Erdenfaust`7 (2/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=2",true);
                if ($goodguy[magicuses]>2)
                        addnav("L?`%&#149; Leben absaugen`7 (3/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=3",true);
                if ($goodguy[magicuses]>4)
                        addnav("A?`%&#149; Blitz Aura`7 (5/".$goodguy[magicuses].")`0","pvparena.php?op=fight&skill=MP&l=5",true);
        }
        */
        
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
        while(list($key,$buff) = each($goodguy['bufflist'])) {
                if (isset($buff['startmsg'])) {
                        $msg = $buff['startmsg'];
                        $msg = str_replace("{badguy}", $badguy[name], $msg);
                        output("`%$msg`0");
                        $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                        unset($goodguy['bufflist'][$key]['startmsg']);
                }
                $activate = strpos($buff['activate'], $tag);
                if ($activate !== false) $activate = true; // handle strpos == 0;
                // If this should activate now and it hasn't already activated,
                // do the round message and mark it.
                if ($activate && !$buff['used']) {
                        // mark it used.
                        $goodguy['bufflist'][$key]['used'] = 1;
                        // if it has a 'round message', run it.
                        if (isset($buff['roundmsg'])) {
                                $msg = $buff['roundmsg'];
                                $msg = str_replace("{badguy}", $badguy[name], $msg);
                                output("`)$msg`0`n");
                                $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
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
                        $hpdiff = $goodguy['maxhitpoints'] -
                        $goodguy['hitpoints'];
                        // Don't regen if we are above max hp
                        if ($hpdiff < 0) $hpdiff = 0;
                        if ($hpdiff < $hptoregen) $hptoregen = $hpdiff;
                        $goodguy['hitpoints'] += $hptoregen;
                        // Now, take abs value just incase this was a damaging buff
                        $hptoregen = abs($hptoregen);
                        if ($hptoregen == 0) $msg = $buff['effectnodmgmsg'];
                        else $msg = $buff['effectmsg'];
                        $msg = str_replace("{badguy}", $badguy[name], $msg);
                        $msg = str_replace("{damage}", $hptoregen, $msg);
                        output("`)$msg`0`n");
                        $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                }
                if (isset($buff['minioncount']) && $activate) {
                        $who = -1;
                        $buff['minioncount'] = stripslashes($buff['minioncount']);
                        eval("\$buff['minioncount'] = $buff[minioncount];");
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
                                        $badguy[hitpoints] -= $damage;
                                } else if ($who == 1) {
                                        $goodguy[hitpoints] -= $damage;
                                }
                                if ($damage < 0) {
                                        $msg = $buff['effectfailmsg'];
                                } else if ($damage == 0) {
                                        $msg = $buff['effectnodmgmsg'];
                                } else if ($damage > 0) {
                                        $msg = $buff['effectmsg'];
                                }
                                if ($msg>"") {
                                        $msg = str_replace("{badguy}", $badguy['name'], $msg);
                                        $msg = str_replace("{goodguy}", $session['user']['name'], $msg);
                                        $msg = str_replace("{damage}", $damage, $msg);
                                        output("`)$msg`0`n");
                                        $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                                }
                        }
                }
        }
        while(list($key,$buff) = each($badguy['bufflist'])) { // check badguy buffs
                $activate = strpos($buff['activate'], $tag);
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
        while(list($key,$buff) = each($ltaps)) {
                $healhp = $goodguy['maxhitpoints'] -
                        $goodguy['hitpoints'];
                if ($healhp < 0) $healhp = 0;
                if ($healhp == 0) {
                        $msg = $buff['effectnodmgmsg'];
                } else {
                        if ($healhp > $damage * $buff['lifetap'])
                                $healhp = $damage * $buff['lifetap'];
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
                $msg = str_replace("{badguy}",$badguy['name'], $msg);
                $msg = str_replace("{damage}",$healhp, $msg);
                if ($msg > ""){
                        output("`)$msg`n");
                        $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                }
        }
}

function process_dmgshield($dshield, $damage) {
        global $session,$badguy,$message;
        reset($dshield);
        while(list($key,$buff) = each($dshield)) {
                $realdamage = $damage * $buff['damageshield'];
                if ($realdamage < 0) $realdamage = 0;
                if ($realdamage > 0) {
                        $msg = $buff['effectmsg'];
                } else if ($realdamage == 0) {
                        $msg = $buff['effectnodmgmsg'];
                } else if ($realdamage < 0) {
                        $msg = $buff['effectfailmsg'];
                }
                $badguy[hitpoints] -= $realdamage;
                $msg = str_replace("{badguy}",$badguy['name'], $msg);
                $msg = str_replace("{damage}",$realdamage, $msg);
                if ($msg > ""){
                        output("`)$msg`n");
                        $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                }
        }
}
function expire_buffs() {
        global $goodguy,$badguy;
        reset($goodguy['bufflist']);
        reset($badguy['bufflist']);
        while (list($key, $buff) = each($goodguy['bufflist'])) {
                if ($buff['used']) {
                        $goodguy['bufflist'][$key]['used'] = 0;
                        $goodguy['bufflist'][$key]['rounds']--;
                        if ($goodguy['bufflist'][$key]['rounds'] <= 0) {
                                if ($buff['wearoff']) {
                                        $msg = $buff['wearoff'];
                                        $msg = str_replace("{badguy}", $badguy['name'], $msg);
                                        output("`)$msg`n");
                                        $message=$message.$goodguy[name].": \"`i$msg`i\"`n";
                                }
                                unset($goodguy['bufflist'][$key]);
                        }
                }
        }
}

addcommentary();
$cost=$session[user][level]*20;

if ($_GET[op]=="challenge"){
        if($_GET[name]=="" && $session[user][playerfights]>0 && ($session[user][level]>4 || $session[user][dragonkills]>0)){
                if (isset($_POST['search'])){
                        $search="%";
                        for ($x=0;$x<strlen($_POST['search']);$x++){
                                $search .= substr($_POST['search'],$x,1)."%";
                        }
                        $search="name LIKE '".$search."' AND ";
                }else{
                        $search="";
                }
                $ppp=25; // Players Per Page to display
                if (!$_GET[limit]){
                        $page=0;
                }else{
                        $page=(int)$_GET[limit];
                        addnav("Vorherige Seite","pvparena.php?op=challenge&limit=".($page-1)."");
                }
                $limit="".($page*$ppp).",".($ppp+1); // love PHP for this ;)
                pvpwarning();
                $days = getsetting("pvpimmunity", 5);
                $exp = getsetting("pvpminexp", 1500);
                output("`6Wen willst du zu einem Duell mit allen Fähigkeiten herausfordern? Die Arenagebühr kostet dich `^$cost `6Gold. Dein Gegner wird nicht unvorbereitet zustimmen.`nDu kannst heute noch `4".$session[user][playerfights]."`6 mal gegen einen anderen Krieger antreten.`n`n");
                output("<form action='pvparena.php?op=challenge' method='POST'>Nach Name suchen: <input name='search'><input type='submit' class='button' value='Suchen'></form>",true);
                addnav("","pvparena.php?op=challenge");
                  $sql = "SELECT name,alive,location,sex,level,laston,loggedin,login,pvpflag FROM accounts WHERE
                $search
                (locked=0) AND
                (age > $days OR dragonkills > 0 OR pk > 0 OR experience > $exp) AND
                (level >= ".($session[user][level]-1)." AND level <= ".($session[user][level]+2).") AND
                (acctid <> ".$session[user][acctid].")
                ORDER BY level DESC LIMIT $limit";
                  $result = db_query($sql) or die(db_error(LINK));
                if ($session['user']['pvpflag']=="5013-10-06 00:42:00"){
                        output("`n`&Du hast PvP-Immunität gekauft. Diese verfällt, wenn du jetzt angreifst!`0`n");
                }
                output("`4Wildkampf: `&Kampf ohne bes. Fähigkeiten. Verlierer stirbt!`n`n`0");
                if (db_num_rows($result)>$ppp) addnav("Nächste Seite","pvparena.php?op=challenge&limit=".($page+1)."");
                output("<table border='0' cellpadding='3' cellspacing='0'><tr><td>Name</td><td>Level</td><td>Status</td><td>Ops</td></tr>",true);
                for ($i=0;$i<db_num_rows($result);$i++){
                        $row = db_fetch_assoc($result);
                        if ($row[pvpflag]!="5013-10-06 00:42:00"){
                                  $biolink="bio.php?char=".rawurlencode($row[login])."&ret=".urlencode($_SERVER['REQUEST_URI']);
                                  addnav("", $biolink);
                                $loggedin=(date("U") - strtotime($row[laston]) < getsetting("LOGINTIMEOUT",900) && $row[loggedin]);
                                  output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>".($loggedin?"`#Online`0":"`3Offline`0")."</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvparena.php?op=challenge&typus=0&name=".rawurlencode($row[login])."'>Herausfordern</a> | <a href='pvparena.php?op=challenge&typus=1&name=".rawurlencode($row[login])."'>`4Wildkampf</a> ]</td></tr>",true);
                                #output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name]</td><td>$row[level]</td><td>".($loggedin?"`#Online`0":"`3Offline`0")."</td><td>[ <a href='$biolink'>Bio</a> | <a href='pvparena.php?op=challenge&name=".rawurlencode($row[login])."'>Herausfordern</a> ]</td></tr>",true);
                                addnav("","pvparena.php?op=challenge&typus=0&name=".rawurlencode($row[login]));
                                addnav("","pvparena.php?op=challenge&typus=1&name=".rawurlencode($row[login]));
                        }
                }
                output("</table>",true);
                  addnav("Zurück zur Arena","pvparena.php");
        }else if ($session[user][playerfights]<=0){
                output("`6Du kannst heute keinen weiteren Krieger mehr herausfordern.");
                addnav("Zurück zur Arena","pvparena.php");
        }else if ($session[user][level]<=4 && $session[user][dragonkills]==0){
                output("`6Der Arenawärter lacht sich darüber kaputt, dass ein so kleiner Schwächling wie du in der Arena kämpfen will. Vielleicht solltest du wirklich erst etwas mehr Kampferfahrung sammeln.");
                addnav("Zurück zur Arena","pvparena.php");
                //addnav("Zurück zum Dorf","village.php");
        }else{
                if ($session[user][gold]>=$cost){
                          $sql = "SELECT acctid,name,level,sex,hitpoints,maxhitpoints FROM accounts WHERE login='".$_GET[name]."'";
                          $result = db_query($sql) or die(db_error(LINK));
                        $row = db_fetch_assoc($result);
                        if (ac_check($row)){
                                output("`n`4Du kannst deine eigenen oder derart verwandte Spieler nicht zu einem Duell herausfordern!`0`n`n");
                        }else{
                                $sql = "SELECT * FROM pvp WHERE acctid2=".$session[user][acctid]." OR acctid1=$row[acctid] OR acctid2=$row[acctid]";
                                  $result = db_query($sql) or die(db_error(LINK));
                                if (db_num_rows($result)){
                                        output("`6Bei dieser Herausforderung ist dir jemand zuvor gekommen!");
                                }else{
                                        if ($_GET['typus']==0){
                                                systemmail($row[acctid],"`2Du wurdest herausgefordert!","`2".$session[user][name]."`2 (Level ".$session[user][level].") hat dich zu einem Duell in der Arena herausgefordert. Du kannst diese Herausforderung in der Arena annehmen oder ablehnen, solange sich dein Level nicht ändert.`nBereite dich gut vor und betrete die Arena erst dann!");
                                                $sql = "INSERT INTO pvp (acctid1,acctid2,name1,name2,lvl1,lvl2,hp1,maxhp1,att1,def1,weapon1,armor1,skill1,skillpoints1,bufflist1,turn,typus)
                                        VALUES (".$session[user][acctid].",$row[acctid],'".addslashes($session[user][name])."','".addslashes($row[name])."',".$session[user][level].",$row[level],".$session[user][hitpoints].",".$session[user][maxhitpoints].",".$session[user][attack].",".$session[user][defence].",'".addslashes($session[user][weapon])."','".addslashes($session[user][armor])."',".$session[user][skill].",".$session[user][skillpoints].",'".addslashes(gettexts('bufflist'))."',0,0)";
                                        }else{
                                                $dmg=$session[user][attack]-$session[user][weapondmg];
                                                $def=$session[user][defence]-$session[user][armordef];
                                                systemmail($row[acctid],"`2Du wurdest herausgefordert!","`2".$session[user][name]."`2 (Level ".$session[user][level].") hat dich zu einem Duell `4auf Leben und Tod und ohne Waffen `2in der Arena herausgefordert. Du kannst diese Herausforderung in der Arena annehmen oder ablehnen, solange sich dein Level nicht ändert.`nBereite dich gut vor und betrete die Arena erst dann!");
                                                $sql = "INSERT INTO pvp (acctid1,acctid2,name1,name2,lvl1,lvl2,hp1,maxhp1,att1,def1,weapon1,armor1,skill1,skillpoints1,bufflist1,turn,typus)
                                        VALUES (".$session[user][acctid].",$row[acctid],'".addslashes($session[user][name])."','".addslashes($row[name])."',".$session[user][level].",$row[level],".$session[user][maxhitpoints].",".$session[user][maxhitpoints].",".$dmg.",".$def.",'Fäuste','T-Shirt',0,0,'a:0:{}',0,1)";
                                        }
                                        db_query($sql) or die(db_error(LINK));
                                        if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
                                        output("`6Du hast`4 $row[name] `6zu einem Duell herausgefordert und wartest nun auf ".($row[sex]?"ihre":"seine")." Antwort. Du könntest $row[name]`6 den Kampf ");
                                        output(" schmackhafter machen, indem du ".($row[sex]?"ihr":"ihm")." die Arenagebühr von  `^".($row[level]*20)."`6 Gold überweist.`n");
                                        if ($session[user][dragonkills]<2) output("`n`n`i(Du kannst jetzt ganz normal weiterspielen. Wenn $row[name]`6 sich meldet, bekommst du eine Nachricht.)`i");
                                        $session[user][gold]-=$cost;
                                }
                        }
                          addnav("Zurück zur Arena","pvparena.php");
                }else{
                        output("`4Du hast nicht genug Gold dabei, um die Arenagebühr zu bezahlen. Mit rotem Kopf ziehst du ab.");
                        addnav("Zurück zur Arena","pvparena.php");
                }
        }
          addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]=="deny"){
        $sql = "DELETE FROM pvp WHERE acctid2=".$session[user][acctid];
        db_query($sql) or die(db_error(LINK));
        $sql="SELECT acctid,name FROM accounts WHERE acctid=$_GET[id]";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`6Beim Anblick deines Gegners $row[name] `6wird dir Angst und Bang. Mit ein paar sehr dürftigen Ausreden wie \"nicht genug Gold\" lehnst du die Herausforderung ab und verlässt schnell die Arena.");
        systemmail($row[acctid],"`2Herausforderung abgelehnt","`2".$session[user][name]."`2 hat deine Herausforderung abgelehnt. Vielleicht solltest du ".($session[user][sex]?"ihr":"ihm")." etwas für den Kampf anbieten - falls ".($session[user][sex]?"sie":"er")." dich besiegen kann.");
          addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]=="accept"){
        if($session[user][gold]<$cost){
                output("`4Du kannst dir die Arena Nutzungsgebühr von `^$cost`4 Gold nicht leisten.");
                  addnav("Zurück zum Dorf","village.php");
        }else if($session[user][playerfights]<=0){
                output("`4Du kannst heute nicht mehr gegen andere Krieger antreten.");
                  addnav("Zurück zum Dorf","village.php");
        }else{
                $sql="SELECT * FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
                  $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                if ($row[typus]==1){
                        $dmg=$session[user][attack]-$session[user][weapondmg];
                           $def=$session[user][defence]-$session[user][armordef];
                        $sql = "UPDATE pvp SET name2='".addslashes($session[user][name])."',hp2=".$session[user][maxhitpoints].",maxhp2=".$session[user][maxhitpoints].",att2=".$dmg.",def2=".$def.",weapon2='Fäuste',armor2='T-Shirt',skill2=0,skillpoints2=0,bufflist2='a:0:{}',turn=2 WHERE acctid2=".$session[user][acctid]."";
                 }else{
                         $sql = "UPDATE pvp SET name2='".addslashes($session[user][name])."',hp2=".$session[user][hitpoints].",maxhp2=".$session[user][maxhitpoints].",att2=".$session[user][attack].",def2=".$session[user][defence].",weapon2='".addslashes($session[user][weapon])."',armor2='".addslashes($session[user][armor])."',skill2=".$session[user][skill].",skillpoints2=".$session[user][skillpoints].",bufflist2='".addslashes(gettexts('bufflist'))."',turn=2 WHERE acctid2=".$session[user][acctid]."";
                }
                db_query($sql) or die(db_error(LINK));
                if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
                $sql="SELECT * FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
                  $result = db_query($sql) or die(db_error(LINK));
                $row = db_fetch_assoc($result);
                $session[user][gold]-=$cost;
                arenanav($row);
                stats($row);
        }
}else if ($_GET[op]=="back"){
        $sql="SELECT acctid,name FROM accounts WHERE acctid=$_GET[id]";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        output("`6Du bist es Leid, auf $row[name]`6 zu warten und ziehst deine Herausforderung zurück. Die Arenagebühr bekommst aber trotz langer Verhandlungen mit der Arena-Leitung nicht zurück.`n");
        $sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid];
        db_query($sql) or die(db_error(LINK));
        systemmail($row[acctid],"`2Herausforderung zurückgezogen","`2".$session[user][name]."`2 hat seine Herausforderung zurückgezogen.");
          addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]=="fight"){
        $sql="SELECT * FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        if ($row[turn]==1){
                 $badguy = array("acctid"=>$row[acctid2],"name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"bufflist"=>$row[bufflist2]);
                 $goodguy = array("name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"maxhitpoints"=>$row[maxhp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"skill"=>$row[skill1],"skillpoints"=>$row[skillpoints1],"bufflist"=>$row[bufflist1]);
        }
        if ($row[turn]==2){
                 $badguy = array("acctid"=>$row[acctid1],"name"=>$row[name1],"level"=>$row[lvl1],"hitpoints"=>$row[hp1],"attack"=>$row[att1],"defense"=>$row[def1],"weapon"=>$row[weapon1],"armor"=>$row[armor1],"bufflist"=>$row[bufflist1]);
                 $goodguy = array("name"=>$row[name2],"level"=>$row[lvl2],"hitpoints"=>$row[hp2],"maxhitpoints"=>$row[maxhp2],"attack"=>$row[att2],"defense"=>$row[def2],"weapon"=>$row[weapon2],"armor"=>$row[armor2],"skill"=>$row[skill2],"skillpoints"=>$row[skillpoints2],"bufflist"=>$row[bufflist2]);
        }
        stats($row);
        $adjustment=1;
        $goodguy[bufflist]=unserialize($goodguy[bufflist]);
        $badguy[bufflist]=unserialize($badguy[bufflist]);
        //FähigkeitenMOD by Angel **Start**
        if ($_GET[skill]=="SK"){
           //$sqls="SELECT * FROM skills WHERE id='".$session[user][skill]."'";
           //$results = db_query($sqls); //Befehl senden
           //$rows = db_fetch_assoc($results);
           if ($goodguy[skillpoints] >= $_GET[1]){
              //$creaturedmg = 0;
              $skillsql="SELECT * FROM `skills` WHERE `id` = '".$goodguy[skill]."'";
              $resultskill = db_query($skillsql) or die(db_error(LINK));
              $skill = db_fetch_assoc($resultskill);
              if ($_GET[1]==1){
                    if ($skill["name"]!="") $arr["name"] = $skill[color].$skill[force1];
                    if ($skill["startmsg1"]!="") $arr["startmsg"] = $skill[color].$skill[startmsg1];
                    if ($skill["rounds1"]!="") $arr["rounds"] = $skill[rounds1];
                    if ($skill["wearoff1"]!="") $arr["wearoff"] = $skill[color].$skill[wearoff1];
                    if ($skill["minioncount1"]!="") $arr["minioncount"] = $skill[minioncount1];
                    if ($skill["maxbadguydamage1"]!="") $arr["maxbadguydamage"] = $skill[maxbadguydamage1];
                    if ($skill["minbadguydamage1"]!="") $arr["minbadguydamage"] = $skill[minbadguydamage1];
                    if ($skill["badguyatkmod1"]!="") $arr["badguyatkmod"] = $skill[badguyatkmod1];
                    if ($skill["badguydefmod1"]!="") $arr["badguydefmod"] = $skill[badguydefmod1];
                    if ($skill["atkmod1"]!="") $arr["atkmod"] = $skill[atkmod1];
                    if ($skill["defmod1"]!="") $arr["defmod"] = $skill[defmod1];
                    if ($skill["reg1"]!="") $arr["reg2"] = $skill[reg1];
                    if ($skill["roundmsg1"]!="") $arr["roundmsg"] = $skill[roundmsg1];
                    if ($skill["badguydmgmod1"]!="") $arr["badguydmgmod"] = $skill[badguydmgmod1];
                    if ($skill["effectmsg1"]!="") $arr["effectmsg"] = $skill[effectmsg1];
                    if ($skill["effectnodmgmsg1"]!="") $arr["effectnodmgmsg"] = $skill[effectnodmgmsg1];
                    if ($skill["effectfailmsg1"]!="") $arr["effectfailmsg"] = $skill[effectfailmsg1];
                    if ($skill["lifetap1"]!="") $arr["lifetap"] = $skill[lifetap1];
                    if ($skill["damageshield1"]!="") $arr["damageshield"] = $skill[damageshield1];
                    if ($skill["activate1"]!="") $arr["activate"] = $skill[activate1];
                    $goodguy[bufflist]['sk1']=$arr;
                    $goodguy[skillpoints]--;
              }
              if ($_GET[1]==2){
                    if ($skill["name"]!="") $arr["name"] = $skill[color].$skill[force2];
                    if ($skill["startmsg2"]!="") $arr["startmsg"] = $skill[color].$skill[startmsg2];
                    if ($skill["rounds2"]!="") $arr["rounds"] = $skill[rounds2];
                    if ($skill["wearoff2"]!="") $arr["wearoff"] = $skill[color].$skill[wearoff2];
                    if ($skill["minioncount2"]!="") $arr["minioncount"] = $skill[minioncount2];
                    if ($skill["maxbadguydamage2"]!="") $arr["maxbadguydamage"] = $skill[maxbadguydamage2];
                    if ($skill["minbadguydamage2"]!="") $arr["minbadguydamage"] = $skill[minbadguydamage2];
                    if ($skill["badguyatkmod2"]!="") $arr["badguyatkmod"] = $skill[badguyatkmod2];
                    if ($skill["badguydefmod2"]!="") $arr["badguydefmod"] = $skill[badguydefmod2];
                    if ($skill["atkmod2"]!="") $arr["atkmod"] = $skill[atkmod2];
                    if ($skill["defmod2"]!="") $arr["defmod"] = $skill[defmod2];
                    if ($skill["reg2"]!="") $arr["reg2"] = $skill[reg2];
                    if ($skill["roundmsg2"]!="") $arr["roundmsg"] = $skill[roundmsg2];
                    if ($skill["badguydmgmod2"]!="") $arr["badguydmgmod"] = $skill[badguydmgmod2];
                    if ($skill["effectmsg2"]!="") $arr["effectmsg"] = $skill[effectmsg2];
                    if ($skill["effectnodmgmsg2"]!="") $arr["effectnodmgmsg"] = $skill[effectnodmgmsg2];
                    if ($skill["effectfailmsg2"]!="") $arr["effectfailmsg"] = $skill[effectfailmsg2];
                    if ($skill["lifetap2"]!="") $arr["lifetap"] = $skill[lifetap2];
                    if ($skill["damageshield2"]!="") $arr["damageshield"] = $skill[damageshield2];
                    if ($skill["activate2"]!="") $arr["activate"] = $skill[activate2];
                    $goodguy[bufflist]['sk2']=$arr;
                    $goodguy[skillpoints]-=2;
              }
              if ($_GET[1]==3){
                    if ($skill["name"]!="") $arr["name"] = $skill[color].$skill[force3];
                    if ($skill["startmsg3"]!="") $arr["startmsg"] = $skill[color].$skill[startmsg3];
                    if ($skill["rounds3"]!="") $arr["rounds"] = $skill[rounds3];
                    if ($skill["wearoff3"]!="") $arr["wearoff"] = $skill[color].$skill[wearoff3];
                    if ($skill["minioncount3"]!="") $arr["minioncount"] = $skill[minioncount3];
                    if ($skill["maxbadguydamage3"]!="") $arr["maxbadguydamage"] = $skill[maxbadguydamage3];
                    if ($skill["minbadguydamage3"]!="") $arr["minbadguydamage"] = $skill[minbadguydamage3];
                    if ($skill["badguyatkmod3"]!="") $arr["badguyatkmod"] = $skill[badguyatkmod3];
                    if ($skill["badguydefmod3"]!="") $arr["badguydefmod"] = $skill[badguydefmod3];
                    if ($skill["atkmod3"]!="") $arr["atkmod"] = $skill[atkmod3];
                    if ($skill["defmod3"]!="") $arr["defmod"] = $skill[defmod3];
                    if ($skill["reg3"]!="") $arr["reg2"] = $skill[reg3];
                    if ($skill["roundmsg3"]!="") $arr["roundmsg"] = $skill[roundmsg3];
                    if ($skill["badguydmgmod3"]!="") $arr["badguydmgmod"] = $skill[badguydmgmod3];
                    if ($skill["effectmsg3"]!="") $arr["effectmsg"] = $skill[effectmsg3];
                    if ($skill["effectnodmgmsg3"]!="") $arr["effectnodmgmsg"] = $skill[effectnodmgmsg3];
                    if ($skill["effectfailmsg3"]!="") $arr["effectfailmsg"] = $skill[effectfailmsg3];
                    if ($skill["lifetap3"]!="") $arr["lifetap"] = $skill[lifetap3];
                    if ($skill["damageshield3"]!="") $arr["damageshield"] = $skill[damageshield3];
                    if ($skill["activate3"]!="") $arr["activate"] = $skill[activate3];
                    $goodguy[bufflist]['sk3']=$arr;
                    $goodguy[skillpoints]-=3;
              }
              if ($_GET[1]==5){
                    if ($skill["name"]!="") $arr["name"] = $skill[color].$skill[force4];
                    if ($skill["startmsg4"]!="") $arr["startmsg"] = $skill[color].$skill[startmsg4];
                    if ($skill["rounds4"]!="") $arr["rounds"] = $skill[rounds4];
                    if ($skill["wearoff4"]!="") $arr["wearoff"] = $skill[color].$skill[wearoff4];
                    if ($skill["minioncount4"]!="") $arr["minioncount"] = $skill[minioncount4];
                    if ($skill["maxbadguydamage4"]!="") $arr["maxbadguydamage"] = $skill[maxbadguydamage4];
                    if ($skill["minbadguydamage4"]!="") $arr["minbadguydamage"] = $skill[minbadguydamage4];
                    if ($skill["badguyatkmod4"]!="") $arr["badguyatkmod"] = $skill[badguyatkmod4];
                    if ($skill["badguydefmod4"]!="") $arr["badguydefmod"] = $skill[badguydefmod4];
                    if ($skill["atkmod4"]!="") $arr["atkmod"] = $skill[atkmod4];
                    if ($skill["defmod4"]!="") $arr["defmod"] = $skill[defmod4];
                    if ($skill["reg4"]!="") $arr["reg2"] = $skill[reg4];
                    if ($skill["roundmsg4"]!="") $arr["roundmsg"] = $skill[roundmsg4];
                    if ($skill["badguydmgmod4"]!="") $arr["badguydmgmod"] = $skill[badguydmgmod4];
                    if ($skill["effectmsg4"]!="") $arr["effectmsg"] = $skill[effectmsg4];
                    if ($skill["effectnodmgmsg4"]!="") $arr["effectnodmgmsg"] = $skill[effectnodmgmsg4];
                    if ($skill["effectfailmsg4"]!="") $arr["effectfailmsg"] = $skill[effectfailmsg4];
                    if ($skill["lifetap4"]!="") $arr["lifetap"] = $skill[lifetap4];
                    if ($skill["damageshield4"]!="") $arr["damageshield"] = $skill[damageshield4];
                    if ($skill["activate4"]!="") $arr["activate"] = $skill[activate4];
                    $goodguy[bufflist]['sk4']=$arr;
                    $goodguy[skillpoints]-=5;
              }
           }
        }
       //ENDE SKILLMOD BY ANGEL */
       /*
        if ($_GET[skill]=="MP"){
                if ($goodguy[magicuses] >= $_GET[l]){
                        $creaturedmg = 0;
                        switch($_GET[l]){
                        case 1:
                                $goodguy[bufflist]['mp1'] = array(
                                        "startmsg"=>"`n`^Du fängst an zu regenerieren!`n`n",
                                        "name"=>"`%Regeneration",
                                        "rounds"=>5,
                                        "wearoff"=>"Deine Regeneration hat aufgehört",
                                        "regen"=>$goodguy['level'],
                                        "effectmsg"=>"Du regenerierst um {damage} Punkte.",
                                        "effectnodmgmsg"=>"Du bist völlig gesund.",
                                        "activate"=>"roundstart");
                                break;
                        case 2:
                                $goodguy[bufflist]['mp2'] = array(
                                        "startmsg"=>"`n`^{badguy}`% wird von einer Klaue aus Erde gepackt und auf den Boden geschleudert!`n`n",
                                        "name"=>"`%Erdenfaust",
                                        "rounds"=>5,
                                        "wearoff"=>"Die erdene Faust zerfällt zu staub.",
                                        "minioncount"=>1,
                                        "effectmsg"=>"`) Eine gewaltige Faust aus Erde trifft {badguy} `)mit `^{damage}`) Schadenspunkten.",
                                        "minbadguydamage"=>1,
                                        "maxbadguydamage"=>$goodguy['level']*3,
                                        "activate"=>"roundstart"
                                        );
                                break;
                        case 3:
                                $goodguy[bufflist]['mp3'] = array(
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
                                $goodguy[bufflist]['mp5'] = array(
                                        "startmsg"=>"`n`^Deine Haut glitzert, als du dir eine Aura aus Blitzen zulegst`n`n",
                                        "name"=>"`%Blitzaura",
                                        "rounds"=>5,
                                        "wearoff"=>"Mit einem Zischen wird deine Haut wieder normal.",
                                        "damageshield"=>2,
                                        "effectmsg"=>"{badguy}`) wird von einem Blitzbogen aus deiner Haut mit `^{damage}`) Schadenspunkten zurückgeworfen.",
                                        "effectnodmg"=>"{badguy} `)ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt.",
                                        "effectfailmsg"=>"{badguy}`) ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt.",
                                        "activate"=>"offense,defense"
                                );
                                break;
                        }
                        $goodguy[magicuses]-=(int)$_GET[l];
                }else{
                        $goodguy[bufflist]['mp0'] = array(
                                "startmsg"=>"`nDu legst deine Stirn in Falten und beschwörst die Elemente.  Eine kleine Flamme erscheint. {badguy} zündet sich eine Zigarette daran an, dankt dir und stürzt sich wieder auf dich.`n`n",
                                "rounds"=>1,
                                "activate"=>"roundstart"
                        );
                }
        }
        if ($_GET[skill]=="DA"){
                if ($goodguy[darkartuses] >= $_GET[l]){
                        $creaturedmg = 0;
                        switch($_GET[l]){
                        case 1:
                                $goodguy[bufflist]['da1']=array(
                                        "startmsg"=>"`n`\$Du rufst die Geister der Toten und skelettartige Hände zerren an {badguy} aus den Tiefen ihrer Gräber.`n`n",
                                        "name"=>"`\$Skelettdiener",
                                        "rounds"=>5,
                                        "wearoff"=>"Deine Skelettdiener zerbröckeln zu staub.",
                                        "minioncount"=>round($goodguy[level]/3)+1,
                                        "maxbadguydamage"=>round($goodguy[level]/2,0)+1,
                                        "effectmsg"=>"`)Ein untoter Diener trifft {badguy} mit `^{damage}`) Schadenspunkten.",
                                        "effectnodmgmsg"=>"`)Ein untoter Diener versucht {badguy} zu treffen, aber `\$TRIFFT NICHT`)!",
                                        "activate"=>"roundstart"
                                        );
                                break;
                        case 2:
                                $goodguy[bufflist]['da2']=array(
                                        "startmsg"=>"`n`\$Du holst eine winzige Puppe die aussieht wie {badguy} hervor`n`n",
                                        "effectmsg"=>"Du stößt eine Nadel in die {badguy}-Puppe und machst damit `^{damage}`) Schadenspunkte!",
                                        "minioncount"=>1,
                                        "maxbadguydamage"=>round($goodguy[attack]*3,0),
                                        "minbadguydamage"=>round($goodguy[attack]*1.5,0),
                                        "activate"=>"roundstart"
                                        );
                                break;
                        case 3:
                                $goodguy[bufflist]['da3']=array(
                                        "startmsg"=>"`n`\$Du sprichst einen Fluch auf die Ahnen von {badguy}.`n`n",
                                        "name"=>"`\$Geist verfluchen",
                                        "rounds"=>5,
                                        "wearoff"=>"Dein Fluch ist gewichen.",
                                        "badguydmgmod"=>0.5,
                                        "roundmsg"=>"{badguy} taumelt unter der Gewalt deines Fluchs und macht nur halben Schaden.",
                                        "activate"=>"defense"
                                        );
                                break;
                        case 5:
                                $goodguy[bufflist]['da5']=array(
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
                        $goodguy[darkartuses]-=(int)$_GET[l];
                }else{
                        $goodguy[bufflist]['da0'] = array(
                                "startmsg"=>"`nErschöpft versuchst du deine dunkelste Magie: einen schlechten Witz.  {badguy} schaut dich nachdenklich eine Minute lang an. Endlich versteht er den Witz und stürzt sich lachend wieder auf dich.`n`n",
                                "rounds"=>1,
                                "activate"=>"roundstart"
                                );
                }
        }
        if ($_GET[skill]=="TS"){
                if ($goodguy[thieveryuses] >= $_GET[l]){
                        $creaturedmg = 0;
                        switch($_GET[l]){
                        case 1:
                                $goodguy[bufflist]['ts1']=array(
                                        "startmsg"=>"`n`^Du gibst deinem Gegner einen schlimmen Namen und bringst {badguy} zum Weinen.`n`n",
                                        "name"=>"`^Beleidigung",
                                        "rounds"=>5,
                                        "wearoff"=>"Dein Gegner putzt sich die Nase und hört auf zu weinen.",
                                        "roundmsg"=>"{badguy} ist deprimiert und kann nicht so gut angreifen.",
                                        "badguyatkmod"=>0.5,
                                        "activate"=>"defense"
                                        );
                                break;
                        case 2:
                                $goodguy[bufflist]['ts2']=array(
                                        "startmsg"=>"`n`^Du reibst Gift auf dein(e/n) ".$goodguy[weapon].".`n`n",
                                        "name"=>"`^Vergiftete Waffe",
                                        "rounds"=>5,
                                        "wearoff"=>"Das Blut deines Gegners hat das Gift von deiner Waffe gewaschen.",
                                        "atkmod"=>2,
                                        "roundmsg"=>"Dein Angriffswert vervielfacht sich!",
                                        "activate"=>"offense"
                                        );
                                break;
                        case 3:
                                $goodguy[bufflist]['ts3'] = array(
                                        "startmsg"=>"`n`^Mit dem Geschick eines erfahrenen Diebs scheinst du zu verschwinden und kannst {badguy} aus einer günstigeren und sichereren Position angreifen.`n`n",
                                        "name"=>"`^Versteckter Angriff",
                                        "rounds"=>5,
                                        "wearoff"=>"Dein Opfer hat dich gefunden.",
                                        "roundmsg"=>"{badguy} kann dich nicht finden.",
                                        "badguyatkmod"=>0,
                                        "activate"=>"defense"
                                        );
                                break;
                        case 5:
                                $goodguy[bufflist]['ts5']=array(
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
                        $goodguy[thieveryuses]-=(int)$_GET[l];
                }else{
                        $goodguy[bufflist]['ts0'] = array(
                                "startmsg"=>"`nDu versuchst, {badguy} anzugreifen, indem du deine besten Diebeskünste in die Praxis umsetzt - aber du stolperst über deine eigenen Füsse.`n`n",
                                "rounds"=>1,
                                "activate"=>"roundstart"
                                );
                }
        }
        */
        if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0) {
                output ("`\$`c`b~ ~ ~ Kampf ~ ~ ~`b`c`0");
                output("`@Du hast den Gegner `^$badguy[name]`@ entdeckt, der sich mit seiner Waffe `%$badguy[weapon]`@");
                // Let's display what buffs the opponent is using - oh yeah
                $buffs="";
                $disp[bufflist]=$badguy[bufflist];
                reset($disp[bufflist]);
                while (list($key,$val)=each($disp[bufflist])){
                        $buffs.=" `@und `#$val[name] `7($val[rounds] Runden)";
                }
                if (count($disp[bufflist])==0){
                        $buffs.=appoencode("",true);
                }
                output("$buffs");
                output(" `@auf dich stürzt!`0`n`n");
                output("`2Level: `6$badguy[level]`0`n");
                output("`2`bBeginn der Runde:`b`n");
                output("`2$badguy[name]`2's Lebenspunkte: `6$badguy[hitpoints]`0`n");
                output("`2DEINE Lebenspunkte: `6$goodguy[hitpoints]`0`n");
        }
        reset($goodguy[bufflist]);
        while (list($key,$buff)=each($goodguy['bufflist'])){
                $buff[used]=0;
        }
        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0){
                $buffset = activate_buffs("roundstart");
                $creaturedefmod=$buffset['badguydefmod'];
                $creatureatkmod=$buffset['badguyatkmod'];
                $atkmod=$buffset['atkmod'];
                $defmod=$buffset['defmod'];
        }
        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0){
                $adjustedcreaturedefense = $badguy[defense];
                $creatureattack = $badguy[attack]*$creatureatkmod;
                $adjustedselfdefense = ($goodguy[defense] * $adjustment * $defmod);
                while($creaturedmg==0 && $selfdmg==0){
                        $atk = $goodguy[attack]*$atkmod;
                        if (e_rand(1,20)==1) $atk*=3;
                        $patkroll = e_rand(0,$atk);
                        $catkroll = e_rand(0,$adjustedcreaturedefense);
                        $creaturedmg = 0-(int)($catkroll - $patkroll);
                        if ($creaturedmg<0) {
                                $creaturedmg = (int)($creaturedmg/2);
                                $creaturedmg = round($buffset[badguydmgmod]*$creaturedmg,0);
                        }
                        if ($creaturedmg > 0) {
                                $creaturedmg = round($buffset[dmgmod]*$creaturedmg,0);
                        }
                        $pdefroll = e_rand(0,$adjustedselfdefense);
                        $catkroll = e_rand(0,$creatureattack);
                        $selfdmg = 0-(int)($pdefroll - $catkroll);
                        if ($selfdmg<0) {
                                $selfdmg=(int)($selfdmg/2);
                                $selfdmg = round($selfdmg*$buffset[dmgmod], 0);
                        }
                        if ($selfdmg > 0) {
                                $selfdmg = round($selfdmg*$buffset[badguydmgmod], 0);
                        }
                }
        }
        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0){
                $buffset = activate_buffs("offense");
                if ($atk > $goodguy[attack]) {
                        if ($atk > $goodguy[attack]*3){
                                if ($atk>$godguy[attack]*4){
                                        output("`&`bDu holst zu einem <font size='+1'>MEGA</font> Powerschlag aus!!!`b`n",true);
                                }else{
                                        output("`&`bDu holst zu einem DOPPELTEN Powerschlag aus!!!`b`n");
                                }
                        }else{
                                if ($atk>$goodguy[attack]*2){
                                        output("`&`bDu holst zu einem Powerschlag aus!!!`b`0`n");
                                }elseif ($atk>$goodguy['attack']*1.25){
                                        output("`7`bDu holst zu einem kleinen Powerschlag aus!`b`0`n");
                                }
                        }
                }
                if ($creaturedmg==0){
                        output("`4Du versuchst `^$badguy[name]`4 zu treffen, aber `\$TRIFFST NICHT!`n");
                        $message=$message."`^$goodguy[name]`4 versucht dich zu treffen, aber `\$TRIFFT NICHT!`n";
                        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_dmgshield($buffset[dmgshield], 0);
                        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_lifetaps($buffset[lifetap], 0);
                }else if ($creaturedmg<0){
                        output("`4Du versuchst `^$badguy[name]`4 zu treffen, aber der `\$ABWEHRSCHLAG `4trifft dich mit `\$".(0-$creaturedmg)."`4 Schadenspunkten!`n");
                        $message=$message."`^$goodguy[name]`4 versucht dich zu treffen, aber dein `\$ABWEHRSCHLAG`4 trifft mit `\$".(0-$creaturedmg)."`4 Schadenspunkten!`n";
                        $badguy['diddamage']=1;
                        $goodguy[hitpoints]+=$creaturedmg;
                        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_dmgshield($buffset[dmgshield],-$creaturedmg);
                        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_lifetaps($buffset[lifetap],$creaturedmg);
                }else{
                        output("`4Du triffst `^$badguy[creaturename]`4 mit `^$creaturedmg`4 Schadenspunkten!`n");
                        $message=$message."`^$goodguy[name]`4 trifft dich mit `^$creaturedmg`4 Schadenspunkten!`n";
                        $badguy[hitpoints]-=$creaturedmg;
                        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_dmgshield($buffset[dmgshield],-$creaturedmg);
                        if ($badguy[hitpoints]>0 && $goodguy[hitpoints]>0) process_lifetaps($buffset[lifetap],$creaturedmg);
                }
                // from hardest punch mod -- remove if not installed!!
                if ($creaturedmg>$session[user][punch]){
                        $session[user][punch]=$creaturedmg;
                        output("`@`b`c--- DAS WAR DEIN BISHER HÄRTESTER SCHLAG! ---`c`b`n");
                }
                // end hardest punch
        }
        if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0) $buffset = activate_buffs("defense");
        expire_buffs();
        if ($goodguy[hitpoints]>0 && $badguy[hitpoints]>0){
                output("`2`bEnde der Runde:`b`n");
                output("`2$badguy[name]`2's Lebenspunkte: `6$badguy[hitpoints]`0`n");
                output("`2DEINE Lebenspunkte: `6".$goodguy[hitpoints]."`0`n");
        }

        $goodguy[bufflist]=serialize($goodguy[bufflist]);
        $badguy[bufflist]=serialize($badguy[bufflist]);
        if ($row[acctid1]){ // battle still in DB? Result of round:
                if ($badguy[hitpoints]>0 and $goodguy[hitpoints]>0){
                        $message=addslashes($message);
                        if ($row[turn]==1) $sql = "UPDATE pvp SET hp1=$goodguy[hitpoints],hp2=$badguy[hitpoints],skillpoints1=$goodguy[skillpoints],bufflist1='".addslashes($goodguy[bufflist])."',lastmsg='$message',turn=2 WHERE acctid1=".$session[user][acctid]."";
                        if ($row[turn]==2) $sql = "UPDATE pvp SET hp1=$badguy[hitpoints],hp2=$goodguy[hitpoints],skillpoints2=$goodguy[skillpoints],bufflist2='".addslashes($goodguy[bufflist])."',lastmsg='$message',turn=1 WHERE acctid2=".$session[user][acctid]."";
                        db_query($sql) or die(db_error(LINK));
                        if (db_affected_rows(LINK)<=0) redirect("pvparena.php");
                        output("`n`n`2Du erwartest den Zug deines Gegners.");
                        addnav("Aktualisieren","pvparena.php");
                }else if ($badguy[hitpoints]<=0){
                        $win=$badguy[level]*20+$goodguy[level]*20;
                        $exp=$badguy[level]*10-(abs($goodguy[level]-$badguy[level])*10);
                        if ($badguy[level]<=$goodguy[level]){
                                $session[user][battlepoints]+=2;
                        }else{
                                $session[user][battlepoints]+=3*($badguy[level]-$goodguy[level]);
                        }
                        if ($row['typus']==0){
                            output("`n`&Kurz vor deinem finalen Todesstoß beendet der Arenawärter euren Kampf und erklärt dich zum Sieger!`0`n");
                        }else{
                                output("`n`&Mit einem finalen Schlag beendest du den Kampf und siehst deinen Gegner tot vor dir im Staub liegen. Der Sieg ist deiner!`0`n");
                                $exp2=$exp*5;
                        }
                        output("`b`\$Du hast $badguy[name] `\$besiegt!`0`b`n");
                        output("`#Du bekommst das Preisgeld in Höhe von `^$win`# Gold und deinen gerechten Lohn an Arenakampfpunkten!`n");
                        $session['user']['donation']+=1;
                        output("Du bekommst insgesamt `^$exp`# Erfahrungspunkte!`n`0");
                        if ($row[typus]==1){
                                output("Weil dies ein Kampf auf Leben und Tod war erhältst du diese Erfahrungspunkte fünfach!!`n");
                                $exp=$exp2;
                        }
                        $session['user']['gold']+=$win;
                        $session['user']['playerfights']--;
                        $session['user']['experience']+=$exp;
                        $exp = round(getsetting("pvpdeflose",5)*10,0);
                        if ($row[typus]==1) $sql = "UPDATE accounts SET gravefights=0,alive=0,hitpoints=0,charm=charm-1,experience=experience-$exp,playerfights=playerfights-1 WHERE acctid=$badguy[acctid]";
                        if ($row[typus]==0) $sql = "UPDATE accounts SET charm=charm-1,experience=experience-$exp,playerfights=playerfights-1 WHERE acctid=$badguy[acctid]";
                        db_query($sql);
                        if ($row[typus]==1){
                                $mailmessage = "`^$goodguy[name]`2 hat dich mit %p `^".$goodguy['weapon']."`2 in der Arena besiegt!"
                                ." `n`n%o hatte am Ende noch `^".$goodguy['hitpoints']."`2 Lebenspunkte übrig."
                                ." `n`nDu hast `\$$exp`2 deiner Erfahrungspunkte verloren."
                                ." `n`nWeil das ein Wildkampf war:"
                                ." `n`nDu bist tot."
                                ." `n`nDu hast keine Grabkämpfe mehr.";
                        }else{
                                $mailmessage = "`^$goodguy[name]`2 hat dich mit %p `^".$goodguy['weapon']."`2 in der Arena besiegt!"
                                ." `n`n%o hatte am Ende noch `^".$goodguy['hitpoints']."`2 Lebenspunkte übrig."
                                ." `n`nDu hast `\$$exp`2 deiner Erfahrungspunkte verloren.";
                        }
                         $mailmessage = str_replace("%p",($session['user']['sex']?"ihre(r/m)":"seine(r/m)"),$mailmessage);
                         $mailmessage = str_replace("%o",($session['user']['sex']?"Sie":"Er"),$mailmessage);
                         systemmail($badguy['acctid'],"`2Du wurdest in der Arena besiegt",$mailmessage);
                        addnews("`\$$goodguy[name] `6besiegt `\$$badguy[name]`6 bei einem Duell in der `8Arena`6!");
                        $sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid];
                        db_query($sql) or die(db_error(LINK));
                        redirect("news.php");
                }else if ($goodguy[hitpoints]<=0){
                        $exp=$badguy[level]*10-(abs($goodguy[level]-$badguy[level])*10);
                        $win=$badguy[level]*20+$goodguy[level]*20;
                        if ($badguy[level]>=$goodguy[level]){
                                $points=2;
                        }else{
                                $points=3*($goodguy[level]-$badguy[level]);
                        }
                        $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
                        $result = db_query($sql) or die(db_error(LINK));
                        $taunt = db_fetch_assoc($result);
                        $taunt = str_replace("%s",($session[user][sex]?"sie":"ihn"),$taunt[taunt]);
                        $taunt = str_replace("%o",($session[user][sex]?"sie":"er"),$taunt);
                        $taunt = str_replace("%p",($session[user][sex]?"ihr(e/n)":"sein(e/n)"),$taunt);
                        $taunt = str_replace("%x",($session[user][weapon]),$taunt);
                        $taunt = str_replace("%X",$badguy[weapon],$taunt);
                        $taunt = str_replace("%W",$badguy[name],$taunt);
                        $taunt = str_replace("%w",$session[user][name],$taunt);
                        $badguy[acctid]=(int)$badguy[acctid];
                        $badguy[creaturegold]=(int)$badguy[creaturegold];
                        systemmail($badguy[acctid],"`2 du warst in der Arena erfolgreich! ","`^".$session[user][name]."`2 hat in der Arena verloren!`n`nDafür hast du `^$exp`2 Erfahrungspunkte und `^$win`2 Gold erhalten!");
                        $sql = "UPDATE accounts SET gold=gold+$win,experience=experience+$exp,donation=donation+1,playerfights=playerfights-1,battlepoints=battlepoints+$points WHERE acctid=$badguy[acctid]";
                        db_query($sql);
                        $exp = round(getsetting("pvpdeflose",5)*10,0);
                        $session[user][experience]-=$exp;
                        $session['user']['playerfights']--;
                        output("`n`b`&Du wurdest von `%$badguy[name]`& besiegt!!!`n");
                        output("$taunt");
                        if ($row['typus']==1){
                                output("`n`4Du bist tot!");
                                output("`n`4Da du das Risiko bewusst auf dich genommen hast gestattet dir Ramius heute keine Grabkämpfe mehr!");
                                $session[user][alive]=0;
                                $session[user][hitpoints]=0;
                                $session[user][gravefights]=0;
                                addnav("News","news.php");
                        }
                        output("`n`4Du hast `^$exp Erfahrungspunkte verloren!`n");
                        if ($session[user][charm]>0) $session[user][charm]--;
                        addnews("`\$$badguy[name]`6 besiegt `\$$goodguy[name]`6 bei einem Duell in der `8Arena`6!");
                        $sql = "DELETE FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid];
                        db_query($sql) or die(db_error(LINK));
                        redirect("news.php");
                }
        }else{
                output("`6Euer Kampf wurde vorzeitig beendet. Die Nutzungsgebühr kommt dem Ausbau der Arena zugute.");
        }
          if ($session[user][alive]!=0) addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]=="del"){
        $sql="DELETE FROM pvp WHERE acctid1=$_GET[kid1] AND acctid2=$_GET[kid2]";
        db_query($sql);
        output("Der Arenarichter beendet einen langweiligen Kampf.");
        systemmail($_GET[kid1],"`2Dein Arenakampf wurde beendet!","`@Dein Kampf in der Arena wurde vom Kampfrichter beendet.");
        systemmail($_GET[kid2],"`2Dein Arenakampf wurde beendet!","`@Dein Kampf in der Arena wurde vom Kampfrichter beendet.");

        addnav("Zur Arena","pvparena.php");
        addnav("Zurück zum Dorf","village.php");
}else if ($_GET[op]==""){
        $sql="SELECT * FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
          $result = db_query($sql) or die(db_error(LINK));
        $row = db_fetch_assoc($result);
        $text=0;
        if($row[acctid1]==$session[user][acctid] && $row[turn]==0){
                 $text=1;
                output("`6Da du noch eine Herausforderung mit `&$row[name2] `6offen hast, machst du dich auf in Richtung Arena, um nach deinem Gegner Ausschau zu halten. Doch der scheint nirgendwo in Sicht zu sein.`n");
                addnav("Herausforderung zurücknehmen","pvparena.php?op=back&id=$row[acctid2]");
                if (@file_exists("battlearena.php")) addnav("Gladiator herausfordern","battlearena.php"); // ONE arena for TWO things - if installed ;)
                 addnav("Zurück zum Dorf","village.php");
                stats($row);
        }else if($row[acctid1]==$session[user][acctid] && $row[turn]==1){
                stats($row);
                arenanav($row);
        }else if($row[acctid1]==$session[user][acctid] && $row[turn]==2){
                stats($row);
                output("`6Dein Gegner `&$row[name2]`6 hat den nächsten Zug noch nicht gemacht.`n`n");
                $text=1;
                if (@file_exists("battlearena.php")) addnav("Gladiator herausfordern","battlearena.php");
                 addnav("Zurück zum Dorf","village.php");
        }else if($row[acctid2]==$session[user][acctid] && $row[turn]==0){
                output("`6Du wurdest von `&$row[name1] `6herausgefordert. Wenn du die Herausforderung annimmst, musst du `^$cost`6 Gold Arenagebühr bezahlen.`n");
                addnav("Du wurdest von $row[name1] herausgefordert");
                addnav("Herausforderung annehmen","pvparena.php?op=accept");
                addnav("Feige ablehnen","pvparena.php?op=deny&id=$row[acctid1]");
        }else if($row[acctid2]==$session[user][acctid] && $row[turn]==1){
                stats($row);
                output("`6Dein Gegner `&$row[name1]`6 hat den nächsten Zug noch nicht gemacht.`n`n");
                $text=1;
                if (@file_exists("battlearena.php")) addnav("Gladiator herausfordern","battlearena.php");
                 addnav("Zurück zum Dorf","village.php");
        }else if($row[acctid2]==$session[user][acctid] && $row[turn]==2){
                stats($row);
                arenanav($row);
        }else{
                $text=1;
                if ($session[user][alive]!=0) addnav("Spieler herausfordern","pvparena.php?op=challenge");
                if ($session[user][alive]!=0) addnav("Gladiator herausfordern","battlearena.php");
                 if ($session[user][alive]!=0) addnav("Zurück zum Dorf","village.php");
        }
        if($text==1){
                checkday();
                  if ($session[user][alive]!=0) addnav("Aktualisieren","pvparena.php");
                  if ($session[user][alive]==0) addnav("News","news.php");
                output("`6Du betrittst die große Kampfarena in der Nähe des Trainingslagers. Hier kämpfen einge der Krieger gegeneinander und üben sich in ihren besonderen Fertigkeiten, ");
                output("um herauszufinden, wer von ihnen der Beste ist. Es geht um die Ehre - und um eine gute Platzierung in der Kämpferliga.`n");
                $sql="SELECT * FROM pvp WHERE acctid1 AND acctid2 AND turn>0";
                  $result = db_query($sql) or die(db_error(LINK));
                if (db_num_rows($result)){
                        output(" Du beobachtest das bunte Treiben auf dem Platz eine Weile.`nFolgende Krieger kämpfen gerade gegeneinander:`n`n<table border='0' cellpadding='3' cellspacing='0'><tr><td align='center'>`bHerausforderer`b</td><td align='center'>`bVerteidiger`b</td><td align='center'>`bStand (LP)`b</td><td align='center'>`bKampfart`b</td>".($session[user][superuser]>2?"<td>Ops</td>":"")."</tr>",true);
                        for ($i=0;$i<db_num_rows($result);$i++){
                                $row = db_fetch_assoc($result);
                                if ($row[typus]==0) $typus='Normale Herausforderung';
                                if ($row[typus]==1) $typus='`4Wildkampf`0';
                                output("<tr class='".($i%2?"trlight":"trdark")."'><td>$row[name1]</td><td>$row[name2]</td><td>$row[hp1] : $row[hp2]</td><td>$typus</td>".($session[user][superuser]>2?"<td><a href='pvparena.php?op=del&kid1=$row[acctid1]&kid2=$row[acctid2]'>Löschen</a></td>":"")."</tr>",true);
                                if ($session[user][superuser]>2) addnav("","pvparena.php?op=del&kid1=$row[acctid1]&kid2=$row[acctid2]");
                        }
                        output("</table>`n`n",true);
                }else{
                        output("`n`n`6Im Moment laufen keine Kämpfe zwischen den Helden dieser Welt.`n`n");
                }
                viewcommentary("pvparena","Rufen:",10,"ruft");
                output("`n`n");
                $result2=db_query("SELECT newsdate,newstext FROM news WHERE newstext LIKE '%Arena`6!' ORDER BY newsid DESC LIMIT 10");
                for ($i=0;$i<db_num_rows($result2);$i++){
                          $row2 = db_fetch_assoc($result2);
                        output("`n`0$row2[newsdate]: $row2[newstext]");
                }
        }
}

page_footer();

// this is not the end ;)
?> 