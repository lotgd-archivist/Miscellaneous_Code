
<?php 

//Idee by theKlaus 

if (!isset($session)) exit(); 

output("<img src='images/orkploerre.jpg' width='250' height='173' alt='Orkplörre' align='right'>",true); 

output("`^Du entdeckst eine Lederflasche mit Orkplörre, die über einem Baumstumpf hängt.  Es ist auf ihr vermerkt, dass sie aus der Haut eines Kämpfer gefertigt ist, den du als sehr starken Krieger kanntest. Du beschließt, dass es nicht schaden kann, einen Schluck daraus zu nehmen.`n`n`nMann, ist das ein geiles Zeugs!  Du fühlst dich `!super`^.`n`n`%Du erhältst einen extra Waldkampf!`^ `0"); 

$session[user][thirsty]+=2;
$session[user][turns]++; 
?>

