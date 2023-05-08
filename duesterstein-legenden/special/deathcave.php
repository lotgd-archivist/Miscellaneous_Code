
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();


if ($HTTP_GET_VARS[op]==""){
    output("`n`#Du wanderst auf der Suche nach Gegnern ziellos durch den Wald. Plötzlich
    stehst Du unter einem grossen Felsvorsprung. Hier liegen ziemlich viele Opfer. Ist dies
    der Eingang zur legendären `bTodeshöhle`b?`n
    Du hast die Leute im Dorf über diesen mystischen Ort reden hören, aber Du hast
    eigentlich nie geglaubt, dass sie wirklich existiert. Sie sagen, die Höhle hätte
    große magische Kräfte und dass diese Kräfte unberechenbar sind...`n`n`0");
    //abschluss intro
    addnav("Höhle erkunden","forest.php?op=hell");
    addnav("weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "deathcave.php";
}
else if ($HTTP_GET_VARS[op]=="hell"){
    $was = e_rand(1,22);
    output("`n`#Obwohl Du weisst, dass die Kräfte der Höhle unvorhersagbar wirken,
    nimmst Du diese Chance wahr. Du läufst in das Innere der legendären Höhle und bist
    bereit, die fantastischen Kräfte der Höhle zu erfahren. `n
    Als Du die Mitte erreichst,
    wird die Decke der Höhle zu einer schwarzen, sternenklaren Nacht. Du bemerkst, dass
    der Boden unter Deinen Füssen in einem schwachen Licht lila zu glühen scheint, fast
    so, als ob sich der Boden selbst in Nebel verwandeln will. Du fühlst ein Kitzeln,
    dass sich durch Deinen gesamten Körper ausbreitet. `n
    Plötzlich umgibt ein helles, intensives Licht die Höhle und Dich. `n`n
    Als das Licht verschwindet, `0");
    switch ($was){
        case 1:
        case 2:
        case 3:
        case 4:
        output("`#bist Du nicht mehr länger in der Höhle.`nÜberall um Dich herum sind die
        Seelen derer, die in alten Schlachten und bei bedauerlichen Unfällen umgekommen
        sind. Jede trägt Anzeichen der Niedertracht, durch welche sie ihr Ende gefunden
        habt. Du bemerkst mit steigender Verzweiflung, dass die Höhle Dich direkt ins
        Reich der Toten transportiert hat!`n`n
        `^Du wurdest aufgrund Deiner dümmlichen Entscheidung in die Unterwelt geschickt.`n
        Da Du physisch dorthin transportiert worden bist, hast du noch Dein ganzes Gold.`n
        Du verlierst aber 10% deiner Erfahrung und alle Deine Edelsteine.
        Du kannst morgen wieder kämpfen.`0");
        $session[user][alive]=false;
        $session[user][hitpoints]=0;
        $session[user][gems]=0;
        $session[user][experience]*=0.9;
        addnav("Tägliche News","news.php");
        addnews($session[user][name]." ist in der Todeshöhle zu Tode gekommen. `bDarum`b heisst sie so!`0");
        break;

        case 5:
        case 6:
        case 7:
        output("`#liegt dort nur noch der Körper eines Kriegers, der die Kräfte der Höhle
        herausgefordert hat. `^Dein Geist wurde aus Deinem Körper gerissen!`n
        Da Dein Körper in der Höhle liegt, verlierst Du alle Deine Wertsachen. Du verlierst 5%
        Deiner Erfahrung.`n`0");
        output("Du kannst morgen wieder spielen.`0");
        $session[user][alive]=false;
        $session[user][hitpoints]=0;
        $session[user][experience]*=0.95;
        $session['user']['donation']+=1;
        $session[user][gold] = 0;
        $session[user][gems]=0;
        addnav("Tägliche News","news.php");
        addnews($session[user][name]." ist in der Todeshöhle zu Tode gekommen. `bMan weiß nicht, wie....!`b`0");
        break;

        case 8:
        case 9:
        output("`#fühlst Du eine zerrende Energie durch Deinen Körper zucken, als ob Deine
        Muskeln verbrennen würden. Als der schreckliche Schmerz nachlässt, bemerkst Du,
        dass Deine Muskeln VIEL grösser geworden sind.`0");
        $reward = round($session[user][experience] * 0.1);
        output("`n`n`^Du bekommst `7$reward`^ Erfahrungspunkte!`0");
        $session[user][experience] += $reward;
        break;
        
        case 10:
        case 11:
        case 12:
        $reward = e_rand(1, 3);
        if ($reward == 3) $rewardn = "DREI`^ Edelsteine";
        else if ($reward == 2) $rewardn = "ZWEI`^ Edelsteine";
        else if ($reward == 1) $rewardn = "EINEN`^ Edelstein";
        output("...`n`n`^bemerkst Du `%$rewardn vor deinen Füssen!`n`n`0");
        $session[user][gems]+=$reward;
        break;

        case 13:
        case 14:
        case 15:
        output("`#hast Du viel mehr Vertrauen in Deine eigenen Fähigkeiten.`n`n
        `^Diese Selbstsicherheit schlägt in Arroganz um und Du verlierst 5 Charmepunkte!`0");
        $session[user][charm] -= 5;
        break;

        case 16:
        case 17:
        output("`#fühlst Du Dich plötzlich extrem gesund. `^Deine Lebenspunkte wurden
        vollständig aufgefüllt.`0");
        if ($session[user][hitpoints]<$session[user][maxhitpoints])
            $session[user][hitpoints]=$session[user][maxhitpoints];
        break;

        case 18:
        case 19:
        case 20:
        case 21:
        $prevTurns = $session[user][turns];
        if ($prevTurns >= 25) $session[user][turns]-=9;
        if ($prevTurns >= 12) $session[user][turns]-=6;
        if ($prevTurns >= 4) $session[user][turns]-=4;
        else if ($prevTurns < 4) $session[user][turns]=0;
        $currentTurns = $session[user][turns];
        $lostTurns = $prevTurns - $currentTurns;
        output("`#ist der Tag vergangen. Es scheint, als hätte die Höhle Dich für die
        meiste Zeit des Tages in der Zeit eingefroren.`n
        Das Ergebnis ist, daß Du $lostTurns Waldkämpfe verlierst!`0");
        break;

        case 22:
        output("`#fühlst Du Deine Ausdauer in die Höhe schiessen!");
        $reward = 2;
        output("`n`n`^Deine Lebenspunkte wurden `bpermanent`b um `7$reward `^erhöht!`0");
        $session[user][maxhitpoints] += $reward;
        $session[user][hitpoints] = $session[user][maxhitpoints];
        break;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`#Du fürchtest die unglaublichen Kräfte der Höhle und beschliesst, die
    kalten Gänge der Höhle lieber in Ruhe zu lassen. Du gehst zurück in den Wald.`0");
    $session[user][specialinc]="";
}

?>


