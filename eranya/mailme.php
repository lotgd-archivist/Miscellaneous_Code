
<?php
error_reporting(-1);
// Die Nachricht
$nachricht = "Zeile 1\r\nZeile 2\r\nZeile 3";

// Falls eine Zeile der Nachricht mehr als 70 Zeichen enthälten könnte,
// sollte wordwrap() benutzt werden
$nachricht = wordwrap($nachricht, 70, "\r\n");

// Verschicken
mail('mr_edah@gmx.net', 'Mein Betreff', $nachricht);
echo"send";
?>

