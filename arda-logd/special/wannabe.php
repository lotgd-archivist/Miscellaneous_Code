<?php

// 22062004

/********************
Wannabe Knight #1
Wannabe Knight script with option to give chase.
Written by Robert for Maddnets LoGD
german by theKlaus
*********************/
if (!isset($session)) exit();
if ($HTTP_GET_VARS[op]==""){
//    output("<img src='images/knight.gif' width='103' height='150' alt='Ritter' align='right'>",true);
     output(" `c`KDu triffst einen `PRit`ster`S M`döch`steg`Pern,`n `Kder dich mit seiner Kampfaxt angreift,
aber du bist schneller!`n
Deine Rüstung {$session['user']['armor']}`Kschützt dich und du wirst nicht verletzt!`n
Du wehrst die Angriffe des `PRit`ster`S M`döch`steg`Pern, `Kbis er sich umdreht und flüchet!?`n
Du hast dir etwas Erfahrung verdient! `n
Du bemerkst, dass er verletzt ist und langsam rennt.`n
Willst du ihn jagen und ganz fertig machen? `n
Dies würde dich einen Waldkampf kosten, wenn du es tust.`n
 Was machst du?`n`c");
     $session[user][experience]*=1.02;
     addnav("Jag den Feigling","forest.php?op=chase");
     addnav("Zurück in den Wald","forest.php?op=dont");
     $session[user][specialinc]="wannabe.php";
}else if ($HTTP_GET_VARS[op]=="chase"){
    $session[user][reputation]+=2;
     $session[user][specialinc]="";
        output(" `KDu beschließt, dass du den `4Ritter Möchtegern `^ein wenig jagst, `n ");
        output(" `^bist dir aber nicht sicher, ob das das Richtige ist. Doch du willst Blutrache für seinen feigen Angriff auf dich.`n");
        output(" `^Du schnappst dir den `PRit`ster`S M`döch`steg`Pern`K, der sich schnell umdreht, seine Kampfaxt hebt und ");
        $session[user][turns]--;
        switch(e_rand(1,5)){
            case 1:
                output(" `Kbevor du deine Waffe {$session['user']['weapon']}`K heben kannst, wirst du schwer verletzt!");
                output(" `KDer `PRit`ster`S M`döch`steg`Pern `Kschont dein Leben und geht stolzen Schrittes weiter seines Weges. ");
                output(" `KDie Schwere deiner Wunden kostet dich 1 Waldkampf!");
                if ($session[user][turns]>0) $session[user][turns]--;
                break;
            case 2:
                output(" `Kdu reagierst blitzartig mir deiner Waffe {$session['user']['weapon']}`K. Du wirst nicht verletzt!`n");
                output(" `K Du kämpfst mit dem `PRit`ster`S M`döch`steg`Pern `Kund wehrst jeden Angriff seiner Kampfaxt ab. `n");
                output(" `KDu schaffst es, ihn einige Male schwer zu treffen und ein weiteres Mal ergreift er die Flucht. `n");
                output(" `KDu bist jetzt zu erschöpft, um ihn nochmals zu jagen, hast aber einiges dabei gelernt!`n");
                output(" `KDu steigerst deine Erfahrung!`n");
                $session[user][experience]*=1.02;
                break;
            case 3:
                output(" `Kund zielt auf deine Brust. Diesmal kann deine Rüstung {$session['user']['armor']}`K dich nicht komplett schützen. `n");
                output(" `KEin mächtiger Schlag seiner Kampfaxt schickt dich auf den Boden. Er schont dein Leben und geht stolzen Schrittes weiter seines Weges.`n");
                output(" `KDu bist schwer verletzt und stellst fest, dass Rache keine gute Idee ist. `n");
                output(" `KDu verlierst 3% Erfahrung! `n");
                $session[user][hitpoints]=2;
                $session[user][experience]*=0.97;
                break;
            case 4:
                output(" `Kund zielt auf deine Brust. Du bist zu langsam und deine Rüstung {$session['user']['armor']} `Kversagt ihren Dienst.`n");
                output(" `eDu bist  tot! `n");
                output(" `QDu verlierst 5% deiner Erfahrung.`n");
                output(" `qDu kannst morgen wieder weiterspielen.");
                $session[user][alive]=false;
                $session[user][hitpoints]=0;
                $session[user][experience]*=0.95;
                $session[user][gold] = 0;
                addnav("Tägliche News","news.php");
                addnews($session[user][name]."`K wurde vom `PRit`ster`S M`döch`steg`Pern`K  niedergestreckt.");
                break;
            case 5:
                $gold = e_rand($session[user][level]*15,$session[user][level]*50);
                output("`Kspricht: \"`P Ich habe Dich, {$session[user][name]},`P besiegt, aber Dir keinen Schaden zugefügt`K!");
                output("`PEigentlich hatte ich einen heimtückischen Troll gejagt und habe Dich mit ihm verwechselt. `n");
                output(" `PAls meine Sinne wieder klar waren, sah ich, dass Du gar nicht der Troll bist. Ich schämte mich so sehr,");
                output("`P dass ich mich umdrehte und davon lief. Aber das sollte ein Geheimnis zwischen uns bleiben. Ich gebe dir `K$gold`K Gold ");
                output(" `Pfür dein Schweigen und wir sprechen nie wieder über diesen Tag.`K\" ");
                $session[user][gold]+=$gold;
                //debuglog("got $gold gold from wannabe knight");
                break;
}
}else{
      output("`VDu verschwendest keine Zeit und gehst zurück in den Wald. ");
}


?>