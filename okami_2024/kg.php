
<?php
require_once "common.php";
addcommentary();
$session[user][standort]="Kämpfergasse";
page_header("Gasse für Krieger");

addnav("Geschäfte");
addnav("W?Yangs Waffen","weapons.php");
addnav("R?Yings Rüstungen","armor.php");
addnav("Söldner","stables.php");
addnav("Training");
addnav("Trainingslager","train.php");
addnav("A?Die Arena","pvparena.php");
if (@file_exists("lodge.php"))    addnav("J?Jägerhütte","lodge.php");
addnav("Arbeiten","job.php");
addnav("Dojo","dojo.php");
addnav("Wege");
addnav("Platz","village.php");
addnav("Markt","mg.php");
addnav("Zur Schenke Brennenden Wolf","inn.php");

output("`c`)Als du dich durch die Trümmer gekämpft hast findest du einige Geschäfte.
Im ersten Moment sehen sie geschlossen aus, aber an jeder Tür steht geöffnet.`c`n`n");
output("`n`n`_Mit anderen reden:`n");
viewcommentary("kg","aktion",5);

page_footer();
?>

