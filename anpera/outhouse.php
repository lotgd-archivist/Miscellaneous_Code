
ï»¿<?php



// 20060614



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

if ($session['user']['gold'] >= $cost) $canpay = True;



if ($_GET['op'] == "pay"){

    page_header("Private Toilette");

    $session['user']['usedouthouse']=1;

    output("`7Du bezahlst die $cost Gold an den Klo-Gnom fÃ¼r die Erlaubnis, das private Klo zu benutzen.`n");

    output("Dies ist das sauberste Plumpsklo im ganzen Land!`n");

    output("Der Klopapier-Gnom sagt dir noch, dass du einfach fragen sollst, wenn du noch etwas brauchst.`n");

    output (($session['user']['sex']?"Sie":"Er") . " dreht dir hÃ¶flich ");

    output (($session['user']['sex']?"ihren":"seinen") . " RÃ¼cken zu und schlieÃŸt die Reinigung des Waschstandes ab.`n`n");

    $session['user']['gold'] -= $cost;

    //debuglog("spent $cost gold to use the outhouse");

    addnav("HÃ¤nde waschen", "outhouse.php?op=washpay");

    addnav("Verlassen", "outhouse.php?op=nowash");

    

}elseif ($_GET['op'] == "free"){

    page_header("&Ouml;ffentliche Toilette!");

    $session['user']['usedouthouse'] = 1;

    output("`2Der furchtbare Gestank treibt dir TrÃ¤nen in die Augen und deine Nasenhaare krÃ¤useln sich!`n");

    output("Nachdem er sich die Nase damit geputzt hat, Ã¼berreicht dir der Klopapier-Gnom ein Blatt einlagiges Klopapier.`n");

    output("Du entschliesst dich, dieses Teil lieber nicht zu benutzen, nachdem du seine HÃ¤nde gesehen hast.`n`n");

    output("Beinahe rutschst du in das groÃŸe Loch in der Mitte des Raumes, wÃ¤hrend du " . ($session['user']['sex']?"darÃ¼ber in die Hocke gehst":"dich darÃ¼ber stellst") . ". Der Klopapier-Gnom beobachtet dich bei deinem GeschÃ¤ft sehr genau.`n");

    output("Du machst so schnell du kannst, denn so arg lange kannst du die Luft nicht anhalten.`n");

    if (!$session['user']['prefs']['nosounds']) output("<embed src=\"media/ogpissd.wav\" width=\"10\" height=\"10\" autostart=\"true\" loop=\"false\" hidden=\"true\" volume=\"100\">",true);

    addnav("HÃ¤nde waschen", "outhouse.php?op=washfree");

    addnav("Verlassen", "outhouse.php?op=nowash");



}elseif ($_GET['op'] == "washpay"|| $_GET['op'] == "washfree"){

    page_header("Wasch Stand");

    output("`2HÃ¤nde waschen ist immer eine gute Sache. Du machst dich zurecht, bringst dein(e/n) {$session['user']['armor']} in Ordnung und betrachtest dein Spiegelbild im Wasser. Dann machst du dich wieder auf den Weg.`0`n");

    $goodhabits = e_rand($goodminimum, $goodmaximum);

    if ($goodhabits > $musthit && $_GET['op']=="washpay"){

        output("`^Die Waschraum-Fee segnet dich!`n");

        if (e_rand(1,2)==1){

            output("`7Du bekommst `^$giveback `7Gold fÃ¼r Hygiene und Sauberkeit!`0`n");

            $session['user']['gold'] += $giveback;

            // debuglog("got $giveback gold in the outhouse for washing");

        }else{

            // idea by metatron-sama, coded by anpera

            output("`7Deine Verteidigung steigt!`0`n");

            $session['bufflist']['segen'] = array("name"=>"`9Segen","rounds"=>8,"wearoff"=>"Der Segen der Waschraumfee wirkt nicht mehr.","defmod"=>1.1,"roundmsg"=>"`9Der Segen der Waschraumfee schÃ¼tzt dich.","activate"=>"offense");

        }

        if ($giveagem == 1){

            $givegemtemp = e_rand($gemminimum, $gemmaximum);

            if ($givegemtemp <= $givegempercent){

                $session['user']['gems']++;

                //debuglog("gained 1 gem in the outhouse");

                output("`&Bist du nicht ein GlÃ¼ckspilz? Du findest einen Edelstein beim Eingang!`0`n");

            }

            if ($giveaturn == 1){

                $session['user']['turns']++;

                output("`&Du hast eine Extrarunde erhalten!`0`n");

            }

            if ($session['user']['drunkenness']>0){

                $session['user']['drunkenness']=round($session['user']['drunkenness']*0.9);

                output("`&Du verlÃ¤sst das KlohÃ¤uschen und fÃ¼hlst dich etwas nÃ¼chterner!`n`0");

            }

        }

    }elseif ($goodhabits > $musthit && $_GET['op'] == "washfree"){

        if (e_rand(1, 3)==1) {

            output("`&Du bemerkst einen kleinen Beutel mit `^$giveback `7Gold, den hier wohl jemand vergessen hat.`0");

            $session['user']['gold'] += $giveback;

            //debuglog("got $giveback gold in the outhouse for washing");

        }

    }

    forest(true);



}elseif (($_GET['op'] == "nowash")){

    page_header("Stinkende H&auml;nde");

    output("`2Deine HÃ¤nde sind schmutzig und stinken!`n");

    output("Hat dir deine Mutter denn gar nichts beigebracht?`n");

    $takeaway = e_rand($badminimum, $badmaximum);

    if ($takeaway >= $badmusthit){

        if ($session['user']['gold'] >= $goldinhand){

            $session['user']['gold'] -= $takeback;

            //debuglog("lost $takeback gold in the outhouse for not washing");

            output("`nDer Klopapier-Gnom hat dich auf den schleimigen, verdreckten Boden geschmissen und dir $takeback GoldstÃ¼ck" . ($takeback > 1?"e":"") . " fÃ¼r deine Schlampigkeit abgenommen!`n");

        }

        output("Bist du nicht auch froh, dass peinliche Momente wie dieser nicht in den News stehen?`n");

        $session['user']['donation']+=1;

        addnews("`2Cool, " . ($session['user']['name']) . " `2lief mit einem langen StÃ¼ck Klopapier an " . ($session['user']['sex']?"ihrem":"seinem") . " FuÃŸ herum.");

    }

    forest(true);



}else{

    page_header("Die Kloh&auml;uschen");

    if ($session['user']['usedouthouse'] == 0){

        output("`2Das Dorf verfÃ¼gt Ã¼ber 2 KlohÃ¤uschen, die wegen der monsterabwehrenden Wirkung des Gestanks etwas ");

        output("ausserhalb im Wald stehen.`n`nIn typischer Klassenmanier gibt es ein bevorzugtes und ein ");

        output("heruntergekommenes HÃ¤uschen. Du hast die Wahl!`0`n`n");

        addnav("Toiletten");

        if ($canpay){

            addnav("Private Toilette: ($cost Gold)", "outhouse.php?op=pay");

        }else{

            output("`2Die private Toilette kostet `^$cost `2Gold. Sieht so aus. als ob du es entweder aushalten, oder die Ã¶ffentliche Toilette benutzen musst!");

        }

        addnav("Ã–ffentliche Toilette (kostenlos)", "outhouse.php?op=free");

        addnav("Aushalten", "forest.php");

        if (getsetting("dailyspecial","Keines")=="Orkburg"){

            addnav("Zur Orkburg","paths.php?ziel=castle");

            output("`2Neben den KlohÃ¤uschen findest du, halb von Gras bewachsen, einen uralten, abgebrochenen Wegweiser am Boden. Er scheint aber noch immer in die ");

            output("Richtung zu zeigen, fÃ¼r die er einst gedacht war. Die Aufschrift lautet \"`tOrkburg`2\". Du kÃ¶nntest diese Chance nutzen...");

        }

    }else{

        output("`2Das Dorf verfÃ¼gt Ã¼ber 2 KlohÃ¤uschen, die wegen der monsterabwehrenden Wirkung des Gestanks etwas ");

        output("ausserhalb im Wald stehen.`n`n");

            switch(e_rand(1,3)){

            case 1:

        output("Die KlohÃ¤uschen sind wegen ... Reparaturarbeiten ... geschlossen.`nDu wirst es bis morgen aushalten mÃ¼ssen!");

                break;

            case 2:

                output("Als du dich den Plumpsklos nÃ¤herst, erkennst du, dass du den Gestank heute nicht noch einmal aushalten kannst.");

                break;

            case 3:

                output("Du hast wirklich nichts mehr in dir, was du heute noch ablassen kÃ¶nntest!");

                break;

            }

        output("`n`n`7Du kehrst in den Wald zurÃ¼ck.`0");

        forest(true);

    }

}

page_footer();



?>

