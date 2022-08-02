<?php
/*
 * Major MAJOR revamps by JT from logd.dragoncat.net  Frankly I threw out my code and used his.
 *
 */

if ($_GET[auto]=="full"){
        $count=100;
}else if ($_GET[auto]=="five"){
        $count=5;
}else{
        $count=1;
}

function activate_buffs($tag) {
        global $session, $badguy;
        reset($session['bufflist']);
        $result = array();
        $result['invulnerable'] = 0;
        $result['dmgmod'] = 1;
        $result['badguydmgmod'] = 1;
        $result['atkmod'] = 1;
        $result['badguyatkmod'] = 1;
        $result['defmod'] = 1;
        $result['badguydefmod'] = 1;
        $result['lifetap'] = array();
        $result['dmgshield'] = array();

        while(list($key,$buff) = each($session['bufflist'])) {
                if (isset($buff['startmsg'])) {
                        $msg = $buff['startmsg'];
                        $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                        output("`%$msg`0");
                        unset($session['bufflist'][$key]['startmsg']);
                }
                $activate = strpos($buff['activate'], $tag);
                if ($activate !== false) $activate = true; // handle strpos == 0;

                // If this should activate now and it hasn't already activated,
                // do the round message and mark it.
                if ($activate && !$buff['used']) {
                        // mark it used.
                        $session['bufflist'][$key]['used'] = 1;
                        // if it has a 'round message', run it.
                        if (isset($buff['roundmsg'])) {
                                $msg = $buff['roundmsg'];
                                $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                                output("`)$msg`0`n");
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
                        $hpdiff = $session['user']['maxhitpoints'] -
                        $session['user']['hitpoints'];
                        // Don't regen if we are above max hp
                        if ($hpdiff < 0) $hpdiff = 0;
                        if ($hpdiff < $hptoregen) $hptoregen = $hpdiff;
                        $session['user']['hitpoints'] += $hptoregen;
                        // Now, take abs value just incase this was a damaging buff
                        $hptoregen = abs($hptoregen);
                        if ($hptoregen == 0) $msg = $buff['effectnodmgmsg'];
                        else $msg = $buff['effectmsg'];
                        $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                        $msg = str_replace("{damage}", $hptoregen, $msg);
                        output("`)$msg`0`n");
                }
                //Funktion, um Regeneration variabler zu machen - der Wert, der über "reg2" übermittelt wird, ist somit eigentlich irrelevant.
                //"regen" kann nicht überschrieben werden, da darüber auch z.B. das Einhorn funktioniert.
                if (isset($buff['reg2']) && $activate) {
                        $hptoregen = e_rand($session['user']['level'],$session['user']['maxhitpoints']*0.2);
                        $hpdiff = $session['user']['maxhitpoints'] -
                        $session['user']['hitpoints'];
                        if ($hpdiff < 0) $hpdiff = 0;
                        if ($hpdiff < $hptoregen) $hptoregen = $hpdiff;
                        $session['user']['hitpoints'] += $hptoregen;
                        $hptoregen = abs($hptoregen);
                        if ($hptoregen == 0) $msg = $buff['effectnodmgmsg'];
                        else $msg = $buff['effectmsg'];
                        $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                        $msg = str_replace("{damage}", $hptoregen, $msg);
                        output("`)$msg`0`n");
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
                                        $badguy['creaturehealth'] -= $damage;
                                } else if ($who == 1) {
                                        $session['user']['hitpoints'] -= $damage;
                                }
                                if ($damage < 0) {
                                        $msg = $buff['effectfailmsg'];
                                } else if ($damage == 0) {
                                        $msg = $buff['effectnodmgmsg'];
                                } else if ($damage > 0) {
                                        $msg = $buff['effectmsg'];
                                }
                                if ($msg>"") {
                                        $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                                        $msg = str_replace("{goodguy}", $session['user']['name'], $msg);
                                        $msg = str_replace("{damage}", $damage, $msg);
                                        output("`)$msg`0`n");
                                }
                        }
                }
        }
        return $result;
}

