<?php

require_once "common.php";
addcommentary();
checkday();


page_header("Besprechungszimmer");
switch($_GET['op']) {


    case "":
    output("`(Du betrittst den Raum hinter dem Verhandlungssaal, hier sind nur die wichtigsten Personen zugelassen. Es befindet sich in der Mitte des Raumes ein grosser runder Tisch mit einigen ");
    output(" `(Holzstühlen, sie sehen sehr bequem aus. Auf dem Tisch liegen PergamenteAktenordner mit den aktuellen Fällen.");
    
    addnav("Zurück zum Gericht","gericht.php");
    
            addcommentary();
        output("`n`n`n`n`n`n`n`n`n`n`n`n`n");
        viewcommentary("Richter-lounge","`vHinzufügen",15);
    break;
    

}


    
page_footer();
?> 