<?php
/*
Ritual by dso 10/2004
*/

require_once("common.php");
addcommentary();
checkday();

page_header("Geheimnisvolle Lichtung");

addnav("Zum Wald","forest.php");
    /*###oldtext###
    output('`4Du siehst eine Lichtung, umgeben von dunklen hohen Bäumen.
    In der Mitte findest du einen in die Erde gezogenen Kreis, umrandet von 5 Felsbrocken.
    Neben dem Kreis steht ein sonderbarer Altar, überhäuft von übel riechenden Kräutern und getrocknetem Wachs.
    Als du dich dem Kreis näherst überkommt dich ein Schaudern. Du hast das Gefühl diesen Ort schnellstens
    wieder verlassen zu müssen. Auf deinem Weg zurück in den Wald könntest du schwören, leise gemurmelte Worte
    zu hören, die dir vor Schreck fast das Herz stehen lassen.');
    */
    output('`4Du siehst eine Lichtung, umgeben von dunklen, hohen Bäumen.
    Dir gegenüber kannst du einen in die Erde gezogenen Kreis, umrandet von 5 Felsbrocken,
    und einen Altar erblicken. Links von dem Waldweg, den du eben entlanggekommen bist, siehst du noch ein paar
    Eisenstücke im Rasen liegen, Überbleibsel irgendwelcher Rüstungen oder Waffen.
    Es scheint, als ob hier nicht nur Hexer und Magier zugange wären, sondern auch schon etliche Kämpfe stattgefunden hätten.
    Auf deinem Weg zurück in den Wald könntest du schwören, leise gemurmelte Worte
    zu hören, die dir vor Schreck fast das Herz stehen lassen.`n`n`n');
viewcommentary("ritual","Mit Anwesenden reden:",25,"sagt");

page_footer();
?>