function process_lifetaps($ltaps, $damage) {
        global $session, $badguy;
        reset($ltaps);
        while(list($key,$buff) = each($ltaps)) {
                $healhp = $session['user']['maxhitpoints'] -
                        $session['user']['hitpoints'];
                if ($healhp < 0) $healhp = 0;
                if ($healhp == 0) {
                        $msg = $buff['effectnodmgmsg'];
                } else {
                        if ($healhp > round($damage * $buff['lifetap']))
                                $healhp = round($damage * $buff['lifetap']);
                        if ($healhp < 0) $healhp = 0;
                        if ($damage > 0) {
                                $msg = $buff['effectmsg'];
                        } else if ($damage == 0) {
                                $msg = $buff['effectfailmsg'];
                        } else if ($damage < 0) {
                                $msg = $buff['effectfailmsg'];
                        }
                }
                $session['user']['hitpoints'] += $healhp;
                $msg = str_replace("{badguy}",$badguy['creaturename'], $msg);
                $msg = str_replace("{damage}",$healhp, $msg);
                if ($msg > "") output("`)$msg`n");
        }
}

function process_dmgshield($dshield, $damage) {
        global $session, $badguy;
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
                $badguy[creaturehealth] -= $realdamage;
                $msg = str_replace("{badguy}",$badguy['creaturename'], $msg);
                $msg = str_replace("{damage}",$realdamage, $msg);
                if ($msg > "") output("`)$msg`n");
        }
}

function expire_buffs() {
        global $session, $badguy;
        reset($session['bufflist']);
        while (list($key, $buff) = each($session['bufflist'])) {
                if ($buff['used']) {
                        $session['bufflist'][$key]['used'] = 0;
                        $session['bufflist'][$key]['rounds']--;
                        if ($session['bufflist'][$key]['rounds'] <= 0) {
                                if ($buff['wearoff']) {
                                        $msg = $buff['wearoff'];
                                        $msg = str_replace("{badguy}", $badguy['creaturename'], $msg);
                                        output("`)$msg`n");
                                }
                                unset($session['bufflist'][$key]);
                        }
                }
        }
}

//$badguy = createarray($session[user][badguy]);
$badguy = createarray(gettexts('badguy'));

/* Aprilscherz 2

if (date("m-d")=="04-01"){
        if (!strpos($badguy[creaturename],"bork bork")){
                $badguy[creaturename]=$badguy[creaturename]." bork bork";
        }
}

*/


$adjustment = ($session[user][level]/$badguy[creaturelevel]);
if ($badguy[pvp]) $adjustment=1;

