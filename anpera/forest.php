
ï»¿<?php



// 20140816



require_once("common.php");

$balance = getsetting("creaturebalance", 0.33);



// Handle updating any commentary that might be around.

addcommentary();



//savesetting("creaturebalance","0.33");

if ($_GET['op']=="darkhorse"){

    $_GET['op']="";

    $session['user']['specialinc']="darkhorse.php";

}

if ($_GET['op']=="castle"){

    $_GET['op']="";

    $session['user']['specialinc']="castle.php";

}

$fight = false;

page_header("Der Wald");

if ($session['user']['superuser']>1 && $_GET['specialinc']!=""){

  $session['user']['specialinc'] = $_GET['specialinc'];

}

if ($session['user']['specialinc']!=""){

  //echo "$x including special/".$session[user][specialinc];



    output("`^`c`bEtwas Besonderes!`c`b`0");

    $specialinc = $session['user']['specialinc'];

    $session['user']['specialinc'] = "";

    include("special/".$specialinc);

    if (!is_array($session['allowednavs']) || count($session['allowednavs'])==0) forest(true);

    page_footer();

    exit();

}

if ($_GET['op']=="run"){

    if (e_rand()%3 == 0){

        output ("`c`b`&Du bist erfolgreich vor deinem Gegner geflohen!`0`b`c`n");

        $session['user']['reputation']--;

        $HTTP_GET_VARS['op']=""; // aus KompatibilitÃ¤tsgrÃ¼nden drin gelassen

        $_GET['op']="";

    }else{

        output("`c`b`\$Dir ist es nicht gelungen deinem Gegner zu entkommen!`0`b`c");

    }

}

if ($_GET['op']=="dragon"){

    addnav("Betrete die HÃ¶hle","dragon.php");

    addnav("Renne weg wie ein Baby","inn.php");

    output("`\$Du betrittst den dunklen Eingang einer HÃ¶hle in den Tiefen des Waldes, ");

    output(" im Umkreis von mehreren hundert Metern sind die BÃ¤ume bis zu den StÃ¼mpfen niedergebrannt.  ");

    output("Rauchschwaden steigen an der Decke des HÃ¶hleneinganges empor und werden plÃ¶tzlich ");

    output("von einer kalten WindbÃ¶e verweht.  Der Eingang der HÃ¶hle liegt an der Seite eines Felsens ");

    output("ein Dutzend Meter Ã¼ber dem Boden des Waldes, wobei GerÃ¶ll eine kegelfÃ¶rmige ");

    output("Rampe zum Eingang bildet.  Stalaktiten und Stalagmiten nahe des Einganges ");

    output("erwecken in dir den Eindruck, dass der HÃ¶hleneingang in Wirklichkeit ");

    output("das Maul einer riesigen Bestie ist.  ");

    output("`n`nAls du vorsichtig den Eingang der HÃ¶hle betrittst, hÃ¶rst - oder besser fÃ¼hlst du, ");

    output("ein lautes Rumpeln, das etwa dreiÃŸig Sekunden andauert, bevor es wieder verstummt. ");

    output("Du bemerkst, dass dir ein Schwefelgeruch entgegenkommt.  Das Poltern ertÃ¶nt erneut, und hÃ¶rt wieder auf, ");

    output("in einem regelmÃ¤ÃŸigen Rhythmus.  ");

    output("`n`nDu kletterst den GerÃ¶llhaufen rauf, der zum Eingang der HÃ¶hle fÃ¼hrt. Deine Schritte zerbrechen ");

    output("die scheinbaren Ãœberreste ehemaliger Helden.");

    output("`n`nJeder Instinkt in deinem KÃ¶rper will fliehen und so schnell wie mÃ¶glich zurÃ¼ck ins warme Wirtshaus und ");

    output(" ".($session['user']['sex']?"zum noch wÃ¤rmeren Seth":"zur noch wÃ¤rmeren Violet").".  Was tust du?");

    $session['user']['seendragon']=1;

}



