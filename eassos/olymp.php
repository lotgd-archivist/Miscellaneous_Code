
<?php
/*_____________________________________________________________
  |Der Olymp, ein Ort für Veteranen                           |
  |von Lord Eliwood                                           |
  |___________________________________________________________|
*/
/*___________________________________________________________________________________
  |Installation:                                                                     |
  |füge irgendwo im Dorf folgendes ein:                                              |
  |if (($session['user']['dragonkills']>=x) || ($session['user']['superuser']>=2))   |
  |addnav("Olymp","olymp.php");                                                      |
  |Wobei x durch die Anzahl DKs zu ersetzen ist, ab der man ein Gott ist(Unter- etc. |
  |__________________________________________________________________________________|
*/
require_once "common.php";
place(1);
page_header("Seltsame Lichtung");
addcommentary();

output("`c`b`&Olymp`c`b`n`n");
output("Ein heiliger Ort, hoch über den Wolken. Du siehst verschiedene beschäftigte Götter und Läden, wie Hephaistos Schmiede.");
output("Ein Weg führt zum Gott Zeus, andere Götter rennen über den Dorfplatz. Da Du nun auch einer aus ihren Reihen bist, bist Du");
output("kein niederes Objekt mehr, Du gehörtst nun nicht mehr zu denen, du bist nun ein Gott.`n`n");
//addnav("Hephaistos' Schmiede","gottschmiede.php"); Kann ich nicht mitliefern, da des Haganirs Schmiede ist mit umgeschriebenen Texten
addnav("Zeus' Thron","zeus.php");
addnav("Kratos' Waffenshop","kratos.php");
addnav("Zelos' Rüstungen","zelos.php");
addnav("Zurück in den Wald","forest.php");
addnav("Zurück nach Astaros","village.php");

output("`n`n`%`@In der Nähe sprechen einige Götter:`n`0");
viewcommentary("olymp","Hinzufügen",25,"spricht");
page_footer();
?>

