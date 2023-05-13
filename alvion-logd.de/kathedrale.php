
<?php

require_once "common.php";
addcommentary();
checkday();

page_header("Schwarze Kathedrale");
$session['user']['standort']="Schwarze Kathedrale";


addnav("In die Katakomben","katakomben.php");
addnav("Zurück");
addnav("Zurück ins dunkle Waldstück","wstueck.php");


output("`c`b`jSc`Shw`)ar`7ze Kath`)ed`Sra`jle`b`c`n");
output("`c<img src='http://www.alvion-logd.de/logd/images/kathedrale.gif'>`c`n",true);
output("`jStill ist `Ses in der `)Kälte der `7Dunkelheit`), die sich`S hier an j`jenem Ort z`Su sammeln `)scheint. S`7äulen und `)Figuren au`Ss pechsch`jwarzem Ste`Sin und grä`)ssliche Ge`7sichter, d`)ie dich au`Ss allen Ec`jken heraus`S anstarren`). Ein lang`7er Weg füh`)rt ins Inn`Sere der Ka`jthedrale i`Sn dessen M`)itte ein g`7roßer Stei`)naltar sta`Snd, verzie`jrt mit all`Serlei okku`)lten Zeich`7en. Selbst`) ein umged`7rehtes Kre`)uz prangt `San der geg`jenüberlieg`Senden Wand`). `n
Du meinst`7 einige Fe`)dern zu er`Skennen, di`je blutig u`Sm den Alta`)r herum ve`7rstreut si`)nd und sog`Sar noch Bl`)utstropfen`7 von der l`)etzten sch`Swarzen Mes`jse, die di`Se dunklen `)Wesen Alvi`7ons hier a`)bhielten. `SBleibt nur`j zu hoffen`S, dass hie`)r auch wir`7klich nur `)Tiere geop`Sfert wurde`jn im Anges`Sicht der T`)eufelsfrat`7zen.");

output("`n`n`@Ein kalter Hauch umfängt dich:`n");
viewcommentary("Kathedrale","Hinzufügen",25,"spricht",1,1);

page_footer();
?>

