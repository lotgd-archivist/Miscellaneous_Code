<?php

/******************

* Author: Hadriel
* Idea: Hadriel
* Filename: fightmaster.php
* Descr: You can find your master in the forest, if your level is under lvl 15

******************/

$ql_="SELECT * FROM masters WHERE creaturelevel=".$session[user][level]."";
$ql=db_query($ql_) or die ("Fehler im Script: `n".mysql_error."");
$master=db_fetch_assoc($ql);

if(!isset($session[user][specialinc])) exit();

if($session[user][level]<15){
  
  switch($_GET[op]){
    case "":
    case "search":
      $session[user][specialinc]="fightmaster.php";
      $ausgabe="Als du im Wald spazierst, triffst du plötzlich deinen Meister ".$master[creaturename]." an."
              ."`nEr bietet dir an, mit ihm zu Kämpfen, damit du ein Level höher kommst.";
      output($ausgabe);
    addnav("Kämpfen","forest.php?op=battle");
    addnav("Lieber nicht","forest.php?op=flee");
    break;
    case "battle":
      $atkflux = e_rand(0,$session['user']['dragonkills']);
                $defflux = e_rand(0,($session['user']['dragonkills']-$atkflux));
                $hpflux = ($session['user']['dragonkills'] - ($atkflux+$defflux)) * 5;
                $master['creatureattack']+=$atkflux;
                $master['creaturedefense']+=$defflux;
                $master['creaturehealth']+=$hpflux;
                $session[user][badguy]=createstring($master);
 
                $battle=true;
    break;
    case "flee":
      $session[user][specialinc]="";
    break;
    case "fight":
    $session[user][specialinc]="fightmaster.php";
      $battle=true;
    break;
    case "run":
    $session[user][specialinc]="fightmaster.php";
      output("`\$Dein Stolz verbietet es dir, vor diesem Kampf wegzulaufen!`0");
        $_GET[op]="fight";
        $battle=true;
    break;
    }
    if($battle){
    $session[user][specialinc]="fightmaster.php";
        if (count($session[bufflist])>0 && is_array($session[bufflist]) || $_GET[skill]!=""){
            $_GET[skill]="";
            if ($_GET['skill']=="") $session['user']['buffbackup']=serialize($session['bufflist']);
            $session[bufflist]=array();
            output("`&Dein Stolz verbietet es dir, während des Kampfes Gebrauch von deinen besonderen Fähigkeiten zu machen!`0");
        }
        if (!$victory) include("battle.php");
        if ($victory){
            //$badguy[creaturegold]=e_rand(0,$badguy[creaturegold]);
            $search=array(    "%s",
                                            "%o",
                                            "%p",
                                            "%X",
                                            "%x",
                                            "%w",
                                            "%W"
                                        );
            $replace=array(    ($session[user][sex]?"sie":"ihn"),
                                            ($session[user][sex]?"sie":"er"),
                                            ($session[user][sex]?"ihr":"sein"),
                                            ($session[user][weapon]),
                                            $badguy[creatureweapon],
                                            $badguy[creaturename],
                                            $session[user][name]
                                        );
            $badguy[creaturelose]=str_replace($search,$replace,$badguy[creaturelose]);
    
            output("`b`&$badguy[creaturelose]`0`b`n"); 
            output("`b`\$Du hast deinen Meister $badguy[creaturename] bezwungen!`0`b`n");
    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/cheer.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);

            $session[user][level]++;
            $session[user][maxhitpoints]+=10;
            $session[user][soulpoints]+=5;
            $session[user][attack]++;
            $session[user][defence]++;
                        $exp=$badguy[creaturelevel]*200;
                        $session[user][experience]+=$exp;
            //$session[user][intelligence]++;
            $session[user][seenmaster]=0;
            output("`#Du steigst auf zu Level `^".$session[user][level]."`#!`n");
            output("Deine maximalen Lebenspunkte sind jetzt `^".$session[user][maxhitpoints]."`#!`n");
            output("Du bekommst einen Angriffspunkt dazu!`n");
            output("Du bekommst einen Verteidigungspunkt dazu!`n");
                        output("Du erhältst ".$exp." Erfahrungspunkte!`n");
            if ($session['user']['level']<15){
                output("Du hast jetzt einen neuen Meister.`n");
            }else{
                output("Keiner im Land ist mächtiger als du!`n");
            }
            if ($session['user']['referer']>0 && $session['user']['level']>=5 && $session['user']['refererawarded']<1){
                $sql = "UPDATE accounts SET donation=donation+25 WHERE acctid={$session['user']['referer']}";
                db_query($sql);
                $session['user']['refererawarded']=1;
                systemmail($session['user']['referer'],"`%Eine deiner Anwerbungen hat's geschafft!`0","`%{$session['user']['name']}`# ist auf Level `^{$session['user']['level']}`# aufgestiegen und du hast deine `^25`# Punkte bekommen!");
            }
            if ($session['user']['level']==10){
                $session['user']['donation']+=1;
            }
            increment_specialty();
        
            addnav("Zurück in den Wald","forest.php?op=flee");
            addnews("`%".$session[user][name]."`3 hat ".($session[user][sex]?"ihren":"seinen")." Meister `%$badguy[creaturename]`3 im Wald an ".($session[user][sex]?"ihrem":"seinem")." `^".ordinal($session[user][age])."`3 Tag besiegt und steigt auf Level `^".$session[user][level]."`3 auf!!");
            $badguy=array();
            $session[user][hitpoints] = $session[user][maxhitpoints];
            $sql="SELECT acctid2,turn FROM pvp WHERE acctid1=".$session[user][acctid]." OR acctid2=".$session[user][acctid]."";
            $result = db_query($sql) or die(db_error(LINK));
            $row = db_fetch_assoc($result);
            if($row[acctid2]==$session[user][acctid] && $row[turn]==0){
                output("`n`6`bDu kannst die offene Herausforderung in der Arena jetzt nicht mehr annehmen.`b");
                $sql = "DELETE FROM pvp WHERE acctid2=".$session[user][acctid]." AND turn=0";
                db_query($sql) or die(db_error(LINK));
            }
            //$session[user][seenmaster]=1;
        }else{
            if($defeat){
                //addnav("Daily news","news.php");
                $sql = "SELECT taunt FROM taunts ORDER BY rand(".e_rand().") LIMIT 1";
                $result = db_query($sql) or die(db_error(LINK));
                $taunt = db_fetch_assoc($result);
                $taunt = str_replace("%s",($session[user][gender]?"ihm":"ihr"),$taunt[taunt]);
                $taunt = str_replace("%o",($session[user][gender]?"er":"sie"),$taunt);
                $taunt = str_replace("%p",($session[user][gender]?"sein":"ihr"),$taunt);
                $taunt = str_replace("%x",($session[user][weapon]),$taunt);
                $taunt = str_replace("%X",$badguy[creatureweapon],$taunt);
                $taunt = str_replace("%W",$badguy[creaturename],$taunt);
                $taunt = str_replace("%w",$session[user][name],$taunt);
                
                addnews("`%".$session[user][name]."`5 wurde von Meister $badguy[creaturename] herausgefordert und verloren!`n$taunt");
                //$session[user][alive]=false;
                //$session[user][gold]=0;
                $session[user][hitpoints]=$session[user][maxhitpoints];
                output("`&`bDu wurdest von `%$badguy[creaturename]`& besiegt!`b`n");
                output("`%$badguy[creaturename]`\$ hält vor dem vernichtenden Schlag inne und reicht dir stattdessen seine Hand, um dir auf die Beine zu helfen. Er verabreicht dir einen kostenlosen Heiltrank.`n");
                $search=array(    "%s",
                                                "%o",
                                                "%p",
                                                "%x",
                                                "%X",
                                                "%W",
                                                "%w"
                                            );
                $replace=array(    ($session[user][gender]?"ihm":"ihr"),
                                                ($session[user][gender]?"er":"sie"),
                                                ($session[user][gender]?"sein":"ihr"),
                                                ($session[user][weapon]),
                                                $badguy[creatureweapon],
                                                $badguy[creaturename],
                                                $session[user][name]
                                            );
                $badguy[creaturewin]=str_replace($search,$replace,$badguy[creaturewin]);
                output("`^`b$badguy[creaturewin]`b`0`n");
            addnav("Zurück in den Wald","forest.php?op=flee");
                //$session[user][seenmaster]=1;  not needed in this script
            }else{
              fightnav(false,false);
            }
        }
    }
    
  
  }else{
    redirect("forest.php?op=search");
    }
?> 