if ($_GET['op']=="search"){

    checkday();

  if ($session['user']['turns']<=0){

    output("`\$`bDu bist zu mÃ¼de um heute den Wald weiter zu durchsuchen. Vielleicht hast du morgen mehr Energie dazu.`b`0");

    $HTTP_GET_VARS['op']="";

    $_GET['op']="";

  }else{

      $session['user']['drunkenness']=round($session['user']['drunkenness']*.9,0);

      $specialtychance = e_rand()%7;

      if ($specialtychance==0){

          output("`^`c`bEtwas Besonderes!`c`b`0");

            if ($handle = opendir("special")){

              $events = array();

              while (false !== ($file = readdir($handle))){

                  if (strpos($file,".php")>0){

                        // Skip the darkhorse if the horse knows the way

                      if ($session['user']['hashorse'] > 0 &&

                            $playermount['tavern'] > 0 &&

                          strpos($file, "darkhorse") !== false) {

                          continue;

                      }

                      array_push($events,$file);

                    }

                }

                $x = e_rand(0,count($events)-1);

                if (count($events)==0){

                  output("`b`@Arrr, dein Administrator hat entschieden, dass es dir nicht erlaubt ist, besondere Ereignisse zu haben.  Beschwer dich bei ihm, nicht beim Programmierer.");

                }else{

                  $y = $HTTP_GET_VARS['op'];

                  $yy = $_GET['op'];

                  $HTTP_GET_VARS['op']="";

                  $_GET['op']="";

                  include("special/".$events[$x]);

                  $HTTP_GET_VARS['op']=$y;

                  $_GET['op']=$yy;

                }

            }else{

              output("`c`b`\$FEHLER!!!`b`c`&Es ist nicht mÃ¶glich die besonderen Ereignisse zu Ã¶ffnen! Bitte benachrichtige den Administrator!!");

            }

          if ($nav=="") forest(true);

      }else{

      $session['user']['turns']--;

          $battle=true;

            if (e_rand(0,2)==1){

                $plev = (e_rand(1,5)==1?1:0);

                $nlev = (e_rand(1,3)==1?1:0);

            }else{

              $plev=0;

                $nlev=0;

            }

            if ($_GET['type']=="slum"){

              $nlev++;

                output("`\$Du steuerst den Abschnitt des Waldes an, von dem du weiÃŸt, dass sich dort Feinde aufhalten, die dir ein bisschen angenehmer sind.`0`n");

                $session['user']['reputation']--;

            }

            if ($_GET['type']=="thrill"){

              $plev++;

                output("`\$Du steuerst den Abschnitt des Waldes an, in dem sich Kreaturen deiner schlimmsten AlbtrÃ¤ume aufhalten, in der Hoffnung dass Du eine findest die verletzt ist.`0`n");

                $session['user']['reputation']++;

            }

            $targetlevel = ($session['user']['level'] + $plev - $nlev );

            if ($targetlevel<1) $targetlevel=1;

            $sql = "SELECT * FROM creatures WHERE creaturelevel = $targetlevel ORDER BY rand(".e_rand().") LIMIT 1";

            $result = db_query($sql) or die(db_error(LINK));

            $badguy = db_fetch_assoc($result);

            $expflux = round($badguy['creatureexp']/10,0);

            $expflux = e_rand(-$expflux,$expflux);

            $badguy['creatureexp']+=$expflux;



            //make badguys get harder as you advance in dragon kills.

            $badguy['playerstarthp']=$session['user']['hitpoints'];

            $dk = 0;

            while(list($key, $val)=each($session['user']['dragonpoints'])) {

                if ($val=="at" || $val=="de") $dk++;

            }

            $dk += (int)(($session['user']['maxhitpoints']-

                ($session['user']['level']*10))/5);

            if (!$beta) $dk = round($dk * 0.25, 0);

            else $dk = round($dk,0);



            $atkflux = e_rand(0, $dk);

            if ($beta) $atkflux = min($atkflux, round($dk/4));

            $defflux = e_rand(0, ($dk-$atkflux));

            if ($beta) $defflux = min($defflux, round($dk/4));

            $hpflux = ($dk - ($atkflux+$defflux)) * 5;

            $badguy['creatureattack']+=$atkflux;

            $badguy['creaturedefense']+=$defflux;

            $badguy['creaturehealth']+=$hpflux;

            if ($beta) {

                $badguy['creaturedefense']*=0.66;

                $badguy['creaturegold']*=(1+(.05*$dk));

                if ($session['user']['race']==4) $badguy['creaturegold']*=1.1;

            } else {

                if ($session['user']['race']==4) $badguy['creaturegold']*=1.2;

            }

            $badguy['diddamage']=0;

            $session['user']['badguy']=createstring($badguy);

            if ($beta) {

                if ($session['user']['superuser']>=3){

                    output("Debug: $dk dragon points.`n");

                    output("Debug: +$atkflux attack.`n");

                    output("Debug: +$defflux defense.`n");

                    output("Debug: +$hpflux health.`n");

                }

            }

        }

    }

}

if ($_GET['op']=="fight" || $_GET['op']=="run"){

    $battle=true;

}



