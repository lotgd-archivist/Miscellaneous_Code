<?php 
//°-------------------------°
//|    killerhaeschen.php   |
//|         Idee by         |
//|       Arina & Sari      |
//|        Script by        |
//|        xitachix         |
//|      mcitachi@web.de    |
//°-------------------------°
//http://logd.macjan.de/
//Kampf aus der killeraffen.php

if (!isset($session)) exit();
if ($_GET['op']==""){
output("`n`c`b`8Die Kaninchenlichtung.`b`c`n`n");
output("`(Plötzlich findest du dich auf einer Lichtung wieder. 
        Überall sind kleine rosa Kaninchen, die dich mit ihren großen Kulleraugen anstarren. 
        Du könntest jetzt auf der Wiese bleiben, um mit den Kaninchen zu schmusen, oder wieder gehen.");

$session['user']['specialinc']="killerhaeschen.php";
addnav("Mit den Kaninchen schmusen","forest.php?op=schmusen");
addnav("Zurück in den Wald","forest.php?op=z");
}
if ($_GET['op']=="schmusen"){
output("`(Du setzt dich zu den Kaninchen auf die Wiese. Plötzlich verwandeln sich die kleinen Kaninchen und ihre Kulleraugen verwandeln sich in kleine Feuerbälle. 
        Ihre Krallen werden länger und schärfer und sie stürzen sich mit ihren Reißzähnen auf dich!");
$session['user']['specialinc']="killerhaeschen.php";
addnav("Kämpfe","forest.php?op=kampf");
}

if ($_GET['op']=="kampf"){
output("`(Du entdeckst den Gegner `%Killerkaninchen`(, der sich mit seiner Waffe `%Reißzähne und Riesenkrallen`( auf dich stürzt!");
$badguy = array(
                "creaturename"=>"`%Killerkaninchen`0",
                "creaturelevel"=>$session[user][level],
                "creatureweapon"=>"`%Reißzähne und Riesenkrallen",
                "creatureattack"=>$session['user']['attack']*0.8,
                "creaturedefense"=>$session['user']['defence']*0.8,
                "creaturehealth"=>round($session['user']['maxhitpoints']*0.8,0),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
        $session['user']['specialinc']="killerhaeschen.php";
        $battle=true;
        $session['user']['specialinc']="";
}
//Battle Settings
if ($_GET['op']=="run"){   // Flucht
    if (e_rand()%3 == 0){
        output ("`c`b`rDu konntest dem Hasen entkommen!`0`b`c`n");
        $_GET[op]="";
    }else{
        output("`c`b`\(Der Hase war schneller als du!`0`b`c");
        $battle=true;
    }
}
if ($_GET['op']=="fight"){   // Kampf
    $battle=true;
    $session[user][specialinc]="";
}


if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="killerhaeschen.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            output("`n`(Du konntest nach einem schweren Kampf den Hasen besiegen!");
            //debuglog("defeated a oger");
            //Navigation
            $session['user']['specialinc']="killerhaeschen.php";
            addnav("Weiter","forest.php?op=weiter");
            if (rand(1,5)==1) {
                $gem_gain = rand(1,2);
                $gold_gain = rand($session[user][level]*5,$session[user][level]*10);
                output(" Du findest Du $gem_gain Edelsteine und $gold_gain Goldstücke.`n`n");
            }
            $exp = e_rand(round($session[user][experience]*0.005),round($session[user][experience]*0.015));
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session[user][experience]+=$exp;
            $session[user][gold]+=$gold_gain;
            $session[user][gems]+=$gem_gain;
        } elseif ($defeat){
            $badguy=array();
            $session[user][badguy]="";
            //debuglog("was killed by a oger.");
            output("`n`(Während du deinen letzten Atemzug ziehst, hoppelt ein Kaninchen auf deine Brust und grinst dich frech an.`n 
                `%“Der Schein trügt!“ `(.`n Du bist Tot. Du verlierst 5% deiner Erfahrung. Du kannst morgen wieder kämpfen!");
            addnav("Tägliche News","news.php");
            addnews("".$session['user']['name']." `(wurde von einem Kuschelkaninchen zerfleischt!`0");
            $session[user][alive]=false;
            $session[user][hitpoints]=0;
//            $session[user][gems]=0;
            $session[user][gold]=0;
            $session[user][experience]=round($session[user][experience]*.95,0);
            $session[user][specialinc]="";
        } else {
            fightnav(true,true);
        }

}
if ($_GET['op']=="weiter"){
        $session['user']['specialinc']="killerhaeschen.php";
        output("`( Du hast ein Kaninchen besiegt, ");
        switch(e_rand(1,5)){
        case 1:
        case 2:
        case 3:
                output("als auch schon das nächste angehoppelt kommt`n");
                addnav("Kämpfe","forest.php?op=kampf");
                break;
        case 4:
        case 5:
                output("`(und nun liegen all die kleinen Kaninchen tot auf der Lichtung und du bekommst leichte Skrupel, solch süße Wesen umgebracht zu haben.
                        Doch die Edelsteine, die du findest, wischen alle Skrupel weg und du sammelst sie eilig ein.");
                $gem=(e_rand(3,10));
                output("`n`n`^Du bekommst $gem Edelsteine.");
                $session['user']['gems']+=$gem;
                $session['user']['specialinc']="";
//                addnews("".$session['user']['name']." `(hat kleine, süße Kaninchen umgebracht`0");
                break;
        }
}

if ($_GET['op']=="z"){
        output("`(Du möchtest keine Zeit verschwenden und gehst zurück in den Wald. Seufzend guckst du dich noch mal nach den süßen Kaninchen um, doch du bekräftigst dich selbst in der Entscheidung und gehst weiter in den Wald.");
}
?> 