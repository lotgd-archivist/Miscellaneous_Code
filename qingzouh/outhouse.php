
<?php
/**
* Version:    0.6
* Date:        July 31, 2003
* Author:    John J. Collins
* Email:    collinsj@yahoo.com
* 
* Purpose:    Provide a fun module to Legend of the Green Dragon
* Program Flow:    The player can choose to use the Private or Public Toilet. It costs Gold
* to use the Private Toilet. The Public Toilet is free. After using one of the toilet's,
* the players can wash their hands or return. If they choose to wash their hands, there is a
* chance that they can get their gold back. If they don't choose to wash their hands, there
* is a chance that they will lose some gold. If they loose gold there is an entry added
* to the daily news.
*/
require_once("common.php");

// How much does it cost to use the Private Toilet?
$cost = 5;
// How much gold must user have in hand before they can lose money
$goldinhand = 1;
// How much gold to give back if the player is rewarded for washing their hands
$giveback = 3;
// How much gold to take if the user is punished for not washing their hands
$takeback = 1;
// Minium random number for good habits
$goodminimum = 1;
// Maximum randdom number for good habits
$goodmaximum = 10;
// Odds of getting your money back
$goodmusthit = 6;
// Minimum random number for bad habits
$badminimum = 1;
// Maximum random number for bad habits
$badminimum = 4;
// Odds of losing money
$badmusthit = 2;
// Turn on to give the player a chance of finding a Gem if they visit the Private Toilet and Wash their hands.
// Turn on = 1
// Turn off = 0
$giveagem = 1;
// Give a gem if you visit the pay toilet and wash your hands. 1 in 4 chance of getting the gem.
// How often do you want to give out a Gem?
// Default is 1 out of 4 odds.
$givegempercent = 25;
$gemminimum = 1;
$gemmaximum = 100;
// Do you want to give the player a turn if they use the Pay Toilet and wash their hands.
// 1 give a turn
// 0 does not give an extra turn
$giveaturn = 0;
// Where do you want the player to go after leaving here?
// Usually this is the forest, you don't want no stinking toilet in the village do you, but can be anywhere.
$returnto = "forest.php";
// Does the player have enough gold to use the Private Toilet?


//You should really not have to edit anything below this line!
if ($session[user][gold] >= $cost) $canpay = True;