if ($battle){

  include("battle.php");

    if ($victory){

        if (getsetting("dropmingold",0)){

            $badguy['creaturegold']=e_rand($badguy['creaturegold']/4,3*$badguy['creaturegold']/4);

        }else{

            $badguy['creaturegold']=e_rand(0,$badguy['creaturegold']);

        }

        $expbonus = round(

            ($badguy['creatureexp'] *

                (1 + .25 *

                    ($badguy['creaturelevel']-$session['user']['level'])

                )

            ) - $badguy['creatureexp'],0

        );

        output("`b`&{$badguy['creaturelose']}`0`b`n");

        output("`b`\$Du hast {$badguy['creaturename']} erledigt!`0`b`n");

        output("`#Du erbeutest `^{$badguy['creaturegold']}`# GoldstÃ¼cke!`n");

        if ($session['user']['race']==6 && $session['user']['hitpoints']<$session['user']['maxhitpoints']){

            $session['user']['hitpoints']+=round($badguy['creaturegold']*0.1);

            output("`tDu saugst `%{$badguy['creaturename']}`^ ".round($badguy['creaturegold']*0.1)."`t Lebenspunkte ab.`n`#");

            if ($session['user']['hitpoints']>$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];

        }

        if ($badguy['creaturegold']) {



        }

        $session['user']['creaturekills']++;

        //find something

        $findit=e_rand(1,27);

        if ($findit == 2) { //gem

            output("`&Du findest EINEN EDELSTEIN!`n`#");

              $session['user']['gems']++;

              //debuglog("found a gem when slaying a monster.");

        }

        if ($findit == 5) $session['user']['donation']+=1;



        if ($findit == 20 && e_rand(1,4)==3){ // item

            $sql="SELECT * FROM items WHERE owner=0 AND (class='Beute.Prot' OR class='Zauber.Prot' OR class='Kleid.Prot' OR class='Komponente.Prot' OR class='Fauliges.Prot' OR (class='Rezept.Prot' AND (gold>0 OR gems>0))) ORDER BY rand(".e_rand().") LIMIT 1";

            $result = db_query($sql) or die(db_error(LINK));

            $row2 = db_fetch_assoc($result);

            if ($row2['name']){

                if ($row2['class']=="Beute.Prot" || $row2['class']=="Komponente.Prot" || $row2['class']=="Rezept.Prot"){

                    $klasse = substr($row2['class'],0,-5);

                    $sql="INSERT INTO items(name,class,owner,gold,gems,value1,description) VALUES ('".addslashes($row2['name'])."','{$klasse}',".$session['user']['acctid'].",{$row2['gold']},{$row2['gems']},{$row2['value1']},'".addslashes($row2['description'])."')";

                }elseif ($row2['class']=="Kleid.Prot"){

                    $row2['gold']=round($row2['gold']*0.2);

                    $row2['gems']=round($row2['gems']*0.2);

                    if ($row2['value1']>1) $row2['value1']-=1;

                    $sql="INSERT INTO items(name,class,owner,value1,value2,hvalue,gold,gems,description,buff) VALUES ('".addslashes($row2['name'])."','Kleidung',".$session['user']['acctid'].",{$row2['value1']},{$row2['value2']},0,{$row2['gold']},{$row2['gems']},'".addslashes($row2['description']." (zerschlissen)")."','".addslashes($row2['buff'])."')";

                }else if ($row2['class']=="Zauber.Prot"){

                    $row2['description'].=" (gebraucht)";

                    $row2['value1']=e_rand(1,$row2['value2']);

                    $row2['gold']=$row2['gold']*(($row2['value1']+1)/($row2['value2']+1));

                    $sql="INSERT INTO items(name,class,owner,gold,gems,value1,value2,hvalue,description,buff) VALUES ('".addslashes($row2['name'])."','Zauber',".$session['user']['acctid'].",{$row2['gold']},0,{$row2['value1']},{$row2['value2']},{$row2['hvalue']},'".addslashes($row2['description'])."','".addslashes($row2['buff'])."')";

                }else{

                    $sql = "UPDATE items SET owner=".$session['user']['acctid']." WHERE id={$row2['id']}";

                }

                db_query($sql) or die(sql_error($sql));

                output("`n`qBeim Durchsuchen von {$badguy['creaturename']} `qfindest du `&{$row2['name']}`q! ({$row2['description']})`n`n`#");

            }

        }

        if ($findit == 25 && e_rand(1,5)==2){ // armor

            $sql = "SELECT * FROM armor WHERE defense<=".$session['user']['level']." ORDER BY rand(".e_rand().") LIMIT 1";

            $result2 = db_query($sql) or die(db_error(LINK));

            if (db_num_rows($result2)>0){

                $row2 = db_fetch_assoc($result2);

                $row2['value']=round($row2['value']/10);

                $sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($row2[armorname])."','RÃ¼stung',".$session[user][acctid].",$row2[value],$row2[defense],'Gebrauchte Level $row2[level] RÃ¼stung mit $row2[defense] Verteidigung.')";

                db_query($sql) or die(sql_error($sql));

                output("`n`QBeim Durchsuchen von $badguy[creaturename] `Qfindest du die RÃ¼stung `%$row2[armorname]`Q!`n`n`#");

            }

        }

        if ($findit == 26 && e_rand(1,5)==2){ // weapon

            $sql = "SELECT * FROM weapons WHERE damage<=".$session['user']['level']." ORDER BY rand(".e_rand().") LIMIT 1";

            $result2 = db_query($sql) or die(db_error(LINK));

            if (db_num_rows($result2)>0){

                $row2 = db_fetch_assoc($result2);

                $row2['value']=round($row2['value']/10);

                $sql="INSERT INTO items(name,class,owner,gold,value1,description) VALUES ('".addslashes($row2[weaponname])."','Waffe',".$session[user][acctid].",$row2[value],$row2[damage],'Gebrauchte Level $row2[level] Waffe mit $row2[damage] Angriffswert.')";

                db_query($sql) or die(sql_error($sql));

                output("`n`QBeim Durchsuchen von $badguy[creaturename] `Qfindest du die Waffe `%$row2[weaponname]`Q!`n`n`#");

            }

        }



        if ($expbonus>0){

          output("`#*** Durch die hohe Schwierigkeit des Kampfes erhÃ¤ltst du zusÃ¤tzlich `^$expbonus`# Erfahrungspunkte! `n($badguy[creatureexp] + ".abs($expbonus)." = ".($badguy[creatureexp]+$expbonus).") ");

        }else if ($expbonus<0){

          output("`#*** Weil dieser Kampf so leicht war, verlierst du `^".abs($expbonus)."`# Erfahrungspunkte! `n($badguy[creatureexp] - ".abs($expbonus)." = ".($badguy['creatureexp']+$expbonus).") ");

        }

        output("Du bekommst insgesamt `^".($badguy['creatureexp']+$expbonus)."`# Erfahrungspunkte!`n`0");

        $session['user']['gold']+=$badguy['creaturegold'];

        $session['user']['experience']+=($badguy['creatureexp']+$expbonus);

        $creaturelevel = $badguy['creaturelevel'];

        $HTTP_GET_VARS['op']="";

        $_GET['op']="";

        if ($badguy['diddamage']!=1){

            if ($session['user']['level']>=getsetting("lowslumlevel",4) || $session['user']['level']<=$creaturelevel){

                output("`b`c`&~~ Perfekter Kampf! ~~`\$`n`bDu erhÃ¤ltst eine Extrarunde!`c`0`n");

                $session['user']['turns']++;

                if ($expbonus>0){

                    $session['user']['donation']+=1;

                }

            }else{

                output("`b`c`&~~ Perfekter Kampf! ~~`b`\$`nEin schwierigerer Kampf hÃ¤tte dir eine extra Runde gebracht.`c`n`0");

            }

        }

        $dontdisplayforestmessage=true;

        addhistory(($badguy['playerstarthp']-$session['user']['hitpoints'])/max($session['user']['maxhitpoints'],$badguy['playerstarthp']));

        $badguy=array();

    }else{

        if($defeat){

            addnav("TÃ¤gliche News","news.php");

            $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";

            $result = db_query($sql) or die(db_error(LINK));

            $taunt = db_fetch_assoc($result);

            $taunt = str_replace("%s",($session['user']['sex']?"sie":"ihn"),$taunt['taunt']);

            $taunt = str_replace("%o",($session['user']['sex']?"sie":"er"),$taunt);

            $taunt = str_replace("%p",($session['user']['sex']?"ihr":"sein"),$taunt);

            $taunt = str_replace("%x",($session['user']['weapon']),$taunt);

            $taunt = str_replace("%X",$badguy['creatureweapon'],$taunt);

            $taunt = str_replace("%W",$badguy['creaturename'],$taunt);

            $taunt = str_replace("%w",$session['user']['name'],$taunt);

            //addhistory(1);

            addnews("`%".$session['user']['name']."`5 wurde im Wald von $badguy[creaturename] niedergemetzelt.`n$taunt");

            $session['user']['alive']=0;

            debuglog("lost {$session['user']['gold']} gold when they were slain in the forest");

            $session['user']['gold']=0;

            $session['user']['hitpoints']=0;

            $session['user']['experience']=round($session['user']['experience']*.9,0);

            $session['user']['badguy']="";

            output("`b`&Du wurdest von `%$badguy[creaturename]`& niedergemetzelt!!!`n");

            output("`4Dein ganzes Gold wurde dir abgenommen!`n");

            output("`410% deiner Erfahrung hast du verloren!`n");

            output("Du kannst morgen weiter kÃ¤mpfen.");



            page_footer();

        }else{

          fightnav();

        }

    }

}



if ($_GET['op']==""){

    // Need to pass the variable here so that we show the forest message

    // sometimes, but not others.

    forest($dontdisplayforestmessage);

}



page_footer();



function addhistory($value){

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

        if ($session['user']['superuser']>=3) output("History: {$history[$x]}`n");

    }

    $session['user']['history']=serialize($history);

 */

}

?>

