
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nVor Dir türmt sich ein Wall auf. Wahrscheinlich haben sogar Deine
    Vorväter hier eine Befestigungsanlage gebaut.`n
    Wofür oder wogegen auch immer...`0");
    //abschluss intro
    addnav("Wall überqueren","forest.php?op=climb");
    addnav("Wall untersuchen","forest.php?op=look");
    addnav("Wall umgehen","forest.php?op=cont");
    $session[user][specialinc] = "limes.php";
}
else if ($HTTP_GET_VARS[op]=="climb"){
    output("`nForschen Schrittes gehst Du auf den Wall zu, um ihn zu überqueren.`n`0");
    $session[user][specialinc] = "";
    switch ( e_rand(1,8)) {
        case 1:
        output("Der Wall sah mächtiger aus als er ist und Du nimmst ihn im Laufschritt.
        Oben verlierst Du durch Deinen Schwung das Gleichgewicht und stürzt auf der
        anderen Seite herab. Du fällst genau auf einige angespitzte Palisaden, die dort
        am Fuße des Walls eingegraben sind. Hättest Du das bloß vorher gesehen...`n`n
        `$ Du bist sehr schwer verletzt.`0");
        $session[user][hitpoints]=1;
        break;
        case 2:
        output("Der Wall sah mächtiger aus als er ist und Du nimmst ihn im Laufschritt.
        Oben verlierst Du durch Deinen Schwung das Gleichgewicht und stürzt wieder
        herunter. Du stehst an der gleichen Stelle, an der Du eben Deinen Entschuß
        gefasst hast.`0");
        addnav("Wall überqueren","forest.php?op=climb");
        addnav("Wall untersuchen","forest.php?op=look");
        addnav("Wall umgehen","forest.php?op=cont");
        $session[user][specialinc] = "limes.php";
        break;
        case 3:
        case 4:
        output("Der Wall sah mächtiger aus als er ist und Du nimmst ihn im Laufschritt.`0");
        break;
        case 5:
        case 6:
        $gold = $session[user][level]*5;
        output("Ohne grosse Mühe steigst Du auf den Wall. Beim langsamen Abstieg auf der
        anderen Seite entdeckst Du in einem Loch einen hellen Schimmer.`n
        Du schaust genauer hin und findest `^$gold Gold.`0");
        $session[user][gold]+= $gold;
        break;
        case 7:
        $exp = $session[user][level]*2;
        output("Der Aufstieg ist schwieriger als Gedacht, immer wieder rutscht Du
        ab und musst höllisch aufpassen, nicht die Balance zu verlieren.`n
        `8Diese Erfahrung ist $exp Punkte wert.`0");
        $session[user][experience]+= $exp;
        break;
        case 8:
        output("Der Aufstieg ist schwieriger als Gedacht, immer wieder rutscht Du
        ab und musst höllisch aufpassen, nicht die Balance zu verlieren.`n
        Oben angekommen fühlst du Dich einfach stark. `9Du regenerierst vollständig.`0");
        if ($session['user']['hitpoints']<$session['user']['maxhitpoints'])
            $session[user][hitpoints]=$session[user][maxhitpoints];
        break;
    }
}
else if ($HTTP_GET_VARS[op]=="look"){
    output("`nDu schaust Dir den Wall genauer an.`n`0");
    $session[user][specialinc] = "";
    switch ( e_rand (1,6) ) {
        case 1:
        case 2:
        output("Nur finden kannst Du hier nichts. Du vertrödelst einen Waldkampf und
        stehst vor der gleichen Entscheidung wie eben...`0");
        addnav("Wall überqueren","forest.php?op=climb");
        addnav("Wall untersuchen","forest.php?op=look");
        addnav("Wall umgehen","forest.php?op=cont");
        $session[user][specialinc] = "limes.php";
        $session[user][turns]--;
        break;
        case 3:
        case 4:
        case 5:
        $gold = $session[user][level]*4;
        output("Du gehst ein Stück am Wall entlang, als Du in einem Loch einen hellen
        Schimmer bemerkst.`n
        Du schaust genauer hin und findest `^$gold Gold.`0");
        $session[user][gold]+= $gold;
        break;
        case 6:
        output("Du gehst ein Stück am Wall entlang, als Du in einem Loch ein glitzern
        bemerkst.`n
        Du schaust genauer hin und findest `Qein Edelstein.`0");
        $session[user][gems]++;
        break;
    }
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`5Du umgehst den Wall, verlierst dabei jedoch `3einen Waldkampf.");
    $session[user][turns]--;
}
?>


