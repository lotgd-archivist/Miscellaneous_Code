<?php 
require_once "common.php"; 
page_header("Hafen"); 
$aktiv = getsetting("angriff","0");
 if ($aktiv==1) {
 $anzahl = getsetting("dangreifer","0");
 }
output("`^`c`bHafen`b`c`6"); 
addcommentary();
if ($_GET[op]==""){
output ("Die Hafengegend mag zwar nicht die Schönste sein, dennoch werden hier die meisten Geschäfte abgewickelt. Als du dich genauer umschauen willst fällt dir der Nebel auf, welcher deine Sicht stark einschränkt. Dennoch kannst du vereinzelt Geschäfte und Lagerhäuser sehen. Die Anlegestellen für die Schiffe strecken sich weit ins Meer hinaus und als du einen von Ihnen betrittst entdeckst du das Schiff nach Symia.`n`n");
 
 viewcommentary("hafen","brüllt heiser:",25);
 if ($aktiv!=1) addnav ("Zum Schiff nach Symia (3 Gold)","hafen.php?op=necron");
addnav("Mericks Ställe","stables2.php");
addnav("Kräuterladen","kraut.php");

addnav("Zurück ins Dorf","village.php"); 
//addnav("Das Meer","meer.php"); noch in arbeit
}
if ($_GET[op]=="necron"){
if ($session[user][gold]>2){
output ("Du betrittst das Schiff und wirst gleich vom Capitän eingewiesen. Dann bezahlst du die 3 Gold und wartest einige Stunden`n");
addnav ("Zum Schiff nach Symia","schiff.php");
addnav ("Zurück zum Hafen","hafen.php");
viewcommentary("hafen2","sagt:",25);
//$session[user][turns]-=0;
}
else{
output ("Du kannst nicht Reisen da du zu Müde bist oder zu Arm!");
addnav ("Zurück zum Hafen","hafen.php");
addnav("Zurück ins Dorf","village.php"); 
}
}
page_footer(); 
?> 