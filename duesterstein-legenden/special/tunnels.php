
<?
// idea of gargamel @ www.rabenthal.de
if (!isset($session)) exit();

if ($HTTP_GET_VARS[op]==""){
    output("`nObwohl durch üppig gewachsene Büsche gut versteckt, entdeckst Du neben
    einem Felsen einen Tunneleingang. Ob Du die sagenhafte Goldmine gefunden hast? `n
    Gierig läufst Du zum Eingang.`n`n
    Du schaust Dich etwas um und bemerkst eine Inschrift, die in den Fels geritzt ist.
    Sie muss sehr alt sein, den durch Verwitterung ist sie kaum lesbar:`n
    \"Vor__cht. Der Tu_nel _ührt in fern_ _elten!\"`n`n
    Was das wohl heissen mag? `9Du zögerst...`0");
    //abschluss intro
    addnav("Tunnel betreten","forest.php?op=go");
    addnav("Weg fortsetzen","forest.php?op=away");
    $session[user][specialinc] = "tunnels.php";
}
else if ($HTTP_GET_VARS[op]=="go"){
    output("`nDu betrittst den Tunnel und atmest abgestandene Luft ein. Was sollte
    die Inschrift bedeuten? Du folgst dem Gang durch eine Biegung.`n`n`0");
    switch(e_rand(1,4)){
        case 1:
        $plus = round($session[user][experience]*0.01);
        output("Plötzlich spürst Du einen Energiestoß. `6Du fühlst Dich gut und erhälst
        $plus Erfahrungspunkte!`0");
        $session[user][experience]+=$plus;
        break;
        
        case 2:
        output("Dir wird schwarz vor Augen und Du fühlst Dich benommen. Du nimmst
        die Umgebung nur durch einen Schleier war. `$ Dein Unterbewußtsein registriert
        eine Ortsveränderung.`0`n
        Nun weißt Du, was die Inschrift mit \"fernen Welten\" gemeint hat. `$ Du wachst ");
        $wo = e_rand(1,4);
        switch ( $wo ) {
            case 1:
            output("an der alten Eiche wieder auf.`0");
            addnav("Weiter","gardens.php?op=oldoak");
            break;
            case 2:
            output("auf dem Dorfplatz wieder auf.`0");
            addnav("Weiter","village.php");
            break;
            case 3:
            output("hinter Merricks Ställen wieder auf.`0");
            addnav("Weiter","stables.php");
            break;
            case 4:
            output("an einem seltsamen Felsen auf.`0");
            addnav("Weiter","rock.php");
            break;
        }
        break;
        
        case 3:
        output("Dann folgst Du einer weiteren Biegung und noch einer. Du siehst
        absolut nichts. Vielleicht wurde die Inschrift ja auch absichtlich unleserlich
        gemacht, weil sie nicht stimmt...`n
        `3Du vertrödelst einen Waldkampf.`0");
        $session[user][turns]--;
        break;
        
        case 4:
        $minus = round ($session[user][experience]*0.01);
        output("Du bist kurz unachtsam und stößt mit Deiner Waffe an die Decke.
        Ein Stein löst sich und fällt Dir auf den Kopf.`n
        `QDurch diese Dummheit verlierst Du $minus Erfahrungspunkte!`0");
        $session[user][experience]-= $minus;
        break;
    }
    $session[user][specialinc]="";
}
else if ($HTTP_GET_VARS[op]=="away"){   // einfach weitergehen
    output("`n`5Du gehst weiter und lässt den Tunnel Tunnel sein.");
    $session[user][specialinc]="";
}
?>


