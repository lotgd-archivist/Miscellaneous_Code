
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();


if ($HTTP_GET_VARS[op]==""){
    output("`nDurch das Wetter der vergangenen Tage konnten sich die Pilze richtig
    gut entwickeln. Überall durchstossen bunte Hütchen den Waldboden...`n
    Du überlegst, ob Du Dir einen Pilz abschneiden sollst?`0");
    //abschluss intro
    addnav("`$ Roter`0 Pilz","forest.php?op=red");
    addnav("`^Gelber`0 Pilz","forest.php?op=yel");
    addnav("`@Grüner`0 Pilz","forest.php?op=gre");
    addnav("nein, weitergehen","forest.php?op=cont");
    $session[user][specialinc] = "mushroom.php";
}
else if ($HTTP_GET_VARS[op]=="red"){   //
    $chance = e_rand(0,100);
    output("`nDu schneidest einen `$ roten`0 Pilz ab und betrachtest die Frucht.
    Irgendwie sieht der Pilz komisch aus...`n
    Als Du vorsichtig am Pilz riechst, atmest Du einige Sporen ein, die sofort ihre
    Wirkung entfalten.`n`0");
    if ( $chance < 60 ) {
        output("`nDu spürst neue Kraft in Dir! Du bekommst für heute einige
        Spezialfähigkeiten zurück.`0");
        //-> fähigkeiten aktivieren
        $session[user][darkartuses]=floor ( $session[user][darkarts]/3 );
        $session[user][magicuses]=floor ( $session[user][magic]/3 );
        $session[user][thieveryuses]=floor ( $session[user][thievery]/3 );
    } else {
        output("`nDu spürst einen Schmerz in Deinen Lungen! Mit jedem Atemzug kannst
        Du aber glücklicherweise einen Teil der Sporen wieder ausatmen, so dass es Dir
        langsam besser geht. `n
        Trotzdem hast Du Deine Spezialfähigkeiten für heute verloren!`0");
        $session[user][darkartuses]=0;
        $session[user][magicuses]=0;
        $session[user][thieveryuses]=0;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="yel"){   //
    $chance = e_rand(0,100);
    output("`nDu bückst Dich, um einen `^gelben`0 Pilz abzuschneiden. Als Du die Frucht
    anfasst, zerbröselt sie sofort zu Staub, der von einer Böe aufgewirbelt wird. Du
    kannst nicht verhindern, etwas von dem gelben Staub einzuatmen...`n`n`0");
    if ( $chance < 50 ) {
        output("Sofort erfährst Du einen stechenden Schmerz in Deiner Lunge, der Dir
        fast alle Kraft raubt. Stark geschwächt ziehst Du weiter.`0");
        $session[user][hitpoints]=round($session[user][hitpoints]*0.51);
    } else {
        output("Dir wird plötzlich heiss und kalt zugleich, Du schwitzt und frierst
        in der gleichen Sekunde. Offenbar besaß der Pilz geheime Kräfte, die sich nun
        auf Dich übertragen.`nDu regenerierst vollständig.`0");
        if ($session[user][hitpoints] < $session[user][maxhitpoints] )
           $session[user][hitpoints] = $session[user][maxhitpoints];
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="gre"){   //
    output("`nSchnell entscheidest Du Dich, Dir einen `@grünen`0 Pilz abzuschneiden.
    Mißtrauisch beäugst du die Frucht. Ob sie gefährlich ist...?`n
    Während Du den Pilz in der Hand hälst, spürst Du plötzlich ein kribbeln in Deiner
    Handfläche.  Dann kippst Du um.`n`n
    Du wachst einen Waldkampf später wieder auf.`0");
    $session[user][turns]--;
    $session[user][donation]++;
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    if ( $session[user][race]!= 4 ) {
        output("`n`Q\"Zwerge brauchen die Pilze noch als Regenschutz\" denkst Du und gehst weiter.`0");
    } else {
        output("`n`Q\"Andere Zwerge brauchen die Pilze noch als Regenschutz\" denkst Du und gehst weiter.`0");
    }
    $session[user][specialinc]="";
}
?>