if ($_GET[op]=="fight"){
        if ($_GET[skill]=="godmode"){
                $session[bufflist]['godmode']=array(
                        "name"=>"`&GOD MODE",
                        "rounds"=>1,
                        "wearoff"=>"Du bist wieder sterblich.",
                        "atkmod"=>25,
                        "defmod"=>25,
                        "invulnerable"=>1,
                        "startmsg"=>"`n`&Du fühlst dich gottgleich`n`n",
                        "activate"=>"roundstart"
                );
        }
        //FähigkeitenMOD by Angel **Start**
        if ($_GET[skill]=="SK"){
           //$sqls="SELECT * FROM skills WHERE id='".$session[user][skill]."'";
           //$results = db_query($sqls); //Befehl senden
           //$rows = db_fetch_assoc($results);
           if ($session[user][skillpoints] >= $_GET[1]){
              //$creaturedmg = 0;
              $skillsql="SELECT * FROM `skills` WHERE `id` = '".$session[user][skill]."'";
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
                    $session[bufflist]['sk1']=$arr;
                    $session[user][skillpoints]--;
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
                    $session[bufflist]['sk2']=$arr;
                    $session[user][skillpoints]-=2;
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
                    $session[bufflist]['sk3']=$arr;
                    $session[user][skillpoints]-=3;
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
                    $session[bufflist]['sk4']=$arr;
                    $session[user][skillpoints]-=5;
              }
           }
        }
       //ENDE SKILLMOD BY ANGEL */

        if ($_GET[skill]=="MP"){
                if ($session[user][magicuses] >= $_GET[l]){
                        $creaturedmg = 0;
                        switch($_GET[l]){
                        case 1:
                                $session[bufflist]['mp1'] = array(
                                        "startmsg"=>"`n`^Du fängst an zu regenerieren!`n`n",
                                        "name"=>"`%Regeneration",
                                        "rounds"=>5,
                                        "wearoff"=>"Deine Regeneration hat aufgehört",
                                        "reg2"=>e_rand($session['user']['level'],$session['user']['maxhitpoints']*0.2),
                                        "effectmsg"=>"Du regenerierst um {damage} Punkte.",
                                        "effectnodmgmsg"=>"Du bist völlig gesund.",
                                        "activate"=>"roundstart");
                                break;
                        case 2:
                                $session[bufflist]['mp2'] = array(
                                        "startmsg"=>"`n`^{badguy}`% wird von einer Klaue aus Erde gepackt und auf den Boden geschleudert!`n`n",
                                        "name"=>"`%Erdenfaust",
                                        "rounds"=>5,
                                        "wearoff"=>"Die erdene Faust zerfällt zu staub.",
                                        "minioncount"=>1,
                                        "effectmsg"=>"Eine gewaltige Faust aus Erde trifft {badguy} mit `^{damage}`) Schadenspunkten.",
                                        "minbadguydamage"=>1,
                                        "maxbadguydamage"=>$session['user']['level']*5,
                                        "activate"=>"roundstart"
                                        );
                                break;
                        case 3:
                                $session[bufflist]['mp3'] = array(
                                        "startmsg"=>"`n`^Deine Waffe glüht in einem überirdischen Schein.`n`n",
                                        "name"=>"`%Leben absaugen",
                                        "rounds"=>5,
                                        "wearoff"=>"Die Aura deiner Waffe verschwindet.",
                                        "atkmod"=>1.5,
                                        "lifetap"=>1.5, //ratio of damage healed to damage dealt
                                        "effectmsg"=>"Du wirst um {damage} Punkte geheilt.",
                                        "effectnodmgmsg"=>"Du fühlst ein Prickeln, als deine Waffe versucht, deinen vollständig gesunden Körper zu heilen.",
                                        "effectfailmsg"=>"Deine Waffe scheint zu jammern, als du deinem Gegner keinen Schaden machst.",
                                        "activate"=>"offense,defense",
                                        );
                                break;
                        case 5:
                                $session[bufflist]['mp5'] = array(
                                        "startmsg"=>"`n`^Deine Haut glitzert, als du dir eine Aura aus Blitzen zulegst`n`n",
                                        "name"=>"`%Blitzaura",
                                        "rounds"=>5,
                                        "wearoff"=>"Mit einem Zischen wird deine Haut wieder normal.",
                                        "damageshield"=>4,
                                        "effectmsg"=>"{badguy} wird von einem Blitzbogen aus deiner Haut mit `^{damage}`) Schadenspunkten zurückgeworfen.",
                                        "effectnodmg"=>"{badguy} ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt.",
                                        "effectfailmsg"=>"{badguy} ist von deinen Blitzen leicht geblendet, ansonsten aber unverletzt.",
                                        "activate"=>"offense,defense"
                                );
                                break;
                        }
                        $session[user][magicuses]-=$_GET[l];
                }else{
                        $session[bufflist]['mp0'] = array(
                                "startmsg"=>"`nDu legst deine Stirn in Falten und beschwörst die Elemente.  Eine kleine Flamme erscheint. {badguy} zündet sich eine Zigarette daran an, dankt dir und stürzt sich wieder auf dich.`n`n",
                                "rounds"=>1,
                                "activate"=>"roundstart"
                        );
                }
        }
        if ($_GET[skill]=="DA"){
                if ($session[user][darkartuses] >= $_GET[l]){
                        $creaturedmg = 0;
                        switch($_GET[l]){
                        case 1:
                                $session[bufflist]['da1']=array(
                                        "startmsg"=>"`n`\$Du rufst die Geister der Toten und skelettartige Hände zerren an {badguy} aus den Tiefen ihrer Gräber.`n`n",
                                        "name"=>"`\$Skelettdiener",
                                        "rounds"=>5,
                                        "wearoff"=>"Deine Skelettdiener zerbröckeln zu staub.",
                                        "minioncount"=>round($session[user][level]/3)+1,
                                        "maxbadguydamage"=>round($session[user][level]/2,0)+1,
                                        "effectmsg"=>"`)Ein untoter Diener trifft {badguy} mit `^{damage}`) Schadenspunkten.",
                                        "effectnodmgmsg"=>"`)Ein untoter Diener versucht {badguy} zu treffen, aber `\$TRIFFT NICHT`)!",
                                        "activate"=>"roundstart"
                                        );
                                break;
                        case 2:
                                $session[bufflist]['da2']=array(
                                        "startmsg"=>"`n`\$Du holst eine winzige Puppe, die aussieht wie {badguy}, hervor`n`n",
                                        "effectmsg"=>"Du stößt eine Nadel in die {badguy}-Puppe und machst damit `^{damage}`) Schadenspunkte!",
                                        "minioncount"=>1,
                                        "maxbadguydamage"=>round($session[user][attack]*3,0),
                                        "minbadguydamage"=>round($session[user][attack]*1.5,0),
                                        "activate"=>"roundstart"
                                        );
                                break;
                        case 3:
                                $session[bufflist]['da3']=array(
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
                                $session[bufflist]['da5']=array(
                                        "startmsg"=>"`n`\$Du streckst deine Hand aus und {badguy} fängt an aus den Ohren zu bluten.`n`n",
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
                        $session[user][darkartuses]-=$_GET[l];
                }else{
                        $session[bufflist]['da0'] = array(
                                "startmsg"=>"`nErschöpft versuchst du deine dunkelste Magie: einen schlechten Witz.  {badguy} schaut dich nachdenklich eine Minute lang an. Endlich versteht er den Witz und stürzt sich lachend wieder auf dich.`n`n",
                                "rounds"=>1,
                                "activate"=>"roundstart"
                                );
                }
        }
        if ($_GET[skill]=="TS"){
                if ($session[user][thieveryuses] >= $_GET[l]){
                        $creaturedmg = 0;
                        switch($_GET[l]){
                        case 1:
                                $session[bufflist]['ts1']=array(
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
                                $session[bufflist]['ts2']=array(
                                        "startmsg"=>"`n`^Du reibst Gift auf dein(e/n) ".$session[user][weapon].".`n`n",
                                        "name"=>"`^Vergiftete Waffe",
                                        "rounds"=>5,
                                        "wearoff"=>"Das Blut deines Gegners hat das Gift von deiner Waffe gewaschen.",
                                        "atkmod"=>2,
                                        "roundmsg"=>"Dein Angriffswert vervielfacht sich!",
                                        "activate"=>"offense"
                                        );
                                break;
                        case 3:
                                $session[bufflist]['ts3'] = array(
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
                                $session[bufflist]['ts5']=array(
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
                        $session[user][thieveryuses]-=$_GET[l];
                }else{
                        $session[bufflist]['ts0'] = array(
                                "startmsg"=>"`nDu versuchst, {badguy} anzugreifen, indem du deine besten Diebeskünste in die Praxis umsetzt - aber du stolperst über deine eigenen Füsse.`n`n",
                                "rounds"=>1,
                                "activate"=>"roundstart"
                                );
                }
        }
}


if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0) {
        output ("`\$`c`b~ ~ ~ Kampf ~ ~ ~`b`c`0");

        output("`@Du hast den Gegner `^$badguy[creaturename]`@ entdeckt, der sich mit seiner Waffe `%$badguy[creatureweapon]`@ auf dich stürzt!`0`n`n");

        if ($session['user']['alive']){
                output("`2Level: `6$badguy[creaturelevel]`0`n");
        }else{
                output("`2Level: `6Untoter`0`n");
        }

        output("`2`bBeginn der Runde:`b`n");
        output("`2".($session['user']['alive']?"Lebenspunkte":"Seelenpunkte")." deines Gegners: `6$badguy[creaturehealth]`0`n");
        output("`2DEINE ".($session['user']['alive']?"Lebenspunkte":"Seelenpunkte").": `6".$session[user][hitpoints]."`0`n");
}

// weather mod
if ($session['user']['alive'] && gettexts('buffbackup')==""){
        if (e_rand(1,5)==2){
                if (getsetting("weather","0")=="Starker Wind mit vereinzelten Regenschauern"){
                        if (e_rand(1,2)==1){
                                $session['bufflist']['weather'] = array("name"=>"`6Sturm","rounds"=>1,"wearoff"=>"","atkmod"=>0,"roundmsg"=>"`6Ein starker Windstoss läßt dich dein Ziel verfehlen.","activate"=>"offense");
                        }else{
                                $session['bufflist']['weather'] = array("name"=>"`6Sturm","rounds"=>1,"wearoff"=>"","badguyatkmod"=>0,"roundmsg"=>"`6Ein starker Windstoss hindert {badguy} daran, dich zu treffen.","activate"=>"defense");
                        }
                }
        }
}
// end weather mod

reset($session[bufflist]);
while (list($key,$buff)=each($session['bufflist'])){
        // reset the 'used this round state'
        $buff[used]=0;
}

if ($badguy[pvp] && count($session[bufflist])>0 && is_array($session[bufflist])) {
        if (gettexts('buffbackup')>""){

        }else{
                output("`&Die Götter verbieten den Einsatz jeder Spezialfähigkeit!`n");
                updatetexts('buffbackup',serialize($session['bufflist']));
                $session[bufflist]=array();
                if ($_GET['bg']==1){
                        $session['bufflist']['bodyguard'] = array(
                                "startmsg"=>"`n`\${$badguy['creaturename']} ist durch einen Leibwächter geschützt!`n`n",
                                "name"=>"`&Leibwächter",
                                "rounds"=>5,
                                "wearoff"=>"Der Leibwächter scheint eingeschlafen zu sein.",
                                "minioncount"=>1,
                                "maxgoodguydamage"=> round($session['user']['level']/2,0) +1,
                                "effectmsg"=>"`7{badguy}'s Leibwächter trifft dich mit `\${damage}`7 Schadenspunkten.",
                                "effectnodmgmsg"=>"`7Der Leibwächter versucht dich zu treffen, aber `\$TRIFFT NICHT`7!",
                                "activate"=>"roundstart"
                                );
                }
                if ($_GET['bg']==2){
                        $mgddmg = ($session['user']['attack']+$session['user']['defense']-$badguy['creatureattack']-$badguy['creaturedefense'])*3;
                        if ($mgddmg < $session['user']['level']) $mgddmg = $session['user']['level'];
                        $session['bufflist']['heimvorteil'] = array(
                                "startmsg"=>"`n`\${$badguy['creaturename']} `\$hat einen gewaltigen Heimvorteil!`n`n",
                                "name"=>"`\$Nachteil",
                                "rounds"=>25,
                                "wearoff"=>"Der Heimvorteil ist deinem Gegner nicht mehr von Vorteil.",
                                "minioncount"=>1,
                                "maxgoodguydamage"=> $mgddmg,
                                "effectmsg"=>"`7Durch den Heimvorteil, den {badguy} hat, bekommst du zusätzlich `\${damage}`7 Schadenspunkte.",
                                "effectnodmgmsg"=>"",
                                "activate"=>"roundstart"
                                );
                }
        }
}
// Run the beginning of round buffs (this also calculates all modifiers)

// for ($count=$count;$count>0 && $victory==0 && $defeat==0;$count--){
for ($count=$count;$count>0;$count--){

if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0){

$buffset = activate_buffs("roundstart");

$creaturedefmod=$buffset['badguydefmod'];
$creatureatkmod=$buffset['badguyatkmod'];
$atkmod=$buffset['atkmod'];
$defmod=$buffset['defmod'];
}

if ($badguy['creaturehealth']>0 && $session['user']['hitpoints']>0){

        if ($badguy[pvp]) {
                $adjustedcreaturedefense = $badguy[creaturedefense];
        } else {
                $adjustedcreaturedefense =
                         ($creaturedefmod*$badguy[creaturedefense] /
                         ($adjustment*$adjustment));
        }
        $creatureattack = $badguy[creatureattack]*$creatureatkmod;
        $adjustedselfdefense = ($session[user][defence] * $adjustment * $defmod);

        while($creaturedmg==0 && $selfdmg==0){//---------------------------------
                $atk = $session[user][attack]*$atkmod;
                if ((e_rand(1,20)==1) && !($badguy[pvp])) $atk*=3;
                $patkroll = e_rand(0,$atk);
                $catkroll = e_rand(0,$adjustedcreaturedefense);
                $creaturedmg = 0-(int)($catkroll - $patkroll);
                if ($creaturedmg<0) {
                        //output("`#DEBUG: Initial (<0) creature damage $creaturedmg`n");
                        $creaturedmg = (int)($creaturedmg/2);
                        //output("`#DEBUG: Modified (<0) creature damage $creaturedmg`n");
                        $creaturedmg = round($buffset[badguydmgmod]*$creaturedmg,0);
                        //output("`#DEBUG: Modified (<0) creature damage $creaturedmg`n");
                }
                if ($creaturedmg > 0) {
                        //output("`#DEBUG: Initial (>0) creature damage $creaturedmg`n");
                        $creaturedmg = round($buffset[dmgmod]*$creaturedmg,0);
                        //output("`#DEBUG: Modified (>0) creature damage $creaturedmg`n");
                }
                //output("`#DEBUG: Attack score: $atk`n");
                //output("`#DEBUG: Creature Defense Score: $adjustedcreaturedefense`n");
                //output("`#DEBUG: Player Attack roll: $patkroll`n");
                //output("`#DEBUG: Creature Defense roll: $catkroll`n");
                //output("`#DEBUG: Final Creature Damage: $creaturedmg`n");
                $pdefroll = e_rand(0,$adjustedselfdefense);
                $catkroll = e_rand(0,$creatureattack);
                $selfdmg = 0-(int)($pdefroll - $catkroll);
                if ($selfdmg<0) {
                        //output("`#DEBUG: Initial (<0) self damage $selfdmg`n");
                        $selfdmg=(int)($selfdmg/2);
                        //output("`#DEBUG: Modified (<0) self damage $selfdmg`n");
                        $selfdmg = round($selfdmg*$buffset[dmgmod], 0);
                        //output("`#DEBUG: Modified (<0) self damage $selfdmg`n");
                }
                if ($selfdmg > 0) {
                        //output("`#DEBUG: Initial (>0) self damage $selfdmg`n");
                        $selfdmg = round($selfdmg*$buffset[badguydmgmod], 0);
                        //output("`#DEBUG: Modified (>0) self damage $selfdmg`n");
                }
                //output("`#DEBUG: Defense score: $adjustedselfdefense`n");
                //output("`#DEBUG: Creature Attack score: $creatureattack`n");
                //output("`#DEBUG: Player Defense roll: $pdefroll`n");
                //output("`#DEBUG: Creature Attack roll: $catkroll`n");
                //output("`#DEBUG: Final Player damage: $selfdmg`n");
                //output("`#DEBUG: count: $count`n");
        }
}else{
        $creaturedmg=0;
        $selfdmg=0;
        $count=0;
}
// Handle god mode's invulnerability
if ($buffset[invulnerable]) {
        $creaturedmg = abs($creaturedmg);
        $selfdmg = -abs($selfdmg);
}

if (e_rand(1,3)==1 &&
        ($_GET[op]=="search" ||
         ($badguy[pvp] && $_GET[act]=="attack"))) {
        if ($badguy[pvp]){
                output("`bDie Fähigkeiten von `^$badguy[creaturename]`\$ erlauben deinem Gegner den ersten Schlag!`0`b`n`n");
        }else{
                output("`b`^$badguy[creaturename]`\$ überrascht dich und hat den ersten Schlag!`0`b`n`n");
        }
        $_GET[op]="run";
        $surprised=true;
}else{
        if ($_GET[op]=="search")
                output("`b`\$Dein Können erlaubt dir den ersten Angriff!`0`b`n`n");
        $surprised=false;
}

if ($_GET[op]=="fight" || $_GET[op]=="run"){
        if ($_GET[op]=="fight"){
                if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0){
                        $buffset = activate_buffs("offense");
                }
                if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0){
                        if ($atk > $session[user][attack]) {
                                if ($atk > $session[user][attack]*3){
                                        if ($atk>$session[user][attack]*4){
                                                output("`&`bDu holst zu einem <font size='+1'>MEGA</font> Powerschlag aus!!!`b`n",true);
                                        }else{
                                                output("`&`bDu holst zu einem DOPPELTEN Powerschlag aus!!!`b`n");
                                        }
                                }else{
                                        if ($atk>$session[user][attack]*2){
                                                output("`&`bDu holst zu einem Powerschlag aus!!!`b`0`n");
                                        }elseif ($atk>$session['user']['attack']*1.25){
                                                output("`7`bDu holst zu einem kleinen Powerschlag aus!`b`0`n");
                                        }
                                }
                        }
                        if ($creaturedmg==0){
                                output("`4Du versuchst `^$badguy[creaturename]`4 zu treffen, aber `\$TRIFFST NICHT!`n");
                                if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_dmgshield($buffset[dmgshield], 0);
                                if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_lifetaps($buffset[lifetap], 0);
                        }else if ($creaturedmg<0){
                                output("`4Du versuchst `^$badguy[creaturename]`4 zu treffen, aber der `\$ABWEHRSCHLAG `4trifft dich mit `\$".(0-$creaturedmg)."`4 Schadenspunkten!`n");
                                $badguy['diddamage']=1;
                                $session[user][hitpoints]+=$creaturedmg;
                                if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_dmgshield($buffset[dmgshield],-$creaturedmg);
                                if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_lifetaps($buffset[lifetap],$creaturedmg);
                        }else{
                                output("`4Du triffst `^$badguy[creaturename]`4 mit `^$creaturedmg`4 Schadenspunkten!`n");
                                $badguy[creaturehealth]-=$creaturedmg;
                                if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_dmgshield($buffset[dmgshield],-$creaturedmg);
                                if (/*$badguy[creaturehealth]>0 && */$session[user][hitpoints]>0) process_lifetaps($buffset[lifetap],$creaturedmg);
                        }
                        if ($creaturedmg>$session[user][punch]){
                                $session[user][punch]=$creaturedmg;
                                output("`@`b`c--- DAS WAR DEIN BISHER HÄRTESTER SCHLAG! ---`c`b`n");
                        }
                }
        }else if($_GET[op]=="run" && !$surprised){
                output("`4Du bist zu beschäftigt damit wegzulaufen wie ein feiger Hund und kannst nicht gegen `^$badguy[creaturename]`4 kämpfen.`n");
        }
        // We need to check both user health and creature health. Otherwise the user
         // can win a battle by a RIPOSTE after he has gone <= 0 HP.
        //-- Gunnar Kreitz
        if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0){
                $buffset = activate_buffs("defense");
        }
        if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0){
                if ($selfdmg==0){
                        output("`^$badguy[creaturename]`4 versucht dich zu treffen, aber `\$TRIFFT NICHT!`n");
                        if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_dmgshield($buffset[dmgshield], 0);
                        if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_lifetaps($buffset[lifetap], 0);
                }else if ($selfdmg<0){
                        output("`^$badguy[creaturename]`4 versucht dich zu treffen, aber dein `^ABWEHRSCHLAG`4 trifft mit `^".(0-$selfdmg)."`4 Schadenspunkten!`n");
                        $badguy[creaturehealth]+=$selfdmg;
                        if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_lifetaps($buffset[lifetap], -$selfdmg);
                        if (/*$badguy[creaturehealth]>0 && */$session[user][hitpoints]>0) process_dmgshield($buffset[dmgshield], $selfdmg);
                }else{
                        output("`^$badguy[creaturename]`4 trifft dich mit `\$$selfdmg`4 Schadenspunkten!`n");
                        $session[user][hitpoints]-=$selfdmg;
                        if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_dmgshield($buffset[dmgshield], $selfdmg);
                        if ($badguy[creaturehealth]>0 && $session[user][hitpoints]>0) process_lifetaps($buffset[lifetap], -$selfdmg);
                        $badguy['diddamage']=1;
                }
        }
}
expire_buffs();


$creaturedmg=0;
$selfdmg=0;
if ($count>1 && $session[user][hitpoints]>0 && $badguy[creaturehealth]>0) output("`2`bNächste Runde:`b`n");
if ($session[user][hitpoints]<=0 || $badguy[creaturehealth]<=0) $count=-1;

if ($badguy[creaturehealth]<=0 && $session[user][hitpoints]>0){
        $victory=true;
        $defeat=false;
        $count=0;
}else{
        if ($session[user][hitpoints]<=0){
                $defeat=true;
                $victory=false;
                $count=0;
        }else{
                $defeat=false;
                $victory=false;
        }
}
}

