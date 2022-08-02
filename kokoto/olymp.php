<?php
/*_____________________________________________________________
  |Der Olymp, ein Ort für Veteranen                           |
  |von Lord Eliwood                                           |
  |___________________________________________________________|
*/
/*___________________________________________________________________________________
  |Installation:                                                                     |
  |füge irgendwo im Dorf folgendes ein:                                              |
  |if (($session['user']['dragonkills']>=x) || ($session['user']['superuser']>=2))   |
  |addnav("Olymp","olymp.php");                                                      |
  |Wobei x durch die Anzahl DKs zu ersetzen ist, ab der man ein Gott ist(Unter- etc. |
  |__________________________________________________________________________________|
*/
require_once "common.php";
page_header("Seltsame Lichtung");
addcommentary();

output('`c`b`&Olymp`c`b`n`nEin heiliger Ort, hoch über den Wolken. Du siehst verschiedene beschäftigte Götter und Läden, wie Hepaistos Schmiede. Ein Weg führt zum Gott Zeus, andere Götter rennen über den Dorfplatz. Da du nun auch einer aus ihren Reihen bist, bist du kein niederes Objekt mehr, du gehörtst nun nicht mehr zu denen, du bist nun ein Gott.`n`n');
//addnav("Hephaistos' Schmiede","gottschmiede.php"); Kann ich nicht mitliefern, da des Haganirs Schmiede ist mit umgeschriebenen Texten
addnav("Zeus' Trohn","zeus.php");
addnav("Kratos' Waffenshop","kratos.php");
addnav("Zelos' Rüstungen","zelos.php");
addnav("Zurück zum Dorfrand","village.php?op=abseits");

output('`n`n`%`@In der Nähe sprechen einige Götter:`n');
viewcommentary("olymp","Hinzufügen",10,"spricht");
page_footer();
?>