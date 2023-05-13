
<?php
/* idea of bibir (logd_bibir@email.de)
*      and Chaosmaker (webmaster@chaosonline.de)
*      for http://logd.chaosonline.de
*
* idea:
*     you can find this thieving ape and fight against him
* details:
*      (09.11.04) start of idea
* todo:
*    find the gems others lost by him (setting 'apegems')
*/

/*
 * settings
 */
$special = basename(__FILE__);


if ($_GET['op']=="leave") {
    $session['user']['specialinc'] = "";
    output("`tDu denkst, dass der Affe einfach zu fix für dich ist,
    da er dir schon massig `#Edelsteine`t abgenommen hat.
    Also flüchtest du und bist ihn los - diesmal.`0");

}
elseif ($_GET['op']=="attack") {
    output("`tGlücklich darüber, dass du diesmal dem Affen zuvorgekommen bist,
    stürzt du dich mit lautem Geschrei auf ihn.`n");
    $session['user']['turns']--;
    switch (e_rand(1,6)) {
        case 1:
            $session['user']['specialinc'] = "";
            output("`tDer Affe entdeckt dich zu spät und ist somit von deiner
            schnellen Hand auch schon erledigt worden.`n");
            $gain = getsetting("apegems","0");
            $realgain = e_rand($gain/3,$gain);
            output("Du erbeutest `#".$realgain." Edelsteine`t, die er bei sich trug.`0");
            $session['user']['gems'] += $realgain;
            savesetting("apegems",$gain-$realgain);
            break;
        case 2:
            $session['user']['specialinc'] = "";
            output("`tDer Affe bemerkt dein Schreien noch rechtzeitig und schwingt
            sich in den nächsten Baum.`n
            Dort oben hast du keine Chance und somit musst du unverrichteter Dinge
            weiterziehen.`0");
            break;
        case 3:
        case 4:
            $session['user']['specialinc'] = $special;
            output("`6Der Affe`t sieht deine gezogene Waffe und deinen Zorn und nimmt
            einen `6stabilen Stock`t vom Waldboden auf, um gegen dich anzutreten.");
            $badguy = array("creaturename"=>"Diebisches Äffchen",
                    "creaturelevel"=>$session['user']['level'],
                    "creatureweapon"=>"Stabiler Stock",
                    "creatureattack"=>$session['user']['attack'],
                    "creaturedefense"=>$session['user']['defence'],
                    "creaturehealth"=>$session['user']['hitpoints']+50,
                    "diddamage"=>0);
            $session['user']['badguy']=createstring($badguy);
            $_GET['op'] = "fight";
            break;
        case 5:
            $session['user']['specialinc'] = "";
            output("`tDer Affe sieht dich erschrocken an und streckt dir die Hände
            entgegen, in denen einer deiner `#Edelsteine`t liegt.`n
            Du überlegst dir, dass es doch ein süßes Geschöpf ist, nimmst den
            Edelstein und lässt den Affen leben.`n
            `6Dieser führt freudig noch ein Tänzchen auf, das dich erheitert.
            Zum Glück hast du keine Zeit vertrödelt.`0");
            $session['user']['turns']++;
            $session['user']['gems']++;
            break;
        case 6:
            $session['user']['specialinc'] = "";
            output("`tIn deiner Wut stürmst du auf ihn zu, ohne darauf zu achten, wie unwegsam der Boden ist. Schon bist du über
            eine Wurzel gestolpert.");
            if ($session['user']['gems']>0) {
                $gems2lose = min($session['user']['gems'],$session['user']['dragonkills']);
                $lost = e_rand(1,$gems2lose/5+1);
                output("`tWie das Unglück so will, ist dein Beutel dabei zerrissen und nun versuchst du, die Edelsteine wieder
                einzusammeln. Der Affe hingegen hat das gesehen und klaut dir den ein oder anderen Stein.`n
                Als du nachzählst, bemerkst du, dass du nun `#".$lost." Edelsteine`t weniger hast.`0");
                $session['user']['gems'] -= $lost;
                $gain = getsetting("apegems",0)+$lost;
                savesetting("apegems",$gain);
            }
            else {
                $hurt = round($session['user']['hitpoints']*0.1);
                output("`tDu stürzt und als du dich wieder aufgerappelt hast, ist der Affe auch schon verschwunden.
                Dieser Sturz hat dich `4".$hurt."`t Lebenspunkte gekostet.`0");
                $session['user']['hitpoints'] -= $hurt;

            }
            break;
        default:
            output("tztzt...was machst du denn?");
    }
}
elseif ($_GET['op']=="") {
    $session['user']['specialinc'] = $special;
    output("`tDu siehst dieses zottelige Etwas von diebischem Affen durch den Wald
    auf dich zukommen. Genervt von seiner `qEdelsteinklauerei`t, die dich schon den ein oder
    anderen `#Edelstein`t gekostet hat, überlegst du,
    <a href=\"forest.php?op=attack\">dein Schwert zu ziehen</a> und ihm anstelle eines Monsters
    den Garaus zu machen oder
    <a href=\"forest.php?op=leave\">das Weite zu suchen</a>.`0",true);
    addnav("","forest.php?op=attack");
    addnav("A?Den Affen angreifen","forest.php?op=attack");
    addnav("","forest.php?op=leave");
    addnav("W?Das Weite suchen","forest.php?op=leave");
}
elseif ($_GET['op']=="run") {
    output('`c`b`$Es gelingt dir nicht, vor dem Affen zu fliehen!`0`b`c');
    $session['user']['specialinc'] = $special;
}

if ($_GET['op']=="fight" || $_GET['op']=="run") {
    $battle=true;
}
if ($battle) {
    include("battle.php");
    $session['user']['specialinc'] = $special;
    if ($victory) {
        $badguy=array();
//        updatetexts('badguy','');
        output("`n`6Jetzt hast du dem Affen gezeigt, was für eine Strafe auf Diebstahl steht.");
        $gain = e_rand(1,2);
        output("`nNach deinem Sieg nimmst du dir, was dir deiner Meinung nach zusteht. Du findest beim Affen `#".$gain." Edelstein".($gain!=1?'e':'')."`6.`n");
        $session['user']['gems'] += $gain;
        $exp_gain = e_rand(($session['user']['level']+1)*18,($session['user']['level']+1)*22);
        output("`#Du bekommst `^".$exp_gain."`# Erfahrungspunkte.`0`n`n");
        $session['user']['experience'] += $exp_gain;
        $session['user']['specialinc'] = "";
        $badguy=array();
        $session['user']['badguy']="";
    }
    elseif ($defeat) {
        $badguy = array();
//        updatetexts('badguy','');
        output("`n`tDu wurdest vom diebischen Affen besiegt! `nDu verlierst 10% deiner Erfahrung".($session['user']['gems']>0?" und vermisst plötzlich einen deiner Edelsteine":"").".");
        if ($session['user']['gems']>0) $session['user']['gems']--;
        $session['user']['alive'] = false;
        $session['user']['hitpoints'] = 0;
        $session['user']['experience'] = round($session['user']['experience']*0.9);
        $session['user']['specialinc'] = "";
//        locnav('KILLED');
        addnews("`t".$session['user']['name']."`t konnte den diebischen Affen nicht bezwingen.");
        $badguy=array();
        $session['user']['badguy']="";
        addnav("Tägliche News","news.php");
        
    }
    else {
        fightnav(true,true);
    }
}


