<?
// idea of gargamel @ www.rabenthal.de
// überarbeitet von Tidus (www.kokoto.de)
if (!isset($session)) exit();

if ($_GET['op']=='go'){
    $was = mt_rand(1,4);
    switch ($was) {
        case '1':
        output('`nDu gehst auf ein `8bläuliches`0 Irrlicht zu. Je näher Du kommst, desto
        schwerer Fallen Dir die Schritte, bis es Dir plötzlich einfällt:`n
        Irrlichter sieht man in sumpfigen Gegenden!`n`n
        Du sackst immer weiter in den Sumpf ein, nur Dein sofortiges Umkehren rettet
        Dir Dein Leben. Durch dieses Erlebnis bist Du so geschockt, dass Du erstmal
        eine kleine Pause brauchst. `8Du verlierst einen Waldkampf.`0`n`n
        Aus sicherer Entfernung kannst Du beobachten, wie sich das Irrlicht in einen
        lachenden Kobold verwandelt.`0');
        $session['user']['turns']-=1;
        $session['user']['specialinc']='';
        break;

        case '2':
        output("`nDu gehst auf ein `4rötliches`0 Irrlicht zu. Neugierig beschleunigst Du
        Deinen Schritt, Du bist in Entdeckerlaune. Leider erlischt das Irrlicht, als
        Du näher kommst.`n
        Aber beim nächsten Mal, da wirst Du das Rätsel des Irrlichtes lösen. Etwas
        deprimiert ziehst Du weiter.`0");
        $session['user']['specialinc']='';
        break;

        case '3':
        $gold = mt_rand($session['user']['level']23,$session['user']['level']37);
        output("`nDu gehst auf ein `6gelbliches`0 Irrlicht zu. Du hast so eine Ahnung,
        woher das Irrlicht seine Farbe hat. Und richtig, als Du es erreichst, liegt
        dort tatsächlich etwas Gold.`n
        `6Du steckst $gold Goldstücke ein und verschwindest wieder in den Wald.`0");
        $session['user']['gold']+=$gold;
        $session['user']['specialinc']='';
        break;

        case '4':
        output('`nDu gehst auf ein `2grünliches`0 Irrlicht zu. Schon viele Geschichten
        hast Du über die sagenumwobenen Irrlichter gehört, jetzt nutzt Du die Chance,
        Dir mal eins aus der Nähe anzusehen.`n
        Je weiter Du auf das Irrlicht zugehst, desto deutlicher spürst Du eine Kraft,
        die vom `2grünen Licht`0 auszugehen scheint. `2Plötzlich bist Du vollständig regeneriert.`0`n
        Bevor noch was schlimmes passiert, kehrst Du lieber um und verschwindest wieder
        im Wald.`0');
        if ($session['user']['hitpoints'] < $session['user']['maxhitpoints'])
           $session['user']['hitpoints']=$session['user']['maxhitpoints'];
           $session['user']['specialinc']='';
        break;
    }
    
}else if ($_GET['op']=='cont'){   // einfach weitergehen
    output('`n`9Du erinnerst Dich an komische Geschichten, die Du über Irrlichter
    gehört hast und setzt lieber Deinen Weg fort.`0');
    $session['user']['specialinc']='';
}else{
    output('`nDie Bäume lichten sich etwas, Du kommst leichter voran und erreichst
    kurz darauf den Waldesrand. Du blickst über das angrenzende Gelände und bist
    überrascht, als Du am Horizont plötzlich schwach flackernde Flämmchen tanzen siehst.`n
    `bDu hast Irrlichter entdeckt!`b Möchtest Du Dir das aus der Nähe ansehen?`0');
    //abschluss intro
    addnav('Irrlichter aufsuchen','forest.php?op=go');
    addnav('einfach weitergehen','forest.php?op=cont');
    $session['user']['specialinc'] = 'fenfire.php';
}
?>