
<?php
// idea of gargamel @ www.rabenthal.de 
if (!isset($session)) exit(); 


if ($_GET[op]==""){ 
    output("Die Bäume lichten sich etwas. Du kommst leichter voran und erreichst 
    kurz darauf den Rand des Bergwaldes. Du blickst über das angrenzende Gelände und bist 
    überrascht, als Du am Horizont plötzlich schwach flackernde Flämmchen tanzen siehst.`n 
    `bDu hast Irrlichter entdeckt!`b`n`nMöchtest Du Dir das aus der Nähe ansehen?`0"); 
    //abschluss intro 
    addnav("Irrlichter aufsuchen","berge.php?op=go"); 
    addnav("Einfach weitergehen","berge.php?op=cont"); 
    $session[user][specialinc] = "fenfire.php"; 
} 
else if ($_GET[op]=="go"){ 
    $was = e_rand(1,4); 
    switch ($was) { 
        case 1: 
        output("Du gehst auf ein `9bläuliches`0 Irrlicht zu. Je näher Du kommst, desto 
        schwerer fallen Dir die Schritte, bis es Dir plötzlich einfällt: Irrlichter sieht man in sumpfigen Gegenden!`n`n 
        Du sackst immer weiter in den Sumpf ein, nur Dein sofortiges Umkehren rettet 
        Dir Dein Leben. Durch dieses Erlebnis bist Du so geschockt, dass Du erstmal 
        eine kleine Pause brauchst. `9Du verlierst einen Waldkampf.`0`n`n 
        Aus sicherer Entfernung kannst Du beobachten, wie sich das Irrlicht in einen 
        lachenden Kobold verwandelt.`0"); 
        $session[user][turns]-=1; 
//        $session[user][clean]+=5;
        break; 

        case 2: 
        output("Du gehst auf ein `4rötliches`0 Irrlicht zu. Neugierig beschleunigst Du 
        Deinen Schritt, Du bist in Entdeckerlaune. Leider erlischt das Irrlicht, als 
        Du näher kommst.`n 
        Aber beim nächsten Mal, da wirst Du das Rätsel des Irrlichtes lösen.`n`nEtwas 
        deprimiert ziehst Du weiter.`0"); 
        break; 

        case 3: 
        $gold = e_rand($session[user][level]*23,$session[user][level]*37); 
        output("Du gehst auf ein `6gelbliches`0 Irrlicht zu. Du hast so eine Ahnung, 
        woher das Irrlicht seine Farbe hat. Und richtig, als Du es erreichst, liegt 
        dort tatsächlich etwas Gold.`n`n
        `6Du steckst $gold Goldstücke ein und verschwindest wieder in den Wald.`0"); 
        $session[user][gold]+=$gold; 
        break; 

        case 4: 
        output("Du gehst auf ein `2grünliches`0 Irrlicht zu. Schon viele Geschichten 
        hast Du über die sagenumwobenen Irrlichter gehört, jetzt nutzt Du die Chance, 
        Dir mal eins aus der Nähe anzusehen.`n`n
        Je weiter Du auf das Irrlicht zugehst, desto deutlicher spürst Du eine Kraft, 
        die vom `2grünen Licht`0 auszugehen scheint.`n`n`2Plötzlich bist Du vollständig regeneriert.`0`n`n
        Bevor noch was Schlimmes passiert, kehrst Du lieber um und verschwindest wieder 
        im Wald.`0"); 
        if ($session[user][hitpoints] < $session[user][maxhitpoints]) 
           $session[user][hitpoints]=$session[user][maxhitpoints]; 
        break; 
    } 
    $session[user][specialinc]=""; 
} 
else if ($_GET[op]=="cont"){   // einfach weitergehen 
    output("`9Du erinnerst Dich an komische Geschichten, die Du über Irrlichter 
    gehört hast und setzt lieber Deinen Weg fort.`0"); 
    $session[user][specialinc]=""; 
} 
?>

