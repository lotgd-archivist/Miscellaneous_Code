
<?php
if ($_GET['op']=="" || $_GET['op']=="search"){
    output("`#Du wanderst auf der Suche nach etwas zum BekÃ¤mpfen ziellos durch den Wald. PlÃ¶tzlich stehst du mitten auf einem Feld.");
    output("In der Mitte kannst du einen Steinkreis sehen. Du hast das legendÃ¤re ");
    output("Stonehenge gefunden! Du hast die Leute im Dorf Ã¼ber diesen mystischen Ort reden hÃ¶ren, aber");
    output(" du hast eigentlich nie geglaubt, dass es wirklich existiert. Sie sagen, der Kreis hat groÃŸe magische ");
    output("KrÃ¤fte und dass diese KrÃ¤fte unberechenbar sind. Was wirst du tun?");
    output("`n`n<a href='forest.php?op=stonehenge'>Betrete Stonehenge</a>`n<a href='forest.php?op=leavestonehenge'>Lasse es in Ruhe</a>",true);
    addnav("S?Betrete Stonehenge","forest.php?op=stonehenge");
    addnav("Lasse es in Ruhe","forest.php?op=leavestonehenge");
    addnav("","forest.php?op=stonehenge");
    addnav("","forest.php?op=leavestonehenge");
    $session['user']['specialinc']="stonehenge.php";
}else{
    $session['user']['specialinc']="";
    if ($_GET['op']=="stonehenge"){
        $rand = e_rand(1,22);
        output("`#Obwohl du weiÃŸt, daÃŸ die KrÃ¤fte der Steine unvorhersagbar wirken, nimmst du diese Chance wahr. Du ");
        output("lÃ¤ufst in die Mitte der unzerstÃ¶rbaren Steine und bist bereit, die fantastischen KrÃ¤fte von Stonehenge zu erfahren. ");
        output("Als du die Mitte erreichst, wird der Himmel zu einer schwarzen, sternenklaren Nacht. Du bemerkst, dass der Boden unter ");
        output("deinen FÃ¼ssen in einem schwachen Licht lila zu glÃ¼hen scheint, fast so, als ob sich der Boden selbst in Nebel ");
        output("verwandeln will. Du fÃ¼hlst ein Kitzeln, das sich durch deinen gesamten KÃ¶rper ausbreitet. PlÃ¶tzlich umgibt ein ");
        output("helles, intensives Licht den Kreis und dich. Als das Licht verschwindet, ");
        switch ($rand){
            case 1:
            case 2:
                output("bist du nicht mehr lÃ¤nger in Stonehenge.`n`nÃœberall um dich herum sind die Seelen derer, die ");
                output("in alten Schlachten und bei bedauerlichen UnfÃ¤llen umgekommen sind. ");
                output("Jede trÃ¤gt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden haben. Du bemerkst mit steigender Verzweiflung, daÃŸ ");
                output("der Steinkreis dich direkt ins Land der Toten transportiert hat!");
                output("`n`n`^Du wurdest aufgrund deiner dÃ¼mmlichen Entscheidung in die Unterwelt geschickt.`n");
                output("Da du physisch dorthin transportiert worden bist, hast du noch dein ganzes Gold.`n");
                output("Du verlierst aber 5% deiner Erfahrung.`n");
                output("Du kannst morgen wieder spielen.");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['experience']*=0.95;
                addnav("TÃ¤gliche News","news.php");
                addnews($session['user']['name']." ist fÃ¼r eine Weile verschwunden und jene, welche gesucht haben, kommen nicht zurÃ¼ck.");
                break;
            case 3:
                 output(" liegt dort nur noch der KÃ¶rper eines Kriegers, der die KrÃ¤fte von Stonehenge herausgefordert hat.");
                output("`n`n`^Dein Geist wurde aus deinem KÃ¶rper gerissen!`n");
                output("Da dein KÃ¶rper in Stonehenge liegt, verlierst du all dein Gold.`n");
                output("Du verlierst 10% deiner Erfahrung.`n");
                output("Du kannst morgen wieder spielen.");
                $session['user']['alive']=false;
                $session['user']['hitpoints']=0;
                $session['user']['experience']*=0.9;
                $session['user']['donation']+=1;
                $session['user']['gold'] = 0;
                addnav("TÃ¤gliche News","news.php");
                addnews($session['user']['name']."'s lebloser KÃ¶rper wurde auf einer leeren Lichtung gefunden.");
                break;
            case 4:
            case 5:
            case 6:
                output("fÃ¼hlst du eine zerrende Energie durch deinen KÃ¶rper zucken, als ob deine Muskeln verbrennen wÃ¼rden. Als der schreckliche Schmerz nachlÃ¤sst, bemerkst du, dass deine Muskeln VIEL grÃ¶sser geworden sind.");
                  $reward = round($session['user']['experience'] * 0.1);
                output("`n`n`^Du bekommst `7".$reward."`^ Erfahrungspunkte!");
                $session['user']['experience'] += $reward;
                break;
            case 7:
            case 8:
            case 9:
            case 10:
                $reward = e_rand(1, 3);         // original value: 1,4
                 if ($reward == 4) $rewardn = "VIER`^ Edelsteine";
                else if ($reward == 3) $rewardn = "DREI`^ Edelsteine";
                else if ($reward == 2) $rewardn = "ZWEI`^ Edelsteine";
                else if ($reward == 1) $rewardn = "EINEN`^ Edelstein";
                output("...`n`n`^bemerkst du `%".$rewardn." vor deinen FÃ¼ssen!`n`n");
                $session['user']['gems']+=$reward;
                //debuglog("found gems from Stonehenge");  // said 4 gems ... can be less!!
                break;
            
            case 11:
            case 12:
            case 13:            
                output("hast du viel mehr Vertrauen in deine eigenen FÃ¤higkeiten.`n`n");
                //output("`^You gain four charm!");    // whoooohaa ... slow down a bit ;)
                output("`^Dein Charme steigt!");
                $session['user']['charm'] += 2;
                break;
            case 14:
            case 15:
            case 16:
            case 17:
            case 18:
                output("fÃ¼hlst du dich plÃ¶tzlich extrem gesund.");
                output("`n`n`^Deine Lebenspunkte wurden vollstÃ¤ndig aufgefÃ¼llt.");
                if ($session['user']['hitpoints']<$session['user']['maxhitpoints']) $session['user']['hitpoints']=$session['user']['maxhitpoints'];
                break;
            case 19:
            case 20:
                output("fÃ¼hlst du deine Ausdauer in die HÃ¶he schiessen!");
                //$reward = $session['user']['maxhitpoints'] * 0.1;     // uhm ... seems to be too much for permanent HP
                  $reward = 2;
                output("`n`n`^Deine Lebenspunkte wurden `bpermanent`b um `7".$reward." `^erhÃ¶ht!");
                $session['user']['maxhitpoints'] += $reward;
                $session['user']['hitpoints'] = $session['user']['maxhitpoints'];
                break;
            case 21:
            case 22:
                $prevTurns = $session['user']['turns'];
                if ($prevTurns >= 3) $session['user']['turns']-=3;    // original value 5 - but i only offer 20 ff a day
                else if ($prevTurns < 3) $session['user']['turns']=0;
                $currentTurns = $session['user']['turns'];
                $lostTurns = $prevTurns - $currentTurns;
                
                output("ist der Tag vergangen. Es scheint, als hÃ¤tte Stonehenge dich fÃ¼r die meiste Zeit des Tages in der Zeit eingefroren.`n");
                output("Das Ergebnis ist, daÃŸ du $lostTurns WaldkÃ¤mpfe verlierst!");                
                break;
        }
    }else{
        output("`#Du fÃ¼rchtest die unglaublichen KrÃ¤fte von Stonehenge und beschliesst, die Steine lieber in Ruhe zu lassen. Du gehst zurÃ¼ck in den Wald.");
    }
}
?>