if ($session[user][hitpoints]>0 &&
        $badguy[creaturehealth]>0 &&
        ($_GET[op]=="fight" || $_GET[op]=="run")){
        output("`2`bEnde der Runde:`b`n");
        output("`2".($session['user']['alive']?"Lebenspunkte":"Seelenpunkte")." deines Gegners: `6$badguy[creaturehealth]`0`n");
        output("`2DEINE ".($session['user']['alive']?"Lebenspunkte":"Seelenpunkte").": `6".$session[user][hitpoints]."`0`n");
}

if ($victory || $defeat){
        // Unset the bodyguard buff at the end of the fight.
        // Without this, the bodyguard persists *and* the older buffs are held
        // off for a while! :/
        if (isset($session['bufflist']['bodyguard']))
                unset($session['bufflist']['bodyguard']);
        if (isset($session['bufflist']['heimvorteil'])) unset($session['bufflist']['heimvorteil']);
        if (isset($session['bufflist']['weather'])) unset($session['bufflist']['weather']);
        if (!is_array($session['bufflist']) || count($session['bufflist']) <= 0) {
                $session['bufflist'] = unserialize(gettexts('buffbackup'));
                if (is_array($session['bufflist'])) {
                        if (count($session['bufflist'])>0 && $badguy[pvp])
                                output("`&Die Götter gewähren dir wieder alle deine speziellen Fähigkeiten.`n`n");
                } else {
                        $session['bufflist'] = array();
                }
        }
        updatetexts('buffbackup',"");
}

//$session[user][badguy]=createstring($badguy);
updatetexts('badguy',createstring($badguy));
?> 