
<?
// Based upon Idea of Lord Wolfen  www.LoGD.net.tc  www.fleigh.net
// translation, modifications by gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nBeschwingt wanderst Du durch den Wald, erklimmst eine Anhöhe und
    betrachtest die wunderschöne Landschaft. Du blickst auf einen kleinen `8See`0
    hinab, der einladend in der Sonne strahlt. Du beschliesst, dort eine kleine
    Pause zu machen.`n`nAm See angekommen hockst Du Dich am Ufer nieder und schaust
    verträumt auf den friedlichen See.`nDu überlegst, Dich zu erfrischen.`0");
    //abschluss intro
    addnav("Schwimmen gehen","forest.php?op=swim");
    addnav("Etwas schlafen","forest.php?op=sleep");
    addnav("zurück in den Wald","forest.php?op=back");
    $session[user][specialinc] = "lake.php";
}
else if ($HTTP_GET_VARS[op]=="sleep"){   // schlafen
    output("`nDu schläfst tief und fest. Als Du nach einiger Zeit wieder aufwachst,
    siehst Du am Stand der Sonne, dass Du `Qeinen Waldkampf vertrödelt`0 hast.`0");
    $session[user][turns]--;
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="back"){   // zurück in den Wald
    output("`nDu bedauerst, diese malerische Stelle verlassen zu müssen, aber Du
    hast ja noch etwas vor im Wald...`0");
    $session[user][specialinc] = "";
}
else if ($HTTP_GET_VARS[op]=="swim"){   // zurück in den Wald
    $rand = e_rand(1,3);
    output("`8Du legst Waffe und Rüstung ab, ebenso Deine Unterkleider. Du freust
    Dich wirklich auf eine Erfrischung und springst beherzt in den See.`0`n`n");
    switch ($rand){
        case 1:
        output("`8Das Wasser ist schon verdammt kalt, aber Du wolltest ja auch eine
        Erfrischung. Du wäscht Dir Blut und Schweiß ab und kommst dann wieder ans
        Ufer um Dich anzuziehen und weiter den Wald zu erkunden. Schließlich hast
        Du gerade ein Waldkampf verpasst.`0");
        output("`n`n`9Du bist frisch und sauber und bekommst 2 Charmpunkte.`0");
        $session[user][charm]+=2;
        $session[user][turns]--;
        break;
        
        case 2:
        output("`2`8Du wäscht Dir Blut und Schweiß ab und kommst dann wieder ans
        Ufer um Dich anzuziehen und weiter den Wald zu erkunden.`0");
        output("`n`n`9Du bist frisch und sauber und bekommst 2 Charmpunkte.`0");
        $session[user][charm]+=2;
        break;

        case 3:
        output("`8Das Wasser ist schon verdammt kalt, aber Du wolltest ja auch eine
        Erfrischung. Du wäscht Dir Blut und Schweiß ab und drehst dann noch ein paar
        Runden im See. Du vertrödelst dabei einen Waldkampf, als Du dies bemerkst
        kommst Du schnell wieder ans Ufer um Dich anzuziehen und weiter den Wald zu
        erkunden.`0");
        output("`n`n`9Du bist komplett regeneriert und bekommst 1 Charmpunkt.`0");
        $session[user][charm]++;
        $session[user][turns]--;
        if ($session[user][hitpoints] < $session[user][maxhitpoints] )
           $session[user][hitpoints] = $session[user][maxhitpoints];
        break;
    }
    $session[user][specialinc] = "";
}
?>


