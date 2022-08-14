
<?php
/*****************************************/ 
/* Waterfall */ 
/* --------- */ 
/* Written by Kevin Kilgore */ 
/* (with some creative help by Jake Taft)*/
/* german translation by nTE */ 
/*****************************************/ 

$session['user']['specialinc']="waterfall.php"; 
switch($_GET['op']){ 
    case "search": 
        case "": 
        output("`n`2Du siehst einen kleinen Pfad, der vom Hauptweg abgeht. Der Pfad ist zugewachsen und du hÃ¤ttest ihn beim"); 
        output("Vorbeischleichen fast nicht gesehen.`n`n"); 
        output("WÃ¤hrend du dich hinunterkniest, um den Pfad nÃ¤her zu betrachten, bemerkst du FuÃŸabdrÃ¼cke die den Pfad entlang fÃ¼hren, aber merkwÃ¼rdigerweise keine, die wieder zurÃ¼ck fÃ¼hren."); 
        output("WÃ¤hrend du den Pfad untersuchst hÃ¶rst etwas, dass sich wie flieÃŸendes Wasser anhÃ¶rt.`n"); 
        addnav("Folge dem Pfad","forest.php?op=trail"); 
        addnav("ZurÃ¼ck in den Wald","forest.php?op=leave"); 
        $session['user']['specialinc']="waterfall.php"; 
        break; 

    case "trail": 
        output("`2Du entschlieÃŸt dich dem Pfad zu folgen und fÃ¤ngst an, die Gegend nÃ¤her zu untersuchen...`n`n"); 
        $rand = e_rand(1,12); 
        switch ($rand) 
        { case 1:case 2: case 3: case 4: case 5: 
            output("`n`2Nach ein paar Stunden des Suchens verlÃ¤ufst du dich.`n`n"); 
            output("`7Du verlierst einen Waldkampf dabei den Weg zurÃ¼ck zu finden.`n`n"); 
            if ($session['user']['turns']>0) $session['user']['turns']--; 
            $session['user']['specialinc']=""; 
            break; 
        case 6: case 7: case 8: 
            output("`^Nach ein paar Minuten des Erforschens findest du einen Wasserfall!`n`n"); 
            output("`2Du bemerkst auch einen kleinen Vorsprung entlang der SteinoberflÃ¤che des Wasserfalls.`n"); 
            output("Ob du zum Vorsprung gehen solltest?"); 
            addnav("Gehe zum Vorsprung","forest.php?op=ledge"); 
            addnav("ZurÃ¼ck in den Wald","forest.php?op=leaveleave"); 
            break; 
        case 9: case 10: case 11: case 12: 
            output("`^Nach ein paar Minuten des Erforschens des Gebiets findest du einen Wasserfall!`n"); 
            output("`2Durstig vom Herumlaufen Ã¼berlegst du, ob du vielleicht einen Schluck Wasser trinken solltest.`n"); 
            addnav("Trinke einen Schluck Wasser","forest.php?op=drink"); 
            addnav("ZurÃ¼ck in den Wald","forest.php?op=leaveleave"); 
            break; 
        } 
        break; 

    case "ledge": 
        $fall = e_rand(1,9); 
        $session['user']['specialinc']=""; 
        switch ($fall) 
        { case 1: case 2: case 3: case 4: 
            output("Du bewegst dich vorsichtig Ã¼ber die Steine, um hinter den Wasserfall zu gelangen und findest dort... "); 
            $gems = e_rand(1,2); 
            if ($gems == 1) output("`^einen Edelstein!`n"); 
            else output("`^$gems Edelsteine!`n"); 
            $session['user']['gems'] += $gems; 
            //debuglog("found $gems gem(s)behind the waterfall.");
            break; 
        case 5: case 6: case 7: case 8: 
            $lhps = round($session['user']['hitpoints']*.25); 
            $session['user']['hitpoints'] = round($session['user']['hitpoints']*.75); 
            output("Du gehst vorsichtig Ã¼ber die Steine, um hinter den Wasserfall zu gelangen, aber nicht vorsichtig genug!`n"); 
            output("Du rutschst ab, fÃ¤llst hinunter und verletzt dich.`n`n"); 
            output("`4Du hast $lhps Lebenspunkte dabei verloren."); 
            if ($session['user']['gold']>0) 
            {
            $gold = round($session['user']['gold']*.15); 
            output("`4Du stellst ausserdem fest, dass du $gold Gold wÃ¤hrend deines Sturzes verloren hast.`n`n"); 
            $session['user']['gold'] -= $gold; 
            //debuglog("lost $gold when he fell in the water by the waterfall."); 
            } 
            break; 
        case 9: 
            output("`7WÃ¤hrend du den Vorsprung entlanggehst, rutschst du aus und fÃ¤llst hinab,`n"); 
            output("prallst auf einige Steine unter dir auf und landest schlussendlich im Wasser!`n`n"); 
            output("`4`nDu bist gestorben! Du kannst morgen wieder spielen.`n"); 
            $session['user']['turns'] = 0; 
            $session['user']['hitpoints'] = 0; 
            $session['user']['gold'] = 0; 
            $session['user']['alive'] = false; 
            //debuglog("lost $session['user']['gold'] gold when he fell from the top of the waterfall."); 
            addnews($session['user']['name']."'s `%zerschundener KÃ¶rper wurde, teils von Steinen begraben, unter einem Wasserfall gefunden."); 
            addnav("TÃ¤gliche News","news.php"); 
            break; 
        } 
        break; 

    case "drink": 
        $session['user']['specialinc']=""; 
        $cnt = e_rand(1,6); 
        switch ($cnt) 
        { case 1: case 2: case 3: 
            output("`2Du trinkst vom Wasser und fÃ¼hlst dich erfrischt!`n`n"); 
            output("`^Deine Lebenspunkte wurden vollstÃ¤ndig aufgefÃ¼llt!"); 
            if ($session['user']['hitpoints'] < $session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints']; 
            break; 
        case 4: 
            output("`2Du gehst zum FuÃŸe des Wasserfalls und nimmst einen tiefen Schluck des klaren Wassers.`n"); 
            output("WÃ¤hrend du trinkst, spÃ¼rst du ein kribbelndes GefÃ¼hl das sich in deinem ganzen KÃ¶rper ausbreitet...`n"); 
            output("Du fÃ¼hlst dich erfrischt und gesÃ¼nder als je zuvor!`n`n"); 
            output("`^Deine Lebenspunkte wurden vollstÃ¤ndig aufgefÃ¼llt und deine maximalen Lebenspunkte wurden `bpermanent`b um `71 `^erhÃ¶ht!"); 
            $session['user']['maxhitpoints']++; 
            $session['user']['hitpoints'] = $session['user']['maxhitpoints']; 
            break; 
        case 5: case 6: 
            output("`2Du trinkst von dem Wasser und beginnst dich seltsam zu fÃ¼hlen. Du setzt dich und wirst krank.`n"); 
            output("`4Du verlierst einen Waldkampf wÃ¤hrend du dich langsam wieder erholst!"); 
            if ($session['user']['turns']>0) $session['user']['turns']--; 
            break; 
        } 
        break; 

    case "leave": 
        output("Du starrst fÃ¼r einen Moment auf den Pfad um den Mut zu bekommen ihn zu erforschen. "); 
        output("Ein kalter Schauer lÃ¤uft dir den RÃ¼cken runter und du musst unwillkÃ¼rlich zittern. Du entscheidest "); 
        output("dich auf dem Hauptweg zu bleiben und siehst zu, dass du zÃ¼gig Abstand zu dem mysteriÃ¶sen Pfad gewinnst."); 
        $session['user']['specialinc']=""; 
        break; 

    case "leaveleave": 
        output("Du entscheidest, dass Vorsicht der bessere Teil des Heldenmuts ist, oder zumindest des Ãœberlebens und kehrst zum Wald zurÃ¼ck."); 
        $session['user']['specialinc']=""; 
        break; 
} 
?>

