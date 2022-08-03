<?
/*
mazemonster.php part of the Abandonded Castle Mod By Lonnyl @ http://www.pqcomp.com/logd
Author Lonnyl
version 1.01
June 2004
*/
require_once "common.php";
checkday();
page_header("Unheimliche Begegnung");
if ($_GET[op]=="ghost1"){
    $badguy = array(        "creaturename"=>"`@Körperloser Schatten`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Geisterkraft"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>1000
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy[creaturelevel]=$session['user']['level'];
                                $badguy[creatureattack]+=($userattack*.5);
                                $badguy[creaturehealth]+=$userhealth;
                                $badguy[creaturedefense]+=($userdefense*2);
                                $session[user][badguy]=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="ghost2"){
    $badguy = array(        "creaturename"=>"`@Ärgerliche Mumie`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Geisterkraft"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>400
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy[creaturelevel]=$session['user']['level'];
                                $badguy[creatureattack]+=($userattack*.5);
                                $badguy[creaturehealth]+=$userhealth;
                                $badguy[creaturedefense]+=($userdefense*1.5);
                                $session[user][badguy]=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="bat"){
    $badguy = array(        "creaturename"=>"`@Fledermaus`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Spitze Zähne"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>1
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy[creaturelevel]=$session['user']['level'];
                                $badguy[creatureattack]+=($userattack*.5);
                                $badguy[creaturehealth]+=($userhealth*.5);
                                $badguy[creaturedefense]+=($userdefense*.5);
                                $session[user][badguy]=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="rat"){
    $badguy = array(        "creaturename"=>"`@Ratte`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Scharfe Zähne"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>2
                                ,"creaturehealth"=>1
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy[creaturelevel]=$session['user']['level'];
                                $badguy[creatureattack]+=($userattack*.75);
                                $badguy[creaturehealth]+=($userhealth*.75);
                                $badguy[creaturedefense]+=($userdefense*.75);
                                $session[user][badguy]=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}
if ($_GET[op]=="minotaur"){
    $badguy = array(        "creaturename"=>"`@Cherti`0"
                                ,"creaturelevel"=>0
                                ,"creatureweapon"=>"Böses Lächeln"
                                ,"creatureattack"=>1
                                ,"creaturedefense"=>40
                                ,"creaturehealth"=>1000
                                ,"diddamage"=>0);


                                $userattack=$session['user']['attack']+e_rand(1,3);
                                $userhealth=round($session['user']['hitpoints']/2);
                                $userdefense=$session['user']['defense']+e_rand(1,3);
                                $badguy[creaturelevel]=$session['user']['level'];
                                $badguy[creatureattack]+=($userattack-4);
                                $badguy[creaturehealth]+=$userhealth;
                                $badguy[creaturedefense]+=$userdefense;
                                $session[user][badguy]=createstring($badguy);
    redirect("mazemonster.php?op=fight");
}

if ($_GET[op]=="fight" or $_GET[op]=="run"){
$battle=true;
$fight=true;
if ($battle){
    include_once ("battle.php");

    if ($victory){
                output("`b`2Du hast `^".$badguy['creaturename']." `2besiegt.`b`n");
                $badguy=array();
                $session[user][badguy]="";
                $session['user']['specialinc']="";
                $gold=e_rand(100,500);
                $experience=$session[user][level]*e_rand(37,99);
                output("`#Du bekommst `6$gold `#Gold!`n");
                $session[user][gold]+=$gold;
                output("`#Du erhältst `6$experience `#Erfahrungspunkte!`n");
                $session[user][experience]+=$experience;
                addnav("Weiter","abandoncastle.php?loc=".$session['user']['pqtemp']);
    }elseif ($defeat){
                 output("`2Du schlägst am Boden auf, `^".$badguy['creaturename']."`2 läuft davon. Chontamenti freut sich schon auf dich...");
                addnews("$colUser".$session[user][name]."$colText wurde getötet, als ".($session[user][sex]?"sie":"er")." im geheimen Gewölbe von ".$badguy['creaturename']." überfallen wurde.");
                $badguy=array();
                $session[user][badguy]="";
                $session[user][hitpoints]=0;
                $session[user][alive]=0;
                $session['user']['specialinc']="";
                addnav("Weiter","shades.php");
    }else{
            if ($fight){
            fightnav(true,false);
               if ($badguy['creaturehealth'] > 0){
            $hp=$badguy['creaturehealth'];
        }
        }
    }
    }else{
        redirect("abandoncastle.php?loc=".$session['user']['pqtemp']);
    }
}
//I cannot make you keep this line here but would appreciate it left in.
//rawoutput("<div style=\"text-align: left;\"><a href=\"http://www.pqcomp.com\" target=\"_blank\">Abandonded Castle by Lonny @ http://www.pqcomp.com</a><br>");
page_footer();
?> 