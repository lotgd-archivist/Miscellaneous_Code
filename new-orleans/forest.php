
<?php

// 24072004

require_once "common.php";
$balance = getsetting("creaturebalance", 0.33);

// Handle updating any commentary that might be around.
addcommentary();

//savesetting("creaturebalance","0.33");
if ($_GET[op] == "darkhorse") {
    $_GET[op]                     = "";
    $session[user][specialinc]     = "darkhorse.php";
}
if ($_GET[op] == "castle") {
    $_GET[op]                     = "";
    $session[user][specialinc]     = "castle.php";
}
$fight = false;
page_header("Das Waldgebiet");

$session['user']['standort'] = "Im Waldgebiet";

if ($session[user][superuser] >= 1 && $_GET[specialinc] != "") {
    $session[user][specialinc] = $_GET[specialinc];
}
if ($session[user][specialinc] != "") {
    //echo "$x including special/".$session[user][specialinc];

    output("`^`c`bEtwas Besonderes!`c`b`0");
    $specialinc                     = $session[user][specialinc];
    $session[user][specialinc]     = "";
    include("special/" . $specialinc);
    if (!is_array($session['allowednavs']) || count($session['allowednavs']) == 0) {
        forest(true);
        //output(serialize($session['allowednavs']));
    }
    page_footer();
    exit();
}
if ($_GET[op] == "run") {
    if (e_rand() % 3 == 0) {
        output("`c`b`&Du bist erfolgreich vor deinem Gegner geflohen!`0`b`c`n");
        $session[user][reputation] --;
        $_GET[op] = "";
    } else {
        output("`c`b`\$Dir ist es nicht gelungen deinem Gegner zu entkommen!`0`b`c");
    }
}
if ($_GET[op] == "dragon") {
    addnav("Betritt die Höhle", "dragon.php");
    addnav("Renne weg wie ein Baby", "inn.php");
    output("`\$Du betrittst den dunklen Eingang einer Höhle in den Tiefen des Waldes, ");
    output(" im Umkreis von mehreren hundert Metern sind die Bäume bis zu den Stümpfen niedergebrannt.  ");
    output("Rauchschwaden steigen an der Decke des Höhleneinganges empor und werden plötzlich ");
    output("von einer kalten Windböe verweht.  Der Eingang der Höhle liegt an der Seite eines Felsens ");
    output("ein Dutzent Meter über dem Boden des Waldes, wobei Geröll eine kegelförmige ");
    output("Rampe zum Eingang bildet.  Stalaktiten und Stalagmiten nahe des Einganges ");
    output("erwecken in dir dein Eindruck, dass der Höhleneingang in Wirklichkeit ");
    output("das Maul einer riesigen Bestie ist.  ");
    output("`n`nAls du vorsichtig den Eingang der Höhle betrittst, hörst - oder besser fühlst du, ");
    output("ein lautes Rumpeln, das etwa dreißig Sekunden andauert, bevor es wieder verstummt ");
    output("Du bemerkst, dass dir ein Schwefelgeruch entgegenkommt.  Das Poltern ertönt erneut, und hört wieder auf, ");
    output("in einem regelmäßigen Rhythmus.  ");
    output("`n`nDu kletterst den Geröllhaufen rauf, der zum Eingang der Höhle führt. Deine Schritte zerbrechen ");
    output("die scheinbaren Überreste ehemaliger Helden.");
    output("`n`nJeder Instinkt in deinem Körper will fliehen und so schnell wie möglich zurück ins warme Wirtshaus und ");
    output(" " . ($session[user][sex] ? "zum noch wärmeren Seth" : "zur noch wärmeren Violet") . ".  Was tust du?");
    set_special_val('seendragon', 1);
}
if ($_GET[op] == "search") {
    checkday();
    if ($session[user][turns] <= 0) {
        output("`\$`bDu bist zu müde um heute den Wald weiter zu durchsuchen. Vielleicht hast du morgen mehr Energie dazu.`b`0");
        $_GET[op] = "";
    } else {
        $session[user][drunkenness]     = round($session[user][drunkenness] * .9, 0);
        $specialtychance             = e_rand() % 7;
        if ($specialtychance == 0) {
            output("`^`c`bEtwas Besonderes!`c`b`0");
            if ($handle = opendir("special")) {

                // Skip the darkhorse if the horse knows the way
                if ($session['user']['hashorse'] > 0 && $playermount['tavern'] > 0) {
                    $sql_add = " AND filename <> 'darkhorse.php'";
                }
                $waldspecial = @mysql_result(mysql_query("SELECT filename FROM waldspecial WHERE prio <= " . e_rand(0, 3) . " AND dk <=" . $session[user][dragonkills] . " ORDER BY RAND() LIMIT 1"), 0, "filename");
                if ($waldspecial == '') {
                    output("`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben.  Beschwer dich bei ihm, nicht beim Programmierer. Es könnte natürlich auch sein, dass es kein Waldspecial gibt, das für dich freigeschalten ist... zu dumm..");
                }
                $y             = $_GET[op];
                $_GET[op]     = "";
                include("special/" . $waldspecial);
                $_GET[op]     = $y;
            } else {
                output("`c`b`\$FEHLER!!!`b`c`&Es ist nicht möglich die besonderen Ereignisse zu öffnen! Bitte benachrichtige den Administrator!!");
            }

            if ($nav == "")
                forest(true);
        }else {
            $session[user][turns] --;
            $battle = true;
            if (e_rand(0, 2) == 1) {
                $plev     = (e_rand(1, 5) == 1 ? 1 : 0);
                $nlev     = (e_rand(1, 3) == 1 ? 1 : 0);
            } else {
                $plev     = 0;
                $nlev     = 0;
            }
            if ($_GET['type'] == "slum") {
                $nlev++;
                output("`\$Du steuerst den Abschnitt des Waldes an, von dem du weißt, dass sich dort Feinde aufhalten, die dir ein bisschen angenehmer sind.`0`n");
                $session[user][reputation] --;
            }
            if ($_GET['type'] == "thrill") {
                $plev++;
                output("`\$Du steuerst den Abschnitt des Waldes an, in dem sich Kreaturen deiner schlimmsten Albträume aufhalten, in der Hoffnung dass Du eine findest die verletzt ist.`0`n");
                $session[user][reputation] ++;
            }
            $targetlevel = ($session['user']['level'] + $plev - $nlev );
            if ($targetlevel < 1)
                $targetlevel = 1;
            $sql         = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(" . e_rand() . ") LIMIT 1";
            $result         = db_query($sql) or die(db_error(LINK));
            $badguy         = db_fetch_assoc($result);
            $expflux     = round($badguy['creatureexp'] / 10, 0);
            // more XP per DK
//            $badguy['creatureexp']+=round($session['user']['dragonkills']/300 * $badguy['creatureexp']);

            $expflux = e_rand(-$expflux, $expflux);
            $badguy['creatureexp']+=$expflux;

            //make badguys get harder as you advance in dragon kills.
            //output("`#Debug: badguy gets `%$dk`# dk points, `%+$atkflux`# attack, `%+$defflux`# defense, +`%$hpflux`# hitpoints.`n");
            $badguy['playerstarthp'] = $session['user']['hitpoints'];
            $dk                         = 0;
            while (list($key, $val) = each($session[user][dragonpoints])) {
                if ($val == "at" || $val == "de")
                    $dk++;
            }
            $dk += (int) (($session['user']['maxhitpoints'] -
                    ($session['user']['level'] * 10)) / 5);
            if (!$beta)
                $dk     = round($dk * 0.25, 0);
            else
                $dk     = round($dk, 0);

            $atkflux = e_rand(0, $dk);
            if ($beta)
                $atkflux = min($atkflux, round($dk / 4));
            $defflux = e_rand(0, ($dk - $atkflux));
            if ($beta)
                $defflux = min($defflux, round($dk / 4));
            $hpflux     = ($dk - ($atkflux + $defflux)) * 5;
            $badguy['creatureattack']+=$atkflux;
            $badguy['creaturedefense']+=$defflux;
            $badguy['creaturehealth']+=$hpflux;
            if ($beta) {
                $badguy['creaturedefense']*=0.66;
                $badguy['creaturegold']*=(1 + (.05 * $dk));
                if ($session['user']['race'] == 4)
                    $badguy['creaturegold']*=1.1;
            } else {
                if ($session['user']['race'] == 4)
                    $badguy['creaturegold']*=1.2;
            }
            $badguy['diddamage']         = 0;
            $session['user']['badguy']     = createstring($badguy);
            if ($beta) {
                if ($session['user']['superuser'] >= 1) {
                    output("Debug: $dk dragon points.`n");
                    output("Debug: +$atkflux attack.`n");
                    output("Debug: +$defflux defense.`n");
                    output("Debug: +$hpflux health.`n");
                }
            }
        }
    }
}
if ($_GET[op] == "fight" || $_GET[op] == "run") {
    $battle = true;
}
if ($battle) {
    include("battle.php");
//    output(serialize($badguy));
    if ($victory) {
        if (getsetting("dropmingold", 0)) {
            $badguy[creaturegold] = e_rand($badguy[creaturegold] / 4, 3 * $badguy[creaturegold] / 4);
        } else {
            $badguy[creaturegold] = e_rand(0, $badguy[creaturegold]);
        }
        $expbonus = round(
                ($badguy[creatureexp] *
                (1 + .25 *
                ($badguy[creaturelevel] - $session[user][level])
                )
                ) - $badguy[creatureexp], 0
        );
        output("`b`&$badguy[creaturelose]`0`b`n");
        output("`b`\$Du hast $badguy[creaturename] erledigt!`0`b`n");
        output("`#Du erbeutest `^$badguy[creaturegold]`# Goldstücke!`n");
        if ($badguy['creaturegold']) {
            //debuglog("received {$badguy['creaturegold']} gold for slaying a monster.");
                $unique1=1;
                $unique2=1000; 
        }


        //find something
        $findit=e_rand(1,30); 
        if ($findit == 2) { //gem
            /*
              output("`&Du findest EINEN EDELSTEIN!`n`#");
              $session['user']['gems']++;
             */
            output('`&Du findest EINEN ROHDIAMANTEN!`n`#');
            $session['user']['rohdiamant'] ++;
            //debuglog("found a gem when slaying a monster.");
        }
        if ($findit == 5)
            $session['user']['donation']+=1;

        if ($findit == 20 && e_rand(1, 4) == 3) { // item
            $sql     = "SELECT * FROM items WHERE owner=0 AND (class='Beute.Prot' OR class='Zaub.Prot') ORDER BY rand(" . e_rand() . ") LIMIT 1";
            $result     = db_query($sql) or die(db_error(LINK));
            $row2     = db_fetch_assoc($result);
            if ($row2[name]) {
                if ($row2['class'] == "Beute.Prot") {
                    $sql = "INSERT INTO items(name,class,owner,gold,gems,description) VALUES ('" . addslashes($row2[name]) . "','Beute'," . $session[user][acctid] . ",$row2[gold],$row2[gems],'" . addslashes($row2[description]) . "')";
                } else if ($row2['class'] == "Zaub.Prot") {
                    $row2[description].=" (gebraucht)";
                    $row2[value1]     = e_rand(1, $row2[value2]);
                    $row2[gold]         = $row2[gold] * (($row2[value1] + 1) / ($row2[value2] + 1));
                    $sql             = "INSERT INTO items(name,class,owner,gold,gems,value1,value2,hvalue,description,buff) VALUES ('" . addslashes($row2[name]) . "','Zauber'," . $session[user][acctid] . ",$row2[gold],0,$row2[value1],$row2[value2],$row2[hvalue],'" . addslashes($row2[description]) . "','" . addslashes($row2[buff]) . "')";
                } else {
                    $sql = "UPDATE items SET owner=" . $session[user][acctid] . " WHERE id=$row2[id]";
                }
                db_query($sql) or die(sql_error($sql));
                output("`n`qBeim Durchsuchen von $badguy[creaturename] `qfindest du `&$row2[name]`q! ($row2[description])`n`n`#");
            }
        }

        if ($findit == 25 && e_rand(1, 6) == 2) { // armor
            $sql     = "SELECT * FROM armor WHERE defense<=" . $session[user][level] . " ORDER BY rand(" . e_rand() . ") LIMIT 1";
            $result2 = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result2) > 0) {
                $row2             = db_fetch_assoc($result2);
                $row2['value']     = round($row2['value'] / 10);
                $sql             = "INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('" . addslashes($row2[armorname]) . "','Rüstung'," . $session[user][acctid] . ",$row2[value],$row2[defense],'Gebrauchte Level $row2[level] Rüstung mit $row2[defense] Verteidigung.')";
                db_query($sql) or die(sql_error($sql));
                output("`n`QBeim Durchsuchen von $badguy[creaturename] `Qfindest du die Rüstung `%$row2[armorname]`Q!`n`n`#");
            }
        }
        if ($findit == 26 && e_rand(1, 6) == 2) { // weapon
            $sql     = "SELECT * FROM weapons WHERE damage<=" . $session[user][level] . " ORDER BY rand(" . e_rand() . ") LIMIT 1";
            $result2 = db_query($sql) or die(db_error(LINK));
            if (db_num_rows($result2) > 0) {
                $row2             = db_fetch_assoc($result2);
                $row2['value']     = round($row2['value'] / 10);
                $sql             = "INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('" . addslashes($row2[weaponname]) . "','Waffe'," . $session[user][acctid] . ",$row2[value],$row2[damage],'Gebrauchte Level $row2[level] Waffe mit $row2[damage] Angriffswert.')";
                db_query($sql) or die(sql_error($sql));
                output("`n`QBeim Durchsuchen von $badguy[creaturename] `Qfindest du die Waffe `%$row2[weaponname]`Q!`n`n`#");
            }
        }


