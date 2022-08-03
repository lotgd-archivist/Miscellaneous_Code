<?php
///////////////////////////////////////////////////////////////////////////////
// Specialname: Das Gebüsch
// code: Opal
// Idee & Text : Melinda eine Userin von www.Aladrion.de
/////////////////////////////////////////////////////////////////////////////
if (!isset($session)) exit();
$filename = basename(__FILE__);
$fn = "forest.php";
$spi = ($session['user']['specialinc']=$filename);

switch ($_GET['op']){
case "zeigen":
$session['user']['specialinc']="";
                switch(e_rand(1,7)){
                case '1':
            output("Du hast für solche Sachen keine Zeit und zeigst ihm grob die Richtung. Die Miene des Mannes wird ernst und er schlägt dich aufgebracht mit seinem Stock.Du verlierst 1 Angriffspunkt und 1 Verteidigungspunkt.");
            $session['user']['attack']-=1;
            $session['user']['defence']-=1;
            break;
            case '2':
            output("Du hast für solche Sachen keine Zeit und zeigst ihm grob die Richtung. Die Miene des Mannes wird ernst und er schlägt dich aufgebracht mit seinem StockDu verlierst 10 Lebenspunkte.");
 $session['user']['hitpoints']-=10;

            break;
case '3':
            output("Du hast für solche Sachen keine Zeit und zeigst ihm grob die Richtung. Die Miene des Mannes wird ernst und er schlägt dich aufgebracht mit seinem Stock.Du verlierst 1 Charmepunkt");
            $session['user']['charm']-=1;
            break;
case '4':
            output("Du nickst freundlich und bringst ihn bis zum Dorf. Er bedankt sich bei dir indem er dich mit seinem Stock berührt.Du bekommst 1 Verteidigungspunkt.");
            $session['user']['defence']+=1;
            break;
case '5':
            output("Du nickst freundlich und bringst ihn bis zum Dorf. Er bedankt sich bei dir indem er dich mit seinem Stock berührt.Du bekommst 1 Angriffspunkt.");
            $session['user']['attack']+=1;
            break;
case '6':
            output("Du nickst freundlich und bringst ihn bis zum Dorf. Er bedankt sich bei dir indem er dich mit seinem Stock berührt.Du bekommst 2 permanente Lebenspunkte dazu.");
            $session['user']['maxhitpoints']+=2;
            break;
case '7':
            output("Du nickst freundlich und bringst ihn bis zum Dorf. Er bedankt sich bei dir indem er dich mit seinem Stock berührt.Du bekommst 1 Charmepunkt.");
            $session['user']['charm']+=1;
            break;
               }

break;

case "wald":
        $session['user']['specialinc']="";
        output("`3Du rennst weg, denn was könnte dir denn schon Gutes mitten im Wald widerfahren…");
        addnews($session['user']['name']." hatte Angst vor einem Busch.");

    break;

case "fliehen":
        $session['user']['specialinc']="";
        output("`3Du gehst ohne ein Wort weiter doch plötzlich.....…siehst du dich einem Stinktier gegenüber. Es fühlt sich bedroht und verteidigt sich dementsprechend.
Du stinkst zum Himmel und verlierst durch deinen üblen Geruch 1 Charmepunkt.");
$session['user']['charm']-=1;
        addnews($session['user']['name']." verbreitet einen üblen Geruch.");

    break;

  case "story":
                $spi;
                output("`QDu bleibst stehen und auf einmal…`n`n…tritt ein alter Mann aus dem Busch hervor und fragt dich ob du ihm den Weg ins Dorf zeigen kannst.");
                addnav("Weg zeigen",$fn."?op=zeigen");
                addnav("Weiter gehen",$fn."?op=fliehen");
                  break;

    break;
    default:

    default:
        $spi;
        output("`&Plötzlich raschelt es im Gebüsch vor dir und du fragst dich was darin sein könnte.`n`n");
        addnav("Stehenbleiben",$fn."?op=story");
        addnav("Wegrennen",$fn."?op=wald");
    break;
}

?>