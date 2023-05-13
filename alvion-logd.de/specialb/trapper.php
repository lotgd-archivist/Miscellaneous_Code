
<?php
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($_GET[op]==""){
    output("Du begegnest einem `3Trapper`0, der allein durch den Bergwald streift. `0");
    $was =  e_rand(1,6) ;
    switch ( $was ) {
        case 1:
        output("Und während du ihm freundlich winkst, gibt plötzlich der Boden unter
        deinen Füssen nach. Du bist in eine Fallgrube gelaufen!`n
        Sofort eilt dir der `3Trapper`0 zur Hilfe und zieht dich heraus. `6\"Hier also war
        sie...\"`0 hörst du ihn murmeln. Er entschuldigt sich wortreich bei dir, was
        deine Schmerzen vom Sturz aber nicht lindern kann.`n`n
        `9Du verlierst einige Lebenspunkte.`0");
        $session[user][hitpoints]=round( $session[user][hitpoints]*0.9 );
        break;
        case 2:
        output("`6\"HALT\" `0ruft er plötzlich, `6\"da ist eine Falle!\"`0. Sofort bleibst
        du stehen. Der Trapper kommt zu dir herüber und führt dich zurück auf einen
        sicheren Pfad.`n`n
        Die Sache ist ihm sichtlich unangenehm und zur Entschuldigung steckt er
        dir ein `^paar Goldmünzen`0 zu.`0");
        $session[user][gold]+=($session[user][level]*10);
        break;
        case 3:
        output("`6\"Sei vorsichtig, ich habe hier Fallen aufgestellt!\"`0 ruft er dir
        zu. Dankbar für diese Warnung beschließt du, einen grossen Bogen um diese
        Gegend zu machen.`n`n`3Du büsst einen Waldkampf ein.`0");
        $session[user][turns]--;
        case 4:
        case 5:
        output("`6\"Schaut, was ich habe\"`0 ruft er dir zu und eilt herüber. Er bietet
        dir seine Funde an, verlangt aber etwas Gold dafür.`0");
        addnav("Kräuter für 100 Gold","berge.php?op=kraut");
        addnav("Pilze für 500 Gold","berge.php?op=pilz");
        addnav("Früchte für 1000 Gold","berge.php?op=beere");
        addnav("weitergehen","berge.php?op=cont");
        $session[user][specialinc] = "trapper.php";
        break;
        case 6:
        output("Sofort kommt er zu dir und fragt dich aus, ob du seltene Tiere
        gesehen hast, wo und wann. Bereitwillg gibst du Auskunft.`n`n
        Zum Dank für deine Hilfe gibt dir der Trapper `Qeinen Edelstein`0, den er
        irgendwo gefunden hat.`0");
        $session[user][gems]++;
        break;
    }
}
else if ($_GET[op]=="kraut"){
    if ( $session[user][gold] < 100 ) {
        output("`nAls der `3Trapper`0 mit bekommt, dass du nicht genug Gold dabei hast,
        wird er wütend. Schnell steckt er die Kräuter wieder ein und schubst dich
        ein Stück zurück. Du trittst nach hinten, um nicht das Gleichgewicht zu
        verlieren - leider genau in eine Bärenfalle.`n`n
        `$ Du sitzt hier erst mal fest und kannst heute nicht mehr kämpfen!`0");
        $session[user][turns]=0;
    }
    else {
        $was = e_rand (1,100);
        if ( $was < 25 ) {
            output("Du gibst dem `3Trapper`0 die 100 Gold und nimmst die Kräuter gleich
            in den Mund, um sie zu zerkauen.`n
            Eine Wirkung kannst du jedoch nicht spüren.`n`n
            `9Der Trapper ist schon nicht mehr zu sehen, nur sein Lachen hörst du noch...`0");
        }
        else {
            output("Nachdem du die 100 Gold gegeben hast, zerkaust du hastig die
            dargereichten Kräuter. Es kratzt etwas im Hals, aber du spürst auch eine
            wohlige Wärme.`n`n`^Du regenerierst vollständig.`0");
            if ($session[user][hitpoints] < $session[user][maxhitpoints] )
                $session[user][hitpoints] = $session[user][maxhitpoints];
        }
        $session[user][gold]-=100;
    }
    $session[user][specialinc] = "";
}
else if ($_GET[op]=="pilz"){
    if ( $session[user][gold] < 500 ) {
        output("`nAls der `3Trapper`0 mit bekommt, dass du nicht genug Gold dabei hast,
        wird er wütend. Schnell steckt er die Pilze wieder ein und schubst dich
        ein Stück zurück. Du trittst nach hinten. um nicht das Gleichgewicht zu
        verlieren - leider genau in eine Bärenfalle.`n`n
        `$ Du sitzt hier erstmal fest und kannst heute nicht mehr kämpfen!`0");
        $session[user][turns]=0;
    }
    else {
        $was = e_rand (1,100);
        if ( $was > 70 ) {
            output("Du gibst dem `3Trapper`0 die 500 Gold und nimmst die Pilze gleich
            in den Mund, um sie zu zerkauen.`n
            Eine Wirkung kannst du jedoch nicht spüren.`n`n
            `9Der Trapper ist schon nicht mehr zu sehen, nur sein Lachen hörst du noch...`0");
        }
        else {
            output("Nachdem du die 500 Gold gegeben hast, zerkaust du hastig die
            dargereichten Pilze. Es kratzt etwas im Hals, aber du spürst auch eine
            wohlige Wärme.`n`n`^Deine Lebenspunkte verdoppeln sich.`0");
            $session[user][hitpoints] *= 2;
        }
        $session[user][gold]-=500;
    }
    $session[user][specialinc] = "";
}
else if ($_GET[op]=="beere"){
    if ( $session[user][gold] < 1000 ) {
        output("`nAls der `3Trapper`0 mitbekommt, dass du nicht genug Gold dabei hast,
        wird er wütend. Schnell steckt er die Beeren wieder ein und schubst dich
        ein Stück zurück. Du trittst nach hinten, um nicht das Gleichgewicht zu
        verlieren - leider genau in eine Bärenfalle.`n`n
        `$ Du sitzt hier erstmal fest und kannst heute nicht mehr kämpfen!`0");
        $session[user][turns]=0;
    }
    else {
        $was = e_rand (1,100);
        if ( $was > 40 ) {
            output("Du gibst dem `3Trapper`0 die 1000 Gold und nimmst die Früchte gleich
            in den Mund, um sie zu zerkauen.`n
            Eine Wirkung kannst du jedoch nicht spüren.`n`n
            `9Der Trapper ist schon nicht mehr zu sehen, nur sein Lachen hörst du noch...`0");
        }
        else {
            output("Nachdem du die 1000 Gold gegeben hast, zerkaust du hastig die
            dar gereichten Früchte. Es kratzt etwas im Hals, aber du spürst auch eine
            wohlige Wärme.`n`n`^Du bekommst einen `bpermanenten`b Lebenspunkt!`0");
            $session[user][maxhitpoints]++;
        $session[user][hitpoints]++;
        }
        $session[user][gold]-=1000;
    }
    $session[user][specialinc] = "";
}
else if ($_GET[op]=="cont"){   // einfach weitergehen
    output("`n`QDu lässt den Trapper stehen. Bestimmt eh ein Gauner...`0");
    $session[user][specialinc]="";
}
?>

