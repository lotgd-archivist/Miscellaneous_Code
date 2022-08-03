<?php 
// occ raum hier können die user Out of Charakter sprchen. 

require_once('common.php'); 
addcommentary(); 
page_header('OOC Raum'); 
//$session['user']['standort'] // wen du den Standort in der Kriegerliste angeben willst^^ 
if ($_GET['op']==''){ 
 output('`&Du betrittst den `2O`&ut `2o`&f `2C`&harakter Raum (`2OOC`&)! Hier kannst du `2O`&ut `2o`&f `2C`&harakter sprechen.`n`n'); 
 addnav('Zum Dorfplatz','village.php'); 
 viewcommentary('ooc','Hinzufügen',25); 
} 
page_footer(); 
?>