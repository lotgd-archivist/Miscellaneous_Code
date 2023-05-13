
<?php

require_once "common.php";
addcommentary();
checkday();

page_header("Die Katakomben");
$session['user']['standort']="Die Katakomben";

addnav("In die Gruft","gruft.php");
addnav("Zurück");
addnav("Zurück in die Kathedrale","kathedrale.php");

output("`b`c`ÓKa`Úta`àko`Úmb`Óen`b`n`n`c");
output("`c<img src='http://www.alvion-logd.de/logd/images/kata.gif'>`c`n`n",true);
output("`ÚDu erreich`Óst eine dun`Úkle Treppe, die zu den `àKatakomben der Kathedra`Úle hinab f`Óührt. `nSie ist zieml`Úich schmal und ke`àin Tageslicht fäll`Út auf die Stufen. Ei`Ón finsterer Ort de`Úr dich sofort wachsam se`àin lässt. Andererse`Úits lädt es Gesch`Óöpfe der Nacht da`Úzu ein, sich hier e`àin zu nisten. Es i`Úst unheimlich un`Ód er Geruch vo`Ún Fäulnis liegt i`àn der Luft. `nEin ri`Úesiges Gewölbe ers`Ótreckt sich vor di`Úr als du über di`àe Treppen hina`Úb in die Tiefe der Kata`Ókomben steigst. Hi`Úer und da erschei`ànt eine Tür, wo e`Ús wahrscheinlich no`Óch tiefer hinein in d`Úie Dunkelheit ge`àht. Nur mit Hi`Úlfe einer Fackel i`Óst es überhaupt mö`Úglich die vielen Nisc`àhen zu sehen die von dem Ge`Úwölbe abgehen. Dump`Ófe Hilferufe verhall`Úen im Nichts als D`àu deinen Weg in e`Úinen Vorraum fortfü`Óhrst. Wieder erschei`Únt eine Tür ob es do`àrt wohl zu der groß`Úen Gruft geht? Nu`Ón... eigentlich war d`Úer Weg bis hier`àher doch düst`Úer genug oder et`Ówa nicht? `n`n`n");
viewcommentary("katakomben","Hinzufügen",25,"flüstert",1,1);

page_footer();
?>

