
<?php
require_once "common.php";
addcommentary();
page_header('Oase');
$session[user][standort]="Oase";
output('`c`b`PDie Oase von Wolfs Realm.`b`n');
//output("<img src='images/oase.jpg'>`n",true);
output('Nachdem du einen langen Fußmarsch unter der Hitze der Wüstensonne über dich ergehen hast lassen, stehst du nun an einer Oase. Ein strahlend blauer See liegt vor dir, 
umgeben von einem dichten Palmenhain, doch nichts scheint so verwildert wie man es erwarten möchte. Selbst hier haben sich Abenteurer eingefunden, um Erfahrungen 
auszutauschen, sich einen kühlen Trank zu genehmigen, oder ihr Können gegen die Wüstenkrieger unter Beweis zu stellen. Unweit siehst du am Rande des Palmenhains ein kleines 
Haus, willst du es nicht besuchen?`n
Oder zieht es dich vielmehr zu dem kleinen Fluss und seinem Lauf?`n
Vielleicht stellst du dich einer der Herausforderungen oder schiebst eine ruhig Kugel, wie im Urlaub kommst du dir hier allemal vor. `c');
output('`n`n`PMit anderen flüstern:`n');
viewcommentary('Oase','flüstern',5);

addnav('Wüste','kw.php');
addnav('Haus des Wissen','hdw.php');
addnav('Fluss des Nebels','nfluss.php');
addnav('Red Sun','sun.php');

page_footer();
?>

