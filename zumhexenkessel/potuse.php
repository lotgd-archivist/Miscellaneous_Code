<?php
#########################################
#                                       #
#  Tranktasche                          #
#  Tranktasche für Spieler              #
#  by Laserian                          #
#  v 1.0                                #
#                                       #
#########################################
require_once "common.php";
popup_header("Tranktasche");
$acctid=$session['user']['acctid'];
$sql = "SELECT * FROM potions WHERE acctid=".$acctid;
$result=db_query($sql);
$count = db_num_rows($result);
output("`^");
if($_GET['op']==""){
if($count==0){
output("Du besitzt keine Tränke.");
}else{
for($i=0;$i<$count;$i++){
$row = db_fetch_assoc($result);
output($row['name']." <a href='potuse.php?op=use&act=".$row['name']."'>Benutzen</a>`n`n",true);
addnav("","potuse.php?op=use&act=".$row['name']."");
}
}
}
if($_GET['op']=="use"){
$row = db_fetch_assoc($result);
switch($_GET['act']){
case "Leichter Heiltrank":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $life=e_rand(5,15)+$row['trankbrau']+$row['umgang'];
        output("Der Trank schließt einige deiner Wunden");
        $session['user']['hitpoints']+=$life;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
        if($session['user']['hitpoints']>=$session['user']['maxhitpoints']){
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
    }elseif($row['type']==1){
        $turns=e_rand(25,100)-($row['trankbrau']+$row['umgang']);
        if($turns<5)$turns=5;
        $life=e_rand(10,20)+($row['trankbrau']+$row['umgang'])*2;
        $attack=e_rand(3,8)/10+$trank+$umgang;
        if($attack>.95) $attack=.95;
        $defence=e_rand(3,8)/10+$trank+$umgang;
        if($defence>.95) $defence=.95;
        output("Der Trank schließt viele deiner Wunden, schwächt dich allerdings ein wenig.");
        $session['user']['hitpoints']+=$life;
        $buff=array(
        "name"=>"`4Schwäche",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder besser.",
        "atkmod"=>$attack,
        "defmod"=>$defence,
        "roundmsg"=>"`4Du fühlst dich schwach.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['heilschwaeche']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
        if($session['user']['hitpoints']>=$session['user']['maxhitpoints']){
            output("`nDu spürst wie der Trank nicht nur deine Wunden schließt, sondern dich noch dazu etwas stärkt.");
        }
    }elseif($row['type']==2){
        $turns=e_rand(25,100)-($row['trankbrau']+$row['umgang']);
        if($turns<5)$turns=5;
        $attack=e_rand(3,7)/10+$trank+$umgang;
        if($attack>.9) $attack=.9;
        $defence=e_rand(3,7)/10+$trank+$umgang;
        if($defence>.9) $defence=.9;
        output("Der Trank war ein Gift! Dein Angriff und deine Verteidigung werden gesenkt.");
        $buff=array(
        "name"=>"`4Vergiftung",
        "rounds"=>$turns,
        "wearoff"=>"`^Die Vergiftung lässt nach.",
        "atkmod"=>$attack,
        "defmod"=>$defence,
        "roundmsg"=>"`4Das Gift senkt deine Kampfkraft.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['heilgift']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Heiltrank":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $life=e_rand(10,25)+($row['trankbrau']+$row['umgang'])*2;
        output("Der Trank schließt einige deiner Wunden");
        $session['user']['hitpoints']+=$life;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
        if($session['user']['hitpoints']>=$session['user']['maxhitpoints']){
            $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        }
    }elseif($row['type']==1){
        $turns=e_rand(25,100)-($row['trankbrau']+$row['umgang']);
        if($turns<5)$turns=5;
        $life=e_rand(15,45)+($row['trankbrau']+$row['umgang'])*e_rand(3,4);
        $attack=e_rand(3,8)/10+($trank+$umgang)/2;
        if($attack>.9) $attack=.9;
        $defence=e_rand(3,8)/10+($trank+$umgang)/2;
        if($defence>.9) $defence=.9;
        output("Der Trank schließt viele deiner Wunden, allerdings fühlst du dich nicht mehr so stark.");
        $session['user']['hitpoints']+=$life;
        $buff=array(
        "name"=>"`4Schwäche",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder besser.",
        "atkmod"=>$attack,
        "defmod"=>$defence,
        "roundmsg"=>"`4Du fühlst dich schwach.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['heilschwaeche2']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
        if($session['user']['hitpoints']>=$session['user']['maxhitpoints']){
            output("`nDu spürst wie der Trank nicht nur deine Wunden schließt, sondern dich noch dazu etwas lebhafter macht.");
        }
    }elseif($row['type']==2){
        $life=e_rand(2,5)/10+$trank+$umgang;
        if($life>.75) $life=.75;
        output("Der Trank war ein Gift! Du verlierst viele Lebenspunkte.");
        $session['user']['hitpoints']*=$life;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Schlafgift":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $turns=e_rand(5,10)+($row['trankbrau']+$row['umgang']);
        if($turns>50)$turns=50;
        $badatk=e_rand(7,10)/10-$trank-$umgang;
        if($badatk<.5) $badatk=.5;
        $baddef=e_rand(7,10)/10-$trank-$umgang;
        if($baddef<.5) $baddef=.5;
        output("Das Gift wird deinen Gegner schwächen.");
        $buff=array(
        "name"=>"Schlafgift",
        "rounds"=>$turns,
        "wearoff"=>"`4Das Gift wirkt nicht mehr.",
        "badguyatkmod"=>$badatk,
        "badguydefmod"=>$baddef,
        "roundmsg"=>"`@Das Gift schwächt deinen Gegner.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['giftgood']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $turns=e_rand(15,20)+($row['trankbrau']+$row['umgang'])*2;
        if($turns>70)$turns=70;
        $badatk=e_rand(6,9)/10-$trank-$umgang;
        if($badatk<.35) $badatk=.35;
        $baddef=e_rand(6,9)/10-$trank-$umgang;
        if($baddef<.35) $baddef=.35;
        output("Das Gift wird deinen Gegner schwächen.");
        $buff=array(
        "name"=>"Schlafgift",
        "rounds"=>$turns,
        "wearoff"=>"`4Das Gift wirkt nicht mehr.",
        "badguyatkmod"=>$badatk,
        "badguydefmod"=>$baddef,
        "roundmsg"=>"`@Das Gift schwächt deinen Gegner.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['giftgood2']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $turns=e_rand(25,100)-($row['trankbrau']+$row['umgang']);
        if($turns<5)$turns=5;
        $badatk=e_rand(15,20)/10-$trank-$umgang;
        if($badatk<1.25) $badatk=1.25;
        $baddef=e_rand(15,20)/10-$trank-$umgang;
        if($baddef<1.25) $baddef=1.25;
        output("`4Das Gift wurde falsch gebraut und stärkt nun deinen Gegner!");
        $buff=array(
        "name"=>"Gifttrank",
        "rounds"=>$turns,
        "wearoff"=>"`^Das Gift wirkt nicht mehr.",
        "badguyatkmod"=>$badatk,
        "badguydefmod"=>$baddef,
        "roundmsg"=>"`4Das Gift stärkt deinen Gegner.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['giftbad']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Lähmungsgift":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $turns=e_rand(5,15)+($row['trankbrau']+$row['umgang']);
        if($turns>50)$turns=50;
        $badatk=e_rand(6,9)/10+$trank+$umgang;
        if($badatk<.5) $badatk=.5;
        $baddef=e_rand(6,9)/10+$trank+$umgang;
        if($baddef<.5) $baddef=.5;
        output("Das Gift wird deinen Gegner lähmen.");
        $buff=array(
        "name"=>"Lähmungsgift",
        "rounds"=>$turns,
        "wearoff"=>"`4Das Gift wirkt nicht mehr.",
        "badguyatkmod"=>$badatk,
        "badguydefmod"=>$baddef,
        "roundmsg"=>"`@Das Gift schwächt deinen Gegner.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['giftlaehm']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $turns=e_rand(15,20)+($row['trankbrau']+$row['umgang'])*2;
        if($turns>70)$turns=70;
        $badatk=e_rand(3,7)/10-$trank-$umgang;
        if($badatk<.25) $badatk=.25;
        $baddef=e_rand(3,7)/10-$trank-$umgang;
        if($baddef<.25) $baddef=.25;
        output("Das Gift wird deinen Gegner lähmen.");
        $buff=array(
        "name"=>"Lähmungsgift",
        "rounds"=>$turns,
        "wearoff"=>"`4Das Gift wirkt nicht mehr.",
        "badguyatkmod"=>$badatk,
        "badguydefmod"=>$baddef,
        "roundmsg"=>"`@Das Gift schwächt deinen Gegner.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['giftlaehm2']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $turns=e_rand(25,100)-($row['trankbrau']+$row['umgang']);
        if($turns<5)$turns=5;
        output("Als du das Gift öffnest entweichen giftige Dämpfe die dich benebeln.");
        $buff=array(
        "name"=>"Giftige Dämpfe",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder besser.",
        "mingoodguydamage"=>0,
        "maxgoodguydamage"=>$session['user']['level']*2,
        "roundmsg"=>"`4Du fühlst dich unwohl.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['giftdampf']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Steinhauttrank":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $turns=e_rand(5,10)+($row['trankbrau']+$row['umgang']);
        if($turns>50)$turns=50;
        $defence=e_rand(10,13)/10+$trank+$umgang;
        if($defence>1.5) $defence=1.5;
        output("Deine Haut fühlt sich härter an und du bist dir sicher, dass du von nun an weniger Schaden erleiden wirst.");
        $buff=array(
        "name"=>"`7Steinhaut",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder normal.",
        "defmod"=>$defence,
        "roundmsg"=>"`@Deine Haut ist hart wie Stein.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['steinhaut']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $turns=e_rand(15,20)+($row['trankbrau']+$row['umgang']);
        if($turns>70)$turns=70;
        $defence=e_rand(12,15)/10+$trank+$umgang;
        if($defence>2) $defence=2;
        output("Deine Haut fühlt sich härter an und du bist dir sicher, dass du von nun an weniger Schaden erleiden wirst.");
        $buff=array(
        "name"=>"`7Steinhaut",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder normal.",
        "defmod"=>$defence,
        "roundmsg"=>"`@Deine Haut ist hart wie Stein.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['steinhaut2']=$buff;
        $session['user']['turns']-=5;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $turns=e_rand(25,100)-($row['trankbrau']+$row['umgang']);
        if($turns<5)$turns=5;
        $defence=e_rand(2,5)/10+$trank+$umgang;
        if($defence>.8) $defence=.8;
        output("Der Trank ist falsch gebraut worden und macht dich verwundbarer.");
        $buff=array(
        "name"=>"`4Verwundbarkeit",
        "rounds"=>$turns,
        "wearoff"=>"`^Die Wirkung des Tranks lässt nach.",
        "defmod"=>$defence,
        "roundmsg"=>"`4Der Trank macht dich verwundbarer.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['verwundbar']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Krafttrank":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $turns=e_rand(15,20)+$row['trankbrau']+$row['umgang'];
        if($turns>70)$turns=70;
        $attack=e_rand(10,13)/10+$trank+$umgang;
        if($attack>1.5) $attack=1.5;
        $buff=array(
        "name"=>"`QKräftigung",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder normal.",
        "atkmod"=>$attack,
        "roundmsg"=>"`4Du fühlst dich stark.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['kraft']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $turns=e_rand(25,30)+$row['trankbrau']+$row['umgang'];
        if($turns>100)$turns=100;
        $attack=e_rand(12,15)/10+$trank+$umgang;
        if($attack>2) $attack=2;
        $buff=array(
        "name"=>"`^Stärkung",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder normal.",
        "atkmod"=>$attack,
        "roundmsg"=>"`4Du fühlst dich schwach.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['kraft2']=$buff;
        $session['user']['turns']-=5;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $turns=e_rand(25,100)-($row['trankbrau']+$row['umgang']);
        if($turns<5)$turns=5;
        $attack=e_rand(6,9)/10+$trank+$umgang;
        if($attack<.5) $attack=.5;
        output("Der Trank wurde falsch gebraut und du fühlst wie dich die Kraft verlässt.");
        $buff=array(
        "name"=>"`4Schwäche",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder normal.",
        "atkmod"=>$attack,
        "roundmsg"=>"`4Du fühlst dich schwach.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['schwaeche']=$buff;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Lebenstrank":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $life=e_rand(1,5)+$row['umgang']/4+$row['trankbrau']/4;
        output("Du spürst wie du dich etwas stärker fühlst.");
        $session['user']['maxhitpoints']+=$life;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $life=e_rand(1,5)+$row['umgang']/2+$row['trankbrau']/2;
        output("Du spürst wie du dich stärker fühlst, und du dich erholst.");
        $session['user']['maxhitpoints']+=$life;
        $session['user']['hitpoints']=$session['user']['maxhitpoints'];
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $life=e_rand(1,3)/10+$trank+$umgang;
        output("Der Trank war ein starkes Gift. Du verlierst viele Lebenspunkte.");
        $session['user']['hitpoints']=$life;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Schönheitstrank":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        $charm=e_rand(20,30)+$row['umgang']+$row['trankbrau'];
        if($charm>50) $charm=50;
        output("Du trinkst den Trank und blickst in den Spiegel... Du siehst wie du dich veränderst.");
        $session['user']['charme']+=$charm;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $charm=e_rand(15,20)+($row['umgang']+$row['trankbrau'])*2;
        if($charm>70) $charm=70;
        output("Du trinkst den Trank und willst in den Spiegel blicken, doch du ziehst es aufgrund der Übelkeit die dich überkommt vor erst auf die Toilette zu gehen.
        Nachdem du dich übergeben hast, gehst du zum Spiegel und stellst fest, dass der Trank gewirkt hat. Der Spruch: \"Wer schön sein will, muss leiden.\" scheint tatsächlich zu stimmen.");
        $session['user']['charme']+=$charm;
        $session['user']['hitpoints']*=.75;
        $session['user']['turns']*=.5;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $charm=e_rand(20,30)-$row['umgang']-$row['trankbrau'];
        if($charm<5) $charm=5;
        output("Du trinkst den Trank und blickst in den Spiegel... Der Trank scheint falsch gebraut worden zu sein.");
        $session['user']['charme']-=$charm;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Elixier der Stärke":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        output("Du trinkst den Trank und fühlst dich etwas stärker. Dein Angriff wurde permanent um 1 erhöht!");
        $session['user']['attack']++;
        $session['alchemie']['permatt']++;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $turns=e_rand(5,10)+$row['umgang']+$row['trankbrau'];
        $attack=e_rand(15,20)/10+$trank+$umgang;
        output("Du trinkst den Trank und fühlst dich etwas stärker. Dein Angriff wurde permanent um 1 erhöht!");
        $buff=array(
        "name"=>"`6Stärke",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder normal.",
        "atkmod"=>$attack,
        "roundmsg"=>"`UDu fühlst dich stark.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['staerke']=$buff;
        $session['user']['attack']+=1;
        $session['alchemie']['permatt']+=1;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $life=e_rand(20,100);
        output("Der Trank war falsch gebraut. Du verlierst Lebenspunkte.");
        $session['user']['hitpoints']-=$life;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
    
case "Elixier des Geschicks":
$sql = "SELECT * FROM potions WHERE acctid=".$acctid." AND name='".$_GET['act']."' LIMIT 1";
$result=db_query($sql);
$row=db_fetch_assoc($result);
$trank=$row['trankbrau']/100;
$umgang=$row['umgang']/100;
    if($row['type']==0){
        output("Du trinkst den Trank und fühlst dich geschickter. Deine Verteidigung wurde permanent um 1 erhöht!");
        $session['user']['defence']+=1;
        $session['alchemie']['permdef']+=1;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"0\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==1){
        $turns=e_rand(5,10)+$row['umgang']+$row['trankbrau'];
        $defence=e_rand(15,20)/10+$trank+$umgang;
        output("Du trinkst den Trank und fühlst dich geschickter. Deine Verteidigung wurde permanent um 1 erhöht!");
        $buff=array(
        "name"=>"`6Geschick",
        "rounds"=>$turns,
        "wearoff"=>"`^Du fühlst dich wieder normal.",
        "defmod"=>$defence,
        "roundmsg"=>"`BDu fühlst dich unverwundbar.",
        "activate"=>"roundstart"
        );
        $session['bufflist']['geschick']=$buff;
        $session['user']['defence']+=1;
        $session['alchemie']['permdef']+=1;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"1\" LIMIT 1";
        db_query($del);
    }elseif($row['type']==2){
        $life=e_rand(20,100);
        output("Der Trank war falsch gebraut. Du verlierst Lebenspunkte.");
        $session['user']['hitpoints']-=$life;
        $del = "DELETE FROM `potions` WHERE `acctid`=".$acctid." AND `name`='".$_GET['act']."' AND `type`=\"2\" LIMIT 1";
        db_query($del);
    }else{
        output("`\$FEHLER! Bitte an den Admin wenden.");
    }
break;
}
addnav("Zurück","potuse.php");
}
$copyright ="<div align='center'><a href=http://www.lotgd-midgar.de/index.php target='_blank'>&copy;`#Laserian`0</a></div>";
output("`n`n`n`n$copyright`n ",true);
popup_footer();
?> 