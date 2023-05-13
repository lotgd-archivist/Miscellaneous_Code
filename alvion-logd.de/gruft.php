
<?php

require_once "common.php";
addcommentary();
checkday();

page_header("Die Gruft");
$session['user']['standort']="Die Gruft";

addnav("Zurück");
addnav("Zurück in die Katakomben","katakomben.php");

output("`b`c`=D`7i`)e `àGr`)u`7f`=t`b`n`n`c");

output("`=Als du nach ein`7em langen und bes`)chwerlichen Weg d`àurch die Gänge der K`)atakomben endlic`7h wieder einmal etw`=as anderes, in Form ein`7er Tür, vor dir siehst al`)s nur Wände, trittst d`àu einfach ein ohne di`)r noch groß Gedank`7en darüber zu machen. `=`n Im ersten Moment emp`7fängt dich tiefste Dunkelh`)eit, sodass selbst die Fack`àeln hinter dir nicht o`)ffenbaren, was sich da vo`7r dir befindet. Erst nach un`=d nach gewöhnst du dich a`7n die Dunkelheit und e`)rkennst wenige Meter v`àor dir einen großen Stein`)block, der beinahe einem Al`7tar gleicht. Riesige Statu`=en stehen zur Linken un`7d zur Rechten als würden sie dar`)auf Acht geben, dass nie`àmand die Ruhe stört, die h`)ier herrscht.`n Als du einige S`7chritte näher trittst, sin`=d sogar unbekannte Schrif`7tzeichen auf dem Stein zu er`)kennen und ein großes kreu`àzähnliches Gebilde ziert d`)ie gesamte obere Fläche de`7s Steinblocks. `n Kno`=chen liegen auf dem Bod`7en und in den Wänden erk`)ennst du Nischen, `àvon wo aus dich sogar ei`)ne Ratte anfunkelt, di`7e gerade aus einem Totens`=chädel klettert.`n`n`n");
viewcommentary("gruft","Hinzufügen",25,"flüstert",1,1);

page_footer();
?>

