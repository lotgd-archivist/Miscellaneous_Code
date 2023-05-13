
<?php

require_once "common.php";
addcommentary();
checkday();
$session['user']['standort']="Beautysalon";

page_header("Kalas Beautysalon");

$out="`c`b`3K`Wa`#la's `#Be`Wa`3ut`*y`3sa`Wlo`#n`c`b`n`n";
$out.="`#Du betrittst den Salon und wirst erst einmal geblendet von der Schönheit der Inhaberin. Nachdem du den ersten Schock ";
$out.="überwunden hast, siehst du dich genauer um: Gedeckte Farben zieren die Wände, hübsch gerahmte Bilder lockern die Linien ";
$out.="etwas auf. Ein großes Fenster, das auf den Marktplatz hinausgeht, spendet ausreichend Licht und überall flackern Kerzen ";
$out.="in schmiedeeisernen Haltern. An zwei Seiten entdeckst du Regale, die mit irdenen Gefäßen und Tiegeln gefüllt sind. In ";
$out.="einer Ecke kannst du gemütliche Sessel und einen kleinen Tisch ausmachen, in denen das Warten bestimmt nicht lang wird. ";
$out.="Hinter einem Paravent steht eine Liege, wo `3K`Wa`#la ihr Wunderwerk vollbringt.`n";
$out.="Diese wartet geduldig, bis du dich ihr wieder zuwendest. Dann fragt sie dich mit einem freundlichen Lächeln: `3\"Was kann ich für Euch tun?\"`n`n";

output($out);
viewcommentary("kala","Mit Kala unterhalten",25,"sagt",1,1);

addnav("Z?Zurück","marktplatz.php");
addnav("u?Zurück zum Dorf","village.php");

page_footer();


