
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($_GET[op]==""){
    output("`3Obwohl durch üppig gewachsene Büsche gut versteckt, entdeckst Du neben
    einem Felsen einen Tunneleingang. Ob Du die sagenhafte Goldmine gefunden hast? `n
    Gierig läufst Du zum Eingang.`n`n
    Du schaust Dich etwas um und bemerkst eine Inschrift, die in den Fels geritzt ist.
    Sie muss sehr alt sein, den durch Verwitterung ist sie kaum lesbar:`n
    `4\"Vor__cht. Der Tu_nel _ührt in fern_ _elten!\"`n`n
    `3Was das wohl heissen mag? `9Du zögerst...`0");
    //abschluss intro
    addnav("Tunnel betreten","berge.php?op=go");
    addnav("Weg fortsetzen","berge.php?op=away");
    $session[user][specialinc] = "tunnels.php";
}
else if ($_GET[op]=="go"){
    output("Du betrittst den Tunnel und atmest abgestandene Luft ein. Was sollte
    die Inschrift bedeuten? Du folgst dem Gang durch eine Biegung.`n`n`0");
    switch(e_rand(1,4)){
        case 1:
        $plus = round($session[user][experience]*0.01);
        output("Plötzlich spürst Du einen Energiestoß.`n`n`6Du fühlst Dich gut und erhältst
        $plus Erfahrungspunkte!`0");
        $session[user][experience]+=$plus;
        break;

        case 2:
        output("Dir wird schwarz vor Augen und Du fühlst Dich benommen. Du nimmst
        die Umgebung nur durch einen Schleier war. `\$ Dein Unterbewußtsein registriert
        eine Ortsveränderung.`0`n
        Nun weißt Du, was die Inschrift mit \"fernen Welten\" gemeint hat.`n`\$ Du wachst ");
        $wo = e_rand(1,4);
        switch ( $wo ) {
            case 1:
            output("in den Gärten wieder auf.`0");
            addnav("Weiter","gardens.php");
            break;
            case 2:
            output("auf dem Dorfplatz wieder auf.`0");
            addnav("Weiter","village.php");
            break;
            case 3:
            output("hinter den Ställen wieder auf.`0");
            addnav("Weiter","stables.php");
            break;
            case 4:
            output("im Klostergarten wieder auf.`0");
            addnav("Weiter","klostergarten.php");
            break;
        }
        break;

        case 3:
        output("Dann folgst Du einer weiteren Biegung und noch einer. Du siehst
        absolut nichts. Vielleicht wurde die Inschrift ja auch absichtlich unleserlich
        gemacht, weil sie nicht stimmt...`n
        `3Du vertrödelst einen Waldkampf.`0");
        $session[user][turns]--;
        break;

        case 4:
        $minus = round ($session[user][experience]*0.01);
        output("Du bist kurz unachtsam und stößt mit Deiner Waffe an die Decke.
        Ein Stein löst sich und fällt Dir auf den Kopf.`n
        `QDurch diese Dummheit verlierst Du $minus Erfahrungspunkte, gewinnst aber einen Gem, als der sich der Stein heraus stellt!`0");
        $session[user][gems]+=1;
        $session[user][experience]-= $minus;
        break;
    }
    $session[user][specialinc]="";
}
else if ($_GET[op]=="away"){   // einfach weitergehen
    output("`5Du gehst weiter und lässt den Tunnel Tunnel sein.`0");
    $session[user][specialinc]="";
}
?>