if ($_GET[op] == "pay"){
    page_header("Private Toilette");
    set_special_val('usedouthouse', 1);
    output("`n`n`n`n`c`TDu bezahlst die `Â$cost Gold`T an den `âKlo-Gnom`T für die Erlaubnis, das private Klo zu benutzen.`n`c`0");
    output("`c`TDies ist das sauberste Plumpsklo im ganzen Land!`n`c`0");
    output("`c`TDer `âKlopapier-Gnom`T sagt dir noch, dass du einfach fragen sollst, wenn du noch etwas brauchst.`n`c`0");
    output (($session[user][sex]?"`TSie":"`TEr") . " dreht dir höflich `0");
    output (($session[user][sex]?"`Tihren":"`Tseinen") . " `TRücken zu und schließt die Reinigung des Waschstandes ab.`n`n`0");
    $session[user][gold] -= $cost;
    //debuglog("spent $cost gold to use the outhouse");
    addnav("Hände waschen", "outhouse.php?op=washpay");
    addnav("Verlassen", "outhouse.php?op=nowash");
}elseif ($_GET[op] == "free"){
    page_header("Öffentliche Toilette!");
    set_special_val('usedouthouse', 1);
    output("`c`n`n`n`n`TDer furchtbare Gestank treibt dir Tränen in die Augen und deine Nasenhaare kräuseln sich!`n`c`0");
    output("`c`TNachdem er sich die Nase damit geputzt hat, überreicht dir der Klopapier-Gnom ein Blatt einlagiges Klopapier.`n`c`0");
    output("`c`TDu entschliesst dich, dieses Teil lieber nicht zu benutzen, nachdem du seine Hände gesehen hast.`n`n`c`0");
    output("`c`TBeinahe rutschst du in das große Loch in der Mitte des Raumes, während du " . ($session[user][sex]?"darüber in die Hocke gehst":"dich darüber stellst") . ". Der `âKlopapier-Gnom`T beobachtet dich bei deinem Geschäft sehr genau.`n`c`0");
    output("`c`TDu machst so schnell du kannst, denn so arg lange kannst du die Luft nicht anhalten.`n`c`0");
    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/ogpissd.wav\" width=10 height=10 autostart=true loop=false hidden=true volume=100>",true);
    addnav("Hände waschen", "outhouse.php?op=washfree");
    addnav("Verlassen", "outhouse.php?op=nowash");
}elseif ($_GET[op] == "washpay"|| $_GET[op] == "washfree"){
    page_header("Wasch Stand");
    output("`c`n`n`n`n`THände waschen ist immer eine gute Sache. Du machst dich zurecht, bringst dein(e/n) {$session['user']['armor']} in Ordnung und betrachtest dein Spiegelbild im Wasser. Dann machst du dich wieder auf den Weg.`c`0`n");
    $goodhabits = e_rand($goodminimum, $goodmaximum);
    if ($goodhabits > $musthit && $_GET['op']=="washpay"){
        output("`c`ÂDie Waschraum-Fee segnet dich!`n`c`0");
        if (e_rand(1,2)==1){
            output("`c`TDu bekommst `Â$giveback Gold`T für Hygiene und Sauberkeit!`0`n`c`0");
            $session['user']['gold'] += $giveback;
            // debuglog("got $giveback gold in the outhouse for washing");
        }else{
            // idea by metatron-sama, coded by anpera
            output("`c`ÂDeine Verteidigung steigt!`n`c`0");
            $session['bufflist']['segen'] = array("name"=>"`9Segen","rounds"=>8,"wearoff"=>"`c`ÂDer Segen der Waschraumfee wirkt nicht mehr.`c`0","defmod"=>1.1,"roundmsg"=>"`c`ÂDer Segen der Waschraumfee schützt dich.`c`0","activate"=>"offense");
        }
        if ($giveagem == 1){
            $givegemtemp = e_rand($gemminimum, $gemmaximum);
            if ($givegemtemp <= $givegempercent){
                $session[user][gems]++;
                //debuglog("gained 1 gem in the outhouse");
                output("`c`TBist du nicht ein Glückspilz? Du findest einen `ÂEdelstein`T beim Eingang!`c`0`n");
            }
            if ($giveaturn == 1){
                $session[user][turns]++;
                output("`c`ÂDu hast eine Extrarunde erhalten!`c`0`n");
            }
            if ($session['user']['drunkenness']>0){
                $session[user][drunkenness] *= .9;
                output("`c`TDu verlässt das Klohäuschen und fühlst dich etwas `Ânüchterner`T!`n`c`0");
            }
        }
    }elseif ($goodhabits > $musthit && $_GET[op] == "washfree"){
        if (e_rand(1, 3)==1) {
            output("`c`TDu bemerkst einen kleinen Beutel mit `Â$giveback Gold`T, den hier wohl jemand vergessen hat.`c`0");
            $session['user']['gold'] += $giveback;
            //debuglog("got $giveback gold in the outhouse for washing");
        }
    }
    forest(true);
}elseif (($_GET[op] == "nowash")){
    page_header("Stinkende Hände");
    output("`c`n`n`n`n`TDeine Hände sind schmutzig und stinken!`n`c`0");
    output("`c`THat dir deine Mutter denn gar nichts beigebracht?`n`c`0");
    $takeaway = e_rand($badminimum, $badmaximum);
    if ($takeaway >= $badmusthit){
        if ($session[user][gold] >= $goldinhand){
            $session[user][gold] -= $takeback;
            //debuglog("lost $takeback gold in the outhouse for not washing");
            output("`c`n`TDer `âKlopapier-Gnom`T hat dich auf den schleimigen, verdreckten Boden geschmissen und dir `Â$takeback Goldstück" . ($takeback > 1?"e":"") . " `Tfür deine Schlampigkeit abgenommen!`n`c`0");
        }
        output("`c`TBist du nicht auch froh, dass peinliche Momente wie dieser nicht in den News stehen?`n`c`0");
        $session['user']['donation']+=1;
        addnews("`TCool,`Â " . ($session['user']['name']) . " `Tlief mit einem langen Stück Klopapier an " . ($session[user][sex]?"ihrem":"seinem") . " Fuß herum.`0");
    }
    forest(true);
}else{
    page_header("Die Klohäuschen");
    if (get_special_val('usedouthouse') == 0){
        output("`c`n`n`n`n`TDie Stadt verfügt über 2 Klohäuschen, die wegen der monsterabwehrenden Wirkung des Gestanks etwas `c`0");
        output("`c`Tausserhalb im Wald stehen.`n`nIn typischer Klassenmanier gibt es ein bevorzugtes und ein `c`0");
        output("`c`Theruntergekommenes Häuschen. Du hast die Wahl!`c`0`n`n");
        addnav("Toiletten");    
        if ($canpay){
            addnav("Private Toilette: ($cost Gold)", "outhouse.php?op=pay");
        }else{
            output("`c`TDie private Toilette kostet `Â$cost Gold`T. Sieht so aus, als ob du es entweder aushalten, oder die öffentliche Toilette benutzen musst!`c`0");
        }
        addnav("Öffentliche Toilette (kostenlos)", "outhouse.php?op=free");
        addnav("Aushalten", "forest.php");
        if (getsetting("dailyspecial","Keines")=="Orkburg"){
            addnav("Zur Orkburg","paths.php?ziel=castle");
            output("`c`n`n`n`n`TNeben den Klohäuschen findest du, halb von Gras bewachsen, einen uralten, abgebrochenen Wegweiser am Boden. Er scheint aber noch immer in die `c`0");
            output("`c`TRichtung zu zeigen, für die er einst gedacht war. Die Aufschrift lautet \"`ÂOrkburg`T\". Du könntest diese Chance nutzen...`c`0");
        }
    }else{
        output("`c`n`n`n`n`TDie Stadt verfügt über 2 Klohäuschen, die wegen der monsterabwehrenden Wirkung des Gestanks etwas `c`0");
        output("`c`Tausserhalb im Wald stehen.`n`n`c`0");
            switch(e_rand(1,3)){
            case 1:
        output("`c`TDie Klohäuschen sind wegen ... Reparaturarbeiten ... geschlossen.`nDu wirst es bis morgen aushalten müssen!`c`0");
                break;
            case 2:
                output("`c`TAls du dich den Plumpsklos näherst, erkennst du, dass du den Gestank heute nicht noch einmal aushalten kannst.`c`0");
                break;
            case 3:
                output("`c`TDu hast wirklich nichts mehr in dir, was du heute noch ablassen könntest!`c`0");
                break;
            }
        output("`c`n`n`TDu kehrst in den Wald zurück.`c`0");
        forest(true);
    }
}
page_footer();

?>


