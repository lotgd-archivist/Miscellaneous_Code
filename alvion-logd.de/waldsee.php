
<?php

require_once "common.php";
addcommentary();
checkday();

page_header("Waldsee");
$session['user']['standort']="Waldsee";

addnav("Auf die Klippen","klippen.php");
addnav("Zurück");
addnav("Z?Zurück zum Dorf","village.php");

output("`c`b`# Der Waldsee`b`n`n");
output("<img src='./images/waldsee.gif'>`n`n`c",true);
output("`(Stil`,l u`Ynd`& leise haucht ein `Ysa`,nft`(er Wind `,übe`Yr `&die glatte Oberfläche des Sees und versetzt sie so m`Yit`, za`(rten`(Well`,en,`Y di`&e so durch die warm`Yen`, St`(rahlen d`,er `YSo`&nne mit einem wunderschönen Farbenspiel ve`Yrz`,aub`(ern.`(Auf `,den`Y B`&äumen springen die Vög`Yel`, ve`(rgnügt u`,mhe`Yr `&und trällern munter ihre Lieder. Lärchen... `YMe`,ise`(n...`(und `,wel`Ych`&e nicht sonst noch alles. Eine leichte Brise weht durch dein Haar und versetzt`Y a`,uch`( die `(zart`,grü`Yne`&n Wipfel und Äste der `YBä`,ume`( in sanf`,te `YSc`&hwingungen. So umgarnt von der Natur erh`Yeb`,en `(sich `(glei`,ch `Yne`&ben dem See schroff aber doch`Y i`,dyl`(lisch wi`,rk`Yen`&de Klippen in fast glitzerndem`Y G`,rau`( und `(Silb`,ert`Yön`&en, die nur noch mehr zur romantische`Yn `,Sti`(mmung di`,ese`Yr `&Gegend beitragen. Das`Y U`,fer`( läd `(gera`,dez`Yu `&zum picknicken ein, ob nun ganz allein `Yod`,er `(mit sein`,em `Yli`&ebsten Partner um mit ihm `Yge`,mei`(nsam `(das `,Fun`Yke`&ln des glasklaren Wassers zu be`Yob`,ach`(ten.");
output("`n`n`n");
viewcommentary("Waldsee","Hinzufügen",25,"flüstert",1,1);

page_footer();
?>







