<?php
/*
Talkerplace by dso 10/2004
*/

require_once("common.php");
addtalkervillage();
checkday();

page_header("Erzählerplatz");

addnav("Zum Dorfplatz","village.php");
    /*###oldtext###
    output('`4Du siehst eine Lichtung, umgeben von dunklen hohen Bäumen.
    In der Mitte findest du einen in die Erde gezogenen Kreis, umrandet von 5 Felsbrocken.
    Neben dem Kreis steht ein sonderbarer Altar, überhäuft von übel riechenden Kräutern und getrocknetem Wachs.
    Als du dich dem Kreis näherst überkommt dich ein Schaudern. Du hast das Gefühl diesen Ort schnellstens
    wieder verlassen zu müssen. Auf deinem Weg zurück in den Wald könntest du schwören, leise gemurmelte Worte
    zu hören, die dir vor Schreck fast das Herz stehen lassen.');
    */
    output('`4Hier sitzen die Erzähler
    Beeinflusse das Leben auf den Durfplatz... Es wird kein Name gepostet sondern nur ein Text...Farben erlaubt aber keine Emotes.`n`n`n');
viewcommentary("village","Das leben beeinflussen",25," ");

page_footer();
?>