
<?php
/* ******************* 
Gold-mine 
Written by Ville Valtokari

Modifications for Old Drawl by Raven
No Goldmine for greater than Level 14 - Raven
******************* */  

$hashorse = $session['user']['hashorse'];
$horsecanenter = 0;
$horsecandie = 0;
$horsecansave = 0;
if ($hashorse) {
    $horsecanenter = $playermount['mine_canenter'];
    // And a 10% chance that they tether their horse anyway.
    if (e_rand(1,10) == 1) $horsecanenter=0;
    if ($horscanenter) {
        // The horse cannot die or save you if it cannot enter.
        $horsecandie = $playermount['mine_candie'];
        $horsecansave = $playermount['mine_cansave'];
    }
}

if ($HTTP_GET_VARS[op]==""){ 
    $session[user][specialinc]="goldmine.php"; 
    output("`2In den tiefen des Waldes hast du eine alte verlassene Mine gefunden. Es liegt sogar noch Goldgräberausrüstung in der Nähe herum.`n`n");
    output("`^Als du dich etwas umsiehst, bemerkst du, dass das hier eine Menge Arbeit wäre. So viel, dass du auf jeden Fall einen Waldkampf verlieren wirst, wenn du dein Glück versuchen willst.`n`n");
    output("`^Ausserdem stellst du fest, dass durchaus die Gefahr von gelegentlichen Einstürzen in der Mine besteht.`n`n");
    if ($session[user][level]>14){
        output("`^Da Du schon ganz kurz vor Deinem Drachenkampf stehst, beschließt Du das es besser ist, wenn Du Dich
            vollkommen darauf konzentrierst und Deine Kräfte nicht in der Mine verschwendest. Alles zum Wohle von
            Rabenthal.`n`n");
        addnav("Zurück in den Wald","forest.php?op=no");
    }else{
        addnav("Nach Gold und Edelsteinen suchen","forest.php?op=mine"); 
        addnav("Zurück in den Wald","forest.php?op=no");
        addnav("","forest.php?op=mine"); 
        addnav("","forest.php?op=no");
    }

}else if ($HTTP_GET_VARS[op]=="no"){ 
    output("`2Nop, für so einen langsamen Weg an Gold und Edelsteine zu kommen hast du keine Zeit. Also verlässt du die alte Mine...`n"); 
    $session[user][specialinc]="";
 
} elseif ($HTTP_GET_VARS[op]=="mine") {
    $config = unserialize($session['user']['donationconfig']);
    $kaempfe_rest=0;
    if ($config['goldmine']>0){
        $kaempfe_rest=$config['goldmine'];
        $config['goldmine']--;
    }
    $session['user']['donationconfig'] = serialize($config);
    $session[user][specialinc]="goldmine.php"; 
    if ($session[user][turns]<=0) {
        output("`2Du bist zu müde um weiter zu buddeln...`n");
        $session[user][specialinc]=""; 
    } else {
        // Horsecanenter is a percent, so, if rand(1-100) > enterpercent,
        // tether it.  Set enter percent to 0 (the default), to always tether.
        if (e_rand(1, 100) > $horsecanenter) {
            if ($playermount['mine_tethermsg']) {
                output($playermount['mine_tethermsg']);
            } else {
                output("`&Du siehst, dass der Eingang zu Mine zu klein für dein {$playermount['mountname']}`&, ist und du bindest das Tier am Eingang fest.`n");
            }
        }
        output("`2Du hebst die Ausrüstung auf und fängst an nach Gold und Edelsteinen zu graben...`n`n");
        $rand = e_rand(1,20);
        switch ($rand){

          case 1:case 2:case 3:case 4: case 5:
            output("`2Nach einigen Stunden harter Arbeit hast du nur ein paar wertlose Steine und einen Schädel gefunden...`n`n");
            output("`^Beim Graben verlierst du einen Waldkampf.`n`n");
            if ($session[user][turns]>0) $session[user][turns]--;
            $session[user][specialinc]="";
            break;
          case 6: case 7: case 8:case 9: case 10:
            $gold = e_rand($session[user][level]*5, $session[user][level]*20);
            output("`^Nach einigen Stunden harter Arbeit findest du $gold Gold!`n`n"); 
            $session[user][gold] += $gold; 
            debuglog("found $gold gold in the goldmine");
            output("`^Beim Graben verlierst du einen Waldkampf.`n`n");
            if ($session[user][turns]>0) $session[user][turns]--;
            $session[user][specialinc]="";
            break;
          case 11: case 12: case 13: case 14: case 15:
            $gems = e_rand(1, $session[user][level]/7+1);
            output("`^Nach einigen Stunden harter Arbeit findest du $gems Edelsteine!`n`n");
            $session[user][gems] += $gems;
            debuglog("found $gems gems in the goldmine");
            output("`^Beim Graben verlierst du einen Waldkampf.`n`n");
            if ($session[user][turns]>0) $session[user][turns]--;
            $session[user][specialinc]="";
            break;
          case 16: case 17: case 18:
            $gold = e_rand($session[user][level]*10, $session[user][level]*40);
            $gems = e_rand(1, $session[user][level]/3+1);
            output("`^Du hast die Hauptader entdeckt!`n`n");
            output("`^Nach einigen Stunden harter Arbeit findest du $gems Edelsteine und $gold Gold!`n`n");
            $session[user][gems] += $gems;
            $session[user][gold] += $gold;
            debuglog("found $gold gold and $gems gems in the goldmine");
            output("`^Beim Graben verlierst du einen Waldkampf.`n`n");
            if ($session[user][turns]>0) $session[user][turns]--;
            $session[user][specialinc]="";
            break;
          case 19: case 20:
              output("`2Nach einer Menge harter Arbeit glaubst du, einen `&riesigen`2 Edelstein und etwas `6Gold`2 gesehen zu haben`2.`n");
            output("`2Begierig auf Reichtum lehnst du dich zurück und schmetterst mit der  Spitzhacke darauf ein. `2Denn je fester du zuschlägst, umso schneller wird es erledigt sein....`n");
            output("`7Unglücklicherweise bist `bdu`b erledigt.`n");
            output("Dein übereifriges Schlagen verursacht einen schweren Höhleneinsturz.`n");
            // Dwarves are very wiley so will only ever die in the mines
            // infrequently.
            if ($session['user']['race'] != 4) {
                $dead = 1;
                // Non dwarves will survive on luck 10% of the time.
                if (e_rand(1,10) == 1) $dead = 0;
            } else {
                // Dwarves can only die 5% of the time.
                if (e_rand(1,20) == 1) $dead = 1;
            }
            // Now, if the player died, see if their horse save them
            if ($dead) {
                if (e_rand(1,100) < $horsecansave) {
                    $dead = 0;
                    $horsesave = 1;
                }
            }
            // If we are still dead, see if the horse dies too.
            if ($dead) {
                if (e_rand(1,100) < $horsecandie) $horsedead = 1;
            }

            $session[user][specialinc]="";
            if ($dead == 1) {
                output("Du wurdest unter einer Tonne Felsen zerquetscht.`n`nVielleicht wird der nächste Abenteurer deine Knochen bergen und ordentlich begraben.`n");
                if ($horsedead) {
                    if ($playermount['mine_deathmessage'])
                        output($playermount['mine_deathmessage']);
                    else
                        output("Die Knochen deines {$playermount['mountname']} wurden genau neben deinen begraben.");
                    $session[user][hashorse] = 0;
                    if(isset($session[bufflist]['mount']))
                        unset($session[bufflist]['mount']);
                } else {
                    if ($horsecanenter) {
                        output("Dein {$playermount['mountname']} hat es geschafft zu entkommen. Du weisst, dass das Tier darauf trainiert ist, zum Dorf zurückzukehren.`n");
                    } else {
                        output("Zum Glück hast du dein(e/n) {$playermount['mountname']} draussen angebunden. Du weisst, dass das Tier darauf trainiert ist, zum Dorf zurückzukehren.`n");
                    }
                }
                // Anpassung Old Drawl - wenn dort Zugang gekauft dann kein Exp Gewinn bei Tod
                $exp=0;
                if ($kaempfe_rest==0){
                    $exp=$session[user][experience]*0.6;
                    output("Wenigstens hast du dabei etwas über Bergbau gelernt. Du bekommst $exp Erfahrungspunkte.`n`n");
                    $session[user][experience]+=$exp;
                }
                debuglog("`^Goldmine`@{$session['user']['gold']} gold und {$session['user']['gems']} Edelsteine in der Goldmine verloren");
                debuglog("`^Goldmine`@".$exp." Exp bekommen und Goldmine = ".$kaempfe_rest.".");
                output("`3Du kannst morgen weiterspielen`n");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                $session[user][gold]=0;
                $session['user']['donation']+=(round($session[user][gems]/2));
                $session[user][gems]=0;
                addnav("Tägliche News","news.php");
                addnews($session[user][name]." wurde komplett begraben, als ".($session[user][sex]?"sie":"er")." in der Mine zu gierig wurde.");
            } else {
                if ($session[user][race] == 4) {
                    output("Glücklicherweise lassen dich deine Zwergenfähigkeiten unverletzt entkommen.`n");
                } elseif ($horsesave) {
                    if ($playermount['mine_savemsg'])
                        output($playermount['mine_savemsg']);
                    else
                        output("Dein {$playermount['mountname']} hat es geschafft, dich gerade noch rechtzeitig in Sicherheit zu zerren!`n");
                } else {
                    output("Durch pures Glück kannst du der Mine unverletzt entkommen!`n");
                }
                output("Dein knappes Entkommen vor dem Tod hat dich so erschreckt, dass du heute keinen weiteren Gegner mehr zur Strecken bringen wirst.`n");
                $session['user']['donation']+=1;
                $session[user][turns]=0;
            }
            break;
        }
    }
}
?>


