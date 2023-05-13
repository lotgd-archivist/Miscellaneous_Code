
<?php
require_once "common.php";
addcommentary();
checkday();
page_header("Die Rosengärten");
$session['user']['standort']="Die Rosengärten";

$session[user][location]=11;



    output("`b`c`€D`:i`;e `fR`Ao`%s`ren`%g`Aä`fr`:t`:e`€n`0`c`b`n");
//output("`c<img src='http://abanai.family-board.de/images/rosengarten.jpg' width='200' height='142'> `c",true);
output("`n`€Du hast einen w`:eiteren Weg ent`;deckt und gehs`ft ihn neugier`;ig entlang. Da `:öffnet sich vor d`€ir ein wunderba`:res Bild: Ein rie`;siger Garten vol`fler Rosensträ`;ucher, die in a`:llen Größen un`€d Farben blüh`:en! Ein herrlich`;er Duft umweht `fdeine Nase, un`;d ohne es zu `:wissen, musst d`€u lächeln. Du füh`:lst dich woh`;l und gehst i`fmmer weiter in d`;iesen Garten h`:inein, genieß`€t deinen Aufen`:thalt hier un`;d möchtest n`foch verweile`;n.`n`n");
viewcommentary("gartenrp","Hinzufügen",25,"sagt",1,1);

if ($session['user']['rp_only']!=0){ addnav("Nähere Umgebung");
 addnav("Durch den Garten schlendern","rosengeist.php");
 addnav("Tempel der Götter","lodge.php");
 }


addnav("Die Stadt","village.php");
page_footer();
?>

