
<?php
/********************
Wannabe Knight #1
Wannabe Knight script with option to give chase.
Written by Robert for Maddnets LoGD
german by theKlaus
*********************/
if (!isset($session)) exit();
if ($_GET['op']==""){
    //output("<img src='images/knight.gif' width='103' height='150' alt='Ritter' align='right'>",true);
    output(" `^Du triffst einen `b`4Ritter MÃ¶chtegern,`b `^der dich mit seiner Kampfaxt angreift,`n");
    output(" `^aber du bist schneller! Deine RÃ¼stung {$session['user']['armor']} schÃ¼tzt dich und du wirst nicht verletzt!`n");
    output(" `^ Du wehrst die Angriffe des `b`4Ritter MÃ¶chtegern`b `^ab, bis er sich umdreht und flÃ¼chet!`n");
    output(" `^Du hast dir etwas Erfahrung verdient! `n");
    output(" Du bemerkst, daÃŸ er verletzt ist und langsam rennt. Willst du ihn jagen und ganz fertig machen? `n");
    output(" Dies wÃ¼rde dich einen Waldkampf kosten, wenn du es tust. `&Was machst du? `n ");
    output(" Jagst du ihn mit blutdurstigen Augen oder gehst du zurÃ¼ck in den Wald um andere Kreaturen zu bekÃ¤mpfen? ");
    $session['user']['experience']*=1.02;
    addnav("Jag den Feigling","forest.php?op=chase");
    addnav("ZurÃ¼ck in den Wald","forest.php?op=dont");
    $session['user']['specialinc']="wannabe.php";
}else if ($_GET['op']=="chase"){
    $session['user']['reputation']+=2;
    $session['user']['specialinc']="";
    output(" `^Du beschlieÃŸt, daÃŸ du den `4Ritter MÃ¶chtegern `^ein wenig jagst, `n ");
    output(" `^bist dir aber nicht sicher, ob das das Richtige ist. Doch du willst Blutrache fÃ¼r seinen feigen Angriff auf dich.`n");
    output(" `^Du schnappst dir den `4Ritter MÃ¶chtegern`^, der sich schnell umdreht, seine Kampfaxt hebt und ");
    $session['user']['turns']--;
    switch(e_rand(1,5)){
        case 1:
            output(" `^bevor du deine Waffe {$session['user']['weapon']} heben kannst, wirst du schwer verletzt!");
            output(" Der `4Ritter MÃ¶chtegern `^schont dein Leben und geht stolzen Schrittes weiter seines Weges. ");
            output(" `^Die Schwere deiner Wunden kostet dich 1 Waldkampf!");
            if ($session['user']['turns']>0) $session['user']['turns']--;
            break;
        case 2:
            output(" `^du reagierst blitzartig mir deiner Waffe {$session['user']['weapon']}. Du wirst nicht verletzt!`n");
            output(" `^ Du kÃ¤mpfst mit dem `4Ritter MÃ¶chtegern `^und wehrst jeden Angriff seiner Kampfaxt ab. `n");
            output(" Du schaffst es, ihn einige Male schwer zu treffen und ein weiteres Mal ergreift er die Flucht. `n");
            output(" Du bist jetzt zu erschÃ¶pft, um ihn nochmals zu jagen, hast aber einiges dabei gelernt!`n");
            output(" `^Du steigerst deine Erfahrung!`n");
            $session['user']['experience']*=1.02;
            break;
        case 3:
            output(" `^und zielt auf deine Brust. Diesmal kann deine RÃ¼stung {$session['user']['armor']} dich nicht komplett schÃ¼tzen. `n");
            output(" Ein mÃ¤chtiger Schlag seiner Kampfaxt schickt dich auf den Boden. Er schont dein Leben und geht stolzen Schrittes weiter seines Weges.`n");
            output(" `^Du bist schwer verletzt und stellst fest, daÃŸ Rache keine gute Idee ist. `n");
            output(" `^Du verlierst 3% Erfahrung! `n");
            $session['user']['hitpoints']=2;
            $session['user']['experience']*=0.97;
            break;
        case 4:
            output(" `^und zielt auf deine Brust. Du bist zu langsam und deine RÃ¼stung {$session['user']['armor']} versagt ihren Dienst.`n");
            output(" `5Du bist  tot! `n");
            output(" `^Du verlierst 5% deiner Erfahrung.`n");
            output(" Du kannst morgen wieder weiterspielen.");
            $session['user']['alive']=false;
            $session['user']['hitpoints']=0;
            $session['user']['experience']*=0.95;
            $session['user']['gold'] = 0;
            addnav("TÃ¤gliche News","news.php");
            addnews($session['user']['name']."`0 wurde vom `4Ritter MÃ¶chtegern`0 niedergestreckt.");
            break;
        case 5:
            $gold = e_rand($session['user']['level']*15,$session['user']['level']*50);
            output("`^spricht: \"`8 Ich habe Dich, {$session['user']['name']},`8 besiegt, aber Dir keinen Schaden zugefÃ¼gt!");
            output(" Eigentlich hatte ich einen heimtÃ¼ckischen Troll gejagt und habe Dich mit ihm verwechselt. `n");
            output(" Als meine Sinne wieder klar waren, sah ich, daÃŸ Du garnicht der Troll bist. Ich schÃ¤mte mich so sehr,");
            output(" daÃŸ ich mich umdrehte und davon lief. Aber das sollte ein Geheimnis zwischen uns bleiben. Ich gebe dir `5".$gold." Gold ");
            output(" `8fÃ¼r dein Schweigen und wir sprechen nie wieder Ã¼ber diesen Tag.`^\" ");
            $session['user']['gold']+=$gold;
            //debuglog("got $gold gold from wannabe knight");
            break;
    }
}else{
    output("`%Du verschwendest keine Zeit und gehst zurÃ¼ck in den Wald. ");
}

?>

