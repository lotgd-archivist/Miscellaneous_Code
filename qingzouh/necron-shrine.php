
<?php

// 23052005

// Poseidon by Gimmick

require_once "common.php";

page_header("Schrein des Poseidon");


output("`b`c`2Schrein des Poseidon`0`c`b");
if ($_GET[op]==""){
output ("Du betrittst erfürchtig den Schrein des Poseidon und siehst dich etwas um,  an der linken Wand steht ein Mann und an der anderen sieht man eine große Statue`n");
addnav ("Den Priester ansprechen","necron-shrine.php?op=speak");
addnav ("Zur Statue","necron-shrine.php?op=stat");
addnav ("Nach Necron","necron.php");
}
if ($_GET[op]=="speak"){
output ("Du gehst zum Priester und guckst ihn fragend an`n");
addnav ("Was ist das für ein Tempel?","necron-shrine.php?op=quest");
addnav ("Kann ich der Bruderschaft beitreten?","necron-shrine.php?op=monk");
addnav ("Zurück zum Schrein","necron-shrine.php");
}
if ($_GET[op]=="quest"){
output ("Der Tempel des Poseidon ist ein Treffpunkt für Pilger aus der ganzen Welt um dem großen Herren Poseidon.");
output ("Dieser Tempel wurde im Jahre 0000 erbaut und wird von der Firma Sharks Co KG finanziert.");
addnav ("Zurück","necron-shrine.php");
}
if ($_GET[op]=="monk"){
output ("Entschuldige momentan nehmen wir keine Brüder auf, aber bald....");
addnav ("Zurück","necron-shrine.php");
}
if ($_GET[op]=="stat"){
output ("Sie stellt Poseidon da wie er Menschen mit einem Dreizack brät. Du findest sie langweilig, und gehst zurück.....");
addnav ("Zurück zum Schrein","necron-shrine.php");
}
page_footer();
?>

