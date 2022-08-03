<?php

// 23052005

// Poseidon by Gimmick

require_once "common.php";

page_header("Schrein des Poseidon");


output("`b`c`2Schrein des Poseidon`0`c`b");
if ($_GET[op]==""){
output ("Du betrittst erfürchtig den Schrein des Poseidon und siehst dich etwas um,  an der linken Wand steht ein Mann und an der anderen sieht man eine große Statue`n");
addnav ("Den Priester ansprechen","zylyma-shrine.php?op=speak");
addnav ("Zur Statue","zylyma-shrine.php?op=stat");
addnav ("Nach Zylyma","zylyma.php");
}
if ($_GET[op]=="speak"){
output ("Du gehst zum Priester und guckst ihn fragend an`n");
addnav ("Was ist das für ein Tempel?","zylyma-shrine.php?op=quest");
addnav ("Kann ich der Bruderschaft beitreten?","zylyma-shrine.php?op=monk");
addnav ("Zurück zum Schrein","zylyma-shrine.php");
}
if ($_GET[op]=="quest"){
output ("Der Tempel des Poseidon ist ein Treffpunkt für Pilger aus der ganzen Welt um dem großen Herren Poseidon.");
output ("Dieser Tempel wurde im Jahre 0000 erbaut und wird von der Firma Sharks Co KG finanziert.");
addnav ("Zurück","zylyma-shrine.php");
}
if ($_GET[op]=="monk"){
output ("Entschuldige momentan nehmen wir keine Brüder auf, aber bald....");
addnav ("Zurück","zylyma-shrine.php");
}
if ($_GET[op]=="stat"){
output ("Sie stellt Poseidon da wie er Menschen mit einem Dreizack brät. Du findest sie langweilig, und gehst zurück.....");
addnav ("Zurück zum Schrein","zylyma-shrine.php");
}
page_footer();
?>