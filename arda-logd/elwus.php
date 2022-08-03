<?php

//Elwus

require_once "common.php";

page_header("Elwus");

  $badguy = array(
        "creaturename"=>"`$ Elwus, Meister des Feuers`0"
        ,"creaturelevel"=>20
        ,"creatureweapon"=>"Flammenpeitsche"
        ,"creatureattack"=>25
        ,"creaturedefense"=>25
        ,"creaturehealth"=>350
      ,"diddamage"=>0);

    /*$userlevel=$session['user']['level'];
    $userattack=$session['user']['attack'];
    $userhealth=$session['user']['hitpoints'];
    $userdefense=$session['user']['defense'];
    $badguy[creaturelevel]+=$userlevel;
    $badguy[creatureattack]+=$userattack;
    $badguy[creaturehealth]+=$userhealth;
    $badguy[creaturedefense]+=$userdefense;
    $session[user][badguy]=createstring($badguy);*/

    $session[user][badguy]=createstring($badguy);
    $atkflux = e_rand(0,$session['user']['dragonkills']*2);
    $defflux = e_rand(0,($session['user']['dragonkills']*2-$atkflux));
    $hpflux = ($session['user']['dragonkills']*2 - ($atkflux+$defflux)) * 5;
    $badguy['creatureattack']+=$atkflux;
    $badguy['creaturedefense']+=$defflux;
    $badguy['creaturehealth']+=$hpflux;

$battle=true;

if ($battle)
{
    include ("battle.php");
    if ($victory)
    {        
            output("`nDu hast `^".$badguy['creaturename']." besiegt.`n`# Du erhältst einen Orden!");
            $badguy=array();
            $session[user][badguy]="";
            $session[user][orden]+=1;
addnav("Verlasse den Raum","man.php");

    }

    elseif($defeat)
    {
        output("Du wurdest von deinem Meister besiegt! Du bist tot!");

$session[user][hitpoints]=0;
        $session[user][specialinc]="";
        $session[user][reputation]--;
        addnav("Tägliche News","news.php");


    }
    else
    {
        fightnav();
    }
}




page_footer();
?>