
<?php
require_once "common.php";
addcommentary();

page_header("Tiefer im Paradies.");
$session[user][standort]="Paradies";

output("`c`&Du bist tiefer ins Paradies gewandert und siehst dich verwundert um.`n
`rAnders als im vorderen Teil ist hier noch alles in Ordnung und bunt.`n
`gEin weiser Kieselweg führt dich durch diesen Teil des Pradieses.`n
`vEr führt dich zu verschiedenen Pavillon, der richtige Ort für verliebte.`n
`tDer Weg endet an einer kleinen Kapelle welche wohl für Hochzeiten sind.`0
`c`n`n");
output("`n`n`_Mit anderen flüstern:`n");
viewcommentary("paradies","flüstern",5);


case 'p1':
output("`&Du betrittst den Rosenpavillon.`n");

case 'p2':
output("`rDu betrittst den pavillon.`n");

case 'p3':
output("`gDu betrittst den pavillon.`n");

case 'p4':
output("`vDu betrittst den pavillon.`n");

case 'p5':
output("`tDu betrittst den pavillon.`n");

//addnav("`&Rosenpavillon`0","paradies.php?op=p1");
//addnav("`rpavillon`0","paradies.php?op=p2");
//addnav("`gpavillon`0","paradies.php?op=p3");
//addnav("`vpavillon`0","paradies.php?op=p4");
//addnav("`tpavillon`0","paradies.php?op=p5");
//addnav("`&Kirche0","kirche.php");
addnav("Zurück");
addnav("Paradies","gardens.php");
page_footer();
?>

