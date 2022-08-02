<?php
if ($_GET[op]=="norp"){
    $session[user][specialinc]="";
    output("`2Dir kommt das Ganze etwas komisch vor. Du machst lieber einen großen Bogen um diese Flüssigkeit und ziehst weiter.");
    addnav("Zurück in den Wald","forest.php");

}else if ($_GET[op]=="trinken") {
    $session[user][specialinc]="";

    $rand = e_rand(1,3);

    output("`2Du nimmst einen kräftigen Schluck und wartest ab, was passiert..`n`n");

    switch ($rand){
        case 1:
        output("`2Außer, dass die Brühe kalt war hast du nichts weiter gespürt.`n");
        output("`2Naja du fühlst dich wenigstens wieder frisch.`n`n");
        addnav("Zurück in den Wald","forest.php");
        break;

        case 2:
        output("`^Du spürst, wie dein Blut pulsiert und sich das blaue Glühen auf deinen Körper überträgt.`n`n");
        output("`^Als du deine Hand ansiehst, fällt dir auf, dass dort das Glühen gebündelt wird.`n");
        $session[user][gems]++;
        output("`^Ein Edelstein hat sich in deiner Hand gebildet. Du hast jetzt insgesamt ".$session[user][gems].", `n");
        output("wird es nicht Zeit, langsam ein Juweliergeschäft zu eröffnen?");
        addnav("Zurück in den Wald","forest.php");
        break;

        case 3:
        output("`^Du fühlst wie dein Körper regeneriert.`n");
        output("`^Jetzt wo du weißt, dass es eine Heilquelle ist und keine Gefahr davon ausgeht, entspannst du dich noch ein wenig `n");
        output("und träumst davon den Grünen Drachen zu besiegen.");
        if ($session[user][turns]>0) $session[user][turns]--;
        $session[user][hitpoints] = $session[user][maxhitpoints];
        addnav("Zurück in den Wald","forest.php");
    break;
    }
}else{
    output("`2Du stehst vor einer alten Tempelruine. Einige Säulen sind zerfallen und liegen verteilt auf dem Boden.`n");
    output("`2Vorsichtig schaust du dich etwas um und entdeckst eine kleine Quelle, die aus einer Wand des Gemäuers austritt.`n`n");
    output("`2Dir fällt auf, dass das Wasser leicht bläulich glüht. Nach näherer Untersuchung kannst du nichts feststellen ");
    output("außer das es eben bläulich glüht.`n");
    addnav("Trinken","forest.php?op=trinken");
    addnav("Zurück in den Wald","forest.php?op=norp");
$session[user][specialinc]="lake.php";
}
?>