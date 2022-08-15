
<?php

//Seria zu Ehren ein vermutlich überflüssiger RP-Ort
//Code gemobst von pavillon.php
//Erstellt von Morpheus aka Apollon 
//Mail to: Apollon@magic.ms
//Der zweite Teil des RP-Ortes, da zu unerfahren, um das ganze eleganter zu lösen -.-'

require_once "common.php";

page_header('Das Tobleroneland');

output('`c`b`FDas Tobleroneland`b`c`n');
output('`aIrgendwann näherst du dich endlich dem Tor des Schlosses. Wie von Geisterhand und als ob man dich erwartet hätte, öffnet es sich. Ein weiter Platz
        erstreckt sich vor dir, in dessen Mitte eine unwahrscheinlich riesig große Statue steht, welche eine goldgelbe Farbe hat (ein weiterer Wink mit dem
        Zaunpfahl: Es ist die Farbe der Tobleroneverpackung...) Du liest das Namensschild und mit einem Mal wird dir klar, wer die Herrscherin dieses seltsamen
        Landes ist: `bJud`b.`n
        `n
        Als du weitergehst, entdeckst du, dass der Platz an einen Garten grenzt. Du gehst auf ihn zu, um ihn näher zu erkunden und entdeckst, dass dort
        palmenähnliche Bäume stehen, zwischen welchen überall Hängematten aufgehängt wurden. In der Nähe siehst du einen kleinen See, welcher die tiefbraune
        Farbe von Schokolade hat. Du willst näher treten, doch plötzlich fällt etwas vor dir auf den Boden. Du hebst es auf und siehst: Es ist Toblerone! Dein
        Blick wandert nach oben, um zu sehen, woher es kommt, da fällt dir auf, dass überall an den Bäumen verschiedene Sorten der Süßigkeit hängen. Du willst
        vor Freude am liebsten anfangen zu heulen, doch dann erkennst du, dass sich in deiner Umgebung mehrere, in Goldgelb gekleidete Menschen unterhalten.`n
        `n');
        
echo 'debug: '.mb_convert_encoding('`ÃS`Ã³p`ze`Xk`fu`Ãl`Ã³a`zt`Xi`fu`Ãs `ÃS`Ã³Ã½`zm`Xe`fo`Ãn','ISO-8859-1','utf-8');
echo '<hr>debug: '.mb_convert_encoding('`CW`Ã¢a`eh`Â¤n`Ãw`ki`ut`2z `2F`ua`kr`Ãi`Â¤s','ISO-8859-1','utf-8');

addcommentary();
viewcommentary("tobleroneland_platz","Hier blubbern:`n",20,"blubbert");
addnav("Rumhüpfen");
addnav("G?Zur Grotte","superuser.php");
page_footer();
?>

