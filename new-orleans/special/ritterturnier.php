
<?php 
//°-------------------------°
//|     ritterturnier.php   |
//|        Script by        |
//|        xitachix         |
//|      mcitachi@web.de    |
//°-------------------------°
//http://logd.macjan.de/
//Kampf aus der killeraffen.php

if (!isset($session)) exit();
if ($_GET['op']==""){
output("`n`c`b`8Der geheime Turnierplatz`b`c`n`n");
output("`7Plötzlich findest du dich auf einer Lichtung wieder. `n
    Es sieht aus wie ein riesengroßer Turnierplatz.`n
    Und dies ist er auch. Überall tummeln sich die Leute und wollen die Stärksten Kämpfer sehen.`n
    Möchtest du nicht auch einmal dort kämpfen?`n
    Aus irgendeinem Grund weisst du, dass die Ritter alle ziemlich stark sind.`n");
$session['user']['specialinc']="ritterturnier.php";
addnav("Am Turnier teilnehmen","forest.php?op=turnier");
addnav("Zuschauen","forest.php?op=zuschauen");
addnav("Feige flüchten","forest.php?op=z");
}
if ($_GET['op']=="turnier"){
output("`7Du nimmst all deinen Mut zusammen und willst dich nun den Rittern stellen.`n
    Doch wirst du auch die Kraft haben zu gewinnen?");
$session['user']['specialinc']="ritterturnier.php";
addnav("Nehme Teil","forest.php?op=teilnahme");
}

if ($_GET['op']=="teilnahme"){
output("`7Vor dir steht der Gegner `9SchwertRitter`7, der sich mit seiner Waffe `9Langschwert `7auf dich stürzt");
$badguy = array(
                "creaturename"=>"`9SchwertRitter`0",
                "creaturelevel"=>$session[user][level],
                "creatureweapon"=>"`9Langschwert",
                "creatureattack"=>$session['user']['attack']*0.8,
                "creaturedefense"=>$session['user']['defence']*0.8,
                "creaturehealth"=>round($session['user']['maxhitpoints']*0.8,0),
                "diddamage"=>0);
        $session['user']['badguy']=createstring($badguy);
    $session['user']['specialinc']="ritterturnier.php";
        $battle=true;
        $session['user']['specialinc']="";
}
//Battle Settings
if ($_GET['op']=="run"){   // Flucht
    if (e_rand()%3 == 0){
        output ("`c`b`rDu konntest dem Ritter entkommen!`0`b`c`n");
        $HTTP_GET_VARS[op]="";
    }else{
        output("`c`b`\7Der Ritter war schneller als du!`0`b`c");
        $battle=true;
    }
}
if ($_GET['op']=="fight"){   // Kampf
    $battle=true;
    $session[user][specialinc]="";
}


if ($battle) {
    include("battle.php");
    $session['user']['specialinc']="ritterturnier.php";
        if ($victory){
            $badguy=array();
            $session['user']['badguy']="";
            output("`n`7Du konntest nach einem schweren Kampf den Ritter besiegen!");
            //debuglog("defeated a oger");
            //Navigation
            $session['user']['specialinc']="ritterturnier.php";
            addnav("Weiter","forest.php?op=weiter");
            if (rand(1,5)==1) {
                $gem_gain = rand(1,2);
                $gold_gain = rand($session[user][level]*5,$session[user][level]*10);
                output(" Du findest Du $gem_gain Edelsteine und $gold_gain Goldstücke.`n`n");
            }
            $exp = round($session[user][experience]*0.05);
            output("Durch diesen Kampf steigt Deine Erfahrung um $exp Punkte.`n`n");
            $session[user][experience]+=$exp;
            $session[user][gold]+=$gold_gain;
            $session[user][gems]+=$gem_gain;
        } elseif ($defeat){
            $badguy=array();
            $session[user][badguy]="";
            //debuglog("was killed by a oger.");
            output("`n`7Während du nach oben schaust, siehst du noch einen Ritter, der sich an deinem Edelsteinbeutel zu schaffen macht.
            `n`n`#Du verlierst alle Edelsteine");
            addnav("Tägliche News","news.php");
            addnews("".$session['user']['name']." `7hat beim Ritterturnier kläglich versagt!`0");
            $session[user][alive]=false;
            $session[user][hitpoints]=0;
            $session[user][gems]=0;

            $session[user][experience]=round($session[user][experience]*.95,0);
            $session[user][specialinc]="";
        } else {
            fightnav(true,true);
        }

}
if ($_GET['op']=="weiter"){
$session['user']['specialinc']="ritterturnier.php";
output("`7 Du hast einen Ritter besiegt,");
switch(e_rand(1,5)){
case 1:
case 2:
case 3:
                    output("als auch schon der nächste Ritter angerannt kommt`n");
                    addnav("Kämpfe","forest.php?op=teilnahme");
break;
break;
case 5:
case 6:
                    output("`7Du hast alle Ritter besiegt und bekommst eine hohe Siegesprämie, die in Gold & Edelsteinen gewiegt wird");
                    $gem=(e_rand(1,10));
                    $gold=(e_rand(1000,10000));
                    output("`n`n`^Du bekommst $gem Edelsteine.");
                    output("`n`n`^Du bekommst $gold Gold.");
                    $session['user']['gems']+=$gem;
                    $session['user']['gold']+=$gold;
                    addnews("".$session['user']['name']." `7wurde soeben beim Ritterturnier gefeiert`0");
break;
                   }
}
if ($_GET['op']=="zuschauen"){
                    $angriff=(e_rand(1,3));
                    output("`7Du entschliesst dich, dir mal ein richtiges Ritterturnier anzusehen.`n    
                            Du staunst darüber, wie stark die Ritter sind und schaust sie beeindruckt an.`n
                Irgendwann willst auch du so stark werden.`n
                Du erhälst `#$angriff `7Angriffspunkte!`n
                Verlierst aber 5 Runden für die Zeit, die du verwendet hast!");
                    $session['user']['attack']+=$angriff;
                    $session['user']['turns']-=5;
                    addnews("".$session['user']['name']." `7hat sich ein richtiges Turnier im Wald angesehen!`0");
}
if ($_GET['op']=="z"){
                    $blubb=(e_rand(2,5));
                    output("`7Du möchtest keine Zeit verschwenden und flüchtest in den Wald, wobei du `#$blubb `7Waldkämpfe verlierst .");
                    $session['user']['turns']-=$blubb;
}
?>

