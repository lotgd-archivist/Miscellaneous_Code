
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Kirche";
page_header("Kirche");

output("`c`8Ehrfurchtsvoll betrittst du die Kirche. Hoch über Dir spannt sich das kuppelförmige Dach wie ein Zelt über`n
die weite, an der Frontseite in einen Rundbogen übergehende Halle. Durch hohe, schmale Rundbogenfenster an`n
den Seitenwänden fällt etwas Tageslicht in den Raum. Darunter verläuft ein quadratischer Säulengang, hinter`n
dem eine Pforte ins Allerheiligste führt.Den vorderen Teil dominiert ein erhöht stehender, marmorner Tisch,`n
verziert mit vielerlei magischen Symbolen. Dies scheint der Altar zu sein. Auf der rechten Seite, hinter`n
den Säulen entdeckst einen kleineren Altar, der für Opfer gedacht zu sein scheint.`c`n`n");
output("`n`n`gMit anderen flüstern:`n");
viewcommentary("kir","flüstern",15);

addnav("Pavillon");
addnav("`&Frühlingspavillon`0","p2.php");
addnav("`rSommerpavillon`0","p3.php");
addnav("`gHerbstpavillon`0","p4.php");
addnav("`vWinterpavillon`0","p5.php");
addnav("`tTeich`0","p6.php");
addnav("Kirche");
addnav("`sZeremonien.`0","zere.php");
addnav("Tempel der Toleranz","goettertempel.php");
addnav("Zurück");
addnav("zurück","p1.php");
page_footer();
?>

