
<?php

require_once "common.php";
addcommentary();
checkday();

page_header("Das dunkle Waldstück");
$session['user']['standort']="Dunkles Waldstück";
addnav("Dunkle Kathedrale","kathedrale.php");
addnav("Pilze suchen","pilzsuche.php");
addnav("Zurück");
addnav("Zurück zum Sumpf","suempfe.php");
addnav("Z?Zurück zum Dorf","village.php");

output("`c`b`BD`Ta`js d`Úunk`Óle Wald`Úst`jü`Tc`Bk`b`n`n");
output("<img src='./images/dwald.gif'>`n`n`n`c",true);
output("`ÓVors`Úicht`jig sc`Thaus`Bt du i`Tn das `jWald`Ústüc`Ók, welches sich vor dir ausstreckt. Es wirkt unheimlich und das Rufen eines Kauzes, welches zu hören ist, lässt dich `Úkurz`jerha`Tnd auf`Bschr`Tecke`jn. Auc`Úh die `ÓEule, die durch die Nacht ruft, klingt anders als sonst. Es scheint, a`Úls spu`jke es i`Tn die`Bsem Wa`Tld. T`jiefe`Úr geh`Óst du hinein und die großen, dicht bewachsenen Bäume links und rechts neben dir tü`Úrmen s`jich au`Tf, als w`Bürden s`Tie leb`jendig`Ú. Es s`Ócheint, als funkelten dich ihre gelbe Augen an, ber`Úeit un`jd den`Tnoch `Bnur d`Tara`juf war`Útend, si`Óch auf dich stürzen zu können. Du fühlst dich gelähmt und kannst nichts mac`Úhen, a`juße`Tr hie`Br zu w`Tarte`jn, das`Ús sie `Ódich holen, die unheimliche Stimmung zu genießen oder diesen Ort schleuni`Úgst wied`jer zu `Tverla`Bssen. `n`n`n");
viewcommentary("Waldstueck","Hinzufügen",25,"flüstert",1,1);

page_footer();
?>

