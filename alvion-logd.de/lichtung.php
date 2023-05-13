
<?php

// 13_04_2007 by Linus

require_once "common.php";
addcommentary();
checkday();

page_header("Waldlichtung");
$session['user']['standort']="Waldlichtung";


//if (@file_exists("schloss.php")) addnav("S?Zum Schloss gehen","schloss.php");
//if (@file_exists("brunnen.php")) addnav("B?verzauberter Brunnen","brunnen.php");
//addnav("G?Die Gärten","gardens.php");
//if (@file_exists("tanzsaal.php")) addnav("B?Tanzsaal","tanzsaal.php");
//addnav("l?Blumenmädchen","blumenmaedchen.php");
if (@file_exists("anglerhuette.php")) addnav("H?Hütte","anglerhuette.php");
if (@file_exists("wasserfall.php")) addnav("W?Zum Wasserfall","wasserfall.php");
addnav("k?Waldkapelle","kapelle.php");
addnav("Z?Zurück zum Dorf","village.php");


output("`oAls d`2u au`²f dies`µe Waldlichtung trittst bi`²st du er`2st einm`oal beeindruckt von de`2r Vie`²lfal`µt der hier wachsenden Pf`²lanze`2n. Allei`one die Größe ist ate`2mbera`²uben`µd. Sogar ein kleiner Wald`²see f`2inde`ot hier Platz und h`2ier u`²nd da f`µlattern einige Vög`²el zwi`2sche`on den Ästen der B`2äum`²e umhe`µr. Im Dorf erzählt ma`²n sic`2h soga`or, dass es hier hinter de`2n Büs`²che`µn einen Wasserfa`²ll ge`2ben sol`ol. Vielleicht solltest du d`2en Ge`²rücht`µen einmal auf den Gr`²und ge`2hen?`o Oder möchtest du doc`2h lieb`²er zue`µrst eine kleine Boo`²tsfa`2hrt `oüber den Waldsee unter`2neh`²men`µ?");

output("`n`n`%`@In deiner Nähe reden einige Dorfbewohner:`n");
viewcommentary("Lichtung","Hinzufügen",25,"spricht",1,1);

page_footer();
?>

