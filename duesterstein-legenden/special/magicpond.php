
<?
/*
Originally Made By : Zousug - Magic Pond
Rewritten by Robert of Maddnet - Version 1.0
- All code errors removed
- Optimized for efficiency
- Spell checked to remove errors
-----
Translation by gargamel @ www.rabenthal.de
*/
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`n`3Du streifst durch den Wald auf der Suche nach einem schönen Plätzchen
    für eine Rast, als Du plötzlich über einen Baumstumpf stolperst und hinfällst.
    Du landest in weichem Matsch.`n
    Neben Dir bemerkst Du eine Pfütze. Als Du in das spiegelnde Wasser siehst, erkennst
    Du, dass Du `^".$session['user']['charm']."`3 Charmpunkte hast.`n`n
    Dann siehst Du das Schild, dass neben der Pfütze aufgestellt ist und liest:
    \"Diese Pfütze ist verzaubert - Verschwinde oder fordere Dein Glück heraus!\"`0");
    //abschluss intro
    addnav("Trinke aus Pfütze","forest.php?op=drink");
    addnav("Verschwinde","forest.php?op=cont");
    $session[user][specialinc] = "magicpond.php";
}
else if ($HTTP_GET_VARS[op]=="drink"){
    if ($session['user']['charm']>65){
        output("`n`n`3Mutig und durstig wie Du bist, beugst Du Dich über die Pfütze
        und schlürfst einen grossen Schluck des Wassers.`n`0");
        switch ( e_rand (1,15) ) {
            case 1: case 2: case 3: case 4: case 5:
            output("Nun wartest Du, was passiert. Aber es passiert nichts.`n
            Du überlegst kurz, ob das gut oder schlecht ist und gehst dann weiter,`0");
            break;
            case 6: case 7:
            $session['user']['charm']+=2;
            $c = $session['user']['charm'];
            output("Nun wartest Du, was passiert. Als Du zunächst nichts spüren kannst,
            schaust Du wieder in die Pfütze. Du siehst, dass Du nun $c Charmepunkte hast.`n
            `3Du musst 2 dazubekommen haben...`0");
            break;
            case 8:
            output("Nun wartest Du, was passiert. Zunächst kannst Du keinerlei Wirkung
            feststellen, aber dann...`n`n`0");
            increment_specialty();
            break;
            case 9: case 10: case 11: case 12: case 13:
            output("Nun wartest Du, was passiert. Aber es passiert nichts. Du forderst
            das Glück heraus und trinkst die Pfütze leer.`n
            Nun spürst Du eine komische Kraft, die von Deinem Magen aus durch den
            Körper zieht.`n
            `3Du hast nun Kraft für einen zusätzlichen Waldkampf.`0");
            $session['user']['turns']+=1;
            break;
            case 14: case 15:
            output("Nun wartest Du, was passiert. Aber es passiert nichts. Du forderst
            das Glück heraus und trinkst die Pfütze leer.`n
            Nun spürst Du eine komische Kraft, die von Deinem Magen aus durch den
            Körper zieht.`n
            `3Du hast nun Kraft für zwei zusätzliche Waldkämpfe.`0");
            $session['user']['turns']+=2;
            break;
        }
    }
    else {
        output("`n`&Du bist der Pfütze zu hässlich, sie verweigert Dir ihren Zauber.`0");
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="cont"){   // einfach weitergehen
    output("`n`3Schnell verlässt Du diesen Ort. Verzauberte Pfützen haben Dir noch nie
    Glück gebracht.`0");
    $session[user][specialinc]="";
}
?>