if($uniqueran==154){ // uniqueweapon , Hadriel
$sql = "SELECT * FROM items WHERE (class='uniquewa.p') ORDER BY rand(".e_rand().") LIMIT 1";
$result3 = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result3)>0){
$row3 = db_fetch_assoc($result3);
$row3['value']=round($row3['value']/10);
$sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($row3[name])."','Waffe',".$session[user][acctid].",$row3[gold],$row3[value1],'Unique mit $row3[value1] Angriffswert.')";
db_query($sql) or die(sql_error($sql));
output("`n`Q`bBeim Durchsuchen von $badguy[creaturename] `Qfindest du das Unique `%$row3[name]`Q!`b`n`n`#");

}
}
if($uniqueran==78){ // uniquearmor , Hadriel
$sql = "SELECT * FROM items WHERE (class='uniquear.p') ORDER BY rand(".e_rand().") LIMIT 1";
$result3 = db_query($sql) or die(db_error(LINK));
if (db_num_rows($result3)>0){
$row3 = db_fetch_assoc($result3);
$row3['value']=round($row3['value']/10);
$sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($row3[name])."','Rüstung',".$session[user][acctid].",$row3[gold],$row3[value1],'Unique mit $row3[value1] Verteidigungswert.')";
db_query($sql) or die(sql_error($sql));
output("`n`Q`bBeim Durchsuchen von $badguy[creaturename] `Qfindest du das Unique `%$row3[name]`Q!`b`n`n`#");
}
} 

        $extraexp = getsetting('extraexp', '1.0');

        $session['user']['gold']+=$badguy['creaturegold'];

        /*
          $exparray=array(1=>100,400,1002,1912,3140,4707,6641,8985,11795,15143,19121,23840,29437,36071,43930,55000);
          while(list($key,$val)=each($exparray)){
          $exparray[$key]= round($val + ($session['user']['dragonkills']/4) * $key * 100,0);
          }

          $exp_check = $session['user']['experience']-$exparray[$session['user']['level']-1];
          $req_check = $exparray[$session['user']['level']]-$exparray[$session['user']['level']-1];
         */

        if ($session['user']['rp_char'] == 3 OR $session['user']['rp_char'] == 4) {
            //if($exp_check < ($req_check + ($req_check/100)*10)){
            output("Du bekommst insgesamt `^" . (($badguy[creatureexp] + $expbonus) * $extraexp) . "`# Erfahrungspunkte!`0`n");
            $session['user']['experience']+=(($badguy['creatureexp'] + $expbonus) * $extraexp);

            if ($expbonus > 0) {
                output("`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^$expbonus`# Erfahrungspunkte! `n($badguy[creatureexp] + " . abs($expbonus) . " = " . ($badguy[creatureexp] + $expbonus) . ")`0`n");
            } else if ($expbonus < 0) {
                output("`#*** Weil dieser Kampf so leicht war, verlierst du `^" . abs($expbonus) . "`# Erfahrungspunkte! `n($badguy[creatureexp] - " . abs($expbonus) . " = " . ($badguy[creatureexp] + $expbonus) . ")`0`n");
            }
            //}else{
            //    output("Du bekommst keine Erfahrungspunkte da du die Anforderung für dein Level breits erfüllst!`0`n");
            //}
        }
        if ($session['user']['rp_char'] == 2) {
            //if($exp_check < ($req_check + ($req_check/100)*10)){
            output("Du bekommst insgesamt `^" . ((($badguy[creatureexp] + $expbonus) * 0.5) * $extraexp) . "`# Erfahrungspunkte!`0`n");
            $session['user']['experience']+=((($badguy['creatureexp'] + $expbonus) * 0.5) * $extraexp);

            if ($expbonus > 0) {
                output("`#*** Durch die hohe Schwierigkeit des Kampfes erhältst du zusätzlich `^$expbonus`# Erfahrungspunkte! `n($badguy[creatureexp] + " . abs($expbonus) . " = " . ($badguy[creatureexp] + $expbonus) . ")`0`n");
            } else if ($expbonus < 0) {
                output("`#*** Weil dieser Kampf so leicht war, verlierst du `^" . abs($expbonus) . "`# Erfahrungspunkte! `n($badguy[creatureexp] - " . abs($expbonus) . " = " . ($badguy[creatureexp] + $expbonus) . ")`0`n");
            }
            //}else{
            //    output("Du bekommst keine Erfahrungspunkte da du die Anforderung für dein Level breits erfüllst!`0`n");
            //}
        }
        $creaturelevel     = $badguy[creaturelevel];
        $_GET[op]         = "";
        //if ($session[user][hitpoints] == $session[user][maxhitpoints]){
        if ($badguy['diddamage'] != 1) {
            if ($session[user][level] >= getsetting("lowslumlevel", 4) || $session[user][level] <= $creaturelevel) {
                output("`b`c`&~~ Perfekter Kampf! ~~`\$`n`bDu erhältst eine Extrarunde!`c`0`n");
                $session[user][turns] ++;
                if ($expbonus > 0) {
                    $session['user']['donation']+=1;
                }
            } else {
                output("`b`c`&~~ Perfekter Kampf! ~~`b`\$`nEin schwierigerer Kampf hätte dir eine extra Runde gebracht.`c`n`0");
            }
        }
        $dontdisplayforestmessage     = true;
        addhistory(($badguy['playerstarthp'] - $session['user']['hitpoints']) / max($session['user']['maxhitpoints'], $badguy['playerstarthp']));
        $badguy                         = array();
    } else {
        if ($defeat) {
            addnav("Tägliche News", "news.php");
            $sql                         = "SELECT taunt FROM taunts ORDER BY rand(" . e_rand() . ") LIMIT 1";
            $result                         = db_query($sql) or die(db_error(LINK));
            $taunt                         = db_fetch_assoc($result);
            $taunt                         = str_replace("%s", ($session[user][sex] ? "sie" : "ihn"), $taunt[taunt]);
            $taunt                         = str_replace("%o", ($session[user][sex] ? "sie" : "er"), $taunt);
            $taunt                         = str_replace("%p", ($session[user][sex] ? "ihr" : "sein"), $taunt);
            $taunt                         = str_replace("%x", ($session[user][weapon]), $taunt);
            $taunt                         = str_replace("%X", $badguy[creatureweapon], $taunt);
            $taunt                         = str_replace("%W", $badguy[creaturename], $taunt);
            $taunt                         = str_replace("%w", $session[user][name], $taunt);
            addhistory(1);
            addnews("`%" . $session[user][name] . "`5 wurde im Wald von $badguy[creaturename] niedergemetzelt.`n$taunt");
            $session[user][alive]         = false;
            debuglog("lost {$session['user']['gold']} gold when they were slain in the forest");
            $session[user][gold]         = 0;
            $session[user][hitpoints]     = 0;
            $session[user][experience]     = round($session[user][experience] * .9, 0);
            $session[user][badguy]         = "";
            output("`b`&Du wurdest von `%$badguy[creaturename]`& niedergemetzelt!!!`n");
            output("`4Dein ganzes Gold wurde dir abgenommen!`n");
            output("`410% deiner Erfahrung hast du verloren!`n");
            output("Du kannst morgen weiter kämpfen.");

            page_footer();
        } else {
            fightnav();
        }
    }
}

if ($_GET[op] == "") {
    // Need to pass the variable here so that we show the forest message
    // sometimes, but not others.
    forest($dontdisplayforestmessage);
}

page_footer();

function addhistory($value) {
    /*
      global $session,$balance;
      $history = unserialize($session['user']['history']);
      $historycount=50;
      for ($x=0;$x<$historycount;$x++){
      if (!isset($history[$x])) $history[$x]=$balance;
      }
      array_shift($history);
      array_push($history,$value);
      $history = array_values($history);
      for ($x=0;$x<$historycount;$x++){
      $history[$x] = round($history[$x],4);
      if ($session['user']['superuser']>=1) output("History: {$history[$x]}`n");
      }
      $session['user']['history']=serialize($history);
     */
}

?>


