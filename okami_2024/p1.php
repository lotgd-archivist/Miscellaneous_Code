
<?php
require_once "common.php";
addcommentary();

page_header("Tiefer im Paradies.");
$session[user][standort]="Paradies";

output("`c`&Du bist tiefer ins Paradies gewandert und siehst dich verwundert um.`n
`rAnders als im vorderen Teil ist hier noch alles in Ordnung und bunt.`n
`gEin weiser Kieselweg führt dich durch diesen Teil des Pradieses.`n
`vEr führt dich zu verschiedenen Pavillon, der richtige Ort für verliebte.`n
`tDer Weg endet an einer kleinen Kapelle welche wohl für Hochzeiten sind.`0`c`n`n");
output("`n`n`gMit anderen flüstern:`n");
viewcommentary("paradies","flüstern",5);

addnav("Pavillon");
addnav("`&Frühlingspavillon`0","p2.php");
addnav("`rSommerpavillon`0","p3.php");
addnav("`gHerbstpavillon`0","p4.php");
addnav("`vWinterpavillon`0","p5.php");
addnav("`tTeich`0","p6.php");
addnav("`8Kirche`0","kir.php");
addnav("Zurück");
addnav("Paradies","gardens.php");
page_footer();
?>

